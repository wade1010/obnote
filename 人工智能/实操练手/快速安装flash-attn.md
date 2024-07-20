[https://github.com/Dao-AILab/flash-attention](https://github.com/Dao-AILab/flash-attention)

```
pip install ninja   
MAX_JOBS=64 pip install -v flash-attn --no-build-isolation
```

不安装ninja，MAX_JOBS不起作用

MAX_JOBS根据自己硬件配置来设置

![](https://gitee.com/hxc8/images0/raw/master/img/202407172038417.jpg)

经过10分钟不到，编译成功

![](https://gitee.com/hxc8/images0/raw/master/img/202407172038786.jpg)