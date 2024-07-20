我想了想，还是不支持那样的设计，一方面是不兼容S3，另一方面丢一个版本数据，我觉得是不能接受的。

我通过今天看AWS分享文件哪里看到是带版本号的再结合我以前做过一个项目叫"图床"

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006752.jpg)



![](D:/download/youdaonote-pull-master/data/Technology/存储/minio/images/DA350EDE36D440ADA0FBEEDB690303CCimage.png)



开启多版本，客户传一张名叫ad.jpg图到我们这，然后就点击分享这个图片链接，这个链接带版本的。

然后客户把这个链接放到他们APP首页或者其他地方使用。

如果按我们现在的设计，这时候客户暂停 多版本，然后再传一张同名ad.jpg图片，就将之前的放到回收区，也就是丢失了用户使用链接里面的那个数据，

我觉得用户还是不能接受的。





我有以下思路：

没开多版本，不给versionID,

按下一下操作步骤，

1. 创建不带版本的桶，然后传一个1.txt，保存到TBDL最新分区。

1. 这时候再传一个1.txt,把老的移到TBDR回收区,新的覆盖到TBDL最新分区，之前你的key格式是OS/[TenantID]/[BucketID]/Data/Recycle/[Objectname]/[versionID],

1. 改法1:我改成OS/[TenantID]/[BucketID]/Data/Recycle/[Objectname]/[LocationID],value 还是存元数据，这样key也不会冲突覆盖。

1. 改法2:我改成OS/[TenantID]/[BucketID]/Data/Recycle/[LogicStorageID]/[Objectname]/[LocationID],value为空(tikv不支持空,我们随便存入一个占位符就行)，这样还能批量回收某一个盘上的数据

1. 这时候开启多版本，然后再传一次1.txt,原来在TBDL分区的key value 会添加到TBDH历史分区，最新的会写到TBDL分区。

1. 关键步骤，这时候暂停多版本，然后再传一次1.txt。今天定的设计就是把TBDL挪到TBDR回收区，最新版写到TBDL分区。这样也就是我上面说的，丢了一个版本数据，应用场景就是我上面说的图床。我的思路，判断如果    多版本是开启或者(多版本是暂停且(versionID!=""&&versionID!="null"))  就把TBDL分区添加到TBDH历史分区，最新数据写入到TBDL分区。同时删除TBDH里面的versionID为null的版本，将该版本(非目录)放入TBDR回收区，如果该版本在打包区，就要删除打包区的key



另外关于小文件打包SNOC分区我的思路

如果文件小于1M,还要保存到SNOC小文件打包分区。

今天定的KEY格式是Node/[NodeID]/OCache/[TIKV_FULL_KEY]/[versionID]

我改成Node/[NodeID]/OCache/[LogicStorageID]/[TIKV_FULL_KEY]/[LocationID],value为空(tikv不支持空,我们随便存入一个占位符就行)，这样也能批量打包某一个盘上的数据