<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\PaymentChannelModel;
use app\validate\PaymentChannelValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 支付渠道管理
 * PaymentChannel
 * Class PaymentChannel
 * @package app\controller
 */
class PaymentChannel extends BaseEncryptController
{
    public function __construct(PaymentChannelModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $type
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return Json
     */
    public function getPage(string $status = '', string $type = '', string $keyword = '', int $page = 1, int $limit = 10): Json
    {
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['name', 'like', '%' . trim($keyword) . '%'];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        $data = $this->model->getPageList($map, '', $page, $limit);
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
            'name' => $request->param('name'),
            'ident' => $request->param('ident'),
            'src' => $request->param('src'),
            'status' => $request->param('status'),
            'withdrawal_status' => $request->param('withdrawal_status'),
            'pay_status' => $request->param('pay_status'),
            'config' => $request->param('config'),
            'type' => $request->param('type'),
            'min_withdrawal_money' => $request->param('min_withdrawal_money'),
            'max_withdrawal_money' => $request->param('max_withdrawal_money'),
        ];
        if (empty($formData['id'])) {
            $validate = PaymentChannelValidate::class . '.add';
        } else {
            $validate = PaymentChannelValidate::class;
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
            $request->param('column') => $request->param('status'),
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
