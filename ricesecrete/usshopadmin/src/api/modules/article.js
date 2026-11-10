import {get,post} from '@/libs/axios'

const article = {
    // 分页列表
    page(queryData){
        return get('/article/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/article/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/article/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/article/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/article/delete',{id:id})
    }
}

export default article
