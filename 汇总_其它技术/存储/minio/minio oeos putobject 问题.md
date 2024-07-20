假如用户putobject的时候，object name = "aaaa/"  但是 contentlength>0。也就是说是一个文件名为目录的文件。有什么建议吗



我的想法是当做目录来存，假如用户传的contentlength=0 我就只存到tikv；如果contentlength>0我也把内容存到cache或者s3。  

这种情况 就算开启多版本，我也只存一份。



帮忙看下有啥问题没



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006875.jpg)



我们不能确定到底是不是创建目录。  确实就有  /结尾  内容>0的情况哦







minio 跟aws 服务端处理应该是一样的。/结尾，如果有内容是存内容的，如果有多版本也是存多份。



不过前端展示不一样，minio不展示多版本。就展示是一个目录。  aws比较奇怪。有时候点目录进去显示此文件夹中没有任何对象，图一所示。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006289.jpg)

但是你点击刷新按钮或者刷新页面就显示一个 "/" 点击这个"/"是能看到多版本的。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006366.jpg)









minio 处理逻辑  如果没开启多版本且大小小于128KB 或 大小小于16KB 就是将数据存在xl.meta文件中。不满足就存在part.1这个文件中。

如果是目录会自动 替换掉  "/"  加上"__XLDIR__"





![](https://gitee.com/hxc8/images6/raw/master/img/202407190006627.jpg)







用了aws sdk 的listobjectv2看了下。

应该还是那样理解，S3里面没有附录的概念。

概要设计那里是不是改下，如果是/结尾并且size为0就只更新tikv。不用去调cache或者s3存东西。

如果/结尾 size>0就要走概要里面的正常逻辑。





如果没开多版本，  aaaa/   size=0  不移到回收区 应该没问题吧。









多版本问题



传3次文件  名称  dirname/      有/结尾

tikv key数据如下



OS/T000/001/Data/HS/dirname/6688367/9d49cb16-6029-4761-9580-9c70f9183550

OS/T000/001/Data/HS/dirname/6688368/de56adce-1afd-4ada-927c-c84977e8019c

OS/T000/001/Data/LS/dirname/



再传2次文件 名称 dirname 	  没有/结尾   HS分区就会混淆了

tikv key数据如下

OS/T000/001/Data/HS/dirname

OS/T000/001/Data/HS/dirname/6688367/9d49cb16-6029-4761-9580-9c70f9183550

OS/T000/001/Data/HS/dirname/6688368/db8ff50c-0d06-4643-b0f3-f4aa819f24a5

OS/T000/001/Data/HS/dirname/6688368/de56adce-1afd-4ada-927c-c84977e8019c

OS/T000/001/Data/LS/dirname/





这个HS分区要不要两个/  如果两个/ 会有啥问题,或者学minio.这里把/替换为__DIR__   表明是目录

如果是两个//则HS分区如下

OS/T000/001/Data/HS/dirname//6688367/9d49cb16-6029-4761-9580-9c70f9183550

OS/T000/001/Data/HS/dirname//6688368/de56adce-1afd-4ada-927c-c84977e8019c

或

OS/T000/001/Data/HS/dirname__DIR__/6688367/9d49cb16-6029-4761-9580-9c70f9183550

OS/T000/001/Data/HS/dirname__DIR__/6688368/de56adce-1afd-4ada-927c-c84977e8019c





有时间帮忙想下























![](https://gitee.com/hxc8/images6/raw/master/img/202407190006858.jpg)

上图 并通知小文件打包服务  暂时不做



比如有个小文件 需要存到cache层，cache保存的文件需要在SNOC存一个记录，

示例数据如下

Node/N01/OCache/OS/T000/001/Data/LS/1.txt

OS/T000/001/Data/LS/1.txt



但是后来我又存了个文件1.txt,这次文件比较大，需要存到s3。

怎么处理？

1、把SNOC的文件删除 

2、SNOC的value不为空，存元数据

3、非多版本时，不用管，打包发现不存在就不管了,TBDR会回收

4、多版本时，打包那边是通过key获取到元数据，遍历所有版本，然后找到所有cache层的版本，将这些打包











你测试步骤什么是。我昨天用ListObjectVersions  没问题哦



图1 条件是

input := &s3.ListObjectVersionsInput{

  Bucket: aws.String("bobtest2022"),

  Prefix: aws.String("dirname"),

 }

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006951.jpg)

图2条件是

input := &s3.ListObjectVersionsInput{

  Bucket: aws.String("bobtest2022"),

  Prefix: aws.String("dirname/"),

 }

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006203.jpg)

没问题呀







下面是listobjv2 也应该是对的

图一

input2 := &s3.ListObjectsV2Input{

  Bucket:  aws.String("bobtest2022"),

  MaxKeys: aws.Int64(2),

  Prefix:  aws.String("dirname"),

 }

 v2, _ := s3Client.ListObjectsV2(input2)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006257.jpg)





图二

input2 := &s3.ListObjectsV2Input{

  Bucket:  aws.String("bobtest2022"),

  MaxKeys: aws.Int64(2),

  Prefix:  aws.String("dirname/"),

 }

 v2, _ := s3Client.ListObjectsV2(input2)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006305.jpg)

