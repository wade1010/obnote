关闭后的通道有一下特点：

1. 对一个关闭的通道在发送值就会导致panic

1. 对一个关闭的通道进行接收会一直获取值知道通道为空

1. 对一个关闭的且没有值得通道执行接收操作会得到对应类型的零值

1. 关闭一个已经关闭的通道会导致panic







