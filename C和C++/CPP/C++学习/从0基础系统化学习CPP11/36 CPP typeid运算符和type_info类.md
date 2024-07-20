36 CPP typeid运算符和type_info类

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220845.jpg)

type_info 重载了==和!=运算符，用于对类型进行比较

注意：

1 type_info类的构造函数是private属性，也没有拷贝构造函数，所以不能直接实例化，只能由编译器在内部实例化

2 不建议用name()成员函数返回的字符串作为判断数据类型的依据。（编译器可能会转换类型名）

3 typeid运算符可以用于多态的场景，在运行阶段识别对象的数据类型。

4 假设有表达式typeid(*ptr)，当ptr是空指针时，如果ptr是多态的类型，将引发bad_typeid异常。

```
#include "iostream"
using namespace std;
class AA
{
};
void test()
{
    // typeid用于内置数据类型
    int num = 3;
    int *pi = &num;
    int &ri = num;
    cout << "typeid(int)=" << typeid(int).name() << endl;
    cout << "typeid(num)=" << typeid(num).name() << endl;
    cout << "typeid(int *)=" << typeid(int *).name() << endl;
    cout << "typeid(pi)=" << typeid(pi).name() << endl;
    cout << "typeid(int &)=" << typeid(int &).name() << endl;
    cout << "typeid(ri)=" << typeid(ri).name() << endl;

    // typeid用于自定义数据类型
    AA aa;
    AA *paa = &aa;
    AA &raa = aa;
    cout << "typeid(AA)=" << typeid(AA).name() << endl;
    cout << "typeid(aa)=" << typeid(aa).name() << endl;
    cout << "typeid(AA *)=" << typeid(AA *).name() << endl;
    cout << "typeid(paa)=" << typeid(paa).name() << endl;
    cout << "typeid(AA &)=" << typeid(AA &).name() << endl;
    cout << "typeid(raa)=" << typeid(raa).name() << endl;

    // type_info 重载了==和!=运算符，用于对类型进行比较
    if (typeid(AA) == typeid(aa))
    {
        cout << "ok1" << endl;
    }
}
int main()
{
    test();
    return 0;
}
typeid(int)=i
typeid(num)=i
typeid(int *)=Pi
typeid(pi)=Pi
typeid(int &)=i
typeid(ri)=i
typeid(AA)=2AA
typeid(aa)=2AA
typeid(AA *)=P2AA
typeid(paa)=P2AA
typeid(AA &)=2AA
typeid(raa)=2AA
ok1
```