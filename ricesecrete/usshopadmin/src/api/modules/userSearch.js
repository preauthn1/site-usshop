import { get, post } from '@/libs/axios'

const userSearch = {
     // 分页列表
     page(queryData){
        return get('/userSearch/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/userSearch/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/userSearch/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/userSearch/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/userSearch/delete',{id:id})
    }
}

export default userSearch
