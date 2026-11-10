<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\Util;
use app\model\goods\GoodsLangModel;
use app\model\goods\GoodsProductModel;
use app\model\goods\GoodsSpecModel;
use app\model\goods\GoodsStoreModel;
use app\model\goods\GoodsTypeModel;
use app\model\system\AuthUserModel;
use app\model\system\LanguageModel;
use app\model\user\UserModel;
use think\Request;

/**
 * 商品管理
 * GoodsProduct
 * Class GoodsProduct
 * @package app\controller
 */
class GoodsProduct extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new GoodsProductModel();
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $keyword
     * @param string $type_id
     * @param string $init
     * @param string $classify
     * @param string $product_id
     * @param string $type
     * @param string $id
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPage($status = '', $keyword = '', $type_id = '', $init = '', $classify = '', $product_id = '', $type = '', $id = '', $page = 1, $limit = 10)
    {
        $bureau_id = $this->currentUser['bureau_id'];
        if (empty($bureau_id)) {
            $bureau_id = '';
        }
        if (!empty($init)) {
            //获取初始化数据
            $tourism_type_model = new GoodsTypeModel();
            $list = $tourism_type_model->where('is_show', 1)->select();
            $levelTree = Util::levelTreeChildren($list, 0);
            Util::listOrder($levelTree, 'sort', 'desc', 'number');
            $out_data['levelTree'] = $levelTree;
            $out_data['langList'] = LanguageModel::getCacheList();
            return self::returnMsg(1, $out_data);
        }
        if (!empty($type)) {
            if ($type == 'spec') {
                $map = [
                    'product_id' => $product_id
                ];
                $product_name = GoodsProductModel::getProductName($product_id);
                $goods_spec_model = new GoodsSpecModel();
                $data = $goods_spec_model->getPageData($map, $page, $limit);
                $dataList = $data['dataList'];
                $row_num = $data['total'];
                if ($page > 1) {
                    $row_num = $row_num - (($page - 1) * $limit);
                }
                foreach ($dataList as $k => $v) {
                    $dataList[$k]['row_num'] = $row_num;
                    $row_num--;
                    $dataList[$k]['product_name'] = $product_name;
                }
                return self::returnMsg(1, [
                    'totalCount' => $data['total'],
                    'currentPage' => $data['current'],
                    'pageList' => $dataList
                ]);
            }
            if ($type == 'store') {
                $map = [
                    'product_id' => $product_id
                ];
                $product_name = GoodsProductModel::getProductName($product_id);
                $goods_store_model = new GoodsStoreModel();
                $data = $goods_store_model->getPageData($map, $page, $limit);
                $dataList = $data['dataList'];
                $row_num = $data['total'];
                if ($page > 1) {
                    $row_num = $row_num - (($page - 1) * $limit);
                }
                foreach ($dataList as $k => $v) {
                    $dataList[$k]['row_num'] = $row_num;
                    $row_num--;
                    $dataList[$k]['product_name'] = $product_name;
                    $dataList[$k]['spec'] = GoodsSpecModel::where('id', $v['spec_id'])->value('spec');
                    $user = UserModel::where('id', $v['user_id'])->field('phone,nick_name,head_img')->find();
                    if (!empty($user)) {
                        $dataList[$k]['user'] = $user;
                    } else {
                        $dataList[$k]['user'] = [
                            'phone' => '',
                            'nick_name' => '',
                            'head_img' => ''
                        ];
                    }
                    $admin = AuthUserModel::where('id', $v['admin_id'])->field('username,realname,avatar')->find();
                    if (!empty($admin)) {
                        $dataList[$k]['admin'] = $admin;
                    } else {
                        $dataList[$k]['admin'] = [
                            'username' => '',
                            'realname' => '',
                            'avatar' => ''
                        ];
                    }
                }
                return self::returnMsg(1, [
                    'totalCount' => $data['total'],
                    'currentPage' => $data['current'],
                    'pageList' => $dataList
                ]);
            }
        }
        $map = [];
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        if (!empty($type_id)) {
            $type_id_arr = GoodsTypeModel::getChildId($type_id);
            $map[] = ['type_id', 'in', $type_id_arr];
        }
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
        }
        if (!empty($bureau_id)) {
            $map[] = ['merchant_id', '=', $bureau_id];
        }
        if (!empty($id)) {
            $map[] = ['id', '=', $id];
        }
        if (!empty($classify)) {
            $map[] = ['classify', '=', $classify];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        foreach ($dataList as $key => $value) {
            $dataList[$key]['type_name'] = GoodsTypeModel::getTypeName($value['type_id']);
            $dataList[$key]['item_list'] = GoodsSpecModel::getSpecList($value['id']);
            $dataList[$key]['item_sum'] = GoodsSpecModel::where('product_id', $value['id'])->count();
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
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            if ($data['merchant_id'] != $bureau_id) {
                return self::returnMsg(1001);
            }
        }
        if (!empty($data)) {
            if (!empty($data['spec'])) {
                $spec = json_decode($data['spec'], true);
                foreach ($spec as $ke => $va) {
                    if (isChinese($va['name'])) {
                        $spec[$ke]['name'] = lang($va['name']);
                        foreach ($va['list'] as $k => $v) {
                            if (isChinese($v['name'])) {
                                $spec[$ke]['list'][$k]['name'] = lang($v['name']);
                            }
                        }
                    }
                }
                $data['spec'] = $spec;
            } else {
                $data['spec'] = [];
            }
            $data['goods_list'] = GoodsSpecModel::getSpecList($id, "*", 30);
            $data = GoodsLangModel::getGoodsLangs($data);
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
        //`title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
        //  `classify` tinyint(1) DEFAULT '1' COMMENT '商品模块分类  1正常商品  2积分商品',
        //  `type_id` int DEFAULT '0' COMMENT '归属分类',
        //  `merchant_id` int DEFAULT '0' COMMENT '商家id',
        //  `spec` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格名称spec json',
        //  `express_type` int DEFAULT '0' COMMENT '物流类型 1快递  2到店',
        //  `serve` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '服务说明  id 集合  七天无理由 假一赔四  48小时内发货',
        //  `max_price` decimal(20,2) DEFAULT '0.00' COMMENT '规格最高价格',
        //  `min_price` decimal(20,2) DEFAULT '0.00' COMMENT '规格最低价格',
        //  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
        //  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
        //  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
        //  `county` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
        //  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自提具体地址',
        //  `lng` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
        //  `lat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
        //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
        //  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
        //  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
        //  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：2下线，1上线',
        //  `sort` int DEFAULT '0' COMMENT '排序',
        //  `hot` tinyint(1) DEFAULT '1' COMMENT '是否热门 2否 1是',
        //  `rec` tinyint(1) DEFAULT '1' COMMENT '是否推荐 2否 1是',
        $bureau_id = $this->currentUser['bureau_id'];
        if (empty($bureau_id)) {
            $merchant_id = $request->param('merchant_id');
        } else {
            $merchant_id = $bureau_id;
        }
        $type = $request->param('type');
        if (!empty($type)) {
            if ($type == 'store') {
                $id = $request->param('id');
                $product_id = $request->param('product_id');
                $number = $request->param('number');
                $change = $request->param('change');
                if (!empty($id)) {
                    GoodsStoreModel::changeStore($product_id, $id, $number, $change, '', $this->currentUser['user_id']);
                    return self::returnMsg(1);
                }
            }
            return self::returnMsg(0);
        }

        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'classify' => $request->param('classify'),
            'express_type' => $request->param('express_type'),
            'type_id' => $request->param('type_id'),
            'merchant_id' => $merchant_id,
            'spec' => $request->param('spec'),
            'max_price' => $request->param('max_price'),
            'min_price' => $request->param('min_price'),
            'country' => $request->param('country'),
            'province' => $request->param('province'),
            'city' => $request->param('city'),
            'county' => $request->param('county'),
            'address' => $request->param('address'),
            'lng' => $request->param('lng'),
            'lat' => $request->param('lat'),
            'img' => $request->param('img'),
            'photo' => $request->param('photo'),
            'details' => $request->param('details'),
            'status' => $request->param('status'),
            'sort' => $request->param('sort'),
            'hot' => $request->param('hot'),
            'rec' => $request->param('rec'),
            'buy' => $request->param('buy'),
        ];

        if ($formData['express_type'] == 2) {
            if (empty($formData['lng']) || empty($formData['lat']) || empty($formData['address'])) {
                return self::returnMsg(0, '', '请完善自提信息');
            }
        }
        $formData = GoodsLangModel::addGoodsInfos($formData);
        // 商品规格列表
        $goods_list = $request->param('goods_list');
        if (empty($formData['id'])) {
            $validate = 'app\validate\GoodsProductValidate.add';
        } else {
            $validate = 'app\validate\GoodsProductValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            if (!empty($formData['spec'])) {
                $formData['spec'] = json_encode($formData['spec'], true);
            } else {
                $formData['spec'] = json_encode([]);
            }
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                foreach ($goods_list as $k => $v) {
                    if (empty($v['id'])) {
                        $goods_list[$k]['product_id'] = $ret['id'];
                        if (empty($v['img'])) {
                            $goods_list[$k]['img'] = $formData['img'];
                        }
                    }
                }
                //添加规格
                GoodsSpecModel::addSpecInfo($goods_list, $ret['id']);
                //刷新最高最低价格
                GoodsSpecModel::upMinMaxPrice($formData['id']);
                return self::returnMsg(1, $ret);
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
        $flag = false;
        if (is_array($id)) {
            foreach ($id as $k => $v) {
                $data = $this->model->getInfo($v);
                $bureau_id = $this->currentUser['bureau_id'];
                if (!empty($bureau_id)) {
                    if ($data['merchant_id'] != $bureau_id) {
                        return self::returnMsg(1002);
                    }
                }
                $flag = $this->model::destroy(['id' => $v]);
            }
        } else {
            $data = $this->model->getInfo($id);
            $bureau_id = $this->currentUser['bureau_id'];
            if (!empty($bureau_id)) {
                if ($data['merchant_id'] != $bureau_id) {
                    return self::returnMsg(1002);
                }
            }
            $flag = $this->model::destroy(['id' => $id]);
        }
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
        $field = $request->param('field') ?? 'status';
        $formData = [
            'id' => $request->param('id'),
            $field => $request->param($field),
        ];
        $data = $this->model->getInfo($formData['id']);
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            if ($data['merchant_id'] != $bureau_id) {
                return self::returnMsg(0);
            }
        }
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
