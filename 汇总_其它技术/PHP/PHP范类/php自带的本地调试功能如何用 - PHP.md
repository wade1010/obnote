php自带的本地调试功能怎么用？



这个简单的调试功能好象是在5.4以后版本提供的，以前用过，现在忘了怎么用了，有谁知道呢？



------解决思路----------------------



你是指Xdebug吗？



------解决思路----------------------



哦，你是指PHP内含的HTTPServer，这个没用过。这应该说是预览用的，应该不叫调试。。。。



------解决思路----------------------



cd $PHP_INSTALL_PATH  



./bin/php -S <addr>:<port> -t <docroot>  



如：  



前台运行：  



./bin/php -S localhost:80 -t /data/www/  



后台运行：  



./bin/php -S localhost:80 -t /data/www/ >> /tmp/access.log 2>&1 &  