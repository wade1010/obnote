
```
1. 各种计数，商品维度计数和用户维度计数
说起电商，肯定离不开商品，而附带商品有各种计数（喜欢数，评论数，鉴定数，浏览数,etc），Redis的命令都是原子性的，你可以轻松地利用INCR，DECR等命令来计数。

商品维度计数（喜欢数，评论数，鉴定数，浏览数,etc）
采用Redis 的类型: Hash. 如果你对redis数据类型不太熟悉，可以参考 http://redis.io/topics/data-types-intro

为product定义个key product:，为每种数值定义hashkey, 譬如喜欢数xihuan

redis> HSET product:1231233 xihuan 5
(integer) 1
redis> HINCRBY product:1231233 xihuan 1 //喜欢数+1
(integer) 6 
redis> HGETALL product:1231233 //获取这key hashkey 和value
1) "xihuan"
2) "6"
用户维度计数（动态数、关注数、粉丝数、喜欢商品数、发帖数 等）
用户维度计数同商品维度计数都采用 Hash. 为User定义个key user:，为每种数值定义hashkey, 譬如关注数follow

redis> HSET user:100000 follow 5
(integer) 1
redis> HINCRBY user:100000 follow 1 //关注数+1
(integer) 6 
redis> HGETALL user:100000 //获取这key hashkey 和value
1) "follow"
2) "6"
2. 存储社交关系
譬如将用戶的好友/粉丝/关注，可以存在一个sorted set中，score可以是timestamp，这样求两个人的共同好友的操作，可能就只需要用求交集命令即可。

redis> ZADD user:100000:follow 61307510400000 "100001" //score 为timestamp
(integer) 1
redis> ZADD user:100000:follow 61307510402300 "100002"
(integer) 1
redis> ZADD user:100000:follow 61307510405600 "100003"
(integer) 1
redis> ZADD user:200000:follow 61307510400000 "100001"
(integer) 1
redis> ZADD user:200000:follow 61307510402300 "100005"
(integer) 1
redis> ZADD user:200000:follow 61307510405600 "100004"
(integer) 1
redis> ZINTERSTORE out:100000:200000 1 user:100000:follow user:200000:follow //交集命令，获得共同关注
(integer) 2
redis> ZRANGE out:100000:200000 0 -1
1) "100001"
3. 用作缓存代替memcached（商品列表，评论列表，@提示列表，etc）
相对memcached 简单的key-value存储来说，redis众多的数据结构（list,set,sorted set,hash, etc）可以更方便cache各种业务数据，性能也不亚于memcached。

NOTE: RPUSH pagewviews.user: EXPIRE pagewviews.user: 60 //注意要update timeout

4. 反spam系统（评论，发布商品，论坛发贴，etc）
作为一个电商网站被各种spam攻击是少不免（垃圾评论、发布垃圾商品、广告、刷自家商品排名等），针对这些spam制定一系列anti-spam规则，其中有些规则可以利用redis做实时分析，譬如：1分钟评论不得超过2次、5分钟评论少于5次等（更多机制/规则需要结合drools ）。 采用sorted set将最近一天用户操作记录起来（为什么不全部记录？节省memory，全部操作会记录到log，后续利用hadoop进行更全面分析统计），通过ZRANGEBYSCORE user:200000:operation:comment 61307510405600 +inf 获得1分钟内的操作记录， redis> ZADD user:200000:operation:comment 61307510402300 "这是一条评论" //score 为timestamp (integer) 1 redis> ZRANGEBYSCORE user:200000:operation:comment 61307510405600 +inf//获得1分钟内的操作记录 1) "这是一条评论"

BTW, 更复杂一点的实时计算可以采用Storm。

5. 用户Timeline/Feeds
在逛 有个类似微博的栏目我关注，里面包括关注的人、主题、品牌的动态。redis在这边主要当作cache使用。

redis> ZADD user:100000:feed:topic  61307510400000 <feedId> //score 为timestamp
(integer) 1
redis> EXPIRE user:100000:feed:topic 24*60*60 //set timeout to one day
(integer) 1
redis> ZADD user:100000:feed:friend 61307510400000 <feedId> //不同类型feed
(integer) 1
redis> EXPIRE user:100000:feed:friend 24*60*60 //set timeout
(integer) 1
6. 最新列表&排行榜（用户刚刚喜欢的商品，etc）
这里采用Redis的List数据结构或sorted set 结构, 方便实现最新列表or排行榜 等业务场景。

7. 消息通知
其实这业务场景也可以算在计数上，也是采用Hash。如下：

redis> HSET user:<userId>:message:ur system 1//1条未读系统消息
(integer) 1
redis> HINCRBY user:<userId>:message:ur system 1 //未读系统消息+1
(integer) 2
redis> HINCRBY user:<userId>:message:ur comment 1 //未读评论消息+1
(integer) 1
redis> HSET user:<userId>:message:ur system 0//设为系统消息已读
(integer) 1
redis> HGETALL user:<userId>:message:ur //获取这key hashkey 和value
1) "system"
2) "0"
3) "comment"
4) "1"
8. 将Redis用作消息队列
当在集群环境时候，java ConcurrentLinkedQueue 就无法满足我们需求，此时可以采用Redis的List数据结构实现分布式的消息队列。
```


转自

http://kenny7.com/2012/09/redis-usage-scenario.html