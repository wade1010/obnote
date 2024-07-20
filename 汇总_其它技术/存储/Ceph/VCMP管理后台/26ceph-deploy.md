server\4_Ceph\ceph_deploy\ceph-deploy-2.0.1\ceph_deploy\hosts\remotes.py

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353402.jpg)

```
    # TODO 暂时适配centos8
    version_parts = release.split(".")
    version_num = float(".".join(version_parts[:2]))
    if 'centos' in distro.lower() and version_num >= 8:
        codename = 'stretch'
        distro = 'debian'
        release = 9
    # TODO 暂时适配申威
    elif 'uos' in distro.lower():  # this could be sw
        codename = 'stretch'
        distro = 'debian'
        release = 9
```