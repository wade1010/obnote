

```javascript
versioned := globalBucketVersioningSys.Enabled(bucket)
versionSuspended := globalBucketVersioningSys.Suspended(bucket)
vid := strings.TrimSpace(r.Form.Get("versionId"))
if vid != "" && vid != nullVersionID {
   _, err := uuid.Parse(vid)
   if err != nil {
      logger.LogIf(ctx, err)
      return opts, InvalidVersionID{
         Bucket:    bucket,
         Object:    object,
         VersionID: vid,
      }
   }
   if !versioned {
      return opts, InvalidArgument{
         Bucket: bucket,
         Object: object,
         Err:    fmt.Errorf("VersionID specified %s, but versioning not enabled on  %s", opts.VersionID, bucket),
      }
   }
}
```

