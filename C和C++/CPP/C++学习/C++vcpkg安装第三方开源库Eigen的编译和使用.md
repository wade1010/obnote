[https://blog.csdn.net/cjmqas/article/details/79282847](https://blog.csdn.net/cjmqas/article/details/79282847)

[https://blog.csdn.net/weixin_42703267/article/details/120603746](https://blog.csdn.net/weixin_42703267/article/details/120603746)

[https://github.com/microsoft/vcpkg/blob/master/docs/README.md](https://github.com/microsoft/vcpkg/blob/master/docs/README.md)

1、下载vcpkg源码

git clone [https://github.com/microsoft/vcpkg](https://github.com/microsoft/vcpkg)

2、编译安装

bootstrap-vcpkg.bat

编译好以后会在同级目录下生成vcpkg.exe文件

3、使用

我先设定vcpkg默认安装64位的包

对于 Windows 平台， vcpkg 默认安装32位库，如果想要设置为默认安装64位库，在环境变量中加上 **VCPKG_DEFAULT_TRIPLET=x64-windows** 即可。

3-1、查看支持的开源库列表

.\vcpkg.exe search

3-2、安装一个开源库

.\vcpkg.exe install eigen3:x64-windows        

这里安装后所在目录

W:\env\vcpkg\installed\x64-windows\include       其中 W:\env\vcpkg是我的安装目录，从自己的安装目录找即可

3-3、列出已安装的开源库

.\vcpkg.exe list

4、配合vscode使用

测试代码

```
#include <iostream>
#include <Eigen/Dense>
#define MM_PI 3.14159265358979323846264338327950
int main()
{
     Eigen::Matrix3d Matrixcomp;
     Matrixcomp << cos(MM_PI * 3 / 4), -sin(MM_PI * 3 / 4), 0,
         sin(MM_PI * 3 / 4), cos(MM_PI * 3 / 4), 0,
         0, 0, 1;
     std::cout << Matrixcomp;
     return 0;
}

```

4-1、配置code runner

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240040.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240408.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240036.jpg)

然后就可以使用了

![](images/WEBRESOURCEcf82d6f5e5921d09916c3783bf7ab2e7截图.png)

4-2、在c_cpp_properties.json和tasks.json中使用

4-2-1、c_cpp_properties.json中配置

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240218.jpg)

4-2-2、tasks.json中配置

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240771.jpg)

测试

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240229.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240841.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240270.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240787.jpg)