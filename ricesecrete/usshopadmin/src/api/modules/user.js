import {get,post} from '@/libs/axios';

const user = {
    // 分页列表
    page(queryData){
        return get('/user/page',queryData);
    },
    // 单条信息
    info(id){
        return get('/user/info',{id:id});
    },
    // 保存
    save(formData){
        return post('/user/save',formData);
    },
    // 保存状态
    status(id,status){
        return post('/user/status',{'id':id,'status':status});
    },
    // 删除
    del(id){
        return post('/user/delete',{id:id});
    },
    // 修改上级用户
    changeTopUser(id, topUserId) {
        return post('/user/change_top_user_id', {
            id: id,
            top_user_id: topUserId
        });
    }
};

export default user;
