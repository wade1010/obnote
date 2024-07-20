多进程中，每个进程都是独立的，各自持有一份数据，无法共享。本篇文章介绍三种用于进程数据共享的方法

- queues

- Array

- Manager.dict

- pipe

Queue

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13 | from multiprocessing import queues<br>import multiprocessing<br>deffunc(i, q):<br> q.put(i)<br> print("---&gt;", i, q.qsize())<br>q = queues.Queue(9, ctx=multiprocessing)<br>for i in range(5):<br> p = multiprocessing.Process(target=func, args=(i, q,))<br> p.start()<br>p.join() |


Queue是多进程安全的队列，可以使用Queue实现多进程之间的数据传递。put方法用以插入数据到队列中，put方法还有两个可选参数：blocked和timeout。如果blocked为True（默认值），并且timeout为正值，该方法会阻塞timeout指定的时间，直到该队列有剩余的空间。如果超时，会抛出Queue.Full异常。如果blocked为False，但该Queue已满，会立即抛出Queue.Full异常

get方法可以从队列读取并且删除一个元素。同样，get方法有两个可选参数：blocked和timeout。如果blocked为True（默认值），并且timeout为正值，那么在等待时间内没有取到任何元素，会抛出Queue.Empty异常。如果blocked为False，有两种情况存在，如果Queue有一个值可用，则立即返回该值，否则，如果队列为空，则立即抛出Queue.Empty异常

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12 | import multiprocessing<br>deffunc(i, q):<br> q.put(i)<br> print("---&gt;", i, q.qsize())<br>q = multiprocessing.Queue()<br>for i in range(5):<br> p = multiprocessing.Process(target=func, args=(i, q,))<br> p.start()<br>p.join() |


Array

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14 | from multiprocessing import Process<br>from multiprocessing import Array<br>deffunc(i, ar):<br> ar[i] = i<br>for item in ar:<br> print(item)<br> print("------")<br>ar = Array('i', 5)<br>for i in range(5):<br> p = Process(target=func, args=(i, ar,))<br> p.start()<br>p.join() |


Array的局限性在于受制于数组的特性，即需要指定数据类型且长度固定

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7 | \# 数据类型对照表<br>'c': ctypes.c\_char, 'u': ctypes.c\_wchar,<br>'b': ctypes.c\_byte, 'B': ctypes.c\_ubyte,<br>'h': ctypes.c\_short, 'H': ctypes.c\_ushort,<br>'i': ctypes.c\_int, 'I': ctypes.c\_uint,<br>'l': ctypes.c\_long, 'L': ctypes.c\_ulong,<br>'f': ctypes.c\_float, 'd': ctypes.c\_double |


Manager.dict

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24 | from multiprocessing import Process, Manager<br>\# 每个子进程执行的函数<br>\# 参数中，传递了一个用于多进程之间数据共享的特殊字典<br>deffunc(i, d):<br> d[i] = i + 100<br> print(d.values())<br>\# 在主进程中创建特殊字典<br>m = Manager()<br>d = m.dict()<br>for i in range(5):<br>\# 让子进程去修改主进程的特殊字典<br> p = Process(target=func, args=(i, d))<br> p.start()<br>p.join()<br>------------<br>[100]<br>[100, 101]<br>[100, 101, 102, 103]<br>[100, 101, 102, 103]<br>[100, 101, 102, 103, 104] |


Manager.dict是多进程数据共享中比较常用的做法

pipe

Pipe方法返回(conn1, conn2)代表一个管道的两个端。Pipe方法有duplex参数，如果duplex参数为True(默认值)，那么这个管道是全双工模式，也就是说conn1和conn2均可收发。duplex为False，conn1只负责接受消息，conn2只负责发送消息

send和recv方法分别是发送和接受消息的方法。例如，在全双工模式下，可以调用conn1.send发送消息，conn1.recv接收消息。如果没有消息可接收，recv方法会一直阻塞。如果管道已经被关闭，那么recv方法会抛出EOFError

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19 | import multiprocessing<br>deffunc1(arg, pp):<br> pp.send(arg)<br>deffunc2(pp):<br> recv = pp.recv()<br> print(recv)<br>pp = multiprocessing.Pipe()<br>p1 = multiprocessing.Process(target=func1, args=("PolarSnow", pp[0],))<br>p2 = multiprocessing.Process(target=func2, args=(pp[1],))<br>p1.start()<br>p2.start()<br>p1.join()<br>p2.join()<br>------------<br>PolarSnow |
