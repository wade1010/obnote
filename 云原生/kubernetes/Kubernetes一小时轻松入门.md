![](https://gitee.com/hxc8/images0/raw/master/img/202407172036014.jpg)

[https://geekhour.net/2023/12/23/kubernetes/](https://geekhour.net/2023/12/23/kubernetes/)

# 1. 使用

[minikube](https://minikube.sigs.k8s.io/)只能用来在本地搭建一个单节点的kubernetes集群环境，

下面介绍如何使用[Multipass](https://multipass.run/)和[k3s](https://k3s.io/)来搭建一个多节点的kubernetes集群环境，

[Multipass](https://multipass.run/)是一个轻量级的虚拟机管理工具，

可以用来在本地快速创建和管理虚拟机，

相比于VirtualBox或者VMware这样的虚拟机管理工具，

[Multipass](https://multipass.run/)更加轻量快速，

而且它还提供了一些命令行工具来方便我们管理虚拟机。

官方网址: [https://Multipass.run/](https://multipass.run/)

|   | 


关于Multipass的一些常用命令我们可以通过multipass help来查看，

这里大家只需要记住几个常用的命令就可以了，

|   | 


## 1.2 

[k3s](https://k3s.io/) 是一个轻量级的[Kubernetes](https://kubernetes.io/)发行版，它是 [Rancher Labs](https://www.rancher.com/) 推出的一个开源项目，

旨在简化[Kubernetes](https://kubernetes.io/)的安装和维护，同时它还是CNCF认证的[Kubernetes](https://kubernetes.io/)发行版。

首先我们需要使用multipass创建一个名字叫做k3s的虚拟机，

```
multipass launch --name k3s --cpus 2 --memory 8G --disk 10G
```

执行如下，大概执行了2分钟

```
~# multipass launch --name k3s --cpus 2 --memory 8G --disk 10G
Launched: k3s
```

查看虚拟机列表

![](https://gitee.com/hxc8/images0/raw/master/img/202407172036176.jpg)

虚拟机创建完成之后，

可以配置SSH密钥登录，

不过这一步并不是必须的，

即使不配置也可以通过multipass exec或者multipass shell命令来进入虚拟机，

然后我们需要在master节点上安装k3s，

执行 如下命令

```
multipass shell k3s
```

进入到虚拟机

使用k3s搭建kubernetes集群非常简单，

只需要执行一条命令就可以在当前节点上安装k3s，

打开刚刚创建的k3s虚拟机，

执行下面的命令就可以安装一个k3s的master节点，

```
# 安装k3s的master节点
curl -sfL https://get.k3s.io | sh -
```

国内用户可以换成下面的命令，使用ranher的镜像源来安装：

curl -sfL [https://rancher-mirror.rancher.cn/k3s/k3s-install.sh](https://rancher-mirror.rancher.cn/k3s/k3s-install.sh) | INSTALL_K3S_MIRROR=cn sh -

安装完成之后，可以通过kubectl命令来查看集群的状态，

```
sudo kubectl get nodes
```

### 1.2.2 创建和配置worker节点

接下来需要在这个master节点上获取一个token，

用来作为创建worker节点时的一个认证凭证，

它保存在/var/lib/rancher/k3s/server/node-token这个文件里面，

我们可以使用sudo cat命令来查看一下这个文件中的内容，

```
sudo cat /var/lib/rancher/k3s/server/node-token
```

大概内容如下：

![](https://gitee.com/hxc8/images0/raw/master/img/202407172036836.jpg)

然后打开一个新终端（不是虚拟机内部环境，是登录到宿主机的环境）

将TOKEN保存到一个环境变量中

```
TOKEN=$(multipass exec k3s sudo cat /var/lib/rancher/k3s/server/node-token)
```

保存master节点的IP地址

```
MASTER_IP=$(multipass info k3s | grep IPv4 | awk '{print $2}')
```

确认：

```
echo $MASTER_IP
```

使用刚刚的TOKEN和MASTER_IP来创建两个worker节点

并把它们加入到集群中

```
# 创建两个worker节点的虚拟机
multipass launch --name worker1 --cpus 2 --memory 8G --disk 10G
multipass launch --name worker2 --cpus 2 --memory 8G --disk 10G
```

在worker节点虚拟机上安装k3s

```
 for f in 1 2; do
     multipass exec worker$f -- bash -c "curl -sfL https://rancher-mirror.rancher.cn/k3s/k3s-install.sh | INSTALL_K3S_MIRROR=cn K3S_URL=\"https://$MASTER_IP:6443\" K3S_TOKEN=\"$TOKEN\" sh -"
 done
```

上面几个步骤执行过程如下

![](https://gitee.com/hxc8/images0/raw/master/img/202407172036655.jpg)

这样就完成了一个多节点的kubernetes集群的搭建。

# 2.kubectl常用命令

## 2.1 基础使用

```
# 查看帮助
kubectl --help

# 查看API版本
kubectl api-versions

# 查看集群信息
kubectl cluster-info

```

## 2.2 资源的创建和运行

```
# 创建并运行一个指定的镜像
kubectl run NAME --image=image [params...]
# e.g. 创建并运行一个名字为nginx的Pod
kubectl run nginx --image=nginx

# 根据YAML配置文件或者标准输入创建资源
kubectl create RESOURCE
# e.g.
# 根据nginx.yaml配置文件创建资源
kubectl create -f nginx.yaml
# 根据URL创建资源
kubectl create -f https://k8s.io/examples/application/deployment.yaml
# 根据目录下的所有配置文件创建资源
kubectl create -f ./dir

# 通过文件名或标准输入配置资源
kubectl apply -f (-k DIRECTORY | -f FILENAME | stdin)
# e.g.
# 根据nginx.yaml配置文件创建资源
kubectl apply -f nginx.yaml
```

## 2.3 查看资源信息

```
# 查看集群中某一类型的资源
kubectl get RESOURCE
# 其中，RESOURCE可以是以下类型：
kubectl get pods / po         # 查看Pod
kubectl get svc               # 查看Service
kubectl get deploy            # 查看Deployment
kubectl get rs                # 查看ReplicaSet
kubectl get cm                # 查看ConfigMap
kubectl get secret            # 查看Secret
kubectl get ing               # 查看Ingress
kubectl get pv                # 查看PersistentVolume
kubectl get pvc               # 查看PersistentVolumeClaim
kubectl get ns                # 查看Namespace
kubectl get node              # 查看Node
kubectl get all               # 查看所有资源

# 后面还可以加上 -o wide 参数来查看更多信息
kubectl get pods -o wide

# 查看某一类型资源的详细信息
kubectl describe RESOURCE NAME
# e.g. 查看名字为nginx的Pod的详细信息
kubectl describe pod nginx
```

## 2.4 资源的修改、删除和清理

```
# 更新某个资源的标签
kubectl label RESOURCE NAME KEY_1=VALUE_1 ... KEY_N=VALUE_N
# e.g. 更新名字为nginx的Pod的标签
kubectl label pod nginx app=nginx

# 删除某个资源
kubectl delete RESOURCE NAME
# e.g. 删除名字为nginx的Pod
kubectl delete pod nginx

# 删除某个资源的所有实例
kubectl delete RESOURCE --all
# e.g. 删除所有Pod
kubectl delete pod --all

# 根据YAML配置文件删除资源
kubectl delete -f FILENAME
# e.g. 根据nginx.yaml配置文件删除资源
kubectl delete -f nginx.yaml

# 设置某个资源的副本数
kubectl scale --replicas=COUNT RESOURCE NAME
# e.g. 设置名字为nginx的Deployment的副本数为3
kubectl scale --replicas=3 deployment/nginx

# 根据配置文件或者标准输入替换某个资源
kubectl replace -f FILENAME
# e.g. 根据nginx.yaml配置文件替换名字为nginx的Deployment
kubectl replace -f nginx.yaml
```

## 2.5 调试和交互

```
# 进入某个Pod的容器中
kubectl exec [-it] POD [-c CONTAINER] -- COMMAND [args...]
# e.g. 进入名字为nginx的Pod的容器中，并执行/bin/bash命令
kubectl exec -it nginx -- /bin/bash

# 查看某个Pod的日志
kubectl logs [-f] [-p] [-c CONTAINER] POD [-n NAMESPACE]
# e.g. 查看名字为nginx的Pod的日志
kubectl logs nginx

# 将某个Pod的端口转发到本地
kubectl port-forward POD [LOCAL_PORT:]REMOTE_PORT [...[LOCAL_PORT_N:]REMOTE_PORT_N]
# e.g. 将名字为nginx的Pod的80端口转发到本地的8080端口
kubectl port-forward nginx 8080:80

# 连接到现有的某个Pod（将某个Pod的标准输入输出转发到本地）
kubectl attach POD -c CONTAINER
# e.g. 将名字为nginx的Pod的标准输入输出转发到本地
kubectl attach nginx

# 运行某个Pod的命令
kubectl run NAME --image=image -- COMMAND [args...]
# e.g. 运行名字为nginx的Pod
kubectl run nginx --image=nginx -- /bin/bash
```

下面是一些命令的执行和输出

![](https://gitee.com/hxc8/images0/raw/master/img/202407172036453.jpg)