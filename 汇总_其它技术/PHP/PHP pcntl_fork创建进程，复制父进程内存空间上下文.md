```javascript
<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/2/28
 * Time: 21:02
 */
$a=1;
$ppid=posix_getpid(); //得到当前进程id
echo $ppid.PHP_EOL;

//创建多进程
for ($i=0;$i<1;$i++){
    $pid=pcntl_fork(); //创建成功会返回子进程id
    if($pid<0){
        exit('创建失败');
    }else if($pid>0){
        //父进程空间，返回子进程id
        $status=0;
        //$pid=pcntl_wait($status); //结束的子进程信息，阻塞状态
        echo "子进程回收了:$pid".PHP_EOL;
    }else{ //返回为0子进程空间
        //子进程创建成功
        sleep(10);
    }
}
 
```

循环一次 时 创建一个子进程  2次 3个 3次 7个 4次15个

因为复制了内存空间 创建下一个进程时之前创建的子进程这个时候角色也是父进程