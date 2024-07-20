vim是打开vim编辑器，别的编辑器还有vi(功能没有vim 强大),nano，emacs等等，感觉还是vim最强大，其次是vi,别的就要差一些了。 我听我们老师说，用图形界面本身已经会被高手笑了，如果打开一个gpedit或者kwrite那就废了......

常用的命令

ls,列出当前目录下的文件，ls -l是列出详细信息，ls -a列出隐藏文件。

cd,更改目录。clear，清屏命令。reset，重置终端。

startx,启动图形界面。fdisk -l,查看硬盘分区。      

ps aux，列出系统进程。cat,显示文本。tac,逆序显示文本。

od,二进制格式显示文本。wc,判断文件的大小行数和字符数等等。

aspell，检查文件中的拼写错误。less，分页读取文件。more，与less类似，但是功能不及less。

reboot，重启系统。poweroff，关机。halt，也是关机，但是需要手动切断电源，不推荐使用。shutdown -h now,立即关机，后面的now可以替换成时间，可以指定关机时间的指令，据说良好的系统管理员应该使用这个命令。shutdown -r now,与上一条类似，只不过是重启。sync，同步硬盘数据，重启或关机前应该多次使用。

locate，查询文件位置，每隔一段时间应该使用updatedb命令以提供搜索范围。find，强大的查询命令，参数众多。find / -name *,这是查询/下所有文件的意思。

whereis，我用他来判断命令的所在位置，如whereis ls。

sudo，在普通帐户的情况下使用root权限，不过需要修改/etc/sudoers文件才可以。

mv，移动文件或者重命名。mv /etc/* /home/tom，是将/etc所有文件移动到tom目录下的意思。mv a b,把a重命名为b.当然，这只是个例子，具体操作的时候需要看具体情况进行判定。

cp,于mv类似，也是相同的格式，只不过不是移动，是复制。如果复制的是目录的话，需使用-r参数，cp -r ***.

rm,这是删除指令，与cp类似，删除目录添加-r,提示删除使用-i

useradd，添加一个新帐户。userdel，删除一个帐户。

passwd，为一个帐户设置密码。都有许多参数来实现其他功能。

chown，更改文件所属。如chown tom.tom 文件名，将文件改为所属组tom，所属者也是tom。

chmod,更改文件的权限，只说简单的改法，chmod 777 文件名，文件将有所有的权限。

chkconfig --list,用来观察服务状态，chkconfig --level ? 服务名 on/off,打开或者关闭服务，？代表运行级别。

init (1,2,3,4,5,6)用来在6个运行级别切换。

runlevel查看现在的运行级别。

bc，一个计算器。date，显示时间。cal显示日历。

如果是redhat的话，还有setup，用来设置一些系统相关，ntsysv，专门用来设置服务，这样就不用chkconfig了。

tr，压缩或者替换字符。dh，计算目录的大小。df，显示文件系统的信息。

free，显示内存cpu的时用情况。top，动态观察进程。

tar -czvf,创建*.tar.gz压缩包，tar -xzvf,解压这种压缩包。

tar -cjvf,创建的是*.tar.bz2,解压是tar -xjvf

rpm -ivh,安装rpm包，rpm -e卸载rpm包

who，观察登录情况。whoami，who am i，两条命令有一些区别，不过差不多。id，用来查看帐户的信息。w，也是查看登录情况的，更加详细。

echo,用来显示环境变量等等，例子echo $LANG。

history,显示命令历史。mount挂在设备。umount，卸载设备。dmesg，显示启动信息。yum，更新时用的命令。

ssh，ssh登录。telnet，telnet登录。还有ftp命令。

gcc,g++，java，javac，都是编程用的命令。make，如果有makefile的话，可以用他编译。

以上都是我想到以后打出来的，难免有错误，而且顺序好像不怎么好，请见谅。

补充一下，由于安装包的问题，并不是所有命令都可能出现，如果需要某些功能需要安装对应的包文件才可以。