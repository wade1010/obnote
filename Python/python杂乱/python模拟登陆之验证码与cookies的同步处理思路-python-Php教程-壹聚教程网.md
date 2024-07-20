自动登陆可能是写爬虫的第一步，如果都不能登陆，很多东西爬不到的。这也不是第一次写包含验证码识别的自动登陆脚本了。这次有点被坑住了，把这次的记录下来。

这次要自动登陆的网站地址是：2013年株洲市中小学教师全员培训   /IndexPage/Index.aspx

先说下思路，好多人写那些不需要验证码识别的自动登陆脚本很容易，只要保存好cookies就可以了，但是对于需要验证码的网站就总是登陆不上去。

对于需要验证码的网站的自动登陆脚本的步骤：(以上面我说的那个网站为例，对于python和其他语言，思路和步骤都是适用的)

a.先打开登陆页面，获得cookies。

b.再访问验证码的地址。验证码是动态的，每次打开都不一样。

c.识别验证码。这里就需要你处理、识别刚才得到的验证码。自己去找验证码(captcha)识别库，python可以用 pytesser(这个库是调用PIL来处理识别的) 、openc 之类的   或者可以人工识别然后手动输入验证码。

d.构造post请求数据(request data)和请求头部(request head)  ，然后 将构造的请求 post给网站

f.获取 响应(response)信息，并通过测试来验证登陆是否成功。

或者直接跳过a步骤：

b.直接访问验证码的地址。(这里之所以能跳过 a  ,是因为当我们打开 验证码地址时候，已经能获得所有我们需要的cookies 和登陆的验证码 ，所以就没必要 先操作 a 步骤)

c.识别验证码。这里就需要你处理、识别刚才得到的验证码。自己去找验证码(captcha)识别库，python可以用 pytesser(这个库是调用PIL来处理识别的) 、openc 之类的   或者可以人工识别然后手动输入验证码。

d.构造post请求数据(request data)和请求头部(request head)  ，然后 将构造的请求 post给网站

f.获取 响应(response)信息，并通过测试来验证登陆是否成功。

#############################################################################

这两个步骤的区别无非是多了个访问登陆页面的步骤，对登陆没什么影响。我这里用浏览器演示下 第一种步骤(即a ，b,c,d,e,f步骤)，下篇文章开始更具体的操作，获取修改cookies，post数据构造等。

通过下面的步骤来验证我们的思路(还是刚才的网站)：

用到的工具：

firefox浏览器中的firebug扩展 。还可以使用Fiddler，或者httpfox，wireshark 。我这用firebug方便。chrome也有firebug。

1.访问登陆页面获取cookies (相当于a 步骤)

先清空火狐的缓存和cookies  ，打开firebug，监控所有网页。然后打开登陆页面：/IndexPage/Index.aspx 。我们得到了第一个验证码：2073 ，可以通过firebug看到所获得的cookies ， 

​

2.访问验证码地址获得cookies与验证码(相当于 b ，c  步骤)

先找到验证码地址。鼠标右键点击验证码，选择“复制图像地址”，得到验证码地址为：/GuoPeiAdmin/Login/ImageLog.aspx ，如下图：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/4D9874B9AE56492CB1A11FEE0E07D1E940813220752.jpeg)

 

测试下这个验证码地址是否有效：在不关闭刚才登陆页面的 同时用火狐打开验证码的地址，的确得到了一个验证码，也是我们获得的第二个验证码：2876 ，cookies 还是原来的cookies。

 

3. 登陆网站(相当于 d ,f  步骤)

接着，我们开始在刚才打开的登陆网页登陆，输入账号和密码，那么我问你，现在输入哪个验证码才是正确的？你肯定会回答是 2073 这个。错了，这时，我们应该输入第二个获得的验证码：2876   才能正常的登陆进去。不信的话，你可以自己多试几次来验证。

 

如果我们把 刚才 1——2——3 的操作顺序改为：2——1——3

先打开验证码页面获得一个验证码：AAAA，然后打开登陆页面又得到一个验证码：BBBB，此时再登陆，输入第一个 AAAA 验证码登陆， 就是错误的,无法登陆成功。

 

 

至于第二种步骤，我实在找不到能够模拟这个过程的，例如，用fiddler 要先访问验证码地址，获取cookies ，接着用fiddler来模拟post来登陆网站。可惜，我用了n多post软件，都没法实现这些步骤，总是登陆失败。所以直接用python来模拟这个过程：

其中需要 pytesser 模块及其调用的模块和软件都要自己安装，自己去网上google教程安装吧。哥很久很久之前就装了。

pytesser下载

http://code.google.com/p/pytesser/

Tesseract OCR engine下载：

