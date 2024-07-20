thinkphp自带模板么？

可以用很多种方法获取session，

一、{:session('xxxx')}，这个冒号后面就可以直接跟函数了，可以获取设置都没问题；

二、另外一种是直接用thinkphp的模板系统变量{$Think.session.xxxx}

三、不太推荐的方法，可以直接用<php>echo session('xxxx');</php>或者<?php echo session('xxxx');?> 都是可以的，因为thinkphp模板最后还是要生成php文件的，所以可以直接把这个模板看成php文件。