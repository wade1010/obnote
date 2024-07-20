MSL是Maximum Segment Lifetime英文的缩写，中文可以译为“报文最大生存时间”



问题

主动发起关闭连接的操作的一方将达到TIME_WAIT状态，而且这个状态要保持Maximum Segment Lifetime的两倍时间。为什么要这样做而不是直接进入CLOSED状态？

原因：

1. 保证TCP协议的全双工连接能够可靠关闭

1. 保证这次连接的重复数据段从网络中消失

解释

1. 如果Client直接CLOSED了，那么由于IP协议的不可靠性或者是其它网络原因，导致Server没有收到Client最后回复的ACK。那么Server就会在超时之后继续发送FIN，此时由于Client已经CLOSED了，就找不到与重发的FIN对应的连接，最后Server就会收到RST而不是ACK，Server就会以为是连接错误把问题报告给高层。这样的情况虽然不会造成数据丢失，但是却导致TCP协议不符合可靠连接的要求。所以，Client不是直接进入CLOSED，而是要保持TIME_WAIT，当再次收到FIN的时候，能够保证对方收到ACK，最后正确的关闭连接。

1. 如果Client直接CLOSED，然后又再向Server发起一个新连接，我们不能保证这个新连接与刚关闭的连接的端口号是不同的。也就是说有可能新连接和老连接的端口号是相同的。一般来说不会发生什么问题，但是还是有特殊情况出现：假设新连接和已经关闭的老连接端口号是一样的，如果前一次连接的某些数据仍然滞留在网络中，这些延迟数据在建立新连接之后才到达Server，由于新连接和老连接的端口号是一样的，又因为TCP协议判断不同连接的依据是socket pair，于是，TCP协议就认为那个延迟的数据是属于新连接的，这样就和真正的新连接的数据包发生混淆了。所以TCP连接还要在TIME_WAIT状态等待2倍MSL，这样可以保证本次连接的所有数据都从网络中消失。







漫画版解释

https://www.toutiao.com/a6688932154518274571/



![](https://gitee.com/hxc8/images7/raw/master/img/202407190024660.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024607.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024513.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024544.jpg)

MSL是Maximum Segment Lifetime英文的缩写，中文可以译为“报文最大生存时间”，他是任何报文在网络上存活的最长时间，超过这个时间报文将被丢弃。而2MSL的意思就是2倍的MSL的意思。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024383.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024391.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024685.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024711.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024707.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024690.jpg)

小萌： 这种情况下，那服务器会一直收不到客户端的回应，所以这种情况是和只进行三次挥手的情况类似的，服务器没有收到回应，服务器就无法知道到底客户端有没有收到服务器断开的请求。

如果客户端收到了，那还好，皆大欢喜客户端和服务器端都断开连接;

如果客户端没有收到（为啥这么说呢？虽然客户端已经收到了，但是客户端没有回应呀~，没有回应谁知道你到底收没收到，所以服务器不知道客户端收到没有，就好比你自己捡了100块钱，你不交给警察叔叔，那么警察叔叔怎么知道你捡没捡100块呢？），客户端还一直傻傻地在那里等着服务器继续发送消息。

服务器无法判断客户端是否收到，这种情况本身就是一种不可靠的情况，堂堂号称可靠的TCP的连接出现这种情况岂不是要被笑掉大牙？

![](D:/download/youdaonote-pull-master/data/Technology/网络编程/协议/images/3C74867A19F7459C9B9D255674A426AAcd9c6b4795fb440493ee9abc9bbb1042.jpeg)

乔戈里：小萌你说得很好，也因此出现了客户端要等待2MSL的情况。为了保证客户端最后一次挥手的报文能够到达服务器，如果第四次挥手的报文段丢失了，服务器会超时重传这个第三次挥手的报文段，所以客户端不是直接进入CLOSED，而是要保持TIME_WAIT（等待2MSL就是TIME_WAIT）就起到作用了，当再次收到服务器的超时重传的断开连接的第三次挥手的请求的时候，客户端会继续给服务器发送一个第四次挥手的报文，能够保证对方（服务器）收到客户端的回应报文，最后客户端和服务器正确的关闭连接。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024539.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024794.jpg)

乔戈里：如果Client（客户端）直接CLOSED（关闭），然后又再向Server（服务器端）发起一个新连接，我们不能保证这个新连接与刚关闭的连接的端口号是不同的。也就是说有可能新连接和老连接的端口号是相同的。一般来说不会发生什么问题，但是还是有特殊情况出现：假设新连接和已经关闭的老连接端口号是一样的，如果前一次连接的某些数据仍然滞留在网络中，这些延迟数据在建立新连接之后才到达Server，由于新连接和老连接的端口号是一样的，于是，TCP协议就认为那个延迟的数据是属于新连接的，这样就和真正的新连接的数据包发生混淆了。所以TCP连接还要在TIME_WAIT状态等待2倍MSL，这样可以保证本次连接的所有数据都从网络中消失。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024899.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024866.jpg)

小明与女神的对话： 所处的网络环境：如果客户端不等待2MSL直接进行关闭，前一次的连接的数据还在网络中。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024087.jpg)

这个时候小明以为他的消息会在这2MSL中的时间中消失（小明以为这个TCP是正常的四次挥手），悄悄说了一句女神的坏话，但是悲剧的是这个小明所在的网络环境不是可靠的没有等待2MSL的网络环境，并没有消失，因此如果没有2MSL的等待时间上一次的小明说的坏话的数据包还在网络中。

你说好巧不巧，小明和女神重新建立了连接以后，端口号还是之前的端口号，ip地址也没变，于是小明和女神上一次说坏话聊天的记录，重新送到了女神那里。

于是女神收到了小明说的坏话，然后小明就。。。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024464.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024002.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024016.jpg)

