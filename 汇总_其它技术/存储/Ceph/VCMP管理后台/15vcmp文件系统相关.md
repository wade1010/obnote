不创建文件系统的时候，表现为下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354370.jpg)

这时候目录管理是不可用的

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354560.jpg)

MDS主备是备用状态

而且这时候 tail -f agent-worker.log看到的挂载定时检查任务是显示

```
2023-06-16 14:00:35,433 ERROR /root/workspace/vcmp-agent/src/agent/celery_app/cluster_task.py mount 65 139695534936448 not necessary to mount
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354756.jpg)

创建文件系统之后，发现页面弹出小框提示"文件系统新建成功，节点192.168.100.24客户端挂载失败"，这个应该是没问题的，看源码写着

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354794.jpg)

一会就发现MDS主备变成了主，然后目录管理也是可以操作的

但是这时候agent-worker.log里面就会定时报错

```
2023-06-16 16:16:13,273 INFO /root/workspace/vcmp-agent/src/agent/celery_app/cluster_task.py exec_cmd 105 140228011774336 cmd_info = timeout 8 ceph-fuse -m 192.168.100.24:/ /vcluster/cephfs -o relatime,rbytes,ceph_quota=3,mount_timeout=10,pool_rule_id=5
2023-06-16 16:16:13,324 ERROR /root/workspace/vcmp-agent/src/agent/celery_app/cluster_task.py mount 53 140228011774336 mount fs_info failed out=b'',err=b"2023-06-16T16:16:13.306+0800 7f6860b75080 -1 init, newargv = 0x5608555bf940 newargc=11\nceph-fuse[2633476]: starting ceph client\nfuse: unknown option `relatime'\nceph-fuse[2633476]: fuse failed to start\n2023-06-16T16:16:13.314+0800 7f6860b75080 -1 fuse_lowlevel_new failed\n
```

> 后来不报错了


看了源码，这个/vcluster/cephfs已经在创建文件系统的时候被ceph-fuse挂载了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354969.jpg)