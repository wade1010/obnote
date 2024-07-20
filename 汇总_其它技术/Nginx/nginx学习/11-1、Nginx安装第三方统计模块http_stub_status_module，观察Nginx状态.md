进入到之前nginx编译包的根目录



➜  nginx-1.16.1 make clean          

rm -rf Makefile objs

➜  nginx-1.16.1 ./configure --help|grep status

  --with-http_stub_status_module     enable ngx_http_stub_status_module

➜  nginx-1.16.1 /usr/local/nginx/sbin/nginx -V                           

nginx version: nginx/1.16.1

built by gcc 4.8.5 20150623 (Red Hat 4.8.5-39) (GCC) 

configure arguments: --prefix=/usr/local/nginx --add-module=/usr/local/nginx/modules/ngx_http_consistent_hash





得到 命令



./configure --prefix=/usr/local/nginx --add-module=/usr/local/nginx/modules/ngx_http_consistent_hash --with-http_stub_status_module



make  





mv /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak





cp objs/nginx /usr/local/nginx/sbin



➜  nginx-1.16.1 ps aux | grep nginx

root      27309  0.0  0.0  25052    28 ?        S    10:35   0:00 nginx: master process ./sbin/nginx

nobody    55353  0.0  0.0  27372     0 ?        S    17:54   0:00 nginx: worker process

nobody    55354  0.0  0.0  27372     8 ?        S    17:54   0:00 nginx: worker process



kill -USR2 27309



kill -WINCH 27309



kill -QUIT 27309



可以参考 <<10-1、源码安装的nginx平滑升级及重新编译添加模块>>





使用



vim  nginx.conf



加入一个location



        location /status {

                stub_status on;

                access_log off;

                allow 192.168.1.52;

                allow 192.168.1.10;

                deny all;

        }













