//将标准输出和标准错误通过管道输出到log.txt文件

make && make install 2>&1 | tee log.txt

等价于：

make 2>&1 > log.txt && make install 2>&1 >>  log.txt

&：等同的意思

0：标准输入

1：标准输出

2：标准错误

分解这个组合：“>/dev/null 2>&1” 为五部分。

1：> 代表重定向到哪里，例如：echo "123" > /home/123.txt

2：/dev/null 代表空设备文件

3：2> 表示stderr标准错误

4：& 表示等同于的意思，2>&1，表示2的输出重定向等同于1

5：1 表示stdout标准输出，系统默认值是1，所以">/dev/null"等同于 "1>/dev/null"