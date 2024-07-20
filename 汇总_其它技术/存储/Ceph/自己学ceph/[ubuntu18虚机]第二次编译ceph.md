时间：2023年2月

环境：ubuntu 18

版本：ceph-15.1.0

官网：[https://docs.ceph.com/en/pacific/install/build-ceph/](https://docs.ceph.com/en/pacific/install/build-ceph/)

sudo apt-get install -y curl libatomic-ops-dev libaio-dev xfslibs-dev libboost-iostreams-dev libtool cython3 libsnappy-dev libleveldb-dev libblkid-dev libudev-dev libkeyutils-dev libcrypto++-dev libcrypto++-doc libcrypto++-utils libfuse-dev libcurl4-openssl-dev libxml++2.6-dev  gperf libjemalloc-dev libfcgi-dev autotools-dev autoconf automake cdbs g++ gcc git libatomic-ops-dev libboost-dev libcrypto++-dev libedit-dev libexpat1-dev libfcgi-dev libfuse-dev libgoogle-perftools-dev libgtkmm-2.4-dev libtool pkg-config uuid-dev libkeyutils-dev uuid-dev libkeyutils-dev  btrfs-tools libcurl4-openssl-dev libssl-dev

wget [https://download.ceph.com/tarballs/ceph-15.1.0.tar.gz](https://download.ceph.com/tarballs/ceph-15.1.0.tar.gz)

tar zxvf ceph-15.1.0.tar.gz

mv ceph-15.1.0 ceph

cd ceph

./install-deps.sh

遇到一个错误

```
Saved ./wheelhouse-wip/typing_extensions-4.1.1-py3-none-any.whl
Saved ./wheelhouse-wip/zipp-3.6.0-py3-none-any.whl
Saved ./wheelhouse-wip/setuptools-59.6.0-py3-none-any.whl
Ignoring mock: markers 'python_version <= "3.3"' don't match your environment
Ignoring ipaddress: markers 'python_version < "3.3"' don't match your environment
Processing /home/ceph/workspace/ceph/src/python-common
  Preparing metadata (setup.py) ... done
Collecting pytest-cov==2.7.1
  Downloading pytest_cov-2.7.1-py2.py3-none-any.whl (17 kB)
ERROR: Exception:
Traceback (most recent call last):
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/urllib3/response.py", line 438, in _error_catcher
    yield
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/urllib3/response.py", line 519, in read
    data = self._fp.read(amt) if not fp_closed else b""
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/cachecontrol/filewrapper.py", line 62, in read
    data = self.__fp.read(amt)
  File "/usr/lib/python3.6/http/client.py", line 467, in read
    n = self.readinto(b)
  File "/usr/lib/python3.6/http/client.py", line 511, in readinto
    n = self.fp.readinto(b)
  File "/usr/lib/python3.6/socket.py", line 586, in readinto
    return self._sock.recv_into(b)
  File "/usr/lib/python3.6/ssl.py", line 1012, in recv_into
    return self.read(nbytes, buffer)
  File "/usr/lib/python3.6/ssl.py", line 874, in read
    return self._sslobj.read(len, buffer)
  File "/usr/lib/python3.6/ssl.py", line 631, in read
    v = self._sslobj.read(len, buffer)
socket.timeout: The read operation timed out

During handling of the above exception, another exception occurred:

Traceback (most recent call last):
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/cli/base_command.py", line 164, in exc_logging_wrapper
    status = run_func(*args)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/cli/req_command.py", line 205, in wrapper
    return func(self, options, args)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/commands/wheel.py", line 144, in run
    requirement_set = resolver.resolve(reqs, check_supported_wheels=True)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/resolver.py", line 93, in resolve
    collected.requirements, max_rounds=try_to_avoid_resolution_too_deep
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/resolvelib/resolvers.py", line 482, in resolve
    state = resolution.resolve(requirements, max_rounds=max_rounds)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/resolvelib/resolvers.py", line 349, in resolve
    self._add_to_criteria(self.state.criteria, r, parent=None)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/resolvelib/resolvers.py", line 173, in _add_to_criteria
    if not criterion.candidates:
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/resolvelib/structs.py", line 151, in __bool__
    return bool(self._sequence)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/found_candidates.py", line 155, in __bool__
    return any(self)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/found_candidates.py", line 143, in <genexpr>
    return (c for c in iterator if id(c) not in self._incompatible_ids)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/found_candidates.py", line 47, in _iter_built
    candidate = func()
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/factory.py", line 206, in _make_candidate_from_link
    version=version,
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/candidates.py", line 287, in __init__
    version=version,
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/candidates.py", line 156, in __init__
    self.dist = self._prepare()
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/candidates.py", line 225, in _prepare
    dist = self._prepare_distribution()
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/resolution/resolvelib/candidates.py", line 292, in _prepare_distribution
    return preparer.prepare_linked_requirement(self._ireq, parallel_builds=True)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/operations/prepare.py", line 482, in prepare_linked_requirement
    return self._prepare_linked_requirement(req, parallel_builds)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/operations/prepare.py", line 528, in _prepare_linked_requirement
    link, req.source_dir, self._download, self.download_dir, hashes
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/operations/prepare.py", line 217, in unpack_url
    hashes=hashes,
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/operations/prepare.py", line 94, in get_http_url
    from_path, content_type = download(link, temp_dir.path)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/network/download.py", line 145, in __call__
    for chunk in chunks:
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_internal/network/utils.py", line 87, in response_chunks
    decode_content=False,
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/urllib3/response.py", line 576, in stream
    data = self.read(amt=amt, decode_content=decode_content)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/urllib3/response.py", line 541, in read
    raise IncompleteRead(self._fp_bytes_read, self.length_remaining)
  File "/usr/lib/python3.6/contextlib.py", line 99, in __exit__
    self.gen.throw(type, value, traceback)
  File "/home/ceph/workspace/ceph/install-deps-python3/lib/python3.6/site-packages/pip/_vendor/urllib3/response.py", line 443, in _error_catcher
    raise ReadTimeoutError(self._pool, None, "Read timed out.")
pip._vendor.urllib3.exceptions.ReadTimeoutError: HTTPSConnectionPool(host='files.pythonhosted.org', port=443): Read timed out.

```

应该是python pip没有配置国内源

我在ceph这个用户和root用户都配置了

```
cd
mkdir .pip
vim .pip/pip.conf
```

内容如下：

```
[global]
index-url = https://mirrors.aliyun.com/pypi/simple/
[install]
trusted-host=mirrors.aliyun.com
```

然后保存，最后再次执行 ./install-deps.sh

不一会就能看到成功了！！！！！

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359608.jpg)

./do_cmake.sh

成功，如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359345.jpg)

