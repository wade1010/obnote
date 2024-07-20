断点

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003156.jpg)

挂载后，在桶的根目录执行命令行 ls

go-fuse库(server.go)::readRequest->handleRequest(这个时候handler是READDIRPLUS)->

go-fuse库(opcode.go)::doReadDirPlus

juicefs代码(fuse.go)::ReadDirPlus

juicefs代码(vfs.go)::Readdir

juicefs代码(base.go)::Readdir

juicefs代码(redis.go)::doReaddir

go-fuse库(server.go)::readRequest->handleRequest(这个时候handler是RELEASEDIR)->

go-fuse库(opcode.go)::doReleaseDir

juicefs代码(fuse.go)::ReleaseDir

juicefs代码(vfs.go)::Releasedir

##### --------------------go-fuse库开始--------------------

```
func (ms *Server) loop(exitIdle bool) {
   defer ms.loops.Done()
exit:
   for {
      req, errNo := ms.readRequest(exitIdle)
      switch errNo {
      case OK:
         if req == nil {
            break exit
         }
      case ENOENT:
         continue
      case ENODEV:
         // unmount
         if ms.opts.Debug {
            log.Printf("received ENODEV (unmount request), thread exiting")
         }
         break exit
      default: // some other error?
         log.Printf("Failed to read from fuse conn: %v", errNo)
         break exit
      }

      if ms.singleReader {
         go ms.handleRequest(req)
      } else {
         ms.handleRequest(req)
      }
   }
}
```

req, errNo := ms.readRequest(exitIdle)

```
// Returns a new request, or error. In case exitIdle is given, returns
// nil, OK if we have too many readers already.
func (ms *Server) readRequest(exitIdle bool) (req *request, code Status) {
   req = ms.reqPool.Get().(*request)
   dest := ms.readPool.Get().([]byte)

   ms.reqMu.Lock()
   if ms.reqReaders > ms.maxReaders {
      ms.reqMu.Unlock()
      return nil, OK
   }
   ms.reqReaders++
   ms.reqMu.Unlock()

   var n int
   err := handleEINTR(func() error {
      var err error
      n, err = syscall.Read(ms.mountFd, dest)
      return err
   })
   if err != nil {
      code = ToStatus(err)
      ms.reqPool.Put(req)
      ms.reqMu.Lock()
      ms.reqReaders--
      ms.reqMu.Unlock()
      return nil, code
   }

   if ms.latencies != nil {
      req.startTime = time.Now()
   }
   gobbled := req.setInput(dest[:n])

   ms.reqMu.Lock()
   defer ms.reqMu.Unlock()
   // Must parse request.Unique under lock
   if status := req.parseHeader(); !status.Ok() {
      return nil, status
   }
   req.inflightIndex = len(ms.reqInflight)
   ms.reqInflight = append(ms.reqInflight, req)
   if !gobbled {
      ms.readPool.Put(dest)
      dest = nil
   }
   ms.reqReaders--
   if !ms.singleReader && ms.reqReaders <= 0 {
      ms.loops.Add(1)
      go ms.loop(true)
   }

   return req, OK
}
```

go ms.loop(true)   重新返回第一个代码片段

ms.handleRequest(req)   

```
func (ms *Server) handleRequest(req *request) Status {
   if ms.opts.SingleThreaded {
      ms.requestProcessingMu.Lock()
      defer ms.requestProcessingMu.Unlock()
   }

   req.parse()
   if req.handler == nil {
      req.status = ENOSYS
   }

   if req.status.Ok() && ms.opts.Debug {
      log.Println(req.InputDebug())
   }

   if req.inHeader.NodeId == pollHackInode ||
      req.inHeader.NodeId == FUSE_ROOT_ID && len(req.filenames) > 0 && req.filenames[0] == pollHackName {
      doPollHackLookup(ms, req)
   } else if req.status.Ok() && req.handler.Func == nil {
      log.Printf("Unimplemented opcode %v", operationName(req.inHeader.Opcode))
      req.status = ENOSYS
   } else if req.status.Ok() {
      req.handler.Func(ms, req)
   }

   errNo := ms.write(req)
   if errNo != 0 {
      // Unless debugging is enabled, ignore ENOENT for INTERRUPT responses
      // which indicates that the referred request is no longer known by the
      // kernel. This is a normal if the referred request already has
      // completed.
      if ms.opts.Debug || !(req.inHeader.Opcode == _OP_INTERRUPT && errNo == ENOENT) {
         log.Printf("writer: Write/Writev failed, err: %v. opcode: %v",
            errNo, operationName(req.inHeader.Opcode))
      }

   }
   ms.returnRequest(req)
   return Status(errNo)
}
```

