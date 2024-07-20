一直在做php的开发工作.在开发的过程中老早就听说了“伪静态”这一说。但是一直没有对其进行了解。

今天终于下定决定 要好好的了解下这方面的内容。

首先，什么是伪静态：

伪静态又名URL重写，是动态的网址看起来像静态的网址。换句话说就是，动态网页通过重写 URL 方法实现去掉动态网页的参数，但在实际的网页目录中并没有必要实现存在重写的页面。

 

另外在补充两个名词解析

静态网址：纯静态HTML文档，能使用filetype:htm 查询到的网页

动态网址：内容存于数据库中，根据要求显示内容，URL中以 ？ # & 显示不同的参数，如：news.php？lang=cn&class=1&id=2

 

动态、静态、伪静态之间的利与弊（新）

动态网址

首先，动态网址目前对于Google来说，“不能被抓取”的说法是错误的，Google能够很好的处理动态网址并顺利抓取；其次“参数不能超过3个”的说法也不正确，Google能够抓取参数超过3个的动态网址，不过，为了避免URL太长应尽量减少参数。

其次，动态网址有其一定的优势，以上面所说的 news.php？lang=cn&class=1&id=2 为例，网址中的参数准确的告诉Google，此URL内容语言为cn、隶属于分类1、内容ID为2，更便于Google对内容的识别处理。

最后，动态网址应尽量精简，特别是会话标识（sid）和查询（query）参数，容易造成大量相同页面。

静态网址

首先，静态网址具有的绝对优势是其明晰，/product/nokia/n95.html和/about.html可以很容易被理解，从而在搜索结果中可能点击量相对较高。

其次，静态网址未必就是最好的网址形式，上述动态网址中说到，动态网址能够告诉Google一些可以识别的参数，而静态网址如果文档布置不够恰当（如：过于扁平化，将HTML文档全放在根目录下）及其他因素，反而不如静态网址为Google提供的参考信息丰富。

最后，樂思蜀觉得Google此文中是否有其隐藏含义？“更新此种类型网址的页面会比较耗费时间，尤其是当信息量增长很快时，因为每一个单独的页面都必须更改编译代码。”虽然所说的是网站，但在Google系统中是否同样存在这样的问题呢？

伪静态网址

首先，伪静态网址不能让动态网址“静态化”，伪静态仅仅是对动态网址的一个重写，Google不会认为伪静态就是HTML文档。

其次，伪静态可取，但应把重心放在去除冗余参数、规范URL、尽可能的避免重复页上。

最后，伪静态有很大潜大危险，最好在对网站系统、网站结构、内容分布、参数意义熟悉的情况下使用。

在写伪静态规则时，应保留有价值的参数，不要将有价值的参数全部精简掉，如前面例子中的 news.php？lang=cn&class=1&id=2 最好重写为 news-cn-class1-id2.html，而不是过份精简重写为 news-2.html。

再就是伪静态中一定不能包含会话标识（sid）和查询（query）参数，/product.asp？sid=98971298178906&id=1234 这样的动态网址，其中的sid本来Google能够识别并屏蔽，但如果重写为 /product/98971298178906/1234，Google不但无法识别，还在整站中造成无限重复页面（每个会话都会产生一个新的会话ID）。

 

我们应该选择伪静态还是真静态

      1、使用真静态和假静态对SEO来说没有什么区别

　　2、使用真静态可能将导致硬盘损坏并将影响论坛性能

　　3、使用伪静态将占用一定量的CPU占有率，大量使用将导致CPU超负荷

　　4、最重要的一点，我们要静态是为了SEO

　　所以：

　　1、使用真静态的方法可以直接排除了，因为无论怎么生成，对硬盘来说都是很伤的。

　　2、既然真伪静态的效果一样，我们就可以选择伪静态了。

　　3、但是伪静态大量使用会造成CPU超负荷。

　　4、所以我们只要不大量使用就可以了。

　　5、既然静态只是给SEO看的，我们只需要伪静态给SEO就行了，不需要给用户使用。

　　6、所以我们只要在专门提供给SEO爬的Archiver中使用伪静态就可以了。

　　7、谢谢大家耐心看我写的文章。

　　8、有何不解的地方或是有不同的看法欢迎提出

关于伪静态和真静态的评论  

      真正的静态化和伪静态还是有本质的区别的。为浏览用户处理一个纯粹html和一个调用多个数据的php在CPU的使用率方面明显前者少。记得原来有个人说html下载硬盘读写频繁，他这么说好像读取数据库不用读写磁盘似的，何况还有一大堆缓存的零散php也是放在硬盘的，这些读取不用磁盘操作么？可笑。

　　读取单个html+图片Flash等附件就可以实现的目的，何苦要读数据库又要读php缓存文件又要重新整合数据输出再+图片Flash等附件这么大费周章呢？CMS首页不需要很多的互动的，论坛那一套不应该拿到这里来用，相反应该更多考虑的是：美观！兼容！信息的直观！性能！还有稳定！

 

