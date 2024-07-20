CPP 核心编程11-谓词

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237132.jpg)

```
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

//谓词 仿函数 返回值类型时bool数据类型，称为谓词
//一元谓词
class GreaterFive {
public:
    bool operator()(int a) {
        return a > 5;
    }
};

void test() {
    vector<int> v;
    for (int i = 0; i < 10; i++) {
        v.push_back(i);
    }
    //查找容器中有没有大于5的值
    vector<int>::iterator it = find_if(v.begin(), v.end(), GreaterFive());//匿名对象（匿名函数对象）也可以使用下面代码
    if (it != v.end()) {
        cout << "method one found,num is " << *it << endl;
    } else {
        cout << "method one not found" << endl;
    }
    GreaterFive gf;
    vector<int>::iterator it2 = find_if(v.begin(), v.end(), gf);
    if (it != v.end()) {
        cout << "method two found,num is " << *it << endl;
    } else {
        cout << "method two not found" << endl;
    }
}

int main() {
    test();
    return 0;
}
method one found,num is 6
method two found,num is 6

```