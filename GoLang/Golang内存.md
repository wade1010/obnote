## Go的对象在内存中是怎样分配的

Go中的内存分类并不像TCMalloc那样分成小、中、大对象，但是它的小对象里又细分了一个Tiny对象，Tiny对象指大小在1Byte到16Byte之间并且不包含指针的对象。

小对象和大对象只用大小划定，无其他区分。

大对象指大小大于32kb.小对象是在mcache中分配的，而大对象是直接从mheap分配的，从小对象的内存分配看起。

Go的内存分配原则:

Go在程序启动的时候，会先向操作系统申请一块内存（注意这时还只是一段虚拟的地址空间，并不会真正地分配内存），切成小块后自己进行管理。

申请到的内存块被分配了三个区域，在X64上分别是512MB，16GB，512GB大小。



arena区域就是我们所谓的堆区，Go动态分配的内存都是在这个区域，它把内存分割成8KB大小的页，一些页组合起来称为mspan。



bitmap区域标识arena区域哪些地址保存了对象，并且用4bit标志位表示对象是否包含指针、GC标记信息。bitmap中一个byte大小的内存对应arena区域中4个指针大小（指针大小为 8B ）的内存，所以bitmap区域的大小是512GB/(4*8B)=16GB。



![](https://gitee.com/hxc8/images7/raw/master/img/202407190751587.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190751855.jpg)



此外我们还可以看到bitmap的高地址部分指向arena区域的低地址部分，这里bitmap的地址是由高地址向低地址增长的。



spans区域存放mspan（是一些arena分割的页组合起来的内存管理基本单元，后文会再讲）的指针，每个指针对应一页，所以spans区域的大小就是512GB/8KB*8B=512MB。



除以8KB是计算arena区域的页数，而最后乘以8是计算spans区域所有指针的大小。创建mspan的时候，按页填充对应的spans区域，在回收object时，根据地址很容易就能找到它所属的mspan。



## 栈的内存是怎么分配的

栈和堆只是虚拟内存上2块不同功能的内存区域：



1. 栈在高地址，从高地址向低地址增长。



1. 堆在低地址，从低地址向高地址增长。



栈和堆相比优势：



1. 栈的内存管理简单，分配比堆上快。



1. 栈的内存不需要回收，而堆需要，无论是主动free，还是被动的垃圾回收，这都需要花费额外的CPU。



1. 栈上的内存有更好的局部性，堆上内存访问就不那么友好了，CPU访问的2块数据可能在不同的页上，CPU访问数据的时间可能就上去了。





## 堆内存管理怎么分配的

通常在Golang中,当我们谈论内存管理的时候，主要是指堆内存的管理，因为栈的内存管理不需要程序去操心。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190751083.jpg)



堆内存管理中主要是三部分, 

1.分配内存块，

2.回收内存块, 

3.组织内存块。



![](https://gitee.com/hxc8/images7/raw/master/img/202407190751366.jpg)



一个内存块包含了3类信息，如下图所示，元数据、用户数据和对齐字段，内存对齐是为了提高访问效率。下图申请5Byte内存的时候，就需要进行内存对齐。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190751530.jpg)



释放内存实质是把使用的内存块从链表中取出来，然后标记为未使用，当分配内存块的时候，可以从未使用内存块中有先查找大小相近的内存块，如果找不到，再从未分配的内存中分配内存。



上面这个简单的设计中还没考虑内存碎片的问题，因为随着内存不断的申请和释放，内存上会存在大量的碎片，降低内存的使用率。为了解决内存碎片，可以将2个连续的未使用的内存块合并，减少碎片。



想要深入了解可以看下这个文章,《Writing a Memory Allocator》.