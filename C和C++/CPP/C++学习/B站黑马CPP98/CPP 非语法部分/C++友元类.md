```javascript
#include "iostream"
#include "string"

using namespace std;

//类做友元
class Building;

class GoodGay {
public:
    GoodGay();

public:
    void visit();

    Building *building;
};

class Building {
public:
    Building();

public:
    string m_SittingRoom;
private:
    string m_BedRoom;

};

//类外写成员函数
Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

//类外写成员函数
GoodGay::GoodGay() {
    //创建建筑物
    building = new Building;

}

void GoodGay::visit() {
    cout << "好基友正在访问:" << building->m_SittingRoom << endl;
    cout << "好基友正在访问:" << building->m_BedRoom << endl;//不是友元类会报错
}


void test() {
    GoodGay gg;
    gg.visit();
}

int main() {
    test();
    return 0;
}


```



将goodgay作为building的友元类



```javascript
#include "iostream"
#include "string"

using namespace std;

//类做友元

class Building;//GoodGay在Building之前，这里可以先声明个Building类，如果将GoodGay放到Building后面就不用声明

class GoodGay {
public:
    GoodGay();

public:
    void visit();

    Building *building;
};

class Building {
    //GoodGay是本类的好朋友，可以访问类中的私有成员
    friend class GoodGay;

public:
    Building();

public:
    string m_SittingRoom;
private:
    string m_BedRoom;

};

//类外写成员函数
Building::Building() {
    m_SittingRoom = "客厅";
    m_BedRoom = "卧室";
}

//类外写成员函数
GoodGay::GoodGay() {
    //创建建筑物
    building = new Building;

}

void GoodGay::visit() {
    cout << "好基友正在访问:" << building->m_SittingRoom << endl;
    cout << "好基友正在访问:" << building->m_BedRoom << endl;//不是友元类会报错
}


void test() {
    GoodGay gg;
    gg.visit();
}

int main() {
    test();
    return 0;
}


```

