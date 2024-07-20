## 一、静态库和动态库的基本概念
静态库和动态库简单理解就是对目标文件的打包操作
### 1.1 静态库
一般情况下的静态库命名规则
- lib开头
- .lib结尾---->Windows
- .a结尾  ---->Linux

例如：
1.libxxx.lib的名字就是xxx
2.libyyy.a的名字就是yyy
在windows下的.lib和.a都可以表示静态库，但是使用的时候
- .lib的静态库需要用lib加名字
- .a的静态库只需要用名字

#### 静态库的特点：
1. 编译阶段完成
2. 在链接的时候把静态库的“内容”放到最终的可执行文件中
3. 静态库一旦嵌到可执行文件中就可以直接运行程序，静态库和程序本身再无关系
4. 把静态库嵌入到可执行文件中会使可执行文件的体积变大


lib:library库
a:archive档案

### 1.2 动态库
动态库：有“动态链接库”和“共享对象”的叫法
一般情况下的动态库命名规则：
- lib开头
- .dll结尾----->Windows
- .so结尾---->Linux

例如：
1. libxxx.dll是名为xxx的动态库
2. libyyy.so是名为yyy的动态库

DLL: Dynamic Link Library 动态链接库
SO: Shared Object 共享对象

#### 动态库的特点：
1. 程序运行的时候才会使用到动态库中的内容
2. 在链接的时候把动态库的“访问方式”放到可执行文件中
3. 使用动态库而生成的可执行文件**必须依赖**到动态库才能成功的运行程序
4. 使用动态库的可执行文件体积相对于使用静态库的小


### 1.3 静态库和动态库的简单区别
从静态库和动态库的特点就可以看出，静态库的优点就是动态库的缺点，动态库的优点就是静态库的缺点。 
静态库是把具体的实现嵌入到程序里面来，如果静态库又有了新的更新，就需要重新编译。
动态库，程序里面使用的可以理解为是存放动态库的指针，就算动态库有更新，只要指针指向的接口不变，还是能访问到动态库里面的东西


## 二、静态库和动态库的创建和使用
- 使用命令行创建静态库和动态库
关于静态库和动态库创建需要注意的事项：
1. 操作系统不同，静态库和动态库的内部格式不同
2. 同操作系统，不同编译器，静态库和动态库的生成方式也不同
3. 静态库和动态库的创建和使用一定是基于指定的操作系统和编译器才可以

### 2.1 基于windows,MinGW的静态库的创建
> MinGW就是对gcc的封装，成了编译套件  Min就是小 G就是GNU   W就是windows

A项目下：

MyMath.h
```C++
#ifndef _MYMATH_H_
#define _MYMATH_H_

#include <stdio.h>
#include <stdlib.h>
#include <assert.h>

typedef struct MyMath
{
    int a;
    int b;
} MyMath;

MyMath *createMath(int a, int b);

int add(MyMath *mobj);
int sub(MyMath *mobj);
int mul(MyMath *mobj);
int div(MyMath *mobj);
void delMath(MyMath *mobj);

#endif
```

MyMath.cpp
```c++
#include "MyMath.h"

MyMath *createMath(int a, int b)
{
    MyMath *mobj = (MyMath *)malloc(sizeof(MyMath));
    assert(mobj);
    mobj->a = a;
    mobj->b = b;
    return mobj;
}

int add(MyMath *mobj)
{
    return mobj->a + mobj->b;
}
int sub(MyMath *mobj)
{
    return mobj->a - mobj->b;
}
int mul(MyMath *mobj)
{
    return mobj->a * mobj->b;
}
int div(MyMath *mobj)
{
    if (mobj->b == 0)
    {
        return 0; // 简单处理下
    }

    return mobj->a / mobj->b;
}
void delMath(MyMath *mobj)
{
    if (mobj == nullptr)
        return;
    free(mobj);
    mobj = nullptr;
}
```

g++ -c MyMath.cpp -o MyMath.o
##### 生成.a结尾的
ar -crus libmymath_a.a MyMath.o

> r replace
> u update
> s search

然后将A项目下面两个文件（图中标红）拷贝到B项目

