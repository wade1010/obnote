option(WITH_BLUEFS "libbluefs library" OFF) 语法解析下

```
"option" 是 CMake 命令，用于定义一个选项。其语法为：

option(<option_variable> "help string" [initial_value])
其中，"" 是要定义的选项变量名，"help string" 是选项的描述信息，"[initial_value]" 是选项的默认值，可以省略。

在这段代码中，"option(WITH_BLUEFS "libbluefs library" OFF)" 定义了一个名为 "WITH_BLUEFS" 的选项，用于控制是否启用 libbluefs 库，其默认值为 OFF。

通常情况下，可以通过命令行参数 "-D" 来修改选项的值，例如：

cmake -DWITH_BLUEFS=ON ..
这样就可以将 "WITH_BLUEFS" 的值设置为 ON。

通过定义选项，可以使得 CMake 构建过程更加灵活和可配置，用户可以根据自己的需求启用或禁用特定的功能或库。
```

```
find_package(<package_name> [version] [EXACT] [QUIET] [MODULE]
             [REQUIRED] [[COMPONENTS] [components...]]
             [OPTIONAL_COMPONENTS components...]
             [NO_POLICY_SCOPE])
，"
" 是要查找的包名，"[version]" 是指定要查找的包的版本号，"[EXACT]" 表示是否精确匹配版本号，"[QUIET]" 表示不输出任何消息，"[REQUIRED]" 表示如果找不到指定的包则会产生错误并停止构建过程，"[MODULE]" 表示查找 CMake 模块文件，"[COMPONENTS]" 表示要查找的组件，"[OPTIONAL_COMPONENTS]" 表示可选的组件，"[NO_POLICY_SCOPE]" 表示不应用当前策略。

```

