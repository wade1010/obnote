在互联网流量传输只使用了几个网络协议。使用 IPv4 进行路由，使用 TCP 进行连接层面的流量控制，使用 SSL/TLS 协议实现传输安全，使用 DNS 进行域名解析，使用 HTTP 进行应用数据的传输。

但是使用Http进行应用数据的传输,却是在不断的改变,那么Http1和Http2和Grpc之间的区别是什么,我们下面分析下.

通常影响一个 HTTP 网络请求的因素主要有两个：带宽和延迟。

- 带宽

如果说我们还停留在拨号上网的阶段，带宽可能会成为一个比较严重影响请求的问题，但是现在网络基础建设已经使得带宽得到极大的提升，我们不再会担心由带宽而影响网速，那么就只剩下延迟了。

- 延迟

浏览器阻塞（HOL blocking）：浏览器会因为一些原因阻塞请求。浏览器对于同一个域名，同时只能有 4 个连接（这个根据浏览器内核不同可能会有所差异），超过浏览器最大连接数限制，后续请求就会被阻塞。

DNS 查询（DNS Lookup）：浏览器需要知道目标服务器的 IP 才能建立连接。将域名解析为 IP 的这个系统就是 DNS。这个通常可以利用DNS缓存结果来达到减少这个时间的目的。

建立连接（Initial connection）：HTTP 是基于 TCP 协议的，浏览器最快也要在第三次握手时才能捎带 HTTP 请求报文，达到真正的建立连接，但是这些连接无法复用会导致每次请求都经历三次握手和慢启动。三次握手在高延迟的场景下影响较明显，慢启动则对文件类大请求影响较大。

然而,HTTP2并不是对HTTP1协议的重写，相对于HTTP1，HTTP2 的侧重点主要在性能。其中请求方法，状态码和语义和HTTP1都是相同的，可以使用与 HTTP1相同的 API（可能有一些小的添加）来表示协议。

HTTP2主要有两个规范组成:

- Hypertext Transfer Protocol version 2 (超文本传输协议版本 2)

- HPACK - HTTP2 的头压缩 （HPACK 是一种头部压缩算法）

HTTP2和HTTP1相比的新特性包括:

- 新的二进制格式（Binary Format）

HTTP1.x的解析是基于文本。基于文本协议的格式解析存在天然缺陷，文本的表现形式有多样性，要做到健壮性考虑的场景必然很多，二进制则不同，只认0和1的组合。基于这种考虑HTTP2.0的协议解析决定采用二进制格式，实现方便且健壮。

- 多路复用（MultiPlexing）

连接共享，即每一个request都是是用作连接共享机制的。一个request对应一个id，这样一个连接上可以有多个request，每个连接的request可以随机的混杂在一起，接收方可以根据request的 id将request再归属到各自不同的服务端请求里面。

- Header压缩

Header压缩，如上文中所言，对前面提到过HTTP1.x的header带有大量信息，而且每次都要重复发送，HTTP2.0使用encoder来减少需要传输的header大小，通讯双方各自cache一份header fields表，既避免了重复header的传输，又减小了需要传输的大小。

- 服务端推送（server push）

服务端推送（server push），同SPDY一样，HTTP2.0也具有server push功能。

Grpc的设计目标是在任何环境下运行，支持可插拔的负载均衡，跟踪，运行状况检查和身份验证。它不仅支持数据中心内部和跨数据中心的服务调用，它也适用于分布式计算的最后一公里，将设备，移动应用程序和浏览器连接到后端服务，同时，它也是高性能的，而 HTTP2 恰好支持这些。

而Grpc是基于http2的.

- HTTP2天然的通用性满足各种设备，场景.

- HTTP2的性能相对来说也是很好的，除非你需要极致的性能.

- HTTP2的安全性非常好，天然支持 SSL.

- HTTP2的鉴权也非常成熟.

- Grpc基于 HTTP2 多语言实现也更容易.