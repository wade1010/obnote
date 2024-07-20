~# cephadm --image quay.io/ceph/ceph:v17.2 bootstrap --mon-ip 192.168.10.147 --allow-fqdn-hostname --skip-firewalld --skip-mon-network --dashboard-password-noupdate --skip-monitoring-stack  --allow-overwrite **--skip-pull**

Verifying podman|docker is present...

Verifying lvm2 is present...

Verifying time synchronization is in place...

Unit chrony.service is enabled and running

Repeating the final host check...

podman|docker (/usr/bin/docker) is present

systemctl is present

lvcreate is present

Unit chrony.service is enabled and running

Host looks OK

Cluster fsid: d38f9242-44e8-11ee-adbb-ff491036a09d

Verifying IP 192.168.10.147 port 3300 ...

Verifying IP 192.168.10.147 port 6789 ...

Pulling container image quay.io/ceph/ceph:v17.2...

Extracting ceph user uid/gid from container image...

Creating initial keys...

Creating initial monmap...

Creating mon...

Waiting for mon to start...

Waiting for mon...

mon is available

Assimilating anything we can from ceph.conf...

Generating new minimal ceph.conf...

Restarting the monitor...

Creating mgr...

Verifying port 9283 ...

Wrote keyring to /etc/ceph/ceph.client.admin.keyring

Wrote config to /etc/ceph/ceph.conf

Waiting for mgr to start...

Waiting for mgr...

mgr not available, waiting (1/10)...

mgr not available, waiting (2/10)...

mgr not available, waiting (3/10)...

mgr not available, waiting (4/10)...

mgr not available, waiting (5/10)...

mgr not available, waiting (6/10)...

mgr not available, waiting (7/10)...

mgr not available, waiting (8/10)...

mgr is available

Enabling cephadm module...

Waiting for the mgr to restart...

Waiting for Mgr epoch 5...

Mgr epoch 5 is available

Setting orchestrator backend to cephadm...

Generating ssh key...

Wrote public SSH key to to /etc/ceph/ceph.pub

Adding key to root@localhost's authorized_keys...

Adding host node1...

Deploying mon service with default placement...

Deploying mgr service with default placement...

Deploying crash service with default placement...

Enabling the dashboard module...

Waiting for the mgr to restart...

Waiting for Mgr epoch 9...

Mgr epoch 9 is available

Generating a dashboard self-signed certificate...

Creating initial admin user...

Fetching dashboard port number...

Ceph Dashboard is now available at:

```
         URL: 
        User: admin
    Password: hrsyp1dyin

```

You can access the Ceph CLI with:

```
    sudo /usr/sbin/cephadm shell --fsid d38f9242-44e8-11ee-adbb-ff491036a09d -c /etc/ceph/ceph.conf -k /etc/ceph/ceph.client.admin.keyring

```

Please consider enabling telemetry to help improve Ceph:

```
    ceph telemetry on

```

For more information see:

```
    

```

Bootstrap complete.

[https://blog.csdn.net/weixin_42397937/article/details/128398850](https://blog.csdn.net/weixin_42397937/article/details/128398850)