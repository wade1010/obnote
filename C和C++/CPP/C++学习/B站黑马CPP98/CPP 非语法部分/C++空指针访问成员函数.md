

```javascript
#include "iostream"

using namespace std;

//1 解决名称冲突

class Person {
public:
    void print() {
        cout << "print" << endl;
    }

    void printAge() {
        if (this == NULL) {//没有这一判断，直接运行，目前跟学习视频里面的报错不一样，这里是不报错的，但是debug运行就有错误了
            return;
        }
//        cout << "age" << this->age << endl;//age其实就是this->age
        cout << "age" << this->age << endl;
    }

    int age;
};

void test() {
    Person *p = NULL;
    p->print();
    p->printAge();
}

int main() {
    test();
    return 0;
}
```

