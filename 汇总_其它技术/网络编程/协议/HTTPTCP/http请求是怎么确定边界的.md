[https://mp.weixin.qq.com/s?__biz=Mzg3NTU3OTgxOA==&mid=2247486733&idx=1&sn=8ec7f7cbaf66a98c68de28cf5cba95f7&chksm=cf3e1dc8f84994de223c6a136fc1853ca9ccb9f31e1e409bb06f9ece6c9a3cce9f1639afe229&cur_album_id=1819478029098663939&scene=190#rd](https://mp.weixin.qq.com/s?__biz=Mzg3NTU3OTgxOA==&mid=2247486733&idx=1&sn=8ec7f7cbaf66a98c68de28cf5cba95f7&chksm=cf3e1dc8f84994de223c6a136fc1853ca9ccb9f31e1e409bb06f9ece6c9a3cce9f1639afe229&cur_album_id=1819478029098663939&scene=190#rd)

- S3 上传有两种 method 方式

- HTTP 数据怎么确定边界？

	- 分四种情况讨论

	- 截图示例

#### S3 上传有两种 method 方式

1. PUT 请求：这个上传请求上传对象协议明确携带 Content-length 的；

1. POST 请求：这个不要求知名 Content-length，而是通过一种流式的数据传输，但是总归还是要知道边界在哪里？有以下几种方式让服务端知道数据的边界；

S3 是基于HTTP的协议，HTTP 是基于TCP协议的应用层协议，而 TCP 是面向数据流的协议，是没有边界的。HTTP 作为应用层协议需要自己明确定义数据边界。

HTTP边界判定由于http1.1协议之后，

http可以是一个keep-alive的，

可以是一个流式协议。

那么我们需要有办法去标识body边界，

有三种方式：

1. http包头部显式设置 Content-Length

1. http传输编码方式用 Transfer-Encoding: chunked

1. 短连接（连接断开）

第3种情况，一般作为异常场景看待。所以下面我们就讨论前两种情况

这两种情况都取决于客户端的协议是否遵守。

正常情况，如果传输了Content-Length，就要和body一致。

如果头部没有这个字段，那么也可以客户端采用Transfer-Encoding：chunked的编码方式传输body，

也能让服务器正确的识别body的边界。

**情况一：如果只设置了Content-Length，但是body不准，怎么办？**

1. Content-Length比body大？

1. 服务器一般实现会设置一个超时。服务端主动断开连接，报告超时。这个当前线上系统由nginx完成，1分钟超时。

1. 客户端自己主动断开连接

1. 服务一般是卡住，服务器等待读完客户端声明的数据。

1. Content-Length比body小？

1. 请求成功，数据截断

**情况二：如果没有Content-Length，设置了Transfer-Encoding：Chunked？**

这个时候能正确识别到边界，以Chunked模式为准。有无Content-Length没区别。就算设置了Content-Length，也不会依赖用这个值，不准也没关系。只要是按照chunked的模式，以实际取到的body为准。

**情况三：Content-Length和Transfer-Encoding都设置？**

以chunked传输为准。

**情况四：Content-Length和Transfer-Encoding都没设置？**

这种情况和Content-Length=0是一样处理的。