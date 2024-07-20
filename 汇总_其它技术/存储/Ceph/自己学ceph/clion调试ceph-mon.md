vstart 启动集群 ，默认是3个mon

```
sudo ../src/vstart.sh -d -n -x --localhost
```

sudo ../src/stop.sh mon  关闭所有mon

启动其中两个

sudo ./bin/ceph-mon -i a -c /home/cepher/workspace/ceph/build/ceph.conf

sudo ./bin/ceph-mon -i b -c /home/cepher/workspace/ceph/build/ceph.conf

可以调试剩余一个mon