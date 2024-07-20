```
lttng-sessiond --daemonize
```

MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh -d -n -t -l -e -o "osd_tracing = true"

```
root@mon-node1:/home/ceph/workspace/ceph/build# ps ax|grep ceph
   1724 ?        Ssl    0:01 /home/ceph/workspace/ceph/build/bin/ceph-mon -i a -c /home/ceph/workspace/ceph/build/ceph.conf
   1822 ?        Ssl    0:08 /home/ceph/workspace/ceph/build/bin/ceph-mgr -i x -c /home/ceph/workspace/ceph/build/ceph.conf
   1855 ?        Ssl    0:10 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf
   2416 pts/0    Sl     0:00 /home/ceph/workspace/ceph/build/bin/ceph-mds -i a -c /home/ceph/workspace/ceph/build/ceph.conf
   2569 ?        Ssl    0:01 /home/ceph/workspace/ceph/build/bin/radosgw -c /home/ceph/workspace/ceph/build/ceph.conf --log-file=/home/ceph/workspace/ceph/build/out/radosgw.8000.log --admin-socket=/home/ceph/workspace/ceph/build/out/radosgw.8000.asok --pid-file=/home/ceph/workspace/ceph/build/out/radosgw.8000.pid --rgw_luarocks_location=/home/ceph/workspace/ceph/build/out/luarocks --debug-rgw=20 --debug-ms=1 -n client.rgw.8000 --rgw_frontends=beast port=8000

```

```
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
start rgw on http://localhost:8000
/home/cepher/workspace/ceph/build/bin/radosgw -c /home/cepher/workspace/ceph/build/ceph.conf --log-file=/home/cepher/workspace/ceph/build/out/radosgw.8000.log --admin-socket=/home/cepher/workspace/ceph/build/out/radosgw.8000.asok --pid-file=/home/cepher/workspace/ceph/build/out/radosgw.8000.pid --rgw_luarocks_location=/home/cepher/workspace/ceph/build/out/luarocks --debug-rgw=20 --debug-ms=1 -n client.rgw.8000 '--rgw_frontends=beast port=8000'

vstart cluster complete. Use stop.sh to stop. See out/* (e.g. 'tail -f out/????') for debug output.

dashboard urls: https://127.0.0.1:41404
  w/ user/pass: admin / admin

```

./bin/radosgw-admin user modify --uid=testid --access-key=admin --secret-key=adminadmin

./bin/ceph daemon osd.0 config show|grep -i rbd_tracing

修改默认ceph.conf配置，把rbd_tracing 设置成true

../src/stop.sh

MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh -d -l -e -t -o "osd_tracing = true"

[https://docs.ceph.com/en/latest/dev/blkin/#testing-trace](https://docs.ceph.com/en/latest/dev/blkin/#testing-trace)

sudo gdb -ex 'set follow-fork-mode child' -p $(pidof radosgw)

[https://blog.csdn.net/weixin_34232617/article/details/92231105](https://blog.csdn.net/weixin_34232617/article/details/92231105)

[https://www.google.com/search?q=gdb+debug+ceph&sxsrf=APwXEddifDvCNu_GMpr9QNwN0SiiIO4cUg%3A1679660938511&source=hp&ei=ipcdZNb4HJ-m1e8P9rij4Ao&iflsig=AOEireoAAAAAZB2lmqokJlgPTECTMlNfKPdInKe1j23j&oq=&gs_lcp=Cgdnd3Mtd2l6EAEYAjIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJ1AAWABg6zpoAXAAeACAAQCIAQCSAQCYAQCwAQo&sclient=gws-wiz](https://www.google.com/search?q=gdb+debug+ceph&sxsrf=APwXEddifDvCNu_GMpr9QNwN0SiiIO4cUg%3A1679660938511&source=hp&ei=ipcdZNb4HJ-m1e8P9rij4Ao&iflsig=AOEireoAAAAAZB2lmqokJlgPTECTMlNfKPdInKe1j23j&oq=&gs_lcp=Cgdnd3Mtd2l6EAEYAjIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJzIHCCMQ6gIQJ1AAWABg6zpoAXAAeACAAQCIAQCSAQCYAQCwAQo&sclient=gws-wiz)

[https://lists.ceph.io/hyperkitty/list/dev@ceph.io/thread/NONY2DOWX7SV5LSBE5FVGJN6NDEQQ2ZL/](https://lists.ceph.io/hyperkitty/list/dev@ceph.io/thread/NONY2DOWX7SV5LSBE5FVGJN6NDEQQ2ZL/)

[https://yinminggang.github.io/2018/04/15/how-to-debug-ceph-for-gdb/](https://yinminggang.github.io/2018/04/15/how-to-debug-ceph-for-gdb/)

ninja install

gdb

file ceph-mon

set args -i mon-node1 -c /etc/ceph/ceph.conf

set follow-fork-mode child

set detach-on-fork off

run

info inferiors

inferior 1

set args -i a -c /home/ceph/workspace/ceph/build/ceph.conf

好站点：

[https://www.dovefi.com/post/%E6%B7%B1%E5%85%A5%E7%90%86%E8%A7%A3crush3object%E8%87%B3pg%E6%98%A0%E5%B0%84%E6%BA%90%E7%A0%81%E5%88%86%E6%9E%90/](https://www.dovefi.com/post/%E6%B7%B1%E5%85%A5%E7%90%86%E8%A7%A3crush3object%E8%87%B3pg%E6%98%A0%E5%B0%84%E6%BA%90%E7%A0%81%E5%88%86%E6%9E%90/)

[https://my.oschina.net/leeyoung/blog/777636](https://my.oschina.net/leeyoung/blog/777636)

[https://blog.csdn.net/qq_21539375/article/details/120274190](https://blog.csdn.net/qq_21539375/article/details/120274190)

[https://blog.csdn.net/fingding/article/details/46459095](https://blog.csdn.net/fingding/article/details/46459095)

```
"setupCommands": [
                {
                    "description": "为 gdb 启用整齐打印",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                },
                {
                    "description": "只调试子进程",
                    "text": "set follow-fork-mode child",
                    "ignoreFailures": true
                },
                {
                    "description": "只调试子进程",

                    "text": "set detach-on-fork on",

                    "ignoreFailures": true

                },
            ],

```