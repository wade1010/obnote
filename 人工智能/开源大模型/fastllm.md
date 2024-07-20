[https://github.com/ztxz16/fastllm](https://github.com/ztxz16/fastllm)

docker 运行需要本地安装好 NVIDIA Runtime,且修改默认 runtime 为 nvidia

1. 安装 nvidia-container-runtime

```
sudo apt-get install nvidia-container-runtime

```

1. 修改 docker 默认 runtime 为 nvidia

/etc/docker/daemon.json

```
Explain{
  "registry-mirrors": [
    "
    "
  ],
  "runtimes": {
      "nvidia": {
          "path": "/usr/bin/nvidia-container-runtime",
          "runtimeArgs": []
      }
   },
   "default-runtime": "nvidia" // 有这一行即可
}

```

[运行](https://github.com/ztxz16/fastllm#%E8%BF%90%E8%A1%8C)

1. 在Android设备上安装termux软件 

1. 在termux中执行termux-setup-storage获得读取手机文件的权限。

1. 将NDK编译出的main文件，以及模型文件存入手机，并拷贝到termux的根目录。

我这里操作是，通过电脑把main文件放到如下图目录下

![](https://gitee.com/hxc8/images2/raw/master/img/202407172159699.jpg)

然后打开termux，cd storage/dcim

mv main ~

cd ~

1. 使用命令

chmod 777 main

1. 然后可以运行main文件，参数格式参见

./main --help

[推理速度](https://github.com/ztxz16/fastllm/blob/master/docs/benchmark.md#%E6%8E%A8%E7%90%86%E9%80%9F%E5%BA%A6)

可以使用benchmark程序进行测速，根据不同配置、不同输入，推理速度也会有一些差别

例如:

```
./benchmark -p ~/chatglm-6b-int4.flm -f ../example/benchmark/prompts/beijing.txt -b 1
./benchmark -p ~/chatglm-6b-int8.flm -f ../example/benchmark/prompts/beijing.txt -b 1
./benchmark -p ~/chatglm-6b-fp16.flm -f ../example/benchmark/prompts/hello.txt -b 512 -l 18
```

| 模型 | Data精度 | 平台 | Batch | 最大推理速度(token / s) | 
| -- | -- | -- | -- | -- |
| ChatGLM-6b-int4 | float32 | RTX 4090 | 1 | 176 | 
| ChatGLM-6b-int8 | float32 | RTX 4090 | 1 | 121 | 
| ChatGLM-6b-fp16 | float32 | RTX 4090 | 64 | 2919 | 
| ChatGLM-6b-fp16 | float32 | RTX 4090 | 256 | 7871 | 
| ChatGLM-6b-fp16 | float32 | RTX 4090 | 512 | 10209 | 
| ChatGLM-6b-int4 | float32 | Xiaomi 10 Pro - 4 Threads | 1 | 4 ~ 5 | 
