/**
 * 验证手机号
 * @param rule
 * @param value
 * @param callback
 */
export function validPhone(rule, value,callback){
    const reg = /^1[3|4|5|6|7|8|9][0-9]\d{8}$/
    if (!value){
        callback(new Error('请输入手机号码'))
    }else  if (!reg.test(value)){
        callback(new Error('请输入正确的11位手机号码'))
    }else {
        callback()
    }
}