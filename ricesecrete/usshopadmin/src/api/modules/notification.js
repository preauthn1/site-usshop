import {get,post} from '@/libs/axios'

const notification = {
    // 分页列表
    page(queryData){
        return get('/notification/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/notification/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/notification/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/notification/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/notification/delete',{id:id})
    }
}

export default notification