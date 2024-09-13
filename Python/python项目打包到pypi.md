https://blog.csdn.net/u011519550/article/details/105253075

https://juejin.cn/post/6844903906158313485

https://cloud.tencent.com/developer/article/1757852


![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111759763.png)

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111801071.png)


去 https://pypi.org/manage/projects/

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111803526.png)

`MANIFEST.in` 文件用于指定在生成源代码分发包（`sdist`）时要包含的额外文件。然而，对于二进制分发包（`bdist_wheel`），`MANIFEST.in` 并不直接控制文件的包含。`bdist_wheel` 主要依赖于 `setup.py` 中的 `package_data` 或 `data_files` 来包含额外的数据文件。

