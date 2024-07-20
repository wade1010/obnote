        源代码下载：http://download.csdn.net/detail/geloin/6644097

        步骤一：创建web项目，集成Spring

        1. 创建一个web项目，并使其web.xml文件如下所示：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644678.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. < xmlversion=""1.0" encoding="UTF-8"?<  

1. "http://www.w3.org/2001/XMLSchema-instance"

1.     xmlns="http://java.sun.com/xml/ns/javaee" xmlns:web="http://java.sun.com/xml/ns/javaee/web-app_2_5.xsd"

1.     xsi:schemaLocation="http://java.sun.com/xml/ns/javaee http://java.sun.com/xml/ns/javaee/web-app_2_5.xsd"

1.     id="WebApp_ID" version="2.5"<  

1.     esserver  

1.       

1.         contextClass  

1.         org.springframework.web.context.support.XmlWebApplicationContext  

1.       

1.       

1.         contextConfigLocation  

1.             

1.             classpath:/spring/applicationContext.xml  

1.           

1.       

1.       

1.         class<org.springframework.web.context.ContextLoaderListenerclass<  

1.       

1.       

1.         index.html  

1.       

1.   

        需要注意的地方为“contextConfigLocation”配置，指的是spring配置文件所在的位置，按实现情况配置即可。

        2. 创建spring/applicationContext.xml文件，并使其内容如下所示：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644975.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. < xmlversion=""1.0" encoding="UTF-8"?<  

1. "http://www.springframework.org/schema/beans"

1.     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"   xmlns:context="http://www.springframework.org/schema/context"

1.     xsi:schemaLocation="http://www.springframework.org/schema/beans

1.                         http://www.springframework.org/schema/beans/spring-beans.xsd

1.                         http://www.springframework.org/schema/context

1.                         http://www.springframework.org/schema/context/spring-context.xsd"

1. default-lazy-init="true"default-autowire="byName"<  

1.       

1.     package="com.geloin" /<  

1.       

1.     "propertyConfigurer"

1. class="org.springframework.beans.factory.config.PropertyPlaceholderConfigurer"<  

1.         "ignoreResourceNotFound" value="true"<  

1.         "locations"<  

1.               

1.                 classpath:/profile/config.properties  

1.               

1.           

1.       

1.   

        其中，context:component-scan配置适用注解，base-package指定你要使用注解的java类的包名。

        配置propertyConfigurer后允许在程序中使用@Value获取配置文件的配置住处，locations指向配置文件的位置，可以有多个配置文件。

        3. 创建profile/config.properties文件，使其内容为空。

        4. 导入jar包，项目最终结果如下图所示：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644911.jpg)

        5. 此时，启动程序，未报错。

        步骤二：集成ES服务端

        1. 导入jar包，导入后结果如下图所示：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644006.jpg)

        elasticsearch-0.20.6.jar是es的核心类，elasticsearch-analysis-paoding-1.0.0.jar允许es集成paoding，其他几个lucene包是es必须要使用的jar文件。

        2. 建立config/paoding文件夹，将paoding分词器的dic文件夹复制到此文件夹下（到网上下载paoding分词器的dic文件夹），然后建立config/paoding/paoding-analyzer.properties文件，使其内容如下所示：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644140.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. paoding.analyzer.mode=most-words  

1. paoding.analyzer.dictionaries.compiler=net.paoding.analysis.analyzer.impl.MostWordsModeDictionariesCompiler  

1. paoding.dic.home=classpath:config/paoding/dic  

1. paoding.dic.detector.interval=60

1. paoding.knife.class.letterKnife=net.paoding.analysis.knife.LetterKnife  

1. paoding.knife.class.numberKnife=net.paoding.analysis.knife.NumberKnife  

1. paoding.knife.class.cjkKnife=net.paoding.analysis.knife.CJKKnife  

        需要注意的是paoding.dic.home，即dic文件夹所在的位置，如果直接放在config/paoding下，则不需要改变。

        3. 填充config.properties文件，使其内容如下所示：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644328.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. # 集群名称  

1. esserver.cluster.name = elasticsearchclustername  

1. # paoding配置位置  

1. esserver.path.home = classpath:  

1. # 索引文件存储路径  

