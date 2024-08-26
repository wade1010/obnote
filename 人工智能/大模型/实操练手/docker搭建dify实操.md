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
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261532934.png)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408261637137.png)
上图点击文件后，右边会有预览，然后点击 "下一步"
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262008198.png)
如上图，可以点击"前往文档"去看下。
# 创建应用
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262018713.png)
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262031307.png)
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262031759.png)
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262032848.png)
## 修改工作流
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262033906.png)
知识检索链接LLM
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262034641.png)
## 整理节点
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262035107.png)
整理后如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262035125.png)
## 配置知识库
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262037558.png)
选择后如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262038970.png)

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262046120.png)


## 设置LLM
如上面一个步骤一样，点击LLM节点，然后设置上下文，选择result，即为知识检索的结果作为result
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262039240.png)
## 设置prompt
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262042505.png)
设置后，如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262044173.png)
## 发布
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262044866.png)

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262044956.png)
## 调试和预览
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262048390.png)
可以分析工作流
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262050025.png)

追踪示例
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408262051828.png)
