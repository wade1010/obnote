A5交易A5任务SEO诊断淘宝客站长团购

　　站长的工作是设计精美的网站，为大众展现网站丰富多彩的内容。当然，我们也希望精心设计的网站获得理想的排名，这就要求我们去研究搜索引擎排名规律，最大程度的获得机会展现给客户。然而，搜索引擎种类很多，有时候，我们对某一种搜索引擎的排名很好，却在另外的搜索引擎上面获得不到一样的排名，原因是各个搜索引擎规则不一样。为此，有人复制出相同的内容以应付不同搜索引擎的排名规则。然而，一旦搜索引擎发现站内有大量“克隆”的页面，就会给以惩罚，不收录这些重复的页面。另一方面，我们网站的内容属于个人私密文件，不想暴露在搜索引擎中。这时，robot.txt就是为了解决这两个问题。

　一、搜索引擎和其对应的User-Agent

　　那么，目前有哪些搜索引擎和其对应的User-Agent呢?下面，我列出了一些，以供参考。

　　搜索引擎 User-Agent

　　AltaVista Scooter

　　baidu Baiduspider

　　Infoseek Infoseek

　　Hotbot Slurp

　　AOL Search Slurp

　　Excite ArchitextSpider

　　Google Googlebot

　　Goto Slurp

　　Lycos Lycos

　　MSN Slurp

　　Netscape Googlebot

　　NorthernLight Gulliver

　　WebCrawler ArchitextSpider

　　Iwon Slurp

　　Fast Fast

　　DirectHit Grabber

　　Yahoo Web Pages Googlebot

　　Looksmart Web Pages Slurp

　二、robots基本概念

　　Robots.txt文件是网站的一个文件，它是给搜索引擎蜘蛛看的。搜索引擎蜘蛛爬行道我们的网站首先就是抓取这个文件，根据里面的内容来决定对网站文件访问的范围。它能够保护我们的一些文件不暴露在搜索引擎之下，从而有效的控制蜘蛛的爬取路径，为我们站长做好seo创造必要的条件。尤其是我们的网站刚刚创建，有些内容还不完善，暂时还不想被搜索引擎收录时。

　　robots.txt也可用在某一目录中。对这一目录下的文件进行搜索范围设定。

　　几点注意：

　　网站必须要有一个robot.txt文件。

　　文件名是小写字母。

　　当需要完全屏蔽文件时，需要配合meta的robots属性。

　三、robots.txt的基本语法

　　内容项的基本格式：键: 值对。

　　1) User-Agent键

　　后面的内容对应的是各个具体的搜索引擎爬行器的名称。如百度是Baiduspider，谷歌是Googlebot。

　　一般我们这样写：

　　User-Agent: *

　　表示允许所有搜索引擎蜘蛛来爬行抓取。如果只想让某一个搜索引擎蜘蛛来爬行，在后面列出名字即可。如果是多个，则重复写。

　　注意：User-Agent:后面要有一个空格。

　　在robots.txt中，键后面加：号，后面必有一个空格，和值相区分开。

　　2)Disallow键

　　该键用来说明不允许搜索引擎蜘蛛抓取的URL路径。

　　例如：Disallow: /index.php 禁止网站index.php文件

　　Allow键

　　该键说明允许搜索引擎蜘蛛爬行的URL路径

　　例如：Allow: /index.php 允许网站的index.php

　　通配符*

　　代表任意多个字符

　　例如：Disallow: /*.jpg 网站所有的jpg文件被禁止了。

　　结束符$

　　表示以前面字符结束的url。

　　例如：Disallow: /?$ 网站所有以?结尾的文件被禁止。

四、robots.txt实例分析

　　例1. 禁止所有搜索引擎访问网站的任何部分

　　User-agent: *

　　Disallow: /

　　例2. 允许所有的搜索引擎访问网站的任何部分

　　User-agent: *

　　Disallow:

　　例3. 仅禁止Baiduspider访问您的网站

　　User-agent: Baiduspider

　　Disallow: /

　　例4. 仅允许Baiduspider访问您的网站

　　User-agent: Baiduspider

　　Disallow:

　　例5. 禁止spider访问特定目录

　　User-agent: *

　　Disallow: /cgi-bin/

　　Disallow: /tmp/

　　Disallow: /data/

　　注意事项：1)三个目录要分别写。2)请注意最后要带斜杠。3)带斜杠与不带斜杠的区别。

　　例6. 允许访问特定目录中的部分url

　　我希望a目录下只有b.htm允许访问，怎么写?

　　User-agent: *

　　Allow: /a/b.htm

　　Disallow: /a/

　　注：允许收录优先级要高于禁止收录。

　　从例7开始说明通配符的使用。通配符包括("$" 结束符;

　　"*"任意符)

　　例7. 禁止访问网站中所有的动态页面

　　User-agent: *

　　Disallow: /*?*

　　例8. 禁止搜索引擎抓取网站上所有图片

　　User-agent: *

　　Disallow: /*.jpg$

　　Disallow: /*.jpeg$

　　Disallow: /*.gif$

　　Disallow: /*.png$

　　Disallow: /*.bmp$

　　其他很多情况呢，需要具体情况具体分析。只要你了解了这些语法规则以及通配符的使用，相信很多情况是可以解决的。

五、meta robots标签

　　meta是网页html文件的head标签里面的标签内容。它规定了此html文件对与搜索引擎的抓取规则。与robot.txt 不同，它只针对写在此html的文件。

　　写法：

       。

　　…里面的内容列出如下

　　noindex - 阻止页面被列入索引。

　　nofollow - 阻止对于页面中任何超级链接进行索引。

　　noarchive - 不保存该页面的网页快照。

　　nosnippet - 不在搜索结果中显示该页面的摘要信息，同时不保存该页面的网页快照。

　　noodp - 在搜索结果中不使用Open Directory Project中的描述信息作为其摘要信息。

　　六、robots的测试

　　在谷歌站长工具中，添加网站后使用左侧的抓取工具的权限，就可以对网站的robots进行测试了，详细见图。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190802899.jpg)

　　robots.txt和mtea robots的内容就介绍到这里，相信大家对robot已经有了比较详细的了解。使用好robots对于我们网站的seo有着重要作用，做的好，可以有效的屏蔽那些我们不想让搜索引擎抓取的页面，也就是对用户体验不高的页面，从而将有利于关键词排名的内页充分展示个客户，获得搜索引擎对站内页面的权重，从而有利于我们将关键词排名做的更好。

　　本文由idsem小组吉智刚编写 版权链接：http://www.idsem.com 尊重版权转载请注明!!!