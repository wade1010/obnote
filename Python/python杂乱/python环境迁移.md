pip list 

```
(base) root@89300a30efaf:~# pip list
Package                                  Version
---------------------------------------- -------------------
absl-py                                  0.11.0
aioch                                    0.0.2
aiohttp                                  3.6.3
alabaster                                0.7.12
alembic                                  1.4.3
amqp                                     5.0.5
anaconda-client                          1.7.2
..................省略...................
xmltodict                                0.12.0
yapf                                     0.30.0
yarl                                     1.6.3
zenlog                                   1.1
zict                                     2.0.0
zipp                                     3.4.0
zope.event                               4.5.0
zope.interface                           5.1.2
```

仅复制包名和版本到sublime中

按住cmd键version最前方拉倒最低下，然后输入’==‘

然后切换到正则模式，' .*==' 替换为 '==' ,结果为

```
absl-py==0.11.0
aioch==0.0.2
aiohttp==3.6.3
alabaster==0.7.12
alembic==1.4.3
amqp==5.0.5
anaconda-client==1.7.2
anaconda-navigator==1.10.0
anaconda-project==0.8.3
apache-airflow==2.0.0
apache-airflow-providers-cncf-kubernetes==1.0.0
apache-airflow-providers-ftp==1.0.0
apache-airflow-providers-http==1.0.0
。。。。。。。。。省略。。。。。。。。。
wurlitzer==2.0.1
xlrd==1.2.0
XlsxWriter==1.3.7
xlwt==1.3.0
xmltodict==0.12.0
yapf==0.30.0
yarl==1.6.3
zenlog==1.1
zict==2.0.0
zipp==3.4.0
zope.event==4.5.0
zope.interface==5.1.2
```

拷贝到本地python项目中，创建requirement.txt，粘贴到里面

anaconda切换到自己想要的环境，然后开始安装

pip install -r requirements.txt -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

PS:可能有些包不是你需要的，或者有些包可能安装失败（遇到的是anaconda），都删掉就行 ，然后重复上面命令