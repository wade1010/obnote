[https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd](https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE2e0bd4a7fa6930fe16e8369bd36824e4截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE92708bf35b638a2faa016b24721f6266截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE102ecfa3ad0a638c4c067b9e41df8d26截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE4cb9bd4df0d07d1d997c576eee0b9c5e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEb7052d158f11d71e08201ff79f4d457a截图.png)

进入h方法的JS页面，可以复制出h方法体

```
function h(a) {
    function b(a, b) {
        return a << b | a >>> 32 - b
    }
    function c(a, b) {
        var c, d, e, f, g;
        return e = 2147483648 & a,
        f = 2147483648 & b,
        c = 1073741824 & a,
        d = 1073741824 & b,
        g = (1073741823 & a) + (1073741823 & b),
        c & d ? 2147483648 ^ g ^ e ^ f : c | d ? 1073741824 & g ? 3221225472 ^ g ^ e ^ f : 1073741824 ^ g ^ e ^ f : g ^ e ^ f
    }
    function d(a, b, c) {
        return a & b | ~a & c
    }
    function e(a, b, c) {
        return a & c | b & ~c
    }
    function f(a, b, c) {
        return a ^ b ^ c
    }
    function g(a, b, c) {
        return b ^ (a | ~c)
    }
    function h(a, e, f, g, h, i, j) {
        return a = c(a, c(c(d(e, f, g), h), j)),
        c(b(a, i), e)
    }
    function i(a, d, f, g, h, i, j) {
        return a = c(a, c(c(e(d, f, g), h), j)),
        c(b(a, i), d)
    }
    function j(a, d, e, g, h, i, j) {
        return a = c(a, c(c(f(d, e, g), h), j)),
        c(b(a, i), d)
    }
    function k(a, d, e, f, h, i, j) {
        return a = c(a, c(c(g(d, e, f), h), j)),
        c(b(a, i), d)
    }
    function l(a) {
        for (var b, c = a.length, d = c + 8, e = (d - d % 64) / 64, f = 16 * (e + 1), g = new Array(f - 1), h = 0, i = 0; c > i; )
            b = (i - i % 4) / 4,
            h = i % 4 * 8,
            g[b] = g[b] | a.charCodeAt(i) << h,
            i++;
        return b = (i - i % 4) / 4,
        h = i % 4 * 8,
        g[b] = g[b] | 128 << h,
        g[f - 2] = c << 3,
        g[f - 1] = c >>> 29,
        g
    }
    function m(a) {
        var b, c, d = "", e = "";
        for (c = 0; 3 >= c; c++)
            b = a >>> 8 * c & 255,
            e = "0" + b.toString(16),
            d += e.substr(e.length - 2, 2);
        return d
    }
    function n(a) {
        a = a.replace(/\r\n/g, "\n");
        for (var b = "", c = 0; c < a.length; c++) {
            var d = a.charCodeAt(c);
            128 > d ? b += String.fromCharCode(d) : d > 127 && 2048 > d ? (b += String.fromCharCode(d >> 6 | 192),
            b += String.fromCharCode(63 & d | 128)) : (b += String.fromCharCode(d >> 12 | 224),
            b += String.fromCharCode(d >> 6 & 63 | 128),
            b += String.fromCharCode(63 & d | 128))
        }
        return b
    }
    var o, p, q, r, s, t, u, v, w, x = [], y = 7, z = 12, A = 17, B = 22, C = 5, D = 9, E = 14, F = 20, G = 4, H = 11, I = 16, J = 23, K = 6, L = 10, M = 15, N = 21;
    for (a = n(a),
    x = l(a),
    t = 1732584193,
    u = 4023233417,
    v = 2562383102,
    w = 271733878,
    o = 0; o < x.length; o += 16)
        p = t,
        q = u,
        r = v,
        s = w,
        t = h(t, u, v, w, x[o + 0], y, 3614090360),
        w = h(w, t, u, v, x[o + 1], z, 3905402710),
        v = h(v, w, t, u, x[o + 2], A, 606105819),
        u = h(u, v, w, t, x[o + 3], B, 3250441966),
        t = h(t, u, v, w, x[o + 4], y, 4118548399),
        w = h(w, t, u, v, x[o + 5], z, 1200080426),
        v = h(v, w, t, u, x[o + 6], A, 2821735955),
        u = h(u, v, w, t, x[o + 7], B, 4249261313),
        t = h(t, u, v, w, x[o + 8], y, 1770035416),
        w = h(w, t, u, v, x[o + 9], z, 2336552879),
        v = h(v, w, t, u, x[o + 10], A, 4294925233),
        u = h(u, v, w, t, x[o + 11], B, 2304563134),
        t = h(t, u, v, w, x[o + 12], y, 1804603682),
        w = h(w, t, u, v, x[o + 13], z, 4254626195),
        v = h(v, w, t, u, x[o + 14], A, 2792965006),
        u = h(u, v, w, t, x[o + 15], B, 1236535329),
        t = i(t, u, v, w, x[o + 1], C, 4129170786),
        w = i(w, t, u, v, x[o + 6], D, 3225465664),
        v = i(v, w, t, u, x[o + 11], E, 643717713),
        u = i(u, v, w, t, x[o + 0], F, 3921069994),
        t = i(t, u, v, w, x[o + 5], C, 3593408605),
        w = i(w, t, u, v, x[o + 10], D, 38016083),
        v = i(v, w, t, u, x[o + 15], E, 3634488961),
        u = i(u, v, w, t, x[o + 4], F, 3889429448),
        t = i(t, u, v, w, x[o + 9], C, 568446438),
        w = i(w, t, u, v, x[o + 14], D, 3275163606),
        v = i(v, w, t, u, x[o + 3], E, 4107603335),
        u = i(u, v, w, t, x[o + 8], F, 1163531501),
        t = i(t, u, v, w, x[o + 13], C, 2850285829),
        w = i(w, t, u, v, x[o + 2], D, 4243563512),
        v = i(v, w, t, u, x[o + 7], E, 1735328473),
        u = i(u, v, w, t, x[o + 12], F, 2368359562),
        t = j(t, u, v, w, x[o + 5], G, 4294588738),
        w = j(w, t, u, v, x[o + 8], H, 2272392833),
        v = j(v, w, t, u, x[o + 11], I, 1839030562),
        u = j(u, v, w, t, x[o + 14], J, 4259657740),
        t = j(t, u, v, w, x[o + 1], G, 2763975236),
        w = j(w, t, u, v, x[o + 4], H, 1272893353),
        v = j(v, w, t, u, x[o + 7], I, 4139469664),
        u = j(u, v, w, t, x[o + 10], J, 3200236656),
        t = j(t, u, v, w, x[o + 13], G, 681279174),
        w = j(w, t, u, v, x[o + 0], H, 3936430074),
        v = j(v, w, t, u, x[o + 3], I, 3572445317),
        u = j(u, v, w, t, x[o + 6], J, 76029189),
        t = j(t, u, v, w, x[o + 9], G, 3654602809),
        w = j(w, t, u, v, x[o + 12], H, 3873151461),
        v = j(v, w, t, u, x[o + 15], I, 530742520),
        u = j(u, v, w, t, x[o + 2], J, 3299628645),
        t = k(t, u, v, w, x[o + 0], K, 4096336452),
        w = k(w, t, u, v, x[o + 7], L, 1126891415),
        v = k(v, w, t, u, x[o + 14], M, 2878612391),
        u = k(u, v, w, t, x[o + 5], N, 4237533241),
        t = k(t, u, v, w, x[o + 12], K, 1700485571),
        w = k(w, t, u, v, x[o + 3], L, 2399980690),
        v = k(v, w, t, u, x[o + 10], M, 4293915773),
        u = k(u, v, w, t, x[o + 1], N, 2240044497),
        t = k(t, u, v, w, x[o + 8], K, 1873313359),
        w = k(w, t, u, v, x[o + 15], L, 4264355552),
        v = k(v, w, t, u, x[o + 6], M, 2734768916),
        u = k(u, v, w, t, x[o + 13], N, 1309151649),
        t = k(t, u, v, w, x[o + 4], K, 4149444226),
        w = k(w, t, u, v, x[o + 11], L, 3174756917),
        v = k(v, w, t, u, x[o + 2], M, 718787259),
        u = k(u, v, w, t, x[o + 9], N, 3951481745),
        t = c(t, p),
        u = c(u, q),
        v = c(v, r),
        w = c(w, s);
    var O = m(t) + m(u) + m(v) + m(w);
    return O.toLowerCase()
}
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE36d1dd7a182fd6218ef226a023dd87a0截图.png)

h方法放到1688.js

1688.py代码如下

```
# d.token + "&" + i + "&" + g + "&" + c.data

