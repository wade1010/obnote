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
注意：我自己本地已经有一个80端口的进程了，而这个dify里面的nginx默认也是80，这里给它改成180

```shell
sudo vim .env
```
将里面的EXPOSE_NGINX_PORT默认80改成180，然后保存
```shell
sudo vim docker-compose.yaml
```
将里面的EXPOSE_NGINX_PORT默认80改成180，然后保存

