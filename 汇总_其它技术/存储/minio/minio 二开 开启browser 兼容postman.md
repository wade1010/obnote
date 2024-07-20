routers.go

```javascript
// List of some generic handlers which are applied for all incoming requests.
var globalHandlers = []mux.MiddlewareFunc{
   // filters HTTP headers which are treated as metadata and are reserved
   // for internal use only.
   filterReservedMetadata,
   // Enforce rules specific for TLS requests
   setSSETLSHandler,
   // Auth handler verifies incoming authorization headers and
   // routes them accordingly. Client receives a HTTP error for
   // invalid/unsupported signatures.
   setAuthHandler,
   // Validates all incoming URL resources, for invalid/unsupported
   // resources client receives a HTTP error.
   setIgnoreResourcesHandler,
   // Validates all incoming requests to have a valid date header.
   setTimeValidityHandler,
   // Adds cache control for all browser requests.
   setBrowserCacheControlHandler,
   // Validates if incoming request is for restricted buckets.
   setReservedBucketHandler,
   // Redirect some pre-defined browser request paths to a static location prefix.
   setBrowserRedirectHandler,
   // Adds 'crossdomain.xml' policy handler to serve legacy flash clients.
   setCrossDomainPolicy,
   // Limits all header sizes to a maximum fixed limit
   setRequestHeaderSizeLimitHandler,
   // Limits all requests size to a maximum fixed limit
   setRequestSizeLimitHandler,
   // Network statistics
   setHTTPStatsHandler,
   // Validate all the incoming requests.
   setRequestValidityHandler,
   // Forward path style requests to actual host in a bucket federated setup.
   setBucketForwardingHandler,
   // set HTTP security headers such as Content-Security-Policy.
   addSecurityHeaders,
   // set x-amz-request-id header.
   addCustomHeaders,
   // add redirect handler to redirect
   // requests when object layer is not
   // initialized.
   setRedirectHandler,
   // Add new handlers here.
}
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190004232.jpg)



mux.go

ServeHTTP(){

if r.Match(req, &match) {

   handler = match.Handler

   req = requestWithVars(req, match.Vars)

   req = requestWithRoute(req, match.Route)

}

}











如果是postman传过去heder里面会有Postman-Token这个key



```javascript
if r.Header.Get("Postman-Token") != "" {
   for _, key := range []string{
      "Sec-Ch-Ua-Mobile",
      "Sec-Fetch-Site",
      "Sec-Ch-Ua",
      "Sec-Fetch-Dest",
      "Accept-Encoding",
      "Sec-Fetch-Mode",
      "Connection",
      "Cache-Control",
      "Accept",
      "Accept-Language",
   } {
      r.Header.Del(key)
   }
   r.Header.Set("User-Agent", "MinIO (darwin; amd64) madmin-go/0.0.1 ___go_build_github_com_minio_mc/DEVELOPMENT.GOGET")
}
```





这样在开启browser功能时，不会认为postman请求是browser请求。



这样就兼容了postman代替mc发送请求