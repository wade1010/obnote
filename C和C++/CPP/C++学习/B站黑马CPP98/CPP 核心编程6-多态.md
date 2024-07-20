CPP 核心编程6-多态

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236449.jpg)

```
#include "iostream"
using namespace std;

//多态
class Animal
{
public:
    void speak()
    {
        cout << "动物在说话" << endl;
    }
};

class Cat : public Animal
{
public:
    void speak()
    {
        cout << "cat在说话" << endl;
    }
};

//地址早绑定  在编辑阶段确定函数地址
//如果想执行让猫说话,那么这个函数地址就不能提前绑定,需要在运行阶段进行绑定,地址晚绑定
void speak(Animal &animal) // Animal & animal = cat;
{
    animal.speak();
}
int main()
{
    Cat cat;
    speak(cat); //输出的是动物在说话
    return 0;
}
动物在说话
```

想要让猫说话就需要将Animal 的speak方法定义为虚函数

```
#include "iostream"
using namespace std;

//多态
class Animal
{
public:
    //虚函数
    virtual void speak()
    {
        cout << "动物在说话" << endl;
    }
};

class Cat : public Animal
{
public:
    void speak()
    {
        cout << "cat在说话" << endl;
    }
};

//地址早绑定  在编辑阶段确定函数地址
//如果想执行让猫说话,那么这个函数地址就不能提前绑定,需要在运行阶段进行绑定,地址晚绑定
void speak(Animal &animal) // Animal & animal = cat;
{
    animal.speak();
}
int main()
{
    Cat cat;
    speak(cat); //输出的cat在说话
    return 0;
}
cat在说话
```

多态满足条件

1 有继承关系

2 子类要重写父类的虚函数

动态多态的使用

父类的指针或引用指向子类对象

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236107.jpg)

多态的原理：

```
#include "iostream"

using namespace std;
class Animal {
public:
     virtual void speak(){
        cout << "动物在说话" << endl;
    }
};
class Animal2 {
public:
    void speak(){
        cout << "动物在说话" << endl;
    }
};
void test1(){
    //空对象的大小为1
    cout << "sizeof Animal2:"<<sizeof(Animal2) << endl;
    //64位系统下，虚函数占8个字节，也就是一个指针的大小
    cout << "sizeof Animal:"<<sizeof(Animal) << endl;
}
int main() {
    test1();
    return 0;
}
sizeof Animal:8
sizeof Animal2:1
```

空对象内存分布：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236671.jpg)

加虚函数后：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236177.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236334.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236849.jpg)

```
#include "iostream"

using namespace std;
class Animal {
public:
     virtual void speak(){
        cout << "动物在说话" << endl;
    }
};
class Cat: public Animal {
public:
    virtual void speak(){
        cout << "小猫在说话" << endl;
    }
};
void speak(Animal & animal){
    animal.speak();
}
void test1(){
    Cat cat;
    speak(cat);
}
int main() {
    test1();
    return 0;
}
小猫在说话

```

 

原理：

由于Animal些了个虚函数，类结构发生了改变，多了一个指针，这个指针叫做虚函数表指针，虚函数表的内部记录了虚函数的入口地址，当子类重写了这个虚函数的时候，会把自身虚函数表中的函数给替换掉，替换为子类的函数，所以当你用父类的引用指向子类对象的时候，由于你本身是一个子类对象，所以当你去调用speak时，会在子类去找真正的函数地址。

Cat没发生重写时Cat的内存分布：

![](images/WEBRESOURCE83fca9e78bdffb357832ce9fbd007616截图.png)

Cat发生重写时Cat的内存分布：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236788.jpg)

多态案例：

开闭原则：对修改进行关闭，对扩展进行开放

