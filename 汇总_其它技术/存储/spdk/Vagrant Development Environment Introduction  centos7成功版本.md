brew install vagrant

brew install virtualbox-extension-pack

brew install virtualbox



git clone https://github.com/spdk/spdk



cd spdk



git submodule update --init



cd spdk/scripts/vagrant



vagrant plugin install vagrant-proxyconf

sudo mkdir -p /var/lib/libvirt/images

sudo chmod -R 777 /var/lib/libvirt



以上步骤直接复制Ubuntu失败的版本步骤



lspci | grep "Non-Volatile"   这里就显示了。Ubuntu那个不显示

```javascript
[vagrant@localhost ~]$ lspci | grep "Non-Volatile"
00:0e.0 Non-Volatile memory controller: InnoTek Systemberatung GmbH Device 4e56
```



cd spdk_repo/spdk



yum clean all

yum makecache

sudo yum -y update





sudo scripts/pkgdep.sh



./configure



make



sudo ./scripts/setup.sh



```javascript
[vagrant@localhost spdk]$ sudo ./scripts/setup.sh
0000:00:0e.0 (80ee 4e56): nvme -> uio_pci_generic

## ERROR: requested 1024 hugepages but 846 could be allocated on node0.
## Memory might be heavily fragmented. Please try flushing the system cache, or reboot the machine.
```



reboot



vagrant ssh



cd spdk_repo/spdk



sudo ./scripts/setup.sh   #绑定设备



> 关闭虚机后，重启步骤：打开virtual box 然后 进入到虚机目录 执行  vagrant up



```javascript
[vagrant@localhost spdk]$ sudo ./scripts/setup.sh
0000:00:0e.0 (80ee 4e56): nvme -> uio_pci_generic
```



sudo build/examples/hello_world

```javascript
[vagrant@localhost spdk]$ sudo build/examples/hello_world
TELEMETRY: No legacy callbacks, legacy socket not created
Initializing NVMe Controllers
Attaching to 0000:00:0e.0
Attached to 0000:00:0e.0
  Namespace ID: 1 size: 1GB
Initialization complete.
INFO: using host memory buffer for IO
Hello world!

Message from syslogd@localhost at Jan 12 09:30:06 ...
 kernel:Disabling IRQ #22
```



sudo build/examples/hello_world

```javascript
[vagrant@localhost spdk]$ sudo build/examples/hello_world
TELEMETRY: No legacy callbacks, legacy socket not created
Initializing NVMe Controllers
Attaching to 0000:00:0e.0
Attached to 0000:00:0e.0
  Namespace ID: 1 size: 1GB
Initialization complete.
INFO: using host memory buffer for IO
Hello world!
```



切换为 root



sudo su