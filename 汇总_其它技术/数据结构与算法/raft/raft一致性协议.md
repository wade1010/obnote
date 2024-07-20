[https://github.com/maemual/raft-zh_cn/blob/master/raft-zh_cn.md](https://github.com/maemual/raft-zh_cn/blob/master/raft-zh_cn.md)

一、raft概念

在分布式系统中，一致性是比较常见的概念，所谓一致性指的是集群中的多个节点在状态上达成一致。

在程序和操作系统不会崩溃、硬件不会损坏、服务器不会掉电、网络绝对可靠且没有延迟的理想情况下，我们可以将急群众的多个节点看作一个整体，此时要保证它们的一致性并不困难。

但是在现实场景中，很难保证上述极端的条件全部满足，节点之间的一致性也就很难保证，这样就需要Paxos、Raft等一致性协议。

一致性协议可以保证集群中大部分节点可用的情况下，集群依然可以工作并给出一个正确的结果，从而保证依赖于该集群的其它服务不受影响。

raft算法是保证多个服务器节点数据一致性的共识算法，它是受到Paxos的影响而产生的。相对于Paxos而言，Raft共识算法更加简单易懂。

在Raft共识算法中，所有的数据变化都是以日志的形式记录在服务节点中，日志包含索引值、任期值等信息。

其中，索引值表示日志的序号，任期值表示生成该索引值时，raft leader对应的任期值。服务节点会不断的读取日志记录，并将日志记录更新到服务节点的数据中。

其中Follower和Leader是Raft共识算法中常见的两种角色状态:

Follower:状态为跟随者，负责被动接收数据；

Leader状态为领导者，负责处理所有客户端的交互，日志复制等 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011519.jpg)

在raft协议中，每个节点都维护一个状态机，该状态机有3种状态，分别是Leader状态、Candidate状态和Follower状态。在任意时刻集群中的任意一个节点都处于这3个状态之一。在大多数情况下，集群中只有一个Leader,其它节点都处于follower状态。

Leader节点：

第一主要负责处理所有客户端的请求，当接收到客户端的写入请求时，Leader节点会对写入的请求追加一条日志，然后将其封装成消息，发送到集群中的Follower节点，当Follower节点收到该消息时，会对其进行响应，如果集群中有大多数节点收到该日志记录时，那么Leader节点认为这条日志记录已经提交，可以向客户端返回响应。

第二就是向集群中其它节点发送心跳消息，这主要是防止集群中其它节点选举计时器超时触发新一轮选举。

Follower节点：

Follower节点不会发送任何请求，它们只是简单的响应来自Leader或者Candidate的请求，Follower节点不会处理客户端的请求，而是将请求重定向给集群中的Leader节点进行处理。

Candidate节点：

是由Follower节点转化而来，Follower节点长时间没收到Leader节点发送的心跳消息时，该节点的选举计时器就会过期，然后将自身状态转化成Candidate，发起新一轮选举。

在上图所示图例中，Raft集群初始化后，所有节点均为Folower节点，当某个Follower节点超时后便会触发选举，并切换成Candidate，Candidate会向其它节点发送投票消息，若能收到大多数节点的选票，则切换成Leader。若发现已有Leader或没有成为新任期的Leader，则切换成为Follower。若在选举计时器选举范围内没收到大多数的选票，那么就会触发新一轮选举。

场景一、

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011077.jpg)

场景二、

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011416.jpg)

三种情况进行leader选举

1、在平时，leader发送heartbeat给所有follower，表示自己还健康，如果其中某个follower没有收到heatbeat，它就会认为我的leader已经宕掉了，那么这个时候follower角色就会转变为Candidate，那么candidate就会进行一轮leader的竞选。

2、在raft里面把时间分割成不同的小块，每一块以term来表示，在每个term里面，我们的leader都是不变的，但是在我们不同的term之间我们会做leader竞选，假设其它leader竞选的条件没有达到，但是我们term结束了，型的term产生了，我们就会自动进行一个leader竞选。

