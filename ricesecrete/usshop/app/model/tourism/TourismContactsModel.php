<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismContactsModel
 * Class TourismContactsModel
 * @package app\model
 */
class TourismContactsModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_contacts';

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
     * 添加联系人
     * @param $data
     * @return TourismContactsModel|false|\think\Model
     * @throws \think\db\exception\DbException
     */
    public static function addContacts($data)
    {
        $map = [
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'mobile' => $data['mobile'],
        ];
        if (self::where($map)->count() > 0) {
            return false;
        } else {
            $data['update_time'] = time();
            $data['create_time'] = time();
            return self::create($data);
        }
    }

    /**
     * 删除联系人
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delContacts($data)
    {
        $map = [
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'mobile' => $data['mobile'],
        ];
        $temp = self::where($map)->find();
        if (empty($temp)) {
            return false;
        } else {
            return $temp->delete();
        }
    }

    /**
     * 获取联系人列表
     * @param string $user_id
     * @return TourismContactsModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getContactsList($user_id = '')
    {
        $temp = self::where('user_id', $user_id)->field('id,name,mobile')->select();
        if (empty($temp)) {
            return [];
        } else {
            return $temp;
        }
    }


}
