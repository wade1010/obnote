首先安装PHP扩展



https://pecl.php.net/package/hprose



wget https://pecl.php.net/get/hprose-1.8.0.tgz



tar -zxvf hprose-1.8.0.tgz



cd hprose-1.8.0



phpize



./configure



make&&make install



在php.ini里面添加下面代码

[hprose]

extension=hprose.so







项目目录新建composer.json



{

  "require": {

    "php": ">=7.1.0",

    "ext-hprose": ">=1.8.0",

    "hprose/hprose": "dev-master"

  },

  "autoload": {

    "psr-4": {

      "Hprose\\": "src/Hprose"

    }

  }

}





composer install





