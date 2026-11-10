<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\Util;
use app\model\system\AuthRuleModel;
use app\model\system\LanguageModel;
use think\facade\Cache;
use think\Request;

/**
 * 权限菜单
 * Class AuthRule
 * @package app\controller
 */
class AuthRule extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new AuthRuleModel();
    }

    /**
     * 层级列表
     * @return \think\response\Json
     */
    public function getTree()
    {
        $ruleList = $this->model->getAll('', true);
        $ruleTree = Util::levelTreeChildren($ruleList, 0);
        return self::returnMsg(1, ['treeData' => $ruleTree]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $rule = $this->model->getInfoById($id);
        if (!empty($rule)) {
            return self::returnMsg(1, $rule);
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
        $formData = [
            'id' => $request->param('id'),
            'name' => $request->param('name'),
            'title' => $request->param('title'),
            'url' => $request->param('url'),
            'url_param' => $request->param('url_param'),
            'icon' => $request->param('icon'),
            'type' => $request->param('type'),
            'api_method' => $request->param('api_method'),
            'rank' => $request->param('rank'),
            'parent_id' => $request->param('parent_id')
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\AuthRuleValidate.add';
        } else {
            $validate = 'app\validate\AuthRuleValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                $obj = $this->model->getInfo($ret['id']);
                $this->model->redis->hSet($this->model->redis_key, $obj["name"], serialize($obj));
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
        $obj = $this->model->getInfo($id);
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            $this->model->delRedis($obj['name']);
            AuthRuleModel::clearAuthMenuCache();
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }
}
