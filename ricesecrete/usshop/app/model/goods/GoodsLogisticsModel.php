<?php

namespace app\model\goods;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-GoodsLogisticsModel
 * Class GoodsLogisticsModel
 * @package app\model
 */
class GoodsLogisticsModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_logistics';

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('logistics_name', '%' . trim($keyword) . '%');
        }
    }

    /**
     * 获取分页列表
     * @param $keyword
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @param string $store_id
     * @return array
     */
    public function getPageData($keyword, $current_page, $list_rows, $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->keyword($keyword)
            ->order('sort', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * @param $code
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getLogisticsName($code)
    {
        $map = ['code' => $code];
        $logistics = self::where($map)->field('name')->find();
        if (empty($logistics)) {
            return "";
        }
        return $logistics['name'];
    }

    /**
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getLogisticsIdName($id)
    {
        $map = ['id' => $id];
        $logistics = self::where($map)->field('name')->find();
        if (empty($logistics)) {
            return "";
        }
        return $logistics['name'];
    }
}
