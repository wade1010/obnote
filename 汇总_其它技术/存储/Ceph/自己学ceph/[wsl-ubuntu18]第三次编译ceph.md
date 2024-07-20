wget [https://download.ceph.com/tarballs/ceph-15.2.4.tar.gz](https://download.ceph.com/tarballs/ceph-15.2.4.tar.gz)

tar zxf ceph-15.2.4.tar.gz && mv ceph-15.2.4 ceph && cd ceph

This may be due to a lack of SYSV IPC support.

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359829.jpg)

[http://cn.voidcc.com/question/p-rkuwiada-uu.html](http://cn.voidcc.com/question/p-rkuwiada-uu.html)

[https://blog.csdn.net/Chaowanq/article/details/121559709](https://blog.csdn.net/Chaowanq/article/details/121559709)

[https://blog.csdn.net/qq_43744723/article/details/121172122](https://blog.csdn.net/qq_43744723/article/details/121172122)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359770.jpg)

ERROR: Package 'mkcodes' requires a different Python: 3.6.9 not in '<4.0,>=3.8'

[https://blog.csdn.net/fcdm_/article/details/121586731](https://blog.csdn.net/fcdm_/article/details/121586731)

sudo apt install -y python3.8

sudo update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.8

sudo update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.8 1

sudo update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.6 2

sudo update-alternatives --config python3

这里还有一部要删掉之前python3.6下载的包，会缓存在  rm -rf /home/ceph/ceph/install-deps-cache/pip

最后用了代理，wsl里面好像也能用宿主机的代理

export https_proxy=[http://127.0.0.1:33210](http://127.0.0.1:33210) http_proxy=[http://127.0.0.1:33210](http://127.0.0.1:33210) all_proxy=socks5://127.0.0.1:33211