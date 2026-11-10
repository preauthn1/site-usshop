import {get,post} from '@/libs/axios'

const authRule = {
    // 树形数据
    tree(){
        return get('/auth/rule/tree')
    },
    // 单条信息
    info(id){
        return get('/auth/rule/info',{id:id})
    },
    // 保存
    save(formData){
        return post('/auth/rule/save',formData)
    },
    // 删除
    del(id){
        return post('/auth/rule/delete',{id:id})
    }
}

export default authRule