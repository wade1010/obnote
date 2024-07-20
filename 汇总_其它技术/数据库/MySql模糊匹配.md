MySQL中的模式匹配（标准SQL匹配和正则表达式匹配） 



1. 使用LIKE和NOT LIKE比较操作符(注意不能使用=或!=); 



2. 模式默认是忽略大小写的； 



3. 允许使用”_”匹配任何单个字符，”%”匹配任意数目字符(包括零字符)； 



MySQL还提供象UNIX实用程序的扩展正则表达式模式匹配的格式： 



1. 使用REGEXP和NOT REGEXP操作符(或RLIKE和NOT RLIKE，他们是同义词)； 



2. REGEXP模式匹配与被匹配字符的任何地方匹配，则匹配成功(即只要被匹配字符包含或者可以等于所定义的模式，就匹配成功)； 



不同于LIKE模式匹配，只有和整个值匹配，才匹配成功(即只有被匹配字符完全和所定义的模式匹配，才匹配成功)



3. REGEXP默认也是不区分大小写，可以使用BINARY关键词强制区分大小写； 



如：SELECT * FROM pet WHERE name REGEXP BINARY ‘^B’; 



4. 正则表达式为一个表达式，它能够描述一组字符串。REGEXP操作符完成MySQL的扩展正则表达式匹配。REGEXP实现的功能是如果被匹配字符中部分或完全符合所定义的表达式描述的字符，则表示匹配成功。 



1）最简单的正则表达式是不含任何特殊字符的正则表达式，如hello。 



SELECT * FROM pet WHERE name REGEXP ‘hello’;表示的意思是如果name这列的某一行包含hello这个单词，则匹配就成功了。（注意和LIKE的区别，LIKE要求name这列的某一行必须完全等于hello，才匹配成功）。 



2）非平凡的正则表达式，除了含有最简单表达式那些东西，还需要采用特殊的特殊结构，用到的字符，往下看。（因此，通常的正则表达式是普通单词和这些正则表达式字符构成的表达式） 



5. 扩展正则表达式的一些字符： 



1) ‘.’匹配任何单个字符； 



2) […]匹配在方括号内的任何字符，可以使用’-’表示范围，如[a-z],[0-9]，而且可以混合[a-dXYZ]表示匹配a,b,c,d,X,Y,Z中的任何一个；(注意使用括号以及’|’的方法也可以达到相同的效果，如(a|b|c)匹配a,b,c中的任何一个)；此外可以使用’^’表示否定，如[^a-z]表示不含有a-z中间的任何一个字符； 



3) ‘*’表示匹配0个或多个在它前面的字符。如x*表示0个或多个x字符，.*表示匹配任何数量的任何字符； 



4) 可以将模式定位必须匹配被匹配字符的开始或结尾，在匹配模式前加”^”：表示匹配从被匹配字符的最开头开始，在匹配模式后加”$”：表示匹配要进行到被匹配字符的最末尾。 



5) ‘+’表示匹配1个或多个在它前面的字符。如a+表示1个或多个a字符。 



6) ‘?’表示匹配0个或1个在它前面的字符。如a?表示0个或1个a字符。 



7) ‘|’如de|abc表示匹配序列de或者abc。注意虽然[…]也可以表示匹配中的某一个，但是每次仅仅能表示单个字符及[a-bXYZ]实际每一次只代表了一个字符。 



8) ()括号可以应用在表达式中，使得更容易理解。 



9) a{5}表示匹配共5个a，a{2,8}表示匹配2～8个a。 



a*可以写成a{0, } 第二个参数省略表示没有上界；a+可以写成a{1,}；a?可以写成a{0,1} 



更准确地讲，a{n}与a的n个实例准确匹配。a{n,}匹配a的n个或更多实例。a{m,n}匹配a的m～n个实例，包含m和n 

m和n必须位于0～RE_DUP_MAX（默认为255）的范围内，包含0和RE_DUP_MAX。如果同时给定了m和n，m必须小于或等于n。 

<!--[if !supportLineBreakNewLine]--> 

<!--[endif]--> 



10) 标准类别[:character_class:]： 



常用的一些标准类别，一般在[]中使用，由于用在[]中故和[a-z]类似，每一次只能顶替一个字符。（这个有点类似perl里面定义的常用的一些标准类别：\w表示一个单词字符即[a-zA-Z0-9];\W一个非单词字符与\w相反; \d一个数字即[0-9];\D一个非数字;\s一个白空间字符即[\t\f\r\n];\f为换页符;\S一个非白空间字符） 



标准的类别名称： 



alnum 

文字数字字符 



alpha 

文字字符 



blank 

空白字符 



cntrl 

控制字符 



digit 

数字字符 



graph 

图形字符 



lower 

小写文字字符 



print 

图形或空格字符 



punct 

标点字符 



space 

空格、制表符、新行、和回车 



upper 

大写文字字符 



xdigit 

十六进制数字字符 



使用实例： 



