<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\AuthGroupModel;
use app\model\system\AuthRuleModel;
use think\Request;

/**
 * 角色分组
 * Class AuthGroup
 * @package app\controller
 */
class AuthGroup extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new AuthGroupModel();
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     */
    public function getPage($status = '', $keyword = '', $page = 1, $limit = 10)
    {
        $data = $this->model->getPageData($status, $keyword, $page, $limit, 'id,title,status,create_time');
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $data['dataList']
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $group = $this->model->getInfoByRedis($id);
        if (!empty($group)) {
            return self::returnMsg(1, $group);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 所有列表
     * @return \think\response\Json
     */
    public function getAll()
    {
        $groupList = $this->model->getAllByRedis();
        return self::returnMsg(1, ['groupList' => $groupList]);
    }

    /**
     * 保存
     * @param Request $request
     * @return \think\response\Json
     */
    public function onSave(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'status' => $request->param('status'),
            'rules' => $request->param('rules')
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\AuthGroupValidate.add';
        } else {
            $validate = 'app\validate\AuthGroupValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveDataRedis($formData, true);
            if (false !== $ret['flag']) {
                AuthRuleModel::clearAuthMenuCache();
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            $this->model->delRedis($id);
            AuthRuleModel::clearAuthMenuCache();
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
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
        $validate = 'app\validate\AuthGroupValidate.upstatus';
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData, true);
            if (false !== $ret['flag']) {
                AuthRuleModel::clearAuthMenuCache();
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }
}
