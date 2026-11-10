<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\user\VipModel;
use app\model\user\RightModel;
use app\transform\VipTransform;
use app\transform\RightTransform;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;


/**
 * 会员中心接口
 * Class User
 * @package app\controller
 */
class Vip extends BaseIndexEncryptController
{
    protected $model;

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, VipModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 团队总览
     * @param  Request  $request
     * @param  VipTransform  $transform
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(Request $request, VipTransform $transform): Json
    {
        $level = $this->model->with(['rights'])->select();
        $data = $transform->transformCollection($level->toArray());
        return self::returnMsg(1, $data, '获取成功');
    }

    public function rights(RightTransform $transform)
    {
        $rights = RightModel::with('vips')->select();
        $data = $transform->transformCollection($rights->toArray());
        return self::returnMsg(1, $data, '获取成功');
    }


}
