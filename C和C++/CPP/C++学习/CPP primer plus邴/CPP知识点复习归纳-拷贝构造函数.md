先看一段代码：

```cpp
class A{
  int value;
  public:
      A(int n):value(n){}
      A(A other):value(other.value){}
      void print(){cout<< value << endl;}  
};
```

上面代码编译报错。

error: invalid constructor; you probably meant `A (const A&)`

![](https://gitee.com/hxc8/images2/raw/master/img/202407172218903.jpg)

A(A other):value(other.value){} => A(const A &other):value(other.value){}

A(A other):value(other.value){}

上面代码是按值传递，实参传递给形参，A other = a; 那么在实参传递给形参的过程中又出现了拷贝构造函数调用，形成递归调用，最终导致内存溢出。

A(const A &other):value(other.value){}

相当于 A &other = a;