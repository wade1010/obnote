TCP快速打开（TCP Fast Open，TFO）是对TCP的一种简化握手手续的拓展，用于提高两端点间连接的打开速度。 简而言之，就是在TCP的三次握手过程中传输实际有用的数据。这个扩展最初在Linux系统实现，Linux服务器，Linux系统上的Chrome浏览器，或运行在Linux上的其他支持的软件。 它通过握手开始时的SYN包中的TFO cookie来验证一个之前连接过的客户端。如果验证成功，它可以在三次握手最终的ACK包收到之前就开始发送数据，这样便跳过了一个绕路的行为，更在传输开始时就降低了延迟。 这个加密的Cookie被存储在客户端，在一开始的连接时被设定好。然后每当客户端连接时，这个Cookie被重复返回。

请求Tcp Fast Open Cookie

- 客户端发送SYN数据包，该数据包包含Fast Open选项，且该选项的Cookie为空，这表明客户端请求Fast Open Cookie；

- 支持TCP Fast Open的服务器生成Cookie，并将其置于SYN-ACK数据包中的Fast Open选项以发回客户端；

- 客户端收到SYN-ACK后，缓存Fast Open选项中的Cookie。