以http://127.0.0.1:9001/api/v1/buckets为例



/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/console@v0.7.5-0.20210618230329-b10c4f51b1ef/restapi/user_buckets.go



```javascript
api.UserAPIListBucketsHandler = user_api.ListBucketsHandlerFunc(func(params user_api.ListBucketsParams, session *models.Principal) middleware.Responder {
   listBucketsResponse, err := getListBucketsResponse(session)//断点在这里即可
   if err != nil {
      return user_api.NewListBucketsDefault(int(err.Code)).WithPayload(err)
   }
   return user_api.NewListBucketsOK().WithPayload(listBucketsResponse)
})
```

