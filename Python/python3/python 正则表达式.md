re.finall 建议使用

```javascript
"""
正则表达式 匹配模式
"""
import re

a = 'PythonC#JavaPhp'

r = re.findall('c#', a, re.I)  # 不区分大小写
print(r)

a = 'PythonC#\nJavaPhp'
r = re.findall('c#.{1}', a, re.I)  # 匹配不到
print(r)

r = re.findall('c#.{1}', a, re.I | re.S)  # 匹配到  | 表示且
print(r)
```



 # 不明白 为什么结果是['Python', 'Python']

```javascript
"""
正则表达式
"""
import re

a = 'PythonPythonPythonPython'

r = re.findall('(Python){1,3}', a)  # 不明白 为什么结果是['Python', 'Python']
print(r)
```



re.sub

```javascript
"""
正则表达式 re.sub 替换 函数逻辑替换
"""
import re


def convert(value):
    matched = value.group()
    if int(matched) >= 6:
        return '9'
    else:
        return '0'


a = 'A8C445D8'
r = re.sub('\d', convert, a)
print(r)
```



其他

```javascript
"""
正则表达式 re.match re.search
"""
import re

a = 'A8C445D8'
# 从字符串开头匹配 匹配到就返回 不会匹配多次
r = re.match('\d', a)
print(r)
# 匹配到第一个就返回  不会匹配多次
r = re.search('\d', a)
print(r)

a = '9A8C445D8'
r = re.match('\d', a)
print(r)
print("------")
r = re.search('\d', a)
print(r)
```





