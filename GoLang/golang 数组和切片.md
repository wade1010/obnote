切片是数组的一个引用



切片的长度和容量



切片的长度是指切片中元素的个数。切片的容量是指从切片的起始元素开始到其底层数组中的最后一个元素的个数。



切片追加元素时 容量的变化

每次append一个元素时：如果超出容量，append内部会创建一个新的数组，并将原有数组的元素拷贝给这个新的数组，新切片容量是旧切片容量的二倍；

每次append多个元素时:如果超过容量，但不超过容量的的两倍，则新切片容量是旧切片的两倍，同单个元素时一样，超过两倍时，新切片容量等于旧切片容量+append的元素个数

fruits := []string{"oranges", "apples", "pear"}

food := append(fruits, "aaa")

fmt.Println("food cap:", cap(food))                   //6 两倍

food2 := append(fruits, "aaa", "aaa")                 

fmt.Println("food3 cap:", cap(food2))                 //6 不超过2倍 按2倍算

food3 := append(fruits, "aaa", "aaa", "aaac", "aaad") 

fmt.Println("food3 cap:", cap(food3))                  //7 超过2倍 等于旧切片容量+append的元素个数



后来发现字符串是符合上面的额，int不符合，其他类型待测试



```javascript
s := make([]int, 5)
println(cap(s))
s = append(s, 1, 2, 3, 4, 5, 8)
println(cap(s))

s2 := make([]string, 5)
println(cap(s2))
s2 = append(s2, "a1", "a1", "a1", "a1", "a1", "a1")
println(cap(s2))
```



上面输出结果

```javascript
5
12
5
11
```



你可能会问一个问题：(每次添加一个元素)如果切片是建立在数组之上的，而数组本身不能改变长度，那么切片是如何动态改变长度的呢？实际发生的情况是，当新元素通过调用 append 函数追加到切片末尾时，如果超出了容量，append 内部会创建一个新的数组。并将原有数组的元素被拷贝给这个新的数组，最后返回建立在这个新数组上的切片。这个新切片的容量是旧切片的二倍（译者注：当超出切片的容量时，append 将会在其内部创建新的数组，该数组的大小是原切片容量的 2 倍。最后 append 返回这个数组的全切片，即从 0 到 length - 1 的切片



挺好的一篇文章 入门不错  https://www.cnblogs.com/liuzhongchao/p/9159896.html



数组

数组是类型相同的元素的集合。例如，整数 5, 8, 9, 79, 76 的集合就构成了一个数组。Go不允许在数组中混合使用不同类型的元素（比如整数和字符串）。

声明

var variable_name [SIZE] variable_type

有很多声明数组的方式，让我们一个一个地介绍。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    var a [3]int //int array with length 3
    fmt.Println(a)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

var a [3]int 声明了一个长度为 3 的整型数组。数组中的所有元素都被自动赋值为元素类型的 0 值。比如这里 a 是一个整型数组，因此 a 中的所有元素都被赋值为 0（即整型的 0 值）。运行上面的程序，输出为：[0 0 0]。

数组的索引从 0 开始到 length - 1 结束。下面让我们给上面的数组赋一些值。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)


