<?php

namespace app\model\tourism;

use app\base\BaseModel;

/**
 * 模型-TourismCityModel
 * Class TourismCityModel
 * @package app\model
 */
class TourismCityModel extends BaseModel
{
    protected $name = 'tourism_city';

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
     * 获取城市列表
     * @param int $limit
     * @return TourismCityModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCityList($limit = 10)
    {
        return self::field('id,city,lng,lat')->where('status', 1)->order('sort DESC')->limit($limit)->select();
    }
}
