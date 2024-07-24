#### tensor core原理

什么是混合精度？

网络层面既有FP16,又有FP32，这样理解是错误的，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105231.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105450.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106755.jpg)

每一个SM有8组tensor core，每一个tensor core在每个时钟周期内能执行4X4X4的GEMM，也就是64个FMA

GEMM是矩阵乘法-矩阵乘法（General Matrix-Matrix Multiplication）的缩写。

上图ABCD都是4X4的矩阵，矩阵乘法里面的数，A和B可以是FP16,而累加矩阵C和得到的结果D可以是FP16或者FP32，因此称底层硬件tensor core它是一个混合精度的计算，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106060.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106445.jpg)

神经网络里面，不仅仅只有一个矩阵乘这么简单，在训练的过程当中，就会遇到卷积跟激活进行相乘，得到另外一个新的feature map

上图就是混合精度训练（正向都是FP16，反向也是FP16，但是真正存储的时候是使用FP32进行存储的）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106947.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106198.jpg)

在coda里面是通过控制一个warp，一个warp包含很多个线程，同一时间并行并发的去执行，那在真正执行的时候，会做一个warp同步的操作，把所有的线程都进行同步，然后获取同样的数据，接着进行一个16X16的矩阵相乘和矩阵计算，最后再把结果存储在不同的warp上面，warp就是在软件上面做一个大的线程的概念

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106585.jpg)

在CUDA执行过程中，可以通过线程的warp来去调度tensor core。在一个warp线程里面，通过tensor core来提供一个16X16X16的矩阵运算（之前说的是4X4X4，这里为什么变成16了，后面会展开的讲解下）

在真正CUDA通过tensor core进行变成，通过warp来提供cuda c++ WMMA的API ，对外提供给开发者，这里的WMMA主要是针对tensor core进行矩阵的加载（void load_matrix_xxx）存储（store_matrix_xxx）,还有具体的计算（mma_sync）。

上面几个方法，大部分都有sync，就是所有warp之间需要进行不同的。

刚才提到tensor core是一个4X4的tensor core的核，但实际上一个SM里面有多个tensor core，不可能最细粒度的去控制每一个tensor core，这样效率会很低，于是，一个warp就帮好几个tensor core包装起来，对外提供一个16x16x16的一个warp level的卷积的指令。而这个指令，最后通过MMA sync这个API进行计算。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106907.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106268.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106185.jpg)

** 所有的卷积计算会变成GEMM矩阵乘的方式，那矩阵乘，现在有一个蓝色的矩阵和一个黄色的矩阵，两个相乘得到绿色的矩阵。**

**但实际计算的时候，会取片段的数据（如下图）**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106365.jpg)

**也就是fragment，取到了fragment这条长长的、横横的，就变成了fragment box ,就是线程块，在具体硬件编程线程块，在真正线程块执行的时候，就会把这里面其中一部分（如下图）再提取出来变成warp level的计算，**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106114.jpg)

**warp level的计算其实还是很大，在真正fragment执行的时候，又会把它变成满足CUDA 和矩阵输入的计算，**

**总结为一句话：从简单的矩阵乘到实际硬件执行的时候，会把数据的一部分根据硬件的多级缓存，放在box warp thread里面，最终通过线程的box提供tensor core的核心的计算。**

GEMM会非常大， 因此通过多级的缓存，利用数据的局部性，拆分成block、warp还有thread，最终通过thread去提供实际的tensor core的运算。