猜测可能是对抗的原因,无法连接到国外服务器.

```
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://docker.rainbond.cc"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
```

2024-8-1 14:44:28可以下载
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408011444291.png)
