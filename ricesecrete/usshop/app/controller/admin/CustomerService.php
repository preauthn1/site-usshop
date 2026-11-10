<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\CustomerServiceModel;
use app\validate\CustomerServiceValidate;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;

/**
 * 客服
 * CustomerService
 * Class CustomerService
 * @package app\controller
 */
class CustomerService extends BaseEncryptController
{
    public function __construct(CustomerServiceModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param  string  $status
     * @param  string  $keyword
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     */
    public function getPage(string $status = '', string $keyword = '', int $page = 1, int $limit = 10): Json
    {
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%'.trim($keyword).'%'];
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
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getInfo($id): Json
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
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'      => $request->param('id'),
            'type'    => $request->param('type'),
            'title'   => $request->param('title'),
            'content' => $request->param('content'),
            'status'  => $request->param('status'),
            'sort'    => $request->param('sort')
        ];
        if (empty($formData['id'])) {
            $validate = CustomerServiceValidate::class.'.add';
        } else {
            $validate = CustomerServiceValidate::class;
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
    public function onDelete($id): Json
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
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function onStatus(Request $request): Json
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
