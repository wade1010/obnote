CPP 核心编程3-类和对象

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237527.jpg)

```
#include "iostream"

using namespace std;
const double PI = 3.14;

class Circle {
public:
    double m_r;

    double calculateZC() {
        return 2 * PI * m_r;
    }
};

int main() {
    Circle c;
    c.m_r = 10;
    double zc = c.calculateZC();
    cout << "周长=" << zc << endl;
    return 0;
}
周长=62.8

```

```
#include "iostream"

using namespace std;

class Student {
public:
    int m_id;
    string m_name;

    void show() {
        cout << "学号：" << m_id << " 姓名：" << m_name << endl;
    }
};

int main() {
    Student s;
    s.m_id = 10;
    s.m_name = "真是";
    s.show();
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237083.jpg)

```
#include "iostream"

using namespace std;

//常函数
class Person {
public:
    //this指针的本质是 指针常量  指针的指向是不能改的  相当于Person * const this
    //如果想限定this指针指向的值也不能修改，就得类似于 const Person * const this,此时这个const加在哪里呢？最终决定加在函数()后面
    //在成员函数后面加const,修饰的是this指向，让指针指向的值也不可以修改
    void showPerson() const {
//        this = NULL;//加不加const,this指针是不可以修改指针的指向的
//        m_A = 100;//加了const就不能修改指针指向的值
        cout << m_A << endl;
    }

    int m_A;

    void showNormal() {

    }
};

//常函数
class Person2 {
public:
    //this指针的本质是 指针常量  指针的指向是不能改的  相当于Person * const this
    //如果想限定this指针指向的值也不能修改，就得类似于 const Person * const this,此时这个const加在哪里呢？最终决定加在函数()后面
    //在成员函数后面加const,修饰的是this指向，让指针指向的值也不可以修改
    void showPerson() const {
//        this = NULL;//加不加const,this指针是不可以修改指针的指向的
//        m_A = 100;//加了const就不能修改指针指向的值
        m_B = 100;//或this->m_B=100;
        cout << m_B << endl;
    }

    void showNormal() {

    }

    int m_A;
    mutable int m_B;//特殊变量，即使在常函数中，也可以修改这个值，加关键字mutable
};

void test1() {
    Person p;
    p.showPerson();// 0
    p.showNormal();//不报错
    Person2 p2;
    p2.showPerson();// 100
    p2.showNormal();//不报错
}

//常对象
//常对象只能调常函数，不可以调用普通成员函数，因为普通成员函数是可以修改属性的。
// 可以这么理解，如果普通函数里面修改m_A，如 m_A=100，那么如果能调用普通函数就会修改m_A,所以不允许
void test2() {
//    const Person p;//视频里是这样的，不报错。新版的报错， 'const class Person' has no user-provided default constructor
    const Person p{};//新版得这样声明 可以发现test1中不报（但是有warning），但是加了const就报错。
//    p.m_A = 100;//报错
//    p.showNormal();//报错

    const Person2 p2{};
//    p2.m_A = 100;//报错
    p2.m_B = 100;//不报错
//    p2.showNormal();//报错
}

int main() {
//    test1();
    test2();
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237924.jpg)

全局函数作友元

下面代码是不能访问private属性的

```
#include "iostream"

using namespace std;

class Building {
public:
    Building() {
        m_SittingRoom = "客厅";
        m_BedRoom = "卧室";
    }

    string m_SittingRoom;
private:
    string m_BedRoom;

};

//全局函数
void goodGay(Building *building) {
    cout << "好基友全局函数 正在访问:" << building->m_SittingRoom << endl;//可以访问
//    cout << "好基友全局函数 正在访问:" << building->m_BedRoom << endl;//报错
}

void test1() {
    Building building;
    goodGay(&building);
}

int main() {
    test1();
    return 0;
}
```

修改下，如下

