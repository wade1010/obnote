![](https://gitee.com/hxc8/images2/raw/master/img/202407172223406.jpg)

之前用new和delete模拟了C++编译器分配内存和释放内存的方法，功能和效果与编译器默认提供的函数没什么区别，意义不大。

在实际开发中，重载new和delete运算符的主要目的是实现内存池。

内存池在高性能的服务程序中很常用。

1 是提升分配和归还的效率，对高性能的服务程序来说，频繁的new和delete也算是浪费时间，

2 减少内存碎片，碎片多了系统管理内存的效率就会下降，系统的性能也会下降。

```
class Person {
public:
    int m_age;
    int m_score;

    Person() {
        m_age = 1;
        m_score = 2;
    }

    ~Person() {
        cout << "调用析构" << endl;
    }

};
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223667.jpg)

打算申请连续的18字节的内存空间，也就是说内存池的大小是18字节。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223703.jpg)

组织数据的方法是，两个标志位，1个字节。表示后面的空间是否被占用  。（每行）后面的8个字节刚好可以存放一个Person类对象。标志位的取值只有0和1,0表示空闲，1表示占用。这样的话，18字节的内存池，可以存放2个Person对象。

当需要为超女分配内存的时候，先判断标志位是否被占用，如果没有被占用，就把标志位后面这个内存的地址返回。

```
#include <iostream>

using namespace std;

class Person {
public:
    int m_age;
    int m_score;
    static char *m_pool;//内存池的起始地址

    static bool initPool() {//初始化内存池的函数
        m_pool = (char *) malloc(18);//向系统申请18个字节的内存
        if (m_pool == nullptr) {
            return false;
        }
        memset(m_pool, 0, 18);//把内存池中的内容初始化为0
        cout << "内存的起始地址是：" << (void *) m_pool << endl;
        return true;
    }

    static void freePool() {
        if (m_pool == nullptr) {
            return;
        }
        free(m_pool);
        cout << "内存池已释放" << endl;
    }

    Person() {
        m_age = 1;
        m_score = 2;
    }

    ~Person() {
        cout << "调用析构" << endl;
    }

    void *operator new(size_t size) {
        //判断第一个位置是否空闲
        if (m_pool[0] == 0) {
            cout << "分配第一块内存:" << (void *) (m_pool + 1) << endl;
            m_pool[0] = 1;//把第一个位置标记为已分配
            return m_pool + 1;//返回第一个用于存放对象地址
        }
        //判断第二个位置是否空闲
        if (m_pool[9] == 0) {
            cout << "分配第二块内存：" << (void *) (m_pool + 9) << endl;
            m_pool[9] = 1;
            return m_pool + 9;
        }
        /*内存池用完，一般有3种方法：
         * 1 扩展内存池
         * 2 直接向系统申请内存
         * 3 返回空地址
         * */

        //如果以上两个位置都不可以用，那就直接向系统申请内存
        void *ptr = malloc(size);
        cout << "申请到的内存地址是：" << ptr << endl;
        return ptr;
    }

    void operator delete(void *ptr) {
        if (ptr == nullptr) {
            return;
        }
        //如果闯进来的地址是内存池的第一个位置（从0算起）
        if (ptr == m_pool + 1) {
            cout << "释放第一块内存" << endl;
            m_pool[0] = 0;//把第一个位置标记为空闲
            return;
        }
        if (ptr == m_pool + 9) {
            cout << "释放第二块内存" << endl;
            m_pool[9] = 0;//把第二个位置标记为空闲
            return;
        }

        //如果传进来的地址 不属于内存池，把它归还给系统。
        free(ptr);
    }

};

char *Person::m_pool = nullptr;

void test() {
//初始化内存池
    if (!Person::initPool()) {
        cout << "初始化内存池失败" << endl;
    }

    Person *p1 = new Person;
    cout << "p1的地址是：" << p1 << endl;
    Person *p2 = new Person;
    cout << "p2的地址是：" << p2 << endl;
    Person *p3 = new Person;
    cout << "p3的地址是：" << p3 << endl;

    delete p1;//将释放内存池的第一个位置

    Person *p4 = new Person;//将使用内存池的第一个位置
    cout << "p4的地址是：" << p4 << endl;

    delete p2;
    delete p3;
    delete p4;

    Person::freePool();
}

int main() {
    test();
    return 0;
}
/*
 内存的起始地址是：0x7f8133d058f0
分配第一块内存:0x7f8133d058f1
p1的地址是：0x7f8133d058f1
分配第二块内存：0x7f8133d058f9
p2的地址是：0x7f8133d058f9
申请到的内存地址是：0x7f8133d05910
p3的地址是：0x7f8133d05910
调用析构
释放第一块内存
分配第一块内存:0x7f8133d058f1
p4的地址是：0x7f8133d058f1
调用析构
释放第二块内存
调用析构
调用析构
释放第一块内存
内存池已释放
 */
```