今天发现了一个新的标记ufeff，说实话这个标签，我也是第一次见，网上的介绍比较少，大概就是Unicode空白分隔符，至于怎么产生的我还真不知道，本来是把一个ASP的程序重新开发成PHP的，因为之前使用的是GB2312编码，新的项目我使用了UTF-8，也不知道是不是在这个过程中产生的，我本来页面有贴齐顶部的，但不管怎么调都与顶部有一段距离，在CSS和HTML源代码中均没有发现任何问题。

下决心要解决这个问题，下载了火狐、谷歌浏览器，最后在谷歌浏览器的错误里看到了一个红色实心圆点，检查源代码就是找不到这个东西，无意将鼠标指到这个点，鼠标边上显示了ufeff提示，在编辑器里是什么也不显示的，和正常的地方是一样的，你完全看不出有这么一个标记，自然也是无法删除，弄了一个小时还是没有解决，用批量替换工具查找ufeff也是一无所获。冷静下来想了下，既然ufeff是Unicode空白符，那我就重新存成别的编码，再存回UTF-8，果然，这样一试，问题解决了。





![](https://gitee.com/hxc8/images5/raw/master/img/202407172331899.jpg)





说明：

在做项目时出现了一个诡异的问题，游戏客户端发送数据请求到服务端，php获取到客户端发送的请求后返回json格式的数据给客户端。但客户端一直显示获取不到数据。

1.查看php日志，发现php有接收客户端生成json数据，php正常

2.通过charles抓取客户端通信，可以看到客户端有正常发送请求、但php返回给客户端的数据（response）却是空的。

3.有怀疑是不是php文件保存的编码方式错误，导致生成的json有错误，但发现json数据都是英文、数字，并没有汉字

4.后来通过php模拟客户端请求，手动post数据到服务端，在浏览器开发工具下看到原来php返回给客户端的json数据前面出现了几个莫名其妙的小红点。因为json数据开头该红点的存在，客户端以为php返回的json数据是错误的。。。其实正确的json就在后面

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331208.jpg)





很明显就是这几个小红点惹的祸，剩下的就是排查为什么会有小红点了，开始以为这几个小红点应该是空格之类的字符，后来在调试中无意间把鼠标放到小红点上面发现居然有提示，提示内容为：\UFEFF。原来是这个字符惹的祸。后来发现这个原来是window编辑器为保存为utf8的文件自动加上BOM头，这样其它编辑器就会知道用utf8来显示字符。



附录：

附录一、bom头说明

类似WINDOWS自带的记事本等软件，在保存一个以UTF-8编码的文件时，会在文件开始的地方插入三个不可见的字符（0xEF 0xBB 0xBF，即BOM）。它是一串隐藏的字符，用于让记事本等编辑器识别这个文件是否以UTF-8编码。对于一般的文件，这样并不会产生什么麻烦。

但对于PHP来说，BOM是个大麻烦。PHP并不会忽略BOM，所以在读取、包含或者引用这些文件时，会把BOM作为该文件开头正文的一部分。根据嵌入式语言的特点，这串字符将被直接执行（显示）出来。由此造成即使页面的 top padding 设置为0，也无法让整个网页紧贴浏览器顶部，因为在html一开头有这3个字符呢！

在网页上并不需要添加BOM头识别，因为网页上可以使用 head头 指定charset=utf8告诉浏览器用utf8来解释.但是你用window自动的编辑器，编辑,然后有显示在网页上这样就会显示出0xEF 0xBB 0xBF这3个字符。

这样网页上就需要去除0xEF 0xBB 0xBF，可以使用editplus 选择不带BOM的编码，这样就可以去除了

附录二、Linux下查找包含BOM头的文件和清除BOM头命令

查看包含bom头的文件

# grep -r -I -l $’^\xEF\xBB\xBF’ ./

清除包含bom头的文件

# find . -type f -exec sed -i ‘s/\xEF\xBB\xBF//’ {} \;

