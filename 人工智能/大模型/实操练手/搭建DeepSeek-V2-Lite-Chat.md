纯简单记录

#  下载模型
vim download.py

```python
from modelscope.hub.snapshot_download import snapshot_download

model_dir1 = snapshot_download('deepseek-ai/DeepSeek-V2-Lite-Chat', cache_dir='/zzzz/xxx/yyyy')  # cache_dir填下你想下载到的目标目录

```

python download.py执行下载，下载流程如下，
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191323160.png)

源文件地址：https://huggingface.co/deepseek-ai/DeepSeek-V2-Lite-Chat/tree/main
大概37GB左右大小
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191326511.png)

# 模型加载 llama factory
我使用之前搭建好的llama factory加载
选好模型名称和填好模型在服务器上地址即可
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191327543.png)
![屏幕录制 2024-08-19 133427.gif](https://gitee.com/hxc8/images10/raw/master/img/202408191342187.gif)
fp16显存大概占用37GB左右
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191345911.png)


# 模型加载text-generation-webui
将 模型目录DeepSeek-V2-Lite-Chat移动到text-generation-webui的根目录下的models下面

然后启动text-generation-webui

```shell
CUDA_VISIBLE_DEVICES=3 python server.py --listen --trust-remote-code
```

然后浏览器打开 http://yourip:7860
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191643697.png)
然后切换到Chat的tab页面，使用如下
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191645494.png)
此时显卡所占显存如下
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191751574.png)
