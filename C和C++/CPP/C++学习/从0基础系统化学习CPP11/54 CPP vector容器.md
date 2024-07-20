54 CPP vector容器

vector 容器封装了动态数组

包含头文件 #include<vector>

vector类模板的声明：

template<class T,class Alloc=allocator<T>>

class vector{

private:

T *start_;

T *finish_;

T *end_;

......

};

分配器

各种STL容器模板都接受一个可选的模板参数，该参数指定使用哪个分配器对象来管理内存，如果省略了该模板参数的值，将默认使用allocator<T>,用new和delete分配和释放内存。

我们自己也可以提供分配器，例如采用内存池技术，不用new和delete。不过，对普通开发者来说，STL提供的分配器已经足够了。

```
#include <iostream>
#include <vector>
using namespace std;
void test()
{
    vector<int> v1;
    cout << v1.capacity() << "," << v1.size() << endl;
    vector<int> v2(8);
    cout << v2.capacity() << "," << v2.size() << endl;

    // vector<int> v3({1, 2, 3, 4, 5});//同下
    vector<int> v3 = {1, 2, 3, 4, 5};
    cout << v3.capacity() << "," << v3.size() << endl;

    vector<int> v4;
    v4.reserve(1000);
    v4.push_back(1);
    v4.push_back(2);
    v4.push_back(3);
    v4.push_back(4);
    cout << v4.capacity() << "," << v4.size() << endl;
    vector<int>(v4).swap(v4);
    cout << v4.capacity() << "," << v4.size() << endl;

    vector<int> v5;
    cout << "v5.data()=" << v5.data() << endl;

    vector<int> v6 = {1, 2, 3, 4, 5};
    cout << "v6.data()=" << v6.data() << endl;

    for (int i = 0; i < v6.size(); i++)
    {
        cout << v6[i] << " ";
    }
    cout << endl;
    v6.data()[0] = 100;
    v6.data()[1] = 200;
    *(v6.data() + 2) = 300;
    *(v6.data() + 3) = 400;
    for (int i = 0; i < v6.size(); i++)
    {
        cout << v6[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
0,0
8,8
5,5
1000,4
4,4
v5.data()=0
v6.data()=0x1091af0
1 2 3 4 5
100 200 300 400 5
```