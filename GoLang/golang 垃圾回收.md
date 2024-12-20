#### For-learning-Go-Tutorial
在理解垃圾回收之前,我们先理解一下Cache 和 Buffer，这两个都是缓存，这两者之间有什么区别呢？

buffer：缓冲

用于存储速度不同步的设备或优先级不同的设备之间传输数据；通过buffer可以减少进程间通信需要等待的时间，当存储速度快的设备与存储速度慢的设备进行通信时，存储慢的数据先把数据存放到buffer，达到一定程度存储快的设备再读取buffer的数据，在此期间存储快的设备CPU可以做其他的事情。

```markdown
A buffer is something that has yet to be "written" to disk.
```
cache：缓存

是高速缓存，是位于CPU和主内存之间的容量较小但速度很快的存储器，因为CPU的速度远远高于主内存的速度，CPU从内存中读取数据需等待很长的时间，而  Cache保存着CPU刚用过的数据或循环使用的部分数据，这时从Cache中读取数据会更快，减少了CPU等待的时间，提高了系统的性能。

```markdown
A cache is something that has been "read" from the disk and stored for later use.
```
buffer是用于存放将要输出到disk（块设备）的数据,进行流量整形，把突发的大数量较小规模的 I/O 整理成平稳的小数量较大规模的 I/O，以**减少响应次数**，而cache是存放从disk上读出的数据,为了弥补高速设备和低速设备的鸿沟而引入的中间层，最终起到**加快访问速度**的作用。。二者都是为提高IO性能而设计的。

而Go标准库Buffer是一个可变大小的字节缓冲区,可以用Wirte和Read方法操作它.

#### Golang 垃圾回收发展史

通常在编程中的垃圾指内存中不再使用的内存区域，自动发现与释放这种内存区域的过程就是垃圾回收。

内存资源是有限的，而垃圾回收可以让内存重复使用，并且减轻开发者对内存管理的负担，减少程序中的内存问题。


我们透过这个来看下Go垃圾回收发展史:

* go1.1，提高效率和垃圾回收精确度。

* go1.3，提高了垃圾回收的精确度。

* go1.4，之前版本的runtime大部分是使用C写的，这个版本大量使用Go进行了重写，让GC有了扫描stack的能力，进一步提高了垃圾回收的精确度。

* go1.5，目标是降低GC延迟，采用了并发标记和并发清除，三色标记，write barrier，以及实现了更好的回收器调度，设计文档1，文档2，以及这个版本的[Go talk]。

* go1.6，小优化，当程序使用大量内存时，GC暂停时间有所降低。

* go1.7，小优化，当程序有大量空闲goroutine，stack大小波动比较大时，GC暂停时间有显著降低。

* go1.8，write barrier切换到hybrid write barrier，以消除STW中的re-scan，把STW的最差情况降低到50us，设计文档。

混合屏障的优势在于它允许堆栈扫描永久地使堆栈变黑（没有STW并且没有写入堆栈的障碍），这完全消除了堆栈重新扫描的需要，从而消除了对堆栈屏障的需求。重新扫描列表。特别是堆栈障碍在整个运行时引入了显着的复杂性，并且干扰了来自外部工具（如GDB和基于内核的分析器）的堆栈遍历。

此外，与Dijkstra风格的写屏障一样，混合屏障不需要读屏障，因此指针读取是常规的内存读取; 它确保了进步，因为物体单调地从白色到灰色再到黑色。

混合屏障的缺点很小。它可能会导致更多的浮动垃圾，因为它会在标记阶段的任何时刻保留从根（堆栈除外）可到达的所有内容。然而，在实践中，当前的Dijkstra障碍可能几乎保留不变。混合屏障还禁止某些优化：特别是，如果Go编译器可以静态地显示指针是nil，则Go编译器当前省略写屏障，但是在这种情况下混合屏障需要写屏障。这可能会略微增加二进制大小。

* go1.9，提升指标主要是:

1. 过去 `runtime.GC`, `debug.SetGCPercent`, 和 `debug.FreeOSMemory`都不能触发并发GC，他们触发的GC都是阻塞的，go1.9可以了，变成了在垃圾回收之前只阻塞调用GC的goroutine。

2. `debug.SetGCPercent`只在有必要的情况下才会触发GC。

* go.1.10，小优化，加速了GC，程序应当运行更快一点点。

* go1.12，显著提高了堆内存存在大碎片情况下的sweeping性能，能够降低GC后立即分配内存的延迟。


#### Go垃圾回收主要算法

