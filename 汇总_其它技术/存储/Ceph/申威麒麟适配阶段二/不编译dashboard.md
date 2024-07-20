ARGS="-DCMAKE_BUILD_TYPE=RelWithDebInfo -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF -DWITH_SYSTEM_BOOST=ON" ./do_cmake.sh

ARGS="-DCMAKE_INSTALL_PREFIX=/usr -DCMAKE_INSTALL_LIBDIR=/usr/lib -DCMAKE_INSTALL_LIBEXECDIR=/usr/libexec -DCMAKE_INSTALL_LOCALSTATEDIR=/var -DCMAKE_INSTALL_SYSCONFDIR=/etc -DCMAKE_INSTALL_MANDIR=/usr/share/man -DCMAKE_INSTALL_DOCDIR=/usr/share/doc/ceph -DCMAKE_INSTALL_INCLUDEDIR=/usr/include -DCMAKE_INSTALL_SYSTEMD_SERVICEDIR=/usr/lib/systemd/system -DWITH_MANPAGE=ON -DWITH_PYTHON3=3.7 -DWITH_MGR_DASHBOARD_FRONTEND=OFF -DWITH_LTTNG=OFF -DWITH_BABELTRACE=OFF -DWITH_LIBRADOSSTRIPER=OFF -DWITH_RADOSGW_AMQP_ENDPOINT=OFF -DWITH_RADOSGW_KAFKA_ENDPOINT=OFF -DWITH_RADOSGW_LUA_PACKAGES=OFF -DBOOST_J=64 -DWITH_GRAFANA=ON -DCMAKE_BUILD_TYPE=RelWithDebInfo -DWITH_SYSTEM_BOOST=ON"  ./do_cmake.sh

[bob@bogon build]$ sudo pip install markupsafe==1.1.1

[sudo] bob 的密码：

WARNING: Running pip install with root privileges is generally not a good idea. Try pip install --user instead.

Looking in indexes: [https://mirrors.aliyun.com/pypi/simple/](https://mirrors.aliyun.com/pypi/simple/)

Collecting markupsafe==1.1.1

Downloading [https://mirrors.aliyun.com/pypi/packages/b9/2e/64db92e53b86efccfaea71321f597fa2e1b2bd3853d8ce658568f7a13094/MarkupSafe-1.1.1.tar.gz](https://mirrors.aliyun.com/pypi/packages/b9/2e/64db92e53b86efccfaea71321f597fa2e1b2bd3853d8ce658568f7a13094/MarkupSafe-1.1.1.tar.gz)

werkzeug 2.2.3 has requirement MarkupSafe>=2.1.1, but you'll have markupsafe 1.1.1 which is incompatible.

Installing collected packages: markupsafe

Found existing installation: MarkupSafe 2.1.2

Uninstalling MarkupSafe-2.1.2:

Successfully uninstalled MarkupSafe-2.1.2

Running setup.py install for markupsafe ... done

Successfully installed markupsafe-1.1.1




[100%] Built target ceph-dencoder





real    86m26.103s


user    3493m5.922s


sys     41m13.198s
