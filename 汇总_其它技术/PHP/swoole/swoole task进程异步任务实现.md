1、worker进程到中，我们调用对应的task()方法发送数据通知到task worker进程

2、task worker进程会在onTask()回调中接收到这些数据，并进行处理。

3、处理完成之后通过调用finsh()函数或者直接return返回消息给worker进程

4、worker进程在onFinsh()进程收到这些消息并进行处理

 

计算方法

单个task的处理耗时，如100ms，那一个进程1秒就可以处理1/0.1=10个task

task投递的速度，如每秒产生2000个task

2000/10=200，需要设置task_worker_num => 200，启用200个task进程

对于单个服务器的承受数量我们要提前做预知，处理能力必须是大于投放能力的





2、task怎么使用？

Task进程其实是要在worker进程内发起的，即我们把需要投递的任务，通过worker进程投递到task进程中去处理。

怎么操作呢？我们可以利用swoole_server->task函数把任务数据投递到task进程池中。

swoole_server->task函数是非阻塞函数，任务投递到task进程中后会立即返回，即不管任务需要在task进程内处理多久，worker进程也不需要任何的等待，不会影响到worker进程的其他操作。但是task进程却是阻塞的，如果当前task进程都处于繁忙状态即都在处理任务，你又投递过来更多任务，这个时候新投递的任务就只能乖乖的排队等task进程空闲才能继续处理。如果投递的任务量总是大于task进程的处理能力，建议适当的调大task_worker_num的数量，增加task进程数，不然一旦task塞满缓冲区，就会导致worker进程阻塞，所以需要使用好task前期必须有所规划





3、Task常见问题：

Task传递数据的大小

数据小于8k直接通过管道传递,数据大于8k写入临时文件传递

onTask会读取这个文件,把他读出来

运行Task,必须要在swoole服务中配置参数task_worker_num,此外，必须给swoole_server绑定两个回调函数：onTask和onFinish。 

onTask要return 数据 



2、Task传递对象

默认task只能传递数据，可以通过序列化传递一个对象的拷贝,Task中对象的改变并不会反映到worker进程中数据库连接,网络连接对象不可传递,会引起php报错



