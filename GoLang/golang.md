string 底层就是一个byte数组，因此也可以就行切片操作



str:="hello world"

s1:=str[0:5]

fmt.Println(s1)



s2:=str[6:]

fmt.Println(s2)



输出结果



hello

world





channel 是线程安全的





chan的关闭

1.使用内置函数close进行关闭，chan关闭之后， for range遍历chan中

已经存在的元素后结束



2.使用内置 函数close进行关闭，chan关闭之后，没有使用for range的写法

需要使用，y, ok:= <- ch进行判断chan是否关闭



