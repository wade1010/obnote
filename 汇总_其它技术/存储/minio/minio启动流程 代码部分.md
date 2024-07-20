1. main.go

1. server-main.go:serverMain(ctx *cli.Context)

1. Initialize globalConsoleSys system

1. Perform any self-tests,bitrotSelfTest() erasureSelfTest() compressSelfTest() 保证启动启动的时候这些功能必须是好的，才能保证程序正常执行。

1. Handle all server command args. serverHandleCmdArgs()

1. Handle common command args.   address，console-address，config-dir等主要参数就在这处理

1. Check and load TLS certificates.

1. Check and load Root CAs. 也是证书

1. Add the global public crts as part of global root CAs  

1. Register root CAs for remote ENVs

1. LookupEnv retrieves the value of the environment variable  环境变量

1. CreateServerEndpoints - validates and creates new endpoints from input args, supports both ellipses and without ellipses transparently.

1. add endpoints to endpointServerPools

1. GetLocalPeer，returns local peer value, returns globalMinioAddr for FS and Erasure mode. In case of distributed server return the first element from the set of peers which indicate that they are local. There is always one entry that is local even with repeated server endpoints.对于分布式服务器，返回xxxx的第一个元素，表明它们是本地的，即使有重复的服务器端点，也总是有一个条目是本地的

1. Handle all server environment vars. serverHandleEnvVars()

1. Set node name, only set for distributed setup，sets the node name if any after distributed setup has initialized

1. Initialize all help  命令行help输出的内容

1. Initialize all sub-systems，newAllSubsystems()

1. 如果是globalIsErasure模式启动

1. New global heal state  [newHealState - initialize global heal state management]

1. globalAllHealState = newHealState(true)     ， go hstate.periodicHealSeqsClean(GlobalContext)  Launch clean-up routine to remove this heal sequence (after it ends) from the global state after timeout has elapsed，启动清理例程以在超时后从全局状态删除此修复序列（结束后）

1. globalBackgroundHealState = newHealState(false)

1. Create new notification system and initialize notification targets，只有在分布式EC模式启动时，NotificationSys对象实例才有peerClients和allPeerClients

1. Create new bucket metadata system. returns a metadata with defaults

1. Create the bucket bandwidth monitor,returns a monitor with defaults

1. Create a new config system. with defaults

1. Create new IAM system.with defaults

1. Create new policy system.with defaults

1. Create new lifecycle system.with defaults

1. Create new bucket encryption subsystem.with defaults,Creates an empty in-memory bucket encryption configuration cache

1. Create new bucket object lock subsystem,returns initialized BucketObjectLockSys.[BucketObjectLockSys - map of bucket and retention configuration.]

1. Create new bucket quota subsystem,returns initialized BucketQuotaSys.[BucketQuotaSys - map of bucket and quota configuration.]

1. Create new bucket versioning subsystem.BucketVersioningSys类包含Enabled(bucket),Suspended(bucket),Get(bucket)等方法

1. Create new bucket replication subsytem,BucketTargetSys represents bucket targets subsystem

1. Create new ILM tier configuration subsystem,NewTierConfigMgr - creates new tier configuration manager,TierConfigMgr holds the collection of remote tiers configured in this deployment.      tier.go

1. 如果是globalIsDistErasure模式启动,Is distributed setup, error out if no certificates are found for HTTPS endpoints.判断下配置和启动模式是否相符合 

1. checkUpdate(),Check for updates and print a notification message 检查minio是否有可以更新的版本 

1. 如果是globalIsDistErasure模式启动,检查globalActiveCred是否合法，不符合赋值为默认证书

1. Set system resources to maximum.主要是最大文件打开数，最大内存

1. Configure server.   configureServerHandler(globalEndpoints),  returns final handler for the http server.

1. 如果是globalIsDistErasure模式启动, registerDistErasureRouters(router, endpointServerPools) [Initialize distributed NS lock.]

1. register storage rpc router

1. register peer rest router.

1. register bootstrap rest router.

1. register lock rest router.

1. Add Admin router, all APIs are enabled in server mode.    registerAdminRouter(router, true)   registerAdminRouter - Add handler functions for each service REST API routes. mc admin相关的命令所对应的接口就在这里。

1. Add server metrics router

1. Add STS router always.registerSTSRouter - registers AWS STS compatible APIs.   一种临时访问权限管理服务

1. Add API router.registers S3 compatible APIs.

