![](https://gitee.com/hxc8/images9/raw/master/img/202407191643707.jpg)

$("#search_btn").click(function(){      //此处可以添加对查询数据的合法验证  var orderId = $("#orderId").val();      $("#list4").jqGrid('setGridParam',{          datatype:'json',          postData:{'orderId':orderId}, //发送数据          page:1      }).trigger("reloadGrid"); //重新载入  }); 

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643585.jpg)

① setGridParam用于设置jqGrid的options选项。返回jqGrid对象

② datatype为指定发送数据的格式；

③ postData为发送请求的数据，以key:value的形式发送，多个参数可以以逗号”,”间隔；

④ page为指定查询结果跳转到第一页；

⑤ trigger(“reloadGrid”);为重新载入jqGrid表格。

2 无数据的提示信息。

当后台返回数据为空时，jqGrid本身的提示信息在右下角，不是很显眼

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643894.jpg)

，下面方法将实现在无数据显示的情况下，在jqGrid表格中间位置提示“无数据显示”。如下图：当然，你的div样式可以设置的更好看些。

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643760.jpg)

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643934.jpg)

loadComplete: function() {//如果数据不存在，提示信息var rowNum = $("#list4").jqGrid('getGridParam','records');    if (rowNum      if($("#norecords").html() == null){            $("#list4").parent().append("

"norecords" >没有查询记录！

");        }        $("#norecords").show();    }else{//如果存在记录，则隐藏提示信息。        $("#norecords").hide();    }}

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643013.jpg)

① loadComplete 为jqGrid加载完成，执行的方法；

② getGridParam这个方法用来获得jqGrid的选项值。它具有一个可选参数name，name即代表着jqGrid的选项名，如果不传入name参数，则会返回jqGrid整个选项options。例：

$("#list4").jqGrid('getGridParam','records');//获取当前jqGrid的总记录数；

注意：这段代码要加在jqGrid的选项设置Option之间，即：$(“#list4″).jqGrid({});代码之间。且各个option之间加逗号间隔。

3 显示jqGrid统计信息。

通常统计信息都显示在jqGrid表格最后一行，分页控件之上，如下图：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643081.jpg)

代码片段：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643003.jpg)

$("#list4").jqGrid({    ......    colModel:[        {name:'productName',index:'productName',align:'center',sortable:false},        {name:'productAmt',index:'productAmt', align:'center'}    ],    footerrow: true,//分页上添加一行，用于显示统计信息    ......    pager:$('#gridPager'),    gridComplete: function() {//当表格所有数据都加载完成，处理统计行数据var rowNum = $(this).jqGrid('getGridParam','records');        if(rowNum > 0){            var options = {                url: "test.action",// 默认是form的action，如果写的话，会覆盖from的action.                dataType: "json",// 'xml', 'script', or 'json' (接受服务端返回的类型.)                type: "POST",                success: function(data){//成功后调用方法                    $("#list4").footerData("set",{productName:"合计",productAmt:data.productAmtSum});                }            };            $("#searchForm").ajaxSubmit(options);        }    }});

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643068.jpg)

详细介绍：

3.1jqGrid的options配置； 需要在jqGrid的options中添加如下属性：

footerrow: true,//分页上添加一行，用于显示统计信息

3.2 调用gridComplete方法，当数据加载完成后，处理统计行数据； 3.3调用jqGrid的footerData方法，为统计行赋值：

$("#list4").footerData("set",{productName:"合计",productAmt:data.productAmtSum});

4 jqGrid的数据格式化。

jqGrid中对列表cell属性格式化设置主要通过colModel中formatter、formatoptions来设置

基本用法：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643083.jpg)

jQuery("#jqGrid_id").jqGrid({...   colModel: [      ...      {name:'price', index:'price',  formatter:'integer', formatoptions:{thousandsSeparator: ','}},      ...   ]...});

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643368.jpg)

formatter主要是设置格式化类型(integer、email等以及函数来支持自定义类型),formatoptions用来设置对应formatter的参数，jqGrid中预定义了常见的格式及其options：

integer

thousandsSeparator： //千分位分隔符,

defaulValue

number

decimalSeparator, //小数分隔符，如”.”

thousandsSeparator, //千分位分隔符，如”,”

decimalPlaces, //小数保留位数

defaulValue

currency

decimalSeparator, //小数分隔符，如”.”

thousandsSeparator, //千分位分隔符，如”,”

decimalPlaces, //小数保留位数

defaulValue,

prefix //前缀，如加上”$”

suffix//后缀

date

srcformat, //source的本来格式

newformat //新格式

email

没有参数，会在该cell是email加上： mailto:name@domain.com

showlink

baseLinkUrl, //在当前cell中加入link的url，如”jq/query.action”

showAction, //在baseLinkUrl后加入&action=actionName

addParam, //在baseLinkUrl后加入额外的参数，如”&name=aaaa”

target,

idName //默认会在baseLinkUrl后加入,如”.action?id=1″。改如果设置idName=”name”,那么”.action?name=1″。其中取值为当前rowid

checkbox

disabled //true/false 默认为true此时的checkbox不能编辑，如当前cell的值是1、0会将1选中

select

设置下拉框，没有参数，需要和colModel里的editoptions配合使用

下面是一个使用的例子:

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643252.jpg)

colModel:[    {name:'id',     index:'id',     formatter:  customFmatter},    {name:'name',   index:'name',   formatter: "showlink", formatoptions:{baseLinkUrl:"save.action",idName: "id", addParam:"&name=123"}},    {name:'price',  index:'price',  formatter: "currency", formatoptions: {thousandsSeparator:",",decimalSeparator:".", prefix:"$"}},    {name:'email',  index:'email',  formatter: "email"},    {name:'amount', index:'amount', formatter: "number", formatoptions: {thousandsSeparator:",", defaulValue:"",decimalPlaces:3}},    {name:'gender', index:'gender', formatter: "checkbox",formatoptions:{disabled:false}},    {name:'type',   index:'type',   formatter: "select", editoptions:{value:"0:无效;1:正常;2:未知"}}]

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644359.jpg)

其中customFmatter声明如下：

function customFmatter(cellvalue, options, rowObject){    console.log(cellvalue);    console.log(options);    console.log(rowObject);    return "["+cellvalue+"]";};

在页面显示的效果如下： 

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644365.jpg)

当然还得支持自定义formatter函数，只需要在formatter:customFmatter设置formatter函数，该函数有三个签名：

function customFmatter(cellvalue, options, rowObject){   }//cellvalue - 当前cell的值//options - 该cell的options设置，包括{rowId, colModel,pos,gid}//rowObject - 当前cell所在row的值，如{ id=1, name="name1", price=123.1, ...}

当然对于自定义formatter，在修改时需要获取原来的值，这里就提供了unformat函数，这里见官网的例子：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644483.jpg)

jQuery("#grid_id").jqGrid({...   colModel: [      ...      {name:'price', index:'price', width:60, align:"center", editable: true, formatter:imageFormat, unformat:imageUnFormat},      ...   ]...});   function imageFormat( cellvalue, options, rowObject ){    return '

';}function imageUnFormat( cellvalue, options, cell){    return $('img', cell).attr('src');}

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644596.jpg)

5 常见错误问题：

chrome报错：

Uncaught TypeError: Cannot read property ‘integer’ of undefined

IE报错：

SCRIPT5007: 无法获取属性“integer”的值: 对象为 null 或未定义

出现这样的问题，是由于页面没有添加语言文件的引用导致的

解决办法为：添加语言文件js