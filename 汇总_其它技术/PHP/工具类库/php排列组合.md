

```javascript
<?php
 
/**
 * 无重复排列組合
 * @Author   MAX
 * @DateTime 2018-09-07T16:28:40+0800
 * @param    Array                   $arr 需要排列組合的数组
 * @param    Number                   $m   每几个一組
 * @param    [Array]                  $push   添加到数组里
 * @return   Array                        組合好的数组
 */
function getCombinationToString($arr, $m, $push=null) {
    $rst = array();
    for($i = 0; $i < pow(2, count($arr)); $i++) {
        $a = 0;
        $b = array();
        for($j = 0; $j < count($arr); $j++) {
            if($i >> $j & 1) {
                $a++;
                array_push($b, $arr[$j]);
            }
        }
        if($a == $m) {
            if(!is_null($push)){
                if(is_string($push)){
                    $b = array_unshift($b, $push);
                }else{
                    $b = array_merge($push, $b);
                }
            }
            $rst[] = $b;
        }
    }
    return $rst;
}
$t1 = microtime(true);
$a = [1,2,3];
$b = getCombinationToString($a,2,[]);
echo '无重复排列組合1';
echo '<pre>';
print_r($b);
$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒<br>';
echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';
 
/*
    ps:
    microtime() 加上 true 参数, 返回的将是一个浮点类型. 这样 t1 和 t2 得到的就是两个浮点数, 相减之后得到之间的差. 由于浮点的位数很长, 或者说不确定, 所以使用 round() 取出小数点后 3 位。
    memory_get_usage() 返回的单位是b,/1024得到kb,/(1024*1024)得到mb，依次类推。
*/
 
/**
 * 无重复排列組合
 * @Author   MAX
 * @DateTime 2018-09-07T16:28:40+0800
 * @param    Array                   $arr 需要排列組合的数组
 * @param    Number                   $m   每几个一組
 * @return   Array                        組合好的数组
 */
function getCombinationToString2($arr, $m)
{
    $result = array();
    if ($m ==1)
    {
        return $arr;
    }
    if ($m == count($arr))
    {
        $result[] = implode(',' , $arr);
        return $result;
    }
    $temp_firstelement = $arr[0];
    unset($arr[0]);
    $arr = array_values($arr);
    $temp_list1 = getCombinationToString2($arr, ($m-1));
    foreach ($temp_list1 as $s)
    {
        $s = $temp_firstelement.','.$s;
        $result[] = $s;
    }
    unset($temp_list1);
    $temp_list2 = getCombinationToString2($arr, $m);
    foreach ($temp_list2 as $s)
    {
        $result[] = $s;
    }
    unset($temp_list2);
    return $result;
}
 
$t1 = microtime(true);
$a = [1,2,3];
$b = getCombinationToString2($a,2);
echo '无重复排列組合2';
echo '<pre>';
print_r($b);
$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒<br>';
echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';
 
/**
 * 可重复排列組合（全组合）
 * 解决问题：求一个含有N个元素的数组中取出M个元素组成新的数组，一共可以组合成的数组并输出
 * $arr $arr 需要排列組合的数组
 * $m   每几个一組
 */
function getCombinationToString3($arr, $m) {
    if ($m ==1) {
        return $arr;
    }
    $result = array();
 
    $tmpArr = $arr;
    unset($tmpArr[0]);
    for($i=0;$i<count($arr);$i++) {
        $s = $arr[$i];
        $ret = getCombinationToString3(array_values($tmpArr), ($m-1), $result);
 
        foreach($ret as $row) {
            $result[] = $s . $row;
        }
    }
 
    return $result;
}
 
$t1 = microtime(true);
$arr = array(1,2,3);
$r = getCombinationToString3($arr, 2);
echo '可重复排列組合';
echo '<pre>';
print_r($r);
$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒<br>';
echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';
```







![](https://gitee.com/hxc8/images8/raw/master/img/202407191111189.jpg)





```javascript
/**
 * 组合
 * @param $arr
 * @param $result
 */
static function Combination($arr, &$result)
{
    $len = count($arr);
    $cache = [];
    for ($i = 1; $i <= $len; $i++) {
        self::Combine($arr, 0, $i, $cache, $result);
    }
}

/**
 * 组合操作
 * @param $arr
 * @param $begin
 * @param $number
 * @param $cache
 * @param $result
 */
private static function Combine($arr, $begin, $number, &$cache, &$result)
{
    if ($number == 0) {
        $result[] = $cache;
        return;
    }
    if ($begin >= count($arr)) return;
    array_push($cache, $arr[$begin]);
    self::Combine($arr, $begin + 1, $number - 1, $cache, $result);
    array_pop($cache);
    self::Combine($arr, $begin + 1, $number, $cache, $result);
}
```

