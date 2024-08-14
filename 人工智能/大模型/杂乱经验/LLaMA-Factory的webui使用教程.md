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
### 自定义数据集

数据集的格式要求在不同的阶段是不同的，本教程以sft阶段的数据集需求，将以系统自带的identity数据集和将自定义的一个商品文案生成数据集为例，介绍数据集的使用。更多详情可以在[https://github.com/hiyouga/LLaMA-Factory/blob/main/data/README_zh.md](https://github.com/hiyouga/LLaMA-Factory/blob/main/data/README_zh.md) 中找到相关解释。

系统目前支持 alpaca 和sharegpt两种数据格式，以alpaca为例，整个数据集是一个json对象的list，具体数据格式为

```json
[  
  {  
    "instruction": "用户指令（必填）",  
    "input": "用户输入（选填）",  
    "output": "模型回答（必填）",  
    "system": "系统提示词（选填）",  
    "history": [  
      ["第一轮指令（选填）", "第一轮回答（选填）"],  
      ["第二轮指令（选填）", "第二轮回答（选填）"]  
    ]  
  }  
]


```
例子比如单轮

```json
{  
  "instruction": "写一个有效的比较语句",  
  "input": "篮球和足球",  
  "output": "篮球和足球都是受欢迎的运动。"  
}


```

和多轮

```json
{  
  "instruction": "谢谢",  
  "input": "",  
  "output": "不用谢! 很高兴我提供的信息能够帮助到你! 如果还有什么其他问题也可以向我提问。",  
  "history": [  
    [  
      "请你给我写一个面试准备计划，我想要去面试微软的程序员岗位",  
      "首先，你可以去微软官网寻找招聘信息并申请面试。\n其次，您可以在社交媒体平台寻找微软公司对程序员的面试问题，并做好准备。\n最后，您可以自己对面试过程进行模拟，熟悉话题并减少紧张感。\n我希望你能面试成功。"  
    ]  
  ]  
}


```

所以我们训练的数据最好也转换成上述这种格式，然后在 data/dataset_info.json中进行注册

想将该自定义数据集放到我们的系统中使用，则需要进行如下几步操作

进入到项目根目录，输入下面命令

```bash
cd /root/workspace/LLaMA-Factory

```
复制该数据集到当前目录下面的data 目录下

然后修改 data/dataset_info.json 新加内容完成注册
### 示例如下
加入自定义数据集之前截图如下：![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141505053.png)

我创建了一个test.json的数据集，然后传到/root/workspace/LLaMA-Factory/data目录下，如下图
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141506147.png)

然后修改dataset_info.json，输入下面命令

```bash
vim dataset_info.json

```
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141506939.png)
my_test是自定义的名称，将在UI界面显示的名字，file_name填入文件名称，这里是test.json

然后保存即可。

验证数据集是否添加，回到UI页面，刷新下，查看数据集是否增加
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141506048.png)

验证数据集是否合法：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141506029.png)
能预览即表明合法
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141506047.png)
## 训练
如下图，确保左上角是Train这个tab，然后点击开始即可。这个过程比较漫长，等待即可。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141507355.png)
## 使用模型
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141507703.png)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141507184.png)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408141507804.png)
