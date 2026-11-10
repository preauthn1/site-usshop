import {get,post} from '@/libs/axios'

const authUser = {
    // 分页列表
    page(queryData){
        return get('/auth/user/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/auth/user/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/auth/user/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/auth/user/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/auth/user/delete',{id:id})
    },
    // 清除缓存
    delcache(id){
        return post('/auth/user/delcache',{id:id})
    }
}

export default authUser