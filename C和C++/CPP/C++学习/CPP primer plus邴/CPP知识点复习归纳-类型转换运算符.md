说明下面两个类型转换运算符的区别

class A{

operator const int();

operator int() const;

};

![](https://gitee.com/hxc8/images2/raw/master/img/202407172217241.jpg)

注意一点，转换到的类型不能是void类型和数组类型，因为作为函数的返回值的类型不能是数组

转换函数跟构造函数是相反的，构造函数只用于从某种类型到类类型的转换。

 

所以上面的题目

operator const int();

将A的类型转换为 const int 类型

operator int() const;

将A的类型转换为int 类型，但是不希望你通过转换函数修改类的对象

![](images/WEBRESOURCE22ead7e38706e0417c3c6e70c3534ce8截图.png)