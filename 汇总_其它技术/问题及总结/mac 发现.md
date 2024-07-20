ps aux|grep php-fpm

看到的是php5.6版本，而且我怎么杀进程都不行，会自动重启



是以守护进程启动的，所以会自动重启



解决办法



brew services stop php@5.6





brew service start php@7.3  （换成你的版本就行了）





或者 直接

brew service stop php



brew service start php

