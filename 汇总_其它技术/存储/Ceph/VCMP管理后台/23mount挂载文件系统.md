### 错误1

```
# mount -t ceph 192.168.10.88:/ /vcluster/cephfs -o relatime,rbytes,ceph_quota=3,mount_timeout=10,pool_rule_id=4
2023-07-03 17:06:15.508 7f3a8c45bdc0 -1 auth: unable to find a keyring on /etc/ceph/ceph.client.guest.keyring,/etc/ceph/ceph.keyring,/etc/ceph/keyring,/etc/ceph/keyring.bin,: (2) No such file or directory
mount error 22 = Invalid argument
```

解决

```
ceph fs authorize cephfs client.guest / rw|tee /etc/ceph/ceph.client.guest.keyring
```

### 错误2

```
]# mount -t ceph 192.168.10.88:/ /vcluster/cephfs -o relatime,rbytes,ceph_quota=3,mount_timeout=10,pool_rule_id=4
mount error 22 = Invalid argument
```