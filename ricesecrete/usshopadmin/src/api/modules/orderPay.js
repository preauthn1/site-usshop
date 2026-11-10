import { get, post } from '@/libs/axios'

const orderPay = {
     // 分页列表
     page(queryData){
        return get('/orderPay/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/orderPay/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/orderPay/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/orderPay/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/orderPay/delete',{id:id})
    }
}

export default orderPay
