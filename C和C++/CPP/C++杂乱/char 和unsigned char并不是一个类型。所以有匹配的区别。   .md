char 和unsigned char并不是一个类型。所以有匹配的区别。   

1.     

     char也是整型数据， 一个字节，被分为三种：char， signed char, unsigned char。 类型char和类型signed char并不一样。 尽管字符型有三种，但是表现形式却只有两种，即带符号和无符号的。类型char会表现为其中一种，具体是哪种由编译器决定。signed char最高位要用来作符号位。          

1.     

     2. 在算是表达式中不要使用char或者bool， 只有在存放字符或布尔值时才使用它们。因为char在一些机器上是有符号，一些上是无符号，特别容易出问题。如果你需要用一个不大的整数，要明确指定它是signed char还是unsigned char。