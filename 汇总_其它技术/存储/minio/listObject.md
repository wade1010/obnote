

```javascript

func listObjectsFsV2(ctx context.Context, obj ObjectLayer, bucket, prefix, marker, delimiter string, maxKeys int, tpool *TreeWalkPool, listDir ListDirFunc, isLeaf IsLeafFunc, isLeafDir IsLeafDirFunc, getObjInfo func(context.Context, string, string) (ObjectInfo, error), getObjectInfoDirs ...func(context.Context, string, string) (ObjectInfo, error)) (loi ListObjectsInfo, err error) {
   if delimiter != SlashSeparator && delimiter != "" {
      return listObjectsNonSlashFsV2(ctx, bucket, prefix, marker, delimiter, maxKeys, tpool, listDir, isLeaf, isLeafDir, getObjInfo, getObjectInfoDirs...)
   }

   if err := checkListObjsArgs(ctx, bucket, prefix, marker, obj); err != nil {
      return loi, err
   }

   // Marker is set validate pre-condition.
   if marker != "" {
      // Marker not common with prefix is not implemented. Send an empty response
      if !HasPrefix(marker, prefix) {
         return loi, nil
      }
   }

   // With max keys of zero we have reached eof, return right here.
   if maxKeys == 0 {
      return loi, nil
   }

   // For delimiter and prefix as '/' we do not list anything at all
   // since according to s3 spec we stop at the 'delimiter'
   // along // with the prefix. On a flat namespace with 'prefix'
   // as '/' we don't have any entries, since all the keys are
   // of form 'keyName/...'
   if delimiter == SlashSeparator && prefix == SlashSeparator {
      return loi, nil
   }

   // Over flowing count - reset to maxObjectList.
   if maxKeys < 0 || maxKeys > maxObjectList {
      maxKeys = maxObjectList
   }

   // Default is recursive, if delimiter is set then list non recursive.
   recursive := true
   if delimiter == SlashSeparator {
      recursive = false
   }

   walkResultCh, endWalkCh := tpool.Release(listParams{bucket, recursive, marker, prefix})
   if walkResultCh == nil {
      endWalkCh = make(chan struct{})
      walkResultCh = startTreeWalk(ctx, bucket, prefix, marker, recursive, listDir, isLeaf, isLeafDir, endWalkCh)
   }

   var eof bool
   var nextMarker string

   // List until maxKeys requested.
   g := errgroup.WithNErrs(maxKeys).WithConcurrency(10)
   ctx, cancel := g.WithCancelOnError(ctx)
   defer cancel()

   objInfoFound := make([]*ObjectInfo, maxKeys)
   var i int
   for i = 0; i < maxKeys; i++ {
      i := i
      walkResult, ok := <-walkResultCh
      if !ok {
         // Closed channel.
         eof = true
         break
      }

      if HasSuffix(walkResult.entry, SlashSeparator) {
         g.Go(func() error {
            for _, getObjectInfoDir := range getObjectInfoDirs {
               objInfo, err := getObjectInfoDir(ctx, bucket, walkResult.entry)
               if err == nil {
                  objInfoFound[i] = &objInfo
                  // Done...
                  return nil
               }

               // Add temp, may be overridden,
               if err == errFileNotFound {
                  objInfoFound[i] = &ObjectInfo{
                     Bucket: bucket,
                     Name:   walkResult.entry,
                     IsDir:  true,
                  }
                  continue
               }
               return toObjectErr(err, bucket, prefix)
            }
            return nil
         }, i)
      } else {
         g.Go(func() error {
            objInfo, err := getObjInfo(ctx, bucket, walkResult.entry)
            if err != nil {
               // Ignore errFileNotFound as the object might have got
               // deleted in the interim period of listing and getObjectInfo(),
               // ignore quorum error as it might be an entry from an outdated disk.
               if IsErrIgnored(err, []error{
                  errFileNotFound,
                  errErasureReadQuorum,
               }...) {
                  return nil
               }
               return toObjectErr(err, bucket, prefix)
            }
            objInfoFound[i] = &objInfo
            return nil
         }, i)
      }

      if walkResult.end {
         eof = true
         break
      }
   }
   if err := g.WaitErr(); err != nil {
      return loi, err
   }
   // Copy found objects
   objInfos := make([]ObjectInfo, 0, i+1)
   for _, objInfo := range objInfoFound {
      if objInfo == nil {
         continue
      }
      objInfos = append(objInfos, *objInfo)
      nextMarker = objInfo.Name
   }

   // Save list routine for the next marker if we haven't reached EOF.
   params := listParams{bucket, recursive, nextMarker, prefix}
   if !eof {
      tpool.Set(params, walkResultCh, endWalkCh)
   }

   result := ListObjectsInfo{}
   for _, objInfo := range objInfos {
      if objInfo.IsDir && delimiter == SlashSeparator {
         result.Prefixes = append(result.Prefixes, objInfo.Name)
         continue
      }
      result.Objects = append(result.Objects, objInfo)
   }

   if !eof {
      result.IsTruncated = true
      if len(objInfos) > 0 {
         result.NextMarker = objInfos[len(objInfos)-1].Name
      }
   }

   // Success.
   return result, nil
}

func listObjectsNonSlashFsV2(ctx context.Context, bucket, prefix, marker, delimiter string, maxKeys int, tpool *TreeWalkPool, listDir ListDirFunc, isLeaf IsLeafFunc, isLeafDir IsLeafDirFunc, getObjInfo func(context.Context, string, string) (ObjectInfo, error), getObjectInfoDirs ...func(context.Context, string, string) (ObjectInfo, error)) (loi ListObjectsInfo, err error) {
   endWalkCh := make(chan struct{})
   defer close(endWalkCh)
   recursive := true
   walkResultCh := startTreeWalk(ctx, bucket, prefix, "", recursive, listDir, isLeaf, isLeafDir, endWalkCh)

   var objInfos []ObjectInfo
   var eof bool
   var prevPrefix string

   for {
      if len(objInfos) == maxKeys {
         break
      }
      result, ok := <-walkResultCh
      if !ok {
         eof = true
         break
      }

      var objInfo ObjectInfo
      var err error

      index := strings.Index(strings.TrimPrefix(result.entry, prefix), delimiter)
      if index == -1 {
         objInfo, err = getObjInfo(ctx, bucket, result.entry)
         if err != nil {
            // Ignore errFileNotFound as the object might have got
            // deleted in the interim period of listing and getObjectInfo(),
            // ignore quorum error as it might be an entry from an outdated disk.
            if IsErrIgnored(err, []error{
               errFileNotFound,
               errErasureReadQuorum,
            }...) {
               continue
            }
            return loi, toObjectErr(err, bucket, prefix)
         }
      } else {
         index = len(prefix) + index + len(delimiter)
         currPrefix := result.entry[:index]
         if currPrefix == prevPrefix {
            continue
         }
         prevPrefix = currPrefix

         objInfo = ObjectInfo{
            Bucket: bucket,
            Name:   currPrefix,
            IsDir:  true,
         }
      }

      if objInfo.Name <= marker {
         continue
      }

      objInfos = append(objInfos, objInfo)
      if result.end {
         eof = true
         break
      }
   }

   result := ListObjectsInfo{}
   for _, objInfo := range objInfos {
      if objInfo.IsDir {
         result.Prefixes = append(result.Prefixes, objInfo.Name)
         continue
      }
      result.Objects = append(result.Objects, objInfo)
   }

   if !eof {
      result.IsTruncated = true
      if len(objInfos) > 0 {
         result.NextMarker = objInfos[len(objInfos)-1].Name
      }
   }

   return result, nil
}
```





