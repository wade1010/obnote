HPRPC简介：

PHPRPC 是一个轻型的、安全的、跨网际的、跨语言的、跨平台的、跨环境的、跨域的、支持复杂对象传输的、支持引用参数传递的、支持内容输出重定向的、支持分级错误处理的、支持会话的、面向服务的高性能远程过程调用协议。

了解更多请访问http://www.phprpc.com

所谓的单点登录(SSO)，就是用户只需要在用户中心服务器登录认证一次，然后浏览器通过用户中心服务器返回的信息轮询访问应用程序进行模拟登录，之后用户再访问应用系统的时候不需要登录就能访问了。discuz的ucenter就是此原理。

其实现原理就是浏览器（用phprpc for javascript）请求用户中心服务器(phprpc for php)，通常此时的浏览器就是一个登录页面，浏览器将用户名和密码传递给用户中心服务器，用户中心服务器验证成功之后，查找数据库里此用户名和密码对应的信息，根据这些信息生成一个令牌(token)返回给浏览器，浏览器在用token访问应用系统，应用程序收到token之后，再用收到的token访问用户中心服务器，用户中心服务器通过这个token查找数据库，从而能够找到对应应用系统的用户名和密码，然后将这个用户名和密码返回给应用系统，应用系统再收到的用户名和密码进行登录（通常就是一次写SESSION或者COOKIE的过程）。

参考数据库设计图:

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/C1C7EE2FC07E42A19989E9C314493897200901011230779830.JPG.jpeg)

如设计图中所展示的，统一认证服务器收到浏览器通过javascript传递过来的用户名和密码检索“认证服务器用户表”，从而得到“用户ID”，根据 “用户ID”再去检索“应用系统用户表”，然后就会得到一条或多条记录，把每一个结果都在“临时会话表”中生成一条记录，这里的“会话ID”是表的主键， 也就是我们的token，而第二个字段id是对应的“应用系统用户表”的主键，还有一点比较重要，就是根据“应用系统ID”检索“应用系统表”，从而获得应用系统的地址。

然后统一认证服务器将token与应用系统的访问地址组成一个新的数组返回给浏览器

然后浏览器再运行JS将传回来的token作为参数访问应用系统服务端。

应用系统使用token访问统一认证服务器从而获取用户名和密码，获得用户名密码之后就完成了登录。

demo:

用户中心：

AuthServer.php

require_once("phprpc/phprpc_server.php");//引入phprpc服务端require_once("class/Server.class.php");//引入phprpc服务端$AuthServer=new AuthServer($db);$centerAuthServer = new PHPRPC_Server();$centerAuthServer->add('rpcLogin',$AuthServer);//统一认证服务$centerAuthServer->add('autToken',$AuthServer);//Token验证服务$centerAuthServer->start();class/Server.class.php:require_once("db.php");//引入数据库配置文件require_once("Mysql.class.php");//引入Mysql封装类require_once("Log.class.php");//引入日志记录类$db=new Mysql($db_host,$db_user,$db_pwd,$db_name);class AuthServer{function __construct($db){//将数据库对象赋值给$this->db$this->db = $db;$this->db->Query("SET NAMES 'utf8'");}function rpcLogin($username,$password) {//根据用户名和密码判断是否是已注册用户$result=$this->db->GetRow("SELECT userId FROM sysuser WHERE username='{$username}' and password='{$password}'");if ($result!=false) {//如果验证成功，那么通过userId检索映射表，从而得到该用户所有的应用系统映射号$appMapArr=$this->db->GetRows("SELECT id,appId FROM appmapper WHERE         userId={$result['userId']}");//$appIdArr的个数代表用户的应用系统数目,将每一个appId都写入会话表$insertIdArr=array();for($i=0;$i
   
   
   
   
   
    
    
    
    
    db->Query("INSERT INTO session SET id={$appMapArr[$i]['id']}");//取得最后一次插入数据库的主键作为token$insertId=$this->db->InsertID();//根据appId查找到appsys表里的应用系统RPC服务端地址$appSys=$this->db->GetRow("SELECT checkUrl FROM appsys WHERE appId=      {$appMapArr[$i]['appId']}");//合并输出array_push($insertIdArr,array($appSys['checkUrl'],$insertId));}if ($insertIdArr!=false){//如果有结果，那么将token返回给客户端return $insertIdArr;}else{return false;}}else{return false;}}function autToken($token){//根据token查询会话表,返回与当前token对应的appmapper表的主键$result=$this->db->GetRow("SELECT id FROM session WHERE hash=$token");if ($result!=false){//如果存在，则从映射表里取出用户的应用系统用户名和密码，并且删除这个token$this->db->Query("DELETE FROM session WHERE hash=$token");//从数据库里检索用户名和密码$userInfo=$this->db->GetRow("SELECT username,password FROM appmapper WHERE id={$result['id']}");//将检索到的用户名和密码返回给应用系统return $userInfo;}else{return false;}}}
   
   
   
   
   

用户中心登录页Login.html:

用户名:密  码:

测试应用程序：

sso.php:

//应用验证服务器require_once("phprpc/phprpc_client.php");require_once("phprpc/phprpc_server.php");function rpcLogin($token){$userInfo=getUserInfo($token);if ($userInfo!=false){//这里实行模拟登录$tempLogin=login($userInfo['username'],$userInfo['password']);return true;if ($tempLogin){return true;}else{return false;}}else{return false;}}function getUserInfo($token){$client = new PHPRPC_Client("http://localhost/sso/AuthServer.php");$userInfo=$client->autToken($token);return $userInfo;}function login($username,$password){$_SESSION['login']="ok";$_SESSION['username']=$username;return true;}$server = new PHPRPC_Server();$server->add('getUserInfo');$server->add('rpcLogin');$server->start();

index.php:

session_start();if ($_SESSION['login']=='ok') {echo "您好！".$_SESSION['username'];echo "

登录成功！";echo "

注销登录";}else {echo "请登录！";}

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/490C928F0F6746FEA6AA957A57E61F2A29_1308839498Atkh.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/3FD99BBB68BB4D34BCB7621D0EEEE8E229_130883978088Ob.jpg.jpeg)