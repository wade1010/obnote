location 有”定位”的意思, 根据Uri来进行不同的定位.

在虚拟主机的配置中,是必不可少的,location可以把网站的不同部分,定位到不同的处理方式上.

比如, 碰到.php, 如何调用PHP解释器?  --这时就需要location

location 的语法

location [=|~|~*|^~] patt {

}

语法规则： location [=|~|~*|^~] /uri/ { … }

- = 开头表示精确匹配

- ^~ 开头表示uri以某个常规字符串开头，理解为匹配 url路径即可。nginx不对url做编码，因此请求为/static/20%/aa，可以被规则^~ /static/ /aa匹配到（注意是空格）。以xx开头

- ~ 开头表示区分大小写的正则匹配                     以xx结尾

- ~* 开头表示不区分大小写的正则匹配                以xx结尾

- !~和!~*分别为区分大小写不匹配及不区分大小写不匹配 的正则

- / 通用匹配，任何请求都会匹配到。



首先精确匹配 =-》其次以xx开头匹配^~-》然后是按文件中顺序的正则匹配-》最后是交给 / 通用匹配。

当有匹配成功时候，停止匹配，按当前匹配规则处理请求。

例子，有如下匹配规则：



```javascript
location = / {
   #规则A
}
location = /login {
   #规则B
}
location ^~ /static/ {
   #规则C
}
location ~ \.(gif|jpg|png|js|css)$ {
   #规则D，注意：是根据括号内的大小写进行匹配。括号内全是小写，只匹配小写
}
location ~* \.png$ {
   #规则E
}
location !~ \.xhtml$ {
   #规则F
}
location !~* \.xhtml$ {
   #规则G
}
location / {
   #规则H
}
```



那么产生的效果如下：

访问根目录/， 比如http://localhost/ 将匹配规则A

访问 http://localhost/login 将匹配规则B，http://localhost/register 则匹配规则H

访问 http://localhost/static/a.html 将匹配规则C

访问 http://localhost/a.gif, http://localhost/b.jpg 将匹配规则D和规则E，但是规则D顺序优先，规则E不起作用， 而 http://localhost/static/c.png 则优先匹配到 规则C

访问 http://localhost/a.PNG 则匹配规则E， 而不会匹配规则D，因为规则E不区分大小写。

访问 http://localhost/a.xhtml 不会匹配规则F和规则G，

http://localhost/a.XHTML不会匹配规则G，（因为!）。规则F，规则G属于排除法，符合匹配规则也不会匹配到，所以想想看实际应用中哪里会用到。

访问 http://localhost/category/id/1111 则最终匹配到规则H，因为以上规则都不匹配，这个时候nginx转发请求给后端应用服务器，比如FastCGI（php），tomcat（jsp），nginx作为方向代理服务器存在。

所以实际使用中，个人觉得至少有三个匹配规则定义，如下：



```javascript
#直接匹配网站根，通过域名访问网站首页比较频繁，使用这个会加速处理，官网如是说。
#这里是直接转发给后端应用服务器了，也可以是一个静态首页
# 第一个必选规则
location = / {
    proxy_pass http://tomcat:8080/index
}
 
# 第二个必选规则是处理静态文件请求，这是nginx作为http服务器的强项
# 有两种配置模式，目录匹配或后缀匹配,任选其一或搭配使用
location ^~ /static/ {                              //以xx开头
    root /webroot/static/;
}
location ~* \.(gif|jpg|jpeg|png|css|js|ico)$ {     //以xx结尾
    root /webroot/res/;
}
 
#第三个规则就是通用规则，用来转发动态请求到后端应用服务器
#非静态文件请求就默认是动态请求，自己根据实际把握
location / {
    proxy_pass http://tomcat:8080/
}
```

中括号可以不写任何参数,此时称为一般匹配

也可以写参数

因此,大类型可以分为3种

location = patt {} [精准匹配]

location patt{}  [一般匹配]

location ~ patt{} [正则匹配]

 



如何发挥作用?:

首先看有没有精准匹配,如果有,则停止匹配过程.

location = patt {

    config A

}

如果 $uri == patt,匹配成功，使用configA

   location = / {

              root   /var/www/html/;

             index  index.htm index.html;

        }

         

  location / {

             root   /usr/local/nginx/html;

            index  index.html index.htm;

  }

 

如果访问　　http://xxx.com/

定位流程是　

1: 精准匹配中　”/”   ,得到index页为　　index.htm

2: 再次访问 /index.htm , 此次内部转跳uri已经是”/index.htm” ,

根目录为/usr/local/nginx/html

3: 最终结果,访问了 /usr/local/nginx/html/index.htm

 



再来看,正则也来参与.

location / {

            root   /usr/local/nginx/html;

            index  index.html index.htm;

        }

 

location ~ image {

           root /var/www/image;

           index index.html;

}

 

如果我们访问  http://xx.com/image/logo.png

此时, “/” 与”/image/logo.png” 匹配

同时,”image”正则 与”image/logo.png”也能匹配,谁发挥作用?

正则表达式的成果将会使用.

 

图片真正会访问 /var/www/image/logo.png

 

 



location / {

             root   /usr/local/nginx/html;

             index  index.html index.htm;

         }

 

location /foo {

            root /var/www/html;

             index index.html;

}

我们访问 http://xxx.com/foo

 对于uri “/foo”,   两个location的patt,都能匹配他们

即 ‘/’能从左前缀匹配 ‘/foo’, ‘/foo’也能左前缀匹配’/foo’,

此时, 真正访问 /var/www/html/index.html

原因:’/foo’匹配的更长,因此使用之.;





![](https://gitee.com/hxc8/images7/raw/master/img/202407190801328.jpg)



普通命中后保存最长的命中结果，继续匹配正则，从上到下匹配到则返回，匹配不到则返回普通命中的，如果没有普通命中则返回根配置里面的 / 匹配