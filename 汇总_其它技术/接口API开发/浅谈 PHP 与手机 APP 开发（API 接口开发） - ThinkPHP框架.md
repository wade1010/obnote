1. MySQL://Nginx:PHP@Linux => 开发交流Q群：12349137

复制代码

推荐阅读： RESTful 是什么？一起来理解 RESTful 架构 更深入了解API开发



这个帖子写给不太了解PHP与API开发的人



一、先简单回答两个问题：



1、PHP 可以开发客户端？

答：不可以，因为PHP是脚本语言，是负责完成 B/S架构 或 C/S架构 的S部分，即：服务端的开发。（别去纠结 GTK、WinBinder）



2、为什么选择 PHP 作为开发服务端的首选？

答：跨平台（可以运行在UNIX、LINUX、WINDOWS、Mac OS下）、低消耗（PHP消耗相当少的系统资源）、运行效率高（相对而言）、MySQL的完美搭档，本身是免费开源的，......



二、如何使用 PHP 开发 API（Application Programming Interface，应用程序编程接口） 呢？



做过 API 的人应该了解，其实开发 API 比开发 WEB 更简洁，但可能逻辑更复杂，因为 API 其实就是数据输出，不用呈现页面，所以也就不存在 MVC（API 只有 M 和 C），

1、和 WEB 开发一样，首先需要一些相关的参数，这些参数，都会由客户端传过来，也许是 GET 也许是 POST，这个需要开发团队相互之间约定好，或者制定统一规范。

2、有了参数，根据应用需求，完成数据处理，例如：任务进度更新、APP内购、一局游戏结束数据提交等等

3、数据逻辑处理完之后，返回客户端所需要用到的相关数据，例如：任务状态、内购结果、玩家信息等等

数据怎么返给客户端？

直接输出的形式，如：JSON、XML、TEXT 等等。

4、客户端获取到你返回的数据后，在客户端本地和用户进行交互



临时写的一个简单 API 例子：

1. php

1. $output = array();

1. $a =@$_GET['a']? $_GET['a']:'';

1. $uid =@$_GET['uid']? $_GET['uid']:0;

1. if(empty($a)){

1.     $output = array('data'=>NULL,'info'=>'坑爹啊!','code'=>-201);

1. exit(json_encode($output));

1. }

1. //走接口

1. if($a =='get_users'){

1. //检查用户

1. if($uid ==0){

1.         $output = array('data'=>NULL,'info'=>'The uid is null!','code'=>-401);

1. exit(json_encode($output));

1. }

1. //假设 $mysql 是数据库

1.     $mysql = array(

1. 10001=> array(

1. 'uid'=>10001,

1. 'vip'=>5,

1. 'nickname'=>'Shine X',

1. 'email'=>'979137@qq.com',

1. 'qq'=>979137,

1. 'gold'=>1500,

1. 'powerplay'=> array('2xp'=>12,'gem'=>12,'bingo'=>5,'keys'=>5,'chest'=>8),

1. 'gems'=> array('red'=>13,'green'=>3,'blue'=>8,'yellow'=>17),

1. 'ctime'=>1376523234,

1. 'lastLogin'=>1377123144,

1. 'level'=>19,

1. 'exp'=>16758,

1. ),

1. 10002=> array(

1. 'uid'=>10002,

1. 'vip'=>50,

1. 'nickname'=>'elva',

1. 'email'=>'elva@ezhi.net',

1. 'qq'=>NULL,

1. 'gold'=>14320,

1. 'powerplay'=> array('2xp'=>1,'gem'=>120,'bingo'=>51,'keys'=>5,'chest'=>8),

1. 'gems'=> array('red'=>13,'green'=>3,'blue'=>8,'yellow'=>17),

1. 'ctime'=>1376523234,

1. 'lastLogin'=>1377123144,

1. 'level'=>112,

1. 'exp'=>167588,

1. ),

1. 10003=> array(

1. 'uid'=>10003,

1. 'vip'=>5,

1. 'nickname'=>'Lily',

1. 'email'=>'Lily@ezhi.net',

1. 'qq'=> NULL,

1. 'gold'=>1541,

1. 'powerplay'=> array('2xp'=>2,'gem'=>112,'bingo'=>4,'keys'=>7,'chest'=>8),

1. 'gems'=> array('red'=>13,'green'=>3,'blue'=>9,'yellow'=>7),

1. 'ctime'=>1376523234,

1. 'lastLogin'=>1377123144,

1. 'level'=>10,

1. 'exp'=>1758,

1. ),

1. );

1.     $uidArr = array(10001,10002,10003);

1. if(in_array($uid, $uidArr,true)){

1.         $output = array('data'=> NULL,'info'=>'The user does not exist!','code'=>-402);

1. exit(json_encode($output));

1. }

1. //查询数据库

1.     $userInfo = $mysql[$uid];

1. //输出数据

1.     $output = array(

1. 'data'=> array(

1. 'userInfo'=> $userInfo,

1. 'isLogin'=>true,//是否首次登陆

1. 'unread'=>4,//未读消息数量

1. 'untask'=>3,//未完成任务

1. ),

1. 'info'=>'Here is the message which, commonly used in popup window',//消息提示，客户端常会用此作为给弹窗信息。

1. 'code'=>200,//成功与失败的代码，一般都是正数或者负数

1. );

1. exit(json_encode($output));

1. } elseif ($a =='get_games_result'){

1. //...

1. die('您正在调 get_games_result 接口!');

1. } elseif ($a =='upload_avatars'){

1. //....

1. die('您正在调 upload_avatars 接口!');

1. }

