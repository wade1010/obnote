### 开启worm失败

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353861.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353723.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353812.jpg)

这个worm是kernelclient里面实现的，但是这个在申威上不适配，底层原理:

```
Ceph是一个分布式的对象存储系统，支持多种数据访问方式，包括块、文件和对象存储。其提供了多种数据保护功能，例如数据备份、数据恢复、数据版本控制等。Ceph并不直接提供WORM（Write Once Read Many）功能，但可以通过一些方法来实现类似的功能。
一种常见的实现方法是在Ceph中使用RADOS Gateway的Object Lock功能。Object Lock允许用户指定一个对象的保留策略，以确保该对象在一段时间内不会被修改或删除。这种保留策略可以通过在对象元数据中设置特殊的标记来实现。一旦对象被锁定，它就无法被修改或删除，直到锁定期满。
使用Object Lock功能可以实现类似于WORM的功能，但需要在应用程序层面上进行一些额外的管理和控制。此外，Object Lock功能需要在RADOS Gateway中启用，因此在使用之前需要进行一些配置和准备工作。
```

源码大概地址

vcmp-agent->src/agent/controlers/x86/cluster/worm.py->open_worm、close_worm相关

目前报错地方时因为如下命令

```
cmd = "echo 1 > {}".format(self.worm_switch_file)
```

```
cmd = "echo 0 > /proc/{}/worm_enable".format(CLUSTER_NAME)
```

这两个命令所写的文件不存在，这个文件是他们在kernel client里面实现的

### agent的agent-worker日志报错

下面不知道为什么

```
2023-06-16 16:16:13,273 INFO /root/workspace/vcmp-agent/src/agent/celery_app/cluster_task.py exec_cmd 105 140228011774336 cmd_info = timeout 8 ceph-fuse -m 192.168.100.24:/ /vcluster/cephfs -o relatime,rbytes,ceph_quota=3,mount_timeout=10,pool_rule_id=5
2023-06-16 16:16:13,324 ERROR /root/workspace/vcmp-agent/src/agent/celery_app/cluster_task.py mount 53 140228011774336 mount fs_info failed out=b'',err=b"2023-06-16T16:16:13.306+0800 7f6860b75080 -1 init, newargv = 0x5608555bf940 newargc=11\nceph-fuse[2633476]: starting ceph client\nfuse: unknown option `relatime'\nceph-fuse[2633476]: fuse failed to start\n2023-06-16T16:16:13.314+0800 7f6860b75080 -1 fuse_lowlevel_new failed\n
```

> 后来我把agent代码改回原来的mount -t了，然后重启下celery， 好像就没这个问题了，用df看是挂载成功饿了
> 


![](https://gitee.com/hxc8/images6/raw/master/img/202407182353811.jpg)

下面是没有haproxy

```
2023-06-16 16:17:45,333 ERROR /root/workspace/vcmp-agent/src/agent/celery_app/node_warn.py get_http_num 1076 140228011774336 <urlopen error [Errno 111] Connection refused>
```

### agent的vcmp-agent.log日志报错

下面是kernelclient相关代码没有

```
2023-06-16 16:18:02,009 ERROR /root/workspace/vcmp-agent/src/agent/utils/cluster/fs_feature/baseinfo.py get_kc_status 68 140043906998656 get_kc_status(): err: b'cat: /proc/ceph/kc_debug: \xe6\xb2\xa1\xe6\x9c\x89\xe9\x82\xa3\xe4\xb8\xaa\xe6\x96\x87\xe4\xbb\xb6\xe6\x88\x96\xe7\x9b\xae\xe5\xbd\x95\n'
2023-06-16 16:18:02,010 INFO /root/workspace/vcmp-agent/src/agent/api/__init__.py write 49 140043906998656 response data = {'status': 'success', 'data': {'data': {'kc_restart': 1, 'ser_restart': 0}, 'errmsg': '', 'errcode': 0}}
2023-06-16 16:18:02,022 INFO /root/workspace/vcmp-agent/src/agent/api/__init__.py initialize 41 140043906998656 request path=/api/cluster/feature_reset method=POST data = b'{"reset": {}}'
2023-06-16 16:18:02,038 ERROR /root/workspace/vcmp-agent/src/agent/utils/cluster/fs_feature/baseinfo.py write_kc_status 86 140043906998656 write_kc_status(): err: b'/bin/sh: /proc/ceph/kc_debug: \xe6\xb2\xa1\xe6\x9c\x89\xe9\x82\xa3\xe4\xb8\xaa\xe6\x96\x87\xe4\xbb\xb6\xe6\x88\x96\xe7\x9b\xae\xe5\xbd\x95\n'

