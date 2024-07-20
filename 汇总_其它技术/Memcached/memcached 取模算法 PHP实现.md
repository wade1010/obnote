

php版本 php7.3

```javascript
<?php

class Moder
{
    protected $nodes = [];
    protected $cnt = 0;

    public function _hash($str)
    {
        return sprintf('%u', crc32($str));
    }

    public function _lookup($key)
    {
        return $this->nodes[$this->_hash($key) % $this->cnt];
    }

    public function addNode($node)
    {
        if (in_array($node, $this->nodes)) {
            return false;
        }
        $this->nodes[] = $node;
        $this->cnt++;
        return true;
    }

    public function deleteNode($node)
    {
        if (!in_array($node, $this->nodes)) {
            return false;
        }
        unset($this->nodes[array_search($node, $this->nodes)]);
        $this->cnt--;
        return true;
    }
}

$moder = new Moder();
$moder->addNode('a');
$moder->addNode('b');
$moder->addNode('c');
$key = 'nfdsaffsods';
echo $key, ',此 key 落在', $moder->_lookup($key), '号节点';
```