Go语言提供的一个变量GOGC，用来对GC进行控制。该变量表示：最近一次GC过后，总的heap内存比所有可达节点所占用heap内存 大的百分比。如果GOGC=100则表示最近一次GC过后，总的heap内存比所有可达节点所占用heap内存大100%，即总heap内存是可达节点内存的2倍。 

该值越大，则GC速度越快，但程序占用的内存较大，GC效果相对不明显。反之，则GC对内存的清理效果明显，但往往需要更多的时间。

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190751236.jpg)

GC 算法有四种:
* 引用计数（reference counting）
* 标记-清除（mark & sweep）
* 节点复制（Copying Garbage Collection）
* 分代收集（Generational Garbage Collection）。

#### 引用计数（reference counting）

引用计数的思想：每个单元维护一个域，保存其它单元指向它的引用数量（类似有向图的入度）。当引用数量为0时，将其回收。引用计数是渐进式的，能够将内存管理的开销分布到整个程序之中。C++ 的 share_ptr 使用的就是引用计算方法。

引用计数算法实现一般是把所有的单元放在一个单元池里，比如类似 free list。这样所有的单元就被串起来了，就可以进行引用计数了。新分配的单元计数值被设置为 1（注意不是 0，因为申请一般都说 ptr = new object 这种）。每次有一个指针被设为指向该单元时，该单元的计数值加 1；而每次删除某个指向它的指针时，它的计数值减 1。
当其引用计数为 0 的时候，该单元会被进行回收。虽然这里说的比较简单，实现的时候还是有很多细节需要考虑，比如删除某个单元的时候，那么它指向的所有单元都需要对引用计数减 1。

* 优点

1. 渐进式。内存管理与用户程序的执行交织在一起，将 GC 的代价分散到整个程序。不像标记-清扫算法需要 STW (Stop The World，GC 的时候挂起用户程序)。

2. 算法易于实现。

3. 内存单元能够很快被回收。相比于其他垃圾回收算法，堆被耗尽或者达到某个阈值才会进行垃圾回收。


* 缺点

1. 原始的引用计数不能处理循环引用。大概这是被诟病最多的缺点了。不过针对这个问题，也除了很多解决方案，比如强引用等。

2. 维护引用计数降低运行效率。内存单元的更新删除等都需要维护相关的内存单元的引用计数，相比于一些追踪式的垃圾回收算法并不需要这些代价。

3. 单元池 free list 实现的话不是 cache-friendly 的，这样会导致频繁的 cache miss，降低程序运行效率。


#### 标记-清除（mark & sweep）

标记-清除算法是第一种自动内存管理，基于追踪的垃圾收集算法。算法思想在 70 年代就提出了，是一种非常古老的算法。内存单元并不会在变成垃圾立刻回收，而是保持不可达状态，直到到达某个阈值或者固定时间长度。这个时候系统会挂起用户程序，也就是 STW，转而执行垃圾回收程序。
垃圾回收程序对所有的存活单元进行一次全局遍历确定哪些单元可以回收。算法分两个部分：标记（mark）和清除（sweep）。标记阶段表明所有的存活单元，清除阶段将垃圾单元回收。

标记-清除算法的优点也就是基于追踪的垃圾回收算法具有的优点：避免了引用计数算法的缺点（不能处理循环引用，需要维护指针）。缺点也很明显，需要 STW。

三色标记算法是对标记阶段的改进，原理如下：

* 起初所有对象都是白色。
* 从根出发扫描所有可达对象，标记为灰色，放入待处理队列。
* 从队列取出灰色对象，将其引用对象标记为灰色放入队列，自身标记为黑色。
* 重复 3，直到灰色对象队列为空。此时白色对象即为垃圾，进行回收。

三色法标记主要是第一部分是扫描所有对象进行三色标记，标记为黑色、灰色和白色，标记完成后只有黑色和白色对象，黑色代表使用中对象，白色对象代表垃圾，灰色是白色过渡到黑色的中间临时状态，第二部分是清扫垃圾，即清理白色对象。

第一部分包含了栈扫描、标记和标记结束3个阶段。在栈扫描之前有2个重要的准备：STW（Stop The World）和开启写屏障（WB，Write Barrier）。

STW是Stop The World，指会暂停所有正在执行的用户线程/协程，进行垃圾回收的操作，在这之前会进行一些准备工作，比如开启Write Barrier，把全局变量，以及每个goroutine中的 Root对象 收集起来，Root对象是标记扫描的源头，可以从Root对象依次索引到使用中的对象,STW为垃圾对象的扫描和标记提供了必要的条件。

