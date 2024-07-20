

![](images/1EF328FB40ED43FCBDA18CA27E8144ADimage.png)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231859.jpg)





```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

void myPrint(int v) {
    cout << v << endl;
}

/*
 * vector 容器存放内置数据类型
 */

void test() {
    //创建一个vector容器数组
    vector<int> v;
    //向容器中插入数据
    v.push_back(1);
    v.push_back(2);
    v.push_back(3);
    v.push_back(14);
    v.push_back(5);


    //第一种遍历方式
//    //通过迭代器访问容器中的数据
//    vector<int>::iterator itBegin = v.begin();//起始迭代器 指向容器中第一个元素
//    vector<int>::iterator itEnd = v.end();//结束迭代器，指向容器中最后一个元素的下一个位置
//

//    while (itBegin != itEnd) {
//        cout << *itBegin << endl;
//        itBegin++;
//    }


//第二种遍历方式
//    for (vector<int>::iterator it = v.begin(); it != v.end(); it++)
//        cout << *it << endl;
//}

//第三种遍历方式
    for_each(v.begin(), v.end(), myPrint);
}

int main() {
    test();
    return 0;
}

```





存放自定义类型数据

```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;


/*
 * vector 容器存放自定义数据类型
 */

class Person {
public:
    Person(string n, int a) {
        name = n;
        age = a;
    }

    string name;
    int age;

};

void myPrint(int v) {
    cout << v << endl;
}

void test() {
    //创建一个vector容器数组
    vector<Person> v;
    //向容器中插入数据
    Person p1("aaa", 10);
    Person p2("bbb", 20);
    Person p3("ccc", 30);
    Person p4("ddd", 40);
    Person p5("eee", 50);
    v.push_back(p1);
    v.push_back(p2);
    v.push_back(p3);
    v.push_back(p4);
    v.push_back(p5);


    /*vector<Person>::iterator itBegin = v.begin();//起始迭代器 指向容器中第一个元素
    vector<Person>::iterator itEnd = v.end();//结束迭代器，指向容器中最后一个元素的下一个位置
    while (itBegin != itEnd) {
//        cout << (*itBegin).name << ":" << (*itBegin).age << endl;
        cout << itBegin->name << ":" << itBegin->age << endl;

        itBegin++;
    }*/

}

//存放自定义类型 指针
void test2() {
    vector<Person *> v;
    //向容器中插入数据
    Person p1("aaa", 10);
    Person p2("bbb", 20);
    Person p3("ccc", 30);
    Person p4("ddd", 40);
    Person p5("eee", 50);
    v.push_back(&p1);
    v.push_back(&p2);
    v.push_back(&p3);
    v.push_back(&p4);
    v.push_back(&p5);

    for (vector<Person *>::iterator it = v.begin(); it != v.end(); it++) {
//        cout << (*(*it)).name << " " << (*(*it)).age << endl;
        cout << (*it)->name << " " << (*it)->age << endl;
    }
}

int main() {
//    test();
    test2();
    return 0;
}

```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231465.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;


/*
 * 容器嵌套容器
 */

void test() {
    vector<vector<int>> v;
    vector<int> v1;
    vector<int> v2;
    vector<int> v3;
    vector<int> v4;
    //向小容器中添加数据
    for (int i = 0; i < 4; i++) {
        v1.push_back(i + 1);
        v2.push_back(i + 2);
        v3.push_back(i + 3);
        v4.push_back(i + 4);
    }
    v.push_back(v1);
    v.push_back(v2);
    v.push_back(v3);
    v.push_back(v4);

    //通过大容器，把所有数据遍历一遍
    for (vector<vector<int>>::iterator it = v.begin(); it != v.end(); it++) {
        for (vector<int>::iterator subIt = (*it).begin(); subIt != (*it).end(); subIt++) {
            cout << *subIt << " ";
        }
        cout << endl;
    }

}

int main() {
    test();
    return 0;
}
```

