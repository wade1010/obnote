- 介绍

- 查询方式

- 一、使用字符串作为查询条件

- 二、使用数组作为查询条件

- 三、使用对象方式来查询

- 表达式查询

- 快捷查询

- 一、实现不同字段相同的查询条件

- 二、实现不同字段不同的查询条件

- 区间查询

- 组合查询

- 一、字符串模式查询（采用_string 作为查询条件）

- 二、请求字符串查询方式

- 三、复合查询

- 统计查询

- SQL查询

- 1、query方法

- 2、execute方法

- 动态查询

- 一、getBy动态查询

- 二、getFieldBy动态查询

- 子查询

- 1、使用select方法

- 2、使用buildSql方法

- 总结

上一篇中我们掌握了基本的数据CURD方法，但更多的情况下面，由于业务逻辑的差异，CURD操作往往不是那么简单，尤其是复杂的业务逻辑下面，这也是ActiveRecord模式的不足之处。ThinkPHP的查询语言配合连贯操作可以很好解决复杂的业务逻辑需求，本篇我们就首先来深入了解下框架的查询语言。

介绍

ThinkPHP内置了非常灵活的查询方法，可以快速的进行数据查询操作，查询条件可以用于读取、更新和删除等操作，主要涉及到where方法等连贯操作即可，无论是采用什么数据库，你几乎采用一样的查询方法（个别数据库例如Mongo在表达式查询方面会有所差异），系统帮你解决了不同数据库的差异性，因此我们把框架的这一查询方式称之为查询语言。查询语言也是ThinkPHP框架的ORM亮点，让查询操作更加简单易懂。下面来一一讲解查询语言的内涵。

查询方式

ThinkPHP可以支持直接使用字符串作为查询条件，但是大多数情况推荐使用索引数组或者对象来作为查询条件，因为会更加安全。

一、使用字符串作为查询条件

这是最传统的方式，但是安全性不高，例如：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $User->where('type=1 AND status=1')->select();


  
  
  
  
  

最后生成的SQL语句是

  
  
  
  
  
   
   
   
   
   

1. SELECT * FROM think_user WHERE type=1 AND status=1


  
  
  
  
  

采用字符串查询的时候，我们可以配合使用新版提供的字符串条件的安全预处理机制，暂且不再细说。

二、使用数组作为查询条件

这种方式是最常用的查询方式，例如：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $condition['name']='thinkphp';


   
   
   
   
   

1. $condition['status']=1;


   
   
   
   
   

1. // 把查询条件传入查询方法


   
   
   
   
   

1. $User->where($condition)->select();


  
  
  
  
  

最后生成的SQL语句是

  
  
  
  
  
   
   
   
   
   

1. SELECT * FROM think_user WHERE `name`='thinkphp' AND status=1


  
  
  
  
  

如果进行多字段查询，那么字段之间的默认逻辑关系是 逻辑与 AND，但是用下面的规则可以更改默认的逻辑判断，通过使用 _logic 定义查询逻辑：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $condition['name']='thinkphp';


   
   
   
   
   

1. $condition['account']='thinkphp';


   
   
   
   
   

1. $condition['_logic']='OR';


   
   
   
   
   

1. // 把查询条件传入查询方法


   
   
   
   
   

1. $User->where($condition)->select();


  
  
  
  
  

最后生成的SQL语句是

  
  
  
  
  
   
   
   
   
   

1. SELECT * FROM think_user WHERE `name`='thinkphp' OR `account`='thinkphp'


  
  
  
  
  

三、使用对象方式来查询

这里以stdClass内置对象为例：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. // 定义查询条件


   
   
   
   
   

1. $condition =new stdClass();


   
   
   
   
   

1. $condition->name ='thinkphp';


   
   
   
   
   

1. $condition->status=1;


   
   
   
   
   

1. $User->where($condition)->select();


  
  
  
  
  

最后生成的SQL语句和上面一样

  
  
  
  
  
   
   
   
   
   

