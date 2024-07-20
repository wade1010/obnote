Log.h

```
#include <iostream>
void Log(const char * msg){
    std::cout<< msg << std::endl;
}
```

Log.cpp

```
#include "Log.h"

```

study.cpp

```
#include "Log.h"
........
```

链接阶段会报错，相当于重定义，

因为Log.h的预处理，我们都知道预处理在编译的时候会把整个头文件直接copy过来，这样Log.cpp和study.cpp都会copy一遍，那最终链接是链接到Log.cpp中的还是study.cpp中的，就不好定了。

解决这个问题：

1，在Log.h中加一个static关键字

```
#include <iostream>
static void Log(const char * msg){
    std::cout<< msg << std::endl;
}
```

加了static关键字后，在链接的时候，就会相当于，每个cpp文件都有一个单独Log函数，而且本Log只会在本cpp文件中产生作用，不会链接到其它cpp文件中的Log函数。

2，改成inline

```
#include <iostream>
inline void Log(const char * msg){
    std::cout<< msg << std::endl;
}
```

直接告诉编译器复制该方法体到需要的地方，不需要再使用该函数了。

3，只在Log.h只进行声明

```
#include <iostream>
void Log(const char * msg);
```

然后在某个cpp文件中实现该方法，这样，链接也只会链接到这个方法。