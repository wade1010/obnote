官网http://aui.github.io/artDialog/doc/index.html

1.直接引用

<script src="lib/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="css/ui-dialog.css">
<script src="dist/dialog-min.js"></script>

2.作为 RequireJS 或 SeaJS 的模块引入

var dialog = require('./src/dialog');

注意：内部依赖全局模块require('jquery')，请注意全局模块配置是否正确。seajs加载示例

- 如果需要支持 iframe 内容与拖拽，请引用加强版 dialog-plus.js

- jquery 最低要求版本为1.7+

快速参考

普通对话框

var d = dialog({
    title: '欢迎',
    content: '欢迎使用 artDialog 对话框组件！'
});
d.show();


模态对话框

var d = dialog({
    title: 'message',
    content: '<input autofocus />'
});
d.showModal();


气泡浮层

var d = dialog({
    content: 'Hello World!',
    quickClose: true// 点击空白处快速关闭
});
d.show(document.getElementById('quickref-bubble'));


12 个方向定位演示

添加按钮

1.确定与取消按钮：

var d = dialog({
    title: '提示',
    content: '按钮回调函数返回 false 则不许关闭',
    okValue: '确定',
    ok: function () {
        this.title('提交中…');
        return false;
    },
    cancelValue: '取消',
    cancel: function () {}
});
d.show();


2.指定更多按钮：

请参考 button 方法或参数。

控制对话框关闭

var d = dialog({
    content: '对话框将在两秒内关闭'
});
d.show();
setTimeout(function () {
    d.close().remove();
}, 2000);


给对话框左下脚添加复选框

var d = dialog({
    title: '欢迎',
    content: '欢迎使用 artDialog 对话框组件！',
    ok: function () {},
    statusbar: '<label><input type="checkbox">不再提醒</label>'
});
d.show();


阻止对话框关闭

按钮事件返回 false 则不会触发关闭。

var d = dialog({
    title: '欢迎',
    content: '欢迎使用 artDialog 对话框组件！',
    ok: function () {
        var that = this;
        this.title('正在提交..');
        setTimeout(function () {
            that.close().remove();
        }, 2000);
        return false;
    },
    cancel: function () {
        alert('不许关闭');
        return false;
    }
});
d.show();


不显示关闭按钮

var d = dialog({
    title: '欢迎',
    content: '欢迎使用 artDialog 对话框组件！',
    cancel: false,
    ok: function () {}
});
d.show();


创建 iframe 内容

artDialog 提供了一个增强版用来支持复杂的 iframe 套嵌的页面，可以在顶层页面创建一个可供 iframe 访问的对话框创建方法，例如：

seajs.use(['dialog/src/dialog-plus'], function (dialog) {
    window.dialog = dialog;
});

然后子页面就可以通过top.dialog控制对话框了。

打开示例页面

小提示：增强版的选项比标准版多了url、oniframeload这几个字段。

方法

若无特别说明，方法均支持链式调用。

show([anchor])

显示对话框。

默认居中显示，支持传入元素节点或者事件对象。

- 参数类型为HTMLElement：可吸附到元素上，同时对话框将呈现气泡样式。

- 参数类型为Event Object：根据event.pageX与event.pageY定位。

示例

var d = dialog();
d.content('hello world');
d.show(document.getElementById('api-show'));


var d = dialog({
    id: 'api-show-dialog',
    quickClose: true,
    content: '右键菜单'
});
$(document).on('contextmenu', function (event) {
    d.show(event);
    return d.destroyed;
});


showModal([anchor])

显示一个模态对话框。

其余特性与参数可参见show([anchor])方法。

示例

var d = dialog({
    title: 'message',
    content: '<input autofocus />'
});
d.showModal();


close([result])

关闭（隐藏）对话框。

可接收一个返回值，可以参见 returnValue。

注意：close()方法只隐藏对话框，不会在 DOM 中删除，删除请使用remove()方法。

remove()

销毁对话框。

注意：不同于close([result])方法，remove()方法会从 DOM 中移出对话框相关节点，销毁后的对话框无法再次使用。

对话框按钮点击后默认会依次触发 close()、remove() 方法。如果想手动控制对话框关闭可以如下操作：

var d = dialog();
// [..]
d.close().remove();


content(html)

写入对话框内容。

html参数支持String、HTMLElement类型。

示例

传入字符串：

var d = dialog();
d.content('hello world');
d.show();


传入元素节点：

