s3 putobject的时候是不能自定义versionid的

 



 对 就是我上面说的那种类似的情况。 操作应该分两种（两种都是小文件，不讨论大文件）

1、开多版本时，put的时候 老版本会从TBDL会挪到tbdh，然后新的也会放到SNOC区。这个操作SNOC那里没关系。SNOC哪里其实是有两个文件了。还是可以通过fullkey和logicstorageID、locationID找到这些版本。



2、不开多版本时，Put 操作，老的从TBDL挪到TBDR，新的赋值给TBDL且在SNOC分区也保存一份，这时候老的在SNOC也会有一份（这里又1个讨论点，要不要删除这个SNOC的key）

按你说的如果都存元数据，有可能要在TBDR和TBDL都冗余一份。



先按你说的SNOC格式：Node/[NodeID]/OCache/[TIKV_FULL_KEY]/[versionID]，就算存了元数据。

你打包时应该是list出Node/[NodeID]/OCache开头的所有key value

比如list里面有一条数据key为 Node/N01/OCache/OS/T000/001/Data/LS/a.java/c01a0f03-b71b-4ed4-866d-a67a25669a98 ，value就是当时完整元数据

然后你应该是根据元数据里面的logicstorageID和locationID找到真正的文件，然后读取打包，然后再把打包后的信息，通过TIKV_FULL_KEY，versionID来更新相对应TBDL或者TBDH里面的，logicstorageID、locationID、offset等字段。

上面大体过程应该没错吧？



我在key的设计是Node/[NodeID]/OCache/[LogicStorageID]/[TIKV_FULL_KEY]/[LocationID],value不存数据，这样不会出现冗余

比上面的步骤就是多了一步，根据snoc key 分析得出 在TBDL和TBDH区的key的格式，然后通过tikv API 批量读取元数据，后面步骤一样的









java端SDK不用使用tikv事务我做了如下分析



你打包那里其实就是，读完要打包的元数据后，然后到各个服务器的盘上把文件读出来。然后传到S3，传完后就会返回 新的locationID,storageID,offset等，

然后循环列表，通过TIKV_FULL_KEY、locationID和storageID 找到对应的元数据，把对应的版本上的locationID,storageID,offset等更新。

而这几个更新的字段。目前全局是没有事务改的。



但是这样会出现几种情况：

1、没开多版本：

比如现在TBDL分区有个OS/T000/003/Data/LS/a.java

我重新put个a.java，假如在我开启事务到提交事务期间，你的打包服务更新了a.java。我这边就会提示写冲突，导致失败

还有可能就是打包服务找的文件被挪到TBDR分区了

挪走后有两种办法：

1.1、打包服务发现找不到这个文件，就跳过，遗留问题就是S3那边就成了孤魂野鬼了，垃圾数据回收不掉，而且TBDR那里回收的时候在对应盘里找不到该文件，因为被打包到S3了

1.2、打包服务发现找不到这个文件，就去回收区找，因为我们的打包服务和回收服务是互斥的，所以肯定在回收区，更新回收区就行了



2、开启多版本。

如果打包区的文件是最新的，也就是在TBDL分区就会出现上面不开多版本实收同样问题

如果打包区的文件不是最新的，应该就没事。应该没有其他地方改历史分区的数。最多是挪走,挪走也没事，挪走后两种处理办法如上。





总结下来，你打包那里不用事务，仅在打包文件是TBDL上时可能会造成我这边写冲突导致我这边有操作失败。

如果能接受这个。上面整体逻辑应该没事。哈哈就是没事分析下。





