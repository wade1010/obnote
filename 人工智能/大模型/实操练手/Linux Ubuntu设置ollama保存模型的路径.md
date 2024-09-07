下载模型之前可以修改下模型默认保存路径。
## 设置ollama保存模型的路径
```bash
sudo mkdir /path/to/ollama/models
sudo chown -R 777 /path/to/ollama/models
sudo vi /etc/systemd/system/ollama.service
```
在[Service]下面添加相应的环境变量Environment，包括OLLAMA_HOST和OLLAMA_MODELS

```bash
Environment="OLLAMA_MODELS=/path/to/ollama/models" 
Environment="OLLAMA_HOST=0.0.0.0:11434"
```
通过上述设置，以后ollama pull命令拉取到的模型都保存在/path/to/ollama/models，而ollama run命令运行的模型都可以在地址0.0.0.0:11434被访问。修改后的ollama.service完整内容如下：

```
[Unit]
Description=Ollama Service
After=network-online.target

[Service]
ExecStart=/usr/local/bin/ollama serve
User=ollama
Group=ollama
Restart=always
RestartSec=3
Environment=xxxxxxxxxxx这一行就不复制了
Environment="OLLAMA_MODELS=/path/to/ollama/models"
Environment="OLLAMA_HOST=0.0.0.0:11434"

[Install]
WantedBy=default.target
```
最后，sudo执行以下两个命令以使设置生效并重启ollama：
```bash
sudo systemctl daemon-reload 
sudo systemctl restart ollama
```

上面修改来源参考来自 知乎 南门子 的内容


PS:上面修改方式不支持用snap这种方式去安装的ollama。
之前折腾环境的时候，不小心删除了/usr/share/ollama这个目录，这个也是需要的