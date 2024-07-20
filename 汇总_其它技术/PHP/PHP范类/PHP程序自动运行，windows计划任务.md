在windows中如果你想让PHP程序自动运行那么我们必须使用windows计划任务来完成了，下面我来给各位同学介绍实现方法。

具体来说，我们若需利用任务计划程序自动运行则应执行如下步骤：　

1．单击”开始”按钮，然后依次选择”程序”→”附件”→”系统工具”→”任务计划”（或者是”设置”→”控制面板”→”任务计划”），启动Windows 2000的任务计划管理程序。

2．在”任务计划”窗口中双击”添加任务计划”图标，启动系统的”任务计划向导”，然后单击”　　　　下一步”按钮，在给出的程序列表中选择需要自动运行的应用程序，然后单击”下一步”按钮。　　

3．设置适当的任务计划名称并选择自动执行这个任务的时间频率(如每天、每星期、每月、一次性、每次启动计算机时、每次登录时等)，然后单击”下一步”按钮。此时系统将会要求用户对程序运行的具体时间进行设置，如几号、几点钟、哪几个时间段才能运行等，我们只需根据自己的需要加以设置即可。  　　

4．接下来系统将会要求用户设置适当的用户名及密码(如图5所示)，以便系统今后能自动加以运行。 　　

5．最后，我们只需单击”完成”按钮即可将相应任务添加到Windows 2000的任务计划程序中，此后它就会自动”记住”这个任务，一旦系统时间及相关条件与用户设置的计划相符，它就会自动调用用户所指定的应用程序，十分方 便(每次启动Windows 2000的时候，任务计划程序都会自动启动，并在后台运行，确保用户的计划能够按时执行)。 

　　现在我们来测试一下刚才所建的任务是否成功，鼠标右键单击”php”程序图标(如图6所示)，在弹出的菜单里面选择”运行”。一般情况下程序图标只要这样 激活运行就可以正常启动。如果运行失败可查看用户和密码是否设置正确，还有确定”Task Scheduler”服务是否已启动，本人当初就是为了节省系统资源把它关掉了导致运行失败，害我找了大半天。另外也可从”系统日志”里查看到底是什么原 因造成运行失败的。

　　好了，讲了这么多任务计划的应用，现在我们切入正题，下面将介绍两个例子：

　　一、让PHP定时运行

编辑如下代码，并保存为test.php：

$fp = @fopen(”test.txt”, “a+”);

fwrite($fp, date(”Y-m-d H:i:s”) . ” 让PHP定时运行吧！/n”);

fclose($fp);

?>

添加一个任务计划，在(如图2所示)这一步输入命令：

D:/php4/php.exe -q D:/php4/test.php

时间设置为每隔1分钟运行一次，然后运行这个任务。  现在我们来看看d:/php4/test.txt文件的内容时候是否成功。如果内容为如下所示，那么恭喜你成功了。

2007-10-30 11:08:01 让PHP定时运行吧！

2007-10-3011:09:02 让PHP定时运行吧！

2007-10-30 11:10:01 让PHP定时运行吧！

2007-10-30 11:11:02 让PHP定时运行吧！

　　 二、让MYSQL实现自动备份

编辑如下代码，并保存为backup.php，如果要压缩可以拷贝一个rar.exe：

if ($argc != 2 || in_array($argv[1], array(’–help’, ‘-?’))) {

?>

backup Ver 0.01, for Win95/Win98/WinNT/Win2000/WinXP on i32

Copyright (C) 2000 ptker All rights reserved.

This is free software,and you are welcome to modify and redistribute it

under the GPL license

PHP Shell script for the backup MySQL database.

Usage:

can be database name you would like to backup.

With the –help, or -? options, you can get this help and exit.

} else {

$dbname = $argv[1];

$dump_tool = “c://mysql//bin//mysqldump”;

$rar_tool = “d://php4//rar”;

@exec(”$dump_tool –opt -u user -ppassword $dbname > ./$dbname.sql”);

@exec(”$rar_tool a -ag_yyyy_mm_dd_hh_mm $dbname.rar $dbname.sql”);

@unlink(”$dbname.sql”);

echo “Backup complete!”;

}

?>

添加一个任务计划，在(如图2所示)这一步输入命令：

D:/php4/php.exe -q D:/php4/backup.php databasename

时间设置为每天运行一次，然后运行这个任务。  最后会在d:/php4/目录下生成一个以数据库名和当前时间组成的rar文件。  恭喜你！大功告成了！

　　当然备份方式有很多种，读者可按照自己喜欢的去做！

　　以上是原著.结合本人实贱,补充说明如下:

如果出现错误:

在试着设置任务帐户信息时出现错误

指定的错误是：

0×80070005:拒绝访问

您没有运行所请求的操作的权限

在上面’”4.接下来系统将会要求用户设置适当的用户名及密码，以便系统今后能自动加以运行”.这里最好用”system”用户,密码可为空.

这个system的权限非常之高,比你的administrator还要高,所以你在运行命令的时候千万不要乱来,这个可是什么提示都没有就会无条件执行的,这个权限下你kill核心进程都行.

　　2、添加一个任务计划，在这一步输入命令：

D:/php4/php.exe -q D:/php100/test.php

　　正确形式应为

“D:/php4/php.exe” -q “D:/php100/test.php”

　　即路径要用双引号括住.

