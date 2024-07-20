

```javascript
func (bucket SynchronizedBucket) Get(context Context, path string) ([]byte, error) {
   callback := make(chan interface{})
   switch response := <-callback; response.(type) {
   case []byte:
      return response.([]byte), nil
   case error:
      return nil, response.(error)
   default:
      return nil, errors.New("Unexpected error, service failed")
   }
}
```





```javascript
type GatewayConfig struct {
   StorageDriver StorageDriver
}

// Storage driver function, should read from a channel and respond through callback channels
type StorageDriver func(bucket string, input chan ObjectRequest)

config := GatewayConfig{StorageDriver: InMemoryStorageDriver}
。。。。。
config.StorageDriver(request.name, bucketChannel)
```



```javascript
// Get Listen URL
func (s *Server) GetlistenURL() string {
   scheme := "http"
   if s.enableTLS {
      scheme = "https"
   }
   if s.listener != nil {
      if taddr, ok := s.listener.Addr().(*net.TCPAddr); ok {
         if taddr.IP.IsUnspecified() {
            return fmt.Sprintf("%s://localhost:%d", scheme, taddr.Port)
         }
         return fmt.Sprintf("%s://%s", scheme, s.listener.Addr())
      }
   }
   return ""
}
```



```javascript
statusChans := make([]<-chan error, 0)
cases := createSelectCases(statusChans)
for {
   chosen, value, recvOk := reflect.Select(cases)
   if recvOk == true {
      // Status Message Received
      log.Println(chosen, value.Interface(), recvOk)
   } else {
      // Channel closed, remove from list
      aliveStatusChans := make([]<-chan error, 0)
      for i, ch := range statusChans {
         if i != chosen {
            aliveStatusChans = append(aliveStatusChans, ch)
         }
      }
      // create new select cases without defunct channel
      statusChans = aliveStatusChans
      cases = createSelectCases(statusChans)
   }
}
```

