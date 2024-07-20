启动的时候

server-main.go

```javascript
// Initialize object layer with the supplied disks, objectLayer is nil upon any error.
func newObjectLayer(ctx context.Context, endpointServerPools EndpointServerPools) (newObject ObjectLayer, err error) {
   // For FS only, directly use the disk.
   if endpointServerPools.NEndpoints() == 1 {
      // Initialize new FS object layer.
      return NewFSObjectLayer(endpointServerPools[0].Endpoints[0].Path)
   }

   return newErasureServerPools(ctx, endpointServerPools)
}
```



Initialize object layer with the supplied disks



endpoint只有一个就直接使用disk



Initialize meta volume, if volume already exists ignores it.

if err = initMetaVolumeFS(fsPath, fsUUID); err != nil {

	return nil, err

}

```javascript
// Initializes meta volume on all the fs path.
func initMetaVolumeFS(fsPath, fsUUID string) error {
   // This happens for the first time, but keep this here since this
   // is the only place where it can be made less expensive
   // optimizing all other calls. Create minio meta volume,
   // if it doesn't exist yet.
   metaBucketPath := pathJoin(fsPath, ".minio.sys")

   if err := os.MkdirAll(metaBucketPath, 0777); err != nil {
      return err
   }

   metaTmpPath := pathJoin(fsPath, ".minio.sys/tmp", fsUUID)
   if err := os.MkdirAll(metaTmpPath, 0777); err != nil {
      return err
   }

   if err := os.MkdirAll(pathJoin(fsPath, ".minio.sys/buckets"), 0777); err != nil {
      return err
   }

   metaMultipartPath := pathJoin(fsPath, ".minio.sys/multipart")
   return os.MkdirAll(metaMultipartPath, 0777)

}
```



Initialize `format.json`

fsFormatPath := pathJoin(fsPath, ".minio.sys", "format.json")



```javascript
func newFormatFSV1() (format *formatFSV1) {
   f := &formatFSV1{}
   f.Version = formatMetaVersionV1
   f.Format = formatBackendFS
   f.ID = mustGetUUID()
   f.FS.Version = formatFSVersionV1
   return f
}
```







Enabling component pd

	Enabling instance pd 192.168.199.11:2379

	Enable pd 192.168.199.11:2379 success

Enabling component tikv

	Enabling instance tikv 192.168.199.11:20162

	Enabling instance tikv 192.168.199.11:20160

	Enabling instance tikv 192.168.199.11:20161

	Enable tikv 192.168.199.11:20162 success

	Enable tikv 192.168.199.11:20161 success

	Enable tikv 192.168.199.11:20160 success

Enabling component tidb

	Enabling instance tidb 192.168.199.11:4000

	Enable tidb 192.168.199.11:4000 success

Enabling component tiflash

	Enabling instance tiflash 192.168.199.11:9000

	Enable tiflash 192.168.199.11:9000 success

Enabling component prometheus

	Enabling instance prometheus 192.168.199.11:9090

	Enable prometheus 192.168.199.11:9090 success

Enabling component grafana

	Enabling instance grafana 192.168.199.11:3000

	Enable grafana 192.168.199.11:3000 success







create bucket 

put object

get object



# 初始化

server-main.go 中 initServer()->initAllSubsystems()

```javascript
func initAllSubsystems(ctx context.Context, newObject ObjectLayer) (err error) {
   // %w is used by all error returns here to make sure
   // we wrap the underlying error, make sure when you
   // are modifying this code that you do so, if and when
   // you want to add extra context to your error. This
   // ensures top level retry works accordingly.
   // List buckets to heal, and be re-used for loading configs.
   rquorum := InsufficientReadQuorum{}
   wquorum := InsufficientWriteQuorum{}

   buckets, err := newObject.ListBuckets(ctx)
   if err != nil {
      return fmt.Errorf("Unable to list buckets to heal: %w", err)
   }

   if globalIsErasure {
      if len(buckets) > 0 {
         if len(buckets) == 1 {
            logger.Info(fmt.Sprintf("Verifying if %d bucket is consistent across drives...", len(buckets)))
         } else {
            logger.Info(fmt.Sprintf("Verifying if %d buckets are consistent across drives...", len(buckets)))
         }
      }

      // Limit to no more than 50 concurrent buckets.
      g := errgroup.WithNErrs(len(buckets)).WithConcurrency(50)
      ctx, cancel := g.WithCancelOnError(ctx)
      defer cancel()
      for index := range buckets {
         index := index
         g.Go(func() error {
            _, berr := newObject.HealBucket(ctx, buckets[index].Name, madmin.HealOpts{Recreate: true})
            return berr
         }, index)
      }
      if err := g.WaitErr(); err != nil {
         return fmt.Errorf("Unable to list buckets to heal: %w", err)
      }
   }

   // Initialize config system.
   if err = globalConfigSys.Init(newObject); err != nil {
      if errors.Is(err, errDiskNotFound) ||
         errors.Is(err, errConfigNotFound) ||
         errors.Is(err, context.DeadlineExceeded) ||
         errors.Is(err, errErasureWriteQuorum) ||
         errors.Is(err, errErasureReadQuorum) ||
         errors.As(err, &rquorum) ||
         errors.As(err, &wquorum) ||
         isErrBucketNotFound(err) {
         return fmt.Errorf("Unable to initialize config system: %w", err)
      }
      // Any other config errors we simply print a message and proceed forward.
      logger.LogIf(ctx, fmt.Errorf("Unable to initialize config, some features may be missing %w", err))
   }

   // Populate existing buckets to the etcd backend
   if globalDNSConfig != nil {
      // Background this operation.
      go initFederatorBackend(buckets, newObject)
   }

   // Initialize bucket metadata sub-system.
   globalBucketMetadataSys.Init(ctx, buckets, newObject)

   // Initialize notification system.
   globalNotificationSys.Init(ctx, buckets, newObject)

   // Initialize bucket targets sub-system.
   globalBucketTargetSys.Init(ctx, buckets, newObject)

   return nil
}
```

