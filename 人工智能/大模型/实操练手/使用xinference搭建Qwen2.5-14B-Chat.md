这里是使用下面的docker部署启动
https://github.com/chatchat-space/Langchain-Chatchat/blob/master/docker/docker-compose.yaml

这个Qwen2.5-14B-Chat在服务器上已经下载好，所以需要将目录映射到容器里面

所以我在xinference的容器配置里面增加了一个目录映射


```
xinference:
    image: xprobe/xinference:v0.12.3
    restart: always
    command: xinference-local -H 0.0.0.0
    # ports: # 不使用 host network 时可打开.
    #  - "9997:9997"
    network_mode: "host"
    # 将本地路径(~/xinference)挂载到容器路径(/root/.xinference)中,
    # 详情见: https://inference.readthedocs.io/zh-cn/latest/getting_started/using_docker_image.html
    volumes:
      - ~/xinference:/root/.xinference
      # - ~/xinference/cache/huggingface:/root/.cache/huggingface
      # - ~/xinference/cache/modelscope:/root/.cache/modelscope
      - /你的模型目录:/容器内目录  示例如下
      - /yourllmdir/Qwen2.5-14B-Instruct:/dockerllmdir/Qwen2.5-14B-Instruct
```

启动容器后，打开xinference

http://192.168.100.13:9997

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202411191136306.png)

启动自定义模型
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202411191137992.png)


![image.png](https://gitee.com/hxc8/images10/raw/master/img/202411191137124.png)



在oneapi中使用改模型

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202411191139411.png)
