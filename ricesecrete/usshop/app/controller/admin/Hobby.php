<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\HobbyModel;
use app\validate\HobbyValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 爱好
 * Hobby
 * Class Hobby
 * @package app\controller
 */
class Hobby extends BaseEncryptController
{
    public function __construct(HobbyModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param int $init
     * @return Json
     */
    public function getPage(string $status = '', string $keyword = '', int $page = 1, int $limit = 10, string $init = ''): Json
    {
        if (!empty($init)) {
            //获取初始化数据
            $out_data['hobbyType'] = HobbyModel::$hobbyType;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($status, $map, $page, $limit);
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
     * @return Json
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'src' => $request->param('src'),
            'status' => $request->param('status'),
            'sort' => $request->param('sort')
        ];
        if (empty($formData['id'])) {
            $validate = HobbyValidate::class . '.add';
        } else {
            $validate = HobbyValidate::class;
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
     * @param Request $request
     * @return Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
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
