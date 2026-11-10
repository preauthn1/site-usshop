<?php

namespace app\model\tourism;

use app\base\BaseModel;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismWorkerModel
 * Class TourismWorkerModel
 * @package app\model
 */
class TourismWorkerModel extends BaseModel
{
    protected $name = 'tourism_worker';

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
     * 获取工作人员列表
     * @param string $bureau_id
     * @return array
     */
    public static function getWorkerList($bureau_id = '')
    {
        $map = [
            'bureau_id' => $bureau_id
        ];
        return self::where($map)->field('*')->select();
    }

    /**
     * 添加工作人员
     * @param $bureau_id
     * @param $phone
     * @param $name
     * @param $id
     * @return bool
     */
    public static function addWorker($bureau_id, $phone, $name, $id = '')
    {
        $map = [
            'bureau_id' => $bureau_id,
            'phone' => $phone,
        ];
        if (!empty($id)) {
            $map = [
                'id' => $id
            ];
        }
        $temp = self::where($map)->find();
        if (!empty($temp)) {
            $up_data = [
                'name' => $name,
                'phone' => $phone,
            ];
            $ret = self::where($map)->update($up_data);
            if (false === $ret) {
                return false;
            }
            return true;
        }
        $add_data = [
            'bureau_id' => $bureau_id,
            'phone' => $phone,
            'name' => $name,
            'status' => 1,
        ];
        $ret = self::insertGetId($add_data);
        if (false === $ret) {
            return false;
        }
        return true;
    }

    /**
     * 获取工作人员所属旅行社
     * @param $user_id
     * @return array
     */
    public static function getWorkerBureauArr($user_id)
    {
        $phone = UserModel::where('id', $user_id)->value('phone');
        $map = [
            'phone' => $phone
        ];
        $temp = self::where($map)->column('bureau_id');
        if (!empty($temp)) {
            return $temp;
        }
        return [];
    }
}
