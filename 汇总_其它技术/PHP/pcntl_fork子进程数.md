//子进程数 2的N次方-1个





<?php

$n = 3;

$ppid = posix_getpid(); //得到当前进程id

echo $ppid . PHP_EOL;

//创建多进程

for ($i = 0; $i < $n; $i++) {

    $pid = pcntl_fork();//创建成功会返回子进程id

    if ($pid < 0) {

        exit('创建失败');

    } else if ($pid > 0) {

        echo $pid, PHP_EOL;

        //父进程空间，返回子进程id

        $status = 0;

        $pid = pcntl_wait($status); //结束的子进程信息，阻塞状态

        echo "子进程回收了:$pid" . PHP_EOL;

    } else { //返回为0子进程空间

        //子进程创建成功

        sleep(10);

    }

}

//子进程数 2的N次方-1个

