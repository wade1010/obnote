
```
 <el-form ref="tableform" class="click-table2-form" size="mini" :inline="true" :model="formData">
                <el-form-item title="名字" prop="name">
                    <el-input v-model="formData.name" placeholder="名字"></el-input>
                </el-form-item>
                <el-form-item title="角色" prop="role">
                    <el-input v-model="formData.role" placeholder="请输入角色"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="searchEvent">查询</el-button>
                    <el-button @click="$refs.tableform.resetFields()">重置</el-button>
                </el-form-item>
            </el-form>
```



```
<script>
import {Form, FormItem, Input,Button} from 'element-ui';
export default {
        data() {
            return {
            }
        },
        methods: {
            
        },
        components: {
            elForm: Form,
            elFormItem: FormItem,
            elInput: Input,
            elButton: Button,
        }
    }
</script>
```



