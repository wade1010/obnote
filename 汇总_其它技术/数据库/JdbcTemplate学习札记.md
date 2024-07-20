JdbcTemplate学习笔记

1、使用JdbcTemplate的execute()方法执行SQL语句

Java 代码

    

1. jdbcTemplate.execute("CREATE TABLE USER (user_id integer, name varchar(100))");    

1. jdbcTemplate.execute("CREATE TABLE USER (user_id integer, name varchar(100))");  

2、如果是UPDATE或INSERT,可以用update()方法。

Java 代码

    

1. jdbcTemplate.update("INSERT INTO USER VALUES('"      

1. + user.getId() + "', '"      

1. + user.getName() + "', '"      

1. + user.getSex() + "', '"      

1. + user.getAge() + "')");      

1. jdbcTemplate.update("INSERT INTO USER VALUES('"      

1. + user.getId() + "', '"      

1. + user.getName() + "', '"      

1. + user.getSex() + "', '"      

1. + user.getAge() + "')");  

3、带参数的更新

Java代码

    

1. jdbcTemplate.update("UPDATE USER SET name = ? WHERE user_id = ?", new Object[] {name, id});        

1. jdbcTemplate.update("UPDATE USER SET name = ? WHERE user_id = ?", new Object[] {name, id});  

Java代码

    

1. jdbcTemplate.update("INSERT INTO USER VALUES(?, ?, ?, ?)", new Object[] {user.getId(), user.getName(), user.getSex(), user.getAge()});        

1. jdbcTemplate.update("INSERT INTO USER VALUES(?, ?, ?, ?)", new Object[] {user.getId(), user.getName(), user.getSex(), user.getAge()});  

4、使用JdbcTemplate进行查询时，使用queryForXXX()等方法

Java代码

    

1. int count = jdbcTemplate.queryForInt("SELECT COUNT(*) FROM USER");        

1. int count = jdbcTemplate.queryForInt("SELECT COUNT(*) FROM USER");  

Java代码

    

1. String name = (String) jdbcTemplate.queryForObject("SELECT name FROM USER WHERE user_id = ?", new Object[] {id}, java.lang.String.class);        

1. String name = (String) jdbcTemplate.queryForObject("SELECT name FROM USER WHERE user_id = ?", new Object[] {id}, java.lang.String.class);  

Java代码

    

1. List rows = jdbcTemplate.queryForList("SELECT * FROM USER");        

1. List rows = jdbcTemplate.queryForList("SELECT * FROM USER");  

Java代码

    

1. List rows = jdbcTemplate.queryForList("SELECT * FROM USER");      

1. Iterator it = rows.iterator();      

1. while(it.hasNext()) {      

1. Map userMap = (Map) it.next();      

1. System.out.print(userMap.get("user_id") + "\t");      

1. System.out.print(userMap.get("name") + "\t");      

1. System.out.print(userMap.get("sex") + "\t");      

1. System.out.println(userMap.get("age") + "\t");      

1. }      

1.       

1. List rows = jdbcTemplate.queryForList("SELECT * FROM USER");      

1.       

1. Iterator it = rows.iterator();      

1. while(it.hasNext()) {      

1. Map userMap = (Map) it.next();      

1. System.out.print(userMap.get("user_id") + "\t");      

1. System.out.print(userMap.get("name") + "\t");      

1. System.out.print(userMap.get("sex") + "\t");      

1. System.out.println(userMap.get("age") + "\t");

        

1. }  

JdbcTemplate将我们使用的JDBC的流程封装起来，包括了异常的捕捉、SQL的执行、查询结果的转换等等。spring大量使用Template Method模式来封装固定流程的动作，XXXTemplate等类别都是基于这种方式的实现。

除了大量使用Template Method来封装一些底层的操作细节，spring也大量使用callback方式类回调相关类别的方法以提供JDBC相关类别的功能，使传统的JDBC的使用者也能清楚了解spring所提供的相关封装类别方法的使用。

JDBC的PreparedStatement

Java代码

    

1. final String id = user.getId();        

1. final String name = user.getName();        

1. final String sex = user.getSex() + "";        

1. final int age = user.getAge();      

1.       

1. jdbcTemplate.update("INSERT INTO USER VALUES(?, ?, ?, ?)",      

1.       

1. new PreparedStatementSetter() {        

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setString(1, id);      

1. ps.setString(2, name);      

1. ps.setString(3, sex);      

1. ps.setInt(4, age);      

1. }      

1. });      

1.       

1. final String id = user.getId();      

1. final String name = user.getName();      

1. final String sex = user.getSex() + "";      

1. final int age = user.getAge();      

1.         

1. jdbcTemplate.update("INSERT INTO USER VALUES(?, ?, ?, ?)",      

1.         

1. new PreparedStatementSetter() {          

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setString(1, id);        

1. ps.setString(2, name);          

1. ps.setString(3, sex);        

1. ps.setInt(4, age);          

1. }        

1. });        

Java代码

    

1. final User user = new User();          

1. jdbcTemplate.query("SELECT * FROM USER WHERE user_id = ?",        

1. new Object[] {id},        

1. new RowCallbackHandler() {        

1. public void processRow(ResultSet rs) throws SQLException {        

1. user.setId(rs.getString("user_id"));      

1. user.setName(rs.getString("name"));      

1. user.setSex(rs.getString("sex").charAt(0));        

1. user.setAge(rs.getInt("age"));        

1. }      

1. });      

