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

# 模型加载
我使用之前搭建好的llama factory加载
选好模型名称和填好模型在服务器上地址即可
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408191327543.png)
