import { get, post } from '@/libs/axios'

const goodsType = {
    // 树形数据
    tree(queryData = {}) {
        return get('/goodsType/page', queryData)
    },
    // 单条信息
    info(id) {
        return get('/goodsType/info', { id: id })
    },
    // 修改
    edit(formData) {
        return post('/goodsType/edit', formData)
    },
    // 上线状态
    status(id, status) {
        return post('/goodsType/status', { 'id': id, 'status': status })
    },
    // 添加
    add(formData) {
        return post('/goodsType/add', formData)
    },
    // 保存
    save(formData) {
        return post('/goodsType/save', formData)
    },
    // 删除
    del(id) {
        return post('/goodsType/delete', { id: id })
    }
}

export default goodsType