1. router.Use(globalHandlers...)  这里一堆handler

1. globalHandlers:List of some generic handlers which are applied for all incoming requests.

1. 启动httpServer

1. 如果globalIsDistErasure && globalEndpoints.FirstLocal() ,validate the setup and configuration.

1. 创建objectLayer,newObject, err := newObjectLayer(GlobalContext, globalEndpoints),Initialize object layer with the supplied disks，For FS only, directly use the disk.

1. 如果globalIsErasure，则Enable background operations for erasure coding

1. initAutoHeal

1. start quick background healing,启动gomaxprocs/2个workers,Wait for heal requests and process them,可以HealBucket和HealObject

1. initHealMRF

1. initBackgroundExpiry(GlobalContext, newObject)

1. applyExpiryOnTransitionedObject()

1. expireTransitionedObject

1. expireObj,globalTierJournal.AddEntry(entry); objectAPI.DeleteObject(ctx, oi.Bucket, oi.Name, opts); Notify object deleted event.

1. expireRestoredObj,delete locally restored copy of object or object version from the source, while leaving metadata behind. The data on transitioned tier lies untouched and still accessible[从源文件中删除对象或对象版本的本地恢复副本，同时留下元数据。转换层上的数据未受影响，仍然可以访问]

1. applyExpiryOnNonTransitionedObjects()

1. objLayer.DeleteObject(ctx, obj.Bucket, obj.Name, opts);Notify object deleted event.

1. initServer

1. setObjectLayer(newObject)

1. handleEncryptedConfigBackend(newObject),Migrate all backend configs to encrypted backend configs, optionally handles rotating keys for encryption, if there is any retriable failure that shall be retried if there is an error.迁移所有后端配置到加密后端配置，可选地处理旋转密钥加密，如果有任何可检索的失败，应重试，如果有错误。

1. 如果上一步骤成功则initAllSubsystems(ctx, newObject)

1. 如果globalIsErasure模式启动，Limit to no more than 50 concurrent buckets；newObject.HealBucket(ctx, buckets[index].Name, madmin.HealOpts{Recreate: true})

1. initializes config system from config.json.  【./minio/.fsv2data/.minio.sys/config/config.json】

1. initConfig，Initialize and load config from remote etcd or local config directory

1. readServerConfig

1. go globalIAMSys.Init(GlobalContext, newObject)，Initialize users credentials and policies in background right after config has initialized，initializes config system by reading entries from config/iam

1. Initialize IAM store

1. newIAMObjectStore(objAPI)

1. newIAMEtcdStore()

1. 如果globalEtcdClient != nil,migrateIAMConfigsEtcdToEncrypted

1. Migrate IAM configuration, if necessary.

1. sys.store.loadAll(retryCtx, sys)，从store里面加载配置

1. Set up polling for expired accounts and credentials purging.设置对过期帐户和凭证清除的轮询。

1. go sys.store.watch(ctx, sys) Refresh IAMSys.

1. newIAMObjectStore(objAPI),每5分钟循环执行一次

1. newIAMEtcdStore()是利用etcd的watch机制

1. initDataScanner(GlobalContext, newObject)，initDataScanner will start the scanner in the background.There should only ever be one scanner running per cluster

1. Load current bloom cycle。nextBloomCycle := intDataUpdateTracker.current() + 1；current returns the current index.

1. br,err := objAPI.GetObjectNInfo(ctx, ".minio.sys/buckets", ".bloomcycle.bin", nil, http.Header{}, readLock, ObjectOptions{}),returns object info and a reader for object content

1. 如果br.ObjInfo.Size == 8 则赋值给nextBloomCycle

1. scannerTimer 默认是1分钟一次,

1. dataScannerSleepPerFolder，Time to wait between folders.是1毫秒

1. dataUsageUpdateDirCycles，Visit all folders every n cycles.  是16个cycle就扫描一次全部目录

1. for 循环执行，results := make(chan madmin.DataUsageInfo, 1)； go storeDataUsageInBackend(ctx, objAPI, results),storeDataUsageInBackend will store all objects sent on the gui channel until closed.就是把DataUsageInfo从channel里面读出来，然后写入到.minio.sys/buckets/.usage.json文件中

1. bf, err := globalNotificationSys.updateBloomFilter(ctx, nextBloomCycle)，updateBloomFilter will cycle all servers to the current index and return a merged bloom filter if a complete one can be retrieved.将所有服务器循环到当前索引，如果可以检索到完整的bloom过滤器，则返回合并的bloom过滤器。

