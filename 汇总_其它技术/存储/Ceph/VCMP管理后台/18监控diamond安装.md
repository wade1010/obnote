```
/opt/monitor-manager/diamond/run_env/bin/pip install /opt/monitor-manager/diamond/psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl
```

rm -rf /opt/monitor-manager/diamond/run_env/bin/python

ln -s /usr/bin/python2.7 /opt/monitor-manager/diamond/run_env/bin/python

安装pip2

cd /opt/monitor-manager/diamond/run_env/bin

curl [https://bootstrap.pypa.io/pip/2.7/get-pip.py](https://bootstrap.pypa.io/pip/2.7/get-pip.py) --output get-pip.py

./python get-pip.py

rm -rf pip

ln -s ./pip2 ./pip

修改下install脚本

就是注释掉

#systemctl daemon-reload

#systemctl enable diamond

#rm -rf /opt/monitor-manager/diamond/diamond-4.0.518

```
#! /bin/sh

PWD=$(cd `dirname $0`; pwd)
cd $PWD
version=`cat /etc/issue`

mkdir -p /opt/monitor-manager/diamond
mkdir -p /var/log/diamond/
mkdir -p /opt/monitor-manager/manager
mkdir -p /etc/diamond/
cp -rf diamond.conf /etc/diamond/diamond.conf

# 升级psutil包，因为涉及太多版本的脚本需要改动，所以这里将5.6.3的psutil包名替换为psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl
if [[ $version != *Kylin* ]] && [[ !($version =~ "Welcome to the vCluster Virtual Environment")]] && [[ $version != *AltArch* ]] && [[ $version != *Kylin-Server-10-SP1-Release* ]]; then
    cp psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl /opt/monitor-manager/diamond/
fi

mkdir -p /usr/share/diamond/

if [[ $version == *AltArch* || $version == *Kylin-Server-10-SP1-Release* ]]; then
    tar -zxf run_env-ap.tgz -C /opt/monitor-manager/diamond
elif [[ $version == *Kylin* ]]; then
    tar -zxf run_env-ft.tar.gz -C /opt/monitor-manager/diamond
elif [[ $version =~ "Welcome to the vCluster Virtual Environment" ]]; then
    tar -zxf run_env-db.tgz -C /opt/monitor-manager/diamond
else
    tar -zxf run_env.tar.gz -C /opt/monitor-manager/diamond
fi

cp -rf diamond-4.0.518.tar.gz /opt/monitor-manager/diamond/


cd /opt/monitor-manager/diamond/

tar -xzf diamond-4.0.518.tar.gz

cd diamond-4.0.518

# 安装diamond 模块
/opt/monitor-manager/diamond/run_env/bin/python setup.py install

cd $PWD
if [[ $version != *Kylin* ]] && [[ !($version =~ "Welcome to the vCluster Virtual Environment") ]] && [[ $version != *AltArch* ]] && [[ $version != *Kylin-Server-10-SP1-Release* ]]; then
    /opt/monitor-manager/diamond/run_env/bin/python -m pip install /opt/monitor-manager/diamond/psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl
fi

# 配置服务
cp -rf rpm/systemd/diamond.service /etc/systemd/system/
cp -rf src/collectors /usr/share/diamond/
cp -rf bin/diamond /usr/bin

systemctl set-property daemon-reload MemoryLimit=2G

#systemctl daemon-reload

#systemctl enable diamond

cd $PWD

#rm -rf /opt/monitor-manager/diamond/diamond-4.0.518

exit 0



```

发现systermctl start diamond启动失败

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354915.jpg)

直接命令行启动，看看报错内容

/opt/monitor-manager/diamond/run_env/bin/python /usr/bin/diamond

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354185.jpg)

发现缺包，所以从申威服务器上面把那个好的环境的包导出来,./pip freeze ->requirements.txt,删掉其中的psutil==4.1.0

cd /opt/monitor-manager/diamond/run_env/bin

vim requirements.txt 

