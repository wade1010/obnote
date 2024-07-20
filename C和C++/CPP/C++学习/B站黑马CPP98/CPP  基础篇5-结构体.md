CPP  基础篇5-结构体

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238478.jpg)

```
#include "iostream"

using namespace std;

//1 创建学生数据类型：学生包括 姓名 年龄 分数
//自定义数据类型 一些类型集合组成的一个类型
//语法  struct 类型名称 {成员列表}
struct Student {
    //成员列表
    string name;
    int age;
    double score;
};
//2 通过学生类型创建具体学生
//2.1 struct Student stu;
//2.2 struct Student stu2={name:""}
//2.3 在定义结构体的时候顺便创建结构体变量

//3 struct 关键字创建的时候可以省略  定义的时候不可以省略

int main() {
    //2 通过学生类型创建具体学生
//2.1 struct Student stu;
//    struct Student stu1;
    Student stu1;//省略 同上
    stu1.name = "张三";
    stu1.age = 18;
    stu1.score = 100;
    cout << "姓名：" << stu1.name << " 年龄:" << stu1.age << " 分数:" << stu1.score << endl;
//2.2 struct Student stu2={name:""}
//    struct Student stu2 = {
//            name:"李四",
//            age:20,
//            score:99
//    };
//    上面的省略写法如下
    struct Student stu2 = {
            "李四",
            20,
            99
    };
    cout << "姓名：" << stu2.name << " 年龄:" << stu2.age << " 分数:" << stu2.score << endl;

//2.3 在定义结构体的时候顺便创建结构体变量
    struct StudentDemo {
        string name;
        int age;
        double score;
    } stu3;//在定义结构体的时候顺便创建结构体变量

    stu3.name = "王五";
    stu3.age = 21;
    stu3.score = 81;
    cout << "姓名：" << stu3.name << " 年龄:" << stu3.age << " 分数:" << stu3.score << endl;
    return 0;
}
姓名：张三 年龄:18 分数:100
姓名：李四 年龄:20 分数:99
姓名：王五 年龄:21 分数:81
```

总结：

1 定义结构体时的关键字是struct，不可以省略

2 创建结构体变量时，关键字struct可以省略

3 结构体变量利用操作符 "." 访问成员

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238515.jpg)

```
#include "iostream"

using namespace std;

struct Student {
    string name;
};

int main() {
    //结构体数组
//    struct Student stu[3] = {
//            Student{"hello1"},
//            Student{"hello2"},
//            Student{"hello3"},
//    };
//省略写法如下
    struct Student stu[3] = {
            {"hello1"},
            {"hello2"},
            {"hello3"},
    };
    cout << "修改前的姓名：" << stu[2].name << endl;
    stu[2].name = "xiugai";
    cout << "修改后的姓名：" << stu[2].name << endl;
    for (int i = 0; i < 3; i++) {
        cout << "姓名:" << stu[i].name << endl;
    }
    return 0;
}

修改前的姓名：hello3
修改后的姓名：xiugai
姓名:hello1
姓名:hello2
姓名:xiugai
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238431.jpg)

```
#include "iostream"

using namespace std;
//结构体指针
struct Student {
    string name;
    int age;
    int score;
};

int main() {
    //创建学生结构体
    Student stu = {"张三", 31, 100};
    //通过指针指向结构体变量
//    struct Student *p = &stu;
//省略写法如下
    Student *p = &stu;
    //通过指针访问结构体变量中的数据
    cout << p->name << " " << p->age << " " << p->score << endl;
    return 0;
}
```

总结：结构体指针可以通过->操作符 来访问结构体中的成员

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238307.jpg)

```
#include "iostream"

using namespace std;

struct Student {
    int id;
    string name;
    int age;
};


struct Teacher {
    int id;
    string name;
    int age;
    struct Student stu;
};

int main() {
    Teacher t;
    t.id = 1;
    t.age = 55;
    t.name = "力王";

    Student stu = {101, "王五", 19};
    t.stu = stu;

    cout << t.name << "老师的学生是" << t.stu.name << endl;

    return 0;
}
力王老师的学生是王五

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238644.jpg)

