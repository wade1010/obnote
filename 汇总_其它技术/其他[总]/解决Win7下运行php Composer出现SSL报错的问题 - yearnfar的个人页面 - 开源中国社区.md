[cacert.pem](attachments/529CA87467AF4CB78B05AC9B6E3BE293cacert.pem)



以前都在linux环境使用php composer。今天尝试在win7下运行composer却出现SSL报错：

?

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14 | D:\\data\\www\\mmoyu\\symapp&gt;php -f %phprc%\\composer install<br>Loading composer repositories with package information<br> <br>  [Composer\\Downloader\\TransportException]<br>  The "https://packagist.org/packages.json" file could not be downloaded: SSL<br>   operation failed with code 1. OpenSSL Error messages:<br>  error:14090086:SSL routines:SSL3\_GET\_SERVER\_CERTIFICATE:certificate verify<br>  failed<br>  Failed to enable crypto<br>  failed to open stream: operation failed<br> <br>install [--prefer-source] [--prefer-dist] [--dry-run] [--dev] [--no-dev] [--no-p<br>lugins] [--no-custom-installers] [--no-scripts] [--no-progress] [-v|vv|vvv|--ver<br>bose] [-o|--optimize-autoloader] |


没有安装CA证书导致的！！！

CA证书下载地址：http://curl.haxx.se/docs/caextract.html 或http://vdisk.weibo.com/s/dbGSP_kssImp0?category_id=0&parents_ref=dbGSP_kssIn22,dbGSP_kssIn1I

然后修改php.ini文件 

?

|   |   |
| - | - |
| 1 | openssl.cafile= D:/wamp/php/verify/cacert.pem |


就OK了~