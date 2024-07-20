英文博客原文：Vue.js: a (re)introduction

Vue.js 是一个用来开发 web 界面的前端库。它也有配套的周边工具。如果把这些东西都算在一起，那么你也可以叫它一个『前端框架』。但我个人更倾向于把它看做是一套可以灵活选择的工具组合。如果你到现在都还没听说过 Vue.js，你心里可能在想：前端的幺蛾子就是多，怎么又来一个框架？其实 Vue.js 已经开发了两年多了。第一次公开发布则是在 2014 年 2 月。这两年间它一直在不断进化，今天也已经有许多人在生产环境中使用它。

所以，Vue.js 到底提供了什么？和其他框架又有何不同？在已经有了 Angular、React、Ember 的情况下，为什么要关注 Vue ？这篇文章会简要地介绍 Vue 的特点，希望读者看完以后能自行判断。

响应式编程

都说保持状态和视图的同步是困难的。真的是这样么？

让我们从最基本的任务说起：展示数据。假设我们有这样一个对象：

varobject={message:'Hello World!'}

以及这样的模板：

id="example">  {{ message }}

我们可以用 Vue 这样将两者绑定：

newVue({el:'#example',data:object})

看上去和渲染模板没什么两样。那么当我们改动了 object 时，如何更新视图呢？答案是... 你什么都不用做。Vue 已经把 object 对象改造成了一个『响应式对象』。当你修改 object.message 的值时，渲染的 HTML 会自动更新。更关键的是，你不需要担心你是否因为是在一个 timeout 里面修改了状态而需要调用 $apply，或是需要调用 setState()，也不需要给 Flux store 上侦听一堆事件，更不需要创建一些框架专有的可观察对象，比如 ko.observable() 或是 Ember.Object.create() ... It just works。

Vue 也提供了无缝的『计算属性』：

varexample=newVue({data:{a:1},computed:{b:function(){returnthis.a+1}}})// example 实例会同时代理 a 和 b 这两个属性.example.a// -> 1example.b// -> 2example.a++example.b// -> 3

这里，计算属性 b 会将 a 作为一个依赖进行追踪，每当 a 变化，b 也自动跟着变化。你不需要特意去声明 b 的依赖，因为这本来就不应该由你来做。

组件化

你这个做小 demo 还不错，可是大项目呢？

在如何组织复杂界面的问题上，Vue 和 React 可以说是异曲同工：一切都是组件。我们可以把一开始的那个例子做成一个可复用的组件：

