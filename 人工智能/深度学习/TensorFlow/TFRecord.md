TFRecord

为什么使用TFRecord?

如何写入TFRecord?

如何读取TFRecord?

.为什么使用TFRecord?

·在数据集较小时，我们会把数据全部加载到内存里方便快速导入，但

当数据量超过内存大小时，就只能放在硬盘上来一点点读取，这时就

不得不考虑数据的移动、读取、处理等速度。使用TFRecord就是为了

提速和节约空间的。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172200017.jpg)

·如何读取TFRecord?

·生成文件队列后，可以生成相应的数据队列，定义解析的

key和数据类型即可decode

![](https://gitee.com/hxc8/images2/raw/master/img/202407172200558.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172200009.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172200374.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172200066.jpg)