

![](https://gitee.com/hxc8/images8/raw/master/img/202407191059376.jpg)





![](https://gitee.com/hxc8/images8/raw/master/img/202407191059231.jpg)







IO多路复用

![](https://gitee.com/hxc8/images8/raw/master/img/202407191059796.jpg)





public function runAll(){

//epoll(IO复用机制)



//监听服务端socket，当服务器可读的时候触发(连接的时候)

swoole_event_add($this->server,function($fd){}

$clientSocket=stream_socket_accept($fd);//阻塞获取客户端的fd



//当客户端状态发生改变的时候触发（数据发送的时候）

swoole_event_add($clientSocket,function($fd){

if(feof($fd)||!is_resource($fd)){

//删除时间

swoole_event_del($fd);

//触发onclose事件

fclose($fd);



}

});

);

}





epoll



用户空间    （swoole 调用epoll_create()在内核生成一棵红黑树） 			|	          内核空间



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059275.jpg)



