cd build

make -j4  

第一次执行，报错，如下

```
[100%] Linking CXX static library librocksdb.a
[100%] Built target rocksdb
[  5%] Performing install step for 'rocksdb_ext'
[  5%] Completed 'rocksdb_ext'
[  5%] Built target rocksdb_ext
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] <--- Last few GCs --->
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] [22832:0x319cdf0]   175323 ms: Mark-sweep 1322.2 (1441.3) -> 1308.3 (1441.3) MB, 916.9 / 0.0 ms  (average mu = 0.219, current mu = 0.158) allocation failure scavenge might not succeed
[build:en-US -- -- --prod --progress=false] [22832:0x319cdf0]   176325 ms: Mark-sweep 1321.9 (1441.3) -> 1309.1 (1442.8) MB, 906.7 / 0.0 ms  (average mu = 0.162, current mu = 0.095) allocation failure scavenge might not succeed
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] <--- JS stacktrace --->
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] ==== JS stack trace =========================================
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false]     0: ExitFrame [pc: 0x1b7cee15be1d]
[build:en-US -- -- --prod --progress=false] Security context: 0x286037b1e6e9 <JSObject>
[build:en-US -- -- --prod --progress=false]     1: /* anonymous */ [0x244fc37df739] [/home/ceph/workspace/ceph/src/pybind/mgr/dashboard/frontend/node_modules/terser/dist/bundle.min.js:~1] [pc=0x1b7cf0fada01](this=0x244fc37df779 <Dn map = 0x2542dfd85699>,t=0x37f55a6c3249 <AST_Null map = 0x2542dfd8be69>,o=0x3be843c535b9 <JSFunction a (sfi = 0x10911f713e31)>)
[build:en-US -- -- --prod --progress=false]     2: _walk [0x3be843c2d119] [/home/ceph/wor...
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] FATAL ERROR: Ineffective mark-compacts near heap limit Allocation failed - JavaScript heap out of memory
[build:en-US -- -- --prod --progress=false]  1: 0x8fa090 node::Abort() [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  2: 0x8fa0dc  [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  3: 0xb0039e v8::Utils::ReportOOMFailure(v8::internal::Isolate*, char const*, bool) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  4: 0xb005d4 v8::internal::V8::FatalProcessOutOfMemory(v8::internal::Isolate*, char const*, bool) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  5: 0xef4ae2  [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  6: 0xef4be8 v8::internal::Heap::CheckIneffectiveMarkCompact(unsigned long, double) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  7: 0xf00cc2 v8::internal::Heap::PerformGarbageCollection(v8::internal::GarbageCollector, v8::GCCallbackFlags) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  8: 0xf015f4 v8::internal::Heap::CollectGarbage(v8::internal::AllocationSpace, v8::internal::GarbageCollectionReason, v8::GCCallbackFlags) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false]  9: 0xf04261 v8::internal::Heap::AllocateRawWithRetryOrFail(int, v8::internal::AllocationSpace, v8::internal::AllocationAlignment) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false] 10: 0xecd6e4 v8::internal::Factory::NewFillerObject(int, bool, v8::internal::AllocationSpace) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false] 11: 0x116d86e v8::internal::Runtime_AllocateInNewSpace(int, v8::internal::Object**, v8::internal::Isolate*) [ng build --outputPath=dist/en-US --i18nFile= --i18nLocale=en-US --prod --progress=false]
[build:en-US -- -- --prod --progress=false] 12: 0x1b7cee15be1d 
[build:en-US -- -- --prod --progress=false] Aborted (core dumped)
[build:en-US -- -- --prod --progress=false] npm ERR! code ELIFECYCLE
[build:en-US -- -- --prod --progress=false] npm ERR! errno 134
[build:en-US -- -- --prod --progress=false] npm ERR! ceph-dashboard@0.0.0 build: `export _locale=${LOCALE:-$npm_package_config_locale}; if [ ${_locale} = $npm_package_config_locale ]; then export _file=; else export _file=src/locale/messages.${_locale}.xlf; fi; ng build --outputPath=dist/${_locale} --i18nFile=${_file} --i18nLocale=${_locale} "--prod" "--progress=false"`
[build:en-US -- -- --prod --progress=false] npm ERR! Exit status 134
[build:en-US -- -- --prod --progress=false] npm ERR! 
[build:en-US -- -- --prod --progress=false] npm ERR! Failed at the ceph-dashboard@0.0.0 build script.
[build:en-US -- -- --prod --progress=false] npm ERR! This is probably not a problem with npm. There is likely additional logging output above.
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] npm ERR! A complete log of this run can be found in:
[build:en-US -- -- --prod --progress=false] npm ERR!     /home/ceph/.npm/_logs/2023-02-26T11_15_32_752Z-debug.log
[build:en-US -- -- --prod --progress=false] npm ERR! code ELIFECYCLE
[build:en-US -- -- --prod --progress=false] npm ERR! errno 134
[build:en-US -- -- --prod --progress=false] npm ERR! ceph-dashboard@0.0.0 build:en-US: `LOCALE=en-US npm run build "--" "--prod" "--progress=false"`
[build:en-US -- -- --prod --progress=false] npm ERR! Exit status 134
[build:en-US -- -- --prod --progress=false] npm ERR! 
[build:en-US -- -- --prod --progress=false] npm ERR! Failed at the ceph-dashboard@0.0.0 build:en-US script.
[build:en-US -- -- --prod --progress=false] npm ERR! This is probably not a problem with npm. There is likely additional logging output above.
[build:en-US -- -- --prod --progress=false] 
[build:en-US -- -- --prod --progress=false] npm ERR! A complete log of this run can be found in:
[build:en-US -- -- --prod --progress=false] npm ERR!     /home/ceph/.npm/_logs/2023-02-26T11_15_32_781Z-debug.log
ERROR: "build:en-US -- -- --prod --progress=false" exited with 134.
src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/build.make:3586: recipe for target '../src/pybind/mgr/dashboard/frontend/dist' failed
make[2]: *** [../src/pybind/mgr/dashboard/frontend/dist] Error 1
CMakeFiles/Makefile2:5103: recipe for target 'src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/all' failed
make[1]: *** [src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/all] Error 2
make[1]: *** Waiting for unfinished jobs....
[  5%] Completed 'Boost'
[  5%] Built target Boost
Makefile:140: recipe for target 'all' failed
make: *** [all] Error 2

```

