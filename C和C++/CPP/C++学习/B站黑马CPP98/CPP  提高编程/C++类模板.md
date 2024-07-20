

![](https://gitee.com/hxc8/images3/raw/master/img/202407172235440.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板
 */

template<class NameType, class AgeType>
class Person {
public:
    Person(NameType n, AgeType a) {
        name = n;
        age = a;
    }

    void showPerson() {
        cout << this->name << " " << this->age << endl;
    }

    NameType name;
    AgeType age;
};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Person<string, int> p("哈哈", 11);
    p.showPerson();
    Person<int, double> p2(1, 11.2);
    p2.showPerson();
}
```



```javascript
哈哈 11
1 11.2
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235977.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172235872.jpg)







编译不会报错，表明类模板中成员函数编译时不创建

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板中成员函数创建时间
 *
 * 结论：类模板中成员函数在调用时才去创建
 */
class Person1 {
public:
    void showPerson1() {
        cout << "person1 show" << endl;
    }

};

class Person2 {
public:
    void showPerson2() {
        cout << "person2 show" << endl;
    }
};

template<class T>
class MyClass {
public:
    T obj;

    //类模板中成员函数
    void func1() {
        obj.showPerson1();
    }

    void func2() {
        obj.showPerson2();
    }

};

void test() {

}

int main() {
    test();
    return 0;
}
```





调用

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板中成员函数创建时间
 *
 * 结论：类模板中成员函数在调用时才去创建
 */
class Person1 {
public:
    void showPerson1() {
        cout << "person1 show" << endl;
    }

};

class Person2 {
public:
    void showPerson2() {
        cout << "person2 show" << endl;
    }
};

template<class T>
class MyClass {
public:
    T obj;

    //类模板中成员函数
    void func1() {
        obj.showPerson1();
    }

    void func2() {
        obj.showPerson2();
    }

};


void test() {
    MyClass<Person1> m;
    m.func1();//不报错
//    m.func2();//报错
}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235429.jpg)









---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235890.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板对象做函数参数
 * 1 指定传入类型  最常用
 * 2 参数模板化
 * 3 整个类模板化
 */

template<class T1, class T2>
class Person {
public:
    Person(T1 n, T2 a) {
        name = n;
        age = a;
    }

    void showPerson() {
        cout << this->name << " " << this->age << endl;
    }

    T1 name;
    T2 age;

};

//1 指定传入类型
void printPerson1(Person<string, int> &p) {
    p.showPerson();
}

void test() {
    Person<string, int> p("孙悟空", 19);
    printPerson1(p);
}

//2 参数模板化
template<class T1, class T2>
void printPerson2(Person<T1, T2> &p) {
    p.showPerson();
    cout << "T1的类型为：" << typeid(T1).name() << endl;
    cout << "T2的类型为：" << typeid(T2).name() << endl;
}

void test2() {
    Person<string, int> p("猪八戒", 19);
    printPerson2(p);
}

//3 整个类模板化
template<class T>
void printPerson3(T &p) {
    p.showPerson();
}

void test3() {
    Person<string, int> p("唐僧", 30);
    printPerson3(p);
}


int main() {
    test();
    test2();
    test3();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235276.jpg)







---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235749.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板与继承
 */
template<typename T>
class Base {
public:
    T m;

};

//class Son : public Base {//报错 必须要知道父类中的T的类型，才能继承
class Son : public Base<int> {//不报错  必须要知道父类中的T的类型，才能继承


};

void test() {
    Son s;
}

int main() {
    test();
    return 0;
}
```





```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板与继承
 */
template<typename T>
class Base {
public:
    T m;

};

//class Son : public Base {//报错 必须要知道父类中的T的类型，才能继承
class Son : public Base<int> {//不报错  必须要知道父类中的T的类型，才能继承


};

//如果想灵活指定父类中的T类型，子类也需要变成类模板

template<class T1, class T2>
class Son2 : public Base<T2> {
public:
    Son2() {
        cout << "T1的类型为：" << typeid(T1).name() << endl;
        cout << "T2的类型为：" << typeid(T2).name() << endl;
    }

    T1 obj;
};

void test() {
    Son s;
}

void test2() {
    Son2<int,char> s;

}

int main() {
//    test();
    test2();
    return 0;
}
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172235280.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235727.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235722.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 * 类模板成员函数类外实现
 */
template<class T1, class T2>
class Person {
public:
    Person(T1 n, T2 a);

    void showPerson();

    T1 name;
    T2 age;
};

//构造函数类外实现
template<class T1, class T2>
Person<T1, T2>::Person(T1 n, T2 a) {
    this->name = n;
    this->age = a;
}

//成员函数类外实现
template<class T1, class T2>
void Person<T1, T2>::showPerson() {
    cout << this->name << " " << this->age << endl;
}


void test() {
    Person<string, int> p("tom", 20);
    p.showPerson();

}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235678.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235207.jpg)

单文件如下：

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 * 类模板分文件编写问题以及解决
 */
template<class T1, class T2>
class Person {
public:
    Person(T1 n, T2 a);
 
    void showPerson();

    T1 name;
    T2 age;
};

//构造函数类外实现
template<class T1, class T2>
Person<T1, T2>::Person(T1 n, T2 a) {
    this->name = n;
    this->age = a;
}

//成员函数类外实现
template<class T1, class T2>
void Person<T1, T2>::showPerson() {
    cout << this->name << " " << this->age << endl;
}


void test() {
    Person<string, int> p("tom", 20);
    p.showPerson();

}

int main() {
    test();
    return 0;
}
```



