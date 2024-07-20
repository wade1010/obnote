挂载后，在桶的根目录执行命令行 ls

fuse库(file_system.go)::ServeOps 循环等待IO操作

fuse库(file_system.go)::ReadO（得到对应的fuse操作）

fuse库(file_system.go)::handleOp （协程处理）

```
func (s *fileSystemServer) handleOp(
   c *fuse.Connection,
   ctx context.Context,
   op interface{}) {
   defer s.opsInFlight.Done()

   // Dispatch to the appropriate method.
   var err error
   switch typed := op.(type) {
   default:
      err = fuse.ENOSYS

   case *fuseops.StatFSOp:
      err = s.fs.StatFS(ctx, typed)

   case *fuseops.LookUpInodeOp:
      err = s.fs.LookUpInode(ctx, typed)

   case *fuseops.GetInodeAttributesOp:
      err = s.fs.GetInodeAttributes(ctx, typed)

   case *fuseops.SetInodeAttributesOp:
      err = s.fs.SetInodeAttributes(ctx, typed)

   case *fuseops.ForgetInodeOp:
      err = s.fs.ForgetInode(ctx, typed)

   case *fuseops.BatchForgetOp:
      err = s.fs.BatchForget(ctx, typed)
      if err == fuse.ENOSYS {
         // Handle as a series of single-inode forget operations
         for _, entry := range typed.Entries {
            err = s.fs.ForgetInode(ctx, &fuseops.ForgetInodeOp{
               Inode:     entry.Inode,
               N:         entry.N,
               OpContext: typed.OpContext,
            })
            if err != nil {
               break
            }
         }
      }

   case *fuseops.MkDirOp:
      err = s.fs.MkDir(ctx, typed)

   case *fuseops.MkNodeOp:
      err = s.fs.MkNode(ctx, typed)

   case *fuseops.CreateFileOp:
      err = s.fs.CreateFile(ctx, typed)

   case *fuseops.CreateLinkOp:
      err = s.fs.CreateLink(ctx, typed)

   case *fuseops.CreateSymlinkOp:
      err = s.fs.CreateSymlink(ctx, typed)

   case *fuseops.RenameOp:
      err = s.fs.Rename(ctx, typed)

   case *fuseops.RmDirOp:
      err = s.fs.RmDir(ctx, typed)

   case *fuseops.UnlinkOp:
      err = s.fs.Unlink(ctx, typed)

   case *fuseops.OpenDirOp:
      err = s.fs.OpenDir(ctx, typed)

   case *fuseops.ReadDirOp:
      err = s.fs.ReadDir(ctx, typed)

   case *fuseops.ReleaseDirHandleOp:
      err = s.fs.ReleaseDirHandle(ctx, typed)

   case *fuseops.OpenFileOp:
      err = s.fs.OpenFile(ctx, typed)

   case *fuseops.ReadFileOp:
      err = s.fs.ReadFile(ctx, typed)

   case *fuseops.WriteFileOp:
      err = s.fs.WriteFile(ctx, typed)

   case *fuseops.SyncFileOp:
      err = s.fs.SyncFile(ctx, typed)

   case *fuseops.FlushFileOp:
      err = s.fs.FlushFile(ctx, typed)

   case *fuseops.ReleaseFileHandleOp:
      err = s.fs.ReleaseFileHandle(ctx, typed)

   case *fuseops.ReadSymlinkOp:
      err = s.fs.ReadSymlink(ctx, typed)

   case *fuseops.RemoveXattrOp:
      err = s.fs.RemoveXattr(ctx, typed)

   case *fuseops.GetXattrOp:
      err = s.fs.GetXattr(ctx, typed)

   case *fuseops.ListXattrOp:
      err = s.fs.ListXattr(ctx, typed)

   case *fuseops.SetXattrOp:
      err = s.fs.SetXattr(ctx, typed)

   case *fuseops.FallocateOp:
      err = s.fs.Fallocate(ctx, typed)
   }

   c.Reply(ctx, err)
}
```

fuse库(file_system.go)::ReadDir  (是 ReadDirOp)

goofys代码(panic_logger.go)::ReadDir

goofys代码(goofys.go)::ReadDir

goofys代码(dir.go)::ReadDir

goofys代码(dir.go)::listObjects

goofys代码(dir.go)::listBlobsSafe

goofys代码(backend_s3.go)::ListBlobs

goofys代码(backend_s3.go)::ListObjectsV2

aws sdk代码(api.go)::ListObjects

fuse库(file_system.go)::ReadDirOp  (第二次)

fuse库(file_system.go)::GetInodeAttributesOp   （这一步，在终端ls显示了结果）

fuse库(file_system.go)::ReleaseDirHandlerOp

每个OP结尾

```
func (c *Connection) Reply(ctx context.Context, opErr error) {.....}
```

```
// Send the reply to the kernel, if one is required.
noResponse := c.kernelResponse(outMsg, inMsg.Header().Unique, op, opErr)
```