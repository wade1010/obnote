

```javascript
func (n *nsLockMap) NewNSLock(lockers func() ([]dsync.NetLocker, string), volume string, paths ...string) RWLocker {
   opsID := mustGetUUID()
   drwmutex := dsync.NewDRWMutex(&dsync.Dsync{
      GetLockers: lockers,
   }, pathsJoinPrefix(volume, paths...)...)
   return &distLockInstance{drwmutex, opsID}
}
```



```javascript
获取分布式集群所有客户端
// NewDRWMutex - initializes a new dsync RW mutex.
func NewDRWMutex(clnt *Dsync, names ...string) *DRWMutex {
   restClnts, _ := clnt.GetLockers()
   return &DRWMutex{
      writeLocks: make([]string, len(restClnts)),
      Names:      names,
      clnt:       clnt,
   }
}
```





```javascript
获取读锁
func (dm *DRWMutex) GetLock(ctx context.Context, cancel context.CancelFunc, id, source string, opts Options) (locked bool) {

   isReadLock := false
   return dm.lockBlocking(ctx, cancel, id, source, isReadLock, opts)
}

获取写锁
func (dm *DRWMutex) RLock(id, source string) {

   isReadLock := true
   dm.lockBlocking(context.Background(), nil, id, source, isReadLock, Options{
      Timeout: drwMutexInfinite,
   })
}
```



准备获取锁

```javascript
// lockBlocking will try to acquire either a read or a write lock
//
// The function will loop using a built-in timing randomized back-off
// algorithm until either the lock is acquired successfully or more
// time has elapsed than the timeout value.
func (dm *DRWMutex) lockBlocking(ctx context.Context, lockLossCallback func(), id, source string, isReadLock bool, opts Options) (locked bool) {
   restClnts, _ := dm.clnt.GetLockers()

   r := rand.New(rand.NewSource(time.Now().UnixNano()))

   // Create lock array to capture the successful lockers
   locks := make([]string, len(restClnts))

   log("lockBlocking %s/%s for %#v: lockType readLock(%t), additional opts: %#v\n", id, source, dm.Names, isReadLock, opts)

   // Add total timeout
   ctx, cancel := context.WithTimeout(ctx, opts.Timeout)
   defer cancel()

   // Tolerance is not set, defaults to half of the locker clients.
   tolerance := len(restClnts) / 2

   // Quorum is effectively = total clients subtracted with tolerance limit
   quorum := len(restClnts) - tolerance
   if !isReadLock {
      // In situations for write locks, as a special case
      // to avoid split brains we make sure to acquire
      // quorum + 1 when tolerance is exactly half of the
      // total locker clients.
      if quorum == tolerance {
         quorum++
      }
   }

   tolerance = len(restClnts) - quorum

   for {
      select {
      case <-ctx.Done():
         return false
      default:
         // Try to acquire the lock.
         if locked = lock(ctx, dm.clnt, &locks, id, source, isReadLock, tolerance, quorum, dm.Names...); locked {
            dm.m.Lock()

            // If success, copy array to object
            if isReadLock {
               // Append new array of strings at the end
               dm.readersLocks = append(dm.readersLocks, make([]string, len(restClnts)))
               // and copy stack array into last spot
               copy(dm.readersLocks[len(dm.readersLocks)-1], locks[:])
            } else {
               copy(dm.writeLocks, locks[:])
            }

            dm.m.Unlock()
            log("lockBlocking %s/%s for %#v: granted\n", id, source, dm.Names)

            // Refresh lock continuously and cancel if there is no quorum in the lock anymore
            dm.startContinousLockRefresh(lockLossCallback, id, source, quorum)

            return locked
         }

         time.Sleep(time.Duration(r.Float64() * float64(lockRetryInterval)))
      }
   }
}
```



//刷新锁

