前面的章节主要讲mybatis如何解析配置文件，这些都是一次性的过程。从本章开始讲解动态的过程，它们跟应用程序对mybatis的调用密切相关。本章先从sqlsession开始。

创建

正如其名，Sqlsession对应着一次数据库会话。由于数据库回话不是永久的，因此Sqlsession的生命周期也不应该是永久的，相反，在你每次访问数据库时都需要创建它（当然并不是说在Sqlsession里只能执行一次sql，你可以执行多次，当一旦关闭了Sqlsession就需要重新创建它）。创建Sqlsession的地方只有一个，那就是SqlsessionFactory的openSession方法：

[java]view plaincopy

1. public SqlSessionopenSession() {  

1.     returnopenSessionFromDataSource(configuration.getDefaultExecutorType(),null, false);  

1. }  

我们可以看到实际创建SqlSession的地方是openSessionFromDataSource，如下：

[java]view plaincopy

1. private SqlSessionopenSessionFromDataSource(ExecutorType execType, TransactionIsolationLevellevel, boolean autoCommit) {  

1.     Connectionconnection = null;  

1. try {  

1.         finalEnvironment environment = configuration.getEnvironment();  

1. final DataSourcedataSource = getDataSourceFromEnvironment(environment);  

1.        TransactionFactory transactionFactory =getTransactionFactoryFromEnvironment(environment);  

1.        connection = dataSource.getConnection();  

1. if (level != null) {  

1.            connection.setTransactionIsolation(level.getLevel());  

1.         }  

1.        connection = wrapConnection(connection);  

1.        Transaction tx = transactionFactory.newTransaction(connection,autoCommit);  

1.         Executorexecutor = configuration.newExecutor(tx, execType);  

1.         returnnewDefaultSqlSession(configuration, executor, autoCommit);  

1.     } catch (Exceptione) {  

1.        closeConnection(connection);  

1.         throwExceptionFactory.wrapException("Error opening session.  Cause: " + e, e);  

1.     } finally {  

1.        ErrorContext.instance().reset();  

1.     }  

1. }  

可以看出，创建sqlsession经过了以下几个主要步骤：

1)       从配置中获取Environment；

2)       从Environment中取得DataSource；

3)       从Environment中取得TransactionFactory；

4)       从DataSource里获取数据库连接对象Connection；

5)       在取得的数据库连接上创建事务对象Transaction；

6)       创建Executor对象（该对象非常重要，事实上sqlsession的所有操作都是通过它完成的）；

7)       创建sqlsession对象。

Executor的创建

Executor与Sqlsession的关系就像市长与书记，Sqlsession只是个门面，真正干事的是Executor，Sqlsession对数据库的操作都是通过Executor来完成的。与Sqlsession一样，Executor也是动态创建的：

[java]view plaincopy

1. public ExecutornewExecutor(Transaction transaction, ExecutorType executorType) {  

1.        executorType = executorType == null ? defaultExecutorType :executorType;  

1.        executorType = executorType == null ?ExecutorType.SIMPLE : executorType;  

1.         Executor executor;  

1. if(ExecutorType.BATCH == executorType) {  

1.            executor = new BatchExecutor(this,transaction);  

1.         } elseif(ExecutorType.REUSE == executorType) {  

1.            executor = new ReuseExecutor(this,transaction);  

1.         } else {  

1.             executor = newSimpleExecutor(this, transaction);  

1.         }  

1. if (cacheEnabled) {  

1.            executor = new CachingExecutor(executor);  

1.         }  

1.         executor =(Executor) interceptorChain.pluginAll(executor);  

1. return executor;  

1. }  





可以看出，如果不开启cache的话，创建的Executor只是3中基础类型之一，BatchExecutor专门用于执行批量sql操作，ReuseExecutor会重用statement执行sql操作，SimpleExecutor只是简单执行sql没有什么特别的。开启cache的话（默认是开启的并且没有任何理由去关闭它），就会创建CachingExecutor，它以前面创建的Executor作为唯一参数。CachingExecutor在查询数据库前先查找缓存，若没找到的话调用delegate（就是构造时传入的Executor对象）从数据库查询，并将查询结果存入缓存中。