```javascript

// Initiate a new treeWalk in a goroutine.
func startTreeWalk(ctx context.Context, bucket, prefix, marker string, recursive bool, listDir ListDirFunc, isLeaf IsLeafFunc, isLeafDir IsLeafDirFunc, endWalkCh <-chan struct{}) chan TreeWalkResult {
   // Example 1
   // If prefix is "one/two/three/" and marker is "one/two/three/four/five.txt"
   // treeWalk is called with prefixDir="one/two/three/" and marker="four/five.txt"
   // and entryPrefixMatch=""

   // Example 2
   // if prefix is "one/two/th" and marker is "one/two/three/four/five.txt"
   // treeWalk is called with prefixDir="one/two/" and marker="three/four/five.txt"
   // and entryPrefixMatch="th"

   resultCh := make(chan TreeWalkResult, maxObjectList)
   entryPrefixMatch := prefix
   prefixDir := ""
   lastIndex := strings.LastIndex(prefix, SlashSeparator)
   if lastIndex != -1 {
      entryPrefixMatch = prefix[lastIndex+1:]
      prefixDir = prefix[:lastIndex+1]
   }
   marker = strings.TrimPrefix(marker, prefixDir)
   go func() {
      isEnd := true // Indication to start walking the tree with end as true.
      doTreeWalk(ctx, bucket, prefixDir, entryPrefixMatch, marker, recursive, listDir, isLeaf, isLeafDir, resultCh, endWalkCh, isEnd)
      close(resultCh)
   }()
   return resultCh
}
```





