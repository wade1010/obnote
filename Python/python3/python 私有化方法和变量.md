

```javascript
class Student:
    __private_name = 'private name'

    def do_homework(self):
        print("doing homework")
        self.__private_do_homework()

    def __private_do_homework(self):
        print("__private_do_homework")
        print(self.__private_name)

    def __private_do_homework__(self):
        print("__private_do_homework__")
        print(self.__private_name)


student = Student()
# student.__private_do_homework()  # 访问不到 报错
student.__private_do_homework__()  # 访问的到
student.do_homework()  # 访问不到 报错
```





私有化 使用 "__" 开头，但是不要在结尾也加上"__"





加上就跟内置的 __init__   __class__等类似 外部是可以访问的







注意属性的动态赋值，不要搞混了



```javascript
class Student:
    name = ''
    __private_name = 'private name'

    def __init__(self, name):
        self.name = name

    def modify_private_name(self, name):
        self.__private_name = name


student = Student("bob")
student.modify_private_name("bob2")  # 访问不到 报错
print(student.__dict__)  # 去掉上面赋值语句 就会报错

student2 = Student("crab")
student2.__private_name = "bob3" #加上后相当于动态的给student加了一个属性
print(student2.__dict__)  # 打开注释会报错
```



可以通过打印结果来确认

```javascript
{'name': 'bob', '_Student__private_name': 'bob2'}
{'name': 'crab', '__private_name': 'bob3'}
```



系统自动的转换成_Student__private_name





但是其实Python没有严格的私有机制



上面的例子

```javascript
student._Student__private_name='bob333'
print(student.__dict__)
```



外部就能直接修改了



完整代码如下

```javascript
class Student:
    name = ''
    __private_name = 'private name'

    def __init__(self, name):
        self.name = name

    def modify_private_name(self, name):
        self.__private_name = name

    def print_name(self):
        print(self.__private_name)


student = Student("bob")
student.modify_private_name("bob2")  # 访问不到 报错
print(student.__dict__)  # 去掉上面赋值语句 就会报错
student._Student__private_name = 'bob333'
print(student.__dict__)
student.print_name()

student2 = Student("crab")
student2.__private_name = "bob3"
print(student2.__dict__)  # 打开注释会报错
```



输出结果如下

```javascript
{'name': 'bob', '_Student__private_name': 'bob2'}
{'name': 'bob', '_Student__private_name': 'bob333'}
bob333
{'name': 'crab', '__private_name': 'bob3'}
```

