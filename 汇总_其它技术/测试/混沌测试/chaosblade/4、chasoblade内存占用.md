实现原理

ram 模式采用代码申请内存实现 

cache 模式采用 dd、mount 命令实现，挂载 tmpfs 并且进行文件填充



blade create mem load



参数



--mem-percent string    内存使用率，取值是 0 到 100 的整数

--mode string   内存占用模式，有 ram 和 cache 两种，例如 --mode ram。ram 采用代码实现，可控制占用速率，优先推荐此模式；cache 是通过挂载tmpfs实现；默认值是 --mode cache

--reserve string    保留内存的大小，单位是MB，如果 mem-percent 参数存在，则优先使用 mem-percent 参数

--rate string 内存占用速率，单位是 MB/S，仅在 --mode ram 时生效

--timeout string   设定运行时长，单位是秒，通用参数



```javascript
# 在执行命令之前，先使用 top 命令查看内存使用信息，如下，总内存大小是 8G，使用了 7.6%
KiB Mem :  7.6/8010196  

# 执行内存占用 50%
blade c mem load --mode ram --mem-percent 50

# 查看内存使用
KiB Mem : 50.0/8010196 

# 执行内存占用 100%
KiB Mem : 99.6/8010196

# 保留 200M 内存，总内存大小 1G
blade c mem load --mode ram --reserve 200 --rate 100
KiB Mem :  1014744 total,    78368 free,   663660 used,   272716 buff/cache
KiB Swap:        0 total,        0 free,        0 used.   209652 avail Mem
KiB Mem : 79.7/1014744  [||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||                   ]
```