1.       

1. final User user = new User();      

1.         

1. jdbcTemplate.query("SELECT * FROM USER WHERE user_id = ?",        

1. new Object[] {id},          

1. new RowCallbackHandler() {      

1.       

1.       

1. public void processRow(ResultSet rs) throws SQLException {        

1. user.setId(rs.getString("user_id"));        

1. user.setName(rs.getString("name"));        

1. user.setSex(rs.getString("sex").charAt(0));        

1. user.setAge(rs.getInt("age"));        

1. }        

1. });  

Java代码

    

1. class UserRowMapper implements RowMapper {      

1.       

1. public Object mapRow(ResultSet rs, int index) throws SQLException {      

1.       

1. User user = new User();      

1. user.setId(rs.getString("user_id"));          

1. user.setName(rs.getString("name"));        

1. user.setSex(rs.getString("sex").charAt(0));          

1. user.setAge(rs.getInt("age"));          

1. return user;             

1. }             

1. }      

1.         

1. public List findAllByRowMapperResultReader() {           

1.       

1. String sql = "SELECT * FROM USER";      

1.       

1. return jdbcTemplate.query(sql, new RowMapperResultReader(new UserRowMapper()));      

1.       

1. }      

1.       

1. class UserRowMapper implements RowMapper {      

1.       

1. public Object mapRow(ResultSet rs, int index) throws SQLException {      

1. User user = new User();        

1. user.setId(rs.getString("user_id"));        

1. user.setName(rs.getString("name"));        

1. user.setSex(rs.getString("sex").charAt(0));        

1. user.setAge(rs.getInt("age"));        

1. return user;        

1. }        

1. }      

1.       

1. public List findAllByRowMapperResultReader() {          

1. String sql = "SELECT * FROM USER";        

1. return jdbcTemplate.query(sql, new RowMapperResultReader(new UserRowMapper()));      

1. }  

在getUser(id)里面使用UserRowMapper

Java代码

    

1. public User getUser(final String id) throws DataAccessException {        

1. String sql = "SELECT * FROM USER WHERE user_id=?";        

1. final Object[] params = new Object[] { id };        

1. List list = jdbcTemplate.query(sql, params, new RowMapperResultReader(new UserRowMapper()));          

1. return (User) list.get(0);        

1. }    

1.       

1. public User getUser(final String id) throws DataAccessException {      

1. String sql = "SELECT * FROM USER WHERE user_id=?";          

1. final Object[] params = new Object[] { id };          

1. List list = jdbcTemplate.query(sql, params, new RowMapperResultReader(new UserRowMapper()));          

1. return (User) list.get(0);          

1. }

网上收集

org.springframework.jdbc.core.PreparedStatementCreator 返回预编译SQL 不能于Object[]一起用

Java代码

    

1. public PreparedStatement createPreparedStatement(Connection con) throws SQLException {        

1. return con.prepareStatement(sql);        

1. }      

1.       

1. public PreparedStatement createPreparedStatement(Connection con) throws SQLException {        

1. return con.prepareStatement(sql);        

1. }  

1.增删改

org.springframework.jdbc.core.JdbcTemplate 类(必须指定数据源dataSource)

Java代码

    

1. template.update("insert into web_person values(?,?,?)",Object[]);        

1. template.update("insert into web_person values(?,?,?)",Object[]);  

    

或

Java代码

    

1. template.update("insert into web_person values(?,?,?)",new PreparedStatementSetter(){ //匿名内部类 只能访问外部最终局部变量        

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setInt(index++,3);        

1. });      

1.       

1. template.update("insert into web_person values(?,?,?)",new PreparedStatementSetter(){ //匿名内部类 只能访问外部最终局部变量      

1.       

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setInt(index++,3);        

1. });      

1.       

1. org.springframework.jdbc.core.PreparedStatementSetter //接口 处理预编译SQL            

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setInt(index++,3);        

1. }      

1.       

1. public void setValues(PreparedStatement ps) throws SQLException {        

1. ps.setInt(index++,3);        

1. }  

2.查询JdbcTemplate.query(String,[Object[]/PreparedStatementSetter],RowMapper/RowCallbackHandler)

org.springframework.jdbc.core.RowMapper 记录映射接口 处理结果集

Java代码

    

1. public Object mapRow(ResultSet rs, int arg1) throws SQLException { //int表当前行数        

1. person.setId(rs.getInt("id"));        

1. }        

1. List template.query("select * from web_person where id=?",Object[],RowMapper);        

1. public Object mapRow(ResultSet rs, int arg1) throws SQLException { //int表当前行数        

1. person.setId(rs.getInt("id"));        

1. }        

1. List template.query("select * from web_person where id=?",Object[],RowMapper);  

org.springframework.jdbc.core.RowCallbackHandler 记录回调管理器接口 处理结果集

Java代码

    

1. template.query("select * from web_person where id=?",Object[],new RowCallbackHandler(){        

1. public void processRow(ResultSet rs) throws SQLException {        

1. person.setId(rs.getInt("id"));        

1. });  