感觉是node npm没安装

sudo apt install -y nodejs libssl1.0-dev nodejs-dev node-gyp npm

再次执行 make -j4 尝试一波 ，2023-2-26 19:24:25

2023-2-26 19:47:39 发现报错

```
[ 24%] Built target common-common-objs
Makefile:140: recipe for target 'all' failed
make: *** [all] Error 2

```

看了一眼不知道什么原因，我就直接再次执行 make -j 4 2023-2-26 19:47

还是会有fatal error 

fatal error: curl/curl.h: No such file or directory

sudo apt-get install libssl-dev

sudo apt-get install libcrypto++

  [ 51%] Built target gmock_main

[ 51%] Linking CXX executable ../../bin/ceph-dedup-tool

/usr/bin/ld: warning: libcrypto.so.1.0.0, needed by ../../lib/libceph-common.so.2, may conflict with libcrypto.so.1.1

CMakeFiles/ceph-dedup-tool.dir/ceph_dedup_tool.cc.o: In function EstimateDedupRatio::add_chunk_fp_to_stat(ceph::buffer::v14_2_0::list&)': /home/ceph/workspace/ceph/src/common/ceph_crypto.h:72: undefined reference to ceph::crypto::ssl::OpenSSLDigest::OpenSSLDigest(evp_md_st const*)'

