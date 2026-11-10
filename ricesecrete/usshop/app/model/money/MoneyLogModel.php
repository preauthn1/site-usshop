<?php

namespace app\model\money;

use app\base\BaseModel;
use app\model\order\OrderModel;
use app\model\user\UserModel;
use think\db\exception\DbException;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * 模型-MoneyLogModel
 * Class OrderModel
 * @package app\model
 */
class MoneyLogModel extends BaseModel
{
    protected $autoWriteTimestamp = true;
    use SoftDelete;

    protected $name = 'money_log';


    // 1-佣金到账 2-积分到账 3-佣金提现 4-积分转佣金 5-用户充值 6-分享佣金收入 7-活动佣金收入 8-充值入账 9-余额支付 10-平台增加 11-平台减少
    const FROM_TYPE_COMMISSION_TEAM_INCOME = 1; // 团队佣金收入
    const FROM_TYPE_INTEGRAL_TRAVEL_INCOME = 2; // 旅游订单积分收入
    const FROM_TYPE_COMMISSION_WITHDRAWAL = 3; // 佣金提现
    const FROM_TYPE_INTEGRAL_TRANSFER = 4; // 积分转换
    const FROM_TYPE_INTEGRAL_DEDUCTION = 5; // 积分抵扣
    const FROM_TYPE_COMMISSION_SHARE_INCOME = 6; // 分享佣金收入
    const FROM_TYPE_COMMISSION_ACTIVITY_INCOME = 7; // 活动佣金收入
    const FROM_TYPE_BALANCE_RECHARGE = 8; // 充值入账
    const FROM_TYPE_BALANCE_PAY = 9; // 余额支付
    const FROM_TYPE_PLATFORM_INCREASE = 10;// 平台增加
    const FROM_TYPE_PLATFORM_REDUCE = 11;// 平台减少

    static $FROM_TYPE_MAP = [
        self::FROM_TYPE_COMMISSION_TEAM_INCOME => '团队佣金收入',
        self::FROM_TYPE_INTEGRAL_TRAVEL_INCOME => '旅游订单积分收入',
        self::FROM_TYPE_COMMISSION_WITHDRAWAL => '佣金提现',
        self::FROM_TYPE_INTEGRAL_TRANSFER => '积分转换',
        self::FROM_TYPE_INTEGRAL_DEDUCTION => '积分抵扣',
        self::FROM_TYPE_COMMISSION_SHARE_INCOME => '分享佣金收入',
        self::FROM_TYPE_COMMISSION_ACTIVITY_INCOME => '活动佣金收入',
        self::FROM_TYPE_BALANCE_RECHARGE => '充值入账',
        self::FROM_TYPE_BALANCE_PAY => '余额支付',
        self::FROM_TYPE_PLATFORM_INCREASE => '平台增加',
        self::FROM_TYPE_PLATFORM_REDUCE => '平台减少',
    ];

    // 余额类型
    const BALANCE_TYPE_COMMISSION = 1; // 佣金余额
    const BALANCE_TYPE_INTEGRAL = 2; // 积分余额
    const BALANCE_TYPE_FROZEN_COMMISSION = 3; // 冻结余额
    const BALANCE_TYPE = 4; // 账户余额

    static $BALANCE_TYPE_MAP = [
        self::BALANCE_TYPE_COMMISSION => '佣金余额',
        self::BALANCE_TYPE_INTEGRAL => '积分余额',
        self::BALANCE_TYPE_FROZEN_COMMISSION => '冻结余额',
        self::BALANCE_TYPE => '账户余额',
    ];

    // 余额类型
    const CHANGE_TYPE_ADD = 1; // 增加
    const CHANGE_TYPE_SUB = -1; // 减少

    static $CHANGE_TYPE_MAP = [
        self::CHANGE_TYPE_ADD => '增加',
        self::CHANGE_TYPE_SUB => '减少',
    ];
    // 获取今日积分转佣金次数

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
            ->withSearch(['team'], [
                'team' => 'user_id'
            ])
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['user_info'] = UserModel::getUserInfo($item['user_id'], 'username,phone,email');
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取佣金记录
     * @param $user_id
     * @param $page
     * @param $limit
     * @param $balance_type
     * @param $change_type
     * @param string $order
     * @return \think\Paginator
     * @throws DbException
     */
    public static function getMoneyLog($user_id, $page, $limit, $balance_type = MoneyLogModel::BALANCE_TYPE, $change_type = MoneyLogModel::CHANGE_TYPE_SUB, $order = 'id DESC')
    {
        $map = [
            'user_id' => $user_id,
            'change_type' => $change_type,
            'balance_type' => $balance_type
        ];
        $list = MoneyLogModel::where($map)
            ->order($order)
            ->paginate(['page' => $page, 'list_rows' => $limit], false);
        foreach ($list as &$item) {
            $item['title'] = lang($item['title']);
        }
        return $list;

    }

    /**
     * @throws DbException
     */
    public static function getTodayIntegralToCommissionCount($user_id): int
    {
        // 获取今日佣金转换次数
        return MoneyLogModel::where('user_id', $user_id)
            ->where('balance_type', MoneyLogModel::BALANCE_TYPE_INTEGRAL)
            ->where('from_type', MoneyLogModel::FROM_TYPE_INTEGRAL_TRANSFER)
            ->whereDay('create_time')
            ->count();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'from_user_id', 'id');
    }

    public function tourismOrder(): BelongsTo
    {
        return $this->belongsTo(OrderModel::class, 'order_id', 'id');
    }

    // 时间搜索器
    public function searchCreateTimeAttr($query, $value, $data)
    {
        if ($value[0]) {
            $query->where('create_time', '>=', strtotime($value[0]));
        }
        if ($value[1]) {
            $query->where('create_time', '<=', strtotime($value[1]));
        }
    }

    public function searchFromTypeAttr($query, $value, $data)
    {
        $query->where('from_type', $value);
    }

    public function searchUserIdsAttr($query, $value, $data)
    {
        $query->where('user_id', 'in', $value);
    }

    public function searchFromUserIdsAttr($query, $value, $data)
    {
        $query->where('from_user_id', 'in', $value);
    }

    public function searchOrderIdsAttr($query, $value, $data)
    {
        $query->where('order_id', 'in', $value);
    }
}
