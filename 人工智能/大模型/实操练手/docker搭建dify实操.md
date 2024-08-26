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
