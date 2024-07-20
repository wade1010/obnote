原文https://mp.weixin.qq.com/s?__biz=Mzg3MTA0NDQ1OQ==&mid=2247483702&idx=1&sn=50825426986120a0b306e0f6da176951&scene=21#wechat_redirect



select在Golang并发中扮演着重要的角色，如果你已经入门了select可以跳过这篇文章，关注下一篇文章“select进阶”。如果想看看，select是如何源自生活的，也可以阅读下这篇文章，几分钟就可以读完。

之前的文章都提到过，Golang的并发模型都来自生活，select也不例外。举个例子：我们都知道一句话，“吃饭睡觉打豆豆”，这一句话里包含了3件事：

1. 妈妈喊你吃饭，你去吃饭。

1. 时间到了，要睡觉。

1. 没事做，打豆豆。

在Golang里，select就是干这个事的：到吃饭了去吃饭，该睡觉了就睡觉，没事干就打豆豆。

结束发散，我们看下select的功能，以及它能做啥。

select功能

在多个通道上进行读或写操作，让函数可以处理多个事情，但1次只处理1个。以下特性也都必须熟记于心：

1. 每次执行select，都会只执行其中1个case或者执行default语句。

1. 当没有case或者default可以执行时，select则阻塞，等待直到有1个case可以执行。

1. 当有多个case可以执行时，则随机选择1个case执行。

1. case后面跟的必须是读或者写通道的操作，否则编译出错。

select长下面这个样子，由select和case组成，default不是必须的，如果没其他事可做，可以省略default。

 1func main() {

 2    readCh := make(chan int, 1)

 3    writeCh := make(chan int, 1)

 4

 5    y := 1

 6    select {

 7    case x := <-readCh:

 8        fmt.Printf("Read %d\n", x)

 9    case writeCh <- y:

10        fmt.Printf("Write %d\n", y)

11    default:

12        fmt.Println("Do what you want")

13    }

14}

我们创建了readCh和writeCh2个通道：

1. readCh中没有数据，所以case x := <-readCh读不到数据，所以这个case不能执行。

1. writeCh是带缓冲区的通道，它里面是空的，可以写入1个数据，所以case writeCh <- y可以执行。

1. 有case可以执行，所以default不会执行。

这个测试的结果是

1$ go run example.go

2Write 1

用打豆豆实践select

来，我们看看select怎么实现打豆豆：eat()函数会启动1个协程，该协程先睡几秒，事件不定，然后喊你吃饭，main()函数中的sleep是个定时器，每3秒喊你吃1次饭，select则处理3种情况：

1. 从eatCh中读到数据，代表有人喊我吃饭，我要吃饭了。

1. 从sleep.C中读到数据，代表闹钟时间到了，我要睡觉。

1. default是，没人喊我吃饭，也不到时间睡觉，我就打豆豆。

 1import (

 2    "fmt"

 3    "time"

 4    "math/rand"

 5)

 6

 7func eat() chan string {

 8    out := make(chan string)

 9    go func (){

10        rand.Seed(time.Now().UnixNano())

11        time.Sleep(time.Duration(rand.Intn(5)) * time.Second)

12        out <- "Mom call you eating"

13        close(out)

14    }()

15    return out

16}

17

18

19func main() {

20    eatCh := eat()

21    sleep := time.NewTimer(time.Second * 3)

22    select {

23    case s := <-eatCh:

24        fmt.Println(s)

25    case <- sleep.C:

26        fmt.Println("Time to sleep")

27    default:

28        fmt.Println("Beat DouDou")

29    }

30}

由于前2个case都要等待一会，所以都不能执行，所以执行default，运行结果一直是打豆豆：

1$ go run x.go

2Beat DouDou

现在我们不打豆豆了，你把default和下面的打印注释掉，多运行几次，有时候会吃饭，有时候会睡觉，比如这样：

1$ go run x.go

2Mom call you eating

3$ go run x.go

4Time to sleep

5$ go run x.go

6Time to sleep

select很简单但功能很强大，它让golang的并发功能变的更强大。这篇文章写的啰嗦了点，重点是为下一篇文章做铺垫，下一篇我们将介绍下select的高级用法。

select的应用场景很多，让我总结一下，放在下一篇文章中吧。