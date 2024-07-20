本文实例讲述了在Python中模仿POST HTTP数据及带Cookie提交数据的实现方法，分享给大家供大家参考。具体实现方法如下：

方法一

如果不使用Cookie, 发送HTTP POST非常简单:

复制代码 代码如下:

import urllib2, urllib

data = {'name' : 'www', 'password' : '123456'}

f = urllib2.urlopen(

        url     = 'http://www.jb51.net/',

        data    = urllib.urlencode(data)

  )

print f.read()



当使用Cookie时, 代码变得有些复杂:

复制代码 代码如下:

import urllib2

cookies = urllib2.HTTPCookieProcessor()

opener = urllib2.build_opener(cookies)

f = opener.open('http://www.xxxx.net/?act=login&name=user01')

data = ' Hello '

request = urllib2.Request(

        url     = 'http://www.xxxx.net/?act=send',

        headers = {'Content-Type' : 'text/xml'},

        data    = data)

opener.open(request)



第一次 open() 是进行登录. 服务器返回的 Cookie 被自动保存在 cookies 中, 被用在后来的请求.

第二次 open() 用 POST 方法向服务器发送了 Content-Type=text/xml 的数据. 如果你不创建一个 Request, 而是直接使用 urlopen() 方法, Python 强制把 Content-Type 改为 application/x-www-form-urlencoded.

方法二

用urllib2库，带Cookie请求URL页面

例1：

复制代码 代码如下:

import urllib2

opener = urllib2.build_opener()

opener.addheaders.append(('Cookie', 'cookiename=cookievalue'))

f = opener.open("http://example.com/")



例2：

复制代码 代码如下:

import urllib2

import urllib

from cookielib import CookieJar



cj = CookieJar()

opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cj))

# input-type values from the html form

formdata = { "username" : username, "password": password, "form-id" : "1234" }

data_encoded = urllib.urlencode(formdata)

response = opener.open("https://page.com/login.php", data_encoded)

content = response.read()

希望本文所述对大家的Python程序设计有所帮助。