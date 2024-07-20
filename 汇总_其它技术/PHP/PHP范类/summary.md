2016年08月05日10:47:04

1. 使用file系统，curl中少了curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  取不到curl_exec返回的结果，结果只有true或者false,打开后，返回服务端返回的结果。

1. UPLOAD_ERR_INI_SIZE其值为1，上传文件超过php.ini中的upload_max_filesize选项限制的值。

1. 获取状态时返回Freason,好猎友PHP将Freson存在user属性中，update用户的时候，将Freason传了上来，我们F开头的统一接收，而User表是没有Freason字段的。导致sql报错。

1. bug 809  没有看清文档中的文案，差别小的文案不注意；

1. 添加猎头公司没有发送邮件；产品