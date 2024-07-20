Error EINVAL: all MDS daemons must be inactive before resetting filesystem: set the cluster_down flag and use ceph mds fail to make this so

systemctl stop ceph-mds.target

ceph fs rm cephfs --yes-i-really-mean-it