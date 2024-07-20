本文提供调用本地 C 代码的 Java 代码示例，包括传递和返回某些常用的数据类型。本地方法包含在特定于平台的可执行文件中。就本文中的示例而言，本地方法包含在 Windows 32 位动态链接库 (DLL) 中。

不过我要提醒您，对 Java 外部的调用通常不能移植到其他平台上，在 applet 中还可能引发安全异常。实现本地代码将使您的 Java 应用程序无法通过 100% 纯 Java 测试。但是，如果必须执行本地调用，则要考虑几个准则：

1. 将您的所有本地方法都封装在单个类中，这个类调用单个 DLL。对于每种目标操作系统，都可以用特定于适当平台的版本替换这个 DLL。这样就可以将本地代码的影响减至最小，并有助于将以后所需的移植问题包含在内。

1. 本地方法要简单。尽量将您的 DLL 对任何第三方（包括 Microsoft）运行时 DLL 的依赖减到最小。使您的本地方法尽量独立，以将加载您的 DLL 和应用程序所需的开销减到最小。如果需要运行时 DLL，必须随应用程序一起提供它们。

Java 调用 C

对于调用 C 函数的 Java 方法，必须在 Java 类中声明一个本地方法。在本部分的所有示例中，我们将创建一个名为 MyNative 的类，并逐步在其中加入新的功能。这强调了一种思想，即将本地方法集中在单个类中，以便将以后所需的移植工作减到最少。

示例 1 -- 传递参数

在第一个示例中，我们将三个常用参数类型传递给本地函数： String、 int和 boolean 。本例说明在本地 C 代码中如何引用这些参数。

|   |
| - |
| public class MyNative<br>{<br>  public void showParms( String s, int i, boolean b )<br>  {<br>    showParms0( s, i , b );<br>  }<br>  private native void showParms0( String s, int i, boolean b );<br>  static<br>  {<br>    System.loadLibrary( "MyNative" );<br>  }<br>} |


请注意，本地方法被声明为专用的，并创建了一个包装方法用于公用目的。这进一步将本地方法同代码的其余部分隔离开来，从而允许针对所需的平台对它进行优化。 static子句加载包含本地方法实现的 DLL。

下一步是生成 C 代码来实现 showParms0 方法。此方法的 C 函数原型是通过对 .class 文件使用 javah 实用程序来创建的，而 .class 文件是通过编译 MyNative.java 文件生成的。这个实用程序可在 JDK 中找到。下面是 javah 的用法：

|   |
| - |
|  javac MyNative.java（将 .java 编译为 .class） <br> javah -jni <br>      MyNative（生成 .h 文件）  |


这将生成一个 MyNative.h 文件，其中包含一个本地方法原型，如下所示：

|   |
| - |
| /\*<br> \* Class:     MyNative<br> \* Method:    showParms0<br> \* Signature: (Ljava/lang/String;IZ)V<br> \*/<br>JNIEXPORT void JNICALL Java\_MyNative\_showParms0<br>  (JNIEnv \*, jobject, jstring, jint, jboolean); |


第一个参数是调用 JNI 方法时使用的 JNI Environment 指针。第二个参数是指向在此 Java 代码中实例化的 Java 对象 MyNative 的一个句柄。其他参数是方法本身的参数。请注意，MyNative.h 包括头文件 jni.h。jni.h 包含 JNI API 和变量类型（包括jobject、jstring、jint、jboolean，等等）的原型和其他声明。

本地方法是在文件 MyNative.c 中用 C 语言实现的：

|   |
| - |
| \#include <br>\#include "MyNative.h"<br>JNIEXPORT void JNICALL Java\_MyNative\_showParms0<br>  (JNIEnv \*env, jobject obj, jstring s, jint i, jboolean b)<br>{<br>  const char\* szStr = (\*env)-&lt;GetStringUTFChars( env, s, 0 );<br>  printf( "String = [%s]\\n", szStr );<br>  printf( "int = %d\\n", i );<br>  printf( "boolean = %s\\n", (b==JNI\_TRUE ? "true" : "false") );<br>  (\*env)-&lt;ReleaseStringUTFChars( env, s, szStr );<br>} |


JNI API，GetStringUTFChars，用来根据 Java 字符串或 jstring 参数创建 C 字符串。这是必需的，因为在本地代码中不能直接读取 Java 字符串，而必须将其转换为 C 字符串或 Unicode。有关转换 Java 字符串的详细信息，请参阅标题为 NLS Strings and JNI 的一篇论文。但是，jboolean 和 jint 值可以直接使用。

