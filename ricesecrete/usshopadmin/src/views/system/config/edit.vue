<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">
                    <span v-if="objForm.id">{{ $t('编辑') }}</span>
                    <span v-else>{{ $t('添加') }}</span>{{ $t('参数') }}
                </b>
                <div class="u-right u-5mt">
                    <el-button type="success" size="small" icon="el-icon-document-checked" @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
                    <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
                </div>
            </div>
            <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
                <el-form-item :label="$t('参数名称')" prop="name">
                    <el-input v-model="objForm.name" :placeholder="$t('请输入参数名称')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('参数键值')" prop="key">
                    <el-input v-model="objForm.key" :placeholder="$t('请输入参数键值')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('参数值')" prop="value">
                    <el-input v-model="objForm.value" :placeholder="$t('请输入参数值')"></el-input>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
    import commonEdit from '@/mixins/common-edit'

    export default {
        mixins:[commonEdit],
        data() {
            return {
                apiName:'sysConfig',
                backRoute:'sysConfig',
                groupList:[],
                objForm: {
                    id:'',
                    name: '',
                    key: '',
                    value: ''
                },
                rules: {
                    name: [
                        { required: true, message: this.$t('请输入参数名称'), trigger: 'blur' },
                        { max: 50, message: this.$t('最大长度50个字符'), trigger: 'blur' }
                    ],
                    key: [
                    { required: true, message: this.$t('请输入参数键值'), trigger: 'blur' },
                        { max: 50, message: this.$t('最大长度50个字符'), trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {

        },
        created() {

        }
    }
</script>