```
#include "iostream"

using namespace std;

class Building {
    //告诉编译器 goodGay全局函数是Building的好朋友，可以访问Building中私有成员
    friend void goodGay(Building *building);

public:
    Building() {
        m_SittingRoom = "客厅";
        m_BedRoom = "卧室";
    }

    string m_SittingRoom;
private:
    string m_BedRoom;

};

//全局函数
void goodGay(Building *building) {
    cout << "好基友全局函数 正在访问:" << building->m_SittingRoom << endl;//可以访问
    cout << "好基友全局函数 正在访问:" << building->m_BedRoom << endl;//报错
}

void test1() {
    Building building;
    goodGay(&building);
}

int main() {
    test1();
    return 0;
}
好基友全局函数 正在访问:客厅
好基友全局函数 正在访问:卧室

```

类作友元

下面代码用到了类外实现，以前一般都是类内实现，这里用下类外实现。

下面代码不能访问私有变量

```
#include "iostream"

using namespace std;

//类作友元
class Building;//告诉编译器有一个Building类，暂时没定义，不要给我报错

class GoodGay {
public:
    GoodGay();

    void visit();//参观函数 访问Building中的属性 ，这里就声明下，准备用类外实现

    Building *building;
};

class Building {
public:
    Building();

    string m_SittingRoom;
private:
    string m_BedRoom;
};

//类外写成员函数
GoodGay::GoodGay() {
    building = new Building;//在堆区创建一个对象
}

void GoodGay::visit() {
    cout << "好基友这个类正在访问：" << building->m_SittingRoom << endl;
//    cout << "好基友这个类正在访问：" << building->m_BedRoom << endl;//不能访问私有变量

}

Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

void test1() {
    GoodGay gg;
    gg.visit();
}

int main() {
    test1();
    return 0;
}
```

加上 friend class GoodGay; 

修改后

```
#include "iostream"

using namespace std;

//类作友元
class Building;//告诉编译器有一个Building类，暂时没定义，不要给我报错

class GoodGay {
public:
    GoodGay();

    void visit();//参观函数 访问Building中的属性 ，这里就声明下，准备用类外实现

    Building *building;
};

class Building {
    friend class GoodGay;

public:
    Building();

    string m_SittingRoom;
private:
    string m_BedRoom;
};

//类外写成员函数
GoodGay::GoodGay() {
    building = new Building;//在堆区创建一个对象
}

void GoodGay::visit() {
    cout << "好基友这个类正在访问：" << building->m_SittingRoom << endl;
    cout << "好基友这个类正在访问：" << building->m_BedRoom << endl;

}

Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

void test1() {
    GoodGay gg;
    gg.visit();
}

int main() {
    test1();
    return 0;
}
好基友这个类正在访问：客厅
好基友这个类正在访问：卧室
```

成员函数作友元

```
#include "iostream"

using namespace std;

//成员函数作友元
class Building;

class GoodGay {
public:
    GoodGay();//锻炼下类外实现

    void visit();//让visit可以访问Building中私有成员

    void visit2();//让visit2不可以访问Building中私有成员
    Building *building;
};

class Building {
    friend void GoodGay::visit();

public:
    Building();//锻炼下类外实现

    string m_SittingRoom;
private:
    string m_BedRoom;

};
//类外实现成员函数

Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

GoodGay::GoodGay() {
    building = new Building;
}

void GoodGay::visit() {
    cout << "visit正在访问" << building->m_SittingRoom << endl;
    cout << "visit正在访问" << building->m_BedRoom << endl;
}

void GoodGay::visit2() {
    cout << "visit2正在访问" << building->m_SittingRoom << endl;
//    cout << "visit2正在访问" << building->m_BedRoom << endl;//报错
}

void test1() {
    GoodGay gg;
    gg.visit();
    gg.visit2();
}

int main() {

    test1();
    return 0;
}
visit正在访问客厅
visit正在访问卧室
visit2正在访问客厅

```