1. Load initial state from local

1. bfr, err := intDataUpdateTracker.cycleFilter(ctx, req),cycleFilter will cycle the bloom filter to start recording to index y if not already.The response will contain a bloom filter starting at index x up to, but not including index y.If y is 0, the response will not update y, but return the currently recorded information from the oldest (unless 0, then it will be all) until and including current y.        cycleFilter将会循环bloom过滤器到开始记录到索引y（如果没有）。响应将包含一个从索引x开始直到但不包括索引y的bloom过滤器。如果y为0，响应将不更新y，而是返回当前记录的信息，从最早的（除非0，否则是全部）到当前y。

1. Loop through each index requested.     v := d.History.find(idx),

1. if v == nil {if d.Current.idx == idx {// Merge current.err := bf.Merge(d.Current.bf.BloomFilter)  ；  bfr.Complete = false(将bloom置为非完全在history中，也就是新请求)}}

1. v!=nil ,err := bf.Merge(v.bf.BloomFilter)

1. Merge the data from two Bloom Filters.

1. f.b.InPlaceUnion(g.b)

1. b.extendSetMaybe(compare.length - 1),extendSetMaybe adds additional words to incorporate new bits if needed，是否需要扩容

1. 如果发生了bloom过滤器合并,则nbf := intDataUpdateTracker.newBloomFilter()，returns a new bloom filter with default settings.

1. 循环sys.peerClients(其实就是以globalIsDistErasure模式启动)，每个都执行下方，其实就是调用远端的bf, err := intDataUpdateTracker.cycleFilter(ctx, req)方法(上方local中的代码)

1. serverBF, err := client.cycleServerBloomFilter(ctx, req)

1. respBody, err := client.callWithContext(ctx, "/cyclebloom", nil, &reader, -1)，调用远端的intDataUpdateTracker.cycleFilter(ctx, req)调

1. 检查一遍结果，if err != nil || !serverBF.Complete || bf == nil   如果err或bf等于nil或者serverBF.Complete为false(也就是bloom置为非完全在history中，也就是新请求)，则logger.LogOnceIf(ctx, err, fmt.Sprintf("host:%s, cycle:%d", client.host, current), client.cycleServerBloomFilter)【发送日志到相应的对端节点上】

1. 合并本地和对端节点的bloom过滤器

1. err = objAPI.NSScanner(ctx, bf, results, uint32(nextBloomCycle))，scan目标，将DataUsageInfo放入results这个channel中，供上方f步骤的storeDataUsageInBackend()消费

1. FS模式启动，即fs-v1.go实现objectLayer,NSScanner returns data usage stats of the current FS deployment

1. totalCache.load(ctx, fs, ".usage-cache.bin")   【Load bucket totals】

1. buckets, err := fs.ListBuckets(ctx)

1. if len(buckets) == 0 

1. totalCache.keepBuckets(buckets),这里就是删除所有缓存的意思// keepBuckets will keep only the buckets specified specified by delete all others.

1. updates <- totalCache.dui(dataUsageRoot, buckets) ，这里其实就是把一个默认DataUsageInfo放到results这个channel中

1. return返回

1. if len(buckets) > 0 

1. var root dataUsageEntry 使用默认值声明root对象；if r := totalCache.root(); r != nil {root.Children = r.Children} 如果缓存中有children则赋值给root对象，

1. 替换下totalCache里面的根目录,即d.Cache["/"] = root

1. totalCache.keepBuckets(buckets)，Delete all buckets that does not exist anymore.删除totalCache里面不存在的bucket对应的数据

1. for _, b := range buckets   循环每个bucket，然后Load bucket cache.

1.  		err := bCache.load(ctx, fs, "bk1/.usage-cache.bin"))  获取bucket目录下的缓存文件.usage-cache.bin

1.    bCache.Info.BloomFilter = totalCache.Info.BloomFilter    将全局bloomfilter赋值给该bucket

1.     cache, err := fs.scanBucket(ctx, b.Name, bCache),scanBucket scans a single bucket in FS mode.The updated cache for the bucket is returned.A partially updated bucket may be returned.

1. fs.scanBucket内代码，Load bucket info.

1. scanDataFolder

1.     case "", dataUsageRoot；return cache, errors.New("internal error: root scan attempted") 这个目录不能是 ""也不能是"/"

1.   Add disks for set healing.     if len(cache.Disks) > 0 ；objAPI, ok := newObjectLayerFn().(*erasureServerPools)；if ok {objAPI.GetDisksID(cache.Disks...)}  

1.       if len(cache.Info.BloomFilter) > 0   则把缓存的bloomfilter读取到当前的缓存中

1.    	root := dataUsageEntry{}；folder := cachedFolder{name: cache.Info.Name, objectHealProbDiv: 1}；

1.     	err := s.scanFolder(ctx, folder, &root)，scanFolder will scan the provided folder.Files found in the folders will be added to f.newCache.If final is provided folders will be put into f.newFolders or f.existingFolders.If final is not provided the folders found are returned from the function.扫描scanFolder方法,将扫描提供的文件夹，在文件夹中发现的文件将被添加到新的缓存，【由于有道云笔记不能继续缩进了，下方以“s.scanFolder的方法内”  开头】

1.  	s.scanFolder的方法内，for循环执行，existing, ok := f.oldCache.Cache[thisHash.Key()]【判断老的cache里面存不存在这些目录的缓存信息】

1.  	s.scanFolder的方法内，if !into.Compacted 如果没有统计信息没有压缩，则abandonedChildren = f.oldCache.findChildrenCopy(thisHash)

1.    	s.scanFolder的方法内，If there are lifecycle rules for the prefix, remove the filter.

1.  		s.scanFolder的方法内，If there are replication rules for the prefix, remove the filter.

1.  		s.scanFolder的方法内，Check if we can skip it due to bloom filter...    	if filter != nil && ok && existing.Compacted，// If folder isn't in filter and we have data, skip it completely.  if folder.name != dataUsageRoot && !filter.containsDir(folder.name)  ;if f.healObjectSelect == 0 || !thisHash.mod(f.oldCache.Info.NextCycle, f.healFolderInclude/folder.objectHealProbDiv) 如果满足则拷贝老cache里面的内容到新cache,f.newCache.copyWithChildren(&f.oldCache, thisHash, folder.parent);	f.updateCache.copyWithChildren(&f.oldCache, thisHash, folder.parent)  然后return跳出scanFolder这个方法

1.  		s.scanFolder的方法内，if f.healObjectSelect == 0 || !thisHash.mod(f.oldCache.Info.NextCycle, f.healFolderInclude/folder.objectHealProbDiv) 如果不满足则folder.objectHealProbDiv = f.healFolderInclude    // If probability was already scannerHealFolderInclude, keep it.

1.      s.scanFolder的方法内，scannerSleeper.Sleep(ctx, time.Millisecond)休息1ms

1.   	 s.scanFolder的方法内，readDirFn

1.  	readDirFn的方法内  读取目录获取 object      	into.addSizes(sz)	sz就是统计信息对象		into.Objects++ 

1.   	s.scanFolder的方法内， if foundObjects && globalIsErasure  则break跳出循环，// If we found an object in erasure mode, we skip subdirs (only datadirs)...

1.  	s.scanFolder的方法内，// If we have many subfolders, compact ourself.   Compact when this many subfolders in a single folder.     														if !into.Compacted && f.newCache.Info.Name != folder.name && len(existingFolders)+len(newFolders) >= 2500  则 into.Compacted = true			newFolders = append(newFolders, existingFolders...)			existingFolders = nil

1.  	s.scanFolder的方法内，	 // Transfer existing。 if !into.Compacted   for _, folder := range existingFolders {	f.updateCache.copyWithChildren(&f.oldCache,  hashPath(folder.name), folder.parent)}

```javascript
                         scanFolder := func(folder cachedFolder) {
            			if contextCanceled(ctx) {
            				return
            			}
            			dst := into
            			if !into.Compacted {
            				dst = &dataUsageEntry{Compacted: false}
            			}
            			if err := f.scanFolder(ctx, folder, dst); err != nil {
            				logger.LogIf(ctx, err)
            				return
            			}
            			if !into.Compacted {
            				h := dataUsageHash(folder.name)
            				into.addChild(h)
            				// We scanned a folder, optionally send update.
            				f.updateCache.deleteRecursive(h)
            				f.updateCache.copyWithChildren(&f.newCache, h, folder.parent)
            				f.sendUpdate()
            			}
            		}
```

1.   	s.scanFolder的方法内， // Scan new...  for _, folder := range newFolders {// Add new folders to the update tree so totals update for these. scanFolder(folder)}

1.   	s.scanFolder的方法内，// Scan existing...   scanFolder(folder)

1.  		s.scanFolder的方法内，// Scan for healing 		 if f.healObjectSelect == 0 || len(abandonedChildren) == 0 {

			// If we are not heal scanning, return now.

			break

		}

