

BSD locks (flock)

Features:

- not specified in POSIX, but widely available on various Unix systems

- always lock the entire file

- associated with a file object

- do not guarantee atomic switch between the locking modes (exclusive and shared)

- up to Linux 2.6.11, didn’t work on NFS; since Linux 2.6.12, flock() locks on NFS are emulated using fcntl() POSIX record byte-range locks on the entire file (unless the emulation is disabled in the NFS mount options)

The lock acquisition is associated with a file object, i.e.:

- duplicated file descriptors, e.g. created using dup2 or fork, share the lock acquisition;

- independent file descriptors, e.g. created using two open calls (even for the same file), don’t share the lock acquisition;



POSIX record locks (fcntl)

Features:

- specified in POSIX (base standard)

- can be applied to a byte range

- associated with an [i-node, pid] pair instead of a file object

- guarantee atomic switch between the locking modes (exclusive and shared)

- work on NFS (on Linux)

The lock acquisition is associated with an [i-node, pid] pair, i.e.:

- file descriptors opened by the same process for the same file share the lock acquisition (even independent file descriptors, e.g. created using two open calls);

- file descriptors opened by different processes don’t share the lock acquisition;

This means that with POSIX record locks, it is possible to synchronize processes, but not threads. All threads belonging to the same process always share the lock acquisition of a file, which means that:

- the lock acquired through some file descriptor by some thread may be released through another file descriptor by another thread;

- when any thread calls close on any descriptor referring to given file, the lock is released for the whole process, even if there are other opened descriptors referring to this file.





|  | Posix locks: fcntl/lockf | BSD locks: flock |
| - | - | - |
| 范围 | 字节范围锁 | 只能对整个文件加锁 |
| 类型 | 建议锁（默认）/<br>强制锁（非 POSIX 标准，默认关闭） | 建议锁 |
| 关联关系 | 与进程关联( 标准 POSIX )/<br>与文件描述符关联(非 POSIX 标准，<br>需特定参数，<br>linux 3.15 支持) | 与文件描述符关联 |
| 网络文件系统 | 支持 NFS<br>不支持 ocfs2 | 支持 NFS（实现仿 fcntl，<br>linux 2.6.12 支持）<br>支持 ocfs2 |




一、锁的类型

建议锁

- 只在合作进程（在读写文件之前尝试加锁）间有效。

- 其他进程非要读写是拦不住的。

强制锁

- 需要 mount -o mand 和 chmod g+s,g-x lockfile 同时满足才行

- linux 内核会阻塞其他进程的 IO 请求

- 可以通过删除锁文件绕过

2、关联关系

与进程关联

- 当一个进程终止时，所建立的所有锁全部被释放

- 关闭一个文件描述符，会释放对该文件的所有锁，包括对其他指向相同文件的文件描述符加的锁

- 同一进程打开多个文件描述符 fd1, fd2

- 对 fd1 加锁

- 关闭 fd2

- fd1 上的锁会被释放

- fork 产生的子进程并不继承父进程所设置的锁

- 在执行 exec 后，新程序可以继承原程序的锁（如果对fd设置了close-on-exec，则exec前会关闭fd，相应文件的锁也会被释放）

与文件描述符关联

- 当一个文件描述符及其所有副本（包括子进程继承的和 dup 的）关闭时，才会释放对其建立的锁

- fork 的子进程由于继承了文件描述符，所以也继承了其上的锁

- 子进程对继承的文件描述符上的锁进行修改/解锁，会影响到父进程的锁（对于 dup 出的副本同样试用）

- 在执行 exec 后，新程序可以继承原程序的锁

3、fcntl/lockf 和 flock 的交互

- linux 2.0 后在本地文件系统上互不影响

- 在 NFS 上， flock 由于底层实现仿造 fcntl 的字节范围锁，所以两者会产生交互。

4、NOTE

- Linux fcntl 的强制锁在设置的时候会和 write/read 有 race condition。

- fcntl 有死锁检测，而 flock 没有

- linux 3.12 之前，在 NFS 上设置的 fcntl 锁会因为 client 长时间(90s)与 nfs server 失去连接而丢失





File locking in Linux

Linux 中的文件锁定

29 Jul 2016 二零一六年七月二十九日

linux

 

posix Posixth

 

ipc 等离子电视

Table of contents

目录

- Introduction 引言

- Advisory locking 通知锁定

- Common features 共同特征

- Differing features 不同的特征

- File descriptors and i-nodes 文件描述符和 i 节点

- BSD locks (flock) BSD 锁(群)

- POSIX record locks (fcntl) POSIX 记录锁(fcntl)

- lockf function 锁函数

- Open file description locks (fcntl) 打开文件描述锁(fcntl)

- Emulating Open file description locks 仿真打开文件描述锁

- Test program 测试程序

- Command-line tools 命令行工具

- Mandatory locking 强制锁定

- Example usage 使用示例

---

Introduction 引言

File locking is a mutual-exclusion mechanism for files. Linux supports two major kinds of file locks:

文件锁定是一种文件互斥机制。 Linux 支持两种主要的文件锁定:

- advisory locks 通知船闸

- mandatory locks 强制锁

Below we discuss all lock types available in POSIX and Linux and provide usage examples.

下面我们将讨论 POSIX 和 Linux 中可用的所有锁类型，并提供使用示例。

---

Advisory locking 通知锁定

Traditionally, locks are advisory in Unix. They work only when a process explicitly acquires and releases locks, and are ignored if a process is not aware of locks.

传统上，在 Unix 中，锁是建议性的。它们仅在进程显式获取和释放锁时才起作用，如果进程不知道锁，则忽略它们。

There are several types of advisory locks available in Linux:

在 Linux 中有几种类型的建议锁:

- BSD locks (flock) BSD 锁(群)

- POSIX record locks (fcntl, lockf) POSIX 记录锁(fcntl，lockf)

- Open file description locks (fcntl) 打开文件描述锁(fcntl)

All locks except the lockf function are reader-writer locks, i.e. support exclusive and shared modes.

除 lockf 函数之外的所有锁都是读写器锁，即支持排他和共享模式。

Note that flockfile and friends have nothing to do with the file locks. They manage internal mutex of the FILE object from stdio.

注意 flockfile 和 friends 与文件锁无关。他们从 stdio 管理 FILE 对象的内部互斥。

Reference:

参考资料:

