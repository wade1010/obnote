```
API: SYSTEM()
Time: 10:13:33 CST 05/30/2022
Error: Marking http://oeos3.com:19000/minio/storage/opt/oeos/dt/data2/v41 temporary offline; caused by Post "http://oeos3.com:19000/minio/storage/opt/oeos/dt/data2/v41/readall?disk-id=&file-path=format.json&volume=.minio.sys": dial tcp 172.16.1.233:19000: connect: connection refused (*fmt.wrapError)
       6: internal/rest/client.go:149:rest.(*Client).Call()
       5: cmd/storage-rest-client.go:152:cmd.(*storageRESTClient).call()
       4: cmd/storage-rest-client.go:520:cmd.(*storageRESTClient).ReadAll()
       3: cmd/format-erasure.go:406:cmd.loadFormatErasure()
       2: cmd/format-erasure.go:326:cmd.loadFormatErasureAll.func1()
       1: internal/sync/errgroup/errgroup.go:123:errgroup.(*Group).Go.func1()
.....

Waiting for all MinIO sub-systems to be initialized.. lock acquired
Verifying if 1 bucket is consistent across drives...
Waiting for all MinIO sub-systems to be initialized.. possible cause (Unable to list buckets to heal: Storage resources are insufficient for the write operation oeosstorage1/)
```

Waiting for all MinIO sub-systems to be initialized.. lock acquired

Verifying if 1 bucket is consistent across drives...

Waiting for all MinIO sub-systems to be initialized.. possible cause (Unable to list buckets to heal: Storage resources are insufficient for the write operation oeosstorage1/)

***mc: <ERROR> Unable to display heal status. Heal had an error - Storage resources are insufficient for the read operation .minio.sys/config/config.json.***

[http://blog.minio.org.cn/index/news/newsdetails.html?nid=59](http://blog.minio.org.cn/index/news/newsdetails.html?nid=59)

[https://github.com/minio/minio/tree/master/docs/erasure/storage-class](https://github.com/minio/minio/tree/master/docs/erasure/storage-class)

[https://blog.csdn.net/a772304419/article/details/121103694](https://blog.csdn.net/a772304419/article/details/121103694)

[https://blog.csdn.net/u014294083/article/details/120667857](https://blog.csdn.net/u014294083/article/details/120667857)

原因：

因为测试环境4个节点，每个节点4个driver

但是在启动minio的时候，设置了MINIO_STORAGE_CLASS_STANDARD=EC:2

也就是坏两个driver是可以自动修复的。

但是后来宕机了一个节点，导致，坏的driver超过了2.所以minio就有问题了