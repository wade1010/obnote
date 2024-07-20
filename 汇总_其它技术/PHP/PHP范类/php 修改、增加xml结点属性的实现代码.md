php 修改 增加xml结点属性的代码，供大家学习参考。

php修改xml结点属性，增加xml结点属性的代码，有需要的朋友，参考下。

1、xml文件

 复制代码代码如下:



   
   
   
   
   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
   
   
   
   
   

2、php代码

load('x.xml');$em=$dom->getElementsByTagName('emotions');$em=$em->item(0);$items=$em->getElementsByTagName('item');foreach($items as $a){foreach($a->attributes as $b){if($b->nodeValue=='Birthday'){$a->setAttribute('name','nBirthday');}}}$t=$dom->createElement('item');$t->setAttribute('name','x');$t->setAttribute('src','www.baidu.com');$t->setAttribute('duration','duration');$em->appendChild($t);$dom->save('x.xml');?>

PHP解析XML文档属性并编辑

load('data.xml'); $em=$dom->getElementsByTagName('videos');//最外层节点 $em=$em->item(0); $items=$em->getElementsByTagName('video');//节点 //如果不用读取直接添加的话把下面这一段去掉即可 foreach($items as $a){ foreach($a->attributes as $b){//$b->nodeValue;节点属性的值$b->nodeName;节点属性的名称  echo $b->nodeName;  echo ":";  // www.jbxue.com echo $b->nodeValue;  echo "

"; } } //下面是往xml写入一行新的 $t=$dom->createElement('video');//
  
  
  
  
  



当时的xml文档: 

 
  
  
  
  
    
   
   
   
   
     
   
   
   
   
     
   
   
   
   
      
  
  
  
  
   





//后改的可以修改xml

load('data.xml'); //查找 videos 节点 $root = $doc->getElementsByTagName('videos'); //第一个 videos 节点 $root = $root->item(0); //查找 videos 节点下的 video 节点 $userid = $root->getElementsByTagName('video'); //遍历所有 video 节点 foreach ($userid as $rootdata) { //遍历每一个 video 节点所有属性 foreach ($rootdata->attributes as $attrib) { $attribName = $attrib->nodeName;   //nodeName为属性名称 $attribValue = $attrib->nodeValue; //nodeValue为属性内容 //查找属性名称为ip的节点内容 if ($attribName =='img') { //查找属性内容为ip的节点内容 if ($attribValue =='1') { //将属性为img，img内容为1的修改为image; $rootdata->setAttribute('img','image'); $doc->save('data.xml'); } } } }  ?>