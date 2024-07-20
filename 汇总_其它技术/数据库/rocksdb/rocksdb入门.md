https://rocksdb.org.cn/



高度分层架构

RocksDB是一种可以存储任意二进制kv数据的嵌入式存储。RocksDB按顺序组织所有数据，他们的通用操作是Get(key), Put(key), Delete(Key)以及NewIterator()

RocksDB有三种基本的数据结构：mentable，sstfile以及logfile。mentable是一种内存数据结构——所有写入请求都会进入mentable，然后选择性进入logfile。logfile是一种有序写存储结构。当mentable被填满的时候，他会被刷到sstfile文件并存储起来，然后相关的logfile会在之后被安全地删除。sstfile内的数据都是排序好的，以便于根据key快速搜索。