```
#include "iostream"

using namespace std;

class AbstractCalculator {
public:
    virtual int getResult() = 0;

    int m_Num1;
    int m_Num2;
};

class AddCalculator : public AbstractCalculator {
public:
    virtual int getResult() {
        return m_Num1 + m_Num2;
    }
};

class SubCalculator : public AbstractCalculator {
public:
    virtual int getResult() {
        return m_Num1 - m_Num2;
    }
};

class MulCalculator : public AbstractCalculator {
public:
    virtual int getResult() {
        return m_Num1 * m_Num2;
    }
};

void test() {
//多态使用：父类指针或者引用指向子类对象
    AbstractCalculator *ac = new MulCalculator;
    ac->m_Num1 = 10;
    ac->m_Num2 = 20;
    cout << ac->getResult() << endl;
    //开辟在堆区，用完记得销毁
    delete ac;
}

int main() {
    test();
    return 0;
}
```

总结：多态的优点：

代码组织结构清晰

可读性强

利于前期和后期的扩展以及维护

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236821.jpg)

```
#include "iostream"

using namespace std;

//纯虚函数和抽象类
class Base {
public:
    //纯虚函数
    //只要有一个纯虚函数，这个类就称为抽象类
    //抽象类特点：
    //1 无法实例化对象
    //2 抽象类的子类，必须重写父类中的纯虚函数，否则也属于抽象类
    virtual void func() = 0;
};

class Son : public Base {
public:
    void func() {
        cout << "son func函数调用" << endl;
    }
};

class Son2 : public Base {
public:
    void func() {
        cout << "son2 func函数调用" << endl;
    }
};

void test() {
//    Base b;//抽象类时无法实例化对象
//    new Base;//抽象类时无法实例化对象

//Son s;//子类必须重写父类中的纯虚函数，否则无法实例化对象

    Base *base = new Son;
    base->func();
    Base *base2 = new Son2;
    base2->func();
}

int main() {
    test();
    return 0;
}
son func函数调用
son2 func函数调用 
```

```
#include "iostream"

using namespace std;

//多态案例2 制作饮品
class AbstractDrink {
public:
    //煮水
    virtual void Boil() = 0;

    //冲泡
    virtual void Brew() = 0;

    //倒入杯中
    virtual void PourInCup() = 0;

    //加入辅料
    virtual void PutSomething() = 0;

    //制作饮品
    void makeDrink() {
        Boil();
        Brew();
        PourInCup();
        PutSomething();
    }
};

class Coffee : public AbstractDrink {
public:
    //煮水
    virtual void Boil() {
        cout << "咖啡煮水" << endl;
    }

    //冲泡
    virtual void Brew() {
        cout << "咖啡冲泡" << endl;
    }

    //倒入杯中
    virtual void PourInCup() {
        cout << "咖啡倒入杯中" << endl;
    };

    //加入辅料
    virtual void PutSomething() {
        cout << "咖啡加入辅料" << endl;
    }
};

class Tea : public AbstractDrink {
public:
    //煮水
    virtual void Boil() {
        cout << "茶叶煮水" << endl;
    }

    //冲泡
    virtual void Brew() {
        cout << "茶叶冲泡" << endl;
    }

    //倒入杯中
    virtual void PourInCup() {
        cout << "茶叶倒入杯中" << endl;
    };

    //加入辅料
    virtual void PutSomething() {
        cout << "茶叶加入辅料" << endl;
    }
};

void doWork(AbstractDrink *ad) {
    ad->makeDrink();
    delete ad;//释放
}

int main() {
    doWork(new Coffee);
    cout << "------------------------" << endl;
    doWork(new Tea);
    return 0;
}
咖啡煮水
咖啡冲泡
咖啡倒入杯中
咖啡加入辅料
------------------------
茶叶煮水
茶叶冲泡
茶叶倒入杯中
茶叶加入辅料
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236476.jpg)

虚析构和纯虚析构

下面有问题的代码：

```
#include "iostream"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal构造函数调用" << endl;
    }

    ~Animal() {
        cout << "Animal的析构函数调用" << endl;
    }

    //纯虚函数
    virtual void speak() = 0;
};

