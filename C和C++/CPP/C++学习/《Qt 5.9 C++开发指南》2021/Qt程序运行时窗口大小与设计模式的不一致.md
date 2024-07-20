这个问题可能是由分辨率不同的显示器的缩放不同导致的。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172215449.jpg)

在main函数添加：

```
if(QT_VERSION >= QT_VERSION_CHECK(5, 6, 0))
    QCoreApplication::setAttribute(Qt::AA_EnableHighDpiScaling);
QApplication a(argc, argv); // 放在这一句的前面

```

## Qt6解决方法

在main函数添加一行代码：

```
int main(int argc, char* argv[])
{
    QGuiApplication::setHighDpiScaleFactorRoundingPolicy(Qt::HighDpiScaleFactorRoundingPolicy::Floor);
    QApplication a(argc, argv);\\必须写在这一句的前面
    MainWidget w;
    w.show();
    return a.exec();
}

```

如果没有达到预期效果，可以尝试传入其他参数：

![](https://gitee.com/hxc8/images2/raw/master/img/202407172215492.jpg)

原文

[https://blog.csdn.net/weixin_51242840/article/details/120857393](https://blog.csdn.net/weixin_51242840/article/details/120857393)