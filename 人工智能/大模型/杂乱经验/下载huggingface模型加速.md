使用[https://hf-mirror.com/](https://hf-mirror.com/)来替换

![](https://gitee.com/hxc8/images0/raw/master/img/202407172038427.jpg)

将上面命令封装为一个简单的脚本，并放入.bashrc

cd ~

vim hfd

```
#!/bin/bash

if [ "$#" -eq 1 ]; then
    model_name="$1"
    model_dir="$1"

    echo "Downloading $model_name model to $model_dir..."
    huggingface-cli download --resume-download $model_name --local-dir $model_dir

elif [ "$#" -eq 2 ]; then
    model_name="$1"
    model_dir="$2"

    echo "Downloading $model_name model to $model_dir..."
    huggingface-cli download --resume-download $model_name --local-dir $model_dir


elif [ "$#" -eq 3 ]; then
    dataset_name="$1"
    dataset_dir="$2"

    echo "Downloading $dataset_name dataset to $dataset_dir..."
    huggingface-cli download --repo-type dataset --resume-download $dataset_name --local-dir $dataset_dir

else
    echo "Usage: $0 <name> [dir] [isdataset]"
    exit 1
fi

echo "Downloads complete!"
```

chmod a+x hfd

vim .bashrc

添加

```
alias hfd='/home/user/hfd'
```

source .bashrc

后面就可以

hfd x x下载模型了

hfd x x x下载数据集了

![](https://gitee.com/hxc8/images0/raw/master/img/202407172038543.jpg)