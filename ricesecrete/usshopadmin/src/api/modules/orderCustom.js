import { get, post } from '@/libs/axios'

const orderCustom = {
     // 分页列表
     page(queryData){
        return get('/orderCustom/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/orderCustom/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/orderCustom/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/orderCustom/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/orderCustom/delete',{id:id})
    }
}

export default orderCustom
