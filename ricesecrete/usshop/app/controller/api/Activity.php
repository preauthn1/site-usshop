<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\ActivityModel;
use app\transform\ActivityTransform;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;

class Activity extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, ActivityModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 活动列表
     * @throws DbException
     */
    public function getPage(Request $request, ActivityTransform $transform): Json
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', 1);
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        $notifications = $this->model->getPageList($map, 'id desc', $page, $limit);
        $data = $transform->transformPages($notifications);
        return self::returnMsg(1, $data);
    }

    /**
     * 活动详情
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function detail($id, ActivityTransform $transform): Json
    {
        $notification = $this->model->getInfo($id);
        if ($notification) {
            return self::returnMsg(1, $transform->transform($notification->toArray()));
        }
        return self::returnMsg(0);
    }

}
