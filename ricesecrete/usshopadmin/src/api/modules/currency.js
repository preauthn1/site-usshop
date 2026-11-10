import {get, post} from '@/libs/axios';

const currency = {
  // 分页列表
  page(queryData) {
    return get('/currency/page', queryData);
  },
  // 单条信息
  info(id) {
    return get('/currency/info', {id: id});
  },
  // 保存
  save(formData) {
    return post('/currency/save', formData);
  },
  // 保存状态
  status(id, status) {
    return post('/currency/status', {'id': id, 'status': status});
  },
  // 保存状态
  isDefault(id, is_default) {
    return post('/currency/is_default', {'id': id, 'is_default': is_default});
  },
  // 删除
  del(id) {
    return post('/currency/delete', {id: id});
  }
};

export default currency;
