[~]$ brew install mysql@5.6                                                    

==> Downloading https://mirrors.tuna.tsinghua.edu.cn/homebrew-bottles/bottles/my

######################################################################## 100.0%

==> Pouring mysql@5.6-5.6.47.catalina.bottle.tar.gz

==> /usr/local/Cellar/mysql@5.6/5.6.47/bin/mysql_install_db --verbose --user=bob

==> Caveats

A "/etc/my.cnf" from another install may interfere with a Homebrew-built

server starting up correctly.



MySQL is configured to only allow connections from localhost by default



To connect:

    mysql -uroot



mysql@5.6 is keg-only, which means it was not symlinked into /usr/local,

because this is an alternate version of another formula.



If you need to have mysql@5.6 first in your PATH run:

  echo 'export PATH="/usr/local/opt/mysql@5.6/bin:$PATH"' >> ~/.zshrc



For compilers to find mysql@5.6 you may need to set:

  export LDFLAGS="-L/usr/local/opt/mysql@5.6/lib"

  export CPPFLAGS="-I/usr/local/opt/mysql@5.6/include"





To have launchd start mysql@5.6 now and restart at login:

  brew services start mysql@5.6

Or, if you don't want/need a background service you can just run:

  /usr/local/opt/mysql@5.6/bin/mysql.server start

==> Summary

🍺  /usr/local/Cellar/mysql@5.6/5.6.47: 344 files, 155.2MB       







安装完后 执行



echo 'export PATH="/usr/local/opt/mysql@5.6/bin:$PATH"' >> ~/.zshrc



这样就能在命令行直接使用msyql了





如果忘记了信息 可以使用下面命令来查看



brew info mysql@5.6                                                                

