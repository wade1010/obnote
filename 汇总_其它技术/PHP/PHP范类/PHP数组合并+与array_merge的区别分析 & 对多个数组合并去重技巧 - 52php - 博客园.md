PHP中两个数组合并可以使用+或者array_merge，但之间还是有区别的，而且这些区别如果了解不清楚项目中会要命的！

主要区别是两个或者多个数组中如果出现相同键名，键名分为字符串或者数字，需要注意

1）键名为数字时，array_merge()后面的值将不会覆盖原来的值，而是附加到后面，但＋合并数组则会把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）

2）键名为字符串时，array_merge()此时会覆盖掉前面相同键名的值，但＋仍然把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）。

ALTER TABLE user_cookie CHANGE COLUMN ent_uid ent_uid char(32) NOT NULL DEFAULT '' COMMENT '企业号id 或 openid';



需要注意的是数组键形式 '数字' 等价于 数字

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48<br>49<br>50<br>51<br>52<br>53<br>54<br>55<br>56<br>57<br>58<br>59<br>60<br>61<br>62<br>63<br>64<br>65<br>66<br>67<br>68<br>69<br>70<br>71<br>72<br>73<br>74<br>75<br>76<br>77<br>78<br>79<br>80<br>81<br>82<br>83<br>84<br>85<br>86<br>87<br>88<br>89<br>90<br>91 | $a= array('a', 'b');<br>$b= array('c', 'd');<br>$c= $a+ $b;<br>var\_dump($a);<br>var\_dump(array\_merge($a, $b));<br>//输出：<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>2 =&gt; string 'c'(length=1)<br>3 =&gt; string 'd'(length=1)<br>++++++++++++++++++++++++++++++++++++++++++ <br>$a= array(<br>0 =&gt; 'a',<br>1 =&gt; 'b'<br>);<br>$b= array(<br>0 =&gt; 'c',<br>1 =&gt; 'b'<br>);<br>$c= $a+ $b;<br>var\_dump($c);<br>var\_dump(array\_merge($a, $b));<br>//输出：<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>2 =&gt; string 'c'(length=1)<br>3 =&gt; string 'b'(length=1)<br>++++++++++++++++++++++++++++++++++++++++++ <br>$a= array('a', 'b');<br>$b= array(<br>'0'=&gt; 'c',<br>1 =&gt; 'b'<br>);<br>$c= $a+ $b;<br>var\_dump($c);<br>var\_dump(array\_merge($a, $b));<br>//输出：<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>2 =&gt; string 'c'(length=1)<br>3 =&gt; string 'b'(length=1)<br>++++++++++++++++++++++++++++++++++++++++++<br>$a= array(<br>0 =&gt; 'a',<br>1 =&gt; 'b'<br>);<br>$b= array(<br>'0'=&gt; 'c',<br>'1'=&gt; 'b'<br>);<br>$c= $a+ $b;<br>var\_dump($c);<br>var\_dump(array\_merge($a, $b));<br>输出：<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>array<br>0 =&gt; string 'a'(length=1)<br>1 =&gt; string 'b'(length=1)<br>2 =&gt; string 'c'(length=1)<br>3 =&gt; string 'b'(length=1) |


对多个数组合并去重技巧

|   |   |
| - | - |
| 1<br>2<br>3<br>4 | $a= array('1001','1002');<br>$b= array('1002','1003','1004');<br>$c= array('1003','1004','1005');<br>$d= count(array\_flip($a) + array\_flip($b) + array\_flip($c)); |


延伸阅读：

PHP合并2个数字键数组的值