Executor对象是可以被插件拦截的，如果定义了针对Executor类型的插件，最终生成的Executor对象是被各个插件插入后的代理对象（关于插件会有后续章节专门介绍，敬请期待）。

Mapper

Mybatis官方手册建议通过mapper对象访问mybatis，因为使用mapper看起来更优雅，就像下面这样：

[java]view plaincopy

1. session = sqlSessionFactory.openSession();  

1. UserDao userDao= session.getMapper(UserDao.class);  

1. UserDto user =new UserDto();  

1. user.setUsername("iMbatis");  

1. user.setPassword("iMbatis");  

1. userDao.insertUser(user);  

那么这个mapper到底是什么呢，它是如何创建的呢，它又是怎么与sqlsession等关联起来的呢？下面为你一一解答。

创建

表面上看mapper是在sqlsession里创建的，但实际创建它的地方是MapperRegistry：

[java]view plaincopy

1. public T getMapper(Class type, SqlSession sqlSession) {  

1. if (!knownMappers.contains(type))  

1.         thrownewBindingException("Type " + type + " isnot known to the MapperRegistry.");  

1. try {  

1.         returnMapperProxy.newMapperProxy(type, sqlSession);  

1.     } catch (Exceptione) {  

1.         thrownewBindingException("Error getting mapper instance. Cause: " + e, e);  

1.     }  

1. }  

可以看到，mapper是一个代理对象，它实现的接口就是传入的type，这就是为什么mapper对象可以通过接口直接访问。同时还可以看到，创建mapper代理对象时传入了sqlsession对象，这样就把sqlsession也关联起来了。我们进一步看看MapperProxy.newMapperProxy(type,sqlSession);背后发生了什么事情：

[java]view plaincopy

1. publicstatic T newMapperProxy(Class mapperInterface, SqlSession sqlSession) {  

1.     ClassLoaderclassLoader = mapperInterface.getClassLoader();  

1.     Class< >[] interfaces = new Class[]{mapperInterface};  

1.     MapperProxyproxy = new MapperProxy(sqlSession);  

1. return (T) Proxy.newProxyInstance(classLoader,interfaces, proxy);  

1. }  

看起来没什么特别的，和其他代理类的创建一样，我们重点关注一下MapperProxy的invoke方法

MapperProxy的invoke

我们知道对被代理对象的方法的访问都会落实到代理者的invoke上来，MapperProxy的invoke如下：

[java]view plaincopy

1. public Objectinvoke(Object proxy, Method method, Object[] args) throws Throwable{  

1. if (method.getDeclaringClass()== Object.class) {  

1. return method.invoke(this, args);  

1.     }  

1.     finalClass< > declaringInterface = findDeclaringInterface(proxy, method);  

1.     finalMapperMethod mapperMethod = newMapperMethod(declaringInterface, method, sqlSession);  

1. final Objectresult = mapperMethod.execute(args);  

1. if (result ==null && method.getReturnType().isPrimitive()&& !method.getReturnType().equals(Void.TYPE)) {  

1.         thrownewBindingException("Mapper method '" + method.getName() + "'(" + method.getDeclaringClass()  

1.                 + ") attempted toreturn null from a method with a primitive return type ("

1.                + method.getReturnType() + ").");  

1.     }  

1. return result;  

1. }  





可以看到invoke把执行权转交给了MapperMethod，我们来看看MapperMethod里又是怎么运作的：

[java]view plaincopy

