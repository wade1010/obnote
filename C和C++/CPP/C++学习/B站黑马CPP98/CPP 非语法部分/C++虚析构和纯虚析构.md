

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226120.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226644.jpg)

```javascript
#include "iostream"
#include "string"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    //纯虚函数
    virtual void speak() = 0;

};

class Cat : public Animal {
public:
    void speak() {
        cout << "cat speak" << endl;
    }

};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Animal *a = new Cat();
    a->speak();
    delete a;
}
```





//下面是有问题代码

```javascript
#include "iostream"
#include "string"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal 构造函数" << endl;
    }

    ~Animal() {
        cout << "Animal 析构函数" << endl;
    }

    //纯虚函数
    virtual void speak() = 0;

};

class Cat : public Animal {
public:
    Cat(string n) {
        cout << "Cat 构造函数" << endl;
        name = new string(n);
    }

    ~Cat() {
        cout << "Cat 析构函数" << endl;
        if (name != NULL) {
            delete name;
            name = NULL;
        }
    }

    void speak() {
        cout << "cat speak" << endl;
    }

    string *name;

};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Animal *a = new Cat("Tom");
    a->speak();
    delete a;
}
```



结果不会输出 析构调用 （视频里面会调用父类的析构函数，只是不调用子类析构而已）https://www.bilibili.com/video/BV1et411b73Z?p=140&spm_id_from=pageDriver



```javascript
Animal 构造函数
Cat 构造函数
cat speak
```





1、利用虚析构函数解决此问题



```javascript
#include "iostream"
#include "string"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal 构造函数" << endl;
    }

//利用虚析构可以解决 父类指针释放子类对象时不干净的问题
    virtual   ~Animal() {
        cout << "Animal 虚析构函数" << endl;
    }


    //纯虚函数
    virtual void speak() = 0;

};


class Cat : public Animal {
public:
    Cat(string n) {
        cout << "Cat 构造函数" << endl;
        name = new string(n);
    }

    ~Cat() {
        cout << "Cat 析构函数" << endl;
        if (name != NULL) {
            delete name;
            name = NULL;
        }
    }

    void speak() {
        cout << "cat speak" << endl;
    }

    string *name;

};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Animal *a = new Cat("Tom");
    a->speak();
    delete a;
}
```



```javascript
Animal 构造函数
Cat 构造函数
cat speak
Cat 析构函数
Animal 虚析构函数
```





2、利用纯虚析构函数 解决此问题





```javascript
#include "iostream"
#include "string"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal 构造函数" << endl;
    }

//利用虚析构可以解决 父类指针释放子类对象时不干净的问题
//    virtual   ~Animal() {
//        cout << "Animal 虚析构函数" << endl;
//    }

    //或者纯虚析构  二者选其一
    //有了纯虚析构 之后，这个类也属于抽象类 无法实例化
    virtual  ~Animal() = 0;

    //纯虚函数
    virtual void speak() = 0;

};

Animal::~Animal() {
    cout << "Animal 纯虚析构函数" << endl;
}

class Cat : public Animal {
public:
    Cat(string n) {
        cout << "Cat 构造函数" << endl;
        name = new string(n);
    }

    ~Cat() {
        cout << "Cat 析构函数" << endl;
        if (name != NULL) {
            delete name;
            name = NULL;
        }
    }

    void speak() {
        cout << "cat speak" << endl;
    }

    string *name;

};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Animal *a = new Cat("Tom");
    a->speak();
    delete a;
}
```



```javascript
Animal 构造函数
Cat 构造函数
cat speak
Cat 析构函数
Animal 纯虚析构函数
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226965.jpg)

