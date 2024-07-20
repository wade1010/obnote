```
RGW=1 ../src/vstart.sh -n
```

rm -f core*

hostname ubuntu20

ip 192.168.10.164

port 40136

/home/cepher/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mon. /home/cepher/workspace/ceph/build/keyring --cap mon 'allow *'

creating /home/cepher/workspace/ceph/build/keyring

/home/cepher/workspace/ceph/build/bin/ceph-authtool --gen-key --name=client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *' /home/cepher/workspace/ceph/build/keyring

/home/cepher/workspace/ceph/build/bin/monmaptool --create --clobber --addv a [v2:192.168.10.164:40136,v1:192.168.10.164:40137] --addv b [v2:192.168.10.164:40138,v1:192.168.10.164:40139] --addv c [v2:192.168.10.164:40140,v1:192.168.10.164:40141] --print /tmp/ceph_monmap.2412

/home/cepher/workspace/ceph/build/bin/monmaptool: monmap file /tmp/ceph_monmap.2412

/home/cepher/workspace/ceph/build/bin/monmaptool: generated fsid d612ded3-ac9d-4674-8287-3e43424c33e0

setting min_mon_release = octopus

epoch 0

fsid d612ded3-ac9d-4674-8287-3e43424c33e0

last_changed 2023-03-24T10:15:07.750063+0800

created 2023-03-24T10:15:07.750063+0800

min_mon_release 15 (octopus)

election_strategy: 1

0: [v2:192.168.10.164:40136/0,v1:192.168.10.164:40137/0] mon.a

1: [v2:192.168.10.164:40138/0,v1:192.168.10.164:40139/0] mon.b

2: [v2:192.168.10.164:40140/0,v1:192.168.10.164:40141/0] mon.c

/home/cepher/workspace/ceph/build/bin/monmaptool: writing epoch 0 to /tmp/ceph_monmap.2412 (3 monitors)

rm -rf -- /home/cepher/workspace/ceph/build/dev/mon.a

mkdir -p /home/cepher/workspace/ceph/build/dev/mon.a

/home/cepher/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/cepher/workspace/ceph/build/ceph.conf -i a --monmap=/tmp/ceph_monmap.2412 --keyring=/home/cepher/workspace/ceph/build/keyring

rm -rf -- /home/cepher/workspace/ceph/build/dev/mon.b

mkdir -p /home/cepher/workspace/ceph/build/dev/mon.b

/home/cepher/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/cepher/workspace/ceph/build/ceph.conf -i b --monmap=/tmp/ceph_monmap.2412 --keyring=/home/cepher/workspace/ceph/build/keyring

rm -rf -- /home/cepher/workspace/ceph/build/dev/mon.c

mkdir -p /home/cepher/workspace/ceph/build/dev/mon.c

/home/cepher/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/cepher/workspace/ceph/build/ceph.conf -i c --monmap=/tmp/ceph_monmap.2412 --keyring=/home/cepher/workspace/ceph/build/keyring

rm -- /tmp/ceph_monmap.2412

/home/cepher/workspace/ceph/build/bin/ceph-mon -i a -c /home/cepher/workspace/ceph/build/ceph.conf

/home/cepher/workspace/ceph/build/bin/ceph-mon -i b -c /home/cepher/workspace/ceph/build/ceph.conf

/home/cepher/workspace/ceph/build/bin/ceph-mon -i c -c /home/cepher/workspace/ceph/build/ceph.conf

Populating config ...

[mgr]

mgr/telemetry/enable = false

mgr/telemetry/nag = false

creating /home/cepher/workspace/ceph/build/dev/mgr.x/keyring

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -i /home/cepher/workspace/ceph/build/dev/mgr.x/keyring auth add mgr.x mon 'allow profile mgr' mds 'allow *' osd 'allow *'

added key for mgr.x

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring config set mgr mgr/dashboard/x/ssl_server_port 41136 --force

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring config set mgr mgr/prometheus/x/server_port 9283 --force

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring config set mgr mgr/restful/x/server_port 42136 --force