1. public Objectexecute(Object[] args) {  

1.         Objectresult = null;  

1. if(SqlCommandType.INSERT == type) {  

1.             Objectparam = getParam(args);  

1.             result= sqlSession.insert(commandName, param);  

1.         } elseif(SqlCommandType.UPDATE == type) {  

1.             Object param = getParam(args);  

1.             result= sqlSession.update(commandName, param);  

1.         } elseif(SqlCommandType.DELETE == type) {  

1.             Objectparam = getParam(args);  

1.             result= sqlSession.delete(commandName, param);  

1.         } elseif(SqlCommandType.SELECT == type) {  

1. if (returnsVoid &&resultHandlerIndex != null) {  

1.                executeWithResultHandler(args);  

1.             } elseif (returnsList) {  

1.                result = executeForList(args);  

1.             } elseif (returnsMap) {  

1.                result = executeForMap(args);  

1.             } else {  

1.                Object param = getParam(args);  

1.                result = sqlSession.selectOne(commandName, param);  

1.             }  

1.         } else {  

1.             thrownewBindingException("Unknown execution method for: " + commandName);  

1.         }  

1. return result;  

1. }  





可以看到，MapperMethod就像是一个分发者，他根据参数和返回值类型选择不同的sqlsession方法来执行。这样mapper对象与sqlsession就真正的关联起来了。

Executor

前面提到过，sqlsession只是一个门面，真正发挥作用的是executor，对sqlsession方法的访问最终都会落到executor的相应方法上去。Executor分成两大类，一类是CacheExecutor，另一类是普通Executor。Executor的创建前面已经介绍了，下面介绍下他们的功能：

CacheExecutor

CacheExecutor有一个重要属性delegate，它保存的是某类普通的Executor，值在构照时传入。执行数据库update操作时，它直接调用delegate的update方法，执行query方法时先尝试从cache中取值，取不到再调用delegate的查询方法，并将查询结果存入cache中。代码如下：

[java]view plaincopy

1. public Listquery(MappedStatement ms, Object parameterObject, RowBounds rowBounds,ResultHandler resultHandler) throws SQLException {  

1. if (ms != null) {  

1.         Cachecache = ms.getCache();  

1. if (cache != null) {  

1.            flushCacheIfRequired(ms);  

1.            cache.getReadWriteLock().readLock().lock();  

1. try {  

1. if (ms.isUseCache() && resultHandler ==null) {  

1.                    CacheKey key = createCacheKey(ms, parameterObject, rowBounds);  

1. final List cachedList = (List)cache.getObject(key);  

1. if (cachedList != null) {  

1.                         returncachedList;  

1.                    } else {  

1.                        List list = delegate.query(ms,parameterObject, rowBounds, resultHandler);  

1.                        tcm.putObject(cache,key, list);  

1. return list;  

1.                    }  

1.                } else {  

1.                    returndelegate.query(ms,parameterObject, rowBounds, resultHandler);  

1.                }  

1.             } finally {  

1.                cache.getReadWriteLock().readLock().unlock();  

1.             }  

1.         }  

1.     }  

1.     returndelegate.query(ms,parameterObject, rowBounds, resultHandler);  

1. }  

普通Executor

普通Executor有3类，他们都继承于BaseExecutor，BatchExecutor专门用于执行批量sql操作，ReuseExecutor会重用statement执行sql操作，SimpleExecutor只是简单执行sql没有什么特别的。下面以SimpleExecutor为例：

[java]view plaincopy

1. public ListdoQuery(MappedStatement ms, Object parameter, RowBounds rowBounds,ResultHandler resultHandler) throws SQLException {  

1.     Statementstmt = null;  

1. try {  

1.        Configuration configuration = ms.getConfiguration();  

1.        StatementHandler handler = configuration.newStatementHandler(this, ms,parameter, rowBounds,resultHandler);  

1.        stmt =prepareStatement(handler);  

1.        returnhandler.query(stmt, resultHandler);  

1.     } finally {  

1.        closeStatement(stmt);  

1.     }  

1. }  

可以看出，Executor本质上也是个甩手掌柜，具体的事情原来是StatementHandler来完成的。

StatementHandler