```javascript

// treeWalk walks directory tree recursively pushing TreeWalkResult into the channel as and when it encounters files.
func doTreeWalk(ctx context.Context, bucket, prefixDir, entryPrefixMatch, marker string, recursive bool, listDir ListDirFunc, isLeaf IsLeafFunc, isLeafDir IsLeafDirFunc, resultCh chan TreeWalkResult, endWalkCh <-chan struct{}, isEnd bool) (emptyDir bool, treeErr error) {
   // Example:
   // if prefixDir="one/two/three/" and marker="four/five.txt" treeWalk is recursively
   // called with prefixDir="one/two/three/four/" and marker="five.txt"

   var markerBase, markerDir string
   if marker != "" {
      // Ex: if marker="four/five.txt", markerDir="four/" markerBase="five.txt"
      markerSplit := strings.SplitN(marker, SlashSeparator, 2)
      markerDir = markerSplit[0]
      if len(markerSplit) == 2 {
         markerDir += SlashSeparator
         markerBase = markerSplit[1]
      }
   }

   emptyDir, entries, delayIsLeaf := listDir(bucket, prefixDir, entryPrefixMatch)
   // When isleaf check is delayed, make sure that it is set correctly here.
   if delayIsLeaf && isLeaf == nil || isLeafDir == nil {
      return false, errInvalidArgument
   }

   // For an empty list return right here.
   if emptyDir {
      return true, nil
   }

   // example:
   // If markerDir="four/" Search() returns the index of "four/" in the sorted
   // entries list so we skip all the entries till "four/"
   idx := sort.Search(len(entries), func(i int) bool {
      return entries[i] >= markerDir
   })
   entries = entries[idx:]
   // For an empty list after search through the entries, return right here.
   if len(entries) == 0 {
      return false, nil
   }

   for i, entry := range entries {
      var leaf, leafDir bool

      // Decision to do isLeaf check was pushed from listDir() to here.
      if delayIsLeaf {
         leaf = isLeaf(bucket, pathJoin(prefixDir, entry))
         if leaf {
            entry = strings.TrimSuffix(entry, slashSeparator)
         }
      } else {
         leaf = !HasSuffix(entry, slashSeparator)
      }

      if HasSuffix(entry, slashSeparator) {
         leafDir = isLeafDir(bucket, pathJoin(prefixDir, entry))
      }

      isDir := !leafDir && !leaf

      if i == 0 && markerDir == entry {
         if !recursive {
            // Skip as the marker would already be listed in the previous listing.
            continue
         }
         if recursive && !isDir {
            // We should not skip for recursive listing and if markerDir is a directory
            // for ex. if marker is "four/five.txt" markerDir will be "four/" which
            // should not be skipped, instead it will need to be treeWalk()'ed into.

            // Skip if it is a file though as it would be listed in previous listing.
            continue
         }
      }
      if recursive && isDir {
         // If the entry is a directory, we will need recurse into it.
         markerArg := ""
         if entry == markerDir {
            // We need to pass "five.txt" as marker only if we are
            // recursing into "four/"
            markerArg = markerBase
         }
         prefixMatch := "" // Valid only for first level treeWalk and empty for subdirectories.
         // markIsEnd is passed to this entry's treeWalk() so that treeWalker.end can be marked
         // true at the end of the treeWalk stream.
         markIsEnd := i == len(entries)-1 && isEnd
         emptyDir, err := doTreeWalk(ctx, bucket, pathJoin(prefixDir, entry), prefixMatch, markerArg, recursive,
            listDir, isLeaf, isLeafDir, resultCh, endWalkCh, markIsEnd)
         if err != nil {
            return false, err
         }

         // A nil totalFound means this is an empty directory that
         // needs to be sent to the result channel, otherwise continue
         // to the next entry.
         if !emptyDir {
            continue
         }
      }

      // EOF is set if we are at last entry and the caller indicated we at the end.
      isEOF := ((i == len(entries)-1) && isEnd)
      select {
      case <-endWalkCh:
         return false, errWalkAbort
      case resultCh <- TreeWalkResult{entry: pathJoin(prefixDir, entry), isEmptyDir: leafDir, end: isEOF}:
      }
   }

   // Everything is listed.
   return false, nil
}
```





