<template>
    <div class="login">
        <div class="wrapper">
            <div class="form">
                <div class="logo">
                    <img :src="logoImg" width="250" />
                </div>
                <ul class="navs u-right">
                    <li>
                        <el-dropdown>
                            <span class="el-dropdown-link">
                                <!-- Language -->
                                <span class="u-pl-10">{{ langName }}</span>
                                <i class="el-icon-arrow-down el-icon--right"></i>
                            </span>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item icon="iconfont hg-icon-jichu"
                                    @click.native="ChangeLanguage(item.code)" v-for="(item, index) in langList"
                                    :key="index">{{ item.name }}</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </li>
                </ul>
                <el-form ref="loginForm" :model="loginForm" :rules="loginRule" class="u-mt-50">
                    <el-form-item prop="username">
                        <el-input v-model="loginForm.username" :placeholder="$t('登录账号')" size="large">
                            <template slot="prepend">
                                <i class="iconfont icon-zhanghao"></i>
                            </template>
                        </el-input>
                    </el-form-item>

                    <el-form-item prop="password">
                        <el-input type="password" v-model="loginForm.password" :placeholder="$t('登录密码')" size="large">
                            <template slot="prepend">
                                <i class="iconfont icon-mima"></i>
                            </template>
                        </el-input>
                    </el-form-item>

                    <el-form-item prop="captcha">
                        <el-input v-model="loginForm.captcha" :placeholder="$t('验证码')" size="large"
                            @keyup.enter.native="onSubmit">
                            <template slot="prepend">
                                <i class="iconfont icon-yanzhengma"></i>
                            </template>

                            <template slot="append">
                                <img :src="captchaImg" class="captcha" @click="setCaptchaImg">
                            </template>
                        </el-input>
                    </el-form-item>

                    <el-form-item>
                        <el-button type="primary" size="large" @click="onSubmit" class="u-w-block">{{ $t('登 录')
                        }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>

        <div class="copyright">Copyright © {{ year }} {{ copyright }}</div>
    </div>
</template>

<script>
import { getApiUrl ,getStorage} from "@/libs/util"
import { AesEncrypt } from "@/libs/secret"
import { mapActions } from 'vuex'
import config from '@/config'

export default {
    data() {
        return {
            year: '',
            logoImg: config.logoImg,
            copyright: config.copyright,
            loginForm: {
                username: '',
                password: '',
                captcha: ''
            },
            loginRule: {
                username: [
                    { required: true, message: this.$t('请输入登录用户名'), trigger: 'blur' }
                ],
                password: [
                    { required: true, message: this.$t('请输入登录密码'), trigger: 'blur' }
                ],
                captcha: [
                    { required: true, message: this.$t('请输入验证码'), trigger: 'blur' }
                ]
            },
            captchaImg: '',
            langList: [],
            langName: "Language"
        }
    },
    methods: {
        ...mapActions([
            'handleLogin',
            'getConfig'
        ]),
        ChangeLanguage(lang) {
            this.$i18n.locale = lang;
            localStorage.setItem('admin_lang', lang);
            window.location.reload();
        },
        setCaptchaImg() {
            this.captchaImg = getApiUrl() + '/common/captcha?rand=' + Math.random()
        },
        onSubmit() {
            this.$refs['loginForm'].validate((valid) => {
                if (valid) {
                    let param = {
                        username: this.loginForm.username,
                        password: AesEncrypt(this.loginForm.password),
                        captcha: this.loginForm.captcha
                    }
                    this.handleLogin(param).then(response => {
                        if (response.code === 1) {
                            this.$router.push({ name: 'home' }).catch(() => { })
                        } else {
                            this.setCaptchaImg()
                        }
                    }).catch(() => {
                        this.setCaptchaImg()
                    })
                }
            })
        }
    },
    created() {
        this.year = this.$moment().format('YYYY')
        this.setCaptchaImg()
        this.getConfig().then(response => {
            console.log(response);
            this.langList = getStorage(config.langList,true);
            // console.log(JSON.stringify(this.langList));
            this.langList.forEach(element => {
                if(element.code==localStorage.getItem('admin_lang')){
                    this.langName = element.name;
                }
            });
        });

    }
}
</script>

<style lang="scss">
.login {
    height: 100%;
    background: url("../assets/imgs/login-bg.png") center top no-repeat;
    background-size: cover;

    .wrapper {
        position: absolute;
        z-index: 100;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #dadada;
        box-shadow: 0 9px 30px -6px rgba(0, 0, 0, .2), 0 18px 20px -10px rgba(0, 0, 0, .04), 0 18px 20px -10px rgba(0, 0, 0, .04), 0 10px 20px -10px rgba(0, 0, 0, .04);

        .form {
            padding: 40px 40px 20px 40px;

            .logo {
                text-align: center;
            }

            .el-input-group__prepend {
                padding: 0 12px;
            }

            .el-input-group__append {
                padding: 0;
            }

            .captcha {
                border-radius: 0 4px 0 4px;
                cursor: pointer;
            }

            .forget {
                text-align: center;

                a {
                    color: #888888;
                }
            }
        }
    }

    .copyright {
        position: fixed;
        z-index: 1;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        color: #ffffff;
    }
}
</style>
