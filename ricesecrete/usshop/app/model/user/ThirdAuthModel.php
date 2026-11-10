<?php

namespace app\model\user;

use app\base\BaseModel;
use think\db\exception\DbException;

/**
 * 模型-会员权益
 * Class RightModel
 * @package app\model
 */
class ThirdAuthModel extends BaseModel
{

    protected $name = 'third_auth';


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
}
