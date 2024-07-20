# ps:后来发现最好要以root身份执行

# 一、安装mariadb

sudo apt install mariadb-server -y  （源代码里面需要的是mariadb5.5.60，这是mariadb10如果后面发现不行，可以改代码适配到这个版本，也可以参考这个安装5.5版本，[https://www.cnblogs.com/daofaziran/p/13100556.html](https://www.cnblogs.com/daofaziran/p/13100556.html)）

vim install.sh 输入下面内容

```
MYSQL_ROOT_NEWPASSWD="vClusters@2019"
sudo mysqladmin -u root password ${MYSQL_ROOT_NEWPASSWD}
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs CHARACTER SET utf8 collate utf8_bin"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_warn"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_warn CHARACTER SET utf8 collate utf8_bin"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "DROP DATABASE IF EXISTS vcfs_task"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE DATABASE IF NOT EXISTS vcfs_task CHARACTER SET utf8 collate utf8_bin"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "CREATE USER 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT SUPER, RELOAD, REPLICATION SLAVE, RELOAD, SUPER, REPLICATION CLIENT ON *.* TO 'vClusters'@'%' IDENTIFIED BY '${MYSQL_ROOT_NEWPASSWD}'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_warn.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON vcfs_task.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "GRANT ALL ON mysql.* TO 'vClusters'@'%'"
sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "FLUSH PRIVILEGES"
# delete invalid user
#sudo mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "USE mysql; DELETE FROM user WHERE Password=''"
sudo systemctl restart mariadb
```

sh install.sh

ps：如果需要远程访问mariadb

注释掉mariadb配置文件中/etc/mysql/mariadb.conf.d/50-server.cnf中以下行，各位自行匹配自己版本中的配置文件位置

```
# bind-address = 127.0.0.1  #记得重启
```

# 二、安装miniconda

```
wget https://repo.anaconda.com/miniconda/Miniconda3-py38_23.3.1-0-Linux-x86_64.sh

bash Miniconda3-py38_23.3.1-0-Linux-x86_64.sh

会出现交互页面，执行到conda init那个页面

conda config --set auto_activate_base false     如果提示没有conda命令就 source更新下环境变量

conda deactivate                       退出base
```

# 三、安装依赖

ssh-keygen -t ed25519 -C "your_email@example.com"

cat /home/runner/.ssh/id_ed25519.pub 把内容复制到github，进行免密配置

git clone 项目

进入 cxfs根目录  这里需要使用python3.6 ，用conda创建一个py3.6的环境

conda create --name cxfs python=3.6

conda activate cxfs

pip install setuptools==57.5.0

vim requirements/base.txt

修改django版本 

Django==1.11.29

pip install -r requirements/dev.txt

应该会报错 use_2to3相关的，使用下面命令解决

pip install setuptools==57.5.0

sudo mkdir /var/log/platform && sudo chmod -R 777 /var/log/platform

执行项目命令

```
export DJANGO_READ_DOT_ENV_FILE=true
```

python web/manage.py makemigrations

python web/manage.py makemigrations vclusters

如果报错

```
ModuleNotFoundError: No module named 'gunicorn'
```

pip install gunicorn

```
python web/manage.py makemigrations
Loading : /home/runner/workspace/vcfs/web/.env
The .env file has been loaded.
/home/runner/miniconda3/envs/vcfs/lib/python3.6/site-packages/environ/environ.py:416: UserWarning: Engine not recognized from url: {'NAME': '', 'USER': '', 'PASSWORD': '', 'HOST': '', 'PORT': ''}
  warnings.warn("Engine not recognized from url: {0}".format(config))
Migrations for 'taskapp':
  web/taskapp/migrations/0001_initial.py
    - Create model Task
Migrations for 'warn_app':
  web/warn_app/migrations/0001_initial.py
    - Create model WarnControl
    - Create model WarnStorage
Migrations for 'users':
  web/users/migrations/0001_initial.py
    - Create model User
    - Create model Feature
    - Create model FeatureControl

```

python web/manage.py migrate    就是把model转成slq，生成对应的数据库表

```
╰─○ python web/manage.py migrate
Loading : /home/runner/workspace/vcfs/web/.env
The .env file has been loaded.
/home/runner/miniconda3/envs/vcfs/lib/python3.6/site-packages/environ/environ.py:416: UserWarning: Engine not recognized from url: {'NAME': '', 'USER': '', 'PASSWORD': '', 'HOST': '', 'PORT': ''}
  warnings.warn("Engine not recognized from url: {0}".format(config))
Operations to perform:
  Apply all migrations: admin, auth, authtoken, contenttypes, djcelery, sessions, taskapp, users, warn_app
Running migrations:
  Applying contenttypes.0001_initial... OK
  Applying contenttypes.0002_remove_content_type_name... OK
  Applying auth.0001_initial... OK
  Applying auth.0002_alter_permission_name_max_length... OK
  Applying auth.0003_alter_user_email_max_length... OK
  Applying auth.0004_alter_user_username_opts... OK
  Applying auth.0005_alter_user_last_login_null... OK
  Applying auth.0006_require_contenttypes_0002... OK
  Applying auth.0007_alter_validators_add_error_messages... OK
  Applying auth.0008_alter_user_username_max_length... OK
  Applying users.0001_initial... OK
  Applying admin.0001_initial... OK
  Applying admin.0002_logentry_remove_auto_add... OK
  Applying authtoken.0001_initial... OK
  Applying authtoken.0002_auto_20160226_1747... OK
  Applying djcelery.0001_initial... OK
  Applying sessions.0001_initial... OK
  Applying taskapp.0001_initial... OK
  Applying warn_app.0001_initial... OK
/home/runner/miniconda3/envs/vcfs/lib/python3.6/site-packages/environ/environ.py:416: UserWarning: Engine not recognized from url: {'NAME': '', 'USER': '', 'PASSWORD': '', 'HOST': '', 'PORT': ''}
  warnings.warn("Engine not recognized from url: {0}".format(config))

```

这个时候可以登录到数据库看下表

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354773.jpg)