http://code.google.com/p/tesseract-ocr/

PIL官方下载

http://www.pythonware.com/products/pil/

 

python模拟登陆代码;

|  代码如下 | 复制代码 |
| - | - |
| \# -\*- coding: utf-8 -\*-<br><br>import urllib2<br>import cookielib<br>import urllib<br>import Image<br>import cStringIO <br>from pytesser import \*<br>import re<br>import os<br>\#避免 UnicodeEncodeError: 'ascii' codec can't encode character.  的报错<br>import sys<br>reload(sys)<br>sys.setdefaultencoding( "utf-8" )<br> <br>\#下面这段是关键了，将为urlib2.urlopen绑定cookies<br>\#MozillaCookieJar(也可以是 LWPCookieJar ，这里模拟火狐，所以用这个了) 提供可读写操作的cookie文件,存储cookie对象<br>cookiejar = cookielib.MozillaCookieJar()<br>\# 将一个保存cookie对象，和一个HTTP的cookie的处理器绑定<br>cookieSupport= urllib2.HTTPCookieProcessor(cookiejar)<br>\#下面两行为了调试的<br>httpHandler = urllib2.HTTPHandler(debuglevel=1)<br>httpsHandler = urllib2.HTTPSHandler(debuglevel=1)<br>\#创建一个opener，将保存了cookie的http处理器，还有设置一个handler用于处理http的<br>opener = urllib2.build\_opener(cookieSupport, httpsHandler)<br>\#将包含了cookie、http处理器、http的handler的资源和urllib2对象绑定在一起，安装opener,此后调用urlopen()时都会使用安装过的opener对象，<br>urllib2.install\_opener(opener)<br> <br> <br>\#登陆页面<br>loginpage = "http://zhuzhou2013.feixuelixm.teacher.com.cn/IndexPage/Index.aspx"<br>\#要post的url<br>LoginUrl   = "http://zhuzhou2013.feixuelixm.teacher.com.cn/GuoPeiAdmin/Login/Login.aspx"<br> <br>\#\#打开登陆页面, 以此来获取cookies   。  但是因为  \#\#打开验证码页面就可以获取全部cookies了，所以可以直接跳过这一步。算是可有可无的<br>\#taobao = urllib2.urlopen(loginpage)<br>\#\#打印cookies<br>\#print   cookiejar<br>\#\#先打开页面获取的cookie与  后打开验证码页面的cookie不同。<br> <br><br>\#\#提取验证码text(手动输入验证码)<br>\#vrifycodeUrl = "http://zhuzhou2013.feixuelixm.teacher.com.cn/GuoPeiAdmin/Login/ImageLog.aspx"<br>\#file = urllib2.urlopen(vrifycodeUrl)<br>\#pic= file.read()<br>\#path = "c:code.jpg"<br>\#\#img = cStringIO.StringIO(file) \# constructs a StringIO holding the image  AttributeError: addinfourl instance has no attribute 'seek'<br>\#localpic = open(path,"wb")<br>\#localpic.write(pic)<br>\#localpic.close()<br>\#print "please  %s,open code.jpg"%path  <br>\#\#text =raw\_input("input code :")<br>\#im = Image.open(path)<br>\#text =image\_to\_string(im)<br>\#print text<br> <br>\#提取验证码地址(用pytesser 识别， 自己网上找教程安装)<br>\#并且用pytesser 识别验证码，赋值给 text ，并打印出来。<br>vrifycodeUrl = "http://zhuzhou2013.feixuelixm.teacher.com.cn/GuoPeiAdmin/Login/ImageLog.aspx"<br>file = urllib2.urlopen(vrifycodeUrl).read()<br>img = cStringIO.StringIO(file) \# constructs a StringIO holding the image  AttributeError: addinfourl instance has no attribute 'seek'<br>im = Image.open(img)<br>text = image\_to\_string(im)<br>print "vrifycode:", text<br> <br> <br>\#设置cookie的值，因为post request head  需要 返回 cookie (不是cookies ，是将cookies的格式处理后的值)  <br>cookies = ''<br>\#这里要从<br>for index, cookie in enumerate(cookiejar):<br>  \#print '[',index, ']';<br>  \#print cookie.name;<br>  \#print cookie.value;<br>  \#print "\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#"<br>  cookies = cookies+cookie.name+"="+cookie.value+";";<br>print "\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#"<br>cookie = cookies[:-1]<br>print "cookies:",cookie<br><br>\#用户名，密码<br>\#当然，我这里登顶要处理掉密码和用户名<br>\#username = "7879954564555664"<br>\#password = "12313164"<br><br>\#用户名，密码<br>username = "430223198809308045"<br>password = "56961888"<br>\#请求数据包<br>postData = {   <br> '\_\_EVENTTARGET':'', <br> '\_\_EVENTARGUMENT':'',<br> '\_\_VIEWSTATE': '/wEPDwUKLTcyMzEyMTY2Nw8WAh4LTG9naW5lZFBhZ2UFEExvZ2luZWRQYWdlLmFzcHgWAmYPZBYCZg8PZBYGHgV0aXRsZQUg55So5oi35ZCNL+WtpuS5oOeggS/ouqvku73or4Hlj7ceB29uZm9jdXMFEGNoZWNrSW5wdXQodGhpcykeBm9uYmx1cgUNcmVzdG9yZSh0aGlzKWQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFC0ltZ2J0bkxvZ2luckJjpNhrusWhtPuT33UJ1dBUkvw=',<br>    'txtUserName':username, <br>    'txtPassWord':password,    <br> 'txtCode':text,<br> 'ImgbtnLogin.x':44 ,<br> 'ImgbtnLogin.y':14,<br> 'ClientScreenWidth':1180<br>} <br> <br> <br>\#post请求头部<br>headers = {<br>    <br> 'Accept' : 'text/html,application/xhtml+xml,application/xml;q=0.9,\*/\*;q=0.8',<br>    'Accept-Language': 'zh-cn,en-us;q=0.8,zh;q=0.5,en;q=0.3',<br>    'Accept-Encoding': 'gzip, deflate',<br>    <br>    'Host':    'zhuzhou2013.feixuelixm.teacher.com.cn',<br>    'Cookie':cookies,<br>    'User-Agent' : 'Mozilla/5.0 (Windows NT 5.1; rv:29.0) Gecko/20100101 Firefox/29.0',  <br>    'Referer' : 'http://zhuzhou2013.feixuelixm.teacher.com.cn/GuoPeiAdmin/Login/Login.aspx',<br> \#'Content-Type': 'application/x-www-form-urlencoded',<br> \#'Content-Length' :474,<br>    'Connection' : 'Keep-Alive'<br> <br>}<br><br>\#合成post数据 <br>data = urllib.urlencode(postData)    <br>print "data:\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#"<br>print  data<br>\#创建request<br>\#构造request请求<br>request = urllib2.Request(  LoginUrl,data,headers  )<br>try:<br> \#访问页面<br> response = urllib2.urlopen(request)<br> \#cur\_url =  response.geturl()<br> \#print "cur\_url:",cur\_url<br> status = response.getcode()<br> print status<br>except  urllib2.HTTPError, e:<br>  print e.code<br>\#将响应的网页打印到文件中，方便自己排查错误<br>\#必须对网页进行解码处理<br>f= response.read().decode("utf8")<br>outfile =open("rel\_ip.txt","w")<br>print &gt;&gt; outfile , "%s"   % ( f)<br><br>\#但因响应的信息<br>info = response.info()<br>print info<br> <br><br>\#测试登陆是否成功，因为在testurl只有登陆后才能访问<br>testurl = "http://zhuzhou2013.feixuelixm.teacher.com.cn/GuoPeiAdmin/Login/LoginedPage.aspx"<br>try:<br> response = urllib2.urlopen(testurl)<br>except  urllib2.HTTPError, e:<br>  print e.code<br>\#因为后面要从网页查找字符来验证登陆成功与否，所以要保证查找的字符与网页编码相同，否则无非得到正确的结论。建议用英文查找,如css中的 id， name 之类的。<br>f= response.read().decode("utf8").encode("utf8")<br>outfile =open("out\_ip.txt","w")<br>print &gt;&gt; outfile , "%s"   % ( f)<br>\#在返回的网页中，查找&quot;你好” 两个字符，因为只有登陆成功后才有两个字，找到了即表示登陆成功。建议用英文<br>tag = '你好'.encode("utf8")<br>if  re.search( tag,f):<br> \#登陆成功<br> print 'Logged in successfully!'<br>else:<br>    \#登陆失败<br> print 'Logged in failed, check result.html file for details'<br>response.close() |  |


 

#这个代码很随意，但是容易看，需要的活，可以写成函数。还有就是urlopen()在大量登陆及检验过程中，可能read(0因为网络阻塞而timeout(超时) ，需要设置urlopen() 的超时时间，或者多次发送请求

总结:在需要输入验证码的网页写自动登陆登陆脚本时候。关键是保证cookies 和 验证码 是同步的。如果直接打开验证码地址就能获得 登陆所需的全部cookies (此时验证码和cookies必定是同步到的)，那么就不必 先打开登陆页面来获取cookies，后打开验证码地址获取 验证码。何必多此一举呢？