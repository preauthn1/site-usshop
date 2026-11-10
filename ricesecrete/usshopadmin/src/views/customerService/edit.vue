<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">
                    <span v-if="objForm.id">编辑</span>
                    <span v-else>添加</span>客服
                </b>
                <div class="u-right u-5mt">
                    <el-button type="success" size="small" icon="el-icon-document-checked"
                               @click="submitForm('objForm')">提交保存
                    </el-button>
                    <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
                </div>
            </div>
            <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
                <el-form-item label="客服类型" prop="type">
                    <el-select v-model="objForm.type">
                        <el-option label="电话" :value="1"></el-option>
                        <el-option label="企业微信" :value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="客服标题" prop="title">
                    <el-input v-model="objForm.title" placeholder="请输入客服标题" maxlength="30"
                              show-word-limit></el-input>
                </el-form-item>
                <el-form-item label="客服内容" prop="content">
                    <el-input v-model="objForm.content" placeholder="请输入客服内容" maxlength="30"
                              show-word-limit></el-input>
                </el-form-item>


                <el-form-item label="排序">
                    <el-input-number v-model="objForm.sort" :min="0" label="数值越大权重越高"></el-input-number>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";

export default {
    components: {},
    mixins: [commonEdit],
    data() {
        return {
            apiName: "customerService",
            backRoute: "customerServiceIndex",
            fileList: [],
            fileList2: [], //详情
            objForm: {
                id: "",
                title: "",
                content: "",
                status: 1,
                sort: 0,
            },
            rules: {
                title: [
                    {
                        required: true,
                        message: "请输入客服标题",
                        trigger: "blur",
                    },
                    {
                        max: 10,
                        message: "最大长度10个字符",
                        trigger: "blur",
                    },
                ],
                content: [
                    {
                        required: true,
                        message: "请输入客服内容",
                        trigger: "blur",
                    },
                ],
                type: [
                    {
                        required: true,
                        message: "请选择客服类型",
                        trigger: "change"
                    }
                ],
                region: [
                    { required: true, message: '请选择活动区域', trigger: 'change' }
                ],
            },
        };
    },
    methods: {},
};
</script>
