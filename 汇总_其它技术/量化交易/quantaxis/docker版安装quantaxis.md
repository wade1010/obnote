[在装完docker以后 quantaxis的推荐路径 - QUANTAXIS.pdf](attachments/WEBRESOURCE886cde3e2b5bfb77e962f69423568f6b在装完docker以后 quantaxis的推荐路径 - QUANTAXIS.pdf)

** 首先确认你的docker-compose.yaml**

如果你是股票方向的 ==> 选择 qa-service 下的docker-compose.yaml

如果你是期货方向的 ==> 选择 qa-service-future 下的docker-compose.yaml

你可以理解 docker的构成类似搭积木的模式, 你需要这个功能的积木, 就选择他放在你的docker

compose.yaml里面

期货方向的yaml 比股票多一个 QACTPBEE的docker-container [这是用于分发期货的tick行情所需的 股票则无需此积木]

docker-compose.yaml

```
version: '2'
services:
    qa:
        image: daocloud.io/quantaxis/qacommunity-rust-go:allin-20210218
        container_name: qacommunity-rust
        depends_on:
            - mgdb
            - qaeventmq
        networks:
            qanetwork:
                ipv4_address: 172.19.0.3
        ports:
            - "8888:8888"
            - "81:80"
            - "8787:8787"
        environment:
          - TZ=Asia/Shanghai
          - LANG=C.UTF-8
          - MONGODB=mgdb
          - QARUN=qaweb
          - QAPUBSUB_IP=qaeventmq
          - QAPUBSUB_PORT=5672
          - QAPUBSUB_USER=admin
          - QAPUBSUB_PWD=admin
        volumes:
            - qacode:/root/code
        restart: always

    qaweb:
        image: daocloud.io/quantaxis/qacommunity-rust-go:allin-20210218
        container_name: qaweb
        depends_on:
            - mgdb
            - qaeventmq
        networks:
            qanetwork:
                ipv4_address: 172.19.0.4
        ports:
            - "8010:8010"
            - "8018:8018"
            - "8019:8019"
            - "8028:8028"
            - "8029:8029"
        environment:
          - MONGODB=mgdb
          - QAPUBSUB_IP=qaeventmq
          - QAPUBSUB_PORT=5672
          - QAPUBSUB_USER=admin
          - QAPUBSUB_PWD=admin
          - QARUN_AMQP=pyamqp://admin:admin@qaeventmq:5672//
          - TZ=Asia/Shanghai
        restart: always
        depends_on:
          - qaeventmq
          - mgdb
        command: ['/root/wait_for_it.sh', 'qaeventmq:5672', '--' , "/root/runcelery.sh"]


    mgdb:
        image: daocloud.io/quantaxis/qamongo_single:latest
        ports:
            - "27017:27017"
        environment:
            - TZ=Asia/Shanghai
            - MONGO_INITDB_DATABASE=quantaxis
        volumes:
            - qamg:/data/db
        networks:
            qanetwork:
                ipv4_address: 172.19.0.2
        restart: always


    qaeventmq:
        image: daocloud.io/quantaxis/qaeventmq:latest
        ports:
            - "15672:15672"
            - "5672:5672"
            - "4369:4369"
        environment:
            - TZ=Asia/Shanghai
        networks:
            qanetwork:
                ipv4_address: 172.19.0.5
        restart: always


    qatrader:
        image: daocloud.io/quantaxis/qatrader:latest
        ports:
            - "8020:8020"
        depends_on:
            - mgdb
            - qaeventmq
        environment:
            - MONGODB=mgdb
            - QAPUBSUB_IP=qaeventmq
            - QAPUBSUB_PORT=5672
            - QAPUBSUB_USER=admin
            - QAPUBSUB_PWD=admin
            - QARUN_AMQP=pyamqp://admin:admin@qaeventmq:5672//
            - TZ=Asia/Shanghai
        command:
            ['/root/QATrader/docker/wait_for_it.sh', 'qaeventmq:5672', '--' ,'qatraderserver']

        networks:
            qanetwork:
                ipv4_address: 172.19.0.9



volumes:
    qamg:
        external:
            name: qamg
    qacode:
        external:
            name: qacode
networks:
    qanetwork:
        ipam:
            config:
            - subnet: 172.19.0.0/24
              gateway: 172.19.0.1

```

```
docker volume create --name=qamg
docker volume create --name=qacode
```

```
docker-compose up 
```

初始化数据

- 在浏览器输入docker宿主机"ip地址:8888", 如 

- b. 进入jupyterlab登录界面后输入口令"quantaxis";

- c. 进入jupyterlab启动页，点击Terminal进入终端；

- d. 在终端界面输入"/bin/bash",进入bash状态；

- e. bash状态输入"quantaxis",进入数据库操作状态；

- f. 依次输入完成数据库初始化"save stock_list"，“save stock_block” “save stock_info”; 如果不知道命令，则输入"save", 可查看命令列表；

- g. 输入exit退出终端

