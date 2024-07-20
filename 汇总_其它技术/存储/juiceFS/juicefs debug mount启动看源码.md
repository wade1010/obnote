main.go里面importcmd包和utils包

通过多个init()函数首先注册各种元数据组件

下面以redis为例

```
func init() {
   Register("redis", newRedisMeta)
   Register("rediss", newRedisMeta)
   Register("unix", newRedisMeta)
}
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003508.jpg)

然后进入main函数

```
err := app.Run(reorderOptions(app, args))
```

这里就会去调用juicefs/cmd/mount.go

解析参数

如果是windows，就开启CaseInsensi

创建metaCli客户端

通过metaCli load format

```
{
"Name": "myjuicefs",
"UUID": "2b645fb0-12f3-479b-bdc9-7c6978e7e124",
"Storage": "s3",
"Bucket": "http://127.0.0.1:9000/bkjuicifs",
"AccessKey": "minioadmin",
"SecretKey": "rihchqP4C/YS/hkR1xQ2pIiHkogIk28TO4RIvYChXgcOcBlouK4=",
"BlockSize": 4096,
"Compression": "none",
"EncryptAlgo": "aes256gcm-rsa",
"KeyEncrypted": true,
"TrashDays": 1,
"MetaVersion": 1
}
```

Wrap the default registry, all prometheus.MustRegister() calls should be afterwards

```
registerer, registry := wrapRegister(mp, format.Name)
```

```
func wrapRegister(mp, name string) (prometheus.Registerer, *prometheus.Registry) {
   registry := prometheus.NewRegistry() // replace default so only JuiceFS metrics are exposed
   registerer := prometheus.WrapRegistererWithPrefix("juicefs_",
      prometheus.WrapRegistererWith(prometheus.Labels{"mp": mp, "vol_name": name}, registry))
   registerer.MustRegister(collectors.NewProcessCollector(collectors.ProcessCollectorOpts{}))
   registerer.MustRegister(collectors.NewGoCollector())
   return registerer, registry
}
```

```
blob, err := NewReloadableStorage(format, metaCli, updateFormat(c))
```

-------------------------------------NewReloadableStorage开始-------------------------------------

```
blob, err := createStorage(*format)
```

```
blob, err = object.CreateStorage(strings.ToLower(format.Storage), format.Bucket, format.AccessKey, format.SecretKey, format.SessionToken)
```

```
func CreateStorage(name, endpoint, accessKey, secretKey, token string) (ObjectStorage, error) {
   f, ok := storages[name]
   if ok {
      logger.Debugf("Creating %s storage at endpoint %s", name, endpoint)
      return f(endpoint, accessKey, secretKey, token)
   }
   return nil, fmt.Errorf("invalid storage: %s", name)
}
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003538.jpg)

这里是S3，其实会调用 pkg/object/s3.go里面的newS3,创建s3client

-------------------------------------NewReloadableStorage结束-------------------------------------

-------------------------------------接续进入mount.go------------------------------------

获取存储层chunkConf

```
chunkConf := getChunkConf(c, format)
store := chunk.NewCachedStore(blob, *chunkConf, registerer)
```

```
vfsConf := getVfsConf(c, metaConf, format, chunkConf)
ignore := prepareMp(vfsConf, mp)
```

```
if c.Bool("background") && os.Getenv("JFS_FOREGROUND") == "" {
   daemonRun(c, addr, vfsConf, metaCli)
} else {
   go checkMountpoint(vfsConf.Format.Name, mp, c.String("log"), false)
}

removePassword(addr)
err = metaCli.NewSession()
```

```
func (m *baseMeta) NewSession() error {
    .......
   if !m.conf.NoBGJob {
      go m.cleanupDeletedFiles()
      go m.cleanupSlices()
      go m.cleanupTrash()
   }
   return nil
}
```

```
installHandler(mp)
```

```
v := vfs.NewVFS(vfsConf, metaCli, store, registerer, registry)
```

```
initBackgroundTasks(c, vfsConf, metaConf, metaCli, blob, registerer, registry)
主要是开启统计、备份元数据到对象存储和发送使用情况到juicefs官方（就是上报自己使用情况）ReportUsage
if !c.Bool("no-usage-report") {
   go usage.ReportUsage(m, version.Version())
}
```

```
mount_main(v, c)
```

```
主要部分
err := fuse.Serve(v, c.String("o"), c.Bool("enable-xattr"), c.Bool("enable-ioctl"))
    fssrv, err := fuse.NewServer(imp, conf.Meta.MountPoint, &opt)
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003086.jpg)

至此系统正式启动。