MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh -n -d

build ➤ ps ax|grep ceph                                                                                                                                                                                          git:v16.2.12

17354 ?        Ssl    0:02 /home/runner/workspace/ceph-16.2.12/build/bin/ceph-mon -i a -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

17557 ?        Ssl    0:04 /home/runner/workspace/ceph-16.2.12/build/bin/ceph-mgr -i x -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

19151 ?        Ssl    0:09 /home/runner/workspace/ceph-16.2.12/build/bin/ceph-osd -i 0 -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

20017 pts/1    Sl     0:00 /home/runner/workspace/ceph-16.2.12/build/bin/ceph-mds -i a -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

20568 ?        Ssl    0:00 /home/runner/workspace/ceph-16.2.12/build/bin/radosgw -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf --log-file=/home/runner/workspace/ceph-16.2.12/build/out/radosgw.8000.log --admin-socket=/home/runner/workspace/ceph-16.2.12/build/out/radosgw.8000.asok --pid-file=/home/runner/workspace/ceph-16.2.12/build/out/radosgw.8000.pid --rgw_luarocks_location=/home/runner/workspace/ceph-16.2.12/build/out/luarocks --debug-rgw=20 --debug-ms=1 -n client.rgw.8000 --rgw_frontends=beast port=8000

```

** going verbose **
rm -f core*
hostname ceph
ip 192.168.1.141
port 40238
/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mon. /home/ceph/workspace/ceph/build/keyring --cap mon 'allow *'
creating /home/ceph/workspace/ceph/build/keyring
/home/ceph/workspace/ceph/build/bin/ceph-authtool --gen-key --name=client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *' /home/ceph/workspace/ceph/build/keyring
/home/ceph/workspace/ceph/build/bin/monmaptool --create --clobber --addv a [v2:192.168.1.141:40238,v1:192.168.1.141:40239] --print /tmp/ceph_monmap.80082
/home/ceph/workspace/ceph/build/bin/monmaptool: monmap file /tmp/ceph_monmap.80082
/home/ceph/workspace/ceph/build/bin/monmaptool: generated fsid 4fc4e9b0-5d05-4c89-ab60-d15a8ba7c6c6
setting min_mon_release = octopus
epoch 0
fsid 4fc4e9b0-5d05-4c89-ab60-d15a8ba7c6c6
last_changed 2023-04-02T11:34:46.268039+0800
created 2023-04-02T11:34:46.268039+0800
min_mon_release 15 (octopus)
election_strategy: 1
0: [v2:192.168.1.141:40238/0,v1:192.168.1.141:40239/0] mon.a
/home/ceph/workspace/ceph/build/bin/monmaptool: writing epoch 0 to /tmp/ceph_monmap.80082 (1 monitors)
rm -rf -- /home/ceph/workspace/ceph/build/dev/mon.a
mkdir -p /home/ceph/workspace/ceph/build/dev/mon.a
/home/ceph/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/ceph/workspace/ceph/build/ceph.conf -i a --monmap=/tmp/ceph_monmap.80082 --keyring=/home/ceph/workspace/ceph/build/keyring
rm -- /tmp/ceph_monmap.80082
/home/ceph/workspace/ceph/build/bin/ceph-mon -i a -c /home/ceph/workspace/ceph/build/ceph.conf
Populating config ...

[mgr]
        mgr/telemetry/enable = false
        mgr/telemetry/nag = false
Setting debug configs ...
creating /home/ceph/workspace/ceph/build/dev/mgr.x/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mgr.x/keyring auth add mgr.x mon 'allow profile mgr' mds 'allow *' osd 'allow *'
added key for mgr.x
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/dashboard/x/ssl_server_port 41238 --force
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/prometheus/x/server_port 9283 --force
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/restful/x/server_port 42238 --force
Starting mgr.x
/home/ceph/workspace/ceph/build/bin/ceph-mgr -i x -c /home/ceph/workspace/ceph/build/ceph.conf
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring dashboard ac-user-create admin -i /home/ceph/workspace/ceph/build/dashboard-admin-secret.txt administrator --force-password
{"username": "admin", "password": "$2b$12$9Ve.lIJPiJGojiKR.TxguOVAm/hGwia9oyp6avvId5CwYQ3nWpnJu", "roles": ["administrator"], "name": null, "email": null, "lastUpdate": 1680406495, "enabled": true, "pwdExpirationDate": null, "pwdUpdateRequired": false}
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring dashboard create-self-signed-cert
Self-signed certificate created
add osd0 3b9cba3a-aa50-4511-b352-1e9943b47467
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring osd new 3b9cba3a-aa50-4511-b352-1e9943b47467 -i /home/ceph/workspace/ceph/build/dev/osd0/new.json
0
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf --mkfs --key AQDg9yhkWAS2ExAAeRDs4DvqJ0D1LPbWZ8X/Og== --osd-uuid 3b9cba3a-aa50-4511-b352-1e9943b47467
2023-04-02T11:34:56.836+0800 7f72bf13d240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-04-02T11:34:56.836+0800 7f72bf13d240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-04-02T11:34:56.836+0800 7f72bf13d240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-04-02T11:34:56.844+0800 7f72bf13d240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0) _read_fsid unparsable uuid
start osd.0
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf
2023-04-02T11:35:03.948+0800 7f799b90d240 -1 Falling back to public interface
2023-04-02T11:35:06.776+0800 7f799b90d240 -1 osd.0 0 log_to_monitors true
OSDs started
mkdir -p /home/ceph/workspace/ceph/build/dev/mds.a
/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.a /home/ceph/workspace/ceph/build/dev/mds.a/keyring
creating /home/ceph/workspace/ceph/build/dev/mds.a/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mds.a/keyring auth add mds.a mon 'allow profile mds' osd 'allow rw tag cephfs *=*' mds allow mgr 'allow profile mds'
added key for mds.a
/home/ceph/workspace/ceph/build/bin/ceph-mds -i a -c /home/ceph/workspace/ceph/build/ceph.conf
starting mds.a at
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs volume ls
[]
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs volume create a
Volume created successfully (no MDS daemons created)
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs authorize a client.fs_a / rwp
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs authorize * client.fs / rwp
setting up user testid
setting up s3-test users
setting up user tester

S3 User Info:
  access key:  0555b35654ad1656d804
  secret key:  h7GhxuBLTrlhVUyxSPUKUV8r/2EI4ngqJxD7iBdBYLhwluN30JaT3Q==

Swift User Info:
  account   : test
  user      : tester
  password  : testing

/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring auth get-or-create client.rgw.8000 mon 'allow rw' osd 'allow rwx' mgr 'allow rw'
start rgw on http://localhost:8000
/home/ceph/workspace/ceph/build/bin/radosgw -c /home/ceph/workspace/ceph/build/ceph.conf --log-file=/home/ceph/workspace/ceph/build/out/radosgw.8000.log --admin-socket=/home/ceph/workspace/ceph/build/out/radosgw.8000.asok --pid-file=/home/ceph/workspace/ceph/build/out/radosgw.8000.pid --rgw_luarocks_location=/home/ceph/workspace/ceph/build/out/luarocks --debug-rgw=20 --debug-ms=1 -n client.rgw.8000 '--rgw_frontends=beast port=8000'

vstart cluster complete. Use stop.sh to stop. See out/* (e.g. 'tail -f out/????') for debug output.

dashboard urls: https://192.168.1.141:41238
  w/ user/pass: admin / admin

export PYTHONPATH=/home/ceph/workspace/ceph/src/pybind:/home/ceph/workspace/ceph/build/lib/cython_modules/lib.3:/home/ceph/workspace/ceph/src/python-common:$PYTHONPATH
export LD_LIBRARY_PATH=/home/ceph/workspace/ceph/build/lib:$LD_LIBRARY_PATH
export PATH=/home/ceph/workspace/ceph/build/bin:$PATH
alias cephfs-shell=/home/ceph/workspace/ceph/src/tools/cephfs/cephfs-shell
CEPH_DEV=1


WARNING:
    Please remove stray /etc/ceph/ceph.conf if not needed.
    Your conf files /home/ceph/workspace/ceph/build/ceph.conf and /etc/ceph/ceph.conf may not be in sync
    and may lead to undesired results.

NOTE:
    Remember to restart cluster after removing /etc/ceph/ceph.conf

```