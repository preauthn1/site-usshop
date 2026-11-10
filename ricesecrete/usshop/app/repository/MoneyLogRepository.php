<?php

namespace app\repository;

use app\event\TeamCommission;
use app\model\money\MoneyLogModel;
use app\model\order\OrderModel;
use app\model\user\UserModel;
use app\model\user\VipRightModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Log;

class MoneyLogRepository extends Repository
{
    protected $model;

    const CHANGE_TYPE_MINUS = -1; // 减少余额
    const CHANGE_TYPE_ADD = 1; // 增加余额

    // 1-佣金到账 2-积分到账 3-佣金提现 4-积分转佣金 5-用户充值 6-分享佣金收入 7-活动佣金收入 8-充值入账 9-余额支付 10-平台增加 11-平台减少
    const FROM_TYPE_COMMISSION_TEAM_INCOME = 1; // 团队佣金收入
    const FROM_TYPE_INTEGRAL_TRAVEL_INCOME = 2; // 旅游订单积分收入
    const FROM_TYPE_COMMISSION_WITHDRAWAL = 3; // 佣金提现
    const FROM_TYPE_INTEGRAL_TRANSFER = 4; // 积分转换
    const FROM_TYPE_INTEGRAL_DEDUCTION = 5; // 积分抵扣
    const FROM_TYPE_COMMISSION_SHARE_INCOME = 6; // 分享佣金收入
    const FROM_TYPE_COMMISSION_ACTIVITY_INCOME = 7; // 活动佣金收入
    const FROM_TYPE_BALANCE_RECHARGE = 8; // 充值入账
    const FROM_TYPE_BALANCE_PAY = 9; // 支付
    const FROM_TYPE_PLATFORM_INCREASE = 10;// 平台增加
    const FROM_TYPE_PLATFORM_REDUCE = 11;// 平台减少
    const FROM_TYPE_BALANCE_WITHDRAWAL = 12;// 余额提现

    // 余额收支方式
    static array $BALANCE_INCOME_TYPE = [
        self::FROM_TYPE_BALANCE_RECHARGE,
        self::FROM_TYPE_BALANCE_PAY,
        self::FROM_TYPE_PLATFORM_INCREASE,
        self::FROM_TYPE_PLATFORM_REDUCE,
    ];

    // 佣金收入方式
    static array $COMMISSION_INCOME_TYPE = [
        self::FROM_TYPE_COMMISSION_TEAM_INCOME,
    ];

    // 积分收入方式
    static array $INTEGRAL_INCOME_TYPE = [
        self::FROM_TYPE_INTEGRAL_TRAVEL_INCOME,
        self::FROM_TYPE_PLATFORM_INCREASE,
        self::FROM_TYPE_PLATFORM_REDUCE,
    ];

    // 余额类型
    const BALANCE_TYPE_COMMISSION = 1; // 佣金余额
    const BALANCE_TYPE_INTEGRAL = 2; // 积分余额
    const BALANCE_TYPE_FROZEN_COMMISSION = 3; // 冻结余额
    const BALANCE_TYPE = 4; // 用户余额

    static array $BALANCE_TYPE_FIELDS = [
        self::BALANCE_TYPE_COMMISSION => 'commission',
        self::BALANCE_TYPE_INTEGRAL => 'integral',
        self::BALANCE_TYPE_FROZEN_COMMISSION => 'frozen_commission',
        self::BALANCE_TYPE => 'balance',
    ];
    static array $BALANCE_TYPE_NAMES = [
        self::BALANCE_TYPE_COMMISSION => '佣金',
        self::BALANCE_TYPE_INTEGRAL => '积分',
        self::BALANCE_TYPE_FROZEN_COMMISSION => '佣金冻结',
        self::BALANCE_TYPE => '用户余额',
    ];

    /**
     * @param int $user_id 用户id
     * @param float $amount 金额
     */
    public function __construct(int $user_id = 0, float $amount = 0)
    {
        parent::__construct();
        //绑定模型
        $this->model = new MoneyLogModel();
        $this->user_id = $user_id;
        $this->amount = $amount;
    }


    /**
     * @param int $admin_id
     * @return MoneyLogRepository
     */
    public function setAdminId(int $admin_id): MoneyLogRepository
    {
        $this->admin_id = $admin_id;
        return $this;
    }

