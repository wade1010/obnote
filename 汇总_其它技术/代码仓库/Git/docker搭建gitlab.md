

```javascript
docker run \
    --detach \
    --publish 1443:443 \
    --publish 122:22 \
    --publish 180:80 \
    --name gitlab \
    --privileged=true \
    -v /home/gitlab/etc:/etc/gitlab \
    -v /home/gitlab/log:/var/log/gitlab \
    -v /home/gitlab/data:/var/opt/gitlab \
    gitlab/gitlab-ce

```





vim /home/gitlab/etc/gitlab.rb

添加下面一行内容

```javascript
external_url 'http://192.168.1.118'
gitlab_rails['gitlab_shell_ssh_port'] = 122
```



注意

1、external_url不要加180这个端口。因为docker里面还是80的(根据我们的命令)



2、这里虽然改了端口为122，但是端口映射时，还是要映射到容器的22端口

猜测这里的修改应该是仅作为复制项目地址时端口变为122方便操作，如下图



进入容器，执行命令，使其配置生效

docker exec -it gitlab bash



gitlab-rake "gitlab:password:reset[root]"    修改root 密码



gitlab-ctl reconfigure



退出容器

Ctrl+d





关闭gitlab CI/CD  要不然会出现下面的问题

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000130.jpg)



打开 这个页面 http://192.168.1.118:180/admin/application_settings/ci_cd

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000231.jpg)

去掉这个然后保存就行了





