有关RocketMQ实现分布式事务前面写了一篇博客

1、RocketMQ实现分布式事务原理

下面就这个项目做个整体简单介绍，并在文字最下方附上项目Github地址。

一、项目概述

1、技术架构

项目总体技术选型

SpringCloud(Finchley.RELEASE) + SpringBoot2.0.4 + Maven3.5.4 + RocketMQ4.3 +MySQL + lombok(插件)


有关SpringCloud主要用到以下四个组建

Eureka Server +config-server(配置中心)+ Eureka Client + Feign(服务间调用) 


配置中心是用MySQL存储数据。

2、项目整体结构

config-service  # 配置中心
eureka          # 注册中心
service-order   #订单微服务
service-produce #商品微服务


各服务的启动顺序就安装上面的顺序启动。

大致流程

启动后，配置中心、订单微服务、商品微服务都会将信息注册到注册中心。

如果访问：localhost:7001(注册中心地址）,以上服务都出现说明启动成功。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027482.jpg)

3、分布式服务流程

用户在订单微服务下单后，会去回调商品微服务去减库存。这个过程需要事务的一致性。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027025.jpg)

4、测试流程

页面输入：

http://localhost:9001/api/v1/order/save?userId=1&productId=1&total=4	


订单微服务执行情况（订单服务事务执行成功）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027429.jpg)

商品微服务执行情况（商品服务事务执行成功）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027755.jpg)

当然你也可以通过修改参数来模拟分布式事务出现的各种情况。



二、MQ中生产者核心代码

这里展示下，生产者发送消息核心代码。

@Slf4j
@Component
public class TransactionProducer {

    /**
     * 需要自定义事务监听器 用于 事务的二次确认 和 事务回查
     */
    private TransactionListener transactionListener ;
    /**
     * 这里的生产者和之前的不一样
     */
    private TransactionMQProducer producer = null;
    /**
     * 官方建议自定义线程 给线程取自定义名称 发现问题更好排查
     */
    private ExecutorService executorService = new ThreadPoolExecutor(2, 5, 100, TimeUnit.SECONDS,
            new ArrayBlockingQueue<Runnable>(2000), new ThreadFactory() {
        @Override
        public Thread newThread(Runnable r) {
            Thread thread = new Thread(r);
            thread.setName("client-transaction-msg-check-thread");
            return thread;
        }
    });

    public TransactionProducer(@Autowired Jms jms, @Autowired ProduceOrderService produceOrderService) {
        transactionListener = new TransactionListenerImpl(produceOrderService);
        // 初始化 事务生产者
        producer = new TransactionMQProducer(jms.getOrderTopic());
        // 添加服务器地址
        producer.setNamesrvAddr(jms.getNameServer());
        // 添加事务监听器
        producer.setTransactionListener(transactionListener);
        // 添加自定义线程池
        producer.setExecutorService(executorService);

        start();
    }

    public TransactionMQProducer getProducer() {
        return this.producer;
    }

    /**
     * 对象在使用之前必须要调用一次，只能初始化一次
     */
    public void start() {
        try {
            this.producer.start();
        } catch (MQClientException e) {
            e.printStackTrace();
        }
    }

    /**
     * 一般在应用上下文，使用上下文监听器，进行关闭
     */
    public void shutdown() {
        this.producer.shutdown();
    }
}

/**
 * @author xub
 * @Description: 自定义事务监听器
 * @date 2019/7/15 下午12:20
 */
@Slf4j
class TransactionListenerImpl implements TransactionListener {

    @Autowired
    private ProduceOrderService produceOrderService ;

    public TransactionListenerImpl( ProduceOrderService produceOrderService) {
        this.produceOrderService = produceOrderService;
    }

