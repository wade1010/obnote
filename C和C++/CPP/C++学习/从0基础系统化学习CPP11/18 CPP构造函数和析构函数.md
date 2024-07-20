18 CPP构造函数和析构函数

1）构造函数

语法：类名(){.....}

1 访问权限一般是public,有时候可以设置为private，比如单例的时候 

2 函数名称必须与类名相同

3 没有返回值，也不写void

4 可以有参数，可以重载，可以有默认参数

5 创建对象时会自动调用一次，**不能手工调用**。

2）析构函数

语法：~类名(){....}

1 访问权限一般是public,有时候可以设置为private

2 函数名称必须与类名相同,且加~

3 没有返回值，也不写void

4  *没有参数，不能重载*

*5 销毁对象时会自动调用一次，****但是可以手工调用****。*

*手动调用析构函数*

```
#include "iostream"

using namespace std;

class Person {
public:
    ~Person() {
        cout << "调用了析构函数" << endl;
    }
};

void test() {
    Person p;
    p.~Person();//手动调用析构函数
}

int main() {
    test();
    return 0;
}
//调用了析构函数
//调用了析构函数
```

注意：

1 如果没有提供构造/析构函数，编译器将提供空实现的构造/析构函数。

2 如果提供了构造/析构函数，编译器将不提供空实现的构造/析构函数。

3 创建对象的时候，如果重载了构造函数，编译器根据实参匹配相应的构造函数，没有参数的构造函数也叫默认构造函数。

4 创建对象的时候不要再对象名后面加空的圆括号，编译器误认为是声明函数。（如果没有构造函数、构造函数没有参数、构造函数的参数都有默认参数）（后面有说明）

5** 在构造函数名后面加括号和参数不是调用构造函数，是创建匿名对象。 （后面有说明）**

6 接受一个参数的构造函数允许使用赋值语法将对象初始化为一个值（可能会导致问题，不推荐）(后面有说明)

7 以下两行代码有本质的区别：

```
Person p = Person("hello", 20);//显示创建对象

Person p;//创建对象
p = Person("hello", 20);//创建匿名对象，然后给现有的对象赋值
```

8 用new/delete 创建/销毁对象时，也会调用构造函数/析构函数。

9 不建议在构造/析构函数中写太多工作（只能成功不会失败，因为没有返回值）

10 除了初始化，不建议让构造函数做太多工作（只能成功不会失败）

11 c++11支持使用统一初始化列表 （后面有说明）

12 如果类的成员也是类，创建对象的时候，先构造成员类；销毁对象时，先析构成员类。

4** 创建对象的时候不要再对象名后面加空的圆括号，编译器误认为是声明函数。（如果没有构造函数、构造函数没有参数、构造函数的参数都有默认参数）**的代码演示

```
#include <iostream>
#include <cstring>

using namespace std;

//创建对象的时候不要再对象名后面加空的圆括号，编译器误认为是声明函数。
// （如果没有构造函数、构造函数没有参数、构造函数的参数都有默认参数）
class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        memset(m_Hobby, 0, sizeof(m_Hobby));
    }

    Person(string name) {
        m_Name = name;
        m_Age = 0;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用了构造函数" << endl;
    }

    Person(string name, int age) {
        m_Name = name;
        m_Age = age;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用了构造函数" << endl;
    }

    void show() {
        cout << "show" << endl;
    }

    ~Person() {
        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
};

void test() {
    Person p();//编译不会报错
//    编译warning如下 empty parentheses interpreted as a function declaration
//意思就是 空括号被解释为函数声明
//    p.show();//调用不了
//其实Person p();的意思是声明一个函数返回值为Person的函数，函数名叫p
}

int main() {
    test();
    return 0;
}
```

5 **在构造函数名后面加括号和参数不是调用构造函数，是创建匿名对象。**的代码演示

```
#include <iostream>

using namespace std;

class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        memset(m_Hobby, 0, sizeof(m_Hobby));
//        cout << "调用了构造函数Person() " << endl;

    }

    Person(string name) {
        Person();
        m_Name = name;
//        m_Age = 0;
//        memset(m_Hobby, 0, sizeof(m_Hobby));
//        cout << "调用了构造函数Person(string name)" << endl;
    }

    Person(string name, int age) {
        Person();
        m_Name = name;
        m_Age = age;
//        memset(m_Hobby, 0, sizeof(m_Hobby));
//        cout << "调用了构造函数Person(string name, int age)" << endl;
    }

    void show() {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << " 兴趣：" << m_Hobby << endl;
    }

    ~Person() {
//        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
};

void test() {
    Person p("kobe", 22);
    p.show();
}

int main() {
    test();
    return 0;
}
/*
 *调用输出内容为：
姓名：kobe，年龄：22 兴趣：�

如果粗心的人，这事就这么过了，留下巨大的bug，因为没有调用构造函数
 16行 24行的 Person(); 的真正含义不是调用构造函数，而是创建一个匿名对象，也叫临时对象
 临时对象的特点，没有名字，创建之后立马就销毁（;后面就开始销毁）
 */
```

