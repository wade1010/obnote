### k8s-clean-data-pvc.yaml内容如下

```
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: clean-data-nfs-pvc
spec:
  storageClassName: nfs-client-hanbo
  accessModes:
    - ReadWriteMany
  resources:
    requests:
      storage: 800Gi
```

### 创建pvc

```
kubectl apply -f k8s-clean-data-pvc.yaml
```

### 查看创建的pvc

```
kubectl get persistentvolumeclaim clean-data-nfs-pvc
```

```
$ kubectl get persistentvolumeclaim clean-data-nfs-pvc
NAME                 STATUS   VOLUME                                     CAPACITY   ACCESS MODES   STORAGECLASS       VOLUMEATTRIBUTESCLASS   AGE
clean-data-nfs-pvc   Bound    pvc-01d93900-8aa0-4618-800a-78e8b279ad0c   800Gi      RWX            nfs-client-hanbo   <unset>                 48s
```

在上面的示例中，PVC 的名称是 clean-data-nfs-pvc，并且它已经绑定（Bound）到一个存储卷（Volume）。你可以查看 STATUS 字段以确认 PVC 的状态。

如果你想获取更详细的 PVC 信息，可以使用以下命令：

```
kubectl describe persistentvolumeclaim clean-data-nfs-pvc
```

这会显示 PVC 的详细信息，包括存储卷的名称、容量、访问模式、状态以及与之关联的存储类等。

### 删除pvc