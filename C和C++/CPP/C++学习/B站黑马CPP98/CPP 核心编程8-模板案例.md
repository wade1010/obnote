CPP 核心编程8-模板案例

![](images/WEBRESOURCE089d6fa35402d19273f219e757a10a33截图.png)

```
#include "iostream"

using namespace std;

#include "MyArray.hpp"

template<class T>
void printInfo(MyArray<T> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
}

/*class Person;

template<>
void printInfo(MyArray<Person> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << "name=" << arr[i].m_Name << " age=" << arr[i].m_Age << endl;
    }
}*/

void test() {
    //有参构造
    MyArray<int> ma(5);
    //拷贝构造
    MyArray<int> ma2(ma);
    //赋值
    MyArray<int> ma3(100);
    ma3 = ma;
}

void test2() {
    //有参构造
    MyArray<int> ma(5);

    for (int i = 0; i < 5; i++) {
        ma.pushBack(i);
    }
    cout << "ma的打印输出为：" << endl;
    printInfo(ma);
    cout << "ma容量：" << ma.getCapacity() << endl;
    cout << "ma大小：" << ma.getSize() << endl;

    MyArray<int> ma2(ma);
    cout << "ma2 的打印输出为：" << endl;
    printInfo(ma2);
    cout << "ma2容量：" << ma.getCapacity() << endl;
    cout << "ma2大小：" << ma.getSize() << endl;
    ma2.popBack();
    cout << "ma2 尾删后的打印输出为：" << endl;
    printInfo(ma2);
    cout << "ma2 尾删后容量：" << ma2.getCapacity() << endl;
    cout << "ma2 尾删后大小：" << ma2.getSize() << endl;

    cout << "ma2[0]的值为：" << ma2[0] << endl;
    ma2[0] = 100;
    cout << "ma2[0]=100 后 ma2[0]的值为：" << ma2[0] << endl;
}

//测试自定义类型
class Person {
public:
    Person() {}

    Person(string name, int age) {
        m_Name = name;
        m_Age = age;
    }

    string m_Name;
    int m_Age;
};

template<>
void printInfo(MyArray<Person> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << "name=" << arr[i].m_Name << " age=" << arr[i].m_Age << endl;
    }
}

void test3() {
    MyArray<Person> ma(5);
    Person p1("孙悟空", 100);
    Person p2("猪八戒", 99);
    Person p3("沙僧", 98);
    Person p4("白龙马", 97);
    Person p5("唐僧", 1000);
    ma.pushBack(p1);
    ma.pushBack(p2);
    ma.pushBack(p3);
    ma.pushBack(p4);
    ma.pushBack(p5);
    printInfo(ma);
    cout << "容量" << ma.getCapacity() << endl;
    cout << "大小" << ma.getSize() << endl;
}


int main() {
    test();
    test2();
    test3();
    return 0;
}
有参构造
拷贝构造
有参构造
=调用
析构
析构
析构
有参构造
ma的打印输出为：
0 1 2 3 4 
ma容量：5
ma大小：5
拷贝构造
ma2 的打印输出为：
0 1 2 3 4 
ma2容量：5
ma2大小：5
ma2 尾删后的打印输出为：
0 1 2 3 
ma2 尾删后容量：5
ma2 尾删后大小：4
ma2[0]的值为：0
ma2[0]=100 后 ma2[0]的值为：100
析构
析构
有参构造
name=孙悟空 age=100
name=猪八戒 age=99
name=沙僧 age=98
name=白龙马 age=97
name=唐僧 age=1000
容量5
大小5
析构
```

上面有一点主意

如果把76行的

```
template<>
void printInfo(MyArray<Person> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << "name=" << arr[i].m_Name << " age=" << arr[i].m_Age << endl;
    }
}
```

给删掉

将15行的

```
/*class Person;

template<>
void printInfo(MyArray<Person> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << "name=" << arr[i].m_Name << " age=" << arr[i].m_Age << endl;
    }
}*/
```

注释打开,编译会报错

invalid use of incomplete type 'class Person'

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237932.jpg)