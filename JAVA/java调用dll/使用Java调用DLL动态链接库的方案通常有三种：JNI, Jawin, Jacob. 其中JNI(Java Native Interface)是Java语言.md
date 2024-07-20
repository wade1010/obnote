使用Java调用DLL动态链接库的方案通常有三种：JNI, Jawin, Jacob. 其中JNI(Java Native Interface) 是Java语言本身提供的调用本地已编译的函数库的方法，本身具有跨平台性，可以在不同的机器上调用不同的本地库。Jawin和Jacob都是 sourceforge.net的开源项目，都是基于JNI技术的依赖Windows的实现，使得在Windows平台下使用COM和DLL的更加方便。



http://www.qqread.com/java/2008/05/w411663.html









LZ java调用dll可以用jni但是这个最复杂 需要自己写个dll然后去调用需要调用dll 还有jawin 也可以不过这个对参数转换要求很高 我比较推荐使用jnative比较方便，对参数的类型敏感度不是很高而且还提供了Pointer指针。

 至于你要的例子 GOOGOLE下吧 呵呵 就不贴出来了







java 调用dll库，你必须要依次进行以下几个步骤：



 1.你必须在你的Java类中定义一个声明为native的方法。



 2.用javac命令编译你刚写好的这个类。



 3.使用javah java类名(你那个类的名字) 会生成扩展名为.h的头文件。



 4.把这个.h的文件导入到你所使用c/c++的开发工具。



 5.使用C/C++实现本地方法，也就是你定义的那个native方法。（这时候你需要看jni的API，因为语言之间对数据类型的定义是不一样的，特 别是Java中的String 类型。你要把它转换成C/C++所能认识的数据类型，必须用JNI提供的一下方法，恩，和麻烦）。



 6.将C/C++编写的文件生成dll动态连接库.



 7.在Java中用System.loadLibrary("动态链接库名");加载动态库.dll文件放在在当前的路径上。



不过强烈建议，不要用这种方法。太痛苦了。不但要转化变量类型，且中文字符的处理更麻烦（当时时间比较紧，老师催，我最终也没能让他支持中文）。最痛苦的 是，程序出问题很难调试。你根本不知道那边错了，是Java这边在调这个方法前出错了，还是c/c++在实现过程中错了。因为c/c++虽然可以实现这个 方法，但它本事不能调用这个方法，也就是它不能测试这个方法是不是对的。而Java虽然可以调用，又不是它实现的，没法追踪找错。     











jni这个东西比较复杂 因为前几天自己刚好做过这个么东西 所以才推荐你用JNative的哦（我也是刚接触这个是个菜鸟）.....



看楼主这么执着那我就写个小例子 大家不要见笑(- -!)



假设现在有个A.dll里面有个方法叫做int add(int a, int b)现在就是要用jni去调用它



这里附上A.dll里面的代码（自己瞎个的吧 能用就行）



C/C++ code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15 |  \#include "stdafx.h"<br> <br>BOOL APIENTRY DllMain( HANDLE hModule, <br>                       DWORD  ul\_reason\_for\_call, <br>                       LPVOID lpReserved<br>                     )<br>{<br>    return TRUE;<br>}<br> <br>   extern "C" <br>  int \_declspec(dllexport) add(int a ,int b)<br> {<br>     return a+b;<br> } |






接下来我们的思路自己写个B.dll然后在这个dll里面去调用A.dll里面的这个add方法，然后通过JAVA去调用自己的这个B.DLL....



第一步: 随便建个txt文件 呵呵 当然后缀改上.JAVA写上



Java code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5 | public class DemoJni{<br> public native int getNumber(int a,int b);<br> public static void main(String[] args) {<br>   }<br>} |






然后javac一下 没错的话 javah 生成了DemoJni.h（如下）



