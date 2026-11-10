<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\goods\GoodsBuyLogModel;
use app\model\goods\GoodsProductModel;
use app\model\goods\GoodsSpecModel;
use app\model\user\UserModel;
use think\Request;

/**
 * 商品买记录
 * GoodsBuy
 * Class GoodsBuy
 * @package app\controller
 */
class GoodsBuy extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new GoodsBuyLogModel();
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
            $map[] = ['user_name', 'like', '%' . trim($keyword) . '%'];
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
            $data['product_name'] = GoodsProductModel::where('id', $data['product_id'])->value('title');
            $data['spec_name'] = GoodsSpecModel::where('id', $data['spec_id'])->value('spec');
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
        //`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
        //  `user_id` int NOT NULL DEFAULT '0' COMMENT '购买用户',
        //  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户昵称',
        //  `user_head` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户头像',
        //  `product_id` int DEFAULT '0' COMMENT '产品id',
        //  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
        $formData = [
            'id' => $request->param('id'),
            'user_id' => $request->param('user_id'),
            'product_id' => $request->param('product_id'),
            'spec_id' => 0,
            'num' => 0,
            'price' => 0,
        ];
        $speclist = GoodsSpecModel::getSpecList($formData['product_id']);
        if (empty($speclist)||(!isset($speclist[0]))) {
            return self::returnMsg(0, '', '请先添加套餐规格');
        }
        $formData['spec_id'] = $speclist[0]['id'];
        if (empty($formData['id'])) {
            $validate = 'app\validate\TourismBuyValidate.add';
        } else {
            $validate = 'app\validate\TourismBuyValidate';
        }
        $user_info = UserModel::getNameHead($formData['user_id']);
        $formData['user_name'] = $user_info['nick_name'];
        $formData['user_head'] = $user_info['head_img'];
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

    }
}
