<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\facade\Cache;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismTypeModel
 * Class TourismTypeModel
 * @package app\model
 */
class TourismTypeModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_type';

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
     * 获取分类id集合
     * @param $pid
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getChildId($pid)
    {
        if (empty($pid)) {
            return [];
        }
        $return_data[] = $pid;
        $list = self::where(['parent_id' => $pid])->select();
        if (!($list->isEmpty())) {
            foreach ($list as $value) {
                $return_data[] = $value['id'];
                $list_temp = self::where(['parent_id' => $value['id']])->select();
                foreach ($list_temp as $val) {
                    $return_data[] = $val['id'];
                }
            }
        } else {
            $return_data = [$pid];
        }
        return $return_data;
    }

    /**
     * 获取顶级分类id
     * @param string $parent_id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTopId($parent_id = '')
    {
        $temp = self::where('id', $parent_id)->find();
        if (empty($temp)) {
            return $parent_id;
        }
        if (empty($temp['parent_id'])) {
            return $parent_id;
        }
        return self::getTopId($temp['parent_id']);
    }

    /**
     * 获取分类名称
     * @param $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTypeName($id)
    {
        if (empty($id)) {
            return '';
        }
        $type_arr = self::_getTypeName($id);
        $type_arr = array_reverse($type_arr);
        return implode('/', $type_arr);
    }

    private static function _getTypeName($id, $name = [])
    {
        $type_name = self::where(['id' => $id])->field('name,parent_id')->find();
        $name[] = $type_name['name'];
        if (!empty($type_name['parent_id'])) {
            return self::_getTypeName($type_name['parent_id'], $name);
        }
        return $name;
    }

    /**
     * 获取所有数据
     * @param int $limit
     * @param string $field
     * @return TourismTypeModel[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllData($limit = 10, $field = 'id,name,img,url')
    {
        $data = Cache::get('sys_tourism_type');
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)->where('parent_id', 0)
            ->order('sort', 'desc')->limit($limit)->select();
        foreach ($dataList as $k => $v) {
            if ($v['name'] == '周边游') {
                $dataList[$k]['id'] = "circum";
            }
        }
        Cache::set('sys_tourism_type', serialize($dataList));
        return $dataList;
    }

    /**
     * 获取所有数据
     * @param string $type_id
     * @param int $limit
     * @param string $field
     * @return TourismTypeModel[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllTypeData($type_id = '', $limit = 10, $field = 'id,name,img,url')
    {
        $data = Cache::get('sys_tourism_type' . $type_id);
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)->where('parent_id', $type_id)
            ->order('sort', 'desc')->limit($limit)->select();
        Cache::set('sys_tourism_type' . $type_id, serialize($dataList), 600);
        return $dataList;
    }

}
