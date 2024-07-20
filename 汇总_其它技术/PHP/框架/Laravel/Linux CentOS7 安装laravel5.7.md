## 安装composer


```
php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');"
```



```
php composer-setup.php
```




```
php -r "unlink('composer-setup.php');"
```

上述 3 条命令的作用依次是：

下载安装脚本 － composer-setup.php － 到当前目录。

执行安装过程。

删除安装脚本。


#### 全局安装


```
sudo mv composer.phar /usr/local/bin/composer
```


### 验证

composer -v

 报错如下
 
 
```
PHP Warning:  proc_get_status() has been disabled for security reasons in phar:///usr/local/bin/composer/vendor/symfony/process/Process.php on line 1279

Warning: proc_get_status() has been disabled for security reasons in phar:///usr/local/bin/composer/vendor/symfony/process/Process.php on line 1279
```

打开php.ini文件，搜索 disable_functions，找到如下类似内容：


```
disable_functions=passthru,exec,system,chroot,scandir,chgrp,chown,shell_exec,proc_get_status,proc_open,popen,ini_alter,ini_restore,dl,openlog,syslog,readlink,symlink,popepassthru,stream_socket_server
找到proc_get_status并删除然后重启php服务。
```

## 安装laravel

```
composer global require laravel/installer
```

#### 官方有下面这段话

确保 Composer 的全局 vendor bin 目录包含在系统 $PATH 路径中
我是这么做的

> vim ~/.zshrc （看你的文件了。我是用ZSH）

最后加入


```
export PATH="$HOME/.config/composer/vendor/bin:$PATH"
```


然后全局使用laravel命令


## 创建项目 这是使用的是composer安装


```
composer create-project --prefer-dist laravel/laravel yourprojectname "5.7.*"
```


#### 如果慢就是用中国镜像 


```
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```

#### ！！！！结果发现国内镜像没有这个5.7版本了 重新切回官方源


```
composer config -g --unset repos.packagist
```


## 启动项目


> cd laravel

> php artisan serve

报错

```
php artisan serve
Laravel development server started: <http://127.0.0.1:8000>

   ErrorException  : passthru() has been disabled for security reasons

  at /opt/webroot/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Console/ServeCommand.php:39
    35|         chdir(public_path());
    36| 
    37|         $this->line("Laravel development server started: <http://{$this->host()}:{$this->port()}>");
    38| 
  > 39|         passthru($this->serverCommand(), $status);
    40| 
    41|         return $status;
    42|     }
    43| 

  Exception trace:

  1   passthru("'/usr/local/php/bin/php' -S 127.0.0.1:8000 '/opt/webroot/laravel/server.php'")
      /opt/webroot/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Console/ServeCommand.php:39

  2   Illuminate\Foundation\Console\ServeCommand::handle()
      /opt/webroot/laravel/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:29

  Please use the argument -v to see more details.
```


### 解决

打开php.ini文件，搜索 disable_functions  去掉 passthru

> php artisan serve  启动服务