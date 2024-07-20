```
ILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/lib/python3.7/site-packages
running install_egg_info
running egg_info
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info
writing /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/PKG-INFO
writing dependency_links to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/dependency_links.txt
writing top-level names to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/top_level.txt
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/SOURCES.txt'
reading manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/SOURCES.txt'
reading manifest template 'MANIFEST.in'
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info/SOURCES.txt'
Copying /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rados/rados.egg-info to /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/lib/python3.7/site-packages/rados-2.0.0-py3.7.egg-info
running install_scripts
writing list of installed files to '/dev/null'
/usr/lib/python3.7/site-packages/Cython/Compiler/Main.py:369: FutureWarning: Cython directive 'language_level' not set, using 2 for now (Py2). This will change in a later release! File: /rpmbuild/BUILD/ceph-16.2.9/src/pybind/rados/rados.pxd
  tree = Parsing.p_module(s, pxd, full_module_name)

Error compiling Cython file:
------------------------------------------------------------
...
#
# Shared object for librbdpy
#
# Copyright 2016 Mehdi Abaakouk <sileht@redhat.com>

IF BUILD_DOC:
  ^
------------------------------------------------------------

rados.pxd:7:3: Compile-time name 'BUILD_DOC' not defined


Error compiling Cython file:
------------------------------------------------------------
...
from cpython cimport PyObject, ref
from cpython.pycapsule cimport *
from libc cimport errno
from libc.stdint cimport *
from libc.stdlib cimport malloc, realloc, free
IF BUILD_DOC:
  ^
------------------------------------------------------------

rados.pyx:21:3: Compile-time name 'BUILD_DOC' not defined

running build
running build_ext
cythoning rbd.pyx to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/pyrex/rbd.c
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/pyrex
running install
running install_lib
running install_egg_info
running egg_info
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info
writing /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/PKG-INFO
writing dependency_links to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/dependency_links.txt
writing top-level names to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/top_level.txt
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/SOURCES.txt'
reading manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/SOURCES.txt'
reading manifest template 'MANIFEST.in'
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info/SOURCES.txt'
Copying /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rbd/rbd.egg-info to /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/lib/python3.7/site-packages/rbd-2.0.0-py3.7.egg-info
running install_scripts
writing list of installed files to '/dev/null'
/usr/lib/python3.7/site-packages/Cython/Compiler/Main.py:369: FutureWarning: Cython directive 'language_level' not set, using 2 for now (Py2). This will change in a later release! File: /rpmbuild/BUILD/ceph-16.2.9/src/pybind/rbd/rbd.pyx
  tree = Parsing.p_module(s, pxd, full_module_name)

Error compiling Cython file:
------------------------------------------------------------
...
from datetime import datetime
import errno
from itertools import chain
import time

IF BUILD_DOC:
  ^
------------------------------------------------------------

rbd.pyx:36:3: Compile-time name 'BUILD_DOC' not defined

Error compiling Cython file:
------------------------------------------------------------
...
        return exception_map[ret](msg, errno=ret)
    else:
        return OSError(msg, errno=ret)


IF BUILD_DOC:
  ^
------------------------------------------------------------

rbd.pyx:360:3: Compile-time name 'BUILD_DOC' not defined

running build
running build_ext
cythoning cephfs.pyx to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/pyrex/cephfs.c
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/pyrex
running install
running install_lib
running install_egg_info
running egg_info
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info
writing /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/PKG-INFO
writing dependency_links to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/dependency_links.txt
writing top-level names to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/top_level.txt
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/SOURCES.txt'
reading manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/SOURCES.txt'
reading manifest template 'MANIFEST.in'
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info/SOURCES.txt'
Copying /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/cephfs/cephfs.egg-info to /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/lib/python3.7/site-packages/cephfs-2.0.0-py3.7.egg-info
running install_scripts
writing list of installed files to '/dev/null'
/usr/lib/python3.7/site-packages/Cython/Compiler/Main.py:369: FutureWarning: Cython directive 'language_level' not set, using 2 for now (Py2). This will change in a later release! File: /rpmbuild/BUILD/ceph-16.2.9/src/pybind/cephfs/cephfs.pyx
  tree = Parsing.p_module(s, pxd, full_module_name)

Error compiling Cython file:
------------------------------------------------------------
...
from cpython cimport PyObject, ref, exc
from libc.stdint cimport *
from libc.stdlib cimport malloc, realloc, free

from types cimport *
IF BUILD_DOC:
  ^
------------------------------------------------------------

cephfs.pyx:10:3: Compile-time name 'BUILD_DOC' not defined

running build
running build_ext
cythoning rgw.pyx to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/pyrex/rgw.c
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/pyrex
running install
running install_lib
running install_egg_info
running egg_info
creating /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info
writing /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/PKG-INFO
writing dependency_links to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/dependency_links.txt
writing top-level names to /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/top_level.txt
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/SOURCES.txt'
reading manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/SOURCES.txt'
reading manifest template 'MANIFEST.in'
writing manifest file '/rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info/SOURCES.txt'
Copying /rpmbuild/BUILD/ceph-16.2.9/build/src/pybind/rgw/rgw.egg-info to /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/lib/python3.7/site-packages/rgw-2.0.0-py3.7.egg-info
running install_scripts
writing list of installed files to '/dev/null'
/usr/lib/python3.7/site-packages/Cython/Compiler/Main.py:369: FutureWarning: Cython directive 'language_level' not set, using 2 for now (Py2). This will change in a later release! File: /rpmbuild/BUILD/ceph-16.2.9/src/pybind/rgw/rgw.pyx
  tree = Parsing.p_module(s, pxd, full_module_name)

Error compiling Cython file:
------------------------------------------------------------
...
from cpython cimport PyObject, ref, exc, array
from libc.stdint cimport *
from libc.stdlib cimport malloc, realloc, free
from cstat cimport stat

IF BUILD_DOC:
  ^
------------------------------------------------------------

rgw.pyx:11:3: Compile-time name 'BUILD_DOC' not defined

-- Installing: /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/share/ceph/mgr
-- Installing: /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/share/ceph/mgr/dashboard
-- Installing: /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/share/ceph/mgr/dashboard/frontend
-- Installing: /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/share/ceph/mgr/dashboard/frontend/.npmrc
-- Installing: /rpmbuild/BUILDROOT/ceph-16.2.9-0.ky10.ky10.sw_64/usr/share/ceph/mgr/dashboard/frontend/angular.json

```