/home/ceph/workspace/ceph/src/common/ceph_crypto.h:78: undefined reference to ceph::crypto::ssl::OpenSSLDigest::OpenSSLDigest(evp_md_st const*)' /home/ceph/workspace/ceph/src/common/ceph_crypto.h:84: undefined reference to ceph::crypto::ssl::OpenSSLDigest::OpenSSLDigest(evp_md_st const*)'

collect2: error: ld returned 1 exit status

src/tools/CMakeFiles/ceph-dedup-tool.dir/build.make:120: recipe for target 'bin/ceph-dedup-tool' failed

make[2]: *** [bin/ceph-dedup-tool] Error 1

CMakeFiles/Makefile2:7413: recipe for target 'src/tools/CMakeFiles/ceph-dedup-tool.dir/all' failed

make[1]: *** [src/tools/CMakeFiles/ceph-dedup-tool.dir/all] Error 2

make[1]: *** Waiting for unfinished jobs....

[ 51%] Building CXX object src/rgw/CMakeFiles/rgw_a.dir/rgw_period_pusher.cc.o

[ 51%] Building CXX object src/rgw/CMakeFiles

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359060.jpg)

ceph@ceph:/usr/local/lib$ sudo find / -name 'libcrypto.so*'

/usr/lib/x86_64-linux-gnu/libcrypto.so.1.0.0

/usr/lib/x86_64-linux-gnu/libcrypto.so.1.1

/usr/lib/x86_64-linux-gnu/libcrypto.so

cd /usr/lib/x86_64-linux-gnu

sudo rm -rf libcrypto.so

sudo ln -s libcrypto.so.1.1 libcrypto.so

sudo ln -s libcrypto.so.1.0.0 libcrypto.so

sudo find / -name 'libcrypto.so*'