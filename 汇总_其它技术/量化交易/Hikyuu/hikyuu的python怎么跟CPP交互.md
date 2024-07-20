Hikyuu是一个基于C++编写的开源量化交易框架，它提供了一套完整的股票、期货、期权等金融数据的获取、处理、分析和交易的解决方案。在Python中使用Hikyuu框架时，需要通过Python的扩展模块机制（即Python C API）来实现Python与C++的交互。

具体而言，Hikyuu通过将核心C++库封装为动态链接库（.dll或.so文件），并提供与Python C API兼容的C接口，从而使Python程序可以调用Hikyuu的C++函数。Python程序在调用Hikyuu函数时，可以传递Python对象作为参数，这些Python对象将被转换为C++对象，然后传递给Hikyuu函数进行处理。同样地，当Hikyuu函数返回结果时，这些结果将被转换为Python对象，并返回给Python程序。

在Hikyuu中，Python与C++之间的交互主要是通过以下两个扩展模块实现的：

1. Boost.Python：Boost.Python是一个C++库，它提供了一组工具和库，用于将C++函数和类导出为Python模块。在Hikyuu中，Boost.Python库被用于将Hikyuu的核心C++库导出为Python模块，从而使Python程序可以调用Hikyuu函数。

1. pybind11：pybind11是一个轻量级的头文件库，它提供了一组工具和库，用于将C++函数和类导出为Python模块。在Hikyuu中，pybind11库被用于将一些Hikyuu的C++函数和类导出为Python模块，从而使Python程序可以方便地调用这些函数和类。

总体而言，Python与C++之间的交互主要是通过Python C API、Boost.Python和pybind11等工具实现的。这些工具使得Python程序可以方便地调用C++函数和类，从而可以利用Hikyuu框架提供的丰富功能和高性能。