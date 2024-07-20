# 安装MySQL5.7

wget https://dev.mysql.com/get/Downloads/MySQL-5.7/mysql-5.7.34-linux-glibc2.12-x86_64.tar.gz

tar -xvf mysql-5.7.34-linux-glibc2.12-x86_64.tar.gz

mv mysql-5.7.34-linux-glibc2.12-x86_64 /usr/local/mysql

cd /usr/local

groupadd mysql

useradd -r -g mysql mysql

mkdir -p  /data/mysql

chown mysql:mysql -R /data/mysql

vim /etc/my.cnf

```javascript
[mysqld]
bind-address=0.0.0.0
port=3306
user=mysql
basedir=/usr/local/mysql
datadir=/data/mysql
socket=/tmp/mysql.sock
log-error=/data/mysql/mysql.err
pid-file=/data/mysql/mysql.pid
#character config
character_set_server=utf8mb4
symbolic-links=0
explicit_defaults_for_timestamp=true
```



cd /usr/local/mysql/bin/

./mysqld --defaults-file=/etc/my.cnf --basedir=/usr/local/mysql/ --datadir=/data/mysql/ --user=mysql --initialize

cat /data/mysql/mysql.err

cp /usr/local/mysql/support-files/mysql.server /etc/init.d/mysql

ps -ef|grep mysql

./mysql -u root -p

ln -s  /usr/local/mysql/bin/mysql    /usr/bin

详细步骤参考下方链接

https://blog.csdn.net/qq_37598011/article/details/93489404