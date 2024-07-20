libevent是一个时间通知库   网络事件、超时事件、信号事件

![](https://gitee.com/hxc8/images7/raw/master/img/202407190025976.jpg)

网络事件：

- C->connect->S

- C->send->S

- S->connect->DB

- C<-send->S<-send->DB

超时事件：

add_timer(expire,callback);

信号事件：

对信号进行处理

帮我们检测某个事件是否就绪，如果就绪就通知我们

事件处理流程：

- 注册事件

- 检测事件

- 触发执行事件

 io与事件到底什么关系？

阻塞io和非阻塞io

若io未就绪，io函数是否立刻返回

libevent使用的是非阻塞io,特性就是io未就绪，立刻返回，其中检测事件是通过io多路复用进行的

非阻塞io就需要看listenfd怎么处理了，它先去注册了第一个fd的事件，注册之后就交给了io多路复用去帮我们做检测，检测这个事件是否触发，是否就绪了，如果就绪了，我们就会提供一个回调函数，再回调函数里面再去操作这个io

[https://libevent.org/](https://libevent.org/)

下载下源码，然后按照Markdown里面的教程编译安装下。

ev_listenfd.c

```cpp
#include <event.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <stdio.h>
    /**
    1 socket
    2 bind
    3 listen
    4 注册 读事件  提供回调函数
    **/
void evconnlistener_cb(struct evconnlistener *lis,evutil_socket_t fd,struct sockaddr *sock,int socklen,void *ctx){
    printf("recv a connection\n");
}

int main(){
    struct event_base *base = event_base_new();
    struct sockadr_in sin = {0};
    //作为listenfd的地址
    sin.sin_family = AF_INET;
    sin.sin_port = htons(8080);
    
    struct evconnlistener *listener = evconnlistener_new_bind(base,evconnlistener_cb,base,LEV_OPT_REUSEABLE | LEV_OPT_CLOSE_ON_FREE,256,(struct sockaddr*)&sin,sizeof(struct sockaddr_in));
    
    event_base_loop(base,EVLOOP_NO_EXIT_ON_EMPTY);
    
    event_base_free(base);
    
    return 0;
}
```

编译：

gcc ev_listenfd.c -o ev_listenfd -levent

./ev_listenfd

起一个终端 telnet测试下

telnet 192.168.1.11 8080

然后就能发现server端打印出 recv a connection

再写一个服务端连接第三方数据库等，这里以redis为例

ev_connectfd.c

```cpp
#include <event.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <event2/bufferevent.h>
#include <event2/buffer.h>
void data_cb(struct bufferevent *bev,void * ctx){
    struct evbuffer *evbuf = bufferevent_get_input(bev);
    char * msg = evbuffer_readln(evbuf,NULL,EVBUFFER_EOL_CRLF_STRICT);
    printf("recv redis message %s\n",msg);
}
void event_cb(struct bufferevent * bev,short what,void * ctx){
    if(what & BEV_EVENT_CONNECTED){
        printf("connect redis success\n"); 
        //向redis发送一个PING
        bufferevent_write(bev,"*1\r\n$4\r\nPING\r\n",14); 
        //注册链接的读事件
        bufferevent_enabal(bev,EV_READ);
        //redis回复数据会在data_cb里面         
    }else{
        printf("connection error");    
    }
}
int main(){
    struct event_base *base = event_base_new();
    //作为客户端地址
    struct sockad  dr_in sin;
    sin.sin_addr.s_addr = inet_addr("127.0.0.1");
    sin.sin_family = AF_INET;
    sin.sin_port = htons(6379);
    
    struct bufferevent *redis = bufferevent_socket_new(base,-1,BEV_OPT_CLOSE_ON_FREE);
    bufferevent_socket_connect(redis,(struct sockaddr*)&sin,sizeof(sin));
    bufferevent_setcb(redis,data_cb,NULL,event_cb,base);
    
    event_base_loop(base,EVLOOP_NO_EXIT_ON_EMPTY);
    
    event_base_free(base);
    
    return 0;
}
```

在libevent里面writecallback是写水平线触发的回调函数，并不是写触发的回调函数

bufferevent是带buffer的，设置低水平线和高水平线，写入数据后，低于或者高于水平线就会触发函数。	 

启动redis

gcc ev_connectfd.c -o ev_connectfd -levent

./ev_connectfd

将会打印connect redis success