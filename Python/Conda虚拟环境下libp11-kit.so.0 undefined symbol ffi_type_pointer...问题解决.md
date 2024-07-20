[https://blog.csdn.net/qq_38606680/article/details/129118491](https://blog.csdn.net/qq_38606680/article/details/129118491)

1 背景说明

最近需要在Conda虚拟环境下运行ROS相关代码，其中在运行代码import moveit_commander时，返回报错ImportError: /lib/x86_64-linux-gnu/libp11-kit.so.0: undefined symbol: ffi_type_pointer, version LIBFFI_BASE_7.0。由于在网上没有找到和我一样的问题，且在github上也没找到解决方法，以为是个例就没打算记录，后来在与同学交流过程中发现，他们在conda虚拟环境中使用ROS时，均出现这种错误，故此记录错误并提出一种解决方法，希望能够对解决此类问题有所帮助。

2 报错原因

可以看到，我的报错来源是我在python中import moveit_commander，但是可能其他命令也会报出该错误，但是报错原因均来自libp11-kit.so.0: undefined symbol: ffi_type_pointer, version LIBFFI_BASE_7.0，结合github上相关问题的讨论，理解过来意思大概就是libffi的版本不一致，导致了libp11-kit.so.0在使用时出现了未定义符号问题。其实可以推到以后出现同类型问题，解决方法也应该基本类似。

3 解决方法

打开至conda虚拟环境下lib文件夹中，路径为/home/anaconda3/envs/xxx/lib，在文件夹内启动终端，输入命令ls -l，获得结果如图所是。

这里，由于我已经修改了链接，所以可能会有所不同。可以看到，你的libffi.so.7链接至libffi.so.8.1.0，所以，这也就是为什么会在程序中，libffi报版本错误了。找到原因，解决方法也很简单，我这边选择的方式是将该路径下的libffi.so.7文件备份后（重命名为libffi_bak.so.7），再在该路径下创建一个新的libffi.so.7链接至/lib/x86_64-linux-gnu/libffi.so.7.1.0，即输入命令：

sudo mv libffi.so.7 libffi_bak.so.7

sudo ln -s /lib/x86_64-linux-gnu/libffi.so.7.1.0 libffi.so.7

sudo ldconfig

至此，再次运行程序，问题应该就解决了。

4 补充说明

上述过程解决问题后，师弟秉持打破沙锅问到底的精神，一直在追究为什么会出现这种情况。。。后来，找到问题所在：原来是Python 3.8.16版本在安装过程中，就会默认安装libffi-3.4.2，在该库中，就会出现旧版本兼容老版本问题，即出现libffi.so.7链接至libffi.so.8.1.0，进而产生报错。而在python3.8.10中，默认安装libffi-3.3版本，在该版本内，libffi.so.7链接至libffi.so.7.1.0，就不会产生上述问题。因此，另一种解决方式即为安装python 3.8.10，同样能解决该问题。

————————————————

版权声明：本文为CSDN博主「Destinycjk」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/qq_38606680/article/details/129118491](https://blog.csdn.net/qq_38606680/article/details/129118491)