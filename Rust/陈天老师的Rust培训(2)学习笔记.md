陈天老师的Rust培训(2)学习笔记

![](https://gitee.com/hxc8/images5/raw/master/img/202407172325425.jpg)

所有权：

- Rust中的每一个值都有一个被称为其 所有者（owner）的变量

- 值在任一时刻有且只有一个所有者。

- 当所有者(变量)离开作用域的时候，这个值将被丢弃。

Copy的类型：

- 所有整数类型，比如u32。

- 布尔类型

- 所有浮点数类型，比如f64

- 字符类型，char

- 元组，当且仅当其包含的类型也都是Copy的时候。比如(u32,u32)是Copy的，但是(u32,String)就不是。

当我们变量从一个地方传递到另外一个地方，如果你实现了Copy Trait，就会按bit去拷贝，把这个变量的值，拷贝到新的scope下面。

如果一个变量的类型没有实现Copy Trait，默认就是move,它会把变量的值从一个地move到另外一个地方，

![](https://gitee.com/hxc8/images5/raw/master/img/202407172325396.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172325637.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172325833.jpg)

rust大部分数据结构都是Sized的，如果说一个data type的大小在编译器可以确定的话，它的类型就是Sized。反之就是DST。

因为在编译器可以确定数据结构的大小，就可以放在栈上。我在一个函数里面声明一个变量，变量它的数据结构的类型是Sized，那么它就可以放在栈上，反之的话，它不是Sized，是DST的话一般来说只能放在堆上，当然有一些例外，因为DST就意味着编译期的时候，我的大小是不确定的，所以不确定的东西，编译器是没办法把它放在这个栈上的，因为当编译器为函数分配一个新的栈的时候，它需要计算里面用到的局部变量的大小，然后把栈帧指好，这样函数可以正常运行。

但是有些方法可以让DST也放在栈上。比如上图的stack_dst：[https://github.com/thepowersgang/stack_dst-rs](https://github.com/thepowersgang/stack_dst-rs)

trait object，因为生成一个Vtable,Vtable包含了很多信息，Vtable大小是根据不同的trait，是展示不一样的。

上面Q2：

如果说我们知道这个DST它的最大Sized的话，我们依旧可以给它分配一个它不可能超过的一个大小，比如说我知道这个DST，它的SIZE虽然是变动的，但是它最大不超过64个字节，那么我如果在栈上分配64个字节的话，就能满足这个DST的处理。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326333.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326717.jpg)

tonic:[https://github.com/hyperium/tonic/blob/master/tests/integration_tests/tests/connection.rs](https://github.com/hyperium/tonic/blob/master/tests/integration_tests/tests/connection.rs)

snow:[https://github.com/mcginty/snow/blob/master/hfuzz/src/bin/params.rs](https://github.com/mcginty/snow/blob/master/hfuzz/src/bin/params.rs)

cellar:[https://github.com/tyrchen/cellar/blob/master/cellar-core/src/lib.rs](https://github.com/tyrchen/cellar/blob/master/cellar-core/src/lib.rs)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326964.jpg)

Leaf:比如说一个TOKIO 下面创建的TCPStream ，它是一个future，它是一个leaf future。就是这个future，它依赖于外部事件，比如说，操作系统来告诉它什么时候数据有，future会被wake up，进一步的去被处理。

Non-Leaf:它的运转有赖于leaf future的不断poll，得到执行，继续往下走，从而trigger non-leaf future往下执行。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326762.jpg)

讲到future ，就不得不讲pin。rust的future从0.1到0.3它经历了很长时间的变迁，甚至async await在rust unstable里面也存在了很长时间，一直没有稳定，很大一个原因就是但是没有找到妥善的方法来处理一些可能存在的corner case(边界情况)，而这个corner case 就是自我引用结构（如上左图）

交换后，因为b变量只是单纯的bit by bit的拷贝，这个指针只是作为一个数据拷过来，所以它还是0x1002，而0x1002指向的是原来a的位置，而原来a的位置已经被swap之后到了右边了。

所以这样就形成了一个环，这个对于rust来讲是一个很棘手的问题，可能将会导致内存泄露，逻辑错误等等，引发一系列的不安全问题，这是rust核心开发团队，很长时间没有找到一个方法来处理这种问题。后来找到的方法，还是跟send sync比较类似，但有这种自引用的结构的时候，我不允许你做这种swap操作，memory move 来move去的操作。那怎么做呢？比较形象的就是用个钉子，把数据结构钉在这，不让你去做move,怎么做到这一点呢？参考send sync，原理类似，首先绝大部分数据结构，都是会自动实现unpin的，如果带了unpin的话，pin就不起任何作用，也就是说它不能把数据结构钉死在这个位置，而是允许这个指针移动。

但是如果一个数据结构没有实现unpin的话，Pin就没办法去拿到这个P的可变引用，Pin<P>这个P是一个泛型,P要求在做DerefMut的时候，它的target必须是Unpin，所以一旦一个数据结构本身没有实现Unpin，就意味着它的pointer（因为这个pointer是做这个derefmut嘛），对这个pointer做一个mutable reference的话，那它这个mutable reference就不满足P:DerefMut<Target:Unpin>这个前提，编译器就不会让它通过。

pin_project:[https://github.com/taiki-e/pin-project](https://github.com/taiki-e/pin-project)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326482.jpg)

[https://docs.rs/tokio/1.7.1/tokio/io/trait.AsyncRead.html](https://docs.rs/tokio/1.7.1/tokio/io/trait.AsyncRead.html)

[https://docs.rs/tokio/1.7.1/tokio/io/trait.AsyncWrite.html](https://docs.rs/tokio/1.7.1/tokio/io/trait.AsyncWrite.html)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326758.jpg)

[https://docs.rs/futures/0.3.15/futures/prelude/stream/trait.Stream.html](https://docs.rs/futures/0.3.15/futures/prelude/stream/trait.Stream.html)

[https://docs.rs/futures/0.3.15/futures/prelude/trait.Sink.html](https://docs.rs/futures/0.3.15/futures/prelude/trait.Sink.html)

以上是培训（1）拾遗

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326583.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326123.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326934.jpg)

 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326229.jpg)

dashmap和hashmap的区别，hashmap如果要做并发，需要自己加mutex,dashmap，内部已经帮我们做好这些了。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326696.jpg)

[https://docs.rs/tokio-util/0.7.4/tokio_util/codec/index.html](https://docs.rs/tokio-util/0.7.4/tokio_util/codec/index.html)

[https://github.com/spacejam/sled](https://github.com/spacejam/sled)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326007.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326238.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326726.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326896.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326472.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326766.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326083.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326607.jpg)

![](images/WEBRESOURCE7788648d5e630467d91baf87097f2a53截图.png)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326112.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172326653.jpg)

[https://github.com/grpc/grpc/blob/master/doc/PROTOCOL-HTTP2.md](https://github.com/grpc/grpc/blob/master/doc/PROTOCOL-HTTP2.md)

[https://www.arewewebyet.org/](https://www.arewewebyet.org/)

[https://github.com/hyperium/tonic](https://github.com/hyperium/tonic)

[https://github.com/mcginty/snow](https://github.com/mcginty/snow)

[https://folyd.com/blog/rust-pin-unpin/](https://folyd.com/blog/rust-pin-unpin/)