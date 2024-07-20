php+socket请求原理

1：连接某URL的80端口（打开）

2：发送头信息（写）

3：读取网页内容（读）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024797.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190024201.jpg)





GET方法



```javascript
<?php

/*
PHP+socket编程  发送请求

要求能 模拟下载、注册、登陆、批量发帖
*/


//http请求类的接口
interface proto{
     //连接url
	 function conn($url);

	 //发送get查询
	 function get();

	 //发送post查询
	 function post();

	 //关闭连接
	 function close();

}

Class Http implements Proto{

	const CRLF ="\r\n";

	protected $erron=-1;
	protected $errstr='';
	protected $response='';

	protected $url=null;
	protected $version='HTTP/1.1';
	protected $fh=null;

	protected $line=array();
	protected $header=array();
	protected $body=array();


	public function _construct($url){
		$this->conn($url);
		 $this->setHeader('Host:' .$this->url['host'] );
	}


    //此方法负责写请求行
	protected function setLine($method){
		$this->Line[0]=$method . '' . $this->url['path'] . ' ' . $this->version;
	}
    //此方法负责写头信息
	protected function setHeader($hederline){
		$this->header[]=$headerline;
	}
	//此方法负责写主体信息
	protected function setBody(){
	}
     //连接url
	public function conn($url){
	   $this->url= parse_url($url);

	   //判断端口
	   if(isset($this->url['port'])){
		   $this->url['port']=80;
	   }


	   $this->fh= fsockopen($this->url['host'],$this->url['port'],$this->errno,$this->errstr,3);
	 }

	 //构造get请求的数据
	public function get(){
	     $this->setLine('GET');
		 $this->request();
		 return $this->response;
		
	 }

	 //构造post查询的数据
	public function post(){
	}

	 //真正请求
	public function request(){
		//把请求行，头信息，实体信息，放在一个数组中，便于拼接
		$req=array_merge($this->line,$this-
			>header,array(''),$this->body,array(''));
		//print_r($req);
		$req=implode(self::CRLF,$req);
		//echo $req;

		fwrite($this->fh,$req);

		while(!feof($this->fh)){
			$this->response.=fread($this->fh,1024);
	 }
	 $this->close();//关闭连接
     return $this->response;
}

	 //关闭连接
	 function close(){
	 }

}
/*
$url='http://renjian.163.com/20/1019/11/FPA1LP5V000181RV.html';
$http=new Http($url);
echo $http->get();
*/


```



POST发送



