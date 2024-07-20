## brew安装

> brew install rabbitmq

> brew install rabbitmq-c 

> pecl install amqp


第三个命令安装的时候会提醒你输入librabbitmq的path


```
pecl install amqp      
downloading amqp-1.10.2.tgz ...
Starting to download amqp-1.10.2.tgz (107,350 bytes)
.........................done: 107,350 bytes
28 source files, building
running: phpize
Configuring for:
PHP Api Version:         20180731
Zend Module Api No:      20180731
Zend Extension Api No:   320180731
Set the path to librabbitmq install prefix [autodetect] :
```

这里第二个命令是安装在 下面的路径  后面的版本号可能有变 根据自己改变

/usr/local/Cellar/rabbitmq-c/0.10.0

输入进去 按回车就行 

虽然上面写着 autodetect  但是我安装第一遍的时候  没输入librabbitmq的path没成功。不纠结了。


反正有这个就行


```
Build process completed successfully
Installing '/usr/local/Cellar/php@7.3/7.3.22/pecl/20180731/amqp.so'
install ok: channel://pecl.php.net/amqp-1.10.2
Extension amqp enabled in php.ini
```


```
php -m | grep amqp                                                       1 ↵
amqp
```


检查也有此扩展 OK