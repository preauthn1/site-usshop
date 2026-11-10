<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\money\MoneyLogModel;
use app\model\system\SysConfigModel;
use app\model\user\UserModel;
use app\repository\MoneyLogRepository;
use app\transform\IntegralTransform;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;

class Integral extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, MoneyLogModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    public function getPage(Request $request, IntegralTransform $transform): Json
    {
        $user_id = $this->currentUser['user_id'];
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');

        $map[] = ['user_id', '=', $user_id];
        $map[] = ['balance_type', '=', MoneyLogModel::BALANCE_TYPE_INTEGRAL];

        if ($start_time) {
            $map[] = ['create_time', '>=', strtotime($start_time)];
        }
        if ($end_time) {
            $map[] = ['create_time', '<=', strtotime($end_time)];
        }

        $integral = $this->model->getPageList($map, 'id asc', $page, $limit);
        $data = $transform->transformPages($integral);
        return self::returnMsg(1, $data);
    }

    /**
     * @param  Request  $request
     * @return Json
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function index(Request $request): Json
    {
        // 获取用户当前积分
        $user_id = $this->currentUser['user_id'];
        $user = UserModel::where('id', $user_id)->find();
        $integral = $user['integral'];
        // 获取积分转换成佣金的比例
        $rate = SysConfigModel::where('key', 'integralToCommissionRate')->value('value');
        // 获取每日积分转佣金次数
        $integral_to_commission_count = SysConfigModel::where('key', 'dailyIntegralToCommissionCount')->value('value');
        // 计算佣金
        $commission = round($integral * $rate, 2);

        $today_integral_to_commission_count = MoneyLogModel::getTodayIntegralToCommissionCount($user_id);

        $can_exchange = $today_integral_to_commission_count < $integral_to_commission_count;
        // 返回数据
        return self::returnMsg(1, [
            'integral'     => $integral,
            'commission'   => $commission,
            'rate'         => $rate,
            'can_exchange' => $can_exchange
        ]);
    }

    /**
     * 积分转佣金
     * @param  Request  $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function exchange(Request $request): Json
    {
        // 获取用户当前积分
        $user_id = $this->currentUser['user_id'];
        $user = UserModel::where('id', $user_id)->find();
        $integral = $user['integral'];
        // 获取积分转换成佣金的比例
        $rate = SysConfigModel::where('key', 'integralToCommissionRate')->value('value');

        // 获取每日积分转佣金次数
        $integral_to_commission_count = SysConfigModel::where('key', 'dailyIntegralToCommissionCount')->value('value');
        // 计算佣金
        $commission = $integral * $rate;

        // 获取今日佣金转换次数
        $money_log = new MoneyLogRepository($user_id, $commission);

        $today_commission_count = $money_log->search([
            'user_id'      => $user_id,
            'balance_type' => MoneyLogModel::BALANCE_TYPE_INTEGRAL,
            'from_type'    => MoneyLogModel::FROM_TYPE_INTEGRAL_TRANSFER,

        ])->get()->count();

        if ($today_commission_count >= $integral_to_commission_count) {
            return self::returnMsg(9001);
        }

        $res = $money_log->integralToCommission();
        if ($res['code'] === 0) {
            return self::returnMsg(0, $res, $res['msg']);
        }

        return self::returnMsg(1);
    }
}
