<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\AuthHandleLogModel;
use think\Request;

/**
 * 系统操作日志
 * SysLog
 * Class SysLog
 * @package app\controller
 */
class SysLog extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new AuthHandleLogModel();
    }

    /**
     * 分页列表
     * @param string $admin_id
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     */
    public function getPage($admin_id = '', $keyword = '', $page = 1, $limit = 10)
    {
        $map = [];
        if (!empty($admin_id)) {
            $map[] = ['a.admin_id', '=', $admin_id];
        }
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map[] = ['a.bureau_id', '=', $bureau_id];
        }
        if (!empty($keyword)) {
            $map[] = ['a.name|a.param', 'like', '%' . trim($keyword) . '%'];
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
     * @return \think\response\Json
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
     * @return \think\response\Json
     */
    public function onSave(Request $request)
    {
        return self::returnMsg(0);
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        return self::returnMsg(1);
//        $flag = $this->model::destroy(['id' => $id]);
//        if (false !== $flag) {
//            return self::returnMsg(1);
//        } else {
//            return self::returnMsg(1002);
//        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return \think\response\Json
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
