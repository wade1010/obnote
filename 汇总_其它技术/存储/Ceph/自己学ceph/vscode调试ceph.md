MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh --debug --new -x --localhost --bluestore

./bin/ceph osd pool create rbd 1 1

./bin/rados put cconf ./ceph.conf -p rbd

```
{
    "configurations": [
        
    {
        "name": "(gdb) Attach",
        "type": "cppdbg",
        "request": "attach",
        "program": "${workspaceFolder}/build/bin/ceph-mon",
        "processId": "${command:pickProcess}",
        "MIMode": "gdb",
        "setupCommands": [
            {
                "description": "Enable pretty-printing for gdb",
                "text": "-enable-pretty-printing",
                "ignoreFailures": true
            }
        ]
    }
    ]
}

```