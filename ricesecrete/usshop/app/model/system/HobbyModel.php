<?php

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\concern\SoftDelete;

/**
 * 模型-兴趣
 * Class HobbyModel
 * @package app\model
 */
class HobbyModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'hobby';


    // 1我最喜欢的分类 2我最常为谁购买衣服 3我最喜欢的风格 4身高 5体重 6胸围 7胸围 8腰围 9臀围 10请选择您的偏好
    public static $hobbyType = [
        1 => '我最喜欢的分类',
        2 => '我最常为谁购买衣服',
        3 => '我最喜欢的风格',
        4 => '身高',
        5 => '体重',
        6 => '胸围',
        7 => '胸脯',
        8 => '腰围',
        9 => '臀围',
        10 => '请选择您的偏好',
    ];

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
     * @param string $order
     * @param string $field
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
