官网文档没更新，黄色框部分没更新

[https://element.eleme.cn/#/zh-CN/component/quickstart](https://element.eleme.cn/#/zh-CN/component/quickstart)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743036.jpg)

上图黄色部分应该是指下图黄色部分

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743080.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743193.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743268.jpg)

```
<template>
	<div>
		<br>
		<el-row>
			<el-button icon="el-icon-search" circle></el-button>
			<el-button type="primary" icon="el-icon-edit" circle></el-button>
			<el-button type="success" icon="el-icon-check" circle></el-button>
			<el-button type="info" icon="el-icon-message" circle></el-button>
			<el-button type="warning" icon="el-icon-star-off" circle></el-button>
			<el-button type="danger" icon="el-icon-delete" circle></el-button>
		</el-row>
	</div>
</template>

<script>
	export default {
		name:'App',
	}
</script>
```

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743108.jpg)

```
module.exports = {
  presets: [
    '@vue/cli-plugin-babel/preset',
    ["@babel/preset-env", { "modules": false }]
  ],
  plugins: [
    [
      "component",
      {        
        "libraryName": "element-ui",
        "styleLibraryName": "theme-chalk"
      }
    ]
  ]
}
```

![](https://gitee.com/hxc8/images7/raw/master/img/202407190743067.jpg)

```
import Vue from 'vue'
import App from './App.vue'
import { Button,Row } from 'element-ui'	// 按需引入

Vue.config.productionTip = false

Vue.component(Button.name, Button);
Vue.component(Row.name, Row);
/* 或写为
 * Vue.use(Button)
 * Vue.use(Row)
 */

new Vue({
    el:"#app",
    render: h => h(App),
})
```