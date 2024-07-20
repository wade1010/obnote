vim redis.yaml

```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: redis-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: redis
  template:
    metadata:
      labels:
        app: redis
    spec:
      containers:
        - name: redis
          image: redis:latest
          ports:
            - containerPort: 6379
          resources:
            limits:
              memory: "256Mi"
              cpu: "500m"
---
apiVersion: v1
kind: Service
metadata:
  name: redis-service
spec:
  selector:
    app: redis
  ports:
    - protocol: TCP
      port: 6379
      targetPort: 6379
```

在 Kubernetes 上创建 Redis 服务，你可以使用 YAML 文件定义 Redis 服务的配置，并通过 Kubernetes 命令行工具（kubectl）来创建。下面是一个示例的 Redis 服务的 YAML 配置文件：

```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: redis-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: redis
  template:
    metadata:
      labels:
        app: redis
    spec:
      containers:
        - name: redis
          image: redis:latest
          ports:
            - containerPort: 6379
          resources:
            limits:
              memory: "256Mi"
              cpu: "500m"
---
apiVersion: v1
kind: Service
metadata:
  name: redis-service
spec:
  selector:
    app: redis
  ports:
    - protocol: TCP
      port: 6379
      targetPort: 6379

```

在这个示例中，我们定义了一个名为 redis-deployment 的 Deployment，它使用 Redis 镜像，并指定了资源限制（内存和 CPU）。同时，我们还定义了一个名为 redis-service 的 Service，用于将 Redis 服务暴露给其他应用程序。

要在 Kubernetes 上创建 Redis 服务，你可以将上述 YAML 配置文件保存为 redis.yaml，然后使用以下命令创建：

```shell
kubectl apply -f redis.yaml

```

执行上述命令后，Kubernetes 将会创建一个 Redis Deployment 和一个对应的 Service，并为其分配一个 Cluster IP 地址。你可以使用 kubectl get 命令来查看创建的服务：

```shell
kubectl get deployment
kubectl get service

```

你将看到类似以下输出：

```
NAME                READY   UP-TO-DATE   AVAILABLE   AGE
redis-deployment    1/1     1            1           1m

NAME               TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)    AGE
redis-service      ClusterIP   10.100.200.10   <none>        6379/TCP   1m

```

现在，你可以在其他应用程序中使用 redis-service 的 Cluster IP 地址（这里是 10.100.200.10）来访问 Redis 服务。

请注意，上述示例只是一个基本的 Redis 服务配置，你可能需要根据自己的需求进行调整和扩展。另外，确保你的 Kubernetes 集群已经正确配置，并且你具有足够的权限来创建服务。