- File Locks 文件锁, GNU libc manual ，GNU libc 手册

- Open File Description Locks 打开文件描述锁, GNU libc manual ，GNU libc 手册

- File-private POSIX locks 文件私有 POSIX 锁, an LWN article about the predecessor of open file description locks ，一篇关于打开文件描述锁的前身的 LWN 文章

Common features 共同特征

The following features are common for locks of all types:

对于所有类型的锁，以下特性都是常见的:

- All locks support blocking and non-blocking operations. 所有锁都支持阻塞和非阻塞操作

- Locks are allowed only on files, but not directories. 只允许在文件上使用锁，不允许在目录上使用锁

- Locks are automatically removed when the process exits or terminates. It’s guaranteed that if a lock is acquired, the process acquiring the lock is still alive. 当进程退出或终止时，锁将自动移除。它保证如果获取了锁，获取锁的进程仍然是活动的

Differing features 不同的特征

This table summarizes the difference between the lock types. A more detailed description and usage examples are provided below.

此表总结了锁类型之间的差异。下面提供了更详细的描述和使用示例。

|  | BSD locks BSD 锁 | lockf function 锁函数 | POSIX record locks POSIX 记录锁 | Open file description locks 打开文件描述锁 |
| - | - | - | - | - |
| Portability 便携性 | widely available 广泛使用的 | POSIX (XSI) | POSIX (base standard) POSIX (基本标准) | Linux 3.15+ 3.15 + |
| Associated with 相关的 | File object 文件对象 | [i-node, pid] pair [ i-node，pid ]对 | [i-node, pid] pair [ i-node，pid ]对 | File object 文件对象 |
| Applying to byte range 应用于字节范围 | no 没有 | yes 是的 | yes 是的 | yes 是的 |
| Support exclusive and shared modes 支持独占和共享模式 | yes 是的 | no 没有 | yes 是的 | yes 是的 |
| Atomic mode switch 原子模式开关 | no 没有 | - | yes 是的 | yes 是的 |
| Works on NFS (Linux) 在 NFS (Linux)上工作 | Linux 2.6.12+ 2.6.12 + | yes 是的 | yes 是的 | yes 是的 |


File descriptors and i-nodes 文件描述符和 i 节点

A file descriptor is an index in the per-process file descriptor table (in the left of the picture). Each file descriptor table entry contains a reference to a file object, stored in the file table (in the middle of the picture). Each file object contains a reference to an i-node, stored in the i-node table (in the right of the picture).

文件描述符是每个进程文件描述符表中的索引(在图片的左侧)。每个文件描述符表条目都包含对文件对象的引用，存储在文件表中(在图片的中间)。每个文件对象都包含对 i-node 的引用，存储在 i-node 表中(在图片的右侧)。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243718.jpg)

A file descriptor is just a number that is used to refer a file object from the user space. A file object represents an opened file. It contains things likes current read/write offset, non-blocking flag and another non-persistent state. An i-node represents a filesystem object. It contains things like file meta-information (e.g. owner and permissions) and references to data blocks.

文件描述符只是一个数字，用于从用户空间引用文件对象。文件对象表示打开的文件。它包含当前读/写偏移量、非阻塞标志和另一个非持久状态等内容。I-node 表示文件系统对象。它包含文件元信息(例如所有者和权限)和对数据块的引用。

File descriptors created by several open() calls for the same file path point to different file objects, but these file objects point to the same i-node. Duplicated file descriptors created by dup2() or fork() point to the same file object.

由多个 open ()调用为同一文件路径创建的文件描述符指向不同的文件对象，但是这些文件对象指向同一个 i-node。Dup2()或 fork ()创建的重复文件描述符指向同一个文件对象。

A BSD lock and an Open file description lock is associated with a file object, while a POSIX record lock is associated with an [i-node, pid] pair. We’ll discuss it below.

BSD 锁和 Open 文件描述锁与文件对象关联，而 POSIX 记录锁与[ i-node，pid ]对关联。我们将在下面讨论这个问题。

BSD locks (flock) BSD 锁(群)

The simplest and most common file locks are provided by flock(2).

Flock (2)提供了最简单和最常见的文件锁。

Features:

特点:

- not specified in POSIX, but widely available on various Unix systems 在 POSIX 没有具体说明，但在各种 Unix 系统上广泛使用

- always lock the entire file 总是锁定整个文件

- associated with a file object 与一个文件对象相关联

- do not guarantee atomic switch between the locking modes (exclusive and shared) 不保证锁定模式(排他和共享)之间的原子切换

- up to Linux 2.6.11, didn’t work on NFS; since Linux 2.6.12, flock() locks on NFS are emulated using fcntl() POSIX record byte-range locks on the entire file (unless the emulation is disabled in the NFS mount options) 由于 Linux 2.6.12，NFS 上的 flock ()锁使用整个文件上的 fcntl () POSIX 记录字节范围锁来模拟(除非在 NFS 挂载选项中禁用模拟)

The lock acquisition is associated with a file object, i.e.:

获取锁与一个文件对象相关联，即:

- duplicated file descriptors, e.g. created using 重复的文件描述符，例如使用dup2 or 或fork, share the lock acquisition; 、分享获取锁的过程;

- independent file descriptors, e.g. created using two 独立的文件描述符，例如使用两个open calls (even for the same file), don’t share the lock acquisition; 调用(即使是同一个文件) ，不共享锁获取;

This means that with BSD locks, threads or processes can’t be synchronized on the same or duplicated file descriptor, but nevertheless, both can be synchronized on independent file descriptors.

这意味着，使用 BSD 锁时，线程或进程不能在相同或重复的文件描述符上同步，但是，两者都可以在独立的文件描述符上同步。

flock() doesn’t guarantee atomic mode switch. From the man page:

Flock ()不能保证原子模式切换:

Converting a lock (shared to exclusive, or vice versa) is not guaranteed to be atomic: the existing lock is first removed, and then a new lock is established. Between these two steps, a pending lock request by another process may be granted, with the result that the conversion either blocks, or fails if LOCK_NB was specified. (This is the original BSD behaviour, and occurs on many other implementations.)

转换一个锁(从共享到独占，或者从独占到共享)并不能保证是原子的: 首先移除现有的锁，然后建立一个新的锁。在这两个步骤之间，可以授予另一个进程的挂起锁请求，结果转换要么阻塞，要么失败，如果指定了 LOCK _ nb。(这是原始的 BSD 行为，并且发生在许多其他实现上。)

