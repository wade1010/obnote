

![](https://gitee.com/hxc8/images3/raw/master/img/202407172228014.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228931.jpg)



```javascript
#include "iostream"

using namespace std;

const double PI = 3.14;

/*
 * 设计一个圆类，求圆的周长
 *
 */

class Circle {
    //访问权限
    //公共权限
public:
    //属性
    //半径
    int radius;

    //行为
    //获取圆的周长
    double calculateZC() {
        return 2 * PI * radius;
    }
};

int main() {
    //通过类创建具体的对象
//    Circle c = {10}; 或者如下
    Circle c;
    c.radius = 10;
    cout << "圆的周长:" << c.calculateZC() << endl;
    return 0;
}
```



```javascript
#include "iostream"

using namespace std;

class Student {
public:
    string name;
    int id;

    void showStudent() {
        cout << "姓名:" << name << "id:" << id << endl;
    }

};

int main() {
    Student stu;
    stu.name = "bob";
    stu.id = 12321;
    stu.showStudent();
    return 0;
}
```





访问权限



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228814.jpg)

保护权限，继承时，子类也能访问。

是有权限，继承时，子类不能访问。



```javascript
#include "iostream"

using namespace std;

class Student {
public:
    string name;
protected:
    string car;
private:
    string password;
public:
    void init() {
        name = "fadfad";
        car = "car";
        password = "a123412";
    }
};

int main() {
    Student stu;
    stu.init();
    stu.name = "1";
//    stu.car = "1";//保护权限,在类外部访问不到
//    stu.password = "1";//私有权限,在类外部访问不到
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228906.jpg)



```javascript
#include "iostream"

using namespace std;

class Student {
    int age;//默认私有
};

struct Teacher {
    int age;//默认公共
};


int main() {
    Student stu;
    Teacher t;
//    stu.age = 1;//报错
    t.age = 1;
    return 0;
}
```

