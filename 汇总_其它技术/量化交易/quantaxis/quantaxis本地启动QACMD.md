本地docker-compose启动quantaxis的docker集群。因为QAcmd本地启动也是需要依赖别的应用，未来再慢慢把其他的也改成本地。docker-compose的配置最好用默认配置

#update qamodule yutiansut 2021/9/10, 09:58  这是最后一个拥有QAARP的版本

但是没有QAApplication，继续往老的版本查找

往老的找了几次就找到了

#fix tradedate bug yutiansut 2021/5/12, 08:22

git clone 下来

安装群里下载的whl文件，pip install quantaxis-1.10.19r4-py3-none-any.whl

然后从本地找到安装后的源码，目录名叫QUANTAXIS，复制出来备用。

pip uninstall quantaxis

然后进入docker 查看python安装的库需要的版本

docker exec -it qacommunity-rust bash

pip list

然后替换quantaxis项目下面的requirement.txt下面的>=,改为指定版本==,没有版本要求的的也加上指定版本。

```
pymongo==3.11.2
bs4==0.0.1
pandas==1.1.5
lxml==4.6.1
matplotlib==3.3.2
requests==2.24.0
numpy==1.19.2
pytesseract==0.3.7
gevent-websocket==0.10.1
APScheduler==3.6.3
tornado==5.1.1
zenlog==1.1
protobuf==3.14.0
motor==2.3.0
retrying==1.3.3
seaborn==0.11.1
attrs==20.3.0
pyconvert==0.6.3
demjson==2.2.4
janus==0.4.0
pyecharts-snapshot==0.2.0
async-timeout==3.0.1
ipython==7.19.0
numba==0.51.2
websocket-client==0.57.0
tushare==1.2.62
statsmodels==0.12.1
scipy==1.5.2
pytdx==1.67  #这里不需要改，需要安装群里的whl文件
delegator.py==0.1.1
Flask==1.1.2
pyecharts==1.9.0
six==1.15.0
```

如果装过之前老的requirement.txt，卸载就行了

pip uninstall -r requirements.txt

pip install -r requirements.txt -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

然后启动quantaxis/QUANTAXIS/**main**.py

输入 save stock_block

报错如下：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348768.jpg)

从群里下载pytdx-1.72r2-py3-none-any.whl

pip install pytdx-1.72r2-py3-none-any.whl

即可解决上面的问题

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348921.jpg)

保存成功

 不容易啊 。研究了3天了。。