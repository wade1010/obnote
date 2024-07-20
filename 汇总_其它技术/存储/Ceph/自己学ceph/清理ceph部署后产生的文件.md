```
systemctl stop ceph.target
rm -rf /var/lib/bootstrap-osd/*
rm -rf /var/lib/mgr/*
rm -rf /var/lib/mon/*
rm -rf /var/lib/osd/*
rm -rf /var/lib/crash/*
rm -rf /var/run/ceph/*
rm -rf /var/log/ceph/*
rm -rf /etc/ceph/*
echo "# RbdDevice             Parameters
#poolname/imagename     id=client,keyring=/etc/ceph/ceph.client.keyring" > /etc/ceph/rbdmap

```