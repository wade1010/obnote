

1. 准备工作

1. 下载代码 git clone git@gitlab.com:xhcheng/docker_hello.git

1. 下载不了的就按下面步骤创建

1. touch Dockerfile

1. 在 Dockerfile 中输入下面的内容                                                                    # Dockerfile

# 使用 Python 运行时作为基础镜像

FROM python:2.7-slim



# 设置 /app 为工作路径

WORKDIR /app



# 将当前目录所有内容复制到容器的 /app 目录下

ADD . /app



# 安装 requirements.txt 中指定的包

RUN pip install --trusted-host pypi.python.org -r requirements.txt



# 对容器外开放80端口

EXPOSE 80



# 定义环境变量

ENV NAME World



# 当容器启动时运行 app.py 

CMD ["python", "app.py"]

1. touch requirements.txt

1. 在  requirements.txt 中输入下面的内容  Flask
Redis

1. touch app.py

1. 在 app.py 中输入下面的内容    from flask import Flask

from redis import Redis, RedisError

import os

import socket



# Connect to Redis

redis = Redis(host="redis", db=0, socket_connect_timeout=2, socket_timeout=2)



app = Flask(__name__)



@app.route("/")

def hello():

    try:

        visits = redis.incr("counter")

    except RedisError:

        visits = "<i>cannot connect to Redis, counter disabled</i>"



    html = "<h3>Hello {name}!</h3>" \

           "<b>Hostname:</b> {hostname}<br/>" \

           "<b>Visits:</b> {visits}"

    return html.format(name=os.getenv("NAME", "world"), hostname=socket.gethostname(), visits=visits)



if __name__ == "__main__":

    app.run(host='0.0.0.0', port=80)  

1. 开始构建镜像

1. docker build -t docker_hello .

1. 值得注意的是上下文路径这个概念。在本例中我们用 . 定义，那么什么是上下文环境呢？这里我们需要了解整个 build 的工作原理。

1. Docker 在运行时分为 Docker 引擎（也就是服务端守护进程）和客户端工具。Docker 的引擎提供了一组 REST API，被称为 Docker Remote API，而如 docker 命令这样的客户端工具，则是通过这组 API 与 Docker 引擎交互，从而完成各种功能。因此，虽然表面上我们好像是在本机执行各种 docker 功能，但实际上，一切都是使用的远程调用形式在服务端（Docker 引擎）完成。

1. 构建完成后，docker images 查看

1. 运行镜像

1. docker run -p 4000:80  docker_hello

1. 浏览器查看 http://192.168.1.39:4000/

1. 或者curl 查看  curl http://192.168.1.39:4000

1. # 输出结果

<h3>Hello World!</h3><b>Hostname:</b> 98750b60e766<br/><b>Visits:</b> <i>cannot connect to Redis, counter disabled</i>

1. 发布镜像

1. 如果你已经登录docker  （使用命令 docker login）

1. 就使用 docker push username/repository:tag

1. 我这里是用本地仓库

1. docker tag 193d4bebc5d7 192.168.1.39:5000/docker_hello

1. docker push 192.168.1.39:5000/docker_hello

1. 发布完成后，可以在任意一台 docker 环境的机器上运行该镜像

1. 在另外一台机器上

1. docker run -p 4001:80 192.168.1.39:5000/docker_hello