Starting mgr.x

/home/cepher/workspace/ceph/build/bin/ceph-mgr -i x -c /home/cepher/workspace/ceph/build/ceph.conf

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

waiting for mgr dashboard module to start

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -h

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring dashboard ac-user-create admin -i /home/cepher/workspace/ceph/build/dashboard-admin-secret.txt administrator --force-password

{"username": "admin", "password": "$2b$12$.1lBlqnG4SnKuZwW27O4VeqJ31mPLtG5fWOxuSVhhV00U1d3WlQty", "roles": ["administrator"], "name": null, "email": null, "lastUpdate": 1679624142, "enabled": true, "pwdExpirationDate": null, "pwdUpdateRequired": false}

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring dashboard create-self-signed-cert

Self-signed certificate created

add osd0 00035af6-00ca-442e-9df0-6dffa4d68e8c

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring osd new 00035af6-00ca-442e-9df0-6dffa4d68e8c -i /home/cepher/workspace/ceph/build/dev/osd0/new.json

0

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/cepher/workspace/ceph/build/ceph.conf --mkfs --key AQDPBx1kFYpxHRAA/SS7sp7cyAv01yJCdw8tVA== --osd-uuid 00035af6-00ca-442e-9df0-6dffa4d68e8c

2023-03-24T10:15:45.386+0800 7fa835447240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd0/block: (2) No such file or directory

2023-03-24T10:15:45.390+0800 7fa835447240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd0/block: (2) No such file or directory

2023-03-24T10:15:45.390+0800 7fa835447240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd0/block: (2) No such file or directory

2023-03-24T10:15:45.398+0800 7fa835447240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd0) _read_fsid unparsable uuid

start osd.0

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/cepher/workspace/ceph/build/ceph.conf

add osd1 1c0b94bb-1d02-44f5-9306-6602712d1363

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring osd new 1c0b94bb-1d02-44f5-9306-6602712d1363 -i /home/cepher/workspace/ceph/build/dev/osd1/new.json

1

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 1 -c /home/cepher/workspace/ceph/build/ceph.conf --mkfs --key AQDbBx1kRoDXNhAAj87gMvUXLalQIekH3ddfsg== --osd-uuid 1c0b94bb-1d02-44f5-9306-6602712d1363

2023-03-24T10:15:57.114+0800 7fdbb6535240 -1 Falling back to public interface

2023-03-24T10:15:57.162+0800 7fee5cb22240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd1/block: (2) No such file or directory

2023-03-24T10:15:57.162+0800 7fee5cb22240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd1/block: (2) No such file or directory

2023-03-24T10:15:57.162+0800 7fee5cb22240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd1/block: (2) No such file or directory

2023-03-24T10:15:57.182+0800 7fee5cb22240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd1) _read_fsid unparsable uuid

2023-03-24T10:16:00.902+0800 7fdbb6535240 -1 osd.0 0 log_to_monitors true

start osd.1

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 1 -c /home/cepher/workspace/ceph/build/ceph.conf

add osd2 4628ba27-7265-4138-962d-a5f4f78d06cf

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring osd new 4628ba27-7265-4138-962d-a5f4f78d06cf -i /home/cepher/workspace/ceph/build/dev/osd2/new.json

2

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 2 -c /home/cepher/workspace/ceph/build/ceph.conf --mkfs --key AQDnBx1kxixTLRAAMObp5Hp3uuAdMJwONbuD3Q== --osd-uuid 4628ba27-7265-4138-962d-a5f4f78d06cf

2023-03-24T10:16:09.041+0800 7fd5070cf240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd2/block: (2) No such file or directory

2023-03-24T10:16:09.041+0800 7fd5070cf240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd2/block: (2) No such file or directory

2023-03-24T10:16:09.041+0800 7fd5070cf240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/cepher/workspace/ceph/build/dev/osd2/block: (2) No such file or directory

