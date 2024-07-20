断点

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003843.jpg)

挂载后，在桶的根目录执行命令行 echo "11111" > /mnt/s3/333.txt

go-fuse库(server.go)::readRequest->handleRequest(这个时候handler是CREATE)->

go-fuse库(opcode.go)::doCreate

juicefs代码(pkg/fuse.go)::Create

juicefs代码(pkg/vfs/vfs.go)::Create

juicefs代码(pkg/meta/base.go)::Create

juicefs代码(pkg/meta/base.go)::Mknod

juicefs代码(pkg/meta/redis.go)::doMknod

创建完元数据后继续执行juicefs代码(base.go)::Create->m.of.Open(*inode, attr)

创建完inode继续执行juicefs代码(base.go)::Create->fh = v.newFileHandle(inode, attr.Length, flags)

juicefs代码(pkg/vfs/handle.go)::newFileHandle

写完文件，是写到cache层，还没持久化，所以要进行下一个动作flush，这里就是存到s3对象存储

go-fuse库(server.go)::readRequest->handleRequestt(这个时候handler是FLUSH)->

go-fuse库(opcode.go)::doFlush

juicefs代码(pkg/fuse.go)::Flush

juicefs代码(pkg/vfs/vfs.go)::Flush

go-fuse库(server.go)::readRequest->handleRequestt(这个时候handler是RELEASE)->

go-fuse库(opcode.go)::doRelease

juicefs代码(pkg/fuse.go)::Release

juicefs代码(pkg/vfs/vfs.go)::Release

可以看出元数据保存和数据保存是分开的，所以元数据保存成功到数据未成功保存到S3期间，断电，会导致元数据显示数据存在（ls可以看到），但是真实的文件不存在

这个大部分时候是CREATE+FLUSH+WRITE+FLUSH+RELEASE  第二FLUSH不会把内容保存到S3

文件存在的时候FLUSH+WRITE+FLUSH+RELEASE  第二FLUSH不会把内容保存到S3

go-fuse库(opcode.go)::doCreate

```
func doCreate(server *Server, req *request) {
   out := (*CreateOut)(req.outData())
   status := server.fileSystem.Create(req.cancel, (*CreateIn)(req.inData), req.filenames[0], out)
   req.status = status
}
```

juicefs代码(fuse.go)::Create

```
func (fs *fileSystem) Create(cancel <-chan struct{}, in *fuse.CreateIn, name string, out *fuse.CreateOut) (code fuse.Status) {
   ctx := newContext(cancel, &in.InHeader)
   defer releaseContext(ctx)
   entry, fh, err := fs.v.Create(ctx, Ino(in.NodeId), name, uint16(in.Mode), 0, in.Flags)
   if err != 0 {
      return fuse.Status(err)
   }
   out.Fh = fh
   return fs.replyEntry(ctx, &out.EntryOut, entry)
}
```

juicefs代码(vfs.go)::Create

```
func (v *VFS) Create(ctx Context, parent Ino, name string, mode uint16, cumask uint16, flags uint32) (entry *meta.Entry, fh uint64, err syscall.Errno) {
   defer func() {
      logit(ctx, "create (%d,%s,%s:0%04o): %s%s [fh:%d]", parent, name, smode(mode), mode, strerr(err), (*Entry)(entry), fh)
   }()
   if parent == rootID && IsSpecialName(name) {
      err = syscall.EEXIST
      return
   }
   if len(name) > maxName {
      err = syscall.ENAMETOOLONG
      return
   }

   var inode Ino
   var attr = &Attr{}
   err = v.Meta.Create(ctx, parent, name, mode&07777, cumask, flags, &inode, attr)
   if runtime.GOOS == "darwin" && err == syscall.ENOENT {
      err = syscall.EACCES
   }
   if err == 0 {
      v.UpdateLength(inode, attr)
      fh = v.newFileHandle(inode, attr.Length, flags)
      entry = &meta.Entry{Inode: inode, Attr: attr}
   }
   return
}
```

juicefs代码(base.go)::Create

```
func (m *baseMeta) Create(ctx Context, parent Ino, name string, mode uint16, cumask uint16, flags uint32, inode *Ino, attr *Attr) syscall.Errno {
   if attr == nil {
      attr = &Attr{}
   }
   eno := m.Mknod(ctx, parent, name, TypeFile, mode, cumask, 0, "", inode, attr)
   if eno == syscall.EEXIST && (flags&syscall.O_EXCL) == 0 && attr.Typ == TypeFile {
      eno = 0
   }
   if eno == 0 && inode != nil {
      m.of.Open(*inode, attr)
   }
   return eno
}
```

