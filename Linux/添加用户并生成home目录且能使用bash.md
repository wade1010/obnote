useradd默认是不生成home目录的

下面是常见用户，并生成home目录，且使其能使用bash
sudo useradd -m -s /bin/bash newuser
sudo passwd newuser
sudo visudo

```
cstu ALL=(ALL) NOPASSWD: ALL
```
