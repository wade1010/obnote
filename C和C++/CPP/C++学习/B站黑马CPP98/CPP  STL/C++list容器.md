

![](https://gitee.com/hxc8/images3/raw/master/img/202407172232722.jpg)









![](https://gitee.com/hxc8/images3/raw/master/img/202407172232279.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232383.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232120.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172232950.jpg)



 

list容器中的迭代器，不管是在迭代器后面插入删除都不会影响迭代器，除非删除迭代器指向的元素。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172232764.jpg)





而 vector中



 

![](images/9A6974F557E44E959094940876409987image.png)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233698.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233150.jpg)



```javascript
using namespace std;

#include <iostream>
#include <list>

/*
 *list容器
 */
void printList(const list<int> &l) {
    for (list<int>::const_iterator i = l.begin(); i != l.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

void test() {
    list<int> l;
    l.push_back(1);
    l.push_back(2);
    l.push_back(3);
    printList(l);

    list<int> l2(l.begin(),l.end());
    printList(l2);

    list<int> l3(l2);
    printList(l3);

    list<int> l4(10,100);
    printList(l4);
}

int main() {
    test();
    return 0;
}
```









---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233613.jpg)



```javascript
using namespace std;

#include <iostream>
#include <list>

/*
 *list容器
 */
void printList(const list<int> &l) {
    for (list<int>::const_iterator i = l.begin(); i != l.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

void test() {
    list<int> l;
    l.push_back(1);
    l.push_back(2);
    l.push_back(3);
    printList(l);

    list<int> l2;
    l2 = l;
    printList(l2);


    list<int> l3;
    l3.assign(l2.begin(), l2.end());
    printList(l3);

    list<int> l4;
    l4.assign(10, 100);
    printList(l4);

    //交换

    list<int> lswap;
    lswap.assign(10, 1);
    cout << "交换前" << endl;
    printList(l4);
    printList(lswap);

    l4.swap(lswap);
    cout << "交换后" << endl;
    printList(l4);
    printList(lswap);
}

int main() {
    test();
    return 0;
}
```







---



![](images/E73946BC95DA48168CD6B5B04DAF8D0Aimage.png)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233981.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233629.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233072.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233428.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233782.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233345.jpg)







---



---













