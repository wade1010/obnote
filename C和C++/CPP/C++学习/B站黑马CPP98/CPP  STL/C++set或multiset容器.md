

![](https://gitee.com/hxc8/images3/raw/master/img/202407172231482.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172231861.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232932.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232495.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232003.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232475.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232850.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232032.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232534.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232049.jpg)



```javascript
using namespace std;

#include <iostream>
#include <set>

/*
 *
 */

void test() {
    set<int> s;
    pair<set<int>::iterator, bool> ret = s.insert(10);
    if (ret.second) {
        cout << "第一次插入成功" << endl;
    }

    pair<set<int>::iterator, bool> ret2 = s.insert(10);
    if (ret2.second) {
        cout << "第二次插入成功" << endl;
    } else {
        cout << "第二次插入失败" << endl;
    }

    multiset<int> ms;
    ms.insert(10);
    ms.insert(10);

    for (multiset<int>::iterator it = ms.begin(); it != ms.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;


}

int main() {
    test();
    return 0;
}

第一次插入成功
第二次插入失败
10 10 
```



---



![](images/9439CA62C7B5490893EC5518258F1BD6image.png)



```javascript
#include <iostream>
#include <set>

using namespace std;

/*
 *pair对组的使用
 */

void test() {
    //1
    pair<string, int> p("a", 1);
    cout << p.first << "  " << p.second << endl;
    //2
    pair<string, int> p2 = make_pair("b", 2);
    cout << p2.first << "  " << p2.second << endl;
}

int main() {
    test();
    return 0;
}
```





---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232977.jpg)





```javascript
#include <iostream>
#include <set>

using namespace std;

/*
 *set容器排序
 */
class MyCompare {
public:
    bool operator()(int v1, int v2) {
        return v1 > v2;
    }

};

void test() {
    set<int> s1;
    s1.insert(1);
    s1.insert(22);
    s1.insert(4);
    s1.insert(5);
    s1.insert(12);
    //默认是从小到大
    for (set<int>::iterator i = s1.begin(); i != s1.end(); ++i) {
        cout << *i << " ";
    }
    cout << endl;

}

//指定排序规则从大到小
void test2() {
    set<int, MyCompare> s1;
    s1.insert(1);
    s1.insert(22);
    s1.insert(4);
    s1.insert(5);
    s1.insert(12);
    //默认是从小到大
    for (set<int>::iterator i = s1.begin(); i != s1.end(); ++i) {
        cout << *i << " ";
    }
    cout << endl;
}

int main() {
    test();
    test2();
    return 0;
}
```



自定义数据类型排序

```javascript
#include <iostream>
#include <set>

using namespace std;

/*
 *set容器排序 自定义类型
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

class MyCompare {
public:
    bool operator()(const Person &p1, const Person &p2) {
        return p1.age > p2.age;
    }
};

void test() {
    //自定义数据类型 都会指定排序规则
    set<Person,MyCompare> s;
    Person p1("a", 1);
    Person p2("b", 2);
    Person p3("c", 3);
    Person p4("d", 4);
    s.insert(p1);
    s.insert(p2);
    s.insert(p3);
    s.insert(p4);
    for (set<Person>::iterator it = s.begin(); it != s.end(); it++) {
        cout << it->name << " " << it->age << endl;
    }

}

int main() {
    test();
    return 0;
}
```



```javascript
d 4
c 3
b 2
a 1
```