1. esserver.path.data = D:/work/proTmp/gsearch/indexPath  

        esserver.cluster.name为集群名称，随机即可；esserver.path.home为paoding-analyzer.properties文件所在位置；esserver.path.data为索引文件位置，随机即可。

        4. 添加ManagerConfiguration.java文件，用于获取配置文件内容：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644661.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. /**

1.  * 

1.  */

1. package com.geloin.esserver.config;  

1. import org.springframework.beans.factory.annotation.Value;  

1. import org.springframework.stereotype.Service;  

1. /**

1.  * @author Geloin

1.  * 

1.  */

1. @Service("com.geloin.esserver.config.ManagerConfiguration")  

1. publicclass ManagerConfiguration {  

1. @Value("${esserver.cluster.name}")  

1. private String clusterName;  

1. @Value("${esserver.path.home}")  

1. private String pathHome;  

1. @Value("${esserver.path.data}")  

1. private String pathData;  

1. public String getClusterName() {  

1. return clusterName;  

1.     }  

1. public String getPathData() {  

1. return pathData;  

1.     }  

1. public String getPathHome() {  

1. return pathHome;  

1.     }  

1. }  



        具体代码含义请参见Spring开发过程。

        5. 添加NodeListener.java文件，用于启动ES服务：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644693.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. package com.geloin.esserver.listener;  

1. import java.util.HashMap;  

1. import java.util.Map;  

1. import javax.servlet.ServletContext;  

1. import javax.servlet.ServletContextEvent;  

1. import javax.servlet.ServletContextListener;  

1. import org.elasticsearch.common.settings.ImmutableSettings;  

1. import org.elasticsearch.common.settings.Settings;  

1. import org.elasticsearch.node.Node;  

1. import org.elasticsearch.node.NodeBuilder;  

1. import org.springframework.context.ApplicationContext;  

1. import org.springframework.web.context.support.WebApplicationContextUtils;  

1. import com.geloin.esserver.config.ManagerConfiguration;  

1. /**

1.  * @author tangl

1.  * 

1.  */

1. publicclass NodeListener implements ServletContextListener {  

1. private Node node;  

1. /*

1.      * (non-Javadoc)

1.      * 

1.      * @see

1.      * javax.servlet.ServletContextListener#contextInitialized(javax.servlet

1.      * .ServletContextEvent)

1.      */

1. publicvoid contextInitialized(ServletContextEvent sce) {  

1. // 获取Spring的bean

1.         ServletContext servletContext = sce.getServletContext();  

1.         ApplicationContext context = WebApplicationContextUtils  

1.                 .getWebApplicationContext(servletContext);  

1.         ManagerConfiguration config = (ManagerConfiguration) context  

1.                 .getBean("com.geloin.esserver.config.ManagerConfiguration");  

1. // 设置setting

1.         Map settingMap = new HashMap();  

1.         String clusterName = config.getClusterName();  

1.         String pathData = config.getPathData();  

1.         String pathHome = config.getPathHome();  

1.         settingMap.put("cluster.name", clusterName);  

1.         settingMap.put("path.data", pathData);  

1.         settingMap.put("path.home", pathHome);  

1.         Settings settings = ImmutableSettings.settingsBuilder().put(settingMap)  

1.                 .build();  

1. // 创建并启动节点

1.         NodeBuilder nodeBuilder = NodeBuilder.nodeBuilder();  

1.         nodeBuilder.settings(settings);  

1.         node = nodeBuilder.node();  

1.         node.start();  

1.     }  

1. /*

1.      * (non-Javadoc)

1.      * 

1.      * @see javax.servlet.ServletContextListener#contextDestroyed(javax.servlet.

1.      * ServletContextEvent)

1.      */

1. publicvoid contextDestroyed(ServletContextEvent sce) {  

1. if (null != node) {  

1. // 关闭节点

1.             node.stop();  

1.         }  

1.     }  

1. }  



        6. 在web.xml中添加NodeListener的监听：

[java]view plaincopy

![在CODE上查看代码片](https://gitee.com/hxc8/images9/raw/master/img/202407191644746.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1.   

1.     class<com.geloin.esserver.listener.NodeListenerclass<  

1.   

        7. 此时项目结构如下图所示：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644694.jpg)

        8. 启动程序。

        集成成功标志：

![](https://gitee.com/hxc8/images9/raw/master/img/202407191644072.jpg)