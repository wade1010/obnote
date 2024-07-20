一直想写一个poi的使用的总结，话说我第一份正式工作接到的第一个工作就是当时TL让我去整理项目的数据字典，即把内容插入到CSV上，然后把csv的内容插入到数据库中，而且我印象极深的当时使用的就是poi。

今天翻以前的笔记，正好是我当时学POI的一些笔记。这里整理一下。

这里提一下，java操作excel的组件除了apache的poi，还有jexcelapi(jxl)，其中poi组件的获取地址为poi.apache.org。

poi组件中常用的类有HSSFworkbook表示一个完整的excel表格，HSSFsheet表示excel中的一个工作薄，HSSFRow表示工作薄中的一行，HSSFCell表示一个单元格

下面是一个简单的写入的demo



|   |   |
| - | - |
| 01 | publicstaticvoidmain(String [] args){ |


|   |   |
| - | - |
| 02 | try{ |


|   |   |
| - | - |
| 03 | HSSFWorkbook workbook= newHSSFWorkbook(); |


|   |   |
| - | - |
| 04 | HSSFSheet sheet= workbook.createSheet("test"); |


|   |   |
| - | - |
| 05 | HSSFRow row = sheet.createRow(1); |


|   |   |
| - | - |
| 06 | HSSFCell cell= row.createCell(2); |


|   |   |
| - | - |
| 07 | cell.setCellValue("test"); |


|   |   |
| - | - |
| 08 | FileOutputStream os= null; |


|   |   |
| - | - |
| 09 | os = newFileOutputStream("fisrtExcel.xls"); |


|   |   |
| - | - |
| 10 | workbook.write(os); |


|   |   |
| - | - |
| 11 | os.flush(); |


|   |   |
| - | - |
| 12 | os.close(); |


|   |   |
| - | - |
| 13 | } catch(Exception e) { |


|   |   |
| - | - |
| 14 | e.printStackTrace(); |


|   |   |
| - | - |
| 15 | } |


|   |   |
| - | - |
| 16 | System.out.println("ok"); |


|   |   |
| - | - |
| 17 | } |


下面是一个简单的读取demo





|   |   |
| - | - |
| 01 | try{ |


|   |   |
| - | - |
| 02 | FileInputStream file= newFileInputStream("fisrtExcel.xls"); |


|   |   |
| - | - |
| 03 | POIFSFileSystem ts= newPOIFSFileSystem(file); |


|   |   |
| - | - |
| 04 | HSSFWorkbook wb=newHSSFWorkbook(ts); |


|   |   |
| - | - |
| 05 | HSSFSheet sh= wb.getSheetAt(0); |


|   |   |
| - | - |
| 06 | HSSFRow ro=null; |


|   |   |
| - | - |
| 07 | for(inti = 0; sh.getRow(i)!=null; i++) { |


|   |   |
| - | - |
| 08 | ro=sh.getRow(i); |


|   |   |
| - | - |
| 09 | for(intj = 0; ro.getCell(j)!=null; j++) { |


|   |   |
| - | - |
| 10 | System.out.print(ro.getCell(j)+""); |


|   |   |
| - | - |
| 11 | } |


|   |   |
| - | - |
| 12 | System.out.println(); |


|   |   |
| - | - |
| 13 | } |


|   |   |
| - | - |
| 14 | } catch(Exception e) { |


|   |   |
| - | - |
| 15 | e.printStackTrace(); |


|   |   |
| - | - |
| 16 | } |


|   |   |
| - | - |
| 17 | System.out.println("ok"); |


下面是几个常用的api





|   |   |
| - | - |
| 01 | //使用公式 |


|   |   |
| - | - |
| 02 | cell2.setCellFormula("B2"); |


|   |   |
| - | - |
| 03 | //设置列宽 |


|   |   |
| - | - |
| 04 | sheet.setColumnWidth(columnIndex, width); |


|   |   |
| - | - |
| 05 | //设置行高 |


|   |   |
| - | - |
| 06 | row.setHeight(height); |


|   |   |
| - | - |
| 07 | //设这样式： |


|   |   |
| - | - |
| 08 | HSSFFont font= workbook.createFont(); |


|   |   |
| - | - |
| 09 | font.setFontHeightInPoints(height); |


|   |   |
| - | - |
| 10 | font.setBoldweight(HSSFFont.BOLDWEIGHT\_BOLD); |


|   |   |
| - | - |
| 11 | font.setFontName("黑体"); |


|   |   |
| - | - |
| 12 | HSSFCellStyle style= workbook.createCellStyle(); |


|   |   |
| - | - |
| 13 | style.setFont(font); |


|   |   |
| - | - |
| 14 | //style可以设置对齐样式，边框，和格式化日期。 |


|   |   |
| - | - |
| 15 | cell.setCellStyle(style); |


|   |   |
| - | - |
| 16 | //合并单元格 |


|   |   |
| - | - |
| 17 | sheet.addMergedRegion(region); |




我们之前提到了一个jxl的使用。当时在网上也找到了一个使用的demo，可以参考这里：



|   |   |
| - | - |
| 01 | publicstaticvoidmain(String [] args){ |


|   |   |
| - | - |
| 02 | try{ |


|   |   |
| - | - |
| 03 | WritableWorkbook wwb = null; |


|   |   |
| - | - |
| 04 | //首先要使用Workbook类的工厂方法创建一个可写入的工作薄(Workbook)对象 |


|   |   |
| - | - |
| 05 | wwb = Workbook.createWorkbook(newFile("jxlexcel")); |


|   |   |
| - | - |
| 06 | if(wwb!=null){ |


|   |   |
| - | - |
| 07 | //创建一个可写入的工作表 |


|   |   |
| - | - |
| 08 | WritableSheet ws = wwb.createSheet("sheet1", 0); |


|   |   |
| - | - |
| 09 | for(inti=0;i&lt; code&gt;10;i++){ |


|   |   |
| - | - |
| 10 | for(intj=0;j&lt; code&gt;5;j++){ |


|   |   |
| - | - |
| 11 | Label labelC = newLabel(j, i, "这是第"+(i+1)+"行，第"+(j+1)+"列"); |


|   |   |
| - | - |
| 12 | ws.addCell(labelC); |


|   |   |
| - | - |
| 13 | } |


|   |   |
| - | - |
| 14 | } |


|   |   |
| - | - |
| 15 | wwb.write(); |


|   |   |
| - | - |
| 16 | wwb.close(); |


|   |   |
| - | - |
| 17 | } |


|   |   |
| - | - |
| 18 | } catch(Exception e) { |


|   |   |
| - | - |
| 19 | e.printStackTrace(); |


|   |   |
| - | - |
| 20 | } |


|   |   |
| - | - |
| 21 | System.out.println("ok"); |


|   |   |
| - | - |
| 22 | } |


对于更复杂的内容这里有几个参考文档：



http://www.newxing.com/Tech/Java/Web/Excel_186.html

http://www.yesky.com/18/1886018.shtml



总结一下，poi的使用比较简单，主要是写入和读取的时候计算好读取的位置。而且现在项目中已经封装了一个现成的类，几乎几行代码就可以把结果输出到一个excel中并提供下载，PS:很怀念刚工作时的那段日子。