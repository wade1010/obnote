安装stringer

stringer不是Go自带工具，需要手动安装。执行如下命令即可

go get golang.org/x/tools/cmd/stringer

errcode.go

```
package enum
?
type ErrCode int64 //错误码
const (
   ERR_CODE_OK             ErrCode = 0 // OK
   ERR_CODE_INVALID_PARAMS ErrCode = 1 // 无效参数
   ERR_CODE_TIMEOUT        ErrCode = 2 // 超时
)
```

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750863.jpg)

//注释后面是什么字符串，回头生成代码桩后，前面的KEY会自动map到该字符串

执行 

go generate -x ./enum

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750104.jpg)

```
package main

import (
	"fmt"
	"go_generate_demo/enum"
)

func main() {
	fmt.Print(enum.ERR_CODE_OK)             //输出OK
	fmt.Print(enum.ERR_CODE_INVALID_PARAMS) //无效参数
	fmt.Print(enum.ERR_CODE_TIMEOUT)        //输出超时
}


```