    @Override
    public LocalTransactionState executeLocalTransaction(Message msg, Object arg) {
        log.info("=========本地事务开始执行=============");
        String message = new String(msg.getBody());
        JSONObject jsonObject = JSONObject.parseObject(message);
        Integer productId = jsonObject.getInteger("productId");
        Integer total = jsonObject.getInteger("total");
        int userId = Integer.parseInt(arg.toString());
        //模拟执行本地事务begin=======
        /**
         * 本地事务执行会有三种可能
         * 1、commit 成功
         * 2、Rollback 失败
         * 3、网络等原因服务宕机收不到返回结果
         */
        log.info("本地事务执行参数,用户id={},商品ID={},销售库存={}",userId,productId,total);
        int result = produceOrderService.save(userId, productId, total);
        //模拟执行本地事务end========
        //TODO 实际开发下面不需要我们手动返回，而是根据本地事务执行结果自动返回
        //1、二次确认消息，然后消费者可以消费
        if (result == 0) {
            return LocalTransactionState.COMMIT_MESSAGE;
        }
        //2、回滚消息，Broker端会删除半消息
        if (result == 1) {
            return LocalTransactionState.ROLLBACK_MESSAGE;
        }
        //3、Broker端会进行回查消息
        if (result == 2) {
            return LocalTransactionState.UNKNOW;
        }
        return LocalTransactionState.COMMIT_MESSAGE;
    }

    /**
     * 只有上面接口返回 LocalTransactionState.UNKNOW 才会调用查接口被调用
     *
     * @param msg 消息
     * @return
     */
    @Override
    public LocalTransactionState checkLocalTransaction(MessageExt msg) {
        log.info("==========回查接口=========");
        String key = msg.getKeys();
        //TODO 1、必须根据key先去检查本地事务消息是否完成。
        /**
         * 因为有种情况就是：上面本地事务执行成功了，但是return LocalTransactionState.COMMIT_MESSAG的时候
         * 服务挂了，那么最终 Brock还未收到消息的二次确定，还是个半消息 ，所以当重新启动的时候还是回调这个回调接口。
         * 如果不先查询上面本地事务的执行情况 直接在执行本地事务，那么就相当于成功执行了两次本地事务了。
         */
        // TODO 2、这里返回要么commit 要么rollback。没有必要在返回 UNKNOW
        return LocalTransactionState.COMMIT_MESSAGE;
    }
}




三、MQ消费端核心代码

这里展示下，消费端消费消息核心代码。消费端和普通消费一样。

@Slf4j
@Component
public class OrderConsumer {
    private DefaultMQPushConsumer consumer;
    private String consumerGroup = "produce_consumer_group";

    public OrderConsumer(@Autowired Jms jms,@Autowired ProduceService produceService) throws MQClientException {
        //设置消费组
        consumer = new DefaultMQPushConsumer(consumerGroup);
        // 添加服务器地址
        consumer.setNamesrvAddr(jms.getNameServer());
        // 添加订阅号
        consumer.subscribe(jms.getOrderTopic(), "*");
        // 监听消息
        consumer.registerMessageListener((MessageListenerConcurrently) (msgs, context) -> {
            MessageExt msg = msgs.get(0);
            String message = new String(msgs.get(0).getBody());
            JSONObject jsonObject = JSONObject.parseObject(message);
            Integer productId = jsonObject.getInteger("productId");
            Integer total = jsonObject.getInteger("total");
            String key = msg.getKeys();
            log.info("消费端消费消息，商品ID={},销售数量={}",productId,total);
            try {
                produceService.updateStore(productId, total, key);
                return ConsumeConcurrentlyStatus.CONSUME_SUCCESS;
            } catch (Exception e) {
                log.info("消费失败，进行重试，重试到一定次数 那么将该条记录记录到数据库中，进行如果处理");
                e.printStackTrace();
                return ConsumeConcurrentlyStatus.RECONSUME_LATER;
            }
        });
        consumer.start();
        System.out.println("consumer start ...");
    }
}


至于完整的项目地址见GitHub。

如果对您能有帮助,就给个星星吧，哈哈！

GitHubd地址 https://github.com/yudiandemingzi/spring-cloud-rocketmq-transaction.git