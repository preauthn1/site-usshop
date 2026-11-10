<?php

namespace app\model\system;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-公告
 * Class SysBulletinModel
 * @package app\model
 */
class SysBulletinModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'sys_bulletin';

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 保存数据
     * @param $data
     * @return array
     */
    public function saveData($data): array
    {
        if (!empty($data['id'])) {
            $flag = $this->update($data, ['id' => $data['id']]);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $flag = $this->strict(false)->insertGetId($data);
            $id = $flag;
        }
        return ['flag' => $flag, 'id' => $id];
    }

}
