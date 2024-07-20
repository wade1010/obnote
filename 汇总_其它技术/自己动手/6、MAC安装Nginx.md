1、brew install nginx



2、启动nginx

[nginx] ps -ef|grep nginx                      (查看有没有启动)                                                                          

  502 32423 26553   0  9:37下午 ttys002    0:00.00 grep --color=auto --exclude-dir=.bzr --exclude-dir=CVS --exclude-dir=.git --exclude-dir=.hg --exclude-dir=.svn nginx

[nginx] nginx                              (启动)                                                                                      

[nginx] ps -ef|grep nginx                                   (查看有没有启动)                                                                 

  502 32498     1   0  9:37下午 ??         0:00.00 nginx: master process nginx

  502 32499 32498   0  9:37下午 ??         0:00.00 nginx: worker process

  502 32564 26553   0  9:37下午 ttys002    0:00.00 grep --color=auto --exclude-dir=.bzr --exclude-dir=CVS --exclude-dir=.git --exclude-dir=.hg --exclude-dir=.svn nginx

