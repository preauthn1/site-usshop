import {get,post} from '@/libs/axios';

const recharge = {
    // 分页列表
    page(queryData){
        return get('/recharge/page',queryData);
    },
    // 单条信息
    info(id){
        return get('/recharge/info',{id:id});
    },
    // 保存
    save(formData){
        return post('/recharge/save',formData);
    },
    // 保存状态
    status(id,status){
        return post('/recharge/status',{'id':id,'status':status});
    },
    // 删除
    del(id){
        return post('/recharge/delete',{id:id});
    }
};

export default recharge;
