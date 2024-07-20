![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/ED395F2657C640DF917DC30D25BC1F3B9572039e-c0af-4761-b0b2-a41bb3a357c4-thumb.jpg.jpeg)

fw2003 2010-01-18

有服务方法如下

public void showUsers(List list) {}



以js为客户端,参数为json数组(因为之前测试过如果js接收服务器返回的List正是json数组)

var users=[{name:"fw",password:"123"},{name:"jr",password:"321"}];



在进行调用时发现服务方法中List中的元素类型为AssocArray,查看了文档后解决如下:

1.先将AssocArray对象转换成map,然后以客户端json对象的属性作为键得到byte[]形式的数据再转为String



2.简单一点的是使用Cast.cast(AssocArray对象, User.class)将一个AssocArray对象转换为User对象.





请问还有没有其他方法,因为这样做看起来服务方法中定义泛型并没有实际意义

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/13C021EF59EC46FC829B2AD6D044386F9a34210a-1913-3bc9-a720-ed58cac81d1a-thumb.jpg.jpeg)

TonyLian 2010-01-19

关于泛型，我也是觉得没什么意义。

Hessian也不支持泛型，除了“年代久远”的问题外，似乎在不同语言间的传递，

泛型真的发挥不了作用？？



请明白人给解释一下哈

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/0651CFAE6A38408B8E987C6CF8D074ABbc86ba4e-0e76-3236-b658-5d2830b4510d-thumb.png)

andot 2010-01-19

嗯，因为泛型中的 User 类型不能在运行时被获取，因此客户端传入的{name:"fw",password:"123"}这个对象，只能被解析为HashMap，所以，不能自动转换成List，如果想要传User对象的话，那么客户端不应该写{name:"fw",password:"123"}，而应该定义一个 function User(name, password) { this.name = name; this.password = password; }，然后传递 new User("fw", "123")这样的对象，即：



var users=[new User("fw","123"),new User("jr","321")];



只要服务器端的User类拥有相同的类名（含包名）和字段，就可以传递给 List了。

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/9FADC8F5ED024811B22A12E9576A46039572039e-c0af-4761-b0b2-a41bb3a357c4-thumb.jpg.jpeg)

fw2003 2010-01-19

andot 写道

嗯，因为泛型中的 User 类型不能在运行时被获取，因此客户端传入的{name:"fw",password:"123"}这个对象，只能被解析为HashMap，所以，不能自动转换成List，如果想要传User对象的话，那么客户端不应该写{name:"fw",password:"123"}，而应该定义一个 function User(name, password) { this.name = name; this.password = password; }，然后传递 new User("fw", "123")这样的对象，即：



var users=[new User("fw","123"),new User("jr","321")];



只要服务器端的User类拥有相同的类名（含包名）和字段，就可以传递给 List了。







谢谢解答 

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/5C66F8FC1D284A27B4376E3A524D2BF5icon_biggrin.gif)



但是我按照你所说的修改了代码之后,和之前的效果差不多,服务器接收的List里面虽然不再是AssocArray,但也不是User,而是HashMap,



JS代码如下:

var rpc_client = new PHPRPC_Client(URL,["showUsers","getUsers"]);

function User(name,password){

          this.name = name;

          this.password = password;

}

var users=[new User("fw","123"),new User("jr","123")];

        rpc_client.showUsers(users);





服务端方法如下

public void showUsers(List list) {

     System.out.println(list.get(0));

               .............

}



控制台输出为{password=[B@113dd59, name=[B@12929b2}



不知道是什么原因,再次请教

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/7E3FB954EA2544B8B103A4C719C3DE2Ebc86ba4e-0e76-3236-b658-5d2830b4510d-thumb.png)

andot 2010-01-19

fw2003 写道

andot 写道

嗯，因为泛型中的 User 类型不能在运行时被获取，因此客户端传入的{name:"fw",password:"123"}这个对象，只能被解析为HashMap，所以，不能自动转换成List，如果想要传User对象的话，那么客户端不应该写{name:"fw",password:"123"}，而应该定义一个 function User(name, password) { this.name = name; this.password = password; }，然后传递 new User("fw", "123")这样的对象，即：



var users=[new User("fw","123"),new User("jr","321")];



只要服务器端的User类拥有相同的类名（含包名）和字段，就可以传递给 List了。







谢谢解答 

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/82E681E8BDD14354A419F3047F86C418icon_biggrin.gif)



