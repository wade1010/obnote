这段代码可以编译过吗，如果会错是在哪一行？

```javascript
type Test struct {
	Name string
}
var list map[string]Test
func main() {
	list = make(map[string]Test)
	name := Test{"xiaoming"}
	list["name"] = name
	
	fmt.Println(list["name"].Name)
	list["name"].Name = "Hello"
	fmt.Println(list["name"])
}
```

不能！倒数第三行报错： cannot assign to struct field list["name"].Name in map

因为map的value不是指针。首先，map本来存储的就是value的“初始指针值”，可以打印list["name"].Name,  但不能通过取值的方式来修改。 因为当map扩容时，内部元素会在内存中移动， 移动之后list["name"].Name获取到的值依然有效，但获取到的指针是无效的，如果允许这样赋值，那之后再打印list["name"].Name 是获取不到修改后的值的。 而当value是指针时，也就是说list["name"]是指针，list["name"].Name就是指针内部的指针，值改变后，list["name"]仍然获取到的是原始数据指针，也就仍然可以获取到list["name"].Name。







map的value本身是不可寻址的，因为map中的值会在内存中移动，并且旧的指针地址在map改变时会变得无效。故如果需要修改map值，可以将map中的非指针类型value，修改为指针类型，比如使用map[string]*Student.