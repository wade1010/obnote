gRPC是Google公司基于Protobuf开发的跨语言的开源RPC框架。gRPC基于HTTP/2协议设计，可以基于一个HTTP/2链接提供多个服务，对于移动设备更加友好。

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/B4D9E4C75D4441A1AB2DD6404E5D182Aimage.png)

最底层为TCP或Unix Socket协议，在此之上是HTTP/2协议的实现，然后在HTTP/2协议之上又构建了针对Go语言的gRPC核心库。应用程序通过gRPC插件生产的Stub代码和gRPC核心库通信，也可以直接和gRPC核心库通信。



Grpc优缺点：

优点：

- protobuf二进制消息，性能好/效率高（空间和时间效率都很不错）

- proto文件生成目标代码，简单易用

- 序列化反序列化直接对应程序中的数据类，不需要解析后在进行映射(XML,JSON都是这种方式)

- 支持向前兼容（新加字段采用默认值）和向后兼容（忽略新加字段），简化升级

- 支持多种语言（可以把proto文件看做IDL文件）

缺点：

- GRPC尚未提供连接池，需要自行实现

- 尚未提供“服务发现”、“负载均衡”机制

- 因为基于HTTP2，绝大部多数HTTP Server、Nginx都尚不支持，即Nginx不能将GRPC请求作为HTTP请求来负载均衡，而是作为普通的TCP请求。（nginx1.9版本已支持）

- Protobuf二进制可读性差（貌似提供了Text_Fromat功能）默认不具备动态特性（可以通过动态定义生成消息类型或者动态编译支持）

