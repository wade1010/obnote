我现在有个问题，主要是Java客户端通过PHPRPC来读取PHP服务器端的数组问题。

php服务器端的代码如下：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/4ABEC966D2854F9A89082B9CDF683B2Eicon_star.png)

1. include ("phprpc/phprpc_server.php");    

1.     function helloWorld() {  

1. return'hello World ';  

1.     }  

1.     function TestMap() {  

1.        $map = array(  

1. "name" => "土豆丝",  

1. "price" => "15.5元"

1.        );  

1. return $map;    

1.     }  

1. $server = new PHPRPC_Server();  

1. $server->add('Hello');  

1. $server->add('TestMap');  

1. $server->start();  

1. ?>   





我在Java客户端的代码main方法如下：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/53A089B9950742A5B3F75949FF49474Aicon_star.png)

1. publicstaticvoid main(String[] args) {  

1.     PHPRPC_Client client = new PHPRPC_Client("http://10.10.10.59/zdpad/sample.php");  

1.     IHello clientProxy = (IHello)client.useService(IHello.class);  

1.     System.out.println(clientProxy.helloWorld());  

1.     HashMap testMap = clientProxy.testMap();  

1.     System.out.println(testMap);  

1. //输出数组元素

1.     System.out.println("Map name = " + testMap.get("name"));  

1.     System.out.println("Map price = " + (String)testMap.get("price"));  

1. }  





输出结果为：

hello World

{name=[B@100ab23, price=[B@e3b895}

Map name = [B@100ab23

Exception in thread "main" java.lang.ClassCastException: [B cannot be cast to java.lang.String

at test.HelloWorld.main(HelloWorld.java:61)



请问，如何才能得到 name 和 price 下标所对应的值呢？

PS：我使用 AssocArray 这个测试，但是，也没有测试出结果来。 





下面是自己demo   

interface Say {

public String say(String name);

public String HelloWorld(String name);

public AssocArray TestMap();

}

public class Main {



public static void main(String[] args) {

PHPRPC_Client client = new PHPRPC_Client("http://192.168.0.88/phprpc/server.php");

Say hello=(Say) client.useService(Say.class);

AssocArray testMap= hello.TestMap();  

System.out.println("Map name = " + Cast.toString(testMap.get("name")));    

System.out.println("Map price = " + Cast.toString(testMap.get("price"))); 

}



}



输出结果:

Map name = 土豆丝

Map price = 15.5元





