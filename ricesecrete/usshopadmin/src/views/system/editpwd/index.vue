<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">{{$t('修改密码')}}</b>
                <div class="u-right u-5mt">
                    <el-button type="success" size="small" icon="el-icon-document-checked" @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
                    <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
                </div>
            </div>
            <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
                <el-form-item :label="$t('原密码')" prop="oldpwd">
                    <el-input type="password" v-model="objForm.oldpwd"></el-input>
                </el-form-item>
                <el-form-item :label="$t('新密码')" prop="newpwd">
                    <el-input type="password" v-model="objForm.newpwd"></el-input>
                </el-form-item>
                <el-form-item :label="$t('重复新密码')" prop="surepwd">
                    <el-input type="password" v-model="objForm.surepwd"></el-input>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
    import { AesEncrypt } from "@/libs/secret"
    import { mapActions } from 'vuex'

    export default {
        data() {
            let valPassRepeat = (rule, value, callback) => {
              let that = this;
                if (value === "") {
                    callback(new Error(that.$t('请再次输入密码')))
                } else if (value !== this.objForm.newpwd) {
                    callback(new Error(that.$t('两次输入密码不一致!')))
                } else {
                    callback()
                }
            };
            return {
                objForm: {
                    oldpwd: '',
                    newpwd: '',
                    surepwd: ''
                },
                rules: {
                    oldpwd: [
                        { required: true, message: this.$t('请输入原密码'), trigger: 'blur' }
                    ],
                    newpwd: [
                        { required: true, message: this.$t('请输入新密码'), trigger: 'blur' },
                        { min:6, max: 16, message: this.$t('密码必须在6-16个字符之间'), trigger: 'blur' }
                    ],
                    surepwd: [
                        { required: true, validator: valPassRepeat, trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            ...mapActions([
                'handleLogOut'
            ]),
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        let param = {
                            oldpwd: AesEncrypt(this.objForm.oldpwd),
                            newpwd: AesEncrypt(this.objForm.newpwd)
                        }
                        this.$api.account.editpwd(param).then(response => {
                            if(response.code==1){
                                this.$message({
                                    message:this.$t('密码修改成功，请重新登录'),
                                    type:"success"
                                })
                                this.handleLogOut()
                            }
                        })
                    }
                    return false
                })
            }
        }
    }
</script>
