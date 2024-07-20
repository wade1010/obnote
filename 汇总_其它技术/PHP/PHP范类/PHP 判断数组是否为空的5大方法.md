本文介绍了PHP开发中遇到的数组问题，这里介绍了判断PHP数组为空的5种方法，有需要的朋友可以借鉴参考一下。

1. isset功能：判断变量是否被初始化

说明：它并不会判断变量是否为空，并且可以用来判断数组中元素是否被定义过

注意：当使用isset来判断数组元素是否被初始化过时，它的效率比array_key_exists高4倍左右

view source print ?

01.

02.$a= '';

03.$a['c'] = '';

04.if(!isset($a)) echo'$a 未被初始化'. "";

05.if(!isset($b)) echo'$b 未被初始化'. "";

06.if(isset($a['c'])) echo'$a 已经被初始化'. "";

07.// 显示结果为

08.// $b 未被初始化

09.// $a 已经被初始化

2. empty功能：检测变量是否为”空”

说明：任何一个未初始化的变量、值为 0 或 false 或 空字符串”” 或 null的变量、空数组、没有任何属性的对象，都将判断为empty==true

注意1：未初始化的变量也能被empty检测为”空”

注意2：empty只能检测变量，而不能检测语句

view source

print?

1.

2.$a= 0;

3.$b= '';

4.$c= array();

5.if(emptyempty($a)) echo'$a 为空'. "";

6.if(emptyempty($b)) echo'$b 为空'. "";

7.if(emptyempty($c)) echo'$c 为空'. "";

8.if(emptyempty($d)) echo'$d 为空'. "";

3. var == null功能：判断变量是否为”空”

说明：值为 0 或 false 或 空字符串”” 或 null的变量、空数组、都将判断为 null

注意：与empty的显著不同就是：变量未初始化时 var == null 将会报错。

view sourceprint?

01.

02.$a= 0;

03.$b= array();

04.if($a== null) echo'$a 为空'. "";

05.if($b== null) echo'$b 为空'. "";

06.if($c== null) echo'$b 为空'. "";

07.// 显示结果为

08.// $a 为空

09.// $b 为空

10.// Undefined variable: c

4. is_null功能：检测变量是否为”null”

说明：当变量被赋值为”null”时，检测结果为true

注意1：null不区分大小写：$a = null; $a = NULL 没有任何区别

注意2：仅在变量的值为”null”时，检测结果才为true，0、空字符串、false、空数组都检测为false

注意3：变量未初始化时，程序将会报错

view source

print?

01.

02.$a= null;

03.$b= false;

04.if(is_null($a)) echo'$a 为NULL'. "";

05.if(is_null($b)) echo'$b 为NULL'. "";

06.if(is_null($c)) echo'$c 为NULL'. "";

07.// 显示结果为

08.// $a 为NULL

09.// Undefined variable: c

5. var === null功能：检测变量是否为”null”，同时变量的类型也必须是”null”

说明：当变量被赋值为”null”时，同时变量的类型也是”null”时，检测结果为true

注意1：在判断为”null”上，全等于和is_null的作用相同

注意2：变量未初始化时，程序将会报错

总结：

PHP中，”NULL” 和 “空” 是2个概念。

isset 主要用来判断变量是否被初始化过

empty 可以将值为 “假”、”空”、”0″、”NULL”、”未初始化” 的变量都判断为TRUE

is_null 仅把值为 “NULL” 的变量判断为TRUE

var == null 把值为 “假”、”空”、”0″、”NULL” 的变量都判断为TRUE

var === null 仅把值为 “NULL” 的变量判断为TRUE

注意：在判断一个变量是否真正为”NULL”时，大多使用 is_null，从而避免”false”、”0″等值的干扰。

