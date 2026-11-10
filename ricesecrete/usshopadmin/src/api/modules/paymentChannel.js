import {get,post} from '@/libs/axios'

const appVersion = {
    // 分页列表
    page(queryData){
        return get('/paymentChannel/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/paymentChannel/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/paymentChannel/save',formData)
    },
    // 保存状态
    status(id, column, status){
        return post('/paymentChannel/status',{'id':id,'column':column,'status':status})
    },
    // 删除
    del(id){
        return post('/paymentChannel/delete',{id:id})
    }
}

export default appVersion