req.handler.Func(ms, req)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003711.jpg)

```
func doReadDirPlus(server *Server, req *request) {
   in := (*ReadIn)(req.inData)
   buf := server.allocOut(req, in.Size)
   out := NewDirEntryList(buf, uint64(in.Offset))

   code := server.fileSystem.ReadDirPlus(req.cancel, in, out)
   req.flatData = out.bytes()
   req.status = code
}
```

##### **--------------------go-fuse库结束--------------------**

##### **--------------------juicefs fuse.go开始--------------------**

```
func (fs *fileSystem) ReadDirPlus(cancel <-chan struct{}, in *fuse.ReadIn, out *fuse.DirEntryList) fuse.Status {
   ctx := newContext(cancel, &in.InHeader)
   defer releaseContext(ctx)
   entries, readAt, err := fs.v.Readdir(ctx, Ino(in.NodeId), in.Size, int(in.Offset), in.Fh, true)
   ctx.start = readAt
   var de fuse.DirEntry
   for _, e := range entries {
      de.Ino = uint64(e.Inode)
      de.Name = string(e.Name)
      de.Mode = e.Attr.SMode()
      eo := out.AddDirLookupEntry(de)
      if eo == nil {
         break
      }
      if e.Attr.Full {
         fs.replyEntry(ctx, eo, e)
      } else {
         eo.Ino = uint64(e.Inode)
         eo.Generation = 1
      }
   }
   return fuse.Status(err)
}
```

##### **--------------------juicefs fuse.go结束--------------------**

##### **--------------------juicefs pkg/vfs/vfs.go开始--------------------**

```
func (v *VFS) Readdir(ctx Context, ino Ino, size uint32, off int, fh uint64, plus bool) (entries []*meta.Entry, readAt time.Time, err syscall.Errno) {
   defer func() { logit(ctx, "readdir (%d,%d,%d): %s (%d)", ino, size, off, strerr(err), len(entries)) }()
   h := v.findHandle(ino, fh)
   if h == nil {
      err = syscall.EBADF
      return
   }
   h.Lock()
   defer h.Unlock()

   if h.children == nil || off == 0 {
      var inodes []*meta.Entry
      h.readAt = time.Now()
      err = v.Meta.Readdir(ctx, ino, 1, &inodes)
      if err == syscall.EACCES {
         err = v.Meta.Readdir(ctx, ino, 0, &inodes)
      }
      if err != 0 {
         return
      }
      h.children = inodes
      if ino == rootID && !v.Conf.HideInternal {
         // add internal nodes
         for _, node := range internalNodes[1:] {
            h.children = append(h.children, &meta.Entry{
               Inode: node.inode,
               Name:  []byte(node.name),
               Attr:  node.attr,
            })
         }
      }
   }
   if off < len(h.children) {
      entries = h.children[off:]
   }
   readAt = h.readAt
   return
}
```

err = v.Meta.Readdir(ctx, ino, 1, &inodes)

##### **--------------------juicefs pkg/vfs/vfs.go结束--------------------**

##### **--------------------juicefs pkg/meta/base.go结束--------------------**

```
func (m *baseMeta) Readdir(ctx Context, inode Ino, plus uint8, entries *[]*Entry) syscall.Errno {
   inode = m.checkRoot(inode)
   var attr Attr
   if err := m.GetAttr(ctx, inode, &attr); err != 0 {
      return err
   }
   defer m.timeit(time.Now())
   if inode == m.root {
      attr.Parent = m.root
   }
   *entries = []*Entry{
      {
         Inode: inode,
         Name:  []byte("."),
         Attr:  &Attr{Typ: TypeDirectory},
      },
   }
   *entries = append(*entries, &Entry{
      Inode: attr.Parent,
      Name:  []byte(".."),
      Attr:  &Attr{Typ: TypeDirectory},
   })
   return m.en.doReaddir(ctx, inode, plus, entries, -1)
}
```

	if err := m.GetAttr(ctx, inode, &attr); err != 0 {

		return err

	}

```
func (m *baseMeta) GetAttr(ctx Context, inode Ino, attr *Attr) syscall.Errno {
   inode = m.checkRoot(inode)
   if m.conf.OpenCache > 0 && m.of.Check(inode, attr) {
      return 0
   }
   defer m.timeit(time.Now())
   var err syscall.Errno
   if inode == RootInode {
      e := utils.WithTimeout(func() error {
         err = m.en.doGetAttr(ctx, inode, attr)
         return nil
      }, time.Millisecond*300)
      if e != nil || err != 0 {
         err = 0
         attr.Typ = TypeDirectory
         attr.Mode = 0777
         attr.Nlink = 2
         attr.Length = 4 << 10
      }
   } else {
      err = m.en.doGetAttr(ctx, inode, attr)
   }
   if err == 0 {
      m.of.Update(inode, attr)
   }
   return err
}
```

		e := utils.WithTimeout(func() error {

			err = m.en.doGetAttr(ctx, inode, attr)

			return nil

		}, time.Millisecond*300)

