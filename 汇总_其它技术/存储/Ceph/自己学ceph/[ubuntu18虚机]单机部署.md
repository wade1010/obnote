[https://blog.csdn.net/qq_33158376/article/details/124187211](https://blog.csdn.net/qq_33158376/article/details/124187211)

```
 614  wget -q -O- 'https://download.ceph.com/keys/release.asc' | sudo apt-key add -\n
  615  echo deb https://download.ceph.com/debian-luminous/ $(lsb_release -sc) main | sudo tee /etc/apt/sources.list.d/ceph.list
  616  sudo apt update
  617  sudo apt -y install ceph-deploy
  618  ?????
  619  cd
  620  ll
  621  mkdir myceph
  622  cd myceph
  623  sudo apt -y install ceph-deploy
  624  ceph-deploy
  625  ceph-deploy new zhangsn
  626  sudo vim /etc/hosts      改成本机ip  ceph0
  627  ip a
  628  sudo vim /etc/hosts
  629  hostnamectl set-hostname ceph0
  630  ceph-deploy new ceph0
  631  vim ceph.conf
  632  ceph-deploy install --release luminous ceph0
  633  ceph-deploy mon create-initial
  634  ceph-deploy admin ceph0
  635  ceph -s
  636  sudo ceph-deploy mon create-initial
  637  ceph-deploy admin ceph0
  638  sudo ceph-deploy admin ceph0
  639  vim /etc/ceph/ceph.conf
  640  ceph -s
  641  yum install -y ceph ceph-radosgw
  642  chmod +r /etc/ceph/ceph.client.admin.keyring
  643  sudo chmod +r /etc/ceph/ceph.client.admin.keyring
  644  sudo cp /data/services/ceph/ceph*  /etc/ceph/
  645  sudo cp ceph*  /etc/ceph/
  646  chmod +r /etc/ceph/ceph*
  647  sudo chmod +r /etc/ceph/ceph*
  648  chmod +r /etc/ceph/ceph*
  649  ceph -s
  650  su
  651  su root
  652  lsblk
  653  ceph-deploy mgr create ceph0
  654  ceph -s
  655  pvcreate
  656  vgcreate
  657  sudo apt -y install lvm2
  658  vgcreate -h
  659  lsblk    //给虚拟机加一个磁盘50G就行
  660  pvcreate /dev/sdb
  661  sudo pvcreate /dev/sdb
  662  vgcreate ceph-pool /dev/sdb
  663  sudo vgcreate ceph-pool /dev/sdb
  664  sudo lvcreate -n osd0 -l 100%FREE ceph-pool
  665  sudo mkfs.xfs  /dev/ceph-pool/osd0
  666  ceph-deploy osd create --data /dev/ceph-pool/osd0 ceph0
  667  ceph -s
  668  ceph osd tree
  669  ceph-deploy rgw create ceph0
  670  ceph -s
  671  ceph-deploy mds create ceph0
  672  ceph mds stat
  673  radosgw-admin user create --uid=admin --display-name=admin --admin
  674  sudo apt install s3cmd
  675  sudo vim .s3cfg
  676  sudo vim ~/.s3cfg
  677* ip a
  678  s3cmd mb s3://test
  679  s3cmd ls
 
```