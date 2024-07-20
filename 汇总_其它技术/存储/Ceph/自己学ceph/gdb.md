[https://zhuanlan.zhihu.com/p/550525330](https://zhuanlan.zhihu.com/p/550525330)

[root@ceph04 src]# cat ~/.gdbinit

set history save on    

set print pretty on

set pagination off

set confirm off

set follow-fork-mode child

set detach-on-fork off

vim ~/.gdbinit

```
{
    "configurations": [
        {
            "name": "(gdb) Attach",
            "type": "cppdbg",
            "request": "attach",
            "program": "${workspaceFolder}/build/bin/radosgw",
            "processId": "${command:pickProcess}",
            "MIMode": "gdb",
            "setupCommands": [
                {
                    "description": "Enable pretty-printing for gdb",
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
                }
            ]
        }
    ]
}
```

sudo gdb -ex 'set follow-fork-mode child' -p $(pidof radosgw)

sudo gdb  -p $(pidof radosgw)

```
usage: ../src/vstart.sh [option]... 
ex: MON=3 OSD=1 MDS=1 MGR=1 RGW=1 NFS=1 ../src/vstart.sh -n -d
options:
	-d, --debug
	-t, --trace
	-s, --standby_mds: Generate standby-replay MDS for each active
	-l, --localhost: use localhost instead of hostname
	-i <ip>: bind to specific ip
	-n, --new
	--valgrind[_{osd,mds,mon,rgw}] 'toolname args...'
	--nodaemon: use ceph-run as wrapper for mon/osd/mds
	--redirect-output: only useful with nodaemon, directs output to log file
	--smallmds: limit mds cache memory limit
	-m ip:port		specify monitor address
	-k keep old configuration files (default)
	-x enable cephx (on by default)
	-X disable cephx
	-g --gssapi enable Kerberos/GSSApi authentication
	-G disable Kerberos/GSSApi authentication
	--hitset <pool> <hit_set_type>: enable hitset tracking
	-e : create an erasure pool	-o config		 add extra config parameters to all sections
	--rgw_port specify ceph rgw http listen port
	--rgw_frontend specify the rgw frontend configuration
	--rgw_compression specify the rgw compression plugin
	--seastore use seastore as crimson osd backend
	-b, --bluestore use bluestore as the osd objectstore backend (default)
	-f, --filestore use filestore as the osd objectstore backend
	-K, --kstore use kstore as the osd objectstore backend
	--cyanstore use cyanstore as the osd objectstore backend
	--memstore use memstore as the osd objectstore backend
	--cache <pool>: enable cache tiering on pool
	--short: short object names only; necessary for ext4 dev
	--nolockdep disable lockdep
	--multimds <count> allow multimds with maximum active count
	--without-dashboard: do not run using mgr dashboard
	--bluestore-spdk: enable SPDK and with a comma-delimited list of PCI-IDs of NVME device (e.g, 0000:81:00.0)
	--msgr1: use msgr1 only
	--msgr2: use msgr2 only
	--msgr21: use msgr2 and msgr1
	--crimson: use crimson-osd instead of ceph-osd
	--crimson-foreground: use crimson-osd, but run it in the foreground
	--osd-args: specify any extra osd specific options
	--bluestore-devs: comma-separated list of blockdevs to use for bluestore
	--bluestore-zoned: blockdevs listed by --bluestore-devs are zoned devices (HM-SMR HDD or ZNS SSD)
	--bluestore-io-uring: enable io_uring backend
	--inc-osd: append some more osds into existing vcluster
	--cephadm: enable cephadm orchestrator with ~/.ssh/id_rsa[.pub]
	--no-parallel: dont start all OSDs in parallel
	--jaeger: use jaegertracing for tracing
	--seastore-devs: comma-separated list of blockdevs to use for seastore
	--seastore-secondary-des: comma-separated list of secondary blockdevs to use for seastore
```

MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh --debug --new -x --localhost --bluestore

./bin/radosgw-admin user modify --uid=testid --access-key=admin --secret-key=adminadmin

在**launch.json**文件中，可以使用**breakpoints**属性来配置断点信息。**breakpoints**属性是一个数组，其中每个元素表示一个断点。每个断点对象包含以下属性：

- name：断点的名称。

- condition：断点的条件。只有当条件满足时，断点才会触发。

- hitCondition：断点的触发条件。可以是次数或表达式。

- logMessage：断点触发时输出的消息。

- source：断点所在的源代码文件。

- line：断点所在的行号。

以下是一个示例**launch.json**文件，其中包含两个断点：

```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "My Debug",
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/build/program",
            "args": [],
            "stopAtEntry": false,
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": true,
            "MIMode": "gdb",
            "miDebuggerPath": "/usr/bin/gdb",
            "preLaunchTask": "build",
            "setupCommands": [
                {
                    "description": "Enable pretty-printing for gdb",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                }
            ],
            "breakpoints": [  //没有用
                {
                    "name": "Breakpoint 1",
                    "source": "${workspaceFolder}/src/file1.cpp",
                    "line": 10,
                    "condition": "x == 5",
                    "hitCondition": "1",
                    "logMessage": "Breakpoint 1 triggered"
                },
                {
                    "name": "Breakpoint 2",
                    "source": "${workspaceFolder}/src/file2.cpp",
                    "line": 20,
                    "condition": "y == 10",
                    "hitCondition": "> 5",
                    "logMessage": "Breakpoint 2 triggered"
                }
            ]
        }
    ]
}

```