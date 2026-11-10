import { get, post } from '@/libs/axios'

const goodsProduct = {
     // 分页列表
     page(queryData){
        return get('/goodsProduct/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/goodsProduct/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/goodsProduct/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/goodsProduct/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/goodsProduct/delete',{id:id})
    }
}

export default goodsProduct
