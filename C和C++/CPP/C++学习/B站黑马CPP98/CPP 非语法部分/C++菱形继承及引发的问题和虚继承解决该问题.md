

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225448.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172225695.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172225304.jpg)









```javascript
#include "iostream"
#include "string"

using namespace std;

//菱形继承

class Animal {
public:
    int age;
};

class Sheep : public Animal {

};

class Tuo : public Animal {

};

class SheepTuo : public Sheep, public Tuo {

};


void test() {
    SheepTuo st;
    st.age = 18;//这里报错如下

}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172225779.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;

//菱形继承

class Animal {
public:
    int age;
};

//利用虚继承 解决菱形继承问题
//继承之前 加上关键字 virtual 变为虚继承

class Sheep : virtual public Animal {

};

class Tuo : virtual public Animal {

};

class SheepTuo : public Sheep, public Tuo {

};


void test() {
    SheepTuo st;
//    st.age=18;//会报错
    //当菱形继承时，两个父类拥有相同的数据，需要加以作用域区分
    //这份数据我们知道 只有一份数据就可以，菱形继承导致数据有两份，资源浪费
    //利用虚继承解决
    st.Sheep::age = 18;
    st.Tuo::age = 20;
    //没有虚继承之前，输出结果18和20 ，虚继承后输出都是20，而且虚继承后，st.age不会报错
    cout << "st.Sheep::age=" << st.Sheep::age << endl;
    cout << "st.Tuo::age=" << st.Tuo::age << endl;
    //虚继承后，st.age不会报错
    cout << "st.age=" << st.age << endl;


}

int main() {
    test();
    return 0;
}
```



https://www.bilibili.com/video/BV1et411b73Z?p=134&spm_id_from=pageDriver



![](https://gitee.com/hxc8/images3/raw/master/img/202407172225254.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172225084.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172225766.jpg)

 