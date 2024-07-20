1. 目标服务器上 useradd XXX

1. 目标服务器上 passwd XXX  设置密码

1. 本地机器 ssh-copy-id -p 9741 -i ~/.ssh/id_rsa.pub deploy@119.28.214.189   (ssh-keygen -o -f ~/.ssh/id_rsa)

或者直接把id_rsa.pub里面某个key直接拷贝到服务器的.ssh/authorized_keys里面也行