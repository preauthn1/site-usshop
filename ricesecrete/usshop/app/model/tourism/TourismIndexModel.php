<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\facade\Cache;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismIndexModel
 * Class TourismIndexModel
 * @package app\model
 */
class TourismIndexModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_index';

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
    public function getPageData($map, $current_page, $list_rows, $order = 'sort DESC', $field = '*')
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
     * @return TourismTagModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTypeList()
    {
        return self::field('id,name')->select();
    }

    /**
     * 获取首页分类名称
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTypeName($id)
    {
        if (empty($id)) {
            return '';
        }
        try {
            $ids = unserialize($id);
        } catch (\Exception $e) {
            $ids = [$id];
        }
        if (empty($ids)) {
            return '';
        }
        $temp = self::whereIn('id', $ids)->column('name');
        if (empty($temp)) {
            return '';
        } else {
            return $temp;
        }
    }

    /**
     * 获取所有数据
     * @param int $limit
     * @param string $field
     * @return TourismIndexModel[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllData($limit = 10, $field = 'id,name,img')
    {
        $data = Cache::get('sys_tourism_index');
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)
            ->order('sort', 'desc')->limit($limit)->select();
        Cache::set('sys_tourism_index', serialize($dataList));
        return $dataList;
    }
}
