<?php

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\concern\SoftDelete;

/**
 * 模型-客服
 * Class HobbyModel
 * @package app\model
 */
class CustomerServiceModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'customer_service';

    const TYPE_MOBILE = 1;
    const TYPE_WECHAT = 2;

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if ($status !== '') {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param  string  $order
     * @param  string  $field
     * @return array
     */
    public function getPageData($status, $map, $current_page, $list_rows, string $order = 'sort DESC', string $field = '*'): array
    {
        $data = ['total' => 0, 'current' => 0, 'limit' => 0, 'dataList' => []];
        try {
            $dataList = $this
                ->field($field)
                ->status($status)
                ->where($map)
                ->order($order)
                ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        } catch (DbException $e) {
            return $data;
        }

        $data['total'] = $dataList->total();
        $data['current'] = $dataList->currentPage();
        $data['limit'] = $dataList->listRows();
        $data['dataList'] = $dataList->items();
        return $data;
    }
}
