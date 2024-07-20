

```javascript
#include "iostream"
#include "string"

using namespace std;

//多态

class Animal {
public:
    void speak() {
        cout << "动物在说话" << endl;
    }

};

class Animal2 {
public:
    virtual void speak() {
        cout << "动物在说话" << endl;
    }

};


void test2() {
    cout << "sizeof(Animal)=" << sizeof(Animal) << endl;
    cout << "sizeof(Animal2)=" << sizeof(Animal2) << endl;
}

int main() {
    test2();
    return 0;
}
```





```javascript
sizeof(Animal)=1
sizeof(Animal2)=8//32位系统是4
```







https://www.bilibili.com/video/BV1et411b73Z?p=136&spm_id_from=pageDriver





```javascript
#include "iostream"
#include "string"

using namespace std;

//多态

class Animal {
public:
    void speak() {
        cout << "动物在说话" << endl;
    }

};

class Animal2 {
public:
    virtual void speak() {
        cout << "动物在说话" << endl;
    }

};

class Cat : public Animal {

};

class Cat2 : public Animal2 {

};


void test2() {
    cout << "sizeof(Animal)=" << sizeof(Animal) << endl;
    cout << "sizeof(Animal2)=" << sizeof(Animal2) << endl;
    cout << "sizeof(Cat)=" << sizeof(Cat) << endl;
    cout << "sizeof(Cat2)=" << sizeof(Cat2) << endl;
}

int main() {
    test2();
    return 0;
}
```



```javascript
sizeof(Animal)=1
sizeof(Animal2)=8
sizeof(Cat)=1
sizeof(Cat2)=8
```













---



```javascript
#include "iostream"
#include "string"

using namespace std;

//多态

class Animal {
public:
    //虚函数,就可以地址晚绑定
    virtual void speak() {
        cout << "动物在说话" << endl;
    }

};

class Cat : public Animal {
public:
    //重写  函数返回值类型 函数名 参数列表  完全相同
    void speak() {//子类前面也可以加virtual 也可以不加
        cout << "猫在说话" << endl;
    }

};

class Dog : public Animal {
public:
    //重写  函数返回值类型 函数名 参数列表  完全相同
    virtual void speak() {//子类前面也可以加virtual 也可以不加
        cout << "狗在说话" << endl;
    }

};

//地址早绑定，在编译阶段确定函数地址
//如果想执行让猫speak，那么这个函数地址就不能提前绑定，需要在运行阶段进行绑定，即地址晚绑定

//动态多态满足条件
//1 有继承关系
//2 子类要重写（不是重载）父类的虚函数

//动态多台使用
//父类的指针或者引用指向子类对象
void doSpeak(Animal &animal) {//Animal &animal=cat;
    //入参是Animal,早就绑定地址了
    animal.speak();
}

void test() {
    Cat c;
    doSpeak(c);
    Dog dog;
    doSpeak(dog);
}

void test2() {
    cout << sizeof(Animal) << endl;
}

int main() {
//    test();
    test2();
    return 0;
}
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172226703.jpg)

首先Cat不重写父类speak函数，如上图



下图是重写了父类的speak函数

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226687.jpg)





下图是32系统，所以指针是4位

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226337.jpg)







注释掉cat里面的重写部分，看下cat类内存分布

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226004.jpg)



打开注释，看下cat内存分布

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226501.jpg)

