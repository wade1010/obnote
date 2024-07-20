%bcond_with 需要显示声明 with 否则不会执行。同样 %bcond_without 需要显示声明 without 否则会执行。

[https://blog.csdn.net/Michaelwubo/article/details/105833670](https://blog.csdn.net/Michaelwubo/article/details/105833670)

[http://www.jinbuguo.com/pkgmanager/redhat/rpmbuild.html](http://www.jinbuguo.com/pkgmanager/redhat/rpmbuild.html)

[http://www.jinbuguo.com/pkgmanager/redhat/rpm.html](http://www.jinbuguo.com/pkgmanager/redhat/rpm.html)

描述

rpmbuild 用于创建软件的二进制包和源代码包。

一个"包"包括文件的归档以及用来安装和卸载归档中文件的元数据。

元数据包括辅助脚本、文件属性、以及相关的描述性信息。

软件包有两种：

二进制包，用来封装已经编译好的二进制文件；

源代码包，用来封装源代码和要构建二进制包需要的信息。

必须选择下列"模式"之一：

(1)从 spec 构建, (2)从 Tar 构建, (3)重新构建, (4)重新编译, (5)显示配置

通用选项

下列选项可以用于所有不同的模式。

-?, --help

打印详细的帮助信息

--version

打印一行详细的版本号信息

--quiet

输出尽可能少的信息，通常只有错误信息才会显示出来。

-v     输出冗余信息，例如进度之类的信息。

-vv    输出大量冗长的调试信息

--rcfile FILELIST

FILELIST 中冒号分隔的每个文件都被 rpm 按顺序读取，从中获得配置信息。

只有列表中的第一个文件必须存在，波浪线将被替换为 $HOME 。默认值是：

/usr/lib/rpm/rpmrc:/usr/lib/rpm/redhat/rpmrc:/etc/rpmrc:~/.rpmrc

--pipe CMD

将 rpm 的输出通过管道送到 CMD 命令。

--dbpath DIRECTORY

使用 DIRECTORY 中的数据库，而不是默认的 /var/lib/rpm

--root DIRECTORY

以 DIRECTORY 作为根文件系统进行操作。这意味着将使用 DIRECTORY 中的数据库来进行依赖性检测，

并且任何操作(比如安装时的 %post 和构建时的 %prep)都将 chroot 到 DIRECTORY 下执行。

-D, --define='MACRO EXPR'

将 MACRO 宏的值定义为 EXPR

(1,2)构建选项

构建命令的一般形式是

rpmbuild -bSTAGE|-tSTAGE [rpmbuild-options] FILE ...

如果需要根据某个 spec 文件构建，那么使用 -b 参数。

如果需要根据某个 tar 归档(可能是压缩过的)中的 spec 文件构建，那么使用 -t 参数。

STAGE 指定了要完成的构建和打包的阶段，必须是下列其中之一：

a    构建二进制包和源代码包(在执行 %prep, %build, %install 之后)

b    构建二进制包(在执行 %prep, %build, %install 之后)

p    执行 spec 文件的"%prep"阶段。这通常等价于解包源代码并应用补丁。

c    执行 spec 文件的"%build"阶段(在执行了 %prep 之后)。这通常等价于执行了"make"。

i    执行 spec 文件的"%install"阶段(在执行了 %prep, %build 之后)。这通常等价于执行了"make install"。

l    执行一次"列表检查"。spec 文件的"%files"段落中的宏被扩展，检测是否每个文件都存在。

s    只构建源代码包

此外，还可以使用下列选项：

--buildroot DIRECTORY

在构建时，使用 DIRECTORY 目录覆盖默认的 BuildRoot 值

--clean

在打包完成之后删除构建树

--nobuild

不执行任何实际的构建步骤。可用于测试 spec 文件。

--noclean

不执行 spec 文件的"%clean"阶段(即使它确实存在)。

--nocheck

不执行 spec 文件的"%check"阶段(即使它确实存在)。

--nodeps

不检查编译依赖条件是否满足

--rmsource

在构建后删除源代码(也可以单独使用，例如"rpmbuild --rmsource foo.spec")

--rmspec

在构建之后删除 spec 文件(也可以单独使用，例如"rpmbuild --rmspec foo.spec")

--short-circuit

直接跳到指定阶段(也就是跳过指定阶段前面的所有步骤)，只有与 c 或 i 或 b 连用才有意义。

仅用于本地调试。以此种方法构建出的包将被标记为"依赖关系不满足"，以阻止其被正常使用。

--target PLATFORM

在构建时，将 PLATFORM 解析为 arch-vendor-os ，并以此设置宏 %_target, %_target_cpu, %_target_os 的值。

(3,4)重新构建和重新编译选项

有两种构建方法：

rpmbuild --rebuild|--recompile SOURCEPKG ...

使用 --recompile 的话，rpmbuild 将安装指定的源代码包(SOURCEPKG)，然后进行准备、编译、安装。

而使用 --rebuild 的话，还会在 --recompile 的基础上再额外构建一个新的二进制包。

在构建结束时，构建目录将被删除(就好像用了 --clean)，源代码和 spec 文件也将被删除。

(5)显示配置

rpmbuild --showrc

将显示 rpmbuild 使用的、在 rpmrc 和 macros 配置文件中定义的选项的值。

一：跟宏定义相关的文件可分为两类：

1、通过macro files引用类：rpmrc 配置文件

/usr/lib/rpm/rpmrc                #全局配置

/usr/lib/rpm/redhat/rpmrc         #全局指定系统配置

/etc/rpmrc                        #系统相关

~/.rpmrc                          #用户自定义

这4个文件都是rpmrc相关的内容，rpmrc主要是用来定义一些跟平台特型相关的一些选项，比如

optflags: i386 -O2 -g -march=i386 -mtune=i686

optflags: i686 -O2 -g -march=i686

如果optflags引用的是i686，则optflags的值就是：-O2 -g -march=i686，因此这里就可以扩展用来制定macro files

macro files: /usr/lib/rpm/macros:/etc/rpm/macros

这个选项需要在编译阶段定义MACROFILES，否则macrofiles会加载默认的路径，具体的参考rpm源代码：/lib/rpmrc.c：      setDefaults方法。

2、直接定义类： Macro 配置文件

/usr/lib/rpm/macros              #全局配置

/usr/lib/rpm/macros.d            #全局配置扩展配置

/usr/lib/rpm/redhat/macros       #全局指定系统配置

/etc/rpm/macros                  #系统配置

~/.rpmmacros                     #用户自定义配置

/usr/lib/rpm/macros、/usr/lib/rpm/macros.d、/usr/lib/rpm/redhat/macros、/etc/rpm/、~/.rpmmacros

直接定义顾名思义就是直接写在文件里面的，这四个文件的优先级为：用户自定义相关：~/.rpmmacros > 系统相关的配置：/etc/rpm/ > 全局扩展配置：/usr/lib/rpm/macros.d/* > 全局的配置：/usr/lib/rpm/macros

二：查看宏定义：

1、rpm –eval “%{_sysconfdir}”

rpm –showrc | grep _sysconfdir

2、比如你修改~/.rpm/macros，修改rpmbuild的%_topdir 为：

%_topdir %{getenv:PWD}/rpmbuild

查看 rpm –eval “%{_topdir}”   或 rpm –showrc | grep _topdir 或rpmbuild –showrc | grep _topdir

3、一些比较重要的定义

3.1、rpmbuild目录相关的宏定义：

%{_topdir} %{getenv:HOME}/rpmbuild

%{_builddir} %{_topdir}/BUILD

%{_rpmdir} %{_topdir}/RPMS

%{_sourcedir} %{_topdir}/SOURCES

%{_specdir} %{_topdir}/SPECS

%{_srcrpmdir} %{_topdir}/SRPMS

%{_buildrootdir} %{_topdir}/BUILDROOT

3.2、操作系统、python、perl、node.js相关的一些宏定义

cat /etc/rpm/macros.dist

#dist macros.

%centos_ver 7

%centos 7

%jettech_ver 7

%jettech 7

%rhel 7

%dist .el7.centos

%el7 1

3.3、spec里面一些比较重要的宏

%{setup}/%{autosetup}：setup是包含在autosetup里面，宏的具体定义如下:

# One macro to (optionally) do it all.

# -S Sets the used patch application style, eg ‘-S git’ enables

# usage of git repository and per-patch commits.

# -N Disable automatic patch application

# -p Use -p for patch application

%autosetup(a:b:cDn:TvNS:p:)

%setup %{-a} %{-b} %{-c} %{-D} %{-n} %{-T} %{!-v:-q}

%{-S:%global __scm %{-S*}}

%{-S:%{expand:%_scm_setup%{-S*} %{!-v:-q}}}

%{!-N:%autopatch %{-v} %{-p:-p%{-p*}}}

也就是auotosetup相对于setup多扩展了一个-S参数。重要参数的意义如下

-n 解压到BUILD目录下面的目录名

-q 解压tarball的时候不显示具体的文件列表信息，这个选项是setup才有的选项，autosetup上面是没有放开这个选项

-c 解压之前先产生目录

-S patch/hg/git/git_am/quilt/bzr 对于-S选项会再调用其他的宏%_scm_setup%{-S*}，比如git，则会调用如下git宏，干的事就是把源码包初始化成一个git项目包

# Git

%__scm_setup_git(q)

%{__git} init %{-q}

%{__git} config user.name “%{__scm_username}”

%{__git} config user.email “%{__scm_usermail}”

%{__git} add .

%{__git} commit %{-q} -a\

–author “%{__scm_author}” -m “%{name}-%{version} base”

4、宏定义和修改

4.1 、通过–define关键字来扩展

rpmbuild -ba rpmbuild/SPECS/openstack-cinder.spec –define ‘_sysconfdir /test’

在spec里面通过%{_testdir}来引用

install -p -D -m 640 etc/cinder/cinder.conf.sample %{buildroot}%{_testdir}/cinder/cinder.conf

这样就把默认的_sysconfdir从/etc变成test

4.2、直接修改宏定义文件

直接修改上面定义macros的4个文件里面的宏

在spec文件里面定义

%define macro_name value

%define macro_name %(data)

宏使用，

%macro_name

%macro_name 1 2 3（1，2，3为参数传递给宏）

%0：宏名字

%*：传递给宏的所有参数

%#：传递给宏的参数个数

%1，参数1

%2，参数2

————————————————

版权声明：本文为CSDN博主「Michaelwubo」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/Michaelwubo/article/details/105833670](https://blog.csdn.net/Michaelwubo/article/details/105833670)

——————————————————————————————————————————————————

[https://blog.csdn.net/arv002/article/details/123546081](https://blog.csdn.net/arv002/article/details/123546081)

RPM（[Redhat](https://so.csdn.net/so/search?q=Redhat&spm=1001.2101.3001.7020) Package Manager）是用于Redhat、CentOS、Fedora等Linux 分发版（distribution）的常见的软件包管理器。因为它允许分发已编译的软件，所以用户只用一个命令就可以安装软件。看到这篇文章的朋友想必已经知道RPM是个啥，rpm/yum命令怎么用，废话不多说，直接进入正题，来看看RPM包咋打。

首先请准备一个Linux环境，比如CentOS。

RPM打包使用的是rpmbuild命令，这个命令来自rpm-build包，这个是必装的。

 

```bash
$ yum install rpm-build
```

当然也可以直接安装rpmdevtools，这个工具还包含一些其他的工具，同时它依赖rpm-build，所以直接安装的话会同时把rpm-build装上。

```bash
$ yum install rpmdevtools
```

当然，根据不同的软件构建过程，还需要其他的编译打包工具，比如C语言的make、gcc，python的setuptools等，根据需要安装即可。

首先请准备一个Linux环境，比如CentOS。

RPM打包使用的是rpmbuild命令，这个命令来自rpm-build包，这个是必装的。

 

```bash
$ yum install rpm-build
```

当然也可以直接安装rpmdevtools，这个工具还包含一些其他的工具，同时它依赖rpm-build，所以直接安装的话会同时把rpm-build装上。

```bash
$ yum install rpmdevtools
```

当然，根据不同的软件构建过程，还需要其他的编译打包工具，比如C语言的make、gcc，python的setuptools等，根据需要安装即可。

RPM打包的时候需要编译源码，还需要把编译好的配置文件啊二进制命令文件啊之类的东西按照安装好的样子放到合适的位置，还要根据需要对RPM的包进行测试，这些都需要先有一个“工作空间”。rpmbuild命令使用一套标准化的“工作空间”：

 

```bash
$ rpmdev-setuptree
```

rpmdev-setuptree这个命令就是安装rpmdevtools带来的。可以看到运行了这个命令之后，在$HOME家目录下多了一个叫做rpmbuild的文件夹，里边内容如下：

 

```bash
$ tree rpmbuildrpmbuild├── BUILD├── RPMS├── SOURCES├── SPECS└── SRPMS
```

    如果没有安装rpmdevtools的话，其实用mkdir命令创建这些文件夹也是可以的。

    mkdir -p ~/rpmbuild/{BUILD,RPMS,SOURCES,SPECS,SRPMS}

从这些文件的名字大体也能看得出来都是干嘛用的。具体来说：

| 默认位置 | 宏代码 | 名称 | 用途 | 
| -- | -- | -- | -- |
| ~/rpmbuild/SPECS |  %_specdir | 文件目录 |  保存 RPM 包配置（.spec）文件 | 
| ~/rpmbuild/SOURCES | %_sourcedir | 源代码目录 | 保存源码包（如 .tar 包）和所有 patch 补丁 | 
| ~/rpmbuild/BUILD | %_builddir | 构建目录 | 源码包被解压至此，并在该目录的子目录完成编译 | 
| ~/rpmbuild/BUILDROOT |  %_buildrootdir | 最终安装目录 | 保存 %install 阶段安装的文件 | 
| ~/rpmbuild/RPMS | %_rpmdir | 标准 RPM 包目录 | 生成/保存二进制 RPM 包 | 
| ~/rpmbuild/SRPMS |   %_srcrpmdir | 源代码 RPM 包目录 | 生成/保存源码 RPM 包(SRPM) | 


SPECS下是RPM包的配置文件，是RPM打包的“图纸”，这个文件会告诉rpmbuild命令如何去打包。“宏代码”这一列就可以在SPEC文件中用来代指所对应的目录，类似于编程语言中的宏或全局变量。当然~/rpmbuild这个文件夹也是有宏代码的，叫做%_topdir。

打包的过程有点像是流水线，分好几个工序：

1. 首先，需要把源代码放到%_sourcedir中；

2. 然后，进行编译，编译的过程是在%_builddir中完成的，所以需要先把源代码复制到这个目录下边，一般情况下，源代码是压缩包格式，那么就解压过来即可；

3. 第三步，进行“安装”，这里有点类似于预先组装软件包，把软件包应该包含的内容（比如二进制文件、配置文件、man文档等）复制到%_buildrootdir中，并按照实际安装后的目录结构组装，比如二进制命令可能会放在/usr/bin下，那么就在%_buildrootdir下也按照同样的目录结构放置；

4. 然后，需要配置一些必要的工作，比如在实际安装前的准备啦，安装后的清理啦，以及在卸载前后要做的工作啦等等，这样也都是通过配置在SPEC文件中来告诉rpmbuild命令；

5. 还有一步可选操作，那就是检查软件是否正常运行；

5. 最后，生成的RPM包放置到%_rpmdir，源码包放置到%_srpmdir下。

以上这些步骤都是配置在SPEC文件中的，具体来说各个阶段：

 

| 阶段 | 读取的目录 | 写入的目录 | 具体动作 | 
| -- | -- | -- | -- |
| %prep | %_sourcedir | %_builddir | 读取位于 %_sourcedir 目录的源代码和 patch 。之后，解压源代码至 %_builddir 的子目录并应用所有 patch。 | 
| %build | %_builddir | %_builddir | 编译位于 %_builddir 构建目录下的文件。通过执行类似 ./configure && make 的命令实现。 | 
| %install | %_builddir | %_buildrootdir | 读取位于 %_builddir 构建目录下的文件并将其安装至 %_buildrootdir 目录。这些文件就是用户安装 RPM 后，最终得到的文件。注意一个奇怪的地方: 最终安装目录 不是 构建目录。通过执行类似 make install 的命令实现。 | 
| %check | %_builddir | %_builddir | 检查软件是否正常运行。通过执行类似 make test 的命令实现。很多软件包都不需要此步。 | 
| bin | %_buildrootdir | %_builddir | 读取位于 %_buildrootdir 最终安装目录下的文件，以便最终在 %_rpmdir 目录下创建 RPM 包。在该目录下，不同架构的 RPM 包会分别保存至不同子目录， noarch 目录保存适用于所有架构的 RPM 包。这些 RPM 文件就是用户最终安装的 RPM 包。 | 
| src | %_sourcedir |  %_srcrpmdir | 创建源码 RPM 包（简称 SRPM，以.src.rpm 作为后缀名），并保存至 %_srcrpmdir 目录。SRPM 包通常用于审核和升级软件包。 | 


解释再多不如一个例子来的明白，这里用官方文档中的例子来操作一遍。

下面演示 GNU“Hello World” 项目的打包过程。虽然用 C 语言程序打印 “Hello World” 到标准输出是小菜一碟，但 GNU 版本包含了与一个典型的 FOSS 软件项目相关的最常用的外围组件，包括配置/编译/安装环境、文档、国际化等等。GNU 版本包含了一个由源代码和 configure/make 脚本组成的 tar 文件，但并不包含打包信息。因此，这是一个很好的 RPM 包打包示例。

还记得前面介绍到的几个阶段吗，先准备源码，这里我们直接下载官方例子的源码，是个压缩包：

$ cd ~/rpmbuild/SOURCES

$ wget [http://ftp.gnu.org/gnu/hello/hello-2.10.tar.gz](http://ftp.gnu.org/gnu/hello/hello-2.10.tar.gz)

    不知道为啥有时候源码包下起来特别慢甚至下不动，可以先用迅雷下载下来，然后传到虚拟机里。

然后后续的步骤就交给SPEC文件来配置了，编辑SPEC文件（Emacs 和 vi 的最新版本有 .spec 文件编辑模式，它会在创建新文件时打开一个类似的模板。所以可使用以下命令来自动使用模板文件）：

$ cd ~/rpmbuild/SPECS

$ vim hello.spec

既然有模板，那么后边的工作就是填空题了：

```
Name:     helloVersion:  2.1Release:  1%{?dist}Summary:  The "Hello World" program from GNUSummary(zh_CN):  GNU "Hello World" 程序License:  GPLv3+URL:      
```

1. Name 标签就是软件名，Version 标签为版本号，而 Release 是发布编号。

1. Summary 标签是简要说明，英文的话第一个字母应大写，以避免 rpmlint 工具（打包检查工具）警告。

1. License 标签说明软件包的协议版本，审查软件的 License 状态是打包者的职责，这可以通过检查源码或 LICENSE 文件，或与作者沟通来完成。

1. Group 标签过去用于按照 /usr/share/doc/rpm-/GROUPS 分类软件包。目前该标记已丢弃，vim的模板还有这一条，删掉即可，不过添加该标记也不会有任何影响。

1. %changelog 标签应包含每个 Release 所做的更改日志，尤其应包含上游的安全/漏洞补丁的说明。Changelog 日志可使用 rpm --changelog -q <packagename> 查询，通过查询可得知已安装的软件是否包含指定漏洞和安全补丁。%changelog 条目应包含版本字符串，以避免 rpmlint 工具警告。

1. 多行的部分，如 %changelog 或 %description 由指令下一行开始，空行结束。

1. 一些不需要的行 (如 BuildRequires 和 Requires) 可使用 ‘#’ 注释。

1. %prep、%build、%install、%file暂时用默认的，未做任何修改。

有点迫不及待了，尝试执行以下命令，以构建源码、二进制和包含调试信息的软件包：

$ rpmbuild -ba hello.spec

1）包含要安装的文件

不过上边的命令执行失败了0_0。

命令执行后，提示并列出未打包的文件：

 

```bash
RPM build errors:    Installed (but unpackaged) file(s) found:   /usr/bin/hello   /usr/share/info/dir   /usr/share/info/hello.info.gz   /usr/share/locale/bg/LC_MESSAGES/hello.mo   /usr/share/locale/ca/LC_MESSAGES/hello.mo
```

那些需要安装在系统中的文件，我们需要在 %files 中声明它们，这样rpmbuild命令才知道哪些文件是要安装的。

注意不要使用形如 /usr/bin/ 的硬编码, 应使用类似 %{_bindir}/hello 这样的宏来替代。手册页应在 %doc 中声明 : %doc %{_mandir}/man1/hello.1.*。

由于示例的程序使用了翻译和国际化，因此会看到很多未声明的 i18 文件。 使用 推荐方法 来声明它们：

- 包含程序安装的相关文件

- 查找 %install 中的语言文件： 

%find_lang %{name}

- 添加编译依赖： 

BuildRequires: gettext

- 声明找到的文件： 

%files -f %{name}.lang

 这样下来，%files部分的内容为：

```bash
 %files -f %{name}.lang%doc AUTHORS ChangeLog NEWS README THANKS TODO%license COPYING%{_mandir}/man1/hello.1.*%{_infodir}/hello.info.*%{_bindir}/hello
```

2）info文件的处理

如果程序使用 GNU info 文件，你需要确保安装和卸载软件包，不影响系统中的其他软件，按以下步骤操作：

    在 %install 中添加删除 ‘dir’ 文件的命令： rm -f %{buildroot}/%{_infodir}/dir

    在安装后和卸载前添加依赖 Requires(post): info 和 Requires(preun): info

    添加以下安装脚本（在%install和%files中间即可，分别对应安装后和卸载前的阶段，详见后边内容）：

```bash
%post/sbin/install-info %{_infodir}/%{name}.info %{_infodir}/dir || : %preunif [ $1 = 0 ] ; then/sbin/install-info --delete %{_infodir}/%{name}.info %{_infodir}/dir || :fi
```

3）看看各个目录里边的东西

* %_sourcedir下边仍然是源码的压缩包；

* %_builddir下边是源码解压出来的文件夹hello-2.10及其下边的所有文件；

* %_buildrootdir下边是一个名为“hello-2.10-1.el7.centos.x86_64”的文件夹（那么生成的RPM包的完整名称也是{Name}-{Version}-{Release}.{Arch}.rpm），这个文件夹下边有“usr”文件夹，其下还有“bin”、“lib”、“share”、“src”这几个文件夹，可以看到这里的目录结构和安装之后各个文件和文件夹的位置已经是基本一致的了。这里要注意的是，“usr”所在的“根目录”，也就是“hello-2.10-1.el7.centos.x86_64”这个文件夹，用宏表示就是%{buildroot}，有的地方也用$RPM_BUILD_ROOT 代替 %{buildroot}，不过跟%{_buildrootdir}不是一个概念，请注意。

为什么是“趁着失败”呢，因为成功打包之后有些文件夹（比如%_builddir和%_buildrootdir）内的内容就会被清理掉了，不过也可以在%build和%install阶段的时候把这俩文件夹内的东西tree一下或者干脆复制到其他地方再看也行。

那么%build和%install以及其他几个阶段一般怎么配置呢？

4）本示例最终的完整SPEC

```
Name:     helloVersion:  2.10Release:  1%{?dist}Summary:  The "Hello World" program from GNUSummary(zh_CN):  GNU "Hello World" 程序License:  GPLv3+URL:      
```

那么就开动起来，在执行一下rpmbuild命令瞅瞅吧：

```bash
$ rpmbuild -ba hello.spec
```

OK，执行成功了，看看成果吧：

```bash
 $ tree ~/rpmbuild/*RPMS/root/rpmbuild/RPMS└── x86_64    ├── hello-2.10-1.el7.centos.x86_64.rpm    └── hello-debuginfo-2.10-1.el7.centos.x86_64.rpm/root/rpmbuild/SRPMS└── hello-2.10-1.el7.centos.src.rpm
```

在RPMS文件夹下生成了RPM包，在x86_64下，表示所应用的架构，由于没有指定arch为noarch，所以默认用本机架构。在SRPMS文件夹下生产了源码包，源码包当然木有架构这一说了。

所以有些人喜欢在装软件的时候从源码开始安装，因为更能贴合本机的物理情况，就像用光盘安装windows和GHOST安装windows，相对来说光盘一步一步安装更好一点点，不过我比较懒，还是直接yum install。

**5）运行一下下** 

既然已经有RPM包了，那就安装上吧：

```bash
$ rpm -ivh ~/rpmbuild/RPMS/x86_64/hello-2.10-1.el7.centos.x86_64.rpm
```

运行一下：

```
$ helloHello, world!$ which hello/usr/bin/hello$ rpm -qf `which hello`hello-2.10-1.el7.centos.x86_64
```

可以看到编译好的二进制文件hello已经装到/usr/bin下了，其他位置的文件请自行查看吧^_^。因为这个示例程序五脏俱全，不妨man一下，看看使用文档~

```
$ man hello
```

SPEC文件是RPM打包的核心，下面就对SPEC文件中漏掉的而且比较重要的关于各个部分的配置方法进行详细说明：

4.1 %prep阶段

%prep 部分描述了解压源码包的方法。一般而言，其中包含 %autosetup 命令。另外，还可以使用 %setup 和 %patch 命令来指定操作 Source0、Patch0 等标签的文件。

%autosetup 命令

%autosetup 命令用于解压源码包。可用选项包括：

    -n name : 如果源码包解压后的目录名称与 RPM 名称不同，此选项用于指定正确的目录名称。例如，如果 tarball 解压目录为 FOO，则使用 “%autosetup -n FOO”。

    -c name : 如果源码包解压后包含多个目录，而不是单个目录时，此选项可以创建名为 name 的目录，并在其中解压。

%setup 命令

如果使用 %setup 命令，通常使用 -q 抑止不必要的输出。

如果需要解压多个文件，有更多 %spec 选项可用，这对于创建子包很有用。常用选项如下：

    -a number：在切换目录后，只解压指定序号的 Source 文件（例如 “-a 0” 表示 Source0）。

    -b number ：在切换目录前， 只解压指定序号的 Source 文件（例如 “-b 0” 表示 Source0）。

    -D：解压前，不删除目录。

    -T：禁止自动解压归档。

%patch 命令

如果使用 %autosetup 命令，则不需要手动进行补丁管理。如果你的需求很复杂，或需要与 EPEL 兼容，需要用到此部分的内容。%patch0 命令用于应用 Patch0（%patch1 应用 Patch1，以此类推）。Patches 是修改源码的最佳方式。常用的 -pNUMBER 选项，向 patch 程序传递参数，表示跳过 NUM 个路径前缀。

补丁文件名通常像这样 telnet-0.17-env.patch，命名格式为 %{name} - %{version} - REASON.patch（有时省略 version 版本）。补丁文件通常是 diff -u 命令的输出；如果你在 ~/rpmbuild/BUILD 子目录执行此命令，则之后便不需要指定 -p 选项。

    为一个文件制作补丁的步骤：

    cp foo/bar foo/bar.orig

    vim foo/bar

    diff -u foo/bar.orig foo/bar > ~/rpmbuild/SOURCES/PKGNAME.REASON.patch

  

    如果需要修改多个文件，简单方法是复制 BUILD 下的整个子目录，然后在子目录执行 diff。切换至 ~rpmbuild/BUILD/NAME 目录后，执行以下命令：

    cp -pr ./ ../PACKAGENAME.orig/

    ... 执行修改 ...

    diff -ur ../PACKAGENAME.orig . > ~/rpmbuild/SOURCES/NAME.REASON.patch

    如果你想在一个补丁中编辑多个文件，你可以在编辑之前，使用 .orig 扩展名复制原始文件。然后，使用 gendiff（在 rpm-build 包中）创建补丁文件。

4.2 %build阶段

%build阶段顾名思义就是对解压到%_builddir下的源码进行编译的阶段，整个过程在该目录下完成。

许多程序使用 GNU configure 进行配置。默认情况下，文件会安装到前缀为 “/usr/local” 的路径下，对于手动安装很合理。然而，打包时需要修改前缀为 “/usr”。共享库路径视架构而定，安装至 /usr/lib 或 /usr/lib64 目录。

由于 GNU configure 很常见，可使用 %configure 宏来自动设置正确选项（例如，设置前缀为 /usr）。一般用法如下：

```
 %configure
 make %{?_smp_mflags}
```

若需要覆盖 makefile 变量，请将变量作为参数传递给 make：

```
make %{?_smp_mflags} CFLAGS="%{optflags}" BINDIR=%{_bindir}
```

你会发现SPEC中会用到很多预定义好的宏，用来通过一个简单的宏来完成一个或一系列常见的操作，比如：%prep阶段用于解压的%setup和%autosetup，%build阶段的%configure等。

 4.3 %install阶段

此阶段包含安装阶段需要执行的命令，即从 %{_builddir} 复制相关文件到 %{buildroot} 目录（通常表示从 ~/rpmbuild/BUILD 复制到 ~/rpmbuild/BUILDROOT/XXX) 目录，并根据需要在 %{buildroot} 中创建必要目录。

    容易混淆的术语：

    * “build 目录”，也称为 %{_builddir}，实际上与 “build root”，又称为 %{buildroot}，是不同的目录。在前者中进行编译，并将需要打包的文件从前者复制到后者， %{buildroot}通常为 ~/rpmbuild/BUILD/%{name}-%{version}-%{release}.%{arch}。

    * 在 %build 阶段，当前目录为 %{buildsubdir}，是 %prep 阶段中在 %{_builddir} 下创建的子目录。这些目录通常名为 ~/rpmbuild/BUILD/%{name}-%{version}。

    * %install 阶段的命令不会在用户安装 RPM 包时执行，此阶段仅在打包时执行。

一般，这里执行 “make install” 之类的命令：

```
%installrm -rf %{buildroot} # 仅用于 RHEL 5%makeinstall
```

理想情况下，对于支持的程序，你应该使用 %makeinstall（这又是一个宏），它等同于 DESTDIR=%{buildroot}，它会将文件安装到 %{buildroot} 目录中。

    使用 “%makeinstall” 宏。此方法可能有效，但也可能失败。该宏会展开为 make prefix=%{buildroot}%{_prefix} bindir=%{buildroot}%{_bindir} ... install，可能导致某些程序无法正常工作。请在 %{buildroot} 根据需要创建必要目录。

使用 auto-destdir 软件包的话，需要 BuildRequires: auto-destdir，并将 make install 修改为 make-redir DESTDIR=%{buildroot} install。这仅适用于使用常用命令安装文件的情况，例如 cp 和 install。

手动执行安装。这需要在 %{buildroot} 下创建必要目录，并从 %{_builddir} 复制文件至 %{buildroot} 目录。要特别注意更新，通常会包含新文件。示例如下：

```
%installrm -rf %{buildroot}mkdir -p %{buildroot}%{_bindir}/cp -p mycommand %{buildroot}%{_bindir}/
```

4.4 %check 阶段

如果需要执行测试，使用 %check 是个好主意。测试代码应写入 %check 部分（紧接在 %install 之后，因为需要测试 %{buildroot} 中的文件），而不是写入 %{build} 部分，这样才能在必要时忽略测试。通常，此部分包含：

make test

    1

有时候也可以用：

make check

    1

请熟悉 Makefile 的用法，并选择适当的方式。

 

4.5 %files 部分

此部分列出了需要被打包的文件和目录。

%files 基础

%defattr 用于设置默认文件权限，通常可以在 %files 的开头看到它。注意，如果不需要修改权限，则不需要使用它。其格式为：

%defattr(<文件权限>, <用户>, <用户组>, <目录权限>)

    1

第 4 个参数通常会省略。常规用法为 %defattr(-,root,root,-)，其中 “-” 表示默认权限。

您应该列出该软件包拥有的所有文件和目录。尽量使用宏代替目录名，具体的宏列表如下：

```ruby
%{_sysconfdir}        /etc%{_prefix}            /usr%{_exec_prefix}       %{_prefix}%{_bindir}            %{_exec_prefix}/bin%{_libdir}            %{_exec_prefix}/%{_lib}%{_libexecdir}        %{_exec_prefix}/libexec%{_sbindir}           %{_exec_prefix}/sbin%{_sharedstatedir}    /var/lib%{_datarootdir}       %{_prefix}/share%{_datadir}           %{_datarootdir}%{_includedir}        %{_prefix}/include%{_infodir}           /usr/share/info%{_mandir}            /usr/share/man%{_localstatedir}     /var%{_initddir}          %{_sysconfdir}/rc.d/init.d%{_var}               /var%{_tmppath}           %{_var}/tmp%{_usr}               /usr%{_usrsrc}            %{_usr}/src%{_lib}               lib (lib64 on 64bit multilib systems)%{_docdir}            %{_datadir}/doc%{buildroot}          %{_buildrootdir}/%{name}-%{version}-%{release}.%{_arch}$RPM_BUILD_ROOT       %{buildroot}
```

如果路径以 “/” 开头（或从宏扩展），则从 %{buildroot} 目录取用。否则，假设文件在当前目录中（例如：在 %{_builddir} 中，包含需要的文档）。如果您的包仅安装一个文件，如 /usr/sbin/mycommand，则 %files 部分如下所示：

```
%files
%{_sbindir}/mycommand
```

若要使软件包不受上游改动的影响，可使用通配符匹配所有文件：

%{_bindir}/*

包含一个目录：

%{_datadir}/%{name}/

注意，%{_bindir}/* 不会声明此软件包拥有 /usr/bin 目录，而只包含其中的文件。如果您列出一个目录，则该软件包拥有这个目录，及该目录内的所有文件和子目录。因此，不要列出 %{_bindir}，并且要小心的处理那些可能和其他软件包共享的目录。

如果存在以下情况，可能引发错误：

    通配符未匹配到任何文件或目录

    文件或目录被多次列出

    未列出 %{buildroot} 下的某个文件或目录

您也可以使用 %exclude 来排除文件。这对于使用通配符来列出全部文件时会很有用，注意如果未匹配到任何文件也会造成失败。

%files 前缀

上边的“hello”的示例中，%files部分还有用到%doc等宏，可能您看得一知半解，这里详细介绍一下。

如果需要在 %files 部分添加一个或多个前缀，用空格分隔。

%doc 用于列出 %{_builddir} 内，但不复制到 %{buildroot} 中的文档。通常包括 README 和 INSTALL等。它们会保存至 /usr/share/doc 下适当的目录中，不需要声明 /usr/share/doc 的所有权。

    注意： 如果指定 %doc 条目，rpmbuild < 4.9.1 在安装前会将 %doc 目录删除。这表明已保存至其中的文档，例如，在 %install 中安装的文档会被删除，因此最终不会出现在软件包中。如果您想要在 %install 中安装一些文档，请将它们临时安装到 build 目录（不是 build root 目录）中，例如 _docs_staging，接着在 %files 中列出，如 %doc _docs_staging/* 这样。

配置文件保存在 /etc 中，一般会这样指定（确保用户的修改不会在更新时被覆盖）：

%config(noreplace) %{_sysconfdir}/foo.conf

如果更新的配置文件无法与之前的配置兼容，则应这样指定：

%config %{_sysconfdir}/foo.conf

“%attr(mode, user, group)” 用于对文件进行更精细的权限控制，”-” 表示使用默认值：

%attr(0644, root, root) FOO.BAR

“%caps(capabilities)” 用于为文件分配 POSIX capabilities。例如：

%caps(cap_net_admin=pe) FOO.BAR

如果包含特定语言编写的文件，请使用 %lang 来标注：

%lang(de) %{_datadir}/locale/de/LC_MESSAGES/tcsh*

使用区域语言（Locale）文件的程序应遵循 处理 i18n 文件的建议方法：

    在 %install 步骤中找出文件名： %find_lang ${name}

    添加必要的编译依赖： BuildRequires: gettext

    使用找到的文件名： %files -f ${name}.lang

 

4.6 Scriptlets

当用户安装或卸载 RPM 时，您可能想要执行一些命令。这可以通过 scriptlets 完成。

脚本片段可以：

    在软体包安装之前 (%pre) 或之后 (%post) 执行

    在软体包卸载之前 (%preun) 或之后 (%postun) 执行

    在事务开始 (%pretrans) 或结束 (%posttrans) 时执行

例如，每个二进制 RPM 包都会在动态链接器的默认路径中存储共享库文件，并在 %post 和 %postun 中调用 ldconfig 来更新库缓存。如果软件包有多个包含共享库的子包，则每个软体包也需要执行相同动作。

 

```
%post -p /sbin/ldconfig%postun -p /sbin/ldconfig
```

如果仅执行一个命令，则 “-p” 选项会直接执行，而不启用 shell。然而，若有许多命令时，不要使用此选项，按正常编写 shell 脚本即可。

如果你在脚本片段中执行任何程序，就必须以 Requires(CONTEXT)（例： Requires(post)）的形式列出所有依赖。

%pre、%post、%preun 和 %postun 提供 $1 参数，表示动作完成后，系统中保留的此名称的软件包数量。因此可用于检查软件安装情况，不过不要比较此参数值是否等于 2，而是比较是否大于等于 2。对于%pretrans 和 %posttrans，$1 的值恒为 0。

例如，如果软件包安装了一份 info 手册，那么可以用 info 包提供的 install-info 来更新 info 手册索引。首先，我们不保证系统已安装 info 软件包，除非明确声明需要它；其次，我们不想在 install-info 执行失败时，使软件包安装失败：

```
Requires(post): infoRequires(preun): info ... %post/sbin/install-info %{_infodir}/%{name}.info %{_infodir}/dir || : %preunif [ $1 = 0 ] ; then/sbin/install-info --delete %{_infodir}/%{name}.info %{_infodir}/dir || :fi
```

上边的示例中还有一个安装 info 手册时的小问题需要解释一下。install-info 命令会更新 info 目录，所以我们应该在 %install 阶段删除 %{buildroot} 中无用的空目录：

```bash
rm -f %{buildroot}%{_infodir}/dir
```

旦 SPEC 编写完毕，请执行以下命令来构建 SRPM 和 RPM 包：

$ rpmbuild -ba program.spec

如果成功，RPM 会保存至 ~/rpmbuild/RPMS，SRPM 会保存至 ~/rpmbuild/SRPMS。

如果失败，请查看 BUILD 目录的相应编译日志。为了帮助调试，可以用 --short-circuit 选项来忽略成功的阶段。例如，若想要（略过更早的阶段）重新从 %install 阶段开始，请执行：

$ rpmbuild -bi --short-circuit program.spec

如果只想创建 RPM，请执行：

rpmbuild -bb program.spec

如果只想创建 SRPM（不需要执行 %prep 或 %build 或其他阶段），请执行：

rpmbuild -bs program.spec

为避免常见错误，请先使用 rpmlint 查找 SPEC 文件的错误：

$ rpmlint program.spec

如果返回错误/警告，使用 “-i” 选项查看更详细的信息。

也可以使用 rpmlint 测试已构建的 RPM 包，检查 SPEC/RPM/SRPM 是否存在错误。你需要在发布软件包之前，解决这些警告。此页面 提供一些常见问题的解释。如果你位于 SPEC 目录中，请执行：

$ rpmlint NAME.spec ../RPMS/*/NAME*.rpm ../SRPMS/NAME*.rpm

进入 ~/rpmbuild/RPMS 下的特定架构目录中，您会发现有许多二进制 RPM 包。使用以下命令快速查看 RPM 包含的文件和权限：

$ rpmls *.rpm

如果看上去正常，以 root 身份安装它们：

$ rpm -ivp package1.rpm package2.rpm package3.rpm ...

以不同方式来测试程序，看看是否全部都正常工作。如果是 GUI 工具，请确认其是否出现在桌面菜单中，否则表示 .desktop 条目可能有错。

最后卸载软件包：

$ rpm -e package1 package2 package3

默认位置    宏代码    名称    用途

~/rpmbuild/SPECS    %_specdir    Spec 文件目录    保存 RPM 包配置（.spec）文件

~/rpmbuild/SOURCES    %_sourcedir    源代码目录    保存源码包（如 .tar 包）和所有 patch 补丁

~/rpmbuild/BUILD    %_builddir    构建目录    源码包被解压至此，并在该目录的子目录完成编译

~/rpmbuild/BUILDROOT    %_buildrootdir    最终安装目录    保存 %install 阶段安装的文件

~/rpmbuild/RPMS    %_rpmdir    标准 RPM 包目录    生成/保存二进制 RPM 包

~/rpmbuild/SRPMS    %_srcrpmdir    源代码 RPM 包目录    生成/保存源码 RPM 包(SRPM)

6.2 spec文件阶段

阶段    读取的目录    写入的目录    具体动作

%prep    %_sourcedir    %_builddir    读取位于 %_sourcedir 目录的源代码和 patch 。之后，解压源代码至 %_builddir 的子目录并应用所有 patch。

%build    %_builddir    %_builddir    编译位于 %_builddir 构建目录下的文件。通过执行类似 ./configure && make 的命令实现。

%install    %_builddir    %_buildrootdir    读取位于 %_builddir 构建目录下的文件并将其安装至 %_buildrootdir 目录。这些文件就是用户安装 RPM 后，最终得到的文件。注意一个奇怪的地方: 最终安装目录 不是 构建目录。通过执行类似 make install 的命令实现。

%check    %_builddir    %_builddir    检查软件是否正常运行。通过执行类似 make test 的命令实现。很多软件包都不需要此步。

bin    %_buildrootdir    %_rpmdir    读取位于 %_buildrootdir 最终安装目录下的文件，以便最终在 %_rpmdir 目录下创建 RPM 包。在该目录下，不同架构的 RPM 包会分别保存至不同子目录， noarch 目录保存适用于所有架构的 RPM 包。这些 RPM 文件就是用户最终安装的 RPM 包。

src    %_sourcedir    %_srcrpmdir    创建源码 RPM 包（简称 SRPM，以.src.rpm 作为后缀名），并保存至 %_srcrpmdir 目录。SRPM 包通常用于审核和升级软件包。

```bash
%{_sysconfdir}        /etc%{_prefix}            /usr%{_exec_prefix}       %{_prefix}%{_bindir}            %{_exec_prefix}/bin%{_libdir}            %{_exec_prefix}/%{_lib}%{_libexecdir}        %{_exec_prefix}/libexec%{_sbindir}           %{_exec_prefix}/sbin%{_sharedstatedir}    /var/lib%{_datarootdir}       %{_prefix}/share%{_datadir}           %{_datarootdir}%{_includedir}        %{_prefix}/include%{_infodir}           /usr/share/info%{_mandir}            /usr/share/man%{_localstatedir}     /var%{_initddir}          %{_sysconfdir}/rc.d/init.d%{_var}               /var%{_tmppath}           %{_var}/tmp%{_usr}               /usr%{_usrsrc}            %{_usr}/src%{_lib}               lib (lib64 on 64bit multilib systems)%{_docdir}            %{_datadir}/doc%{buildroot}          %{_buildrootdir}/%{name}-%{version}-%{release}.%{_arch}$RPM_BUILD_ROOT       %{buildroot}
```

- Fedora Packaging Guidelines

- How to create an RPM package

- How to create a GNU Hello RPM package

- Spec File Preamble