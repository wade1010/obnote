## 启动
不是每次都需要启动的，目前是关机后需要启动或者访问不了的时候启动下，后期可以改为开机自启动

登录服务器后，依次执行下面命令


```bash
cd LLaMA-Factory
conda activate llamafactory
nohup llamafactory-cli webui > webui.log 2>&1 &

```


验证是否启动成功，输入下面命令


```bash
ps aux|grep -v "grep"|grep llamafactory
```


如果有结果，就表示启动成功。
## 访问方式

使用浏览器打开，[http://YOUR_IP:7860,](http://YOUR_IP:7860,)打开后如下图，可以先把语言改为中文
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141500004.png)
## 训练参数加载
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141500145.png)
载入后，尽量不要修改，除了下面说的数据集

## 训练数据集
测试时可以使用alpaca_zh_demo，如下图，默认的其它的数据集，在点击训练开始按钮后，会从huggingface下载，这个网站国内是被屏蔽的，直接访问是访问不了的，所以就不要选了。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141501657.png)
