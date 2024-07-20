注意：自己做测试的时候，发现引入js的时候要引入那个压缩过的js



转自： http://www.phprpc.org/forum/viewthread.php?tid=319



請先下載最新版的PHPRPC for PHP



當js為客戶端時，呼叫一phprpc server時，而其中的function又去呼叫另一個phprpc server的function，該怎麼保持其中php->php這中間的session cookie呢？



很簡單的，只要在require_once './php/phprpc_client.php';之前定義 define('KEEP_PHPRPC_COOKIE_IN_SESSION',true);即可



請看詳細實例



Php代码

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/F05579695CFC4C70970C71F69FD0BF05icon_star.png)

1. define('KEEP_PHPRPC_COOKIE_IN_SESSION',true);  

1. require_once'./php/phprpc_client.php';  

1. require_once'./php/phprpc_server.php';  

1. function hi(){  

1. if(!isset($_SESSION['q']))$_SESSION['q'] = 0;  

1. return$_SESSION['q']++;  

1. }  

1. function hi2(){  

1. $rpc_client = new phprpc_client ("http://127.0.0.1/phprpc_example/test.php");  

1. return$rpc_client->hi();  

1. }  

1. $server = new PHPRPC_Server ();  

1. $server->add ('hi');  

1. $server->add ('hi2');  

1. $server->start ();  

1. ?>    





Js代码

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/A97A0CEED7654413B8AE61C2B3CE2C27icon_star.png)

1. function hi(){  

1. var rpc = new PHPRPC_Client('http://127.0.0.1/phprpc_example/test.php', ['hi2']);  

1.         rpc.hi2(function(result){;  

1.                         console.info(result);  

1.                 });  

1. }  





就會看見值一直遞增