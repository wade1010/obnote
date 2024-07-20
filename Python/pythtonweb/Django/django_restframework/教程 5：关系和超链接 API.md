目前，我们的 API 中的关系是使用主键表示的。在本教程的这一部分中，我们将通过改为使用关系的超链接来提高 API 的内聚力和可发现性。

现在我们有“代码段”和“用户”的端点，但我们没有一个 API 的入口点。为了创建一个，我们将使用一个常规的基于函数的视图和我们之前介绍的 @api_view 装饰器。在您的 snippets/views.py 中添加：

```
from rest_framework.decorators import api_view
from rest_framework.response import Response
from rest_framework.reverse import reverse


@api_view(['GET'])
def api_root(request, format=None):
    return Response({
        'users': reverse('user-list', request=request, format=format),
        'snippets': reverse('snippet-list', request=request, format=format)
    })
```

这里应该注意两件事。首先，我们使用 REST 框架的 reverse 函数来返回完全限定的 URL;其次，URL 模式由方便名称标识，我们将在后面的 1 中声明这些名称。

我们的 pastebin API 中仍然缺少的另一件明显的事情是突出显示端点的代码。

与所有其他 API 端点不同，我们不想使用 JSON，而只是呈现一个 HTML 表示形式。REST 框架提供了两种样式的 HTML 渲染器，一种用于处理使用模板渲染的 HTML，另一种用于处理预渲染的 HTML。第二个渲染器是我们想用于此终结点的渲染器。

创建代码突出显示视图时需要考虑的另一件事是，没有可以使用的现有具体通用视图。我们不返回对象实例，而是返回对象实例的属性。

我们将使用基类来表示实例，而不是使用具体的泛型视图，并创建自己的 .get() 方法。在您的 snippets/views.py 中添加：

```
from rest_framework import renderers

class SnippetHighlight(generics.GenericAPIView):
    queryset = Snippet.objects.all()
    renderer_classes = [renderers.StaticHTMLRenderer]

    def get(self, request, *args, **kwargs):
        snippet = self.get_object()
        return Response(snippet.highlighted)
```

像往常一样，我们需要将我们创建的新视图添加到我们的 URLconf 中。我们将在 snippets/urls.py 中为新的 API 根添加一个 url 模式：

```
path('', views.api_root),
```

然后为代码段突出显示添加网址模式：

```
path('snippets/<int:pk>/highlight/', views.SnippetHighlight.as_view()),
```

处理实体之间的关系是 Web API 设计中更具挑战性的方面之一。我们可以选择多种不同的方式来表示关系：

-  

使用主键。

- 在实体之间使用超链接。

- 在相关实体上使用唯一的标识辅助信息域。

- 使用相关实体的默认字符串表示形式。

- 将相关实体嵌套在父表示形式中。

- 其他一些自定义表示形式。

REST 框架支持所有这些样式，并且可以将它们应用于正向或反向关系，或者将它们应用于自定义管理器（如泛型外键）。

在本例中，我们希望在实体之间使用超链接样式。为此，我们将修改序列化程序以扩展 HyperlinkedModelSerializer 而不是现有的 ModelSerializer 。

HyperlinkedModelSerializer 与 ModelSerializer 有以下区别：

- 默认情况下，它不包括 

id 字段。

- 它包括一个 

url 字段，使用 

HyperlinkedIdentityField 。

- 关系使用 

HyperlinkedRelatedField ，而不是 

PrimaryKeyRelatedField 。

我们可以轻松地重写现有的序列化程序以使用超链接。在 snippets/serializers.py 中添加：

```
class SnippetSerializer(serializers.HyperlinkedModelSerializer):
    owner = serializers.ReadOnlyField(source='owner.username')
    highlight = serializers.HyperlinkedIdentityField(view_name='snippet-highlight', format='html')

    class Meta:
        model = Snippet
        fields = ['url', 'id', 'highlight', 'owner',
                  'title', 'code', 'linenos', 'language', 'style']


class UserSerializer(serializers.HyperlinkedModelSerializer):
    snippets = serializers.HyperlinkedRelatedField(many=True, view_name='snippet-detail', read_only=True)

    class Meta:
        model = User
        fields = ['url', 'id', 'username', 'snippets']
```

请注意，我们还添加了一个新的 'highlight' 字段。此字段与 url 字段的类型相同，只是它指向 'snippet-highlight' URL 模式，而不是 'snippet-detail' URL 模式。

由于我们包含了格式后缀的 URL，例如 '.json' ，因此我们还需要在 highlight 字段中指示它返回的任何带有后缀的格式超链接都应使用 '.html' 后缀。

如果我们要有一个超链接的API，我们需要确保我们命名我们的URL模式。让我们来看看我们需要命名哪些 URL 模式。

- 我们 API 的根是指 

'user-list' 和 

'snippet-list' 。

- 我们的代码段序列化程序包含一个引用 

'snippet-highlight' 的字段。

- 我们的用户序列化程序包含一个引用 

'snippet-detail' 的字段。

- 我们的代码段和用户序列化程序包含 

'url' 个字段，默认情况下将引用 

'{model_name}-detail' ，在本例中为 

'snippet-detail' 和 

'user-detail' 。

将所有这些名称添加到我们的 URLconf 后，我们的最终 snippets/urls.py 文件应如下所示：

```
from django.urls import path
from rest_framework.urlpatterns import format_suffix_patterns
from snippets import views

# API endpoints
urlpatterns = format_suffix_patterns([
    path('', views.api_root),
    path('snippets/',
        views.SnippetList.as_view(),
        name='snippet-list'),
    path('snippets/<int:pk>/',
        views.SnippetDetail.as_view(),
        name='snippet-detail'),
    path('snippets/<int:pk>/highlight/',
        views.SnippetHighlight.as_view(),
        name='snippet-highlight'),
    path('users/',
        views.UserList.as_view(),
        name='user-list'),
    path('users/<int:pk>/',
        views.UserDetail.as_view(),
        name='user-detail')
])
```

用户的列表视图和代码片段最终可能会返回相当多的实例，因此我们实际上希望确保对结果进行分页，并允许 API 客户端逐步浏览每个单独的页面。

我们可以通过稍微修改我们的 tutorial/settings.py 文件来更改默认列表样式以使用分页。添加以下设置：

```
REST_FRAMEWORK = {
    'DEFAULT_PAGINATION_CLASS': 'rest_framework.pagination.PageNumberPagination',
    'PAGE_SIZE': 10
}
```

请注意，REST 框架中的设置都命名空间化为一个名为 REST_FRAMEWORK 的单个字典设置，这有助于将它们与其他项目设置很好地分开。

如果需要，我们也可以自定义分页样式，但在这种情况下，我们将坚持使用默认值。

如果我们打开浏览器并导航到可浏览的 API，您会发现现在只需点击链接即可绕过 API。

您还可以在代码段实例上看到“突出显示”链接，这将带您进入突出显示的代码 HTML 表示形式。

在本教程的第 6 部分中，我们将介绍如何使用 ViewSet 和路由器来减少构建 API 所需的代码量。