

| 系统 | 日志位置 |
| - | - |
| Ubuntu(14.04) | /var/log/upstart/docker.log |
| Ubuntu(16.04) | journalctl -u docker.service |
| CentOS 7/RHEL 7/Fedora | journalctl -u docker.service |
| CoreOS | journalctl -u docker.service |
| OpenSuSE | journalctl -u docker.service |
| OSX | ~/Library/Containers/com.docker.docker/Data/com.docker.driver.amd64-linux/log/d‌​ocker.log |
| Debian GNU/Linux 7 | /var/log/daemon.log |
| Debian GNU/Linux 8 | journalctl -u docker.service |
| Boot2Docker | /var/log/docker.log |


