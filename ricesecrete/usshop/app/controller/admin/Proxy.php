<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\user\ProxyModel;
use app\model\user\UserModel;
use think\App;
use think\response\Json;

class Proxy extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new ProxyModel();
    }

    /**
     * 分页列表
     * @param string $keyword 关键词搜索
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return Json
     */
    public function getPage($keyword = '', $page = 1, $limit = 10)
    {
        $map = [
            ['is_agent', '=', 1]
        ];

        if (!empty($keyword)) {
            $map[] = ['email|invite_code', 'like', '%' . trim($keyword) . '%'];
        }

        $data = $this->model->getPageData($map, $page, $limit);
        foreach ($data['dataList'] as $k => $v) {
            $user_count = $this->model->where('top_user_id', $v['id'])->count();
            $data['dataList'][$k]['subordinate_count'] = $user_count;
        }

        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $data['dataList']
        ]);
    }

    /**
     * 获取下级列表
     * @param int $id 代理ID
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return Json
     */
    public function onSubordinates($id = 0, $page = 1, $limit = 10): Json
    {
        if (empty($id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $data = (new ProxyModel())->getPageData(['top_user_id' => $id], $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $dataList
        ]);
    }
}
