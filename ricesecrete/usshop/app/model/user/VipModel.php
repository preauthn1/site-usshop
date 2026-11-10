<?php

namespace app\model\user;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\relation\BelongsToMany;

/**
 * 模型-用户
 * Class UserModel
 * @package app\model
 */
class VipModel extends BaseModel
{

    protected $name = 'vip';


    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('username|phone|nick_name', '%'.trim($keyword).'%');
        }
    }

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
     * @param $order
     * @param  string  $field
     * @return array
     * @throws DbException
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

    public function rights(): BelongsToMany
    {
        return $this->belongsToMany(RightModel::class, VipRightModel::class, 'right_id', 'vip_id');
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
        $this->saveRights($data);
        return ['flag' => $flag, 'id' => $id];
    }

    private function saveRights($data)
    {
        $vip = $this->where('id', $data['id'])->find();
        // 获取已经存在的权益
        $exist_rights = $vip->rights()->column('right_id', 'pivot.id');

        // 从 $data['rights'] 取出 id 数组
        $right_ids = array_column($data['rights'], 'id');

        // 获取要新增、要删除和要更新的权益id
        $add_rights_ids = array_diff($right_ids, $exist_rights);
        $del_rights = array_diff($exist_rights, $right_ids);
        $update_rights = array_intersect($right_ids, $exist_rights);

        // 删除权益
        if (!empty($del_rights)) {
            $vip->rights()->detach($del_rights);
        }

        // 从 $data['rights'] 取出 $add_rights 数组中相同id的数据
        $add_rights = array_filter($data['rights'], function ($item) use ($add_rights_ids) {
            return in_array($item['id'], $add_rights_ids);
        });

        // 新增权益
        if (!empty($add_rights)) {
            $add_data = [];
            foreach ($add_rights as $right) {
                $add_data[] = [
                    'vip_level' => $data['level'],
                    'number'    => $right['number'],
                    'unit'      => $right['unit'],
                    'is_open'   => $right['is_open'],
                ];
            }
            $vip->rights()->saveAll($add_rights_ids, $add_data);
        }

        $update_rights = array_filter($data['rights'], function ($item) use ($update_rights) {
            return in_array($item['id'], $update_rights);
        });

        // 给 $update_rights 数组添加 pivot_id 字段
        foreach ($update_rights as $key => $right) {
            if (!key_exists('pivot_id', $right)) {
                $update_rights[$key]['pivot_id'] = array_search($right['id'], $exist_rights);
            }
        }

        // 更新权益
        if (!empty($update_rights)) {
            foreach ($update_rights as $right) {
                VipRightModel::update([
                    'vip_level' => $data['level'],
                    'number'    => $right['number'],
                    'unit'      => $right['unit'],
                    'is_open'   => $right['is_open'],
                ], ['id' => $right['pivot_id']]);
            }
        }
    }
}