但是我按照你所说的修改了代码之后,和之前的效果差不多,服务器接收的List里面虽然不再是AssocArray,但也不是User,而是HashMap,



JS代码如下:

var rpc_client = new PHPRPC_Client(URL,["showUsers","getUsers"]);

function User(name,password){

          this.name = name;

          this.password = password;

}

var users=[new User("fw","123"),new User("jr","123")];

        rpc_client.showUsers(users);





服务端方法如下

public void showUsers(List list) {

     System.out.println(list.get(0));

               .............

}



控制台输出为{password=[B@113dd59, name=[B@12929b2}



不知道是什么原因,再次请教





是 HashMap 的话，已经充分说明已经传递对象过去了，但是你的服务器端的 User 类是带包名的，而js那个是不带包名的，所以才不能被直接反序列化为你服务器端的 User 类对象。



所以，你要保证类名（包括包名）相同。例如如果你的服务器端的User类的全名为：



my.package.User，那么你客户端应该这样定义：



var User = function my_package_User(name,password){

          this.name = name;

          this.password = password;

}



也就是说，客户端是根据 function 的方法名作为类名进行序列化的，类名中的_号会在java中与.匹配。

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/9750659B60534A23B6218F8FB37E81D7user-logo-thumb.gif)

diggywang 2010-01-20

转成XML，王道啊王道！

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/D590BEF0152E4ED3B7E98721E22308999572039e-c0af-4761-b0b2-a41bb3a357c4-thumb.jpg.jpeg)

fw2003 2010-01-20

andot 写道

fw2003 写道

andot 写道

嗯，因为泛型中的 User 类型不能在运行时被获取，因此客户端传入的{name:"fw",password:"123"}这个对象，只能被解析为HashMap，所以，不能自动转换成List，如果想要传User对象的话，那么客户端不应该写{name:"fw",password:"123"}，而应该定义一个 function User(name, password) { this.name = name; this.password = password; }，然后传递 new User("fw", "123")这样的对象，即：



var users=[new User("fw","123"),new User("jr","321")];



只要服务器端的User类拥有相同的类名（含包名）和字段，就可以传递给 List了。







谢谢解答 

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/516334D2643449ABAF83411C20338AEAicon_biggrin.gif)



但是我按照你所说的修改了代码之后,和之前的效果差不多,服务器接收的List里面虽然不再是AssocArray,但也不是User,而是HashMap,



JS代码如下:

var rpc_client = new PHPRPC_Client(URL,["showUsers","getUsers"]);

function User(name,password){

          this.name = name;

          this.password = password;

}

var users=[new User("fw","123"),new User("jr","123")];

        rpc_client.showUsers(users);





服务端方法如下

public void showUsers(List list) {

     System.out.println(list.get(0));

               .............

}



控制台输出为{password=[B@113dd59, name=[B@12929b2}



不知道是什么原因,再次请教





是 HashMap 的话，已经充分说明已经传递对象过去了，但是你的服务器端的 User 类是带包名的，而js那个是不带包名的，所以才不能被直接反序列化为你服务器端的 User 类对象。



所以，你要保证类名（包括包名）相同。例如如果你的服务器端的User类的全名为：



my.package.User，那么你客户端应该这样定义：



var User = function my_package_User(name,password){

          this.name = name;

          this.password = password;

}



也就是说，客户端是根据 function 的方法名作为类名进行序列化的，类名中的_号会在java中与.匹配。





再次感谢耐心地回复

有种似曾相似的感觉...看来我看文档还是不够仔细

修改后已经成功了

谢谢回复!











![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/D246A49D1AF14F3685B8662633457914bc86ba4e-0e76-3236-b658-5d2830b4510d-thumb.png)

andot 2010-01-20

diggywang 写道

转成XML，王道啊王道！



在这里不用XML才是王道。XML只适合写写配置文件，做数据传输太浪费带宽和CPU了。