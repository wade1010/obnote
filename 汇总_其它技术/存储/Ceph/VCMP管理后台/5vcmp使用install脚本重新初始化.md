以前初始化过了，这里清空数据库，重新初始化

vim install.sh

先 conda activate vcmp 切换到python环境

```
MYSQL_ROOT_NEWPASSWD="vClusters@2019"
#sudo mysqladmin -u root password ${MYSQL_ROOT_NEWPASSWD}
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs CHARACTER SET utf8 collate utf8_bin"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_warn"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_warn CHARACTER SET utf8 collate utf8_bin"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_task"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_task CHARACTER SET utf8 collate utf8_bin"
#sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE USER 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT SUPER, RELOAD, REPLICATION SLAVE, RELOAD, SUPER, REPLICATION CLIENT ON *.* TO 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_warn.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_task.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON mysql.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "FLUSH PRIVILEGES"
# delete invalid user
#sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "USE mysql; DELETE FROM user WHERE Password=''"
sudo systemctl restart mariadb

export DJANGO_READ_DOT_ENV_FILE=true
python web/manage.py makemigrations
python web/manage.py makemigrations vclusters
python web/manage.py migrate --database=vcfs_task
python web/manage.py migrate --database=vcfs_warn
python web/manage.py migrate

# 添加 用户权限表、告警控制表 数据
python web/manage.py loaddata web/fixtures/initial_data.json
MYSQL_ROOT_NEWPASSWD="vClusters@2019"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} vcfs_warn < web/fixtures/warn_control.sql
#change char to be case sensitive
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "alter table vcfs.vclusters_clusteruser modify name varchar(255) collate utf8_unicode_ci not null"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e  "alter table vcfs.vclusters_clustergroup modify name varchar(255) collate utf8_unicode_ci not null"

python web/manage.py createsuperuser
```

最后是让你输入管理员 相关信息

启动

python web/manage-dev.py runserver 0.0.0.0:8000 

docker 环境变量 MYSQL_ROOT_PASSWORD