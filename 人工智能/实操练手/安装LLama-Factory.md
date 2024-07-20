在[Llama3-Chinese-8B-Instruct实操练手](note://WEB3c534b7bdc377e3dc66b79202a484f73)的环境中继续安装

参考[https://github.com/hiyouga/LLaMA-Factory](https://github.com/hiyouga/LLaMA-Factory)

```
git clone --depth 1 
cd LLaMA-Factory
pip install -e ".[metrics]"   # 官方文档是pip install -e ".[torch,metrics]"，但是torch，在环境中已经安装
```

启动webui

```
llamafactory-cli webui
```