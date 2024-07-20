单元测试

概述

单元测试（unit testing），是指对软件中的最小可测试单元进行检查和验证。对于单元测试中单元的含义，一般要根据实际情况去判定其具体含义，如C语言中单元指一个函数，Java里单元指一个类，图形化的软件中可以指一个窗口或一个菜单等。总的来说，单元就是人为规定的最小的被测功能模块。

单元测试是在软件开发过程中要进行的最低级别的测试活动，软件的独立单元将在与程序的其他部分相隔离的情况下进行测试。

testing 提供对 Go 包的自动化测试的支持。通过 go test 命令，能够自动执行如下形式的任何函数：

func TestXxx(*testing.T)


- 测试用例文件不会参与正常源码编译，不会被包含到可执行文件中。

- 测试用例文件使用 go test 指令来执行，没有也不需要 main() 作为函数入口。所有在以_test结尾的源码内以Test开头的函数会自动被执行。

- 测试用例可以不传入 *testing.T 参数。

在这些函数中，使用 Error, Fail 或相关方法来发出失败信号。

要编写一个新的测试套件，需要创建一个名称以 _test.go 结尾的文件，该文件包含 TestXxx 函数，如上所述。 将该文件放在与被测试的包相同的包中。该文件将被排除在正常的程序包之外，但在运行 “go test” 命令时将被包含。 有关详细信息，请运行 “go help test” 和 “go help testflag” 了解。

如果有需要，可以调用 *T 和 *B 的 Skip 方法，跳过该测试或基准测试：

func TestTimeConsuming(t *testing.T) {
    if testing.Short() {
        t.Skip("skipping test in short mode.")
    }
    ...
}


Go语言的单元测试对文件名和方法名，参数都有很严格的要求。

1. 文件名必须以xxx_test.go命名

1. 方法必须是Test[^a-z]开头

1. *方法参加必须 t testing.T

1. 使用go test执行单元测试

go test参数解读

go test是go语言自带的测试工具，其中包含的是两类，单元测试和性能测试

通过go help test可以看到go test的使用说明：

格式形如:

go test [-c] [-i] [build/test flags] [packages] [build/test flags & test binary flags]


参数解读:

-c : 编译go test成为可执行的二进制文件，但是不运行测试。

-i : 安装测试包依赖的package，但是不运行测试。

关于build flags，调用go help build，这些是编译运行过程中需要使用到的参数，一般设置为空

关于packages，调用go help packages，这些是关于包的管理，一般设置为空

关于flags for test binary，调用go help testflag，这些是go test过程中经常使用到的参数

-test.v : 是否输出全部的单元测试用例（不管成功或者失败），默认没有加上，所以只输出失败的单元测试用例。

-test.run pattern: 只跑哪些单元测试用例

-test.bench patten: 只跑那些性能测试用例

-test.benchmem : 是否在性能测试的时候输出内存情况

-test.benchtime t : 性能测试运行的时间，默认是1s

-test.cpuprofile cpu.out : 是否输出cpu性能分析文件

-test.memprofile mem.out : 是否输出内存性能分析文件

-test.blockprofile block.out : 是否输出内部goroutine阻塞的性能分析文件

-test.memprofilerate n : 内存性能分析的时候有一个分配了多少的时候才打点记录的问题。这个参数就是设置打点的内存分配间隔，也就是profile中一个sample代表的内存大小。默认是设置为512 * 1024的。如果你将它设置为1，则每分配一个内存块就会在profile中有个打点，那么生成的profile的sample就会非常多。如果你设置为0，那就是不做打点了。

你可以通过设置memprofilerate=1和GOGC=off来关闭内存回收，并且对每个内存块的分配进行观察。

-test.blockprofilerate n: 基本同上，控制的是goroutine阻塞时候打点的纳秒数。默认不设置就相当于-test.blockprofilerate=1，每一纳秒都打点记录一下

-test.parallel n : 性能测试的程序并行cpu数，默认等于GOMAXPROCS。

-test.timeout t : 如果测试用例运行时间超过t，则抛出panic

-test.cpu 1,2,4 : 程序运行在哪些CPU上面，使用二进制的1所在位代表，和nginx的nginx_worker_cpu_affinity是一个道理

-test.short : 将那些运行时间较长的测试用例运行时间缩短


示例:

定义一个test包，包内有一个加法和减法的函数

package test

func Sum(a int, b int) int {
	return a + b
}

