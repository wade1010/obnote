注意:

A)在mongodb中,有一个admin数据库, 牵涉到服务器配置层面的操作,需要先切换到admin数据.

即 use admin , -->相当于进入超级用户管理模式.

 

B)mongo的用户是以数据库为单位来建立的, 每个数据库有自己的管理员.

 

C) 我们在设置用户时,需要先在admin数据库下建立管理员---这个管理员登陆后,相当于超级管理员.

 

 

0: 查看用户

 

 

1: 添加用户

命令:db.addUser();

简单参数: db.addUser(用户名,密码,是否只读)

 

注意: 添加用户后,我们再次退出并登陆,发现依然可以直接读数据库?

原因: mongodb服务器启动时, 默认不是需要认证的.

要让用户生效, 需要启动服务器时,就指定 --auth 选项.

这样, 操作时,就需要认证了.

 

 

例:

1: 添加用户

> use admin

> db.addUser(‘sa’,’sa’,false);

 

2: 认证

> use test

> db.auth(用户名,密码);

 

3: 修改用户密码

> use test

> db.changeUserPassword(用户名, 新密码);

 

3:删除用户

> use test

> db.removeUser(用户名);

 

注: 如果需要给用户添加更多的权限,可以用json结构来传递用户参数

例:

> use test

>db.addUser({user:'guan',pwd:'111111',roles:['readWrite,dbAdmin']});