```
#include "iostream"

using namespace std;
struct Student {
    string name;
};

void printInfo(struct Student s) {
    cout << "printInfo，姓名：" << s.name << endl;
}

void printInfo2(struct Student *p) {
    cout << "printInfo2，姓名：p->name:" << p->name << endl;
    cout << "printInfo2，姓名：(*p).name:" << (*p).name << endl;
}

int main() {
    Student s = {"张三"};
    printInfo(s);
    printInfo2(&s);
    return 0;
}
printInfo，姓名：张三
printInfo2，姓名：p->name:张三
printInfo2，姓名：(*p).name:张三

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238096.jpg)

```
#include "iostream"

using namespace std;
struct Student {
    string name;
};

//将函数的形参改为指针，可以减少内存空间，而且不会复制性的副本出来
void printInfo(const struct Student *const stu) {
//    stu->name = "aa";//编译报错 有const修饰
    cout << stu->name << endl;
}

int main() {
    struct Student s = {"张三"};
    printInfo(&s);
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238148.jpg)

```
#include "iostream"

using namespace std;

struct Student {
    string name;
    int score;
};
struct Teacher {
    string name;
    struct Student students[5];
};


void allocateSpace(Teacher pTeacher[], int len) {
//给老师开始赋值
    string nameSeed = "ABCDE";
    for (int i = 0; i < len; i++) {
        pTeacher[i].name = "Teacher_";
        pTeacher[i].name += nameSeed[i];
        //通过循环给每名老师所带的学生赋值
        for (int j = 0; j < 5; j++) {
            int randScore = rand() % 61 + 40;
            pTeacher[i].students[j] = {"学生", randScore};
            pTeacher[i].students[j].name += nameSeed[j];
        }
    }
}

void printInfo(Teacher ts[], int len) {
    for (int i = 0; i < len; i++) {
        cout << "老师姓名：" << ts[i].name << endl;
        for (int j = 0; j < 5; j++) {
            cout << "\t学生姓名：" << ts[i].students[j].name << " 分数：" << ts[i].students[j].score << endl;
        }
    }
}

int main() {
    //随机数终止
    srand(time(NULL));
    struct Teacher teachers[3];

    int len = sizeof(teachers) / sizeof(teachers[0]);
    allocateSpace(teachers, len);
    printInfo(teachers, len);
    return 0;
}
老师姓名：Teacher_A
        学生姓名：学生A 分数：92
        学生姓名：学生B 分数：90
        学生姓名：学生C 分数：82
        学生姓名：学生D 分数：97
        学生姓名：学生E 分数：64
老师姓名：Teacher_B
        学生姓名：学生A 分数：44
        学生姓名：学生B 分数：91
        学生姓名：学生C 分数：95
        学生姓名：学生D 分数：92
        学生姓名：学生E 分数：93
老师姓名：Teacher_C
        学生姓名：学生A 分数：49
        学生姓名：学生B 分数：53
        学生姓名：学生C 分数：91
        学生姓名：学生D 分数：42
        学生姓名：学生E 分数：54

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238176.jpg)

```
#include "iostream"

using namespace std;

struct Hero {
    string name;
    int age;
    string sex;
};

void bubbleSort(struct Hero h[], int len) {
    for (int i = 0; i < len - 1; i++) {
        for (int j = i + 1; j < len; j++) {
            if (h[i].age > h[j].age) {
                struct Hero temp = h[i];
                h[i] = h[j];
                h[j] = temp;
            }
        }
    }
}

void printHero(struct Hero hArr[], int len) {
    for (int i = 0; i < len; i++) {
        cout << "姓名:" << hArr[i].name << " 年龄:" << hArr[i].age << " 性别:" << hArr[i].sex << endl;
    }
}

int main() {
    struct Hero hArr[5] = {
            {"刘备", 23, "男"},
            {"关羽", 22, "男"},
            {"赵云", 20, "男"},
            {"貂蝉", 21, "男"},
            {"貂蝉", 19, "女"},
    };
    int len = sizeof(hArr) / sizeof(hArr[0]);
    printHero(hArr, len);
    cout << endl;
    bubbleSort(hArr, len);
    printHero(hArr, len);
    return 0;
}
姓名:刘备 年龄:23 性别:男
姓名:关羽 年龄:22 性别:男
姓名:赵云 年龄:20 性别:男
姓名:貂蝉 年龄:21 性别:男
姓名:貂蝉 年龄:19 性别:女

姓名:貂蝉 年龄:19 性别:女
姓名:赵云 年龄:20 性别:男
姓名:貂蝉 年龄:21 性别:男
姓名:关羽 年龄:22 性别:男
姓名:刘备 年龄:23 性别:男
```