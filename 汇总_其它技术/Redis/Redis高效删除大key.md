一、大key的删除问题

大key（bigkey）是指 key 的 value 是个庞然大物，例如 Hashes, Sorted Sets, Lists, Sets，日积月累之后，会变得非常大，可能几十上百MB，甚至到GB。

如果对这类大key直接使用 del 命令进行删除，会导致长时间阻塞，甚至崩溃。

因为 del 命令在删除集合类型数据时，时间复杂度为 O(M)，M 是集合中元素的个数。

Redis 是单线程的，单个命令执行时间过长就会阻塞其他命令，容易引起雪崩。

二、解决方案

不可靠方案：

- 空闲时间删除，如凌晨3-4点删除

可靠方案：

- 渐进式删除

- UNLINK (4.0版本以后)

1. 渐进式删除

思路：

分批删除，通过 scan 命令遍历大key，每次取得少部分元素，对其删除，然后再获取和删除下一批元素。

示例：

- 删除大 Hashes

步骤：

（1）key改名，相当于逻辑上把这个key删除了，任何redis命令都访问不到这个key了

（2）小步多批次的删除

伪代码：

```javascript
# key改名
newkey = "gc:hashes:" + redis.INCR( "gc:index" )
redis.RENAME("my.hash.key", newkey)

# 每次取出100个元素删除
cursor = 0
loop
  cursor, hash_keys = redis.HSCAN(newkey, cursor, "COUNT", 100)
  if hash_keys count > 0
    redis.HDEL(newkey, hash_keys)
  end
  if cursor == 0
    break
  end
end
```

- 删除大 Lists

伪代码：

```javascript
# key改名
newkey = "gc:hashes:" + redis.INCR("gc:index")
redis.RENAME("my.list.key", newkey)

# 删除
while redis.LLEN(newkey) > 0
  redis.LTRIM(newkey, 0, -99)
end
```

- 删除大 Sets

伪代码：

```javascript
# key改名
newkey = "gc:hashes:" + redis.INCR("gc:index")
redis.RENAME("my.set.key", newkey)

# 每次删除100个成员
cursor = 0
loop
  cursor, members = redis.SSCAN(newkey, cursor, "COUNT", 100)
  if size of members > 0
    redis.SREM(newkey, members)
  end
  if cursor == 0
    break
  end
end
```

- 删除大 Sorted Sets

伪代码：

```javascript
# key改名
newkey = "gc:hashes:" + redis.INCR("gc:index")
redis.RENAME("my.zset.key", newkey)

# 删除
while redis.ZCARD(newkey) > 0
  redis.ZREMRANGEBYRANK(newkey, 0, 99)
end
```

2. UNLINK

Redis 4.0 推出了一个重要命令 UNLINK，用来拯救 del 删大key的困境。

UNLINK 工作思路：

（1）在所有命名空间中把 key 删掉，立即返回，不阻塞。

（2）后台线程执行真正的释放空间的操作。

UNLINK 基本可以替代 del，但个别场景还是需要 del 的，例如在空间占用积累速度特别快的时候就不适合使用UNLINK，因为 UNLINK 不是立即释放空间。

三、总结

- 使用 del 删除大key可能会造成长时间阻塞，甚至崩溃。

- 可以使用渐进式删除，对 Hashes, Sorted Sets, Lists, Sets 分别处理，思路相同，先逻辑删除，对key改名，使客户端无法使用原key，然后使用批量小步删除。

- 4.0版本以后可以使用 UNLINK 命令，后台线程释放空间。