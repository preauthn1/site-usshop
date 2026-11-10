import {get,post} from '@/libs/axios'

const account = {
    // 登录
    login({username,password,captcha}){
        return post('/account/login',{'username':username,'password':password,'captcha':captcha})
    },

    // 退出登录
    logout(){
        return post('/account/logout')
    },

    // 刷新token
    token(refresh_token){
        return post('/account/token',{refresh_token:refresh_token})
    },

    // 修改密码
    editpwd({oldpwd,newpwd}){
        return post('/account/editpwd',{'oldpwd':oldpwd,'newpwd':newpwd})
    },

    // 用户信息
    profile(){
        return get('/account/profile')
    }
}

export default account