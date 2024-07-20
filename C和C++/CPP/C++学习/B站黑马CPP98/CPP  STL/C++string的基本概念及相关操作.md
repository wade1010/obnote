

![](https://gitee.com/hxc8/images3/raw/master/img/202407172234785.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string的构造函数
 *
 * string();  创建一个空的字符串，如 string str;
 * string(const char * s);   使用字符串s初始化
 * string(const string &str);  使用一个string对象初始化另一个string对象
 * string(int n,char c);     使用n的字符c初始化
 */
void test() {
    string str1;//默认构造
    const char *str = "hello world";
    string s2(str);
    cout << "s2=" << s2 << endl;
    string s3(s2);
    cout << "s3=" << s3 << endl;
    string s4(10, 'c');
    cout << "s4=" << s4 << endl;
}

int main() {
    test();
    return 0;
}
```



```javascript
s2=hello world
s3=hello world
s4=cccccccccc
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234455.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string的赋值操作
 *
 */
void test() {
    string str1;
    str1 = "hello world";
    cout << "str1=" << str1 << endl;
    string str2;
    str2 = str1;
    cout << "str2=" << str2 << endl;

    string str3;
    str3 = 'a';
    cout << "str3=" << str3 << endl;

    string str4;
    str4.assign("hello c++");
    cout << "str4=" << str4 << endl;

    string str5;
    str5.assign("hello c++", 5);//"hello"
    cout << "str5=" << str5 << endl;
    str5.assign(str4, 5);//" c++" ???
    cout << "str5=" << str5 << endl;

    string str6;
    str6.assign(str4);
    cout << "str6=" << str6 << endl;

    string str7;
    str7.assign(4,'c');
    cout << "str7=" << str7 << endl;

}

int main() {
    test();
    return 0;
}
```



```javascript
str1=hello world
str2=hello world
str3=a
str4=hello c++
str5=hello
str5= c++
str6=hello c++
str7=cccc
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234967.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234306.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string的拼接
 *
 */
void test() {
    string str1 = "我";
    str1 += "爱玩游戏";
    cout << str1 << endl;

    str1 += ':';
    cout << str1 << endl;

    string str2 = "LOL DNF";
    str1 += str2;
    cout << str1 << endl;

    string str3 = "I";
    str3.append(" love ");
    cout << str3 << endl;

    str3.append("game abcd", 4);
    cout << str3 << endl;

    str3.append(str2);
    cout << str3 << endl;

    string str4 = "I love ";
    str4.append(str2, 0, 3);
    cout << str4 << endl;

}

int main() {
    test();
    return 0;
}
```



```javascript
我爱玩游戏
我爱玩游戏:
我爱玩游戏:LOL DNF
I love 
I love game
I love gameLOL DNF
I love LOL
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234995.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string查找和替换
 *
 */
//查找
void test() {
    string str1 = "abcdefg";
    int pos = str1.find("de");
    cout << pos << endl;
    pos = str1.rfind("de");
    cout << pos << endl;

    string str2 = "abcdefgde";
    pos = str2.rfind("de");
    cout << pos << endl;
}


//替换
void test2() {
    string str1 = "abcdefg";
    str1.replace(1, 3, "1111");
    cout << str1 << endl;
}

int main() {
    test();
    test2();
    return 0;
}
```



```javascript
3
3
7
a1111efg
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234575.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234328.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string比较
 *
 */
void test() {
    string str1 = "hello world";
    string str2 = "hello world";
    if (str1.compare(str2) == 0) {
        cout << "str1等于str2" << endl;
    } else if (str1.compare(str2) > 0) {
        cout << "str1大于str2" << endl;
    } else {
        cout << "str1小于str2" << endl;
    }
}


int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234857.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234142.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string字符存取
 *
 */
void test() {
    string str = "hello";
    cout << str << endl;

    //1 通过中括号访问
    for (int i = 0; i < str.size(); i++) {
        cout << str[i] << endl;
    }

    cout << "---------------" << endl;

    //2 通过at访问单个字符
    for (int j = 0; j < str.size(); j++) {
        cout << str.at(j) << endl;
    }

    //修改
    str[0] = 'x';
    cout << str << endl;
    str.at(1) = 'x';
    cout << str << endl;
}


int main() {
    test();
    return 0;
}
```



```javascript
hello
h
e
l
l
o
---------------
h
e
l
l
o
xello
xxllo
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234567.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string插入和删除
 *
 */
void test() {
    string str = "hello world";
    str.insert(1, "xo");
    cout << str << endl;

    //删除
    str.erase(0, 2);
    cout << str << endl;
}


int main() {
    test();
    return 0;
}
```



```javascript
hxoello world
oello world
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234104.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *string子串
 *
 */
void test() {
    string str = "hello world";
    str = str.substr(0, 3);
    cout << str << endl;
}

void test2() {
    string str = "zhangsan@sina.com";
    //从邮件中获取 用户名信息
    int pos = str.find("@");
    string username = str.substr(0, pos);
    cout << username << endl;
}

int main() {
//    test();
    test2();
    return 0;
}
```



```javascript
hel
zhangsan
```

