import { get, post } from '@/libs/axios'

const goodsExplain = {
     // 分页列表
     page(queryData){
        return get('/goodsExplain/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/goodsExplain/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/goodsExplain/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/goodsExplain/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/goodsExplain/delete',{id:id})
    }
}

export default goodsExplain
