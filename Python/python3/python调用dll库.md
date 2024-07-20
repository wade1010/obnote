python调用dll库

## 为什么需要调用动态链接库

有大量优秀的库，直接以本地库（机器代码）提供的，这些库基本都是c/c++语言 编写的。

通常要调用它们，需要使用 c/c++ 语言。

如果你的应用，已经用python开发了，能不能用python直接调用这些库，使用这些库的功能呢？

当然可以。 python语言的官方解释器 CPython 本身就是用C语言开发的，可以很方便的从python代码中调用动态链接库。

动态链接库，在不同的操作系统（Windows、Linux），文件格式不同，但是python调用它们的 方法都差不多。

我们这里以Windows平台的dll为例。

参考 [https://docs.python.org/3/library/ctypes.html](https://docs.python.org/3/library/ctypes.html)

## 一个例子

点击这里下载 [https://cdn2.byhy.net/files/py/etc/Dll1.dll](https://cdn2.byhy.net/files/py/etc/Dll1.dll)

动态链接库中有这样的函数

```c
void SayHello()
{
    MessageBox(NULL, 
                TEXT("白月黑羽向您问好~~~"),
                TEXT("白月黑羽培训专用"), 
                MB_OK);
}

```

使用如下代码加载dll ，并且调用函数 SayHello

```
from  ctypes import *

# Load DLL into memory.
lib  = CDLL ("e:\\Dll1.dll")
lib.SayHello()

```

## 有参数

```
from  ctypes import *

# Load DLL into memory.

lib  = CDLL ("e:\\Dll1.dll")

lib.StrAppend('白月黑羽')

ret = lib.IntAdd(3,4)
print(ret)


```

通过python可以很方便的使用不同参数调用，无须编译运行。

如果是测试，甚至比直接用c语言写测试代码方便。

上面的 IntAdd 和 StrAppend， 我们直接使用了Python对象作为参数传入。

实际上，底层的ctypes库调用c语言库，不能直接传递python对象的，需要转化为c语言接口对应的类型。

它会根据我们使用的python对象类型，猜测应该转化为什么类型的数据。

Python数据对象和C语言的数据对象的对应关系见 [https://docs.python.org/3/library/ctypes.html#fundamental-data-types](https://docs.python.org/3/library/ctypes.html#fundamental-data-types)

特别要注意 w_char 和 char 的区别，前者对应python 3中的 字符串， 后者对应 字节串

## 指定参数类型

前面的示例，我们直接使用了Python对象作为参数传入。

好像也没有什么问题。

但是建议大家最好还是直接告诉 ctypes 你的参数类型。

特别是从python 对象类型 不能唯一对应c语言类型的。比如 char short int long 这些。

否则可能带来意想不到的问题。参考 [https://stackoverflow.com/questions/24377845/ctype-why-specify-argtypes](https://stackoverflow.com/questions/24377845/ctype-why-specify-argtypes)

怎么告诉呢？

```
from  ctypes import *

# Load DLL into memory.

lib  = CDLL ("e:\\Dll1.dll")

lib.StrAppend.argtypes = [c_wchar_p]
lib.StrAppend('白月黑羽')

```

也可以

```
lib.StrAppend(c_wchar_p('白月黑羽'))

```

## 指定返回值类型

看看下面的例子

```
from  ctypes import *

lib  = CDLL ("e:\\Dll1.dll")

ret = lib.IntAdd(3,4)
print(ret)
ret2 = lib.StrAppend('白月黑羽')
print(ret2)

```

第1个是对的，第2个怎么是数字？

返回类型，ctypes 缺省认为是 C int 类型

不是int 类型的，可以通过 function 对象的restype 属性指定

```
from  ctypes import *

# Load DLL into memory.

lib  = CDLL ("e:\\Dll1.dll")

ret = lib.IntAdd(3,4)
print(ret)

lib.StrAppend.restype = c_wchar_p
ret2 = lib.StrAppend('白月黑羽')
print(ret2)

lib.BytesAppend.restype = c_char_p
ret3 = lib.BytesAppend(b'\x39\x99')
print(ret3)


```

## 复合类型参数

参考

[https://stackoverflow.com/questions/4351721/python-ctypes-passing-a-struct-to-a-function-as-a-pointer-to-get-back-data](https://stackoverflow.com/questions/4351721/python-ctypes-passing-a-struct-to-a-function-as-a-pointer-to-get-back-data)

## 导出 c++ 函数

由于 c++ 对符号名的魔改，c++ 语法编译的函数，直接ctypes调用会发现找不到函数

要调用怎么办？

一种方法是加上 extern "C" 的说明，重新编译动态链接库。

```c
extern "C" __declspec(dllexport) void SayHello();

```

如果没有源代码，可以通过工具（比如 dllexp ）找到 这个dll 导出的 c++ 修改后函数名字，然后调用它

参考 [https://stackoverflow.com/questions/21184911/c-dll-called-from-python](https://stackoverflow.com/questions/21184911/c-dll-called-from-python)