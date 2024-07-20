[https://community.openvpn.net/openvpn/wiki/OpenVPN3Linux](https://community.openvpn.net/openvpn/wiki/OpenVPN3Linux)

需要翻墙访问

主要信息如下

```
Stable repository - Debian / Ubuntu
Ensure you have the needed support packages already installed:
# apt install apt-transport-https curl
Retrieve the OpenVPN Inc package signing key:
# mkdir -p /etc/apt/keyrings    ### This might not exist in all distributions
# curl -sSfL https://packages.openvpn.net/packages-repo.gpg >/etc/apt/keyrings/openvpn.asc
Replace the DISTRIBUTION part in the command below using the release name from the table above to set up the apt source listing:
# echo "deb [signed-by=/etc/apt/keyrings/openvpn.asc] https://packages.openvpn.net/openvpn3/debian DISTRIBUTION main" >>/etc/apt/sources.list.d/openvpn3.list
Example for Debian 12:
# echo "deb [signed-by=/etc/apt/keyrings/openvpn.asc] https://packages.openvpn.net/openvpn3/debian bookworm main" >>/etc/apt/sources.list.d/openvpn3.list
To install OpenVPN 3 Linux, run these commands:
# apt update
# apt install openvpn

   
Stable repository - Red Hat Enterprise Linux
Red Hat Enterprise Linux 8 and 9 need to install this package:
# yum install https://packages.openvpn.net/openvpn-openvpn3-epel-repo-1-1.noarch.rpm
Red Hat Enterprise Linux 7 and CentOS 7 must install this package instead:
# yum install https://packages.openvpn.net/openvpn-openvpn3-rhel7-repo-1-1.noarch.rpm
In addition, the ​Fedora EPEL package and the corresponding Code Ready Builder (or PowerTools? on CentOS) must be installed.
To install OpenVPN 3 Linux, run this command:
# yum install openvpn3-client
```

使用openvpn

```
openvpn --daemon --askpass --config /root/chen.ovpn --log-append /root/openvpn.log
```

输入密码即可

下面是我外网登录后，ping内网IP

![](https://gitee.com/hxc8/images7/raw/master/img/202407190802671.jpg)

下面是安装时命令行的history

![](https://gitee.com/hxc8/images7/raw/master/img/202407190802073.jpg)