

```javascript
func main() {
   bucket := aws.String("bobtest2111371111112")
   //bucket := aws.String("bobtest211137111112")
   key := aws.String("123231.txt")

   //Configure to use MinIO Server
   s3Config := &aws.Config{
      Credentials:      credentials.NewStaticCredentials("minioadmin", "minioadmin", ""),
      Endpoint:         aws.String("127.0.0.1:9000"),
      Region:           aws.String("us-east-1"),
      DisableSSL:       aws.Bool(true),
      S3ForcePathStyle: aws.Bool(true),
   }
   s3Config2 := &aws.Config{
      Credentials:      credentials.NewStaticCredentials("xxx", "xxx", ""),
      Endpoint:         aws.String("s3.amazonaws.com"),
      Region:           aws.String("us-east-1"),
      DisableSSL:       aws.Bool(false),
      S3ForcePathStyle: aws.Bool(true),
   }
   newSession, _ := session.NewSession(s3Config)
   newSession2, _ := session.NewSession(s3Config2)

   s3Client := s3.New(newSession)
   s3Client2 := s3.New(newSession2)

   createBucket, err4 := s3Client.CreateBucket(&s3.CreateBucketInput{
      Bucket: bucket,
   })
   fmt.Println(createBucket, err4)

   versioning, err3 := s3Client.PutBucketVersioning(&s3.PutBucketVersioningInput{
      Bucket: bucket,
      VersioningConfiguration: &s3.VersioningConfiguration{
         MFADelete: aws.String("Disabled"),
         Status:    aws.String("Enabled"),
      },
   })
   fmt.Println(versioning, err3)

   bucketVersioning, err3 := s3Client.GetBucketVersioning(&s3.GetBucketVersioningInput{
      Bucket: bucket,
   })
   fmt.Println(bucketVersioning, err3)
   meta := make(map[string]*string)
   meta["name"] = aws.String("bob")
   meta["desc"] = aws.String("desc")
   upload, err2 := s3Client.CreateMultipartUpload(&s3.CreateMultipartUploadInput{
      Bucket:   bucket,
      Key:      aws.String("largeobject"),
      Metadata: meta,
   })
   fmt.Println(upload, err2)

   part2, err22 := s3Client.UploadPart(&s3.UploadPartInput{
      Body:       aws.ReadSeekCloser(strings.NewReader("file221212321ToUpload")),
      Bucket:     bucket,
      Key:        aws.String("largeobject"),
      PartNumber: aws.Int64(1),
      UploadId:   upload.UploadId,
   })
   fmt.Println(part2, err22)

   part23, err223 := s3Client.UploadPart(&s3.UploadPartInput{
      Body:       aws.ReadSeekCloser(strings.NewReader("32131231321312")),
      Bucket:     bucket,
      Key:        aws.String("largeobject"),
      PartNumber: aws.Int64(2),
      UploadId:   upload.UploadId,
   })
   fmt.Println(part23, err223)
   uploads, err4 := s3Client.ListMultipartUploads(&s3.ListMultipartUploadsInput{
      Bucket: bucket,
      //Prefix: aws.String("largeobject"),
   })
   fmt.Println(uploads, err4)
}
```



不传prefix时 minIO和AWS结果不同

minion结果是空.

AWS是能列出结果,且Uploads这个数组内部是按object name 排序的，同object name的按创建时间升序排列



minio如下：

f5640917-590f-455d-bcc6-063103a607dc

![](https://gitee.com/hxc8/images6/raw/master/img/202407190005471.jpg)



AWS如下： bucket=bobtest211137111231123

5BXiuptv7NAV6t4hmKfL7Sdepwxs0LDFmCme3tWqxJFiMdH8hMbNUK70UyfsD2IUiDSF9H_GogTPEW82RZIHEw--

![](https://gitee.com/hxc8/images6/raw/master/img/202407190005580.jpg)





如果传prefix=aws.String("largeobject"),

minion、AWS都能列出结果



minio如下：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190005875.jpg)

AWS如下：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190005969.jpg)











![](https://gitee.com/hxc8/images6/raw/master/img/202407190005990.jpg)

