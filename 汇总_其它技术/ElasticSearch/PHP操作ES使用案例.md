
```
<?php
class search
{
    public function __construct()
    {
        $this->esUri = $host . ':' . $port . "/" . $index . "/" . $type. "/";
    }

    //demo
    public function demo()
    {
        $conditions = [
            ['field' => 'company', 'type' => 'like', 'value' => '春风'],
            ['field' => 'groupid', 'type' => 'between', 'value' => [4, 6]],
            ['field' => 'email', 'type' => '=', 'value' => 'jiaogun@139.com'],
        ];
        $page    = 1;
        $limit   = 20;
        $offset  = ($page > 0) ? ($page - 1) * $limit : 0;
        $columns = ['username', 'userid', 'groupid', 'company'];
        $order   = array('field' => 'userid', 'sort' => 'desc');
        $res     = $this->search($conditions, $offset, $limit, $columns, $order);
        var_dump($res);die();

    }

    //es搜索
    public function search($conditions = array(), $offset = 0, $limit = 20, $columns = array(), $order = array('field' => 'userid', 'sort' => 'desc'))
    {
        $params = [
            "query"   => [
                "filtered" => [
                    'filter' => [
                        'bool' => [
                            'must' => [
                            ],
                        ],
                    ],
                    "query"  => [
                        'bool' => [
                            'must' => [

                            ],
                        ],
                    ],
                ],
            ],
            'sort'    => [
                $order['field'] => [
                    'order' => $order['sort'],
                ],
            ],
            '_source' => $columns,
            'from'    => $offset,
            'size'    => $limit,
        ];
        if (!empty($conditions)) {
            foreach ($conditions as $v) {
                switch ($v['type']) {
                    case 'between':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'][0];
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'][1];
                        break;
                    case '>':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'];
                        break;
                    case '>=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gte'] = $v['value'];
                        break;
                    case '<':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'];
                        break;
                    case '<=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lte'] = $v['value'];
                        break;
                    case '=':
                        $params['query']['filtered']['filter']['bool']['must'][]['term'][$v['field']] = $v['value'];
                        break;
                    case '!=':
                        $params['query']['filtered']['filter']['bool']['must_not'][]['term'][$v['field']] = $v['value'];
                        break;
                    case 'in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'not in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must_not'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'like':
                        $params['query']['filtered']['query']['bool']['must'][]['match'][$v['field']] = $v['value'];
                        unset($params['sort']);
                        $params['sort']['_score']['order'] = 'desc';
                        $params['sort']['userid']['order'] = 'desc';
                        break;
                    default:
                        return false;
                        break;
                }
            }
        }
        $res            = $this->getEs($params, true, $this->esUri . '_search/');
        $res            = json_decode($res, true);
        $reData['data'] = array();
        if (!empty($res['hits']['hits'])) {
            foreach ($res['hits']['hits'] as $k => $v) {
                $reData['data'][$k] = $v;
            }
        }
        $reData['count'] = !empty($res['hits']['total']) ? $res['hits']['total'] : 0;
        return $reData;
    }

    //es搜索 解决深度分页问题 按照userid asc排序
    public function searchAll($conditions = array(), $limit = 50, $columns = array(), $userid = 0)
    {
        $params = [
            "query"   => [
                "filtered" => [
                    'filter' => [
                        'bool' => [
                            'must' => [

                            ],
                        ],
                    ],
                    "query"  => [
                        'bool' => [
                            'must' => [
                                [
                                    'range' => [
                                        'userid' => [
                                            'gt' => $userid,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'sort'    => [
                'userid' => [
                    'order' => 'asc',
                ],
            ],
            '_source' => $columns,
            'size'    => $limit,
        ];
        if (!empty($conditions)) {
            foreach ($conditions as $v) {
                switch ($v['type']) {
                    case 'between':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'][0];
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'][1];
                        break;
                    case '>':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'];
                        break;
                    case '>=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gte'] = $v['value'];
                        break;
                    case '<':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'];
                        break;
                    case '<=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lte'] = $v['value'];
                        break;
                    case '=':
                        $params['query']['filtered']['filter']['bool']['must'][]['term'][$v['field']] = $v['value'];
                        break;
                    case '!=':
                        $params['query']['filtered']['filter']['bool']['must_not'][]['term'][$v['field']] = $v['value'];
                        break;
                    case 'in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'not in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must_not'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'like':
                        $params['query']['filtered']['query']['bool']['must'][]['match'][$v['field']] = $v['value'];
                        break;
                    default:
                        return false;
                        break;
                }
            }
        }
        $res            = $this->getEs($params, true, $this->esUri . '_search/');
        $res            = json_decode($res, true);
        $reData['data'] = array();
        if (!empty($res['hits']['hits'])) {
            foreach ($res['hits']['hits'] as $k => $v) {
                $reData['data'][$k] = $v;
            }
        }
        $reData['count'] = !empty($res['hits']['total']) ? $res['hits']['total'] : 0;
        return $reData;
    }

    //es统计数量
    public function count($conditions = array())
    {
        $params = [
            "query" => [
                "filtered" => [
                    'filter' => [
                        'bool' => [
                            'must' => [
                            ],
                        ],
                    ],
                    "query"  => [
                        'bool' => [
                            'must' => [

                            ],
                        ],
                    ],
                ],
            ],
        ];
        if (!empty($conditions)) {
            foreach ($conditions as $v) {
                switch ($v['type']) {
                    case 'between':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'][0];
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'][1];
                        break;
                    case '>':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gt'] = $v['value'];
                        break;
                    case '>=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['gte'] = $v['value'];
                        break;
                    case '<':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lt'] = $v['value'];
                        break;
                    case '<=':
                        $params['query']['filtered']['filter']['bool']['must'][]['range'][$v['field']]['lte'] = $v['value'];
                        break;
                    case '=':
                        $params['query']['filtered']['filter']['bool']['must'][]['term'][$v['field']] = $v['value'];
                        break;
                    case '!=':
                        $params['query']['filtered']['filter']['bool']['must_not'][]['term'][$v['field']] = $v['value'];
                        break;
                    case 'in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'not in':
                        if (!empty($v['value']) && is_array($v['value'])) {
                            foreach ($v['value'] as $m => $n) {
                                $params['query']['filtered']['filter']['bool']['must_not'][] = array(
                                    'term' => array(
                                        $v['field'] => $n,
                                    ),
                                );
                            }
                        }
                        break;
                    case 'like':
                        $params['query']['filtered']['query']['bool']['must'][]['match'][$v['field']] = $v['value'];
                        break;
                    default:
                        return false;
                        break;
                }
            }
        }
        $res   = $this->getEs($params, true, $this->esUri . '_count/');
        $res   = json_decode($res, true);
        $count = !empty($res['count']) ? $res['count'] : 0;
        return $count;
    }
    private function getEs($data = [], $ispost = true, $uri = "es地址")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode != 200) {
            var_dump($result);
            return false;
        } else {
            return $result;
        }
    }
}
```
