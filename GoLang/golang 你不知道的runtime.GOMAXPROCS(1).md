网上有一道关于多个协程的执行顺序的题目。 下面的代码会输出什么，并说明原因



runtime.GOMAXPROCS(1)
	wg := sync.WaitGroup{}
	wg.Add(20)
	for i := 0; i < 10; i++ {
		go func() {
			fmt.Println("A: ", i)
			wg.Done()
		}()
	}
	for i := 0; i < 10; i++ {
		go func(i int) {
			fmt.Println("B: ", i)
			wg.Done()
		}(i)
	}
	wg.Wait()




这道题的参考答案是：“打印的顺序是随机的。 但是A:均为输出10，B:从0~9输出(顺序不定)。”

A的输出是10，B是0-9，这个是变量的作用域不同引起的，这个很好理解。但是输出顺序是不是随机的呢？经过验证，当runtime.[GOMAXPROCS]时，即只有1个操作系统线程可供用户的Go代码使用时，多个协程的执行顺序是固定不变的，具体顺序跟不同的Go版本的具体实现有关。



为了方便说明问题，在wg.Wait()前后各加一句打印输出：



fmt.Printf("---main end loop---\n") 
wg.Wait() 
fmt.Printf("---  main exit  ---\n")




例如在Go1.13.8和Go1.14.6输出顺序固定为：



---main end loop---
B:  9
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
B:  0
B:  1
B:  2
B:  3
B:  4
B:  5
B:  6
B:  7
B:  8
---  main exit  ---




而在更早期的版本，如果Go1.4的输出顺序则固定为：



---main end loop---
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
A:  10
B:  0
B:  1
B:  2
B:  3
B:  4
B:  5
B:  6
B:  7
B:  8
B:  9
---  main exit  ---




可以看到“---main end loop---”总是最先输出，表明在1个操作系统线程的情况下，只有main协程执行到wg.Wait()阻塞等待时，其子协程才能被执行，而子协程的执行顺序正好对应于它们入队列的顺序。

其中Go1.13.8和Go1.14.6，在实现上和早期版本有一点不同，每增加一个子协程就把其对应的函数地址存放到”_p_.runnext“，而把”_p_.runnext“原来的地址（即上一个子协程对应的函数地址）移动到队列”_p_.runq“里面，这样当执行到wg.Wait()时，”_p_.runnext“存放的就是最后一个子协程对应的函数地址（即输出B: ９的那个子协程）。

当开始执行子协程对应的函数时，首先执行”_p_.runnext“对应的函数，然后按先进先出的顺序执行队列”_p_.runq“里的函数。所以这就解释了为什么总是B：9打在第一个，而后面打印的则是进入队列的顺序。



相关源码：$GOROOT/src/runtime/proc.go

入队列：

// runqput tries to put g on the local runnable queue.// If next is false, runqput adds g to the tail of the runnable queue.// If next is true, runqput puts g in the _p_.runnext slot.// If the run queue is full, runnext puts g on the global queue.// Executed only by the owner P.
func runqput(_p_ *p, gp *g, next bool) { 
       if randomizeScheduler && next && fastrand()%2 == 0 { 
               next = false 
       }
       if next {
          retryNext:oldnext := _p_.runnext//把子协程gp对应的函数地址赋值给_p_.runnext
          if !_p_.runnext.cas(oldnext, guintptr(unsafe.Pointer(gp))) { 
                goto retryNext
          }
          if oldnext == 0 {
                return
          }
         // Kick the old runnext out to the regular run queue. 
         //从这句以下都是把_p_.runnext原来存放的上一个子协程的函数地址放入队列_p_.runq
          gp = oldnext.ptr()
        } 
        retry:h := atomic.LoadAcq(&_p_.runqhead) // load-acquire, synchronize with consumers
        t := _p_.runqtail
        if t-h < uint32(len(_p_.runq)) {
                _p_.runq[t%uint32(len(_p_.runq))].set(gp) 
               atomic.StoreRel(&_p_.runqtail, t+1) // store-release, makes the item available for consumption 
               return
        }
        //print("runqput call runqputslow goid=", gp.goid, "\n")
        if runqputslow(_p_, gp, h, t) {
                return
        }
        // the queue is not full, now the put above must succeed
        goto retry
}




出队列：

// Get g from local runnable queue.
// If inheritTime is true, gp should inherit the remaining time in the
// current time slice. Otherwise, it should start a new time slice.
// Executed only by the owner P.
func runqget(_p_ *p) (gp *g, inheritTime bool) {
        // If there's a runnext, it's the next G to run.
        //第一个For循环是优先返回_p_.runnext对应的子协程函数地址, 返回之前会把_p_.runnext赋值为0，后续会break到下面第二个For循环
        for {
                next := _p_.runnext
                if next == 0 {
                        break
                }
                 if _p_.runnext.cas(next, 0) {
                        return next.ptr(), true
                }
        }
        //按先进先出顺序返回队列_p_.runq里面存储的子协程函数地址
        for {
                h := atomic.LoadAcq(&_p_.runqhead) // load-acquire, synchronize with other consumers
                t := _p_.runqtail
                if t == h {
                        return nil, false
                }
                gp := _p_.runq[h%uint32(len(_p_.runq))].ptr()
                if atomic.CasRel(&_p_.runqhead, h, h+1) { // cas-release, commits consume
                        return gp, false
                }
        }
}