1. SELECT * FROM think_user WHERE `name`='thinkphp' AND status=1


  
  
  
  
  

使用对象方式查询和使用数组查询的效果是相同的，并且是可以互换的，大多数情况下，我们建议采用数组方式更加高效。

表达式查询

上面的查询条件仅仅是一个简单的相等判断，可以使用查询表达式支持更多的SQL查询语法，也是ThinkPHP查询语言的精髓，查询表达式的使用格式：

  
  
  
  
  
   
   
   
   
   

1. $map['字段名']= array('表达式','查询条件');


  
  
  
  
  

表达式不分大小写，支持的查询表达式有下面几种，分别表示的含义是：

| 表达式 | 含义 |
| - | - |
| EQ | 等于（=） |
| NEQ | 不等于（&lt;&gt;） |
| GT | 大于（&gt;） |
| EGT | 大于等于（&gt;=） |
| LT | 小于（&lt;） |
| ELT | 小于等于（&lt;=） |
| LIKE | 模糊查询 |
| [NOT] BETWEEN | （不在）区间查询 |
| [NOT] IN | （不在）IN 查询 |
| EXP | 表达式查询，支持SQL语法 |


示例如下：EQ ：等于（=）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('eq',100);


  
  
  
  
  

和下面的查询等效

  
  
  
  
  
   
   
   
   
   

1. $map['id']=100;


  
  
  
  
  

表示的查询条件就是id = 100

NEQ： 不等于（<>）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('neq',100);


  
  
  
  
  

表示的查询条件就是 id <> 100

GT：大于（>）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('gt',100);


  
  
  
  
  

表示的查询条件就是 id > 100

EGT：大于等于（>=）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('egt',100);


  
  
  
  
  

表示的查询条件就是 id >= 100

LT：小于（<）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('lt',100);


  
  
  
  
  

表示的查询条件就是 id < 100

ELT： 小于等于（<=）例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('elt',100);


  
  
  
  
  
  
  
  
  
  


   
   
   
   
   复制代码
  
  
  
  
  

表示的查询条件就是 id <= 100

[NOT] LIKE： 同sql的LIKE例如：

  
  
  
  
  
   
   
   
   
   

1. $map['name']= array('like','thinkphp%');


  
  
  
  
  

查询条件就变成 name like 'thinkphp%'如果配置了DB_LIKE_FIELDS参数的话，某些字段也会自动进行模糊查询。例如设置了：

  
  
  
  
  
   
   
   
   
   

1. 'DB_LIKE_FIELDS'=>'title|content'


  
  
  
  
  

的话，使用

  
  
  
  
  
   
   
   
   
   

1. $map['title']='thinkphp';


  
  
  
  
  

查询条件就会变成 title like '%thinkphp%'支持数组方式，例如

  
  
  
  
  
   
   
   
   
   

1. $map['a']=array('like',array('%thinkphp%','%tp'),'OR');


   
   
   
   
   

1. $map['b']=array('notlike',array('%thinkphp%','%tp'),'AND');


  
  
  
  
  

生成的查询条件就是：(a like '%thinkphp%' OR a like '%tp') AND (b not like '%thinkphp%' AND b not like '%tp')

[NOT] BETWEEN ：同sql的[not] between， 查询条件支持字符串或者数组，例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('between','1,8');


  
  
  
  
  

和下面的等效：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('between',array('1','8'));


  
  
  
  
  

查询条件就变成 id BETWEEN 1 AND 8

[NOT] IN： 同sql的[not] in ，查询条件支持字符串或者数组，例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('not in','1,5,8');


  
  
  
  
  

和下面的等效：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('not in',array('1','5','8'));


  
  
  
  
  

查询条件就变成 id NOT IN (1,5, 8)

EXP：表达式，支持更复杂的查询情况例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('in','1,3,8');


  
  
  
  
  

可以改成：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('exp',' IN (1,3,8) ');


  
  
  
  
  