```javascript
func (dm *DRWMutex) startContinousLockRefresh(lockLossCallback func(), id, source string, quorum int) {
   ctx, cancel := context.WithCancel(context.Background())

   dm.m.Lock()
   dm.cancelRefresh = cancel
   dm.m.Unlock()

   go func() {
      defer cancel()

      refreshTimer := time.NewTimer(drwMutexRefreshInterval)//drwMutexRefreshInterval = 10 * time.Second
      defer refreshTimer.Stop()

      for {
         select {
         case <-ctx.Done():
            return
         case <-refreshTimer.C:
            refreshTimer.Reset(drwMutexRefreshInterval)

            refreshed, err := refresh(ctx, dm.clnt, id, source, quorum)
            if err == nil && !refreshed {
               // Clean the lock locally and in remote nodes
               forceUnlock(ctx, dm.clnt, id)
               // Execute the caller lock loss callback
               if lockLossCallback != nil {
                  lockLossCallback()
               }
               return
            }
         }
      }
   }()
}
```



刷新锁具体实现

```javascript
func refresh(ctx context.Context, ds *Dsync, id, source string, quorum int) (bool, error) {
   restClnts, _ := ds.GetLockers()

   // Create buffered channel of size equal to total number of nodes.
   ch := make(chan refreshResult, len(restClnts))
   var wg sync.WaitGroup

   for index, c := range restClnts {
      wg.Add(1)
      // Send refresh request to all nodes
      go func(index int, c NetLocker) {
         defer wg.Done()

         if c == nil {
            ch <- refreshResult{offline: true}
            return
         }

         args := LockArgs{
            UID: id,
         }

         ctx, cancel := context.WithTimeout(ctx, drwMutexRefreshCallTimeout)
         defer cancel()

         refreshed, err := c.Refresh(ctx, args)
         if refreshed && err == nil {
            ch <- refreshResult{succeeded: true}
         } else {
            if err != nil {
               ch <- refreshResult{offline: true}
               log("dsync: Unable to call Refresh failed with %s for %#v at %s\n", err, args, c)
            } else {
               ch <- refreshResult{succeeded: false}
               log("dsync: Refresh returned false for %#v at %s\n", args, c)
            }
         }

      }(index, c)
   }

   // Wait until we have either
   //
   // a) received all refresh responses
   // b) received too many refreshed for quorum to be still possible
   // c) timed out
   //
   i, refreshFailed, refreshSucceeded := 0, 0, 0
   done := false

   for ; i < len(restClnts); i++ {
      select {
      case refresh := <-ch:
         if refresh.offline {
            continue
         }
         if refresh.succeeded {
            refreshSucceeded++
         } else {
            refreshFailed++
         }
         if refreshFailed > quorum {
            // We know that we are not going to succeed with refresh
            done = true
         }
      case <-ctx.Done():
         // Refreshing is canceled
         return false, ctx.Err()
      }

      if done {
         break
      }
   }

   refreshQuorum := refreshSucceeded >= quorum
   if !refreshQuorum {
      refreshQuorum = refreshFailed < quorum
   }

   // We may have some unused results in ch, release them async.
   go func() {
      wg.Wait()
      close(ch)
      for range ch {
      }
   }()

   return refreshQuorum, nil
}
```





具体获取锁

