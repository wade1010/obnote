尝试找了一些应用层，主要是模型下载和模型加载使用

# open-webui
我是使用pip来安装的，这里有个小坑，windows上使用pip安装这个软件，需要python3.11(操作记录时间为2024-9-6日)，而我最开始conda创建环境的时候，没指定python版本，默认装了3.12，然后发现装不了open-webui

操作记录

```shell
conda create -n localai python=3.11
conda activate localai
pip install  open-webui
```
