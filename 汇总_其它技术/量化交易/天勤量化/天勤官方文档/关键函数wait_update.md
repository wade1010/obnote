[`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 是 TqApi 中最重要的一个函数. 每次调用 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 函数时将发生这些事:

- 实际发出网络数据包. 例如, 策略程序用 insert_order 函数下单, 实际的报单指令是在 insert_order 后调用 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 时发出的
    
- 让正在运行中的后台任务获得动作机会．例如, 策略程序中创建了一个后台调仓任务, 这个任务只会在 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 时发出交易指令
    
- 尝试从服务器接收一个数据包, 并用收到的数据包更新内存中的业务数据截面.
    
- 如果没有收到数据包，则挂起等待，如果要避免长时间挂起，可通过设置 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 中的deadline参数，设置等待截止时间


![image.png](https://gitee.com/hxc8/images10/raw/master/img/202412041620591.png)



因此, TqSdk 要求策略程序必须反复调用 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update"), 才能保证整个程序正常运行. 一般会将 [`wait_update()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.wait_update "tqsdk.TqApi.wait_update") 放在一个循环中反复调用 （注: 若跳出循环，程序结束前需调用 api.close() 释放资源):


```
while True:             #一个循环
    api.wait_update()   #总是调用 wait_update, 当数据有更新时 wait_update 函数返回, 执行下一句
    do_some_thing()     #每当数据有变更时, 执行自己的代码, 然后循环回去再做下一次 wait_update

#注：若跳出循环并运行到程序末尾，在结束运行前需调用 api.close() 函数以关闭天勤接口实例并释放相应资源，请见下文 “一个典型程序的结构”
```

**注: 它是TqApi中最重要的一个函数, 每次调用它时都会发生这些事:**

- 实际发出网络数据包(如行情订阅指令或交易指令等).
    
- 尝试从服务器接收一个数据包, 并用收到的数据包更新内存中的业务数据截面.
    
- 让正在运行中的后台任务获得动作机会(如策略程序创建的后台调仓任务只会在wait_update()时发出交易指令).
    
- 如果没有收到数据包，则挂起等待.