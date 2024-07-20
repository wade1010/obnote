大部分弄懂，调通后，发现 GoodsModel::all(); 居然超时



后来 用例子和之前的测试了下。结果大失所望



右边是laravel  就是测试一个find(1)  就一条数据



ab -n 400 -c 100 http://api.xxxx.com/goods/index/list







![](https://gitee.com/hxc8/images8/raw/master/img/202407191106934.jpg)