复制代码

点击测试（对于客户端而言，也是直接调用这样的地址）：

http://www.ezhi.net/api/test/index.php

http://www.ezhi.net/api/test/index.php?a=get_users

http://www.ezhi.net/api/test/index.php?a=get_users&uid=10001

http://www.ezhi.net/api/test/index.php?a=get_users&uid=10002

http://www.ezhi.net/api/test/index.php?a=get_users&uid=10003



三、实际项目中，我们在开发 API 应该注意的几个事项（仅供参考）：

1、单文件实现多接口的形式有很多种，例如：if..elseif.. 或 switch 或 动态方法 (也就是TP的这种访问函数体的形式)

2、对于数据的输出最好用json，json具有相当强大的跨平台性，市场上各大主流编程语言都支持json解析，json正在逐步取代xml，成为网络数据的通用格式

3、接口安全，一定要增加接口验证。例如，客户端和服务端针对不同接口统一做好加密方式，服务端在对于每次接口需要都要进行验证。以保证防止接口被恶意刷新或黑客恶意调用，尤其是大型商业应用。

4、对于线上的 API 必须保证所有接口正常且关闭所有的错误信息 => error_reporting(0)，在输出JSON 时，不能有任何其它输出，否则，客户端将解析数据失败，直接 Crash！

5、开发 API 和 WEB 有一定的区别，如果是 WEB 的话，可能代码出错了，不会导致特别严重的错误，也许只是导致数据写入和查询失败，也许导致 WEB 的某个部分错位或乱码。但如果是 API，直接 Crash！

6、做接口开发，不建议使用框架开发，原因概括起来有两点（其实我有点冒风险的，本人也是 TPer 一枚，毕竟这是TP的官网）：

　　1）客户端一般对服务端的响应速度有极高要求，因此，使用最原生态的 PHP 完成接口开发，是最高效的，假如用到了框架，还需要加载各种不需要多余的文件，就好比夏天穿了件冬天的衣服。试想，你在玩手机的时候，使用一个应用随便一个操作，等半天才有动静，你受的了吗？



　　2）就是上面第4点提到的，框架对于WEB开发，是件很幸福的事，但对于 API 而言，你实在不敢想象它会给你出什么岔子！最后你将痛苦不堪~~因为很多框架都是为 WEB 诞生的（我也很期待有一天能看到专门为开发 API 而生的框架或者扩展）



　　这个也有人纠结，接口效率与稳定性，还得看编码的人，有的人可能写的还不如框架跑的快，也有人觉得用框架没什么问题，这里只是建议，关键看自己的实际情况，同时建议代码上线前压测一下



　　说到这，不得不说扯一下，腾讯微博淘宝等开放平台。其实那些开放平台，所谓的开放，就是给你提供一个这样的接口，你根据他们提供的技术文档，按他们制定的格式和要求，调它们提供的接口文件（一般都是返回JSON或者XML），你就可以获取到他们的相关信息，例如：QQ用户基本信息、淘宝店铺、商品消息等等。然后在根据这些消息，在你的应用里完成交互。



　　其实，ajax 也是调用 API 的一种体现形式，你觉得呢？ 呵呵~~

AD： 8小时应聘1500家公司，创新工场投资，JobDeer.com比猎头更靠谱。