## 文章目录

- 一、什么是 NFS-Subdir-External-Provisioner

- 二、创建 NFS Server 端

	- 关闭防火墙

	- 安装 nfs-utils 和 rpcbind

	- 创建存储数据的文件夹

	- 配置 NFS Server

	- 启动 NFS Server

- 三、创建 ServiceAccount

	- 创建 RBAC 资源文件

	- 部署 RBAC 资源

- 四、部署 NFS-Subdir-External-Provisioner

	- 创建 NFS-Subdir-External-Provisioner 部署文件

	- 部署 NFS-Subdir-External-Provisioner

- 五、创建 NFS SotageClass

	- 创建 StorageClass 资源文件

	- 部署 StorageClass 资源

- 六、创建用于测试的 PVC 资源

	- 创建用于测试的 PVC 资源文件

	- 部署用于测试的 PVC 资源

	- 观察是否自动创建 PV 并与 PVC 绑定

- 七、创建测试的 Pod 资源

	- 创建测试的 Pod 资源文件

	- 部署用于测试的 Pod 资源

	- 进入 NFS Server 服务器验证是否存在测试文件

- 八、清理用于测试的资源

  !版权声明：本博客内容均为原创,每篇博文作为知识积累,写博不易,转载请注明出处。

**系统环境:**

- 操作系统: CentOS 7.9

- Docker 版本: 19.03.13

- Kubernetes 版本: 1.20.2

- NFS Subdir External Provisioner 版本: v4.0.0

**参考地址:**

- NFS Subdir External Provisioner 的 Github 地址

**示例地址:**

- Kubernetes 部署 NFS Subdir External Provisioner 的示例文件

## 一、什么是 NFS-Subdir-External-Provisioner

存储组件 NFS subdir external provisioner 是一个存储资源自动调配器，它可用将现有的 NFS 服务器通过持久卷声明来支持 Kubernetes 持久卷的动态分配。自动新建的文件夹将被命名为 ${namespace}-${pvcName}-${pvName} ，由三个资源名称拼合而成。

> 此组件是对 nfs-client-provisioner 的扩展，nfs-client-provisioner 已经不提供更新，且 nfs-client-provisioner 的 Github 仓库已经迁移到 NFS-Subdir-External-Provisioner 的仓库。


## 二、创建 NFS Server 端

我们先创建 NFS Server 端才能够正常使用 NFS 文件系统，下面介绍下如何在 CentOS 7 系统中安装 NFS Server 的过程。

### 关闭防火墙

为了方便部署，我们直接将防火墙关闭，可以执行下面命令:

```bash
$ systemctl stop firewalld && systemctl disable firewalld

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

### 安装 nfs-utils 和 rpcbind

为了能够正常使用 NFS，我们需要在使用 Kubernetes 集群中的所有服务器上安装以下依赖，命令如下:

```bash
$ yum install -y nfs-utils rpcbind

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

### 创建存储数据的文件夹

创建用于共享数据的文件夹，命令如下:

```bash
# 创建文件夹
$ mkdir /nfs

# 更改归属组与用户
$ chown -R nfsnobody:nfsnobody /nfs

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

### 配置 NFS Server

配置 NFS Server，指定共享文件夹目录以及能够使用共享文件夹的 IP 段，命令如下:

```bash
# 编辑exports
$ vi /etc/exports

# 输入以下内容(格式：FS共享的目录 NFS客户端地址1(参数1,参数2,...) 客户端地址2(参数1,参数2,...))
$ /nfs 192.168.2.0/24(rw,async,no_root_squash)

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

> 如果设置为 /nfs *(rw,async,no_root_squash) 则对所以的 IP 都有效


- 常用选项：

	- ro：客户端挂载后，其权限为只读，默认选项；

	- rw:读写权限；

	- sync：同时将数据写入到内存与硬盘中；

	- async：异步，优先将数据保存到内存，然后再写入硬盘；

	- Secure：要求请求源的端口小于1024

- 用户映射：

	- root_squash:当NFS客户端使用root用户访问时，映射到NFS服务器的匿名用户；

	- no_root_squash:当NFS客户端使用root用户访问时，映射到NFS服务器的root用户；

	- all_squash:全部用户都映射为服务器端的匿名用户；

	- anonuid=UID：将客户端登录用户映射为此处指定的用户uid；

	- anongid=GID：将客户端登录用户映射为此处指定的用户gid

### 启动 NFS Server

可与执行下面命令启动且开机就启动 NFS Server:

```bash
## 重启 rpcbind
$ systemctl restart rpcbind

## 重启 NFS Server 并设置开机就启动
$ systemctl enable nfs && systemctl restart nfs

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

到此我们就成功启动 NFS Server，这里服务器 IP 为 192.168.2.11，NFS 目录为 /nfs。

## 三、创建 ServiceAccount

现在的 Kubernetes 集群大部分是基于 RBAC 的权限控制，所以我们需要创建一个拥有一定权限的 ServiceAccount 与后面要部署的 NFS Subdir Externa Provisioner 组件绑定。

### 创建 RBAC 资源文件

创建 RBAC 资源文件 nfs-rbac.yaml，文件内容如下:

```
apiVersion: v1
kind: ServiceAccount
metadata:
  name: nfs-client-provisioner
  namespace: kube-system