每个P都有一个 mcache ，每个 mcache 都有1个Span用来存放 TinyObject，TinyObject 都是不包含指针的对象，所以这些对象可以直接标记为黑色，然后关闭 STW。

每个P都有1个进行扫描标记的 goroutine，可以进行并发标记，关闭STW后，这些 goroutine 就变成可运行状态，接收 Go Scheduler 的调度，被调度时执行1轮标记，它负责第1部分任务：栈扫描、标记和标记结束。

栈扫描阶段就是把前面搜集的Root对象找出来，标记为黑色，然后把它们引用的对象也找出来，标记为灰色，并且加入到gcWork队列，gcWork队列保存了灰色的对象，每个灰色的对象都是一个Work。

后面可以进入标记阶段，它是一个循环，不断的从gcWork队列中取出work，所指向的对象标记为黑色，该对象指向的对象标记为灰色，然后加入队列，直到队列为空。
然后进入标记结束阶段，再次开启STW，不同的版本处理方式是不同的。

在Go1.7的版本是Dijkstra写屏障，这个写屏障只监控堆上指针数据的变动，由于成本原因，没有监控栈上指针的变动，由于应用goroutine和GC的标记goroutine都在运行，当栈上的指针指向的对象变更为白色对象时，这个白色对象应当标记为黑色，需要再次扫描全局变量和栈，以免释放这类不该释放的对象。

在Go1.8及以后的版本引入了混合写屏障，这个写屏障依然不监控栈上指针的变动，但是它的策略，使得无需再次扫描栈和全局变量，但依然需要STW然后进行一些检查。

标记结束阶段的最后会关闭写屏障，然后关闭STW，唤醒熟睡已久的负责清扫垃圾的goroutine。

清扫goroutine是应用启动后立即创建的一个后台goroutine，它会立刻进入睡眠，等待被唤醒，然后执行垃圾清理：把白色对象挨个清理掉，清扫goroutine和应用goroutine是并发进行的。清扫完成之后，它再次进入睡眠状态，等待下次被唤醒。

最后执行一些数据统计和状态修改的工作，并且设置好触发下一轮GC的阈值，把GC状态设置为Off。

这写基本是Go垃圾回收的流程，但是在go1.12的源码稍微有一些不同，例如在标记结束后，就开始设置各种状态数据以及把GC状态成了Off，在开启一轮GC时，会自动检测当前是否处于Off，如果不是Off，则当前goroutine会调用清扫函数，帮助清扫goroutine一起清扫span，实际的Go垃圾回收流程以源码为准。

这里需要提下go的对象大小定义:

* 大对象是大于32KB的.
* 小对象16KB到32KB的.
* Tiny对象指大小在1Byte到16Byte之间并且不包含指针的对象.

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190751474.jpg)

三色标记的一个明显好处是能够让用户程序和 mark 并发的进行.

#### 节点复制（Copying Garbage Collection）

节点复制也是基于追踪的算法。其将整个堆等分为两个半区（semi-space），一个包含现有数据，另一个包含已被废弃的数据。节点复制式垃圾收集从切换（flip）两个半区的角色开始，然后收集器在老的半区，也就是 Fromspace 中遍历存活的数据结构，在第一次访问某个单元时把它复制到新半区，也就是 Tospace 中去。
在 Fromspace 中所有存活单元都被访问过之后，收集器在 Tospace 中建立一个存活数据结构的副本，用户程序可以重新开始运行了。

* 优点
1. 所有存活的数据结构都缩并地排列在 Tospace 的底部，这样就不会存在内存碎片的问题
2. 获取新内存可以简单地通过递增自由空间指针来实现。

* 缺点
1. 内存得不到充分利用，总有一半的内存空间处于浪费状态。

#### 分代收集（Generational Garbage Collection）

基于追踪的垃圾回收算法（标记-清扫、节点复制）一个主要问题是在生命周期较长的对象上浪费时间（长生命周期的对象是不需要频繁扫描的）。同时，内存分配存在这么一个事实 “most object die young”。基于这两点，分代垃圾回收算法将对象按生命周期长短存放到堆上的两个（或者更多）区域，这些区域就是分代（generation）。对于新生代的区域的垃圾回收频率要明显高于老年代区域。

分配对象的时候从新生代里面分配，如果后面发现对象的生命周期较长，则将其移到老年代，这个过程叫做 promote。随着不断 promote，最后新生代的大小在整个堆的占用比例不会特别大。收集的时候集中主要精力在新生代就会相对来说效率更高，STW 时间也会更短。

