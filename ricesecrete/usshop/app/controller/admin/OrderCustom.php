<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\order\OrderCustomModel;
use app\model\user\UserModel;
use think\Request;

/**
 * 套餐定制信息
 * OrderCustom
 * Class OrderCustom
 * @package app\controller
 */
class OrderCustom extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new OrderCustomModel();
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($status = '', $keyword = '', $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            $statusList = OrderCustomModel::$STATUS_MAP;
            $out_data['statusList'] = $statusList;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (is_numeric($status)) {
            $map[] = ['status', '=', $status];
        }
        if (!empty($keyword)) {
            $map[] = ['name|enterprise|address|destination', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        foreach ($dataList as &$item) {
            $user_info = UserModel::getNameHead($item['user_id']);
            $item['user_info'] = $user_info;
            $item['type_str'] = OrderCustomModel::TYPE_MAP[$item['type']];
            $item['status_str'] = OrderCustomModel::$STATUS_MAP[$item['status']];
        }
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
            $user_info = UserModel::getNameHead($data['user_id']);
            $data['user_info'] = $user_info;
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
        // `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
        //  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
        //  `enterprise` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '企业名称',
        //  `type` tinyint(1) DEFAULT '1' COMMENT '定制类型 1企业定制 2私人定制  ',
        //  `address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发地址',
        //  `destination` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '目的地',
        //  `start_day` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发时间',
        //  `number` int DEFAULT '0' COMMENT '人数',
        //  `budget` decimal(11,2) DEFAULT '0.00' COMMENT '人均预算',
        //  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
        //  `admin_id` int DEFAULT '0' COMMENT '接待管理员id',
        $formData = [
            'id' => $request->param("id"),
            "user_id" => $request->param("user_id"),
            "name" => $request->param("name"),
            "phone" => $request->param("phone"),
            "enterprise" => $request->param("enterprise"),
            "type" => $request->param("type"),
            "address" => $request->param("address"),
            "destination" => $request->param("destination"),
            "start_day" => $request->param("start_day"),
            "number" => $request->param("number"),
            "budget" => $request->param("budget"),
            "remark" => $request->param("remark"),
            "admin_id" => $this->currentUser['user_id'],
        ];
        $ret = $this->model->saveData($formData);//修改是否添加进缓存。。。
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
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
        $ret = $this->model->changeStatus($formData['id'], $formData['status'], $this->currentUser['user_id']);
        if (false !== $ret) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