证明下创建后就销毁了。启用打印日志

```
#include <iostream>

using namespace std;

class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用了构造函数Person() " << endl;

    }

    Person(string name) {
        Person();
        m_Name = name;
//        m_Age = 0;
//        memset(m_Hobby, 0, sizeof(m_Hobby));
//        cout << "调用了构造函数Person(string name)" << endl;
    }

    Person(string name, int age) {
        cout << "start" << endl;
        Person();
        cout << "end" << endl;
        m_Name = name;
        m_Age = age;
//        memset(m_Hobby, 0, sizeof(m_Hobby));
//        cout << "调用了构造函数Person(string name, int age)" << endl;
    }

    void show() {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << " 兴趣：" << m_Hobby << endl;
    }

    ~Person() {
        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
};

void test() {
    Person p("kobe", 22);
    p.show();
}

int main() {
    test();
    return 0;
}
/*
 *调用输出内容为：
start
调用了构造函数Person()
调用了析构函数
end
姓名：kobe，年龄：22 兴趣：�
调用了析构函数

 可以从上面输出结果看出
start
调用了构造函数Person()
调用了析构函数
end
 上面输出是25行 Person();  临时对象 创建和销毁时调用的

 */
```

构造函数没被调用，后果就很难说了，这个要看你的类里面有什么数据类型了，上面的例子不调用构造函数问题不大，如果类里面有指针，那就很危险了。

视频里面说的是：（他的环境是VS）

如下面的代码，加入m_ptr指针，如果构造函数没有被调用，指针不会初始化为空，并且别的地方也没有为指针分配内存，那么m_ptr就是野指针了，然后析构里面的delete m_ptr可能会让程序崩溃。

但是我g++ 测试 delete 没问题 

m_ptr默认就是0x0

```
#include <iostream>
#include <cstring>
using namespace std;

class Person
{
public:
    Person()
    {
        m_Name.clear();
        m_Age = 0;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用了构造函数Person() 1" << endl;
    }

    Person(string name)
    {
        Person();
        m_Name = name;
        //        m_Age = 0;
        //        memset(m_Hobby, 0, sizeof(m_Hobby));
        //        cout << "调用了构造函数Person(string name)" << endl;
    }

    Person(string name, int age)
    {
        cout << "start" << endl;
        Person();
        cout << "end" << endl;
        m_Name = name;
        m_Age = age;
        //        memset(m_Hobby, 0, sizeof(m_Hobby));
        //        cout << "调用了构造函数Person(string name, int age)" << endl;
    }

    void show()
    {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << " 兴趣：" << m_Hobby << endl;
    }

    ~Person()
    {
        //        delete m_ptr;//直接写也行，因为对空指针delete是安全的
        cout << m_ptr << endl;
        if (m_ptr != nullptr)
        { // 这样写也行
            delete m_ptr;
            m_ptr = nullptr;
        }
        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
    int *m_ptr;
};

void test()
{
    Person p("kobe", 22);
    p.show();
}

int main()
{
    test();
    return 0;
}
/*
start
调用了构造函数Person() 1
0x0
调用了析构函数
end
姓名：kobe，年龄：22 兴趣：
0x0
调用了析构函数
 */
```

6 接受一个参数的构造函数允许使用赋值语法将对象初始化为一个值（可能会导致问题，不推荐）

```
#include <iostream>

using namespace std;

class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
    }

    Person(string name) {
        m_Name = name;
        m_Age = 0;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
    }

    Person(int age) {
        m_Name.clear();
        m_Age = age;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
    }

    void show() {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << " 兴趣：" << m_Hobby << endl;
    }

    ~Person() {
//        delete m_ptr;//直接写也行，因为对空指针delete是安全的
        cout << m_ptr << endl;
        if (m_ptr != nullptr) {//这样写也行
            delete m_ptr;
            m_ptr = nullptr;
        }
        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
    int *m_ptr;
};

void test() {
    Person p = string("hello");
    p.show();
    Person p2 = 11;
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
姓名：hello，年龄：0 兴趣：
姓名：，年龄：11 兴趣：
0x0
调用了析构函数
0x0
调用了析构函数
 */
```

11 c++11支持使用统一初始化列表

```
#include <iostream>

using namespace std;

class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用构造函数Person()" << endl;
    }

    Person(string name, int age) {
        m_Name = name;
        m_Age = age;
        m_ptr = nullptr;
        memset(m_Hobby, 0, sizeof(m_Hobby));
        cout << "调用构造函数Person(string name, int age)" << endl;
    }

    void show() {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << " 兴趣：" << m_Hobby << endl;
    }

    ~Person() {
        cout << "调用了析构函数" << endl;
    }

private:
    string m_Name;
    int m_Age;
    char m_Hobby[30];
    int *m_ptr;
};

void test() {

    Person p = {"hello", 19};
    Person p2{"hello", 19};
    Person *ptr = new Person{"hello", 19};
}

int main() {
    test();
    return 0;
}
/*

 */
```