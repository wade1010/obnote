 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753194.jpg)



```javascript
package main

import "fmt"

func main() {
   s1 := new([]int)
   fmt.Println(s1)
   s2 := make([]int, 5)
   fmt.Println(s2)
}
```



结果



```javascript
&[]
[0 0 0 0 0]

```



可见 new是返回指针地址 只是申请了空间 不初始化



make是初始化 零值