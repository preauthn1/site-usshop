import {get,post} from '@/libs/axios'

const moneyLog = {
    // 分页列表
    page(queryData){
        return get('/moneyLog/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/moneyLog/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/moneyLog/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/moneyLog/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/moneyLog/delete',{id:id})
    }
}

export default moneyLog
