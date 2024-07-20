6.1 memcached 如何实现分布式?

在第 1 章中,我们介绍 memcached 是一个”分布式缓存”,然后 memcached 并不像 mongoDB 那

样,允许配置多个节点,且节点之间”自动分配数据”.

就是说--memcached 节点之间,是不互相通信的.

因此,memcached 的分布式,要靠用户去设计算法,把数据分布在多个 memcached 节点中.



6.2 分布式之取模算法

最容易想到的算法是取模算法,即 N 个节点要,从 0->N-1 编号.

key 对 N 取模,余 i,则 key 落在第 i 台服务器上(图 6.2).

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/B1262557CAA143E5BCC21088F26CD934image.png)



6.3 取模算法对缓存命中率的影响

假设有 8 台服务器, 运行中,突然 down 一台, 则求余的底数变成 7

后果:

key0%8==0, key0%7 ==0 hits

....

key6%8==6, key6%7== 6 hits

key7%8==7, key7%7==0 miss

key9%8==1, key9%7 == 2 miss

...

key55%8 ==7 key55%7 == 6 miss

一般地,我们从数学上归纳之:

有 N 台服务器, 变为 N-1 台,

每 N*(N-1)个数中, 只有(n-1)个单元,%N, %(N-1)得到相同的结果

所以 命中率在服务器 down 的短期内, 急剧下降至 (N-1)/(N*(N-1)) = 1/N

所以: 服务器越多, 则 down 机的后果越严重!



6.4 一致性哈希算法原理

通俗理解一致性哈希:

把各服务器节点映射放在钟表的各个时刻上, 把 key 也映射到钟表的某个时刻上.

该 key 沿钟表顺时针走,碰到的第 1 个节点即为该 key 的存储节点(图 6.4).



![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/30B276C54DF741C5AE83E5F51636174Bimage.png)



疑问 1: 时钟上的指针最大才 11 点,如果我有上百个 memcached 节点怎么办?

答: 时钟只是为了便于理解做的比喻,在实际应用中,我们可以在圆环上分布[0,2^32-1]的数字,

这样,全世界的服务器都可以装下了.

疑问 2: 我该如何把”节点名”,”键名”转化成整数?

答: 你可以用现在的函数,如 crc32().

也可以自己去设计转化规则,但注意转化后的碰撞率要低.

即不同的节点名,转换为相同的整数的概率要低.

6.5 一致性哈希对其他节点的影响

通过图 5.5 可以看出,当某个节点 down 后,只影响该节点顺时针之后的 1 个节点,而其他节点

不受影响.因此，Consistent Hashing 最大限度地抑制了键的重新分布(图 6.5).

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/7D28BDF2E09A46248B5FF200CBDAEB99image.png)



6.6 一致性哈希+虚拟节点对缓存命中率的影响

由图 5.5 中可以看到,理想状态下,

1) 节点在圆环上分配分配均匀,因此承担的任务也平均,但事实上, 一般的 Hash 函数对于节

点在圆环上的映射,并不均匀.

2) 当某个节点 down 后,直接冲击下 1 个节点,对下 1 个节点冲击过大,能否把 down 节点上的

压力平均的分担到所有节点上?

完全可以---引入虚拟节点来达到目标 (图 5.6)

虚拟节点即----N 个真实节点,把每个真实节点映射成 M 个虚拟节点, 再把 M*N 个虚拟节点,

散列在圆环上. 各真实节点对应的虚拟节点相互交错分布

这样,某真实节点 down 后,则把其影响平均分担到其他所有节点上.

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/87A726FAD09B476CB922803222A922BAimage.png)





6.7 一致性哈希的 PHP 实现

```javascript
/*
实现一致性哈希分布的核心功能.
*/
// 需要一个把字符串转成整数的接口
interface hasher {
public function _hash($str);
}
interface distribution {
public function lookup($key);
}
class Consistent implements hasher,distribution {
protected $_nodes = array();
protected $_postion = array();
protected $_mul = 64; //每个节点对应 64 个虚节点
public function _hash($str) {
return sprintf('%u',crc32($str)); // 把字符串转成 32 位符号整数
}
// 核心功能
public function lookup($key) {
$point = $this->_hash($key);
$node = current($this->_postion); //先取圆环上最小的一个节点,当
成结果
foreach($this->_postion as $k=>$v) {
if($point <= $k) {
$node = $v;
break;
}
}
reset($this->_postion);
}
public function addNode($node) {
if(isset($this->nodes[$node])) {
return;
}
for($i=0; $i<$this->_mul; $i++) {
$pos = $this->_hash($node . '-' . $i);
$this->_postion[$pos] = $node;
$this->_nodes[$node][] = $pos;
}
$this->_sortPos();
}
// 循环所有的虚节点,谁的值==指定的真实节点 ,就把他删掉
public function delNode($node) {
if(!isset($this->_nodes[$node])) {
return;
}
foreach($this->_nodes[$node] as $k) {
unset($this->_postion[$k]);
}
unset($this->_nodes[$node]);
}
protected function _sortPos() {
ksort($this->_postion,SORT_REGULAR);
}
}
// 测试
$con = new Consistent();
$con->addNode('a');
$con->addNode('b');
$con->addNode('c');
$key = 'www.zixue.it';
echo '此 key 落在',$con->lookup($key),'号节点';
```

