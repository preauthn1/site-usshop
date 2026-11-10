<?php
declare (strict_types = 1);

namespace app\listener;

use app\libs\NestedSets;
use app\model\money\CommissionRateModel;
use app\model\user\UserTeamModel;
use app\repository\MoneyLogRepository;
use Exception;
use think\facade\Log;

class TeamCommission
{

    /**
     * 事件监听处理
     * @throws Exception
     */
    public function handle($event)
    {
        $user_id = $event->user_id;
        // 获取当前用户上两级用户
        $team_user = UserTeamModel::where('user_id', $user_id)->find();
        $team_id = $team_user->id;
        $nestedObj = new NestedSets(new UserTeamModel());
        $parents = $nestedObj->getParent($team_id, ['id', 'user_id', 'level'], 2);
        // 获取分佣比例
        $commission_rate = CommissionRateModel::where('status', 1)
            ->field(['id', 'level', 'rate', 'status'])
            ->select();
        if($commission_rate->isEmpty()) {
            Log::channel('balance')->error("用户{$user_id}分佣失败，获取分佣比例失败");
            throw new Exception('获取分佣比例失败');
        }

        $parents->each(function ($item) use ($team_user) {
            $item['level'] = $team_user['level'] - $item['level'];
            return $item;
        });

        foreach ($parents as $key => $parent) {
            $level = $parent['level'];
            $rate = $commission_rate->where('level', $level)->first();
            if ($rate->isEmpty()) {
                Log::channel('balance')->error("用户{$user_id}分佣{$event->amount}给用户{$parent['id']}失败，获取分佣比例失败");
                continue;
            }

            $amount = (float) bcmul((string) $event->amount, (string) ($rate->rate / 100), 2);
            $money_log = new MoneyLogRepository($parent['user_id'], $amount);
            $money_log->setFromUserId($user_id);
            $money_log->setFromUserMoney($event->amount);
            $money_log->commissionIncome();
        }
    }
}
