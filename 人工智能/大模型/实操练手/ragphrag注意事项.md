使用自定义本地模型的时候 如果是中文，不要做prompt_tune

```
python -m graphrag.prompt_tune --root ./ragtest2 --config ./ragtest2/settings.yaml --no-entity-types --language Chinese --output ./ragtest2/prompts
```
