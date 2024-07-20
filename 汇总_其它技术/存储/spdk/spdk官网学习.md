Direct Memory Access (DMA) From User Space



SPDK relies on DPDK to allocate pinned memory. On Linux, DPDK does this by allocating hugepages (by default, 2MiB). The Linux kernel treats hugepages differently than regular 4KiB pages. Specifically, the operating system will never change their physical location. This is not by intent, and so things could change in future versions, but it is true today and has been for a number of years (see the later section on the IOMMU for a future-proof solution).



SPDK 依靠 DPDK 来分配固定内存。在 Linux 上，DPDK 通过分配 hugepages (默认情况下，2MiB)来实现这一点。内核对待巨大页面的方式与一般的4kib 页面不同。具体来说，操作系统永远不会改变它们的物理位置。这不是出于目的，因此在未来的版本中情况可能会发生变化，但是这在今天是正确的，并且已经持续了很多年(关于 IOMMU 的后续部分可以获得一个防未来的解决方案)。



With this explanation, hopefully it is now clear why all data buffers passed to SPDK must be allocated using spdk_dma_malloc() or its siblings. The buffers must be allocated specifically so that they are pinned and so that physical addresses are known.

有了这个解释，希望现在可以清楚地了解为什么传递给 SPDK 的所有数据缓冲区都必须使用 SPDK_dma_malloc ()或其兄弟缓冲区分配。缓冲区必须专门分配，以便它们被固定，并且物理地址是已知的。







IOMMU Support



许多平台包含一个额外的硬件，称为 i/o 内存管理单元(IOMMU)。IOMMU 与普通的 MMU 非常相似，只是它为外围设备(例如 PCI 总线)提供虚拟地址空间。MMU 知道系统上每个进程的虚拟到物理的映射，因此 IOMMU 将一个特定的设备与这些映射关联起来，然后允许用户在他们的进程中为虚拟地址分配任意的总线地址。然后，PCI 设备和系统内存之间的所有 DMA 操作都通过 IOMMU 进行转换，将总线地址转换为虚拟地址，然后将虚拟地址转换为物理地址。这允许操作系统在不中断正在进行的 DMA 操作的情况下自由地修改虚拟到物理地址的映射。Linux 提供了一个设备驱动程序 vfio-pci，它允许用户使用当前进程配置 IOMMU。



这是一个未来证明，硬件加速的解决方案，用于执行进出用户空间进程的 DMA 操作，并形成了 SPDK 和 DPDK 的内存管理策略的长期基础。我们强烈建议使用 vfio 和启用 IOMMU 部署应用程序，目前这些应用程序完全支持。