var elem = document.getElementById('test');
dialog({
    content: elem,
    id: 'EF893L'
}).show();


title(text)

写入对话框标题。

示例

var d = dialog();
d.title('hello world');
d.show();


width(value)

设置对话框宽度。

示例

dialog({
    content: 'hello world'
})
.width(320)
.show();


height(value)

设置对话框高度。

示例

dialog({
    content: 'hello world'
})
.height(320)
.show();


reset()

手动刷新对话框位置。

通常动态改变了内容尺寸后需要刷新对话框位置。

button(args)

自定义按钮。

参数请参考 选项button；同时支持传入 HTML 字符串填充按钮区域。

focus()

聚焦对话框（置顶）。

blur()

让对话框失去焦点。

addEventListener(type, callback)

添加事件。

支持的事件有：show、close、beforeremove、remove、reset、focus、blur

removeEventListener(type, callback)

删除事件。

dialog.get(id)

根据获取打开的对话框实例。

注意：这是一个静态方法。

dialog.getCurrent()

获取当前（置顶）对话框实例。

注意：这是一个静态方法。

配置参数

content

设置消息内容。

类型

String, HTMLElement

示例

传入字符串：

dialog({
    content: 'hello world!'
}).show();


传入元素节点：

var elem = document.getElementById('test');
dialog({
    content: elem,
    id: 'EF893L'
}).show();


title

标题内容。

类型

String

示例

dialog({
    title: 'hello world!'
}).show();


statusbar

状态栏区域 HTML 代码。

可以实现类似“不再提示”的复选框。注意：必须有按钮才会显示。

类型

String

示例

var d = dialog({
    title: '欢迎',
    content: '欢迎使用 artDialog 对话框组件！',
    ok: function () {},
    statusbar: '<label><input type="checkbox">不再提醒</label>'
});
d.show();


ok

确定按钮。

回调函数this指向dialog对象，执行完毕默认关闭对话框，若返回 false 则阻止关闭。

类型

Function

示例

dialog({
    ok: function () {
        this
        .title('消息')
        .content('hello world!')
        .width(130);
        return false;
    }
}).show();


okValue

(默认值: "ok") 确定按钮文本。

类型

String

示例

dialog({
    okValue: '猛击我',
    ok: function () {
        this.content('hello world!');
        return false;
    }
}).show();


cancel

取消按钮。

取消按钮也等同于标题栏的关闭按钮，若值为false则不显示关闭按钮。回调函数this指向dialog对象，执行完毕默认关闭对话框，若返回false则阻止关闭。

类型

Function, Boolean

示例

dialog({
    title: '消息',
    ok: function () {},
    cancel: function () {
        alert('取消');
    }
}).show();


dialog({
    title: '消息',
    content: '不显示关闭按钮',
    ok: function () {},
    cancel: false
}).show();


cancelValue

(默认值: "cancel") 取消按钮文本。

类型

String

示例

dialog({
    cancelValue: '取消我',
    cancel: function () {
        alert('关闭');
    }
}).show();


cancelDisplay

(默认值: true) 是否显示取消按钮。

类型

Boolean

示例

dialog({
    title: '提示',
    content: '这是一个禁止关闭的对话框，并且没有取消按钮',
    cancel: function () {
        alert('禁止关闭');
        return false;
    },
    cancelDisplay: false
}).show();


button

自定义按钮组。

类型

Array

选项

| 名称 | 类型 | 描述 |
| - | - | - |
| value | String | 按钮显示文本 |
| callback | Function | (可选) 回调函数this指向dialog对象，执行完毕默认关闭与销毁对话框（依次执行close()与remove()），若返回false则阻止关闭与销毁 |
| autofocus | Boolean | (默认值:false) 是否自动聚焦 |
| disabled | Boolean | (默认值: false) 是否禁用 |


示例

dialog({
    button: [
        {
            value: '同意',
            callback: function () {
                this
                .content('你同意了');
                return false;
            },
            autofocus: true
        },
        {
            value: '不同意',
            callback: function () {
                alert('你不同意')
            }
        },
        {
            id: 'button-disabled',
            value: '无效按钮',
            disabled: true
        },
        {
            value: '关闭我'
        }
    ]
}).show();


width

设置对话框 内容 宽度。

类型

String, Number

示例

dialog({
    width: 460
}).show();


dialog({
    width: '20em'
}).show();


height

设置对话框 内容 高度。

类型

String, Number

示例

dialog({
    height: 460
}).show();


dialog({
    height: '20em'
}).show();


