第一步：

去除eclipse的JS验证：

将windows-<preference-<Java Script-<Validator-<Errors/Warnings-<

Enable Javascript Sematic validation前面的勾去掉;

第二步：

右键项目 -< properties -< Builders 去掉JavaScript Validator 前面的勾

如果Builders中没有JavaScript Validator这一项。那么去.project文件中修改如下内容：

找到项目目录，删除项目目录里的 .project 文件中的以下部分：



org.eclipse.wst.jsdt.core.javascriptValidator





和org.eclipse.wst.jsdt.core.jsNature

注意，修改.project文件可能会要求重启eclipse，或者在eclipse中关闭该工程，然后重新打开工程。

第三步：

复制该js文件到某个地方，然后从eclipse中直接删除 了报错的js文件，错误消失，再把刚才那

个js文件拷进来。

如果以上做了之后还不对的吧，就在Eclipse中打开Problems view，选中相关的错误，根据提示，进行解决。

您可能感兴趣的文章:

- Zend Studio for Eclipse的java.lang.NullPointerException错误的解决方法

- Eclipse下jQuery文件报错出现错误提示红叉

- Mac下使用Eclipse编译C/C++文件出现 launch failed, binary not found 解决方案