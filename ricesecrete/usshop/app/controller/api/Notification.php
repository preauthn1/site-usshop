<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\SysNotificationModel;
use app\transform\NotificationTransform;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;

class Notification extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, SysNotificationModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 消息列表
     * @throws DbException
     */
    public function getPage(Request $request, NotificationTransform $transform): Json
    {
        $user_id = $this->currentUser['user_id'];
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', '0');
        $map[] = ['user_id', '=', $user_id];
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        $notifications = $this->model->getPageData($map, $page, $limit);
        $data = $transform->transformPages($notifications);
        return self::returnMsg(1, $data);
    }

    /**
     * 标记已读
     * @param  Request  $request
     * @return Json
     */
    public function read(Request $request): Json
    {
        $id = $request->param('id');
        $res = $this->model->read($this->currentUser['user_id'], $id);
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 消息详情
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function detail($id, NotificationTransform $transform): Json
    {
        $notification = $this->model->detail($this->currentUser['user_id'], $id);
        if ($notification) {
            return self::returnMsg(1, $transform->transform($notification->toArray()));
        }
        return self::returnMsg(0);
    }

    /**
     * 标记全部已读
     * @param  Request  $request
     * @return Json
     */
    public function readAll(Request $request): Json
    {
        $res = $this->model->readAll($this->currentUser['user_id']);
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0, '', '暂无未读消息');
    }


    /**
     * 未读消息总数
     * @return Json
     * @throws DbException
     */
    public function unReadMsgCount(): Json
    {
        $user_id = $this->currentUser['user_id'];

        $count = SysNotificationModel::where(['user_id' => $user_id, 'read' => 2])->count();
        return self::returnMsg(1, ['count' => $count]);
    }
}