C/C++ code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21 | /\* DO NOT EDIT THIS FILE - it is machine generated \*/<br>\#include &lt;jni.h&gt;<br>/\* Header for class DemoJni \*/<br> <br>\#ifndef \_Included\_DemoJni<br>\#define \_Included\_DemoJni<br>\#ifdef \_\_cplusplus<br>extern "C" {<br>\#endif<br>/\*<br> \* Class:     DemoJni<br> \* Method:    getNumber<br> \* Signature: (II)I<br> \*/<br>JNIEXPORT jint JNICALL Java\_DemoJni\_getNumber<br>  (JNIEnv \*, jobject, jint, jint);<br> <br>\#ifdef \_\_cplusplus<br>}<br>\#endif<br>\#endif |






用VC++6.0写B.DLL



新建DLL工程名字为B 在Header FILES->添加这个DemoJni.h



然后就写B.cpp



C/C++ code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25 | // B.cpp : Defines the entry point for the DLL application.<br>//<br> <br>\#include "stdafx.h"<br>\#include "jni.h"<br>\#include "DemoJni.h"<br> <br>BOOL APIENTRY DllMain( HANDLE hModule, <br>                       DWORD  ul\_reason\_for\_call, <br>                       LPVOID lpReserved<br>                     )<br>{<br>    return TRUE;<br>}<br> <br>JNIEXPORT jint JNICALL Java\_DemoJni\_getNumber<br> (JNIEnv \* env, jobject o, jint x, jint y)<br>{<br>    typedef int (\*ADD)(int ,int);//函数指针类型<br> HINSTANCE Hint = ::LoadLibrary("A.dll");//加载我们刚才生成的dll<br> ADD add = (ADD)GetProcAddress(Hint,"add");//取得dll导出的add方法<br> return add(x,y);<br> <br> FreeLibrary(Hint);<br>} |




对了在VC++6.0要引入java的一些包 因为我们上面引入了#include "jni.h"



具体做法 工具->选项->目录->新建两个路径 ：比如C:\PROGRAM FILES\JAVA\JDK1.5.0_14\INCLUDE



和C:\PROGRAM FILES\JAVA\JDK1.5.0_14\INCLUDE\WIN32



搞定后就可以编译了 然后在debug文件下面就生成了我们要的B.DLL



 然后A.DLL和B.DLL以及.java文件放一起咯



下面就是调用 改写我们的.java文件如下



Java code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10 | public class DemoJni{<br> public native int getNumber(int a,int b);<br> public static void main(String[] args) {<br>       <br>       System.loadLibrary("B");<br>          DemoJni p=new DemoJni();<br>          System.out.println(p.getNumber(1, 100));<br> <br>   }<br>} |






然后java 一下 然后输出



101



然后OK!





这个东西如果用JNative来做的话 实在是太简单了- -！附上JNative的代码



Java code?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37 | import org.xvolks.jnative.JNative;<br>import org.xvolks.jnative.Type;<br>import org.xvolks.jnative.exceptions.NativeException;<br> <br>public class test {<br>    static JNative myjnative = null;<br> <br>    public int getnumber(int a, int b) throws NativeException,<br>            IllegalAccessException {<br> <br>        try {<br>            if (myjnative == null) {<br> <br>                myjnative = new JNative("A.dll", "add");<br>                myjnative.setRetVal(Type.INT);<br>            }<br>            int i = 0;<br>            myjnative.setParameter(i++, a);<br>            myjnative.setParameter(i++, b);<br> <br>            myjnative.invoke();<br>            return myjnative.getRetValAsInt();<br>        } finally {<br>            if (myjnative != null) {<br>                myjnative.dispose();<br>            }<br>        }<br>    }<br> <br>    public static void main(String[] args) throws NativeException,<br>            IllegalAccessException {<br>        test uc = new test();<br>        int result = uc.getnumber(1,100);<br>                  <br>        System.err.println("result:" + result);<br>    }<br>} |






Jnative我觉得有个很好的地方就是有POINTER 指针这个东西.....      