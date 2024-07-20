我们知道呢，map在golang里头是只增不减的一种数组结构，他只会在删除的时候进行打标记说明该内存空间已经empty了，不会回收的。



```javascript
package main

import (
   "log"
   "runtime"
)

var intMap map[int]int

func main() {
   printMemStats("初始化")
   // 添加1w个map值
   intMap = make(map[int]int, 10000)
   for i := 0; i < 10000; i++ {
      intMap[i] = i
   }

   // 手动进行gc操作
   runtime.GC()
   // 再次查看数据
   printMemStats("增加map数据后")

   log.Println("删除前数组长度：", len(intMap))
   for i := 0; i < 10000; i++ {
      delete(intMap, i)
   }
   log.Println("删除后数组长度：", len(intMap))

   // 再次进行手动GC回收
   runtime.GC()
   printMemStats("删除map数据后")

   // 设置为nil进行回收
   intMap = nil
   runtime.GC()
   printMemStats("设置为nil后")
}
func printMemStats(mag string) {
   var m runtime.MemStats
   runtime.ReadMemStats(&m)
   log.Printf("%v：分配的内存 = %vKB, GC的次数 = %v\n", mag, m.Alloc/1024, m.NumGC)
}
```



会输出：



2021/02/05 13:50:37 初始化：分配的内存 = 120KB, GC的次数 = 0

2021/02/05 13:50:37 增加map数据后：分配的内存 = 428KB, GC的次数 = 1

2021/02/05 13:50:37 删除前数组长度： 10000

2021/02/05 13:50:37 删除后数组长度： 0

2021/02/05 13:50:37 删除map数据后：分配的内存 = 430KB, GC的次数 = 2

2021/02/05 13:50:37 设置为nil后：分配的内存 = 118KB, GC的次数 = 3



