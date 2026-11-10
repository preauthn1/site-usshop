import { get, post } from '@/libs/axios'

const goodsSpec = {
     // 分页列表
     page(queryData){
        return get('/goodsSpec/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/goodsSpec/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/goodsSpec/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/goodsSpec/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/goodsSpec/delete',{id:id})
    }
}

export default goodsSpec
