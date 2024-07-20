核心代码

```javascript
public function trans($num){
        $res='';
        while($num>62){
            $res=$this->table[($num%62)].$res;
            $num=floor($num/62);
        }
        if($num>0){
            $res=$this->table[$num].$res;
        }
        return $res;
    }
```



其中很多操作mongo的方法 新版本已经废弃，以前是用哪个mongo.so 现在已经使用mongodb.so



1.短网址项目，是将给定的长网址，转换成短网址。

如 新浪 http://t.cn/zQd5NPw ，其中zQd5NPw就是短网址

前段页面如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809678.jpg)

我们在文本框中输入一个网址，点击生成短网址，系统就去mongodb中查，如果查得到，就直接返回短网址，如果查不到，就计算出短网址，并且加入到mongodb中，再返回短网址，给前端页面。

2.点击短网址 跳转到原有网站

 

短网址项目开发准备

公关点：

1. 短网址和原有网址的映射关系

　　形如d.cn/acF54Và解析到某一个固定的页面上

　　解决：在apache中用URL重写，在nginx中用location

　　2. 如何生成短网址，如zQd5NPw

　　分析：

　　　　2.1短

　　　　2.2为公众服务，存储的网址特别多

　　　　注：a、b两条是矛盾的，短自然存储的就少，所以要合理的设计短网址的组成规律

　　　　解决：请看短网址生成办法(62进制数)

　　3. 如何跳转

　　302跳转

 

短网址生成办法

我们为每一个网址，指定一个序号，该序号是该网址被加到数据库的顺序。有一个全局的递增数据，来存储当前共有多少个网址被转换成短网址，并且被加入到数据库中。我们把该序号转换成62进制数，转换成的62进制数就是短网址。62进制数是由[a-zA-Z0-9]打乱之后表示的。

1.  用a-z,A-Z,0-9，打乱之后，共62位，组成62进制的数

7位可以存储62^7，大约存储4万亿条数据

1. 链接编码看起来”无规律一些”

十进制的URL的id转换为62进制



```javascript
<?php
$str='abcdefghijk......zABCD......Z0123456789';//点点部分自己补全
var_export(str_split(str_shuffle($str)));


开头的核心代码中的$this->table 就是等于这个62个字符的数组
```

 

在mongodb中如何生成一个不断递增的数据

就像mysql中的increment，redis中的incr，oracle中的sequence

通过db.collection.findAndModify();

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809876.jpg)

我们需要两个表，一个用来存储短网址和长网址的映射，取名为url，一个全局的序号生成器，取名为globalsn。

mongodb建模

库：info

表：url

文档的格式：

{_id:xxxx,sn:62进制数,oriurl:http://www.baidu.com,hits:0}

我们需要给url表添加唯一索引，因为我们的短网址，在表中存储一条就够了。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809286.jpg)

全局的序号生成器

库：info

表：globalsn

{_id:1,sn:0}

 

 

生成界面

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809316.jpg)

url表

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809434.jpg)

 

nginx做如下修改

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809581.jpg)



```javascript
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>新建网页</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="" />
<meta name="keywords" contend="" />
<script type="text/javascript" src="http://libs.baidu.com/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
    $(function (){
        $('#subm').click(function(){
            var oriurl=$('#ori').val();
            if(oriurl==''){
                alert('请输入网址!')
                return;
            }
            $.post(
                '/api.php',
                {'oriurl':oriurl},
                function(msg){
                    //alert('short url is'+msg);
                    $('#msg').html('短网址：'+'http://d.cn/'+url);
                }
            );
        })
    });
</script>
<style type="text/css">
    div{
        width:800px;
        margin:0 auto;
    }
    #ori{
        width: 500px;
        height: 40px;
        font-size: 36px;
    }
    #subm{
        width: 200px;
        height: 40px;
        font-size: 36px;
    }
</style>
</head>
<body>
    <div>
        <h1>mongodb短网址生成</h1>
        <input id="ori" type="text" name="oriurl"></input>
        <input id="subm" type="submit" value="生成短网址"></input>
        <div id="msg"></div>
    </div>
</body>
</html>
```



 

```javascript
<?php
require('./url.class.php');
$url = new url();
$oriurl = $_POST['oriurl'];
$short = $url->addUrl($    oriurl)
echo $short;
```

 

```javascript
 
<?php
class url{
    public function __construct(){
        $mongoClient = new mongoCLient(‘mongodb://..’);
        //选择test库
        $this->mongodb=$mongoClient->test;
    }
    //trans函数功能，把任意一个整数，转化成自定义的62进制
    //62进制规则表，在table中
    public function trans($num){
        $res=’’;
        while($num>62){
            $res=$this->table[($num%62)].$res;
            $num=floor($num/62);
        }
        if($num>0){
            $res=$this->table[$num].$res;
        }
        return $res;
    }
    public function getSn(){
        $cnt = $this->mongodb->selectCollection(‘cnt’);
        $seq = $cnt->findAndModify(array(‘_id’=>1),array(‘$inc’=>array(‘sn’=>1)));
        return (int)$seq[‘sn’];
    }
    public function getId($ori){
        $url = $this->mongodb->selectCollection('url');
        $row = $url->findOne(array('url'=>$ori),array('_id'=>1));
        return $row;
    }
    public function getUrl($id){
        $url=$this-mongodb->selectCollection('url');
        $row=$url-fineOne(array('_id'=>$id),array('url'=>1));
        return $row;
    }
    public function inchits($id){
        $url=$this->mongodb->selectCollection('url');
        $url->update(array('_id'=>$id),array('$inc'=>array('hits'=>1)));
    }
    public function addUrl($oriurl){
        $sn=$this->getSn();
        $sn=$this->trans($sn);
        $row=array(‘_id’=>$sn,’url’=>$oriurl);
        //选择表url
        $url=$this->mongodb->selectCollection(‘url’);
        //插入记录
        $url->insert($row);
        return $sn;
        //print_r($row);
    }


}
```



```javascript
 
<?php
//print_r($_GET)
require(./url.class.php);

$id=$_GET['id'];

$url=new url();
$row=$url->getUrl($id);

if(empty($row)){
    echo 'err address!'
    exit;
}
$url->inchits($id);
//302跳转
header('Location: '.$row['url'],302);
```



