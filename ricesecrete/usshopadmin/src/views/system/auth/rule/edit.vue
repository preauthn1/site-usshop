<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">
                    <span v-if="objForm.id">{{$t('编辑')}}</span>
                    <span v-else>{{$t('添加')}}</span>{{$t('权限')}}
                </b>
                <div class="u-right u-5mt">
                    <el-button type="success" size="small" icon="el-icon-document-checked" @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
                    <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
                </div>
            </div>
            <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
                <el-form-item :label="$t('权限标识')" prop="name">
                    <el-input v-model="objForm.name" :placeholder="$t('请输入权限标识')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('权限名称')" prop="title">
                    <el-input v-model="objForm.title" :placeholder="$t('请输入权限名称')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('权限类型')">
                    <el-radio-group v-model="objForm.type">
                        <el-radio :label="0">{{$t('目录')}}</el-radio>
                        <el-radio :label="1">{{$t('菜单')}}</el-radio>
                        <el-radio :label="2">{{$t('页面')}}</el-radio>
                        <el-radio :label="3">{{$t('按钮元素')}}</el-radio>
                        <el-radio :label="4">{{$t('接口')}}</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item :label="$t('父级权限')">
                    <el-cascader expand-trigger="hover"
                                 :options="levelTree"
                                 @change="handleLevelSelect"
                                 :placeholder="$t('请选择父级')"
                                 clearable
                                 change-on-select
                                 :props="levelProps"
                                 v-model="level_ids"
                                 class="u-w-block">
                    </el-cascader>
                </el-form-item>
                <template v-if="objForm.type!=3">
                    <el-form-item :label="$t('URL地址')" prop="url" v-if="objForm.type>0">
                        <el-input v-model="objForm.url" :placeholder="$t('请输入URL地址')"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('URL参数')" prop="url_param" v-if="objForm.type==2">
                        <el-input v-model="objForm.url_param" :placeholder="$t('请输入URL参数')"></el-input>
                    </el-form-item>
                </template>
                <el-form-item :label="$t('请求类型')" v-if="objForm.type==4">
                    <el-radio-group v-model="objForm.api_method">
                        <el-radio :label="1">get</el-radio>
                        <el-radio :label="2">post</el-radio>
                        <el-radio :label="3">delete</el-radio>
                        <el-radio :label="4">put</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item :label="$t('菜单图标')" prop="icon" v-if="objForm.type<2">
                    <el-input v-model="objForm.icon" :placeholder="$t('请输入菜单图标')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('排序')">
                    <el-input-number v-model="objForm.rank" :min="0" :label="$t('数值越大权重越高')"></el-input-number>
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
                apiName:'authRule',
                backRoute:'authRule',
                levelTree:[],
                level_ids:[],
                levelProps:{
                    label:'title',
                    value: 'id',
                    children: 'children'
                },
                objForm: {
                    id:'',
                    name: '',
                    title:'',
                    url: '',
                    url_param: '',
                    icon: '',
                    type: 0,
                    api_method: '',
                    rank: '',
                    parent_id: 0
                },
                rules: {
                    name: [
                        { required: true, message: '请输入权限标识', trigger: 'blur' },
                        { max: 30, message: '最大长度30个字符', trigger: 'blur' }
                    ],
                    title: [
                        { required: true, message: '请输入权限名称', trigger: 'blur' },
                        { max: 30, message: '最大长度30个字符', trigger: 'blur' }
                    ],
                    url: [
                        { max: 200, message: '最大长度200个字符', trigger: 'blur' }
                    ],
                    url_param: [
                        { max: 30, message: '最大长度30个字符', trigger: 'blur' }
                    ],
                    icon: [
                        { max: 60, message: '最大长度60个字符', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            afterGetInfo(){
                this.level_ids = this.objForm.pids
            },
            getLevelTree(){
                this.$api.authRule.tree().then(response => {
                    if(response.code==1){
                        this.levelTree = response.data.treeData
                    }
                })
            },
            handleLevelSelect(val){
                this.objForm.parent_id = val[val.length-1]
            }
        },
        created() {
            this.getLevelTree()
        }
    }
</script>