exp查询的条件不会被当成字符串，所以后面的查询条件可以使用任何SQL支持的语法，包括使用函数和字段名称。查询表达式不仅可用于查询条件，也可以用于数据更新，例如：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. // 要修改的数据对象属性赋值


   
   
   
   
   

1. $data['name']='ThinkPHP';


   
   
   
   
   

1. $data['score']= array('exp','score+1');// 用户的积分加1


   
   
   
   
   

1. $User->where('id=5')->save($data);// 根据条件保存修改的数据


  
  
  
  
  

快捷查询

采用快捷查询方式，可以进一步简化查询条件的写法，例如：

一、实现不同字段相同的查询条件

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $map['name|title']='thinkphp';


   
   
   
   
   

1. // 把查询条件传入查询方法


   
   
   
   
   

1. $User->where($map)->select();


  
  
  
  
  

查询条件就变成name= 'thinkphp' OR title = 'thinkphp'

二、实现不同字段不同的查询条件

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $map['status&title']=array('1','thinkphp','_multi'=>true);


   
   
   
   
   

1. // 把查询条件传入查询方法


   
   
   
   
   

1. $User->where($map)->select();


  
  
  
  
  

'_multi'=>true必须加在数组的最后，表示当前是多条件匹配，这样查询条件就变成status= 1 AND title = 'thinkphp'

，查询字段支持更多的，例如：

  
  
  
  
  
   
   
   
   
   

1. $map['status&score&title']=array('1',array('gt','0'),'thinkphp','_multi'=>true);


  
  
  
  
  

查询条件就变成status= 1 AND score >0 AND title = 'thinkphp'

注意：快捷查询方式中“|”和“&”不能同时使用。

区间查询

ThinkPHP支持对某个字段的区间查询，例如：

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array(array('gt',1),array('lt',10));


  
  
  
  
  

得到的查询条件是：

  
  
  
  
  
   
   
   
   
   

1. (`id`>1) AND (`id`<10)


  
  
  
  
  

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array(array('gt',3),array('lt',10),'or');


  
  
  
  
  

得到的查询条件是：

  
  
  
  
  
   
   
   
   
   

1. (`id`>3) OR (`id`<10)


  
  
  
  
  

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array(array('neq',6),array('gt',3),'and');


  
  
  
  
  

得到的查询条件是：

  
  
  
  
  
   
   
   
   
   

1. (`id`!=6) AND (`id`>3)


  
  
  
  
  

最后一个可以是AND、 OR或者 XOR运算符，如果不写，默认是AND运算。

区间查询的条件可以支持普通查询的所有表达式，也就是说类似LIKE、GT和EXP这样的表达式都可以支持。另外区间查询还可以支持更多的条件，只要是针对一个字段的条件都可以写到一起，例如：

  
  
  
  
  
   
   
   
   
   

1. $map['name']= array(array('like','%a%'), array('like','%b%'), array('like','%c%'),'ThinkPHP','or');


  
  
  
  
  

最后的查询条件是：

  
  
  
  
  
   
   
   
   
   

1. (`name` LIKE '%a%') OR (`name` LIKE '%b%') OR (`name` LIKE '%c%') OR (`name`='ThinkPHP')


  
  
  
  
  

组合查询

组合查询的主体还是采用数组方式查询，只是加入了一些特殊的查询支持，包括字符串模式查询（_string）、复合查询（_complex）、请求字符串查询（_query），混合查询中的特殊查询每次查询只能定义一个，由于采用数组的索引方式，索引相同的特殊查询会被覆盖。

一、字符串模式查询（采用_string 作为查询条件）

数组条件还可以和字符串条件混合使用，例如：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. $map['id']= array('neq',1);


   
   
   
   
   

1. $map['name']='ok';


   
   
   
   
   

1. $map['_string']='status=1 AND score>10';


   
   
   
   
   

1. $User->where($map)->select();


  
  
  
  
  

最后得到的查询条件就成了：

  
  
  
  
  
   
   
   
   
   

1. (`id`!=1) AND (`name`='ok') AND ( status=1 AND score>10)


  
  
  
  
  