当Executor将指挥棒交给StatementHandler后，接下来的工作就是StatementHandler的事了。我们先看看StatementHandler是如何创建的。

创建

[java]view plaincopy

1. publicStatementHandler newStatementHandler(Executor executor, MappedStatementmappedStatement,  

1.         ObjectparameterObject, RowBounds rowBounds, ResultHandler resultHandler) {  

1.    StatementHandler statementHandler = newRoutingStatementHandler(executor, mappedStatement,parameterObject,rowBounds, resultHandler);  

1.    statementHandler= (StatementHandler) interceptorChain.pluginAll(statementHandler);  

1.    returnstatementHandler;  

1. }  

可以看到每次创建的StatementHandler都是RoutingStatementHandler，它只是一个分发者，他一个属性delegate用于指定用哪种具体的StatementHandler。可选的StatementHandler有SimpleStatementHandler、PreparedStatementHandler和CallableStatementHandler三种。选用哪种在mapper配置文件的每个statement里指定，默认的是PreparedStatementHandler。同时还要注意到StatementHandler是可以被拦截器拦截的，和Executor一样，被拦截器拦截后的对像是一个代理对象。由于mybatis没有实现数据库的物理分页，众多物理分页的实现都是在这个地方使用拦截器实现的，本文作者也实现了一个分页拦截器，在后续的章节会分享给大家，敬请期待。

初始化

StatementHandler创建后需要执行一些初始操作，比如statement的开启和参数设置、对于PreparedStatement还需要执行参数的设置操作等。代码如下：

[java]view plaincopy

1. private StatementprepareStatement(StatementHandler handler) throwsSQLException {  

1.     Statementstmt;  

1.     Connectionconnection = transaction.getConnection();  

1.     stmt =handler.prepare(connection);  

1.     handler.parameterize(stmt);  

1. return stmt;  

1. }  

statement的开启和参数设置没什么特别的地方，handler.parameterize倒是可以看看是怎么回事。handler.parameterize通过调用ParameterHandler的setParameters完成参数的设置，ParameterHandler随着StatementHandler的创建而创建，默认的实现是DefaultParameterHandler：

[java]view plaincopy

1. publicParameterHandler newParameterHandler(MappedStatement mappedStatement, ObjectparameterObject, BoundSql boundSql) {  

1.    ParameterHandler parameterHandler = new DefaultParameterHandler(mappedStatement,parameterObject,boundSql);  

1.    parameterHandler = (ParameterHandler) interceptorChain.pluginAll(parameterHandler);  

1.    returnparameterHandler;  

1. }  

同Executor和StatementHandler一样，ParameterHandler也是可以被拦截的。

参数设置

DefaultParameterHandler里设置参数的代码如下：

[java]view plaincopy

1. publicvoidsetParameters(PreparedStatement ps) throwsSQLException {  

1.    ErrorContext.instance().activity("settingparameters").object(mappedStatement.getParameterMap().getId());  

1.    List parameterMappings = boundSql.getParameterMappings();  

1. if(parameterMappings != null) {  

1.        MetaObject metaObject = parameterObject == null ? null :configuration.newMetaObject(parameterObject);  

1. for (int i = 0; i< parameterMappingssizeispan>

1.            ParameterMapping parameterMapping = parameterMappings.get(i);  

1. if(parameterMapping.getMode() != ParameterMode.OUT) {  

1.                Object value;  

1.                String propertyName = parameterMapping.getProperty();  

1.                PropertyTokenizer prop = newPropertyTokenizer(propertyName);  

1. if (parameterObject == null) {  

1.                    value = null;  

1.                } elseif (typeHandlerRegistry.hasTypeHandler(parameterObject.getClass())){  

1.                    value = parameterObject;  

1.                } elseif (boundSql.hasAdditionalParameter(propertyName)){  

1.                    value = boundSql.getAdditionalParameter(propertyName);  

1.                } elseif(propertyName.startsWith(ForEachSqlNode.ITEM_PREFIX)  

1.                         && boundSql.hasAdditionalParameter(prop.getName())){  

1.                    value = boundSql.getAdditionalParameter(prop.getName());  

1. if (value != null) {  

1.                         value = configuration.newMetaObject(value).getValue(propertyName.substring(prop.getName().length()));  

1.                    }  

1.                } else {  

1.                    value = metaObject == null ? null :metaObject.getValue(propertyName);  

1.                }  

1.                TypeHandler typeHandler = parameterMapping.getTypeHandler();  

1. if (typeHandler == null) {  

1.                    thrownew ExecutorException("Therewas no TypeHandler found for parameter " + propertyName  + " of statement " + mappedStatement.getId());  

1.                 }  

1.                typeHandler.setParameter(ps, i + 1, value,parameterMapping.getJdbcType());  

1.             }  

1.         }  

1.     }  

1. }  

