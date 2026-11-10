import {get, post} from '@/libs/axios';

const language = {
  // 分页列表
  page(queryData) {
    return get('/language/page', queryData);
  },
  // 单条信息
  info(id) {
    return get('/language/info', {id: id});
  },
  // 保存
  save(formData) {
    return post('/language/save', formData);
  },
  // 保存状态
  status(id, status) {
    return post('/language/status', {'id': id, 'status': status});
  },
  // 保存状态
  rtl(id, rtl) {
    return post('/language/rtl', {'id': id, 'rtl': rtl});
  },
  // 保存状态
  isDefault(id, is_default) {
    return post('/language/is_default', {'id': id, 'is_default': is_default});
  },
  // 删除
  del(id) {
    return post('/language/delete', {id: id});
  }
};

export default language;
