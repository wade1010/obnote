

[install_cvparse.tar.gz](attachments/WEBRESOURCE15b39c4ce28821452550d5d622bba73finstall_cvparse.tar.gz)



由于有道笔记会员到期，新的tar包超过200M传不上来。这里的包，还是需要进入image安装一个pip install BeautifulSoup4





1. 下载上面文件到本地

1. scp  到服务器(如：scp install_cvparse.tar.gz root@192.168.1.17:/root)

1. tar zxvf install_cvparse.tar.gz 

1. cd install_cvparse

1. sh install.sh  (重装后注意install_cvparse/packages里面的cv_parser_1_1.tar要还原成cv_parser_1_1.tar.gz。否则gzip失败)

1. docker images  查看是否成功

1. 换国内源

1. vim /etc/docker/daemon.json

1. {"registry-mirrors": ["https://registry.docker-cn.com"]}

1. systemctl restart docker



