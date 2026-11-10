<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismDictModel
 * Class TourismDictModel
 * @package app\model
 */
class TourismDictModel extends BaseModel
{
    use SoftDelete;

    //归属类型 1费用
    const TYPE_COST = 1;//费用

    static $TYPE_MAP = [
        self::TYPE_COST => '费用',//费用
    ];

    protected $name = 'tourism_dict';

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
     * 获取标签列表
     * @param int $type
     * @return TourismDictModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getDictList($type = 1)
    {
        $map = [
            'status' => 1
        ];
        if (!empty($type)) {
            $map['type'] = $type;
        }
        return self::where($map)->field('id,name')->select();
    }
}
