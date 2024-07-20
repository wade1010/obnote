假设 虚拟节点是64个。



php版本 php7.3



```javascript
<?php

class consistentHashing
{
    protected $num = 64;
    protected $position = [];
    protected $nodes = [];

    public function _hash($str)
    {
        return sprintf('%u', crc32($str));
    }

    public function _lookup($key)
    {
        $keyHash = $this->_hash($key);
        $node = current($this->position);
        foreach ($this->position as $k => $v) {
            if ($keyHash <= $k) {
                $node = $v;
                break;
            }
        }
        return $node;
    }

    public function addNode($node)
    {
        if (in_array($node, $this->nodes)) {
            return;
        }
        for ($i = 0; $i < $this->num; $i++) {
            $position = $this->_hash($node . "_" . $i);
            $this->position[$position] = $node;
            $this->nodes[$node][] = $position;
        }
        ksort($this->position);
    }

    public function deleteNode($node)
    {
        if (!in_array($node, $this->nodes)) {
            return;
        }
        foreach ($this->nodes[$node] as $position) {
            unset($this->position[$position]);
        }
        unset($this->nodes[$node]);
    }
}

$hc = new consistentHashing();
$hc->addNode('a');
$hc->addNode('b');
$hc->addNode('c');
$key = 'nihaods';
$hc->_lookup($key);
echo $key, ',此 key 落在', $hc->_lookup($key), '号节点';
```

