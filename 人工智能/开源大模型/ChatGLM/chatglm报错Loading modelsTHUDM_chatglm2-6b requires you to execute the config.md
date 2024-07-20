运行chatglm报错如下：

Loading models/THUDM_chatglm2-6b requires you to execute the configuration file in that repo on your local machine. Make sure you have read the code there to avoid malicious use, then set the option trust_remote_code=True to remove this error.

加上trust_remote_code=True就可以了

这里我用text-generation-webui，如下图

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158400.jpg)