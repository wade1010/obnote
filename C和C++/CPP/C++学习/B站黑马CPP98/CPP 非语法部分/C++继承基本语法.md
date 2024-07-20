

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227898.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;


class BasePage {
public:
    void header() {
        cout << "公共头部" << endl;
    }

    void footer() {
        cout << "公共底部" << endl;
    }

    void left() {
        cout << "公共左侧" << endl;
    }
};

//Java页面
class JavaPage : public BasePage {
public:
    void content() {
        cout << "Java内容" << endl;
    }

};

//Python页面
class PythonPage : public BasePage {
public:
    void content() {
        cout << "Python内容" << endl;
    }

};

//C++页面

class CPPPage : public BasePage {
public:
    void content() {
        cout << "CPP内容" << endl;
    }

};
//继承的好处，减少重复代码

int main() {
    JavaPage j;
    j.header();
    j.left();
    j.content();
    j.footer();
    PythonPage p;
    p.content();
    CPPPage cpp;
    cpp.content();

    return 0;
}
```

