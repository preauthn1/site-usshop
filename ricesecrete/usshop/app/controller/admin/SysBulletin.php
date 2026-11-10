<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\jobs\BulletinJob;
use app\model\system\SysBulletinModel;
use app\validate\SysBulletinValidate;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Queue;
use think\Request;
use think\response\Json;

/**
 * 公告
 * SysBulletin
 * Class SysBulletin
 * @package app\controller
 */
class SysBulletin extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();

    }

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SysBulletinModel();
    }


    /**
     * 分页列表
     * @param  string  $user_id
     * @param  string  $keyword
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     * @throws DbException
     */
    public function getPage($user_id = '', $keyword = '', $page = 1, $limit = 10)
    {
        $map = [];
        if (!empty($user_id)) {
            $map[] = ['user_id', '=', $user_id];
        }
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
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
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
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
     * @param  Request  $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function onSave(Request $request)
    {
//        `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
//  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
//  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息详情 （可以是富文本）',
//  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用，2禁用',
//  `is_popup` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否是弹窗公告 1-是 2-不是',
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'content' => $request->param('content'),
            'status' => $request->param('status'),
            'is_popup' => $request->param('is_popup'),
        ];
        if (empty($formData['id'])) {
            $validate = SysBulletinValidate::class . '.add';
        } else {
            $validate = SysBulletinValidate::class;
        }
        $this->validate($formData, $validate);

        $ret = $this->model->saveData($formData);

        if (false !== $ret['flag']) {
            // 保存成功，加入队列
            Queue::push(BulletinJob::class.'@add_notification', $ret['id'], $queue = null);
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }

    }

    /**
     * 删除
     * @param $id
     * @return Json
     */
    public function onDelete($id): Json
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            // 删除成功，删除站内信
            Queue::push(BulletinJob::class.'@delete_notification', $id, $queue = null);
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return Json
     */
    public function onStatus(Request $request): Json
    {
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            // 保存成功，加入队列
            Queue::push(BulletinJob::class.'@add_notification', $ret['id'], $queue = null);
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