# 1、 globalBucketMetadataSys.Init(ctx, buckets, newObject)->go sys.load(ctx, buckets, objAPI)->sys.concurrentLoad(ctx, buckets, objAPI)

```javascript
// concurrently load bucket metadata to speed up loading bucket metadata.
func (sys *BucketMetadataSys) concurrentLoad(ctx context.Context, buckets []BucketInfo, objAPI ObjectLayer) {
   g := errgroup.WithNErrs(len(buckets))
   for index := range buckets {
      index := index
      g.Go(func() error {
         _, _ = objAPI.HealBucket(ctx, buckets[index].Name, madmin.HealOpts{
            // Ensure heal opts for bucket metadata be deep healed all the time.
            ScanMode: madmin.HealDeepScan,
         })
         meta, err := loadBucketMetadata(ctx, objAPI, buckets[index].Name)
         if err != nil {
            return err
         }
         sys.Lock()
         sys.metadataMap[buckets[index].Name] = meta
         sys.Unlock()
         return nil
      }, index)
   }
   for _, err := range g.Wait() {
      if err != nil {
         logger.LogIf(ctx, err)
      }
   }
}
```

# 其中loadBucketMetadata()

```javascript
// loadBucketMetadata loads and migrates to bucket metadata.
func loadBucketMetadata(ctx context.Context, objectAPI ObjectLayer, bucket string) (BucketMetadata, error) {
   b := newBucketMetadata(bucket)
   err := b.Load(ctx, objectAPI, b.Name)
   if err != nil && !errors.Is(err, errConfigNotFound) {
      return b, err
   }

   // Old bucket without bucket metadata. Hence we migrate existing settings.
   if err := b.convertLegacyConfigs(ctx, objectAPI); err != nil {
      return b, err
   }
   // migrate unencrypted remote targets
   return b, b.migrateTargetConfig(ctx, objectAPI)
}
```

# b.Load()->readConfig(ctx, api, configFile)->objAPI.GetObjectNInfo()->fs.getObjectInfo()->fsMeta.ReadFrom()



# 2、globalBucketTargetSys.Init(ctx, buckets, newObject)->go sys.load(ctx, buckets, objAPI)->globalBucketMetadataSys.GetBucketTargetsConfig(bucket.Name)->sys.GetConfig(bucket)->loadBucketMetadata(GlobalContext, objAPI, bucket)—>b.Load()->readConfig(ctx, api, configFile)->objAPI.GetObjectNInfo()->fs.getObjectInfo()->fsMeta.ReadFrom()







# 创建桶[没有元数据保存需求]

创建桶会调用minio的api-router.go中的 PutBucket  [api.PutBucketHandler]

然后再调 fs-v1.go 中的 MakeBucketWithLocation ,这里面会先调fsMkdir创建桶目录

桶目录的meta信息

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007622.jpg)



然后再调meta.Save()保存

```javascript
meta := newBucketMetadata(bucket)
if err := meta.Save(ctx, fs); err != nil {
   return toObjectErr(err, bucket)
}
```

Save方法如下

```javascript
func (b *BucketMetadata) Save(ctx context.Context, api ObjectLayer) error {
   if err := b.parseAllConfigs(ctx, api); err != nil {
      return err
   }

   data := make([]byte, 4, b.Msgsize()+4)

   // Initialize the header.
   binary.LittleEndian.PutUint16(data[0:2], bucketMetadataFormat)
   binary.LittleEndian.PutUint16(data[2:4], bucketMetadataVersion)

   // Marshal the bucket metadata
   data, err := b.MarshalMsg(data)
   if err != nil {
      return err
   }

   configFile := path.Join(bucketConfigPrefix, b.Name, bucketMetadataFile)
   return saveConfig(ctx, api, configFile, data)
}
```

