WebDAV 基于 HTTP 协议的通信协议，在GET、POST、HEAD等几个HTTP标准方法以外添加了一些新的方法，使应用程序可对Web Server直接读写，并支持写文件锁定(Locking)及解锁(Unlock)，还可以支持文件的版本控制。



常用的文件共享有三种：FTP、Samba、WebDAV，它们各有优缺点，了解后才能更好地根据自己的需求选择方案。



FTP属于古老的文件共享方式了，因为安全性，现代浏览器最新已默认不能打开FTP协议。SFTP在FTP基础上增加了加密，在Linux上安装OpenSSH后可以直接用SFTP协议传输。使用SFTP临时传送文件还可以，但做文件共享，性能不高，速度较慢。



Samba是Linux下CIFS协议的实现，优势在于对于小白使用简章，和Windows系统文件共享访问一样，不需要安装第三方软件，而且移动端也有大量APP支持。苹果手机文件APP中添加网络存储用的就是这种方式。Windows下文件共享使用445端口，且不能更改。445端口常常受黑客关照，在广域网上大多运营封掉了访端口，所以这种文件共享只适合在内网使用。



WebDAV 基于 HTTP 协议的通信协议，在GET、POST、HEAD等几个HTTP标准方法以外添加了一些新的方法，使应用程序可对Web Server直接读写，并支持写文件锁定(Locking)及解锁(Unlock)，还可以支持文件的版本控制。因为基于HTTP，在广域网上共享文件有天然的优势，移动端文件管理APP也大多支持WebDAV协议。使用HTTPS还能保安全性。Apache和Nginx支持WebDAV，可作为WebDAV文件共享服务器软件。也可以使用专门的WebDAV软件部署。

WebDAV Server (推荐)

WebDAV 是 GitHub 上开源的项目，基于 Go 语言实现，不仅跨平台，还支持 ARM 架构，可在㠌入式设备中部署 WebDAV 服务器。



项目地址：https://github.com/hacdias/webdav





在 GitHub 下载对应的架构 WebDAV，如：windows-amd64-webdav.zip 。解压后获得 webdav.exe 。



用文本编辑器新建 config.yaml 文件，内容如下：



```javascript
# 监听任意网卡，多网卡可指定对应ip
address: 0.0.0.0
port: 8081
# 如果无需验证填 false
auth: true
# 如果不需要 https 则填 false
tls: true
# https证书和密钥，如果 tls 为 false，cert 和 key 不需要
cert: /data/www/cert/szhome.xf1024.com_nginx/cert.pem
key: /data/www/cert/szhome.xf1024.com_nginx/cert.key
# 访问前缀，建议默认
prefix: /
 
# 如果 auth 为 false 生效，文件共享的路径
scope: /data/users/public
# 是否允许修改
modify: true
rules: []
 
# 跨域设置
cors:
  enabled: true
  credentials: true
  allowed_headers:
    - Depth
  allowed_hosts:
    - http://localhost:8081
  allowed_methods:
    - GET
  exposed_headers:
    - Content-Length
    - Content-Range
 
# 用户信息，如果 auth 为 true 生效
users:
  - username: user1
    password: 123456
    scope: /data/users/2021
  - username: user2
    password: 654321
    scope: /data/users/2022
```

注意 yaml 文件格式的书写规则，users 下是需认证的用户名，密码，及用户共享文件。



使用时使用命令：



```javascript
webdav -c ./config.yaml
```

Apache 开启 WebDAV

Apache 开启 WebDAV 需要打开以下模块：



LoadModule dav_module modules/mod_dav.so

LoadModule dav_fs_module modules/mod_dav_fs.so

LoadModule dav_lock_module modules/mod_dav_lock.so



配置如下：



```javascript
<VirtualHost *:80>
    ServerName dav.engr-z.com
    DocumentRoot /data/webdav
    <Directory "/data/webdav">
        Options Indexes FollowSymLinks
        AllowOverride None
        Dav on
        AuthType Basic
        AuthName "WebDAV Upload"
        AuthUserFile conf/.htpasswd
        AuthBasicProvider file
        Require user webdav
    </Directory>
</VirtualHost>
 
<VirtualHost *:443>
    ServerName dav.engr-z.com
    DocumentRoot /data/webdav
    <Directory "/data/webdav">
        Options Indexes FollowSymLinks
        AllowOverride None
        Dav on
        AuthType Basic
        AuthName "WebDAV Upload"
        AuthUserFile conf/.htpasswd
        AuthBasicProvider file
        Require user webdav
    </Directory>
#	Header always set Strict-Transport-Security "max-age=63072000; includeSubdomains; preload"
    # 添加 SSL 协议支持协议，去掉不安全的协议
    SSLProtocol all -SSLv2 -SSLv3
    # 修改加密套件如下
    SSLCipherSuite HIGH:!RC4:!MD5:!aNULL:!eNULL:!NULL:!DH:!EDH:!EXP:+MEDIUM
    SSLHonorCipherOrder on
    # 证书公钥配置
    SSLCertificateFile cert/dav.engr-z.com_apache/public.crt
    # 证书私钥配置
    SSLCertificateKeyFile cert/dav.wangzhengzhen.com_apache/cert.key
    # 证书链配置，如果该属性开头有 '#'字符，请删除掉
    SSLCertificateChainFile cert/dav.engr-z.com_apache/chain.crt
</VirtualHost>
```

