

```javascript
package main

import (
   "fmt"
   "math/rand"
   "strconv"
   "sync"
   "time"
)

type sp interface {
   Out(key string, val interface{})                  //存入key /val，如果该key读取的goroutine挂起，则唤醒。此方法不会阻塞，时刻都可以立即执行并返回
   Rd(key string, timeout time.Duration) interface{} //读取一个key，如果key不存在阻塞，等待key存在或者超时
}
type Map struct {
   c   map[string]*entry
   rmx *sync.RWMutex
}
type entry struct {
   ch      chan struct{}
   value   interface{}
   isExist bool
}

func (m *Map) Out(key string, val interface{}) {
   m.rmx.Lock()
   defer m.rmx.Unlock()
   if e, ok := m.c[key]; ok {
      e.value = val
      if !e.isExist {
         close(e.ch)
      }
      e.isExist = true
   } else {
      e = &entry{ch: make(chan struct{}), isExist: true, value: val}
      m.c[key] = e
      close(e.ch)
   }
}

func (m *Map) Rd(key string, timeout time.Duration) interface{} {
   m.rmx.Lock()
   if e, ok := m.c[key]; ok && e.isExist {
      m.rmx.Unlock()
      return e.value
   } else {
      if !ok {
         e = &entry{ch: make(chan struct{}), isExist: false}
         m.c[key] = e
      }
      m.rmx.Unlock()
      fmt.Println("协程阻塞 -> ", key)
      select {
      case <-e.ch:
         return e.value
      case <-time.After(timeout):
         fmt.Println("协程超时 -> ", key)
         return nil
      }
   }
}
func main() {
   m := Map{}
   m.c = make(map[string]*entry)
   m.rmx = &sync.RWMutex{}
   for i := 0; i < 1000; i++ {
      go func() {
         rand.Seed(time.Now().UnixNano())
         key := rand.Intn(100)
         m.Out(strconv.Itoa(key), i)
         key2 := rand.Intn(100)
         fmt.Println(m.Rd(strconv.Itoa(key2), time.Second*3))
      }()
   }
   time.Sleep(time.Second*10)
}
```