* 优点
1. 性能更优。

* 缺点
1. 实现复杂。

#### Golang GC

Golang GC 有两种,非增量式垃圾回收和增量式垃圾回收.

* 非增量式垃圾回收需要STW，在STW期间完成所有垃圾对象的标记，STW结束后慢慢的执行垃圾对象的清理。
              
* 增量式垃圾回收也需要STW，在STW期间完成部分垃圾对象的标记，然后结束STW继续执行用户线程，一段时间后再次执行STW再标记部分垃圾对象，这个过程会多次重复执行，直到所有垃圾对象标记完成。

GC算法有3大性能指标：吞吐量、最大暂停时间（最大的STW占时）、内存占用率。增量式垃圾回收不能提高吞吐量，但和非增量式垃圾回收相比，每次STW的时间更短，能够降低最大暂停时间，就是Go每个版本Release Note中提到的GC延迟、GC暂停时间。


然而Golang GC STW的时候减少最大暂停时间还有一种思路：并发垃圾回收，注意不是并行垃圾回收。

并行垃圾回收是每个核上都跑垃圾回收的线程，同时进行垃圾回收，这期间为STW，会暂停用户线程的执行。

并发垃圾回收是先STW找到所有的Root对象，然后结束STW，让垃圾标记线程和用户线程并发执行，垃圾标记完成后，再次开启STW，再次扫描和标记，以免释放使用中的内存。

并发垃圾回收和并行垃圾回收的重要区别就是不会持续暂停用户线程，并发垃圾回收也降低了STW的时间，达到了减少最大暂停时间的目的。



* 但是什么时候会触发 GC?

在堆上分配大于 32K byte 对象的时候进行检测此时是否满足垃圾回收条件，如果满足则进行垃圾回收。
```go
func mallocgc(size uintptr, typ *_type, needzero bool) unsafe.Pointer {
    ...
    shouldhelpgc := false
    // 分配的对象小于 32K byte
    if size <= maxSmallSize {
        ...
    } else {
        shouldhelpgc = true
        ...
    }
    ...
    // gcShouldStart() 函数进行触发条件检测
    if shouldhelpgc && gcShouldStart(false) {
        // gcStart() 函数进行垃圾回收
        gcStart(gcBackgroundMode, false)
    }
}
```

上面是自动垃圾回收，还有一种是主动垃圾回收，通过调用 runtime.GC()，这是阻塞式的。
```go
// GC runs a garbage collection and blocks the caller until the
// garbage collection is complete. It may also block the entire
// program.
func GC() {
    gcStart(gcForceBlockMode, false)
}
```

* GC 触发条件

GC有3种触发方式：

1. 辅助GC

在分配内存时，会判断当前的Heap内存分配量是否达到了触发一轮GC的阈值（每轮GC完成后，该阈值会被动态设置），如果超过阈值，则启动一轮GC。

2. 调用runtime.GC()强制启动一轮GC。

3. sysmon是运行时的守护进程，当超过 forcegcperiod (2分钟)没有运行GC会启动一轮GC。


forceTrigger 是 forceGC 的标志,后面意思是当前堆上的活跃对象大于我们初始化时候设置的 GC 触发阈值。在 malloc 以及 free 的时候 heap_live 会一直进行更新，这里就不再展开了。

```go
// gcShouldStart returns true if the exit condition for the _GCoff
// phase has been met. The exit condition should be tested when
// allocating.
//
// If forceTrigger is true, it ignores the current heap size, but
// checks all other conditions. In general this should be false.
func gcShouldStart(forceTrigger bool) bool {
    return gcphase == _GCoff && (forceTrigger || memstats.heap_live >= memstats.gc_trigger) && memstats.enablegc && panicking == 0 && gcpercent >= 0
}

//初始化的时候设置 GC 的触发阈值
func gcinit() {
    _ = setGCPercent(readgogc())
    memstats.gc_trigger = heapminimum
    ...
}
// 启动的时候通过 GOGC 传递百分比 x
// 触发阈值等于 x * defaultHeapMinimum (defaultHeapMinimum 默认是 4M)
func readgogc() int32 {
    p := gogetenv("GOGC")
    if p == "off" {
        return -1
    }
    if n, ok := atoi32(p); ok {
        return n
    }
    return 100
}
```

* forcegc

自动检测和用户主动调用, 除此之外 Golang 本身还会对运行状态进行监控，如果超过两分钟没有 GC，则触发 GC。监控函数是 sysmon()，在主 goroutine 中启动。

