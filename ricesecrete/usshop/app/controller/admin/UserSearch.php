<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\Util;
use app\model\tourism\TourismCityModel;
use app\model\tourism\TourismTagModel;
use app\model\user\UserSearchModel;
use think\Request;

/**
 * 推荐热门搜索
 * UserSearch
 * Class UserSearch
 * @package app\controller
 */
class UserSearch extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new UserSearchModel();
    }

    /**
     * 分页列表
     * @param string $keyword
     * @param int $type
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     */
    public function getPage($keyword = '', $type = '', $page = 1, $limit = 10)
    {
        $map = [
            'user_id' => 0
        ];
        if (!empty($keyword)) {
            $map[] = ['keyword', 'like', '%' . trim($keyword) . '%'];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
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
        //`user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
        //  `keyword` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
        //  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1旅游套餐 2正常商品 3积分商品',
        //  `status` tinyint(1) DEFAULT '1' COMMENT '状态：1启用 2禁用',
        //  `sort` int DEFAULT '0' COMMENT '排序',
        $formData = [
            'id' => $request->param('id'),
            'keyword' => $request->param('keyword'),
            'type' => $request->param('type'),
            'sort' => $request->param('sort'),
            'status' => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\UserSearchValidate.add';
        } else {
            $validate = 'app\validate\UserSearchValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $formData['create_time'] = time();
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