```
export DJANGO_READ_DOT_ENV_FILE=true
python web/manage.py makemigrations
python web/manage.py makemigrations vclusters
python web/manage.py migrate --database=vcfs_task
python web/manage.py migrate --database=vcfs_warn
python web/manage.py migrate
```

```
# 添加 用户权限表、告警控制表 数据
python web/manage.py loaddata web/fixtures/initial_data.json
MYSQL_ROOT_NEWPASSWD="vClusters@2019"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} vcfs_warn < web/fixtures/warn_control.sql
#change char to be case sensitive
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e "alter table vcfs.vclusters_clusteruser modify name varchar(255) collate utf8_unicode_ci not null"
mysql -u root -p${MYSQL_ROOT_NEWPASSWD} -e  "alter table vcfs.vclusters_clustergroup modify name varchar(255) collate utf8_unicode_ci not null"
```

复制celery相关

cp -a vcmp/script/vcmp-beat.service  /etc/systemd/system/

cp -a vcmp/script/vcmp-worker.service  /etc/systemd/system/

cp -a vcmp/script/vcmp.service /etc/systemd/system/  

cp -a vcmp/script/vcmp-wsi.service  /etc/systemd/system/

激活服务

systemctl enable vcmp-beat.service

systemctl enable vcmp-worker.service

# systemctl enable vcmp.service  不激活，使用pycharm本地启动

# systemctl enable vcmp-wsi.service 不激活，看看可不可以pycharm启动

启动服务

```
systemctl start vcmp-beat.service
systemctl start vcmp-worker.service
#systemctl start vcmp.service
#systemctl start vcmp-wsi.service
```

发现systemctl start vcmp-beat.service报错，

```
systemctl start vcmp-beat.service
Failed to start vcmp-beat.service: Unit mariadb.service not found.
```

因为这里vcmp-beat是依赖vcmp-worker，而vcmp-worker是依赖vcmp.service，但是vcmp.service是pycharm启动，所以我这里把相关依赖都去掉

# 四、创建超级管理员

python web/manage.py createsuperuser --email [admin@qq.com](http://admin@qq.com) --username admin

用户名输入：admin

密码输入：a12345678

可以访问 [http://xxx:8000/admin/](http://xxx:8000/admin/)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354382.jpg)

# 五、启动

python web/manage-dev.py runserver 0.0.0.0:8000

```
# import pymysql
# pymysql.install_as_MySQLdb()

把上面注释的#号去掉
```

默认是8000端口，然后启动前端项目，（npm run dev）http请求会代理到这个后端项目

# 六、Pycharm代码跳转

将web目录作为Sources就可以了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354666.jpg)

通过chatgpt来查的

```
python项目，运行没问题，但是放到pycharm里面import包飘红，代码跳转不了，比如from users.models import User
这个项目有个users目录，里面是又User相关的源代码的，
```

回复

```
这种情况通常是因为PyCharm无法正确识别您的项目结构或无法找到所需的包。您可以尝试以下几个步骤来解决这个问题：

1. 确保您已经在PyCharm中正确配置了项目的Python解释器。在PyCharm的菜单栏中选择“File” -> “Settings”，然后在左侧面板中选择“Project: [项目名称]” -> “Python Interpreter”。确保您选择了正确的Python解释器。

2. 确保您的包已经正确安装并可用。您可以在PyCharm的终端中运行“pip list”命令来查看已安装的包。如果您的包没有出现在列表中，您需要使用“pip install [包名]”命令来安装它。

3. 如果您的包是自己编写的，确保您正确设置了包的结构和导入路径。您可以尝试在PyCharm中手动添加包的路径，以便PyCharm能够正确识别它们。在PyCharm的菜单栏中选择“File” -> “Settings”，然后在左侧面板中选择“Project: [项目名称]” -> “Project Structure”。在右侧面板中单击“Add Content Root”按钮，并选择您的包所在的文件夹。

4. 如果上述步骤都无法解决问题，您可以尝试在PyCharm中重新加载项目。在PyCharm的菜单栏中选择“File” -> “Invalidate Caches / Restart”。在弹出的对话框中选择“Invalidate and Restart”选项，然后等待PyCharm重新启动并重新加载您的项目。

希望这些步骤能够帮助您解决问题。如果您仍然遇到困难，请尝试在PyCharm的官方文档或Stack Overflow等社区中寻求帮助。
```