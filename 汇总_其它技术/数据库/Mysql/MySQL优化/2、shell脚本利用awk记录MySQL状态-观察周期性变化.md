Awk脚本

Awk是一个简便的直译式的文本处理工具.擅长处理--多行多列的数据

处理过程: 


```
While(还有下一行) {
	1:读取下一行,并把下一行赋给$0,各列赋给$1,$2...$N变量
        2: 用指定的命令来处理该行
}
```

如何处理1行数据?

答: 分2部分,   pattern (条件)  + action(处理动作)

第1个简单awk脚本

// 把xx.txt的每一行进行输出


```
awk  ‘{printf “%s\n” , $1}’ xx.txt
```


第2个简单awk脚本 统计mysql服务器信息


```
mysqladmin -uroot ext|awk '/Queries/{q=$4}/Threads_connected/{c=$4}/Threads_running/{r=$4}END{printf("%d %d %d\n",q,c,r)}' >> statistics.txt
```

### 计算每秒多少个查询

>  awk '{q=$1-last;last=$1}{printf("%d %d %d\n",q,$2,$3)}' statistics.txt

### shell 脚本

```
#! /bin/bash
while true
do
mysqladmin -uroot ext|awk '/Queries/{q=$4}/Threads_connected/{c=$4}/Threads_running/{r=$4}END{printf("%d %d %d\n",q,c,r)}' >> statistics.txt

sleep 1
done
```


```
147 1 1
2 1 1
2 1 1
2 1 1
2 1 1
```

去掉无效数据，如第一行


```
2 1 1
2 1 1
2 1 1
2 1 1
```

空格替换成制表符 \t

打开Excel，粘贴数据进去

生成图表 如折线图  如下图

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190811662.jpg)



这种有周期性的波动 可能是缓存层面的问题

如 缓存集中失效

缓存时间 在一个时间段随即失效