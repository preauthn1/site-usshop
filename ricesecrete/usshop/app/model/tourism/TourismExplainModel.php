<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismExplainModel
 * Class TourismExplainModel
 * @package app\model
 */
class TourismExplainModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_explain';

    //1退订政策 2赠送积分 3报名须知 4报名限制 5旅游装备 6退订政策
    public static $TYPE_EXPLAIN = [
        '1' => '退订政策',
        '2' => '赠送积分',
        '3' => '报名须知',
        '4' => '报名限制',
        '5' => '旅游装备',
    ];

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
     * 获取说明文档列表
     * @param int $type
     * @return TourismExplainModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getExplainList($type = 1)
    {
        $map = [];
        if (!empty($type)) {
            $map['type'] = $type;
        }
        return self::where($map)->field('id,title,type')->select();
    }

    /**
     * 获取说明文档详情
     * @param $id
     * @return TourismExplainModel|array|mixed|string[]|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getExplainInfo($id)
    {
        $temp = self::where('id', $id)->field('id,title,content,type')->find();
        if (empty($temp)) {
            return "";
        } else {
            return $temp['content'];
        }
    }
}