import time
import execjs

token = "23a16aa2d6369ecea6c352394a3ae84e"

i = round(time.time() * 1000)
g = "12574478"
data = '{"cid":"TpFacRecommendService:TpFacRecommendService","methodName":"execute","params":"{\"pageNo\":\"1\",\"cna\":\"ZawAG3qKEDsCAd9IV6slae8q\",\"pageSize\":\"20\",\"from\":\"PC\",\"sort\":\"mix\",\"trafficSource\":\"pc_index_recommend\",\"url\":\"

sign_key = token + '&' + str(i) + '&' + g + '&' + data

with open('1688.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

ctx = execjs.compile(jscode).call('h', sign_key)
print(ctx)

```

执行py后

```
/Users/bob/workspace/pythonworkspace/python逆向/venv/bin/python /Users/bob/workspace/pythonworkspace/python逆向/1688你想/1688.py
a16991b404f28846478a1709483896c8
```

观察后得出，只有t,也就是时间戳是变化的。可以把断点里面的时间戳拷贝到py里面，看看得到的sign是否一致

```
# d.token + "&" + i + "&" + g + "&" + c.data

import time
import execjs
import requests

i = round(time.time() * 1000)
g = "12574478"
data = '{"cid":"FactorySearchPCConditionService:FactorySearchPCConditionService","methodName":"execute","params":"{\"lv1RecCateSize\":\"50\",\"classifyByCategory\":\"true\",\"classifyByGeo\":\"true\",\"from\":\"pc_index_recommend\",\"trafficSource\":\"pc_index_recommend\",\"url\":\"https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd\"}"}'
token = "23a16aa2d6369ecea6c352394a3ae84e"

sign_key = token + "&" + str(i) + "&" + g + "&" + data
print(sign_key)
with open('1688.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

ctx = execjs.compile(jscode).call('h', sign_key)
print("")
print(ctx)

params = {'jsv': '2.6.1', 'appKey': g, 't': i, 'sign': ctx, 'v': '1.0', 'type': 'jsonp', 'isSec': 0, 'timeout': '20000',
          'api': 'mtop.taobao.widgetService.getJsonComponent', 'dataType': 'jsonp', 'jsonpIncPrefix': 'mboxfc',
          'callback': 'mtopjsonpmboxfc7', 'data': data}

url = 'https://h5api.m.1688.com/h5/mtop.taobao.widgetservice.getjsoncomponent/1.0/'

headers = {
    'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
    'referer': 'https://sale.1688.com/',
    'cookie': 'lid=huiaina1010; _m_h5_tk=23a16aa2d6369ecea6c352394a3ae84e_1661186288338; _m_h5_tk_enc=0b6be75128572d632ba3e44e1010fbe1; cookie2=1050aec592e1c8e9dfad0f8e4ab44ec5; sgcookie=E100i4IlUgisdJDK68DXs%2B3MBlgmuBvkPGMCzVpVrdtzA34Zwea1s0x%2FcjaGHZhTuLNMOhGRfmKWu41TMwPU0HqmBoulfXM4TuswJIk%2BwPRSMLs%3D; t=dfbccdf543461d01c3987c0e03c2d260; _tb_token_=756e8eab5e7e3; __cn_logon__=false; cna=ZawAG3qKEDsCAd9IV6slae8q; xlly_s=1; alicnweb=touch_tb_at%3D1661176216453; tfstk=cQFPBQ092XqjZXya3jGFR-UburKRZQe7dKuiEG_2tSl234MliLxKokhm0DY2xYf..; l=eBjlvFcrTOCSex5XpOfanurza77OSIRYYuPzaNbMiOCPOq5H53Z5W6lcn7TMC3GVh6reR3uO6DEMBeYBcIjcdlWlc7DZWVHmn; isg=BDEx6-Ao9sMvilrXCmeXI0RqQLvLHqWQCiJC7BNGLfgXOlGMW261YN9cXM5c6T3I'
}

resp = requests.get(url, headers=headers, params=params).text
print(resp)

```

没成功

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE871d3bc8596b6bd766143905b753e003截图.png)

第二天发现了问题

```
# d.token + "&" + i + "&" + g + "&" + c.data

import time
import execjs
import requests

# i = round(time.time() * 1000)
i = 1661214983167
g = "12574478"
data = '{"cid":"TpFacRecommendService:TpFacRecommendService","methodName":"execute","params":"{\"pageNo\":\"1\",\"query\":\"facService=厂长在线\",\"pageSize\":\"2\",\"showType\":\"transverse\",\"sort\":\"mix\",\"trafficSource\":\"wireless_boss_call_recommend\",\"url\":\"https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd\"}"}'
token = "69bff1f9ec3c4e61e24aae5056a15946"

sign_key = token + "&" + str(i) + "&" + g + "&" + data
print()
print()
print()
print(sign_key)
with open('1688.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

sign_key='69bff1f9ec3c4e61e24aae5056a15946&1661214983167&12574478&{"cid":"TpFacRecommendService:TpFacRecommendService","methodName":"execute","params":"{\\"pageNo\\":\\"1\\",\\"query\\":\\"facService=厂长在线\\",\\"pageSize\\":\\"2\\",\\"showType\\":\\"transverse\\",\\"sort\\":\\"mix\\",\\"trafficSource\\":\\"wireless_boss_call_recommend\\",\\"url\\":\\"https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd\\"}"}'
print(sign_key)

```

我把console里面传给h的参数打印出来 跟 我程序拼接的作对比  程序如上

发现没有\

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE0c668f5f197bf00f8bb0bffba7e05bff截图.png)

有\的 是console里显示的 。

所以这次我改成  断点后从console里面打印后再复制

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEe7cd0fe222ecc51c16c6b98c7c026f5c截图.png)

这样sign的值就跟断点显示的一致，如下面2个图

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE4197dcfc4064aa4b27e483eebe7e84da截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCE14125e8ab3375f2ba9ee38d6c459508d截图.png)

那再来试试自动 生成的时间戳

终于大功告成

![](D:/download/youdaonote-pull-master/data/Technology/Python/python逆向爬虫/images/WEBRESOURCEae6456e6850cda1c68176ee95e153eb4截图.png)

1688.py代码如下

```
# d.token + "&" + i + "&" + g + "&" + c.data

import time
import execjs
import requests

i = round(time.time() * 1000)
g = "12574478"
data = '{"cid":"TpFacRecommendService:TpFacRecommendService","methodName":"execute","params":"{\\"pageNo\\":\\"1\\",\\"query\\":\\"facService=厂长在线\\",\\"pageSize\\":\\"2\\",\\"showType\\":\\"transverse\\",\\"sort\\":\\"mix\\",\\"trafficSource\\":\\"wireless_boss_call_recommend\\",\\"url\\":\\"https://sale.1688.com/factory/home.html?spm=a260k.dacugeneral.searchbox.2.7f9235e4GESsmd\\"}"}'

token = "69bff1f9ec3c4e61e24aae5056a15946"

sign_key = token + "&" + str(i) + "&" + g + "&" + data
with open('1688.js', 'r', encoding='utf-8') as f:
    jscode = f.read()

ctx = execjs.compile(jscode).call('h', sign_key)

params = {'jsv': '2.6.1', 'appKey': g, 't': i, 'sign': ctx, 'v': '1.0', 'type': 'jsonp', 'isSec': 0, 'timeout': '20000',
          'api': 'mtop.taobao.widgetService.getJsonComponent', 'dataType': 'jsonp', 'jsonpIncPrefix': 'mboxfc',
          'callback': 'mtopjsonpmboxfc7', 'data': data}

url = 'https://h5api.m.1688.com/h5/mtop.taobao.widgetservice.getjsoncomponent/1.0/'

headers = {
    'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
    'referer': 'https://sale.1688.com/',
    'cookie': 'cookie2=1050aec592e1c8e9dfad0f8e4ab44ec5; sgcookie=E100i4IlUgisdJDK68DXs%2B3MBlgmuBvkPGMCzVpVrdtzA34Zwea1s0x%2FcjaGHZhTuLNMOhGRfmKWu41TMwPU0HqmBoulfXM4TuswJIk%2BwPRSMLs%3D; t=dfbccdf543461d01c3987c0e03c2d260; _tb_token_=756e8eab5e7e3; __cn_logon__=false; cna=ZawAG3qKEDsCAd9IV6slae8q; xlly_s=1; _m_h5_tk=69bff1f9ec3c4e61e24aae5056a15946_1661224272179; _m_h5_tk_enc=c88eb72f00d3850801d82bbdeebd7bbc; alicnweb=touch_tb_at%3D1661213840274; isg=BNbWaHjgqaWBrp0G-T74okfzJ4rYdxqxKQtFXUA_wrlUA3adqAdqwTzym5_v0RLJ; l=eBjlvFcrTOCSesN_BOfZlurza77OSIRYhuPzaNbMiOCP_4W956jhW6l-CfKpC3GVhs_JR3uO6DEMBeYBcQd-nxvOc7DZWVHmn; tfstk=cSLOBb6XKXFtAOj5cCnh07SxUkEOabuVdR6bkEuRQXcxF5a0usjXrUhxJV1cUOhd.'
}

resp = requests.get(url, headers=headers, params=params).text
print(resp)

```