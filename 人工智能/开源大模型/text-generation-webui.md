它是一个用于运行 GPT-J 6B、OPT、GALACTICA、LLaMA 和 Pygmalion 等大型语言模型的 gradio web UI。其目标是成为文本生成领域的AUTOMATIC1111/stable-diffusion-webui

完成各种的本地大模型部署、微调训练等

只要是huggingface上的model，都可以做这个加载启动。

pip3 install torch torchvision torchaudio -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

git clone [https://github.com/oobabooga/text-generation-webui](https://github.com/oobabooga/text-generation-webui)

cd text-generation-webui/

pip install -r requirements.txt -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

```
python server.py
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172159125.jpg)

pip install httpx[socks]

```
python server.py
```

python server.py --listen  (服务器运行的话，得加--listen，要不然默认监听的是127.0.0.1)

prompt使用示例：

```
[INST] 
<<SYS>>
You are a helpful, respectful and honest assistant. Always answer as helpfully as possible, while being safe.  Your answers should not include any harmful, unethical, racist, sexist, toxic, dangerous, or illegal content. Please ensure that your responses are socially unbiased and positive in nature. The answer always been translate into Chinese language.

If a question does not make any sense, or is not factually coherent, explain why instead of answering something not correct. If you don't know the answer to a question, please don't share false information.

The answer always been translate into Chinese language.
<</SYS>>

列出5种可以改善睡眠质量的方法
[/INST]
```