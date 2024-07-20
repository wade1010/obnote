

```javascript
import (
   "container/list"
   "sync"
)

// Cache is an LRU cache. It is not safe for concurrent access.
type Cache struct {
   // MaxEntries is the maximum number of cache entries before
   // an item is evicted. Zero means no limit.
   MaxEntries int

   // OnEvicted optionally specificies a callback function to be
   // executed when an entry is purged from the cache.
   OnEvicted func(key Key, value interface{})

   ll    *list.List
   cache map[interface{}]*list.Element
   lock  *sync.RWMutex
}

// A Key may be any value that is comparable. See http://golang.org/ref/spec#Comparison_operators
type Key interface{}

type entry struct {
   key   Key
   value interface{}
}

// New creates a new Cache.
// If maxEntries is zero, the cache has no limit and it's assumed
// that eviction is done by the caller.
func New(maxEntries int) *Cache {
   return &Cache{
      MaxEntries: maxEntries,
      ll:         list.New(),
      cache:      make(map[interface{}]*list.Element),
      lock:       &sync.RWMutex{},
   }
}

// Add adds a value to the cache.
func (c *Cache) Add(key Key, value interface{}) {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      c.cache = make(map[interface{}]*list.Element)
      c.ll = list.New()
   }
   if ee, ok := c.cache[key]; ok {
      c.ll.MoveToFront(ee)
      ee.Value.(*entry).value = value
      return
   }
   ele := c.ll.PushFront(&entry{key, value})
   c.cache[key] = ele
   if c.MaxEntries != 0 && c.ll.Len() > c.MaxEntries {
      c.RemoveOldest()
   }
}

// Get looks up a key's value from the cache.
func (c *Cache) Get(key Key) (value interface{}, ok bool) {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      return
   }
   if ele, hit := c.cache[key]; hit {
      c.ll.MoveToFront(ele)
      return ele.Value.(*entry).value, true
   }
   return
}

// Remove removes the provided key from the cache.
func (c *Cache) Remove(key Key) {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      return
   }
   if ele, hit := c.cache[key]; hit {
      c.removeElement(ele)
   }
}

// RemoveOldest removes the oldest item from the cache.
func (c *Cache) RemoveOldest() {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      return
   }
   ele := c.ll.Back()
   if ele != nil {
      c.removeElement(ele)
   }
}

// GetOldest returns the oldest key, value, ok without modifying the lru
func (c *Cache) GetOldest() (key Key, value interface{}, ok bool) {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      return nil, nil, false
   }
   ele := c.ll.Back()
   if ele != nil {
      return ele.Value.(*entry).key, ele.Value.(*entry).value, true
   }
   return nil, nil, false
}

func (c *Cache) removeElement(e *list.Element) {
   c.ll.Remove(e)
   kv := e.Value.(*entry)
   delete(c.cache, kv.key)
   if c.OnEvicted != nil {
      c.OnEvicted(kv.key, kv.value)
   }
}

// Len returns the number of items in the cache.
func (c *Cache) Len() int {
   c.lock.Lock()
   defer c.lock.Unlock()
   if c.cache == nil {
      return 0
   }
   return c.ll.Len()
}
```

