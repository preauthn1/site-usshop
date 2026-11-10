import {get,post} from '@/libs/axios'

const activity = {
    // 分页列表
    page(queryData){
        return get('/activity/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/activity/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/activity/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/activity/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/activity/delete',{id:id})
    }
}

export default activity
