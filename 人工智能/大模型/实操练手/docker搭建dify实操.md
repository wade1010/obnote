# 拉取代码

```git
git clone --depth 1 https://github.com/langgenius/dify.git
```

# docker部署

```shell
cd dify/docker
cp .env.example .env
docker compose up -d   如报错找不到命令则用 docker-compose up -d
```
注意：我自己本地已经有一个80端口的进程了，而这个dify里面的nginx默认也是80，这里给它改成180，不改的话，直接跳过改步骤

```shell
sudo vim .env
```
将里面的EXPOSE_NGINX_PORT默认80改成180，然后保存
```shell
sudo vim docker-compose.yaml
```
将里面的EXPOSE_NGINX_PORT默认80改成180，然后保存

部署后，docker ps查看结果如下
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261429323.png)
包括 3 个业务服务 api / worker / web，以及 6 个基础组件 weaviate / db / redis / nginx / ssrf_proxy / sandbox 。

# 首次访问安装页面
使用浏览器访问http://your_server_ip:180/install  （这里我改为了180，如果没改就是80，不填也行）
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261435342.png)
首次访问会出现上面的注册页面，填入信息即可。后续访问，查询到已注册，会自动跳转到登录页面，如下：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261436556.png)
# 设置模型供应商
这里我以openai和xinference为例
点击设置进入到设置模型供应商页面
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261439254.png)
然后会出现如下图，我这里设置过了，如果没设置点击"OpenAI",![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261450683.png)
## OpenAI设置
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261450732.png)
然后点击右上角“系统模型设置”,如下图，你会发现，除了rerank模型，其它的从OpenAI都能选，我这里的openai的APIkey只支持gpt-3.5-turbo，所以就选了这个。最后语音和文本互转的，没试过，不知道我的key支不支持，后续测试下。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261452708.png)
## XInference设置
rerank配置，如下，这里我选择了使用XInference，这个我之前搭建过了，详情可以看我其它笔记，很简单。
打开XInference，[http://yourip:9997/ui/#/launch_model/rerank](http://yourip:9997/ui/#/launch_model/rerank),我这里选择了 bge-reranker-v2-m3
我都选择默认的配置，直接点击启动，没一会就能启动，初次需要下载模型，稍微慢点。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261457920.png)
验证是否启动，如下图，表明已经启动成功
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261458546.png)
后续即可回到dify里面继续设置rerank了。服务器URL就填web访问XInference的地址即可。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261501958.png)
然后就可以设置rerank了
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261502631.png)
# 创建知识库
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261528646.png)
