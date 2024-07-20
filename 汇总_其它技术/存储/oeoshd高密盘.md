mkdir /opt/oeos



mv oeos-hd /opt/oeos



chmod +x /opt/oeos/oeos-hd



vi /etc/init.d/oeos



```javascript
#!/bin/bash
# chkconfig: 3 88 88
/opt/oeos/oeos-hd server http://127.0.0.1/opt/oeos/.data/disk\{1...4\} --console-address :19003 --address :19002 --secret xxxxxxx
```

  



chmod +x /etc/init.d/oeos



chkconfig --add oeos



chkconfig --list oeos









e版x86在pve-hg-06 



页面访问：

http://10.10.21.9:19003

用户名 oeosadmin

密码     oeosadmin



S3 API调用:

http://10.10.21.9:19002

用户名 oeosadmin

密码     oeosadmin









arm C 版

页面访问：

http://192.168.122.1:19003

用户名 oeosadmin

密码     oeosadmin



S3 API调用:

http://192.168.122.1:19002

用户名 oeosadmin

密码     oeosadmin





arm e版

页面访问：

http://10.10.21.114:19003

用户名 oeosadmin

密码     oeosadmin



S3 API调用:

http://10.10.21.114:19002

用户名 oeosadmin

密码     oeosadmin



