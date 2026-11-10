<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\NestedSets;
use app\model\money\CommissionRateModel;
use app\model\money\MoneyLogModel;
use app\model\user\UserModel;
use app\model\user\UserTeamModel;
use app\repository\MoneyLogRepository;
use app\transform\MoneyLogTransform;
use app\transform\TeamUserTransform;
use Exception;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;


/**
 * 团队
 * Class User
 * @package app\controller
 */
class Team extends BaseIndexEncryptController
{
    protected $model;

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, UserTeamModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 团队总览
     * @param  Request  $request
     * @return Json
     * @throws Exception
     */
    public function index(Request $request): Json
    {
        $user_id = $this->currentUser['user_id'];
        $team_id = $this->model->where('user_id', $user_id)->value('id');

        $nestedObj = new NestedSets($this->model);
        if (!$team_id){
            // 没有团队信息，创建团队信息
            $time = time();
            $data = ['user_id' => $user_id];
            $data['create_time'] = $time;
            $data['update_time'] = $time;
            $res = $nestedObj->insert(0, $data);
            $team_id = $res['id'];
        }

        $level1_user = $nestedObj->getBranch($team_id, ['id', 'user_id', 'activated']);
        $level2_user = $nestedObj->getBranch($team_id, ['id', 'user_id', 'activated'], 2);

        // 总提现金额
        $withdrawal_amount = MoneyLogModel::where('user_id', 'in', $level2_user->column('user_id'))
            ->where('from_type', MoneyLogModel::FROM_TYPE_COMMISSION_WITHDRAWAL)
            ->sum('amount');

        // 一级激活/未激活数量
        $level1_activated_count = $level1_user->where('activated', 1)->count();
        $level1_unactivated_count = $level1_user->where('activated', 2)->count();
        // 二级激活/未激活数量
        $level2_activated_count = $level2_user->where('activated', 1)->count() - $level1_activated_count;
        $level2_unactivated_count = $level2_user->where('activated', 2)->count() - $level1_unactivated_count;

        // 获取佣金比例
        $commission_rate = CommissionRateModel::where('status', 1)->select();
        $level1 = $commission_rate->where('level', 1)->first();
        $level2 = $commission_rate->where('level', 2)->first();

        // 获取一级代理返佣金额
        $level1_commission = MoneyLogModel::where('user_id', 'in', $level1_user->column('user_id'))
            ->where('from_type', MoneyLogModel::FROM_TYPE_COMMISSION_TEAM_INCOME)
            ->sum('amount');
        // 获取二级代理返佣金额
        $level2_commission = MoneyLogModel::where('user_id', 'in', $level2_user->column('user_id'))
            ->where('from_type', MoneyLogModel::FROM_TYPE_COMMISSION_TEAM_INCOME)
            ->sum('amount');

        // 一级代理总览
        $level1 = [
            'activated_count'   => $level1_activated_count,
            'unactivated_count' => $level1_unactivated_count,
            'commission'        => $level1_commission,
            'rate'              => $level1 ? $level1->rate : '0.00',
        ];
        // 二级代理总览
        $level2 = [
            'activated_count'   => $level2_activated_count,
            'unactivated_count' => $level2_unactivated_count,
            'commission'        => $level2_commission,
            'rate'              => $level2 ? $level2->rate : '0.00'
        ];

        $data = [
            'team_count'         => $level2_user->count(),
            'popularize_code'    => $this->currentUser['popularize_code'],
            'consumption_amount' => 0, // 团队消费
            'withdraw_amount'    => $withdrawal_amount, // 团队提现
            'level1'             => $level1,
            'level2'             => $level2,
        ];

        return self::returnMsg(1, $data, '获取成功');
    }

