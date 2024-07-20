挂载后，在桶的根目录执行命令行 echo "11111" > /mnt/s3/123456.txt

fuse库(file_system.go)::ServeOps 循环等待IO操作

fuse库(file_system.go)::ReadO（得到对应的fuse操作）

fuse库(file_system.go)::handleOp （协程处理）

use库(file_system.go)::CreateFile  (是 CreateFileOp)

goofys代码(panic_logger.go)::CreateFile

goofys代码(goofys.go)::CreateFile    (这一步创建一个空文件，Put操作的时候Content-Length为0)

```
inode, fh := parent.Create(op.Name, op.OpContext)
```

```
fs.insertInode(parent, inode)
```

fuse库(file_system.go)::FlushFileOp

fuse库(file_system.go)::WriteFileOp   (这一步创建把数据写入到该文件，这样的话，如果开启了多版本，其实就是生成了两个版本)

goofys代码(panic_logger.go)::WriteFile

goofys代码(goofys.go)::WriteFile

goofys代码(file.go)::WriteFile

fuse库(file_system.go)::FlushFileOp

fuse库(file_system.go)::GetInodeAttributesOp 

fuse库(file_system.go)::LookUpInodeOp

fuse库(file_system.go)::FlushFileOp

fuse库(file_system.go)::ReleaseFileHandlerOp