This problem is solved by POSIX record locks and Open file description locks.

POSIX 记录锁和打开文件描述锁解决了这个问题。

Usage example:

使用例子:

#include <sys/file.h>

// acquire shared lock
if (flock(fd, LOCK_SH) == -1) {
    exit(1);
}

// non-atomically upgrade to exclusive lock
// do it in non-blocking mode, i.e. fail if can't upgrade immediately
if (flock(fd, LOCK_EX | LOCK_NB) == -1) {
    exit(1);
}

// release lock
// lock is also released automatically when close() is called or process exits
if (flock(fd, LOCK_UN) == -1) {
    exit(1);
}


POSIX record locks (fcntl) POSIX 记录锁(fcntl)

POSIX record locks, also known as process-associated locks, are provided by fcntl(2), see “Advisory record locking” section in the man page.

POSIX 记录锁，也称为与进程相关的锁，由 fcntl (2)提供，请参阅 man 页中的“ Advisory record locking”部分。

Features:

特点:

- specified 指定 in POSIX (base standard) 在 POSIX (基本标准)

- can be applied to a byte range 可以应用于一个字节范围

- associated with an 与... 有关的[i-node, pid] pair instead of a file object 对象而不是文件对象

- guarantee atomic switch between the locking modes (exclusive and shared) 保证锁模式之间的原子开关(独占和共享)

- work on NFS (on Linux) 在 NFS (在 Linux 上)上工作

The lock acquisition is associated with an [i-node, pid] pair, i.e.:

锁获取与一个[ i-node，pid ]对相关联，即:

- file descriptors opened by the same process for the same file share the lock acquisition (even independent file descriptors, e.g. created using two 同一进程为同一文件打开的文件描述符共享锁获取(即使是独立的文件描述符，例如使用两个open calls); ) ;

- file descriptors opened by different processes don’t share the lock acquisition; 不同进程打开的文件描述符不共享锁获取;

This means that with POSIX record locks, it is possible to synchronize processes, but not threads. All threads belonging to the same process always share the lock acquisition of a file, which means that:

这意味着使用 POSIX 记录锁，可以同步进程，但不能同步线程。属于同一进程的所有线程总是共享一个文件的锁获取，这意味着:

- the lock acquired through some file descriptor by some thread may be released through another file descriptor by another thread; 某个线程通过某个文件描述符获得的锁可以通过另一个线程通过另一个文件描述符释放;

- when any thread calls 当任何线索调用close on any descriptor referring to given file, the lock is released for the whole process, even if there are other opened descriptors referring to this file. 对于任何引用给定文件的描述符，即使有其他引用该文件的已打开描述符，也会为整个进程释放锁

This problem is solved by Open file description locks.

通过打开文件描述锁解决了这个问题。

Usage example:

使用例子:

#include <fcntl.h>

struct flock fl;
memset(&fl, 0, sizeof(fl));

// lock in shared mode
fl.l_type = F_RDLCK;

// lock entire file
fl.l_whence = SEEK_SET; // offset base is start of the file
fl.l_start = 0;         // starting offset is zero
fl.l_len = 0;           // len is zero, which is a special value representing end
                        // of file (no matter how large the file grows in future)

fl.l_pid = 0; // F_SETLK(W) ignores it; F_OFD_SETLK(W) requires it to be zero

// F_SETLKW specifies blocking mode
if (fcntl(fd, F_SETLKW, &fl) == -1) {
    exit(1);
}

// atomically upgrade shared lock to exclusive lock, but only
// for bytes in range [10; 15)
//
// after this call, the process will hold three lock regions:
//  [0; 10)        - shared lock
//  [10; 15)       - exclusive lock
//  [15; SEEK_END) - shared lock
fl.l_type = F_WRLCK;
fl.l_start = 10;
fl.l_len = 5;

// F_SETLKW specifies non-blocking mode
if (fcntl(fd, F_SETLK, &fl) == -1) {
    exit(1);
}

// release lock for bytes in range [10; 15)
fl.l_type = F_UNLCK;

if (fcntl(fd, F_SETLK, &fl) == -1) {
    exit(1);
}

// close file and release locks for all regions
// remember that locks are released when process calls close()
// on any descriptor for a lock file
close(fd);


lockf function 锁函数

lockf(3) function is a simplified version of POSIX record locks.

Lockf (3)函数是 POSIX 记录锁的简化版本。

Features:

特点:

- specified 指定 in POSIX (XSI) 在 POSIX (XSI)中

- can be applied to a byte range (optionally automatically expanding when data is appended in future) 可以应用到一个字节范围(可以选择在将来追加数据时自动扩展)

- associated with an 与... 有关的[i-node, pid] pair instead of a file object 对象而不是文件对象

- supports only exclusive locks 只支持独占锁

- works on NFS (on Linux) 可以在 NFS (在 Linux 上)上工作

Since lockf locks are associated with an [i-node, pid] pair, they have the same problems as POSIX record locks described above.

由于 lockf 锁与[ i-node，pid ]对相关联，因此它们与上面描述的 POSIX 记录锁具有相同的问题。

The interaction between lockf and other types of locks is not specified by POSIX. On Linux, lockf is just a wrapper for POSIX record locks.

POSIX 没有指定 lockf 和其他类型锁之间的交互。在 Linux 上，lockf 只是 POSIX 记录锁的包装器。

Usage example:

使用例子:

#include <unistd.h>

// set current position to byte 10
if (lseek(fd, 10, SEEK_SET) == -1) {
    exit(1);
}

// acquire exclusive lock for bytes in range [10; 15)
// F_LOCK specifies blocking mode
if (lockf(fd, F_LOCK, 5) == -1) {
    exit(1);
}

// release lock for bytes in range [10; 15)
if (lockf(fd, F_ULOCK, 5) == -1) {
    exit(1);
}


Open file description locks (fcntl) 打开文件描述锁(fcntl)

Open file description locks are Linux-specific and combine advantages of the BSD locks and POSIX record locks. They are provided by fcntl(2), see “Open file description locks (non-POSIX)” section in the man page.

打开文件描述锁是特定于 linux 的，它结合了 BSD 锁和 POSIX 记录锁的优点。它们由 fcntl (2)提供，请参阅手册页中的“打开文件描述锁(non-POSIX)”部分。

Features:

特点:

