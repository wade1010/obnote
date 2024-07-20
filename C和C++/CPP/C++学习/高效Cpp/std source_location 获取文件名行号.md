```
#include <iostream>
#include <string_view>
// 采用宏展开的方式 ，定义一个日志宏
#define LOG(message) log(message, __FILE__, __LINE__)

void log(std::string_view message, std::string_view filename, int line)
{
    std::cout << "info:" << filename << ":" << line << " " << message << std::endl;
}

int main()
{
    LOG("Hello world!");
    return 0;
}
```