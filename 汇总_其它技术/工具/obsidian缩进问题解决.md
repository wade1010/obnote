obsidian段首缩进，会把整段都缩进，参考下面方法解决，可能不是最好的办法，先将就用下。
https://forum-zh.obsidian.md/t/topic/28562

我用的是上面最后一个css块
```
/* === 段落-首行缩进2个字符 By Linzeal 2024/2/1更新 === */

/* 也包括段落中每个回车换行后的首行缩进 */

:is(
.markdown-source-view .cm-line:not(:is(:has(>.cm-hmd-frontmatter,>br),.HyperMD-header,.HyperMD-list-line,.HyperMD-quote,table .cm-line, .HyperMD-codeblock)), /* 编辑模式 */
.markdown-rendered :not(:is(blockquote,.HyperMD-codeblock)) > p /* 阅读模式 text-indent不支持each-line的办法 */
){
  text-indent: 2em !important;
}

.markdown-rendered :not(:is(blockquote,.HyperMD-codeblock)) > p
{
  /*text-indent: 2em each-line !important; 若支持each-line参数则用这个即可，更为简单，就无需下面的修正 */
}

/* 阅读模式下对每个回车换行后的首行缩进的修正 */
.markdown-rendered :not(:is(blockquote, .HyperMD-codeblock)) > p > br
{
  content:'';
  white-space:pre; 
}
.markdown-rendered :not(:is(blockquote, .HyperMD-codeblock)) > p > br::after
{
  content:'\A\0009\00A0\00A0\00A0'; /* Unicode字符编码\0009表示水平制表符，\00A0表示不换行空格，可通过增减、组合搭配这两个Unicode字符来微调回车换行后的首行缩进量 */
}

/* === CSS代码结束 === */
```

使用方法：