```javascript
<?php

/*
PHP+socket编程  发送请求

要求能 模拟下载、注册、登陆、批量发帖
*/


//http请求类的接口
interface proto{
     //连接url
	 function conn($url);

	 //发送get查询
	 function get();

	 //发送post查询
	 function post();

	 //关闭连接
	 function close();

}

Class Http implements Proto{

	const CRLF ="\r\n";

	protected $erron=-1;
	protected $errstr='';
	protected $response='';

	protected $url=null;
	protected $version='HTTP/1.1';
	protected $fh=null;

	protected $line=array();
	protected $header=array();
	protected $body=array();


	public function _construct($url){
		$this->conn($url);
		 $this->setHeader('Host:' .$this->url['host'] );
	}


    //此方法负责写请求行
	protected function setLine($method){
		$this->Line[0]=$method . '' . $this->url['path'] . ' ' . $this->version;
	}
    //此方法负责写头信息
	protected function setHeader($hederline){
		$this->header[]=$headerline;
	}
	//此方法负责写主体信息
	protected function setBody($boby){
		$this->boby[]=http_build_query($body);
	}
     //连接url
	public function conn($url){
	   $this->url= parse_url($url);

	   //判断端口
	   if(isset($this->url['port'])){
		   $this->url['port']=80;
	   }


	   $this->fh= fsockopen($this->url['host'],$this->url['port'],$this->errno,$this->errstr,3);
	 }

	 //构造get请求的数据
	public function get(){
	     $this->setLine('GET');
		 $this->request();
		 return $this->response;
		
	 }

	 //构造post查询的数据
	public function post($boby=array()){
		$this->setLine('POST');

		//设计content-type
		$this->setHeader('Content-type:application/x-www-form-urlencoded')

		
		//设计主题信息，比GET不一样的地方
		$this->setBoby($boby);

		$this->request();

		//计算content-length
		$this->setHeader('Content-length:' . strlen($this->boby[0]))

	}

	 //真正请求
	public function request(){
		//把请求行，头信息，实体信息，放在一个数组中，便于拼接
		$req=array_merge($this->line,$this-
			>header,array(''),$this->boby,array(''));
		//print_r($req);
		$req=implode(self::CRLF,$req);
		//echo $req;exit;

		fwrite($this->fh,$req);

		while(!feof($this->fh)){
			$this->response.=fread($this->fh,1024);
	 }
	 $this->close();//关闭连接
     return $this->response;
}

	 //关闭连接
	public function close(){
		fclose($this->fh);

	 }

}
/*
$url='http://renjian.163.com/20/1019/11/FPA1LP5V000181RV.html';
$http=new Http($url);
echo $http->get();
*/

set_time_limit(0);

$url='http://renjian.163.com/20/1019/11/FPA1LP5V000181RV.html';


for($i=1;$i<100;$i++){

$str=str_shuffle'abcdfghijkimnopqrst0776656';

$tit=substr($str,0,5);
$con=substr($str,6,8);


$http=new Http($url);
echo $http->post(array('tit'=>$tit,'con'=>$con,'submit'=>'留言'));

echo $tit,'-----------',$con,'<br />';

usleep(2000);
}
```





无状态？

2次请求之间没有关系

？服务器如何记住一个客户？

cookie 模拟登录发贴，cookie可以记住用户



```javascript
<?php


require('./http.class.php')


$http=new Http('http://home.verycd.com/cp.php?ac=pm&op=send&touid=0&pmid=0');

$http->setHeader('Referer: ');
$http->setHeader('User-Agent: ');
$http->setHeader('cookie: ');


$msg =array(
'formhash'=>'4f23e777',
'message'=>'world',
'pmsubmit'=>'true',
'pmsubmit_btn'=>'发送',
'refer'=>'http://home.verycd.com/space.php?do=pm&filter=privatepm',
'username'=>'http接收'
);

file_put_contents('./res.htlm' $http->post($msg ));
echo '0k';
```



Http协议之referer防盗链

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024562.jpg)

当我们在网页里引用站外图片时，会出现上述情况。那么服务器是怎么知道这个图片是在站外被引用的呢？

在Http协议中，头信息里有一个重要的选项：Referer，代表网页的来源，既上一页的地址，如果是直接在浏览器上输入地址，回车进来，则没有Referer。

配置apache服务器，用于图片防盗链?

原理：在web服务器层面，根据http协议的referer头信息来判断。如果来自站外，则统一重写到一个很小的防盗链图片上去。

具体步骤：1.打开apache重写模块mod_rewrite，2.在需要防盗链的网站或目录，写.htaccess文件，并指定防盗链规则，分析referer信息，如果不是来自本站，则重写。

重写规则：

①哪种情况下重写：是jpeg/jpg/gif/png图片时，referer头与localhost不匹配时重写，统一rewrite到某个防盗链图片

```javascript
RewriteEngine OnRewrite Base /test
RewriteCond %{REQUEST_FILENAME} .*\.(jpg|jpeg|gif|png) [NC]
RewriteCond %{HTTP_REFERER} !localhost [NC]
RewriteRule  .* no.png 
```

伪造referer头采集防盗链图片：



```javascript
<?php
require('./http.class.php');

$http = new Http('http://localhost/test/test.jpg');    //图片路径

$http->setHeader('Referer: http://localhost');    //伪造referer
$res = $http->get();
//应该判断路径或response的mime头信息，来确定图片的类型
file_put_contents('./aa.jpg',substr(strstr($res,"\r\n\r\n"),4)); 
```



