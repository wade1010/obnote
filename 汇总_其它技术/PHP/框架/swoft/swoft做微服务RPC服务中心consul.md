一、搭建consul集群

可以参考 http://note.youdao.com/s/85W25KQr  这是用docker搭建的集群

其中一个consul client 为127.0.0.1:8511    下面需要用到



二、服务提供者 项目 swoft1   (我自己命名的项目)



修改1

app/bean.php

```javascript
<?php

use Swoft\Rpc\Server\ServiceServer;

return [
    'rpcServer' => [
        'class' => ServiceServer::class,
        'host' => '192.168.1.10',
        'port' => 8307,
    ],
    'consul' => [
        'port' => 8511
    ]
];
```



其他的配置看需要添加，这里两个选项要配置，rpcServer也可以去掉（有默认值），但是去掉就不直观了



修改2

Listener/RegisterServiceListener.php   去掉注释 并引入CLog包

```javascript
public function handle(EventInterface $event): void
{
    /** @var HttpServer $httpServer */
    $httpServer = $event->getTarget();

    $service = [
        'ID' => 'swoft',
        'Name' => 'swoft',
        'Tags' => [
            'http'
        ],
        'Address' => $httpServer->getHost(),
        'Port' => $httpServer->getPort(),
        'Meta' => [
            'version' => '1.0'
        ],
        'EnableTagOverride' => false,
        'Weights' => [
            'Passing' => 10,
            'Warning' => 1
        ]
    ];


    // Register
    $this->agent->registerService($service);
    CLog::info('Swoft http register service success by consul!');
}
```





修改3

Listener/DeregisterServiceListener.php   去掉注释，这个是使用命令行关闭swoft服务的时候自动反注册服务



```javascript
public function handle(EventInterface $event): void
{
    /** @var HttpServer $httpServer */
    $httpServer = $event->getTarget();

    $this->agent->deregisterService('swoft');
}
```



然后就可以启动服务端了



```javascript
php ./bin/swoft rpc:start
```





三、服务使用者  项目swoft2   (我自己命名的项目)

修改1

app/Common/RpcProvider.php



```javascript
class RpcProvider implements ProviderInterface
{
    /**
     * @Inject()
     *
     * @var Health
     */
    private $health;

    public function getList(Client $client): array
    {
        $serviceName = $client->getSetting()['consul_service_name'] ?? '';
        $services = $this->health->service($serviceName, ['passing' => true]);
        $services = $services->getResult();
        $address = [];
        foreach ($services as $k => $v) {
            $address[] = $v['Service']['Address'] . ":" . $v['Service']['Port'];
        }
        return array_unique($address);
    }
}
```



修改2

app/bean.php   其他的配置看需要添加

```javascript
return [
    'httpServer' => [
        'class' => HttpServer::class,
        'port' => 18308,
    ],
    'user' => [
        'class' => ServiceClient::class,
        'setting' => [
            'consul_service_name' => 'swoft',//consulservice里面筛选出自己需要的
            'timeout' => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout' => 10.0,
            'read_timeout' => 0.5,
        ],
        'packet' => bean('rpcClientPacket'),
        'provider' => bean(RpcProvider::class)
    ],
    'user.pool' => [
        'class' => ServicePool::class,
        'client' => bean('user'),
    ],
    'consul' => [
        'port' => 8511
    ]
];
```

 

然后就可以启动调用端了





php ./bin/swoft rpc:start



```javascript
php ./bin/swoft http:start
```





然后再浏览器输入



http://127.0.0.1:18308/rpc/getList



就可以通过调用端获取consul服务里面有效的服务地址来调用提供端的接口