TCP与UDP区别总结：

1、 TCP面向连接（如打电话要先拨号建立连接）;

UDP是无连接的，即发送数据之前不需要建立连接. 

2、TCP提供可靠的服务。也就是说，通过TCP连接传送的数据，无差错，不丢失，不重复，且按序到达;

UDP尽最大努力交付，即不保证可靠交付. 

3、TCP面向字节流，实际上是TCP把数据看成一连串无结构的字节流;

UDP是面向报文的. UDP没有拥塞控制，因此网络出现拥塞不会使源主机的发送速率降低（对实时应用很有用，如IP电话，实时视频会议等）. 

4、每一条TCP连接只能是点到点的;

UDP支持一对一，一对多，多对一和多对多的交互通信. 

5、TCP首部开销20字节;

UDP的首部开销小，只有8个字节. 

6、TCP的逻辑通信信道是全双工的可靠信道

UDP则是不可靠信道.





因此UDP不提供复杂的控制机制，利用IP提供面向无连接的通信服务，随时都可以发送数据，处理简单且高效.



UDP经常用于以下场景：

- 包总量较小的通信（DNS、SNMP）

- 视频、音频等多媒体通信（即时通信）

- 广播通信



TCP 使用场景:

相对于 UDP，TCP 实现了数据传输过程中的各种控制，可以进行丢包时的重发控制，还可以对次序乱掉的分包进行顺序控制。

在对可靠性要求较高的情况下，可以使用 TCP，即不考虑 UDP 的时候，都可以选择 TCP。