- Linux-specific, not specified in POSIX 特定于 linux 的，没有在 POSIX 中指定

- can be applied to a byte range 可以应用于一个字节范围

- associated with a file object 与一个文件对象相关联

- guarantee atomic switch between the locking modes (exclusive and shared) 保证锁模式之间的原子开关(独占和共享)

- work on NFS (on Linux) 在 NFS (在 Linux 上)上工作

Thus, Open file description locks combine advantages of BSD locks and POSIX record locks: they provide both atomic switch between the locking modes, and the ability to synchronize both threads and processes.

因此，开放文件描述锁结合了 BSD 锁和 POSIX 记录锁的优点: 它们提供了锁模式之间的原子开关，以及同步线程和进程的能力。

These locks are available since the 3.15 kernel.

这些锁在3.15内核之后就可以使用了。

The API is the same as for POSIX record locks (see above). It uses struct flock too. The only difference is in fcntl command names:

该 API 与 POSIX 记录锁相同(见上文)。它也使用结构群。唯一的区别是 fcntl 命令的名称:

- F_OFD_SETLK instead of 而不是F_SETLK

- F_OFD_SETLKW instead of 而不是F_SETLKW

- F_OFD_GETLK instead of 而不是F_GETLK

Emulating Open file description locks 仿真打开文件描述锁

What do we have for multithreading and atomicity so far?

到目前为止，我们对多线程和原子性有什么了解？

- BSD locks allow thread synchronization but don’t allow atomic mode switch. BSD 锁允许线程同步，但不允许原子模式切换

- POSIX record locks don’t allow thread synchronization but allow atomic mode switch. POSIX 记录锁不允许线程同步，但允许原子模式切换

- Open file description locks allow both but are available only on recent Linux kernels. 打开的文件描述锁允许两者兼容，但是只能在最近的 Linux 内核上使用

If you need both features but can’t use Open file description locks (e.g. you’re using some embedded system with an outdated Linux kernel), you can emulate them on top of the POSIX record locks.

如果您需要这两个特性，但是不能使用 Open 文件描述锁(例如，您使用的嵌入式系统具有过时的 Linux 内核) ，您可以在 POSIX 记录锁之上模拟它们。

Here is one possible approach:

这里有一个可能的方法:

- Implement your own API for file locks. Ensure that all threads always use this API instead of using fcntl() directly. Ensure that threads never open and close lock-files directly.

为文件锁定实现自己的 API。确保所有线程始终使用此 API，而不是直接使用 fcntl ()。确保线程从未直接打开和关闭锁文件。

- In the API, implement a process-wide singleton (shared by all threads) holding all currently acquired locks.

在 API 中，实现一个进程范围的单例(由所有线程共享) ，其中包含当前获取的所有锁。

- Associate two additional objects with every acquired lock:

将另外两个对象与每个获取的锁相关联:

- a counter 一个计数器

- an RW-mutex, e.g. 一个 RW-mutex，例如pthread_rwlock

Now, you can implement lock operations as follows:

现在，您可以实现如下锁定操作:

- Acquiring lock

获取锁

- First, acquire the RW-mutex. If the user requested the shared mode, acquire a read lock. If the user requested the exclusive mode, acquire a write lock. 首先，获取 RW-mutex。如果用户请求共享模式，请获取一个读锁。如果用户请求独占模式，请获取写锁

- Check the counter. If it’s zero, also acquire the file lock using 检查计数器。如果它是零，也获取文件锁使用fcntl().

- Increment the counter. 递增计数器

- Releasing lock

释放锁

- Decrement the counter. 减少计数器

- If the counter becomes zero, release the file lock using 如果计数器变为零，则使用fcntl().

- Release the RW-mutex. 释放 RW-mutex

This approach makes possible both thread and process synchronization.

这种方法使线程和进程同步成为可能。

Test program 测试程序

I’ve prepared a small program that helps to learn the behavior of different lock types.

我准备了一个小程序来帮助学习不同锁类型的行为。

The program starts two threads or processes, both of which wait to acquire the lock, then sleep for one second, and then release the lock. It has three parameters:

程序启动两个线程或进程，它们都等待获得锁，然后休眠一秒钟，然后释放锁。它有三个参数:

- lock mode: flock (BSD locks), lockf, fcntl_posix (POSIX record locks), fcntl_linux (Open file description locks)

锁模式: flock (BSD locks) ，lockf，fcntl _ POSIX (POSIX record locks) ，fcntl _ linux (Open file description locks)

- access mode: same_fd (access lock via the same descriptor), dup_fd (access lock via duplicated descriptors), two_fds (access lock via two descriptors opened independently for the same path)

访问模式: same _ fd (通过相同描述符访问锁) ，dup _ fd (通过重复描述符访问锁) ，两个 _ fd (通过两个描述符为相同路径独立打开访问锁)

- concurrency mode: threads (access lock from two threads), processes (access lock from two processes)

并发模式: 线程(从两个线程访问锁) ，进程(从两个进程访问锁)

Below you can find some examples.

下面你可以找到一些例子。

Threads are not serialized if they use BSD locks on duplicated descriptors:

如果线程在重复的描述符上使用 BSD 锁，则不会被序列化:

$ ./a.out flock dup_fd threads
13:00:58 pid=5790 tid=5790 lock
13:00:58 pid=5790 tid=5791 lock
13:00:58 pid=5790 tid=5790 sleep
13:00:58 pid=5790 tid=5791 sleep
13:00:59 pid=5790 tid=5791 unlock
13:00:59 pid=5790 tid=5790 unlock


But they are serialized if they are used on two independent descriptors:

但是如果在两个独立的描述符上使用它们，那么它们就会被序列化:

$ ./a.out flock two_fds threads
13:01:03 pid=5792 tid=5792 lock
13:01:03 pid=5792 tid=5794 lock
13:01:03 pid=5792 tid=5792 sleep
13:01:04 pid=5792 tid=5792 unlock
13:01:04 pid=5792 tid=5794 sleep
13:01:05 pid=5792 tid=5794 unlock


Threads are not serialized if they use POSIX record locks on two independent descriptors:

如果线程在两个独立的描述符上使用 POSIX 记录锁，则不会序列化:

$ ./a.out fcntl_posix two_fds threads
13:01:08 pid=5795 tid=5795 lock
13:01:08 pid=5795 tid=5796 lock
13:01:08 pid=5795 tid=5795 sleep
13:01:08 pid=5795 tid=5796 sleep
13:01:09 pid=5795 tid=5795 unlock
13:01:09 pid=5795 tid=5796 unlock


