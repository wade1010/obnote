原文地址 https://mp.weixin.qq.com/s?__biz=Mzg3MTA0NDQ1OQ==&mid=2247483714&idx=1&sn=383e060fbf824d956dab6dc0a5555352&scene=21#wechat_redirect



前一篇文章《Golang并发模型：轻松入门select》介绍了select的作用和它的基本用法，这次介绍它的3个进阶特性。

1. nil的通道永远阻塞

1. 如何跳出for-select

1. select{}阻塞

nil的通道永远阻塞

当case上读一个通道时，如果这个通道是nil，则该case永远阻塞。这个功能有1个妙用，select通常处理的是多个通道，当某个读通道关闭了，但不想select再继续关注此case，而是关注其他case，把该通道设置为nil即可。

下面是一个合并程序等待两个输入通道都关闭后才退出的例子，就使用了这个特性。

func combine(inCh1, inCh2 <-chan int) <-chan int {

    // 输出通道

    out := make(chan int)



    // 启动协程合并数据

    go func() {

        defer close(out)

        for {

            select {

            case x, open := <-inCh1:

                if !open {

                    inCh1 = nil

                    continue

                }

                out<-x

            case x, open := <-inCh2:

                if !open {

                    inCh2 = nil

                    continue

                }

                out<-x

            }



            // 当ch1和ch2都关闭是才退出

            if inCh1 == nil && inCh2 == nil {

                break

            }

        }

    }()



    return out

}

如何跳出for-select

在select内的break并不能跳出for-select循环。看下面的例子，consume函数从通道inCh不停读数据，期待在inCh关闭后退出for-select循环，但结果是永远没有退出。

func consume(inCh <-chan int) {

    i := 0

    for {

        fmt.Printf("for: %d\n", i)

        select {

        case x, open := <-inCh:

            if !open {

                break

            }

            fmt.Printf("read: %d\n", x)

        }

        i++

    }



    fmt.Println("combine-routine exit")

}

运行结果：

➜ go run x.go

for: 0

read: 0

for: 1

read: 1

for: 2

read: 2

for: 3

gen exit

for: 4

for: 5

for: 6

for: 7

for: 8

... // never stop

既然break不能跳出for-select，那怎么办呢？给你3个锦囊：

1. 在满足条件的case内，使用return介绍协程，如果有结尾工作，尝试交给defer。

1. 在select外for内使用break挑出循环，如combine函数。

1. 使用goto，goto没有那么可怕，适当使用。

select{}永远阻塞

select{}的效果等价于直接从刚创建的通道读数据：

ch := make(chan int)

<-ch

但是，这个写起来多麻烦啊！没select{}简洁啊。

但是，永远阻塞能有什么用呢！？

当你开发一个并发程序的时候，main函数千万不能在子协程干完活前退出啊，不然所有的协程都被迫退出了，还怎么提供服务呢？

比如，写了个Web服务程序，端口监听、后端处理等等都在子协程跑起来了，main函数这时候能退出吗？

select应用场景

最后，介绍下我常用的select场景：

1. 无阻塞的读、写通道。即使通道是带缓存的，也是存在阻塞的情况，使用select可以完美的解决阻塞读写，这篇文章我之前发在了个人博客，后面给大家介绍下。

1. 给某个请求/处理/操作，设置超时时间，一旦超时时间内无法完成，则停止处理。

1. select本色：多通道处理。