二、请求字符串查询方式

请求字符串查询是一种类似于URL传参的方式，可以支持简单的条件相等判断。

  
  
  
  
  
   
   
   
   
   

1. $map['id']= array('gt','100');


   
   
   
   
   

1. $map['_query']='status=1&score=100&_logic=or';


  
  
  
  
  

得到的查询条件是：

  
  
  
  
  
   
   
   
   
   

1. `id`>100 AND (`status`='1' OR `score`='100')


  
  
  
  
  

三、复合查询

复合查询相当于封装了一个新的查询条件，然后并入原来的查询条件之中，所以可以完成比较复杂的查询条件组装。例如：

  
  
  
  
  
   
   
   
   
   

1. $where['name']= array('like','%thinkphp%');


   
   
   
   
   

1. $where['title']= array('like','%thinkphp%');


   
   
   
   
   

1. $where['_logic']='or';


   
   
   
   
   

1. $map['_complex']= $where;


   
   
   
   
   

1. $map['id']= array('gt',1);


  
  
  
  
  

查询条件是

  
  
  
  
  
   
   
   
   
   

1. ( id >1) AND (( name like '%thinkphp%') OR ( title like '%thinkphp%'))


  
  
  
  
  

复合查询使用了_complex作为子查询条件来定义，配合之前的查询方式，可以非常灵活的制定更加复杂的查询条件。很多查询方式可以相互转换，例如上面的查询条件可以改成：

  
  
  
  
  
   
   
   
   
   

1. $where['id']= array('gt',1);


   
   
   
   
   

1. $where['_string']=' (name like "%thinkphp%") OR ( title like "%thinkphp") ';


  
  
  
  
  

最后生成的SQL语句是一致的。

统计查询

在应用中我们经常会用到一些统计数据，例如当前所有（或者满足某些条件）的用户数、所有用户的最大积分、用户的平均成绩等等，ThinkPHP为这些统计操作提供了一系列的内置方法，包括：

| 方法 | 说明 |
| - | - |
| Count | 统计数量，参数是要统计的字段名（可选） |
| Max | 获取最大值，参数是要统计的字段名（必须） |
| Min | 获取最小值，参数是要统计的字段名（必须） |
| Avg | 获取平均值，参数是要统计的字段名（必须） |
| Sum | 获取总分，参数是要统计的字段名（必须） |


用法示例：

  
  
  
  
  
   
   
   
   
   

1. $User = M("User");// 实例化User对象


   
   
   
   
   

1. // 获取用户数：


   
   
   
   
   

1. $userCount = $User->count();


   
   
   
   
   

1. // 或者根据字段统计：


   
   
   
   
   

1. $userCount = $User->count("id");


   
   
   
   
   

1. // 获取用户的最大积分：


   
   
   
   
   

1. $maxScore = $User->max('score');


   
   
   
   
   

1. // 获取积分大于0的用户的最小积分：


   
   
   
   
   

1. $minScore = $User->where('score>0')->min('score');


   
   
   
   
   

1. // 获取用户的平均积分：


   
   
   
   
   

1. $avgScore = $User->avg('score');


   
   
   
   
   

1. // 统计用户的总成绩：


   
   
   
   
   

1. $sumScore = $User->sum('score');


  
  
  
  
  

并且所有的统计查询均支持连贯操作的使用。

SQL查询

ThinkPHP内置的ORM和ActiveRecord模式实现了方便的数据存取操作，而且新版增加的连贯操作功能更是让这个数据操作更加清晰，但是ThinkPHP仍然保留了原生的SQL查询和执行操作支持，为了满足复杂查询的需要和一些特殊的数据操作，SQL查询的返回值因为是直接返回的Db类的查询结果，没有做任何的处理。主要包括下面两个方法：

1、query方法

| query | 执行SQL查询操作 |
| - | - |
| 用法 | query($sql,$parse=false) |
| 参数 | sql（必须）：要查询的SQL语句 parse（可选）：是否需要解析SQL |
| 返回值 | 如果数据非法或者查询错误则返回false，否则返回查询结果数据集（同select方法） |


