[https://mowangblog.github.io/SpringMVC-Demo/#/?id=spring-mvc-demo](https://mowangblog.github.io/SpringMVC-Demo/#/?id=spring-mvc-demo)

- Spring 家族原生产品，与 IOC 容器等基础设施无缝对接

- 基于原生的Servlet，通过了功能强大的**前端控制器DispatcherServlet**，对请求和响应进行统一处理

- 表述层各细分领域需要解决的问题**全方位覆盖**，提供**全面解决方案**

- 代码清新简洁，大幅度提升开发效率

- 内部组件化程度高，可插拔式组件**即插即用**，想要什么功能配置相应组件即可

- 性能卓著，尤其适合现代大型、超大型互联网项目要求

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE8eb78491ce3e428489b369a9eb6db3ac截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE1755105dec8404e3f75942728222f7be截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEa21e87085470acff1b4560e5e3f86eaf截图.png)

配置打包方式

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE64677de8db3f18ab8123bc8a0abf6696截图.png)

```
<dependencies>
    <!-- SpringMVC -->
    <dependency>
        <groupId>org.springframework</groupId>
        <artifactId>spring-webmvc</artifactId>
        <version>5.3.1</version>
    </dependency>

    <!-- 日志 -->
    <dependency>
        <groupId>ch.qos.logback</groupId>
        <artifactId>logback-classic</artifactId>
        <version>1.2.3</version>
    </dependency>

    <!-- ServletAPI -->
    <dependency>
        <groupId>javax.servlet</groupId>
        <artifactId>javax.servlet-api</artifactId>
        <version>3.1.0</version>
        <scope>provided</scope>
    </dependency>

    <!-- Spring5和Thymeleaf整合包 -->
    <dependency>
        <groupId>org.thymeleaf</groupId>
        <artifactId>thymeleaf-spring5</artifactId>
        <version>3.0.12.RELEASE</version>
    </dependency>
</dependencies>
```

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEd26fe25b67073d1d3e41ba1457b6f184截图.png)

点击图片右上角的按钮，就能导入/下载  所需要的包了

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEd072c6caeeafd3814e3c0eceb695703e截图.png)

创建webapp目录

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEbe61117afb799db4b3ba180db15b6124截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEac665306c2bcb5e54b80ee71a380509e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE64270f0f07dcaa27f2afba47264ba90c截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE8233b7956d251506bb3d310df04d1dc2截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEa67b74c8f64130b2616b17bb15eb6968截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE2ec01b8031f14290e98beb9da02a72ac截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEbb6b7c9ab0ed3dff78d4123ba6e05b04截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEd0bb81bd5a7a362894a2a9d170a11871截图.png)

注册SpringMVC的前端控制器DispatcherServlet

