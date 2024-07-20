```javascript
// getPoolIdx returns the found previous object and its corresponding pool idx,
// if none are found falls back to most available space pool.
func (z *erasureServerPools) getPoolIdx(ctx context.Context, bucket, object string, size int64) (idx int, err error) {
   idx, err = z.getPoolIdxExisting(ctx, bucket, object)//见后面
   if err != nil && !isErrObjectNotFound(err) {
      return idx, err
   }

   if isErrObjectNotFound(err) {
      idx = z.getAvailablePoolIdx(ctx, bucket, object, size)//见后面
      if idx < 0 {
         return -1, toObjectErr(errDiskFull)
      }
   }

   return idx, nil
}
```





```javascript
// getPoolIdxExisting returns the (first) found object pool index containing an object.
// If the object exists, but the latest version is a delete marker, the index with it is still returned.
// If the object does not exist ObjectNotFound error is returned.
// If any other error is found, it is returned.
// The check is skipped if there is only one zone, and 0, nil is always returned in that case.
func (z *erasureServerPools) getPoolIdxExisting(ctx context.Context, bucket, object string) (idx int, err error) {
   return z.getPoolIdxExistingWithOpts(ctx, bucket, object, ObjectOptions{})//见后面
}
```





```javascript
func (z *erasureServerPools) getPoolIdxExistingWithOpts(ctx context.Context, bucket, object string, opts ObjectOptions) (idx int, err error) {
   if z.SinglePool() {
      return 0, nil
   }

   poolObjInfos := make([]poolObjInfo, len(z.serverPools))

   var wg sync.WaitGroup
   for i, pool := range z.serverPools {
      wg.Add(1)
      go func(i int, pool *erasureSets) {
         defer wg.Done()
         // remember the pool index, we may sort the slice original index might be lost.
         pinfo := poolObjInfo{
            PoolIndex: i,
         }
         pinfo.ObjInfo, pinfo.Err = pool.GetObjectInfo(ctx, bucket, object, opts)
         poolObjInfos[i] = pinfo
      }(i, pool)
   }
   wg.Wait()

   // Sort the objInfos such that we always serve latest
   // this is a defensive change to handle any duplicate
   // content that may have been created, we always serve
   // the latest object.
   sort.Slice(poolObjInfos, func(i, j int) bool {
      mtime1 := poolObjInfos[i].ObjInfo.ModTime
      mtime2 := poolObjInfos[j].ObjInfo.ModTime
      return mtime1.After(mtime2)
   })

   for _, pinfo := range poolObjInfos {
      if pinfo.Err != nil && !isErrObjectNotFound(pinfo.Err) {
         return -1, pinfo.Err
      }
      if isErrObjectNotFound(pinfo.Err) {
         // No object exists or its a delete marker,
         // check objInfo to confirm.
         if pinfo.ObjInfo.DeleteMarker && pinfo.ObjInfo.Name != "" {
            return pinfo.PoolIndex, nil
         }

         // objInfo is not valid, truly the object doesn't
         // exist proceed to next pool.
         continue
      }
      return pinfo.PoolIndex, nil
   }

   return -1, toObjectErr(errFileNotFound, bucket, object)
}
```





```javascript
// getAvailablePoolIdx will return an index that can hold size bytes.
// -1 is returned if no serverPools have available space for the size given.
func (z *erasureServerPools) getAvailablePoolIdx(ctx context.Context, bucket, object string, size int64) int {
   serverPools := z.getServerPoolsAvailableSpace(ctx, bucket, object, size)//见后面
   total := serverPools.TotalAvailable()//见后面
   if total == 0 {
      return -1
   }
   // choose when we reach this many
   choose := rand.Uint64() % total
   atTotal := uint64(0)
   for _, pool := range serverPools {
      atTotal += pool.Available
      if atTotal > choose && pool.Available > 0 {
         return pool.Index
      }
   }
   // Should not happen, but print values just in case.
   logger.LogIf(ctx, fmt.Errorf("reached end of serverPools (total: %v, atTotal: %v, choose: %v)", total, atTotal, choose))
   return -1
}
```





```javascript
// getServerPoolsAvailableSpace will return the available space of each pool after storing the content.
// If there is not enough space the pool will return 0 bytes available.
// Negative sizes are seen as 0 bytes.
func (z *erasureServerPools) getServerPoolsAvailableSpace(ctx context.Context, bucket, object string, size int64) serverPoolsAvailableSpace {
   var serverPools = make(serverPoolsAvailableSpace, len(z.serverPools))

   storageInfos := make([][]*DiskInfo, len(z.serverPools))
   g := errgroup.WithNErrs(len(z.serverPools))
   for index := range z.serverPools {
      index := index
      g.Go(func() error {
         // Get the set where it would be placed.
         storageInfos[index] = getDiskInfos(ctx, z.serverPools[index].getHashedSet(object).getDisks())
         return nil
      }, index)
   }

   // Wait for the go routines.
   g.Wait()

   for i, zinfo := range storageInfos {
      var available uint64
      if !isMinioMetaBucketName(bucket) && !hasSpaceFor(zinfo, size) {
         serverPools[i] = poolAvailableSpace{Index: i}
         continue
      }
      for _, disk := range zinfo {
         if disk == nil {
            continue
         }
         available += disk.Total - disk.Used
      }
      serverPools[i] = poolAvailableSpace{
         Index:     i,
         Available: available,
      }
   }
   return serverPools
}
```



```javascript
// TotalAvailable - total available space
func (p serverPoolsAvailableSpace) TotalAvailable() uint64 {
   total := uint64(0)
   for _, z := range p {
      total += z.Available
   }
   return total
}
```

