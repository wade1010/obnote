[https://docs.ceph.com/en/latest/rados/man/](https://docs.ceph.com/en/latest/rados/man/)

不能将纠删码池用作 CephFS 元数据池，因为 CephFS 元数据是使用 RADOS OMAP数据结构存储的，而 EC 池无法存储