eno := m.Mknod(ctx, parent, name, TypeFile, mode, cumask, 0, "", inode, attr)

```
func (m *baseMeta) Mknod(ctx Context, parent Ino, name string, _type uint8, mode, cumask uint16, rdev uint32, path string, inode *Ino, attr *Attr) syscall.Errno {
   if isTrash(parent) {
      return syscall.EPERM
   }
   if parent == RootInode && name == TrashName {
      return syscall.EPERM
   }
   if m.conf.ReadOnly {
      return syscall.EROFS
   }
   if name == "" {
      return syscall.ENOENT
   }

   defer m.timeit(time.Now())
   if m.checkQuota(4<<10, 1) {
      return syscall.ENOSPC
   }
   return m.en.doMknod(ctx, m.checkRoot(parent), name, _type, mode, cumask, rdev, path, inode, attr)
}
```

return m.en.doMknod(ctx, m.checkRoot(parent), name, _type, mode, cumask, rdev, path, inode, attr)

```
func (m *redisMeta) doMknod(ctx Context, parent Ino, name string, _type uint8, mode, cumask uint16, rdev uint32, path string, inode *Ino, attr *Attr) syscall.Errno {
   var ino Ino
   var err error
   if parent == TrashInode {
      var next int64
      next, err = m.incrCounter("nextTrash", 1)
      ino = TrashInode + Ino(next)
   } else {
      ino, err = m.nextInode()
   }
   if err != nil {
      return errno(err)
   }
   if attr == nil {
      attr = &Attr{}
   }
   attr.Typ = _type
   attr.Mode = mode & ^cumask
   attr.Uid = ctx.Uid()
   attr.Gid = ctx.Gid()
   if _type == TypeDirectory {
      attr.Nlink = 2
      attr.Length = 4 << 10
   } else {
      attr.Nlink = 1
      if _type == TypeSymlink {
         attr.Length = uint64(len(path))
      } else {
         attr.Length = 0
         attr.Rdev = rdev
      }
   }
   attr.Parent = parent
   attr.Full = true
   if inode != nil {
      *inode = ino
   }

   err = m.txn(ctx, func(tx *redis.Tx) error {
      var pattr Attr
      a, err := tx.Get(ctx, m.inodeKey(parent)).Bytes()
      if err != nil {
         return err
      }
      m.parseAttr(a, &pattr)
      if pattr.Typ != TypeDirectory {
         return syscall.ENOTDIR
      }
      if (pattr.Flags & FlagImmutable) != 0 {
         return syscall.EPERM
      }

      buf, err := tx.HGet(ctx, m.entryKey(parent), name).Bytes()
      if err != nil && err != redis.Nil {
         return err
      }
      var foundIno Ino
      var foundType uint8
      if err == nil {
         foundType, foundIno = m.parseEntry(buf)
      } else if m.conf.CaseInsensi { // err == redis.Nil
         if entry := m.resolveCase(ctx, parent, name); entry != nil {
            foundType, foundIno = entry.Attr.Typ, entry.Inode
         }
      }
      if foundIno != 0 {
         if _type == TypeFile || _type == TypeDirectory { // file for create, directory for subTrash
            a, err = tx.Get(ctx, m.inodeKey(foundIno)).Bytes()
            if err == nil {
               m.parseAttr(a, attr)
            } else if err == redis.Nil {
               *attr = Attr{Typ: foundType, Parent: parent} // corrupt entry
            } else {
               return err
            }
            if inode != nil {
               *inode = foundIno
            }
         }
         return syscall.EEXIST
      }

      var updateParent bool
      now := time.Now()
      if parent != TrashInode {
         if _type == TypeDirectory {
            pattr.Nlink++
            updateParent = true
         }
         if updateParent || now.Sub(time.Unix(pattr.Mtime, int64(pattr.Mtimensec))) >= minUpdateTime {
            pattr.Mtime = now.Unix()
            pattr.Mtimensec = uint32(now.Nanosecond())
            pattr.Ctime = now.Unix()
            pattr.Ctimensec = uint32(now.Nanosecond())
            updateParent = true
         }
      }
      attr.Atime = now.Unix()
      attr.Atimensec = uint32(now.Nanosecond())
      attr.Mtime = now.Unix()
      attr.Mtimensec = uint32(now.Nanosecond())
      attr.Ctime = now.Unix()
      attr.Ctimensec = uint32(now.Nanosecond())
      if pattr.Mode&02000 != 0 || ctx.Value(CtxKey("behavior")) == "Hadoop" || runtime.GOOS == "darwin" {
         attr.Gid = pattr.Gid
         if _type == TypeDirectory && runtime.GOOS == "linux" {
            attr.Mode |= pattr.Mode & 02000
         }
      }

      _, err = tx.TxPipelined(ctx, func(pipe redis.Pipeliner) error {
         pipe.HSet(ctx, m.entryKey(parent), name, m.packEntry(_type, ino))
         if updateParent {
            pipe.Set(ctx, m.inodeKey(parent), m.marshal(&pattr), 0)
         }
         pipe.Set(ctx, m.inodeKey(ino), m.marshal(attr), 0)
         if _type == TypeSymlink {
            pipe.Set(ctx, m.symKey(ino), path, 0)
         }
         pipe.IncrBy(ctx, m.usedSpaceKey(), align4K(0))
         pipe.Incr(ctx, m.totalInodesKey())
         return nil
      })
      return err
   }, m.inodeKey(parent), m.entryKey(parent))
   if err == nil {
      m.updateStats(align4K(0), 1)
   }
   return errno(err)
}
```

