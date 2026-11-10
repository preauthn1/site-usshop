<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">
                    <span v-if="objForm.id">{{ $t('编辑') }}</span>
                    <span v-else>{{ $t('添加') }}</span>{{ $t('管理员') }}
                </b>
                <div class="u-right u-5mt">
                    <el-button type="success" size="small" icon="el-icon-document-checked"
                        @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
                    <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
                </div>
            </div>
            <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
                <el-form-item :label="$t('所属角色')" prop="group_id">
                    <el-select v-model="objForm.group_id" class="u-w-block" :placeholder="$t('请选择所属角色')" clearable>
                        <el-option :label="role.title" :value="role.id" v-for="role in groupList" :key="role.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :label="$t('用户名')" prop="username">
                    <el-input v-model="objForm.username" :placeholder="$t('请输入用户名')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('登录密码')" prop="password">
                    <el-input v-model="objForm.password" :placeholder="$t('请输入登录密码，不填则为默认密码123456')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('真实姓名')" prop="realname">
                    <el-input v-model="objForm.realname" :placeholder="$t('请输入真实姓名')"></el-input>
                </el-form-item>
                <el-form-item :label="$t('头像')" prop="avatar">
                    <upload file-folder="admin" :file-arr="fileList" list-type="picture-card" :limit="1"
                        accept="image/jpeg, image/png" :tips="$t('只能上传jpg/png文件，且不超过4MB')" @getUploadFiles="getUploadFiles">
                    </upload>
                </el-form-item>
                <el-form-item :label="$t('所属代理')">
                    <ProxySearch :user_id="objForm.agent_id" @changeUserId="changeUserId" ></ProxySearch>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
import commonEdit from '@/mixins/common-edit'
import Upload from "@/components/upload/upload";
import ProxySearch from '@/components/forms/proxysearch.vue'

export default {
    components: {
        Upload,
      ProxySearch
    },
    mixins: [commonEdit],
    data() {
        return {
            apiName: 'authUser',
            backRoute: 'authUser',
            groupList: [],
            fileList: [],
            objForm: {
                id: '',
                username: '',
                password: '',
                realname: '',
                group_id: '',
                status: 1,
                agent_id: '',
                avatar: ''
            },
            rules: {
                username: [
                    { required: true, message: this.$t('请输入用户名'), trigger: 'blur' },
                    { max: 30, message: this.$t('最大长度30个字符'), trigger: 'blur' }
                ],
                password: [
                    { min: 6, max: 16, message: this.$t('密码长度6-16位'), trigger: 'blur' }
                ],
                realname: [
                    { required: true, message: this.$t('请输入真实姓名'), trigger: 'blur' },
                    { max: 30, message: this.$t('最大长度30个字符'), trigger: 'blur' }
                ],
                group_id: [
                    { required: true, message: this.$t('请选择角色'), trigger: 'blur' }
                ]
            }
        }
    },
    methods: {
        getAuthGroupList() {
            this.$api.authGroup.all().then(response => {
                if (response.code == 1) {
                    this.groupList = response.data.groupList
                }
            })
        },
        getUploadFiles(files) {
            this.fileList = files;
            this.objForm.avatar = files[0]["url"];
        },
        changeUserId(id) {
            this.objForm.agent_id = id
        }
    },
    created() {
        this.getAuthGroupList()
    }
}
</script>
