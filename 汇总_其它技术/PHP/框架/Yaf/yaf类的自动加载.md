目录映射规则：



1）model controller plugin

![](https://gitee.com/hxc8/images8/raw/master/img/202407191106648.jpg)



例如model类在new的时候，会自动到model目录寻找，model内可以再有子文件夹，文件命名需要符合规范，见下文

controller会到controllers目录寻找



2）类库



Yaf为了方便在一台服务器上部署的不同产品之间共享公司级别的共享库, 支持全局类和本地类两种加载方式.

全局类是指, 所有产品之间共享的类, 这些类库的路径是通过ap.library在php.ini(当然,如果PHP在编译的时候, 支持了with-config-file-scan-dir,那么也可以写在单独的ap.ini中)(php支持多个配置文件)

而本地类是指, 产品自身的类库, 这些类库的路径是通过在产品的配置文件中, 通过ap.library配置的，自己模块的。

为了防止类库调用的时候，本地类和全局类不知道调用哪个，两个方法：方法1：需要在ap配置文件中指定本地类库的namespace（版本原因，applicatin的配置说明中并未说明，我的代码中未指定，估计默认都是library）。

例如：applicatin.library.namespace = “Tool,Data” 。在library文件夹下创建与application.library.namespace声名的空间名称相同的文件夹，如Tool与Data等目录

方法2： Yaf_Loader来注册：public Yaf_Loader Yaf_Loader::registerLocalNamespace( mixed $local_name_prefix );

文件规则：

controller : 位于controller文件夹下，文件名大写与controllerName相同，类名 结合php.ini中ap.name_suffix，例如后缀模式 ApiController

action ： 路径不限，和controller中指定一样即可，文件名大写与actionName相同，类名 结合php.ini中ap.name_suffix，例如后缀模式 LoginAction

model ：位于models文件夹下，可以有子文件夹，命名需要和文件夹映射，例如目录结构为models/login/Login.php 类名为Login_LoginModel

plugin：位于plugins文件夹下，文件名大写，例如SafCallerPlugin

library：可以分子文件夹，一类类库放在同一个文件夹 ， 命名需要和文件夹映射，例如目录结构为library/utils/mysql.php 类名为Utils_Mysql

