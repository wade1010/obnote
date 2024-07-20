[https://www.python100.com/html/Z93DQ458AM4U.html](https://www.python100.com/html/Z93DQ458AM4U.html)

Python是一门面向对象的编程语言，类的封装性是面向对象编程中最基础的特性之一。在面向对象编程中，类可以将数据和操作封装在一起，避免了数据的直接访问和修改，从而保证了代码的安全性，更好地维护了对象的状态。Python中的类也支持封装性，私有属性定义为双下划线“__”开头的属性，意味着这些属性只能在自身类的内部访问，其他地方无法访问。然而，在某些情况下，需要在外部访问和修改这些私有属性，本篇文章就是介绍Python在外部修改私有属性的方法。

## 一、 使用property和setter方法

在Python中，可以使用property和setter方法对私有属性进行修改。

```
class MyClass:
    def __init__(self, value):
        self.__value = value

    @property
    def value(self):
        return self.__value

    @value.setter
    def value(self, value):
        self.__value = value

```

在上述代码中，我们定义了类MyClass，并在类中定义了私有属性__value，同时定义了value属性的getter和setter方法，访问value属性会返回私有属性__value的值，设置value属性会修改私有属性__value的值。

```
obj = MyClass(10)
print(obj.value)  # 获取私有属性 __value 的值，输出 10
obj.value = 20   # 修改私有属性 __value 的值
print(obj.value)  # 输出 20

```

我们首先创建了一个MyClass类的实例obj，并将参数10传递给它。接着我们使用obj.value访问私有属性__value的值，此时会自动调用value属性的getter方法并返回__value的值10，接着使用obj.value = 20修改私有属性__value的值，此时会自动调用value属性的setter方法并将20作为参数传递给它，接着再次使用obj.value输出私有属性__value的值，此时会自动调用value属性的getter方法并返回__value的值20。

## 二、使用反射机制修改私有属性

Python中的反射机制是指运行时动态地访问、检查、修改对象的属性和方法。在Python中可以使用反射机制修改私有属性。

```
class MyClass:
    def __init__(self, value):
        self.__value = value

obj = MyClass(10)
print(obj._MyClass__value)   # 通过类名和属性名访问私有属性 __value 的值
setattr(obj, '_MyClass__value', 20)   # 使用 setattr() 函数设置私有属性 __value 的值
print(obj._MyClass__value)   # 输出修改后私有属性 __value 的值 20

```

在上述代码中，我们定义了类MyClass，并在类中定义了私有属性__value。同时我们创建了一个MyClass类的实例obj，并将参数10传递给它。使用obj._MyClass__value访问私有属性__value的值，此时会返回私有属性__value的值10。接着使用setattr(obj, '_MyClass__value', 20)设置私有属性__value的值为20，使用obj._MyClass__value输出私有属性__value的值，此时会返回私有属性__value的值20。

## 三、使用私有属性的相关方法修改私有属性

在Python中，还有一些内置的方法可以修改私有属性：

- 使用__setattr__方法，可以在类中修改私有属性：

- 使用__dict__方法，可以修改实例中的私有属性：

## 四、使用其他方法修改私有属性

除了上述方法外，Python中还有其他一些方法可以修改私有属性，例如使用ctypes模块访问C语言中的内存，使用pickle模块反序列化。

## 五、小结

本篇文章主要介绍了Python中如何在外部修改私有属性。使用property和setter方法、反射机制、私有属性的相关方法以及其他方法都可以实现对私有属性的访问和修改。在使用时需要注意，直接修改私有属性可能会破坏类中的封装性，建议在使用时慎重考虑。