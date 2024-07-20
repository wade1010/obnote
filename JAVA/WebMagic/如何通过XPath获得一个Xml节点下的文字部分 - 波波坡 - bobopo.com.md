- 首页

- 编程

- 休闲

- 知识

- 杂记

首页 < 文库 < 编程 <

如何通过XPath获得一个Xml节点下的文字部分

上一篇: 在.Net中用XmlDocument.LoadXml载入Xhtml文件时候速度很慢(2008-01-12)

下一篇: 下载更新了GridMove v1.9.49(2008-01-08)

实例

如果存在以下的Xml文本，

Hello, my name is Bob 

可以通过XPath的语法获得下面的文字部分，也就是""Hello, my name is"。XPath语法如下：

/a/text()[1]

解释

这实际上是一个缩写的XPath语法。完整的语法如下：

/a/child::text()[1]

text()就是child::text()的缩写。

text()是一个Node Test语法，当一个节点类型是文字(Text)的时候，该语法为真。类似的Node Test语法还有comment()、node()等，分别在节点类型是注释和一般节点是为真。

child::text()则返回一个集合，包含在所有子节点中类型为文字(Text)的节点。[1]则是表明取集合中的第1项。

其他资料

- Google Group: XPath: Filtering out the text string in the child node

- MSDN: Node Type Test

作者: 闹博 [nowbor]

波波坡原创文章 链接：http://www.bobopo.com/article/code/xpath_text.htm

标签:IT

关键词: XPath, Xml节点, 文字部分, Node Test, XPath语法

创建日期: 2008-01-10

IT 相关文章

- 在.Net Framework中处理Xml名称空间(Namespace)(2008-01-22)

- Xml名称空间(Namespace)的简介和优劣分析(2008-01-22)

- 硬盘检测、寿命、S.M.A.R.T.和硬盘检测软件(2008-01-13)

- 通过Microsoft Media Encoder用VBScript提取视频中的音频(2008-01-01)

- 在一个文本文件输入病毒代码测试杀毒软件能力(2008-01-01)

- ...... 更多相关文章 ......

其它文章

- 在托管(Managed)代码中调用原生(Native)Dll的手段和调试方法(2008-01-20)

- 硬盘检测、寿命、S.M.A.R.T.和硬盘检测软件(2008-01-13)

- 在.Net中用XmlDocument.LoadXml载入Xhtml文件时候速度很慢(2008-01-12)

- 下载更新了GridMove v1.9.49(2008-01-08)

- 下载安装K-Lite Mega Codec Pack v3.6.5(2008-01-07)

- NoteZilla 7.0，实用的便签管理程序，可从QNP升级(2008-01-06)

- HttpWebRequest某些Web服务器的Url时出现错误(2008-01-06)

- ...... 更多其它文章 ......