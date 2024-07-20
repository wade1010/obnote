[https://www.boost.org/](https://www.boost.org/)

[https://blog.csdn.net/dengdui6364/article/details/126413578](https://blog.csdn.net/dengdui6364/article/details/126413578)

ubuntu编译boost

[https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2](https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2)

tar xf boost_1_73_0.tar.bz2

cd boost_1_73_0

./bootstrap.sh 或者你只编译ceph需要的，我这里编译全部了（./bootstrap.sh  --with-libraries=atomic,chrono,container,context,coroutine,date_time,filesystem,iostreams,program_options,python,random,regex,system,thread） 逗号分割即可，括号里面的实例是ceph需要编译的	

./b2

```text
sudo ./b2 install
```

-- SHA256 hash of

```
/home/bob/workspace/ceph-16.2.12/build/boost/src/boost_1_73_0.tar.bz2

```

does not match expected value

expected: '4eb3b8d442b426dc35346235c8733b5ae35ba431690e38c6a8263dce9fcbb402'

actual: '612865ab8b54ebb5dff7b152bd393423c5bbc022a73a27df2d4ce696e1a45d06'

-- File already exists but hash mismatch. Removing...

-- Downloading...

dst='/home/bob/workspace/ceph-16.2.12/build/boost/src/boost_1_73_0.tar.bz2'

timeout='none'

inactivity timeout='none'

-- Using src='[https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2](https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2)'

[  3%] Built target mgr-dashboard-nodeenv

[  3%] Built target mgr-dashboard-frontend-deps

[  3%] Built target mgr-dashboard-frontend-build

-- verifying file...

```
   file='/home/bob/workspace/ceph-16.2.12/build/boost/src/boost_1_73_0.tar.bz2'

```

-- Downloading... done

-- extracting...

```
 src='/home/bob/workspace/ceph-16.2.12/build/boost/src/boost_1_73_0.tar.bz2'
 dst='/home/bob/workspace/ceph-16.2.12/build/boost/src/Boost'

```

-- extracting... [tar xfz]

-- extracting... [analysis]

-- extracting... [rename]

-- extracting... [clean up]

-- extracting... done

[  3%] No update step for 'Boost'

[  3%] Performing patch step for 'Boost'

patching file libs/python/src/exec.cpp

[  3%] Performing configure step for 'Boost'

Building Boost.Build engine with toolset gcc... tools/build/src/engine/b2

Detecting Python version... 3.8

Detecting Python root... /usr

Unicode/ICU support for Boost.Regex?... /usr

Generating Boost.Build configuration in project-config.jam for gcc...

Bootstrapping is done. To build, run:

```
./b2

```

To generate header files, run:

```
./b2 headers

```

To adjust configuration, edit 'project-config.jam'.

Further information:

- Command line help:

./b2 --help

- Getting started guide:

[http://www.boost.org/more/getting_started/unix-variants.html](http://www.boost.org/more/getting_started/unix-variants.html)

- Boost.Build documentation:

[http://www.boost.org/build/](http://www.boost.org/build/)

[  3%] Performing build step for 'Boost'

Performing configuration checks

```
- default address-model    : 64-bit
- default architecture     : x86
- C++11 mutex              : yes
- lockfree boost::atomic_flag : yes
- zlib                     : yes
- bzip2                    : yes
- lzma                     : yes
- zstd                     : yes
- lzma                     : yes
- has_lzma_cputhreads builds : yes
- has_icu builds           : yes

```

Component configuration:

```
- atomic                   : building
- chrono                   : building
- container                : building
- context                  : building
- contract                 : not building
- coroutine                : building
- date_time                : building
- exception                : not building
- fiber                    : not building
- filesystem               : building
- graph                    : not building
- graph_parallel           : not building
- headers                  : not building
- iostreams                : building
- locale                   : not building
- log                      : not building
- math                     : not building
- mpi                      : not building
- nowide                   : not building
- program_options          : building
- python                   : building
- random                   : building
- regex                    : building
- serialization            : not building
- stacktrace               : not building
- system                   : building
- test                     : not building
- thread                   : building
- timer                    : not building
- type_erasure             : not building
- wave                     : not building

```