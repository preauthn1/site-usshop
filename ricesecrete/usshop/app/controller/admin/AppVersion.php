<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\AppVersionModel;
use app\validate\AppVersionValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 爱好
 * Hobby
 * Class Hobby
 * @package app\controller
 */
class AppVersion extends BaseEncryptController
{
    public function __construct(AppVersionModel $model, App $app)
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
            $map[] = ['version_code|version_name', 'like', '%'.trim($keyword).'%'];
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
            'id'              => $request->param('id'),
            'version_code'    => $request->param('version_code'),
            'version_name'    => $request->param('version_name'),
            'bulletin'        => $request->param('bulletin'),
            'platform_type'   => $request->param('platform_type'),
            'is_force_update' => $request->param('is_force_update'),
            'is_wgt'          => $request->param('is_wgt'),
            'url'             => $request->param('url'),
            'status'          => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = AppVersionValidate::class.'.add';
        } else {
            $validate = AppVersionValidate::class;
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
