1. ctypes

ctypes 是 Python 自带的一个库，可以用来调用 C/C++ 的动态链接库。使用 ctypes 调用 C++ 代码的步骤如下：

- 编写 C++ 代码，并将其编译成动态链接库（.so 或 .dll 文件）；

- 在 Python 中导入 ctypes 库，并使用 ctypes.cdll.LoadLibrary() 方法加载动态链接库；

- 使用 ctypes 定义 C++ 函数的参数类型和返回值类型，并调用 C++ 函数。

以下是一个简单的例子：

C++ 代码：

```cpp
// add.cpp
extern "C" int add(int a, int b) {
return a + b;
}

```

编译成动态链接库：

```bash
g++ -shared -fPIC add.cpp -o libadd.so

```

Python 代码：

```python
# main.py
import ctypes
# 加载动态链接库
lib = ctypes.cdll.LoadLibrary("./libadd.so")
# 定义函数参数类型和返回值类型
lib.add.argtypes = [ctypes.c_int, ctypes.c_int]
lib.add.restype = ctypes.c_int
# 调用 C++ 函数
result = lib.add(1, 2)
print(result) # 输出 3

```

1. SWIG

SWIG 是一个开源工具，可以将 C/C++ 代码转换为 Python、Java、Ruby 等语言的扩展模块。使用 SWIG 调用 C++ 代码的步骤如下：

- 编写 C++ 代码，并使用 SWIG 生成 Python 扩展模块；

- 在 Python 中导入生成的扩展模块，并调用其中的函数。

以下是一个简单的例子：

C++ 代码：

```cpp
// add.cpp
extern "C" int add(int a, int b) {
return a + b;
}

```

SWIG 接口文件：

```
// add.i
%module add
%{
extern int add(int a, int b);
%}
extern int add(int a, int b);

```

生成 Python 扩展模块：

```bash
swig -python add.i
g++ -shared -fPIC add.cpp add_wrap.cxx -I/usr/include/python3.8/ -o _add.so

```

Python 代码：

```python
# main.py
import add
# 调用 C++ 函数
result = add.add(1, 2)
print(result) # 输出 3

```

1. Boost.Python

Boost.Python 是一个开源库，可以将 C++ 代码转换为 Python 扩展模块。使用 Boost.Python 调用 C++ 代码的步骤如下：

- 编写 C++ 代码，并使用 Boost.Python 将其转换为 Python 扩展模块；

- 在 Python 中导入生成的扩展模块，并调用其中的函数。

以下是一个简单的例子：

C++ 代码：

```cpp
// add.cpp
#include <boost/python.hpp>
int add(int a, int b) {
return a + b;
}
BOOST_PYTHON_MODULE(add) {
using namespace boost::python;
def("add", add);
}

```

```
sudo apt-get install libboost-python-dev   # Ubuntu/Debian
sudo yum install boost-python-devel       # CentOS/Fedora


如果你使用的是Windows系统，可以在Boost官网上下载对应版本的Boost安装包，安装时需要勾选“包括源码”选项。
```

生成 Python 扩展模块：

切记生成的文件要与module同名。

```bash
g++ -shared -fPIC -I/usr/include/python3.9/ add.cpp -lboost_python39 -o add.so

```

Python 代码：

```python
# main.py
import add
# 调用 C++ 函数
result = add.add(1, 2)
print(result) # 输出 3

```