[a>默认配置方式](https://mowangblog.github.io/SpringMVC-Demo/#/?id=agt%e9%bb%98%e8%ae%a4%e9%85%8d%e7%bd%ae%e6%96%b9%e5%bc%8f)

此配置作用下，SpringMVC的配置文件默认位于WEB-INF下，默认名称为-servlet.xml，例如，以下配置所对应SpringMVC的配置文件位于WEB-INF下，文件名为springMVC-servlet.xml

```xml
<!-- 配置SpringMVC的前端控制器，对浏览器发送的请求统一进行处理 -->
<servlet>
    <servlet-name>springMVC</servlet-name>
    <servlet-class>org.springframework.web.servlet.DispatcherServlet</servlet-class>
</servlet>
<servlet-mapping>
    <servlet-name>springMVC</servlet-name>
    <!--
        设置springMVC的核心控制器所能处理的请求的请求路径
        /所匹配的请求可以是/login或.html或.js或.css方式的请求路径
        但是/不能匹配.jsp请求路径的请求
    -->
    <url-pattern>/</url-pattern>
</servlet-mapping>
```

[b>扩展配置方式](https://mowangblog.github.io/SpringMVC-Demo/#/?id=bgt%e6%89%a9%e5%b1%95%e9%85%8d%e7%bd%ae%e6%96%b9%e5%bc%8f)

可通过init-param标签设置SpringMVC配置文件的位置和名称，通过load-on-startup标签设置SpringMVC前端控制器DispatcherServlet的初始化时间

```xml
<!-- 配置SpringMVC的前端控制器，对浏览器发送的请求统一进行处理 -->
<servlet>
    <servlet-name>springMVC</servlet-name>
    <servlet-class>org.springframework.web.servlet.DispatcherServlet</servlet-class>
    <!-- 通过初始化参数指定SpringMVC配置文件的位置和名称 -->
    <init-param>
        <!-- contextConfigLocation为固定值 -->
        <param-name>contextConfigLocation</param-name>
        <!-- 使用classpath:表示从类路径查找配置文件，例如maven工程中的src/main/resources -->
        <param-value>classpath:springMVC.xml</param-value>
    </init-param>
    <!-- 
         作为框架的核心组件，在启动过程中有大量的初始化操作要做
        而这些操作放在第一次请求时才执行会严重影响访问速度
        因此需要通过此标签将启动控制DispatcherServlet的初始化时间提前到服务器启动时
    -->
    <load-on-startup>1</load-on-startup>
</servlet>
<servlet-mapping>
    <servlet-name>springMVC</servlet-name>
    <!--
        设置springMVC的核心控制器所能处理的请求的请求路径
        /所匹配的请求可以是/login或.html或.js或.css方式的请求路径
        但是/不能匹配.jsp请求路径的请求
    -->
    <url-pattern>/</url-pattern>
</servlet-mapping>
```

> **注：**
> **标签中使用/和/*的区别：**
> **/所匹配的请求可以是/login或.html或.js或.css方式的请求路径，但是/不能匹配.jsp请求路径的请求**
> **因此就可以避免在访问jsp页面时，该请求被DispatcherServlet处理，从而找不到相应的页面**
> **/*则能够匹配所有请求，例如在使用过滤器时，若需要对所有请求进行过滤，就需要使用/*的写法**


![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE56474444412161127322849edd428200截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE7327db06023e735283e15c438607acf3截图.png)

上图还是一个普通的类，springMVC不认识，

把一个类作为springIOC容器的一个组件进行管理，有两种方法

1、可以桶过bean标签来配置

2、可以通过注解+扫描的方式

这里通过注解+扫描的方式来配置当前的控制器。

PS：需要用到注解应该是什么，我们把一个类标识为IOC容器组件的注解一共4个

1、@Component  将当前类标识为一个普通组件

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE40f28defa3f0d256a34a5c2b9682d344截图.png)

2、@Controller  标识为控制层组件

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE8ea3e6dca37908929b22702ab6ad80fa截图.png)

3、@Service 

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE084fe2a75543fe7204999e0fb097cb48截图.png)

4、@Repository 标识为持久层组件

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEdfb9d5810f0981969dd0a3a3a5f78d82截图.png)

再加上 扫描就能被IOC管理了。

没配置前，如下图

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE5bc13d99be20a882ae1ad0cfc15be7e1截图.png)

进行配置

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE166c38235e88c0f0b3fd92b0141aabe4截图.png)

配置后如下图

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEdf9e766b4c68dfa497ffa5251bd34049截图.png)

配置视图解析器 直接复制到springMVC.xml里面

```
<!-- 配置Thymeleaf视图解析器 -->
<bean id="viewResolver" class="org.thymeleaf.spring5.view.ThymeleafViewResolver">
    <property name="order" value="1"/>
    <property name="characterEncoding" value="UTF-8"/>
    <property name="templateEngine">
        <bean class="org.thymeleaf.spring5.SpringTemplateEngine">
            <property name="templateResolver">
                <bean class="org.thymeleaf.spring5.templateresolver.SpringResourceTemplateResolver">
    
                    <!-- 视图前缀 -->
                    <property name="prefix" value="/WEB-INF/templates/"/>
    
                    <!-- 视图后缀 -->
                    <property name="suffix" value=".html"/>
                    <property name="templateMode" value="HTML5"/>
                    <property name="characterEncoding" value="UTF-8" />
                </bean>
            </property>
        </bean>
    </property>
</bean> 
```

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEfe4663f71aea57c4955cb94a395fa3fc截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEbd0c31ec47956280d38c6c76a12bd708截图.png)

需要thymeleaf语法支持，就要在html加上xmlns:th="[http://www.thymeleaf.org](http://www.thymeleaf.org)" 如上图

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEa5424d63698d589a45f50d9b727ab522截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE45464e144a033044ca1c80e129ddace6截图.png)

tomcat升级了。所以上图的tomcat得重新配置下

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCEe7e1f8d4c74d8ceb97c0321b4bda6d89截图.png)

后来发现老是报错

# java.lang.ClassNotFoundException: javax.servlet.http.HttpServlet

我的做法是把tomcat降级到tomcat8

其实也可以，后来改名了。要用jakaata

> maven工程中tomcat10之后应该导入的servlet包，否则出现java.lang.ClassNotFoundException: javax.servlet.http.HttpServlet
> 


![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springmvc/images/WEBRESOURCE8f55279062bd3b65297dcb836b06cb3d截图.png)

浏览器发送请求，若请求地址符合前端控制器的url-pattern，该请求就会被前端控制器DispatcherServlet处理。前端控制器会读取SpringMVC的核心配置文件，通过扫描组件找到控制器，将请求地址和控制器中@RequestMapping注解的value属性值进行匹配，若匹配成功，该注解所标识的控制器方法就是处理请求的方法。处理请求的方法需要返回一个字符串类型的视图名称，该视图名称会被视图解析器解析，加上前缀和后缀组成视图的路径，通过Thymeleaf对视图进行渲染，最终转发到视图所对应页面

从注解名称上我们可以看到，@RequestMapping注解的作用就是将请求和处理请求的控制器方法关联起来，建立映射关系。

SpringMVC 接收到指定的请求，就会来找到在映射关系中对应的控制器方法来处理这个请求。

@RequestMapping标识一个类：设置映射请求的请求路径的初始信息

@RequestMapping标识一个方法：设置映射请求请求路径的具体信息

```java
@Controller
@RequestMapping("/test")
public class RequestMappingController {

    //此时请求映射所映射的请求的请求路径为：/test/testRequestMapping
    @RequestMapping("/testRequestMapping")
    public String testRequestMapping(){
        return "success";
    }

}
1234567891011Copy to clipboardErrorCopied
```

@RequestMapping注解的value属性通过请求的请求地址匹配请求映射

@RequestMapping注解的value属性是一个字符串类型的数组，表示该请求映射能够匹配多个请求地址所对应的请求

@RequestMapping注解的value属性必须设置，至少通过请求地址匹配请求映射

```html
<a th:href="@{/testRequestMapping}">测试@RequestMapping的value属性-->/testRequestMapping</a><br>
<a th:href="@{/test}">测试@RequestMapping的value属性-->/test</a><br>
12
@RequestMapping(
        value = {"/testRequestMapping", "/test"}
)
public String testRequestMapping(){
    return "success";
}
123456Copy to clipboardErrorCopied
```

@RequestMapping注解的method属性通过请求的请求方式（get或post）匹配请求映射

@RequestMapping注解的method属性是一个RequestMethod类型的数组，表示该请求映射能够匹配多种请求方式的请求

若当前请求的请求地址满足请求映射的value属性，但是请求方式不满足method属性，则浏览器报错405：Request method ‘POST’ not supported

```html
<a th:href="@{/test}">测试@RequestMapping的value属性-->/test</a><br>
<form th:action="@{/test}" method="post">
    <input type="submit">
</form>
1234
@RequestMapping(
        value = {"/testRequestMapping", "/test"},
        method = {RequestMethod.GET, RequestMethod.POST}
)
public String testRequestMapping(){
    return "success";
}
1234567Copy to clipboardErrorCopied
```

> **注：1、对于处理指定请求方式的控制器方法，SpringMVC中提供了@RequestMapping的派生注解处理get请求的映射–>@GetMapping处理post请求的映射–>@PostMapping处理put请求的映射–>@PutMapping处理delete请求的映射–>@DeleteMapping2、常用的请求方式有get，post，put，delete但是目前浏览器只支持get和post，若在form表单提交时，为method设置了其他请求方式的字符串（put或delete），则按照默认的请求方式get处理若要发送put和delete请求，则需要通过spring提供的过滤器HiddenHttpMethodFilter，在RESTful部分会讲到**


@RequestMapping注解的params属性通过请求的请求参数匹配请求映射

@RequestMapping注解的params属性是一个字符串类型的数组，可以通过四种表达式设置请求参数和请求映射的匹配关系

“param”：要求请求映射所匹配的请求必须携带param请求参数

“!param”：要求请求映射所匹配的请求必须不能携带param请求参数

“param=value”：要求请求映射所匹配的请求必须携带param请求参数且param=value

“param!=value”：要求请求映射所匹配的请求必须携带param请求参数但是param!=value

```html
<a th:href="@{/test(username='admin',password=123456)">测试@RequestMapping的params属性-->/test</a><br>
1
@RequestMapping(
        value = {"/testRequestMapping", "/test"}
        ,method = {RequestMethod.GET, RequestMethod.POST}
        ,params = {"username","password!=123456"}
)
public String testRequestMapping(){
    return "success";
}
12345678Copy to clipboardErrorCopied
```

> **注：若当前请求满足@RequestMapping注解的value和method属性，但是不满足params属性，此时页面回报错400：Parameter conditions “username, password!=123456” not met for actual request parameters: username={admin}, password={123456}**


@RequestMapping注解的headers属性通过请求的请求头信息匹配请求映射

@RequestMapping注解的headers属性是一个字符串类型的数组，可以通过四种表达式设置请求头信息和请求映射的匹配关系

“header”：要求请求映射所匹配的请求必须携带header请求头信息

“!header”：要求请求映射所匹配的请求必须不能携带header请求头信息

“header=value”：要求请求映射所匹配的请求必须携带header请求头信息且header=value

“header!=value”：要求请求映射所匹配的请求必须携带header请求头信息且header!=value

若当前请求满足@RequestMapping注解的value和method属性，但是不满足headers属性，此时页面显示404错误，即资源未找到