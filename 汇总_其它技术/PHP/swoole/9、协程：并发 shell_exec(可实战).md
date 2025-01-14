在PHP程序中经常需要用shell_exec执行一些命令，而普通的shell_exec是阻塞的，如果命令执行时间过长，那可能会导致进程完全卡住。 在Swoole4协程环境下可以用Co::exec并发地执行很多命令。

本文基于Swoole-4.2.9和PHP-7.2.9版本

协程示例 (语法借鉴自Golang)

<?php
$c = 10;
while($c--) {
    go(function () {
        //这里使用 sleep 5 来模拟一个很长的命令
        co::exec("sleep 5");
    });
}


返回值

Co::exec执行完成后会恢复挂起的协程，并返回命令的输出和退出的状态码。

var_dump(co::exec("sleep 5"));


协程结果

htf@htf-ThinkPad-T470p:~/workspace/debug$ time php t.php

real    0m5.089s
user    0m0.067s
sys 0m0.038s
htf@htf-ThinkPad-T470p:~/workspace/debug$


只用了 5秒，程序就跑完了。

下面换成 PHP 的 shell_exec 来试试。

阻塞代码

<?php
$c = 10;
while($c--) {
    //这里使用 sleep 5 来模拟一个很长的命令
    shell_exec("sleep 5");
}


使用nohup或&转为后台执行，无法得到命令执行的结果和输出，本文不对此进行深度探讨

阻塞结果

htf@htf-ThinkPad-T470p:~/workspace/debug$ time php s.php 

real    0m50.119s
user    0m0.066s
sys 0m0.058s
htf@htf-ThinkPad-T470p:~/workspace/debug$ 


可以看到阻塞版本花费了50秒才完成。Swoole4提供的协程，是并发编程的利器。在工作中很多地方都可以使用协程，实现并发程序，大大提升程序性能。