b.MarshalMsg方法如下

```javascript
func (z *BucketMetadata) MarshalMsg(b []byte) (o []byte, err error) {
   o = msgp.Require(b, z.Msgsize())
   // map header, size 14
   // string "Name"
   o = append(o, 0x8e, 0xa4, 0x4e, 0x61, 0x6d, 0x65)
   o = msgp.AppendString(o, z.Name)
   // string "Created"
   o = append(o, 0xa7, 0x43, 0x72, 0x65, 0x61, 0x74, 0x65, 0x64)
   o = msgp.AppendTime(o, z.Created)
   // string "LockEnabled"
   o = append(o, 0xab, 0x4c, 0x6f, 0x63, 0x6b, 0x45, 0x6e, 0x61, 0x62, 0x6c, 0x65, 0x64)
   o = msgp.AppendBool(o, z.LockEnabled)
   // string "PolicyConfigJSON"
   o = append(o, 0xb0, 0x50, 0x6f, 0x6c, 0x69, 0x63, 0x79, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x4a, 0x53, 0x4f, 0x4e)
   o = msgp.AppendBytes(o, z.PolicyConfigJSON)
   // string "NotificationConfigXML"
   o = append(o, 0xb5, 0x4e, 0x6f, 0x74, 0x69, 0x66, 0x69, 0x63, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.NotificationConfigXML)
   // string "LifecycleConfigXML"
   o = append(o, 0xb2, 0x4c, 0x69, 0x66, 0x65, 0x63, 0x79, 0x63, 0x6c, 0x65, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.LifecycleConfigXML)
   // string "ObjectLockConfigXML"
   o = append(o, 0xb3, 0x4f, 0x62, 0x6a, 0x65, 0x63, 0x74, 0x4c, 0x6f, 0x63, 0x6b, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.ObjectLockConfigXML)
   // string "VersioningConfigXML"
   o = append(o, 0xb3, 0x56, 0x65, 0x72, 0x73, 0x69, 0x6f, 0x6e, 0x69, 0x6e, 0x67, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.VersioningConfigXML)
   // string "EncryptionConfigXML"
   o = append(o, 0xb3, 0x45, 0x6e, 0x63, 0x72, 0x79, 0x70, 0x74, 0x69, 0x6f, 0x6e, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.EncryptionConfigXML)
   // string "TaggingConfigXML"
   o = append(o, 0xb0, 0x54, 0x61, 0x67, 0x67, 0x69, 0x6e, 0x67, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.TaggingConfigXML)
   // string "QuotaConfigJSON"
   o = append(o, 0xaf, 0x51, 0x75, 0x6f, 0x74, 0x61, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x4a, 0x53, 0x4f, 0x4e)
   o = msgp.AppendBytes(o, z.QuotaConfigJSON)
   // string "ReplicationConfigXML"
   o = append(o, 0xb4, 0x52, 0x65, 0x70, 0x6c, 0x69, 0x63, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x58, 0x4d, 0x4c)
   o = msgp.AppendBytes(o, z.ReplicationConfigXML)
   // string "BucketTargetsConfigJSON"
   o = append(o, 0xb7, 0x42, 0x75, 0x63, 0x6b, 0x65, 0x74, 0x54, 0x61, 0x72, 0x67, 0x65, 0x74, 0x73, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x4a, 0x53, 0x4f, 0x4e)
   o = msgp.AppendBytes(o, z.BucketTargetsConfigJSON)
   // string "BucketTargetsConfigMetaJSON"
   o = append(o, 0xbb, 0x42, 0x75, 0x63, 0x6b, 0x65, 0x74, 0x54, 0x61, 0x72, 0x67, 0x65, 0x74, 0x73, 0x43, 0x6f, 0x6e, 0x66, 0x69, 0x67, 0x4d, 0x65, 0x74, 0x61, 0x4a, 0x53, 0x4f, 0x4e)
   o = msgp.AppendBytes(o, z.BucketTargetsConfigMetaJSON)
   return
}
```

data, err := b.MarshalMsg(data) 生成的数据如下

