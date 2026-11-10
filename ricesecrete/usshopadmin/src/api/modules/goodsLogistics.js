import { get, post } from '@/libs/axios'

const goodsLogistics = {
     // 分页列表
     page(queryData){
        return get('/goodsLogistics/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/goodsLogistics/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/goodsLogistics/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/goodsLogistics/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/goodsLogistics/delete',{id:id})
    }
}

export default goodsLogistics
