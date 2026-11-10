<?php

namespace app\model\user;


use app\base\BaseModel;
use app\libs\Util;
use think\facade\Cache;

/**
 * 模型-用户搜索关键词
 * Class UserSearchModel
 * @package app\model
 */
class UserSearchModel extends BaseModel
{
    protected $name = 'user_search';

    //订单类型 1旅游套餐 2正常商品 3积分商品
    const TYPE_TOURISM = 1;//旅游套餐
    const TYPE_NORMAL = 2;//正常商品
    const TYPE_INTEGRAL = 3;//积分商品

    static $TYPE_MAP = [
        self::TYPE_TOURISM => "旅游套餐",
        self::TYPE_NORMAL => "正常商品",
        self::TYPE_INTEGRAL => "积分商品",
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
     * 添加搜索关键词
     * @param $user_id
     * @param string $keyword
     * @param int $type
     * @return UserSearchModel|bool
     * @throws \think\db\exception\DbException
     */
    public static function addKeyword($user_id, $keyword = '', $type = self::TYPE_TOURISM)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return false;
        }
        if (Util::cacheLock($user_id . $keyword, 2)) {
            return false;
        }
        $map = [
            'user_id' => $user_id,
            'type' => $type,
            'keyword' => $keyword
        ];
        $length = self::where(['user_id' => $user_id])->count();
        if ($length > 8) {
            self::where(['user_id' => $user_id])->order('create_time asc')->limit(1)->delete();
        }
        if (self::where($map)->count() > 0) {
            return self::where($map)->update(['create_time' => time()]);
        }
        $data = [
            'user_id' => $user_id,
            'keyword' => $keyword,
            'type' => $type,
            'create_time' => time()
        ];
        $res = self::insert($data);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 删除搜索关键词
     * @param $user_id
     * @param string $keyword
     * @param int $type
     * @return bool
     */
    public static function delKeyword($user_id, $keyword = '', $type = self::TYPE_TOURISM)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return false;
        }
        $map = [
            'user_id' => $user_id,
            'type' => $type,
            'keyword' => $keyword
        ];
        $ret = self::where($map)->delete();
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 获取搜索关键词
     * @param $user_id
     * @param int $type
     * @return array
     */
    public static function getKeyword($user_id, $type = self::TYPE_TOURISM)
    {
        if (empty($user_id)) {
            $map = [
                'user_id' => 0,
                'type' => $type
            ];
        } else {
            $map = [
                'user_id' => $user_id,
                'type' => $type
            ];
        }
        return self::where($map)->order('create_time desc')->column('keyword');
    }

    /**
     * 清空搜索关键词
     * @param $user_id
     * @return bool
     */
    public static function clearKeyword($user_id, $type = self::TYPE_TOURISM)
    {
        $map = [
            'user_id' => $user_id,
            'type' => $type,
        ];
        $ret = self::where($map)->delete();
        if ($ret) {
            return true;
        }
        return false;
    }
}
