http://nginx.org/en/docs/http/ngx_http_rewrite_module.html#rewrite



重写中用到的指令

if  (条件) {}  设定条件,再进行重写

set #设置变量

return #返回状态码

break #跳出rewrite

rewrite #重写

 

 

If  语法格式

If 空格 (条件) {

    重写模式

}

 

条件又怎么写?

答:3种写法

1: “=”来判断相等, 用于字符串比较

2: “~” 用正则来匹配(此处的正则区分大小写)

   ~* 不区分大小写的正则

3: -f -d -e来判断是否为文件,为目录,是否存在.

 

例子:

 

            if  ($remote_addr = 192.168.1.100) {

                return 403;

            }

 

 

 if ($http_user_agent ~ MSIE) {

                rewrite ^.*$ /ie.htm;

                break; #(不break会循环重定向)

 }

 

             if (!-e $document_root$fastcgi_script_name) {

                rewrite ^.*$ /404.html break;

            } 

            注, 此处还要加break,

以 xx.com/dsafsd.html这个不存在页面为例,

我们观察访问日志, 日志中显示的访问路径,依然是GET /dsafsd.html HTTP/1.1

提示: 服务器内部的rewrite和302跳转不一样.

跳转的话URL都变了,变成重新http请求404.html, 而内部rewrite, 上下文没变,

就是说 fastcgi_script_name 仍然是 dsafsd.html,因此 会循环重定向.

set 是设置变量用的, 可以用来达到多条件判断时作标志用.

达到apache下的 rewrite_condition的效果

 

如下: 判断IE并重写,且不用break; 我们用set变量来达到目的

if ($http_user_agent ~* msie) {

                set $isie 1;

            }

 

            if ($fastcgi_script_name = ie.html) {

                set $isie 0;

            }

 

            if ($isie 1) {

                rewrite ^.*$ ie.html;

            }

 



Rewrite语法

Rewrite 正则表达式  定向后的位置 模式

 

Goods-3.html ---->Goods.php?goods_id=3

goods-([\d]+)\.html ---> goods.php?goods_id =$1  

 

location /ecshop {

index index.php;

rewrite goods-([\d]+)\.html$ /ecshop/goods.php?id=$1;

rewrite article-([\d]+)\.html$ /ecshop/article.php?id=$1;

rewrite category-(\d+)-b(\d+)\.html /ecshop/category.php?id=$1&brand=$2;

 

rewrite category-(\d+)-b(\d+)-min(\d+)-max(\d+)-attr([\d\.]+)\.html /ecshop/category.php?id=$1&brand=$2&price_min=$3&price_max=$4&filter_attr=$5;

 

rewrite category-(\d+)-b(\d+)-min(\d+)-max(\d+)-attr([\d+\.])-(\d+)-([^-]+)-([^-]+)\.html /ecshop/category.php?id=$1&brand=$2&price_min=$3&price_max=$4&filter_attr=$5&page=$6&sort=$7&order=$8;

}

 

注意:用url重写时, 正则里如果有”{}”,正则要用双引号包起来