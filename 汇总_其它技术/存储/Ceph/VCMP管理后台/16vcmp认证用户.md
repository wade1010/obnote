![](https://gitee.com/hxc8/images6/raw/master/img/202407182354241.jpg)

添加报错

```
err: b'/bin/sh: 行 2: smbpasswd：未找到命令\n']
2023-06-16 17:16:10,659 ERROR /root/workspace/vcmp-agent/src/agent/controlers/x86/cluster/user_group.py add 114 139792061350272 action err: 设置用户SMB密码失败

```

apt install samba samba-client -y

再添加报错

```
[timeout 60 useradd -u 10000 -g 'default_group' 'testuser1' -s /bin/bash] [out: b''] [err: b'useradd：用户“testuser1”已存在\n']

```