参考文档

https://packagist.org/packages/xethron/migrations-generator

https://github.com/Xethron/migrations-generator

<html>
Usage
To generate migrations from a database, you need to have your database setup in Laravel's Config.

Run php artisan migrate:generate to create migrations for all the tables, or you can specify the tables you wish to generate using php artisan migrate:generate table1,table2,table3,table4,table5. You can also ignore tables with --ignore="table3,table4,table5"

Laravel Migrations Generator will first generate all the tables, columns and indexes, and afterwards setup all the foreign key constraints. So make sure you include all the tables listed in the foreign keys so that they are present when the foreign keys are created.

You can also specify the connection name if you are not using your default connection with --connection="connection_name"

Run php artisan help migrate:generate for a list of options.
</html>


下面是执行过程

```
[ijisq-backend] php artisan migrate:generate                                                                                                                                                                             master  ✗ ✭ ✱
Using connection: mysql

Generating migrations for: log

 Do you want to log these migrations in the migrations table? [Y/n] :
 > Y

 Next Batch Number is: 1. We recommend using Batch Number 0 so that it becomes the "first" migration [Default: 0] :
 > 

Setting up Tables and Index Migrations
Created: /xxx/xxx/database/migrations/2019_06_04_174310_create_log_table.php

Setting up Foreign Key Migrations


Finished!

```