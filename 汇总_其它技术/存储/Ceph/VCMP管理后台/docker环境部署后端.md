docker pull ubuntu

docker run -it -d -p 8000:8000 --name ubuntu ubuntu /bin/bash

docker exec -it ubuntu /bin/bash

apt-get update

apt-get install vim wget curl mariadb-server -y

/etc/init.d/mysql start

```
MYSQL_ROOT_NEWPASSWD="vClusters@2019"
mysqladmin -u root password ${MYSQL_ROOT_NEWPASSWD}
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs CHARACTER SET utf8 collate utf8_bin"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_warn"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_warn CHARACTER SET utf8 collate utf8_bin"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_task"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_task CHARACTER SET utf8 collate utf8_bin"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE USER 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT SUPER, RELOAD, REPLICATION SLAVE, RELOAD, SUPER, REPLICATION CLIENT ON *.* TO 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs.* TO 'vClusters'@'%'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_warn.* TO 'vClusters'@'%'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_task.* TO 'vClusters'@'%'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON mysql.* TO 'vClusters'@'%'"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "FLUSH PRIVILEGES"
# delete invalid user
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "USE mysql; DELETE FROM user WHERE Password=''"
/etc/init.d/mysql restart
```

wget [https://repo.anaconda.com/miniconda/Miniconda3-py38_23.3.1-0-Linux-x86_64.sh](https://repo.anaconda.com/miniconda/Miniconda3-py38_23.3.1-0-Linux-x86_64.sh)

bash Miniconda3-py38_23.3.1-0-Linux-x86_64.sh

source .bashrc

conda config --set auto_activate_base false

conda deactivate

conda create --name py36 python=3.6

conda activate py36

pip install setuptools==57.5.0

mkdir ~/.pip

vi ~/.pip/pip.conf

```
[global]
timeout = 6000
index-url = http://pypi.douban.com/simple
trusted-host = pypi.douban.com
```

上传源码到服务器

docker cp /root/vcmp-SDS_2_2.tar.gz ubuntu:/root

tar zxvf vcmp-SDS_2_2.tar.gz

cd vcmp

pip install -r requirements.txt

如果上面命令出现编码错误

```
UnicodeDecodeError: 'ascii' codec can't decode byte 0xe7 in position 28: ordinal not in range(128)
```

就把涉及的几个txt里面的中文去掉

python web/manage-dev.py runserver

如果报错

可以试试

python web/manage.py runserver 0.0.0.0:8000