插入数据

```javascript
func testListObjects() {
   // initialize logging params
   startTime := time.Now()
   testName := getFuncName()
   function := "ListObjects(bucketName, objectPrefix, recursive, doneCh)"
   args := map[string]interface{}{
      "bucketName":   "",
      "objectPrefix": "",
      "recursive":    "true",
   }
   // Seed random based on current time.
   rand.Seed(time.Now().Unix())

   // Instantiate new minio client object.
   c, err := minio.New(os.Getenv(serverEndpoint),
      &minio.Options{
         Creds:  credentials.NewStaticV4(os.Getenv(accessKey), os.Getenv(secretKey), ""),
         Secure: mustParseBool(os.Getenv(enableHTTPS)),
      })
   if err != nil {
      logError(testName, function, args, startTime, "", "MinIO client v4 object creation failed", err)
      return
   }

   // Enable tracing, write to stderr.
   // c.TraceOn(os.Stderr)

   // Set user agent.
   c.SetAppInfo("MinIO-go-FunctionalTest", "0.1.0")

   // Generate a new random bucket name.
   bucketName := "bk1"
   args["bucketName"] = bucketName

   // Make a new bucket.


   testObjects := []struct {
      name         string
      storageClass string
   }{
      // Special characters
      {"foo bar", "STANDARD"},
      {"foo-%", "STANDARD"},
      {"/dir1/foo1", "STANDARD"},
      {"/dir1/foo2", "STANDARD"},
      {"/dir2/foo1", "STANDARD"},
      {"/dir2/foo2", "STANDARD"},
      {"/dir2/foo3", "STANDARD"},
      {"random-object-1", "STANDARD"},
      {"random-object-2", "REDUCED_REDUNDANCY"},
   }

   for i, object := range testObjects {
      bufSize := dataFileMap["datafile-33-kB"]
      var reader = getDataReader("datafile-33-kB")
      defer reader.Close()
      _, err = c.PutObject(context.Background(), bucketName, object.name, reader, int64(bufSize),
         minio.PutObjectOptions{ContentType: "binary/octet-stream", StorageClass: object.storageClass})
      if err != nil {
         logError(testName, function, args, startTime, "", fmt.Sprintf("PutObject %d call failed", i+1), err)
         return
      }
   }
   successLogger(testName, function, args, startTime).Info()
}
```

