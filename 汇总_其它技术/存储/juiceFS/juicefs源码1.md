[https://cloud.tencent.com/developer/article/1849514](https://cloud.tencent.com/developer/article/1849514)

使用 JuiceFS，文件最终会被拆分成 Chunks、Slices 和 Blocks 存储在对象存储。因此，你会发现在对象存储平台的文件浏览器中找不到存入 JuiceFS 的源文件，存储桶中只有一个 chunks 目录和一堆数字编号的目录和文件。不要惊慌，这正是 JuiceFS 高性能运作的秘诀！

补充一下源码中，每个blocks的命名规则定义，也就是最终存储在对象存储系统中的对象key名称。

```
func (c *rChunk) key(indx int) string {
    if c.store.conf.Partitions > 1 {
        return fmt.Sprintf("chunks/%02X/%v/%v_%v_%v", c.id%256, c.id/1000/1000, c.id, indx, c.blockSize(indx))
    }
    return fmt.Sprintf("chunks/%v/%v/%v_%v_%v", c.id/1000/1000, c.id/1000, c.id, indx, c.blockSize(indx))
}
```

从命名规则里面也能看出，数据是支持按partition进行分区存储的，也就是说最终存储数据的bucket可以是多个，这样有助于提高并发能力，特别是AWS S3每个bucket是有TPS性能上限的。

### **JuiceFS文件系统golang抽象接口组成**

文件系统定义核心数据结构

```
type FileSystem struct {
    conf   *vfs.Config
    reader vfs.DataReader
    writer vfs.DataWriter
    m      meta.Meta
    logBuffer chan string
}
```

下图为个人理解所画的抽象接口结构图

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003484.jpg)

- 整个JuiceFS文件系统实现主要拆分为VFS抽象实现和相关的config配置管理两大部分。

- 任意文件File操作都涉及到数据和元数据两部分内容，因此代码中包含数据处理相关的DataReader和DataWriter两个抽象接口，用来处理数据的读取和写入两类请求。而元数据抽象出Meta一个数据库相关的接口，基于这个接口目前官方实现了dbMeta也就是兼容SQL相关的元数据实现，以及redisMeta实现(基于redis)。从性能表现来看，redis比MySQL性能要好3~5倍左右。具体可以参考这个

- 所有的数据读写操作都要和本地缓存进行交互(Chunk->Slice->block(page)三个层级进行管理)，缓存目前主要实现了基于本地文件系统diskStore和基于内存缓存cacheStore(堆空间)两种类型。数据写入和读取最终都是由对应的缓存模块同步到远程的ObjectSotrage。

- config主要负责对本地缓存、元数据引擎连接信息等相关的配置。