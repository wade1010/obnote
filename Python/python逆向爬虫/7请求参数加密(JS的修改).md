[https://www.qimai.cn/rank](https://www.qimai.cn/rank)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEa5d76a3d51d5afcbd956ce9fa62dfff3截图.png)

中文未找到，可是试试因为，以为有的接口可能对结果进行了转码。

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE39c37ad303c39ce001a1313634d75960截图.png)

发现参数有加密

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE4ab9297474956ec3b7af611c5267cda7截图.png)

通过关键字查找，结果很多，但是JS文件就一个，进入JS文件源码

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEa7fa1f294f96e1291e42189ff96511c2截图.png)

但是很奇怪的事发生了，里面搜索关键字，却找不到了

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE32575ff03d697cae5ade35cf362d5970截图.png)

不区分大小写，结果是11个

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE5b2debc18e4230c56d1a14632766ae35截图.png)

然后尝试区分大小写

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEbb225bf09cd80272443126a993a94676截图.png)

结果是10个

像遇到这种情况，可能是网站加密比较多。可以尝试xhr路径断点

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEb6367cc212293cc73e27ea0c34fa6b0e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE736549600d599f0138023922f70f719d截图.png)

发现这里已经是发送请求到服务器且返回了。

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE306d1a4dfc900a1c1a13173e3fc038cf截图.png)

然后慢慢断点看看，哪里生成参数的。

首先尝试 "跳出当前函数"  一直点，开始几个都是analysis生成过的

继续点

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE2a1e6b52e22bcc574d3ac48d1f654837截图.png)

发现可能是这里生成。

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEa44a4884a5550e56dd08fa11353344b4截图.png)

```
function i(e) {
    var t, a = (t = "",
        ["66", "72", "6f", "6d", "43", "68", "61", "72", "43", "6f", "64", "65"].forEach((function (e) {
                t += unescape("%u00" + e)
            }
        )),
        t);
    return String[a](e)
}

function h(e) {
    return function (e) {
        try {
            return btoa(e)
        } catch (t) {
            return Buffer.from(e).toString("base64")
        }
    }(encodeURIComponent(e).replace(/%([0-9A-F]{2})/g, (function (e, t) {
            return i("0x" + t)
        }
    )))
}

function g(e, t) {
    t || (t = s());
    for (var a = (e = e.split("")).length, n = t.length, o = "charCodeAt", r = 0; r < a; r++)
        e[r] = i(e[r][o](0) ^ t[(r + 10) % n][o](0));
    return e.join("")
}

function url(e) {
    // 自己补全开始
    var f = 396
    var d = "@#"
    var l = "0000000c735d856"
    var argName = "analysis"
    // 自己补全结束

    var a, o = +new Date - (f || 0) - 1515125653845, r = [];
    r = r.sort().join(""),
        r = (0,
            h)(r),
        r += d + e,
        r += d + o,
        r += d + 1,
        a = (0,
            h)((0,
            g)(r, l)),
    // -1 == e.indexOf(h) && (e += (-1 != e.indexOf("?") ? "&" : "?") + h + "=" + encodeURIComponent(a)), e
        //这里由于变量h已经有个h方法了，所以把h改为  argName
    -1 == e.indexOf(argName) && (e += (-1 != e.indexOf("?") ? "&" : "?") + argName + "=" + encodeURIComponent(a)), e
    return e
}


console.log(url("/rank/indexPlus/brand_id/1"))

```

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE8d90258554564871e200fd6cf7c3558d截图.png)

然后测试python调用

```
import requests
import execjs

with open('test.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

ctx = execjs.compile(jscode).call('url', '/rank/indexPlus/brand_id/1')
print(ctx)

headers = {
    'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36'
}

resp = requests.get('https://api.qimai.cn' + ctx, headers=headers)
print(resp.json())

```

大功告成

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEd020aa0b5a271a878fb63ed4c16a3f7f截图.png)

[test.js](attachments/WEBRESOURCEcedf6b8a134fee3ab93514b79b183f4dtest.js)

[test.py](attachments/WEBRESOURCEfeb186705c7a7d70f693ed887a960a33test.py)