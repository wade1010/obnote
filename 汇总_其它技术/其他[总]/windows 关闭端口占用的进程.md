windows 关闭端口占用的进程

第一步：查看端口占用情况

```
netstat -ano | findstr 端口号
```

第二步：查看进程占用的进程名称 （可有可无）

```
tasklist | findstr 进程号
```

第三步：关闭

```
taskkill -PID 进程号 -F
```

实际操作过程直接用第一步和第三步就行了，第二步可以忽略