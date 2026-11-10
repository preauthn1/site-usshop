import {get,post} from '@/libs/axios'

const appVersion = {
    // 分页列表
    page(queryData){
        return get('/appVersion/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/appVersion/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/appVersion/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/appVersion/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/appVersion/delete',{id:id})
    }
}

export default appVersion