2023-03-24T10:16:09.049+0800 7fd5070cf240 -1 bluestore(/home/cepher/workspace/ceph/build/dev/osd2) _read_fsid unparsable uuid

2023-03-24T10:16:09.202+0800 7ff7969db240 -1 Falling back to public interface

2023-03-24T10:16:11.590+0800 7ff7969db240 -1 osd.1 0 log_to_monitors true

start osd.2

/home/cepher/workspace/ceph/build/bin/ceph-osd -i 2 -c /home/cepher/workspace/ceph/build/ceph.conf

2023-03-24T10:16:20.491+0800 7f4555f06240 -1 Falling back to public interface

2023-03-24T10:16:23.540+0800 7f4555f06240 -1 osd.2 0 log_to_monitors true

2023-03-24T10:16:29.485+0800 7f4552ec3700 -1 osd.2 0 waiting for initial osdmap

OSDs started

mkdir -p /home/cepher/workspace/ceph/build/dev/mds.a

/home/cepher/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.a /home/cepher/workspace/ceph/build/dev/mds.a/keyring

creating /home/cepher/workspace/ceph/build/dev/mds.a/keyring

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -i /home/cepher/workspace/ceph/build/dev/mds.a/keyring auth add mds.a mon 'allow profile mds' osd 'allow rw tag cephfs **=**' mds allow mgr 'allow profile mds'

added key for mds.a

/home/cepher/workspace/ceph/build/bin/ceph-mds -i a -c /home/cepher/workspace/ceph/build/ceph.conf

starting mds.a at

mkdir -p /home/cepher/workspace/ceph/build/dev/mds.b

/home/cepher/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.b /home/cepher/workspace/ceph/build/dev/mds.b/keyring

creating /home/cepher/workspace/ceph/build/dev/mds.b/keyring

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -i /home/cepher/workspace/ceph/build/dev/mds.b/keyring auth add mds.b mon 'allow profile mds' osd 'allow rw tag cephfs **=**' mds allow mgr 'allow profile mds'

added key for mds.b

/home/cepher/workspace/ceph/build/bin/ceph-mds -i b -c /home/cepher/workspace/ceph/build/ceph.conf

starting mds.b at

mkdir -p /home/cepher/workspace/ceph/build/dev/mds.c

/home/cepher/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.c /home/cepher/workspace/ceph/build/dev/mds.c/keyring

creating /home/cepher/workspace/ceph/build/dev/mds.c/keyring

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring -i /home/cepher/workspace/ceph/build/dev/mds.c/keyring auth add mds.c mon 'allow profile mds' osd 'allow rw tag cephfs **=**' mds allow mgr 'allow profile mds'

added key for mds.c

/home/cepher/workspace/ceph/build/bin/ceph-mds -i c -c /home/cepher/workspace/ceph/build/ceph.conf

starting mds.c at

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring fs volume ls

[]

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring fs volume create a

Volume created successfully (no MDS daemons created)

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring fs authorize a client.fs_a / rwp

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring fs authorize * client.fs / rwp

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

/home/cepher/workspace/ceph/build/bin/ceph -c /home/cepher/workspace/ceph/build/ceph.conf -k /home/cepher/workspace/ceph/build/keyring auth get-or-create client.rgw.8000 mon 'allow rw' osd 'allow rwx' mgr 'allow rw'