.htpasswd 文件是保存用户名密码的文件，使用 apache 工具 htpasswd 创建：



htpasswd -c /etc/webdav/.htpasswd user1

如果需要创建多个用户，在第二次执行时注意去掉 -c 参数，防止生成文件覆盖。



Nginx 开启 WebDAV

在Nginx中实现WebDAV需要安装 libnginx-mod-http-dav-ext 模块，以下是Nginx的配置：



```javascript
server {
        listen 80;
        listen [::]:80;
 
        server_name dav.engr-z.com;
        auth_basic "Authorized Users Only";
        auth_basic_user_file /etc/.htpasswd;
 
        location / {
                root /data/webdav;
                client_body_temp_path /var/temp;
                dav_methods PUT DELETE MKCOL COPY MOVE;
                dav_ext_methods PROPFIND OPTIONS;
                create_full_put_path on;
                client_max_body_size 10G;
        }
}
 
server {
        listen 443;
        listen [::]:443;
        server_name dav.engr-z.com;
 
        ssl on;
        ssl_certificate /data/www/cert/dav.engr-z.com_nginx/cert.pem;
        ssl_certificate_key /data/www/cert/dav.engr-z.com_nginx/cert.key;
        ssl_session_timeout 5m;
        ssl_protocols SSLv2 SSLv3 TLSv1;
        ssl_ciphers ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP;
        ssl_prefer_server_ciphers on;
 
        location / {
                root /data/webdav;
                client_body_temp_path /var/temp;
                dav_methods PUT DELETE MKCOL COPY MOVE;
                dav_ext_methods PROPFIND OPTIONS;
                create_full_put_path on;
                client_max_body_size 10G;
        }
 
}
```

.htpasswd 用户密码文件的创建方式和 Apache 一样，htpasswd是apache的工具，如果使用nginx，可以单独安装该工具而不安装整个apache。在Ubuntu中使用 sudo apt install apache2-utils 安装。



Nginx 对 WebDAV 支持不是太好，建议使用 Apache 或专用于 WebDAV 服务软件架设。



WebDAV挂载/映射

Windows

打开 “计算机” ，点右键添加一个网络位置，按向导填入地址，用户名，密码。











挂载指定盘符：



net use Y: https://dav.engr-z.com/ /user:engrz /persistent:YES 密码

其中 qizheng 是我的用户名

密码把 password 换成对应的密码。

/persistent 表示保存映射，下次开机还在。

执行完，打开资源管理器，可以看到磁盘映射了。

如果下次开机，发现不能打开磁盘，访问失败，可以检查 WebClient 服务是否开启。







从Windows Vista起，微软就禁用了http形式的基本WebDAV验证形式（KB841215），必须使用https连接。我们可以修改注册表……



HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WebClient\Parameters

找到BasicAuthLevel把这个值从1改为2，然后进控制面板，服务，把WebClient服务重启（没有启动的就启动它）。







在某些版本的 Windows 操作系统中，WebDAV 驱动器的最大文件大小被限制为 50MB。如果你试图复制超过 50MB 大小的文件，Windows 就会弹出错误提示框。当然，这个限制是可以通过修改注册表来消除的。



将注册表中位于

HKLM\SYSTEM\CurrentControlSet\Services\WebClient\Parameters\FileSizeLimitInBytes

处的键值由 50000000 (50MB) 修改为更大的数值。最大修改为：4294967295（0xffffffff）字节，即4G。







这里推荐使用免费软件 RaiDrive ，通过 RaiDrive 映射的磁盘，没有 http 和 上传文件大小限制，无需修改注册表。



RaiDrive 是一款能够将一些网盘映射为本地网络磁盘的工具，支持 Google Drive、Google Photos、Dropbox、OneDrive、FTP、SFTP、WebDAV。







下载地址：https://www.raidrive.com



Linux

Linux 的文件管理工具大多都支持 WebDAV ，以 Ubuntu 为例：







还可以使用命令挂载，需要安装 davfs2 ：



apt install davfs2

执行命令后系统会自动安装，出现以下提示，选是。







挂载：



sudo mount -t davfs http://dav.engr-z/ ./webdav/

————————————————

版权声明：本文为CSDN博主「攻城狮·正」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：https://blog.csdn.net/ghdqfhw/article/details/113965986