func main() {  
    var a [3]int //int array with length 3
    a[0] = 12 // array index starts at 0
    a[1] = 78
    a[2] = 50
    fmt.Println(a)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

a[0]表示数组中的第一个元素。程序的输出为：[12 78 50]。

（译者注：可以用下标运算符（[]）来访问数组中的元素，下标从 0 开始，例如 a[0] 表示数组 a 的第一个元素，a[1]表示数组 a 的第二元素，以此类推。）

可以利用速记声明（shorthand declaration）的方式来创建同样的数组：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main 

import (  
    "fmt"
)

func main() {  
    a := [3]int{12, 78, 50} // shorthand declaration to create array
    fmt.Println(a)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面的程序输出为：[12 78 50]。

（译者注：这个例子给出了速记声明的方式：在数组类型后面加一对大括号（{}），在大括号里面写元素初始值列表，多个值用逗号分隔。）

在速记声明中，没有必要为数组中的每一个元素指定初始值。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    a := [3]int{12} 
    fmt.Println(a)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的第 8 行：a := [3]int{12} 声明了一个长度为 3 的数组，但是只提供了一个初值 12。剩下的两个元素被自动赋值为 0。程序 的输出为：[12 0 0]。

在声明数组时你可以忽略数组的长度并用 ... 代替，让编译器为你自动推导数组的长度。比如下面的程序：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    a := [...]int{12, 78, 50} // ... makes the compiler determine the length
    fmt.Println(a)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面已经提到，数组的长度是数组类型的一部分。因此 [5]int 和 [25]int 是两个不同类型的数组。正是因为如此，一个数组不能动态改变长度。不要担心这个限制，因为切片（slices）可以弥补这个不足。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

func main() {  
    a := [3]int{5, 78, 8}
    var b [5]int
    b = a //not possible since [3]int and [5]int are distinct types
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面程序的第 6 行，我们试图将一个 [3]int 类型的数组赋值给一个 [5]int 类型的数组，这是不允许的。编译会报错：main.go:6: cannot use a (type [3]int) as type [5]int in assignment。

数组是值类型

在 Go 中数组是值类型而不是引用类型。这意味着当数组变量被赋值时，将会获得原数组（译者注：也就是等号右面的数组）的拷贝。新数组中元素的改变不会影响原数组中元素的值。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func main() {  
    a := [...]string{"USA", "China", "India", "Germany", "France"}
    b := a // a copy of a is assigned to b
    b[0] = "Singapore"
    fmt.Println("a is ", a)
    fmt.Println("b is ", b) 
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的第 7 行，将数组 a 的拷贝赋值给数组 b。第 8 行，b 的第一个元素被赋值为 Singapore。这将不会影响到原数组 a。程序的输出为：

a is [USA China India Germany France]  
b is [Singapore China India Germany France]  

同样的，如果将数组作为参数传递给函数，仍然是值传递，在函数中对（作为参数传入的）数组的修改不会造成原数组的改变。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func changeLocal(num [5]int) {  
    num[0] = 55
    fmt.Println("inside function ", num)

}
func main() {  
    num := [...]int{5, 6, 7, 8, 8}
    fmt.Println("before passing to function ", num)
    changeLocal(num) //num is passed by value
    fmt.Println("after passing to function ", num)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的第 13 行，数组 num 是通过值传递的方式传递给函数 changeLocal 的，因此该函数执行过程中不会造成 num的改变。程序输出如下：

before passing to function  [5 6 7 8 8]  
inside function  [55 6 7 8 8]  
after passing to function  [5 6 7 8 8]  

数组的长度

内置函数 len 用于获取数组的长度：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func main() {  
    a := [...]float64{67.7, 89.8, 21, 78}
    fmt.Println("length of a is",len(a))

}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的输出为：length of a is 4。

使用 range 遍历数组

for 循环可以用来遍历数组中的元素：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func main() {  
    a := [...]float64{67.7, 89.8, 21, 78}
    for i := 0; i < len(a); i++ { //looping from 0 to the length of the array
        fmt.Printf("%d th element of a is %.2f\n", i, a[i])
    }
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func main() {  
    a := [...]float64{67.7, 89.8, 21, 78}
    for i := 0; i < len(a); i++ { //looping from 0 to the length of the array
        fmt.Printf("%d th element of a is %.2f\n", i, a[i])
    }
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面的程序使用 for 循环遍历数组中的元素（索引从 0 到 len(a) - 1）。上面的程序输出如下：

0 th element of a is 67.70  
1 th element of a is 89.80  
2 th element of a is 21.00  
3 th element of a is 78.00  

Go 提供了一个更简单，更简洁的遍历数组的方法：使用 range for。range 返回数组的索引和索引对应的值。让我们用 range for 重写上面的程序（除此之外我们还计算了数组元素的总和）。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import "fmt"

func main() {  
    a := [...]float64{67.7, 89.8, 21, 78}
    sum := float64(0)
    for i, v := range a {//range returns both the index and value
        fmt.Printf("%d the element of a is %.2f\n", i, v)
        sum += v
    }
    fmt.Println("\nsum of all elements of a",sum)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面的程序中，第 8 行 for i, v := range a 是 range 形式的 for 循环。range 将返回数组的索引和相对应的元素。我们打印这些值并计算数组 a 中所有元素的总和。程序的输出如下：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

0 the element of a is 67.70  
1 the element of a is 89.80  
2 the element of a is 21.00  
3 the element of a is 78.00

sum of all elements of a 256.5 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

如果你只想访问数组元素而不需要访问数组索引，则可以通过空标识符来代替索引变量：

for _, v := range a { //ignores index  
}

上面的代码忽略了索引。同样的，也可以忽略值。

多维数组

目前为止我们创建的数组都是一维的。也可以创建多维数组。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func printarray(a [3][2]string) {  
    for _, v1 := range a {
        for _, v2 := range v1 {
            fmt.Printf("%s ", v2)
        }
        fmt.Printf("\n")
    }
}

func main() {  
    a := [3][2]string{
        {"lion", "tiger"},
        {"cat", "dog"},
        {"pigeon", "peacock"}, //this comma is necessary. The compiler will complain if you omit this comma
    }
    printarray(a)
    var b [3][2]string
    b[0][0] = "apple"
    b[0][1] = "samsung"
    b[1][0] = "microsoft"
    b[1][1] = "google"
    b[2][0] = "AT&T"
    b[2][1] = "T-Mobile"
    fmt.Printf("\n")
    printarray(b)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面的程序中，第 17 行利用速记声明创建了一个二维数组 a。第 20 行的逗号是必须的，这是因为词法分析器会根据一些简单的规则自动插入分号。如果你想了解更多，请阅读：https://golang.org/doc/effective_go.html#semicolons 。

在第 23 行声明了另一个二维数组 b，并通过索引的方式给数组 b 中的每一个元素赋值。这是初始化二维数组的另一种方式。

第 7 行声明的函数 printarray 通过两个嵌套的 range for 打印二维数组的内容。上面程序的输出为：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

lion tiger  
cat dog  
pigeon peacock 

apple samsung  
microsoft google  
AT&T T-Mobile  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

以上就是对数组的介绍。尽管数组看起来足够灵活，但是数组的长度是固定的，没办法动态增加数组的长度。而切片却没有这个限制，实际上在 Go 中，切片比数组更为常见。

切片

切片（slice）是建立在数组之上的更方便，更灵活，更强大的数据结构。切片并不存储任何元素而只是对现有数组的引用。

创建切片

元素类型为 T 的切片表示为： []T。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    a := [5]int{76, 77, 78, 79, 80}
    var b []int = a[1:4] //creates a slice from a[1] to a[3]
    fmt.Println(b)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

通过 a[start:end] 这样的语法创建了一个从 a[start] 到 a[end -1] 的切片。在上面的程序中，第 9 行 a[1:4] 创建了一个从 a[1] 到 a[3] 的切片。因此 b 的值为：[77 78 79]。

下面是创建切片的另一种方式：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    c := []int{6, 7, 8} //creates and array and returns a slice reference
    fmt.Println(c)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中，第 9 行 c := []int{6, 7, 8} 创建了一个长度为 3 的 int 数组，并返回一个切片给 c。

修改切片

切片本身不包含任何数据。它仅仅是底层数组的一个上层表示。对切片进行的任何修改都将反映在底层数组中。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    darr := [...]int{57, 89, 90, 82, 100, 78, 67, 69, 59}
    dslice := darr[2:5]
    fmt.Println("array before",darr)
    for i := range dslice {
        dslice[i]++
    }
    fmt.Println("array after",darr) 
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的第 9 行，我们创建了一个从 darr[2] 到 darr[5] 的切片 dslice。for 循环将这些元素值加 1。执行完 for 语句之后打印原数组的值，我们可以看到原数组的值被改变了。程序输出如下：

array before [57 89 90 82 100 78 67 69 59]  
array after [57 89 91 83 101 78 67 69 59]

当若干个切片共享同一个底层数组时，对每一个切片的修改都会反映在底层数组中。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    numa := [3]int{78, 79 ,80}
    nums1 := numa[:] //creates a slice which contains all elements of the array
    nums2 := numa[:]
    fmt.Println("array before change 1",numa)
    nums1[0] = 100
    fmt.Println("array after modification to slice nums1", numa)
    nums2[1] = 101
    fmt.Println("array after modification to slice nums2", numa)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

可以看到，在第 9 行， numa[:] 中缺少了开始和结束的索引值，这种情况下开始和结束的索引值默认为 0 和len(numa)。这里 nums1 和 nums2 共享了同一个数组。程序的输出为：

array before change 1 [78 79 80]  
array after modification to slice nums1 [100 79 80]  
array after modification to slice nums2 [100 101 80]  

从输出结果可以看出，当多个切片共享同一个数组时，对每一个切片的修改都将会反映到这个数组中。

切片的长度和容量

切片的长度是指切片中元素的个数。切片的容量是指从切片的起始元素开始到其底层数组中的最后一个元素的个数。

（译者注：使用内置函数 cap 返回切片的容量。）

让我们写一些代码来更好地理解这一点。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    fruitarray := [...]string{"apple", "orange", "grape", "mango", "water melon", "pine apple", "chikoo"}
    fruitslice := fruitarray[1:3]
    fmt.Printf("length of slice %d capacity %d", len(fruitslice), cap(fruitslice)) //length of is 2 and capacity is 6
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中，创建了一个以 fruitarray 为底层数组，索引从 1 到 3 的切片 fruitslice。因此 fruitslice 长度为2。

fruitarray 的长度是 7。fruiteslice 是从 fruitarray 的索引 1 开始的。因此 fruiteslice 的容量是从 fruitarray 的第 1 个元素开始算起的数组中的元素个数，这个值是 6。因此 fruitslice 的容量是 6。程序的输出为：length of slice 2 capacity 6。

切片的长度可以动态的改变（最大为其容量）。任何超出最大容量的操作都会发生运行时错误。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    fruitarray := [...]string{"apple", "orange", "grape", "mango", "water melon", "pine apple", "chikoo"}
    fruitslice := fruitarray[1:3]
    fmt.Printf("length of slice %d capacity %d\n", len(fruitslice), cap(fruitslice)) //length of is 2 and capacity is 6
    fruitslice = fruitslice[:cap(fruitslice)] //re-slicing furitslice till its capacity
    fmt.Println("After re-slicing length is",len(fruitslice), "and capacity is",cap(fruitslice))
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中， 第 11 行修改 fruitslice 的长度为它的容量。上面的程序输出如下：

length of slice 2 capacity 6  
After re-slicing length is 6 and capacity is 6 

用 make 创建切片

内置函数 func make([]T, len, cap) []T 可以用来创建切片，该函数接受长度和容量作为参数，返回切片。容量是可选的，默认与长度相同。使用 make 函数将会创建一个数组并返回它的切片。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    i := make([]int, 5, 5)
    fmt.Println(i)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

用 make 创建的切片的元素值默认为 0 值。上面的程序输出为：[0 0 0 0 0]。

追加元素到切片

我们已经知道数组是固定长度的，它们的长度不能动态增加。而切片是动态的，可以使用内置函数 append 添加元素到切片。append 的函数原型为：append(s []T, x ...T) []T。

x …T 表示 append 函数可以接受的参数个数是可变的。这种函数叫做变参函数。

你可能会问一个问题：(每次添加一个元素)如果切片是建立在数组之上的，而数组本身不能改变长度，那么切片是如何动态改变长度的呢？实际发生的情况是，当新元素通过调用 append 函数追加到切片末尾时，如果超出了容量，append 内部会创建一个新的数组。并将原有数组的元素被拷贝给这个新的数组，最后返回建立在这个新数组上的切片。这个新切片的容量是旧切片的二倍（译者注：当超出切片的容量时，append 将会在其内部创建新的数组，该数组的大小是原切片容量的 2 倍。最后 append 返回这个数组的全切片，即从 0 到 length - 1 的切片）。下面的程序使事情变得明朗：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    cars := []string{"Ferrari", "Honda", "Ford"}
    fmt.Println("cars:", cars, "has old length", len(cars), "and capacity", cap(cars)) //capacity of cars is 3
    cars = append(cars, "Toyota")
    fmt.Println("cars:", cars, "has new length", len(cars), "and capacity", cap(cars)) //capacity of cars is doubled to 6
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中，cars 的容量开始时为 3。在第 10 行我们追加了一个新的元素给 cars，并将 append(cars, "Toyota")的返回值重新复制给 cars。现在 cars 的容量翻倍，变为 6。上面的程序输出为：

cars: [Ferrari Honda Ford] has old length 3 and capacity 3  
cars: [Ferrari Honda Ford Toyota] has new length 4 and capacity 6  

切片的 0 值为 nil。一个 nil 切片的长度和容量都为 0。可以利用 append 函数给一个 nil 切片追加值。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    var names []string //zero value of a slice is nil
    if names == nil {
        fmt.Println("slice is nil going to append")
        names = append(names, "John", "Sebastian", "Vinay")
        fmt.Println("names contents:",names)
    }
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中 names 为 nil，并且我们把 3 个字符串追加给 names。程序的输出为：

slice is nil going to append  
names contents: [John Sebastian Vinay]

可以使用 ... 操作符将一个切片追加到另一个切片末尾：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func main() {  
    veggies := []string{"potatoes","tomatoes","brinjal"}
    fruits := []string{"oranges","apples"}
    food := append(veggies, fruits...)
    fmt.Println("food:",food)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面的程序中，在第10行将 fruits 追加到 veggies 并赋值给 food。...操作符用来展开切片。程序的输出为：food: [potatoes tomatoes brinjal oranges apples]。

切片作为函数参数

可以认为切片在内部表示为如下的结构体：

type slice struct {  
    Length        int
    Capacity      int
    ZerothElement *byte
}

可以看到切片包含长度、容量、以及一个指向首元素的指针。当将一个切片作为参数传递给一个函数时，虽然是值传递，但是指针始终指向同一个数组。因此将切片作为参数传给函数时，函数对该切片的修改在函数外部也可以看到。让我们写一个程序来验证这一点。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func subtactOne(numbers []int) {  
    for i := range numbers {
        numbers[i] -= 2
    }

}
func main() {

    nos := []int{8, 7, 6}
    fmt.Println("slice before function call", nos)
    subtactOne(nos)                               //function modifies the slice
    fmt.Println("slice after function call", nos) //modifications are visible outside

}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面的程序中，第 17 行将切片中的每个元素的值减2。在函数调用之后打印切片的的内容，发现切片内容发生了改变。你可以回想一下，这不同于一个数组，对函数内部的数组所做的更改在函数外不可见。上面的程序输出如下：

array before function call [8 7 6]  
array after function call [6 5 4]  

- 1

- 2

多维切片

同数组一样，切片也可以有多个维度。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)


func main() {  
     pls := [][]string {
            {"C", "C++"},
            {"JavaScript"},
            {"Go", "Rust"},
            }
    for _, v1 := range pls {
        for _, v2 := range v1 {
            fmt.Printf("%s ", v2)
        }
        fmt.Printf("\n")
    }
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

上面程序的输出如下：

C C++  
JavaScript  
Go Rust  

内存优化

切片保留对底层数组的引用。只要切片存在于内存中，数组就不能被垃圾回收。这在内存管理方便可能是值得关注的。假设我们有一个非常大的数组，而我们只需要处理它的一小部分，为此我们创建这个数组的一个切片，并处理这个切片。这里要注意的事情是，数组仍然存在于内存中，因为切片正在引用它。

解决该问题的一个方法是使用 copy 函数 func copy(dst, src []T) int 来创建该切片的一个拷贝。这样我们就可以使用这个新的切片，原来的数组可以被垃圾回收。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

package main

import (  
    "fmt"
)

func countries() []string {  
    countries := []string{"USA", "Singapore", "Germany", "India", "Australia"}
    neededCountries := countries[:len(countries)-2]
    countriesCpy := make([]string, len(neededCountries))
    copy(countriesCpy, neededCountries) //copies neededCountries to countriesCpy
    return countriesCpy
}
func main() {  
    countriesNeeded := countries()
    fmt.Println(countriesNeeded)
}

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753710.jpg)

在上面程序中，第 9 行 neededCountries := countries[:len(countries)-2] 创建一个底层数组为 countries 并排除最后两个元素的切片。第 11 行将 neededCountries 拷贝到 countriesCpy 并在下一行返回 countriesCpy。现在数组countries 可以被垃圾回收，因为 neededCountries 不再被引用。