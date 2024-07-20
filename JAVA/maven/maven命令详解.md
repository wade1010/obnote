- mvn install 将打包的jar/war文件复制到你的本地仓库中【生成的包放到target目录下+本地仓库】

- mvn deploy 将打包的文件发布到**远程仓库**，提供给其他人员进行下载

- mvn site 生成项目相关信息的网站【**没用过**】

- mvn dependency:tree 打印出项目的整个依赖树，就是项目中所需要依赖的包

- mvn archetype:generate **创建生成**Maven的**普通**java项目【可以参考视频中创建项目xxx-quicklystart】

- mvn tomcat7:run 在tomcat容器中运行web应用，不过一般都是配置自己的tomcat

- mvn jetty:run 调用jetty插件的run 目标在jetty Servlet容器中启动web应用，一般也是用tomcat

可以在cmd中通过一系列的maven命令来对maven工程进行编译、测试、运行、打包、安装、部署。

**3.1.1 compile**

compile是maven工程的编译命令，作用是将src/main/java下的文件编译为class文件输出到target目录下。

cmd进入命令状态，执行mvn compile，如下图提示成功：

mvn compile

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCEca6203b709eb4d549b68845a0220a4bdstickPicture.png)

查看 target目录，class文件已生成，编译完成

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCEb0933da371d44bda45aec082c5920903stickPicture.png)

**3.1.2 test**

test是maven工程的测试命令 mvn test，会执行src/test/java下的单元测试类。

cmd执行mvn test执行src/test/java下单元测试类，下图为测试结果，运行1个测试用例，全部成功。

mvn test

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCE17979b6c2fc66bdc400b8239f04eb7f5stickPicture.png)

**3.1.3 clean**

clean是maven工程的清理命令，执行 clean会删除target目录及内容。

**3.1.4 package**

package是maven工程的打包命令，对于java工程执行package打成jar包，对于web工程打成war包。

**3.1.5 install**

install是maven工程的安装命令，执行install将maven打成jar包或war包发布到本地仓库。

从运行结果中，可以看出：

当后面的命令执行时，前面的操作过程也都会自动执行，

**3.1.6 Maven**指令的生命周期

maven对项目构建过程分为三套相互独立的生命周期，请注意这里说的是“三套”，而且“相互独立”，这三套生命周期分别是：

Clean Lifecycle 在进行真正的构建之前进行一些清理工作。

Default Lifecycle 构建的核心部分，编译，测试，打包，部署等等。

Site Lifecycle 生成项目报告，站点，发布站点。

**3.1.7 maven**的概念模型

Maven包含了一个项目对象模型 (Project Object Model)，一组标准集合，一个项目生命周期(Project Lifecycle)，一个依赖管理系统(Dependency Management System)，和用来运行定义在生命周期阶段(phase)中插件(plugin)目标(goal)的逻辑。

maven的概念模型

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCE962bbee715c4719dd214681d630af230stickPicture.png)

**项目对象模型 (Project Object Model)**

一个maven工程都有一个pom.xml文件，通过pom.xml文件定义项目的坐标、项目依赖、项目信息、插件目标等。

**依赖管理系统(Dependency Management System)**

通过maven的依赖管理对项目所依赖的jar 包进行统一管理。

比如：项目依赖junit4.9，通过在pom.xml中定义junit4.9的依赖即使用junit4.9，如下所示是junit4.9的依赖定义：

junit4.9 的项目依赖

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCEfe91b76ace976885596b1a1296f5dce4stickPicture.png)

**一个项目生命周期(Project Lifecycle)**

使用maven完成项目的构建，项目构建包括：清理、编译、测试、部署等过程，maven将这些过程规范为一个生命周期，如下所示是生命周期的各各阶段：

maven项目生命周期

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCEa88eaabdac2788d97193ee7c0a34ed15stickPicture.png)

maven通过执行一些简单命令即可实现上边生命周期的各各过程，比如执行mvn compile执行编译、执行mvn clean执行清理。

**一组标准集合**

maven将整个项目管理过程定义一组标准，比如：通过maven构建工程有标准的目录结构，有标准的生命周期阶段、依赖管理有标准的坐标定义等。

**插件(plugin)目标(goal)**

maven 管理项目生命周期过程都是基于插件完成的。

compile是maven工程的编译命令，作用是将src/main/java下的文件编译为class文件输出到target目录下

[](https://img2022.cnblogs.com/blog/2789097/202203/2789097-20220312210035842-587314916.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCEffefb73aa32b703c3a723e1cb807e2c5截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/WEBRESOURCE524557bc93a5e944b272abca13a8da2d截图.png)

### test

test是maven工程的测试命令 mvn test，会执行src/test/java下的单元测试类。

### clean

clean是maven工程的清理命令，执行 clean会删除target目录及内容。

### package

package是maven工程的打包命令，对于java工程执行package打成jar包，对于web工程打成war包。

### install

install是maven工程的安装命令，执行install将maven打成jar包或war包发布到本地仓库。

## Maven 的生命周期

maven对项目构建过程分为三套相互独立的生命周期，请注意这里说的是“三套”，而且“相互独立”，这三套生命周期分别是：

Clean Lifecycle 在进行真正的构建之前进行一些清理工作。

Default Lifecycle 构建的核心部分，编译，测试，打包，部署等等。 （同一套生命周期中执行后面的生命周期，会自动执行前面的生命周期）

Site Lifecycle 生成项目报告，站点，发布站点