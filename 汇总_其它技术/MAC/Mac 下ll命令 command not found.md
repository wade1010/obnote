

Mac 下ll命令 command not found

在linux下习惯使用ll、la、l等ls别名的童鞋到mac os提示command not found

打开终端

12014-461deMacBook-Pro:~ root# cd ~22014-461deMacBook-Pro:~ root#vim .bash_profile

加入:

alias ll=‘ls -alF‘

alias la=‘ls -A‘

alias l=‘ls -CF‘

  保存后，执行

3 2014-461deMacBook-Pro:~ root#source .bash_profile







另外，.bash_profile 应该在 ~/ 下建立，/etc/profile /etc/bashrc 是系统全局的，不建议修改，除非用户级的配置文件不能生效。