skin

设置对话框额外的className参数。

多个className请使用空格隔开。

类型

String

示例

dialog({
    skin: 'min-dialog tips'
}).show();


padding

(默认值: 继承 css 文件设置) 设置消息内容与消息容器的填充边距，即 style padding属性

类型

String

示例

dialog({
    content: 'hello world',
    padding: 0
}).show();


fixed

(默认值: false) 开启固定定位。

固定定位是 css2.1 position的一个属性，它能固定在浏览器某个地方，也不受滚动条拖动影响。IE6 与部分移动浏览器对其支持不好，内部会转成绝对定位。

类型

Boolean

示例

dialog({
    fixed: true,
    title: '消息',
    content: '请拖动滚动条查看'
}).show();


align

(默认值: "bottom left") 设置对话框与其他元素的对齐方式。

如果show(elem)与showModal(elem)传入元素，align参数方可生效，支持如下对齐方式：

- "top left"

- "top"

- "top right"

- "right top"

- "right"

- "right bottom"

- "bottom right"

- "bottom"

- "bottom left"

- "left bottom"

- "left"

- "left top"

类型

String

示例

var d = dialog({
    align: 'left',
    content: 'Hello World!',
    quickClose: true
});
d.show(document.getElementById('option-align'));


12 个方向定位演示

autofocus

(默认值: true) 是否支持自动聚焦。

类型

Boolean

quickClose

(默认值: false) 是否点击空白出快速关闭。

类型

Boolean

示例

var d = dialog({
    content: '点击空白处快速关闭',
    quickClose: true
});
d.show(document.getElementById('option-quickClose'));


zIndex

(默认值: 1024) 重置全局zIndex初始值，用来改变对话框叠加高度。

比如有时候配合外部浮动层 UI 组件，但是它们可能默认zIndex没有对话框高，导致无法浮动到对话框之上，这个时候你就可以给对话框指定一个较小的zIndex值。

请注意这是一个会影响到全局的配置，后续出现的对话框叠加高度将重新按此累加。

类型

Number

示例

dialog({
    zIndex: 87
}).show();


onshow

对话框打开的事件。

回调函数this指向dialog对象。

类型

Function

示例

var d = dialog({
    content: 'loading..',
    onshow: function () {
        this.content('dialog ready');
    }
});
d.show();


onclose

对话框关闭后执行的事件。

回调函数this指向dialog对象。

类型

Function

示例

var d = dialog({
    onclose: function () {
        alert('对话框已经关闭');
    },
    ok: function () {}
});
d.show();


onbeforeremove

对话框销毁之前事件。

回调函数this指向dialog对象。

类型

Function

onremove

对话框销毁事件。

回调函数this指向dialog对象。

类型

Function

示例

var d = dialog({
    onclose: function () {
        alert('对话框已经关闭');
    },
    onremove: function () {
        alert('对话框已经销毁');
    },
    ok: function () {}
});
d.show();


onfocus

对话框获取焦点事件。

回调函数this指向dialog对象。

类型

Function

onblur

对话框失去焦点事件。

回调函数this指向dialog对象。

类型

Function

onreset

对话框位置重置事件。

回调函数this指向dialog对象。

类型

Function

id

设定对话框唯一标识。

1. 可防止重复 ID 对话框弹出。

1. 支持使用dialog.get(id)获取某个对话框的接口。

类型

String

示例

dialog({
    id: 'id-demo',
    content: '再次点击运行看看'
}).show();
dialog.get('id-demo').title('8888888888');


属性

open

判断对话框是否被打开。

returnValue

对话框返回值。

示例

var d = dialog({
    title: '消息',
    content: '<input id="property-returnValue-demo" value="1" />',
    ok: function () {
        var value = $('#property-returnValue-demo').val();
        this.close(value);
        this.remove();
    }
});
d.addEventListener('close', function () {
    alert(this.returnValue);
});
d.show();


其他

自定义样式

打开配置文件： src/dialog-config.js，其中cssUir字段是 css 文件的路径，innerHTML字段则是 artDialog 的模板。修改这两个字段即可很方便的设计属于自己的皮肤。

一套皮肤可以添加不同的className实现多种状态，可参考 skin 选项。

源码构建

使用GruntJS在 artDialog 源码根目录执行即可。

artDialog v5 升级 v6 参考

https://github.com/aui/artDialog/wiki/artDialog-v5%E5%8D%87%E7%BA%A7v6%E5%8F%82%E8%80%83