```javascript

func filterListEntries(bucket, prefixDir string, entries []string, prefixEntry string, isLeaf IsLeafFunc) ([]string, bool) {
   // Filter entries that have the prefix prefixEntry.
   entries = filterMatchingPrefix(entries, prefixEntry)

   // Listing needs to be sorted.
   sort.Slice(entries, func(i, j int) bool {
      if !HasSuffix(entries[i], globalDirSuffixWithSlash) && !HasSuffix(entries[j], globalDirSuffixWithSlash) {
         return entries[i] < entries[j]
      }
      first := entries[i]
      second := entries[j]
      if HasSuffix(first, globalDirSuffixWithSlash) {
         first = strings.TrimSuffix(first, globalDirSuffixWithSlash) + slashSeparator
      }
      if HasSuffix(second, globalDirSuffixWithSlash) {
         second = strings.TrimSuffix(second, globalDirSuffixWithSlash) + slashSeparator
      }
      return first < second
   })

   // Can isLeaf() check be delayed till when it has to be sent down the
   // TreeWalkResult channel?
   delayIsLeaf := delayIsLeafCheck(entries)
   if delayIsLeaf {
      return entries, true
   }

   // isLeaf() check has to happen here so that trailing "/" for objects can be removed.
   for i, entry := range entries {
      if isLeaf(bucket, pathJoin(prefixDir, entry)) {
         entries[i] = strings.TrimSuffix(entry, slashSeparator)
      }
   }

   // Sort again after removing trailing "/" for objects as the previous sort
   // does not hold good anymore.
   sort.Slice(entries, func(i, j int) bool {
      if !HasSuffix(entries[i], globalDirSuffix) && !HasSuffix(entries[j], globalDirSuffix) {
         return entries[i] < entries[j]
      }
      first := entries[i]
      second := entries[j]
      if HasSuffix(first, globalDirSuffix) {
         first = strings.TrimSuffix(first, globalDirSuffix) + slashSeparator
      }
      if HasSuffix(second, globalDirSuffix) {
         second = strings.TrimSuffix(second, globalDirSuffix) + slashSeparator
      }
      if first == second {
         return HasSuffix(entries[i], globalDirSuffix)
      }
      return first < second
   })
   return entries, false
}
```

