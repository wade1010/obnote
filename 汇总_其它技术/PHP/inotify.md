

```javascript
<?php
$init=inotify_init(); //初始化

include 'index.php';
$files=get_included_files();

foreach ($files as $file){
    inotify_add_watch($init,$file,IN_MODIFY); //监视相关的文件
}

//监听
swoole_event_add($init,function ($fd){
    $events=inotify_read($fd);
    if(!empty($events)){

    }
});


```

