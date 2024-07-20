LNMPA代表的是Linux下Nginx、MySQL、PHP、Apache这种网站服务器架构，是结合LAMP与LNMP各自的优点而产生的新的网站服务器架构。



https://lnmp.org/install.html







wget http://soft.vpser.net/lnmp/lnmp1.7.tar.gz -cO lnmp1.7.tar.gz && tar zxf lnmp1.7.tar.gz && cd lnmp1.7 && LNMP_Auto="y" DBSelect="3" DB_Root_Password="123456" InstallInnodb="y" PHPSelect="10" SelectMalloc="1" ApacheSelect="2" ServerAdmin="webmaster@example.com" ./install.sh lnmpa