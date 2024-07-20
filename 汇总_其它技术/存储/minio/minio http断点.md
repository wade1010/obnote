internal/http/server.go

```javascript
func (srv *Server) Start() (err error) {
   // Take a copy of server fields.
       。。。。。。。。。
   // Wrap given handler to do additional
   // * return 503 (service unavailable) if the server in shutdown.
   wrappedHandler := http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
          。。。。。。。。。
      // Handle request using passed handler.
      handler.ServeHTTP(w, r)//这里断点
   })
   。。。。。。
    return srv.Server.Serve(listener)
}
```



/Users/xxx/workspace/goworkspace/pkg/mod/github.com/gorilla/mux@v1.8.0/mux.go

```javascript
if r.Match(req, &match) {
   handler = match.Handler
   req = requestWithVars(req, match.Vars)
   req = requestWithRoute(req, match.Route)
}
```



这里通过路由匹配到api-router.go里面的handler





minio skd putobject之前会发一个请求到服务端，确认bucket是否存在，存在则继续发送一个putobject请求





Always scan flattenLevels deep. Cache root is level 0







fi, fiErr := os.Stat(item.Path)



	dataScannerSleepPerFolder     = time.Millisecond                 // Time to wait between folders.

	dataUsageUpdateDirCycles      = 16                               // Visit all folders every n cycles.

	dataScannerCompactLeastObject = 500                              // Compact when there is less than this many objects in a branch.

	dataScannerCompactAtChildren  = 10000                            // Compact when there are this many children in a branch.

	dataScannerCompactAtFolders   = dataScannerCompactAtChildren / 4 // Compact when this many subfolders in a single folder.

	dataScannerStartDelay         = 1 * time.Minute                  // Time to wait on startup and between cycles.







