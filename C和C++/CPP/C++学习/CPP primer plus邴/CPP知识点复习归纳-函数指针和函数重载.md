如何通过函数指针指向不同的重载版本？

```
#include <iostream>
using namespace std;
void print(int a);
void print(int a, int b);
void test()
{
    int x = 1, y = 2;
    // print(x);
    // print(x, y);

    //使用函数指针
    void (*p)(int) = print;
    p(x);

    void (*p2)(int, int) = print;
    p2(x, y);
}
int main()
{
    test();
    return 0;
}

void print(int a)
{
    cout << "a=" << a << endl;
}
void print(int a, int b)
{
    cout << a << endl;
    cout << b << endl;
}
a=1
1
2
```