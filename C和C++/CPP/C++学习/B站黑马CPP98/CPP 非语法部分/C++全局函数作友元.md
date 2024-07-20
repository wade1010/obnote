

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227991.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;

class Building {

    friend void goodGay2(Building *building);

public:
    string m_room;

    Building() {
        m_room = "客厅";
        m_bedroom = "卧室";
    }

private:
    string m_bedroom;
};

//全局函数
void goodGay(Building *building) {
    cout << "全局函数，正在访问" << building->m_room << endl;
    //这下面明显会报错，不能直接访问private
//    cout << "全局函数，正在访问" << building->m_bedroom << endl;
}

void goodGay2(Building *building) {
    cout << "全局函数，正在访问" << building->m_room << endl;
    cout << "全局函数，正在访问" << building->m_bedroom << endl;
}


void test() {
    Building building;
//    goodGay(&building);
    goodGay2(&building);
}

int main() {
    test();
    return 0;
}


```