SELECT 'justalnums' REGEXP '[[:alnum:]]+';解释其中[[:alnum:]]由于[:alnum:]表示文字数字字符，它又用在[]中，故[[:alnum:]]代表一个字符它为一个文字或者数字。后面的+号表示1个或多个这样的文字或数字。 



上述语句返回1.那是因为justalnums中是由字母组成的。 



11）字边界：[[:<:]]表示开始，[[:>:]]表示结束： 



其定义了一个单词的开始和结束边界，这个单词为字字符，这样[[:<:]]代表这个字字符前面的部分，[[:>:]]代表这个字字符后面的部分。字字符为alnum类的字母数字字符或下划线(_)；因此[[:<:]], [[:>:]]均代表不是字字符的字符，即只要不是字母数字字符以及下划线(_)即可。因此其可以为什么都不是。因此[[:<:]]word[[:>:]]能够匹配如下的所有情况： 



即word单词本身，word*** 解释***代表不是字母数字以及_的任何字符(如,word-net)；***word(如,micorsoft word)；***word***(如，this is a word program.) 



举例：[[:<:]]word[[:>:]]: 



SELECT 'a word a' REGEXP '[[:<:]]word[[:>:]]'; 结果为真SELECT 'a xword a' REGEXP '[[:<:]]word[[:>:]]'; 结果为假 最后注意的注意：要在正则表达式中使用特殊字符，需要在这些字符前面添加2个反斜杠’\’，举例：SELECT '1+2' REGEXP '1+2'; 结果为0SELECT '1+2' REGEXP '1\+2'; 结果为0SELECT '1+2' REGEXP '1\\+2'; 结果为1解释：这是因为MySQL解析程序解析该SQL语句时：首先将字符串’1\\+2’解析为1\+2；然后把1\+2当作正则表达式，由正则表达式库来解析，它代表1+2。因此需要加上2个反斜杠。 不要经常犯加一个反斜杠的错误，加一个反斜杠会莫名其妙：如SELECT '1t2' REGEXP '1\t2';结果会返回1本来的意思是匹配1制表符\t以及2，但是由于只添加了一个\所以，解析以后编程了1t2，所以匹配成功。12)[.characters.]和[=character_class=] 参考资料： 



http://dev.mysql.com/doc/refman/5.1/zh/tutorial.html#pattern-matching MySQL的模式匹配 



http://dev.mysql.com/doc/refman/5.1/zh/regexp.html MySQL的正则表达式匹配 



=========================================================================================================



正则表达式： 

    正则表达式是为复杂搜索指定模式的强大方式。 

^ 

所匹配的字符串以后面的字符串开头 

mysql> select "fonfo" REGEXP "^fo$"; -> 0（表示不匹配） 

mysql> select "fofo" REGEXP "^fo"; -> 1（表示匹配） 

$ 

所匹配的字符串以前面的字符串结尾 

mysql> select "fono" REGEXP "^fono$"; -> 1（表示匹配） 

mysql> select "fono" REGEXP "^fo$"; -> 0（表示不匹配） 

. 

匹配任何字符（包括新行） 

mysql> select "fofo" REGEXP "^f.*"; -> 1（表示匹配） 

mysql> select "fonfo" REGEXP "^f.*"; -> 1（表示匹配） 

a* 

匹配任意多个a（包括空串) 

mysql> select "Ban" REGEXP "^Ba*n"; -> 1（表示匹配） 

mysql> select "Baaan" REGEXP "^Ba*n"; -> 1（表示匹配） 

mysql> select "Bn" REGEXP "^Ba*n"; -> 1（表示匹配） 



a+ 

匹配1个或多个a字符的任何序列。 



mysql> select "Ban" REGEXP "^Ba+n"; -> 1（表示匹配） 

mysql> select "Bn" REGEXP "^Ba+n"; -> 0（表示不匹配） 



a? 

匹配一个或零个a 

mysql> select "Bn" REGEXP "^Ba?n"; -> 1（表示匹配） 

mysql> select "Ban" REGEXP "^Ba?n"; -> 1（表示匹配） 

mysql> select "Baan" REGEXP "^Ba?n"; -> 0（表示不匹配） 



de|abc 

匹配de或abc 

mysql> select "pi" REGEXP "pi|apa"; -> 1（表示匹配） 

mysql> select "axe" REGEXP "pi|apa"; -> 0（表示不匹配） 

mysql> select "apa" REGEXP "pi|apa"; -> 1（表示匹配） 

mysql> select "apa" REGEXP "^(pi|apa)$"; -> 1（表示匹配） 

mysql> select "pi" REGEXP "^(pi|apa)$"; -> 1（表示匹配） 

mysql> select "pix" REGEXP "^(pi|apa)$"; -> 0（表示不匹配） 



(abc)* 

匹配任意多个abc（包括空串) 

mysql> select "pi" REGEXP "^(pi)*$"; -> 1（表示匹配） 

mysql> select "pip" REGEXP "^(pi)*$"; -> 0（表示不匹配） 

