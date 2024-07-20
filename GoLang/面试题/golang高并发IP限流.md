在一个高并发的web服务器中，要限制IP的频繁访问。现模拟100个IP同时并发访问服务器，每个IP要重复访问1000次。

每个IP10秒之内只能访问一次。修改以下代码完成该过程，要求能成功输出 success:100



```javascript
package main

import (
   "context"
   "fmt"
   "sync"
   "sync/atomic"
   "time"
)

type Ban struct {
   visitIPs map[string]time.Time
   lock     *sync.RWMutex
}

func NewBan(ctx context.Context) *Ban {
   ban := &Ban{
      visitIPs: make(map[string]time.Time),
      lock:     &sync.RWMutex{},
   }
   go func() {
      timer := time.NewTimer(time.Second * 10)
      for {
         select {
         case <-timer.C:
            ban.lock.Lock()
            for k, t := range ban.visitIPs {
               if time.Now().Sub(t) > 10 {
                  delete(ban.visitIPs, k)
               }
            }
            ban.lock.Unlock()
            timer.Reset(time.Second * 10)
         case <-ctx.Done():
            return
         }
      }
   }()
   return ban
}
func (b *Ban) visit(ip string) bool {
   b.lock.Lock()
   defer b.lock.Unlock()
   if _, ok := b.visitIPs[ip]; ok {
      return true
   }
   b.visitIPs[ip] = time.Now()
   return false
}
func main() {
   ctx, cancel := context.WithCancel(context.Background())
   defer cancel()
   ban := NewBan(ctx)
   for {
      go func() {
         var wg sync.WaitGroup
         wg.Add(100000)
         success := int64(0)
         for i := 0; i < 1000; i++ {
            for j := 0; j < 100; j++ {
               go func(j int) {
                  ip := fmt.Sprintf("192.168.1.%d", j)
                  if !ban.visit(ip) {
                     atomic.AddInt64(&success, 1)
                  }
                  wg.Done()
               }(j)
            }
         }
         wg.Wait()
         fmt.Println(success)
      }()
      time.Sleep(time.Second * 2)
   }
}
```

