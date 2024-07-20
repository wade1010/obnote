go test 功能，提高了开发和测试的效率。

有时会遇到这样的场景：

进行测试之前需要初始化操作(例如打开连接)，测试结束后，需要做清理工作(例如关闭连接)等等。这个时候就可以使用TestMain()。



下面例子的文件结构如下：



hello/add.go

hello/test_add.go



add.go

```javascript
package hello
 
func Add(a,b int) int {
 
    return a+b
}
```



add_test.go

```javascript
package hello
 
import(
    "fmt"
    "testing"
)
 
func TestAdd(t *testing.T) {
        r := Add(1, 2)
        if r !=3 {
                t.Errorf("Add(1, 2) failed. Got %d, expected 3.", r)
        }
}
 
func TestMain(m *testing.M) {
    fmt.Println("begin")
    m.Run()
    fmt.Println("end")
}
```



测试从TestMain进入