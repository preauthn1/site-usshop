<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\money\MoneyLogModel;
use app\model\order\OrderModel;
use app\repository\MoneyLogRepository;
use app\transform\CommissionTransform;
use app\transform\IntegralTransform;
use think\App;
use think\Request;
use think\response\Json;

class Income extends BaseIndexEncryptController
{
    protected $repository;

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, MoneyLogRepository $repository, MoneyLogModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
        $this->repository = $repository;
    }


    public function integral(Request $request, IntegralTransform $transform): Json
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $order_no = $this->request->param('order_no');


        $search = [
            'user_id'      => $this->currentUser['user_id'],
            'balance_type' => MoneyLogModel::FROM_TYPE_INTEGRAL_TRAVEL_INCOME,
            'create_time'  => [$start_time, $end_time],
        ];

        // 如果有order_no, 先查出order_id, 再查出对应的积分记录
        if ($order_no) {
            $order_id = OrderModel::where('order_no', 'like', $order_no.'%')->column('id');
            if ($order_id) {
                $search['order_ids'] = $order_id;
            }
        }

        $integral = $this->repository
            ->with([
                'tourism_order' => function ($query) {
                    $query->field('id,order_no');
                }
            ])
            ->search($search)
            ->paginate($limit);

        $data = $transform->transformPages($integral->toArray());
        return self::returnMsg(1, $data);
    }

    public function commission(Request $request, CommissionTransform $transform): Json
    {
        $user_id = $this->currentUser['user_id'];
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $from = $this->request->param('from');

        $map[] = ['user_id', '=', $user_id];

        if ($from) {
            $map[] = ['balance_type', '=', $from];
        } else {
            $map[] = [
                'balance_type', 'in', [
                    MoneyLogModel::FROM_TYPE_COMMISSION_TEAM_INCOME,
                    MoneyLogModel::FROM_TYPE_COMMISSION_SHARE_INCOME,
                    MoneyLogModel::FROM_TYPE_COMMISSION_ACTIVITY_INCOME
                ]
            ];
        }

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
}
