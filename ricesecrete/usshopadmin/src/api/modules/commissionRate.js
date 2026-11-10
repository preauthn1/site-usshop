import {get,post} from '@/libs/axios'

const commissionRate = {
    // 分页列表
    page(queryData){
        return get('/commissionRate/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/commissionRate/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/commissionRate/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/commissionRate/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/commissionRate/delete',{id:id})
    }
}

export default commissionRate
