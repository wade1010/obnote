TPU最出名的应用AlphaGo。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172054690.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172054820.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172054112.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055298.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055579.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055787.jpg)

因为平均每条指令时钟周期10-20个时钟周期执行完一条指令，于是就是用了CISC指令

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055976.jpg)

脉动阵列

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055168.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055453.jpg)

每一次运算结果都想脉动一样，把上一次计算结果缓存起来，再进行下一次累积。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055711.jpg)

脉动阵列发明的时候，就觉得我们的数据可不可以不断地累加然后进行计算，于是就变成了一个串行的方式。把PE的计算结果给下一次PE，再传给下一次PE。最终存到我们的memory里面。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055944.jpg)

数据的排布不是完全相同的，输进去的时候，数据要错开，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055641.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055060.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055107.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055248.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055386.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172055459.jpg)