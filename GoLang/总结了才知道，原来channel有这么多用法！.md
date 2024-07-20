https://lessisbetter.site/2019/01/20/golang-channel-all-usage/



这篇文章总结了channel的11种常用操作，以一个更高的视角看待channel，会给大家带来对channel更全面的认识。

在介绍11种操作前，先简要介绍下channel的使用场景、基本操作和注意事项。

channel的使用场景

把channel用在数据流动的地方：

1. 消息传递、消息过滤

1. 信号广播

1. 事件订阅与广播

1. 请求、响应转发

1. 任务分发

1. 结果汇总

1. 并发控制

1. 同步与异步

1. …

channel的基本操作和注意事项

channel存在3种状态：

1. nil，未初始化的状态，只进行了声明，或者手动赋值为nil

1. active，正常的channel，可读或者可写

1. closed，已关闭，千万不要误认为关闭channel后，channel的值是nil

channel可进行3种操作：

1. 读

1. 写

1. 关闭

把这3种操作和3种channel状态可以组合出9种情况：

| 操作 | nil的channel | 正常channel | 已关闭channel |
| - | - | - | - |
| &lt;- ch | 阻塞 | 成功或阻塞 | 读到零值 |
| ch &lt;- | 阻塞 | 成功或阻塞 | panic |
| close(ch) | panic | 成功 | panic |


对于nil通道的情况，也并非完全遵循上表，有1个特殊场景：当nil的通道在select的某个case中时，这个case会阻塞，但不会造成死锁。

参考代码请看：https://dave.cheney.net/2014/03/19/channel-axioms

下面介绍使用channel的10种常用操作。

1. 使用for range读channel

场景

当需要不断从channel读取数据时。

原理

使用for-range读取channel，这样既安全又便利，当channel关闭时，for循环会自动退出，无需主动监测channel是否关闭，可以防止读取已经关闭的channel，造成读到数据为通道所存储的数据类型的零值。

用法

|   |   |
| - | - |
| 1<br>2<br>3 | for x := range ch{<br>    fmt.Println(x)<br>} |


2. 使用v,ok := <-ch + select操作判断channel是否关闭

场景

v,ok := <-ch + select操作判断channel是否关闭

原理

ok的结果和含义：

- true：读到通道数据，不确定是否关闭，可能channel还有保存的数据，但channel已关闭。

- false：通道关闭，无数据读到。

从关闭的channel读值读到是channel所传递数据类型的零值，这个零值有可能是发送者发送的，也可能是channel关闭了。

_, ok := <-ch与select配合使用的，当ok为false时，代表了channel已经close。下面解释原因， _,ok := <-ch对应的函数是func chanrecv(c *hchan, ep unsafe.Pointer, block bool) (selected, received bool)，入参block含义是当前goroutine是否可阻塞，当block为false代表的是select操作，不可阻塞当前goroutine的在channel操作，否则是普通操作（即_, ok不在select中）。返回值selected代表当前操作是否成功，主要为select服务，返回received代表是否从channel读到有效值。它有3种返回值情况：

1. block为false，即执行select时，如果channel为空，返回(false,false)，代表select操作失败，没接收到值。

1. 否则，如果channel已经关闭，并且没有数据，ep即接收数据的变量设置为零值，返回(true,false)，代表select操作成功，但channel已关闭，没读到有效值。

1. 否则，其他读到有效数据的情况，返回(true,ture)。

我们考虑_, ok := <-ch和select结合使用的情况。

情况1：当chanrecv返回(false,false)时，本质是select操作失败了，所以相关的case会阻塞，不会执行，比如下面的代码：

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12 | func main() {<br>&emsp;ch := make(chan int)<br>&emsp;select {<br>&emsp;case v, ok := &lt;-ch:<br>&emsp;&emsp;fmt.Printf("v: %v, ok: %v\\n", v, ok)<br>&emsp;default:<br>&emsp;&emsp;fmt.Println("nothing")<br>&emsp;}<br>}<br><br>// 结果：<br>// nothing |


