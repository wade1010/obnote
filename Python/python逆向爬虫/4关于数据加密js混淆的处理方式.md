[https://www.endata.com.cn/BoxOffice/BO/Year/index.html](https://www.endata.com.cn/BoxOffice/BO/Year/index.html)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEed7b908c2ff492685ee43fa860500526截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEd36532db0f4bd422356396f607e7cb0f截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE478a54d10dcd58d4d0741b146f9da0f2截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEdbe8b926f1a1257b1a6febfa4a6aa7c5截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEfded0963ff7cce22993320bacf83a673截图.png)

进入后发现都是混淆的代码。这里行数比较少，整个的都拷贝出来。

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE8faf09bfbec321c1e939930f83190fe3截图.png)

执行JS代码

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE57b823b00e0c5124d850fab5886fb15a截图.png)

发现报错

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEcdf7d7193832567937d4fb90674429ce截图.png)

整个是浏览器环境变量，我们也可以声明

```
global.navigator={'userAgent':'node.js'}
```

再次执行就Ok

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE953e725b704f33615e1fa73be3ef258e截图.png)

源码

[艺恩JS混淆.js](attachments/WEBRESOURCE16eccdb49bdb0d118f4f3347923198d1艺恩JS混淆.js)

python调用JS

```
import execjs
import requests

url = '
headers = {
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36'
}
req_data = {
    'year': '2022',
    'MethodName': 'BoxOffice_GetYearInfoData'
}

resp = requests.post(url, headers=headers, data=req_data)

with open('艺恩JS混淆.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

ctx = execjs.compile(jscode).call('webInstace.shell', resp.text)

print(ctx)

```

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE40fe73e09d15d1cac7f1eb171e5761c4截图.png)