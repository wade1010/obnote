我们有时会把%CPU和us%搞晕，也就是下图所示在top的时候查看cpu的信息。

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Linux命令/images/WEBRESOURCE180b0a43f6582c4c1fff7ff8c0c6e0c4截图.png)

这时有人会问：这两个CPU到底哪个是对的。

其实都是对的，只是表达的意思不一样。

官方解释如下

Cpu(s)：34.0% us: 用户空间占用CPU百分比

%CPU：上次更新到现在的CPU时间占用百分比

读到这里我也不是十分理解他们俩的关系，我一直以为%CPU是每个进程占用的cpu百分比，按理来说所有进程的该值加在一起应该等于us.

但事实并非如此，此时我们可以在top界面按一下1

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Linux命令/images/WEBRESOURCEc396887d334a0704f6c54fef532b8ca9截图.png)

这时候我们可以清晰得看到每个cpu的运行状态。

通过上面的显示我们发现Cpu(s)表示的是 所有用户进程占用整个cpu的平均值，由于每个核心占用的百分比不同，所以按平均值来算比较有参考意义。而%CPU显示的是进程占用一个核的百分比，而不是整个cpu（12核）的百分比，有时候可能大于100，那是因为该进程启用了多线程占用了多个核心，所以有时候我们看该值得时候会超过100%，但不会超过总核数*100。