    /**
     * @param int $order_id
     * @return MoneyLogRepository
     */
    public function setOrderId(int $order_id): MoneyLogRepository
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @param int $from_user_id
     * @return MoneyLogRepository
     */
    public function setFromUserId(int $from_user_id): MoneyLogRepository
    {
        $this->from_user_id = $from_user_id;
        return $this;
    }

    /**
     * @param float $from_user_money
     * @return MoneyLogRepository
     */
    public function setFromUserMoney(float $from_user_money): MoneyLogRepository
    {
        $this->from_user_money = $from_user_money;
        return $this;
    }

    /**
     * @param int $change_type
     * @return MoneyLogRepository
     */
    public function setChangeType(int $change_type): MoneyLogRepository
    {
        $this->change_type = $change_type;
        return $this;
    }

    /**
     * @param int $from_type
     * @return MoneyLogRepository
     */
    public function setFromType(int $from_type): MoneyLogRepository
    {
        $this->from_type = $from_type;
        return $this;
    }

    /**
     * @param int $balance_type
     * @return MoneyLogRepository
     */
    public function setBalanceType(int $balance_type): MoneyLogRepository
    {
        $this->balance_type = $balance_type;
        return $this;
    }

    /**
     * @param string $title
     * @return MoneyLogRepository
     */
    public function setTitle(string $title): MoneyLogRepository
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $remarks
     * @return MoneyLogRepository
     */
    public function setRemarks(string $remarks): MoneyLogRepository
    {
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * @param float $amount
     * @return MoneyLogRepository
     */
    public function setAmount(float $amount): MoneyLogRepository
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * 用户id
     * @var int
     */
    private int $user_id;

    /**
     * 管理员id
     * @var int
     */
    private int $admin_id = 0;

    /**
     * 订单id
     * @var int
     */
    private int $order_id = 0;

    /**
     * 返佣用户id
     * @var int
     */
    private int $from_user_id = 0;

    /**
     * 返佣用户消费金额
     * @var int
     */
    private int $from_user_money = 0;

    /**
     * 余额修改类型 -1-减少 1-增加
     * @var int
     */
    private int $change_type;

    /**
     * 余额修改来源 1-佣金到账 2-积分到账 3-佣金提现 4-积分转佣金 5-用户充值 6-用户消费
     * @var int
     */
    private int $from_type;

    /**
     * 余额类型 1-佣金 2-积分 3-冻结佣金 4-用户余额
     * @var int
     */
    private int $balance_type;

    /**
     * 账单标题
     * @var string
     */
    private string $title = '';

    /**
     * 备注
     * @var string
     */
    private string $remarks = '';

    /**
     * 操作的金额
     * @var float
     */
    private float $amount;

    /**
     * 是否记录日志
     * @var bool
     */
    private bool $log = true;

    /**
     * @param bool $log
     * @return MoneyLogRepository
     */
    public function setLog(bool $log): MoneyLogRepository
    {
        $this->log = $log;
        return $this;
    }

    /**
     * 变更余额
     */
    public function change(): array
    {
        $this->amount = $this->change_type * abs($this->amount);

        $user_model = new UserModel();
        $user_wallet = $user_model->where('id', $this->user_id)->field(self::$BALANCE_TYPE_FIELDS)->find();
        $field = self::$BALANCE_TYPE_FIELDS[$this->balance_type];
        $user_balance = $user_wallet[$field];
        $new_balance = bcadd($user_balance, $this->amount, 2);

        if ($new_balance < 0) {
            return [
                'code' => 7001,
                'msg' => '余额不足'
            ];
        }
        $user_model->startTrans();

        $time = time();
        $data = [
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            'order_id' => $this->order_id,
            'from_user_id' => $this->from_user_id,
            'from_user_money' => $this->from_user_money,
            'change_type' => $this->change_type,
            'from_type' => $this->from_type,
            'balance_type' => $this->balance_type,
            'title' => $this->title,
            'remarks' => $this->remarks,
            'amount' => $this->amount,
            'balance_before' => $user_balance,
            'balance_after' => $new_balance,
            'create_time' => $time,
            'update_time' => $time,
        ];

        // 修改用户余额
        try {
            $user_model->where('id', $this->user_id)
                ->update([
                    $field => $new_balance,
                ]);
            if ($this->log) {
                // 写入余额变动表
                $this->model->insert($data);
            }
            $user_model->commit();
            return [
                'code' => 1,
                'msg' => '操作成功'
            ];
        } catch (\Exception $e) {
            $user_model->rollback();
            // 记录日志
            Log::channel('balance')->error("用户{$this->user_id} {$field}变动余额 {$this->amount}失败，原因：" . $e->getMessage());
        }
        return [
            'code' => 0,
            'msg' => '操作失败'
        ];
    }

    /**
     * 积分和分佣
     * @param int $order_id 订单id
     * @param int $type 订单类型
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function integralAndCommission(int $order_id, int $type): array
    {
        // 旅游套餐用有积分和分佣
        // 商品订单只有分佣
        if (!in_array($type, [OrderModel::TYPE_TOURISM, OrderModel::TYPE_NORMAL])) {
            return [
                'code' => 991,
                'msg' => '参数错误，type 值不正确'
            ];
        }

        // 积分收益
        if ($type === OrderModel::TYPE_TOURISM) {
            // 获取用户vip等级
            $user = (new UserModel())->getInfo($this->user_id);
            $vip_level = $user['vip_level'];
            // 获取vip等级赠送积分规则
            $vip_right = VipRightModel::where('vip_level', $vip_level)
                ->where('right_id', 1)
                ->find();
            if (!$vip_right) {
                return [
                    'code' => 991,
                    'msg' => '参数错误，该vip等级没有设置赠送积分规则'
                ];
            }
            // 计算积分倍数
            $amount = floatval(bcmul($this->amount, $vip_right['number'], 2));
            $money_log_repository = new MoneyLogRepository($this->user_id, $amount);
            $money_log_repository->setOrderId($order_id);
            $money_log_repository->integralIncome();
        }
        event(new TeamCommission($this->user_id, $this->amount));
        return [
            'code' => 1,
            'msg' => '操作成功'
        ];
    }

    /**
     * 余额收入
     * @param int $from_type 余额收入类型
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    public function balanceIncome(
        int $from_type = MoneyLogRepository::FROM_TYPE_BALANCE_RECHARGE,
        string $title = '用户充值',
        string $remarks = ''
    ): array
    {
        if (!in_array($from_type, self::$BALANCE_INCOME_TYPE)) {
            return [
                'code' => 991,
                'msg' => '操作失败'
            ];
        }
        $this->setFromType($from_type);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE);
        return $this->income($title, $remarks);
    }

    /**
     * 佣金收入
     * @param int $from_type 佣金收入类型
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    public function commissionIncome(
        int $from_type = MoneyLogRepository::FROM_TYPE_COMMISSION_TEAM_INCOME,
        string $title = '下级返佣',
        string $remarks = ''
    ): array
    {
        if (!in_array($from_type, self::$COMMISSION_INCOME_TYPE)) {
            return [
                'code' => 991,
                'msg' => '操作失败'
            ];
        }
        if ($from_type === MoneyLogRepository::FROM_TYPE_COMMISSION_TEAM_INCOME) {
            // 下级返佣必须有下级id
            if ($this->from_user_id === 0) {
                return [
                    'code' => 991,
                    'msg' => '参数错误，from_user_id 没有设置值'
                ];
            }
            if ($this->from_user_money === 0) {
                return [
                    'code' => 991,
                    'msg' => '参数错误，from_user_money 没有设置值'
                ];
            }
        }
        $this->setFromType($from_type);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_COMMISSION);
        return $this->income($title, $remarks);
    }

    /**
     * 积分收入
     * @param int $from_type
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    public function integralIncome( // 现在积分只有旅游订单一种获取方式
        int $from_type = MoneyLogRepository::FROM_TYPE_INTEGRAL_TRAVEL_INCOME,
        string $title = '旅游订单赠送',
        string $remarks = ''
    ): array
    {
        if (!in_array($from_type, self::$INTEGRAL_INCOME_TYPE)) {
            return [
                'code' => 991,
                'msg' => '参数错误，from_type 错误'
            ];
        }
        if ($from_type === MoneyLogRepository::FROM_TYPE_INTEGRAL_TRAVEL_INCOME) {
            // 旅游订单必须有订单id
            if ($this->order_id === 0) {
                return [
                    'code' => 991,
                    'msg' => '参数错误，缺少 order_id'
                ];
            }
        }
        if (!empty($this->order_id)) {
            if ($this->from_user_id) { // 旅游订单不能用下级id
                return [
                    'code' => 991,
                    'msg' => '参数错误，不能设置 from_user_id'
                ];
            }
        }
        $this->setFromType($from_type);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_INTEGRAL);
        return $this->income($title, $remarks);
    }


    /**
     * 收入
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    private function income(
        string $title = '',
        string $remarks = ''
    ): array
    {
        $this->setChangeType(MoneyLogRepository::CHANGE_TYPE_ADD);

        if (!$this->title) {
            $this->setTitle($title);
        }
        if (!$this->remarks) {
            $this->setRemarks($remarks);
        }

        return $this->change();
    }

    /**
     * 支出
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    private function outlay(
        string $title = '',
        string $remarks = ''
    ): array
    {
        $this->setChangeType(MoneyLogRepository::CHANGE_TYPE_MINUS);

        if (!$this->title) {
            $this->setTitle($title);
        }
        if (!$this->remarks) {
            $this->setRemarks($remarks);
        }

        return $this->change();
    }

    /**
     * 余额支出
     * @param int $from_type 余额支出类型
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    public function balancePay(
        int $from_type = MoneyLogRepository::FROM_TYPE_BALANCE_PAY,
        string $title = '用户支付',
        string $remarks = ''
    ): array
    {
        if (!in_array($from_type, self::$BALANCE_INCOME_TYPE)) {
            return [
                'code' => 991,
                'msg' => '操作失败'
            ];
        }
        $this->setFromType($from_type);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE);
        return $this->outlay($title, $remarks);
    }

    /**
     * 积分支出
     * @param int $from_type 积分支出类型
     * @param string $title 账单标题
     * @param string $remarks 备注
     * @return array
     */
    public function integralPay(
        int $from_type = MoneyLogRepository::FROM_TYPE_BALANCE_PAY,
        string $title = '用户支付',
        string $remarks = ''
    ): array
    {
        if (!in_array($from_type, self::$BALANCE_INCOME_TYPE)) {
            return [
                'code' => 991,
                'msg' => '操作失败'
            ];
        }
        $this->setFromType($from_type);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_INTEGRAL);
        return $this->outlay($title, $remarks);
    }

