官网安装步骤：

https://about.gitlab.com/installation/



我用的是centos6

https://about.gitlab.com/installation/#centos-6



主要命令如下：

sudo yum install -y curl policycoreutils-python openssh-server cronie
sudo lokkit -s http -s ssh


sudo yum install postfix
sudo service postfix start
sudo chkconfig postfix on


curl https://packages.gitlab.com/install/repositories/gitlab/gitlab-ee/script.rpm.sh | sudo bash


sudo EXTERNAL_URL="http://xxx.xxx.com" yum -y install gitlab-ee






但是执行到第二部的 sudo EXTERNAL_URL="http://xxx.xxx.com" yum -y install gitlab-ee


   会报错，原因是镜像连接失败，解决办法是：

https://mirror.tuna.tsinghua.edu.cn/help/gitlab-ce/



网站内容如下：

Debian/Ubuntu 用户

首先信任 GitLab 的 GPG 公钥:

curl https://packages.gitlab.com/gpg.key 2> /dev/null | sudo apt-key add - &>/dev/null


再选择你的 Debian/Ubuntu 版本，文本框中内容写进 /etc/apt/sources.list.d/gitlab-ce.list

你的Debian/Ubuntu版本: 

deb http://mirrors.tuna.tsinghua.edu.cn/gitlab-ce/debian stretch main


安装 gitlab-ce:

sudo apt-get update
sudo apt-get install gitlab-ce


RHEL/CentOS 用户

新建 /etc/yum.repos.d/gitlab-ce.repo，内容为

[gitlab-ce]
name=Gitlab CE Repository
baseurl=https://mirrors.tuna.tsinghua.edu.cn/gitlab-ce/yum/el$releasever/
gpgcheck=0
enabled=1


再执行

sudo yum makecache
sudo yum install gitlab-ce









安装成功后的提示还挺漂亮的

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756516.jpg)



不过看到貌似没有配置external_url



根据提示，我们配置下就可以了

1、vim /etc/gitlab/gitlab.rb    找到external_url ，修改并保存

2、gitlab-ctl reconfigure



---

配置访问端口

有的时候默认的80端口被占用了，需要你更改端口。

官网中说道:



Setting the NGINX listen port：

By default NGINX will listen on the port specified in external_url or implicitly use the right port (80 for HTTP, 443 for HTTPS). If you are running GitLab behind a reverse proxy, you may want to override the listen port to something else. For example, to use port 8081:



1、vim /etc/gitlab/gitlab.rb 

2、修改配置为nginx['listen_port'] = 8081   

3、如果是买的云服务器，记得在安全组内加入8081端口，如果不是则跳过此步骤

4、gitlab-ctl reconfigure  重启

5、此时通过external_url:listen_port就能够访问到gitlab