juicefs代码(base.go)::Create->m.of.Open(*inode, attr)

```
func (m *baseMeta) Create(ctx Context, parent Ino, name string, mode uint16, cumask uint16, flags uint32, inode *Ino, attr *Attr) syscall.Errno {
   if attr == nil {
      attr = &Attr{}
   }
   eno := m.Mknod(ctx, parent, name, TypeFile, mode, cumask, 0, "", inode, attr)
   if eno == syscall.EEXIST && (flags&syscall.O_EXCL) == 0 && attr.Typ == TypeFile {
      eno = 0
   }
   if eno == 0 && inode != nil {
      m.of.Open(*inode, attr)
   }
   return eno
}
```

```go
type baseMeta struct {
   .....
   of           *openfiles
   ......
   en engine
}

type openfiles struct {
   sync.Mutex
   expire time.Duration
   files  map[Ino]*openFile
}

type openFile struct {
   sync.RWMutex
   attr      Attr
   refs      int
   lastCheck time.Time
   chunks    map[uint32][]Slice
}
```

```
func (o *openfiles) Open(ino Ino, attr *Attr) {
   o.Lock()
   defer o.Unlock()
   of, ok := o.files[ino]
   if !ok {
      of = &openFile{}
      of.chunks = make(map[uint32][]Slice)
      o.files[ino] = of
   } else if attr != nil && attr.Mtime == of.attr.Mtime && attr.Mtimensec == of.attr.Mtimensec {
      attr.KeepCache = of.attr.KeepCache
   } else {
      of.chunks = make(map[uint32][]Slice)
   }
   if attr != nil {
      of.attr = *attr
   }
   // next open can keep cache if not modified
   of.attr.KeepCache = true
   of.refs++
   of.lastCheck = time.Now()
}
```

juicefs代码(base.go)::Create->v.UpdateLength(inode, attr)

```
if err == 0 {
   v.UpdateLength(inode, attr)
   fh = v.newFileHandle(inode, attr.Length, flags)
   entry = &meta.Entry{Inode: inode, Attr: attr}
}
```

juicefs代码(pkg/vfs/handle.go)::newFileHandle

```
func (v *VFS) newFileHandle(inode Ino, length uint64, flags uint32) uint64 {
   h := v.newHandle(inode)
   h.Lock()
   defer h.Unlock()
   switch flags & O_ACCMODE {
   case syscall.O_RDONLY:
      h.reader = v.reader.Open(inode, length)
   case syscall.O_WRONLY: // FUSE writeback_cache mode need reader even for WRONLY
      fallthrough
   case syscall.O_RDWR:
      h.reader = v.reader.Open(inode, length)
      h.writer = v.writer.Open(inode, length)
   }
   return h.fh
}
```

GETATTR

LOOKUP

GETATTR

LOOKUP

CREATE

GETATTR

WRITE

FLUSH

GETATTR

LOOKUP

RELEASE