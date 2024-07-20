# 环境:

3台虚拟机：centos7 虚拟机  16c 64G  HDD

192.168.1.88  oeos、minio、cosbench controller、cosbench driver、tikv

192.168.1.89  cosbench driver、tikv、pd server

192.168.1.90  cosbench driver、tikv、pd server



# 测试：

# 一、写测试

## 256KB文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010602.jpg)

256KB文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010724.jpg)

## 256KB文件写-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010758.jpg)



---



---



---

## 1M文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010784.jpg)

## 1M文件写-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010789.jpg)

## 1M文件写-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010805.jpg)

## 1M文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010718.jpg)

## 1M文件写-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010752.jpg)

## 1M文件写-8192worker

![](D:/download/youdaonote-pull-master/data/Technology/存储/images/C9A18415912442F8909FC71E773F9B10image.png)

## 1M文件写-16384worker

![](D:/download/youdaonote-pull-master/data/Technology/存储/images/5F22D28BA8B140A8BE93A2EC671FE851image.png)

## 1M文件写-32768worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010821.jpg)



---



---



---

## 5M文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010843.jpg)

## 5M文件写-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010868.jpg)

## 5M文件写-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010896.jpg)

## 5M文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010000.jpg)

## 5M文件写-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010067.jpg)

## 5M文件写-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010881.jpg)

## 5M文件写-16384worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010015.jpg)



---



---



---

## 10M文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010022.jpg)

## 10M文件写-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010008.jpg)

## 10M文件写-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010268.jpg)

## 10M文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010315.jpg)

## 10M文件写-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010184.jpg)

## 10M文件写-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010130.jpg)

## 10M文件写-16384worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010150.jpg)



---



---



---

## 32M文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010296.jpg)

## 32M文件写-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010191.jpg)

## 32M文件写-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010235.jpg)

## 32M文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010555.jpg)

## 32M文件写-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010676.jpg)

## 32M文件写-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010913.jpg)



---



---



---

## 64M文件写-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010883.jpg)

## 64M文件写-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010852.jpg)

## 64M文件写-1024worker

![](D:/download/youdaonote-pull-master/data/Technology/存储/images/676C6B81537F4C5ABF5606A43F6558A2image.png)

## 64M文件写-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010845.jpg)

## 64M文件写-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010735.jpg)

## 64M文件写-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010753.jpg)





# 二、读测试

## 256KB文件读-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010801.jpg)

## 256KB文件读-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011319.jpg)

## 256KB文件读-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011489.jpg)

## 256KB文件读-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011662.jpg)

## 256KB文件读-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011678.jpg)

## 256KB文件读-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011790.jpg)



---



---



---

## 1MB文件读-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011013.jpg)

## 1MB文件读-512worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011078.jpg)

## 1MB文件读-1024worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011338.jpg)

## 1MB文件读-2048worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011397.jpg)

## 1MB文件读-4096worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011385.jpg)

## 1MB文件读-8192worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011414.jpg)



---



---



---

## 5MB文件读-128worker

![](https://gitee.com/hxc8/images6/raw/master/img/202407190011701.jpg)

## 5MB文件读-512worker

有问题暂未往下测试

## 5MB文件读-1024worker

## 5MB文件读-2048worker

## 5MB文件读-4096worker

## 5MB文件读-8192worker