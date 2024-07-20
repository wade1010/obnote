什么情况下应该使用指针，什么情况下使用引用？

1）有空指针，但是没有空引用，所以必须对引用赋初值

如果定义一个变量，用它来代表或者指向某个对象，也有可能，这个变量既不代表或指向任何对象，这样的话，用指针更好。

如果一个变量，必须一直代表一个对象，这个时候最好使用引用。

写代码的时候需要注意，指针可能为空，下面两个函数可以看出区别

void test(const int & a){

cout<< a << endl;

}

void test(int *ptr){

if(ptr != nullptr)

cout<< *ptr << endl;

}

2)指针可以被重新赋值，也就是说一会可以指向这个变量，一会可以指向另外一个变量，让指针指向不同的对象，

引用却总是指向它最初代表的那个对象，更专一，一直代表某一个对象。

string str1("a");

string str2("b");

string &rs = str1;

string *ptr = &str1;

rs = str2;  //此时rs扔指向str1，但是str1的值现在变成了“b”

ptr = &str2;   //ptr不再指向str1，此时str1的值没有变化，ptr指向了str2.

需要考虑不指向任何对象或者是说不同的时间段代表不同的对象，这个时候使用指针。

非常确定，一直代表某个对象，使用引用。

3）无法用指针来实现的时候

string name =  'hello"

cout << name[]0 <<endl;

name[0] = 'a';

运算符重载问的问题；

[] 就是运算符，而且是个二元运算符，说明有两个操作数，name是一个操作数，0也是一个操作数

char & String:operator[](int index){

return str[i];

}