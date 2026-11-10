<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\goods\GoodsExplainModel;
use think\Request;

/**
 * 商品说明文档
 * GoodsExplain
 * Class GoodsExplain
 * @package app\controller
 */
class GoodsExplain extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new GoodsExplainModel();
    }

    /**
     * 分页列表
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($keyword = '', $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            //获取初始化数据
            $out_data['typeList'] = GoodsExplainModel::$TYPE_EXPLAIN;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        foreach ($dataList as &$v) {
            $v['type_name'] = GoodsExplainModel::$TYPE_EXPLAIN[$v['type']];
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
        // `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明标题',
        //  `content` text COLLATE utf8mb4_unicode_ci COMMENT '内容',
        //  `type` tinyint DEFAULT '1' COMMENT '类型 1退订政策 2赠送积分 3报名须知 4报名限制 5旅游装备 6退订政策',
        //  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'content' => $request->param('content'),
            'type' => $request->param('type'),
            'status' => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\GoodsExplainValidate.add';
        } else {
            $validate = 'app\validate\GoodsExplainValidate';
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
