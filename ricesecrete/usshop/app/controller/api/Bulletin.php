<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\SysBulletinModel;
use app\transform\BulletinTransform;
use think\App;
use think\db\exception\DbException;
use think\response\Json;

class Bulletin extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, SysBulletinModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 公告列表
     * @throws DbException
     */
    public function index(BulletinTransform $transform): Json
    {
        $map[] = ['status', '=', 1];
        $map[] = ['is_popup', '=', 1];
        $notifications = $this->model->getAll($map);
        $data = $transform->transformCollection($notifications);
        return self::returnMsg(1, $data);
    }
}
