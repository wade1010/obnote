```
#include <iostream>
using namespace std;

class Point2D
{
public:
    Point2D(int _x, int _y) : x(_x), y(_y) {}
    virtual void print() const
    {
        cout << "(" << x << "," << y << ")" << endl;
    }
    virtual ~Point2D() {}

protected:
    int x;
    int y;
};

class Point3D : public Point2D
{
public:
    Point3D(int _x, int _y, int _z) : Point2D(_x, _y), z(_z) {}
    void print() const
    {
        cout << "(" << x << "," << y << "," << z << ")" << endl;
    }

private:
    int z;
};

int main()
{
    Point2D *p2d = new Point3D(1, 2, 3);
    p2d->print();

    Point2D *p2d2 = new Point2D(1, 2);
    p2d2->print();
    return 0;
}
```

g++ main.cpp -g -o main

gdb -q main

b *main

r

n

n

p p2d

```
➜ gdb -q main
Reading symbols from main...done.
(gdb) b *main
Breakpoint 1 at 0xb8a: file main.cpp, line 34.
(gdb) r
Starting program: /home/bob/Desktop/workspace/cpp/cpp_study/main 

Breakpoint 1, main () at main.cpp:34
34      {
(gdb) n
35          Point2D *p2d = new Point3D(1, 2, 3);
(gdb) n
36          p2d->print();
(gdb) p p2d
$1 = (Point2D *) 0x555555768e70
```

看下地址内容

```
(gdb) x/20x 0x555555768e70
0x555555768e70: 0x55755d10      0x00005555      0x00000001      0x00000002
0x555555768e80: 0x00000003      0x00000000      0x0000f181      0x00000000
0x555555768e90: 0x00000000      0x00000000      0x00000000      0x00000000
0x555555768ea0: 0x00000000      0x00000000      0x00000000      0x00000000
0x555555768eb0: 0x00000000      0x00000000      0x00000000      0x00000000
```

0x555555768e70这个地址存放的就是Point3D对象的内容，对象大小非常清楚，包括一个 虚函数表指针（0x55755d10      0x00005555）

然后就是3个数据成员（0x00000001、0x00000002、0x00000003）

观察虚表指针指向的内容

```
(gdb) x/20x 0x0000555555755d10
0x555555755d10 <_ZTV7Point3D+16>:       0x55554daa      0x00005555      0x55554e4a      0x00005555
0x555555755d20 <_ZTV7Point3D+32>:       0x55554e74      0x00005555      0x00000000      0x00000000
0x555555755d30 <_ZTV7Point2D+8>:        0x55755d68      0x00005555      0x55554ca2      0x00005555
0x555555755d40 <_ZTV7Point2D+24>:       0x55554d20      0x00005555      0x55554d3a      0x00005555
0x555555755d50 <_ZTI7Point3D>:  0xf7dc5438      0x00007fff      0x55554f30      0x00005555
或者
(gdb) x/20x 0x555555755d10
0x555555755d10 <_ZTV7Point3D+16>:       0x55554daa      0x00005555      0x55554e4a      0x00005555
0x555555755d20 <_ZTV7Point3D+32>:       0x55554e74      0x00005555      0x00000000      0x00000000
0x555555755d30 <_ZTV7Point2D+8>:        0x55755d68      0x00005555      0x55554ca2      0x00005555
0x555555755d40 <_ZTV7Point2D+24>:       0x55554d20      0x00005555      0x55554d3a      0x00005555
0x555555755d50 <_ZTI7Point3D>:  0xf7dc5438      0x00007fff      0x55554f30      0x00005555
```

可以看出有3个地址

0x55554daa      0x00005555      

0x55554e4a      0x00005555

0x55554e74      0x00005555

分别看下3个地址的内容

