https://mp.weixin.qq.com/s/UpYbmFTowjCPU83W3DxP6Q



我们都知道Golang并发优选channel，但channel不是万能的，Golang为我们提供了另一种选择：sync。通过这篇文章，你会了解sync包最基础、最常用的方法，至于sync和channel之争留给下一篇文章。

sync包提供了基础的异步操作方法，比如互斥锁（Mutex）、单次执行（Once）和等待组（WaitGroup），这些异步操作主要是为低级库提供，上层的异步/并发操作最好选用通道和通信。

sync包提供了：

1. Mutex：互斥锁

1. RWMutex：读写锁

1. WaitGroup：等待组

1. Once：单次执行

1. Cond：信号量

1. Pool：临时对象池

1. Map：自带锁的map

这篇文章是sync包的入门文章，所以只介绍常用的结构和方法：Mutex、RWMutex、WaitGroup、Once，而Cond、Pool和Map留给大家自行探索，或有需求再介绍。

互斥锁

常做并发工作的朋友对互斥锁应该不陌生，Golang里互斥锁需要确保的是某段时间内，不能有多个协程同时访问一段代码（临界区）。

互斥锁被称为Mutex，它有2个函数，Lock()和Unlock()分别是获取锁和释放锁，如下：

1type Mutex

2func (m *Mutex) Lock(){}

3func (m *Mutex) Unlock(){}

Mutex的初始值为未锁的状态，并且Mutex通常作为结构体的匿名成员存在。

经过了上面这么“官方”的介绍，举个例子：你在工商银行有100元存款，这张卡绑定了支付宝和微信，在中午12点你用支付宝支付外卖30元，你在微信发红包，抢到10块。银行需要按顺序执行上面两件事，先减30再加10或者先加10再减30，结果都是80，但如果同时执行，结果可能是，只减了30或者只加了10，即你有70元或者你有110元。前一个结果是你赔了，后一个结果是银行赔了，银行可不希望把这种事算错。

看看实际使用吧：创建一个银行，银行里存每个账户的钱，存储查询都加了锁操作，这样银行就不会算错账了。

银行的定义：

 1type Bank struct {

 2    sync.Mutex

 3    saving map[string]int // 每账户的存款金额

 4}

 5

 6func NewBank() *Bank {

 7    b := &Bank{

 8        saving: make(map[string]int),

 9    }

10    return b

11}

银行的存取钱：

 1// Deposit 存款

 2func (b *Bank) Deposit(name string, amount int) {

 3    b.Lock()

 4    defer b.Unlock()

 5

 6    if _, ok := b.saving[name]; !ok {

 7        b.saving[name] = 0

 8    }

 9    b.saving[name] += amount

10}

11

12// Withdraw 取款，返回实际取到的金额

13func (b *Bank) Withdraw(name string, amount int) int {

14    b.Lock()

15    defer b.Unlock()

16

17    if _, ok := b.saving[name]; !ok {

18        return 0

19    }

20    if b.saving[name] < amount {

21        amount = b.saving[name]

22    }

23    b.saving[name] -= amount

24

25    return amount

26}

27

28// Query 查询余额

29func (b *Bank) Query(name string) int {

30    b.Lock()

31    defer b.Unlock()

32

33    if _, ok := b.saving[name]; !ok {

34        return 0

35    }

36

37    return b.saving[name]

38}

模拟操作：小明支付宝存了100，并且同时花了20。

 1func main() {

 2    b := NewBank()

 3    go b.Deposit("xiaoming", 100)

 4    go b.Withdraw("xiaoming", 20)

 5    go b.Deposit("xiaogang", 2000)

 6

 7    time.Sleep(time.Second)

 8    fmt.Printf("xiaoming has: %d\n", b.Query("xiaoming"))

 9    fmt.Printf("xiaogang has: %d\n", b.Query("xiaogang"))

10}

结果：先存后花。

1➜  sync_pkg git:(master) ✗ go run mutex.go

2xiaoming has: 80

3xiaogang has: 2000

也可能是：先花后存，因为先花20，因为小明没钱，所以没花出去。

1➜  sync_pkg git:(master) ✗ go run mutex.go

2xiaoming has: 100

3xiaogang has: 2000

这个例子只是介绍了mutex的基本使用，如果你想多研究下mutex，那就去我的Github（阅读原文）下载下来代码，自己修改测试。Github中还提供了没有锁的例子，运行多次总能碰到错误：

fatal error: concurrent map writes

这是由于并发访问map造成的。

读写锁

读写锁是互斥锁的特殊变种，如果是计算机基本知识扎实的朋友会知道，读写锁来自于读者和写者的问题，这个问题就不介绍了，介绍下我们的重点：读写锁要达到的效果是同一时间可以允许多个协程读数据，但只能有且只有1个协程写数据。

也就是说，读和写是互斥的，写和写也是互斥的，但读和读并不互斥。具体讲，当有至少1个协程读时，如果需要进行写，就必须等待所有已经在读的协程结束读操作，写操作的协程才获得锁进行写数据。当写数据的协程已经在进行时，有其他协程需要进行读或者写，就必须等待已经在写的协程结束写操作。

读写锁是RWMutex，它有5个函数，它需要为读操作和写操作分别提供锁操作，这样就4个了：

- Lock()和Unlock()是给写操作用的。

- RLock()和RUnlock()是给读操作用的。

RLocker()能获取读锁，然后传递给其他协程使用。使用较少。

1type RWMutex

2func (rw *RWMutex) Lock(){}

3func (rw *RWMutex) RLock(){}

4func (rw *RWMutex) RLocker() Locker{}

5func (rw *RWMutex) RUnlock(){}

6func (rw *RWMutex) Unlock(){}

