import {get,post} from '@/libs/axios'

const banner = {
    // 分页列表
    page(queryData){
        return get('/banner/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/banner/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/banner/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/banner/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/banner/delete',{id:id})
    }
}

export default banner