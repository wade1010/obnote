### 原因
文件句柄数不够，需要调高ulimit 里面的-n 的值。

### 排查
终端输入下面命令
```
ulimit -a
```
得到结果如下
![image](https://gitee.com/hxc8/images7/raw/master/img/202407190802847.jpg)

一般默认是256

### 解决办法
- 在用户目录下的.bash_profile中的末尾加入ulimit -n 10240
- 保存后再执行 source ～/.bash_profile 即可


#### 可能出现的问题
假如设置的数字特别大，会提示下面的错误

```
ulimit:124: setrlimit failed: invalid argument
```

使用下面两个命令看下系统最大数
```
sysctl kern.maxfiles
```

```
sysctl kern.maxfilesperproc
```

假如系统最大数字不满足你的要求，就修改下系统最大数，命令如下(数字自己设定，但是不要太大，有的电脑系统貌似有限定)：

```
sysctl -w kern.maxfiles=65536
```

```
sysctl -w kern.maxfilesperproc=65536
```
然后再设置 ulimit 即可
