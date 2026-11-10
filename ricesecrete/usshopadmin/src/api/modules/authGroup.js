import {get,post} from '@/libs/axios'

const authGroup = {
    // 分页列表
    page(queryData){
        return get('/auth/group/page',queryData)
    },
    // 所有列表
    all(){
        return get('/auth/group/all')
    },
    // 单条信息
    info(id){
        return get('/auth/group/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/auth/group/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/auth/group/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/auth/group/delete',{id:id})
    }
}

export default authGroup