---
kind: ClusterRole
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: nfs-client-provisioner-runner
rules:
  - apiGroups: [""]
    resources: ["persistentvolumes"]
    verbs: ["get", "list", "watch", "create", "delete"]
  - apiGroups: [""]
    resources: ["persistentvolumeclaims"]
    verbs: ["get", "list", "watch", "update"]
  - apiGroups: ["storage.k8s.io"]
    resources: ["storageclasses"]
    verbs: ["get", "list", "watch"]
  - apiGroups: [""]
    resources: ["events"]
    verbs: ["create", "update", "patch"]
---
kind: ClusterRoleBinding
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: run-nfs-client-provisioner
subjects:
  - kind: ServiceAccount
    name: nfs-client-provisioner
    namespace: kube-system
roleRef:
  kind: ClusterRole
  name: nfs-client-provisioner-runner
  apiGroup: rbac.authorization.k8s.io
---
kind: Role
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: leader-locking-nfs-client-provisioner
  namespace: kube-system
rules:
  - apiGroups: [""]
    resources: ["endpoints"]
    verbs: ["get", "list", "watch", "create", "update", "patch"]
---
kind: RoleBinding
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: leader-locking-nfs-client-provisioner
  namespace: kube-system
subjects:
  - kind: ServiceAccount
    name: nfs-client-provisioner
    namespace: kube-system
roleRef:
  kind: Role
  name: leader-locking-nfs-client-provisioner
  apiGroup: rbac.authorization.k8s.io

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

> 注意: 请提前修改里面的 Namespace 名称为你要想部署 Namespace 空间。


### 部署 RBAC 资源

执行 kubectl 命令将 RBAC 文件部署到 Kubernetes 集群，命令如下:

- -f: 指定资源文件名称。

```
$ kubectl apply -f nfs-rbac.yaml

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

## 四、部署 NFS-Subdir-External-Provisioner

设置 NFS-Subdir-External-Provisioner 部署文件，这里将其部署到 kube-system 命令空间中。

### 创建 NFS-Subdir-External-Provisioner 部署文件

创建一个用于部署的 Deployment 资源文件 nfs-provisioner-deploy.yaml，文件内容如下:

```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nfs-client-provisioner
  labels:
    app: nfs-client-provisioner
spec:
  replicas: 1
  strategy: 
    type: Recreate                   ## 设置升级策略为删除再创建(默认为滚动更新)
  selector:
    matchLabels:
      app: nfs-client-provisioner
  template:
    metadata:
      labels:
        app: nfs-client-provisioner
    spec:
      serviceAccountName: nfs-client-provisioner
      containers:
      - name: nfs-client-provisioner
        #image: gcr.io/k8s-staging-sig-storage/nfs-subdir-external-provisioner:v4.0.0
        image: registry.cn-beijing.aliyuncs.com/mydlq/nfs-subdir-external-provisioner:v4.0.0
        volumeMounts:
        - name: nfs-client-root
          mountPath: /persistentvolumes
        env:
        - name: PROVISIONER_NAME     ## Provisioner的名称,以后设置的storageclass要和这个保持一致
          value: nfs-client 
        - name: NFS_SERVER           ## NFS服务器地址,需和valumes参数中配置的保持一致
          value: 192.168.2.11
        - name: NFS_PATH             ## NFS服务器数据存储目录,需和valumes参数中配置的保持一致
          value: /nfs/data
      volumes:
      - name: nfs-client-root
        nfs:
          server: 192.168.2.11       ## NFS服务器地址
          path: /nfs/data            ## NFS服务器数据存储目录

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

> 由于官方镜像存储在 gcr.io 仓库中，国内无法拉取，所以本人将其拉下并存储在阿里云仓库中。


### 部署 NFS-Subdir-External-Provisioner

将组件 NFS-Subdir-External-Provisioner 部署到 Kubernetes 的 kube-system 命名空间下，命令如下:

- -f: 指定资源文件名称。

```bash
$ kubectl apply -f nfs-provisioner-deploy.yaml -n kube-system

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

## 五、创建 NFS SotageClass

我们在创建 PVC 时经常需要指定 storageClassName 名称，这个参数配置的就是一个 StorageClass 资源名称，PVC 通过指定该参数来选择使用哪个 StorageClass，并与其关联的 Provisioner 组件来动态创建 PV 资源。所以，这里我们需要提前创建一个 Storagelcass 资源。

### 创建 StorageClass 资源文件

创建一个 StoageClass 资源文件 nfs-storageclass.yaml，文件内容如下:

```
apiVersion: storage.k8s.io/v1
kind: StorageClass
metadata:
  name: nfs-storage
  annotations:
    storageclass.kubernetes.io/is-default-class: "false"  ## 是否设置为默认的storageclass
