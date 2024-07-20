迭代器可以理解为广义的指针，或者一种泛化的指针，可以近似理解为指针。

```
#include <iostream>
#include <vector>
using namespace std;

void test()
{
    int arr[6]{1, 2, 3, 4, 5, 6};
    vector<int> v(arr, arr + 6);
    cout << "v[1]=" << v[1] << endl;
    vector<int>::iterator it;
    for (it = v.begin(); it != v.end(); it++)
        cout << *it << " ";
    cout << endl;
}
int main()
{
    test();
    return 0;
}
```