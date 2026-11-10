import {get,post} from '@/libs/axios'

const withdrawal = {
    // 分页列表
    page(queryData){
        return get('/withdrawal/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/withdrawal/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/withdrawal/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/withdrawal/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/withdrawal/delete',{id:id})
    }
}

export default withdrawal
