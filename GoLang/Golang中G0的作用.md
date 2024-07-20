在Golang中 g0作为一个特殊的goroutine，为 scheduler 执行调度循环提供了场地（栈）。对于一个线程来说，g0 总是它第一个创建的 goroutine。



之后，它会不断地寻找其他普通的 goroutine 来执行，直到进程退出。



当需要执行一些任务，且不想扩栈时，就可以用到 g0 了，因为 g0 的栈比较大。



g0 其他的一些“职责”有：创建 goroutine、deferproc 函数里新建 _defer、垃圾回收相关的工作（例如 stw、扫描 goroutine 的执行栈、一些标识清扫的工作、栈增长）等等。