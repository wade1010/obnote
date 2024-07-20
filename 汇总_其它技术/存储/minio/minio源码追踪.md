可以声明 类型 不初始化 通过append来初始化底层数组

```javascript
var ctrlChans []chan<- string
var statusChans []<-chan error

ctrlChan, statusChan, storage := storageModule.Start()
ctrlChans = append(ctrlChans, ctrlChan)
statusChans = append(statusChans, statusChan)
```





```javascript
断言相等
c.Assert(bytes.Equal(value, byteBuffer.Bytes()), Equals, true)
或
c.Assert(metadata.Size,Equals,len(value))
```







```javascript
func (c *Config) WriteConfig() error {
   var file *os.File
   var err error
   c.configLock.Lock()
   defer c.configLock.Unlock()
   file, err = os.OpenFile(c.configPath, os.O_WRONLY, 0666)
   defer file.Close()
   if err != nil {
      return err
   }
   encoder := json.NewEncoder(file)
   encoder.Encode(c.Users)
   return nil
}
```





```javascript
objects, isTruncated, err := server.storage.ListObjects(bucket, prefix, 1000)
switch err := err.(type) {
case nil: // success
   response := generateObjectsListResult(bucket, objects, isTruncated)
   w.Write(writeObjectHeadersAndResponse(w, response, acceptsContentType))
case mstorage.BucketNotFound:
   log.Println(err)
   w.WriteHeader(http.StatusNotFound)
   w.Write([]byte(err.Error()))
case mstorage.ImplementationError:
   log.Println(err)
   w.WriteHeader(http.StatusInternalServerError)
   w.Write([]byte(err.Error()))
default:
   w.WriteHeader(http.StatusBadRequest)
   w.Write([]byte(err.Error()))
}
```





err包含 io.EOF  Ignore EOF in ReadConfig() 

```javascript

func (c *Config) ReadConfig() error {
   var file *os.File
   var err error

   c.configLock.RLock()
   defer c.configLock.RUnlock()

   file, err = os.OpenFile(c.configFile, os.O_RDONLY, 0666)
   defer file.Close()
   if err != nil {
      return err
   }

   users := make(map[string]User)
   decoder := json.NewDecoder(file)
   err = decoder.Decode(&users)
   switch err {
   case io.EOF:
      return nil
   case nil:
      c.Users = users
      return nil
   default:
      return err
   }
}
```





```javascript
vals, _ := url.ParseQuery(req.URL.RawQuery)
```



```javascript
buf := new(bytes.Buffer)


var resultsBuffer bytes.Buffer
resultsWriter := bufio.NewWriter(&resultsBuffer)
```





```javascript
sort.Slice(list, func(i, j int) bool { return list[i].Name() < list[j].Name() })
```





```javascript
func AppendUniqInt(slice []int, i int) []int {
   for _, ele := range slice {
      if ele == i {
         return slice
      }
   }
   return append(slice, i)
}

func AppendUniqStr(slice []string, i string) []string {
   for _, ele := range slice {
      if ele == i {
         return slice
      }
   }
   return append(slice, i)
}
```



```javascript
func delimiter(object, delimiter string) string {
   readBuffer := bytes.NewBufferString(object)
   reader := bufio.NewReader(readBuffer)
   stringReader := strings.NewReader(delimiter)
   delimited, _ := stringReader.ReadByte()
   delimitedStr, _ := reader.ReadString(delimited)
   return delimitedStr
}
```

range read





```javascript
policy, e := ioutil.ReadFile("/tmp/test.json")
iamp, e := iampolicy.ParseConfig(bytes.NewReader(policy))


func ParseConfig(reader io.Reader) (*Policy, error) {
   var iamp Policy

   decoder := json.NewDecoder(reader)
   decoder.DisallowUnknownFields()
   if err := decoder.Decode(&iamp); err != nil {
      return nil, Errorf("%w", err)
   }

   return &iamp, iamp.Validate()
}


```

































