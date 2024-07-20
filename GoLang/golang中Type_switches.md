请说出下面代码存在什么问题。

type student struct {
	Name string
}

func zhoujielun(v interface{}) {
	switch msg := v.(type) {
	case *student, student:
		msg.Name="b"
	}
}





golang中有规定，switch type的case T1，类型列表只有一个，那么v := m.(type)中的v的类型就是T1类型。

如果是case T1, T2，类型列表中有多个，那v的类型还是多对应接口的类型，也就是m的类型。

所以这里msg的类型还是interface{}，所以他没有Name这个字段，编译阶段就会报错。具体解释见： https://golang.org/ref/spec#Type_switches