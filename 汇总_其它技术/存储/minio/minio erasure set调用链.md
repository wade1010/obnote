

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007157.jpg)



API请求到minio，如GetObjectNInfo()

先到erasure-server-pool.go:func (z *erasureServerPools) GetObjectNInfo(.....)

再到erasure-sets.go:func (s *erasureSets) GetObjectNInfo(......)

再到erasure-object.go:func (er erasureObjects) GetObjectNInfo(......)