1.  	s.scanFolder的方法内，objAPI, ok := newObjectLayerFn().(*erasureServerPools)   	if !ok || len(f.disks) == 0 {

			break

		}

1.    后面还有很长的代码逻辑。 表明数据统计还是很复杂的，这里就不继续往下了

1.  	if cache.Info.LastUpdate.After(bCache.Info.LastUpdate)

1. cache.save(ctx, fs, path.Join(b.Name, ".usage-cache.bin"))  这就是保存整个桶的统计信息到.usage-cache.bin文件中 如 .fsv1data/.minio.sys/buckets/bk1/.usage-cache.bin

1.  	totalCache.save(ctx, fs, ".usage-cache.bin")  		 这就是保存全部桶的统计信息到.usage-cache.bin文件中 如 .fsv1data/.minio.sys/buckets/.usage-cache.bin

1.  	updates <- cloned.dui("/", buckets)  

1.  	enforceFIFOQuotaBucket(ctx, fs, b.Name, cloned.bucketUsageInfo(b.Name))  enforceFIFOQuota按FIFO顺序删除对象，直到删除了足够的对象，从而使bucket使用率在配额范围内

1. Check if the current bucket has quota restrictions, if not skip it   看是该桶否超过quota限制，不超过就跳过,直接返回

1.  	toFree = bui.Size - cfg.Quota  如果超过，就计算下超过多少个，toFree就是有多少个要删除

