python 类型暗示

## 什么是类型暗示

Python 3.5 版本开始推出了 type hint 功能。

下面的代码

```
def greeting(name: str) -> str:
    return 'Hello ' + str(name)

```

暗示了 name 参数类型是 str， greeting 函数返回值类型 是 str

当然，Python这种写法只是一种暗示而已，IDE可以根据它检查类型是否一致。

解释器运行时，并不会做检查

比如

```
def greeting(name: str) -> str:
    return 'Hello ' + str(name)

# 传入参数 和暗示的类型不同，但是程序仍然可以运行
greeting(1)
```