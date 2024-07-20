为了统一平台服务的配额管理，JavaScript API在新版本引入ak机制。JavaScript API v1.4及以前版本无须申请密钥（ak），自v1.5版本开始需要先申请密钥（ak），才可使用，超出ak配额部分，可以发送邮件进行申请。

地址：

http://api.map.baidu.com/api?v=1.4 //参数v表示您加载API的版本，使用JavaScript APIv1.4及以前版本可使用此方式引用。
http://api.map.baidu.com/api?v=2.0&ak=您的密钥  //使用JavaScript APIv2.0请先申请密钥ak，按此方式引用。


公司ak:8fe1e1e8dddd512775f74d3b8ee5726b

当权限验证(ak)失败时，会报如下错误：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191643566.jpg)



<!DOCTYPE html>  

<html>  

<head>  

<meta charset="utf-8"/> 

<!-- 这样做是为了让页面以正常比例进行显示并且禁止用户缩放页面的操作。 -->

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 

<title>异步加载</title>  

<script type="text/javascript">  

function initialize() {  

  var mp = new BMap.Map('map');  

  mp.centerAndZoom(new BMap.Point(121.491, 31.233), 11);  

}  

function loadScript() {  

 var script = document.createElement("script");  

  script.src = "http://api.map.baidu.com/api?v=2.0&ak=您的密钥&callback=initialize";//此为v2.0版本的引用方式  

  // http://api.map.baidu.com/api?v=1.4&ak=您的密钥&callback=initialize"; //此为v1.4版本及以前版本的引用方式  

  document.body.appendChild(script);  

}  

   

window.onload = loadScript;  

</script>  

</head>  

<body>  

  <div id="map" style="width:500px;height:320px"></div>  

</body>  

</html>