```
backports-abc==0.5
certifi==2019.6.16
chardet==3.0.4
configobj==4.7.2
diamond==4.0.518
functools32==3.2.3.post2
futures==3.2.0
idna==2.8
influxdb==5.2.2
jsonschema==2.6.0
netifaces==0.10.9
python-dateutil==2.8.0
pytz==2019.1
requests==2.22.0
singledispatch==3.4.0.3
six==1.12.0
tornado==5.1.1
Tornado-JSON==1.3.4
urllib3==1.25.3

```

./pip install -r requirements.txt

然后 systemctl start diamond就可以了

后来发现还需要influxd

apt install influxdb  安装下就行，安装时默认就启动了，监听8086端口和8088端口

后来发现页面添加节点还是失败，得需要重启下diamond  

systemctl restart diamond

后来发现ifluxdb没有数据，好像是没有对应collector

rm -rf /opt/monitor-manager

编辑 install脚本

```
#! /bin/sh

PWD=$(cd `dirname $0`; pwd)
cd $PWD
version=`cat /etc/issue`

mkdir -p /opt/monitor-manager/diamond/run_env/bin
mkdir -p /var/log/diamond/
mkdir -p /opt/monitor-manager/manager
mkdir -p /etc/diamond/
cp -rf diamond.conf /etc/diamond/diamond.conf

# 升级psutil包，因为涉及太多版本的脚本需要改动，所以这里将5.6.3的psutil包名替换为psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl
if [[ $version != *Kylin* ]] && [[ !($version =~ "Welcome to the vCluster Virtual Environment")]] && [[ $version != *AltArch* ]] && [[ $version != *Kylin-Server-10-SP1-Release* ]]; then
    cp psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl /opt/monitor-manager/diamond/
fi

mkdir -p /usr/share/diamond/

ln -s /usr/bin/python2.7 /opt/monitor-manager/diamond/run_env/bin/python
ln -s /usr/local/bin/pip2 /opt/monitor-manager/diamond/run_env/bin/pip

cp -rf diamond-4.0.518.tar.gz /opt/monitor-manager/diamond/


cd /opt/monitor-manager/diamond/

tar -xzf diamond-4.0.518.tar.gz

cd diamond-4.0.518

#后面内容注释了
```

rm -rf /opt/monitor-manager/diamond/run_env/bin/python

ln -s /usr/bin/python2.7 /opt/monitor-manager/diamond/run_env/bin/python

cd /opt/monitor-manager/diamond/run_env/bin

curl [https://bootstrap.pypa.io/pip/2.7/get-pip.py](https://bootstrap.pypa.io/pip/2.7/get-pip.py) --output get-pip.py

./python get-pip.py

vim install

```
#! /bin/sh

PWD=$(cd `dirname $0`; pwd)

cd /opt/monitor-manager/diamond/

tar -xzf diamond-4.0.518.tar.gz

cd diamond-4.0.518

# 安装diamond 模块
rm -rf /usr/local/lib/python2.7/dist-packages/diamond
rm -rf /usr/local/lib/python2.7/dist-packages/diamondiamond-4.0.518.egg-infod

/opt/monitor-manager/diamond/run_env/bin/python setup.py install

cd $PWD
if [[ $version != *Kylin* ]] && [[ !($version =~ "Welcome to the vCluster Virtual Environment") ]] && [[ $version != *AltArch* ]] && [[ $version != *Kylin-Server-10-SP1-Release* ]]; then
    /opt/monitor-manager/diamond/run_env/bin/pip install /opt/monitor-manager/diamond/psutil-4.1.0-cp27-cp27mu-linux_x86_64.whl
fi

# 配置服务
cp -rf rpm/systemd/diamond.service /etc/systemd/system/
cp -rf src/collectors /usr/share/diamond/
cp -rf bin/diamond /usr/bin
systemctl enable diamond


    
```

sh install

后来发现还是找不到collector,

对比正常环境，发现psutil这个包版本不对，它的安装脚本里面都是5.几的版本

直接用pip2安装

sudo apt-get install python2-dev

pip2 install psutil==4.1.0

后来发现还需要安装下面

sudo apt-get install iftop ipmitool ifstat mdadm hddtemp

ipmitool 风扇

ifstat 网卡读写

mdadm hddtemp硬盘温度

添加的时候如果这个路径是之前存在的，添加会失败，我是手动到服务器上删除，删除再添加

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354482.jpg)