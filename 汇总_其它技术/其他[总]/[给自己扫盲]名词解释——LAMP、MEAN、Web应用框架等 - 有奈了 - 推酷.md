名词解释

LAMP

![LAMP_software_bundle.svg](https://gitee.com/hxc8/images5/raw/master/img/202407172331335.jpg)

The LAMP software bundle (here additionally with Squid ). A high performance and high-availability solution for a hostile environment

LAMP 是指一组通常一起使用来运行动态网站或者服务器的 自由软件 名称首字母缩写：

· L inux ， 操作系统 ；

· A pache ， 网页服务器 ；

· M ariaDB 或 M ySQL ， 数据库管理系统 （或者 数据库服务器 ）；

· P HP 、 P erl 或 P ython ， 脚本语言 。

From wiki： http://zh.wikipedia.org/wiki/LAMP

MEAN（MongoDB，Express，Angular，Nodejs/Nginx）

MongoDB是一种文件导向数据库管理系统，由C++撰写而成，以此来解决应用程序开发社区中的大量现实问题。NoSql.

Express是一个简洁而灵活的node.js Web应用框架,提供一系列强大特性帮助你创建各种Web应用

AngularJS是一款开源JavaScript函式库，由Google维护，用来协助单一页面应用程式运行的。它的目标是透过MVC模式(MVC)功能增强基于浏览器的应用，使开发和测试变得更加容易。

Nginx（发音同engine x）是一款由俄罗斯程序员Igor Sysoev所开发轻量级的网页服务器、反向代理服务器以及电子邮件（IMAP/POP3）代理服务器。

From

http://zh.wikipedia.org/wiki/MongoDB/

http://expressjs.jser.us/

http://zh.wikipedia.org/wiki/AngularJS

http://zh.wikipedia.org/wiki/Nginx

NoSQL

在计算机科学中，非关系型数据库（NoSQL）是一个和之前的关系型数据库（RDBM）有很大不同的另一类数据结构化存储管理系统。非关系型数据库通常没有固定的表结构，并且避免使用join操作。和关系型数据库相比，非关系型数据库特别适合以SNS为代表web 2.0应用，这些应用需要极高速的并发读写操作，而对数值一致性要求却不甚高。

关系型数据库最大特点就是事务的一致性：传统的关系型数据库读写操作都是事务的，具有ACID（原子性Atomicity、一致性Consistency、隔离性Isolation、持久性Durability）的特点，C就是一致性（Consistency），这个特点是关系型数据库的灵魂（其他三个AID都是为其服务的），这个特性使得关系型数据库可以用于几乎所有对一致性有要求的系统中，如典型的银行系统。

但是，在网页应用中，尤其是SNS应用中，一致性却不是显得那么重要，用户A看到的内容和用户B看到同一用户C内容更新不一致是可以容忍的，或者说，两个人看到同一好友的数据更新的时间差那么几秒是可以容忍的，因此，关系型数据库的最大特点在这里已经无用武之地，起码不是那么重要了。

相反的，关系型数据库为了维护一致性所付出的巨大代价就是其读写性能比较差，而像微博，facebook这类SNS的应用，对并发读写能力要求极高，关系型数据库已经无法应付（在读方面，传统上为了克服关系型数据库缺陷，提高性能，都是增加一级memcache来静态化网页，而在SNS中，变化太快，memcache已经无能为力），因此，必须用新的一种数据结构化存储来来代替关系数据库。

关系数据库的另一个特点就是其具有固定的表结构，因此，其扩展性极差，而在SNS中，系统的升级，功能的增加，往往意味着数据结构巨大改动，这一点关系型数据库也难以应付，需要新的结构化数据存储。

于是，非关系数据库（NoSQL）应运而生，由于不可能用一种数据结构化存储方式应付所有的新的需求，因此， 非关系型数据库严格上不是一种数据库，应该是一种数据结构化存储方法的集合。

必须强调的是，数据的持久存储，尤其是海量数据的持久存储，还是需要关系数据库这员老将。

From:http://www.sigma.me/2011/06/11/intro-to-nosql.html

Web应用框架

（Web application framework）是一种计算机软件框架，用来支持动态网站、网络应用程序及网络服务的开发。这种框架有助于减轻网页开发时共通性活动的工作负荷，例如许多框架提供数据库访问接口、标准样板以及会话管理等，可提升代码的可再用性。

PHP

· Zend framework

· CakePHP

· Yii

· ThinkPHP

· symfony

· Laravel

· kohanaphp

· Seagull

· Drupal

· CodeIgniter

· WindFramework

javascript

· jQuery

· MooTools

· Prototype

· dojo

· zk

Python

· django

· pinax

· Grok

· Pylons

· TurboGears

· web2py

· Zope

· Quixote

· snakelets

· snakelets

· PylonsHQ

Ruby

· Ruby On Rails

· Sinatra

JAVA

· Spring

· Struts

· hibernate

· Grails

· Tapestry

· ZK