上面的银行实现不合理：大家都是拿手机APP查余额，可以同时几个人一起查呀，这根本不影响，银行的锁可以换成读写锁。存、取钱是写操作，查询金额是读操作，代码修改如下，其他不变：

 1type Bank struct {

 2    sync.RWMutex

 3    saving map[string]int // 每账户的存款金额

 4}

 5

 6// Query 查询余额

 7func (b *Bank) Query(name string) int {

 8    b.RLock()

 9    defer b.RUnlock()

10

11    if _, ok := b.saving[name]; !ok {

12        return 0

13    }

14

15    return b.saving[name]

16}

17

18func main() {

19    b := NewBank()

20    go b.Deposit("xiaoming", 100)

21    go b.Withdraw("xiaoming", 20)

22    go b.Deposit("xiaogang", 2000)

23

24    time.Sleep(time.Second)

25    print := func(name string) {

26        fmt.Printf("%s has: %d\n", name, b.Query(name))

27    }

28

29    nameList := []string{"xiaoming", "xiaogang", "xiaohong", "xiaozhang"}

30    for _, name := range nameList {

31        go print(name)

32    }

33

34    time.Sleep(time.Second)

35}

结果，可能不一样，因为协程都是并发执行的，执行顺序不固定：

1➜  sync_pkg git:(master) ✗ go run rwmutex.go

2xiaohong has: 0

3xiaozhang has: 0

4xiaogang has: 2000

5xiaoming has: 100

等待组

互斥锁和读写锁大多数人可能比较熟悉，而对等待组（WaitGroup）可能就不那么熟悉，甚至有点陌生，所以先来介绍下等待组在现实中的例子。

你们团队有5个人，你作为队长要带领大家打开藏有宝藏的箱子，但这个箱子需要4把钥匙才能同时打开，你把寻找4把钥匙的任务，分配给4个队员，让他们分别去寻找，而你则守着宝箱，在这等待，等他们都找到回来后，一起插进钥匙打开宝箱。

这其中有个很重要的过程叫等待：等待一些工作完成后，再进行下一步的工作。如果使用Golang实现，就得使用等待组。

等待组是WaitGroup，它有3个函数:

- Add()：在被等待的协程启动前加1，代表要等待1个协程。

- Done()：被等待的协程执行Done，代表该协程已经完成任务，通知等待协程。

- Wait(): 等待其他协程的协程，使用Wait进行等待。

1type WaitGroup

2func (wg *WaitGroup) Add(delta int){}

3func (wg *WaitGroup) Done(){}

4func (wg *WaitGroup) Wait(){}

来，一起看下怎么用WaitGroup实现上面的问题。

队长先创建一个WaitGroup对象wg，每个队员都是1个协程， 队长让队员出发前，使用wg.Add()，队员出发寻找钥匙，队长使用wg.Wait()等待（阻塞）所有队员完成，某个队员完成时执行wg.Done()，等所有队员找到钥匙，wg.Wait()则返回，完成了等待的过程，接下来就是开箱。

结合之前的协程池的例子，修改成WG等待协程池协程退出，实例代码：

 1func leader() {

 2    var wg sync.WaitGroup

 3    wg.Add(4)

 4    for i := 0; i < 4; i++ {

 5        go follower(&wg, i)

 6    }

 7    wg.Wait()

 8

 9    fmt.Println("open the box together")

10}

11

12func follower(wg *sync.WaitGroup, id int) {

13    fmt.Printf("follwer %d find key\n", id)

14    wg.Done()

15}

结果:

1➜  sync_pkg git:(master) ✗ go run waitgroup.go

2follwer 3 find key

3follwer 1 find key

4follwer 0 find key

5follwer 2 find key

6open the box together

WaitGroup也常用在协程池的处理上，协程池等待所有协程退出，把上篇文章《Golang并发模型：轻松入门协程池》的例子改下：

 1package main

 2

 3import (

 4    "fmt"

 5    "sync"

 6)

 7

 8func main() {

 9    var once sync.Once

10    onceBody := func() {

11        fmt.Println("Only once")

12    }

13    done := make(chan bool)

14    for i := 0; i < 10; i++ {

15        go func() {

16            once.Do(onceBody)

17            done <- true

18        }()

19    }

20    for i := 0; i < 10; i++ {

21        <-done

22    }

23}

单次执行

在程序执行前，通常需要做一些初始化操作，但触发初始化操作的地方是有多处的，但是这个初始化又只能执行1次，怎么办呢？

使用Once就能轻松解决，once对象是用来存放1个无入参无返回值的函数，once可以确保这个函数只被执行1次。

1type Once

2func (o *Once) Do(f func()){}

直接把官方代码给大家搬过来看下，once在10个协程中调用，但once中的函数onceBody()只执行了1次：

 1package main

 2

 3import (

 4    "fmt"

 5    "sync"

 6)

 7

 8func main() {

 9    var once sync.Once

10    onceBody := func() {

11        fmt.Println("Only once")

12    }

13    done := make(chan bool)

14    for i := 0; i < 10; i++ {

15        go func() {

16            once.Do(onceBody)

17            done <- true

18        }()

19    }

20    for i := 0; i < 10; i++ {

21        <-done

22    }

23}

结果：

1➜  sync_pkg git:(master) ✗ go run once.go

2Only once

示例源码

本文所有示例源码，及历史文章、代码都存储在Github：https://github.com/Shitaibin/golang_step_by_step/tree/master/sync_pkg

下期预告

这次先介绍入门的知识，下次再介绍一些深入思考、最佳实践，不能一口吃个胖子，咱们慢慢来，顺序渐进。

下一篇我以这些主题进行介绍，欢迎关注：

1. 哪个协程先获取锁

1. 一定要用锁吗

1. 锁与通道的选择