class Cat : public Animal {
public:
    Cat(string name) {
        cout << "Cat的构造函数调用" << endl;
        m_Name = new string(name);
    }

    ~Cat() {
        if (m_Name != nullptr) {
            cout << "Cat析构函数调用" << endl;
            delete m_Name;
            m_Name = nullptr;
        }
    }

    virtual void speak() {
        cout << *m_Name << "小猫在说话" << endl;
    }

    string *m_Name;
};

void test1() {
    Animal *animal = new Cat("Tom");
    animal->speak();
    //父类指针在析构的时候，不会调用子类中析构函数，
    //导致子类如果有堆区的属性，会出现内存泄漏
    delete animal;
}

int main() {
    test1();
    return 0;
}
Animal构造函数调用
Cat的构造函数调用
Tom小猫在说话
Animal的析构函数调用
```

上面没有输出Cat的析构函数

解决上面的办法也很简单，就是把父类Animal的析构函数改为虚析构函数

代码如下：

```
#include "iostream"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal构造函数调用" << endl;
    }

    //利用虚析构可以解决 父类指针释放子类对象时不干净的问题
    virtual ~Animal() {
        cout << "Animal的析构函数调用" << endl;
    }

    //纯虚函数
    virtual void speak() = 0;
};

class Cat : public Animal {
public:
    Cat(string name) {
        cout << "Cat的构造函数调用" << endl;
        m_Name = new string(name);
    }

    ~Cat() {
        if (m_Name != nullptr) {
            cout << "Cat析构函数调用" << endl;
            delete m_Name;
            m_Name = nullptr;
        }
    }

    virtual void speak() {
        cout << *m_Name << "小猫在说话" << endl;
    }

    string *m_Name;
};

void test1() {
    Animal *animal = new Cat("Tom");
    animal->speak();
    //父类指针在析构的时候，不会调用子类中析构函数，
    // 导致子类如果有堆区的属性，会出现内存泄漏
    delete animal;
}

int main() {
    test1();
    return 0;
}
Animal构造函数调用
Cat的构造函数调用
Tom小猫在说话
Cat析构函数调用
Animal的析构函数调用
```

纯虚析构，直接执行会报错，大概提示就是无法解析的外部命令

```
#include "iostream"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal构造函数调用" << endl;
    }

    //利用虚析构可以解决 父类指针释放子类对象时不干净的问题
//    virtual ~Animal() {
//        cout << "Animal的析构函数调用" << endl;
//    }
    //纯虚析构
    virtual  ~Animal() = 0;

    //纯虚函数
    virtual void speak() = 0;
};

class Cat : public Animal {
public:
    Cat(string name) {
        cout << "Cat的构造函数调用" << endl;
        m_Name = new string(name);
    }

    ~Cat() {
        if (m_Name != nullptr) {
            cout << "Cat析构函数调用" << endl;
            delete m_Name;
            m_Name = nullptr;
        }
    }

    virtual void speak() {
        cout << *m_Name << "小猫在说话" << endl;
    }

    string *m_Name;
};

void test1() {
    Animal *animal = new Cat("Tom");
    animal->speak();
    //父类指针在析构的时候，不会调用子类中析构函数，
    // 导致子类如果有堆区的属性，会出现内存泄漏
    delete animal;
}

int main() {
    test1();
    return 0;
}
```

虚析构的时候，是有函数的实现的，

但是上面的纯虚析构没有实现，比如父类中也有一些数据开辟到了堆区，析构函数实现就有用了，父类堆区实现在父类析构函数中实现。

解决方案，在类外实现析构函数

```
Animal::~Animal() {
    cout << "Animal纯虚析构函数调用" << endl;
}
```

```
#include "iostream"

using namespace std;

//虚析构和纯虚析构
class Animal {
public:
    Animal() {
        cout << "Animal构造函数调用" << endl;
    }

