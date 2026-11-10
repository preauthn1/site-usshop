import { get, post } from '@/libs/axios'

const orderComment = {
     // 分页列表
     page(queryData){
        return get('/orderComment/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/orderComment/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/orderComment/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/orderComment/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/orderComment/delete',{id:id})
    }
}

export default orderComment
