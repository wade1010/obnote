知识点

IIFE（立即调用函数表达式）是一个在定义时就会立即执行的  JavaScript 函数。



```javascript
(function () {
    statements
})();
```



这是一个被称为 自执行匿名函数 的设计模式，主要包含两部分。第一部分是包围在 圆括号运算符 () 里的一个匿名函数，这个匿名函数拥有独立的词法作用域。这不仅避免了外界访问此 IIFE 中的变量，而且又不会污染全局作用域。



第二部分再一次使用 () 创建了一个立即执行函数表达式，JavaScript 引擎到此将直接执行函数。



示例

当函数变成立即执行的函数表达式时，表达式中的变量不能从外部访问。

```javascript
(function () { 
    var name = "Barry";
})();
// 无法从外部访问变量 name
name // 抛出错误："Uncaught ReferenceError: name is not defined"
```



将 IIFE 分配给一个变量，不是存储 IIFE 本身，而是存储 IIFE 执行后返回的结果。

```javascript
var result = (function () { 
    var name = "Barry"; 
    return name; 
})(); 
// IIFE 执行后返回的结果：
result; // "Barry"
```







![](https://gitee.com/hxc8/images7/raw/master/img/202407190744976.jpg)



---



分段上传



![](https://gitee.com/hxc8/images7/raw/master/img/202407190744700.jpg)





闭包计数器

立即执行表达式



```javascript
var xhr =  new XMLHttpRequest();
var clock = null;

function fire(){
    clock = window.setInterval(sendfile,10000));
}
//闭包计数器
var sendfile = (function(){
    const LENGTH = 10*1024*1024;//每次切10M
    var sta = 0;
    var end = sta + LENGTH;
    var sending =true;//标志正在上传中
    var blob = null;
    var fd = null;
    //百分比
    var percent = 0;    
    return (function(){
        if(sending == true) {
            return;
        }
        var mov = document.getElementByName('mov')[0].files[0];
        //如果sta>mov.size就结束
        if(sta > mov.size){
            clearInterval(clock);
            return;
        }
        blob = mov.slice(sta,end);
        fd = new FormData();
        fd.append('part',blob);
        
        up(fd);
        
        sta = end;
        end = sta +LENGTH;
        sending = false;
        
        percent = 100 * end /mov.size;
        if(percent>100){
            percent = 100;
        }
        document.getElementById('bar').style.width = percent + '%';
    });
}}))() //立即执行表达式


function up(){
    xhr.open('POST','sliceup.php');
    xhr.send(fd);
}

```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190744279.jpg)

  