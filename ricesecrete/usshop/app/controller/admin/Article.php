<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\ArticleModel;
use app\model\system\LanguageModel;
use app\validate\ArticleValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 内容管理
 * Article
 * Class Article
 * @package app\controller
 */
class Article extends BaseEncryptController
{
    public function __construct(ArticleModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $type
     * @param string $keyword
     * @param string $init
     * @param int $page
     * @param int $limit
     * @return Json
     */
    public function getPage(string $status = '', string $type = '', string $keyword = '', $init = '', int $page = 1, int $limit = 10): Json
    {
        if (!empty($init)) {
            //获取初始化数据
            // <el-option label="运费" :value="1"></el-option>
            //            <el-option label="退货政策" :value="2"></el-option>
            //            <el-option label="隐私中心" :value="3"></el-option>
            //            <el-option label="条款和条件" :value="4"></el-option>
            //            <el-option label="关于我们" :value="5"></el-option>
            $out_data['typeList'] = [
                1 => "运费",
                2 => "退货政策",
                3 => "隐私中心",
                4 => "条款和条件",
                5 => "关于我们"
            ];
            $out_data['langList'] = LanguageModel::getCacheList();
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%' . trim($keyword) . '%'];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        $data = $this->model->getPageData($status, $map, $page, $limit);
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
     * @param Request $request
     * @return Json
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'lang' => $request->param('lang'),
            'type' => $request->param('type'),
            'content' => $request->param('content'),
            'status' => $request->param('status'),
        ];
        if (empty($formData['id'])) {
            $validate = ArticleValidate::class . '.add';
        } else {
            $validate = ArticleValidate::class;
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
     * @param Request $request
     * @return Json
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