    /**
     * 佣金提现
     * @param string $title
     * @param string $remarks
     * @return array
     */
    public function commissionWithdraw(string $title = '佣金提现', string $remarks = ''): array
    {
        // 扣除佣金
        $this->setFromType(MoneyLogRepository::FROM_TYPE_COMMISSION_WITHDRAWAL);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_COMMISSION);
        $res = $this->outlay($title, $remarks);
        if ($res['code'] === 1) {
            // 冻结佣金
            $this->setFromType(MoneyLogRepository::FROM_TYPE_COMMISSION_WITHDRAWAL);
            $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_FROZEN_COMMISSION);
            $res = $this->income($title . '-冻结金额', $remarks);
        }
        return $res;
    }

    /**
     * 余额提现
     * @param string $title
     * @param string $remarks
     * @return array
     */
    public function balanceWithdraw(string $title = '余额提现', string $remarks = ''): array
    {
        // 扣除佣金
        $this->setFromType(MoneyLogRepository::FROM_TYPE_BALANCE_WITHDRAWAL);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE);
        $res = $this->outlay($title, $remarks);
        return $res;
    }

    /**
     * 积分转佣金
     * @param string $title
     * @param string $remarks
     * @return array
     */
    public function integralToCommission(
        string $title = '积分转佣金',
        string $remarks = ''
    ): array
    {
        // 扣除积分
        $this->setFromType(MoneyLogRepository::FROM_TYPE_INTEGRAL_TRANSFER);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_INTEGRAL);
        $res = $this->outlay($title, $remarks);
        if ($res['code'] === 1) {
            // 增加佣金
            $this->setFromType(MoneyLogRepository::FROM_TYPE_INTEGRAL_TRANSFER);
            $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_COMMISSION);
            $res = $this->income($title, $remarks);
        }
        return $res;
    }

    /**
     * 积分抵扣
     * @param int $order_id
     * @param string $title
     * @param string $remarks
     * @return array
     */
    public function integralDeduction(
        int $order_id,
        string $title = '积分抵扣',
        string $remarks = ''
    ): array
    {
        $this->setOrderId($order_id);
        $this->setFromType(MoneyLogRepository::FROM_TYPE_INTEGRAL_DEDUCTION);
        $this->setBalanceType(MoneyLogRepository::BALANCE_TYPE_INTEGRAL);
        return $this->outlay($title, $remarks);
    }
}
