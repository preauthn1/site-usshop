import {get, post,upload} from '@/libs/axios';

const languageItem = {
  // 分页列表
  page(queryData) {
    return get('/language/item/page', queryData);
  },
  // 单条信息
  info(id) {
    return get('/language/item/info', {id: id});
  },
  // 保存
  save(formData) {
    return post('/language/item/save', formData);
  },
  // 删除
  del(id) {
    return post('/language/item/delete', {id: id});
  },
  // 翻译内容
  detailSave(data) {
    return post('/language/item/detail/save', data);
  },
   // 上传文件
   inputFile(formatData) {
    return upload("/language/item/detail/file", formatData);
  },
};

export default languageItem;
