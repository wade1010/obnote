scp -P33033 zp.tar root@111.222.123.01:/da1/web/zhaopin.shouhuobao.com

#sshd的端口指定的是33033





sudo scp -P 2001 /Users/xhcheng/Desktop/diff.tar chengxinhui@172.16.2.13:/home/chengxinhui 



本地要sudu   而且 chengxinhui账户在目标服务器上的对应目录要有权限







scp服务器到本地



scp -i ~/tx_auth.txt -r root@129.226.124.88:/root/jdk-8u131-linux-x64.rpm .