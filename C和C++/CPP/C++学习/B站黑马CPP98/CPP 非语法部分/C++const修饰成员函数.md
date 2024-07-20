

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227996.jpg)





```javascript
#include "iostream"

using namespace std;

//常函数


class Person {
public:
    //this指针的本质 是指针常量   指针的指向是不可以修改的
    //const Person * const this
    //在成员函数后面加const，修饰的是this的指向，让指针指向的值也不可以修改
    void print() const {
//        this->age = 100;//报错,this指针不可以修改指针指向 的值
//        this = NULL;//报错,this指针不可以修改指针的指向
        this->age2 = 10;
        cout << "print" << endl;
    }

    void print2() {
        cout << "print2" << endl;
    }

    int age;
    mutable int age2;//特殊变量,及时在常函数中，也可以修改这个值
};

void test() {
    Person *p = NULL;
    p->print();
}

//常对象
void test2() {
    const Person p;//常对象
//    p.age = 100;//不能修改
    p.age2 = 100;//可以修改

    //常对象只能调用常函数
    p.print();
//    p.print2();//常对象 不可以调普通成员函数，因为普通成员函数可以修改属性
}

int main() {
    test();
    return 0;
}
```