3.在docker管理界面重启qaweb容器，最后浏览器输入docker宿主机"ip地址:81"，弹出的登录界面把登录地址端口改为docker宿主机"ip地址:8010",就可以进入QA前端界面

PS:上面f步骤 'save stock_info'时间较长，运行后就可以重启qaweb了。另外我们并没有保存所有数据，可能有些接口报错。如果有空最好还是执行save all 保存所有数据，save all需要执行下面替换工作

最好输入 save查看可以save哪些

```
QUANTAXIS> save
Usage: 
            命令格式：save all  : save stock_day/xdxr/ index_day/ stock_list/index_list 
            命令格式：save X|x  : save stock_day/xdxr/min index_day/min etf_day/min stock_list/index_list/block 
            命令格式：save day  : save stock_day/xdxr index_day etf_day stock_list/index_list 
            命令格式：save min  : save stock_min/xdxr index_min etf_min stock_list/index_list 
            命令格式: save future: save future_day/min/list 
            命令格式: save option: save option_contract_list/option_day_all/option_min_all 
            命令格式: save transaction: save stock_transaction and index_transaction (Warning: Large Disk Space Required) 
            命令格式: save ts_all: save ts_industry and ts_namechange and ts_stock_basic ts_daily_basic and ts_financial_reports 
            命令格式: save ts_financial: save ts_financial_reports 
            命令格式: save ts_daily: save ts_daily 
            ------------------------------------------------------------ 
            命令格式：save stock_xdxr : 保存日除权除息数据 
            命令格式：save stock_day  : 保存日线数据 
            命令格式：save single_stock_day  : 保存单个股票日线数据 
            命令格式：save stock_min  : 保存分钟线数据 
            命令格式：save single_stock_min  : 保存单个股票分钟线数据 
            命令格式：save index_day  : 保存指数日线数据 
            命令格式：save single_index_day  : 保存单个指数日线数据 
            命令格式：save index_min  : 保存指数分钟线数据 
            命令格式：save single_index_min  : 保存单个指数分钟线数据 
            命令格式：save future_day  : 保存期货日线数据 
            命令格式：save future_day_all  : 保存期货日线数据(含合约信息,不包括已经过期摘牌的合约数据) 
            命令格式：save single_future_day  : 保存单个期货日线数据 
            命令格式：save future_min  : 保存期货分钟线数据 
            命令格式：save future_min_all  : 保存期货分钟线数据(含合约信息,不包括已经过期摘牌的合约数据) 
            命令格式：save single_future_min  : 保存单个期货分钟线数据 
            命令格式：save etf_day    : 保存ETF日线数据 
            命令格式：save single_etf_day    : 保存单个ETF日线数据 
            命令格式：save etf_min    : 保存ET分钟数据 
            命令格式：save stock_list : 保存股票列表 
            命令格式：save stock_block: 保存板块 
            命令格式：save stock_info : 保存tushare数据接口获取的股票列表 
            命令格式：save financialfiles : 保存高级财务数据(自1996年开始) 
            命令格式：save option_contract_list 保存上市的期权合约信息（不包括已经过期摘牌的合约数据）
            # 命令格式：save 50etf_option_day : 保存上海证券交易所50ETF期权日线数据（不包括已经过期摘牌的数据） 
            # 命令格式：save 50etf_option_min : 保存上海证券交易所50ETF期权分钟线数据（不包括已经过期摘牌的数据） 
            # 命令格式：save 300etf_option_day : 保存上海证券交易所300ETF期权日线数据（不包括已经过期摘牌的数据） 
            # 命令格式：save 300etf_option_min : 保存上海证券交易所300ETF期权分钟线数据（不包括已经过期摘牌的数据） 
            # 命令格式：save option_commodity_day : 保存商品期权日线数据（不包括已经过期摘牌的数据） 
            # 命令格式：save option_commodity_min : 保存商品期权分钟线数据（不包括已经过期摘牌的数据） 
            命令格式：save option_day_all : 保存上海证券交易所所有期权日线数据（不包括已经过期摘牌的数据） 
            命令格式：save option_min_all : 保存上海证券交易所所有期权分钟数据（不包括已经过期摘牌的数据） 
            命令格式：save index_list : 保存指数列表 
            命令格式：save etf_list : 保存etf列表 
            命令格式：save future_list : 保存期货列表 
            命令格式：save bond_day  : 保存债券日线数据 
            命令格式：save single_bond_day  : 保存单个债券日线数据 
            命令格式：save bond_min  : 保存债券分钟线数据 
            命令格式：save single_bond_min  : 保存单个债券分钟线数据 
            命令格式：save bond_list : 保存债券列表 
            命令格式：save bitmex : 保存bitmex交易所日线\现货交易对小时线数据 
            命令格式：save binance : 保存币安交易所数据 
            命令格式：save binance all : 一次性保存币安交易所日/小时/30/15/5/1分钟线数据（耗时很长） 
            命令格式：save binance 1day/1hour/1min : 单独保存币安交易所日/小时/分钟数据 
            命令格式：save bitfinex : 保存bitfinex交易所数据 
            命令格式：save bitfinex all : 一次性保存bitfinex交易所日/小时/30/15/5/1分钟线数据（耗时很长） 
            命令格式：save bitfinex 1day/1hour/1min : 单独保存bitfinex交易所日/小时/分钟数据 
            命令格式：save huobi : 保存火币Pro交易所日/小时/分钟现货交易对数据 
            命令格式：save huobi all : 一次性保存火币Pro交易所日/小时/30/15/5/1分钟线数据（耗时很长） 
            命令格式：save huobi 1day/1hour/1min/5min/15min/30min : 单独保存火币Pro交易所日/小时/分钟线数据 
            命令格式：save huobi realtime : 接收火币Pro交易所实时行情（仅排名前30的主要币种）
            命令格式：save okex : 保存OKEx交易所数据 
            命令格式：save okex all : 一次性保存OKEx交易所日/小时/30/15/5/1分钟线数据（耗时很长） 
            命令格式：save okex 86400/3600/1800/900/300/60 : 单独保存OKEx交易所日/小时/30/15/5/1分钟数据 
            ----------------------------------------------------------
            if you just want to save daily data just
                save all+ save stock_block+save stock_info, it about 1G data 
            if you want to save save the fully data including min level 
                save x + save stock_info 
 
            @yutiansut
            @QUANTAXIS
            请访问 https://book.yutiansut.com/
```

