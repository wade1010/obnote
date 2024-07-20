内存的碎片化

如果用 c 语言直接 malloc,free 来向操作系统申请和释放内存时,

在不断的申请和释放过程中,形成了一些很小的内存片断,无法再利用.

这种空闲,但无法利用内存的现象,---称为内存的碎片化



slab allocator 缓解内存碎片化

memcached 用 slab allocator 机制来管理内存.

slab allocator 原理: 预告把内存划分成数个 slab class 仓库.(每个 slab class 大小 1M)

各仓库,切分成不同尺寸的小块(chunk). （图 3.2）

需要存内容时,判断内容的大小,为其选取合理的仓库.

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/9E20B7806C4441D1B81D82CBA2A941ACimage.png)



3.3 系统如何选择合适的 chunk?

memcached 根据收到的数据的大小, 选择最适合数据大小的 chunk 组(slab class)（图 3.3）。

memcached 中保存着 slab class 内空闲 chunk 的列表, 根据该列表选择空的 chunk, 然后将数

据缓存于其中。



![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/872A6CE34DB04AA291CD5A68B42F617Fimage.png)





警示:

如果有 100byte 的内容要存,但 122 大小的仓库中的 chunk 满了

并不会寻找更大的,如 144 的仓库来存储,

而是把 122 仓库的旧数据踢掉! 详见过期与删除机制(LRU)



memcached得LRU不是全局的，而是针对slab的，可以说是区域性的。





3.4 固定大小 chunk 带来的内存浪费

由于 slab allocator 机制中, 分配的 chunk 的大小是”固定”的, 因此, 对于特定的 item,可能造

成内存空间的浪费.

比如, 将 100 字节的数据缓存到 122 字节的 chunk 中, 剩余的 22 字节就浪费了(图 3.4)



![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/13253C635548414FB4BA5078761F0B46image.png)





对于 chunk 空间的浪费问题,无法彻底解决,只能缓解该问题.

开发者可以对网站中缓存中的 item 的长度进行统计,并制定合理的 slab class 中的 chunk 的大

小.

可惜的是,我们目前还不能自定义 chunk 的大小,但可以通过参数来调整各 slab class 中 chunk

大小的增长速度. 即增长因子, grow factor!



3.5 grow factor 调优

memcached 在启动时可以通过­f 选项指定 Growth Factor 因子, 并在某种程度上控制 slab 之

间的差异. 默认值为 1.25. 但是,在该选项出现之前,这个因子曾经固定为 2,称为”powers of 2”

策略。

我们分别用 grow factor 为 2 和 1.25 来看一看效果:

>memcached ­f 2 ­vvv

slab class 1: chunk size 128 perslab 8192

slab class 2: chunk size 256 perslab 4096

slab class 3: chunk size 512 perslab 2048

slab class 4: chunk size 1024 perslab 1024

....

.....

slab class 10: chunk size 65536 perslab 16

slab class 11: chunk size 131072 perslab 8

slab class 12: chunk size 262144 perslab 4

slab class 13: chunk size 524288 perslab 2

可见，从 128 字节的组开始，组的大小依次增大为原来的 2 倍.

来看看 f=1.25 时的输出:

>memcached -f 1.25 -vvv

slab class 1: chunk size 88 perslab 11915

slab class 2: chunk size 112 perslab 9362

slab class 3: chunk size 144 perslab 7281

slab class 4: chunk size 184 perslab 5698

....

....

slab class 36: chunk size 250376 perslab 4

slab class 37: chunk size 312976 perslab 3

slab class 38: chunk size 391224 perslab 2

slab class 39: chunk size 489032 perslab 2

对比可知, 当 f=2 时, 各 slab 中的 chunk size 增长很快,有些情况下就相当浪费内存.

因此,我们应细心统计缓存的大小,制定合理的增长因子.

注意:

当 f=1.25 时,从输出结果来看,某些相邻的 slab class 的大小比值并非为 1.25,可能会觉得有些

计算误差，这些误差是为了保持字节数的对齐而故意设置的.



3.6 memcached 的过期数据惰性删除

1: 当某个值过期后,并没有从内存删除, 因此,stats 统计时, curr_item 有其信息

2: 当某个新值去占用他的位置时,当成空 chunk 来占用.

3: 当 get 值时,判断是否过期,如果过期,返回空,并且清空, curr_item 就减少了.

即--这个过期,只是让用户看不到这个数据而已,并没有在过期的瞬间立即从内存删除.

这个称为 lazy expiration, 惰性失效.

好处--- 节省了 cpu 时间和检测的成本



3.7: memcached 此处用的 lru 删除机制.

如果以 122byte 大小的 chunk 举例, 122 的 chunk 都满了, 又有新的值(长度为 120)要加入, 要

挤掉谁?

memcached 此处用的 lru 删除机制.

(操作系统的内存管理,常用 fifo,lru 删除)

lru: least recently used 最近最少使用

fifo: first in ,first out

原理: 当某个单元被请求时,维护一个计数器,通过计数器来判断最近谁最少被使用.

就把谁 t 出.

注: 即使某个 key 是设置的永久有效期,也一样会被踢出来!

即--永久数据被踢现象



5.2 中继 MySQL 主从延迟数据



MySQL 在做 replication 时,主从复制之间必然要经历一个复制过程,即主从延迟的时间.

尤其是主从服务器处于异地机房时,这种情况更加明显.

把 facebook 官方的一篇技术文章,其加州的主数据中心到弗吉尼亚州的主从同步延期达到

70ms;

考虑如下场景:

①: 用户 U 购买电子书 B, insert into Master (U,B);

②: 用户 U 观看电子书 B, select 购买记录[user=’A’,book=’B’] from Slave.

③: 由于主从延迟,第②步中无记录,用户无权观看该书.

这时,可以利用 memached 在 master 与 slave 之间做过渡(图 5.2):

①: 用户 U 购买电子书 B, memcached->add(‘U:B’,true)

②: 主数据库 insert into Master (U,B);

③: 用户 U 观看电子书 B, select 购买记录[user=’U’,book=’B’] from Slave.

如果没查询到,则 memcached->get(‘U:B’),查到则说明已购买但 Slave 延迟.

④: 由于主从延迟,第②步中无记录,用户无权观看该书.



![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/A9283F4AF2FE4EDEBA8397A5A735741Bimage.png)