![image](https://cdn.staticaly.com/gh/wade1010/images@master/20221231/image.3xdf9e9uyeg0.webp)


##### 生成.lib结尾的
ar -crus libmymath_lib.lib MyMath.o

同理也复制这个libmymath_lib.lib到B项目

B项目 
main.cpp

```c++
#include "MyMath.h"

int main(int argc, char const *argv[])
{
    MyMath *mobj = createMath(10, 2);
    printf("%d\n", add(mobj));
    printf("%d\n", sub(mobj));
    printf("%d\n", mul(mobj));
    printf("%d\n", div(mobj));
    printf("over\n");
    delMath(mobj);
    return 0;
}

```

##### 使用.a结尾的

g++ -c main.cpp -o main.o

g++ main.o -L . -l mymath_a -o main.exe

执行main.exe，输出如下
```
12
8
20
5
over
```

##### 使用.lib结尾的
g++ main.o -L . -l mymath_lib -o main_lib.exe

执行main_lib.exe
```
12
8
20
5
over
```


### 2.2 基于windows,MinGW的动态库的创建

头文件和源文件还是上面的
进入终端
```shell
#先生成.o文件,-fpic是选加项
g++ -c MyMath.cpp -o MyMath.o -fpic

#先生成动态库文件
g++ MyMath.o -o libmymath_dll.dll -shared -fpic

#也可以将两个命令结合在一起使用
g++ -c MyMath.c -o libmymath_dll.dll -fpic -shared

```

> -shared 是生成动态库的关键选项
> -fpic 是选加项
> f file
> p position
> i independent
> c code

复制 libmymath_dll.dll 到B项目

g++ main.o -L . -l mymath_dll -o main_dll.exe

main_dll.exe
```
12
8
20
5
over
```

#### 动态库在多目录下使用

![image](https://cdn.staticaly.com/gh/wade1010/images@master/20221231/image.4cl6g7h0rne0.webp)

正常编译，生成main.o
g++ -c src/main.cpp -o obj/main.o -I include

链接额时候需要把动态库也链接过来
g++ obj/main.o -L lib -l mymath_dll -o bin/main


cd bin

执行main.exe

发现没输出任何内容（没任何内容肯定也是有问题额。学习的视频中是会有个 由于找不到libmymath.dll，无法继续执行代码....的错误）


最简单的解决办法，就是将dll文件移/复制到bin目录下
上面办法针对少数dll，还是可以的，按时如果非常多额动态库，就不行了


第二种，就是把dll文件放到bin目录下（静态库也还是放在lib下），链接的时候指定bin目录（之前是lib目录）

g++ obj/main.o -L bin -l mymath_dll -o bin/main



使用动态库给C项目，复制bin目录给C目录即可

如果使用静态库给C项目，可以固执2.1步骤里面额main_lib.exe给C目录


## 三、Makefile写法
单文件就不写Makefile了，太简单直接用g++命令比较简单
### 3.1 使用静态库时Makefile写法
项目构造如下，文件全都是上面用到的和生成的

![image](https://cdn.staticaly.com/gh/wade1010/images@master/20221231/image.5a2kpv33iek0.webp)


跟目录创建Makefile 文件，内容如下
```makefile
#声明变量
BIN=./bin
OBJ=./obj
INC=./include
SRC=./src
LIB=./lib

#查找src目录下的所有cpp文件
SRCS=$(wildcard $(SRC)/*.cpp)
#匹配.cpp文件,放到OBJ下面,让它转化为.o文件
OBJS=$(patsubst %.cpp,$(OBJ)/%.o,$(notdir $(SRCS)))

TARGET=main
TARGET_PATH=$(BIN)/$(TARGET)

#编译器
CC=g++
#编译参数 -g 调试, -Wall 警告信息  -I 头文件
CFLAGS=-g -Wall -I$(INC)

LIB_PATH=-L $(LIB)
LIB_FLAGS=-l mymath_lib

#链接
# $@ 表示:前面的  $^ 表示:后面的
$(TARGET_PATH):$(OBJS)
	$(CC) $^ $(LIB_PATH) $(LIB_FLAGS) -o $@
# $@ 表示:前面的  $^ 表示:后面的
$(OBJ)/%.o:$(SRC)/%.cpp
	$(CC) -c $(CFLAGS) $^ -o $@

.PHONY:clean

clean:
	del /Q /F obj
```

在根目录执行  mingw32-make

会在 bin目录下生成main.exe


### 3.2 使用动态库时Makefile写法

![image](https://cdn.staticaly.com/gh/wade1010/images@master/20221231/image.25pa3cmv28qo.webp)
```
#声明变量
BIN=./bin
OBJ=./obj
INC=./include
SRC=./src


#查找src目录下的所有cpp文件
SRCS=$(wildcard $(SRC)/*.cpp)
#匹配.cpp文件,放到OBJ下面,让它转化为.o文件
OBJS=$(patsubst %.cpp,$(OBJ)/%.o,$(notdir $(SRCS)))

TARGET=main
TARGET_PATH=$(BIN)/$(TARGET)

#编译器
CC=g++
#编译参数 -g 调试, -Wall 警告信息  -I 头文件
CFLAGS=-g -Wall -I$(INC)

LIB_PATH=-L$(BIN)
LIB_FLAGS=-lmymath_dll

# $@ 表示:前面的  $^ 表示:后面的
$(OBJ)/%.o:$(SRC)/%.cpp
	$(CC) -c $(CFLAGS) $^ -o $@

# $@ 表示:前面的  $^ 表示:后面的
$(TARGET_PATH):$(OBJS)
	$(CC) $^ $(LIB_PATH) $(LIB_FLAGS) -o $@



.PHONY:clean

clean:
	del /Q /F obj
```

执行 mingw32-make
会在bin目录下生成main.exe


clean就是执行  mingw32-make clean


### 3.3 使用静态库和动态库时Makefile写法

这里加减法做静态库，乘除法做动态库

AA项目文件如下：

mathas.cpp
```
#include "mathas.h"

int add(int a, int b)
{
    return a + b;
}
int sub(int a, int b)
{
    return a - b;
}
```

mathas.h
```
#ifndef _MATH_AS_H
#define _MATH_AS_H

int add(int a, int b);
int sub(int a, int b);

#endif //


```

mathmd.cpp
```
#include "mathmd.h"

int mul(int a, int b)
{
    return a * b;
}
int div(int a, int b)
{
    if (b == 0)
    {
        return 0;
    }
    return a / b;
}
```
mathmd.h
```
#ifndef _MATH_MD_H
#define _MATH_MD_H

int mul(int a, int b);
int div(int a, int b);

#endif // !_MATH_MD_H
```


##### 生成静态库

g++ -c mathas.cpp -o mathas.o

ar -crus libmymath_lib.lib mathas.o


##### 生成动态库
g++ -c mathmd.cpp -o mathmd.o -fpic

g++ mathmd.o -o libmathmd_dll.dll -shared -fpic 



将libmathmd_dll.dll  libmymath_lib.lib mathas.h mathmd.h 这四个文件拷贝到BB项目

src/main.cpp
```
#include "mathas.h"
#include "mathmd.h"
#include <stdio.h>

int main(int argc, char const *argv[])
{
    printf("start\n");
    printf("%d\n", add(1, 2));
    printf("%d\n", sub(1, 2));
    printf("%d\n", mul(1, 2));
    printf("%d\n", div(1, 2));
    printf("over\n");

    return 0;
}

```

Makefile
```
#声明变量
BIN=./bin
OBJ=./obj
INC=./include
SRC=./src
LIB=./lib

#查找src目录下的所有cpp文件
SRCS=$(wildcard $(SRC)/*.cpp)
#匹配.cpp文件,放到OBJ下面,让它转化为.o文件
OBJS=$(patsubst %.cpp,$(OBJ)/%.o,$(notdir $(SRCS)))

TARGET=main
TARGET_PATH=$(BIN)/$(TARGET)

#编译器
CC=g++
#编译参数 -g 调试, -Wall 警告信息  -I 头文件
CFLAGS=-g -Wall -I$(INC)

LIB_PATH=-L$(BIN) -L$(LIB)
LIB_FLAGS=-lmathmd_dll -lmymath_lib

# $@ 表示:前面的  $^ 表示:后面的
$(OBJ)/%.o:$(SRC)/%.cpp
	$(CC) -c $(CFLAGS) $^ -o $@

# $@ 表示:前面的  $^ 表示:后面的
$(TARGET_PATH):$(OBJS)
	$(CC) $^ $(LIB_PATH) $(LIB_FLAGS) -o $@



.PHONY:clean

clean:
	del /Q /F obj
```

项目结构如下
![image](https://cdn.staticaly.com/gh/wade1010/images@master/20221231/image.6ymo0gwlaz80.webp)

mingw32-make

cd bin

main.exe

```
W:\workspace\cpp\静态库和动态库\动静结合\BB>mingw32-make
g++ -c -g -Wall -I./include src/main.cpp -o obj/main.o
g++ obj/main.o -L./bin -L./lib -lmathmd_dll -lmymath_lib -o bin/main

W:\workspace\cpp\静态库和动态库\动静结合\BB>cd bin

W:\workspace\cpp\静态库和动态库\动静结合\BB\bin>main.exe
start
3
-1
2
0
over
```