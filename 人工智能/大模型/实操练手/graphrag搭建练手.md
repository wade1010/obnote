conda create -n graphrag python=3.10
conda activate graphrag

```bash
pip install graphrag
```

```sh
mkdir -p ./ragtest/input
```

```sh
curl https://www.gutenberg.org/cache/epub/24022/pg24022.txt > ./ragtest/input/book.txt
```

```sh
python -m graphrag.index --init --root ./ragtest
```

vim ragtest/.env
修改为你自己的key
```
GRAPHRAG_API_KEY=sk-1UIybqycAYSTCUBf42C5757cBf94492a9c2534A8xxxxxxx
```