在转一个 php伪静态的实现四法：



 1 <?php

 2  //伪静态方法一

 3 

 4 // localhost/php100/test.php?id|1@action|2

 5  $Php2Html_FileUrl = $_SERVER["REQUEST_URI"];

 6  echo $Php2Html_FileUrl."<br>";// /php100/test.php?id|1@action|2

 7  $Php2Html_UrlString = str_replace("?","",str_replace("/", "", strrchr(strrchr($Php2Html_FileUrl, "/"),"?")));

 8  echo $Php2Html_UrlString."<br>";// id|1@action|2

 9  $Php2Html_UrlQueryStrList = explode("@", $Php2Html_UrlString);

10  print_r($Php2Html_UrlQueryStrList);// Array ( [0] => id|1 [1] => action|2 )

11  echo "<br>";

12  foreach($Php2Html_UrlQueryStrList as $Php2Html_UrlQueryStr)

13 {

14  $Php2Html_TmpArray = explode("|", $Php2Html_UrlQueryStr);

15  print_r($Php2Html_TmpArray);// Array ( [0] => id [1] => 1 ) ; Array ( [0] => action [1] => 2 )

16  echo "<br>";

17  $_GET[$Php2Html_TmpArray[0]] = $Php2Html_TmpArray[1];

18 }

19  //echo '假静态：$_GET变量<br />';

20  print_r($_GET); // Array ( [id|1@action|2] => [id] => 1 [action] => 2 )

21  echo "<br>";

22  echo "<hr>";

23  echo $_GET[id]."<br>";// 1

24  echo $_GET[action];// 2

25  ?>

26  

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 



![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 1 <?php

 2  //伪静态方法二

 3 

 4 // localhost/php100/test.php/1/2

 5  $filename = basename($_SERVER['SCRIPT_NAME']);

 6  echo $_SERVER['SCRIPT_NAME']."<br>";// /php100/test.php

 7  echo $filename."<br>";// test.php

 8  

 9  if(strtolower($filename)=='test.php'){

10  if(!empty($_GET[id])){

11   $id=intval($_GET[id]);

12   echo $id."<br>";

13   $action=intval($_GET[action]);

14   echo $action."<br>";

15  }else{

16   $nav=$_SERVER['REQUEST_URI'];

17   echo "1:".$nav."<br>";// /php100/test.php/1/2

18    $script=$_SERVER['SCRIPT_NAME'];

19   echo "2:".$script."<br>";// /php100/test.php

20    $nav=ereg_replace("^$script","",urldecode($nav));

21   echo $nav."<br>"; // /1/2

22    $vars=explode("/",$nav);

23   print_r($vars);// Array ( [0] => [1] => 1 [2] => 2 )

24    echo "<br>";

25   $id=intval($vars[1]);

26   $action=intval($vars[2]);

27  }

28  echo $id.'&'.$action;

29 }

30  ?>

31  

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 1 <?php

 2 //伪静态方法三

 3 

 4 

 5 function mod_rewrite(){

 6 global $_GET;

 7 $nav=$_SERVER["REQUEST_URI"];

 8 echo $nav."<br>";

 9 $script_name=$_SERVER["SCRIPT_NAME"];

10 echo $script_name."<br>";

11 $nav=substr(ereg_replace("^$script_name","",urldecode($nav)),1);

12 echo $nav."<br>";

13 $nav=preg_replace("/^.ht(m){1}(l){0,1}$/","",$nav);//这句是去掉尾部的.html或.htm

14 echo $nav."<br>";

15 $vars = explode("/",$nav);

16 print_r($vars);

17 echo "<br>";

18 for($i=0;$i<Count($vars);$i+=2){

19 $_GET["$vars[$i]"]=$vars[$i+1];

20 }

21 return $_GET;

22 }

23 mod_rewrite();

24 $year=$_GET["year"];//结果为'2006'

25 echo $year."<br>";

26 $action=$_GET["action"];//结果为'_add'

27 echo $action;

28 ?>

29 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 1 <?php

 2 //伪静态方法四

 3 

 4 //利用server变量 取得PATH_INFO信息 该例中为 /1,100,8630.html   也就是执行脚本名后面的部分

 5 if(@$path_info =$_SERVER["PATH_INFO"]){

 6 //正则匹配一下参数

 7 if(preg_match("/\/(\d+),(\d+),(\d+)\.html/si",$path_info,$arr_path)){

 8 $gid     =intval($arr_path[1]); //取得值 1

 9 $sid     =intval($arr_path[2]);   //取得值100

10 $softid   =intval($arr_path[3]);   //取得值8630

11 }else die("Path:Error!");

12 //相当于soft.php?gid=1&sid=100&softid=8630

13 }else die('Path:Nothing!');

14 ?>

15 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110775.jpg)

 

如果不想使用php来实现伪静态，可是使用 apache，nginx，iis 等服务器自带的url rewrite 功能进行设置。

 

参考资料：

http://baike.baidu.com/view/1570373.htm?fr=ala0_1#2

http://blog.sina.com.cn/s/blog_4a657b6b0100gdnk.html

http://www.chinaz.com/Webbiz/Exp/01041029142010.html

http://apps.hi.baidu.com/share/detail/5308118

