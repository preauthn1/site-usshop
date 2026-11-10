<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\money\MoneyLogModel;
use app\repository\MoneyLogRepository;
use think\App;
use think\Request;

/**
 * 用户余额记录
 * MoneyLog
 * Class MoneyLog
 * @package app\controller
 */
class MoneyLog extends BaseEncryptController
{
    public function __construct(App $app, MoneyLogModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param int $from_type
     * @param string $balance_type
     * @param string $change_type
     * @param string $keyword
     * @param array $time_section
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($from_type = '', $balance_type = '', $change_type = '', $keyword = '', $time_section = [], $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            $out_data['fromTypeList'] = MoneyLogModel::$FROM_TYPE_MAP;
            $out_data['balanceTypeList'] = MoneyLogModel::$BALANCE_TYPE_MAP;
            $out_data['changeTypeList'] = MoneyLogModel::$CHANGE_TYPE_MAP;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (is_numeric($from_type)) {
            $map[] = ['from_type', '=', $from_type];
        }
        if (is_numeric($from_type)) {
            $map[] = ['balance_type', '=', $balance_type];
        }
        if (is_numeric($change_type)) {
            $map[] = ['change_type', '=', $change_type];
        }
        if (!empty($time_section)) {
            $map[] = ['create_time', '>=', strtotime($time_section[0])];
            $map[] = ['create_time', '<=', strtotime($time_section[1])];
        }
        if (!empty($keyword)) {
            $map[] = ['title|remarks', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $dataList
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $data = $this->model->getInfo($id);
        if (!empty($data)) {
            return self::returnMsg(1, $data);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存
     * @param Request $request
     * @return \think\response\Json
     */
    public function onSave(Request $request)
    {
        $user_id = $request->param('user_id', '');
        $change_type = $request->param('change_type', -1);
        $balance_type = $request->param('balance_type', 4);
        $amount = $request->param('amount', 0);
        $remarks = $request->param('remarks', '');
        if (empty($user_id) || empty($amount)) {
            return self::returnMsg(0);
        }
        if ($change_type == 1) {
            $money_log = new MoneyLogRepository($user_id, $amount);
            $money_log->setFromUserId($user_id);
            $money_log->setFromUserMoney($amount);
            if ($balance_type == 4) {
                $money_log->balanceIncome(
                    MoneyLogModel::FROM_TYPE_PLATFORM_INCREASE,
                    MoneyLogModel::$FROM_TYPE_MAP[MoneyLogModel::FROM_TYPE_PLATFORM_INCREASE],
                    $remarks
                );
            }
            if ($balance_type == 2) {
                $money_log->integralIncome(
                    MoneyLogModel::FROM_TYPE_PLATFORM_INCREASE,
                    MoneyLogModel::$FROM_TYPE_MAP[MoneyLogModel::FROM_TYPE_PLATFORM_INCREASE],
                    $remarks
                );
            }

        } else {
            $money_log = new MoneyLogRepository($user_id, $amount);
            $money_log->setFromUserId($user_id);
            $money_log->setFromUserMoney($amount);
            if ($balance_type == 4) {
                $money_log->balancePay(
                    MoneyLogModel::FROM_TYPE_PLATFORM_REDUCE,
                    MoneyLogModel::$FROM_TYPE_MAP[MoneyLogModel::FROM_TYPE_PLATFORM_REDUCE],
                    $remarks
                );
            }
            if ($balance_type == 2) {
                $money_log->integralPay(
                    MoneyLogModel::FROM_TYPE_PLATFORM_REDUCE,
                    MoneyLogModel::$FROM_TYPE_MAP[MoneyLogModel::FROM_TYPE_PLATFORM_REDUCE],
                    $remarks
                );
            }
        }

        return self::returnMsg(1);
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function onStatus(Request $request)
    {
        return self::returnMsg(1);
    }
}
