现在基本上都是采用maven来进行开发管理，我有一个需求是需要把通过maven管理的java工程打成可执行的jar包，这样也就是说必需把工程依赖的jar包也一起打包。而使用maven默认的package命令构建的jar包中只包括了工程自身的class文件，并没有包括依赖的jar包。我们可以通过配置插件来对工程进行打包，pom具体配置如下：

maven-assembly-plugin

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/47500E2995FE4E2FAA08CBDB343BD40Cicon_star.png)

1. < span>plugin<

1. < span>artifactId<maven-assembly-pluginartifactId<

1. < span>configuration<

1. < span>appendAssemblyId<falseappendAssemblyId<

1. < span>descriptorRefs<

1. < span>descriptorRef<jar-with-dependenciesdescriptorRef<

1. descriptorRefs<

1. < span>archive<

1. < span>manifest<

1. < span>mainClass<com.chenzhou.examples.MainmainClass<

1. manifest<

1. archive<

1. configuration<

1. < span>executions<

1. < span>execution<

1. < span>id<make-assemblyid<

1. < span>phase<packagephase<

1. < span>goals<

1. < span>goal<assemblygoal<

1. goals<

1. execution<

1. executions<

1. plugin<

其中的值表示此工程的入口类，也就是包含main方法的类，在我的例子中就是com.chenzhou.examples.Main。配置完pom后可以通过执行mvn assembly:assembly命令来启动插件进行构建。构建成功后会生成jar包，这样我们就可以在命令行中通过java -jar XXX.jar来运行jar件了。 

不过使用此插件会有一些问题：我在工程中依赖了spring框架的jar包，我打包成功后使用命令来调用jar包时报错如下（内网环境）：

Shell代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/9ABCB91554F249EA8B1251367B69A031icon_star.png)

1. org.xml.sax.SAXParseException: schema_reference.4: Failed to read schema document 'http://www.springframework.org/schema/beans/spring-beans-3.0.xsd', because 1) could not find the document; 2) the document could not be read; 3) the root element of the document is not .  

关于此问题报错的原因，我在网上找到一篇文章对此有比较详细的解释：http://blog.csdn.net/bluishglc/article/details/7596118 简单来说就是spring在启动时会加载xsd文件，它首先会到本地查找xsd文件（一般都会包含在spring的jar包中），如果找不到则会到xml头部定义的url指定路径下中去寻找xsd，如果找不到则会报错。

附：在spring jar包下的META-INF文件夹中都会包含一个spring.schemas文件，其中就包含了对xsd文件的路径定义，具体如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/BF37276EC5E64919A1A127BAF47AE6C2a793922e-88dc-35f9-bccd-72a0b37cc1b2.jpg.jpeg)

图：spring-aop.jar包下META-INF文件夹下的内容

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/AF45A536622A4262A87C1B54760A2F59133fdfd9-4847-32a3-ba55-66bc3c4dec9d.jpg.jpeg)

图：spring.schemas文件内容

由于我的工程是在内网，所以通过url路径去寻找肯定是找不到的，但是比较奇怪的是既然spring的jar包中都会包含，那为什么还是找不到呢？

原来这是assembly插件的一个bug，具体情况参见：http://jira.codehaus.org/browse/MASSEMBLY-360

该bug产生的原因如下：工程一般依赖了很多的jar包，而被依赖的jar又会依赖其他的jar包，这样，当工程中依赖到不同的版本的spring时，在使用assembly进行打包时，只能将某一个版本jar包下的spring.schemas文件放入最终打出的jar包里，这就有可能遗漏了一些版本的xsd的本地映射，所以会报错。

所以一般推荐使用另外的一个插件来进行打包，插件名称为：maven-shade-plugin，shade插件打包时在对spring.schemas文件处理上，它能够将所有jar里的spring.schemas文件进行合并，在最终生成的单一jar包里，spring.schemas包含了所有出现过的版本的集合，要使用shade插件，必须在pom进行如下配置：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/BD6EE9B65E7D4076A3F04D1C37267A85icon_star.png)

1. < span>plugin<

1. < span>groupId<org.apache.maven.pluginsgroupId<

1. < span>artifactId<maven-shade-pluginartifactId<

1. < span>version<1.4version<

1. < span>executions<

1. < span>execution<

1. < span>phase<packagephase<

1. < span>goals<

1. < span>goal<shadegoal<

1. goals<

1. < span>configuration<

1. < span>transformers<

1. < span>transformer

1. implementation="org.apache.maven.plugins.shade.resource.AppendingTransformer"<

1. < span>resource<META-INF/spring.handlersresource<

1. transformer<

1. < span>transformer

1. implementation="org.apache.maven.plugins.shade.resource.ManifestResourceTransformer"<

1. < span>mainClass<com.chenzhou.examples.MainmainClass<

1. transformer<

1. < span>transformer

1. implementation="org.apache.maven.plugins.shade.resource.AppendingTransformer"<

1. < span>resource<META-INF/spring.schemasresource<

1. transformer<

1. transformers<

1. configuration<

1. execution<

1. executions<

1. plugin<

上面配置文件中有一段定义：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/maven/images/6B7570F3B6E942C7B3C8A8886A6029CCicon_star.png)

1. < span>transformer

1. implementation="org.apache.maven.plugins.shade.resource.AppendingTransformer"<

1. < span>resource<META-INF/spring.schemasresource<

1. transformer<

上面这段配置意思是把spring.handlers和spring.schemas文件以append方式加入到构建的jar包中，这样就不会存在出现xsd找不到的情况。

配置完pom后，调用mvn clean install命令进行构建，构建成功后打开工程target目录，发现生成了2个jar包，一个为：original-XXX-0.0.1-SNAPSHOT.jar，另一个为：XXX-0.0.1-SNAPSHOT.jar，其中original...jar里只包含了工程自己的class文件，而另外的一个jar包则包含了工程本身以及所有依赖的jar包的class文件。我们只需要使用第二个jar包就可以了。

参考资料：

http://hi.baidu.com/yuzhi2217/item/2c1714363f25c4f62684f442

http://blog.csdn.net/bluishglc/article/details/7596118

http://jira.codehaus.org/browse/MASSEMBLY-360