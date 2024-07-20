
```
通过一趟排序将待排记录分割成独立的两部分，其中一部分记录的关键字均比另一部分记录的关键字小，则可分别对这两部分记录继续进行排序，以达到整个序列有序的目的。
1 从数列中挑出一个元素，称为 "基准"（pivot），
2 重新排序数列，所有元素比基准值小的摆放在基准前面，所有元素比基准值大的摆在基准的后面（相同的数可以到任一边）。在这个分区退出之后，该基准就处于数列的中间位置。这个称为分区（partition）操作。
3 递归地（recursive）把小于基准值元素的子数列和大于基准值元素的子数列排序。
递归的最底部情形，是数列的大小是零或一，也就是永远都已经被排序好了。虽然一直递归下去，但是这个算法总会退出，因为在每次的迭代（iteration）中，它至少会把一个元素摆到它最后的位置去。
```



```
<?php

function quickSort($arr) {
    $length = count($arr);
    if ($length <= 1) {
        return $arr;
    }
    $base_num = $arr[0];
    $left_array = array();
    $right_array = array();
    for ($i = 1; $i < $length; $i++) {
        if ($base_num > $arr[$i]) {
            $left_array[] = $arr[$i];
        } else {
            $right_array[] = $arr[$i];
        }
    }
    $left_array = quickSort($left_array);
    $right_array = quickSort($right_array);
    return array_merge($left_array,  array($base_num), $right_array);
}

$arr = array(3, 4, 6,7,2);
var_dump(quickSort($arr));

?>
```
