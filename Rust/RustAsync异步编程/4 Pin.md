![](https://gitee.com/hxc8/images3/raw/master/img/202407172251553.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251111.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251144.jpg)

上图 调用 read_into_buf的时候调用的是x的引用

所以如上图右边所示，ReadIntoBuf里面的buf字段就是对AsyncFuture里面x的引用。

如果AsyncFuture发生了移动，它从内存上移动了，那么它里面的x也会跟着移动，它的地址就变了，

但是ReadIntoBuf里面的buf指向的仍是AsyncFuture里面原来的地址，那么这个指针就失效了，这就会产生问题。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251986.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251798.jpg)

[https://rust-lang.github.io/async-book/04_pinning/01_chapter.html](https://rust-lang.github.io/async-book/04_pinning/01_chapter.html)

后面第一遍感觉没看懂，先跳过了暂不记录，回头再来更新