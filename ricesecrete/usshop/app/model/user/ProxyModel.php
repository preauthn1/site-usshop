<?php

namespace app\model\user;

use app\base\BaseModel;
use think\Model;

/**
 * 模型-代理
 * Class ProxyModel
 * @package app\model\user
 */
class ProxyModel extends BaseModel
{
    protected $name = 'user';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param $order
     * @param string $field
     * @return array
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $query = $this;
        if (!isset($map['top_user_id'])) {
            $query = $query->withSearch(['team'], [
                'team' => 'id'
            ]);
        };

        $dataList = $query->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);

        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();

        return [
            'total' => $total,
            'current' => $current,
            'limit' => $limit,
            'dataList' => $dataList->items()
        ];
    }
}
