###  一、工作原理

#### tensor core执行

![](https://note.youdao.com/yws/res/123002/WEBRESOURCE5711db9e170a632ddd3702ceccbd822b)

![](https://note.youdao.com/yws/res/123004/WEBRESOURCE640e02780a0fe7194f38dae10e6a7779)

![](https://note.youdao.com/yws/res/123006/WEBRESOURCE5017ef57b99887242bec6cd6d96bfba0)

V100其实并不是一行一行的去计算，而是整一个矩阵整一个矩阵的去计算的，

[https://www.nvidia.cn/data-center/tensor-cores/](https://www.nvidia.cn/data-center/tensor-cores/)

[https://images.nvidia.com/aem-dam/Solutions/Data-Center/tensorcore/Volta-Tensor-Core_30fps_FINAL_994x559.gif](https://images.nvidia.com/aem-dam/Solutions/Data-Center/tensorcore/Volta-Tensor-Core_30fps_FINAL_994x559.gif)

上面链接是官方给的模拟图，

pascal就是上一代的架构，没有tensor core，是一个元素跟一行进行相乘，每个时钟周期执行四次相乘得到一列数据。

而在V100里面，是把整个矩阵A跟矩阵B进行相乘，然后得到一个矩阵的输出，整体来说，右边图的tensor core在单个时钟周期内，就能够执行4X4X4=64次的FMA，因此它的吞吐会比左边Pascal架构快12倍。

![](https://note.youdao.com/yws/res/123040/WEBRESOURCEe1cfa4b762872fe5561a05eac8df2d81)

上图可以看到一个时钟周期其实只能执行16个FFMA，但是在V100的tensor core里面，一个时钟周期内可以执行两个4X4X4的FMA操作。

![](https://note.youdao.com/yws/res/123048/WEBRESOURCE40fc27f603d8bef049b9529931f80d4d)

**整体来说，tensor core的计算吞吐比右边的CUDA core要高12倍。（没理解为什么高12倍 后面链接和两张图是查资料的） **[https://baijiahao.baidu.com/s?id=1567082220106634&wfr=spider&for=pc](https://baijiahao.baidu.com/s?id=1567082220106634&wfr=spider&for=pc)

![](https://note.youdao.com/yws/res/123070/WEBRESOURCE06872a23a3f7b9d3d4f23bf1ea1fca3f)

![](https://note.youdao.com/yws/res/123072/WEBRESOURCE3c560d3599146993f5cee4ad65752b69)

![](https://note.youdao.com/yws/res/123057/WEBRESOURCE5b3b6d2b3bc6e4d695eefb925fe335fa)

一个SM里面4个sub core，每个sub core里面有2个tensor core，每个tensor core一个时钟周期内能执行64个FMA，

因此一个SM里面，单个时钟周期就可以执行4X2X(4x4x4)X2=1024次FFMA

#### 指令流水

下面只是猜想，并不一定这么实现

![](https://note.youdao.com/yws/res/123079/WEBRESOURCE450d5041672c8faeac61d6e117218311)

![](https://note.youdao.com/yws/res/123081/WEBRESOURCE2000925b23c626fdc210069333b2aac6)

原来CUDA core是做一个点跟一行进行相乘，

现在V100里面，是一个矩阵跟一个矩阵直接相乘得到一个新的矩阵

![](https://note.youdao.com/yws/res/123088/WEBRESOURCE144ae04510ab93b2e6cf24b439c2a5ca)

上右图，一行跟一列相乘得到一个元素，那更多的怎么办呢？

![](https://note.youdao.com/yws/res/123092/WEBRESOURCEaaebc601b92da90ae856d8c924eca078)

假设刚才展示的A的一行跟B的一列相乘得到一个元素，下面看一下，把这么一个简单的元素进行一个组合，把A0i、A1i、A2i、A3i，A的每一行跟B的每一列进行相乘，

![](https://note.youdao.com/yws/res/123104/WEBRESOURCE210f55a3477ed16b77b9a67acea65b3b)

这个时候就可以得到矩阵的每一个元素，，上图好像是脉动架构

![](https://note.youdao.com/yws/res/123111/WEBRESOURCEe8f3a2f8e716bfdb2321a8f0cb45805d)

当一个元素，也就是scalar的乘加操作的指令，但实际上，tensor core里面的mul只有FP16,存储或者加的时候是用到FP32，于是把刚才的一个round节省掉，如果要实现两个元素相乘，就要把两条流水并行起来，

![](https://note.youdao.com/yws/res/123127/WEBRESOURCEd4ceb82560e1f7942107edf3f61f311b)

这个就是指令流水，

![](https://note.youdao.com/yws/res/123131/WEBRESOURCEe646c7dbb1605824f49850931938c0b4)

现在实现用A的一行乘以B的一列，于是就有4条pipeline的流水，现在只是实现简单计算一个元素，就需要4条流水，

![](https://note.youdao.com/yws/res/123138/WEBRESOURCE3cb150aae9a078851c4e4e4b112350cc)

通过上面绿色指令流水，计算出饿了D00，通过黄色流水计算出了D01，

![](https://note.youdao.com/yws/res/123144/WEBRESOURCEe669eac13aff7554e25b61b3ff5799fc)

接下来把所有元素计算出来，就有大量的指令的流水去拼接，现在把4条流水拼接起来，就简单实现了一个矩阵的D01到D03的一个结果

![](https://note.youdao.com/yws/res/123154/WEBRESOURCE86963a9e9d3554a2168930e3def20c9f)

那现在其实还要把所有的拼接起来，那整个指令的流水在一个屏幕已经放不下，

![](https://note.youdao.com/yws/res/123160/WEBRESOURCE603a8556cf8d65efba8fabde0c2b6dc3)

看下这里面的颜色，其实在某一个时间段，对数据的读写是有规律的，mul就是需要对数据读取出来，算完round就是数据的写入，

![](https://note.youdao.com/yws/res/123179/WEBRESOURCE015ddae0cc2a8232eea01d3b3cd4d720)

所以在某个时刻，对整个流水是有四个数据，从寄存器里面读到计算单元，然后有一个数据存到我们的寄存器里面，通过大量的指令流水，实现了整个tensor core的计算。

#### CUDA 线程执行

![](https://note.youdao.com/yws/res/123183/WEBRESOURCE7243298e1bb61aa73bc0a0bd3167b59a)

在整体CUDA的软件设计方面，其实是希望能够去匹配英伟达计算和存储分层的整个结构，对tensor core的定义，其实主要是通过CUDA来提供一个泛型编程。

上图是一个demo

矩阵A跟矩阵B相乘得到矩阵C，但实际上不可能把这么一个大的矩阵塞到具体的tensor core里面，因为tensor core只能容纳4X4的一个简单计算，那这个时候回对矩阵进行切片，放到thread block（线程块）里面，接着再放到软件上面，定义一个warp，最后整个线程去执行的就是真正的tensor core。

下面逐层去打开具体的内容。

![](https://note.youdao.com/yws/res/123211/WEBRESOURCEe6e356e290c8a9bf06bea698e7b34e53)

GEMM（矩阵乘）其实一次是计算一个小的矩阵块，也就是把矩阵A拿出一个小块，把矩阵B拿出一个小块，算出来一个矩阵C。

那这个时候在整体的软件去编程的时候就会沿着每一个维度（也就是沿着每个m每个k还有n）进行切分，具体就划分为m Tile跟n Tile一个独立的矩阵乘法

![](https://note.youdao.com/yws/res/123232/WEBRESOURCEa6913d35b53f9637fb40090117462c1b)

通过这种累积，n维跟m维还有k维的Tile，把整个矩阵乘累积起来，计算出整个大的矩阵的结果。在整个编程里面每一个维度就惊醒分开

![](https://note.youdao.com/yws/res/123241/WEBRESOURCE340a1ec83c42334cd8650c59c082a985)

![](https://note.youdao.com/yws/res/123244/WEBRESOURCE807c58595d81991c539e123d1882f0f7)

 接下来再打开一层（如下图）

![](https://note.youdao.com/yws/res/123246/WEBRESOURCE7aa7163624fcbccff83956a86a3afe84)

上面是打开warp level这一层，来看看具体是怎么执行的，首先回吧A tile跟B tile，就是共享内存里面的数据，加载到RF，也就是具体的寄存器，那A fragment跟B fragment，就是寄存器里面具体的空间或者数据，这里面值得注意的就是数据加载要快于计算，否则就会严重的影响整个计算的吞吐，结果矩阵C会比较大，所以会存储在线程参加执行的寄存器上面，说白了就是都存在寄存器上面。而共享内存里面的整体的格式，所谓的layout是以k维进行存储，这种layout方式，会使得线程执行的更加高效。所以可以看到整体的循环里面，再打开一层，具体的线程M跟n之间的相乘就是在CUDA thread里面去执行的，就是具体的线程。

![](https://note.youdao.com/yws/res/123285/WEBRESOURCE018ee19870815dbd4b506a9dd673fa65)

线程我们控制不了，大部分我们还是控制warp，现在打开tensor core上面的并行执行，实际上tensor core并行执行就是刚才A乘以B等于C这么一个简单，最核心最里面的一个操作。

![](https://note.youdao.com/yws/res/123299/WEBRESOURCEa29f580b2d81d545ab62bd483cdbf6c8)

tensor core是英伟达对应的硬件，但是CUDA对应的api是WMMA，通过WMMA对外提供具体的计算能力，通过MMA的load storage mmaasync这几个API呢完成整个tensor core的计算， 

![](https://note.youdao.com/yws/res/123308/WEBRESOURCEb9021898318192695320e0b6150201ac)

上图看到在GEMM矩阵的软硬件分层里面，每一层都需要对数据进行复用，在整一个块里面，大矩阵，会把大矩阵放在不同的全局内存里面，在对于局部内存，分块来进行存，分块里面，会把一些具体的数据存在寄存器里面，所以它整体每一层数据都进行大量的去复用，都进行大量的去复用

![](https://note.youdao.com/yws/res/123329/WEBRESOURCE405c54c6c78315c7f154cae9a5c18cbe)

那现在GEMM，具体的计算算完了，但是算完每一次的结果都是很小的一块结果，怎么把整个矩阵或者累积矩阵乘的结果写回去，写回外面的这个C里面呢？（如下图的红色框）

![](https://note.youdao.com/yws/res/123345/WEBRESOURCE2f048f3cbc0766371a4d9e7d6eca6491)

![](https://note.youdao.com/yws/res/123347/WEBRESOURCE5bc8013ee8dd4d8671b13b49e15da653)

刚才提到一个结果会存在register file里面，CUDA通过提供WMMA的API，这里面有个store_matrix_sync() ，去把所有的数据搬到共享内存，也就是SMEM里面，对于SMEM会做大量累积的操作，既然是一条计算，会搬到具体的寄存器里面，去进行计算，最后把所有的数据累积起来， 然后放在全局内存里面，通过全局内存吧一个块一个块的拼接起来，把数据恢复回来。

![](https://note.youdao.com/yws/res/123375/WEBRESOURCE56d0a9e824ee097247a2c9fa35bb2b9c)

![](https://note.youdao.com/yws/res/123379/WEBRESOURCEe3842fb72d7159ab2f98654b2af82199)

总结完整GEMM计算过程。

第一步、对矩阵进行分块，放在global memory里面，接着以thread block Tile进行划分， 把具体的、局部的数据或者fragment放在shared memory共享内存里面，真正执行是放在寄存器里面，以warp level进行执行在tensor core里面， 执行完之后会回传到SMEM里面，不是存储到register file了。因为是从tensor core的register file 写出来，然后进行累加或者融合算子的操作，这里面可能会融合其它算子，例如卷积加relu啊，matmul加各种激活（如下图部分）

![](https://note.youdao.com/yws/res/123415/WEBRESOURCEe71be0b528714988bf0d7b710c49da11)

再往后就把所有的数据写回全局内存，