<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismRegionModel
 * Class TourismRegionModel
 * @package app\model
 */
class TourismRegionModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_region';

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
     * 获取地区数据
     * @return TourismDictModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getRegionData()
    {
        $map = [
            'status' => 1
        ];
        $list = self::where($map)->field('initial,city_name')->order('initial asc')->select();
        $initial = self::where($map)->order('initial asc')->group('initial')->column('initial');
        $city = [];
        foreach ($initial as $key => $value) {
            $index = self::getIndex($initial, $value);
            if (is_numeric($index)) {
                $city[intval($index)] = [];
            }
        }
        foreach ($list as $item) {
            $index = self::getIndex($initial, $item['initial']);
            if (is_numeric($index)) {
                $city[intval($index)][] = $item['city_name'];
            }
        }
        return [
            'initial' => $initial,
            'city' => $city
        ];
    }

    /**
     * 获取数组中某个元素的索引
     * @param $arr
     * @param $value
     * @return false|int|string
     */
    private static function getIndex($arr, $value)
    {
        foreach ($arr as $key => $val) {
            if ($val == $value) {
                return $key;
            }
        }
        return false;
    }
}