```
cmake_minimum_required(VERSION 3.16)//指定了最小的 CMake 版本要求为 3.16。

project(ceph
  VERSION 17.2.5
  LANGUAGES CXX C ASM)//指定了项目名称为 ceph，版本号为 17.2.5，同时支持 C++、C 和汇编语言。

//这些是 CMake 的一些策略设置，用于控制 CMake 在构建项目时的行为。每个策略都有一个标识符（如 CMP0028）和一个值（如 NEW），用于指定 CMake 在遇到某些情况时应该采取的行为。
是使用 CMake 的项目的一个常用技巧，目的是在 CMake 运行时设置一些策略（policy），以便兼容不同版本的 CMake。
/*
是设置 CMake 的一些策略，用于控制 CMake 行为的一些规则。具体含义如下：
CMP0028：设置 INSTALL_*DIR 变量时，不再强制使用大写字母，允许小写字母。
CMP0046：在 PROJECT 命令中使用版本号时，会生成一个 VERSION 变量，此策略表示允许在 VERSION 变量中使用 
PROJECT_VERSION_MAJOR、PROJECT_VERSION_MINOR 和 PROJECT_VERSION_PATCH。
CMP0048：在 PROJECT 命令中使用版本号时，会生成一个 VERSION 变量，此策略表示允许在 VERSION 变量中使用 PROJECT_VERSION_<LANG> 以指定不同编程语言的版本号。
CMP0051：不再强制使用 CMAKE_SHARED_LIBRARY_PREFIX 和 CMAKE_STATIC_LIBRARY_PREFIX 变量，允许使用任何前缀。
CMP0054：在 IF 命令中使用 AND 和 OR 运算符时，必须使用 () 显式括起来。
CMP0056：在 INSTALL 命令中，使用 CONFIGURATIONS 选项时，不再将所有配置都安装到同一目录，而是将其安装到应配置的目录中。
CMP0065：在 INSTALL 命令中，安装到 $<INSTALL_PREFIX> 路径时，不再默认安装到 /usr/local，而是安装到 CMake 配置文件指定的路径。
CMP0074：在 find_package 命令中使用 CONFIG 参数时，不再自动加上 .cmake 后缀。
CMP0075：在 if() 和 elseif() 命令中使用 $<BOOL: 和 $<NOT: 等表达式时，不再自动将表达式转换为布尔值。
CMP0093：在 find_package 命令中使用 REQUIRED 参数时，如果找不到包，则默认抛出错误。
CMP0127：在 find_package 命令中使用 NO_POLICY_SCOPE 参数时，表示不应该应用任何当前策略。

这些策略的设置可以避免一些不必要的警告和错误，同时也可以保证代码的兼容性。
*/
cmake_policy(SET CMP0028 NEW)
cmake_policy(SET CMP0046 NEW)
cmake_policy(SET CMP0048 NEW)
cmake_policy(SET CMP0051 NEW)
cmake_policy(SET CMP0054 NEW)
cmake_policy(SET CMP0056 NEW)
cmake_policy(SET CMP0065 NEW)
cmake_policy(SET CMP0074 NEW)
cmake_policy(SET CMP0075 NEW)
cmake_policy(SET CMP0093 NEW)
if(POLICY CMP0127)
  cmake_policy(SET CMP0127 NEW)
endif()
/*
是将一个路径添加到 CMake 的模块搜索路径中。具体来说：
CMAKE_MODULE_PATH 是一个 CMake 变量，用于指定 CMake 在哪些路径中搜索模块文件。
list(APPEND ...) 是 CMake 的命令，用于向列表变量中添加元素。在这里，它将 
${CMAKE_SOURCE_DIR}/cmake/modules/ 添加到 
CMAKE_MODULE_PATH 变量中。
因此，这段代码的作用是将 ${CMAKE_SOURCE_DIR}/cmake/modules/ 路径添加到 CMake 的模块搜索路径中，以便让 CMake 可以在该路径下查找所需要的模块文件。这样做可以方便地为 CMake 提供自定义的模块，从而增强 CMake 的功能。
*/
list(APPEND CMAKE_MODULE_PATH "${CMAKE_SOURCE_DIR}/cmake/modules/")

/*
设置 CMake 的默认构建类型。

在 CMake 中，构建类型是指编译器生成的代码的优化级别和调试信息的包含程度。常见的构建类型有 Debug、Release、RelWithDebInfo、MinSizeRel 等。在使用 CMake 构建项目时，可以通过设置 CMAKE_BUILD_TYPE 变量来指定构建类型。

这段代码的作用是，如果当前没有指定构建类型且项目目录下存在 .git 文件夹（假设这是一个 Git 仓库），则将默认构建类型设置为 Debug，并强制将 CMAKE_BUILD_TYPE 变量设置为该值。

这样做的目的是为了避免忘记指定构建类型而导致编译结果不符合预期。如果没有指定构建类型，则默认使用 Debug，这样可以在开发阶段方便地进行调试和测试。当然，如果需要生成更高效的代码，可以手动设置构建类型为 Release 或其他选项。
*/
if(NOT CMAKE_BUILD_TYPE AND EXISTS "${CMAKE_SOURCE_DIR}/.git")
  set(default_build_type "Debug")
  set(CMAKE_BUILD_TYPE "${default_build_type}" CACHE
      STRING "Default BUILD_TYPE is Debug, other options are: RelWithDebInfo, Release, and MinSizeRel." FORCE)
endif()
/*
是在 Linux 和 FreeBSD 上查找并链接线程库。

具体来说：

if(CMAKE_SYSTEM_NAME MATCHES "Linux") 判断当前系统是否为 Linux，如果是，则将 LINUX 变量设置为 ON。
elseif(CMAKE_SYSTEM_NAME MATCHES "FreeBSD") 判断当前系统是否为 FreeBSD，如果是，则将 FREEBSD 变量设置为 ON。
FIND_PACKAGE(Threads) 查找并链接线程库。线程库是一种用于支持多线程编程的库，通常在 Linux 和 FreeBSD 系统上使用。
这段代码的作用是针对 Linux 和 FreeBSD 系统分别查找并链接线程库。如果是 Linux 系统，则将 LINUX 变量设置为 ON，如果是 FreeBSD 系统，则将 FREEBSD 变量设置为 ON。在 CMakeLists.txt 文件中可以使用这些变量来控制编译选项，以适配不同的操作系统。

注意，这段代码只是针对 Linux 和 FreeBSD 系统，如果在其他系统上编译该代码，则会忽略这段代码，不会进行线程库的查找和链接。

*/
if(CMAKE_SYSTEM_NAME MATCHES "Linux")
  set(LINUX ON)
  FIND_PACKAGE(Threads)
elseif(CMAKE_SYSTEM_NAME MATCHES "FreeBSD")
  set(FREEBSD ON)
  FIND_PACKAGE(Threads)
endif(CMAKE_SYSTEM_NAME MATCHES "Linux")
/*
是为 Windows 平台设置编译选项。

具体来说：

if(WIN32) 判断当前系统是否为 Windows，如果是，则进行以下操作。
set(WIN32_WINNT "0x0A00" CACHE STRING "Targeted Windows version.") 设置目标 Windows 版本为 Windows 10，将 WIN32_WINNT 变量设置为 0x0A00。
add_definitions(...) 添加编译选项。这里添加了三个宏定义：
-D_WIN32_WINNT=${WIN32_WINNT}：指定目标 Windows 版本。
-DBOOST_THREAD_PROVIDES_GENERIC_SHARED_MUTEX_ON_WIN：使用 Boost 的共享互斥量实现替代 Windows 平台的互斥量实现，以避免已知的 winpthread 问题。
-DBOOST_THREAD_V2_SHARED_MUTEX：指定使用 Boost 的共享互斥量实现的版本。
set(Boost_THREADAPI "win32") 指定 Boost 库使用 Windows API 来实现线程。
这段代码的作用是在 Windows 平台上设置编译选项，以适配当前平台的特性和问题。其中，设置目标 Windows 版本可以确保 Windows API 的可用性和兼容性；使用 Boost 的共享互斥量实现可以避免已知的线程库问题；指定 Boost 库使用 Windows API 来实现线程可以确保与 Windows 平台的线程模型兼容。

WIN32 是 CMake 中的一个特殊变量，它用于判断当前是否在 Windows 平台上编译代码，而不是指特定的操作系统版本。因此，只要是在 Windows 操作系统上编译代码，无论是 32 位还是 64 位，都会被识别为 WIN32。

需要注意的是，WIN32 并不是一个准确的判断标准，因为在某些情况下，即使在非 Windows 平台上，也可能被判断为 WIN32。如果需要更加准确的平台判断，可以使用 CMAKE_SYSTEM_NAME 变量，它可以获取当前操作系统的名称并进行判断，例如 if(CMAKE_SYSTEM_NAME MATCHES "Windows") 可以判断当前是否在 Windows 平台上。
*/
if(WIN32)
  # The Windows headers (e.g. coming from mingw or the Windows SDK) check
  # the targeted Windows version. The availability of certain functions and
  # structures will depend on it.
  set(WIN32_WINNT "0x0A00" CACHE STRING "Targeted Windows version.")
  # In order to avoid known winpthread issues, we're using the boost
  # shared mutex implementation.
  # https://github.com/msys2/MINGW-packages/issues/3319
  add_definitions(
    -D_WIN32_WINNT=${WIN32_WINNT}
    -DBOOST_THREAD_PROVIDES_GENERIC_SHARED_MUTEX_ON_WIN
    -DBOOST_THREAD_V2_SHARED_MUTEX
  )
  set(Boost_THREADAPI "win32")
endif()
/*
为 MinGW 编译器设置编译选项和链接选项。

具体来说：

if(MINGW) 判断当前是否使用 MinGW 编译器，如果是，则进行以下操作。
string(APPEND CMAKE_SHARED_LINKER_FLAGS " -Wl,-allow-multiple-definition") 将 -Wl,-allow-multiple-definition 选项添加到共享库链接器选项中，以允许多个定义的符号。
string(APPEND CMAKE_EXE_LINKER_FLAGS " -Wl,-allow-multiple-definition") 将 -Wl,-allow-multiple-definition 选项添加到可执行文件链接器选项中，以允许多个定义的符号。
set(CMAKE_C_LINK_EXECUTABLE ...) 和 set(CMAKE_CXX_LINK_EXECUTABLE ...) 自定义了 C 和 C++ 编译器的链接规则，以禁用生成可执行文件的导入库。这是因为在某些情况下，可执行文件的导入库会覆盖库文件的导入库，导致链接错误。
link_directories(${MINGW_LINK_DIRECTORIES}) 将 MinGW 链接器的库目录添加到链接器搜索路径中。
*/
if(MINGW)
  string(APPEND CMAKE_SHARED_LINKER_FLAGS " -Wl,-allow-multiple-definition")
  string(APPEND CMAKE_EXE_LINKER_FLAGS " -Wl,-allow-multiple-definition")

  # By default, cmake generates import libs for executables. The issue is that
  # for rados and rbd, the executable import lib overrides the library import lib.
  # For example, for rados.exe, it will end up generating a librados.dll.a import lib.
  # We're providing custom rules to disable import libs for executables.
  set(CMAKE_C_LINK_EXECUTABLE
    "<CMAKE_C_COMPILER> <FLAGS> <CMAKE_C_LINK_FLAGS> <LINK_FLAGS> <OBJECTS> -o <TARGET> ${CMAKE_GNULD_IMAGE_VERSION} <LINK_LIBRARIES>")
  set(CMAKE_CXX_LINK_EXECUTABLE
    "<CMAKE_CXX_COMPILER> <FLAGS> <CMAKE_CXX_LINK_FLAGS> <LINK_FLAGS> <OBJECTS> -o <TARGET> ${CMAKE_GNULD_IMAGE_VERSION} <LINK_LIBRARIES>")

  link_directories(${MINGW_LINK_DIRECTORIES})
endif()
/*
是为编译器设置 ccache 编译加速。这样可以显著减少编译和链接时间，提高编译速度。
具体来说：
option(WITH_CCACHE "Build with ccache.") 定义了一个选项变量 WITH_CCACHE，表示是否启用 ccache 编译加速。
if(WITH_CCACHE) 判断是否启用 ccache 编译加速，如果是，则进行以下操作。
find_program(CCACHE_EXECUTABLE ccache) 查找 ccache 可执行文件。
if(NOT CCACHE_EXECUTABLE) 判断是否找到 ccache 可执行文件，如果没有，则输出错误信息并终止编译。
message(STATUS "Building with ccache: ${CCACHE_EXECUTABLE}, CCACHE_DIR=$ENV{CCACHE_DIR}") 输出启用 ccache 编译加速的信息，包括 ccache 可执行文件的路径和 ccache 缓存目录。
set_property(GLOBAL PROPERTY RULE_LAUNCH_COMPILE "${CCACHE_EXECUTABLE}") 设置编译命令的前缀为 ccache 可执行文件，以启用 ccache 编译加速。
set_property(GLOBAL PROPERTY RULE_LAUNCH_LINK "${CCACHE_EXECUTABLE}") 设置链接命令的前缀为 ccache 可执行文件，以启用 ccache 链接加速。
*/
option(WITH_CCACHE "Build with ccache.")
if(WITH_CCACHE)
  find_program(CCACHE_EXECUTABLE ccache)
  if(NOT CCACHE_EXECUTABLE)
    message(FATAL_ERROR "Can't find ccache. Is it installed?")
  endif()
  message(STATUS "Building with ccache: ${CCACHE_EXECUTABLE}, CCACHE_DIR=$ENV{CCACHE_DIR}")
  set_property(GLOBAL PROPERTY RULE_LAUNCH_COMPILE "${CCACHE_EXECUTABLE}")
  # ccache does not accelerate link (ld), but let it handle it. by passing it
  # along with cc to python's distutils, we are able to workaround
  # https://bugs.python.org/issue8027.
  set_property(GLOBAL PROPERTY RULE_LAUNCH_LINK "${CCACHE_EXECUTABLE}")
endif(WITH_CCACHE)
/*
判断是否需要构建 man 手册页，并查找 sphinx-build 工具。
具体来说：
option(WITH_MANPAGE "Build man pages." ON) 定义了一个选项变量 WITH_MANPAGE，表示是否需要构建 man 手册页。
if(WITH_MANPAGE) 判断是否需要构建 man 手册页，如果是，则进行以下操作。
find_program(SPHINX_BUILD NAMES sphinx-build sphinx-build-3) 查找 sphinx-build 工具，可以使用 sphinx-build 或 sphinx-build-3 命令来执行。
if(NOT SPHINX_BUILD) 判断是否找到 sphinx-build 工具，如果没有，则输出错误信息并终止编译。

man 手册页是 Linux/Unix 操作系统中文档的一种格式，用于提供详细的帮助文档和使用说明。man 是 manual 的缩写，表示手册页。man 手册页是文本格式的，可以通过命令行工具 man 来查看。

在 Linux/Unix 操作系统中，几乎所有的命令和工具都有相应的 man 手册页，可以通过 man 命令来查看它们的详细使用说明和参数介绍。例如，要查看 ls 命令的 man 手册页，可以在命令行中输入 man ls，就可以查看到详细的使用说明和参数介绍。

sphinx-build 通常用于生成项目的文档，例如生成 API 文档、用户手册等。
*/
option(WITH_MANPAGE "Build man pages." ON)
if(WITH_MANPAGE)
  find_program(SPHINX_BUILD
    NAMES sphinx-build sphinx-build-3)
  if(NOT SPHINX_BUILD)
    message(FATAL_ERROR "Can't find sphinx-build.")
  endif(NOT SPHINX_BUILD)
endif(WITH_MANPAGE)
/*
将指定的目录添加到头文件搜索路径中。
具体来说：
include_directories(...) 函数用于将指定的目录添加到头文件搜索路径中。
${PROJECT_BINARY_DIR}/src/include 表示将构建目录下的 src/include 目录添加到头文件搜索路径中，这个目录通常用于存放生成的头文件。
${PROJECT_SOURCE_DIR}/src 表示将源代码目录下的 src 目录添加到头文件搜索路径中，这个目录通常用于存放项目的头文件。
*/
include_directories(
  ${PROJECT_BINARY_DIR}/src/include
  ${PROJECT_SOURCE_DIR}/src)
/*
为 Windows 平台添加特定的头文件和编译选项。

具体来说：

if(WIN32) 判断当前是否在 Windows 平台上，如果是，则进行以下操作。
include_directories(${PROJECT_SOURCE_DIR}/src/include/win32) 将源代码目录下的 src/include/win32 目录添加到头文件搜索路径中，以便包含 Windows 平台特定的头文件。
add_compile_options("SHELL:-include winsock_wrapper.h") 添加编译选项，将 winsock_wrapper.h 头文件包含在所有编译单元中。这是因为在 Windows 平台上，winsock2.h 头文件和 Boost 库的 asio.hpp 头文件存在冲突，需要通过 winsock_wrapper.h 头文件来解决。
add_compile_options("SHELL:-include win32_errno.h") 添加编译选项，将 win32_errno.h 头文件包含在所有编译单元中。这是因为在 Windows 平台上，Boost 库定义了一些与 errno 相关的宏，与 Windows 平台的 errno 值不兼容，需要通过 win32_errno.h 头文件来避免冲突。
*/
if(WIN32)
  include_directories(
    ${PROJECT_SOURCE_DIR}/src/include/win32)
  # Boost complains if winsock2.h (or windows.h) is included before asio.hpp.
  add_compile_options("SHELL:-include winsock_wrapper.h")
  # Boost is also defining some of the errno values, we'll have
  # to avoid mismatches.
  add_compile_options("SHELL:-include win32_errno.h")
endif()
/*
为 FreeBSD 操作系统添加特定的头文件搜索路径和库文件搜索路径。

具体来说：

if(FREEBSD) 判断当前是否在 FreeBSD 操作系统上，如果是，则进行以下操作。
include_directories(SYSTEM /usr/local/include) 将 /usr/local/include 目录添加到头文件搜索路径中，用于包含 FreeBSD 操作系统特定的头文件。SYSTEM 参数表示将该目录标记为系统目录，以避免编译器警告。
link_directories(/usr/local/lib) 将 /usr/local/lib 目录添加到库文件搜索路径中，用于链接 FreeBSD 操作系统特定的库文件。
list(APPEND CMAKE_REQUIRED_INCLUDES /usr/local/include) 将 /usr/local/include 目录添加到 CMake 的必需头文件路径列表中，以确保 CMake 可以正确地查找和使用 FreeBSD 操作系统特定的头文件。
*/
if(FREEBSD)
  include_directories(SYSTEM /usr/local/include)
  link_directories(/usr/local/lib)
  list(APPEND CMAKE_REQUIRED_INCLUDES /usr/local/include)
endif(FREEBSD)

/*
set(CMAKE_ARCHIVE_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/lib) 将生成的静态库文件（.a 文件）放置在 ${CMAKE_BINARY_DIR}/lib 目录下。
set(CMAKE_LIBRARY_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/lib) 将生成的共享库文件（.so 或 .dll 文件）放置在 ${CMAKE_BINARY_DIR}/lib 目录下。
set(CMAKE_RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/bin) 将生成的可执行文件放置在 ${CMAKE_BINARY_DIR}/bin 目录下。
*/
#put all the libs and binaries in one place
set(CMAKE_ARCHIVE_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/lib)
set(CMAKE_LIBRARY_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/lib)
set(CMAKE_RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/bin)
/*
include(GNUInstallDirs) 包含 GNUInstallDirs 模块，该模块定义了一些变量和函数，可以方便地获取系统中的标准目录路径，例如 CMAKE_INSTALL_PREFIX、CMAKE_INSTALL_LIBDIR、CMAKE_INSTALL_BINDIR 等。
include(CephChecks) 包含 CephChecks 模块，该模块定义了一些函数和宏，用于检查系统中是否存在所需的库、头文件、编译器等，以便在编译项目时进行检查。
if(CMAKE_GENERATOR MATCHES Ninja) include(LimitJobs) endif() 如果使用的是 Ninja 生成器，则包含 LimitJobs 模块，该模块定义了一些函数和宏，用于限制并发任务的数量，以避免过度使用系统资源。
*/
include(GNUInstallDirs)
include(CephChecks)
if(CMAKE_GENERATOR MATCHES Ninja)
  include(LimitJobs)
endif()
//设置 Ceph 项目的 man 页面安装路径，默认为 ${CMAKE_INSTALL_PREFIX}/share/man。
set(CEPH_MAN_DIR "share/man" CACHE STRING "Install location for man pages (relative to prefix).")
//设置一个选项，表示是否构建共享库，默认为 ON。如果将选项设置为 OFF，则会构建静态库。
option(ENABLE_SHARED "build shared libraries" ON)
if(ENABLE_SHARED)
  set(CEPH_SHARED SHARED)
else(ENABLE_SHARED)
  set(CEPH_SHARED STATIC)
endif(ENABLE_SHARED)
/*
根据 ENABLE_SHARED 选项的值来设置编译选项 -fPIC。如果 ENABLE_SHARED 选项为 ON，则将 -fPIC 选项添加到编译器选项中，以便生成位置无关的代码。

-fPIC 是 GCC 和 Clang 编译器的选项之一，用于生成位置无关的代码（Position-Independent Code）。位置无关的代码是一种不依赖于内存地址的机器代码，它可以在内存的任何位置加载并运行，适用于共享库等动态链接的场合。

在 Linux 等操作系统中，动态链接库通常被装载到进程地址空间的任意位置，因此需要使用位置无关的代码来确保共享库能够正确运行。使用 -fPIC 选项编译的代码可以在任意地址空间中运行，因此在编译共享库时需要加上该选项。

需要注意的是，生成位置无关代码会导致代码大小增加，运行速度降低。因此，在编译静态库等不需要动态链接的场合，不需要使用 -fPIC 选项。
*/
set(CMAKE_POSITION_INDEPENDENT_CODE ${ENABLE_SHARED})
//这一行定义了一个名为 "WITH_STATIC_LIBSTDCXX" 的选项，用于控制是否链接静态的 libstdc++ 库。OFF 表示默认不链接。
option(WITH_STATIC_LIBSTDCXX "Link against libstdc++ statically" OFF)
if(WITH_STATIC_LIBSTDCXX)
//检查是否使用的是 GCC 编译器，如果不是，就会输出一个致命错误并退出。
  if(NOT CMAKE_COMPILER_IS_GNUCXX)
    message(FATAL_ERROR "Please use GCC to enable WITH_STATIC_LIBSTDCXX")
  endif()
  //定义一个名为 "static_linker_flags" 的变量，设置为链接静态的 libstdc++ 和 libgcc 库的选项
  set(static_linker_flags "-static-libstdc++ -static-libgcc")
  //将这个变量附加到 CMAKE_SHARED_LINKER_FLAGS 和 CMAKE_EXE_LINKER_FLAGS 变量中，以便在链接共享库和可执行文件时使用这些选项。最后，会将 "static_linker_flags" 变量清空，并将 GPERFTOOLS_USE_STATIC_LIBS 设置为 TRUE。
  string(APPEND CMAKE_SHARED_LINKER_FLAGS " ${static_linker_flags}")
  string(APPEND CMAKE_EXE_LINKER_FLAGS " ${static_linker_flags}")
  unset(static_linker_flags)
  set(GPERFTOOLS_USE_STATIC_LIBS TRUE)
endif()

//检查是否支持 C++11 原子操作。如果不支持，就会将 LIBATOMIC_LINK_FLAGS 添加到 CMAKE_CXX_STANDARD_LIBRARIES 变量中，以确保在链接 C++ 标准库时使用这些选项。
include(CheckCxxAtomic)
if(NOT HAVE_CXX11_ATOMIC)
  string(APPEND CMAKE_CXX_STANDARD_LIBRARIES
    " ${LIBATOMIC_LINK_FLAGS}")
endif()

//检查是否启用了 RDMA，并查找需要的库。
option(WITH_RDMA "Enable RDMA in async messenger" ON)
if(WITH_RDMA)
//使用 find_package 命令查找 "verbs" 和 "rdmacm" 库，并将它们的状态存储在 "HAVE_VERBS" 和 "HAVE_RDMACM" 变量中。同时，将 "HAVE_RDMA" 设置为 TRUE。
  find_package(verbs REQUIRED)
  set(HAVE_VERBS ${VERBS_FOUND})
  find_package(rdmacm REQUIRED)
  set(HAVE_RDMACM ${RDMACM_FOUND})
  set(HAVE_RDMA TRUE)
endif()
//使用 find_package 命令，CMake 将搜索名为 "Backtrace" 的库，并尝试查找其头文件和库文件。
find_package(Backtrace)
//控制是否启用与 RADOS 块设备相关的目标
option(WITH_RBD "Enable RADOS Block Device related targets" ON)

//判断当前操作系统是否为 Linux。如果是 Linux，就使用 find_package 命令查找 "udev"、"blkid" 和 "keyutils" 库，并将它们的状态存储在对应的变量中。同时，将 "HAVE_UDEV"、"HAVE_BLKID" 和 "HAVE_KEYUTILS" 设置为对应变量的值，以便在代码中使用这些库。
如果当前操作系统不是 Linux，则会分别将 "HAVE_UDEV"、"HAVE_BLKID" 和 "HAVE_KEYUTILS" 设置为 OFF，表示不需要这些库。
if(LINUX)
  find_package(udev REQUIRED)
  set(HAVE_UDEV ${UDEV_FOUND})
  find_package(blkid REQUIRED)
  set(HAVE_BLKID ${BLKID_FOUND})
  find_package(keyutils REQUIRED)
  set(HAVE_KEYUTILS ${KEYUTILS_FOUND})
elseif(FREEBSD)
  set(HAVE_UDEV OFF)
  set(HAVE_LIBAIO OFF)
  set(HAVE_BLKID OFF)
  set(HAVE_KEYUTILS OFF)
else()
  set(HAVE_UDEV OFF)
  set(HAVE_BLKID OFF)
endif(LINUX)

/*
检查是否启用了 OpenLDAP，并查找需要的库。
首先，有一个名为 "WITH_OPENLDAP" 的选项，如果设置为 ON，则表示需要启用 OpenLDAP。
如果开启了这个选项，就会使用 find_package 命令查找 "OpenLDAP" 库，并将它们的状态存储在 "OpenLDAP_FOUND" 变量中。同时，将 "HAVE_OPENLDAP" 设置为 "OpenLDAP_FOUND" 的值。
这样，如果代码需要使用 OpenLDAP 库，就可以通过检查 "HAVE_OPENLDAP" 变量的值来确定是否已经找到了该库。如果已经找到了，就可以将其链接到代码中，以便使用库的功能。
*/
option(WITH_OPENLDAP "OPENLDAP is here" ON)
if(WITH_OPENLDAP)
  find_package(OpenLDAP REQUIRED)
  set(HAVE_OPENLDAP ${OpenLDAP_FOUND})
endif()
/*
使用 find_package 命令查找 "GSSApi" 库，并将它们的状态存储在 "GSSApi_FOUND" 变量中。同时，将 "HAVE_GSSAPI" 设置为 "GSSApi_FOUND" 的值。
这样，如果代码需要使用 GSSAPI/KRB5 库，就可以通过检查 "HAVE_GSSAPI" 变量的值来确定是否已经找到了该库。如果已经找到了，就可以将其链接到代码中，以便使用库的功能。
注意，此处的选项为 OFF，因此默认情况下不会启用 GSSAPI/KRB5。
*/
option(WITH_GSSAPI "GSSAPI/KRB5 is here" OFF)
if(WITH_GSSAPI)
  find_package(GSSApi REQUIRED)
  set(HAVE_GSSAPI ${GSSApi_FOUND})
endif()
//同上
option(WITH_FUSE "Fuse is here" ON)
if(WITH_FUSE)
  find_package(FUSE)
  set(HAVE_LIBFUSE ${FUSE_FOUND})
endif()
//同上
option(WITH_DOKAN "Dokan is here" OFF)
//同上
option(WITH_XFS "XFS is here" ON)
if(WITH_XFS)
  find_package(xfs)
  set(HAVE_LIBXFS ${XFS_FOUND})
endif()
//同上
option(WITH_ZFS "enable LibZFS if found" OFF)
if(WITH_ZFS)
  find_package(zfs)
  set(HAVE_LIBZFS ${ZFS_FOUND})
endif()
//同上
option(WITH_BLUESTORE "Bluestore OSD backend" ON)
if(WITH_BLUESTORE)
  if(LINUX)
    find_package(aio)
    set(HAVE_LIBAIO ${AIO_FOUND})
  elseif(FREEBSD)
    # POSIX AIO is integrated into FreeBSD kernel, and exposed by libc.
    set(HAVE_POSIXAIO ON)
  endif()
endif()

# libcryptsetup is only available on linux
if(WITH_RBD AND LINUX)
  /*
  "libcryptsetup" 是一个开源软件包，主要用于对 Linux 系统上的磁盘设备进行加密和解密。它提供了一组 API，可以用于创建、打开、关闭和管理加密卷，以及对这些卷进行加密和解密操作。
使用 "libcryptsetup" 可以很方便地实现对硬盘、USB 等存储设备的加密，以保护敏感数据的安全性。它支持多种加密算法，如 AES、Serpent、Twofish 等，同时还支持多种密码模式，如 CBC、XTS 等。
除了 API 接口，"libcryptsetup" 还提供了一些命令行工具，如 "cryptsetup"，可以用于创建、打开和关闭加密卷，以及对其进行加密和解密操作。这些工具可以通过终端或脚本来使用，以实现对加密卷的管理和操作。
  */
  find_package(libcryptsetup 2.0.5 REQUIRED)
  set(HAVE_LIBCRYPTSETUP ${LIBCRYPTSETUP_FOUND})
endif()
/*
于包含一个名为 "CMakeDependentOption" 的模块。

"CMakeDependentOption" 是一个 CMake 模块，它提供了一个选项，可以基于其他选项的值来决定是否启用当前选项。其语法为：

CMAKE_DEPENDENT_OPTION(OPTION_VAR
                       "option help string"
                       ON/OFF
                       DEPENDENCY_VAR
                       "dependency value"
                       [OPTIONAL])
其中，"OPTION_VAR" 是要定义的选项变量名，"option help string" 是选项的描述信息，"ON/OFF" 表示选项默认值，"DEPENDENCY_VAR" 是依赖选项的变量名，"dependency value" 是依赖选项的值，"OPTIONAL" 表示当前选项是否为可选的。
通过包含 "CMakeDependentOption" 模块，就可以在 CMake 中使用 "cmake_dependent_option" 命令来定义基于其他选项的条件选项。

创建一个选项，当另一个选项开启时，才会启用某些特定的功能或库。这样可以使得代码更加灵活和可配置。
*/
include(CMakeDependentOption)

CMAKE_DEPENDENT_OPTION(WITH_ZBD "Enable libzbd bluestore backend" OFF
  "WITH_BLUESTORE" OFF)
if(WITH_ZBD)
  find_package(zbd REQUIRED)
  set(HAVE_LIBZBD ${ZBD_FOUND})
endif()

CMAKE_DEPENDENT_OPTION(WITH_LIBURING "Enable io_uring bluestore backend" ON
  "WITH_BLUESTORE;HAVE_LIBAIO" OFF)
set(HAVE_LIBURING ${WITH_LIBURING})

CMAKE_DEPENDENT_OPTION(WITH_SYSTEM_LIBURING "Require and build with system liburing" OFF
  "HAVE_LIBAIO;WITH_BLUESTORE" OFF)

CMAKE_DEPENDENT_OPTION(WITH_BLUESTORE_PMEM "Enable PMDK libraries" OFF
  "WITH_BLUESTORE" OFF)

CMAKE_DEPENDENT_OPTION(WITH_RBD_MIGRATION_FORMAT_QCOW_V1
  "Enable librbd QCOW v1 migration format support" ON
  "WITH_RBD" OFF)

CMAKE_DEPENDENT_OPTION(WITH_RBD_RWL "Enable librbd persistent write back cache" OFF
  "WITH_RBD" OFF)

CMAKE_DEPENDENT_OPTION(WITH_RBD_SSD_CACHE "Enable librbd persistent write back cache for SSDs" OFF
    "WITH_RBD" OFF)

CMAKE_DEPENDENT_OPTION(WITH_SYSTEM_PMDK "Require and build with system PMDK" OFF
  "WITH_RBD_RWL OR WITH_BLUESTORE_PMEM" OFF)
/*启用 BlueStore 存储后端的 PMEM（Persistent Memory）支持
BlueStore 是 Ceph 存储集群的一种存储后端，它提供了更好的性能和可靠性，更适合大规模存储集群。而 PMEM 是一种新型的存储介质，它具有传统内存的高速度和易失性，同时又具备传统磁盘的持久性和容量。
启用 "WITH_BLUESTORE_PMEM" 选项后，就可以将 PMEM 作为 BlueStore 的存储介质，从而获得更高的性能和可靠性。同时，由于 PMEM 具有持久性，可以避免因断电等原因导致的数据丢失问题。
需要注意的是，启用 "WITH_BLUESTORE_PMEM" 选项需要系统支持 PMEM，且需要安装相关的 PMEM 模块和库。
*/
if(WITH_BLUESTORE_PMEM)
  set(HAVE_BLUESTORE_PMEM ON)
endif()
/*
WITH_SPDK" 的条件选项，用于控制是否启用 SPDK。
其中，"CMAKE_DEPENDENT_OPTION" 是 CMake 命令，用于定义基于其他选项的条件选项，已经在之前的问题中进行了解释。
"WITH_SPDK" 是要定义的选项变量名，"Enable SPDK" 是选项的描述信息，"OFF" 是选项默认值。
依赖选项为 "CMAKE_SYSTEM_PROCESSOR MATCHES i386|i686|amd64|x86_64|AMD64|aarch64"，即在 CPU 为 i386、i686、amd64、x86_64、AMD64 或 aarch64 时，才启用 SPDK。
如果依赖选项的值不满足条件，则 "WITH_SPDK" 选项的值将被设置为 OFF。
*/
CMAKE_DEPENDENT_OPTION(WITH_SPDK "Enable SPDK" OFF
  "CMAKE_SYSTEM_PROCESSOR MATCHES i386|i686|amd64|x86_64|AMD64|aarch64" OFF)
/*
检查是否启用了 SPDK，如果启用了，则编译 SPDK 并将其链接到 Ceph 存储系统中。
首先，通过 "if(WITH_SPDK)" 判断是否启用了 SPDK，如果启用了，则执行下面的代码块。
在代码块中，首先通过 "if(NOT WITH_BLUESTORE)" 判断是否启用了 BlueStore，因为 SPDK 只能与 BlueStore 存储后端一起使用。如果没有启用 BlueStore，则通过 "message(SEND_ERROR)" 输出错误信息，提示用户需要启用 BlueStore。
接下来，通过 "include(BuildSPDK)" 包含 "BuildSPDK.cmake" 文件，该文件用于编译 SPDK 并将其链接到 Ceph 存储系统中。
然后，通过 "build_spdk()" 命令编译 SPDK，并将其链接到 Ceph 存储系统中。
最后，将 "HAVE_SPDK" 设置为 TRUE，表示已经编译并链接了 SPDK。
*/
  if(WITH_SPDK)
  if(NOT WITH_BLUESTORE)
    message(SEND_ERROR "Please enable WITH_BLUESTORE for using SPDK")
  endif()
  include(BuildSPDK)
  build_spdk()
  set(HAVE_SPDK TRUE)
endif(WITH_SPDK)

if(WITH_BLUESTORE)
  if(NOT AIO_FOUND AND NOT HAVE_POSIXAIO AND NOT WITH_SPDK AND NOT WITH_BLUESTORE_PMEM)
    message(SEND_ERROR "WITH_BLUESTORE is ON, "
      "but none of the bluestore backends is enabled. "
      "Please install libaio, or enable WITH_SPDK or WITH_BLUESTORE_PMEM (experimental)")
  endif()
endif()
/*
用于定义两个 CMake 选项：WITH_BLUEFS 和 WITH_QAT，并根据 WITH_QAT 的值决定是否查找并链接 QAT（QuickAssist Technology）驱动程序。
首先，通过 "option(WITH_BLUEFS "libbluefs library" OFF)" 定义了一个名为 "WITH_BLUEFS" 的选项，用于控制是否启用 libbluefs 库。其默认值为 OFF。
接下来，通过 "option(WITH_QAT "Enable Qat driver" OFF)" 定义了一个名为 "WITH_QAT" 的选项，用于控制是否启用 QAT 驱动程序。其默认值为 OFF。
然后，通过 "if(WITH_QAT)" 判断是否启用了 QAT 驱动程序。如果启用了，则使用 "find_package(QatDrv REQUIRED COMPONENTS qat_s usdm_drv_s)" 命令查找 QAT 驱动程序，并将其链接到 Ceph 存储系统中。
在查找 QAT 驱动程序时，需要使用 "qat_s" 和 "usdm_drv_s" 两个组件。如果找到了 QAT 驱动程序，则将 "HAVE_QATDRV" 设置为 QatDrv_FOUND 的值，表示已经找到并链接了 QAT 驱动程序。
*/
option(WITH_BLUEFS "libbluefs library" OFF)

option(WITH_QAT "Enable Qat driver" OFF)
if(WITH_QAT)
  find_package(QatDrv REQUIRED COMPONENTS qat_s usdm_drv_s)
  set(HAVE_QATDRV $(QatDrv_FOUND))
endif()

option(WITH_QATZIP "Enable QATZIP" OFF)
if(WITH_QATZIP)
  find_package(qatzip REQUIRED)
  set(HAVE_QATZIP ${qatzip_FOUND})
endif(WITH_QATZIP)

# needs mds and? XXX
option(WITH_LIBCEPHFS "libcephfs client library" ON)

option(WITH_LIBCEPHSQLITE "libcephsqlite client library" ON)
if(WITH_LIBCEPHSQLITE)
  find_package(SQLite3 REQUIRED)
endif()

# key-value store
option(WITH_KVS "Key value store is here" OFF)

option(WITH_KRBD "Enable Linux krbd support of 'rbd' utility" ON)

if(WITH_KRBD AND NOT WITH_RBD)
  message(FATAL_ERROR "Cannot have WITH_KRBD without WITH_RBD.")
endif()
if(LINUX)
  if(WITH_LIBCEPHFS OR WITH_KRBD)
    # keyutils is only used when talking to the Linux Kernel key store 
    find_package(keyutils REQUIRED)
    set(HAVE_KEYUTILS ${KEYUTILS_FOUND})
  endif()
endif()
//Snappy 压缩算法
find_package(snappy REQUIRED)
/*
Brotli 是一种高效的无损压缩算法，它可以将数据压缩到更小的尺寸，从而节省存储空间和网络带宽。Brotli 压缩算法由 Google 开发，其压缩比和压缩速度在很多情况下都表现出色，特别是在压缩文本和 Web 内容时。
*/
option(WITH_BROTLI "Brotli compression support" OFF)
if(WITH_BROTLI)
  set(HAVE_BROTLI TRUE)
endif()
/*
LZ4 是一种无损数据压缩算法，也是一种开源的压缩库。LZ4 库可以压缩数据以减少存储空间，同时也可以在需要时快速解压数据。
在 Ceph 中，LZ4 库被用于对 OSD 存储的数据进行压缩，以减少存储空间、提高磁盘利用率，并且在提高数据存储效率的同时也不会影响数据的读写效率。它在 Ceph 中的使用可以通过设置 osd compression 参数的值来启用或禁用。
*/
option(WITH_LZ4 "LZ4 compression support" ON)
if(WITH_LZ4)
  find_package(LZ4 1.7 REQUIRED)
  set(HAVE_LZ4 ${LZ4_FOUND})
endif(WITH_LZ4)
//控制是否在 Debug 模式下使用调试版本的 ceph::mutex 锁，并启用 lockdep 锁依赖跟踪功能。
CMAKE_DEPENDENT_OPTION(WITH_CEPH_DEBUG_MUTEX "Use debug ceph::mutex with lockdep" ON
  "CMAKE_BUILD_TYPE STREQUAL Debug" OFF)

/*
用于定义一个名为 "ALLOCATOR" 的 CMake 变量，并通过 CACHE 选项将其设置为可缓存的变量，同时提供了一个字符串参数，用于指定要使用的内存分配器。
在这段代码中，"set(ALLOCATOR "" CACHE STRING "specify memory allocator to use. currently tcmalloc, tcmalloc_minimal, jemalloc, and libc is supported. if not specified, will try to find tcmalloc, and then jemalloc. If neither of then is found. use the one in libc.")" 定义了一个名为 "ALLOCATOR" 的 CMake 变量，并设置其默认值为空字符串 ""，同时通过 CACHE 选项将其设置为可缓存的变量。其中，"specify memory allocator to use. currently tcmalloc, tcmalloc_minimal, jemalloc, and libc is supported. if not specified, will try to find tcmalloc, and then jemalloc. If neither of then is found. use the one in libc." 是对该变量的描述信息。
"ALLOCATOR" 变量用于指定要使用的内存分配器。如果在命令行上指定了分配器，则必须与以下字符串匹配：tcmalloc、tcmalloc_minimal、jemalloc 和 libc。如果未指定分配器，则会尝试查找 tcmalloc，然后是 jemalloc。如果两者都找不到，则使用 libc 中的分配器。
这样，通过设置 "ALLOCATOR" 变量，可以在 Ceph 存储系统中选择合适的内存分配器，以提高性能和效率。  
*/
#if allocator is set on command line make sure it matches below strings
set(ALLOCATOR "" CACHE STRING
  "specify memory allocator to use. currently tcmalloc, tcmalloc_minimal, \
jemalloc, and libc is supported. if not specified, will try to find tcmalloc, \
and then jemalloc. If neither of then is found. use the one in libc.")
```