情况2：下面的结果会是零值和false：

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13 | func main() {<br>&emsp;ch := make(chan int)<br><br>&emsp;// 增加关闭<br>&emsp;close(ch)<br><br>&emsp;select {<br>&emsp;case v, ok := &lt;-ch:<br>&emsp;&emsp;fmt.Printf("v: %v, ok: %v\\n", v, ok)<br>&emsp;}<br>}<br><br>// v: 0, ok: false |


情况3的received为true，即_, ok中的ok为true，不做讨论了，只讨论ok为false的情况。

最后ok为false的时候，只有情况2，此时channel必然已经关闭，我们便可以在select中用ok判断channel是否已经关闭。

用法

下面例子展示了，向channel写数据然后关闭，依然可以从已关闭channel读到有效数据，但channel关闭且没有数据时，读不到有效数据，ok为false，可以确定当前channel已关闭。

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29 | // demo\_select6.go<br>func main() {<br>&emsp;ch := make(chan int, 1)<br><br>&emsp;// 发送1个数据关闭channel<br>&emsp;ch &lt;- 1<br>&emsp;close(ch)<br>&emsp;print("close channel\\n")<br><br>&emsp;// 不停读数据直到channel没有有效数据<br>&emsp;for {<br>&emsp;&emsp;select {<br>&emsp;&emsp;case v, ok := &lt;-ch:<br>&emsp;&emsp;&emsp;print("v: ", v, ", ok:", ok, "\\n")<br>&emsp;&emsp;&emsp;if !ok {<br>&emsp;&emsp;&emsp;&emsp;print("channel is close\\n")<br>&emsp;&emsp;&emsp;&emsp;return<br>&emsp;&emsp;&emsp;}&emsp;<br>&emsp;&emsp;default:<br>&emsp;&emsp;&emsp;print("nothing\\n")<br>&emsp;&emsp;}<br>&emsp;}<br>}<br><br>// 结果<br>// close channel<br>// v: 1, ok:true<br>// v: 0, ok:false<br>// channel is close |


更多见golang_step_by_step/channel/ok仓库中ok和select的示例，或者阅读channel源码。

3. 使用select处理多个channel

场景

需要对多个通道进行同时处理，但只处理最先发生的channel时

原理

select可以同时监控多个通道的情况，只处理未阻塞的case。当通道为nil时，对应的case永远为阻塞，无论读写。特殊关注：普通情况下，对nil的通道写操作是要panic的。

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9 | // 分配job时，如果收到关闭的通知则退出，不分配job<br>func (h \*Handler) handle(job \*Job) {<br>    select {<br>    case h.jobCh&lt;-job:<br>        return <br>    case &lt;-h.stopCh:<br>        return<br>    }<br>} |


4. 使用channel的声明控制读写权限

场景

协程对某个通道只读或只写时

目的：

1. 使代码更易读、更易维护，

1. 防止只读协程对通道进行写数据，但通道已关闭，造成panic。

用法

- 如果协程对某个channel只有写操作，则这个channel声明为只写。

- 如果协程对某个channel只有读操作，则这个channe声明为只读。

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19 | // 只有generator进行对outCh进行写操作，返回声明<br>// &lt;-chan int，可以防止其他协程乱用此通道，造成隐藏bug<br>func generator(int n) &lt;-chan int {<br>    outCh := make(chan int)<br>    go func(){<br>        for i:=0;i&lt;n;i++{<br>            outCh&lt;-i<br>        }<br>    }()<br>    return outCh<br>}<br><br>// consumer只读inCh的数据，声明为&lt;-chan int<br>// 可以防止它向inCh写数据<br>func consumer(inCh &lt;-chan int) {<br>    for x := range inCh {<br>        fmt.Println(x)<br>    }<br>} |


5. 使用缓冲channel增强并发

场景

异步

原理

有缓冲通道可供多个协程同时处理，在一定程度可提高并发性。

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5 | // 无缓冲<br>ch1 := make(chan int)<br>ch2 := make(chan int, 0)<br>// 有缓冲<br>ch3 := make(chan int, 1) |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19 | // 使用5个`do`协程同时处理输入数据<br>func test() {<br>    inCh := generator(100)<br>    outCh := make(chan int, 10)<br><br>    for i := 0; i &lt; 5; i++ {<br>        go do(inCh, outCh)<br>    }<br><br>    for r := range outCh {<br>        fmt.Println(r)<br>    }<br>}<br><br>func do(inCh &lt;-chan int, outCh chan&lt;- int) {<br>    for v := range inCh {<br>        outCh &lt;- v \* v<br>    }<br>} |


6. 为操作加上超时

场景

需要超时控制的操作

原理

使用select和time.After，看操作和定时器哪个先返回，处理先完成的，就达到了超时控制的效果

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16 | func doWithTimeOut(timeout time.Duration) (int, error) {<br>&emsp;select {<br>&emsp;case ret := &lt;-do():<br>&emsp;&emsp;return ret, nil<br>&emsp;case &lt;-time.After(timeout):<br>&emsp;&emsp;return 0, errors.New("timeout")<br>&emsp;}<br>}<br><br>func do() &lt;-chan int {<br>&emsp;outCh := make(chan int)<br>&emsp;go func() {<br>&emsp;&emsp;// do work<br>&emsp;}()<br>&emsp;return outCh<br>} |


7. 使用time实现channel无阻塞读写

场景

并不希望在channel的读写上浪费时间

原理

是为操作加上超时的扩展，这里的操作是channel的读或写

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17 | func unBlockRead(ch chan int) (x int, err error) {<br>&emsp;select {<br>&emsp;case x = &lt;-ch:<br>&emsp;&emsp;return x, nil<br>&emsp;case &lt;-time.After(time.Microsecond):<br>&emsp;&emsp;return 0, errors.New("read time out")<br>&emsp;}<br>}<br><br>func unBlockWrite(ch chan int, x int) (err error) {<br>&emsp;select {<br>&emsp;case ch &lt;- x:<br>&emsp;&emsp;return nil<br>&emsp;case &lt;-time.After(time.Microsecond):<br>&emsp;&emsp;return errors.New("read time out")<br>&emsp;}<br>} |


注：time.After等待可以替换为default，则是channel阻塞时，立即返回的效果

8. 使用close(ch)关闭所有下游协程

场景

退出时，显示通知所有协程退出

原理

所有读ch的协程都会收到close(ch)的信号

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17 | func (h \*Handler) Stop() {<br>    close(h.stopCh)<br><br>    // 可以使用WaitGroup等待所有协程退出<br>}<br><br>// 收到停止后，不再处理请求<br>func (h \*Handler) loop() error {<br>    for {<br>        select {<br>        case req := &lt;-h.reqCh:<br>            go handle(req)<br>        case &lt;-h.stopCh:<br>            return<br>        }<br>    }<br>} |


9. 使用chan struct{}作为信号channel

场景

使用channel传递信号，而不是传递数据时

原理

没数据需要传递时，传递空struct

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6 | // 上例中的Handler.stopCh就是一个例子，stopCh并不需要传递任何数据<br>// 只是要给所有协程发送退出的信号<br>type Handler struct {<br>    stopCh chan struct{}<br>    reqCh chan \*Request<br>} |


10. 使用channel传递结构体的指针而非结构体

场景

使用channel传递结构体数据时

原理

channel本质上传递的是数据的拷贝，拷贝的数据越小传输效率越高，传递结构体指针，比传递结构体更高效

用法

|   |   |
| - | - |
| 1<br>2<br>3<br>4 | reqCh chan \*Request<br><br>// 好过<br>reqCh chan Request |


11. 使用channel传递channel

场景

使用场景有点多，通常是用来获取结果。

原理

channel可以用来传递变量，channel自身也是变量，可以传递自己。

用法

下面示例展示了有序展示请求的结果，另一个示例可以见另外文章的版本3。

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42 | package main<br><br>import (<br>&emsp;"fmt"<br>&emsp;"math/rand"<br>&emsp;"sync"<br>&emsp;"time"<br>)<br><br>func main() {<br>&emsp;reqs := []int{1, 2, 3, 4, 5, 6, 7, 8, 9}<br><br>&emsp;// 存放结果的channel的channel<br>&emsp;outs := make(chan chan int, len(reqs))<br>&emsp;var wg sync.WaitGroup<br>&emsp;wg.Add(len(reqs))<br>&emsp;for \_, x := range reqs {<br>&emsp;&emsp;o := handle(&amp;wg, x)<br>&emsp;&emsp;outs &lt;- o<br>&emsp;}<br><br>&emsp;go func() {<br>&emsp;&emsp;wg.Wait()<br>&emsp;&emsp;close(outs)<br>&emsp;}()<br><br>&emsp;// 读取结果，结果有序<br>&emsp;for o := range outs {<br>&emsp;&emsp;fmt.Println(&lt;-o)<br>&emsp;}<br>}<br><br>// handle 处理请求，耗时随机模拟<br>func handle(wg \*sync.WaitGroup, a int) chan int {<br>&emsp;out := make(chan int)<br>&emsp;go func() {<br>&emsp;&emsp;time.Sleep(time.Duration(rand.Intn(3)) \* time.Second)<br>&emsp;&emsp;out &lt;- a<br>&emsp;&emsp;wg.Done()<br>&emsp;}()<br>&emsp;return out<br>} |


