#### 服务器要求
官方文档要求
```
不过，如果你没有使用 Homestead 的话，那么就需要确认自己的环境满足以下要求：

PHP >= 7.1.3
PHP OpenSSL 扩展
PHP PDO 扩展
PHP Mbstring 扩展
PHP Tokenizer 扩展
PHP XML 扩展
PHP Ctype 扩展
PHP JSON 扩展
PHP BCMath 扩展
```

但是我发现我一个都没启动也没事


#### 使用composer安装 

之前已经brew安装compose

安装laravel安装器
>composer global require laravel/installer


官方有下面这段话
>确保 Composer 的全局 vendor bin 目录包含在系统 $PATH 路径中

我是这么做的

>vim ~/.zshrc   （看你的文件了。我是用ZSH）

最后加入

>export PATH="$HOME/.composer/vendor/bin:$PATH"

然后全局使用laravel命令


#### laravel创建项目

>laravel new myapp

#### 赋予权限

>sudo chmod -R 755 storage 

>sudo chmod -R 755 bootstrap/cache


##### 有用的命令

>php artisan list make