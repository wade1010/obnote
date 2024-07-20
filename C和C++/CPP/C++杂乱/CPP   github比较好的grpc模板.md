C++ github比较好的grpc模板

git 地址 [https://github.com/faaxm/exmpl-cmake-grpc.git](https://github.com/faaxm/exmpl-cmake-grpc.git)

git clone [https://github.com/faaxm/exmpl-cmake-grpc.git](https://github.com/faaxm/exmpl-cmake-grpc.git)

主要代码如下

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242211.jpg)

安装好grpc后

可以参考[attachments/GgiNDNjC](attachments/GgiNDNjC) 安装

mkdir build && cd build

cmake .. && make

启动server

```
./server/server
```

启动client

```
./client/client
I got:
Name: Peter Peterson
City: 
Zip:  12345
Street: 
Country: Superland

```

也可以看到server

```
./server/server
Server: GetAddress for "John".
```