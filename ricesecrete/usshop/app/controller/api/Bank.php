<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\BankModel;
use app\transform\BankTransform;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;

class Bank extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, BankModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 银行列表
     * @throws DbException
     */
    public function index(Request $request, BankTransform $transform): Json
    {
        $map[] = ['status', '=', 1];
        $banks = $this->model->getAll($map);
        $data = $transform->transformCollection($banks);
        return self::returnMsg(1, $data);
    }
}