```go
// The main goroutine
func main() {
    ...
    systemstack(func() {
      	newm(sysmon, nil)
    })
}
// Always runs without a P, so write barriers are not allowed.
func sysmon() {
    ...
    for {
        now := nanotime()
        unixnow := unixnanotime()
      	
        lastgc := int64(atomic.Load64(&memstats.last_gc))
        if gcphase == _GCoff && lastgc != 0 && unixnow-lastgc > forcegcperiod && atomic.Load(&forcegc.idle) != 0 {
            lock(&forcegc.lock)
            forcegc.idle = 0
            forcegc.g.schedlink = 0
            injectglist(forcegc.g)	// 将 forcegc goroutine 加入 runnable queue
            unlock(&forcegc.lock)
        }
    }
}

var forcegcperiod int64 = 2 * 60 *1e9	//两分钟
```

*  垃圾回收的主要流程

为什么需要三色标记？

三色标记的目的，主要是利用Tracing GC(Tracing GC 是垃圾回收的一个大类，另外一个大类是引用计数)做增量式垃圾回收，降低最大暂停时间。原生Tracing GC只有黑色和白色，没有中间的状态，这就要求GC扫描过程必须一次性完成，得到最后的黑色和白色对象。在前面增量式GC中介绍到了，这种方式会存在较大的暂停时间。

三色标记增加了中间状态灰色，增量式GC运行过程中，应用线程的运行可能改变了对象引用树，只要让黑色对象直接引用白色对象，GC就可以增量式的运行，减少停顿时间。


什么是三色标记？


三色标记，通过字面意思我们就可以知道它由3种颜色组成：

1. 黑色 Black：表示对象是可达的，即使用中的对象，黑色是已经被扫描的对象。

2. 灰色 Gary：表示被黑色对象直接引用的对象，但还没对它进行扫描。

3. 白色 White：白色是对象的初始颜色，如果扫描完成后，对象依然还是白色的，说明此对象是垃圾对象。


三色标记规则：黑色不能指向白色对象。即黑色可以指向灰色，灰色可以指向白色。


三色标记法，主要流程如下：

1. 初始所有对象被标记为白色。

2. 从 root 开始找到所有可达对象,标记为灰色,放入待处理队列。

3. 遍历灰色对象队列,将其引用对象标记为灰色放入待处理队列,自身标记为黑色。

4. 处理完灰色对象队列,直到没有灰色对象。

5. 剩余白色对象为垃圾对象,执行清扫工作。


详细的过程如下图所示:

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190751728.jpg)

这里需要解释下：

1. 首先从 root 开始遍历，root 包括全局指针和 goroutine 栈上的指针。

2. mark 有两个过程。第一是从 root 开始遍历，标记为灰色。遍历灰色队列。第二re-scan 全局指针和栈。因为 mark 和用户程序是并行的，所以在过程 1 的时候可能会有新的对象分配，这个时候就需要通过写屏障（write barrier）记录下来。re-scan 再完成检查一下。

3. Stop The World 有两个过程。第一个是 GC 将要开始的时候，这个时候主要是一些准备工作，比如 enable write barrier。第二个过程就是上面提到的 re-scan 过程。如果这个时候没有 stw，那么 mark 将无休止。

另外针对上图各个阶段对应 GCPhase 如下：

* Off: _GCoff
* Stack scan - Mark: _GCmark
* Mark termination: _GCmarktermination


####  写屏障 (write barrier)

垃圾回收中的 write barrier 可以理解为编译器在写操作时特意插入的一段代码，对应的还有 read barrier。

为什么需要 write barrier，很简单，对于和用户程序并发运行的垃圾回收算法，用户程序会一直修改内存，所以需要记录下来。

Golang 1.7 之前的 write barrier 使用的经典的 Dijkstra-style insertion write barrier [Dijkstra ‘78]， STW 的主要耗时就在 stack re-scan 的过程。自 1.8 之后采用一种混合的 write barrier 方式 （Yuasa-style deletion write barrier [Yuasa ‘90] 和 Dijkstra-style insertion write barrier [Dijkstra ‘78]）来避免 re-scan。

#### 标记

垃圾回收的代码主要集中在函数 gcStart() 中。

```go
// gcStart 是 GC 的入口函数，根据 gcMode 做处理。
// 1. gcMode == gcBackgroundMode（后台运行，也就是并行）, _GCoff -> _GCmark
// 2. 否则 GCoff -> _GCmarktermination，这个时候就是主动 GC 
func gcStart(mode gcMode, forceTrigger bool) {
    ...
}
```
* STW phase 1

