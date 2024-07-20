HikyuuTDX.py的def on_start_import_pushButton_clicked(self):

self.hdf5_import_thread = UsePytdxImportToH5Thread(self, config)

self.hdf5_import_thread.start()

start之后会调用下面

class UsePytdxImportToH5Thread(QThread):

的run方法

```
def run(self):
    try:
        self.init_task()
        self._run()
    except Exception as e:
        self.logger.error(str(e))
        self.send_message(['THREAD', 'FAILURE', str(e)])
    else:
        self.logger.info('导入完毕')
        self.send_message(['THREAD', 'FINISHED'])
```

init_task中

创建ImportWeightToSqliteTask、ImportHistoryFinanceTask任务，并将其添加到任务列表中

根据配置文件中的ktype选项，判断各个类型的K线数据是否启用。根据每个类型是否启用，增加相应的任务计数。

搜索最佳的通达信服务器，并将搜索结果保存在hosts变量中。self.hosts = search_best_tdx()

这个搜索最佳，会在GUI工具的执行日志打印类似如下内容

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332643.jpg)

按数据量从大到小依次使用速度从高到低的TDX服务器

MySQL不支持分笔数据、分时数据

根据设置是否开启配置，ImportPytdxTransToH5 、ImportPytdxToH5、ImportPytdxTimeToH5

在_run()中

初始化进度条

根据设置，初始化hdf5或者MySQL，假如数据库不存在则创建数据库，

通过pytdx_api = TdxHq_API()初始化tdx调用对象，并检查通达信连接是否成功。

通过import_new_holidays(connect)导入交易所休假日历

通过import_index_name(connect)导入指数数量

通过import_stock_name(connect, pytdx_api, market, self.quotations)导入新增

```
self.process_list.clear()
for task in self.tasks:
    p = Process(target=task)
    self.process_list.append(p)
    p.start()
```

上面代码是开始启动init_task里面添加的任务

然后通过一个while循环检查其他线程任务完成情况

```
while finished_count > 0:
    try:
        message = self.queue.get(timeout=10)
        taskname, market, ktype, progress, total = message
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332865.jpg)

这个while里面还会更新进度条

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332160.jpg)