有了数据库连接实例，你可以使用 yii\db\Command 来执行SQL查询。

SELECT ¶

返回多行数据：

$command = $connection->createCommand('SELECT * FROM post');

$posts = $command->queryAll();

返回单行数据：

$command = $connection->createCommand('SELECT * FROM post WHERE id=1');

$post = $command->queryOne();

返回列数据：(一个字段的数组)

$command = $connection->createCommand('SELECT title FROM post');

$titles = $command->queryColumn();

返回计数：

$command = $connection->createCommand('SELECT COUNT(*) FROM post');

$postCount = $command->queryScalar();

更新、插入和删除等 ¶

对于非查询语句，你可以使用 yii\db\Command 的 execute 方法：

$command = $connection->createCommand('UPDATE post SET status=1 WHERE id=1');

$command->execute();

也可以使用下面的语法，会自动处理好表名和列名引用：

// INSERT

$connection->createCommand()->insert('user', [

    'name' => 'Sam',

    'age' => 30,

])->execute();



// INSERT multiple rows at once

$connection->createCommand()->batchInsert('user', ['name', 'age'], [

    ['Tom', 30],

    ['Jane', 20],

    ['Linda', 25],

])->execute();



// UPDATE

$connection->createCommand()->update('user', ['status' => 1], 'age > 30')->execute();



// DELETE

$connection->createCommand()->delete('user', 'status = 0')->execute();

+

Compiled by: 差点是帅哥

引用表名和列名 ¶

大多数情况下，你使用如下语法引用表名和列名：

$sql = "SELECT COUNT([[$column]]) FROM {{$table}}";

$rowCount = $connection->createCommand($sql)->queryScalar();

在上述代码中 [[$column]] 将被转换为合适的列名引用，而 {{$table}} 将被转换为表名引用。

对于表名，有一个特殊变量 {{%$table}} ，会自动为表名添加前缀（如果有的话）：

$sql = "SELECT COUNT([[$column]]) FROM {{%$table}}";

$rowCount = $connection->createCommand($sql)->queryScalar();

上述代码将会应用于 tbl_table ，如果你在配置文件中配置了如下的表前缀的话：

return [

    // ...

    'components' => [

        // ...

        'db' => [

            // ...

            'tablePrefix' => 'tbl_',

        ],

    ],

];

另外一个可选方法是使用 yii\db\Connection::quoteTableName() 和 yii\db\Connection::quoteColumnName() 方法来手动引用：

$column = $connection->quoteColumnName($column);

$table = $connection->quoteTableName($table);

$sql = "SELECT COUNT($column) FROM $table";

$rowCount = $connection->createCommand($sql)->queryScalar();

+

Compiled by: iefreer

预备声明Prepared statements ¶

为了安全传递查询参数，你可以使用预备声明（prepared statements），（译注：先声明参数，对用户输入进行escape后，进行参数替换，主要为了防止SQL注入）：

$command = $connection->createCommand('SELECT * FROM post WHERE id=:id');

$command->bindValue(':id', $_GET['id']);

$post = $command->query();

此外，使用预备声明还可以对查询命令进行复用，如下使用不同的参数查询只需要准备一次command：

$command = $connection->createCommand('DELETE FROM post WHERE id=:id');

$command->bindParam(':id', $id);



$id = 1;

$command->execute();



$id = 2;

$command->execute();

+

Compiled by: iefreer

事务 ¶

你可以向下面这样执行一个数据库事务（Transaction）：

$transaction = $connection->beginTransaction();

try {

    $connection->createCommand($sql1)->execute();

     $connection->createCommand($sql2)->execute();

    // ... executing other SQL statements ...

    $transaction->commit();

} catch(Exception $e) {

    $transaction->rollBack();

}

还可以嵌套事务：

// outer transaction

$transaction1 = $connection->beginTransaction();

try {

    $connection->createCommand($sql1)->execute();



    // inner transaction

    $transaction2 = $connection->beginTransaction();

    try {

        $connection->createCommand($sql2)->execute();

        $transaction2->commit();

    } catch (Exception $e) {

        $transaction2->rollBack();

    }



    $transaction1->commit();

} catch (Exception $e) {

    $transaction1->rollBack();

}

+

Compiled by: iefreer

使用数据库概要 ¶

获取 schema 信息 ¶

可以通过如下语句获取到一个 yii\db\Schema （数据库概要类）实例：

$schema = $connection->getSchema();

Shema包含一系列方法让你获取数据库各方面的信息，如获取所有表名：

$tables = $schema->getTableNames();

完整参考文档请查阅 yii\db\Schema.

修改 schema ¶

除了基本的SQL查询外，yii\db\Command 包含了一系列方法可以用来修改数据库schema：

- createTable, renameTable, dropTable, truncateTable

- addColumn, renameColumn, dropColumn, alterColumn

- addPrimaryKey, dropPrimaryKey

- addForeignKey, dropForeignKey

- createIndex, dropIndex

这些方法可使用如下：

// CREATE TABLE

$connection->createCommand()->createTable('post', [

    'id' => 'pk',

    'title' => 'string',

    'text' => 'text',

]);

完整参考文档请查阅 yii\db\Command.