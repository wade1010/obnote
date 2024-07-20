   前几天要做个复杂图表，最后决定同过操作excel来实现，在网上寻觅时发现的一篇很好的技术博客，特转载分享。下面是该博客原文，来自： http://www.cnblogs.com/sunzhenxing19860608/archive/2010/12/27/1918128.html



jxl是一个韩国人写的java操作excel的工具, 在开源世界中，有两套比较有影响的API可供使用，一个是POI，一个是jExcelAPI。其中功能相对POI比较弱一点。但jExcelAPI对中文 支持非常好，API是纯Java的， 并不依赖Windows系统，即使运行在Linux下，它同样能够正确的处理Excel文件。 另外需要说明的是，这套API对图形和图表的支持很有限，而且仅仅识别PNG格式。

使用如下：

搭建环境

 将下载后的文件解包，得到jxl.jar，放入classpath，安装就完成了。

 基本操作

 一、创建文件

 拟生成一个名为“test.xls”的Excel文件，其中第一个工作表被命名为

 “第一页”，大致效果如下：package test;

//生成Excel的类
import java.io.File;
import jxl.Workbook;
import jxl.write.Label;
import jxl.write.WritableSheet;
import jxl.write.WritableWorkbook;
public class CreateExcel {
    public static void main(String args[]) {
        try {
            // 打开文件
            WritableWorkbook book = Workbook.createWorkbook(new File("test.xls"));
            // 生成名为“第一页”的工作表，参数0表示这是第一页
            WritableSheet sheet = book.createSheet("第一页", 0);
            // 在Label对象的构造子中指名单元格位置是第一列第一行(0,0)
            // 以及单元格内容为test
            Label label = new Label(0, 0, "test");
            // 将定义好的单元格添加到工作表中
            sheet.addCell(label);
            /*
             * 生成一个保存数字的单元格 必须使用Number的完整包路径，否则有语法歧义 单元格位置是第二列，第一行，值为789.123
             */
            jxl.write.Number number = new jxl.write.Number(1, 0, 555.12541);
            sheet.addCell(number);
            // 写入数据并关闭文件
            book.write();
            book.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    } 

} 



编译执行后，会产生一个Excel文件。

 三、读取文件

 以刚才我们创建的Excel文件为例，做一个简单的读取操作，程序代码如下：

package test;
//读取Excel的类
import java.io.File;
import jxl.Cell;
import jxl.Sheet;
import jxl.Workbook;
public class ReadExcel {
    public static void main(String args[]) {
        try {
            Workbook book = Workbook.getWorkbook(new File("test.xls"));
            // 获得第一个工作表对象
            Sheet sheet = book.getSheet(0);
            // 得到第一列第一行的单元格
            Cell cell1 = sheet.getCell(0, 0);
            String result = cell1.getContents();
            System.out.println(result);
            book.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    } 

} 

程序执行结果：test

 四、修改文件

 利用jExcelAPI可以修改已有的Excel文件，修改Excel文件的时候，除了打开文件的方式不同之外，

 其他操作和创建Excel是一样的。下面的例子是在我们已经生成的Excel文件中添加一个工作表：

package test;
import java.io.File;
import jxl.Workbook;
import jxl.write.Label;
import jxl.write.WritableSheet;
import jxl.write.WritableWorkbook;
public class UpdateExcel {
    public static void main(String args[]) {
        try {
            // Excel获得文件
            Workbook wb = Workbook.getWorkbook(new File("test.xls"));
            // 打开一个文件的副本，并且指定数据写回到原文件
            WritableWorkbook book = Workbook.createWorkbook(new File("test.xls"),
                    wb);
            // 添加一个工作表
            WritableSheet sheet = book.createSheet("第二页", 1);
            sheet.addCell(new Label(0, 0, "第二页的测试数据"));
            book.write();
            book.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    } 

} 

其他操作

 一、 数据格式化

 在Excel中不涉及复杂的数据类型，能够比较好的处理字串、数字和日期已经能够满足一般的应用。

 1、 字串格式化

 字符串的格式化涉及到的是字体、粗细、字号等元素，这些功能主要由WritableFont和

 WritableCellFormat类来负责。假设我们在生成一个含有字串的单元格时，使用如下语句，

 为方便叙述，我们为每一行命令加了编号：

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/Java操作Excel/images/E73B6568A7744FC79458618886812C3Fclipboard.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/Java操作Excel/images/6714D59EC28D493785D42BBB54643CB8clipboard.png)

Workbook book = Workbook.getWorkbook(new File("测试1.xls"));

        // 获得第一个工作表对象

        Sheet sheet = book.getSheet(0);

        // 得到第一列第一行的单元格

        int columnum = sheet.getColumns();// 得到列数

        int rownum = sheet.getRows();// 得到行数

        System.out.println(columnum);

        System.out.println(rownum);

        for (int i = 0; i < rownum; i++)// 循环进行读写

        {

            for (int j = 0; j < columnum; j++) {

                Cell cell1 = sheet.getCell(j, i);

                String result = cell1.getContents();

                System.out.print(result);

                System.out.print("\t");

            }

            System.out.println();

        }

        book.close();    