[pytdx-1.72r2-py3-none-any.whl](attachments/WEBRESOURCE19594b38dafd4389a51f7a1f585a7120pytdx-1.72r2-py3-none-any.whl)

[quantaxis-1.10.19r4-py3-none-any.whl](attachments/WEBRESOURCE61a43864aa8a85434f73e438a75b1a6aquantaxis-1.10.19r4-py3-none-any.whl)

1、首先去群文件下两个文件pytdx...  quantaxis...

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347302.jpg)

2、打开localhost:8888

把刚下的两个文件拖进去：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348190.jpg)

3、打开这个网页右面的terminal：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348422.jpg)

4、输入/bin/bash ,cd /root，然后Pip install 刚才两个文件

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348994.jpg)

pip uninstall pytdx -y

pip install pytdx.....

pip install quantaxis.....

5、输入quantaxis,然后save all

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348183.jpg)

后续使用

```

docker-compose pull (这里的意思是更新docker文件)

docker-compose up
```

端口:

- 27017 mongodb

- 8888 jupyter

- 8010 quantaxis_webserver

- 81 quantaxis_community 社区版界面

- 61208 系统监控

- 15672 qa-eventmq

### 查看每天数据更新日志：

docker logs cron容器名

日志只输出到容器前台，如果日志对你很重要，建议用专业的日志收集工具，从cron容器收集日志

```
docker ps

docker stats

docker-compose top

docker-compose ps
```

### 数据库备份(备份到宿主机当前目录，文件名：dbbackup.tar)：

1. 停止服务

```
docker-compose stop

```

1. 备份到当前目录

```
docker run  --rm -v qamg:/data/db \
-v $(pwd):/backup alpine \
tar zcvf /backup/dbbackup.tar /data/db

```

1. 停止服务

```
docker-compose stop

```

1. 还原当前目录下的dbbackup.tar到mongod数据库

```
docker run  --rm -v qamg:/data/db \
-v $(pwd):/backup alpine \
sh -c "cd /data/db \
&& rm -rf diagnostic.data \
&& rm -rf journal \
&& rm -rf configdb \
&& cd / \
&& tar xvf /backup/dbbackup.tar"

```

1. 重新启动服务

```
docker-compose up -d
```

开始没有初始化数据，所以web页面登录不上。

然后QQ群咨询，查询解答如下：

1.请确认各个docker的容器是否运行成功。

2.初始配置，[参考](https://www.cnblogs.com/sunshe35/p/14694275.html)：

- a. 在浏览器输入docker宿主机"ip地址:8888", 如：

- b. 进入jupyterlab登录界面后输入口令"quantaxis";

- c. 进入jupyterlab启动页，点击Terminal进入终端；

- d. 在终端界面输入"/bin/bash",进入bash状态；

- e. bash状态输入"quantaxis",进入数据库操作状态；

- f. 依次输入完成数据库初始化"save stock_list"，“save stock_block” “save stock_info”; 如果不知道命令，则输入"save", 可查看命令列表；

- g. 输入exit退出终端

3.在docker管理界面重启qaweb容器，最后浏览器输入docker宿主机"ip地址:81"，弹出的登录界面把登录地址端口改为docker宿主机"ip地址:8010",就可以进入QA前端界面

注意：在第2步和第3步的所说的ip，是主机的**外网ip外网ip外网ip**, 重要的事情说三遍。可以使用docker管理工具或者docker命令查看对应的外网地址。如果是本机电脑，则可以是localhost或者127.0.0.1, 如果是云主机，则应该是对应的公网ip。