编写专业的 C++ 应用程序，您不仅需要基本的文本编辑器和[编译器](https://so.csdn.net/so/search?q=%E7%BC%96%E8%AF%91%E5%99%A8&spm=1001.2101.3001.7020)。您还需要更多工具。在这篇文章中，我们将介绍大量 C++ 编程工具，包括：编译器，IDE，调试器等。

C++计算机编程语言已经成为使用最广泛的现代编程语言之一。使用C++构建的软件以其性能和效率而闻名。C++已用于构建众多广受欢迎的核心库、以及类似 Microsoft Office 之类的应用程序，Unreal之类的[游戏引擎](https://so.csdn.net/so/search?q=%E6%B8%B8%E6%88%8F%E5%BC%95%E6%93%8E&spm=1001.2101.3001.7020)，Adobe Photoshop之类的软件工具，Clang之类的编译器，MySQL之类的数据库，甚至包括Windows之类的操作系统。平台不断发展和壮大。

现代C++被定义为利用基于 C++11，C++14 和 C++17 语言特性的C++代码。这些按年份命名的语言标准（分别是2011年，2014年和2017年），包括对核心语言的许多重要新特性和增强，以实现功能强大，高性能和无错误的代码。现代C++具有支持面向对象编程，函数编程，泛型编程和低级内存操作功能的高级功能。

包括微软、因特尔和自由软件基金会等，都有其自己的C++编译器。诸如 Microsoft，QT Company，JetBrains和Embarcadero之类的公司提供了用于编写C++代码的集成开发环境。适用于C++的流行库可用于广泛的计算机学科，包括人工智能、机器学习、机器人、数学、科学计算、音频处理和图像处理等等。

在这篇文章中，我们将介绍许多编译器，构建工具，IDE，库，框架，编码助手，以及更多可以支持和增强现代C++开发的内容。

开始吧！

有许多支持现代C++的流行编译器，包括 GCC/g ++，MSVC（Microsoft Visual C ++）和 Clang。每个编译器对操作系统的支持都不同，GCC/g ++始于1980年代后期，Microsoft的Visual C ++于1990年代初，而Clang则在2000年代后期。这三个编译器都支持现代C++，至少支持C++ 17，但是它们各自的源代码许可差异很大。

GCC是GCC指导委员会开发、维护和定期更新的通用编译器，率属于GNU项目。GCC描述了许多针对硬件平台和多种语言的庞大编译器家族。虽然它主要针对类Unix平台，但Windows可以通过Cygwin或MinGW运行时库提供支持。GCC 支持编译最新的C++代码，直至C++17，并提供了对某些C++20功能的实验性支持。它还可以编译基于C++标准的各种语言扩展。GCC 使用 GPLv3 许可进行开源，带有GCC运行时库例外。GCC得到了CMake和Ninja等构建工具的支持，以及CLion，Qt Creator和Visual Studio Code等许多IDE的支持。

[https://gcc.gnu.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgcc.gnu.org%2F)

[https://gcc.gnu.org/projects/cxx-status.html](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgcc.gnu.org%2Fprojects%2Fcxx-status.html)

Microsoft Visual C ++（MSVC）是微软公司的编译器，用于实现自定义C++标准（称为Visual C ++）。它会定期更新，并且像GCC和Clang一样，支持最新C ++17的现代C++标准，并提供对某些C++20功能的实验性支持。MSVC是在Microsoft自己的Visual Studio中构建C++应用程序的主要方法。它通常针对Windows，Android，iOS和Linux上的许多体系结构。对构建工具和IDE的支持是有限的，但仍在增长。CMake扩展在Visual Studio 2019中可用。MSVC可以与Visual Studio Code一起使用，CLion和Qt Creator的有限支持以及其他扩展。MSVC是微软专有的项目，可以通过商业许可获得，同时也提供社区版。

[https://zh.wikipedia.org/wiki/Microsoft_Visual_C%2B%2B](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fen.wikipedia.org%2Fwiki%2FMicrosoft_Visual_C%252B%252B)

[https://devblogs.microsoft.com/visualstudio/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fdevblogs.microsoft.com%2Fvisualstudio%2F)

[https://visualstudio.microsoft.com/vs/community/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fvisualstudio.microsoft.com%2Fvs%2Fcommunity%2F)

Clang描述了为LLVM项目维护和定期开发的C语言族的大量编译器。尽管它针对许多流行的体系结构，但是与GCC相比，它支持的平台较少。LLVM项目通过关键设计原则定义了Clang —— 严格遵守C++标准（尽管提供了对GCC扩展的支持），模块化设计以及在编译过程中对源代码的结构进行的最小修改等。像GCC一样，Clang编译具有支持C++17标准和实验性C++20的现代C++代码。它可以在开源（Apache许可证版本2.0）许可下使用。Clang还获得了诸如CMake和Ninja之类的构建工具以及诸如CLion，Qt Creator，Xcode之类的IDE之类的广泛支持。

[https://clang.llvm.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fclang.llvm.org%2F)

[https://clang.llvm.org/cxx_status.html](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fclang.llvm.org%2Fcxx_status.html)

Make是广泛使用的知名构建系统，尤其是在Unix和类似Unix的操作系统中。Make通常用于从源代码构建可执行程序和库。但是该工具适用于涉及执行任意命令以将源文件转换为目标结果的任何过程。Make与任何特定的编程语言都不紧密。它会自动确定已更改了哪些源文件，然后执行最少的构建过程以获取最终输出。它还用于将编译结果安装到系统

[https://www.gnu.org/software/make/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.gnu.org%2Fsoftware%2Fmake%2F)

CMake是用于管理构建过程的跨平台工具。尤其是大型应用程序和依赖库的构建，可能是一个非常复杂的过程，尤其是当您支持多个编译器时。CMake对此进行了抽象。您可以使用一种通用语言定义复杂的构建过程，并将其转换为适用于各种被支持的编译器、IDE和构建工具的本机构建指令，包括Ninja（如下所示）。有适用于Windows，macOS和Linux的CMake版本。

[https://cmake.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fcmake.org%2F)

Ninja构建系统用于构建应用程序的实际过程，类似于Make（一个传统的但现在使用较少的实用程序）。它着重于通过并行化构建来尽可能快地运行。它通常与CMake配对使用，后者支持为Ninja构建系统创建构建文件。Ninja 的功能集故意保持最小，因为重点在于速度。

[https://ninja-build.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fninja-build.org%2F)

MSBuild是基于命令行的内置平台，可从Microsoft获得开源（MIT）许可。它可用于自动化编译和部署项目的过程。也可以独立使用或者与Visual Studio打包在一起，也可以从Github中获得。MSBuild文件的结构和功能与Make非常相似。MSBuild具有基于XML的文件格式，主要支持Windows，但也支持macOS和Linux。诸如CLion和C ++ Builder之类的IDE也可以与MSBuild集成。

[https://docs.microsoft.com/zh-cn/visualstudio/msbuild/msbuild](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fdocs.microsoft.com%2Fen-us%2Fvisualstudio%2Fmsbuild%2Fmsbuild)

诸如Conan，vcpkg和Buckaroo之类的程序包管理器已在C++社区中变得越来越流行。程序包管理器是用于安装库或组件的工具。

Conan是一个分散式开源（MIT）软件包管理器，它支持多个平台和所有构建系统（例如CMake和MSBuild）。Conan支持二进制文件，其目标是自动化依赖性管理，以节省开发和持续集成的时间。

微软的vcpkg是MIT许可下的开源软件，支持Windows，macOS和Linux（甚至支持与CMake集成）。Vcpkr需要在Visual Studio 2015或更高版本中使用，因此其使用范围有所限制。

Buckaroo是一个鲜为人知的开源软件包管理器，可以从GitHub，BitBucket，GitLab等获取依赖项。Buckaroo为许多IDE提供了集成，包括CLion，Visual Studio Code，XCode等。

以下是上述软件包管理器的链接：

- 

- 

- 

Compiler Explorer 是一个基于Web的工具，您可以从多种C++编译器和同一编译器的不同版本中进行选择，用于测试代码。这使开发人员可以在编译器之间比较为特定C++构造生成的代码，并测试正确的行为。不仅有Clang，GCC和MSVC，还有鲜为人知的编译器，例如DJGPP，ELLCC，Intel C ++等。

[https://godbolt.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgodbolt.org%2F)

您还可以使用的便捷在线编译器的列表：例如Coliru，Wandbox，CppInsighs等：[https](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Farnemertz.github.io%2Fonline-compilers%2F)：//arnemertz.github.io/online-compilers/

大量的编辑器和集成开发环境（IDE）可用于现代C++开发。文本编辑器虽然很轻量级，但功能不如完整的IDE，因此仅用于编写代码的过程，而不用于调试或测试。全面开发需要其他工具，而IDE包含这些工具并集成到一个紧密集成的集成开发环境中。可以使用许多文本编辑器（例如Sublime Text，Atom，Visual Studio Code，vi/vim和Emacs）编写C++代码。但是，有些IDE是专门为现代C++而设计的，例如CLion，Qt Creator和C ++ Builder，而Xcode和Visual Studio等IDE也支持其他语言。

- Sublime Text 是一个商业文本编辑器，可通过插件扩展对现代C++的支持。

- Atom是一个开放源代码（MIT许可）文本编辑器，它通过带有可用于调试和编译的集成的软件包来支持现代C++。

- Visual Studio Code 是 Microsoft 提供的流行的开源（MIT许可）源代码编辑器。

提供了许多扩展，这些扩展将诸如调试和现代C++的自动代码完成等功能引入Visual Studio Code。Sublime Text，Atom和Visual Studio Code中，这些工具均可用于Windows，macOS和Linux。

以下是上述工具的链接：

- 

- 

- 

Vi/Vim 和 Emacs是基于命令行的免费文本编辑器，主要在Linux上使用，但也可用于macOS和Windows。可以通过使用脚本将现代C++支持添加到Vi/Vim，Emacs可以通过使用模块来支持现代C++。

[https://www.vim.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.vim.org%2F)

[https://www.gnu.org/software/emacs/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.gnu.org%2Fsoftware%2Femacs%2F)

CLion 是 JetBrains 的商业 IDE，支持现代C++。它可以与CMake和Gradle等构建工具一起使用，与GDB和LLDB调试器集成，可以与版本控制系统（例如Git），测试库（例如Boost.Test）和各种文档工具一起使用。它具有代码生成，重构，动态代码分析，符号导航等功能。

[https://www.jetbrains.com/clion/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.jetbrains.com%2Fclion%2F)

Qt Creator 是 Qt Company 提供的免费开源IDE，支持Windows，macOS和Linux。Qt Creator具有UI设计器、语法高亮显示、自动代码完成以及与许多不同的现代C++编译器（例如GCC和CLang）集成的功能。Qt Creator与Qt库紧密集成，可快速构建跨平台应用程序。此外，它与标准版本控制系统（如Git），调试器（如GDB和LLDB），构建系统（如CMake）集成，并且可以将跨平台部署到iOS和Android设备。

[https://www.qt.io/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.qt.io%2F)

C ++ Builder 是 Embarcadero Technologies的商业IDE，可在Windows上运行。C++ Builder屡获殊荣，主要用于 Windows 开发的Visual Component Library（VCL）和用于Windows，iOS和Android的跨平台开发的FireMonkey（FMX）。C ++ Builder编译器具有Clang的增强版本，集成的调试器，可视UI设计器，数据库库，全面的RTL，以及诸如语法突出显示，代码完成和重构的标准功能。C ++ Builder具有CMake的集成，可以与Ninja以及MSBuild一起使用。

[https://www.embarcadero.com/products/cbuilder](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.embarcadero.com%2Fproducts%2Fcbuilder)

[https://www.embarcadero.com/products/cbuilder/starter](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.embarcadero.com%2Fproducts%2Fcbuilder%2Fstarter)

Visual C++是Microsoft的商业Visual Studio IDE。Visual Studio在IDE中集成了构建，调试和测试。它提供了Microsoft基础类（MFC）库，该库封装了对Win32 API 的访问。Visual Studio 具有用于某些平台的可视UI设计器，附带 MSBuild，支持CMake并提供标准功能，例如代码自动完成，重构和语法高亮显示。此外，Visual Studio支持多种其他编程语言，其C++方面侧重于Windows，并逐渐添加了对其他平台的支持。

[https://visualstudio.microsoft.com/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fvisualstudio.microsoft.com%2F)

Xcode是Apple提供的多语言IDE，仅在支持现代C++的macOS上可用。Xcode是专有软件，但可从Apple免费获得。Xcode具有集成的调试器，支持Git等版本控制系统，具有Clang编译器，并使用libc++作为其标准库。支持标准功能包括语法高亮显示，代码自动完成和重构。此外，Xcode支持诸如CMake的外部构建系统，并利用LLDB调试器。

[https://developer.apple.com/xcode/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fdeveloper.apple.com%2Fxcode%2F)

GDB是基于便携式命令行的调试平台，支持现代C++，可在开放源代码许可（GPL）下使用。许多编辑器和IDE（例如Visual Studio，Qt Creator和CLion）都支持与GDB集成。它也可以用于远程调试应用程序，其中GDB在一个设备上运行，而被调试的应用程序在另一设备上运行。它支持许多平台，包括Windows，macOS和Linux。

[https://www.gnu.org/software/gdb/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fwww.gnu.org%2Fsoftware%2Fgdb%2F)

LLDB 是一个开源调试接口，支持现代C++并与Clang编译器集成。它具有许多可选的性能增强功能，例如JIT，但还支持调试内存，多个线程和机器代码分析。它是用C++构建的。LLDB是Xcode的默认调试器，可与Visual Studio Code，CLion和Qt Creator一起使用。它支持许多平台，包括Windows，macOS和Linux。

[https://lldb.llvm.org/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Flldb.llvm.org%2F)

Catch2是用于现代C ++的跨平台开源（BSL-1.0）测试框架。Catch2 非常轻巧，因为仅需要包含头文件。单元测试可以标记并成组运行。它支持测试驱动的开发和行为驱动的开发。Catch2还可以轻松与CLion集成。

[https://github.com/catchorg/Catch2](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgithub.com%2Fcatchorg%2FCatch2)

Boost.Test 是使用现代C++标准的功能丰富的开源（BSL-1.0）测试框架。它可用于通过可自定义的日志记录和实时监视来快速检测错误，故障和超时。可以将测试分组到套件中，并且该框架支持小规模测试和大规模测试。

[https://github.com/boostorg/test](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgithub.com%2Fboostorg%2Ftest)

Google Test 是 Google 的C ++测试和模拟框架，可以通过开源（BSD）许可获得。Google测试可以在多种平台上使用，包括Linux，macOS，Windows等。它包含一个单元测试框架、断言、死锁测试、检测故障、处理参数化测试以及创建XML测试报告。

[https://github.com/google/googletest](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgithub.com%2Fgoogle%2Fgoogletest)

CUTE 是集成在Cevelop中的单元测试框架，但也可以独立使用。它涵盖从C++98到 C++2a 的C++版本，并且通过头文件即可使用。虽然不如Google Test流行，但它的宏纠结较少，并且仅在没有适当的C++功能可用的情况下使用宏。另外，通过回避某些I/O格式化功能，它具有一种可轻松在嵌入式平台上运行的模式。

[https://cute-test.com/](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fcute-test.com%2F)

- AddressSanitizer- 

- UndefinedBehaviorSanitizer- 

- LeakSanitizer- 

Clang Sanitizers 是为您的应用程序添加额外工具的工具（例如，它们替换了new/malloc/delete调用），并且可以检测各种运行时错误：内存泄漏、指针删除后使用、双重释放等。为了改善您的构建流程，许多指南都建议在进行测试时增加消毒步骤。

我希望上面的清单对C++开发必不可少的工具有一个整体的概述。

如果您想了解有关其他生态系统要素的更多信息：库，框架和其他工具，请参阅Embarcadero的完整报告：

[C ++生态系统白皮书](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Flp.embarcadero.com%2FCEcosystem%3Futm_source%3DBartek_GuideCppEcosystem%26utm_medium%3DAcqEmail%26utm_content%3DWhitepaper-190812-ModernCppEcosystem)（这是一个非常漂亮的pdf，内容超过20页！）

您可以在此参考资料中找到增强C++开发的工具，库和框架的超长列表：[https://github.com/fffaraz/awesome-cpp](https://www.oschina.net/action/GoToLink?url=https%3A%2F%2Fgithub.com%2Ffffaraz%2Fawesome-cpp)