varExample=Vue.extend({template:'

{{ message }}

',data:function(){return{message:'Hello Vue.js!'}}})// 将该组件注册为  标签Vue.component('example',Example)

这样一来我们就可以在其他组件的模板里这样使用它：

组件可以套其他组件，最终形成一个代表了你的 UI 视图的树状结构。为了让组件之间能够有效的进行动态组构，Vue 组件可以：

- 用 props 来定义如何接收外部数据；

- 用自定义事件来向外传递消息；

- 用 API 来将外部动态传入的内容（其他组件或是 HTML）和自身模板进行组合。

这里就不过多深入细节了。有兴趣的同学可以自行查阅官方文档。

模块化

都已经 2015 年了，再不模块化都不好意思跟人打招呼。

让我们用一个模块打包工具来配合 Vue.js，比如 Webpack 或者 Browserify，然后再加上 ES2015。每一个 Vue 组件都可以看做一个独立的模块。同时因为 Vue 会自动用 Vue.extend 把对象转化为组件构建函数，我们在模块里不需要自己调用 Vue.extend，直接导出一个对象即可：

// ComponentA.jsexportdefault{template:'

{{ message }}

',data(){return{message:'Hello Vue.js!'}}}

// App.jsimportComponentAfrom'./ComponentA'exportdefault{// use another component, in this scope only.// ComponentA maps to the tag components:{ComponentA},template:`<div><p>NowI'musinganothercomponent.</p><component-a></component-a></div>`}

看上去还不错。但如果能把一个组件的模板、样式和 JavaScript 逻辑都放在同一个文件里，并且有正确的语法高亮，岂不是更妙？只要配上 vue-loader 或是 vueify，我们就能做到：

.message{color:red;}

class="message">{{ message }}

exportdefault{props:['message'],created(){console.log('MyComponent created!')}}

等一下！这不就是 Web Components 吗！而且你的 CSS 依然是全局的呢！

好吧，这确实很像是阉割版的 Web Components，但是：

- Vue 文件格式可以支持局部 CSS，只要在

- 每一个 Vue 组件最终都被编译为纯粹的 JavaScript 模块，并且不需要任何浏览器 polyfill 即可支持到最低 IE9。如果你想，你也可以把它包在一个原生的自定义元素中。

- Vue 文件的

- 你可以在每一个语言块中使用任何你想用的预处理器。

- 当使用 Webpack + vue-loader 时，你可以借助 Webpack 的强大功能将静态资源作为模块依赖来处理。

所以，只要你想，你就可以写这个样子的 Vue 组件：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190744764.jpg)

哦对了，我有没有提到，Vue 组件支持热替换？

动画

Vue 能用来做酷炫的东西不？

Vue 自带简洁易用的过渡动画系统。有很多获奖的互动类网站是用 Vue 开发的。

Vue 的反应式系统也使得它可以用来开发高效的数据驱动的逐帧动画。这一类逐帧动画在基于脏检查或是 Virtual DOM 的框架中，往往会导致性能问题，因为即使只是改了一个值，整个所处的子树（scope 或是 component）都需要重新计算。而 Vue 则是改了多少，计算多少，不会有无谓的浪费。在小 demo 中，脏检查或是 Virtual DOM 往往也足够快，但是在大型应用中可就不一定了。而且，即使足够快，那些浪费的计算也会无谓地消耗用户设备的电池。虽然在 React 中可以通过 shouldComponentUpdate 或是 Immutable data 来优化，但这都是额外的开发成本 - 相比之下，Vue 在此类用例中默认就是最优化状态。

一个用 Vue 配合逐帧动画的例子

路由

做单页应用，没有路由怎么行？

和 React 一样，Vue 本身是不带路由功能的。但是，有 vue-router 这个可选的库来配合。vue-router 让你可以将嵌套的路径映射到嵌套的组件，并且提供了细致的路径跳转控制。这里是个简单的例子：

importVuefrom'vue'importVueRouterfrom'vue-router'importAppfrom'./app.vue'importViewAfrom'./view-a.vue'importViewBfrom'./view-b.vue'Vue.use(VueRouter)constrouter=newVueRouter()router.map({'/a':{component:ViewA},'/b':{component:ViewB}})router.start(App,'#app')

app.vue 的模板：

This is the layout that won't change

如果你想看一个更全面的实例，可以参考 vue-hackernews。这个小应用用到了 Vue, vue-router, Webpack 和 vue-loader。

稳定性

这是个个人项目，会不会不稳定啊。

是的，这是个个人项目，背后并没有大公司支持的全职团队。但是，数据往往更有说服力。从 0.11 版本开始，Vue 的每一个 commit 都保持了 100% 的测试覆盖率，并且将一直保持；GitHub 上的 1400 多个 issue 平均在 13 小时内关闭。在我写下这篇文章的同时，尚未修复的可重现的 bug 数量为：零。

Vue 近日刚刚发布了 1.0，现在已经适合应用于生产环境中。如果你是从 0.12 升级到 1.0，则有配套的升级版本，全面兼容 0.12 并且带有兼容性的提示警告。以后的所有带有不兼容变化的版本，都会用这样的方式升级。

希望这篇文章让你对 Vue 有了一个比较大致的了解。我相信相对于市面上的现有方案，Vue 有着其独特的存在价值。