剩余部分

```
if(ALLOCATOR)
  if(${ALLOCATOR} MATCHES "tcmalloc(_minimal)?")
    find_package(gperftools 2.6.2 REQUIRED)
    set(HAVE_LIBTCMALLOC ON)
  elseif(${ALLOCATOR} STREQUAL "jemalloc")
    find_package(JeMalloc REQUIRED)
    set(HAVE_JEMALLOC 1)
  elseif(NOT ALLOCATOR STREQUAL "libc")
    message(FATAL_ERROR "Unsupported allocator selected: ${ALLOCATOR}")
  endif()
else(ALLOCATOR)
  find_package(gperftools 2.6.2)
  set(HAVE_LIBTCMALLOC ${gperftools_FOUND})
  if(NOT gperftools_FOUND)
    find_package(JeMalloc)
  endif()
  if(gperftools_FOUND)
    set(ALLOCATOR tcmalloc)
  elseif(JeMalloc_FOUND)
    set(ALLOCATOR jemalloc)
  else()
    if(NOT FREEBSD)
      # FreeBSD already has jemalloc as its default allocator
      message(WARNING "tcmalloc and jemalloc not found, falling back to libc")
    endif()
    set(ALLOCATOR "libc")
  endif(gperftools_FOUND)
endif(ALLOCATOR)

# Mingw generates incorrect entry points when using "-pie".
if(WIN32 OR (HAVE_LIBTCMALLOC AND WITH_STATIC_LIBSTDCXX))
  set(EXE_LINKER_USE_PIE FALSE)
else()
  set(EXE_LINKER_USE_PIE ${ENABLE_SHARED})
endif()

find_package(CURL REQUIRED)
/*
set(CMAKE_REQUIRED_INCLUDES ${CURL_INCLUDE_DIRS}) 表示将变量 ${CURL_INCLUDE_DIRS}（即 CURL 库的头文件所在目录）设置为 CMake 构建系统的必要包含文件目录，以便后续的检查和编译。
set(CMAKE_REQUIRED_LIBRARIES ${CURL_LIBRARIES}) 表示将变量 ${CURL_LIBRARIES}（即 CURL 库的库文件所在目录）设置为 CMake 构建系统的必要库文件目录，以便后续的链接和运行。
这些设置可以确保使用 CURL 库的项目在编译和链接时能够正确地找到所需的头文件和库文件。
*/
set(CMAKE_REQUIRED_INCLUDES ${CURL_INCLUDE_DIRS})
set(CMAKE_REQUIRED_LIBRARIES ${CURL_LIBRARIES})
/*
使用 CMake 内置的 CHECK_SYMBOL_EXISTS 命令来检查 CURL 库中是否定义了 curl_multi_wait 函数，并将结果存储在名为 HAVE_CURL_MULTI_WAIT 的 CMake 变量中。
具体来说，CHECK_SYMBOL_EXISTS 命令用于检查指定的符号（函数、变量等）是否存在于指定的头文件中。在本例中，它检查 CURL 库头文件 curl/curl.h 中是否定义了 curl_multi_wait 函数。如果该函数存在，则将 CMake 变量 HAVE_CURL_MULTI_WAIT 设置为 1，否则设置为 0。
*/
CHECK_SYMBOL_EXISTS(curl_multi_wait curl/curl.h HAVE_CURL_MULTI_WAIT)

find_package(OpenSSL REQUIRED)
//使用 set 命令将 OpenSSL 库的 Crypto 模块的路径设置为 CRYPTO_LIBS 变量。这个变量通常用于后续的编译和链接过程，以确保在构建项目时能够正确地链接 OpenSSL 库的 Crypto 模块。
set(CRYPTO_LIBS OpenSSL::Crypto)

option(WITH_DPDK "Enable DPDK messaging" OFF)
if(WITH_DPDK)
  find_package(dpdk)
  if(NOT DPDK_FOUND)
    include(BuildDPDK)
    build_dpdk(${CMAKE_BINARY_DIR}/src/dpdk)
  endif()
  set(HAVE_DPDK TRUE)
endif()
/*
定义了一个 CMake 选项 WITH_BLKIN，用于控制是否启用 blkin 模块以用于生成 LTTng 跟踪数据。如果启用了 WITH_BLKIN 选项，代码将使用 find_package 命令来查找 LTTngUST 库，并将结果存储在 LTTNGUST_LIBRARIES 变量中。
接下来，代码设置了 BLKIN_LIBRARIES 变量，该变量包含了 blkin 模块、LTTngUST 库和 lttng-ust-fork 库的路径信息，以便在后续的编译和链接过程中使用。
最后，代码使用 include_directories 命令将 blkin-lib 目录添加到项目的包含文件路径中。
*/
option(WITH_BLKIN "Use blkin to emit LTTng tracepoints for Zipkin" OFF)
if(WITH_BLKIN)
  find_package(LTTngUST REQUIRED)
  set(BLKIN_LIBRARIES blkin ${LTTNGUST_LIBRARIES} lttng-ust-fork)
  include_directories(SYSTEM src/blkin/blkin-lib)
endif(WITH_BLKIN)
/*
定义了一个 CMake 选项 WITH_JAEGER，用于控制是否启用 JaegerTracing 和其依赖库。如果启用了 WITH_JAEGER 选项，则代码将设置 HAVE_JAEGER 变量为 TRUE。
这个变量可以在编译过程中用于条件判断，以便根据是否启用了 JaegerTracing 来编译或链接相关的代码或库。
*/
option(WITH_JAEGER "Enable jaegertracing and it's dependent libraries" OFF)
if(WITH_JAEGER)
  set(HAVE_JAEGER TRUE)
endif()

#option for RGW
option(WITH_RADOSGW "Rados Gateway is enabled" ON)
option(WITH_RADOSGW_BEAST_OPENSSL "Rados Gateway's Beast frontend uses OpenSSL" ON)
option(WITH_RADOSGW_AMQP_ENDPOINT "Rados Gateway's pubsub support for AMQP push endpoint" ON)
option(WITH_RADOSGW_KAFKA_ENDPOINT "Rados Gateway's pubsub support for Kafka push endpoint" ON)
option(WITH_RADOSGW_LUA_PACKAGES "Rados Gateway's support for dynamically adding lua packagess" ON)
option(WITH_RADOSGW_DBSTORE "DBStore backend for Rados Gateway" ON)
option(WITH_RADOSGW_SELECT_PARQUET "Support for s3 select on parquet objects" ON)

option(WITH_SYSTEM_ARROW "Use system-provided arrow" OFF)
option(WITH_SYSTEM_UTF8PROC "Use system-provided utf8proc" OFF)

if(WITH_RADOSGW)
  find_package(EXPAT REQUIRED)//一款用于解析 XML 文件的库
  find_package(OATH REQUIRED)//身份验证库，它实现了多种基于时间同步技术的身份验证算法，比如 HOTP、TOTP 等。这些算法用于生成一次性密码，以增强系统的安全性。OATH 库广泛应用于各种领域，比如金融、电子商务等

  /*
  运行一个 shell 命令，获取 curl 库是否启用了 SSL 支持的信息。
具体来说，这个 cmake 代码会执行一个名为 curl-config 的命令，并使用 sh -c 的方式运行命令。curl-config 是一个用于获取 curl 库配置信息的命令行工具，它支持多种参数。在这个代码中，使用了 --configure 参数获取 curl 库的配置信息，并使用 grep with-ssl 命令过滤出是否启用了 SSL 支持。执行结果会存储在 CURL_CONFIG_ERRORS 变量中，如果执行成功则 NO_CURL_SSL_LINK 的值为 0，否则为非零值。
通过这个 cmake 代码，开发者可以在编译时检查 curl 库是否启用了 SSL 支持，并根据检查结果进行相应的处理。比如，如果 curl 库未启用 SSL 支持，则需要使用其他库或者升级 curl 库等。
  */
# https://curl.haxx.se/docs/install.html mentions the
# configure flags for various ssl backends
  execute_process(
    COMMAND
  "sh" "-c"
  "curl-config --configure | grep with-ssl"
  RESULT_VARIABLE NO_CURL_SSL_LINK
  ERROR_VARIABLE CURL_CONFIG_ERRORS
  )
  if (CURL_CONFIG_ERRORS)
    message(WARNING "unable to run curl-config; rgw cannot make ssl requests to external systems reliably")
  endif()

  if (NOT NO_CURL_SSL_LINK)
    message(STATUS "libcurl is linked with openssl: explicitly setting locks")
    set(WITH_CURL_OPENSSL ON)
  endif() # CURL_SSL_LINK

  /*
  获取 OpenSSL 库的 SONAME。

具体来说，这个 cmake 代码会执行一个名为 objdump 的命令，并使用 sh -c 的方式运行命令。objdump 是一个用于显示文件、目标文件、共享库等二进制文件的信息的命令行工具，它支持多种参数。在这个代码中，使用了 -p 参数来显示 OpenSSL 库的信息，并使用 sed 命令过滤出 SONAME。命令执行结果会存储在 LIBSSL_SONAME 变量中，如果执行成功则 OBJDUMP_RESULTS 的值为 0，否则为非零值。同时，执行错误信息会存储在 OBJDUMP_ERRORS 变量中。

通过这个 cmake 代码，开发者可以获取 OpenSSL 库的 SONAME，并使用它来进行版本检查和链接等操作。SONAME 是共享库的符号名称，用于指定共享库的版本和接口等信息。在应用程序中链接共享库时，需要指定共享库的 SONAME，以确保应用程序与正确版本的共享库链接。
  */
  execute_process(
    COMMAND
      "sh" "-c"
      "objdump -p ${OPENSSL_SSL_LIBRARY} | sed -n 's/^  SONAME  *//p'"
    OUTPUT_VARIABLE LIBSSL_SONAME
    ERROR_VARIABLE OBJDUMP_ERRORS
    RESULT_VARIABLE OBJDUMP_RESULTS
    OUTPUT_STRIP_TRAILING_WHITESPACE)
  if (OBJDUMP_RESULTS)
    message(FATAL_ERROR "can't run objdump: ${OBJDUMP_RESULTS}")
  endif()
  if (NOT OBJDUMP_ERRORS STREQUAL "")
    message(WARNING "message from objdump: ${OBJDUMP_ERRORS}")
  endif()
  execute_process(
    COMMAND
      "sh" "-c"
      "objdump -p ${OPENSSL_CRYPTO_LIBRARY} | sed -n 's/^  SONAME  *//p'"
    OUTPUT_VARIABLE LIBCRYPTO_SONAME
    ERROR_VARIABLE OBJDUMP_ERRORS
    RESULT_VARIABLE OBJDUMP_RESULTS
    OUTPUT_STRIP_TRAILING_WHITESPACE)
  if (OBJDUMP_RESULTS)
    message(FATAL_ERROR "can't run objdump: ${OBJDUMP_RESULTS}")
  endif()
  if (NOT OBJDUMP_ERRORS STREQUAL "")
    message(WARNING "message from objdump: ${OBJDUMP_ERRORS}")
  endif()
  message(STATUS "ssl soname: ${LIBSSL_SONAME}")
  message(STATUS "crypto soname: ${LIBCRYPTO_SONAME}")
endif (WITH_RADOSGW)

#option for CephFS
option(WITH_CEPHFS "CephFS is enabled" ON)

/*
则会调用 find_package 函数查找指定版本的 Python3。WITH_PYTHON3 变量用于指定 Python3 的版本号，可以通过在命令行中使用 -DWITH_PYTHON3=3.6 的形式来指定版本号。如果没有指定版本号，则默认使用系统中已安装的最新版本的 Python3。
*/
if(NOT WIN32)
# Please specify 3.[0-7] if you want to build with a certain version of python3.
set(WITH_PYTHON3 "3" CACHE STRING "build with specified python3 version")
find_package(Python3 ${WITH_PYTHON3} EXACT REQUIRED
  COMPONENTS Interpreter Development)

/*
MGR_PYTHON_EXECUTABLE：ceph-mgr 使用的 Python 解释器的路径。
MGR_PYTHON_LIBRARIES：ceph-mgr 使用的 Python 库的路径。
MGR_PYTHON_VERSION_MAJOR：ceph-mgr 使用的 Python 版本号的主版本号。
MGR_PYTHON_VERSION_MINOR：ceph-mgr 使用的 Python 版本号的次版本号。
*/
option(WITH_MGR "ceph-mgr is enabled" ON)
if(WITH_MGR)
  set(MGR_PYTHON_EXECUTABLE ${Python3_EXECUTABLE})
  set(MGR_PYTHON_LIBRARIES ${Python3_LIBRARIES})
  set(MGR_PYTHON_VERSION_MAJOR ${Python3_VERSION_MAJOR})
  set(MGR_PYTHON_VERSION_MINOR ${Python3_VERSION_MINOR})
  # Boost dependency check deferred to Boost section
endif(WITH_MGR)
endif(NOT WIN32)

option(WITH_THREAD_SAFE_RES_QUERY "res_query is thread safe" OFF)
if(WITH_THREAD_SAFE_RES_QUERY)
//设置一个名为 HAVE_THREAD_SAFE_RES_QUERY 的变量，值为 1。这个变量用于表示当前编译环境支持线程安全的 res_query 函数。
  set(HAVE_THREAD_SAFE_RES_QUERY 1 CACHE INTERNAL "Thread safe res_query supported.")
endif()

option(WITH_REENTRANT_STRSIGNAL "strsignal is reentrant" OFF)
if(WITH_REENTRANT_STRSIGNAL)
//设置一个名为 HAVE_REENTRANT_STRSIGNAL 的变量，值为 1。这个变量用于表示当前编译环境支持可重入的 strsignal 函数。
  set(HAVE_REENTRANT_STRSIGNAL 1 CACHE INTERNAL "Reentrant strsignal is supported.")
endif()

/*
ZLIB 库提供的压缩和解压缩功能，以增强项目的功能和性能。例如，在处理大量数据时，可以使用 ZLIB 库对数据进行压缩，以减少数据传输和存储的成本。
*/
# -lz link into kv
find_package(ZLIB REQUIRED)

#option for EventTrace
//通过设置 USE_LTTNG 来决定是否启用事件追踪支持。如果开启了事件追踪支持，则可以在应用程序运行时记录各种事件，以便进行性能分析和调试。
CMAKE_DEPENDENT_OPTION(
  WITH_EVENTTRACE "Event tracing support, requires WITH_LTTNG"
  OFF "USE_LTTNG" OFF)

#option for LTTng
option(WITH_LTTNG "LTTng tracing is enabled" ON)
if(${WITH_LTTNG})
  find_package(LTTngUST REQUIRED)
  find_program(LTTNG_GEN_TP
    lttng-gen-tp)
  if(NOT LTTNG_GEN_TP)
    message(FATAL_ERROR "Can't find lttng-gen-tp.")
  endif()
endif(${WITH_LTTNG})

option(WITH_OSD_INSTRUMENT_FUNCTIONS OFF)

//Babeltrace 库是一个用于处理跨平台追踪数据的工具，可以读取和处理各种追踪数据格式。可以实现更加高效和精确的性能分析和调试工作。
#option for Babeltrace
option(WITH_BABELTRACE "Babeltrace libraries are enabled" ON)
if(WITH_BABELTRACE)
  set(HAVE_BABELTRACE ON)
  find_package(babeltrace REQUIRED)
  set(HAVE_BABELTRACE_BABELTRACE_H ${BABELTRACE_FOUND})
  set(HAVE_BABELTRACE_CTF_EVENTS_H ${BABELTRACE_FOUND})
  set(HAVE_BABELTRACE_CTF_ITERATOR_H ${BABELTRACE_FOUND})
endif(WITH_BABELTRACE)

option(DEBUG_GATHER "C_Gather debugging is enabled" ON)
option(ENABLE_COVERAGE "Coverage is enabled" OFF)
option(PG_DEBUG_REFS "PG Ref debugging is enabled" OFF)

option(WITH_TESTS "enable the build of ceph-test package scripts/binaries" ON)
set(UNIT_TESTS_BUILT ${WITH_TESTS})
set(CEPH_TEST_TIMEOUT 3600 CACHE STRING 
  "Maximum time before a CTest gets killed" )

# fio
option(WITH_FIO "build with fio plugin enabled" OFF)
if(WITH_FIO)
  include(BuildFIO)
  build_fio()
endif()

if(LINUX)
//用于添加编译器选项或预处理器定义的函数
  add_definitions(-D__linux__)
endif(LINUX)
//启用 ASAN，则会将 "address" 添加到 sanitizers 列表中，表示需要使用 ASAN 内存分析器。
// 启用 ASAN 内存分析器来检查程序在运行时的内存使用情况，以及发现和调试内存泄漏、缓冲区溢出等常见的内存错误。
# ASAN and friends
option(WITH_ASAN "build with ASAN" OFF)
if(WITH_ASAN)
  list(APPEND sanitizers "address")
endif()

/*
启用 ASAN、TSAN、UBSAN 内存分析器，以检查程序在运行时的内存使用情况，并发现和调试内存泄漏、缓冲区溢出、数据竞争等常见的内存错误。同时，它还能帮助开发者提高程序的安全性和稳定性。
*/
option(WITH_ASAN_LEAK "explicitly enable ASAN leak detection" OFF)
if(WITH_ASAN_LEAK)
  list(APPEND sanitizers "leak")
endif()

option(WITH_TSAN "build with TSAN" OFF)
if(WITH_TSAN)
  list(APPEND sanitizers "thread")
endif()

option(WITH_UBSAN "build with UBSAN" OFF)
if(WITH_UBSAN)
  list(APPEND sanitizers "undefined_behavior")
endif()

if(sanitizers)
  find_package(Sanitizers REQUIRED ${sanitizers})
  add_compile_options(${Sanitizers_COMPILE_OPTIONS})
  string(REPLACE ";" " " sanitiers_compile_flags "${Sanitizers_COMPILE_OPTIONS}")
  string(APPEND CMAKE_EXE_LINKER_FLAGS " ${sanitiers_compile_flags}")
  string(APPEND CMAKE_SHARED_LINKER_FLAGS " ${sanitiers_compile_flags}")
endif()

# Rocksdb
option(WITH_SYSTEM_ROCKSDB "require and build with system rocksdb" OFF)
if (WITH_SYSTEM_ROCKSDB)
  find_package(RocksDB 5.14 REQUIRED)
endif()

option(WITH_SEASTAR "Build seastar components")
set(HAVE_SEASTAR ${WITH_SEASTAR})

//WITH_SYSTEM_BOOST 的选项变量，用于指定是否使用系统自带的 Boost 库。如果开发者需要使用系统自带的 Boost 库，则需要将该选项变量设置为 ON，否则设置为 OFF。
# Boost
option(WITH_SYSTEM_BOOST "require and build with system Boost" OFF)
//作用是设置 Boost 库的编译选项和组件列表。
//定义了一个名为 BOOST_COMPONENTS 的列表变量，用于存储需要使用的 Boost 组件名称。在这个列表中，列出了 Boost 库的各个组件，包括 atomic、chrono、thread、system、regex、random、program_options、date_time、iostreams、context 和 coroutine 组件。
# Boost::thread depends on Boost::atomic, so list it explicitly.
set(BOOST_COMPONENTS
  atomic chrono thread system regex random program_options date_time
  iostreams context coroutine)
//用于存储只需要使用 Boost 头文件的组件名称
set(BOOST_HEADER_COMPONENTS container)

if(WITH_MGR)
  list(APPEND BOOST_COMPONENTS
    python${MGR_PYTHON_VERSION_MAJOR}${MGR_PYTHON_VERSION_MINOR})
endif()
if(WITH_SEASTAR)
  list(APPEND BOOST_COMPONENTS timer)
endif()

if(WITH_RADOSGW AND WITH_RADOSGW_LUA_PACKAGES)
  list(APPEND BOOST_COMPONENTS filesystem)
endif()

set(Boost_USE_MULTITHREADED ON)
# require minimally the bundled version
if(WITH_SYSTEM_BOOST)
  if(ENABLE_SHARED)
    set(Boost_USE_STATIC_LIBS OFF)
  else()
    set(Boost_USE_STATIC_LIBS ON)
  endif()
  if(BOOST_ROOT AND CMAKE_LIBRARY_ARCHITECTURE)
    set(BOOST_LIBRARYDIR "${BOOST_ROOT}/lib/${CMAKE_LIBRARY_ARCHITECTURE}")
  endif()
  find_package(Boost 1.73 COMPONENTS ${BOOST_COMPONENTS} REQUIRED)
  if(NOT ENABLE_SHARED)
    set_property(TARGET Boost::iostreams APPEND PROPERTY
      INTERFACE_LINK_LIBRARIES ZLIB::ZLIB)
  endif()
else()
  set(BOOST_J 1 CACHE STRING
    "max jobs for Boost build") # override w/-DBOOST_J=<n>
  set(Boost_USE_STATIC_LIBS ON)
  include(BuildBoost)
  build_boost(1.75
    COMPONENTS ${BOOST_COMPONENTS} ${BOOST_HEADER_COMPONENTS})
endif()
include_directories(BEFORE SYSTEM ${Boost_INCLUDE_DIRS})

# dashboard angular2 frontend
option(WITH_MGR_DASHBOARD_FRONTEND "Build the mgr/dashboard frontend using `npm`" ON)
option(WITH_SYSTEM_NPM "Assume that dashboard build tools already installed through packages" OFF)
if(WITH_SYSTEM_NPM)
  find_program(NPM_EXECUTABLE npm)
  if(NOT NPM_EXECUTABLE)
    message(FATAL_ERROR "Can't find npm.")
  endif()
endif()
set(DASHBOARD_FRONTEND_LANGS "" CACHE STRING
  "List of comma separated ceph-dashboard frontend languages to build. \
  Use value `ALL` to build all languages")
CMAKE_DEPENDENT_OPTION(WITH_MGR_ROOK_CLIENT "Enable the mgr's Rook support" ON
  "WITH_MGR" OFF)

include_directories(SYSTEM ${PROJECT_BINARY_DIR}/include)

find_package(Threads REQUIRED)
//标准文件系统库是 C++17 标准库中新增的一个库，用于处理文件系统相关的操作，比如遍历目录、创建、删除、移动、复制文件等等。通过使用标准文件系统库
find_package(StdFilesystem REQUIRED)

option(WITH_SELINUX "build SELinux policy" OFF)
if(WITH_SELINUX)
  find_file(SELINUX_MAKEFILE selinux/devel/Makefile
    PATH /usr/share)
  if(NOT SELINUX_MAKEFILE)
    message(FATAL_ERROR "Can't find selinux's Makefile")
  endif()
  add_subdirectory(selinux)
endif(WITH_SELINUX)

# enables testing and creates Make check command
add_custom_target(tests
  COMMENT "Building tests")
enable_testing()
set(CMAKE_CTEST_COMMAND ctest)
add_custom_target(check
  COMMAND ${CMAKE_CTEST_COMMAND}
  DEPENDS tests)
//systemd 是一个 Linux 系统管理守护进程，用于启动和停止系统服务、管理系统日志、监控进程等等。如需要在项目中使用 systemd 相关的功能，则需要将 WITH_SYSTEMD 设置为 ON，否则设置为 OFF。
option(WITH_SYSTEMD "build with systemd support" ON)

add_subdirectory(src)

add_subdirectory(qa)
add_subdirectory(doc)
if(WITH_MANPAGE)
  add_subdirectory(man)
endif(WITH_MANPAGE)

if(WITH_SYSTEMD)
  add_subdirectory(systemd)
endif()

if(LINUX)
  add_subdirectory(etc/sysctl)//包含一些与系统内核参数相关的文件，需要在 Linux 系统上进行编译和安装
endif()

option(WITH_GRAFANA "install grafana dashboards" OFF)
add_subdirectory(monitoring/ceph-mixin)
//指定是否启用 Boost 对 valgrind 工具的支持。
/*
函数中的第一个参数 "WITH_BOOST_VALGRIND" 表示选项变量的名称，第二个参数 OFF 表示选项变量的默认值。第三个参数 "NOT WITH_SYSTEM_BOOST" 是一个表达式，用于判断当前系统是否使用了系统自带的 Boost 库。如果当前系统没有使用系统自带的 Boost 库，则选项变量 WITH_BOOST_VALGRIND 的值为 OFF。最后一个参数 OFF 表示如果表达式的值为 FALSE，则选项变量的可选值为 OFF。

valgrind 是一种用于检测内存泄漏和程序错误的工具，通过在程序运行时动态分析程序的内存访问情况，可以帮助开发者发现和修复程序中的各种内存问题。
*/
CMAKE_DEPENDENT_OPTION(WITH_BOOST_VALGRIND "Boost support for valgrind" OFF
  "NOT WITH_SYSTEM_BOOST" OFF)

/*
函数中的第一个参数 ctags 表示自定义目标的名称。后面的几个参数 SRC_DIR src、TAG_FILE tags、EXCLUDE_OPTS ${CTAG_EXCLUDES}、EXCLUDES "*.js" "*.css" ".tox" "python-common/build" 分别表示：

SRC_DIR src：标记的源代码目录为 src。
TAG_FILE tags：生成的标签文件名为 tags。
EXCLUDE_OPTS ${CTAG_EXCLUDES}：排除生成标签时需要排除的文件或目录。
EXCLUDES "*.js" "*.css" ".tox" "python-common/build"：另外需要排除的文件或目录。
${CTAG_EXCLUDES} 是一个变量，用于表示用户设置的需要排除的文件或目录，其值由之前的 option(CTAG_EXCLUDES "Exclude files/directories when running ctag.") 命令定义。
*/
include(CTags)
option(CTAG_EXCLUDES "Exclude files/directories when running ctag.")
add_tags(ctags
  SRC_DIR src
  TAG_FILE tags
  EXCLUDE_OPTS ${CTAG_EXCLUDES}
  EXCLUDES "*.js" "*.css" ".tox" "python-common/build")
add_custom_target(tags DEPENDS ctags)
```