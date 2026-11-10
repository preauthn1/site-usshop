<?php

namespace app\model\user;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\relation\BelongsToMany;

/**
 * 模型-会员权益
 * Class RightModel
 * @package app\model
 */
class RightModel extends BaseModel
{

    protected $name = 'rights';

    const TYPE_INTEGRAL = 1; // 买套餐反积分
    const TYPE_WITHDRAW_FEE = 2; // 提现免提现手续费
    const TYPE_BIRTHDAY_WELFARE = 3; // 生日福利
    const TYPE_VIP_GIFT = 4; // 赠送会员专属商品
    const TYPE_SHARE_COMMISSION = 6; // 分享返佣
    const TYPE_ANNUAL_MEETING = 7; // 年会免费参加

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('username|phone|nick_name', '%'.trim($keyword).'%');
        }
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param $order
     * @param  string  $field
     * @return array
     * @throws DbException
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


    public function vips(): BelongsToMany
    {
        return $this->belongsToMany(VipModel::class, VipRightModel::class, 'vip_id', 'right_id');
    }
}
