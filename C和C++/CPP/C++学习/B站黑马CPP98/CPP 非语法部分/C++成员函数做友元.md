

```javascript
#include "iostream"
#include "string"

using namespace std;

class Building;

class GoodGay {
public :
    GoodGay();

    void visit();//让visit函数可以访问Building中的私有成员

    void visit2();//让visit2函数不可以访问Building中的私有成员

    Building *building;
};

class Building {

//    friend void visit();//不能是这样，这样visit就是全局变量

    friend void GoodGay::visit();//得跟他说是GoodGay类下的visit();

public:
    Building();

public :
    string m_SittingRoom;
private:
    string m_BedRoom;

};

//类外实现成员函数

void test();

Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

GoodGay::GoodGay() {
    building = new Building();
}

void GoodGay::visit() {
    cout << "visit 函数正在访问" << building->m_SittingRoom << endl;
    cout << "visit2 函数正在访问" << building->m_BedRoom << endl;//不报错
}

void GoodGay::visit2() {
    cout << "visit2 函数正在访问" << building->m_SittingRoom << endl;
//    cout << "visit2 函数正在访问" << building->m_BedRoom << endl;//报错
}

int main() {
    test();
    return 0;
}

void test() {
    GoodGay gg;
    gg.visit();
    gg.visit2();
}
```



```javascript
visit 函数正在访问客厅
visit2 函数正在访问卧室
visit2 函数正在访问客厅
```

