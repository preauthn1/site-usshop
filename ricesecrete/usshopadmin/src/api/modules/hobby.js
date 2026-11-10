import {get,post} from '@/libs/axios'

const hobby = {
    // 分页列表
    page(queryData){
        return get('/hobby/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/hobby/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/hobby/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/hobby/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/hobby/delete',{id:id})
    }
}

export default hobby
