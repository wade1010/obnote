```
def mount(filesystem, type_, mount_on, params=''):
        """
        挂载文件系统
        :param filesystem:
        :param type_:
        :param mount_on:
        :param params:
        :return:
        """

        # TODO 申威适配, 修改为 ceph-fuse 挂载
        if type_ == CLUSTER_NAME:
            cmd = 'ceph-fuse -m {} {}'.format(filesystem, mount_on)
        else:
            cmd = 'mount -t {} {} {}'.format(type_, filesystem, mount_on)
            if params:
                cmd += ' -o {}'.format(params)
        code, out, _ = yield exec_cmd_as(cmd)
        return code, out
```

尝试使用mount -t，报错如下

```
2023-08-16 17:24:18,453 ERROR /opt/vcfs/vcmp-agent/src/agent/utils/common.py exec_cmd_as 68 71469331099520 [code: 32] [timeout 60 mount -t ceph 10.200.152.47:/ /vcluster/cephfs -o relatime,rbytes,ceph_quota=3,mount_timeout=10,pool_rule_id=38] [out: b'failed to load ceph kernel module (1)\n'] [err: b'2023-08-16 17:24:18.427 41e54d3a99a0 -1 auth: unable to find a keyring on /etc/ceph/ceph.client.guest.keyring,/etc/ceph/ceph.keyring,/etc/ceph/keyring,/etc/ceph/keyring.bin,: (2) No such file or directory\nmodprobe: FATAL: Module ceph not found in directory /lib/modules/4.19.90-25.0.v2111.ky10.sw_64\nmount error: ceph filesystem not supported by the system\n']
2023-08-16 17:24:23,462 INFO /opt/vcfs/vcmp-agent/src/agent/api/__init__.py write 49 71469331099520 response data = {'status': 'success', 'data': 32}
```