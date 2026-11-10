import {get,post} from '@/libs/axios'

const appVersion = {
    // 分页列表
    page(queryData){
        return get('/rights/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/rights/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/rights/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/rights/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/rights/delete',{id:id})
    }
}

export default appVersion
