参考 https://www.runoob.com/mongodb/mongodb-autoincrement-sequence.html





```javascript
> db.inc.insert({_id:1,num:0})
WriteResult({ "nInserted" : 1 })
> db.inc.find()
{ "_id" : 1, "num" : 0 }
```







```javascript
db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
```





```javascript
> db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
{ "_id" : 1, "num" : 0 }
> db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
{ "_id" : 1, "num" : 1 }
> db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
{ "_id" : 1, "num" : 2 }
> db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
{ "_id" : 1, "num" : 3 }
> db.inc.findAndModify({query:{_id:1},update:{$inc:{num:1}}})
{ "_id" : 1, "num" : 4 }
```





PHP7代码



```javascript
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$cmd = new \MongoDB\Driver\Command([
    'findAndModify' => 'inc',
    'query' => ['_id' => 1],
    'update' => ['$inc' => ['num' => 1]]
]);

$rows = $manager->executeCommand('test', $cmd);
foreach ($rows as $r) {
    print_r($r);
}
```



输出：

```javascript
stdClass Object
(
    [lastErrorObject] => stdClass Object
        (
            [n] => 1
            [updatedExisting] => 1
        )

    [value] => stdClass Object
        (
            [_id] => 1
            [num] => 6
        )

    [ok] => 1
)

```

