import {get,post} from '@/libs/axios'

const bulletin = {
    // 分页列表
    page(queryData){
        return get('/bulletin/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/bulletin/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/bulletin/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/bulletin/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/bulletin/delete',{id:id})
    }
}

export default bulletin
