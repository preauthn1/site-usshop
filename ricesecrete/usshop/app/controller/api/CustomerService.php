<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\CustomerServiceModel;
use app\transform\CustomerServiceTransform;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class CustomerService extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, CustomerServiceModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }


    /**
     * @param  CustomerServiceTransform  $transform
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(CustomerServiceTransform $transform): Json
    {
        $customer_services = $this->model->where('status', 1)->select();

        $data = $transform->transformCollection($customer_services->toarray());
        return self::returnMsg(1, $data);
    }

}