But processes are serialized:

但是过程是连续的:

$ ./a.out fcntl_posix two_fds processes
13:01:13 pid=5797 tid=5797 lock
13:01:13 pid=5798 tid=5798 lock
13:01:13 pid=5797 tid=5797 sleep
13:01:14 pid=5797 tid=5797 unlock
13:01:14 pid=5798 tid=5798 sleep
13:01:15 pid=5798 tid=5798 unlock


Command-line tools 命令行工具

The following tools may be used to acquire and release file locks from the command line:

可以使用下列工具从命令行获取和释放文件锁:

- flock

羊群

Provided by util-linux package. Uses flock() function.

由 util-linux 包提供，使用 flock ()函数。

There are two ways to use this tool:

使用这个工具有两种方法:

- run a command while holding a lock:

在锁定状态下运行命令:

flock my.lock sleep 10


flock will acquire the lock, run the command, and release the lock.

鸥群将获得锁定，运行命令，并释放锁定。

- open a file descriptor in bash and use flock to acquire and release the lock manually:

在 bash 中打开一个文件描述符，并使用 flock 手动获取和释放锁:

set -e            # die on errors
exec 100>my.lock  # open file 'my.lock' and link file descriptor 100 to it
flock -n 100      # acquire a lock
echo hello
sleep 10
echo goodbye
flock -u -n 100   # release the lock


You can try to run these two snippets in parallel in different terminals and see that while one is sleeping while holding the lock, another is blocked in flock.

你可以尝试在不同的终端上并行运行这两个代码片段，看看其中一个在持锁睡觉，另一个在群中被阻塞。

- lockfile

锁文件

Provided by procmail package.

由 procmail 包装提供。

Runs the given command while holding a lock. Can use either flock(), lockf(), or fcntl() function, depending on what’s available on the system.

持有锁时运行给定的命令。可以使用 flock ()、 lockf ()或 fcntl ()函数，这取决于系统上的可用内容。

There are also two ways to inspect the currently acquired locks:

还有两种方法可以检查当前获得的锁:

- lslocks

小岛

Provided by util-linux package.

由 util-linux 包提供。

Lists all the currently held file locks in the entire system. Allows to perform filtering by PID and to configure the output format.

列出整个系统中当前持有的所有文件锁。允许执行 PID 过滤和配置输出格式。

Example output:

输出示例:

COMMAND           PID   TYPE   SIZE MODE  M      START        END PATH
containerd       4498  FLOCK   256K WRITE 0          0          0 /var/lib/docker/containerd/...
dockerd          4289  FLOCK   256K WRITE 0          0          0 /var/lib/docker/volumes/...
(undefined)        -1 OFDLCK        READ  0          0          0 /dev...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/builder/...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/buildkit/...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/buildkit/...
dockerd          4289  FLOCK    32K WRITE 0          0          0 /var/lib/docker/buildkit/...
(unknown)        4417  FLOCK        WRITE 0          0          0 /run...


- /proc/locks

A file in procfs virtual file system that shows current file locks of all types. The lslocks tools relies on this file.

Procfs 虚拟文件系统中的一个文件，显示所有类型的当前文件锁。Lslocks 工具依赖于此文件。

Example content:

示例内容:

16: FLOCK  ADVISORY  WRITE 4417 00:17:23319 0 EOF
27: FLOCK  ADVISORY  WRITE 4289 08:03:9441686 0 EOF
28: FLOCK  ADVISORY  WRITE 4289 08:03:9441684 0 EOF
29: FLOCK  ADVISORY  WRITE 4289 08:03:9441681 0 EOF
30: FLOCK  ADVISORY  WRITE 4289 08:03:8528339 0 EOF
31: OFDLCK ADVISORY  READ  -1 00:06:9218 0 EOF
43: FLOCK  ADVISORY  WRITE 4289 08:03:8536567 0 EOF
52: FLOCK  ADVISORY  WRITE 4498 08:03:8520185 0 EOF


---

Mandatory locking 强制锁定

Linux has limited support for mandatory file locking. See the “Mandatory locking” section in the fcntl(2) man page.

Linux 对强制文件锁定的支持有限。

A mandatory lock is activated for a file when all of these conditions are met:

当满足以下所有条件时，文件的强制锁定将被激活:

- The partition was mounted with the 这个隔板上安装了mand option. 选择

- The set-group-ID bit is on and group-execute bit is off for the file. set-group-ID 位是打开的，文件的 group-execute 位是关闭的

- A POSIX record lock is acquired. 获取 POSIX 记录锁

Note that the set-group-ID bit has its regular meaning of elevating privileges when the group-execute bit is on and a special meaning of enabling mandatory locking when the group-execute bit is off.

注意，set-group-ID 位具有在 group-execute 位打开时提升特权的一般含义，以及在 group-execute 位关闭时启用强制锁定的特殊含义。

When a mandatory lock is activated, it affects regular system calls on the file:

当一个强制锁被激活时，它会影响文件上的常规系统调用:

- When an exclusive or shared lock is acquired, all system calls that modify the file (e.g. open() and truncate()) are blocked until the lock is released.

当获得一个独占或共享锁时，所有修改该文件的系统调用(例如 open ()和 truncate ())都会被阻塞，直到锁被释放。

- When an exclusive lock is acquired, all system calls that read from the file (e.g. read()) are blocked until the lock is released.

当获得一个独占锁定时，所有从该文件读取的系统调用(例如 read ())都会被阻塞，直到锁定被释放。

However, the documentation mentions that current implementation is not reliable, in particular:

然而，文档中提到目前的实现并不可靠，特别是:

- races are possible when locks are acquired concurrently with 当锁被同时获取时，竞争是可能的read() or 或write()

- races are possible when using 比赛是可能的当使用mmap()

Since mandatory locks are not allowed for directories and are ignored by unlink() and rename() calls, you can’t prevent file deletion or renaming using these locks.

由于目录不允许使用强制锁，并且 unlink ()和 rename ()调用会忽略它们，因此无法阻止使用这些锁删除或重命名文件。

Example usage 使用示例

Below you can find a usage example of mandatory locking.

下面您可以找到一个强制锁定的使用示例。

fcntl_lock.c:

