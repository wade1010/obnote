REST 框架包括一个用于处理 ViewSets 的抽象，它允许开发人员专注于对 API 的状态和交互进行建模，并根据通用约定自动处理 URL 构造。

ViewSet 类与 View 类几乎相同，只是它们提供 retrieve 或 update 等操作，而不是 get 或 put 等方法处理程序。

ViewSet 类仅在最后一刻绑定到一组方法处理程序，当它实例化为一组视图时，通常通过使用 Router 类来处理为您定义 URL conf 的复杂性。

让我们采用当前的视图集，并将它们重构为视图集。

首先，让我们将 UserList 和 UserDetail 视图重构为一个 UserViewSet 。我们可以删除这两个视图，并用单个类替换它们：

```
from rest_framework import viewsets

class UserViewSet(viewsets.ReadOnlyModelViewSet):
    """
    This viewset automatically provides `list` and `retrieve` actions.
    """
    queryset = User.objects.all()
    serializer_class = UserSerializer
```

在这里，我们使用 ReadOnlyModelViewSet 类自动提供默认的“只读”操作。我们仍然像使用常规视图时一样设置 queryset 和 serializer_class 属性，但我们不再需要向两个单独的类提供相同的信息。

接下来，我们将替换 SnippetList 、 SnippetDetail 和 SnippetHighlight 视图类。我们可以删除这三个视图，然后再次将它们替换为单个类。

```
from rest_framework.decorators import action
from rest_framework.response import Response
from rest_framework import permissions

class SnippetViewSet(viewsets.ModelViewSet):
    """
    This viewset automatically provides `list`, `create`, `retrieve`,
    `update` and `destroy` actions.

    Additionally we also provide an extra `highlight` action.
    """
    queryset = Snippet.objects.all()
    serializer_class = SnippetSerializer
    permission_classes = [permissions.IsAuthenticatedOrReadOnly,
                          IsOwnerOrReadOnly]

    @action(detail=True, renderer_classes=[renderers.StaticHTMLRenderer])
    def highlight(self, request, *args, **kwargs):
        snippet = self.get_object()
        return Response(snippet.highlighted)

    def perform_create(self, serializer):
        serializer.save(owner=self.request.user)
```

这次我们使用 ModelViewSet 类来获取完整的默认读写操作集。

请注意，我们还使用 @action 装饰器创建了一个名为 highlight 的自定义操作。此修饰器可用于添加任何不适合标准 create / update / delete 样式的自定义终结点。

默认情况下，使用 @action 修饰器的自定义操作将响应 GET 个请求。如果我们想要一个响应 POST 个请求的操作，我们可以使用 methods 参数。

默认情况下，自定义操作的 URL 取决于方法名称本身。如果要更改构造 url 的方式，可以包含 url_path 作为装饰器关键字参数。

处理程序方法仅在我们定义 URLConf 时绑定到操作。为了了解幕后发生了什么，让我们首先从我们的 ViewSet 显式创建一组视图。

在 snippets/urls.py 文件中，我们将 ViewSet 类绑定到一组具体视图中。

```
from snippets.views import SnippetViewSet, UserViewSet, api_root
from rest_framework import renderers

snippet_list = SnippetViewSet.as_view({
    'get': 'list',
    'post': 'create'
})
snippet_detail = SnippetViewSet.as_view({
    'get': 'retrieve',
    'put': 'update',
    'patch': 'partial_update',
    'delete': 'destroy'
})
snippet_highlight = SnippetViewSet.as_view({
    'get': 'highlight'
}, renderer_classes=[renderers.StaticHTMLRenderer])
user_list = UserViewSet.as_view({
    'get': 'list'
})
user_detail = UserViewSet.as_view({
    'get': 'retrieve'
})
```

请注意我们如何通过将 http 方法绑定到每个视图所需的操作，从每个 ViewSet 类创建多个视图。

现在我们已经将资源绑定到具体的视图中，我们可以像往常一样使用 URL conf 注册视图。

```
urlpatterns = format_suffix_patterns([
    path('', api_root),
    path('snippets/', snippet_list, name='snippet-list'),
    path('snippets/<int:pk>/', snippet_detail, name='snippet-detail'),
    path('snippets/<int:pk>/highlight/', snippet_highlight, name='snippet-highlight'),
    path('users/', user_list, name='user-list'),
    path('users/<int:pk>/', user_detail, name='user-detail')
])
```

因为我们使用的是 ViewSet 个类而不是 View 个类，所以我们实际上不需要自己设计 URL conf。将资源连接到视图和 url 的约定可以使用 Router 类自动处理。我们需要做的就是向路由器注册适当的视图集，然后让它完成剩下的工作。

这是我们重新连线的 snippets/urls.py 文件。

```
from django.urls import path, include
from rest_framework.routers import DefaultRouter
from snippets import views

# Create a router and register our viewsets with it.
router = DefaultRouter()
router.register(r'snippets', views.SnippetViewSet,basename="snippet")
router.register(r'users', views.UserViewSet,basename="user")

# The API URLs are now determined automatically by the router.
urlpatterns = [
    path('', include(router.urls)),
]
```

将视图集注册到路由器类似于提供 urlpattern。我们包括两个参数 - 视图的 URL 前缀和视图集本身。

我们使用的 DefaultRouter 类也会自动为我们创建 API 根视图，因此我们现在可以从 views 模块中删除 api_root 方法。

使用视图集可能是一个非常有用的抽象。它有助于确保 URL 约定在整个 API 中保持一致，最大限度地减少需要编写的代码量，并允许您专注于 API 提供的交互和表示形式，而不是 URL conf 的细节。

这并不意味着它总是正确的方法。与使用基于类的视图而不是基于函数的视图时，需要考虑一组类似的权衡。使用视图集不如单独构建视图那么明确。