```javascript
// lock tries to acquire the distributed lock, returning true or false.
func lock(ctx context.Context, ds *Dsync, locks *[]string, id, source string, isReadLock bool, tolerance, quorum int, lockNames ...string) bool {
   for i := range *locks {
      (*locks)[i] = ""
   }

   restClnts, owner := ds.GetLockers()

   // Create buffered channel of size equal to total number of nodes.
   ch := make(chan Granted, len(restClnts))
   var wg sync.WaitGroup

   // Combined timeout for the lock attempt.
   ctx, cancel := context.WithTimeout(ctx, drwMutexAcquireTimeout)
   defer cancel()
   for index, c := range restClnts {
      wg.Add(1)
      // broadcast lock request to all nodes
      go func(index int, isReadLock bool, c NetLocker) {
         defer wg.Done()

         g := Granted{index: index}
         if c == nil {
            log("dsync: nil locker\n")
            ch <- g
            return
         }

         args := LockArgs{
            Owner:     owner,
            UID:       id,
            Resources: lockNames,
            Source:    source,
            Quorum:    quorum,
         }

         var locked bool
         var err error
         if isReadLock {
             //这里通过rpc获取对端上面的读锁
            if locked, err = c.RLock(context.Background(), args); err != nil {
               log("dsync: Unable to call RLock failed with %s for %#v at %s\n", err, args, c)
            }
         } else {
             //这里通过rpc获取对端上面的写锁
            if locked, err = c.Lock(context.Background(), args); err != nil {
               log("dsync: Unable to call Lock failed with %s for %#v at %s\n", err, args, c)
            }
         }
         if locked {
            g.lockUID = args.UID
         }
         ch <- g

      }(index, isReadLock, c)
   }

   // Wait until we have either  满足下面任何一个
   //
   // a) received all lock responses
   // b) received too many 'non-'locks for quorum to be still possible
   // c) timed out
   //
   i, locksFailed := 0, 0
   done := false

   for ; i < len(restClnts); i++ { // Loop until we acquired all locks
      select {
      case grant := <-ch:
         if grant.isLocked() {
            // Mark that this node has acquired the lock
            (*locks)[grant.index] = grant.lockUID
         } else {
            locksFailed++
            if locksFailed > tolerance {
               // We know that we are not going to get the lock anymore,
               // so exit out and release any locks that did get acquired
               done = true
            }
         }
      case <-ctx.Done():
         // Capture timedout locks as failed or took too long
         locksFailed++
         if locksFailed > tolerance {
            // We know that we are not going to get the lock anymore,
            // so exit out and release any locks that did get acquired
            done = true
         }
      }

      if done {
         break
      }
   }

   quorumLocked := checkQuorumLocked(locks, quorum) && locksFailed <= tolerance
   if !quorumLocked {
      log("Releasing all acquired locks now abandoned after quorum was not met\n")
      //通过RPC释放已经获取成功的客户端的锁
      if !releaseAll(ds, tolerance, owner, locks, isReadLock, restClnts, lockNames...) {
         log("Unable to release acquired locks, stale locks might be present\n")
      }
   }

   // We may have some unused results in ch, release them async.
   go func() {
      wg.Wait()
      close(ch)
      for grantToBeReleased := range ch {
         if grantToBeReleased.isLocked() {
            // release abandoned lock
            log("Releasing abandoned lock\n")
            sendRelease(ds, restClnts[grantToBeReleased.index],
               owner, grantToBeReleased.lockUID, isReadLock, lockNames...)
         }
      }
   }()

   return quorumLocked
}
```



每个客户端上都注册一批关于锁的router，然后后台有个线程维护本服务器上的锁

```javascript
// registerLockRESTHandlers - register lock rest router.
func registerLockRESTHandlers(router *mux.Router) {
   lockServer := &lockRESTServer{
      ll: newLocker(),
   }

   subrouter := router.PathPrefix(lockRESTPrefix).Subrouter()
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodHealth).HandlerFunc(httpTraceHdrs(lockServer.HealthHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodRefresh).HandlerFunc(httpTraceHdrs(lockServer.RefreshHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodLock).HandlerFunc(httpTraceHdrs(lockServer.LockHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodRLock).HandlerFunc(httpTraceHdrs(lockServer.RLockHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodUnlock).HandlerFunc(httpTraceHdrs(lockServer.UnlockHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodRUnlock).HandlerFunc(httpTraceHdrs(lockServer.RUnlockHandler))
   subrouter.Methods(http.MethodPost).Path(lockRESTVersionPrefix + lockRESTMethodForceUnlock).HandlerFunc(httpTraceAll(lockServer.ForceUnlockHandler))

   globalLockServer = lockServer.ll

   go lockMaintenance(GlobalContext)
}
```





