[https://webapi.cninfo.com.cn/#/marketDataDate](https://webapi.cninfo.com.cn/#/marketDataDate)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE2aeb23e9fd8364a3cbc575091636e1f5截图.png)

应该就是这个mcode作为校验

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEf2b6659c10c1f3797ece72c6a6ee191b截图.png)

对比两次，确实变化

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE3c78e5c6143faf42d55f1e22e0a97503截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE7949ef1facbf4569d160a145003df7c1截图.png)

这里通过接口关键字查找到源码

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE499826301e037663309f31c35ddd7f79截图.png)

断点下一步 

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE6290734cfa3f5405a34351f0e5139b1e截图.png)

通过观察

直接复制getResCode

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEce3f5d6153de01b975fe09d90814803c截图.png)

查看time格式和位数

"1661221069"

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEf5edfe0c031206a40fb7911df8a491f5截图.png)

上方的time对应的mcode为 "MTY2MTIyMTA2OQ=="

本地可以验证下

```
function getResCode() {
    // var time = Math.floor(new Date().getTime() / 1000);
    var time = 1661221069;
    return missjson("" + time);
}

function missjson(input) {
    var keyStr = "ABCDEFGHIJKLMNOP" + "QRSTUVWXYZabcdef" + "ghijklmnopqrstuv"   + "wxyz0123456789+/" + "=";
    var output = "";
    var chr1, chr2, chr3 = "";
    var enc1, enc2, enc3, enc4 = "";
    var i = 0;
    do {
        chr1 = input.charCodeAt(i++);
        chr2 = input.charCodeAt(i++);
        chr3 = input.charCodeAt(i++);
        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        enc4 = chr3 & 63;
        if (isNaN(chr2)) {
            enc3 = enc4 = 64;
        } else if (isNaN(chr3)) {
            enc4 = 64;
        }
        output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2)
                + keyStr.charAt(enc3) + keyStr.charAt(enc4);
        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    } while (i < input.length);

    return output;
}

console.log(getResCode())
```

输出结果也是"MTY2MTIyMTA2OQ=="

一致