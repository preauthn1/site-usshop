<?php

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\model\concern\SoftDelete;

/**
 * 模型-站内信
 * Class SysNotificationModel
 * @package app\model
 */
class SysNotificationModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'sys_notification';

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

    public function scopeRead($query, $read)
    {
        if (!empty($read)) {
            $query->where('read', '=', $read);
        }
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param  string  $order
     * @param  string  $field
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
     * 读消息
     * @param $user_id
     * @param $id
     * @return SysNotificationModel
     */
    public function read($user_id, $id)
    {
        return $this->where('user_id', '=', $user_id)
            ->where('id', '=', $id)
            ->update(['read' => 1]); // 1已读  2未读
    }

    /**
     * 读所有消息
     * @param $user_id
     * @return SysNotificationModel
     */
    public function readAll($user_id)
    {
        return $this->where('user_id', '=', $user_id)
            ->update(['read' => 1]); // 1已读  2未读
    }

    /**
     * 消息详情
     * @param $user_id
     * @param $id
     * @return SysNotificationModel
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function detail($user_id, $id)
    {
        return $this->where('user_id', '=', $user_id)
            ->where('id', '=', $id)
            ->find();
    }

    /**
     * 已经添加公告的用户id
     * @param $bulletin_id
     * @return array
     */
    public function getHasBulletinUsers($bulletin_id): array
    {
        return $this->where('bulletin_id', '=', $bulletin_id)
            ->column('user_id');
    }

    /**
     * 删除公告
     * @param $bulletin_id
     * @return int
     * @throws DbException
     */
    public function deleteByBulletinId($bulletin_id): int
    {
        return Db::name('sys_notification')->where('bulletin_id', $bulletin_id)->delete();
    }

    /**
     * 更新公告状态
     * @param $bulletin
     * @return void
     */
    public function updateByBulletinId($bulletin)
    {
        $bulletin_id = $bulletin->id;
        Db::name('sys_notification')
            ->where('bulletin_id', $bulletin_id)
            ->field('id')
            ->chunk(1000, function ($users) use ($bulletin) {
                Db::name('sys_notification')
                    ->where('id', 'in', $users->column('id'))
                    ->update([
                        'status'   => $bulletin['status'],
                        'is_popup' => $bulletin['is_popup']
                    ]);
            });

    }
}
