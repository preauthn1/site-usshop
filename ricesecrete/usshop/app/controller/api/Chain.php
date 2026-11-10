<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\ChainModel;
use app\transform\ChainTransform;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;

class Chain extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, ChainModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 银行列表
     * @throws DbException
     */
    public function index(Request $request, ChainTransform $transform): Json
    {
        $map[] = ['status', '=', 1];
        $banks = $this->model->getAll($map);
        $data = $transform->transformCollection($banks);
        return self::returnMsg(1, $data);
    }
}