MyNative.dll 是通过编译 C 源文件创建的。下面的编译语句使用 Microsoft Visual C++ 编译器：

|   |
| - |
|  cl -Ic:\\jdk1.1.6\\include -Ic:\\jdk1.1.6\\include\\win32 -LD MyNative.c <br>      -FeMyNative.dll   |


其中 c:\jdk1.1.6 是 JDK 的安装路径。

MyNative.dll 已创建好，现在就可将其用于 MyNative 类了。

可以这样测试这个本地方法：在 MyNative 类中创建一个 main 方法来调用 showParms 方法，如下所示：

|   |
| - |
|    public static void main( String[] args )<br>   {<br>     MyNative obj = new MyNative();<br>     obj.showParms( "Hello", 23, true );<br>     obj.showParms( "World", 34, false );<br>   } |


当运行这个 Java 应用程序时，请确保 MyNative.dll 位于 Windows 的 PATH 环境变量所指定的路径中或当前目录下。当执行此 Java 程序时，如果未找到这个 DLL，您可能会看到以下的消息：

|   |
| - |
|  java MyNative  <br> Can't find class MyNative   |


这是因为 static 子句无法加载这个 DLL，所以在初始化 MyNative 类时引发异常。Java 解释器处理这个异常，并报告一个一般错误，指出找不到这个类。

如果用 -verbose 命令行选项运行解释器，您将看到它因找不到这个 DLL 而加载 UnsatisfiedLinkError 异常。

如果此 Java 程序完成运行，就会输出以下内容：

|   |
| - |
|  java MyNative  <br> String = [Hello]  <br> int = 23 <br> boolean = true  <br> String = [World]  <br> int <br>      = 34   |


boolean = false示例 2 -- 返回一个值

本例将说明如何在本地方法中实现返回代码。

将这个方法添加到 MyNative 类中，这个类现在变为以下形式：

|   |
| - |
| public class MyNative<br>{<br>  public void showParms( String s, int i, boolean b )<br>  {<br>    showParms0( s, i , b );<br>  }<br>  public int hypotenuse( int a, int b )<br>  {<br>    return hyptenuse0( a, b );<br>  }<br>  private native void showParms0( String s, int i, boolean b );<br>  private native int  hypotenuse0( int a, int b );<br>  static<br>  {<br>    System.loadLibrary( "MyNative" );<br>  }<br>  /\* 测试本地方法 \*/<br>  public static void main( String[] args )<br>  {<br>    MyNative obj = new MyNative();<br>    System.out.println( obj.hypotenuse(3,4) );<br>    System.out.println( obj.hypotenuse(9,12) );<br>  }<br>} |


公用的 hypotenuse 方法调用本地方法 hypotenuse0 来根据传递的参数计算值，并将结果作为一个整数返回。这个新本地方法的原型是使用 javah 生成的。请注意，每次运行这个实用程序时，它将自动覆盖当前目录中的 MyNative.h。按以下方式执行 javah：

|   |
| - |
|  javah -jni MyNative   |


生成的 MyNative.h 现在包含 hypotenuse0 原型，如下所示：

|   |
| - |
| /\*<br> \* Class:     MyNative<br> \* Method:    hypotenuse0<br> \* Signature: (II)I<br> \*/<br>JNIEXPORT jint JNICALL Java\_MyNative\_hypotenuse0<br>  (JNIEnv \*, jobject, jint, jint); |


该方法是在 MyNative.c 源文件中实现的，如下所示：

|   |
| - |
| \#include <br>\#include <br>\#include "MyNative.h"<br>JNIEXPORT void JNICALL Java\_MyNative\_showParms0<br>  (JNIEnv \*env, jobject obj, jstring s, jint i, jboolean b)<br>{<br>  const char\* szStr = (\*env)-&lt;GetStringUTFChars( env, s, 0 );<br>  printf( "String = [%s]\\n", szStr );<br>  printf( "int = %d\\n", i );<br>  printf( "boolean = %s\\n", (b==JNI\_TRUE ? "true" : "false") );<br>  (\*env)-&lt;ReleaseStringUTFChars( env, s, szStr );<br>}<br>JNIEXPORT jint JNICALL Java\_MyNative\_hypotenuse0<br>  (JNIEnv \*env, jobject obj, jint a, jint b)<br>{<br>  int rtn = (int)sqrt( (double)( (a\*a) + (b\*b) ) );<br>  return (jint)rtn;<br>} |