    /**
     * 团队成员列表
     * @param  TeamUserTransform  $transform
     * @return Json
     * @throws Exception
     */
    public function userPage(TeamUserTransform $transform): Json
    {
        $level = $this->request->param('level/d', 1);
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $nick_name = $this->request->param('nick_name');


        $user_id = $this->currentUser['user_id'];
        [$user_ids, $team_users] = $this->getTeamUserByLevel($user_id, $level);

        $where = [];
        $where[] = ['id', 'in', $user_ids];

        if ($start_time) {
            $where[] = ['create_time', '>=', strtotime($start_time)];
        }
        if ($end_time) {
            $where[] = ['create_time', '<=', strtotime($end_time)];
        }
        if ($nick_name) {
            $where[] = ['nick_name', 'like', '%'.$nick_name.'%'];
        }

        $field = ['id', 'phone', 'nick_name', 'create_time'];
        $users = (new UserModel())->getPageList($where, 'id desc', $page, $limit, $field);
        $dataList = collect($users['dataList'])->each(function ($item) use ($team_users) {
            $item['level'] = $team_users->where('user_id', $item['id'])->first()['level'];
            return $item;
        });
        $users['dataList'] = $dataList->toArray();
        $data = $transform->transformPages($users);
        return self::returnMsg(1, $data, '获取成功');
    }

    /**
     * 团队提现列表
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function withdrawalPage(MoneyLogTransform $transform): Json
    {
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');

        $user_id = $this->currentUser['user_id'];
        $team_users = $this->model->getTeamUsers($user_id);
        $user_ids = $team_users->column('user_id');

        $money_logs = (new MoneyLogRepository())->with([
            'user' => function ($query) {
                $query->field(['id', 'phone', 'nick_name']);
            }
        ])->search([
            'from_type'    => MoneyLogModel::FROM_TYPE_COMMISSION_WITHDRAWAL,
            'balance_type' => MoneyLogModel::BALANCE_TYPE_COMMISSION,
            'create_time'  => [$start_time, $end_time],
            'user_ids'     => $user_ids
        ])->field(['id', 'user_id', 'amount', 'create_time'])
            ->order('id desc')
            ->paginate(['page' => $page, 'list_rows' => $limit]);

        $data = $transform->transformPages($money_logs->toArray());
        return self::returnMsg(1, $data);
    }

    /**
     * 团队成员返佣列表
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws Exception
     */
    public function commissionPage(MoneyLogTransform $transform): Json
    {
        $level = $this->request->param('level/d', 1);
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');

        $user_id = $this->currentUser['user_id'];
        [$user_ids] = $this->getTeamUserByLevel($user_id, $level);


        $money_logs = (new MoneyLogRepository())->with([
            'from_user' => function ($query) {
                $query->with([
                    'top_user' => function ($query) {
                        $query->visible(['id', 'top_user_id', 'phone', 'nick_name']);
                    }
                ])->field(['id', 'top_user_id', 'phone', 'nick_name', 'head_img']);
            },
        ])->search([
            'create_time'   => [$start_time, $end_time],
            'from_user_ids' => $user_ids
        ])->field(['id', 'user_id', 'from_user_id', 'from_user_money', 'amount', 'create_time'])
            ->order('id desc')
            ->paginate(['page' => $page, 'list_rows' => $limit]);

        $data = $transform->transformPages($money_logs->toArray());
        return self::returnMsg(1, $data);
    }

    /**
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws Exception
     */
    private function getTeamUserByLevel($user_id, $level): array
    {
        $team_user = $this->model->where('user_id', $user_id)->find();
        $nestedObj = new NestedSets($this->model);
        $level_user = $nestedObj->getBranch($team_user->id, ['id', 'user_id', 'activated', 'level'], 1);
        if ($level !== 1) {
            $level2_user = $nestedObj->getBranch($team_user->id, ['id', 'user_id', 'activated', 'level'], 2);
            $level_user = $level2_user->diff($level_user);
        }
        $level_user->each(function ($item) use ($team_user) {
            $item['level'] = $item['level'] - $team_user['level'];
            return $item;
        });
        $user_ids = $level_user->column('user_id');
        return [$user_ids, $level_user];
    }
}
