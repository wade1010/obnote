

```javascript
bucket := aws.String("bobtest211137030")
key := aws.String("123231.txt")

//Configure to use MinIO Server
s3Config := &aws.Config{
   Credentials:      credentials.NewStaticCredentials("minioadmin", "minioadmin", ""),
   Endpoint:         aws.String("127.0.0.1:9000"),
   Region:           aws.String("us-east-1"),
   DisableSSL:       aws.Bool(true),
   S3ForcePathStyle: aws.Bool(true),
}
//s3Config := &aws.Config{
// Credentials:      credentials.NewStaticCredentials("xxxx", "xxx", ""),
// Endpoint:         aws.String("s3.amazonaws.com"),
// Region:           aws.String("us-east-1"),
// DisableSSL:       aws.Bool(false),
// S3ForcePathStyle: aws.Bool(true),
//}
newSession, _ := session.NewSession(s3Config)

s3Client := s3.New(newSession)

//_, err := s3Client.CreateBucket(&s3.CreateBucketInput{
// Bucket: bucket,
//})
//
//s3Client.PutBucketVersioning(&s3.PutBucketVersioningInput{
// Bucket: bucket,
// VersioningConfiguration: &s3.VersioningConfiguration{
//    MFADelete: aws.String("Disabled"),
//    Status:    aws.String("Enabled"),
// },
//})
//
//for i := 0; i < 1; i++ {
// s3Client.PutObject(&s3.PutObjectInput{
//    Body:   aws.ReadSeekCloser(strings.NewReader("filetoupload")),
//    Bucket: bucket,
//    Key:    aws.String("123231.txt"),
// })
//}
//for i := 0; i < 1; i++ {
// s3Client.PutObject(&s3.PutObjectInput{
//    Body:   aws.ReadSeekCloser(strings.NewReader("filetoupload")),
//    Bucket: bucket,
//    Key:    aws.String("123/231.txt"),
// })
//}
//for i := 0; i < 1; i++ {
// s3Client.PutObject(&s3.PutObjectInput{
//    Body:   aws.ReadSeekCloser(strings.NewReader("filetoupload")),
//    Bucket: bucket,
//    Key:    aws.String("12/32/31.txt"),
// })
//}
//
//for i := 0; i < 1; i++ {
// s3Client.PutObject(&s3.PutObjectInput{
//    Body:   aws.ReadSeekCloser(strings.NewReader("filetoupload")),
//    Bucket: bucket,
//    Key:    aws.String("123232.txt"),
// })
//}

input := &s3.ListObjectVersionsInput{
   Bucket:          bucket,
   Delimiter:       aws.String("/"),
   Prefix:          aws.String("12"),
   MaxKeys:         aws.Int64(1000),
   //KeyMarker:       aws.String("123231.txt"),
   //VersionIdMarker: aws.String("6688365/b897b5e0-de70-412f-b489-6c1b3acfecb6"),
   //VersionIdMarker: aws.String("648323a2-f3f9-4848-a501-48091776c55c"),
}
result, err := s3Client.ListObjectVersions(input)
fmt.Println(result)
return
```





minio

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006747.jpg)

aws

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006918.jpg)





```javascript
input := &s3.ListObjectVersionsInput{
   Bucket:    bucket,
   Delimiter: aws.String("1"),
   Prefix:    aws.String("12"),
   MaxKeys:   aws.Int64(1000),
   //KeyMarker:       aws.String("123231.txt"),
   //VersionIdMarker: aws.String("6688365/b897b5e0-de70-412f-b489-6c1b3acfecb6"),
   //VersionIdMarker: aws.String("648323a2-f3f9-4848-a501-48091776c55c"),
}
results2, err := s3Client2.ListObjectVersions(input)//aws
result, err := s3Client.ListObjectVersions(input)//minio
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006053.jpg)





```javascript
// ToObjectInfo - Converts metadata to object info.
func (fi FileInfo) ToObjectInfo(bucket, object string) ObjectInfo {
   object = decodeDirObject(object)
   versionID := fi.VersionID
   if (globalBucketVersioningSys.Enabled(bucket) || globalBucketVersioningSys.Suspended(bucket)) && versionID == "" {
      versionID = nullVersionID
   }

   objInfo := ObjectInfo{
      IsDir:            HasSuffix(object, SlashSeparator),
      Bucket:           bucket,
      Name:             object,
      VersionID:        versionID,
      IsLatest:         fi.IsLatest,
      DeleteMarker:     fi.Deleted,
      Size:             fi.Size,
      ModTime:          fi.ModTime,
      Legacy:           fi.XLV1,
      ContentType:      fi.Metadata["content-type"],
      ContentEncoding:  fi.Metadata["content-encoding"],
      NumVersions:      fi.NumVersions,
      SuccessorModTime: fi.SuccessorModTime,
   }

   // Update expires
   var (
      t time.Time
      e error
   )
   if exp, ok := fi.Metadata["expires"]; ok {
      if t, e = time.Parse(http.TimeFormat, exp); e == nil {
         objInfo.Expires = t.UTC()
      }
   }
   objInfo.backendType = BackendErasure

   // Extract etag from metadata.
   objInfo.ETag = extractETag(fi.Metadata)

   // Verify if Etag is parseable, if yes
   // then check if its multipart etag.
   et, e := etag.Parse(objInfo.ETag)
   if e == nil {
      objInfo.Multipart = et.IsMultipart()
   }

   // Add user tags to the object info
   tags := fi.Metadata[xhttp.AmzObjectTagging]
   if len(tags) != 0 {
      objInfo.UserTags = tags
   }

   // Add replication status to the object info
   objInfo.ReplicationStatus = replication.StatusType(fi.Metadata[xhttp.AmzBucketReplicationStatus])
   if fi.Deleted {
      objInfo.ReplicationStatus = replication.StatusType(fi.DeleteMarkerReplicationStatus)
   }

   objInfo.TransitionedObject = TransitionedObject{
      Name:        fi.TransitionedObjName,
      VersionID:   fi.TransitionVersionID,
      Status:      fi.TransitionStatus,
      FreeVersion: fi.TierFreeVersion(),
      Tier:        fi.TransitionTier,
   }

   // etag/md5Sum has already been extracted. We need to
   // remove to avoid it from appearing as part of
   // response headers. e.g, X-Minio-* or X-Amz-*.
   // Tags have also been extracted, we remove that as well.
   objInfo.UserDefined = cleanMetadata(fi.Metadata)

   // Set multipart for encryption properly if
   // not set already.
   if !objInfo.Multipart {
      if _, ok := crypto.IsEncrypted(objInfo.UserDefined); ok {
         objInfo.Multipart = crypto.IsMultiPart(objInfo.UserDefined)
      }
   }

   // All the parts per object.
   objInfo.Parts = fi.Parts

   // Update storage class
   if sc, ok := fi.Metadata[xhttp.AmzStorageClass]; ok {
      objInfo.StorageClass = sc
   } else {
      objInfo.StorageClass = globalMinioDefaultStorageClass
   }

   objInfo.VersionPurgeStatus = fi.VersionPurgeStatus
   // set restore status for transitioned object
   restoreHdr, ok := fi.Metadata[xhttp.AmzRestore]
   if ok {
      if restoreStatus, err := parseRestoreObjStatus(restoreHdr); err == nil {
         objInfo.RestoreOngoing = restoreStatus.Ongoing()
         objInfo.RestoreExpires, _ = restoreStatus.Expiry()
      }
   }
   // Success.
   return objInfo
}
```

