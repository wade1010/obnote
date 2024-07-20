https://blog.csdn.net/qq_26388117/article/details/114066907



ubuntu下 C/C++ 开发工具选择和环境搭建

 

1.开发工具下载及安装

开发工具选用 CLion，进入终端输入



 sudo snap install clion --classic 



等待执行完成后CLion就安装完成了 显示应用程序--》搜索 cl就出来了



 

2.编译环境配置

首先要安装所需的环境配合 gcc/g++/make,同样是终端安装命令如下：



sudo apt install gcc



sudo apt install g++



sudo apt install make



依次执行，安装等待安装完成后，安装目录是在 /usr/bin 里，你可以cd到这个目录通过find命令查询





安装完成后，进入CLion--》 File | Settings | Build, Execution, Deployment | Toolchains 下配置如图



配置完成重启 CLion ，新建一个项目就可以运行了