3、情况比较复杂，我们leader是和客户端交互的东西发送给follower，那么leader会有它自己的logEntry，follower也会有它自己的logEntry,所以呢，我们在发送logEntry的时候，其实就是一个同步的过程，就是leader把leader logentry,然后以appendEntry这个方法发送给follower，在这种情况下，假设，follower发现自己的logEntry和Leader发送的 不一样，并且我的是比较新的，那么这个follower就会变成candidat参与leader的竞选。

 raft 不允许领导人直接提交非当前任期的日志, 而必须是在提交当前任期的日志时, 「间接」地提交之前任期的、已经被复制到集群中大多数结点的日志, 我们将这里所说的之前任期的日志称为 preLog, 这样做的原理是, 如果领导人直接先去提交 preLog, 有可能 preLog 被复制到集群中的其他结点并应用到状态机后, 领导人发生崩溃, 新任领导人不含有 preLog, 它上任以后会强制要求跟随者覆盖掉已经提交的 preLog, 这样会发生的严重的错误, 因为对于任何结点来说, 已提交的日志是绝对不能再更改的, 而加了这条约束以后, 领导人若想要提交 preLog, 它必须先尝试提交 currentLog, 即先将 currentLog 复制到集群中大多数结点中, 这样以来, 就使得即便它崩溃了, 不含有 preLog 的候选人也不会赢得选举, 因为 currentLog 已经被复制到集群中大多数结点, 因为要想赢得选举, 候选人必须含有 currentLog, 若其含有 currentLog, 则其也一定含有 preLog, 从而避免了上述错误的发生

集群成员变更

采用采用两阶段(two-phase)的方案, 具体的方式如下, 首先当集群要进行扩容或缩容时, 需要向领导人发送配置变更请求, 领导人收到请求后会将旧的配置(记为 C_old )和新的配置(记为 C_new)取并集, 形成一个联合配置 , 然后将该配置作为一个日志项广播给集群中的所有其它结点, 其它结点自从收到该日志项开始便开始应用其中的配置(即 ), 这里无需等到日志提交, 因此在广播的过程中, 整个集群中的所有结点使用的配置有两种, 一种仍然在使用旧配置, 一种已经开始使用联合配置, 这个时候如果领导人宕机了, 则新的领导人可能使用旧配置也可能是使用联合配置, 但不会有只使用新配置的结点, 与普通日志一样, 当日志被复制到集群中的大多数结点之后, 便可以进行提交了, 这便是第一阶段提交, 第一阶段提交以后, 我们可以知道对于整个集群来说, 只有使用联合配置的结点才可能成为领导人, 也就是说无论是单纯的旧配置还是单纯的新配置, 它们都无法仅仅靠自己的配置选出领导人, 而前面所说的之所以会同时出现两个领导人, 原因就在于完全使用旧配置的结点间投票选出了旧配置下的领导人, 完全使用新配置的结点间投票选出了新配置下的领导人, raft 加入联合配置以后, 只有使用联合配置的结点才能成为领导人, 从而避免了割裂, 这时领导人会再生成一个新的日志项, 该日志项中只有新的配置 , 然后将该日志项广播给集群中的所有其它结点, 当集群中的大多数结点都接收到了该日志项后, 该日志便可以被提交了, 此时大多数结点都已完全使用新配置了, 这便是第二阶段的提交, 这时只在旧配置中的结点不在新配置中的结点就可以下线关机了, 以上就是两阶段提交的全部细节, 可以看到两阶段提交规避了在配置文件更新过程中, 集群可能出现的同一时刻有两个领导人的状态, 使得在整个更新过程中仍然能保证安全性

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011935.jpg)

索引为 1~5 的日志项被创建为了一个快照, 快照中保存了状态机最后的状态, 同时也保存了最后一个日志项的索引和任期号, 显然, 应用这个快照和按序重放索引为 1~5 的日志项后, 状态机的状态是完全一致的, 当日志特别长时, 快照可大大降低存储空间, raft 协议规定生成快照的日志项必须是已提交过的, 集群中的各个结点自主生成已提交的日志项的快照, 领导人每隔一段时间也会将自己的快照广播发送给集群中的所有其它结点, 其它结点收到快照后会根据快照中说明的索引检查自己是否已含有此快照中的全部信息, 如果没有, 则删除自己全部的日志项, 然后应用这个快照, 若快照的最大索引小于自身的, 则结点简单地将快照对应的索引之前的所有日志项删除, 并应用这个快照, 而自身原有的在此索引之后的日志项仍然保留, 这样以来, 即便是新加入集群的结点, 也可以使用快照快速达到与领导人的同步

