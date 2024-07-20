git clone -b 1.10.2 [https://github.com/yutiansut/QUANTAXIS.git](https://github.com/yutiansut/QUANTAXIS.git) quantaxis

使用pycharm打开，并用anaconda创建一个python3.8的环境，pycharm选上这个环境

删掉requirement.txt不必要的包，否则会报错

```
demjson>=2.2.4
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348722.jpg)

删掉后，pycharm里面打开控制台，这个时候也是python3.8的环境

pip install -r requirements.txt -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

#好像没用

pip install -r install_afterrequirements.txt -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple) 

得用群里下载的文件替换

python -m pip install -e .