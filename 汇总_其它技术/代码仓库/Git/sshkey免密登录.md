SSH无密码（密钥验证）登录的配置

　　配置主机A免密登录到主机B（方法一）

　　　   1.在主机A生产密钥对: ssh-keygen -t rsa， 会在.ssh目录下产生密钥文件

　　　　2.拷贝主机A的公钥到主机B: scp id_rsa.pub

　　　　3.将主机A的公钥加到主机B的授权列表.ssh/authorized_keys（若不存在，手动创建）: cat id_rsa.pub >> authorized_keys 

　　　　4.授权列表authorized_keys的权限必须是600，chmod 600 authorized_keys

　　

　　fcb25c6f49379af35a9e4d6f00394ad128ec1b35

　　（方法二）

 　　　　　#进入到我的home目录  cd ~/.ssh

　　　　　   ssh-keygen -t rsa （四个回车）  

　　　　　　执行完这个命令后，会生成两个文件id_rsa（私钥）、id_rsa.pub（公钥）  

　　　　　　将公钥拷贝到要免登陆的机器上：  ssh-copy-id 你的服务器



ssh-copy-id -p 9741 -i ~/.ssh/id_rsa.pub deploy@119.28.214.189                                            

/usr/bin/ssh-copy-id: INFO: Source of key(s) to be installed: "/Users/xhcheng/.ssh/id_rsa.pub"

/usr/bin/ssh-copy-id: INFO: attempting to log in with the new key(s), to filter out any that are already installed

/usr/bin/ssh-copy-id: INFO: 1 key(s) remain to be installed -- if you are prompted now it is to install the new keys

deploy@119.28.214.189's password: 

Number of key(s) added:        1

Now try logging into the machine, with:   "ssh -p '9741' 'deploy@119.28.214.189'"

and check to make sure that only the key(s) you wanted were added.