第三部分:

#include <sys/fcntl.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>

int main(int argc, char **argv) {
    if (argc != 2) {
        fprintf(stderr, "usage: %s file\n", argv[0]);
        exit(1);
    }

    int fd = open(argv[1], O_RDWR);
    if (fd == -1) {
        perror("open");
        exit(1);
    }

    struct flock fl = {};
    fl.l_type = F_WRLCK;
    fl.l_whence = SEEK_SET;
    fl.l_start = 0;
    fl.l_len = 0;

    if (fcntl(fd, F_SETLKW, &fl) == -1) {
        perror("fcntl");
        exit(1);
    }

    pause();
    exit(0);
}


Build fcntl_lock:

建立 fcntl 锁:

$ gcc -o fcntl_lock fcntl_lock.c


Mount the partition and create a file with the mandatory locking enabled:

挂载分区并创建一个启用强制锁定的文件:

$ mkdir dir
$ mount -t tmpfs -o mand,size=1m tmpfs ./dir
$ echo hello > dir/lockfile
$ chmod g+s,g-x dir/lockfile


Acquire a lock in the first terminal:

在第一个终端获得一个锁:

$ ./fcntl_lock dir/lockfile
(wait for a while)
^C


Try to read the file in the second terminal:

尝试读取第二个终端中的文件:

$ cat dir/lockfile
(hangs until ^C is pressed in the first terminal)
hello






https://gavv.github.io/articles/file-locks/

File locking in Linux

29 Jul 2016

linux

 

posix

 

ipc

Table of contents

- Introduction

- Advisory locking

- Common features

- Differing features

- File descriptors and i-nodes

- BSD locks (flock)

- POSIX record locks (fcntl)

- lockf function

- Open file description locks (fcntl)

- Emulating Open file description locks

- Test program

- Command-line tools

- Mandatory locking

- Example usage

---

Introduction

File locking is a mutual-exclusion mechanism for files. Linux supports two major kinds of file locks:

- advisory locks

- mandatory locks

Below we discuss all lock types available in POSIX and Linux and provide usage examples.

---

Advisory locking

Traditionally, locks are advisory in Unix. They work only when a process explicitly acquires and releases locks, and are ignored if a process is not aware of locks.

There are several types of advisory locks available in Linux:

- BSD locks (flock)

- POSIX record locks (fcntl, lockf)

- Open file description locks (fcntl)

All locks except the lockf function are reader-writer locks, i.e. support exclusive and shared modes.

Note that flockfile and friends have nothing to do with the file locks. They manage internal mutex of the FILE object from stdio.

Reference:

- File Locks, GNU libc manual

- Open File Description Locks, GNU libc manual

- File-private POSIX locks, an LWN article about the predecessor of open file description locks

Common features

The following features are common for locks of all types:

- All locks support blocking and non-blocking operations.

- Locks are allowed only on files, but not directories.

- Locks are automatically removed when the process exits or terminates. It’s guaranteed that if a lock is acquired, the process acquiring the lock is still alive.

Differing features

This table summarizes the difference between the lock types. A more detailed description and usage examples are provided below.

|  | BSD locks | lockf function | POSIX record locks | Open file description locks |
| - | - | - | - | - |
| Portability | widely available | POSIX (XSI) | POSIX (base standard) | Linux 3.15+ |
| Associated with | File object | [i-node, pid] pair | [i-node, pid] pair | File object |
| Applying to byte range | no | yes | yes | yes |
| Support exclusive and shared modes | yes | no | yes | yes |
| Atomic mode switch | no | - | yes | yes |
| Works on NFS (Linux) | Linux 2.6.12+ | yes | yes | yes |


File descriptors and i-nodes

A file descriptor is an index in the per-process file descriptor table (in the left of the picture). Each file descriptor table entry contains a reference to a file object, stored in the file table (in the middle of the picture). Each file object contains a reference to an i-node, stored in the i-node table (in the right of the picture).

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243718.jpg)

A file descriptor is just a number that is used to refer a file object from the user space. A file object represents an opened file. It contains things likes current read/write offset, non-blocking flag and another non-persistent state. An i-node represents a filesystem object. It contains things like file meta-information (e.g. owner and permissions) and references to data blocks.

File descriptors created by several open() calls for the same file path point to different file objects, but these file objects point to the same i-node. Duplicated file descriptors created by dup2() or fork() point to the same file object.

A BSD lock and an Open file description lock is associated with a file object, while a POSIX record lock is associated with an [i-node, pid] pair. We’ll discuss it below.

BSD locks (flock)

The simplest and most common file locks are provided by flock(2).

Features:

- not specified in POSIX, but widely available on various Unix systems

- always lock the entire file

- associated with a file object

- do not guarantee atomic switch between the locking modes (exclusive and shared)

- up to Linux 2.6.11, didn’t work on NFS; since Linux 2.6.12, flock() locks on NFS are emulated using fcntl() POSIX record byte-range locks on the entire file (unless the emulation is disabled in the NFS mount options)

The lock acquisition is associated with a file object, i.e.:

- duplicated file descriptors, e.g. created using dup2 or fork, share the lock acquisition;

- independent file descriptors, e.g. created using two open calls (even for the same file), don’t share the lock acquisition;

This means that with BSD locks, threads or processes can’t be synchronized on the same or duplicated file descriptor, but nevertheless, both can be synchronized on independent file descriptors.

flock() doesn’t guarantee atomic mode switch. From the man page:

Converting a lock (shared to exclusive, or vice versa) is not guaranteed to be atomic: the existing lock is first removed, and then a new lock is established. Between these two steps, a pending lock request by another process may be granted, with the result that the conversion either blocks, or fails if LOCK_NB was specified. (This is the original BSD behaviour, and occurs on many other implementations.)

This problem is solved by POSIX record locks and Open file description locks.

Usage example:

#include <sys/file.h>

// acquire shared lock
if (flock(fd, LOCK_SH) == -1) {
    exit(1);
}

// non-atomically upgrade to exclusive lock
// do it in non-blocking mode, i.e. fail if can't upgrade immediately
if (flock(fd, LOCK_EX | LOCK_NB) == -1) {
    exit(1);
}

// release lock
// lock is also released automatically when close() is called or process exits
if (flock(fd, LOCK_UN) == -1) {
    exit(1);
}


POSIX record locks (fcntl)

