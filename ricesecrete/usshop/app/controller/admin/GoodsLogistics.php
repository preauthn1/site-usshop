<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\goods\GoodsLogisticsModel;
use think\Request;

/**
 * 商品快递公司
 * GoodsLogistics
 * Class GoodsLogistics
 * @package app\controller
 */
class GoodsLogistics extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new GoodsLogisticsModel();
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
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['name|code', 'like', '%' . trim($keyword) . '%'];
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
        //`id` int NOT NULL AUTO_INCREMENT,
        //  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '物流名称',
        //  `code` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '物流编码',
        //  `status` tinyint DEFAULT '1' COMMENT '状态 1开启 2关闭',
        //  `sort` int DEFAULT '0' COMMENT '排序',
        $formData = [
            'id' => $request->param('id'),
            'name' => $request->param('name'),
            'code' => $request->param('code'),
            'status' => $request->param('status'),
            'sort' => $request->param('sort'),
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\GoodsLogisticsValidate.add';
        } else {
            $validate = 'app\validate\GoodsLogisticsValidate';
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