    //利用虚析构可以解决 父类指针释放子类对象时不干净的问题
//    virtual ~Animal() {
//        cout << "Animal的析构函数调用" << endl;
//    }
    //纯虚析构 需要声明，也需要实现（类外实现）
    //有了纯虚析构之后，这个类属于抽象类，无法实例化
    virtual  ~Animal() = 0;

    //纯虚函数
    virtual void speak() = 0;
};

Animal::~Animal() {
    cout << "Animal纯虚析构函数调用" << endl;
}

class Cat : public Animal {
public:
    Cat(string name) {
        cout << "Cat的构造函数调用" << endl;
        m_Name = new string(name);
    }

    ~Cat() {
        if (m_Name != nullptr) {
            cout << "Cat析构函数调用" << endl;
            delete m_Name;
            m_Name = nullptr;
        }
    }

    virtual void speak() {
        cout << *m_Name << "小猫在说话" << endl;
    }

    string *m_Name;
};

void test1() {
    Animal *animal = new Cat("Tom");
    animal->speak();
    //父类指针在析构的时候，不会调用子类中析构函数，
    // 导致子类如果有堆区的属性，会出现内存泄漏
    delete animal;
}

int main() {
    test1();
    return 0;
}
Animal构造函数调用
Cat的构造函数调用
Tom小猫在说话
Cat析构函数调用
Animal纯虚析构函数调用
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236866.jpg)

多态案例3 电脑组装

![](https://gitee.com/hxc8/images3/raw/master/img/202407172236376.jpg)

```
#include "iostream"

using namespace std;

class CPU {
public:
    virtual void calculator() = 0;
};

class VideoCard {
public:
    virtual void display() = 0;
};

class Memory {
public:
    virtual void storage() = 0;
};

class Computer {
public:
    Computer(CPU *cpu, VideoCard *vCard, Memory *mem) {
        m_cpu = cpu;
        m_vCard = vCard;
        m_mem = mem;
    }

    void work() {
        m_cpu->calculator();
        m_vCard->display();
        m_mem->storage();
    }

    //提供析构函数来释放3个零件
    ~Computer() {
        if (m_cpu != nullptr) {
            delete m_cpu;
            m_cpu = nullptr;
        }
        if (m_vCard != nullptr) {
            delete m_vCard;
            m_vCard = nullptr;
        }
        if (m_mem != nullptr) {
            delete m_mem;
            m_mem = nullptr;
        }
    }

private:
    CPU *m_cpu;
    VideoCard *m_vCard;
    Memory *m_mem;
};

//具体厂商 Intel
class IntelCPU : public CPU {
public:
    virtual void calculator() {
        cout << "Intel CPU" << endl;
    }
};

class IntelVideoCard : public VideoCard {
public:
    virtual void display() {
        cout << "Intel VideoCard" << endl;
    }

};

class IntelMemory : public Memory {
public:
    virtual void storage() {
        cout << "Intel Memory" << endl;
    }
};

//具体厂商 Lenovo
class LenovoCPU : public CPU {
public:
    virtual void calculator() {
        cout << "Lenovo CPU" << endl;
    }
};

class LenovoVideoCard : public VideoCard {
public:
    virtual void display() {
        cout << "Lenovo VideoCard" << endl;
    }

};

class LenovoMemory : public Memory {
public:
    virtual void storage() {
        cout << "Lenovo Memory" << endl;
    }
};

void test() {
    //创建第一台电脑
    Computer *computer = new Computer(new LenovoCPU, new IntelVideoCard, new IntelMemory);
    computer->work();
    cout << "------------------" << endl;
    //创建第二台电脑
    CPU *cpu2 = new IntelCPU;
    VideoCard *vc2 = new IntelVideoCard;
    Memory *mem2 = new LenovoMemory;
    Computer *computer2 = new Computer(cpu2, vc2, mem2);
    computer2->work();
}

int main() {
    test();
    return 0;
}

Lenovo CPU
Intel VideoCard
Intel Memory
------------------
Intel CPU
Intel VideoCard
Lenovo Memory

```