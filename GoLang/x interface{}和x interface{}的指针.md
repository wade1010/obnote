x interface{} 和 *x interface{}



ABCD哪一行会报错?

```javascript
type S struct {
}
 
func f(x interface{}) {
}
 
func g(x *interface{}) {
}
 
func main() {
	s := S{}
	p := &s
	f(s) //A
	g(s) //B
	f(p) //C
	g(p) //D
}
```





B和D行。interface可以接受任意类型参数，包括指针。但是*interface{} 就只能接受*interface{}