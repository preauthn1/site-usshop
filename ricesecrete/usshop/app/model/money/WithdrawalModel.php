<?php

namespace app\model\money;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-提现记录
 * Class SysBulletinModel
 * @package app\model
 */
class WithdrawalModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'withdrawal';

    const STATUS_WAITING = 1; // 审核中
    const STATUS_SUCCESS = 2; // 审核通过
    const STATUS_REJECT = 3; // 审核拒绝
    const STATUS_PAY_SUCCESS = 4; // 支付成功
    const STATUS_PAY_FAIL = 5; // 支付失败

    static array $STATUS_MAP = [
        self::STATUS_WAITING     => '审核中',
        self::STATUS_SUCCESS     => '审核通过',
        self::STATUS_REJECT      => '审核拒绝',
        self::STATUS_PAY_SUCCESS => '支付成功',
        self::STATUS_PAY_FAIL    => '支付失败',
    ];


    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $order
     * @param $current_page
     * @param $list_rows
     * @param string|array $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageList($map, $order, $current_page, $list_rows, $field = '*')
    {
        $model = $this;
        if (isset($map['like'])) {
            foreach ($map['like'] as $key => $value) {
                $model = $model->whereLike($key, $value, 'OR');
            }
            unset($map['like']);
        }
        $model = $model->where($map)->field($field);
        $dataList = $model
            ->withSearch(['team'], [
                'team' => 'user_id'
            ])
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $ret = ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
        return $ret;
    }
}
