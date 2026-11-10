import { get, post } from '@/libs/axios'

const sysLog = {
     // 分页列表
     page(queryData){
        return get('/sysLog/page',queryData)
    },
    // 单条信息
    info(id){
        return get('/sysLog/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/sysLog/save',formData)
    },
    // 保存状态
    status(id,status){
        return post('/sysLog/status',{'id':id,'status':status})
    },
    // 删除
    del(id){
        return post('/sysLog/delete',{id:id})
    }
}

export default sysLog
