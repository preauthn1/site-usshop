import { get } from '@/libs/axios';

const proxy = {
    // 分页列表
    page(queryData) {
        return get('/proxy/page', queryData);
    },
    // 单条信息
    info(id) {
        return get('/proxy/info', { id: id });
    },
    // 获取下级列表
    subordinates(id) {
        return get('/proxy/subordinates', { id: id });
    }
}

export default proxy;