func Sub(a int, b int) int {
	return a - b
}


测试文件的名称不需要和包文件名称一样，也可以叫abc_test.go等

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753015.jpg)

测试文件test_test.go的测试代码如下

package test

import (
	"testing"
)

//编写一个测试用例，去测试Sum函数是否正确

func TestSum(t *testing.T) {

	res := Sum(10, 20)

	if res != 30 {
		t.Fatalf("Sum(10, 20)执行错误")
	}

	//如果正确，输出日志
	t.Logf("Sum(10, 20)执行正确")
}

func TestOk(t *testing.T) {

	t.Logf("这个方法也进来啦")
}

func TestSub(t *testing.T) {

	res := Sub(10, 5)

	if res != 5 {
		t.Fatalf("Sub(10, 5)执行错误")
	}

	//如果正确，输出日志
	t.Logf("Sub(10, 5)执行正确")
}


![](https://gitee.com/hxc8/images7/raw/master/img/202407190753127.jpg)

在终端进入该包的目录内，使用go test命令进行测试

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753610.jpg)

加上-v参数可以让测试时显示详细的流程。

我们再新建一个abc_test.go文件，把test_test.go文件里的TestSub()方法挪到abc_test.go文件中

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753719.jpg)

然后重新执行go test -v命令

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753084.jpg)

可以看到test_test.go内的方法都被执行了，由此可知，使用go test -v命令会把该包目录内的所有测试文件的所有方法都执行一遍。

如果想测试包里面的单个文件，一定要带上被测试的原文件，如

go test -v abc_test.go test.go


