

```javascript
<?php
class Worker{
    //监听socket
    protected $socket = NULL;
    //连接事件回调
    public $onConnect = NULL;
    public  $reusePort=1;
    //接收消息事件回调
    public $onMessage = NULL;
    public $workerNum=3; //子进程个数
    public  $allSocket; //存放所有socket
    public  $addr;
    protected $worker_pid; //子进程pid
    protected  $master_pid;//主进程id
    public function __construct($socket_address) {
        //监听地址+端口
        $this->addr=$socket_address;
        $this->master_pid=posix_getpid();
    }

    public function start() {
        //获取配置文件
        $this->watch();
        $this->fork($this->workerNum);
        $this->monitorWorkers(); //监视程序,捕获信号,监视worker进程
    }

    /**
     * 文件监视,自动重启
     */
    protected  function watch(){
        $init=inotify_init(); //初始化
        $files=get_included_files();
        foreach ($files as $file){
            inotify_add_watch($init,$file,IN_MODIFY); //监视相关的文件
        }
        //监听
        swoole_event_add($init,function ($fd){
            $events=inotify_read($fd);
            if(!empty($events)){
                posix_kill($this->master_pid,SIGUSR1);
            }
        });
    }
    /**
     * 捕获信号
     * 监视worker进程.拉起进程
     */
    public function monitorWorkers(){
         //注册信号事件回调,是不会自动执行的
        // reload
        pcntl_signal(SIGUSR1, array($this, 'signalHandler'),false); //重启woker进程信号
        //ctrl+c
        $status=0;
        while (1){
            // 当发现信号队列,一旦发现有信号就会触发进程绑定事件回调
            pcntl_signal_dispatch();
            $pid = pcntl_wait($status); //当信号到达之后就会被中断
            //如果进程不是正常情况下的退出,重启子进程,我想要维持子进程个数
//            if($pid>1 && $pid != $this->master_pid  && !pcntl_wifexited($status)){
//                     $index=array_search($pid,$this->worker_pid);
//                     $this->fork(1);
//                     var_dump('拉起子进程');
//                     unset($this->worker_pid[$index]);
//            }
            pcntl_signal_dispatch();
            //进程重启的过程当中会有新的信号过来,如果没有调用pcntl_signal_dispatch,信号不会被处理

        }
    }

    public function signalHandler($sigo){
        switch ($sigo){
            case SIGUSR1:
                $this->reload();
                echo "收到重启信号";
                break;
        }

    }
    public function fork($worker_num){
        for ($i=0;$i<$worker_num;$i++){

            $test=include 'index.php';
            var_dump($test);
            $pid=pcntl_fork(); //创建成功会返回子进程id
            if($pid<0){
                exit('创建失败');
            }else if($pid>0){
                //父进程空间，返回子进程id
                $this->worker_pid[]=$pid;
            }else{ //返回为0子进程空间
                $this->accept();//子进程负责接收客户端请求
                exit;
            }
        }
        //放在父进程空间，结束的子进程信息，阻塞状态

    }
    public  function  accept(){
        $opts = array(
            'socket' => array(
                'backlog' =>10240, //成功建立socket连接的等待个数
            ),
        );

      $context = stream_context_create($opts);
      //开启多端口监听,并且实现负载均衡
      stream_context_set_option($context,'socket','so_reuseport',1);
      stream_context_set_option($context,'socket','so_reuseaddr',1);
      $this->socket=stream_socket_server($this->addr,$errno,$errstr,STREAM_SERVER_BIND|STREAM_SERVER_LISTEN,$context);

        //第一个需要监听的事件(服务端socket的事件),一旦监听到可读事件之后会触发
        swoole_event_add($this->socket,function ($fd){
                $clientSocket=stream_socket_accept($fd);
                //触发事件的连接的回调
                if(!empty($clientSocket) && is_callable($this->onConnect)){
                    call_user_func($this->onConnect,$clientSocket);
                }
            //监听客户端可读
             swoole_event_add($clientSocket,function ($fd){
                //从连接当中读取客户端的内容
                $buffer=fread($fd,1024);
                //如果数据为空，或者为false,不是资源类型
                if(empty($buffer)){
                    if(!is_resource($fd) || feof($fd) ){
                        //触发关闭事件
                        fclose($fd);
                    }
                }
                //正常读取到数据,触发消息接收事件,响应内容
                if(!empty($buffer) && is_callable($this->onMessage)){
                    call_user_func($this->onMessage,$fd,$buffer);
                }


            });
        });
    }


    /**
     * 重启worker进程
     */
    public  function reload(){

        foreach ($this->worker_pid as $index=>$pid){
            posix_kill($pid,SIGKILL); //结束进程
            var_dump("杀掉的子进程",$pid);
            unset($this->worker_pid[$index]);
            $this->fork(1); //重新拉起worker
        }

    }

    //捕获信号之后重启worker进程

}


//ps -ef | grep php | grep -v grep | awk '{print $2}' | xargs kill -s 9
$worker = new Worker('tcp://0.0.0.0:9800');

//开启多进程的端口监听
$worker->reusePort = true;


//连接事件
$worker->onConnect = function ($fd) {
    //echo '连接事件触发',(int)$fd,PHP_EOL;
};

$worker->onTask = function ($fd) {
    //echo '连接事件触发',(int)$fd,PHP_EOL;
};

//消息接收
$worker->onMessage = function ($conn, $message) {
    //事件回调当中写业务逻辑
    // $a=include 'index.php';
    // var_dump($a);

    //var_dump($conn,$message);
    $content="我是peter";
    $http_resonse = "HTTP/1.1 200 OK\r\n";
    $http_resonse .= "Content-Type: text/html;charset=UTF-8\r\n";
    $http_resonse .= "Connection: keep-alive\r\n"; //连接保持
    $http_resonse .= "Server: php socket server\r\n";
    $http_resonse .= "Content-length: ".strlen($content)."\r\n\r\n";
    $http_resonse .= $content;
    fwrite($conn, $http_resonse);
};

$worker->start(); //启动


```