在 GC 开始之前的准备工作。

```go
func gcStart(mode gcMode, forceTrigger bool) {
    ...
    //在后台启动 mark worker 
    if mode == gcBackgroundMode {
        gcBgMarkStartWorkers()
    }
    ...
    // Stop The World
    systemstack(stopTheWorldWithSema)
    ...
    if mode == gcBackgroundMode {
        // GC 开始前的准备工作

        //处理设置 GCPhase，setGCPhase 还会 enable write barrier
        setGCPhase(_GCmark)
      	
        gcBgMarkPrepare() // Must happen before assist enable.
        gcMarkRootPrepare()

        // Mark all active tinyalloc blocks. Since we're
        // allocating from these, they need to be black like
        // other allocations. The alternative is to blacken
        // the tiny block on every allocation from it, which
        // would slow down the tiny allocator.
        gcMarkTinyAllocs()
      	
        // Start The World
        systemstack(startTheWorldWithSema)
    } else {
        ...
    }
}
```
*  Mark

Mark 阶段是并行的运行，通过在后台一直运行 mark worker 来实现。

```go
func gcStart(mode gcMode, forceTrigger bool) {
    ...
    //在后台启动 mark worker 
    if mode == gcBackgroundMode {
        gcBgMarkStartWorkers()
    }
}

func gcBgMarkStartWorkers() {
    // Background marking is performed by per-P G's. Ensure that
    // each P has a background GC G.
    for _, p := range &allp {
        if p == nil || p.status == _Pdead {
            break
        }
        if p.gcBgMarkWorker == 0 {
            go gcBgMarkWorker(p)
            notetsleepg(&work.bgMarkReady, -1)
            noteclear(&work.bgMarkReady)
        }
    }
}
// gcBgMarkWorker 是一直在后台运行的，大部分时候是休眠状态，通过 gcController 来调度
func gcBgMarkWorker(_p_ *p) {
    for {
        // 将当前 goroutine 休眠，直到满足某些条件
        gopark(...)
        ...
        // mark 过程
        systemstack(func() {
        // Mark our goroutine preemptible so its stack
        // can be scanned. This lets two mark workers
        // scan each other (otherwise, they would
        // deadlock). We must not modify anything on
        // the G stack. However, stack shrinking is
        // disabled for mark workers, so it is safe to
        // read from the G stack.
        casgstatus(gp, _Grunning, _Gwaiting)
        switch _p_.gcMarkWorkerMode {
        default:
            throw("gcBgMarkWorker: unexpected gcMarkWorkerMode")
        case gcMarkWorkerDedicatedMode:
            gcDrain(&_p_.gcw, gcDrainNoBlock|gcDrainFlushBgCredit)
        case gcMarkWorkerFractionalMode:
            gcDrain(&_p_.gcw, gcDrainUntilPreempt|gcDrainFlushBgCredit)
        case gcMarkWorkerIdleMode:
            gcDrain(&_p_.gcw, gcDrainIdle|gcDrainUntilPreempt|gcDrainFlushBgCredit)
        }
        casgstatus(gp, _Gwaiting, _Grunning)
        })
        ...
    }
}
```
Mark 阶段的标记代码主要在函数 gcDrain() 中实现。

```go
// gcDrain scans roots and objects in work buffers, blackening grey
// objects until all roots and work buffers have been drained.
func gcDrain(gcw *gcWork, flags gcDrainFlags) {
    ...	
    // Drain root marking jobs.
    if work.markrootNext < work.markrootJobs {
        for !(preemptible && gp.preempt) {
            job := atomic.Xadd(&work.markrootNext, +1) - 1
            if job >= work.markrootJobs {
                break
            }
            markroot(gcw, job)
            if idle && pollWork() {
                goto done
            }
        }
    }
  	
    // 处理 heap 标记
    // Drain heap marking jobs.
    for !(preemptible && gp.preempt) {
        ...
        //从灰色列队中取出对象
        var b uintptr
        if blocking {
            b = gcw.get()
        } else {
            b = gcw.tryGetFast()
            if b == 0 {
                b = gcw.tryGet()
            }
        }
        if b == 0 {
            // work barrier reached or tryGet failed.
            break
        }
        //扫描灰色对象的引用对象，标记为灰色，入灰色队列
        scanobject(b, gcw)
    }
}
```
* Mark termination (STW phase 2)

mark termination 阶段会 stop the world。函数实现在 gcMarkTermination()。