![](https://gitee.com/hxc8/images7/raw/master/img/202407190753124.jpg)

如果想测试单个方法，需要加上 -run参数,并且方法名末尾要加上$，原因是-run跟随的测试用例的名称支持正则表达式，使用-run TestSub$即可只执行 TestSub 测试用例。否则会执行所有以TestSub开头的所有函数如，修改abc_test.go

package test

import (
	"testing"
)

//编写一个测试用例，去测试Sum函数是否正确

func TestSum(t *testing.T) {

	res := Sum(10, 20)

	if res != 30 {
		t.Fatalf("Sum(10, 20)执行错误")
	}

	//如果正确，输出日志
	t.Logf("Sum(10, 20)执行正确")
}

func TestOk(t *testing.T) {

	t.Logf("这个方法也进来啦")
}


func TestSub2(t *testing.T) {

	t.Logf("进入到TestSub2方法里来了")
}


go test -v -test.run TestSub$
或
go test -v -run TestSub$


![](https://gitee.com/hxc8/images7/raw/master/img/202407190753124.jpg)

如果不加$,所有以TestSub开头的所有测试函数都会被执行

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753124.jpg)

单元测试日志

每个测试用例可能并发执行，使用 testing.T 提供的日志输出可以保证日志跟随这个测试上下文一起打印输出。testing.T 提供了几种日志输出方法，详见下表所示。

单元测试框架提供的日志方法

| 方 法 | 备 注 |
| - | - |
| Log | 打印日志，同时结束测试 |
| Logf | 格式化打印日志，同时结束测试 |
| Error | 打印错误日志，同时结束测试 |
| Errorf | 格式化打印错误日志，同时结束测试 |
| Fatal | 打印致命日志，同时结束测试 |
| Fatalf | 格式化打印致命日志，同时结束测试 |


基准测试

基准测试可以测试一段程序的运行性能及耗费 CPU 的程度。Go 语言中提供了基准测试框架，使用方法类似于单元测试，使用者无须准备高精度的计时器和各种分析工具，基准测试本身即可以打印出非常标准的测试报告。

压力测试用来检测函数(方法）的性能，和编写单元功能测试的方法类似，但需要注意以下几点：

1. 压力测试用例必须遵循如下格式，其中XXX可以是任意字母数字的组合，但是首字母不能是小写字母

func BenchmarkXXX(b *testing.B) { ... }


1. go test不会默认执行压力测试的函数，如果要执行压力测试需要带上参数-test.bench，语法:-test.bench="test_name_regex",例如go test -test.bench=".*"表示测试全部的压力测试函数

1. 在压力测试用例中,请记得在循环体内使用testing.B.N,以使测试可以正常的运行

1. 文件名也必须以_test.go结尾

基础测试基本使用

新建一个压力测试文件bench_test.go

目录结构

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753124.jpg)

bench_test.go代码

package test

import "testing"

func BenchmarkSub(b *testing.B) {

	for i := 0; i < b.N; i++ { //use b.N for looping
		Sub(10, 5)
	}
}

func BenchmarkTimeConsumingFunction(b *testing.B) {
	
	b.StopTimer() //调用该函数停止压力测试的时间计数
	
	//做一些初始化的工作，例如读取文件数据，数据库连接之类的，
	//这样这些时间不影响我们测试函数本身的性能
	
	b.StartTimer() //重新开始时间
	
	for i := 0; i < b.N; i++ {
		Sub(10, 5)
	}
}


这段代码使用基准测试框架测试减法性能。第 7 行中的 b.N 由基准测试框架提供。测试代码需要保证函数可重入性及无状态，也就是说，测试代码不使用全局变量等带有记忆性质的数据结构。避免多次运行同一段代码时的环境不一致，不能假设 N 值范围。

执行命令

go test -test.bench=".*"


输出结果：

goos: darwin
goarch: amd64
pkg: test
BenchmarkSub-8                          2000000000               0.32 ns/op
//BenchmarkSub执行了2000000000次,每次的执行平均时间是0.32纳秒
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
//BenchmarkTimeConsumingFunction,执行了2000000000次，每次的执行平均时间是0.31纳秒
PASS
ok      test    1.328s


我们还可以使用-count执行次数

go test -test.bench=".*" -count=5


输出结果：

goos: darwin
goarch: amd64
pkg: test
BenchmarkSub-8                          2000000000               0.31 ns/op
BenchmarkSub-8                          2000000000               0.32 ns/op
BenchmarkSub-8                          2000000000               0.32 ns/op
BenchmarkSub-8                          2000000000               0.31 ns/op
BenchmarkSub-8                          2000000000               0.31 ns/op
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
BenchmarkTimeConsumingFunction-8        2000000000               0.31 ns/op
PASS
ok      test    6.573s


基准测试原理

基准测试框架对一个测试用例的默认测试时间是 1 秒。开始测试时，当以 Benchmark 开头的基准测试用例函数返回时还不到 1 秒，那么 testing.B 中的 N 值将按 1、2、5、10、20、50……递增，同时以递增后的值重新调用基准测试用例函数。

自定义测试时间

通过-benchtime参数可以自定义测试时间，例如：

go test -test.bench=".*" -count=5 -benchtime=5s


测试内存

基准测试可以对一段代码可能存在的内存分配进行统计，下面是一段使用字符串格式化的函数，内部会进行一些分配操作。

func Benchmark_Alloc(b *testing.B) {
  
    for i := 0; i < b.N; i++ {
        fmt.Sprintf("%d", i)
    }
}


在命令行中添加-benchmem参数以显示内存分配情况，参见下面的指令：

bogon:test itbsl$ go test -test.bench=Alloc -benchmem
goos: darwin
goarch: amd64
pkg: test
BenchmarkAlloc-8        20000000               111 ns/op              16 B/op          2 allocs/op
PASS
ok      test    2.354s


代码说明如下：

- 第 1 行的代码中-bench后添加了 Alloc，指定只测试 Benchmark_Alloc() 函数。

- 第 4 行代码的“16 B/op”表示每一次调用需要分配 16 个字节，“2 allocs/op”表示每一次调用有两次分配。

开发者根据这些信息可以迅速找到可能的分配点，进行优化和调整。

控制计时器

有些测试需要一定的启动和初始化时间，如果从 Benchmark() 函数开始计时会很大程度上影响测试结果的精准性。testing.B 提供了一系列的方法可以方便地控制计时器，从而让计时器只在需要的区间进行测试。我们通过下面的代码来了解计时器的控制。

基准测试中的计时器控制

func BenchmarkAddTimerControl(b *testing.B) {
	
	// 重置计时器
	b.ResetTimer()
	
	// 停止计时器
	b.StopTimer()
	
	// 开始计时器
	b.StartTimer()
	
	var n int
	for i := 0; i < b.N; i++ {
		n++
	}
}


从 Benchmark() 函数开始，Timer 就开始计数。StopTimer() 可以停止这个计数过程，做一些耗时的操作，通过 StartTimer() 重新开始计时。ResetTimer() 可以重置计数器的数据。

计数器内部不仅包含耗时数据，还包括内存分配的数据。