![](https://gitee.com/hxc8/images3/raw/master/img/202407172238189.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238124.jpg)

前置递增

```
#include "iostream"

using namespace std;

//递增运算符重载
//自定义整型
class MyInteger {
    friend ostream &operator<<(ostream &cout, MyInteger mi);

public:
    MyInteger() {
        m_Num = 0;
    }

    //重置前置++运算符  返回引用是为了对同一个数进行操作
    MyInteger &operator++() {//这里不能返回值MyInteger 必须返回MyInteger&  因为操作的需要是都一个对象，返回值就是拷贝了
        //先进行++运算
        m_Num++;
        //再将自身作返回
        return *this;
    }
    //重置后置++运算符

private:
    int m_Num;
};

//重载左移运算符
ostream &operator<<(ostream &cout, MyInteger mi) {
    cout << mi.m_Num;
    return cout;
}

void test() {
    MyInteger mi;
    cout << ++(++mi) << endl;//C++里面原生的前置递增可以链式调用，后置递增不可以链式调用
}

int main() {
//    test();
    //C++里面原生的前置递增可以链式调用，后置递增不可以链式调用
    int a = 0;
    ++(++a);
    cout << a << endl;//输出2
    return 0;
}
2

```

后置递增

C++里面后置++是不能链式调用的

```
#include "iostream"

using namespace std;

//递增运算符重载
//自定义整型
class MyInteger {
    friend ostream &operator<<(ostream &cout, MyInteger mi);

public:
    MyInteger() {
        m_Num = 0;
    }

    //重置前置++运算符  返回引用是为了对同一个数进行操作
    MyInteger &operator++() {//这里不能返回值MyInteger 必须返回MyInteger&  因为操作的需要是都一个对象，返回值就是拷贝了
        //先进行++运算
        m_Num++;
        //再将自身作返回
        return *this;
    }

    //重置后置++运算符   ！！！！！！之前学习的占位符终于找到用武之地了
    //返回值用const修饰，他能防止一些问题，例如： (p++)++
    const MyInteger operator++(int) {//int 代表占位参数，可以用于区分前置和后置递增，这里只能是int，double等不好使
        MyInteger temp = *this;
        m_Num++;
        return temp;
    }

private:
    int m_Num;
};

//重载左移运算符
ostream &operator<<(ostream &cout, MyInteger mi) {
    cout << mi.m_Num;
    return cout;
}

void test() {
    MyInteger mi;
//    cout << ++(++mi) << endl;
    cout << mi++ << endl;//输出0   //返回值用const修饰，他能防止一些问题，例如： (p++)++
    cout << mi << endl;//输出1
}

int main() {
    test();
    return 0;
}
0
1
```

递增运算符分为前置递增和后置递增。这两个分开来说吧。

需要创建一个名字叫做 人 的类，这个类中有一个 年龄 属性，且默认值为0，然后类外重载左移运算符方便打印输出。我们对人类的对象进行递增操作就是想让年龄增加。如下：

```cpp
#include <iostream>
using namespace std;

class Person
{
public:
	int age = 0;
	Person& operator++()						//**1**
	{
		age += 1;
		return *this;
	}
};
ostream& operator<<(ostream& cout, const Person &p)		
{
	cout << p.age;
	return cout;
}
void test1()
{
	Person p;
	cout << "++p = " << ++p << endl;
	cout << "++(++p) = " << ++(++p) << endl;
	cout << "p = " << p << endl;
}
int main()
{
	test1();
	system("pause");
	return 0;
}

```

**第一个问题** ：返回值为什么是引用？

答案：因为前置递增运算符可以这样使用：++(++p)。即进行两次连续的递增操作。若返回值为一个对象，则会产生一个匿名对象，之后++操作是对匿名独对象进行++操作。结果如下：

**第二个问题** ：为什么这是前置递增，不应该是后置递增吗？

首先解释一下问题，还是举个例子吧。

加运算符在类内实现重载的写法：Person operator+(Person &p);

当你调用的时候，如：p1 + p2，其本质调用是 p1.operator+(p2); 。你会发现，是 +号前面的对象调用函数，加号后面的对象是参数 。所以，当我们调用Person& operator++();的时候，是不是应该写 p1++??

答案：（个人推测）可能和后置递增有关。为了区分前置和后置。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238592.jpg)

**第一个问题** ：是后置递增类内重载的写法遇到的问题：

返回值和参数都是啥？？想这样写？？

```cpp
Person operator++();
Person& operator++();
Person operator++(Person& p);
....

```

想了好久，也没想出来该怎么写。

答案：const Person operator++(int)

其中int的作用是占位符，为了和前置递增区分开来，而且只能写int，写double、float等都是不行的。因为没有这个占位参数的话，该函数与前置递增就只有返回值不同了，而返回值是不可以作为函数重载的条件的。

至于返回值的为什么是Person话，而且还用const修饰，在下一个问题中说明。

**第二个问题** 具体是怎样实现的，毕竟后置递增和前置递增还是有区别的。

答案：

```cpp
const Person operator++(int)
{
		Person p = *this;
		age += 1;
		return p;
}

```

首先创建一个临时的对象，用来保存当前值，因为该函数最后返回的不是递增后的结果，而是递增之前的值。

然后，属性加一，完成递增操作。

最后，返回临时对象

注意1： 此时的temp是一个临时对象，该函数运行结束后就会被编译器回收，所以我们直接返回temp的引用。我们需要进行值返回，值返回的话会调用拷贝构造函数重新创建一个对象。

注意2： 返回值用const修饰，他能防止一些问题，例如： (p++)++ 。我们可以做一个小测试。

```cpp
int p = 0;
cout << "(p++)++ = " << (p++)++ << endl;
cout << "p = " << p << endl;

```

我们预想的结果可能是输出：(p++)++ = 1 p = 2

但实际结果是这样的：

报错了，原因是++需要可修改的左值。从结果我们可以知道，表达式p++的结果是不允许被修改的，所以我们后置递增的返回值是const类型的。

那么为什么会这样呢？

同样举个例子：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238527.jpg)

```cpp
int p;
cout << "p = " << p << endl;
cout << "p++ = " << p++ << endl;
cout << "p = " << p << endl;

```

运行结果如下：

我们输出 p++ 的结果是0，这个0是哪里来的呢？？是变量p中存储的吗？？并不是，此时p中存储的值已经被改变了，这个0是产生的一个临时值。这个临时值在内存中的位置是未知的，我们无法对其进行修改。

所以，当我们 (p++)++ 这样使用后置递增运算符的时候，相当于尝试修改临时变量的值，这是不被允许的。

所以，我们重载递增操作符的返回值要用const来修饰。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238389.jpg)