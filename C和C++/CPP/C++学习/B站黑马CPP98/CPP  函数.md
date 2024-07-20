```javascript
#include "iostream"

using namespace std;

int add(int num1, int num2) {
    return num1 + num2;
}

void test1() {
    cout << "test1" << endl;
}

void test2(int a) {
    cout << a << endl;
}

int test3(int a) {
    return a;
}


int main() {
    test1();
    test2(3);
    test3(3);

    return 0;
}
```

