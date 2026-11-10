<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\SysBannerModel;
use app\model\system\SysNotificationModel;
use think\Request;

/**
 * 站内通知
 * SysNotification
 * Class SysNotification
 * @package app\controller
 */
class SysNotification extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new SysNotificationModel();
    }

    /**
     * 分页列表
     * @param string $user_id
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param string $area
     * @return \think\response\Json
     */
    public function getPage($user_id = '', $keyword = '', $page = 1, $limit = 10)
    {
        $map = [];
        if (!empty($user_id)) {
            $map[] = ['user_id', '=', $user_id];
        }
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
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
        // `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
        //  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
        //  `user_id` int DEFAULT '0' COMMENT '站内消息用户id',
        //  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息详情 （可以是富文本）',
        //  `read` tinyint(1) DEFAULT '1' COMMENT '1未读  2已读',
        $formData = [
            'title' => $request->param('title'),
            'content' => $request->param('content')
        ];
        $user_ids = $request->param('user_id');
        if (empty($user_ids)) {
            return self::returnMsg(0, '', '请选择用户');
        }
        if (empty($formData['id'])) {
            $validate = 'app\validate\SysNotificationValidate.add';
        } else {
            $validate = 'app\validate\SysNotificationValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = [
                'flag' => false,
                'msg' => '保存失败'
            ];
            foreach ($user_ids as $k => $v) {
                $formData['user_id'] = $v;
                $ret = $this->model->saveData($formData);
            }
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
