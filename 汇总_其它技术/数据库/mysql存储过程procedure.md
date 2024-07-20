delimiter $$

create procedure add_course(a int) 

begin

while a > 0 do

	insert into course(name)VALUES(substring(MD5(RAND()),floor(RAND()*26)+1,7) );

set a = a-1;

end while;

end $$



创建后，执行下面调用函数，传入数字，就是执行的次数：

call add_course(100)





自行完成 貌似还要执行一遍分隔符还原

delimiter ;