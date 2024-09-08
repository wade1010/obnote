
# 报错
使用自定义本地模型的时候 如果是中文，做prompt_tune可以能会报错

```
python -m graphrag.prompt_tune --root ./ragtest2 --config ./ragtest2/settings.yaml --no-entity-types --language Chinese --output ./ragtest2/prompts
```

如果执行上面命令出现如下错误：

ValueError: Encountered text corresponding to disallowed special token '<|endoftext|>'.
If you want this text to be encoded as a special token, pass it to `allowed_special`, e.g. `allowed_special={'<|endoftext|>', ...}`.
If you want this text to be encoded as normal text, disable the check for this token by passing `disallowed_special=(enc.special_tokens_set - {'<|endoftext|>'})`.
To disable this check for all special tokens, pass `disallowed_special=()`.

有时候重新再执行一遍，可以解决。


另外如果执行下面命令报错，不做prompt_tune试试

```
python -m graphrag.index --root ./ragtest
```





# 非常消耗token
后来测试了一把使用非本地的大模型，上面的文本，4个文本，4章节西游记的txt，跑一次，就把我积分耗完了。。。乖乖用本地大模型吧。
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409080947087.png)
