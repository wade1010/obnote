```
# 如果服务器上已经安装了GPU和CUDA
if torch.cuda.is_available():
 # 定义⼀个设备对象, 这⾥指定成CUDA, 即使⽤GPU
 device = torch.device("cuda")
 # 直接在GPU上创建⼀个Tensor
 y = torch.ones_like(x, device=device)
 # 将在CPU上⾯的x张量移动到GPU上⾯
 x = x.to(device)
 # x和y都在GPU上⾯, 才能⽀持加法运算
 z = x + y
 # 此处的张量z在GPU上⾯
 print(z)
 # 也可以将z转移到CPU上⾯, 并同时指定张量元素的数据类型
 print(z.to("cpu", torch.double))
```

### 小节总结

- 学习了什么是Pytorch.

	- Pytorch是一个基于Numpy的科学计算包, 作为Numpy的替代者, 向用户提供使用GPU强大功能的能力.

	- 做为一款深度学习的平台, 向用户提供最大的灵活性和速度.

- 学习了Pytorch的基本元素操作.

	- 矩阵的初始化:

		- torch.empty()

		- torch.rand(n, m)

		- torch.zeros(n, m, dtype=torch.long)

	- 其他若干操作:

		- x.new_ones(n, m, dtype=torch.double)

		- torch.randn_like(x, dtype=torch.float)

		- x.size()

- 学习了Pytorch的基本运算操作.

	- 加法操作:

		- x + y

		- torch.add(x, y)

		- torch.add(x, y, out=result)

		- y.add_(x)

	- 其他若干操作:

		- x.view()

		- x.item()

- 学习了Torch Tensor和Numpy Array之间的相互转换.

	- 将Torch Tensor转换为Numpy Array:

		- b = a.numpy()

	- 将Numpy Array转换为Torch Tensor:

		- b = torch.from_numpy(a)

	- 注意: 所有才CPU上的Tensor, 除了CharTensor, 都可以转换为Numpy Array并可以反向转换.

- 学习了任意的Tensors可以用.to()方法来将其移动到任意设备上.

	- x = x.to(device)

## 1.2 Pytorch中的autograd

### 关于torch.Tensor

- torch.Tensor是整个package中的核心类, 如果将属性.requires_grad设置为True, 它将追踪在这个类上定义的所有操作. 当代码要进行反向传播的时候, 直接调用.backward()就可以自动计算所有的梯度. 在这个Tensor上的所有梯度将被累加进属性.grad中.

- 如果想终止一个Tensor在计算图中的追踪回溯, 只需要执行.detach()就可以将该Tensor从计算图中撤下, 在未来的回溯计算中也不会再计算该Tensor.

- 除了.detach(), 如果想终止对计算图的回溯, 也就是不再进行方向传播求导数的过程, 也可以采用代码块的方式with torch.no_grad():, 这种方式非常适用于对模型进行预测的时候, 因为预测阶段不再需要对梯度进行计算.

- 关于torch.Function:

	- Function类是和Tensor类同等重要的一个核心类, 它和Tensor共同构建了一个完整的类, 每一个Tensor拥有一个.grad_fn属性, 代表引用了哪个具体的Function创建了该Tensor.

	- 如果某个张量Tensor是用户自定义的, 则其对应的grad_fn is None.

### 小节总结

- 学习了torch.Tensor类的相关概念.

	- torch.Tensor是整个package中的核心类, 如果将属性.requires_grad设置为True, 它将追踪在这个类上定义的所有操作. 当代码要进行反向传播的时候, 直接调用.backward()就可以自动计算所有的梯度. 在这个Tensor上的所有梯度将被累加进属性.grad中.

	- 执行.detach()命令, 可以将该Tensor从计算图中撤下, 在未来的回溯计算中不会再计算该Tensor.

	- 采用代码块的方式也可以终止对计算图的回溯:

		- with torch.no_grad():

- 学习了关于Tensor的若干操作:

	- torch.ones(n, n, requires_grad=True)

	- x.grad_fn

	- a.requires_grad_(True)

- 学习了关于Gradients的属性:

	- x.grad

	- 可以通过.detach()获得一个新的Tensor, 拥有相同的内容但不需要自动求导.

### 小节总结

- 学习了torch.Tensor类的相关概念.

	- torch.Tensor是整个package中的核心类, 如果将属性.requires_grad设置为True, 它将追踪在这个类上定义的所有操作. 当代码要进行反向传播的时候, 直接调用.backward()就可以自动计算所有的梯度. 在这个Tensor上的所有梯度将被累加进属性.grad中.

	- 执行.detach()命令, 可以将该Tensor从计算图中撤下, 在未来的回溯计算中不会再计算该Tensor.

	- 采用代码块的方式也可以终止对计算图的回溯:

		- with torch.no_grad():

- 学习了关于Tensor的若干操作:

	- torch.ones(n, n, requires_grad=True)

	- x.grad_fn

	- a.requires_grad_(True)

- 学习了关于Gradients的属性:

	- x.grad

	- 可以通过.detach()获得一个新的Tensor, 拥有相同的内容但不需要自动求导.