使用示例：

  
  
  
  
  
   
   
   
   
   

1. $Model =newModel()// 实例化一个model对象 没有对应任何数据表


   
   
   
   
   

1. $Model->query("select * from think_user where status=1");


  
  
  
  
  

如果你当前采用了分布式数据库，并且设置了读写分离的话，query方法始终是在读服务器执行，因此query方法对应的都是读操作，而不管你的SQL语句是什么。

2、execute方法

| execute | 用于更新和写入数据的sql操作 |
| - | - |
| 用法 | execute($sql,$parse=false) |
| 参数 | sql（必须）：要执行的SQL语句 parse（可选）：是否需要解析SQL |
| 返回值 | 如果数据非法或者查询错误则返回false ，否则返回影响的记录数 |


使用示例：

  
  
  
  
  
   
   
   
   
   

1. $Model =newModel()// 实例化一个model对象 没有对应任何数据表


   
   
   
   
   

1. $Model->execute("update think_user set name='thinkPHP' where status=1");


  
  
  
  
  

如果你当前采用了分布式数据库，并且设置了读写分离的话，execute方法始终是在写服务器执行，因此execute方法对应的都是写操作，而不管你的SQL语句是什么。

动态查询

借助PHP5语言的特性，ThinkPHP实现了动态查询，核心模型的动态查询方法包括下面几种：

| 方法名 | 说明 | 举例 |
| - | - | - |
| getBy | 根据字段的值查询数据 | 例如，getByName,getByEmail |
| getFieldBy | 根据字段查询并返回某个字段的值 | 例如，getFieldByName |


一、getBy动态查询

该查询方式针对数据表的字段进行查询。例如，User对象拥有id,name,email,address 等属性，那么我们就可以使用下面的查询方法来直接根据某个属性来查询符合条件的记录。

  
  
  
  
  
   
   
   
   
   

1. $user = $User->getByName('liu21st');


   
   
   
   
   

1. $user = $User->getByEmail('liu21st@gmail.com');


   
   
   
   
   

1. $user = $User->getByAddress('中国深圳');


  
  
  
  
  

暂时不支持多数据字段的动态查询方法，请使用find方法和select方法进行查询。

二、getFieldBy动态查询

针对某个字段查询并返回某个字段的值，例如

  
  
  
  
  
   
   
   
   
   

1. $userId = $User->getFieldByName('liu21st','id');


  
  
  
  
  

表示根据用户的name获取用户的id值。

子查询

子查询有两种使用方式：

1、使用select方法

当select方法的参数为false的时候，表示不进行查询只是返回构建SQL，例如：

  
  
  
  
  
   
   
   
   
   

1. // 首先构造子查询SQL 


   
   
   
   
   

1. $subQuery = $model->field('id,name')->table('tablename')->group('field')->where($where)->order('status')->select(false);


  
  
  
  
  

当select方法传入false参数的时候，表示不执行当前查询，而只是生成查询SQL。

2、使用buildSql方法

  
  
  
  
  
   
   
   
   
   

1. $subQuery = $model->field('id,name')->table('tablename')->group('field')->where($where)->order('status')->buildSql();


  
  
  
  
  

调用buildSql方法后不会进行实际的查询操作，而只是生成该次查询的SQL语句（为了避免混淆，会在SQL两边加上括号），然后我们直接在后续的查询中直接调用。

  
  
  
  
  
   
   
   
   
   

1. // 利用子查询进行查询 


   
   
   
   
   

1. $model->table($subQuery.' a')->where()->order()->select()


  
  
  
  
  

构造的子查询SQL可用于ThinkPHP的连贯操作方法，例如table where等。

总结

本篇主要帮助我们了解如何进行数据的查询，包括简单查询、表达式查询、快捷查询、区间查询、统计查询，以及如何进行子查询操作。后面我们还会详细了解如何使用连贯操作进行更复杂的CURD操作。