POSIX record locks, also known as process-associated locks, are provided by fcntl(2), see “Advisory record locking” section in the man page.

Features:

- specified in POSIX (base standard)

- can be applied to a byte range

- associated with an [i-node, pid] pair instead of a file object

- guarantee atomic switch between the locking modes (exclusive and shared)

- work on NFS (on Linux)

The lock acquisition is associated with an [i-node, pid] pair, i.e.:

- file descriptors opened by the same process for the same file share the lock acquisition (even independent file descriptors, e.g. created using two open calls);

- file descriptors opened by different processes don’t share the lock acquisition;

This means that with POSIX record locks, it is possible to synchronize processes, but not threads. All threads belonging to the same process always share the lock acquisition of a file, which means that:

- the lock acquired through some file descriptor by some thread may be released through another file descriptor by another thread;

- when any thread calls close on any descriptor referring to given file, the lock is released for the whole process, even if there are other opened descriptors referring to this file.

This problem is solved by Open file description locks.

Usage example:

#include <fcntl.h>

struct flock fl;
memset(&fl, 0, sizeof(fl));

// lock in shared mode
fl.l_type = F_RDLCK;

// lock entire file
fl.l_whence = SEEK_SET; // offset base is start of the file
fl.l_start = 0;         // starting offset is zero
fl.l_len = 0;           // len is zero, which is a special value representing end
                        // of file (no matter how large the file grows in future)

fl.l_pid = 0; // F_SETLK(W) ignores it; F_OFD_SETLK(W) requires it to be zero

// F_SETLKW specifies blocking mode
if (fcntl(fd, F_SETLKW, &fl) == -1) {
    exit(1);
}

// atomically upgrade shared lock to exclusive lock, but only
// for bytes in range [10; 15)
//
// after this call, the process will hold three lock regions:
//  [0; 10)        - shared lock
//  [10; 15)       - exclusive lock
//  [15; SEEK_END) - shared lock
fl.l_type = F_WRLCK;
fl.l_start = 10;
fl.l_len = 5;

// F_SETLKW specifies non-blocking mode
if (fcntl(fd, F_SETLK, &fl) == -1) {
    exit(1);
}

// release lock for bytes in range [10; 15)
fl.l_type = F_UNLCK;

if (fcntl(fd, F_SETLK, &fl) == -1) {
    exit(1);
}

// close file and release locks for all regions
// remember that locks are released when process calls close()
// on any descriptor for a lock file
close(fd);


lockf function

lockf(3) function is a simplified version of POSIX record locks.

Features:

- specified in POSIX (XSI)

- can be applied to a byte range (optionally automatically expanding when data is appended in future)

- associated with an [i-node, pid] pair instead of a file object

- supports only exclusive locks

- works on NFS (on Linux)

Since lockf locks are associated with an [i-node, pid] pair, they have the same problems as POSIX record locks described above.

The interaction between lockf and other types of locks is not specified by POSIX. On Linux, lockf is just a wrapper for POSIX record locks.

Usage example:

#include <unistd.h>

// set current position to byte 10
if (lseek(fd, 10, SEEK_SET) == -1) {
    exit(1);
}

// acquire exclusive lock for bytes in range [10; 15)
// F_LOCK specifies blocking mode
if (lockf(fd, F_LOCK, 5) == -1) {
    exit(1);
}

// release lock for bytes in range [10; 15)
if (lockf(fd, F_ULOCK, 5) == -1) {
    exit(1);
}


Open file description locks (fcntl)

Open file description locks are Linux-specific and combine advantages of the BSD locks and POSIX record locks. They are provided by fcntl(2), see “Open file description locks (non-POSIX)” section in the man page.

Features:

- Linux-specific, not specified in POSIX

- can be applied to a byte range

- associated with a file object

- guarantee atomic switch between the locking modes (exclusive and shared)

- work on NFS (on Linux)

Thus, Open file description locks combine advantages of BSD locks and POSIX record locks: they provide both atomic switch between the locking modes, and the ability to synchronize both threads and processes.

These locks are available since the 3.15 kernel.

The API is the same as for POSIX record locks (see above). It uses struct flock too. The only difference is in fcntl command names:

- F_OFD_SETLK instead of F_SETLK

- F_OFD_SETLKW instead of F_SETLKW

- F_OFD_GETLK instead of F_GETLK

Emulating Open file description locks

What do we have for multithreading and atomicity so far?

- BSD locks allow thread synchronization but don’t allow atomic mode switch.

- POSIX record locks don’t allow thread synchronization but allow atomic mode switch.

- Open file description locks allow both but are available only on recent Linux kernels.

If you need both features but can’t use Open file description locks (e.g. you’re using some embedded system with an outdated Linux kernel), you can emulate them on top of the POSIX record locks.

Here is one possible approach:

- Implement your own API for file locks. Ensure that all threads always use this API instead of using fcntl() directly. Ensure that threads never open and close lock-files directly.

- In the API, implement a process-wide singleton (shared by all threads) holding all currently acquired locks.

- Associate two additional objects with every acquired lock:

- a counter

- an RW-mutex, e.g. pthread_rwlock

Now, you can implement lock operations as follows:

- Acquiring lock

- First, acquire the RW-mutex. If the user requested the shared mode, acquire a read lock. If the user requested the exclusive mode, acquire a write lock.

- Check the counter. If it’s zero, also acquire the file lock using fcntl().

- Increment the counter.

- Releasing lock

- Decrement the counter.

- If the counter becomes zero, release the file lock using fcntl().

- Release the RW-mutex.

This approach makes possible both thread and process synchronization.

Test program

I’ve prepared a small program that helps to learn the behavior of different lock types.

The program starts two threads or processes, both of which wait to acquire the lock, then sleep for one second, and then release the lock. It has three parameters:

- lock mode: flock (BSD locks), lockf, fcntl_posix (POSIX record locks), fcntl_linux (Open file description locks)

- access mode: same_fd (access lock via the same descriptor), dup_fd (access lock via duplicated descriptors), two_fds (access lock via two descriptors opened independently for the same path)

- concurrency mode: threads (access lock from two threads), processes (access lock from two processes)

Below you can find some examples.

Threads are not serialized if they use BSD locks on duplicated descriptors:

$ ./a.out flock dup_fd threads
13:00:58 pid=5790 tid=5790 lock
13:00:58 pid=5790 tid=5791 lock
13:00:58 pid=5790 tid=5790 sleep
13:00:58 pid=5790 tid=5791 sleep
13:00:59 pid=5790 tid=5791 unlock
13:00:59 pid=5790 tid=5790 unlock


