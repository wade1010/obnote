1. 直接在 http://www.sphinxsearch.com/downloads.html 找 到最新的 windows 版本，我这里下的是 Win32 release binaries with MySQL support ，下载后解压在 D:/sphinx 目录下；

2. 在 D:/sphinx/ 下新建一个 data 目录用来存放索引文件， 一个 log 目录方日志文件，复制D:/sphinx/sphinx.conf.in 到 D:/sphinx/bin/sphinx.conf （注意修改文件 名）；

3. 修改 D:/sphinx/bin/sphinx.conf ，我这里 列出需要修改的几个：

type           = mysql # 数据源，我这里是mysql

sql_host       = localhost # 数据库服务器

sql_user       = root # 数据库用户名

sql_pass       = '' # 数据库密码

sql_db         = test # 数据库

sql_port       = 3306 # 数据库端口

sql_query_pre      = SET NAMES utf8 # 去掉此行前面的注释，如果你的数据库是uft8 编码的

index test1

{

# 放索引的目录

  path      = D:/sphinx/data/

# 编码

  charset_type     = utf-8

  #  指定utf-8 的编码表

  charset_table     = 0..9, A..Z->a..z, _, a..z, U+410..U+42F->U+430..U+44F, U+430..U+44F

  # 简单分词，只支持0 和1 ，如果要搜索中文，请指定为1

  ngram_len       = 1

# 需要分词的字符，如果要搜索中文，去掉前面的注释

  ngram_chars      = U+3000..U+2FA1F

}

# 搜索服务需要修改的部分

searchd

{

  # 日志

  log        = D:/sphinx/log/searchd.log

# PID file, searchd process ID file name

  pid_file      = D:/sphinx/log/searchd.pid

# windows 下启动searchd 服务一定要注释掉这个

  # seamless_rotate     = 1

}

4. 导入测试数据

sql 文件在 D:/sphinx/example.sql

C:/Program Files/MySQL/MySQL Server 5.0/bin>mysql -uroot test<d:/sphinx/example.sql

5. 建立索引

D:/sphinx/bin>indexer.exe test1 ( 备注 :test1 为 sphinx.conf 的 index test1() )

Sphinx 0.9.8-release (r1533)

Copyright (c) 2001-2008, Andrew Aksyonoff

using config file ‘./sphinx.conf’…

indexing index ‘test1′…

collected 4 docs, 0.0 MB

sorted 0.0 Mhits, 100.0% done

total 4 docs, 193 bytes

total 0.101 sec, 1916.30 bytes/sec, 39.72 docs/sec

D:/sphinx/bin>

6. 搜索 ’test’ 试试

D:/sphinx/bin>search.exe test1



显示结果如下

using config file ‘./sphinx.conf’…

index ‘test1′: query ‘test ‘: returned 3 matches of 3 total in 0.000 sec

displaying matches:

1. document=1, weight=2, group_id=1, date_added=Wed Nov 26 14:58:59 2008

id=1

group_id=1

group_id2=5

date_added=2008-11-26 14:58:59

title=test one

content=this is my test document number one. also checking search within

phrases.

2. document=2, weight=2, group_id=1, date_added=Wed Nov 26 14:58:59 2008

id=2

group_id=1

group_id2=6

date_added=2008-11-26 14:58:59

title=test two

content=this is my test document number two

3. document=4, weight=1, group_id=2, date_added=Wed Nov 26 14:58:59 2008

id=4

group_id=2

group_id2=8

date_added=2008-11-26 14:58:59

title=doc number four

content=this is to test groups

words:

1. ‘test’: 3 documents, 5 hits

D:/sphinx/bin>

 

6. 测试中文搜索

修改 test 数据库中 documents 数据表，

UPDATE `test`.`documents` SET `title` = ‘ 测试中文 ’, `content` = ‘this is my test document number two ，应该搜的到吧 ’ WHERE `documents`.`id` = 2;

重建索引：

D:/sphinx/bin>indexer.exe test1

搜索 ’ 中文 ’ 试试：

D:/sphinx/bin>search.exe 中文 

Sphinx 0.9.8-release (r1533)

Copyright (c) 2001-2008, Andrew Aksyonoff

using config file ‘./sphinx.conf’…

index ‘test1′: query ‘ 中文 ‘: returned 0 matches of 0 total in 0.000 sec

words:

D:/sphinx/bin>

貌似没有搜到，这是因为 windows 命令行中的编码是 gbk ，当然搜不出来。我们可以用程序试试，在D:/sphinx/api 下新建一个 foo.php 的文件，注意 utf-8 编码

<?php

require ’sphinxapi.php’;

$s = new SphinxClient();

$s->SetServer(’localhost’,9312);

$result = $s->Query(’ 中文 ’);

var_dump($result);

?>

启动 Sphinx searchd 服务

D:/sphinx/bin>searchd.exe

Sphinx 0.9.8-release (r1533)

Copyright (c) 2001-2008, Andrew Aksyonoff

WARNING: forcing –console mode on Windows

using config file ‘./sphinx.conf’…

creating server socket on 0.0.0.0:9312

accepting connections

 

执行 PHP 查询：

访问 http://www.test.com/sphinx/api/foo.php ( 自己配置的虚拟主机 )

 

使用 CORESEEK 分词

1 、下载 http://www.coreseek.cn/news/5/89/

2 、安装系统依赖的软件包

