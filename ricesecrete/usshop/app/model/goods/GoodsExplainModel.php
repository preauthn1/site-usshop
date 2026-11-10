<?php

namespace app\model\goods;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-GoodsExplainModel
 * Class GoodsExplainModel
 * @package app\model
 */
class GoodsExplainModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_explain';

    //类型 1运费说明  2退货说明  3赔付说明 4发货说明 5到店说明
    public static $TYPE_EXPLAIN = [
        1 => '运费说明',
        2 => '退货说明',
        3 => '赔付说明',
        4 => '发货说明',
        5 => '到店说明',
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
     * @return GoodsExplainModel|array|mixed|string[]|\think\Model
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

    /**
     * 获取说明文档详情
     * @return GoodsExplainModel|array|mixed|string[]|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getExplainInfos()
    {
        $temp = self::where('status', 1)->field('id,title,content,type')->group('type')->select();
        if (empty($temp)) {
            return [
                1 => [
                    'id' => '',
                    'type' => 1,
                    'title' => '包邮',
                    'content' => '包邮',
                ],
                2 => [
                    'id' => '',
                    'type' => 2,
                    'title' => '7天无理由退货',
                    'content' => '7天无理由退货',
                ],
                3 => [
                    'id' => '',
                    'type' => 3,
                    'title' => '假一赔四',
                    'content' => '假一赔四',
                ],
                4 => [
                    'id' => '',
                    'type' => 4,
                    'title' => '48小时发货',
                    'content' => '48小时发货',
                ],
                5 => [
                    'id' => '',
                    'type' => 5,
                    'title' => '商品购买后需到店领取',
                    'content' => '商品购买后需到店领取',
                ],
            ];
        } else {
            $out_data = [];
            foreach ($temp as $k => $v) {
                $temp_info = [
                    'id' => $v['id'],
                    'type' => $v['type'],
                    'title' => $v['title'],
                    'content' => $v['content'],
                ];
                $out_data[$v['type']] = $temp_info;
            }
            return $out_data;
        }
    }
}
