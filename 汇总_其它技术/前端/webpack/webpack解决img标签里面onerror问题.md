<img alt="good.name" class="img-rounded img-responsive"

     :onerror="defaultImg"

     :src="good.pic" :alt="good.name">





BNZ8FGQTGKWVUL6KPN225WC4W8FNEYBSBQPMM4JANBCS5JXD7HBR4A23A649





import noImg from '../../images/noimg.png'

module.exports = {

data () {

    return {

      

        defaultImg: 'this.src="' + noImg + '"',

    }

},

}









思路来源文档：



https://github.com/webplus/blog/blob/master/README.md





|   |
| - |
| 静态文件.html中图片路径替换<br>&lt;!DOCTYPE html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>    &lt;meta charset="utf-8"&gt;<br>    &lt;meta http-equiv="X-UA-Compatible" content="IE=edge"&gt;<br>    &lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;<br>    &lt;title&gt;App&lt;/title&gt;<br>&lt;/head&gt;<br>&lt;body&gt;<br>    &lt;div id="root" class=""&gt;&lt;/div&gt;<br>    &lt;img src="./publics/img/login.png" alt=这里的路径按项目的实际相对路径，经webpack处理后的生产路径static/img/login.png""&gt;<br>&lt;/body&gt;<br>&lt;/html&gt;<br>关键点是想要webpack处理需要加html-loader,否则html中的路径原样输出<br>     [{<br>          test: /\\.(jpe?g|png|gif|svg)$/,<br>          loader: 'url',<br>          query: {<br>            limit: 100,<br>            name: 'static/img/[name].[ext]?[hash:7]'<br>          },<br>          exclude: /node\_modules/,<br>          include: path.resolve(\_\_dirname, '../src')<br>      }, {<br>          test: /\\.html$/,<br>          loader: "html"<br>        }<br>      ]<br>React.js组件中的img标签中径处理<br>尝试了好几种直接在scr上写路径的方法，绝对、相对地址都试了，webpack还是识别不了。<br>最终想起一句描述webpack的话：webpack中一切都是资源，通过下面这种方式可以替换图标路径。<br>// 引用资源<br>import img from '../../publics/img/login.png'<br>export default class Header extends React.Component {<br>  render() {<br>    return (<br>      &lt;div className="ant-layout-header clearfix"&gt;<br>            &lt;img src={img} alt="使用资源"/&gt;<br>        &lt;/div&gt;<br>    )<br>  }<br>} |


![5625786?s=180&v=4](https://gitee.com/hxc8/images7/raw/master/img/202407190741147.jpg)

OwnerAuthor

webplus commented on 22 Jul 2016

|   |
| - |
| 补充下图片优化的插件<br>https://www.npmjs.com/package/image-webpack-loader |


![5625786?s=180&v=4](https://gitee.com/hxc8/images7/raw/master/img/202407190741147.jpg)

OwnerAuthor

webplus commented on 22 Jul 2016

|   |
| - |
| 补充使用html模板的情况<br>var html = require('html-withimg-loader!./xxx.html');<br><br>plugins: [<br>    new HtmlWebpackPlugin({<br>        template: 'html-withimg-loader!' + path.resolve(srcDir, filename),<br>        filename: filename<br>    }),<br>] |