分文件





第一种解决方式，直接包含 源文件，但是这个很少用

person.h

```javascript
#ifndef FIRST_DEMO_PERSON_H
#define FIRST_DEMO_PERSON_H

#endif //FIRST_DEMO_PERSON_H

template<class T1, class T2>
class Person {
public:
    Person(T1 n, T2 a);

    void showPerson();

    T1 name;
    T2 age;
};
```

person.cpp

```javascript
#include "person.h"
#include "iostream"

using namespace std;

//构造函数类外实现
template<class T1, class T2>
Person<T1, T2>::Person(T1 n, T2 a) {
    this->name = n;
    this->age = a;
}

//成员函数类外实现
template<class T1, class T2>
void Person<T1, T2>::showPerson() {
    cout << this->name << " " << this->age << endl;
}
```



main.cpp

```javascript
#include "string"

//第一种解决方式，直接包含 源文件，但是这个很少用
#include "person.cpp"

using namespace std;

void test() {
    Person<string, int> p("tom", 20);
    p.showPerson();

}

int main() {
    test();
    return 0;
}
```







第二种方式，将.h和.cpp中的内容写到一起，将后缀名该问.hpp文件



person.hpp

```javascript
#pragma once

#include "iostream"

using namespace std;


template<class T1, class T2>
class Person {
public:
    Person(T1 n, T2 a);

    void showPerson();

    T1 name;
    T2 age;
};


using namespace std;

//构造函数类外实现
template<class T1, class T2>
Person<T1, T2>::Person(T1 n, T2 a) {
    this->name = n;
    this->age = a;
}

//成员函数类外实现
template<class T1, class T2>
void Person<T1, T2>::showPerson() {
    cout << this->name << " " << this->age << endl;
}

```



main.cpp

```javascript
#include "string"
using namespace std;
//第二种方式，将.h和.cpp中的内容写到一起，将后缀名该问.hpp文件
#include "person.hpp"
void test() {
    Person<string, int> p("tom", 20);
    p.showPerson();

}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235269.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235718.jpg)

//全局函数类内实现，比较简单

```javascript
#include "string"
#include "iostream"

using namespace std;

/*
 *
 */
template<typename T1, typename T2>
class Person {
public:
    //全局函数 类内实现
    friend void printPerson(Person<T1, T2> p) {
        cout << p.name << " " << p.age << endl;
    }

    Person(T1 n, T2 a) {
        this->name = n;
        this->age = a;
    }

private:
    T1 name;
    T2 age;

};

//1 全局函数在类内实现
void test() {
    Person<string, int> p("tom", 30);
    printPerson(p);
}

int main() {
    test();
    return 0;
}
```



类外实现（复杂，需要让编译器提前看到很多东西 如 //添加一个Person 声明 3   //需要让编译器知道有一个Person类的存在  2   //加一个空模板参数列表，需要把类外实现放到Person类之前 1）

```javascript
#include "string"
#include "iostream"

using namespace std;


//添加一个Person 声明 3
template<typename T1, typename T2>
class Person;

//2 全局函数在类外实现
template<class T1, class T2>
void printPerson2(Person<T1, T2> p) {//需要让编译器知道有一个Person类的存在 2
    cout << p.name << " " << p.age << endl;
}

template<typename T1, typename T2>
class Person {
public:
    //全局函数 类内实现
    friend void printPerson(Person<T1, T2> p) {
        cout << p.name << " " << p.age << endl;
    }

    //全局函数类外实现
//    friend void printPerson2(Person<T1, T2> p);//这样会有问题，这是个普通函数，而外部实现是个函数模板的函数实现，不是一套东西
    //加一个空模板参数列表，需要把类外实现放到Person类之前 1
    friend void printPerson2<>(Person<T1, T2> p);

    Person(T1 n, T2 a) {
        this->name = n;
        this->age = a;
    }

private:
    T1 name;
    T2 age;

};

