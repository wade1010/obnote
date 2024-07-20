docker pull chaosbladeio/chaosblade-demo



docker run -it --privileged chaosbladeio/chaosblade-demo



```javascript
docker run -it --privileged chaosbladeio/chaosblade-demo
Using CATALINA_BASE:   /usr/local/tomcat
Using CATALINA_HOME:   /usr/local/tomcat
Using CATALINA_TMPDIR: /usr/local/tomcat/temp
Using JRE_HOME:        /usr/lib/jvm/java-1.8-openjdk/jre
Using CLASSPATH:       /usr/local/tomcat/bin/bootstrap.jar:/usr/local/tomcat/bin/tomcat-juli.jar
Tomcat started.

use[ curl http://localhost:8080/dubbo/hello?name=dubbo ]command to request demo

You can use blade command to execute a chaos experiment.

Please read README.txt first! 
```





README.txt内容

```javascript
# Command example

# The application is a simple dubbo demo, so you can test java application chaos experiment, and execute
#[ curl http://localhost:8080/dubbo/hello?name=dubbo ] command to call the service for checking experiment
curl http://localhost:8080/dubbo/hello?name=dubbo

# Prepare java application experiment
blade prepare jvm --process business
# or
blade p jvm --process business

# Create a experiment is delay 3s when invoke com.example.service.DemoService#sayHello service,
blade create dubbo delay --time 3000 --service com.example.service.DemoService --methodname sayHello --consumer
# or
blade c dubbo delay --time 3000 --service com.example.service.DemoService --methodname sayHello --consumer

# Execute curl http://localhost:8080/dubbo/hello?name=dubbo again to check the service status.
# Destroy the experiment, <UID> is the create command result.
blade destroy <UID>
#or
blade d <UID>

# Execute curl http://localhost:8080/dubbo/hello?name=dubbo again to check the service status.
# You can use status command to query the experiment status
blade status --type create

blade status <UID>
#or
blade s <UID>

# Create a experiment is throwing exception when request hello controller service(the request mapping method name is
# hello too)
blade create jvm throwCustomException --exception java.lang.Exception \
    --classname com.example.controller.DubboController --methodname hello

# Destroy the experiment
blade destroy UID

# Burn cpu, execute the flow command and use top command to check cpu stats. You can execute destroy command to stop the
# experiment
blade create cpu fullload

# You can also add --timeout flag to set the experiment duration, the unit of timeout flag is second
blade create cpu fullload --timeout 30

# You can use help command to discover other experiment, enjoy it.
blade help

```

