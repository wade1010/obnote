```javascript
data := make([]byte, 11)
for i := 0; i < 10; i++ {
   data[i] = byte(i)
}
dd := data[1:3:3]
fmt.Println(dd)
```



Invalid index values, must be low <= high <= max



1:3就是取切片1:3

:3就是dd的cap是3



注意3个数字的大小关系

Invalid index values, must be low <= high <= max