```javascript
��Name�testbucket1�Created�`��H1t�H�LockEnabled°PolicyConfigJSON��NotificationConfigXML��LifecycleConfigXML��ObjectLockConfigXML��VersioningConfigXML��EncryptionConfigXML��TaggingConfigXML��QuotaConfigJSON��ReplicationConfigXML��BucketTargetsConfigJSON��BucketTargetsConfigMetaJSON�
```



meta.Save() 里面saveConfig(ctx, api, configFile, data)   [configFile=buckets/minio-go-test3vart49kwucxr5qo2/.metadata.bin]

```javascript
func saveConfig(ctx context.Context, objAPI ObjectLayer, configFile string, data []byte) error {
   hashReader, err := hash.NewReader(bytes.NewReader(data), int64(len(data)), "", getSHA256Hash(data), int64(len(data)))
   if err != nil {
      return err
   }

   _, err = objAPI.PutObject(ctx, minioMetaBucket, configFile, NewPutObjReader(hashReader), ObjectOptions{})
   return err
}
```

然后最终掉用fs-v1的PutObject方法

存入位置

bucketdir为  /Users/bob/minio/.minio.sys/buckets/testbucket1

object为   .metadata.bin



所以这个不需要用tikv保存





PutObject是先在临时目录存起来

/Users/bob/minio/.minio.sys/tmp/001b6aac-98b8-48be-9f92-6373c32e0682/2831e09a-1338-43ba-8520-70e2a96c2613

```javascript
tempObj := mustGetUUID()

fsTmpObjPath := pathJoin(fs.fsPath, minioMetaTmpBucket, fs.fsUUID, tempObj)
```



最后rename这个文件到真正的目标处



/Users/bob/minio/.minio.sys/buckets/testbucket1/.metadata.bin



保存完meta信息后，保存meta信息到内存

globalBucketMetadataSys.Set(bucket, meta)

```javascript
func (sys *BucketMetadataSys) Set(bucket string, meta BucketMetadata) {
   if globalIsGateway {
      return
   }

   if bucket != minioMetaBucket {
      sys.Lock()
      sys.metadataMap[bucket] = meta
      sys.Unlock()
   }
}
```



最后同步到其他client [如果有的话]

```javascript
// Load updated bucket metadata into memory.
globalNotificationSys.LoadBucketMetadata(GlobalContext, bucket)


// LoadBucketMetadata - calls LoadBucketMetadata call on all peers
func (sys *NotificationSys) LoadBucketMetadata(ctx context.Context, bucketName string) {
   ng := WithNPeers(len(sys.peerClients))
   for idx, client := range sys.peerClients {
      if client == nil {
         continue
      }
      client := client
      ng.Go(ctx, func() error {
         return client.LoadBucketMetadata(bucketName)
      }, idx, *client.host)
   }
   for _, nErr := range ng.Wait() {
      reqInfo := (&logger.ReqInfo{}).AppendTags("peerAddress", nErr.Host.String())
      if nErr.Err != nil {
         logger.LogIf(logger.SetReqInfo(ctx, reqInfo), nErr.Err)
      }
   }
}
```





# 桶列表

桶列表会调用minio的api-router.go中的 ListBuckets  [api.ListBucketsHandler]

然后再调 fs-v1.go 中的 ListBuckets ,这里面会调用fsMkdir创建桶目录

entries, err := readDir(fs.fsPath)

```javascript
bucketInfos := make([]BucketInfo, 0, len(entries))
for _, entry := range entries {
   // Ignore all reserved bucket names and invalid bucket names.
   if isReservedOrInvalidBucket(entry, false) {
      continue
   }
   var fi os.FileInfo
   fi, err = fsStatVolume(ctx, pathJoin(fs.fsPath, entry))
   // There seems like no practical reason to check for errors
   // at this point, if there are indeed errors we can simply
   // just ignore such buckets and list only those which
   // return proper Stat information instead.
   if err != nil {
      // Ignore any errors returned here.
      continue
   }
   var created = fi.ModTime()
   meta, err := globalBucketMetadataSys.Get(fi.Name())
   if err == nil {
      created = meta.Created
   }

   bucketInfos = append(bucketInfos, BucketInfo{
      Name:    fi.Name(),
      Created: created,
   })
}
```



# 创建对象

创建对象会调用minio的api-router.go中的 PutObject  [api.PutObjectHandler]

->putObject(ctx, bucket, object, pReader, opts)

然后再调fs-v1.go中的PutObject()->putObject()

然后文件存到临时目录，在调用fsRenameFile将临时文件变成目标文件

然后再保存meta信息到fs.json 路径示例如下：

/Users/bob/minio/.minio.sys/buckets/minio-go-test3vart49kwucxr5qo2/b3tdljnzhvbuf1fd6uxfvielszf13a/fs.json



->fsMeta.ToObjectInfo(bucket, object, fi)



# 获取对象

创建对象会调用minio的api-router.go中的 HeadObject  [api.HeadObjectHandler]

最终调fs-v1.go中的GetObjectInfo()->getObjectInfo(ctx, bucket, object, pReader, opts)->fsMeta.ReadFrom(ctx, rlk.LockedFile)->fsMeta.ToObjectInfo(bucket, object, fi)















