ARGS="-DCMAKE_BUILD_TYPE=RelWithDebInfo -DWITH_TESTS=OFF  -DWITH_SYSTEM_BOOST=ON -DWITH_SYSTEM_NPM=ON" ./do_cmake.sh

vim install.sh

```
wget https://nodejs.org/dist/v14.20.0/node-v14.20.0-linux-x64.tar.gz
tar -xf node-v14.20.0-linux-x64.tar.gz
sudo mv -f node-v14.20.0-linux-x64 /usr/local/
sudo ln -s /usr/local/node-v14.20.0-linux-x64/bin/node /usr/local/bin/node
sudo ln -s /usr/local/node-v14.20.0-linux-x64/bin/npm /usr/local/bin/npm

wget https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2
tar xf boost_1_73_0.tar.bz2
cd boost_1_73_0
./bootstrap.sh
./b2
sudo ./b2 install
```

[https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2](https://boostorg.jfrog.io/artifactory/main/release/1.73.0/source/boost_1_73_0.tar.bz2)

tar xf boost_1_73_0.tar.bz2

cd boost_1_73_0

./bootstrap.sh

./b2

sudo ./b2 install

sudo mv /usr/bin/node /usr/bin/node-original

wget [https://nodejs.org/dist/v14.20.0/node-v14.20.0-linux-x64.tar.gz](https://nodejs.org/dist/v14.20.0/node-v14.20.0-linux-x64.tar.gz)

tar -xvf node-v14.20.0-linux-x64.tar.gz

sudo mv -f node-v14.20.0-linux-x64 /usr/local/

sudo ln -s /usr/local/node-v14.20.0-linux-x64/bin/node /usr/local/bin/node

sudo ln -s /usr/local/node-v14.20.0-linux-x64/bin/npm /usr/local/bin/npm

每次修改clion的cmake option，会自动执行

/home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake -DCMAKE_BUILD_TYPE=Debug -DCMAKE_MAKE_PROGRAM=make -DWITH_SYSTEM_BOOST=ON -DWITH_SYSTEM_NPM=ON -G "CodeBlocks - Unix Makefiles" -S /home/runner/workspace/ceph-16.2.12 -B /home/runner/workspace/ceph-16.2.12/build