start rgw on [http://localhost:8000](http://localhost:8000)

/home/cepher/workspace/ceph/build/bin/radosgw -c /home/cepher/workspace/ceph/build/ceph.conf --log-file=/home/cepher/workspace/ceph/build/out/radosgw.8000.log --admin-socket=/home/cepher/workspace/ceph/build/out/radosgw.8000.asok --pid-file=/home/cepher/workspace/ceph/build/out/radosgw.8000.pid --rgw_luarocks_location=/home/cepher/workspace/ceph/build/out/luarocks -n client.rgw.8000 '--rgw_frontends=beast port=8000'

vstart cluster complete. Use stop.sh to stop. See out/* (e.g. 'tail -f out/????') for debug output.

dashboard urls: [https://192.168.10.164:41136](https://192.168.10.164:41136)

w/ user/pass: admin / admin

export PYTHONPATH=/home/cepher/workspace/ceph/src/pybind:/home/cepher/workspace/ceph/build/lib/cython_modules/lib.3:/home/cepher/workspace/ceph/src/python-common:$PYTHONPATH

export LD_LIBRARY_PATH=/home/cepher/workspace/ceph/build/lib:$LD_LIBRARY_PATH

export PATH=/home/cepher/workspace/ceph/build/bin:$PATH

alias cephfs-shell=/home/cepher/workspace/ceph/src/tools/cephfs/cephfs-shell

CEPH_DEV=1

vim ceph.conf

```
; generated by vstart.sh on 2023年 03月 24日 星期五 10:27:51 CST
[client.vstart.sh]
        num mon = 3
        num osd = 3
        num mds = 3
        num mgr = 1
        num rgw = 1
        num ganesha = 0

[global]
        fsid = d3e109cf-9f0c-40cf-bcb4-5b149e776de6
        osd failsafe full ratio = .99
        mon osd full ratio = .99
        mon osd nearfull ratio = .99
        mon osd backfillfull ratio = .99
        mon_max_pg_per_osd = 1000
        erasure code dir = /home/cepher/workspace/ceph/build/lib
        plugin dir = /home/cepher/workspace/ceph/build/lib
        filestore fd cache size = 32
        run dir = /home/cepher/workspace/ceph/build/out
        crash dir = /home/cepher/workspace/ceph/build/out
        enable experimental unrecoverable data corrupting features = *
        osd_crush_chooseleaf_type = 0
        debug asok assert abort = true
        ms bind msgr2 = true
        ms bind msgr1 = true
        
        lockdep = true
        auth cluster required = cephx
        auth service required = cephx
        auth client required = cephx
[client]
        keyring = /home/cepher/workspace/ceph/build/keyring
        log file = /home/cepher/workspace/ceph/build/out/$name.$pid.log
        admin socket = /tmp/ceph-asok.tcTZuP/$name.$pid.asok

        ; needed for s3tests
        rgw crypt s3 kms backend = testing
        rgw crypt s3 kms encryption keys = testkey-1=YmluCmJvb3N0CmJvb3N0LWJ1aWxkCmNlcGguY29uZgo= testkey-2=aWIKTWFrZWZpbGUKbWFuCm91dApzcmMKVGVzdGluZwo=
        rgw crypt require ssl = false
        ; uncomment the following to set LC days as the value in seconds;
        ; needed for passing lc time based s3-tests (can be verbose)
        ; rgw lc debug interval = 10
        
[client.rgw.8000]
        rgw frontends = beast port=8000
        admin socket = /home/cepher/workspace/ceph/build/out/radosgw.8000.asok
[mds]

        log file = /home/cepher/workspace/ceph/build/out/$name.log
        admin socket = /tmp/ceph-asok.tcTZuP/$name.asok
        chdir = ""
        pid file = /home/cepher/workspace/ceph/build/out/$name.pid
        heartbeat file = /home/cepher/workspace/ceph/build/out/$name.heartbeat

        mds data = /home/cepher/workspace/ceph/build/dev/mds.$id
        mds root ino uid = 1000
        mds root ino gid = 1000
        
[mgr]
        mgr disabled modules = rook
        mgr data = /home/cepher/workspace/ceph/build/dev/mgr.$id
        mgr module path = /home/cepher/workspace/ceph/src/pybind/mgr
        cephadm path = /home/cepher/workspace/ceph/src/cephadm/cephadm

        log file = /home/cepher/workspace/ceph/build/out/$name.log
        admin socket = /tmp/ceph-asok.tcTZuP/$name.asok
        chdir = ""
        pid file = /home/cepher/workspace/ceph/build/out/$name.pid
        heartbeat file = /home/cepher/workspace/ceph/build/out/$name.heartbeat

        
[osd]

        log file = /home/cepher/workspace/ceph/build/out/$name.log
        admin socket = /tmp/ceph-asok.tcTZuP/$name.asok
        chdir = ""
        pid file = /home/cepher/workspace/ceph/build/out/$name.pid
        heartbeat file = /home/cepher/workspace/ceph/build/out/$name.heartbeat

        osd_check_max_object_name_len_on_startup = false
        osd data = /home/cepher/workspace/ceph/build/dev/osd$id
        osd journal = /home/cepher/workspace/ceph/build/dev/osd$id/journal
        osd journal size = 100
        osd class tmp = out
        osd class dir = /home/cepher/workspace/ceph/build/lib
        osd class load list = *
        osd class default list = *
        osd fast shutdown = false

        filestore wbthrottle xfs ios start flusher = 10
        filestore wbthrottle xfs ios hard limit = 20
        filestore wbthrottle xfs inodes hard limit = 30
        filestore wbthrottle btrfs ios start flusher = 10
        filestore wbthrottle btrfs ios hard limit = 20
        filestore wbthrottle btrfs inodes hard limit = 30
        bluestore fsck on mount = true
        bluestore block create = true
        bluestore block db path = /home/cepher/workspace/ceph/build/dev/osd$id/block.db.file
        bluestore block db size = 1073741824
        bluestore block db create = true
        bluestore block wal path = /home/cepher/workspace/ceph/build/dev/osd$id/block.wal.file
        bluestore block wal size = 1048576000
        bluestore block wal create = true

        ; kstore
        kstore fsck on mount = true
        osd objectstore = bluestore

        
[mon]
        mon_data_avail_crit = 1
        mgr initial modules = iostat nfs dashboard

        log file = /home/cepher/workspace/ceph/build/out/$name.log
        admin socket = /tmp/ceph-asok.tcTZuP/$name.asok
        chdir = ""
        pid file = /home/cepher/workspace/ceph/build/out/$name.pid
        heartbeat file = /home/cepher/workspace/ceph/build/out/$name.heartbeat


        debug mon = 10
        debug ms = 1
        
        mon cluster log file = /home/cepher/workspace/ceph/build/out/cluster.mon.$id.log
        osd pool default erasure code profile = plugin=jerasure technique=reed_sol_van k=2 m=1 crush-failure-domain=osd
        auth allow insecure global id reclaim = false
[mon.a]
        host = ubuntu20
        mon data = /home/cepher/workspace/ceph/build/dev/mon.a
[mon.b]
        host = ubuntu20
        mon data = /home/cepher/workspace/ceph/build/dev/mon.b
[mon.c]
        host = ubuntu20
        mon data = /home/cepher/workspace/ceph/build/dev/mon.c
[global]
        mon host =  [v2:192.168.10.164:40445,v1:192.168.10.164:40446] [v2:192.168.10.164:40447,v1:192.168.10.164:40448] [v2:192.168.10.164:40449,v1:192.168.10.164:40450]
[mgr.x]
        host = ubuntu20
[osd.0]
        host = ubuntu20
        bluestore fsck on mount = false
[osd.1]
        host = ubuntu20
        bluestore fsck on mount = false
[osd.2]
        host = ubuntu20
        bluestore fsck on mount = false
[mds.a]
        host = ubuntu20
[mds.b]
        host = ubuntu20
[mds.c]
        host = ubuntu20

```

```
../qa/workunits/rgw/run-s3tests.sh
```

执行这个有问题，后面再研究把

按github上的步骤

```
../src/vstart.sh --debug --new -x --localhost --bluestore
```

```
./bin/ceph -s
```

创建rbd pool

```
ceph osd pool create rbd 32 32
```

用rados测试

```
./bin/rados -p rbd bench 30 write
```