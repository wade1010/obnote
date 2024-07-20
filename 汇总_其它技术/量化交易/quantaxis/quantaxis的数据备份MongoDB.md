备份future_day

mongodump -h 127.0.0.1:27017 -d quantaxis -c future_day --gzip  -o .

恢复future_day

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c future_day --gzip ./quantaxis/future_day.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c future_day --gzip ./quantaxis/future_day.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c future_list --gzip ./quantaxis/future_list.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c future_min --gzip ./quantaxis/future_min.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c index_list --gzip ./quantaxis/index_list.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_adj --gzip ./quantaxis/stock_adj.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_block --gzip ./quantaxis/stock_block.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_day --gzip ./quantaxis/stock_day.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_info --gzip ./quantaxis/stock_info.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_list --gzip ./quantaxis/stock_list.bson.gz

mongorestore -h 127.0.0.1:27017  --drop -d quantaxis -c stock_xdxr --gzip ./quantaxis/stock_xdxr.bson.gz

也可以恢复整个目录

mongorestore -h 127.0.0.1:27017 -d quantaxis --gzip --dir=./quantaxis

```
2022-08-20T20:32:32.909+0800	The --db and --collection flags are deprecated for this use-case; please use --nsInclude instead, i.e. with --nsInclude=${DATABASE}.${COLLECTION}
2022-08-20T20:32:32.910+0800	building a list of collections to restore from quantaxis dir
2022-08-20T20:32:32.910+0800	don't know what to do with file "quantaxis/.DS_Store", skipping...
2022-08-20T20:32:32.910+0800	don't know what to do with file "quantaxis/._.DS_Store", skipping...
2022-08-20T20:32:32.927+0800	reading metadata for quantaxis.index_list from quantaxis/index_list.metadata.json.gz
2022-08-20T20:32:32.951+0800	reading metadata for quantaxis.stock_adj from quantaxis/stock_adj.metadata.json.gz
2022-08-20T20:32:32.969+0800	reading metadata for quantaxis.stock_block from quantaxis/stock_block.metadata.json.gz
2022-08-20T20:32:33.029+0800	reading metadata for quantaxis.stock_list from quantaxis/stock_list.metadata.json.gz
2022-08-20T20:32:33.056+0800	reading metadata for quantaxis.future_day from quantaxis/future_day.metadata.json.gz
2022-08-20T20:32:33.079+0800	reading metadata for quantaxis.future_list from quantaxis/future_list.metadata.json.gz
2022-08-20T20:32:33.137+0800	reading metadata for quantaxis.stock_info from quantaxis/stock_info.metadata.json.gz
2022-08-20T20:32:33.163+0800	reading metadata for quantaxis.stock_xdxr from quantaxis/stock_xdxr.metadata.json.gz
2022-08-20T20:32:33.174+0800	reading metadata for quantaxis.future_min from quantaxis/future_min.metadata.json.gz
2022-08-20T20:32:33.190+0800	reading metadata for quantaxis.stock_day from quantaxis/stock_day.metadata.json.gz
2022-08-20T20:32:33.326+0800	restoring quantaxis.stock_day from quantaxis/stock_day.bson.gz
2022-08-20T20:32:33.343+0800	restoring quantaxis.future_min from quantaxis/future_min.bson.gz
2022-08-20T20:32:33.378+0800	restoring quantaxis.future_day from quantaxis/future_day.bson.gz
2022-08-20T20:32:33.389+0800	restoring quantaxis.stock_adj from quantaxis/stock_adj.bson.gz
2022-08-20T20:32:35.908+0800	[........................]   quantaxis.stock_day   1.35MB/359MB   (0.4%)
2022-08-20T20:32:35.908+0800	[........................]  quantaxis.future_min  1.10MB/71.2MB   (1.5%)
2022-08-20T20:32:35.908+0800	[####....................]  quantaxis.future_day  1.54MB/9.08MB  (17.0%)
。。。。。。。。。。
```