```

上面报错解码后文本内容如下

```
2023-06-16 16:20:02,112 ERROR /root/workspace/vcmp-agent/src/agent/utils/cluster/fs_feature/baseinfo.py get_kc_status 68 140043906998656 get_kc_status(): err: b'cat: /proc/ceph/kc_debug: 没有那个文件或目录\n'
2023-06-16 16:20:02,113 INFO /root/workspace/vcmp-agent/src/agent/api/__init__.py write 49 140043906998656 response data = {'status': 'success', 'data': {'data': {'kc_restart': 1, 'ser_restart': 0}, 'errmsg': '', 'errcode': 0}}
2023-06-16 16:20:02,127 INFO /root/workspace/vcmp-agent/src/agent/api/__init__.py initialize 41 140043906998656 request path=/api/cluster/feature_reset method=POST data = b'{"reset": {}}'
2023-06-16 16:20:02,152 ERROR /root/workspace/vcmp-agent/src/agent/utils/cluster/fs_feature/baseinfo.py write_kc_status 86 140043906998656 write_kc_status(): err: b'/bin/sh: /proc/ceph/kc_debug: 没有那个文件或目录\n'
```

### 性能监控->节点性能->CPU核缺数据|CPU外壳温度

看diamond的源码

```
def collect(self):
        if hasattr(psutil, "sensors_temperatures"):
            sensors_temperatures = psutil.sensors_temperatures()
            cpu_percent = psutil.cpu_percent(percpu=True)
            hostname = socket.gethostname()

            if 'coretemp' in sensors_temperatures.keys():
                k = 0
                physical_id = 'unknown'

                for i, coretemp in enumerate(sensors_temperatures.get("coretemp")):
                    if 'Core' in coretemp[0]:
                        self.publish_metric({
                            "path": "device_core_cpu_{}&{}_{}___{}___{}".format(
                                hostname, physical_id, coretemp[0].replace(' ', ''), coretemp[2], coretemp[3]),
                            "timestamp": int(time.time()),
                            'value': {
                                'current_core_temp': coretemp[1],
                                'cpu_percent': cpu_percent[k],
                            }
                        })

                        k += 1
                    else:
                        physical_id = coretemp[0].replace(' ', '')
                        self.publish_metric({
                        "path": "device_cpu_{}&{}___{}___{}".format(
                            hostname, physical_id, coretemp[2], coretemp[3]),
                        "timestamp": int(time.time()),
                        'value': {
                            'current_temp': coretemp[1],
                        }
                    })
```

这里需要通过psutil判断是否sensors_temperatures

系统硬件温度

我测试了下，戴尔的ubuntu系统服务器是False，所以就不执行后面代码，申威的估计也是False，所以都没有这个数据

### 性能监控->节点性能->网卡读写没有数据

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353949.jpg)

看源码发现需要安装 ifstat   ，ubuntu 直接 apt install ifstat就行

### **性能监控->节点性能->硬盘温度没有数据**

修改diamond源码

一般安装后会放在服务器share目录

替换为下面内容

vim   /usr/share/diamond/collectors/vcfs_device_hard_disk_tempdevice_hard_disk_temp.py

```
# -*- coding: utf-8 -*-
import socket
import time
import re
import platform

import diamond.collector
from utils.common import exec_cmd


