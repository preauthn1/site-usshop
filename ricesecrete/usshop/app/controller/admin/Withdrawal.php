<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\money\WithdrawalModel;
use app\model\user\UserModel;
use app\validate\HobbyValidate;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\response\Json;

/**
 * 提现
 * Withdrawal
 * Class Hobby
 * @package app\controller
 */
class Withdrawal extends BaseEncryptController
{
    public function __construct(WithdrawalModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param  Request  $request
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     * @throws DbException
     */
    public function getPage(Request $request, int $page = 1, int $limit = 10): Json
    {
        $status = $request->param('status', '');
        $create_time = $request->param('create_time', []);
        $check_time = $request->param('check_time', []);
        $user_id = $request->param('user_id', '');

        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%'.trim($keyword).'%'];
        }
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        if (is_numeric($user_id)) {
            $map[] = ['user_id', '=', $user_id];
        }
        if (!empty($create_time)) {
            $map[] = ['create_time', '>=', strtotime($create_time[0])];
            $map[] = ['create_time', '<=', strtotime($create_time[1])];
        }
        if (!empty($pay_time)) {
            $map[] = ['check_time', '>=', strtotime($check_time[0])];
            $map[] = ['check_time', '<=', strtotime($check_time[1])];
        }
        $data = $this->model->getPageList($map, 'id desc', $page, $limit);
        $dataList = $data['dataList'];
        foreach ($dataList as &$item) {
            $item['user_info'] = UserModel::getUserInfo($item['user_id'], 'username,phone,email');
            $item['check_time'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['check_time']) : '';
        }
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
            'title'  => $request->param('title'),
            'src'    => $request->param('src'),
            'status' => $request->param('status'),
            'sort'   => $request->param('sort')
        ];
        if (empty($formData['id'])) {
            $validate = HobbyValidate::class.'.add';
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
     * @param  Request  $request
     * @return Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id'         => $request->param('id'),
            'status'     => $request->param('status'),
            'check_time' => time()
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
