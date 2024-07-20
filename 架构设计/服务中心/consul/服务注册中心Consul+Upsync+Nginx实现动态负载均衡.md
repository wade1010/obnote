一、安装环境

1、consul 也是用docker安装 可以参考http://note.youdao.com/s/85W25KQr

并启动 



2、nginx 



wget http://nginx.org/download/nginx-1.19.5.tar.gz



tar -zxvf nginx-1.19.5.tar.gz





cd nginx-1.19.5



下载 nginx-upsync-module



git clone https://github.com/weibocom/nginx-upsync-module.git upsync





./configure --prefix=/usr/local/nginx19 --add-module=./upsync





make && make install 





cd /usr/local/nginx19



vim conf/nginx.conf



```javascript
http {
     upstream test {
        server 127.0.0.1:11111;#默认的不要改
        upsync 192.168.1.10:8511/v1/kv/upstreams/test upsync_timeout=6m upsync_interval=500ms upsync_type=consul strong_dependency=off;
        upsync_dump_path /usr/local/nginx19/conf/servers/servers_test.conf;
    }

      server {
        listen       8084;
        server_name  localhost;

      
        location / {
           proxy_pass http://test;
        } 
        location /upstream_list {
             upstream_show;
     	}
}
```



其他选项我都删了，需要的自己添加



保存后退出然后新建目录 和文件



mkdir /usr/local/nginx19/conf/servers



touch /usr/local/nginx19/conf/servers/servers_test.conf



重启nginx 



curl http://192.168.1.52:8084/hi



发现报错没有返回结果





因为我们没有给consul设置KV





打开postman



![](https://gitee.com/hxc8/images7/raw/master/img/202407190746808.jpg)







其中 8511端口是我consul使用的 ，默认是8500，根据自己的改



使用PUT方式





返回true表明存储成功





再 curl http://192.168.1.52:8084/hi



发现有结果了





接下来就是动态扩容咯



加入第二台



![](https://gitee.com/hxc8/images7/raw/master/img/202407190746943.jpg)



再 curl http://192.168.1.52:8084/hi



发现结果不同了。(自己给两个服务器的相同结果给上不同的返回结果，好区分)





# 不用postman 也可以的使用命令来操作



## #add

直接添加

curl -X PUT http://192.168.1.10:8511/v1/kv/upstreams/test/192.168.1.10:18307



或者加参数

curl -X PUT -d '{"weight":2, "max_fails":2, "fail_timeout":10, "down":0}' http://192.168.1.10:8511/v1/kv/upstreams/test/192.168.1.10:18307



curl -X PUT -d '{"weight":1, "max_fails":2, "fail_timeout":10, "down":0}' http://192.168.1.10:8511/v1/kv/upstreams/test/192.168.1.10:18308

## #delete

curl -X DELETE http://192.168.1.10:8511/v1/kv/upstreams/test/192.168.1.10:18307

curl -X DELETE http://192.168.1.10:8511/v1/kv/upstreams/test/192.168.1.10:18308  









---

另外一个方法



```javascript
upstream test {
        upsync 192.168.1.10:8511/v1/kv/upstreams/test/ upsync_timeout=6m upsync_interval=500ms upsync_type=consul strong_dependency=off;
        upsync_dump_path /usr/local/nginx19/conf/servers/servers_test.conf;
        include /usr/local/nginx19/conf/servers/servers_test.conf;
    } 
```



需要先在 /usr/local/nginx19/conf/servers/servers_test.conf 里面添加一个server  不添加 Nginx重启报错



vim /usr/local/nginx19/conf/servers/servers_test.conf



添加下面内容



server 192.168.1.10:18308;





再就是重启Nginx



接下来步骤就跟上面一样了。通过命令添加或者删除



---

还有一个方法



编译的时候 添加upsync，upstream-check 两个模块



git clone https://github.com/yaoweibin/nginx_upstream_check_module.git upstream_check



自己去研究吧。感觉加一个模块就可以了。