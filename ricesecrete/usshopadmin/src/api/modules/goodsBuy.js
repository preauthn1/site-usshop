import { get, post } from '@/libs/axios'

const goodsBuy = {
     // 分页列表
     page(queryData){
        return get('/goodsBuy/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/goodsBuy/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/goodsBuy/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/goodsBuy/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/goodsBuy/delete',{id:id})
    }
}

export default goodsBuy
