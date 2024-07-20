```
dpkg --list | grep 14.2.9-1
```

vim  uninstall.sh

```
#!/bin/bash

packages=$(dpkg --list | grep 14.2.9-1 | awk '{print $2}')

for package in $packages; do
  sudo apt-get remove --purge -y $package
done

sudo rm -rf /etc/ceph /var/lib/ceph

sudo apt-get update
```