系统的基础组件需要如下的软件包：

   - Active Python 2.5 ( http://www.activestate.com/Products/activepython/ )

   - MySQL_Python 1.2.2 (http://sourceforge.net/project/showfiles.php?group_id=22307 ） 验证 可以不装

安装完前面两个组件后，系统可以运行，但是需要手工修改配置文件。

安装配置界面需要的软件包：

- gtk-dev 2.12.9 (http://sourceforge.net/project/showfiles.php?group_id=98754 ) 验证可以不装 

    - pycairo 1.4.12 (http://ftp.acc.umu.se/pub/GNOME/binaries/win32/pycairo/1.4/ ) 

    - pygobject 2.14.1 (http://ftp.acc.umu.se/pub/GNOME/binaries/win32/pygobject/2.14/ ) 

    - pygtk 2.12.1 (http://ftp.acc.umu.se/pub/GNOME/binaries/win32/pygtk/2.12 ) )

如果您下载的是完整版，前面提到的全部文件应该能在preq 子目录中找到。

安装前面提到的全部软件包（注意：必须先安装Python 和gtk)

注意: 必须是Active Python ，Python 官方的版本缺少系统需要的Win32 扩展支持，将导致系统无法工作。

注意： 完成本步后，必须重新启动您的计算机。

3 、解压 csft 到你认为的目录

4 、csft 文件内配与 sphinx 的内容大致相同 ( 配置详细见:sphinx+mysql (1) , (2) )

5 、创建词典文件

/bin/mmseg -u /data/unigram.txt    # 词库是动态的，指定目录就可以

·   把生成的文件改名为uni.lib ，

6 、导入sample.sql 数据库

7 、建立索引 index.exe --all   （详情见 sphinx + mysql(1) ）

以下分支说明如下

---------------------------------------------------------------

A ：

8 、安装SPHINXSE FOR MYSQL

http://www.sphinxsearch.com/downloads/mysql-5.0.45-sphinxse-0.9.8-win32.zip

下载后，解压然后覆盖MYSQL 目录，就OK 。 ( 注意mysql 版本 必须相同)

进入mysql 运行 show engines; 查看表的类型是否存在 sphinx

9 、创建Sphinx 存储引擎表

CREATE TABLE `sphinx` (

`id` int(11) NOT NULL,

`weight` int(11) NOT NULL,

`query` varchar(255) NOT NULL,

`group_id` int(11) NOT NULL,

KEY `Query` (`Query`)

) ENGINE=SPHINX CONNECTION='sphinx://localhost:3312/test1';

与一般mysql 表不同的是ENGINE=SPHINX CONNECTION='sphinx://localhost:3312/test1'; ，这里表示这个表采用SPHINXSE 引擎，与sphinx 的连接串是'sphinx://localhost:3312/test1 ，test1 是索引名称

根据sphinx 官方说明，这个表必须至少有三个字段，字段起什么名称无所谓，但类型的顺序必须是integer,integer,varchar ，分别表示记录标识document ID, 匹配权重weight 与查询query ，同时document ID与query 必须建索引。另外这个表还可以建立几个字段，这几个字段的只能是integer 或TIMESTAMP 类型，字段是与sphinx 的结果集绑定的，因此字段的名称必须与在sphinx.conf 中定义的属性名称一致，否则取出来的将是Null值。

10 、MySQL SphinxSE 全文检索存储引擎SQL 语句使用方法

安装SphinxSE 存储引擎后首先需新建一张特殊的指定"ENGINE=SPHINX" 检索表，如下：

CREATE TABLE ArticleFulltext (

    ID          INTEGER NOT NULL,

    Weight      INTEGER NOT NULL,

    Query      VARCHAR(3072) NOT NULL,

    ...

    INDEX (Query)

) ENGINE=SPHINX CONNECTION="sphinx://localhost:3312/test";

·   其中表名和字段名可以是任意名称，但前三个属性的类型必须为INT 、INT 和VARCHAR 。也可以拥有更多的属性，类型必须为INT 或TIMESTAMP ，名称必须与Sphinx 配置文件对应，用于返回检索结果的更多信息。

·   创建该表后即可使用如下的SQL 语句在MySQL 中进行全文检索：

·   SELECT * FROM ArticleFulltext WHERE Query=' 全文检索条件';

·   查询返回结果即为全文检索的结果，包括文档ID 、权重，若ArticleFulltext 表包含了更多属性还包含命中结果的其它信息。

·   通过SQL 联接操作可以很容易的实现融合检索，如：

·   SELECT ID, Title 

FROM Article, ArticleFulltext

WHERE ArticleFulltext.ID = Article.ID and Query = ' 博客' 

AND PublishTime > '2007-03-01' AND ReferCount > 0

ORDER BY Weight * 0.5 + ReferCount * 0.5;

·   上述SQL 语句即可检索出2007 年3 月1 日以来包含' 博客' 关键字且并引用过的文章，且按全文检索权重和引用数综合计算所得的权重进行排序。

·   由此可见，通过将全文检索系统提供的功能以存储引擎的形式嵌入到关系数据库MySQL 中可以很方便的提供融合检索功能，虽然功能限制较多，也不失为一种聪明便捷的方式。

11, 将SPHINX 生成WINDOWS 服务

searchd --install --config "csft.conf"

12. 启动服务 net start |searchd( 或者其它服务名)

---------------------------------------------------------------

B ：

配置sphinx.conf 文件，支持中文编码



charset_type = zh_cn.utf-8 

charset_dictpath = D:/csft3.1/bin   # 分词 lib 库文件的目录 

min_infix_len = 0

