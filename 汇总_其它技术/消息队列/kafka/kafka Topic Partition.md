逻辑概念，同一个Topic的消息可分布在一个或多个节点（Broker）上

Ø 一个Topic包含一个或者多个Partition

Ø 每条消息都属于且仅属于一个Topic

Ø Producer发布数据时，必须指定将该消息发布到哪一个Topic

Ø Consumer订阅消息时，也必须指定订阅哪个Topic的消息







物理概念，一个Partition只分布于一个Broker上（不考虑备份）

Ø 一个Partition物理上对应一个文件夹

Ø 一个Partition包含多个Segment（Segment对用户透明）

Ø 一个Segment对应一个文件

Ø Segment由一个个不可变记录组成

Ø 记录只会被append到Segment中，不会被单独删除或者修改

Ø 清除过期日志时，直接删除一个或多个Segment





Sync Producer vs. Async Producer

 Sync Producer

Ø 低延迟

Ø 低吞吐率

Ø 无数据丢失

 Aync Producer

Ø 高延迟

Ø 高吞吐率

Ø 可能会有数据丢失