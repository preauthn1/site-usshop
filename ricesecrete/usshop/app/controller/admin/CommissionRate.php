<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\money\CommissionRateModel;
use app\validate\CommissionRateValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 返佣比例
 * Hobby
 * Class commissionRate
 * @package app\controller
 */
class CommissionRate extends BaseEncryptController
{
    public function __construct(CommissionRateModel $model, App $app)
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
//        if (!empty($keyword)) {
//            $map[] = ['', 'like', '%'.trim($keyword).'%'];
//        }
        $data = $this->model->getPageList($status, $map, $page, $limit);
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
            'id'     => $request->param('id'),
            'level'  => $request->param('level'),
            'rate'   => $request->param('rate'),
            'status' => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = CommissionRateValidate::class.'.add';
        } else {
            $validate = CommissionRateValidate::class;
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
