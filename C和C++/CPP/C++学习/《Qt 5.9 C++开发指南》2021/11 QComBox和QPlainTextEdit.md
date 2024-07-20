QPlainTextEdit的文字内容以QTextDocument类型储存，函数documenti返回这个文档对象的指针。

QTextDocument是内存中的文本对象，以文本块的方式储存，每个段落以换行符结束。

QTextDocument提供一些函数实现对文本内容的存取。

- int blockCount(),返回文本块个数

- QTextBlock finBlockByNumber(int)读取一个文本块，序号从0开始。

实验demo

![](https://gitee.com/hxc8/images2/raw/master/img/202407172215061.jpg)