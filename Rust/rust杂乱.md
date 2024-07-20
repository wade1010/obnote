[https://rustwiki.org/](https://rustwiki.org/)

rust没有GC（垃圾回收机制）

[https://www.go-edu.cn/categories/Rust%E8%AF%AD%E8%A8%80%E5%9F%BA%E7%A1%80%E6%95%99%E7%A8%8B/](https://www.go-edu.cn/categories/Rust%E8%AF%AD%E8%A8%80%E5%9F%BA%E7%A1%80%E6%95%99%E7%A8%8B/)

[https://kaisery.github.io/trpl-zh-cn/](https://kaisery.github.io/trpl-zh-cn/)

sqlx写sql的时候，如果表不存在，他会报错

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327835.jpg)

改成数据库存在的就行

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327860.jpg)

跟主流语言生态圈确实还差很远，在新技术生态圈还是可以的，区块链，wasm都有龙头项目

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327174.jpg)

rust很多地方都是和所有权相关的，所以学rust的时候，遇到想不明白的地方，考虑下是不是和所有权有关

```
fn url2image(url: &str) -> Result<DynamicImage> {
    let start = Instant::now();
    fn to_anyhow(e: impl Display) -> anyhow::Error {
        anyhow!(e.to_string())
    }
    let browser = Browser::default().map_err(to_anyhow)?;

    let tab = browser
        .new_tab_with_options(CreateTarget {
            url: url,
            width: Some(1024),
            height: Some(800),
            browser_context_id: None,
            enable_begin_frame_control: None,
        })
        .map_err(to_anyhow)?;

    let jpeg_data = tab
        .capture_screenshot(ScreenshotFormat::JPEG(Some(75)), None, true)
        .map_err(to_anyhow)?;
    println!("time spent on url2image:{:?}", start.elapsed().as_millis());
    Ok(load_from_memory(&jpeg_data)?)
}
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327312.jpg)