再次请注意，jint 和 int 值是可互换的。

使用相同的编译语句重新编译这个 DLL：

|   |
| - |
|  cl -Ic:\\jdk1.1.6\\include -Ic:\\jdk1.1.6\\include\\win32 -LD MyNative.c <br>      -FeMyNative.dll   |


现在执行 java MyNative 将输出 5 和 15 作为斜边的值。

示例 3 -- 静态方法

您可能在上面的示例中已经注意到，实例化的 MyNative 对象是没必要的。实用方法通常不需要实际的对象，通常都将它们创建为静态方法。本例说明如何用一个静态方法实现上面的示例。更改 MyNative.java 中的方法签名，以使它们成为静态方法：

|   |
| - |
|   public static int hypotenuse( int a, int b )<br>  {<br>    return hypotenuse0(a,b);<br>  }<br>  ...<br>  private static native int  hypotenuse0( int a, int b ); |


现在运行 javah 为 hypotenuse0创建一个新原型，生成的原型如下所示：

|   |
| - |
| /\*<br> \* Class:     MyNative<br> \* Method:    hypotenuse0<br> \* Signature: (II)I<br> \*/<br>JNIEXPORT jint JNICALL Java\_MyNative\_hypotenuse0<br>  (JNIEnv \*, jclass, jint, jint); |


C 源代码中的方法签名变了，但代码还保持原样：

|   |
| - |
| JNIEXPORT jint JNICALL Java\_MyNative\_hypotenuse0<br>  (JNIEnv \*env, jclass cls, jint a, jint b)<br>{<br>  int rtn = (int)sqrt( (double)( (a\*a) + (b\*b) ) );<br>  return (jint)rtn;<br>} |


本质上，jobject 参数已变为 jclass 参数。此参数是指向 MyNative.class 的一个句柄。main 方法可更改为以下形式：

|   |
| - |
|   public static void main( String[] args )<br>  {<br>    System.out.println( MyNative.hypotenuse( 3, 4 ) );<br>    System.out.println( MyNative.hypotenuse( 9, 12 ) );<br>  } |


因为方法是静态的，所以调用它不需要实例化 MyNative 对象。本文后面的示例将使用静态方法。

示例 4 -- 传递数组

本例说明如何传递数组型参数。本例使用一个基本类型，boolean，并将更改数组元素。下一个示例将访问 String（非基本类型）数组。将下面的方法添加到 MyNative.java 源代码中：

