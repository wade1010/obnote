### yml配置文件

k8s-clean-data-pvc.yaml    对应output目录，目前给处理文档的最终结果txt目录

```
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: clean-data-nfs-pvc
  namespace: hanbo
spec:
  storageClassName: nfs-client-hanbo
  accessModes:
    - ReadWriteMany
  resources:
    requests:
      storage: 800Gi
```

k8s-clean-data.yaml

```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: clean-data-deploy
  namespace: hanbo
  labels:
    app: clean-data-deploy
spec:
  replicas: 3
  selector:
    matchLabels:
      app: clean-data-pod
  template:
    metadata:
      labels:
        app: clean-data-pod
    spec:
      volumes:
        - name: clean-data-nfs-storage
          persistentVolumeClaim:
            claimName: clean-data-nfs-pvc
      containers:
        - name: clean-data
          image: harbor.pepris807.com/hanbo/centos7.9-clean-data
          volumeMounts:
            - mountPath: "/app/output"
              name: clean-data-nfs-storage
              subPath: output
            - mountPath: "/app/datasets"
              name: clean-data-nfs-storage
              subPath: datasets
          ports:
            - containerPort: 8000
```

k8s-clean-data-svc.yaml

```
apiVersion: v1
kind: Service
metadata:
  name: clean-data-service
  namespace: hanbo
spec:
  ports:
    - name: http
      protocol: TCP
      port: 8000
      targetPort: 8000
      nodePort: 31111
  selector:
    app: clean-data-pod
  type: NodePort
```

### 测试yml是否可行

kubectl apply -f k8s-clean-data-pvc.yaml --dry-run=server

kubectl apply -f k8s-clean-data.yaml --dry-run=server

kubectl apply -f k8s-clean-data-svc.yaml --dry-run=server

### 创建 namespace

kubectl create namespace hanbo

kubectl get ns # 查看

### 创建pvc

kubectl apply -f k8s-clean-data-pvc.yaml 

查看pvc

kubectl get pvc --namespace hanbo

### 创建clean-data

kubectl apply -f k8s-clean-data.yaml

删除clean-data

kubectl delete -f k8s-clean-data.yaml

### 部署成功验证

```
$ kubectl get pods --namespace hanbo
NAME                                 READY   STATUS    RESTARTS   AGE

clean-data-deploy-565b47b54f-db7z6   1/1     Running   0          35m
clean-data-deploy-565b47b54f-ks8nv   1/1     Running   0          35m
clean-data-deploy-565b47b54f-xs6c5   1/1     Running   0          35m
```

### 查看日志

kubectl logs clean-data-deploy-565b47b54f-kvsk4 --namespace hanbo

### 创建service

kubectl apply -f k8s-clean-data-svc.yaml

### 查看service

运行以下命令获取服务的 NodePort 端口号：

```bash
kubectl get svc clean-data-service  --namespace hanbo
```


在返回的输出中，查找 clean-data-service 服务的 NodePort 端口号。

	- 使用任何节点的外部 IP 地址和上一步中获取的 NodePort 端口号，即可通过 <NodeIP>:<NodePort> 访问这些 Pod。例如：<NodeIP>:<NodePort>。

#### 查看service信息

```
$ kubectl describe svc clean-data-service --namespace hanbo
Name:                     clean-data-service
Namespace:                hanbo
Labels:                   <none>
Annotations:              <none>
Selector:                 app=clean-data-pod
Type:                     NodePort
IP Family Policy:         SingleStack
IP Families:              IPv4
IP:                       10.68.218.181
IPs:                      10.68.218.181
Port:                     http  8000/TCP
TargetPort:               8000/TCP
NodePort:                 http  31111/TCP
Endpoints:                172.20.204.206:8000,172.20.220.201:8000,172.20.49.199:8000
Session Affinity:         None
External Traffic Policy:  Cluster
Events:                   <none>
```

查看pods

kubectl get pods --namespace hanbo

进入其中一个pod

kubectl --namespace hanbo exec -it clean-data-deploy-565b47b54f-kvsk4 -- /bin/bash

然后执行

```
curl http://10.68.212.24:8888/api/v1/a1
```

再执行

```
curl http://10.68.212.24:8888/api/v1/a2
```

ps:上述两个端口是不存在的，只是为了查看日志。

退出容器

然后查看3个容器的日志

发现会随机(或者是某种负载均衡算法)到两个机器上

![](https://gitee.com/hxc8/images0/raw/master/img/202407172035374.jpg)

扩容和缩容，修改下图的值

![](https://gitee.com/hxc8/images0/raw/master/img/202407172035143.jpg)

然后执行 apply

kubectl apply -f k8s-clean-data.yaml

扩容效果图

![](https://gitee.com/hxc8/images0/raw/master/img/202407172035296.jpg)

缩容效果图

![](https://gitee.com/hxc8/images0/raw/master/img/202407172035107.jpg)

### 执行生产者

根据service的信息得到ip 是10.68.218.181

```
$ kubectl describe svc clean-data-service --namespace hanbo
Name:                     clean-data-service
Namespace:                hanbo
Labels:                   <none>
Annotations:              <none>
Selector:                 app=clean-data-pod
Type:                     NodePort
IP Family Policy:         SingleStack
IP Families:              IPv4
IP:                       10.68.218.181
IPs:                      10.68.218.181
Port:                     http  8000/TCP
TargetPort:               8000/TCP
NodePort:                 http  31111/TCP
Endpoints:                172.20.204.208:8000,172.20.220.203:8000,172.20.49.202:8000
Session Affinity:         None
External Traffic Policy:  Cluster
Events:                   <none>
```

进入上面信息中Endpoints中任意节点执行 curl http://10.68.218.181:8000/api/v1/start_clean_data

![](https://gitee.com/hxc8/images0/raw/master/img/202407172035778.jpg)

执行需要几秒钟，期间会把所有文件的所有信息按条push到消息队列（push的内容如:文件名__文件下载url）

然后3个pod中消费进程就开始消费消息队列，进行文件处理