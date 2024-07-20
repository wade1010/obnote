首先肯定是找官网啦



https://www.rabbitmq.com/download.html





# 安装



这里用的是brew安装





https://www.rabbitmq.com/install-homebrew.html





1、brew update





2、brew install rabbitmq





```javascript
 Summary
🍺  /usr/local/Cellar/rabbitmq/3.8.8: 115 files, 23.1MB, built in 7 seconds
==> Caveats
==> erlang
Man pages can be found in:
  /usr/local/opt/erlang/lib/erlang/man

Access them with `erl -man`, or add this directory to MANPATH.
==> rabbitmq
Management Plugin enabled by default at http://localhost:15672

Bash completion has been installed to:
  /usr/local/etc/bash_completion.d

To have launchd start rabbitmq now and restart at login:
  brew services start rabbitmq
Or, if you don't want/need a background service you can just run:
  rabbitmq-server
```







启动 brew services start rabbitmq









# 安装RabiitMQ的可视化监控插件





// 切换到MQ目录,注意你的安装版本



cd /usr/local/Cellar/rabbitmq/<version>/



sudo sbin/rabbitmq-plugins enable rabbitmq_management







插件启动成功后，可以键入http://localhost:15672/   



账号密码默认都是 guest







# 要安装PHP扩展



brew install rabbitmq-c



pecl install amqp





