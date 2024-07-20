brew services start mongodb/brew/mongodb-community

brew services start rabbitmq 

pip install celery==4.4.0

rabbitmq-plugins enable rabbitmq_management 

rabbitmqctl add_user admin admin 

rabbitmqctl set_user_tags admin administrator 

rabbitmqctl  set_permissions -p "/" admin '.**' '.**' '.*'

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347702.jpg)

quantaxis_webserver

qifi_manager

qavifiserver

celery -A quantaxis_run worker --loglevel=info

cd qamazing_community && python -m http.server 81

pip install tornado==5.1.0

pip install git+[https://github.com/yutiansut/tornado_http2](https://github.com/yutiansut/tornado_http2)

```
/usr/local/anaconda3/envs/quantaxisc/bin/python /Users/bob/workspace/pythonworkspace/quantaxisc/quantaxis/demo/demo_qaarp.py
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT ==> receive deal  Time 2018-01-02 00:00:00/ Code:000001/ Price:12.79/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-01-04 00:00:00/ Code:600000/ Price:10.39/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-01-12 00:00:00/ Code:000001/ Price:12.65/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-01-24 00:00:00/ Code:000004/ Price:22.08/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-02-05 00:00:00/ Code:600000/ Price:11.07/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-02-13 00:00:00/ Code:000004/ Price:20.1/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-03-08 00:00:00/ Code:000001/ Price:11.3/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-03-08 00:00:00/ Code:000002/ Price:28.39/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-03-29 00:00:00/ Code:000002/ Price:28.83/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-04-10 00:00:00/ Code:000001/ Price:10.66/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-04-11 00:00:00/ Code:600000/ Price:9.78/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
TIME -- 0:00:02.029283
[['2018-01-02 00:00:00', '000001', 12.79, 1000.0, 987205.0, 'Order_tHIasfUN', 'Order_tHIasfUN', 'Trade_oYTxh2em', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-04 00:00:00', '600000', 10.39, 1000.0, 976810.0, 'Order_yTfMlzSZ', 'Order_yTfMlzSZ', 'Trade_0bZw2NrV', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-12 00:00:00', '000001', 12.65, 1000.0, 964155.0, 'Order_cUqHKDoX', 'Order_cUqHKDoX', 'Trade_VF6RmQjz', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-24 00:00:00', '000004', 22.08, 1000.0, 942069.48, 'Order_1elNr3og', 'Order_1elNr3og', 'Trade_Hp6cdzmM', 'macd_stock', 5.5200000000000005, 0, None, 0, 1, 0], ['2018-02-05 00:00:00', '600000', 11.07, 1000.0, 930994.48, 'Order_xMAr3YRQ', 'Order_xMAr3YRQ', 'Trade_ldH5tzAx', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-02-13 00:00:00', '000004', 20.1, 1000.0, 910889.455, 'Order_NEb9LVsB', 'Order_NEb9LVsB', 'Trade_2QwrNkyA', 'macd_stock', 5.025, 0, None, 0, 1, 0], ['2018-03-08 00:00:00', '000001', 11.3, 1000.0, 899584.455, 'Order_rl5bLGYs', 'Order_rl5bLGYs', 'Trade_xjbpeMEr', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-03-08 00:00:00', '000002', 28.39, 1000.0, 871187.3574999999, 'Order_wnxp5UKI', 'Order_wnxp5UKI', 'Trade_zcVGPvtT', 'macd_stock', 7.0975, 0, None, 0, 1, 0], ['2018-03-29 00:00:00', '000002', 28.83, 1000.0, 842350.1499999999, 'Order_XucOjH1G', 'Order_XucOjH1G', 'Trade_H0xEQGMP', 'macd_stock', 7.2075000000000005, 0, None, 0, 1, 0], ['2018-04-10 00:00:00', '000001', 10.66, 1000.0, 831685.1499999999, 'Order_gezCVY0G', 'Order_gezCVY0G', 'Trade_figqYEJ3', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-04-11 00:00:00', '600000', 9.78, 1000.0, 821900.1499999999, 'Order_dn3kQNwR', 'Order_dn3kQNwR', 'Trade_Q7eKb5st', 'macd_stock', 5, 0, None, 0, 1, 0]]
               datetime    code  price  ...  frozen  direction total_frozen
0   2018-01-02 00:00:00  000001  12.79  ...       0          1            0
1   2018-01-04 00:00:00  600000  10.39  ...       0          1            0
2   2018-01-12 00:00:00  000001  12.65  ...       0          1            0
3   2018-01-24 00:00:00  000004  22.08  ...       0          1            0
4   2018-02-05 00:00:00  600000  11.07  ...       0          1            0
5   2018-02-13 00:00:00  000004  20.10  ...       0          1            0
6   2018-03-08 00:00:00  000001  11.30  ...       0          1            0
7   2018-03-08 00:00:00  000002  28.39  ...       0          1            0
8   2018-03-29 00:00:00  000002  28.83  ...       0          1            0
9   2018-04-10 00:00:00  000001  10.66  ...       0          1            0
10  2018-04-11 00:00:00  600000   9.78  ...       0          1            0

[11 rows x 15 columns]
            000001  000002  000004  600000
date                                      
2018-01-02  1000.0     0.0     0.0     0.0
2018-01-03  1000.0     0.0     0.0     0.0
2018-01-04  1000.0     0.0     0.0  1000.0
2018-01-05  1000.0     0.0     0.0  1000.0
2018-01-08  1000.0     0.0     0.0  1000.0
...            ...     ...     ...     ...
2018-04-03  3000.0  2000.0  2000.0  2000.0
2018-04-04  3000.0  2000.0  2000.0  2000.0
2018-04-09  3000.0  2000.0  2000.0  2000.0
2018-04-10  4000.0  2000.0  2000.0  2000.0
2018-04-11  4000.0  2000.0  2000.0  3000.0

[65 rows x 4 columns]

Process finished with exit code 0

```

```
/usr/local/anaconda3/envs/quantaxisc/bin/python /Users/bob/workspace/pythonworkspace/quantaxisc/quantaxis/demo/demo_qaarp.py
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT: THIS ACCOUNT DOESNOT HAVE ANY TRADE
QAACCOUNT ==> receive deal  Time 2018-01-02 00:00:00/ Code:000001/ Price:12.79/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-01-04 00:00:00/ Code:600000/ Price:10.39/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-01-12 00:00:00/ Code:000001/ Price:12.65/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-01-24 00:00:00/ Code:000004/ Price:22.08/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-02-05 00:00:00/ Code:600000/ Price:11.07/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-02-13 00:00:00/ Code:000004/ Price:20.1/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-03-08 00:00:00/ Code:000001/ Price:11.3/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-03-08 00:00:00/ Code:000002/ Price:28.39/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-03-29 00:00:00/ Code:000002/ Price:28.83/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
QAACCOUNT ==> receive deal  Time 2018-04-10 00:00:00/ Code:000001/ Price:10.66/ TOWARDS:1/ Amounts:1000
QAACCOUNT ==> receive deal  Time 2018-04-11 00:00:00/ Code:600000/ Price:9.78/ TOWARDS:1/ Amounts:1000
ORDER STATUS success_all CANNNOT TRADE
TIME -- 0:00:01.901580
[['2018-01-02 00:00:00', '000001', 12.79, 1000.0, 987205.0, 'Order_3p8YNraP', 'Order_3p8YNraP', 'Trade_5aWSMA1v', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-04 00:00:00', '600000', 10.39, 1000.0, 976810.0, 'Order_cYdEp28L', 'Order_cYdEp28L', 'Trade_z6QiNLeC', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-12 00:00:00', '000001', 12.65, 1000.0, 964155.0, 'Order_u8HfI4zb', 'Order_u8HfI4zb', 'Trade_qLolVIUv', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-01-24 00:00:00', '000004', 22.08, 1000.0, 942069.48, 'Order_9BlF8NvZ', 'Order_9BlF8NvZ', 'Trade_b69OmsRX', 'macd_stock', 5.5200000000000005, 0, None, 0, 1, 0], ['2018-02-05 00:00:00', '600000', 11.07, 1000.0, 930994.48, 'Order_j86dyROG', 'Order_j86dyROG', 'Trade_RB9AkEa8', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-02-13 00:00:00', '000004', 20.1, 1000.0, 910889.455, 'Order_paKdUIRJ', 'Order_paKdUIRJ', 'Trade_amcfhPVw', 'macd_stock', 5.025, 0, None, 0, 1, 0], ['2018-03-08 00:00:00', '000001', 11.3, 1000.0, 899584.455, 'Order_FVRnWI93', 'Order_FVRnWI93', 'Trade_VDR9zxWC', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-03-08 00:00:00', '000002', 28.39, 1000.0, 871187.3574999999, 'Order_Hh0OKvC9', 'Order_Hh0OKvC9', 'Trade_ebrLXljp', 'macd_stock', 7.0975, 0, None, 0, 1, 0], ['2018-03-29 00:00:00', '000002', 28.83, 1000.0, 842350.1499999999, 'Order_IvjaehEN', 'Order_IvjaehEN', 'Trade_IdpgfWyG', 'macd_stock', 7.2075000000000005, 0, None, 0, 1, 0], ['2018-04-10 00:00:00', '000001', 10.66, 1000.0, 831685.1499999999, 'Order_n8ZrFiD9', 'Order_n8ZrFiD9', 'Trade_2Gu1w0n9', 'macd_stock', 5, 0, None, 0, 1, 0], ['2018-04-11 00:00:00', '600000', 9.78, 1000.0, 821900.1499999999, 'Order_bgZpR0PF', 'Order_bgZpR0PF', 'Trade_uFmqeZCM', 'macd_stock', 5, 0, None, 0, 1, 0]]
               datetime    code  price  ...  frozen  direction total_frozen
0   2018-01-02 00:00:00  000001  12.79  ...       0          1            0
1   2018-01-04 00:00:00  600000  10.39  ...       0          1            0
2   2018-01-12 00:00:00  000001  12.65  ...       0          1            0
3   2018-01-24 00:00:00  000004  22.08  ...       0          1            0
4   2018-02-05 00:00:00  600000  11.07  ...       0          1            0
5   2018-02-13 00:00:00  000004  20.10  ...       0          1            0
6   2018-03-08 00:00:00  000001  11.30  ...       0          1            0
7   2018-03-08 00:00:00  000002  28.39  ...       0          1            0
8   2018-03-29 00:00:00  000002  28.83  ...       0          1            0
9   2018-04-10 00:00:00  000001  10.66  ...       0          1            0
10  2018-04-11 00:00:00  600000   9.78  ...       0          1            0

[11 rows x 15 columns]
            000001  000002  000004  600000
date                                      
2018-01-02  1000.0     0.0     0.0     0.0
2018-01-03  1000.0     0.0     0.0     0.0
2018-01-04  1000.0     0.0     0.0  1000.0
2018-01-05  1000.0     0.0     0.0  1000.0
2018-01-08  1000.0     0.0     0.0  1000.0
...            ...     ...     ...     ...
2018-04-03  3000.0  2000.0  2000.0  2000.0
2018-04-04  3000.0  2000.0  2000.0  2000.0
2018-04-09  3000.0  2000.0  2000.0  2000.0
2018-04-10  4000.0  2000.0  2000.0  2000.0
2018-04-11  4000.0  2000.0  2000.0  3000.0

[65 rows x 4 columns]

Process finished with exit code 0

```

pip install webcolors==1.12