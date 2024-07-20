解决从库复制延迟的问题：

1. 优化网络

2. 升级Slave硬件配置

3. Slave调整参数，关闭binlog，修改innodb_flush_log_at_trx_commit参数值

4. 升级MySQL版本到5.7，使用并行复制