But they are serialized if they are used on two independent descriptors:

$ ./a.out flock two_fds threads
13:01:03 pid=5792 tid=5792 lock
13:01:03 pid=5792 tid=5794 lock
13:01:03 pid=5792 tid=5792 sleep
13:01:04 pid=5792 tid=5792 unlock
13:01:04 pid=5792 tid=5794 sleep
13:01:05 pid=5792 tid=5794 unlock


Threads are not serialized if they use POSIX record locks on two independent descriptors:

$ ./a.out fcntl_posix two_fds threads
13:01:08 pid=5795 tid=5795 lock
13:01:08 pid=5795 tid=5796 lock
13:01:08 pid=5795 tid=5795 sleep
13:01:08 pid=5795 tid=5796 sleep
13:01:09 pid=5795 tid=5795 unlock
13:01:09 pid=5795 tid=5796 unlock


But processes are serialized:

$ ./a.out fcntl_posix two_fds processes
13:01:13 pid=5797 tid=5797 lock
13:01:13 pid=5798 tid=5798 lock
13:01:13 pid=5797 tid=5797 sleep
13:01:14 pid=5797 tid=5797 unlock
13:01:14 pid=5798 tid=5798 sleep
13:01:15 pid=5798 tid=5798 unlock


Command-line tools

The following tools may be used to acquire and release file locks from the command line:

- flock

Provided by util-linux package. Uses flock() function.

There are two ways to use this tool:

- run a command while holding a lock:

flock my.lock sleep 10


flock will acquire the lock, run the command, and release the lock.

- open a file descriptor in bash and use flock to acquire and release the lock manually:

set -e            # die on errors
exec 100>my.lock  # open file 'my.lock' and link file descriptor 100 to it
flock -n 100      # acquire a lock
echo hello
sleep 10
echo goodbye
flock -u -n 100   # release the lock


You can try to run these two snippets in parallel in different terminals and see that while one is sleeping while holding the lock, another is blocked in flock.

- lockfile

Provided by procmail package.

Runs the given command while holding a lock. Can use either flock(), lockf(), or fcntl() function, depending on what’s available on the system.

There are also two ways to inspect the currently acquired locks:

- lslocks

Provided by util-linux package.

Lists all the currently held file locks in the entire system. Allows to perform filtering by PID and to configure the output format.

Example output:

COMMAND           PID   TYPE   SIZE MODE  M      START        END PATH
containerd       4498  FLOCK   256K WRITE 0          0          0 /var/lib/docker/containerd/...
dockerd          4289  FLOCK   256K WRITE 0          0          0 /var/lib/docker/volumes/...
(undefined)        -1 OFDLCK        READ  0          0          0 /dev...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/builder/...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/buildkit/...
dockerd          4289  FLOCK    16K WRITE 0          0          0 /var/lib/docker/buildkit/...
dockerd          4289  FLOCK    32K WRITE 0          0          0 /var/lib/docker/buildkit/...
(unknown)        4417  FLOCK        WRITE 0          0          0 /run...


- /proc/locks

A file in procfs virtual file system that shows current file locks of all types. The lslocks tools relies on this file.

Example content:

16: FLOCK  ADVISORY  WRITE 4417 00:17:23319 0 EOF
27: FLOCK  ADVISORY  WRITE 4289 08:03:9441686 0 EOF
28: FLOCK  ADVISORY  WRITE 4289 08:03:9441684 0 EOF
29: FLOCK  ADVISORY  WRITE 4289 08:03:9441681 0 EOF
30: FLOCK  ADVISORY  WRITE 4289 08:03:8528339 0 EOF
31: OFDLCK ADVISORY  READ  -1 00:06:9218 0 EOF
43: FLOCK  ADVISORY  WRITE 4289 08:03:8536567 0 EOF
52: FLOCK  ADVISORY  WRITE 4498 08:03:8520185 0 EOF


---

Mandatory locking

Linux has limited support for mandatory file locking. See the “Mandatory locking” section in the fcntl(2) man page.

A mandatory lock is activated for a file when all of these conditions are met:

- The partition was mounted with the mand option.

- The set-group-ID bit is on and group-execute bit is off for the file.

- A POSIX record lock is acquired.

Note that the set-group-ID bit has its regular meaning of elevating privileges when the group-execute bit is on and a special meaning of enabling mandatory locking when the group-execute bit is off.

When a mandatory lock is activated, it affects regular system calls on the file:

- When an exclusive or shared lock is acquired, all system calls that modify the file (e.g. open() and truncate()) are blocked until the lock is released.

- When an exclusive lock is acquired, all system calls that read from the file (e.g. read()) are blocked until the lock is released.

However, the documentation mentions that current implementation is not reliable, in particular:

- races are possible when locks are acquired concurrently with read() or write()

- races are possible when using mmap()

Since mandatory locks are not allowed for directories and are ignored by unlink() and rename() calls, you can’t prevent file deletion or renaming using these locks.

Example usage

Below you can find a usage example of mandatory locking.

fcntl_lock.c:

#include <sys/fcntl.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>

int main(int argc, char **argv) {
    if (argc != 2) {
        fprintf(stderr, "usage: %s file\n", argv[0]);
        exit(1);
    }

    int fd = open(argv[1], O_RDWR);
    if (fd == -1) {
        perror("open");
        exit(1);
    }

    struct flock fl = {};
    fl.l_type = F_WRLCK;
    fl.l_whence = SEEK_SET;
    fl.l_start = 0;
    fl.l_len = 0;

    if (fcntl(fd, F_SETLKW, &fl) == -1) {
        perror("fcntl");
        exit(1);
    }

    pause();
    exit(0);
}


Build fcntl_lock:

$ gcc -o fcntl_lock fcntl_lock.c


Mount the partition and create a file with the mandatory locking enabled:

$ mkdir dir
$ mount -t tmpfs -o mand,size=1m tmpfs ./dir
$ echo hello > dir/lockfile
$ chmod g+s,g-x dir/lockfile


Acquire a lock in the first terminal:

$ ./fcntl_lock dir/lockfile
(wait for a while)
^C


Try to read the file in the second terminal:

$ cat dir/lockfile
(hangs until ^C is pressed in the first terminal)
hello


