一、导入

import “github.com/tecbot/gorocksdb”

二、创建和连接

```javascript
bbto := gorocksdb.NewDefaultBlockBasedTableOptions()
bbto.SetBlockCache(gorocksdb.NewLRUCache(3 << 30))
opts := gorocksdb.NewDefaultOptions()
opts.SetBlockBasedTableFactory(bbto)
opts.SetCreateIfMissing(true)
db, err := gorocksdb.OpenDb(opts, "/path/to/db")
```



三、写入和获取

```javascript
ro := gorocksdb.NewDefaultReadOptions()
wo := gorocksdb.NewDefaultWriteOptions()
// if ro and wo are not used again, be sure to Close them.
err = db.Put(wo, []byte("foo"), []byte("bar"))
...
value, err := db.Get(ro, []byte("foo"))
defer value.Free()
...
err = db.Delete(wo, []byte("foo"))

```

四、迭代器

```javascript
ro := gorocksdb.NewDefaultReadOptions()
ro.SetFillCache(false)
it := db.NewIterator(ro)
defer it.Close()
it.Seek([]byte("foo"))
for it = it; it.Valid(); it.Next() {
    key := it.Key()
    value := it.Value()
    fmt.Printf("Key: %v Value: %v\n", key.Data(), value.Data())
    key.Free()
    value.Free()
}
if err := it.Err(); err != nil {
    ...
}

```

五、过滤

```javascript
filter := gorocksdb.NewBloomFilter(10)
bbto := gorocksdb.NewDefaultBlockBasedTableOptions()
bbto.SetFilterPolicy(filter)
opts.SetBlockBasedTableFactory(bbto)
db, err := gorocksdb.OpenDb(opts, "/path/to/db")
```