```
(gdb) x/20x 0x0000555555554daa
0x555555554daa <Point3D::print() const>:        0xe5894855      0x10ec8348      0xf87d8948      0x68358d48
0x555555554dba <Point3D::print() const+16>:     0x48000001      0x125c3d8d      0x47e80020      0x48fffffc
0x555555554dca <Point3D::print() const+32>:     0x8b48c289      0x408bf845      0x48c68908      0x83e8d789
0x555555554dda <Point3D::print() const+48>:     0x48fffffc      0x0143358d      0x89480000      0xfc24e8c7
0x555555554dea <Point3D::print() const+64>:     0x8948ffff      0x458b48c2      0x0c408bf8      0x8948c689
(gdb) x/20x 0x0000555555554e4a
0x555555554e4a <Point3D::~Point3D()>:   0xe5894855      0x10ec8348      0xf87d8948      0xb3158d48
0x555555554e5a <Point3D::~Point3D()+16>:        0x4800200e      0x48f8458b      0x8b481089      0x8948f845
0x555555554e6a <Point3D::~Point3D()+32>:        0xfeb0e8c7      0xc990ffff      0x485590c3      0x8348e589
0x555555554e7a <Point3D::~Point3D()+6>: 0x894810ec      0x8b48f87d      0x8948f845      0xffbee8c7
0x555555554e8a <Point3D::~Point3D()+22>:        0x8b48ffff      0x18bef845      0x48000000      0x93e8c789
(gdb) x/20x 0x0000555555554e74
0x555555554e74 <Point3D::~Point3D()>:   0xe5894855      0x10ec8348      0xf87d8948      0xf8458b48
0x555555554e84 <Point3D::~Point3D()+16>:        0xe8c78948      0xffffffbe      0xf8458b48      0x000018be
0x555555554e94 <Point3D::~Point3D()+32>:        0xc7894800      0xfffb93e8      0x90c3c9ff      0x56415741
0x555555554ea4 <__libc_csu_init+4>:     0x41d78949      0x4c544155      0x0e36258d      0x48550020
0x555555554eb4 <__libc_csu_init+20>:    0x0e3e2d8d      0x41530020      0x8949fd89      0xe5294cf6
```

查看type_info，也就是 0x555555755d10的地址减8

```
(gdb) x/20x 0x555555755d08
0x555555755d08 <_ZTV7Point3D+8>:        0x55755d50      0x00005555      0x55554daa      0x00005555
0x555555755d18 <_ZTV7Point3D+24>:       0x55554e4a      0x00005555      0x55554e74      0x00005555
0x555555755d28 <_ZTV7Point2D>:  0x00000000      0x00000000      0x55755d68      0x00005555
0x555555755d38 <_ZTV7Point2D+16>:       0x55554ca2      0x00005555      0x55554d20      0x00005555
0x555555755d48 <_ZTV7Point2D+32>:       0x55554d3a      0x00005555      0xf7dc5438      0x00007fff
(gdb) x/20x 0x555555755d50
0x555555755d50 <_ZTI7Point3D>:  0xf7dc5438      0x00007fff      0x55554f30      0x00005555
0x555555755d60 <_ZTI7Point3D+16>:       0x55755d68      0x00005555      0xf7dc47f8      0x00007fff
0x555555755d70 <_ZTI7Point2D+8>:        0x55554f40      0x00005555      0x00000001      0x00000000
0x555555755d80: 0x00000001      0x00000000      0x00000001      0x00000000
0x555555755d90: 0x0000017a      0x00000000      0x0000000c      0x00000000
```

那么有两个析构函数，0x0000555555554e74和0x0000555555554e4a有什么区别呢？

反汇编来看看

```
(gdb) disassemble 0x0000555555554e74
Dump of assembler code for function Point3D::~Point3D():
   0x0000555555554e74 <+0>:     push   %rbp
   0x0000555555554e75 <+1>:     mov    %rsp,%rbp
   0x0000555555554e78 <+4>:     sub    $0x10,%rsp
   0x0000555555554e7c <+8>:     mov    %rdi,-0x8(%rbp)
   0x0000555555554e80 <+12>:    mov    -0x8(%rbp),%rax
   0x0000555555554e84 <+16>:    mov    %rax,%rdi
   0x0000555555554e87 <+19>:    callq  0x555555554e4a <Point3D::~Point3D()>
   0x0000555555554e8c <+24>:    mov    -0x8(%rbp),%rax
   0x0000555555554e90 <+28>:    mov    $0x18,%esi
   0x0000555555554e95 <+33>:    mov    %rax,%rdi
   0x0000555555554e98 <+36>:    callq  0x555555554a30 <_ZdlPvm@plt>
   0x0000555555554e9d <+41>:    leaveq 
   0x0000555555554e9e <+42>:    retq   
End of assembler dump.
```

可以从上面第九行看到调用了另外一个析构函数（这个是我们自己编写的析构函数）

继续往下

