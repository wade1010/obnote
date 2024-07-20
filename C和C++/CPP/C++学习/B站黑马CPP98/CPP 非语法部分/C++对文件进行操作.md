

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226080.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226825.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226688.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//对文件进行操作

//ofstream 写操作
//ifstream 读操作
//fstream 读写操作

void test1() {
    //1包含头文件 fstream
    //2创建流对象
    ofstream ofs;
    //3指定打开方式
    ofs.open("test1.txt", ios::out);
    //4写内容
    ofs << "姓名：bob" << endl;
    ofs << "性别：男" << endl;
    //5关闭文件
    ofs.close();
}

int main() {
    test1();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226326.jpg)







![](https://gitee.com/hxc8/images3/raw/master/img/202407172226898.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//读文件




void test1() {
//1包含头文件 fstream
//2创建流对象
    ifstream ifs;
//3打开文件文判断文件是否存在
    ifs.open("test1.txt", ios::in);
    if (!ifs.is_open()) {
        cout << "文件打开失败" << endl;
        return;
    }
//4读数据
//第一种
//    char buf[1024] = {0};
//    while (ifs >> buf) {
//        cout << buf << endl;
//        cout << "----" << endl;
//    }
//第二种
//    char buf[1024] = {0};
//    while (ifs.getline(buf, sizeof(buf))) {
//        cout << buf << endl;
//    }
    //第三种
//    string buf;
//    while (getline(ifs, buf)) {
//        cout << buf << endl;
//    }

    //第四种 不太建议使用
    char c;
    while ((c = ifs.get()) != EOF) {
        cout << c;
    }
//5关闭文件
    ifs.close();
}

int main() {
    test1();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226273.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172226616.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226188.jpg)





```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//二进制文件操作

class Person {
public:
    char name[64];
    int age;
};

//二进制文件写文件
void test1() {
    /*
     * 1 包含头文件
     * 2 创建流对象
     * 3 打开文件
     * 4 写入数据
     * 5 关闭文件
     */
//    ofstream ofs();
//    ofs.open("person.txt", ios::out | ios::binary);
    ofstream ofs("person.txt", ios::out | ios::binary);

    Person p = {"bob", 18};
    ofs.write((const char *) &p, sizeof(Person));
    ofs.close();
}

//二进制文件读文件
void test2() {
    ifstream ifs("person.txt", ios::in | ios::binary);
    if (!ifs.is_open()) {
        cout << "打开文件失败" << endl;
        return;
    }
    Person p;
    ifs.read((char *) &p, sizeof(Person));
    cout << p.name << "  " << p.age << endl;
    ifs.close();
}

int main() {
//    test1();
    test2();
    return 0;
}
```

