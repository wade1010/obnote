使用自定义本地模型的时候 如果是中文，不要做prompt_tune

```
python -m graphrag.prompt_tune --root ./ragtest2 --config ./ragtest2/settings.yaml --no-entity-types --language Chinese --output ./ragtest2/prompts
```

如果执行上面命令出现如下错误：

ValueError: Encountered text corresponding to disallowed special token '<|endoftext|>'.
If you want this text to be encoded as a special token, pass it to `allowed_special`, e.g. `allowed_special={'<|endoftext|>', ...}`.
If you want this text to be encoded as normal text, disable the check for this token by passing `disallowed_special=(enc.special_tokens_set - {'<|endoftext|>'})`.
To disable this check for all special tokens, pass `disallowed_special=()`.

有时候重新再执行一遍，可以解决。