下面讲下Leader竞选的过程

candidate会向所有的follower，发送一个requestVote，我要竞选了，你们来投票。投票过程中有一些规定，比如说一个服务器只能投一票，多数票胜出（非常重要的一个概念，分布式概念下一般都是多数获胜的方式去决定的）

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011138.jpg)

开始是4号candidate发起投票，假如这个时候6号candidate也发起了投票

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011789.jpg)

假设4拿到了2和3的投票，6拿到了1和5的投票，这个时候它们的投票是平等的，这个时候不会有一个leader产生，等待下一轮竞选。

在leader竞选完之后，leader会发送一个appendEntry的请求，其实就是说我成为了leader那么这个时候我要把我的logEntry给follower更一下，因为有可能follower的logEntry跟leader的不一样，有可能比leader老旧，也有可能是新的多余的。

还有一点就是，假设4在竞选期间收到了其它节点的appendEntry的请求，这个时候，这个appendEntry有可能是来自其它节点，比如说，4作为一个candidate正在竞选，它竞选过程当中呢，服务器6已经成为了leader，那么服务器6就会发送appendEntry，那么收到这个appendEntry,它首先要做的呢，并不是已经有leader了，我就转化为follower，它会比较自己的term，也就是比较自己logEntry里面的term和服务器发送来的term，如果服务器4认为自己还是比较新的，那么服务器4仍然会作为candidate继续竞选，如果服务器4发现自己的term比leader的旧一点，这个时候4就会放弃竞选转变为follower。

同理选举结束之后呢，leader也会发送appendEntry请求，append请求也是会比较logEntry的term的。

下面介绍下怎么去比较这个logEntry的？

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011395.jpg)

每个小方块代表一个term。在复制日志的时候可能掉线了或者宕机了，重启后又成为leader，然后继续复制等等，反正很多情况，导致日志不一样的情况。

比如上图服务器1只有log1234,服务器5之后又123等等。

leader服务器2首先会比较logEntry最新一个term，比如说：

根据服务器1，发现服务器1没有term5,所以leader这里就会往回走一个term，走到term4,然后继续比较，发现最新的都是term4，这样就会把新的logEntry5发送给服务器1；

针对服务器5呢，它会发现logEntry4也没有，它会一直回溯，回溯到和要同步的follower相同的一个term为止，上图就是term3，那么leader就是从3个开始将4和5复制给服务器5。

遇到服务器4这种情况，发现服务器4 呢，它实际上是多了，多了一个term6，那么leader就会把多余的擦除，擦除之后再比较，发现term匹配。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011782.jpg)

在完成日志复制之后，这个时候客户端发送了一个新的写请求，会产生一个新的logEntry,假如服务器2还是leader,它就会发送一个appendEntry的请求，给所有的follower，如果多数follower都接收到这个logEntry,然后成功复制，那么leader就会把它提交，上图就是被大部分服务器3、4、5接收了，这个就算多数，这个时候就算已提交,  已提交之后，它（term6）就会被写入到我们leader的logEntry。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011298.jpg)

但是如果，只是被其中一个接收（如上图），那么这个logEntry6不会被作为提交写入到我们的leader logEntry中。这种情况就跟上上上个图里面的服务器4一样多了一个term6。

附上Raft协议的动图：[http://thesecretlivesofdata.com/raft/](http://thesecretlivesofdata.com/raft/)

[https://web.stanford.edu/~ouster/cgi-bin/papers/raft-atc14](https://web.stanford.edu/~ouster/cgi-bin/papers/raft-atc14)

[https://blog.csdn.net/yangmengjiao_/article/details/120191314](https://blog.csdn.net/yangmengjiao_/article/details/120191314)