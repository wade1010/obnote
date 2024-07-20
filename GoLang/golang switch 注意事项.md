

```javascript
package main

import "fmt"

func main() {
   a := 1
   switch gen(a) {
   case true:
      fmt.Println("true")
   case false:
      fmt.Println("false")
   }
   fmt.Println(a)
}

func gen(a int) bool {
   return a > 2
}
```



输出false





修改下 switch 加个;

```javascript
package main

import "fmt"

func main() {
   a := 1
   switch gen(a); {
   case true:
      fmt.Println("true")
   case false:
      fmt.Println("false")
   }
   fmt.Println(a)
}

func gen(a int) bool {
   return a > 2
}
```

输出true