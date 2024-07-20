查看现有的configmap

```
$ kubectl get cm 
NAME               DATA   AGE
kube-root-ca.crt   1      3d
ngx-conf           1      2d21h
```

查看其中某个配置的详情

```
$ kubectl describe cm ngx-conf
Name:         ngx-conf
Namespace:    default
Labels:       <none>
Annotations:  <none>

Data
====
default.conf:
----
server {
  listen 80;
  location / {
    default_type text/plain;
    return 200
      'srv : $server_addr:$server_port\nhost: $hostname\nuri : $request_method $host $request_uri\ndate: $time_iso8601\n';
  }
}


BinaryData
====

Events:  <none>
```

新增一个configmap

```
$ kubectl create configmap cm1 --from-literal=host=127.0.0.1 --from-literal=port=3306
configmap/cm1 created
```

查看新增的configmap的信息

```
$ kubectl describe cm cm1
Name:         cm1
Namespace:    default
Labels:       <none>
Annotations:  <none>

Data
====
host:
----
127.0.0.1
port:
----
3306

BinaryData
====

Events:  <none>
```

查看现有configmap

```
$ kubectl get cm 
NAME               DATA   AGE
cm1                2      39s
kube-root-ca.crt   1      3d
ngx-conf           1      2d21h
```

删除某个configmap

```
$ kubectl delete cm cm1 
configmap "cm1" deleted
```

再次查看configmap

```
$ kubectl get cm 
NAME               DATA   AGE
kube-root-ca.crt   1      3d
ngx-conf           1      2d21h
```

[kube_configmap.py](attachments/WEBRESOURCEa924dc5fa1aaaa62d5b03e76b00a31dfkube_configmap.py)

[test.py](attachments/WEBRESOURCEd66342b4b98e6d335557039f2cec1513test.py)