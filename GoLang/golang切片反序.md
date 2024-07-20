package main



import (

	"fmt"

)



func main() {

	fmt.Println(reverse([]byte{11,22,33,44}))

}





func reverse(s []byte) []byte {

	for i, j := 0, len(s)-1; i < j; i, j = i+1, j-1 {

		s[i], s[j] = s[j], s[i]

	}

	return s

}