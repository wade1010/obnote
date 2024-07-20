https://www.ixigua.com/6867766833508942340



同步 异步 阻塞 非阻塞



BIO：多线程模式

部分JAVA代码如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025807.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025365.jpg)



Linux命令strace 



```javascript
strace -ff -o out java SocketBIO
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025568.jpg)

查看strace输出的文件部分如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025782.jpg)







会阻塞在accept

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025430.jpg)





然后模拟链接到服务端



```javascript
nc localhost 9090
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025003.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190025838.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190025491.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025017.jpg)









NIO : 自己控制线程数量

strace -ff -o out java SocketNIO



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025286.jpg)



对应

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025503.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190025957.jpg)





通过高速收费站引出IO多路模型



BIO如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025403.jpg)





NIO 一个人 每条路跑一次看下有没有

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025743.jpg)





多路复用器



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025494.jpg)









以上所有都需要你程序自己读取数据

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025093.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025269.jpg)











![](https://gitee.com/hxc8/images7/raw/master/img/202407190025046.jpg)

上图的弊端：

无状态的，重复传递数据：【开辟空间，增加记录】

打电话触发新一轮内核遍历







![](https://gitee.com/hxc8/images7/raw/master/img/202407190025268.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025843.jpg)



使用epoll的  Nginx  Redis



strace -ff -o out ./redis-server /etc/redis/6379.conf







![](https://gitee.com/hxc8/images7/raw/master/img/202407190025283.jpg)



循环调用 一直调用

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025237.jpg)







EPOLL多CPU如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025496.jpg)





epoll 单CPU如下



![](https://gitee.com/hxc8/images7/raw/master/img/202407190025537.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190025880.jpg)

DMA(Direct memory access直接存储器读写)

































