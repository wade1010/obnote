http://blog.mn886.net/jqGrid/





一、jqGrid 官方安装文档

JQGrid是一个在jquery基础上做的一个表格控件，以ajax的方式和服务器端通信。

首先是安装，只有安装对了，才能进行使用，当然官方的安装方法是最权威的 

下面主要总结下自己在官方文档的学习思路（后面附有第一个 jqGrid Demo实例）

 官网：http://www.trirand.com

 官方安装文档：http://www.trirand.com/jqgridwiki/doku.php?id=wiki:how_to_install

 google搜索关键字：install jqGrid

注意：1、如果想支持IE6最好用 jqGrid 4.4.4以下版本+jQuery ui 2.0以下版本

2、如果想支持多表头 jquery.jqGrid-4.3版本及以上

开发中用到的组合为：jquery.jqGrid-4.4.3+jquery-ui-1.7.2.custom（这两个版本对应）



有兴趣可看：

有官网找到官方安装文档：

1、进入主页后，点击Documentation，看到的信息如下

Online Documentation (this is always the most up-to-date)

The official documentation can be read in jqGrid Wiki site

在线文档(这始终是最新的)官方文档可以读入 jqGrid Wiki站点 

2、点击 jqGrid Wiki site -->Documentation-->Installing jqGrid-->How to Install

二、jqGrid下载及安装

1、下载需要的jqGrid包

    地址：http://www.trirand.com/blog/?page_id=6

 2、下载 JQuery UI theme，需要与jqGrid配合使用

    地址：http://jqueryui.com/download

 3、基本安装：（官方文档）

    步骤1：解压jqGrid和UI主题zip文件到一个临时文件夹中。

    步骤2：创建一个目录在您的web服务器来保存jqGrid文件和文件夹。例如http://myserver/myproject/

    步骤3：根据myproject的文件夹,创建两个额外的目录名为js和css。

    步骤4：复制文件jqGrid包css目录下的ui jqgrid.css文件到上面创建的css目录。            

          重要:不要不小心复制文件ui jqgrid.css从src / css目录的包,这个是开发包，不需要这个

    步骤5：复制jqGrid包下整个目录js到上面创建的js目录。

    步骤6：复制UI主题包下整个目录css到上面创建的css目录。 

这六个步骤之后,您应该有以下文件和文件夹结构:

/myproject/css/

    ui.jqgrid.css

    /ui-lightness/

      /images/

      jquery-ui-1.7.2.custom.css

/myproject/js/

   /i18n/

      grid.locale-bg.js

      list of all language files

      ….

   Changes.txt

   jquery-1.4.2.min.js

   jquery.jqGrid.min.js  

使用您喜欢的编辑器,创建一个文件myfirstgrid.html写入下面的代码

My First Grid

html, body {

margin: 0;

padding: 0;

font-size: 75%;

}













注意：js/i18n/grid.locale-en.js一定要在js/jquery.jqGrid.min.js的前面引入

（这里是基本使用的安装，普通开发应用足够，如果开发需要改里面的js，可以参见Development Installation）



三、下面给出一个我自己的Demo，是在官方jqGrid Demo中参考来的

官方Demo地址： http://www.trirand.com/blog/jqgrid/jqgrid.html





My First Grid

html, body {

margin: 0;

padding: 0;

font-size: 75%;

}

 

$(document).ready(

function() {

jQuery("#list4"). jqGrid({

datatype: "local",

height: 250,

colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],

colModel:[

{name:'id',index:'id', width:60, sorttype:"int"},

{name:'invdate',index:'invdate', width:90, sorttype:"date"},

{name:'name',index:'name', width:100},

{name:'amount',index:'amount', width:80, align:"right",sorttype:"float"},

{name:'tax',index:'tax', width:80, align:"right",sorttype:"float"},

{name:'total',index:'total', width:80,align:"right",sorttype:"float"},

{name:'note',index:'note', width:150, sortable:false}

],

multiselect: true,

caption: "Manipulating Array Data"

});

var mydata = [

{id:"1",invdate:"2007-10-01",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},

{id:"2",invdate:"2007-10-02",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},

{id:"3",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},

{id:"4",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},

{id:"5",invdate:"2007-10-05",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},

{id:"6",invdate:"2007-09-06",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},

{id:"7",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},

{id:"8",invdate:"2007-10-03",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},

{id:"9",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"}

];

for(var i=0;i<=mydata.length;i++)

jQuery("#list4"). jqGrid('addRowData',i+1,mydata[i]);

});

