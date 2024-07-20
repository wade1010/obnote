php原样输出 不解释PHP



HP发布5.6版本后，一直在等，等到了跨越式的PHP7版本，那么问题来了，版本6到哪去了？根据官方的说法，现在的PHP7要比PHP5.6快一倍，有的朋友说快十倍，反正是更快了，本人习惯Mac系统，因此根本Mac系统详细讲解如何安装PHP7！ 

一般有好几种方法来安装。

一，我们可以去官网上下源码去编译，我也尝试这种方法了，但是最后编译安装make test，这一步的时候，报错，于是就大胆尝试第二种方法吧！

二，使用第三方包homebrew来安装，非常迅速有效！



安装教程：

1.首先我们需要安装Homebrew

一条命令完美安装：http://brew.sh/index_zh-cn.html

2.终端输入以下命令

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22 | // 创建目录,如果你没有创建过该目录<br>sudo mkdir /usr/local/var<br>sudo chmod 777 /usr/local/var<br>//修改成你自己的用户名和组,如果你没有创建过该目录<br>sudo mkdir /usr/local/sbin/<br>sudo chown -R &lt;username&gt;:&lt;group&gt; /usr/local/sbin//由于我本身一直在使用5.6版本，故上述步骤省略，下面进入正题<br>//添加PHP库<br>brew tap homebrew/dupes<br>brew tap homebrew/versions<br>brew tap homebrew/homebrew-php<br>//关闭老版本的PHP56或55或更早版本 进程<br>brew unlink php56<br>//开始安装PHP7<br>brew install php70<br>//开启PHP70进程<br>brew link php70<br>//输入命令，查看是否成功<br>php -v<br>//成功后显示下面信息<br>PHP 7.0.8 (cli) (built: Jul 13 2016 15:19:21) ( NTS )<br>Copyright (c) 1997-2016 The PHP Group<br>Zend Engine v3.0.0, Copyright (c) 1998-2016 Zend Technologies |


3.修改Apache配置文件

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4 | sudo vim /etc/apache2/httpd.conf<br>//找到大约168行，该语句，屏蔽后，根据自己的路径，添加php7的.so文件<br>\#LoadModule php5\_module libexec/apache2/libphp5.so<br>LoadModule php7\_module /usr/libexec/apache2/libphp7.so |


说明一下，我的libphp7.so文件目录是这个，好像是默认安装的结果

LoadModule php7_module /usr/local/Cellar/php70/7.0.0-rc.4/libexec/apache2/libphp7.so

4.重启Apache

?

|   |   |
| - | - |
| 1 | sudo apachectl restart |


5.如果发现php文件直接输出到浏览器了，那么你需要修改以下配置

?

|   |   |
| - | - |
| 1 | sudo vim /etc/apache2/httpd.conf |


找到 Include /private/etc/apache2/other/*.conf 这行

进入此文件

将文件内容，修改为以下代码：

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7 | &lt;IfModule php7\_module&gt;<br>AddType application/x-httpd-php .php<br>AddType application/x-httpd-php-source .phps<br>&lt;IfModule dir\_module&gt;<br>DirectoryIndex index.html index.php<br>&lt;/IfModule&gt;<br>&lt;/IfModule&gt; |


6.再次重启apache，重复第4步

到你的Apache的默认目录/Library/WebServer/Documents下面去增加一个info.php的文件。

?

|   |   |
| - | - |
| 1<br>2<br>3 | &lt;?php<br>phpinfo();<br>?&gt; |


http://localhost/info.php久违的画面，应该已经在眼前！

来自千锋PHP的实验你可以多次尝试一下，那么问题来了，安装后可能导致之前的工程无法访问了，排查一下，MySQL出问题了，那么只需重启一下你的mysql即可！

?

|   |   |
| - | - |
| 1 | sudo /Library/StartupItems/MySQLCOM/MySQLCOM restart |


最后

告知一下，Mac如何将mysql路径加入环境变量

1.打开终端,输入： cd ~

会进入~文件夹

2.然后输入：touch .bash_profile

回车执行后，

3.再输入：open -e .bash_profile

会在TextEdit中打开这个文件（如果以前没有配置过环境变量，那么这应该是一个空白文档）。如果有内容，请在结束符前输入，如果没有内容，请直接输入如下语句：

?

|   |   |
| - | - |
| 1 | export PATH=${PATH}:/usr/local/mysql/bin |


以上所述是小编给大家介绍的Mac系统完美安装PHP7详细教程，希望对大家有所帮助，如果大家有任何疑问请给我留言，小编会及时回复大家的。在此也非常感谢大家对脚本之家网站的支持！