List<String> li=page.getHtml().xpath("//div[@id='div_cz_bkcenter']/div/b/[@href]").all();

   int i=0;

   for (String divStr : li) {

    System.out.println(i+++"="+divStr);

   }

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/WebMagic/images/0CC5A005F7824DEDA87F1432D1A31E22clipboard.png)



List<String> li=page.getHtml().xpath("//div[@id='div_cz_bkcenter']/div/b/a/text()").all();

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/WebMagic/images/10F4B2B63BBA4B12B37F97A32B2A46EC)F9@QX`C0LK.jpeg)