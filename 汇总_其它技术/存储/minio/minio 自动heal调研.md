mc heal 桶的时候

mc admin heal mys3minio/test

调用healBucket 函数签名如下

```
func (h *healSequence) healBucket(objAPI ObjectLayer, bucket string, bucketsOnly bool) error {
```

heal 桶的时候 使用默认的，只heal桶和桶的元数据

如果想heal桶下面所有文件，就得使用 mc admin heal --recursive mys3minio/test

意思就是heal这个桶和桶元数据，然后递归桶下面的目录来heal 对象

如果只想看桶的内有哪些需要heal的，mc admin heal -r -n mys3minio/test

上面命令虽然是dry-run  但是如果桶目录丢失了，会修复，但是对象不会修复。

heal的时候如果系统已经有一个heal在运行了，就不会再运行了，如果想强制执行，就需要加上force-start参数。

也可以加上force-stop强制当前系统中running的heal

读的时候不会自动heal

32个循环执行一次目录

512个循环执行一次对象heal