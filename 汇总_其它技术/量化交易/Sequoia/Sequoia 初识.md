[https://github.com/sngyai/Sequoia](https://github.com/sngyai/Sequoia)

```
# 操作示例
# 1. 创建专属 python 环境
conda create -n sequoia
conda activate sequoia

```

写笔记的时间，requirements.txt是有问题的

```
PyYAML==5.4
requests==2.31.0
pandas==1.5.2
numpy==1.23.5
xlrd==1.2.0
TA-Lib==0.4.25
tables==3.7.0
# install schedule 可能遇到的问题及 fix 见：https://github.com/sngyai/Sequoia/issues/26
schedule==0.6.0
wxpusher==2.2.0
pytest==7.2.0
akshare==1.8.26
```

修改后

```
PyYAML==5.4
requests==2.30.0
pandas==1.5.2
numpy==1.23.5
xlrd==1.2.0
tables==3.7.0
schedule==0.6.0
wxpusher==2.2.0
pytest==7.2.0
akshare
```

版本 requests==2.30.0

中文删掉

akshare版本去掉

ta-lib用下面的安装 

[TA_Lib-0.4.24-cp310-cp310-win_amd64.whl](attachments/WEBRESOURCEb6960b13dcc76293a7c676b4f88199adTA_Lib-0.4.24-cp310-cp310-win_amd64.whl)

pip install TA_Lib-0.4.24-cp310-cp310-win_amd64.whl  或者从这里下载对应的版本[https://www.lfd.uci.edu/~gohlke/pythonlibs/#ta-lib/](https://www.lfd.uci.edu/~gohlke/pythonlibs/#ta-lib/)

```
# 3. 验证是否安装成功
python -c "import talib; print(talib.__version__)"
```

```
pip install -r requirements.txt 
```

```
pip install akshare --upgrade
```

```
cp config.yaml.example config.yaml

```

创建logs目录

python main.py