            $sql = 'select SQL_CALC_FOUND_ROWS id from user where rec_ins_id='.$uid." limit {$pageSize}";

            //  找出所有  好友

            $return = DB::GetQueryResult($sql,false);

            $num = DB::GetQueryResult('SELECT FOUND_ROWS()',true);

            //查询总数，用于分页

            $totNum=$num['found_rows()'];

![](https://gitee.com/hxc8/images8/raw/master/img/202407191059863.jpg)





　　分页程序一般由两条SQL组成：

SELECTCOUNT(*) FROM ... WHERE ....

SELECT ... FROM ... WHERE LIMIT ...

　　如果使用SQL_CALC_FOUND_ROWS的话，一条SQL就可以了：

SELECT SQL_CALC_FOUND_ROWS ... FROM ... WHERE LIMIT ...

　　在得到数据后，通过FOUND_ROWS()可以得到不带LIMIT的结果数：

SELECT FOUND_ROWS()

　　看上去，似乎SQL_CALC_FOUND_ROWS应该快于COUNT(*)，但实际情况并不是这样简单，请看：

　　To SQL_CALC_FOUND_ROWS or not to SQL_CALC_FOUND_ROWS?

　　用数据说话，证明了COUNT(*)相对SQL_CALC_FOUND_ROWS来说更快。不过我觉得这个结论也不全面，某些情况下，SQL_CALC_FOUND_ROWS更有优势，看我的实验：

　　表结构如下：

CREATETABLEIFNOTEXISTS `foo` (

`a` int(10) unsigned NOTNULL AUTO_INCREMENT,

`b` int(10) unsigned NOTNULL,

`c` varchar(100) NOTNULL,

PRIMARYKEY (`a`),

KEY `bar` (`b`,`a`)

) ENGINE=MyISAM;

　　导入一些测试数据：

for ($i =0; $i <10000; $i++) {

mysql_query("INSERT INTO foo SET b=ROUND(RAND()*10), c=MD5({$i})");

}

　　先测试COUNT(*)方式：

$start = microtime(true);

for ($i =0; $i <1000; $i++) {

mysql_query("SELECT SQL_NO_CACHE COUNT(*) FROM foo WHERE b = 1");

mysql_query("SELECT SQL_NO_CACHE a FROM foo WHERE b = 1 LIMIT 100, 10");

}

$end = microtime(true);

echo $end - $start;

　　结果输出（数据大小视测试机性能而定）：0.75777006149292

　　再测试SQL_CALC_FOUND_ROWS方式：

$start = microtime(true);

for ($i =0; $i <1000; $i++) {

mysql_query("SELECT SQL_NO_CACHE SQL_CALC_FOUND_ROWS a FROM foo WHERE b = 1 LIMIT 100, 10");

mysql_query("SELECT FOUND_ROWS()");

}

$end = microtime(true);

echo $end - $start;

　　结果输出（数据大小视测试机性能而定）：0.6681969165802

　　有数据有真相，那为什么我的实验结论和MySQL Performance Blog的结论相悖呢？这是因为在MySQL Performance Blog的实验里，COUNT(*)查询是执行的的Covering Index，而SQL_CALC_FOUND_ROWS是执行的表查询；而在我的实验里，因为我定义了适当的索引，COUNT(*)和SQL_CALC_FOUND_ROWS都是执行的Covering Index，所以结论出现了差异。

　　既然使用了Covering Index，就意味着不能再使用SELECT *的形式了，只能使用类似SELECT id这样的形式了，用的列在索引里都能查到，如此说来，我们需要的实际数据从哪来呢？这个很简单，有了主键之后，实际数据可以通过Key/Value形式的缓存获得，这样的架构很常见。

　　结论：SQL_CALC_FOUND_ROWS如果执行的是Covering Index的话，是很快的！换个角度看，如果COUNT(*)和SQL_CALC_FOUND_ROWS都只能通过表查询来检索，那么分页时，SQL_CALC_FOUND_ROWS同样会快于COUNT(*)，读者可自行测试。