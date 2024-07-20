[https://blog.csdn.net/weixin_43721000/article/details/125313547](https://blog.csdn.net/weixin_43721000/article/details/125313547)

一、镜像配置命令

ps：如果没有配置 Anaconda 环境变量就在 Anaconda Prompt 中粘贴执行，配置过了就在cmd中粘贴执行

1.清华镜像

conda config --remove-key channels

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/free/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/free/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/main/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/main/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/conda-forge/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/conda-forge/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/msys2/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/msys2/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/bioconda/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/bioconda/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/menpo/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/menpo/)

conda config --add channels [https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/pytorch/](https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/pytorch/)

conda config --set show_channel_urls yes

1

2

3

4

5

6

7

8

9

10

2.中科大镜像

conda config --remove-key channels

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/pkgs/free/](https://mirrors.ustc.edu.cn/anaconda/pkgs/free/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/pkgs/main/](https://mirrors.ustc.edu.cn/anaconda/pkgs/main/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/cloud/conda-forge/](https://mirrors.ustc.edu.cn/anaconda/cloud/conda-forge/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/cloud/msys2/](https://mirrors.ustc.edu.cn/anaconda/cloud/msys2/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/cloud/bioconda/](https://mirrors.ustc.edu.cn/anaconda/cloud/bioconda/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/cloud/menpo/](https://mirrors.ustc.edu.cn/anaconda/cloud/menpo/)

conda config --add channels [https://mirrors.ustc.edu.cn/anaconda/cloud/pytorch/](https://mirrors.ustc.edu.cn/anaconda/cloud/pytorch/)

conda config --set show_channel_urls yes

1

2

3

4

5

6

7

8

9

10

3.上交大镜像

conda config --remove-key channels

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/pkgs/free/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/pkgs/free/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/pkgs/main/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/pkgs/main/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/conda-forge/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/conda-forge/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/msys2/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/msys2/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/bioconda/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/bioconda/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/menpo/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/menpo/)

conda config --add channels [https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/pytorch/](https://mirrors.sjtug.sjtu.edu.cn/anaconda/cloud/pytorch/)

conda config --set show_channel_urls yes

1

2

3

4

5

6

7

8

9

10

二、测试

输入如下命令查看配置结果

conda config --show-sources

1

三、常用命令解释

1.查看当前源

conda config --show-sources

1

2.添加源

conda config --add channels <url>

1

3.删除源

conda config --remove channels <url>

1

4.删除全部源，恢复默认状态

conda config --remove-key channels

1

5. 搜索源时显示通道地址

conda config --set show_channel_urls yes

1

————————————————

版权声明：本文为CSDN博主「什么都干的派森」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/weixin_43721000/article/details/125313547](https://blog.csdn.net/weixin_43721000/article/details/125313547)