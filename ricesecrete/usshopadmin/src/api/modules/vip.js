import {get,post} from '@/libs/axios'

const vip = {
    // 分页列表
    page(queryData){
        return get('/vip/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/vip/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/vip/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/vip/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/vip/delete',{id:id})
    }
}

export default vip
