import { get, post } from '@/libs/axios'

const orderDamages = {
     // 分页列表
     page(queryData){
        return get('/orderDamages/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/orderDamages/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/orderDamages/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/orderDamages/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/orderDamages/delete',{id:id})
    }
}

export default orderDamages