```go
func gcMarkTermination() {
    // World is stopped.
    // Run gc on the g0 stack. We do this so that the g stack
    // we're currently running on will no longer change. Cuts
    // the root set down a bit (g0 stacks are not scanned, and
    // we don't need to scan gc's internal state).  We also
    // need to switch to g0 so we can shrink the stack.
    systemstack(func() {
        gcMark(startTime)
        // Must return immediately.
        // The outer function's stack may have moved
        // during gcMark (it shrinks stacks, including the
        // outer function's stack), so we must not refer
        // to any of its variables. Return back to the
        // non-system stack to pick up the new addresses
        // before continuing.
    })
    ...
}
```

#### 清扫

```go
func gcSweep(mode gcMode) {
    ...
    //阻塞式
    if !_ConcurrentSweep || mode == gcForceBlockMode {
        // Special case synchronous sweep.
        ...
        // Sweep all spans eagerly.
        for sweepone() != ^uintptr(0) {
            sweep.npausesweep++
        }
        // Do an additional mProf_GC, because all 'free' events are now real as well.
        mProf_GC()
        mProf_GC()
        return
    }
  	
    // 并行式
    // Background sweep.
    lock(&sweep.lock)
    if sweep.parked {
        sweep.parked = false
        ready(sweep.g, 0, true)
    }
    unlock(&sweep.lock)
}
```

并行式清扫，在 GC 初始化的时候就会启动 bgsweep()，然后在后台一直循环

```go
func bgsweep(c chan int) {
    sweep.g = getg()

    lock(&sweep.lock)
    sweep.parked = true
    c <- 1
    goparkunlock(&sweep.lock, "GC sweep wait", traceEvGoBlock, 1)

    for {
        for gosweepone() != ^uintptr(0) {
            sweep.nbgsweep++
            Gosched()
        }
        lock(&sweep.lock)
        if !gosweepdone() {
            // This can happen if a GC runs between
            // gosweepone returning ^0 above
            // and the lock being acquired.
            unlock(&sweep.lock)
            continue
        }
        sweep.parked = true
        goparkunlock(&sweep.lock, "GC sweep wait", traceEvGoBlock, 1)
    }
}

func gosweepone() uintptr {
    var ret uintptr
    systemstack(func() {
        ret = sweepone()
    })
    return ret
}

```
不管是阻塞式还是并行式，都是通过 sweepone()函数来做清扫工作的.内存管理都是基于 span 的，mheap_ 是一个全局的变量，所有分配的对象都会记录在 mheap_ 中。在标记的时候，我们只要找到对对象对应的 span 进行标记，清扫的时候扫描 span，没有标记的 span 就可以回收了。

```go
// sweeps one span
// returns number of pages returned to heap, or ^uintptr(0) if there is nothing to sweep
func sweepone() uintptr {
    ...
    for {
        s := mheap_.sweepSpans[1-sg/2%2].pop()
        ...
        if !s.sweep(false) {
            // Span is still in-use, so this returned no
            // pages to the heap and the span needs to
            // move to the swept in-use list.
            npages = 0
        }
    }
}

// Sweep frees or collects finalizers for blocks not marked in the mark phase.
// It clears the mark bits in preparation for the next GC round.
// Returns true if the span was returned to heap.
// If preserve=true, don't return it to heap nor relink in MCentral lists;
// caller takes care of it.
func (s *mspan) sweep(preserve bool) bool {
    ...
}
```


#### GC调节参数

Go垃圾回收为了保证使用的简洁性，只提供了一个参数GOGC。GOGC代表了占用中的内存增长比率，达到该比率时应当触发1次GC，该参数可以通过环境变量设置。

GOGC参数取值范围为0~100，默认值是100，单位是百分比。

假如当前heap占用内存为4MB，GOGC = 75，

```go

4 * (1+75%) = 7MB

```

等heap占用内存大小达到7MB时会触发1轮GC。

GOGC还有2个特殊值：

1. "off" : 代表关闭GC

2. 0 : 代表持续进行垃圾回收，只用于调试

#### 其他

* gcWork

每个 P 上都有一个 gcw 用来管理灰色对象（get 和 put），gcw 的结构就是 gcWork。gcWork 中的核心是 wbuf1 和 wbuf2，里面存储就是灰色对象，或者说是 work（下面就全部统一叫做 work）。
```go
type p struct {
    ...
    gcw gcWork
}

type gcWork struct {
    // wbuf1 and wbuf2 are the primary and secondary work buffers.
    wbuf1, wbuf2 wbufptr
  
    // Bytes marked (blackened) on this gcWork. This is aggregated
    // into work.bytesMarked by dispose.
    bytesMarked uint64

    // Scan work performed on this gcWork. This is aggregated into
    // gcController by dispose and may also be flushed by callers.
    scanWork int64
}
```