1.  	objectAPI.Walk(ctx, bucket, "", objInfoCh, ObjectOptions{WalkVersions: versioned}) // Walk through all objects   会把object放到objInfoCh这个channel中 

1. reuse the fileScorer used by disk cache to score entries by ModTime to find the oldest objects in bucket to delete. In the context of bucket quota enforcement - number of hits are irrelevant    重用磁盘缓存使用的 fileScorer，通过 ModTime 对条目进行评分，以找到要删除的存储桶中最旧的对象。在桶配额执行的上下文中，命中数量是无关的。

1.  	for obj := range objInfoCh {if obj.DeleteMarker {// Delete markers are automatically added for FIFO purge.      scorer.addFileWithObjInfo(obj, 1);continue;}  // skip objects currently under retention  if rcfg.LockEnabled && enforceRetentionForDeletion(ctx, obj){continue;}  scorer.addFileWithObjInfo(obj, 1)}

1.  	//If we saw less than quota we are good.    if scorer.seenBytes <= cfg.Quota {		return}   经过fileScorer之后再检查一遍是否超过桶配额

1. // Calculate how much we want to delete now.    toFreeNow := scorer.seenBytes - cfg.Quota

1. // We were less over quota than we thought. Adjust so we delete less.

	// If we are more over, leave it for the next run to pick up.

	if toFreeNow < toFree {

		if !scorer.adjustSaveBytes(int64(toFreeNow) - int64(toFree)) {  该算法就是检下我们到底需不需要删除

			// We got below or at quota.    低于或达到限额 就返回，即不需要删除

			return

		}

	}

1. //Deletes a list of objects.  objectAPI.DeleteObjects(ctx, bucket, objects, ObjectOptions{Versioned: versioned})

1.  Notify object deleted event.如果发生错误则发送事件通知

1. // Store new cycle...    nextBloomCycle++ ；objAPI.PutObject(ctx, ".minio.sys/buckets", ".bloomcycle.bin", NewPutObjReader(r), ObjectOptions{})

1. 如果是globalIsErasure模式启动

1. initBackgroundReplication

1. initBackgroundTransition

1. initTierDeletionJournal

1. if globalCacheConfig.Enabled   // initialize the new disk cache objects.    cacheAPI, err = newServerCacheObjects(GlobalContext, globalCacheConfig) ;		setCacheObjectLayer(cacheAPI)

1. printStartupMessage(getAPIEndpoints(), err)   // Prints the formatted startup message, if err is not nil then it prints additional information as well.

1. if globalBrowserEnabled globalConsoleSrv, err = initConsoleServer()  globalConsoleSrv.Serve()   如果启用browser 则启动console server



至此整个启动流程完毕。 最繁杂的就是 runDataScanner那一块了









































































