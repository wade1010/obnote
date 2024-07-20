# VFIO(Virtual Function IO)研究

> 主要研究VFIO在虚拟化中的应用,但VFIO的应用不止于虚拟化.


VFIO的全称是Virtual Function IO,但这个名字并不能反应它的特点,以下两个假名字更能反应VFIO的特点:

1. Very **F**ast **IO**

由于VFIO是将设备直接透传给虚拟机,所以Guest中与该设备相关的IO性能会大幅提高,接近native性能.

1. Versatile **F**ramework for userspace **IO**

这个名字反映了VFIO的功能,即能够将device安全地映射到用户空间,使用户能够对device进行操作.

[https://www.cnblogs.com/haiyonghao/p/14440944.html](https://www.cnblogs.com/haiyonghao/p/14440944.html)