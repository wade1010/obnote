1.隐藏头多选checkbox

var myGrid = $("#list");

$("#cb_"+myGrid[0].id).hide();

  列表中checkbox隐藏为multiselect: false

2.表头capiton 设置为

 caption:"流水账"

或者$('#XXX').setCaption("XXX" );

设置caption居中为

 $('#XXX').closest("div.ui-jqgrid-view")

               .children("div.ui-jqgrid-titlebar")

               .css("text-align", "center")

               .children("span.ui-jqgrid-title")

                .css("float", "none");

3.其余小技巧为

1. 初始化的时候不加载数据设置datatype: 'local'

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644829.jpg)

1 $("#grid").jqGrid({2 url:"http://www.8qiu.cn",3 datatype:"local",4 //other options5 });

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644816.jpg)

2. 当要加载数据的时候把datatype改成json或者XML:

1 $("#list").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');

3.如果不要jqgrid表头上的点击排序可以把对应的colModel中加属性sortable : false.

4.如果要给pager上的按钮加id(这样可以动态的hide,show此按钮)具体的方法就是

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644851.jpg)

jQuery("#list").jqGrid('navButtonAdd', '#pager', {caption : "导出",title : "Excel",position : "first",id :'storeExp',buttonicon :'ui-icon-calculator',onClickButton : function (){}});

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644941.jpg)

5.如果要想显示jqgrid中的caption可以在css样式中加入如下(你可以发现.ui-jqgrid .ui-jqgrid-titlebar 的div块display是none,所以没显示)

1 .ui-jqgrid .ui-jqgrid-titlebar2 {3 display:block;4 }

6.如果要使用jqgrid中caption中文字居中显示

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644806.jpg)

1 gridComplete: function(){2              $('#list').closest("div.ui-jqgrid-view")3                 .children("div.ui-jqgrid-titlebar")4                 .css("text-align", "center")5                 .children("span.ui-jqgrid-title")6                 .css("float", "none");7         }

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644795.jpg)

7.当浏览器的大小改变时,jqgrid的宽度作为相应的改变

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644844.jpg)

1 gridComplete: function(){2             $(window).resize(function(){ 3                 var winwidth=$(window).width()*0.5; //这里的0.5可以自己定4             $("#list").setGridWidth(winwidth);5             });6             7         }

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644736.jpg)

8.在jqgrid后给每一行加超链接如下图所示

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644569.jpg)

代码描述如下:

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644632.jpg)

 1 jqGridAdapter("#an"); 2     jQuery("#an").jqGrid({ 3         url : '../ListServlet?className=announcement', 4         datatype : "json", 5         colNames : ['ID', '公告标题', '更新时间', '覆盖区域','操作'], 6         colModel : [{ 7                     name : 'ID', 8                     index : 'ID', 9                     hidden : true,10                     width : '55px'11                 }, {12                     name : 'ANNOUNCEMENT_TITLE',13                     index : 'ANNOUNCEMENT_TITLE',14                     width : '100px',15                     align : 'left',16                     sortable:false17                 }, {18                     name : 'UPDATE_DATE',19                     index : 'UPDATE_DATE',20                     width : '100px',21                     align: 'left',22                     formatter:'date',23                     formatoptions:{srcformat:'Y-m-d H:i',newformat:'Y-m-d'},24                     sortable:false25                 }, {26                     name : 'ANNOUNCEMENT_REGION',27                     index : 'ANNOUNCEMENT_REGION',28                     width : '100px',29                     align: 'center',30                     sortable:false31                 }, {32                     name : 'LOOK',33                     index : 'LOOK',34                     width : '50px',35                     align: 'center',36                     sortable:false37                 }38                 ],39                 rowNum :15,40                 rowList : [15, 25, 30,50,100],41                 pager : '#an_pager',42                 viewrecords : true,43                 sortname : 'update_date',44                 autowidth:true,45                 sortorder : "desc",46                 caption : "ca",47                 multiselect : false,48                 rownumbers : true,49         jsonReader : {50             id : 'ID',51             repeatitems : false52         },53         gridComplete:function()54         {55 　　　　　　　var ids=jQuery("#an").jqGrid('getDataIDs');56            for(var i=0; i
  
  
  
  
  
   
   
   
   
   style='color:#f60' 
   
   
   
   
   alt='点击查看详细' onclick='showannouncement(" + id + ")'>查看";59                 jQuery("#an").jqGrid('setRowData', ids[i], { LOOK: focusNum});61             }62　　　　 },63         height : '
   
   
   
   
   auto'64     });
  
  
  
  
  

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644681.jpg)

9.单元格内的文本自动换行 ：

加入样式：

.ui-jqgrid tr.jqgrow td {

white-space: normal !important;

height:auto;

vertical-align:text-top;

padding-top:2px;

}

|   |
| - |
| colModel : [{ name : 'CommentID',index :'CommentID',sorttype :"int", |


|   |   |
| - | - |
| 2 | formatter: cLink,width : 60}], |


|   |   |
| - | - |
| 3 | //其中的cLink就是自定义函数名 |


|   |   |
| - | - |
| 4 | functioncLink(cellvalue, options, rowObject){ |


|   |   |
| - | - |
| 5 | return'+rowObject.CommentID+'&gt;编辑'; |


|   |   |
| - | - |
| 6 | } |


  10.获取列宽改变后的列宽值，用于保存移动后的列宽var test = $('#list2').jqGrid('getColProp', 'Name');

        alert(test.width); 

9.jqgrid关于日期格式化在colModel中添加

1 formatter:'date',2 formatoptions:{srcformat:'Y-m-d H:i',newformat:'Y-m-d'}





