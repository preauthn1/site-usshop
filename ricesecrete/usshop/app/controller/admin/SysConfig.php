<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\SysConfigModel;
use think\Request;

/**
 * 系统配置
 * Class SysConfig
 * @package app\controller
 */
class SysConfig extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new SysConfigModel();
    }

    /**
     * 分页列表
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     */
    public function getPage($keyword = '', $page = 1, $limit = 10)
    {
        $data = $this->model->getPageData($keyword, $page, $limit, '*');
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
        $info = $this->model->getInfo($id);
        if (!empty($info)) {
            return self::returnMsg(1, $info);
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
            'key' => $request->param('key'),
            'value' => $request->param('value'),
        ];
        if (empty($formData['id'])) {
            $formData['add_time'] = time();
            $formData['update_time'] = time();
            $validate = 'app\validate\SysConfigValidate.add';
        } else {
            $formData['update_time'] = time();
            $validate = 'app\validate\SysConfigValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData, true);
            if (false !== $ret['flag']) {
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
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

}