```javascript
// lockMaintenance loops over all locks and discards locks
// that have not been refreshed for some time.
func lockMaintenance(ctx context.Context) {
   // Wait until the object API is ready
   // no need to start the lock maintenance
   // if ObjectAPI is not initialized.

   var objAPI ObjectLayer

   for {
      objAPI = newObjectLayerFn()
      if objAPI == nil {
         time.Sleep(time.Second)
         continue
      }
      break
   }

   if _, ok := objAPI.(*erasureServerPools); !ok {
      return
   }

   // Initialize a new ticker with 1 minute between each ticks.
   lkTimer := time.NewTimer(lockMaintenanceInterval)
   // Stop the timer upon returning.
   defer lkTimer.Stop()

   for {
      // Verifies every minute for locks held more than 2 minutes.
      select {
      case <-ctx.Done():
         return
      case <-lkTimer.C:
         // Reset the timer for next cycle.
         lkTimer.Reset(lockMaintenanceInterval)

         globalLockServer.expireOldLocks(lockValidityDuration)//lockValidityDuration = 20 * time.Second
      }
   }
}
```





```javascript
// Similar to removeEntry but only removes an entry only if the lock entry exists in map.
// Caller must hold 'l.mutex' lock.
func (l *localLocker) expireOldLocks(interval time.Duration) {
   l.mutex.Lock()
   defer l.mutex.Unlock()

   for _, lris := range l.lockMap {
      for _, lri := range lris {
         if time.Since(lri.TimeLastRefresh) > interval {
            l.removeEntry(lri.Name, dsync.LockArgs{Owner: lri.Owner, UID: lri.UID}, &lris)
         }
      }
   }
}
```





```javascript
// removeEntry based on the uid of the lock message, removes a single entry from the
// lockRequesterInfo array or the whole array from the map (in case of a write lock
// or last read lock)
func (l *localLocker) removeEntry(name string, args dsync.LockArgs, lri *[]lockRequesterInfo) bool {
   // Find correct entry to remove based on uid.
   for index, entry := range *lri {
      if entry.UID == args.UID && entry.Owner == args.Owner {
         if len(*lri) == 1 {
            // Remove the write lock.
            delete(l.lockMap, name)
         } else {
            // Remove the appropriate read lock.
            *lri = append((*lri)[:index], (*lri)[index+1:]...)
            l.lockMap[name] = *lri
         }
         return true
      }
   }

   // None found return false, perhaps entry removed in previous run.
   return false
}
```





总结下：

某个rpc客户端获取锁：

容忍失败数和仲裁数

默认容忍失败数=所有rpc客户端数量/2  向下取余

如果是获取读锁，仲裁数=所有rpc客户端数量-容忍失败数

如果是获取写锁,如果仲裁数等于容忍失败数，则将仲裁数加1，新的容忍失败数就要减1

然后for循环所有rpc客户端,并发获取对应的锁

有以下3种情况

1、获取所有锁 

2、获取锁失败数>容忍失败数

3、超时



如果获取成功的锁数量>=仲裁数且获取失败的锁数量<=容忍失败数

如果不满足，则循环获获取成功的rpc客户端调用释放锁

后台协程释放结果中未被使用的成功获取锁的服务器上的锁（因为有可能是 获取锁失败数>容忍失败数 则后面就不需要继续使用了，遗留下来的就需要在这释放）



获取到最终的锁之后，会开启个后台协程每10秒续锁，续锁成功的话就会更新TimeLastRefresh为当前时间、续锁失败时循环所有rpc客户端调用释放锁且调用上层丢失锁的回调函数



所有rpc服务端：

注册锁相关的rpc 方法，同时启动后台协程维护本服务器上的锁，每60秒检查下本地锁上面的TimeLastRefresh-当前时间是否大于20秒。如果大于，就删除本地上面的锁。



如果获取锁成功过的节点宕机，最多20秒别的服务器获取不到相同的锁，可以容忍

假如是5个节点，某个节点获取锁成功，但是只是其中3个节点获取成功，3个中一般肯定是有自己的，剩余2个中假如重启了/关闭，会导致续锁失败。会导致上层事务回滚。









