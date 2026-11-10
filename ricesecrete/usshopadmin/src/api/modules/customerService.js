import {get,post} from '@/libs/axios'

const customerService = {
    // 分页列表
    page(queryData){
        return get('/customerService/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/customerService/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/customerService/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/customerService/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/customerService/delete',{id:id})
    }
}

export default customerService