mysql> select "pipi" REGEXP "^(pi)*$"; -> 1（表示匹配） 



{1} {2,3} 

这是一个更全面的方法，它可以实现前面好几种保留字的功能 

a* 

可以写成a{0,} 

a 

可以写成a{1,} 

a? 

可以写成a{0,1} 

在{}内只有一个整型参数i，表示字符只能出现i次；在{}内有一个整型参数i， 

后面跟一个“，”，表示字符可以出现i次或i次以上；在{}内只有一个整型参数i， 

后面跟一个“，”，再跟一个整型参数j,表示字符只能出现i次以上，j次以下 

（包括i次和j次）。其中的整型参数必须大于等于0，小于等于 RE_DUP_MAX（默认是25 

5）。 如果同时给定了m和n，m必须小于或等于n. 



[a-dX], [^a-dX] 



匹配任何是（或不是，如果使用^的话）a、b、c、d或X的字符。两个其他字符之间的“-”字符构成一个范围，与从第1个字符开始到第2个字符之间的所有字符匹配。例如，[0-9]匹配任何十进制数字 。要想包含文字字符“]”，它必须紧跟在开括号“[”之后。要想包含文字字符“-”，它必须首先或最后写入。对于[]对内未定义任何特殊含义的任何字符，仅与其本身匹配。 



mysql> select "aXbc" REGEXP "[a-dXYZ]"; -> 1（表示匹配） 

mysql> select "aXbc" REGEXP "^[a-dXYZ]$"; -> 0（表示不匹配） 

mysql> select "aXbc" REGEXP "^[a-dXYZ] $"; -> 1（表示匹配） 

mysql> select "aXbc" REGEXP "^[^a-dXYZ] $"; -> 0（表示不匹配） 

mysql> select "gheis" REGEXP "^[^a-dXYZ] $"; -> 1（表示匹配） 

mysql> select "gheisa" REGEXP "^[^a-dXYZ] $"; -> 0（表示不匹配） 



[[.characters.]] 

表示比较元素的顺序。在括号内的字符顺序是唯一的。但是括号中可以包含通配符, 

所以他能匹配更多的字符。举例来说：正则表达式[[.ch.]]*c匹配chchcc的前五个字符 

。 



[=character_class=] 

表示相等的类，可以代替类中其他相等的元素，包括它自己。例如，如果o和( )是 

一个相等的类的成员，那么[[=o=]]、[[=( )=]]和[o( )]是完全等价的。 



[:character_class:] 

在括号里面，在[:和:]中间是字符类的名字，可以代表属于这个类的所有字符。 

字符类的名字有: alnum、digit、punct、alpha、graph、space、blank、lower、uppe 

r、cntrl、print和xdigit 

mysql> select "justalnums" REGEXP "[[:alnum:]] "; -> 1（表示匹配） 

mysql> select "!!" REGEXP "[[:alnum:]] "; -> 0（表示不匹配） 



[[:<:]] 

[[:>:]] 

分别匹配一个单词开头和结尾的空的字符串，这个单词开头和结尾都不是包含在alnum中 

的字符也不能是下划线。 

mysql> select "a word a" REGEXP "[[:<:]]word[[:>:]]"; -> 1（表示匹配） 

mysql> select "a xword a" REGEXP "[[:<:]]word[[:>:]]"; -> 0（表示不匹配） 

mysql> select "weeknights" REGEXP "^(wee|week)(knights|nights)$"; -> 1（表示 

匹配） 



要想在正则表达式中使用特殊字符的文字实例，应在其前面加上2个反斜杠“\”字符。MySQL解析程序负责解释其中一个，正则表达式库负责解释另一个。例如，要想与包含特殊字符“+”的字符串“1+2”匹配，在下面的正则表达式中，只有最后一个是正确的： 



mysql> SELECT '1+2' REGEXP '1+2';                       -> 0 

mysql> SELECT '1+2' REGEXP '1\+2';                      -> 0 

mysql> SELECT '1+2' REGEXP '1\\+2';                     -> 1 



全文检索： 

====================================================================================================

在括号里面，在[:和:]中间是字符类的名字，可以代表属于这个类的所有字符。字符类的名字有: alnum、digit、punct、alpha、graph、space、blank、lower、upper、cntrl、print和xdigit 



mysql> select "justalnums" REGEXP "[[:alnum:]]+"; -> 1（表示匹配） 



mysql> select "!!" REGEXP "[[:alnum:]]+"; -> 0（表示不匹配） 



[[:<:]] 



[[:>:]] 



分别匹配一个单词开头和结尾的空的字符串，这个单词开头和结尾都不是包含在alnum中的字符也不能是下划线。 



mysql> select "a word a" REGEXP "[[:<:]]word[[:>:]]"; -> 1（表示匹配） 



mysql> select "a xword a" REGEXP "[[:<:]]word[[:>:]]"; -> 0（表示不匹配） 



mysql> select "weeknights" REGEXP "^(weeweek)(knightsnights)$"; -> 1（表示匹配）