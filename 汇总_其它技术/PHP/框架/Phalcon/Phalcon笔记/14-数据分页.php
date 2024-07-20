<?php

Phalcon\Paginator 提供了一个快捷，方便的方法对大组数据进行分割，以达到分页浏览的效果。

适配器:
NativeArray		Use a PHP array as source data
Model			Use a Phalcon\Model\Resultset object as source data
------------

在下面的例子中，paginator将从model中读取数据作为其数据源，并限制每页显示10条记录：

$currentPage = $this->request->getQuery('page', 'int'); // GET
// The data set to paginate
$robots = Robots::find();
// Create a Model paginator, show 10 rows by page starting from $currentPage
$paginator = new Phalcon\Paginator\Adapter\Model(
    array(
        "data" => $robots,
        "limit"=> 10,
        "page" => $currentPage
    )
);
// Get the paginated results
$page = $paginator->getPaginate();

视图中:
<?php foreach($page->items as $item) { ?>
<tr>
	<td><?php echo $item->id; ?></td>
	<td><?php echo $item->name; ?></td>
	<td><?php echo $item->type; ?></td>
</tr>
<?php } ?>
$page对象还包含以下数据：
<a href="/robots/search">First</a>
<a href="/robots/search?page=<?= $page->before; ?>">Previous</a>
<a href="/robots/search?page=<?= $page->next; ?>">Next</a>
<a href="/robots/search?page=<?= $page->last; ?>">Last</a>
<?php echo "You are in page ", $page->current, " of ", $page->total_pages; ?>