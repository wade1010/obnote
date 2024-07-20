# 最终成功参考如下

[https://www.jianshu.com/p/df48f6ca6511](https://www.jianshu.com/p/df48f6ca6511)

# 更换yum国内源

mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup

wget -O /etc/yum.repos.d/CentOS-Base.repo [http://mirrors.aliyun.com/repo/Centos-7.repo](http://mirrors.aliyun.com/repo/Centos-7.repo)

yum clean all

yum makecache

yum --exclude=kernel* update -y

mk-build-deps

Unknown option: build-profiles

Usage:

mk-build-deps --help|--version

```
mk-build-deps [options] control file | package name ...

```

# 生成秘钥

sudo ceph-authtool --create-keyring /tmp/ceph.mon.keyring --gen-key -n mon. --cap mon 'allow *'

# 创建管理员

sudo ceph-authtool --create-keyring /etc/ceph/ceph.client.admin.keyring --gen-key -n client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *'

# osd用户

sudo ceph-authtool --create-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring --gen-key -n client.bootstrap-osd --cap mon 'profile bootstrap-osd' --cap mgr 'allow r'

# 添加秘钥到ceph.mon.keyring文件

sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /etc/ceph/ceph.client.admin.keyring

sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring

# 修改ceph.mon.keyring拥有者

sudo chown ceph:ceph /tmp/ceph.mon.keyring

monmaptool --create --add ceph0 192.168.100.201 --fsid 7453bfa0-441c-4db1-bcf2-caac1036a598 /tmp/monmap

sudo -u ceph mkdir /var/lib/ceph/mon/ceph-ceph0

sudo -u ceph ceph-mon [--cluster {cluster-name}] --mkfs -i {hostname} --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring

#sudo -u ceph ceph-mon --mkfs -i ceph0 --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring

# 配置用户

ceph auth get-or-create mgr.{$name} mon 'allow profile mgr' osd 'allow *' mds 'allow *'

```
[root@bogon ~]# ceph auth get-or-create mgr.bob mon 'allow profile mgr' osd 'allow *' mds 'allow *'
[mgr.bob]
	key = AQD4XgBkI4FeBhAAD3TVbwbRWesgPv2hKQhHgw==
```

# 启动服务

ceph-mgr -i {$name}

```
ceph-mgr -i bob
```

openssl req -new -nodes -x509 -subj "/O=IT/CN=ceph-mgr-dashboard" -days 3650 -keyout dashboard.key -out dashboard.crt -extensions v3_ca

openssl req -new -nodes -x509 \

-subj "/O=IT/CN=ceph-mgr-dashboard" -days 3650 \

-keyout dashboard.key -out dashboard.crt -extensions v3_ca

如果不行就 pkill ceph 然后重启(systemctl start ceph-mon@ceph0)，再重试

cat > ceph_secretkey <<EOF

> a123456

> EOF

ceph dashboard ac-user-create admin -i ./ceph_secretkey

[https://blog.csdn.net/qq_37382917/article/details/125306379](https://blog.csdn.net/qq_37382917/article/details/125306379)

ceph-volume lvm zap /dev/sdb --destroy

[http://www.strugglesquirrel.com/2018/03/28/%E8%A7%A3%E5%86%B3%E6%97%A0%E6%B3%95%E6%AD%A3%E5%B8%B8%E5%88%A0%E9%99%A4lvm%E7%9A%84%E9%97%AE%E9%A2%98/](http://www.strugglesquirrel.com/2018/03/28/%E8%A7%A3%E5%86%B3%E6%97%A0%E6%B3%95%E6%AD%A3%E5%B8%B8%E5%88%A0%E9%99%A4lvm%E7%9A%84%E9%97%AE%E9%A2%98/)

[https://www.jianshu.com/p/df48f6ca6511](https://www.jianshu.com/p/df48f6ca6511)

清理

[https://www.bbsmax.com/A/pRdB8p0GJn/](https://www.bbsmax.com/A/pRdB8p0GJn/)

[https://blog.csdn.net/qq_33158376/article/details/124187211](https://blog.csdn.net/qq_33158376/article/details/124187211)

yum install -y epel-release

echo deb [http://download.ceph.com/debian-quincy/](http://download.ceph.com/debian-quincy/) $(lsb_release -sc) main | sudo tee /etc/apt/sources.list.d/ceph.list

bin/init-ceph: 40: export: (x86)/Razer: bad variable name

[root@services-ceph ceph]# radosgw-admin user create --uid=admin --display-name=admin --admin

{

"user_id": "admin",

"display_name": "admin",

"email": "",

"suspended": 0,

"max_buckets": 1000,

"auid": 0,

"subusers": [],

"keys": [

{

"user": "admin",

"access_key": "RD1J2N9PES18BDGLML0V",

"secret_key": "U605yZJooMRNqG8oWUGNLPDFqtRPnezBEa3PfZPe"

}

],

"swift_keys": [],

"caps": [],

"op_mask": "read, write, delete",

"default_placement": "",

"placement_tags": [],

"bucket_quota": {

"enabled": false,

"check_on_raw": false,

"max_size": -1,

"max_size_kb": 0,

"max_objects": -1

},

"user_quota": {

"enabled": false,

"check_on_raw": false,

"max_size": -1,

"max_size_kb": 0,

"max_objects": -1

},

"temp_url_keys": [],

"type": "rgw"

}

[default]

access_key = RD1J2N9PES18BDGLML0V

secret_key = U605yZJooMRNqG8oWUGNLPDFqtRPnezBEa3PfZPe

host_base = 192.168.1.139:7480

host_bucket = 192.168.1.139:7480/%(bucket)

use_https = False