1. [代码] 获取Cookie保存到变量     

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10 | import urllib2<br>import cookielib<br>\# 声明一个CookieJar对象实例来保存cookie<br>cookie = cookielib.CookieJar()<br>\# 利用urllib2库的HTTPCookieProcessor对象来创建cookie处理器<br>handler = urllib2.HTTPCookieProcessor(cookie)<br>\# 通过handler来构建opener<br>opener = urllib2.build\_opener(handler)<br>\# 此处的open方法同urllib2的urlopen方法，也可以传入request<br>response = opener.open('http://www.baidu.com') |


2. [代码]保存cookies到文件     

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14 | import cookielib<br>import urllib2<br>\# 设置保存cookie的文件，同级目录下的cookie.txt<br>filename = 'cookie.txt'<br>\# 声明一个MozillaCookieJar对象实例来保存cookie，之后写入文件<br>cookie = cookielib.MozillaCookieJar(filename)<br>\# 利用urllib2库的HTTPCookieProcessor对象来创建cookie处理器<br>handler = urllib2.HTTPCookieProcessor(cookie)<br>\# 通过handler来构建opener<br>opener = urllib2.build\_opener(handler)<br>\# 创建一个请求，原理同urllib2的urlopen<br>response = opener.open("http://www.baidu.com")<br>\# 保存cookie到文件<br>cookie.save(ignore\_discard=True, ignore\_expires=True) |


3. [代码]从文件中获取cookies并访问     

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12 | import cookielib<br>import urllib2<br>\# 创建MozillaCookieJar实例对象<br>cookie = cookielib.MozillaCookieJar()<br>\# 从文件中读取cookie内容到变量<br>cookie.load('cookie.txt', ignore\_discard=True, ignore\_expires=True)<br>\# 创建请求的request<br>req = urllib2.Request("http://www.baidu.com")<br>\# 利用urllib2的build\_opener方法创建一个opener<br>opener = urllib2.build\_opener(urllib2.HTTPCookieProcessor(cookie))<br>response = opener.open(req)<br>print response.read() |


4. [代码]以学校的教育系统为例，将cookie信息保存到文本文件中，实现模拟登录     跳至 [1] [2] [3] [4] [全屏预览]

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21 | import urllib2<br>import cookielib<br>filename = 'cookie.txt'<br>\# 声明一个MozillaCookieJar对象实例来保存cookie，之后写入文件<br>cookie = cookielib.MozillaCookieJar(filename)<br>opener = urllib2.build\_opener(urllib2.HTTPCookieProcessor(cookie))<br>postdata = urllib.urlencode({<br>            'stuid':'201200131012',<br>            'pwd':'23342321'<br>        })<br>\# 登录教务系统的URL<br>loginUrl = 'http://jwxt.sdu.edu.cn:7890/pls/wwwbks/bks\_login2.login'<br>\# 模拟登录，并把cookie保存到变量<br>result = opener.open(loginUrl,postdata)<br>\# 保存cookie到cookie.txt中<br>cookie.save(ignore\_discard=True, ignore\_expires=True)<br>\# 利用cookie请求访问另一个网址，此网址是成绩查询网址<br>gradeUrl = 'http://jwxt.sdu.edu.cn:7890/pls/wwwbks/bkscjcx.curscopre'<br>\# 请求访问成绩查询网址<br>result = opener.open(gradeUrl)<br>print result.read() |
