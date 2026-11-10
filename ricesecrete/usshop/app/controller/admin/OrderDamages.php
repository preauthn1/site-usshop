<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\order\OrderDamagesModel;
use think\Request;

/**
 * 退订规则管理
 * OrderDamages
 * Class OrderDamages
 * @package app\controller
 */
class OrderDamages extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new OrderDamagesModel();
    }

    /**
     * 分页列表
     * @param string $keyword
     * @param string $status
     * @param string $type
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($keyword = '', $status = '', $type = '', $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            $out_data['typeList'] = OrderDamagesModel::$TYPE_MAP;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        if (!empty($keyword)) {
            $map[] = ['name', 'like', '%' . trim($keyword) . '%'];
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
        //`name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
        //  `min_day` int DEFAULT '0' COMMENT '最小天数',
        //  `max_day` int DEFAULT '0' COMMENT '最大天数据',
        //  `ratio` float(5,2) DEFAULT '0.00' COMMENT '扣除比例',
        //  `type` tinyint(1) DEFAULT '1' COMMENT '规则类型 1非节假日 2节假日 3平台取消赔付违约金',
        //  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1开启 2关闭',
        $formData = [
            'id' => $request->param('id'),
            'min_day' => $request->param('min_day'),
            'max_day' => $request->param('max_day'),
            'ratio' => $request->param('ratio'),
            'type' => $request->param('type'),
            'status' => $request->param('status'),
        ];
        if ($formData['min_day'] >= 10) {
            $formData['name'] = '集合日前10日之前(含)';
        } else {
            if (intval($formData['max_day']) == 0) {
                $formData['name'] = '集合当日';
            } else {
                $formData['name'] = "集合日前" . $formData['max_day'] . "日 - " . $formData['min_day'] . "日(含)";
            }

        }
        if (empty($formData['id'])) {
            $validate = 'app\validate\OrderDamagesValidate.add';
        } else {
            $validate = 'app\validate\OrderDamagesValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);
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
