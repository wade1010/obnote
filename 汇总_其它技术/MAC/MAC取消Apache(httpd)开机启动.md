打开终端,执行下面的命令.

sudo launchctl unload -w /System/Library/LaunchDaemons/org.apache.httpd.plist

如果哪天你想让它开机启动了,则将unload 改为 load:

sudo launchctl load -w /System/Library/LaunchDaemons/org.apache.httpd.plist