```
(gdb) l
31      };
32
33      int main()
34      {
35          Point2D *p2d = new Point3D(1, 2, 3);
36          p2d->print();
37
38          Point2D *p2d2 = new Point2D(1, 2);
39          p2d2->print();
40
(gdb) n
(1,2,3)
38          Point2D *p2d2 = new Point2D(1, 2);
(gdb) n
39          p2d2->print();
(gdb) p p2d2
$3 = (Point2D *) 0x5555557692a0
(gdb) x/20x 0x5555557692a0
0x5555557692a0: 0x55755d38      0x00005555      0x00000001      0x00000002
0x5555557692b0: 0x00000000      0x00000000      0x0000ed51      0x00000000
0x5555557692c0: 0x00000000      0x00000000      0x00000000      0x00000000
0x5555557692d0: 0x00000000      0x00000000      0x00000000      0x00000000
0x5555557692e0: 0x00000000      0x00000000      0x00000000      0x00000000
```

虚表指针（0x55755d38      0x00005555）

数据成员：

0x00000001

0x00000002

```
(gdb) p p2d2
$3 = (Point2D *) 0x5555557692a0
(gdb) x/20x 0x5555557692a0
0x5555557692a0: 0x55755d38      0x00005555      0x00000001      0x00000002
0x5555557692b0: 0x00000000      0x00000000      0x0000ed51      0x00000000
0x5555557692c0: 0x00000000      0x00000000      0x00000000      0x00000000
0x5555557692d0: 0x00000000      0x00000000      0x00000000      0x00000000
0x5555557692e0: 0x00000000      0x00000000      0x00000000      0x00000000
(gdb) p 0x00000001
$4 = 1
(gdb) p 0x00000002
$5 = 2
(gdb) x/20x 0x0000555555755d38
0x555555755d38 <_ZTV7Point2D+16>:       0x55554ca2      0x00005555      0x55554d20      0x00005555
0x555555755d48 <_ZTV7Point2D+32>:       0x55554d3a      0x00005555      0xf7dc5438      0x00007fff
0x555555755d58 <_ZTI7Point3D+8>:        0x55554f30      0x00005555      0x55755d68      0x00005555
0x555555755d68 <_ZTI7Point2D>:  0xf7dc47f8      0x00007fff      0x55554f40      0x00005555
0x555555755d78: 0x00000001      0x00000000      0x00000001      0x00000000
(gdb) x/20x 0x0000555555554ca2
0x555555554ca2 <Point2D::print() const>:        0xe5894855      0x10ec8348      0xf87d8948      0x70358d48
0x555555554cb2 <Point2D::print() const+16>:     0x48000002      0x13643d8d      0x4fe80020      0x48fffffd
0x555555554cc2 <Point2D::print() const+32>:     0x8b48c289      0x408bf845      0x48c68908      0x8be8d789
0x555555554cd2 <Point2D::print() const+48>:     0x48fffffd      0x024b358d      0x89480000      0xfd2ce8c7
0x555555554ce2 <Point2D::print() const+64>:     0x8948ffff      0x458b48c2      0x0c408bf8      0x8948c689
(gdb) x/20x 0x0000555555554d20
0x555555554d20 <Point2D::~Point2D()>:   0xe5894855      0xf87d8948      0x09158d48      0x48002010
0x555555554d30 <Point2D::~Point2D()+16>:        0x48f8458b      0x5d901089      0x485590c3      0x8348e589
0x555555554d40 <Point2D::~Point2D()+6>: 0x894810ec      0x8b48f87d      0x8948f845      0xffcee8c7
0x555555554d50 <Point2D::~Point2D()+22>:        0x8b48ffff      0x10bef845      0x48000000      0xcde8c789
0x555555554d60 <Point2D::~Point2D()+38>:        0xc9fffffc      0x485590c3      0x8348e589      0x894820ec
(gdb) x/20x 0x0000555555554d3a
0x555555554d3a <Point2D::~Point2D()>:   0xe5894855      0x10ec8348      0xf87d8948      0xf8458b48
0x555555554d4a <Point2D::~Point2D()+16>:        0xe8c78948      0xffffffce      0xf8458b48      0x000010be
0x555555554d5a <Point2D::~Point2D()+32>:        0xc7894800      0xfffccde8      0x90c3c9ff      0xe5894855
0x555555554d6a <Point3D::Point3D(int, int, int)+4>:     0x20ec8348      0xf87d8948      0x89f47589      0x4d89f055
0x555555554d7a <Point3D::Point3D(int, int, int)+20>:    0x458b48ec      0xf0558bf8      0x89f44d8b      0xc78948ce
```