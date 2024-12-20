![](https://gitee.com/hxc8/images6/raw/master/img/202407190000783.jpg)

Ceph的架构设计包括以下几个组件：

Monitor（ceph-mon）：维护集群Cluster Map的状态，维护集群的Cluster MAP⼆进制表，保证集群数据的⼀致性。Cluster MAP描述了对象块存储的物理位置，以及⼀个将设备聚合到物理位置的桶列表，map中包含monitor组件信息，manger 组件信息，osd 组件信息，mds 组件信息，crush 算法信息。还负责ceph集群的身份验证功能，client 在连接ceph集群时通过此组件进⾏验证。

OSD（ceph-osd）：OSD全称Object Storage Device，⽤于集群中所有数据与对象的存储。ceph 管理物理硬盘时，引⼊了OSD概念，每⼀块盘都会针对的运⾏⼀个OSD进程。换句话说，ceph 集群通过管理 OSD 来管理物理硬盘。负责处理集群数据的复制、恢复、回填、再均衡，并向其他osd守护进程发送⼼跳，然后向Mon提供⼀些监控信息。当Ceph存储集群设定数据有两个副本时（⼀共存两份），则⾄少需要三个OSD守护进程即三个OSD节点，集群才能达到active+clean状态，实现冗余和⾼可⽤。

Manager（ceph-mgr）：⽤于 收集ceph集群状态、运⾏指标，⽐如存储利⽤率、当前性能指标和系统负载。对外提供 ceph dashboard（ceph ui）和 resetful api。Manager组件开启⾼可⽤时，⾄少2个实现⾼可⽤性。

MDS（ceph-mds）：Metadata server，元数据服务。为ceph ⽂件系统提供元数据计算、缓存与同步服务（ceph 对象存储和块存储不需要MDS）。同样，元数据也是存储在osd节点中的，mds类似于元数据的 代理缓存服务器，为 posix ⽂件系统⽤户提供性能良好的基础命令（ls，find等）不过只是在需要使⽤CEPHFS时，才需要配置MDS节点。

Object：Ceph最底层的存储单元是Object对象，每个Object包含元数据和原始数据。

PG：PG全称Placement Grouops，是⼀个逻辑的概念，⼀个PG包含多个OSD。引⼊PG这⼀层其实是为了更好的分配数据和定位数据。

RADOS：RADOS全称Reliable Autonomic Distributed Object Store（可靠、⾃治、分布式对象存储），是Ceph集群的精华，⽤户实现数据分配、Failover（故障转移）等集群操作。

Libradio驱动库：Librados是Rados提供库，因为RADOS是协议很难直接访问，因此上层的RBD、RGW和CephFS都是通过librados访问的，⽬前提供PHP、Ruby、Java、Python、C和C++⽀持。

CRUSH：

CRUSH是Ceph使⽤的数据分布算法，类似⼀致性哈希，让数据分配到预期的地⽅。

RBD：RBD全称RADOS block device，是Ceph对外提供的块设备服务。

RGW：RGW全称RADOS gateway，是Ceph对外提供的对象存储服务，接⼝与S3和Swift兼容。

CephFS：CephFS全称Ceph File System，是Ceph对外提供的⽂件系统服务。

优点：

1. 可靠性高：Ceph使用副本和纠删码等技术来确保数据的可靠性和容错性。当出现硬件故障或其他问题时，Ceph能够自动检测并恢复数据，确保数据的持久性和可靠性。

1. 可扩展性强：Ceph的设计允许将存储容量、计算能力和网络带宽等资源无缝地扩展到更大的规模。它能够轻松地应对数据增长和业务需求的变化，支持多种硬件和存储介质，提高了系统的灵活性和可扩展性。

1. 高性能：Ceph使用并行化和异步化的数据处理方式来提高系统的性能。它可以在多个节点上并行执行数据操作，同时还具有高度优化的数据缓存和数据传输机制，从而提高了系统的性能和吞吐量。

1. 开放性：Ceph是一个开源项目，可以在任何支持Linux操作系统的硬件上运行。它遵循开放标准和协议，支持多种应用程序接口，可以与其他开源项目和商业软件无缝集成。

缺点：

1. 学习曲线陡峭：Ceph的配置和管理需要一定的技能和经验，因为它涉及到多个节点和组件。这可能会对系统的可用性和可维护性产生影响，需要专业的运维人员进行管理。

1. 对象大小对性能的影响：Ceph的对象大小对其性能有很大的影响。较小的对象会导致更多的元数据和数据分片，这可能会影响性能。另一方面，较大的对象可能会对Ceph的负载均衡和数据分布造成不利影响，可能会影响性能和可扩展性。

1. 数据写入延迟较高：由于Ceph需要在多个节点之间进行数据同步和副本备份，因此写入操作的延迟相对较高。这可能会对某些应用程序的性能造成影响，特别是对于需要快速写入大量数据的应用程序。

1. 不能胜任性能、延迟敏感的场景，木桶效应原理，异常场景抖动较大（比如慢盘），由于数据在OSD上随机分布，因而单个硬盘上的IO负载不会固定在平均值上，少数硬盘其IO负拥堵。此外，分布式存储为保证数据完整性，必须定时进行数据完整性校验，而这些操作产生额外的IO负载，可能会加重

1. 去中心节点设计在集群不均衡的情况下需要人工维护

1. 当存储扩容、硬盘或节点损坏、网络故障、OSD假死、 Deep-scrub等多种因素叠加，可能导致多个OSD同时重建，引发重建风暴。在数据重建过程中，重建任务不仅消耗系统的内存、CPU、网络资源，而且还给存储系统带来额外的IO工作负载，挤占用户工作负载的存储资源。在此情形下，用户时常观察到，系统IO延迟大，响应迟钝，轻者引起业务中断，严重时系统可能会陷入不稳定的状态，OSD反复死机重启，甚至会导致数据丢失，系统崩溃。

1. 三副本分布式存储还面临数据丢失的风险。三副本最大可抵御两个HDD同时损坏。当系统处于扩容状态、或一个节点处于维护模式时，此时出现一个HDD故障，则该系统就会进入紧急状态，出现两个HDD同时故障，则可能导致数据丢失。对于一个具有一定规模的存储系统而言，同时出现两个机械硬盘故障的事件不可避免，尤其是当系统运行两三年之后，随着硬件的老化，出现Double、或Triple磁盘故障的概率急剧上升。此外，当系统出现大规模掉电或存储节点意外宕机时，也可能会导致多个机械硬盘同时出现损坏，危及三副本分布式存储的数据安全。

ceph bluestore详细介绍

ceph Bluestore是Ceph存储集群的一种新的存储后端，它的主要目的是优化Ceph在处理大容量存储时的性能。与传统的Filestore相比，Bluestore能够提供更高的性能和更好的可靠性。

Bluestore的设计是基于纯块设备（Block Device）的，将数据直接存储在块设备上，避免了传统Filestore中使用多层存储架构带来的性能开销。下面是Bluestore的一些主要特点：

1. 更好的性能：Bluestore的设计可以避免Filestore中常见的多层存储结构，从而提供更高的性能和更低的延迟。同时，Bluestore还采用了多线程机制来提高I/O处理性能，加速读写操作。

1. 更高的可靠性：Bluestore使用了更严格的数据校验机制，采用了CRC校验和，从而能够检测和纠正数据错误。此外，Bluestore还采用了可写时复制（Copy-On-Write）的方式来避免数据损坏，从而提供更高的可靠性。

1. 更节省的存储空间：Bluestore支持压缩和去重等特性，从而可以节省存储空间。此外，Bluestore还支持将不常用的数据迁移至较慢的存储设备，从而在不影响性能的前提下节约存储空间。

1. 更好的可扩展性：Bluestore的设计可以实现水平扩展，因此可以支持非常大的存储规模，同时仍然保持高性能和高可靠性。

总之，Bluestore是Ceph存储集群的一种新的存储后端，它的主要特点是更好的性能、更高的可靠性、更节省的存储空间和更好的可扩展性。Bluestore已经成为Ceph默认的存储后端，被广泛应用于Ceph存储集群的生产环境中。

写时复制怎么能避免数据损坏，原理是什么

写时复制（Copy-On-Write，简称COW）是一种常用的数据管理技术，它主要用于在多个数据副本之间共享数据的情况下，避免数据损坏的问题。

COW的基本原理是：当多个数据副本需要共享相同的数据时，不再将数据的副本完全复制一份，而是先将该数据标记为只读（Read-Only），当其中某个副本需要修改数据时，会在修改之前将该数据所在的页（Page）复制一份，然后在副本中修改数据，从而避免数据的损坏。

具体来说，COW的实现方法如下：

1. 当一个数据副本需要修改数据时，先将该数据所在的页复制一份，形成一个新的数据页。

1. 然后，在新的数据页中修改数据，同时将新的数据页标记为可写（Writable）。

1. 对于其他数据副本，它们仍然使用原始的数据页，该数据页被标记为只读（Read-Only）。

通过这种方式，每个数据副本都可以读取原始的数据页，同时对数据进行修改时，只会修改该数据副本中的新数据页，从而避免了对原始数据页的修改，从而避免了数据的损坏。

COW技术在现代操作系统和存储系统中得到广泛应用，例如ZFS、Btrfs等文件系统，以及Ceph存储系统中的Bluestore存储后端。它可以提高系统的性能和可靠性，减少数据损坏和数据冲突等问题。

写时复制（Copy-On-Write，简称COW）是一种常见的数据管理技术，可以避免数据损坏。COW的基本原理是，当需要修改一个数据块时，先将该数据块复制一份，然后在新的数据块上进行修改，而原始的数据块则保持不变。

具体来说，COW通常会在内存中维护一个指向原始数据块的指针，当需要修改数据时，先将原始数据块复制一份，然后在新的数据块上进行修改，修改完成后再将指针指向新的数据块。这样，就保证了修改操作不会影响到原始的数据块，从而避免了数据损坏的风险。

COW的优点在于它可以避免因为修改数据而导致数据损坏的风险。在存储系统中，数据损坏可能会因为多种原因而发生，如硬件故障、软件错误等。如果使用COW技术，即使在修改数据时发生了错误，原始数据块也不会被破坏，数据也不会丢失。

此外，COW还可以实现快照（Snapshot）功能。快照是指对存储系统中的数据进行备份，以便在数据出现故障时可以快速恢复。COW的数据复制机制可以非常方便地实现快照功能，即只需在数据块的指针上创建一个新的快照指针即可。

总之，COW是一种常见的数据管理技术，可以避免数据损坏的风险，同时还可以实现快照功能。在存储系统中，COW技术被广泛应用于实现数据的高效管理和保护。

ceph bluestore有哪些组件构成

Ceph Bluestore是Ceph存储集群中的一种OSD（Object Storage Device）存储后端，用于管理Ceph集群中的对象存储。它的组件主要包括以下几个方面：

1. 数据分配器（Allocator）：负责管理数据块的分配和释放，保证数据块的使用效率和空间利用率。

1. 元数据存储（Metadata Store）：用于存储对象的元数据信息，如对象ID、大小、时间戳等。

1. 数据存储（Data Store）：用于存储对象的数据内容，采用了类似B+树的结构，可以支持快速读写和快速扩展存储容量。

1. 日志存储（Journal Store）：用于记录数据操作日志，保证数据的一致性和可靠性。

1. CRC校验（Cyclic Redundancy Check）：用于检测数据在存储过程中的完整性，防止数据损坏。

1. 缓存（Cache）：用于提高数据读写的性能，包括元数据缓存、数据缓存和日志缓存等。

1. 压缩（Compression）：用于在存储数据时对数据进行压缩，减少存储空间和网络带宽的占用。

1. AES加密（AES Encryption）：用于对数据进行加密，保护数据的安全性。

Ceph Bluestore使用的是RocksDB作为其元数据存储的数据库。RocksDB是一个基于LevelDB的高性能嵌入式键值存储引擎，由Facebook开发和维护，主要用于处理大规模的高并发读写数据访问。它具有以下几个特点：

1. 高性能：RocksDB采用了多种性能优化策略，如使用多线程、批量写入、异步I/O等，可以在高并发读写场景下保持出色的性能表现。

1. 可靠性：RocksDB使用LSM-Tree（Log-Structured Merge-Tree）数据结构，保证了数据写入时的可靠性，可以在断电等异常情况下快速恢复数据。

1. 可扩展性：RocksDB支持多种存储引擎和插件，可以根据不同的业务需求进行灵活配置。

1. 易用性：RocksDB提供了简单易用的API，支持多种编程语言，包括C++、Java、Python等。

在Ceph Bluestore中，RocksDB被用于存储对象的元数据信息，如对象ID、大小、时间戳等。通过使用RocksDB，Ceph Bluestore可以实现高性能的元数据读写，并且具备可靠性和可扩展性，保证了Ceph集群中对象存储的高效运行。

**详细说明ceph的crush算法**

用于动态地将数据映射到存储集群的物理设备上。CRUSH算法通过在对象名称、属性和集群拓扑结构之间建立映射关系，实现数据的分散存储和动态重分布。以下是CRUSH算法的详细说明：

1. 设备映射
CRUSH算法将设备表示为一棵树，每个叶子节点表示一个存储设备。树的根节点表示整个集群。树中的每个节点都有一个权重，这个权重用于表示节点的容量大小或者计算能力。

1. Buckets
CRUSH算法通过将存储设备分组为buckets来组织存储设备树。Bucket是一组存储设备的抽象，可以是任意数量的存储设备、其他Bucket，或两者的组合。Bucket也可以具有自己的权重。Bucket可以根据其权重按比例分配数据块，从而在不同存储设备之间实现负载均衡。

1. 带有Hash的数据映射
CRUSH算法使用哈希函数将数据映射到存储设备上。哈希函数可以是任何确定性函数，可以使用对象名称、属性和集群拓扑结构作为输入参数。通过使用哈希函数，CRUSH算法可以将数据均匀地分配到存储设备上，从而实现数据的分散存储和负载均衡。

1. 映射规则
CRUSH算法将映射规则作为存储设备树的一部分来管理。每个规则定义了如何将数据映射到存储设备上，包括在何处执行哈希函数，以及如何对哈希结果进行排序和选择存储设备。CRUSH算法可以根据需要配置不同的映射规则，以便在不同的数据和存储设备上实现不同的负载均衡和容错性

1. 算法细节
CRUSH算法中的核心概念是通过散列函数、存储设备树和映射规则，实现动态地将数据均匀分配到存储设备上。CRUSH算法通过计算散列值、选择存储设备和验证选择结果的有效性，实现了存储设备的选择过程。CRUSH算法支持动态的存储设备添加和删除，以及存储设备的故障处理，从而实现了高可用性和数据可靠性。

Ceph是一种分布式存储系统，可以在多个节点上存储数据，具有高可扩展性、高可靠性和高性能等特点。以下是Ceph的一些详细介绍：

1. 分布式架构：Ceph的架构是基于对象存储的分布式系统。数据被分割为小的对象，然后这些对象被存储在不同的节点上。由于数据是分布式存储的，因此Ceph可以通过添加更多的节点来扩展存储容量和性能。

1. 可扩展性：Ceph是可扩展的，因为可以添加更多的节点来扩展存储容量和性能。当添加更多的节点时，Ceph可以自动重新平衡数据和负载，以保持高可用性和高性能。

1. 可靠性：Ceph可以通过使用数据复制和故障转移技术来保证数据的可靠性。数据可以复制到多个节点上，以确保在节点故障时不会丢失数据。

1. 高性能：Ceph通过使用分布式对象存储和块存储技术来提高性能。Ceph的对象存储可以通过多个节点同时进行读写操作，以提高吞吐量和响应时间。块存储可以通过使用分布式映射和缓存技术来提高性能。

1. 开放性：Ceph是一个开放源代码的项目，可以在Linux等操作系统上运行。它支持多种客户端接口，包括对象存储接口、块存储接口和文件系统接口，可以方便地集成到不同的应用程序中。

1. 管理和监控：Ceph提供了一组管理和监控工具，可以用于管理Ceph集群和监控性能。这些工具包括命令行工具和Web界面，可以用于配置Ceph集群、监控性能、备份和恢复数据等。