既然每个 P 上有一个 work buffer，那么是不是还有一个全局的 work list 呢？是的。通过在每个 P 上绑定一个 work buffer 的好处和 cache 一样，不需要加锁。

```go
var work struct {
    full  uint64                   // lock-free list of full blocks workbuf
    empty uint64                   // lock-free list of empty blocks workbuf
    pad0  [sys.CacheLineSize]uint8 // prevents false-sharing between full/empty and nproc/nwait
    ...
}
```

那么为什么使用两个 work buffer （wbuf1 和 wbuf2）呢？例如我现在要 get 一个 work 出来，先从 wbuf1 中取，wbuf1 为空的话则与 wbuf2 swap 再 get。在其他时间将 work buffer 中的 full 或者 empty buffer 移到 global 的 work 中。
这样的好处在于，在 get 的时候去全局的 work 里面取（多个 goroutine 去取会有竞争）。这里有趣的是 global 的 work list 是 lock-free 的，通过原子操作 cas 等实现。下面列举几个函数看一下 gcWrok。

* 初始化
```go
func (w *gcWork) init() {
    w.wbuf1 = wbufptrOf(getempty())
    wbuf2 := trygetfull()
    if wbuf2 == nil {
        wbuf2 = getempty()
    }
    w.wbuf2 = wbufptrOf(wbuf2)
}
```

* put

```go
// put enqueues a pointer for the garbage collector to trace.
// obj must point to the beginning of a heap object or an oblet.
func (w *gcWork) put(obj uintptr) {
    wbuf := w.wbuf1.ptr()
    if wbuf == nil {
        w.init()
        wbuf = w.wbuf1.ptr()
        // wbuf is empty at this point.
    } else if wbuf.nobj == len(wbuf.obj) {
        w.wbuf1, w.wbuf2 = w.wbuf2, w.wbuf1
        wbuf = w.wbuf1.ptr()
        if wbuf.nobj == len(wbuf.obj) {
            putfull(wbuf)
            wbuf = getempty()
            w.wbuf1 = wbufptrOf(wbuf)
            flushed = true
        }
    }

    wbuf.obj[wbuf.nobj] = obj
    wbuf.nobj++
}
```
* get 

```go
// get dequeues a pointer for the garbage collector to trace, blocking
// if necessary to ensure all pointers from all queues and caches have
// been retrieved.  get returns 0 if there are no pointers remaining.
//go:nowritebarrier
func (w *gcWork) get() uintptr {
    wbuf := w.wbuf1.ptr()
    if wbuf == nil {
        w.init()
        wbuf = w.wbuf1.ptr()
        // wbuf is empty at this point.
    }
    if wbuf.nobj == 0 {
        w.wbuf1, w.wbuf2 = w.wbuf2, w.wbuf1
        wbuf = w.wbuf1.ptr()
        if wbuf.nobj == 0 {
            owbuf := wbuf
            wbuf = getfull()
            if wbuf == nil {
                return 0
            }
            putempty(owbuf)
            w.wbuf1 = wbufptrOf(wbuf)
        }
    }

    // TODO: This might be a good place to add prefetch code

    wbuf.nobj--
    return wbuf.obj[wbuf.nobj]
}
```

#### 参考资料
* [Tracing Garbage Collection - wikipedia](https://en.wikipedia.org/wiki/Tracing_garbage_collection#Na.C3.AFve_mark-and-sweep)
* [On-the-fly Garbage Collection: an exercise in cooperation.](http://www.cs.utexas.edu/users/mckinley/395Tmm/talks/Mar-30-Dijkstra.pdf)
* [Garbage Collection](https://en.wikipedia.org/wiki/Garbage_collection_(computer_science))
* [Tracing Garbage Collection](https://en.wikipedia.org/wiki/Tracing_garbage_collection)
* [Copying Garbage Collection](https://www.youtube.com/watch?v=P1rU_9IB414)
* [Generational Garbage Collection](https://www.youtube.com/watch?v=pJHISaOW6Vc)
* [Golang Gc Talk](https://github.com/KeKe-Li/book/blob/master/Go/go-gc.pdf)
* [Eliminate Rescan](https://github.com/golang/proposal/blob/master/design/17503-eliminate-rescan.md)

#### License

This is free software distributed under the terms of the MIT license