|   |
| - |
|   public static void setArray( boolean[] ba )<br>  {<br>    for( int i=0; i &lt; ba length i br style='font-size:16px;font-style:normal;font-weight:400;font-family:monospace;color:rgb(0, 0, 0);'  /&gt;      ba[i] = true;<br>    setArray0( ba );<br>  }<br>  ...<br>  private static native void setArray0( boolean[] ba ); |


在本例中，布尔型数组被初始化为 true，本地方法将把特定的元素设置为 false。同时，在 Java 源代码中，我们可以更改 main 以使其包含测试代码：

|   |
| - |
|     boolean[] ba = new boolean[5];<br>    MyNative.setArray( ba );<br>    for( int i=0; i &lt; ba length i br style='font-size:16px;font-style:normal;font-weight:400;font-family:monospace;color:rgb(0, 0, 0);'  /&gt;      System.out.println( ba[i] ); |


在编译源代码并执行 javah 以后，MyNative.h 头文件包含以下的原型：

|   |
| - |
| /\*<br> \* Class:     MyNative<br> \* Method:    setArray0<br> \* Signature: ([Z)V<br> \*/<br>JNIEXPORT void JNICALL Java\_MyNative\_setArray0<br>  (JNIEnv \*, jclass, jbooleanArray); |


请注意，布尔型数组是作为单个名为 jbooleanArray 的类型创建的。

基本类型有它们自已的数组类型，如 jintArray 和 jcharArray。

非基本类型的数组使用 jobjectArray 类型。下一个示例中包括一个 jobjectArray。这个布尔数组的数组元素是通过 JNI 方法 GetBooleanArrayElements 来访问的。

针对每种基本类型都有等价的方法。这个本地方法是如下实现的：

|   |
| - |
| JNIEXPORT void JNICALL Java\_MyNative\_setArray0<br>  (JNIEnv \*env, jclass cls, jbooleanArray ba)<br>{<br>  jboolean\* pba = (\*env)-&lt;GetBooleanArrayElements( env, ba, 0 );<br>  jsize len = (\*env)-&lt;GetArrayLength(env, ba);<br>  int i=0;<br>  // 更改偶数数组元素<br>  for( i=0; i &lt; len  i="2" br style='font-size:16px;font-style:normal;font-weight:400;font-family:monospace;color:rgb(0, 0, 0);'  /&gt;    pba[i] = JNI\_FALSE;<br>  (\*env)-&lt;ReleaseBooleanArrayElements( env, ba, pba, 0 );<br>} |


指向布尔型数组的指针可以使用 GetBooleanArrayElements 获得。

数组大小可以用 GetArrayLength 方法获得。使用 ReleaseBooleanArrayElements 方法释放数组。现在就可以读取和修改数组元素的值了。jsize 声明等价于 jint（要查看它的定义，请参阅 JDK 的 include 目录下的 jni.h 头文件）。

示例 5 -- 传递 Java String 数组

本例将通过最常用的非基本类型，Java String，说明如何访问非基本对象的数组。字符串数组被传递给本地方法，而本地方法只是将它们显示到控制台上。

MyNative 类定义中添加了以下几个方法：

|   |
| - |
|   public static void showStrings( String[] sa )<br>  {<br>    showStrings0( sa );<br>  }<br>  private static void showStrings0( String[] sa ); |


并在 main 方法中添加了两行进行测试：

|   |
| - |
|   String[] sa = new String[] { "Hello,", "world!", "JNI", "is", "fun." };<br>  MyNative.showStrings( sa ); |


本地方法分别访问每个元素，其实现如下所示。

|   |
| - |
| JNIEXPORT void JNICALL Java\_MyNative\_showStrings0<br>  (JNIEnv \*env, jclass cls, jobjectArray sa)<br>{<br>  int len = (\*env)-&lt;GetArrayLength( env, sa );<br>  int i=0;<br>  for( i=0; i &lt; len  i br style='font-size:16px;font-style:normal;font-weight:400;font-family:monospace;color:rgb(0, 0, 0);'  /&gt;  {<br>    jobject obj = (\*env)-&lt;GetObjectArrayElement(env, sa, i);<br>    jstring str = (jstring)obj;<br>    const char\* szStr = (\*env)-&lt;GetStringUTFChars( env, str, 0 );<br>    printf( "%s ", szStr );<br>    (\*env)-&lt;ReleaseStringUTFChars( env, str, szStr );<br>  }<br>  printf( "\\n" );<br>} |


数组元素可以通过 GetObjectArrayElement 访问。

在本例中，我们知道返回值是 jstring 类型，所以可以安全地将它从 jobject 类型转换为 jstring 类型。字符串是通过前面讨论过的方法打印的。有关在 Windows 中处理 Java 字符串的信息，请参阅标题为 NLS Strings and JNI 的一篇论文。

示例 6 -- 返回 Java String 数组

最后一个示例说明如何在本地代码中创建一个字符串数组并将它返回给 Java 调用者。MyNative.java 中添加了以下几个方法：

|   |
| - |
|   public static String[] getStrings()<br>  {<br>    return getStrings0();<br>  }<br>  private static native String[] getStrings0(); |


更改 main 以使 showStrings 将 getStrings 的输出显示出来：

|   |
| - |
|   MyNative.showStrings( MyNative.getStrings() ); |


实现的本地方法返回五个字符串。

|   |
| - |
| JNIEXPORT jobjectArray JNICALL Java\_MyNative\_getStrings0<br>  (JNIEnv \*env, jclass cls)<br>{<br>  jstring      str;<br>  jobjectArray args = 0;<br>  jsize        len = 5;<br>  char\*        sa[] = { "Hello,", "world!", "JNI", "is", "fun" };<br>  int          i=0;<br>  args = (\*env)-&lt;NewObjectArray(env, len, (\*env)-&lt;FindClass(env, "java/lang/String"), 0);<br>  for( i=0; i &lt; len  i br style='font-size:16px;font-style:normal;font-weight:400;font-family:monospace;color:rgb(0, 0, 0);'  /&gt;  {<br>    str = (\*env)-&lt;NewStringUTF( env, sa[i] );<br>    (\*env)-&lt;SetObjectArrayElement(env, args, i, str);<br>  }<br>  return args;<br>} |


字符串数组是通过调用 NewObjectArray 创建的，同时传递了 String 类和数组长度两个参数。Java String 是使用 NewStringUTF 创建的。String 元素是使用 SetObjectArrayElement 存入数组中的。

|   |
| - |
|  |


![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/67AE9E355D664E9CABEFE9186F8D297Bblue_rule.gif)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/8FA60B56DA0046E5B70B20D79CE5311Dc.gif)

|   |
| - |
|  |


![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/5AC2FC81C1A3469C9376ACE192C5BD11c.gif)

|   |   |
| - | - |
|  | 回页首 |


![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/3B6D127C4B45485AA41F2941B39B02D3u_bold.gif)



调试

现在您已经为您的应用程序创建了一个本地 DLL，但在调试时还要牢记以下几点。如果使用 Java 调试器 java_g.exe，则还需要创建 DLL 的一个“调试”版本。这只是表示必须创建同名但带有一个 _g 后缀的 DLL 版本。就 MyNative.dll 而言，使用 java_g.exe 要求在 Windows 的 PATH 环境指定的路径中有一个 MyNative_g.dll 文件。在大多数情况下，这个 DLL 可以通过将原文件重命名或复制为其名称带缀 _g 的文件。

现在，Java 调试器不允许您进入本地代码，但您可以在 Java 环境外使用 C 调试器（如 Microsoft Visual C++）调试本地方法。首先将源文件导入一个项目中。

将编译设置调整为在编译时将 include 目录包括在内：

|   |
| - |
|  c:\\jdk1.1.6\\include;c:\\jdk1.1.6\\include\\win32   |


将配置设置为以调试模式编译 DLL。在 Project Settings 中的 Debug 下，将可执行文件设置为 java.exe（或者 java_g.exe，但要确保您生成了一个 _g.dll 文件）。程序参数包括包含 main 的类名。如果在 DLL 中设置了断点，则当调用本地方法时，执行将在适当的地方停止。

下面是设置一个 Visual C++ 6.0 项目来调试本地方法的步骤。

1. 在 Visual C++ 中创建一个 Win32 DLL 项目，并将 .c 和 .h 文件添加到这个项目中。



![](http://www-128.ibm.com/developerworks/cn/java/jnimthds/3665b282.jpg)





![](http://www-128.ibm.com/developerworks/cn/java/jnimthds/3665b283.jpg)

- 在 Tools 下拉式菜单的 Options 设置下设置 JDK 的 include 目录。下面的对话框显示了这些目录。



![](http://www-128.ibm.com/developerworks/cn/java/jnimthds/3665b284.jpg)

- 选择 Build 下拉式菜单下的 Build MyNative.dll 来建立这个项目。确保将项目的活动配置设置为调试（这通常是缺省值）。

- 在 Project Settings 下，设置 Debug 选项卡来调用适当的 Java 解释器，如下所示：



![](http://www-128.ibm.com/developerworks/cn/java/jnimthds/3665b285.jpg)

当执行这个程序时，忽略“在 java.exe 中找不到任何调试信息”的消息。当调用本地方法时，在 C 代码中设置的任何断点将在适当的地方停止 Java 程序的执行。

其他信息

JNI 方法和 C++

上面这些示例说明了如何在 C 源文件中使用 JNI 方法。如果使用 C++，则请将相应方法的格式从：

|   |
| - |
|  (\*env)-&lt;JNIMethod( env, .... );   |


更改为：

|   |
| - |
|  env-&lt;JNIMethod( ... );   |


在 C++ 中，JNI 函数被看作是 JNIEnv 类的成员方法。

字符串和国家语言支持

本文中使用的技术用 UTF 方法来转换字符串。使用这些方法只是为了方便起见，如果应用程序需要国家语言支持 (NLS)，则不能使用这些方法。有关在 Windows 和 NLS 环境中处理 Java 字符串正确方法，请参标题为 NLS Strings and JNI 的一篇论文。



小结

本文提供的示例用最常用的数据类据（如 jint 和 jstring）说明了如何实现本地方法，并讨论了 Windows 特定的几个问题，如显示字符串。本文提供的示例并未包括全部 JNI，JNI 还包括其他参数类型，如 jfloat、jdouble、jshort、jbyte 和 jfieldID，以及用来处理这些类型的方法。有关这个主题的详细信息，请参阅 Sun Microsystems 提供的 Java 本地接口规范。