这里面最重要的一句其实就是最后一句代码，它的作用是用合适的TypeHandler完成参数的设置。那么什么是合适的TypeHandler呢，它又是如何决断出来的呢？BaseStatementHandler的构造方法里有这么一句：

this.boundSql= mappedStatement.getBoundSql(parameterObject);

它触发了sql 的解析，在解析sql的过程中，TypeHandler也被决断出来了，决断的原则就是根据参数的类型和参数对应的JDBC类型决定使用哪个TypeHandler。比如：参数类型是String的话就用StringTypeHandler，参数类型是整数的话就用IntegerTypeHandler等。

参数设置完毕后，执行数据库操作（update或query）。如果是query最后还有个查询结果的处理过程。

结果处理

结果处理使用ResultSetHandler来完成，默认的ResultSetHandler是FastResultSetHandler，它在创建StatementHandler时一起创建，代码如下：

[java]view plaincopy

1. publicResultSetHandler newResultSetHandler(Executor executor, MappedStatementmappedStatement,  

1. RowBoundsrowBounds, ParameterHandler parameterHandler, ResultHandler resultHandler, BoundSqlboundSql) {  

1.    ResultSetHandler resultSetHandler =mappedStatement.hasNestedResultMaps() ? newNestedResultSetHandler(executor, mappedStatement, parameterHandler,resultHandler, boundSql, rowBounds): new FastResultSetHandler(executor,mappedStatement, parameterHandler, resultHandler, boundSql, rowBounds);  

1.    resultSetHandler = (ResultSetHandler) interceptorChain.pluginAll(resultSetHandler);  

1.    returnresultSetHandler;  

1. }  

可以看出ResultSetHandler也是可以被拦截的，可以编写自己的拦截器改变ResultSetHandler的默认行为。

[java]view plaincopy

1. ResultSetHandler内部一条记录一条记录的处理，在处理每条记录的每一列时会调用TypeHandler转换结果，如下：  

1.     protectedbooleanapplyAutomaticMappings(ResultSet rs, List unmappedColumnNames,MetaObject metaObject) throws SQLException {  

1.         booleanfoundValues = false;  

1. for (StringcolumnName : unmappedColumnNames) {  

1. final Stringproperty = metaObject.findProperty(columnName);  

1. if (property!= null) {  

1. final ClasspropertyType =metaObject.getSetterType(property);  

1. if (typeHandlerRegistry.hasTypeHandler(propertyType)) {  

1. final TypeHandler typeHandler = typeHandlerRegistry.getTypeHandler(propertyType);  

1. final Object value = typeHandler.getResult(rs,columnName);  

1. if (value != null) {  

1.                        metaObject.setValue(property, value);  

1.                        foundValues = true;  

1.                    }  

1.                 }  

1.             }  

1.         }  

1.         returnfoundValues;  

1.    }  

从代码里可以看到，决断TypeHandler使用的是结果参数的属性类型。因此我们在定义作为结果的对象的属性时一定要考虑与数据库字段类型的兼容性。