//1 全局函数在类内实现
void test() {
    Person<string, int> p("tom", 30);
    printPerson(p);
}

////2 全局函数在类外实现
//template<class T1, class T2>
//void printPerson2(Person<T1, T2> p) {
//    cout << p.name << " " << p.age << endl;
//}

void test2() {
    Person<string, int> p("jerry", 30);
    printPerson2(p);
}

int main() {
//    test();
    test2();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235753.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235263.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235873.jpg)



MyArray.hpp

```javascript
#ifndef FIRST_DEMO_MYARRAY_HPP
#define FIRST_DEMO_MYARRAY_HPP

#endif //FIRST_DEMO_MYARRAY_HPP

//自己通用的数组类
#pragma once

#include "iostream"

using namespace std;

template<class T>
class MyArray {
public:
    //有参构造
    MyArray(int c) {
        cout << "MyArray 构造函数" << endl;
        this->capacity = c;
        this->size = 0;
        this->pAddress = new T[c];
    }

    //析构函数
    ~MyArray() {
        cout << "MyArray 析构函数" << endl;
        if (this->pAddress != NULL) {
            delete[] this->pAddress;
            this->pAddress = NULL;
        }
    }

    //拷贝构造
    MyArray(const MyArray &other) {
        cout << "MyArray 拷贝构造" << endl;
        this->capacity = other.capacity;
        this->size = other.size;
//        this->pAddress = other.pAddress;//编译器默认浅拷贝
//深拷贝
        this->pAddress = new T(other.capacity);
//将other中的数据都拷贝过来
        for (int i = 0; i < this->size; i++) {
            this->pAddress[i] = other.pAddress[i];
        }
    }

    //operator= 防止浅拷贝问题
    MyArray &operator=(const MyArray &other) {
        cout << "MyArray operator=" << endl;
        //先判断原来堆区是否有数据，如果有先释放
        if (this->pAddress != NULL) {
            delete[] this->pAddress;
            this->pAddress = NULL;
            this->capacity = 0;
            this->size = 0;
        }

        //深拷贝
        this->capacity = other.capacity;
        this->size = other.size;
        this->pAddress = new T(other.capacity);
        for (int i = 0; i < this->size; i++) {
            this->pAddress[i] = other.pAddress[i];
        }
        return *this;
    }

    //尾插法
    void PushBack(const T &value) {
        if (this->capacity == this->size) {
            return;
        }
        this->pAddress[this->size] = value;
        this->size++;
    }

    //尾删法
    void PopBack() {
        //让用户访问不到最后一个元素，即为尾删，逻辑删除
        if (this->size == 0) {
            return;
        }
        this->size--;
    }

    //通过下标的方式访问
    T &operator[](int index) {
        return this->pAddress[index];
    }

    int getCapacity() {
        return this->capacity;
    }

    int getSize() {
        return this->size;
    }

private:
    T *pAddress;//指针指向堆区开辟的真实数组
    int capacity;//数组容量
    int size;//数组大小

};
```



 main.cpp

```javascript
#include "string"
#include "iostream"

using namespace std;

#include "MyArray.hpp"

void printIntArr(MyArray<int> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << arr[i] << endl;
    }
}

void test() {
    MyArray<int> arr1(5);
    for (int i = 0; i < 5; i++) {
        arr1.PushBack(i);
    }
    cout << "arr1的打印输出为：" << endl;
    printIntArr(arr1);
    cout << "arr1的容量为：" << arr1.getCapacity() << endl;
    cout << "arr1的大小为：" << arr1.getSize() << endl;
    MyArray<int> arr2(arr1);
    cout << "arr2的打印输出为：" << endl;
    printIntArr(arr2);
    arr2.PopBack();
    cout << "arr2尾删后：" << endl;
    cout << "arr2的容量为：" << arr2.getCapacity() << endl;
    cout << "arr2的大小为：" << arr2.getSize() << endl;

}

//测试自定义数据类型
class Person {
public:
    Person() {//这里不写默认构造函数      MyArray<Person> arr1(5); 这里会报错

    }

    Person(string n, int a) {
        name = n;
        age = a;
    }

    string name;
    int age;

};

void printPersonArray(MyArray<Person> &arr) {
    for (int i = 0; i < arr.getSize(); i++) {
        cout << arr[i].name << " " << arr[i].age << endl;

    }

}

void test2() {
    MyArray<Person> arr1(10);
    for (int i = 0; i < 5; i++) {
        Person p("name" + to_string(i), i + 10);
        arr1.PushBack(p);
    }

    printPersonArray(arr1);

    cout << "arr1的容量为：" << arr1.getCapacity() << endl;
    cout << "arr1的大小为：" << arr1.getSize() << endl;


}

int main() {
//    test();
    test2();
    return 0;
}
```

