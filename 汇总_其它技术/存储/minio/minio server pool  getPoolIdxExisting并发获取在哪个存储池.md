

```javascript
// getPoolIdxExisting returns the (first) found object pool index containing an object.
// If the object exists, but the latest version is a delete marker, the index with it is still returned.
// If the object does not exist ObjectNotFound error is returned.
// If any other error is found, it is returned.
// The check is skipped if there is only one zone, and 0, nil is always returned in that case.
func (z *erasureServerPools) getPoolIdxExisting(ctx context.Context, bucket, object string) (idx int, err error) {
   return z.getPoolIdxExistingWithOpts(ctx, bucket, object, ObjectOptions{})
}
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

