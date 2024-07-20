Swoole4为PHP语言提供了强大的CSP协程编程模式。底层提供了3个关键词，可以方便地实现各类功能。

- Swoole4提供的PHP协程语法借鉴自Golang，在此向GO开发组致敬

- PHP+Swoole协程可以与Golang很好地互补。Golang：静态语言，严谨强大性能好，PHP+Swoole：动态语言，灵活简单易用

本文基于Swoole-4.2.9和PHP-7.2.9版本



关键词

- go ：创建一个协程

- chan ：创建一个通道

- defer ：延迟任务，在协程退出时执行，先进后出

这3个功能底层现全部为内存操作，没有任何IO资源消耗。就像PHP的Array一样是非常廉价的。如果有需要就可以直接使用。这与socket和file操作不同，后者需要向操作系统申请端口和文件描述符，读写可能会产生阻塞的IO等待。



并发执行

使用go创建协程，可以让test1和test2两个函数变成并发执行。

Swoole\Runtime::enableCoroutine();

go(function () 
{
    sleep(1);
    echo "b";
});

go(function () 
{
    sleep(2);
    echo "c";
});


Swoole\Runtime::enableCoroutine()作用是将PHP提供的stream、sleep、pdo、mysqli、redis等功能从同步阻塞切换为协程的异步IO

执行结果：

bchtf@LAPTOP-0K15EFQI:~$ time php co.php
bc
real    0m2.076s
user    0m0.000s
sys     0m0.078s
htf@LAPTOP-0K15EFQI:~$


可以看到这里只用了2秒就执行完成了。

- 顺序执行耗时等于所有任务执行耗时的总和 ：t1+t2+t3...

- 并发执行耗时等于所有任务执行耗时的最大值 ：max(t1, t2, t3, ...)



协程通信

有了go关键词之后，并发编程就简单多了。与此同时又带来了新问题，如果有2个协程并发执行，另外一个协程，需要依赖这两个协程的执行结果，如果解决此问题呢？

答案就是使用通道（Channel），在Swoole4协程中使用new chan就可以创建一个通道。通道可以理解为自带协程调度的队列。它有两个接口push和pop：

- push：向通道中写入内容，如果已满，它会进入等待状态，有空间时自动恢复

- pop：从通道中读取内容，如果为空，它会进入等待状态，有数据时自动恢复

使用通道可以很方便地实现并发管理。

```javascript
<?php
Swoole\Runtime::enableCoroutine();
$chan = new chan(2);

go(function() use ($chan){
        $result=[];
        for($i = 0; $i < 2; $i++){
                $result+=$chan->pop();
        }
        var_dump($result);
});

go(function()use($chan){
        sleep(5);
        $chan->push(['ss'=>1]);
});

go(function()use($chan){
        sleep(3);
        $chan->push(['gd'=>2]);
});

```



执行结果：

```javascript
[root@vmware40 swoole_demo]# time php chan_tongxing.php 
array(2) {
  ["gd"]=>
  int(2)
  ["ss"]=>
  int(1)
}

real	0m5.070s
user	0m0.024s
sys	0m0.032s

```



这里使用了chan来实现并发管理。

- 协程1循环两次对通道进行pop，因为队列为空，它会进入等待状态

- 协程2和协程3执行完成后，会push数据，协程1拿到了结果，继续向下执行



延迟任务(延迟任务，在协程退出时执行，先进后出)



在协程编程中，可能需要在协程退出时自动执行一些任务，做清理工作。类似于PHP的register_shutdown_function，在Swoole4中可以使用defer实现。

Swoole\Runtime::enableCoroutine();

go(function () {
    echo "a";
    defer(function () {
        echo "~a";
    });
    echo "b";
    defer(function () {
        echo "~b";
    });
    sleep(1);
    echo "c";
});


执行结果：

htf@LAPTOP-0K15EFQI:~/swoole-src/examples/5.0$ time php defer.php
abc~b~a
real    0m1.068s
user    0m0.016s
sys     0m0.047s
htf@LAPTOP-0K15EFQI:~/swoole-src/examples/5.0$


结语

Swoole4提供的Go + Chan + Defer为PHP带来了一种全新的CSP并发编程模式。灵活使用Swoole4提供的各项特性，可以解决工作中各类复杂功能的设计和开发。







