<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\libs\Util;
use think\facade\Cache;
use think\facade\Lang;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * 模型-GoodsTypeModel
 * Class GoodsTypeModel
 * @package app\model
 */
class GoodsTypeModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_type';

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
            return "";
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
            $return_data = $pid;
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
        $name[] = lang($type_name['name']);
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
        $data = Cache::get('sys_goods_type');
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)->where('parent_id', 0)
            ->order('sort', 'desc')->limit($limit)->select();
        foreach ($dataList as $key => $value) {
            $value['name'] = lang($value['name']);
        }
        Cache::set('sys_goods_type', serialize($dataList));
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
        $data = Cache::get('sys_goods_type' . $type_id);
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)->where('parent_id', $type_id)
            ->order('sort', 'desc')->limit($limit)->select();
        foreach ($dataList as $key => $value) {
            $value['name'] = lang($value['name']);
        }
        Cache::set('sys_goods_type' . $type_id, serialize($dataList), 600);
        return $dataList;
    }

    /**
     * 获取所有数据
     * @param int $type_id
     * @param int $limit
     * @param string $field
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllDataList(int $type_id = 0, int $limit = 1000, string $field = 'id,name,sort,img,url,parent_id')
    {
        $lang = strtolower(Lang::getLangSet());
        $data_key = 'sys_goods_type_all_' . $type_id . $lang;

        $data = Cache::get($data_key);
        if (!empty($data)) {
            return unserialize($data);
        }
        $map = [
            'status' => 1,
        ];
        if (!empty($type_id)) {
            $map['parent_id'] = $type_id;
        }
        $dataList = self::field($field)->where($map)->order('sort', 'desc')->limit($limit)->select();
        foreach ($dataList as &$value) {
            $value['name'] = lang($value['name']);
            $value['is_children'] = self::where(['parent_id' => $value['id']])->count();
        }
        if (!empty($dataList)) {
            $tree = Util::levelTreeChildren($dataList, $type_id);
            Util::listOrder($tree, 'sort', 'desc', 'number');
            //"id": 17,
            //		"name": "洗护",
            //		"img": "",
            //		"url": "",
            //		"parent_id": 0
            $parent_id = 0;
            if (count($tree) > 0) {
                $parent_id = $tree[0]['parent_id'];
            }
            if (empty($parent_id)) {
                $children_all = [];
                foreach ($tree as $key => $value) {
                    $children_all[] = $value;
                }
                array_unshift($tree,
                    [
                        'id' => '',
                        'name' => lang("全部"),
                        'img' => '',
                        'url' => '',
                        'parent_id' => 0,
                        'children' => $children_all
                    ]
                );
            } else {
                $temp_parent = self::field($field)->where('id', $parent_id)->find();
                $temp_parent['name'] = lang("全部");
                if (!empty($tree)) {
                    array_unshift($tree,
                        json_decode(json_encode($temp_parent), true)
                    );
                }
            }

            Cache::set($data_key, serialize($tree), 3600);
            return $tree;
        }
        return [];
    }

    public static function getTypeWithGoodsByParentId($parent_id = 0, $goods_limit = 10, $field = 'id,name,img,url')
    {
        $types = self::field($field)->where('status', 1)
            ->where('parent_id', $parent_id)
            ->select();
        $data = [];
        foreach ($types as $key => $type) {
            $type_id_arr = GoodsTypeModel::getChildId($type->id);
            $type['name'] = lang($type['name']);
            $goods = GoodsProductModel::field('id,title,classify,express_type,type_id,min_price,img,buy')
                ->whereIn('type_id', $type_id_arr)
                ->limit($goods_limit)->select();

            if (!$goods->isEmpty()) {
                foreach ($goods as &$good) {
                    $good = GoodsLangModel::getGoodsInfo($good);
                }
                $type['goods'] = $goods;
                $data[] = $type;
            }
        }
        return $data;
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(GoodsProductModel::class, 'id', 'type_id');
    }
}