```
type baseMeta struct {
.......

   en engine
}

```

```
type engine interface {
   // Get the value of counter name.
   getCounter(name string) (int64, error)
   // Increase counter name by value. Do not use this if value is 0, use getCounter instead.
   incrCounter(name string, value int64) (int64, error)
   // Set counter name to value if old <= value - diff.
   setIfSmall(name string, value, diff int64) (bool, error)

   doLoad() ([]byte, error)

   doNewSession(sinfo []byte) error
   doRefreshSession()
   doFindStaleSessions(limit int) ([]uint64, error) // limit < 0 means all
   doCleanStaleSession(sid uint64) error

   scanAllChunks(ctx Context, ch chan<- cchunk, bar *utils.Bar) error
   compactChunk(inode Ino, indx uint32, force bool)
   doDeleteSustainedInode(sid uint64, inode Ino) error
   doFindDeletedFiles(ts int64, limit int) (map[Ino]uint64, error) // limit < 0 means all
   doDeleteFileData(inode Ino, length uint64)
   doCleanupSlices()
   doCleanupDelayedSlices(edge int64, limit int) (int, error)
   doDeleteSlice(id uint64, size uint32) error

   doGetAttr(ctx Context, inode Ino, attr *Attr) syscall.Errno
   doLookup(ctx Context, parent Ino, name string, inode *Ino, attr *Attr) syscall.Errno
   doMknod(ctx Context, parent Ino, name string, _type uint8, mode, cumask uint16, rdev uint32, path string, inode *Ino, attr *Attr) syscall.Errno
   doLink(ctx Context, inode, parent Ino, name string, attr *Attr) syscall.Errno
   doUnlink(ctx Context, parent Ino, name string) syscall.Errno
   doRmdir(ctx Context, parent Ino, name string) syscall.Errno
   doReadlink(ctx Context, inode Ino) ([]byte, error)
   doReaddir(ctx Context, inode Ino, plus uint8, entries *[]*Entry, limit int) syscall.Errno
   doRename(ctx Context, parentSrc Ino, nameSrc string, parentDst Ino, nameDst string, flags uint32, inode *Ino, attr *Attr) syscall.Errno
   doSetXattr(ctx Context, inode Ino, name string, value []byte, flags uint32) syscall.Errno
   doRemoveXattr(ctx Context, inode Ino, name string) syscall.Errno
   doGetParents(ctx Context, inode Ino) map[Ino]int
   doRepair(ctx Context, inode Ino, attr *Attr) syscall.Errno

   scanDeletedSlices(Context, deletedSliceScan) error
   scanDeletedFiles(Context, deletedFileScan) error

   GetSession(sid uint64, detail bool) (*Session, error)
}
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003896.jpg)

##### **--------------------juicefs pkg/meta/base.go结束--------------------**

##### **--------------------juicefs pkg/meta/redis.go开始--------------------**

这里使用的是redis做元数据，所以进入了redis.go

```
func (m *redisMeta) doGetAttr(ctx Context, inode Ino, attr *Attr) syscall.Errno {
   a, err := m.rdb.Get(ctx, m.inodeKey(inode)).Bytes()
   if err == nil {
      m.parseAttr(a, attr)
   }
   return errno(err)
}
```

##### **--------------------juicefs pkg/meta/redis.go结束--------------------**

返回结果

entries, readAt, err := fs.v.Readdir(ctx, Ino(in.NodeId), in.Size, int(in.Offset), in.Fh, true)

```
func (fs *fileSystem) ReadDirPlus(cancel <-chan struct{}, in *fuse.ReadIn, out *fuse.DirEntryList) fuse.Status {
   ctx := newContext(cancel, &in.InHeader)
   defer releaseContext(ctx)
   entries, readAt, err := fs.v.Readdir(ctx, Ino(in.NodeId), in.Size, int(in.Offset), in.Fh, true)
   ctx.start = readAt
   var de fuse.DirEntry
   for _, e := range entries {
      de.Ino = uint64(e.Inode)
      de.Name = string(e.Name)
      de.Mode = e.Attr.SMode()
      eo := out.AddDirLookupEntry(de)
      if eo == nil {
         break
      }
      if e.Attr.Full {
         fs.replyEntry(ctx, eo, e)
      } else {
         eo.Ino = uint64(e.Inode)
         eo.Generation = 1
      }
   }
   return fuse.Status(err)
}
```