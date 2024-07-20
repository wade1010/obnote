2016-1-8 13:46:12 今天发现，输出文件可能编码是正常的，但是该文件中有引入的文件的编码可能有问题，只要将引入的文件也改成无BOM的文件就行了。



前一篇文章中我们已经了解了：PHP将数组转换为json格式

json_decode函数能够接收utf8编码的参数，但是当参数中包含BOM时，json_decode就会失效。

这个函数能将给定的字符串转换成UTF-8编码，移除其中的BOM。

下面是PHP代码：

function prepareJSON($input) {    //This will convert ASCII/ISO-8859-1 to UTF-8.    //Be careful with the third parameter (encoding detect list), because    //if set wrong, some input encodings will get garbled (including UTF-8!)    $imput = mb_convert_encoding($input, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');    //Remove UTF-8 BOM if present, json_decode() does not like it.    if(substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $input = substr($input, 3);    return $input;}//Usage:$myFile = file_get_contents('somefile.json');$myDataArr = json_decode(prepareJSON($myFile), true);

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331687.jpg)