provisioner: nfs-client                                   ## 动态卷分配者名称，必须和上面创建的"provisioner"变量中设置的Name一致
parameters:
  archiveOnDelete: "true"                                 ## 设置为"false"时删除PVC不会保留数据,"true"则保留数据
mountOptions: 
  - hard                                                  ## 指定为硬挂载方式
  - nfsvers=4                                             ## 指定NFS版本,这个需要根据NFS Server版本号设置

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

> 上面配置中 Provisioner 参数用于声明 NFS 动态卷提供者的名称，该参数值要和上面部署 NFS-Subdir-External-Provisioner 部署文件中指定的 PROVISIONER_NAME 参数保持一致，即设置为 nfs-storage。


### 部署 StorageClass 资源

将 StorageClass 资源部署到 Kubernetes 集群，命令如下:

- -f: 指定资源文件名称。

```bash
$ kubectl apply -f nfs-storageclass.yaml

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

## 六、创建用于测试的 PVC 资源

创建一个用于测试的 PVC 资源部署到 Kubernetes 中，这样可以测试 NFS-Subdir-External-Provisioner 是否能够自动创建 PV 与该 PVC 进行绑定。

### 创建用于测试的 PVC 资源文件

创建一个用于测试的 PVC 资源文件 test-pvc.yaml，文件内容如下:

```
kind: PersistentVolumeClaim
apiVersion: v1
metadata:
  name: test-pvc
spec:
  storageClassName: nfs-storage    ## 需要与上面创建的storageclass的名称一致
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 1Mi

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

### 部署用于测试的 PVC 资源

将上面创建的用于测试的 PVC 资源部署到 Kubernetes 集群，命令如下:

- -f: 指定资源文件名称。

```bash
$ kubectl apply -f test-pvc.yaml

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

### 观察是否自动创建 PV 并与 PVC 绑定

等待创建完成后观察 NFS-Subdir-External-Provisioner  是否会自动创建 PV 与该 PVC 进行绑定，可以执行下面命令:

- -o: 指定输出的资源内容的格式，一般会设置为为 yaml 格式。

```bash
$ kubectl get pvc test-pvc -o yaml | grep phase

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

可以看到显示如下状态:

```
phase: Bound

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

如果显示 phase 为 Bound，则说明已经创建 PV 且与 PVC 进行了绑定

## 七、创建测试的 Pod 资源

创建一个测试的 Pod 资源部署到 Kubernetes 中，这样就可以测试上面创建的 PVC 是否能够正常使用。

### 创建测试的 Pod 资源文件

创建一个用于测试的 Pod 资源文件 test-pod.yaml，文件内容如下:

```
kind: Pod
apiVersion: v1
metadata:
  name: test-pod
spec:
  containers:
  - name: test-pod
    image: busybox:latest
    command:
      - "/bin/sh"
    args:
      - "-c"
      - "touch /mnt/SUCCESS && exit 0 || exit 1"  ## 创建一个名称为"SUCCESS"的文件
    volumeMounts:
      - name: nfs-pvc
        mountPath: "/mnt"
  restartPolicy: "Never"
  volumes:
    - name: nfs-pvc
      persistentVolumeClaim:
        claimName: test-pvc

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

YAML

### 部署用于测试的 Pod 资源

将上面创建的用于测试的 Pod 资源部署到 Kubernetes 集群，命令如下:

- -f: 指定资源文件名称。

```bash
$ kubectl apply -f test-pod.yaml

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

### 进入 NFS Server 服务器验证是否存在测试文件

进入 NFS Server 服务器的 NFS 挂载目录，检查在 Pod 中创建的文件 SUCCESS 是否存在：

```bash
$ cd /nfs/data && ls -l | grep test-pvc
drwxrwxrwx 2 root root default-test-pvc-pvc-aa2a0b72-8320-40d2-a5ab-9209f0dfee45

$ cd default-test-pvc-pvc-aa2a0b72-8320-40d2-a5ab-9209f0dfee45 && ls -l
-rw-r--r-- 1 root root SUCCESS

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

可以看到已经生成 SUCCESS 该文件，并且可知通过 NFS-Subdir-External-Provisioner 创建的目录命名方式为 namespace名称-pvc名称-pv名称，PV 名称是随机字符串，所以每次只要不删除 PVC，那么 Kubernetes 中的与存储绑定将不会丢失，要是删除 PVC 也就意味着删除了绑定的文件夹，下次就算重新创建相同名称的 PVC，生成的文件夹名称也不会一致，因为 PV 名是随机生成的字符串，而文件夹命名又跟 PV 有关,所以删除 PVC 需谨慎。

## 八、清理用于测试的资源

在测试组件是否能正常使用后，我们需要将上面的测试资源文件进行清理，可以执行下面命令:

```bash
## 删除测试的 Pod 资源文件
$ kubectl delete -f test-pod.yaml 

## 删除测试的 PVC 资源文件
$ kubectl delete -f test-pvc.yaml

```

[](http://www.mydlq.club/article/109/#)[](http://www.mydlq.club/article/109/#)

BASH

---END---