// listPath will return the requested entries.

// If no more entries are in the listing io.EOF is returned,

// otherwise nil or an unexpected error is returned.

// The listPathOptions given will be checked and modified internally.

// Required important fields are Bucket, Prefix, Separator.

// Other important fields are Limit, Marker.

// List ID always derived from the Marker.



xl-storage-format-utils.go

```javascript
func getFileInfo(xlMetaBuf []byte, volume, path, versionID string) (FileInfo, error) {
   if isXL2V1Format(xlMetaBuf) {
      var xlMeta xlMetaV2
      if err := xlMeta.Load(xlMetaBuf); err != nil {
         return FileInfo{}, err
      }
      return xlMeta.ToFileInfo(volume, path, versionID)
   }

   xlMeta := &xlMetaV1Object{}
   var json = jsoniter.ConfigCompatibleWithStandardLibrary
   if err := json.Unmarshal(xlMetaBuf, xlMeta); err != nil {
      return FileInfo{}, errFileCorrupt
   }
   fi, err := xlMeta.ToFileInfo(volume, path)
   if err == errFileNotFound && versionID != "" {
      return fi, errFileVersionNotFound
   }
   fi.XLV1 = true // indicates older version
   return fi, err
}
```

