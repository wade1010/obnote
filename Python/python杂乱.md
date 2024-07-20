python杂乱

Sanic 支持使用类型注解，下面的例子送给喜欢使用类型注解的人…

```
from sanic.response import HTTPResponse, text
from sanic.request import Request

@app.get("/typed")
async def typed_handler(request: Request) -> HTTPResponse:
    return text("Done.")
 
```