

```javascript
func newObjectLayer(ctx context.Context, endpointServerPools EndpointServerPools) (newObject ObjectLayer, err error) {
   // For FS only, directly use the disk.
   if endpointServerPools.NEndpoints() == 1 {
      // Initialize new FS object layer.
      return NewFSObjectLayer(endpointServerPools[0].Endpoints[0].Path)
   }

   return newErasureServerPools(ctx, endpointServerPools)
}
```





```javascript
if err = initMetaVolumeFS(fsPath, fsUUID); err != nil {
   return nil, err
}

// Initialize `format.json`, this function also returns.
rlk, err := initFormatFS(ctx, fsPath)
```

