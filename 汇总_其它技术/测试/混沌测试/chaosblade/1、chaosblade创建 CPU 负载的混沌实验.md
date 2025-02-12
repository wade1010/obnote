实现原理

利用消耗CPU时间片来做



```javascript
# 创建 cpu 满载实验

blade create cpu fullload

# 返回结果如下

{"code":200,"success":true,"result":"6fa04946baf42920"}

# code 的值等于 200 说明执行成功，其中 result 的值就是 uid。使用 top 命令验证实验效果

PID USER      PR  NI    VIRT    RES    SHR S  %CPU %MEM     TIME+ COMMAND                                           
12203 root      20   0  109768   7568      4 R 199.0  0.4   2:59.63 chaos_burncpu

```





blade create cpu load [flags]



参数

--timeout string   设定运行时长，单位是秒，通用参数

--cpu-count string     指定 CPU 满载的个数

--cpu-list string      指定 CPU 满载的具体核，核索引从 0 开始 (0-3 or 1,3)

--cpu-percent string   指定 CPU 负载百分比，取值在 0-100





```javascript
# 创建 CPU 满载实验
blade create cpu load

# 返回结果如下
{"code":200,"success":true,"result":"beeaaf3a7007031d"}

# code 的值等于 200 说明执行成功，其中 result 的值就是 uid。使用 top 命令验证实验效果
Tasks: 100 total,   2 running,  98 sleeping,   0 stopped,   0 zombie
%Cpu0  : 21.3 us, 78.7 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu1  : 20.9 us, 79.1 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu2  : 20.5 us, 79.5 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu3  : 20.9 us, 79.1 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st

# 4 核都满载，实验生效，销毁实验
blade destroy beeaaf3a7007031d

# 返回结果如下
{"code":200,"success":true,"result":"command: cpu load --help false --debug false"}

# 指定随机两个核满载
blade create cpu load --cpu-count 2

# 使用 top 命令验证结果如下，实验生效
Tasks: 100 total,   2 running,  98 sleeping,   0 stopped,   0 zombie
%Cpu0  : 17.9 us, 75.1 sy,  0.0 ni,  7.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu1  :  3.0 us,  6.7 sy,  0.0 ni, 90.3 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu2  :  0.7 us,  0.7 sy,  0.0 ni, 98.7 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu3  : 19.7 us, 80.3 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st

# 指定索引是 0，3 的核满载，核的索引从 0 开始
blade create cpu load --cpu-list 0,3

# 使用 top 命令验证结果如下，实验生效
Tasks: 101 total,   2 running,  99 sleeping,   0 stopped,   0 zombie
%Cpu0  : 23.5 us, 76.5 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu1  :  0.0 us,  0.0 sy,  0.0 ni,100.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu2  :  0.0 us,  0.0 sy,  0.0 ni,100.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu3  : 20.9 us, 79.1 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st

# 指定索引 1 到 3 的核满载
blade create cpu load --cpu-list 1-3

Tasks: 102 total,   4 running,  98 sleeping,   0 stopped,   0 zombie
%Cpu0  :  2.4 us,  7.1 sy,  0.0 ni, 90.2 id,  0.0 wa,  0.0 hi,  0.3 si,  0.0 st
%Cpu1  : 20.0 us, 80.0 sy,  0.0 ni,  0.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu2  : 15.7 us, 78.7 sy,  0.0 ni,  5.7 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
%Cpu3  : 19.1 us, 78.9 sy,  0.0 ni,  2.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st

# 指定百分比负载
blade create cpu load --cpu-percent 60

# 可以看到 CPU 总的使用率达到 60%， 空闲 40%
Tasks: 100 total,   1 running,  99 sleeping,   0 stopped,   0 zombie
%Cpu(s): 15.8 us, 44.1 sy,  0.0 ni, 40.0 id,  0.0 wa,  0.0 hi,  0.1 si,  0.0 st
```