class DeviceHardDiskTempCollector(diamond.collector.Collector):

    def get_default_config_help(self):
        config_help = super(DeviceHardDiskTempCollector, self).get_default_config_help()
        config_help.update({
            'coretemp':
                "coretemp, per cpu",
        })
        return config_help

    def get_default_config(self):
        """
        Returns the default collector settings
        """
        config = super(DeviceHardDiskTempCollector, self).get_default_config()
        config.update({
            'path': 'currentcoretemp',
            'coretemp': "True",
        })
        return config

    def collect(self):

        def get_raid_disk(disk_name):
            """
            获取组成raid盘的磁盘
            :param disk_name:
            :return:
            """
            ret = {'code': 1, 'cont': []}
            raid_dev = []
            raid_disk = []
            if disk_name.startswith("/dev/md"):
                cmd = "timeout 10  mdadm --detail %s" % disk_name
                _, out, _ = exec_cmd(cmd)

                raid_disk_info = out.strip().split('\n') if out else []
                index = None
                for r_info in raid_disk_info:
                    t1 = r_info.find("Number")
                    t2 = r_info.find("Major")
                    t3 = r_info.find("Minor")
                    t4 = r_info.find("RaidDevice")
                    t5 = r_info.find("State")

                    if t1 != -1 and t2 != -1 and t3 != -1 and t4 != -1 and t5 != -1:
                        index = raid_disk_info.index(r_info)
                        break

                if index:
                    for i in range(index + 1, len(raid_disk_info)):
                        data = raid_disk_info[i].split()
                        check_dev = ''

                        if data:
                            check_dev = data[-1]
                        if check_dev.startswith('/dev/'):
                            raid_disk.append(check_dev)

            for disk in raid_disk:
                raid_dev.append({'raid_dev': disk})
                
            ret = {'code': 0, 'cont': raid_dev}

            return ret

        disk_temp_dic = {}

        cmd = "fdisk -l | grep '^Disk /dev/'"
        _, out, _ = exec_cmd(cmd)

        lines = out.strip().split('\n') if out else []
        all_dev = []
        for line in lines:
            if not line:
                continue
            tmp = line.split(":")[0]
            if tmp == line:
                tmp = line.split("：")[0]
            name = tmp.split()[1]
            if not name.startswith("/dev/mapper/") and not name.startswith('/dev/rbd') and not name.startswith('/dev/loop') and '/dev/cas' not in name:
                # 如果获取的磁盘是raid盘的话需要获取到组成raid盘的磁盘
                if name.startswith('/dev/md'):
                    disk_name = line.split()[0].strip()
                    ret = get_raid_disk(disk_name)

                    for dev in ret.get('cont'):
                        all_dev.append(re.sub('\d*', '', dev.get('raid_dev')))
                    continue

                all_dev.append(name)
                

        for dev_name in all_dev:
            disk_temp = ''
            cmd = "hddtemp %s" % dev_name
            code, out, err = exec_cmd(cmd)
            
            lines = out.strip().split('\n') if out else []
            if not code and 'not available' not in out and out:
                info = lines[0]
                info = info.split(':')[-1].strip()
                disk_temp = info.split('\xc2\xb0C')[0]
                if 'C' in disk_temp:
                    disk_temp = disk_temp.split('C')[0]

            elif 'arm' not in platform.uname()[-1]:
                #  如果通过hddtemp命令获取不到硬盘温度就用smartctl获取
                cmd = "smartctl -a %s | grep Temperature" % dev_name
                code, out, err = exec_cmd(cmd)
                if not code:
                    for info in out.strip().split('\n'):
                        if info.find('Airflow_Temperature_Cel') != -1:
                            disk_temp = int(info.split('Airflow_Temperature_Cel')[1].split()[7])
                            break
                        elif info.find('Temperature_Internal') != -1:
                            disk_temp = int(info.split('Temperature_Internal')[1].split()[7])
                            break
                        elif info.find('Temperature_Celsius') != -1:
                            disk_temp = int(info.split('Temperature_Celsius')[1].split()[7])
                            break
                else:
                    # todo 这里megaraid后面可以是0,1，再调研下
                    cmd = "smartctl -a -d megaraid,0 %s | grep Temperature" % dev_name
                    code, out, err = exec_cmd(cmd)
                    if not code:
                        for info in out.strip().split('\n'):
                            if info.find('Current Drive Temperature') != -1:
                                disk_temp = int(info.split('Current Drive Temperature')[1].split()[1])
                                break

            disk_temp_dic.setdefault(dev_name, disk_temp)

        hostname = socket.gethostname()

        for dev_name, dev_temp in disk_temp_dic.items():
            if dev_temp != '':
                self.publish_metric({
                    "path": "device_harddisktemp_{}&{}".format(
                        hostname, dev_name.replace("/dev/", "")),
                    "timestamp": int(time.time()),
                    'value': {
                        'dev_temp': float(dev_temp),
                    }
                })



```

主要就是修改了下dell服务器上面或者MegaRaid的 smartctl 使用有所不同

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353067.jpg)

ceph命令成功，插库失败，管理端看不到

### NFS路径错误

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353018.jpg)

变成另外一个路径了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353776.jpg)

暂时先手动

```
mkdir /cephnfs/cephfs/nfs_dir
```

### 存储策略第一次创建比较慢且后续添加失败

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353981.jpg)

### 日志审计->桶/对象用户操作日志开启失败

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353148.jpg)

### 系统管理>用户审计配置失败

### 系统管理 > 数据库集群失败

### **系统管理 > 用户审计 失败**

开启的时候涉及ceph.conf配置，好像不生效

### **系统管理 > DNS负载均衡不生效**

### **系统管理 > 收集系统日志开启失败 **

需要配置免密，但是还是有问题

后来发现缺少一些安装包和syslog下面脚本有点问题

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353767.jpg)

2023-8-18 15:48:45

### 任务->进行中 持续

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353062.jpg)