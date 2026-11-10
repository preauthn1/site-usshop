<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\ActivityModel;
use app\validate\ActivityValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 活动管理
 * Activity
 * Class Activity
 * @package app\controller
 */
class Activity extends BaseEncryptController
{
    public function __construct(ActivityModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param  string  $status
     * @param  string  $type
     * @param  string  $keyword
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     */
    public function getPage(
        string $status = '',
        string $type = '',
        string $keyword = '',
        int $page = 1,
        int $limit = 10
    ): Json {
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%'.trim($keyword).'%'];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        $data = $this->model->getPageData($status, $map, $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount'  => $data['total'],
            'currentPage' => $data['current'],
            'pageList'    => $dataList
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return Json
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
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'       => $request->param('id'),
            'title'    => $request->param('title'),
            'type'     => $request->param('type'),
            'head_img' => $request->param('head_img'),
            'summary'  => $request->param('summary'),
            'content'  => $request->param('content'),
            'status'   => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = ActivityValidate::class.'.add';
        } else {
            $validate = ActivityValidate::class;
        }
        $this->validate($formData, $validate);

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
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
     * @param  Request  $request
     * @return Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id'     => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
