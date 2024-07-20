<?php

创建PHQL查询:
PHQL查询可以通过实例化 Phalcon\Mvc\Model\Query 来创建：
// Instantiate the Query
$query = new Phalcon\Mvc\Model\Query("SELECT * FROM Cars");
// Pass the DI container
$query->setDI($di);
// Execute the query returning a result if any
$robots = $query->execute();

在控制器或视图文件中，它可以使用服务容器中的一个注入服务 models manager 来轻松的实现create/execute
$query = $this->modelsManager->createQuery("SELECT * FROM Cars");
$robots = $query->execute();
-----------

选择记录
$query = $manager->createQuery("SELECT * FROM Cars ORDER BY Cars.name");
$query = $manager->createQuery("SELECT Cars.name FROM Cars ORDER BY Cars.name");

带有命名空间的模型类同样可以：
$phql = "SELECT * FROM Formula\Cars ORDER BY Formula\Cars.name";
$query = $manager->createQuery($phql);

使用PHQL可以很方便的通过多个模型来获取数据，Phalcon支持大多数类型的Joins。我们在模型中定义的关系，在使用PHQL时会自动的添加到条件上：
$phql  = "SELECT Cars.name AS car_name, Brands.name AS brand_name FROM Cars JOIN Brands";
$rows = $manager->executeQuery($phql);
foreach ($rows as $row) {
    echo $row->car_name, "\n";
    echo $row->brand_name, "\n";
}

下面的示例将展示如何在PHQL中使用聚合：
$phql = "SELECT MAX(price) AS maximum, MIN(price) AS minimum FROM Cars";
$rows = $manager->executeQuery($phql);
foreach ($rows as $row) {
    echo $row["maximum"], ' ', $row["minimum"], "\n";
}
--------------

插入数据
// Inserting without columns
$phql = "INSERT INTO Cars VALUES (NULL, 'Lamborghini Espada', "
      . "7, 10000.00, 1969, 'Grand Tourer')";
$manager->executeQuery($phql);
// Specifyng columns to insert
$phql = "INSERT INTO Cars (name, brand_id, year, style) "
      . "VALUES ('Lamborghini Espada', 7, 1969, 'Grand Tourer')";
$manager->executeQuery($phql);
// Inserting using placeholders
$phql = "INSERT INTO Cars (name, brand_id, year, style) "
      . "VALUES (:name:, :brand_id:, :year:, :style)";
$manager->executeQuery($sql,
    array(
        'name'     => 'Lamborghini Espada',
        'brand_id' => 7,
        'year'     => 1969,
        'style'    => 'Grand Tourer',
    )
);
----------

更新数据(Updating Data)
// Updating a single column
$phql = "UPDATE Cars SET price = 15000.00 WHERE id = 101";
$manager->executeQuery($phql);
// Updating multiples columns
$phql = "UPDATE Cars SET price = 15000.00, type = 'Sedan' WHERE id = 101";
$manager->executeQuery($phql);
// Updating multiples rows
$phql = "UPDATE Cars SET price = 7000.00, type = 'Sedan' WHERE brands_id > 5";
$manager->executeQuery($phql);
// Using placeholders
$phql = "UPDATE Cars SET price = ?0, type = ?1 WHERE brands_id > ?2";
$manager->executeQuery(
    $phql,
    array(
        0 => 7000.00,
        1 => 'Sedan',
        2 => 5
    )
);
-------------

删除数据(Deleting Data)
// Deleting a single row
$phql = "DELETE FROM Cars WHERE id = 101";
$manager->executeQuery($phql);
--------------

使用Query Builder

$manager->createBuilder()
    >join('RobotsParts');
    ->limit(20);
    ->order('Robots.name')
    ->getQuery()
    ->execute();
	
$builder->from('Robots')
// 'SELECT Robots.* FROM Robots'
// 'SELECT Robots.*, RobotsParts.* FROM Robots, RobotsParts'
$builder->from(array('Robots', 'RobotsParts'))
// 'SELECT * FROM Robots'
$phql = $builder->columns('*')
                ->from('Robots')
// 'SELECT id, name FROM Robots'
$builder->columns(array('id', 'name'))
        ->from('Robots')
// 'SELECT id, name FROM Robots'
$builder->columns('id, name')
        ->from('Robots')
// 'SELECT Robots.* FROM Robots WHERE Robots.name = "Voltron"'
$builder->from('Robots')
        ->where('Robots.name = "Voltron"')
// 'SELECT Robots.* FROM Robots WHERE Robots.id = 100'
$builder->from('Robots')
        ->where(100)
// 'SELECT Robots.* FROM Robots GROUP BY Robots.name'
$builder->from('Robots')
        ->groupBy('Robots.name')
// 'SELECT Robots.* FROM Robots GROUP BY Robots.name, Robots.id'
$builder->from('Robots')
        ->groupBy(array('Robots.name', 'Robots.id'))
// 'SELECT Robots.name, SUM(Robots.price) FROM Robots GROUP BY Robots.name'
$builder->columns(array('Robots.name', 'SUM(Robots.price)'))
    ->from('Robots')
    ->groupBy('Robots.name')
// 'SELECT Robots.name, SUM(Robots.price) FROM Robots
// GROUP BY Robots.name HAVING SUM(Robots.price) > 1000'
$builder->columns(array('Robots.name', 'SUM(Robots.price)'))
    ->from('Robots')
    ->groupBy('Robots.name')
    ->having('SUM(Robots.price) > 1000')
// 'SELECT Robots.* FROM Robots JOIN RobotsParts');
$builder->from('Robots')
    ->join('RobotsParts')
// 'SELECT Robots.* FROM Robots JOIN RobotsParts AS p');
$builder->from('Robots')
    ->join('RobotsParts', null, 'p')
// 'SELECT Robots.* FROM Robots JOIN RobotsParts ON Robots.id = RobotsParts.robots_id AS p');
$builder->from('Robots')
    ->join('RobotsParts', 'Robots.id = RobotsParts.robots_id', 'p')
// 'SELECT Robots.* FROM Robots
// JOIN RobotsParts ON Robots.id = RobotsParts.robots_id AS p
// JOIN Parts ON Parts.id = RobotsParts.parts_id AS t'
$builder->from('Robots')
    ->join('RobotsParts', 'Robots.id = RobotsParts.robots_id', 'p')
    ->join('Parts', 'Parts.id = RobotsParts.parts_id', 't')
// 'SELECT r.* FROM Robots AS r'
$builder->addFrom('Robots', 'r')
// 'SELECT Robots.*, p.* FROM Robots, Parts AS p'
$builder->from('Robots')
    ->addFrom('Parts', 'p')
// 'SELECT r.*, p.* FROM Robots AS r, Parts AS p'
$builder->from(array('r' => 'Robots'))
        ->addFrom('Parts', 'p')
// 'SELECT r.*, p.* FROM Robots AS r, Parts AS p');
$builder->from(array('r' => 'Robots', 'p' => 'Parts'))
// 'SELECT Robots.* FROM Robots LIMIT 10'
$builder->from('Robots')
    ->limit(10)
// 'SELECT Robots.* FROM Robots LIMIT 10 OFFSET 5'
$builder->from('Robots')
        ->limit(10, 5)