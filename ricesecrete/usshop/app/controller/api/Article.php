<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\ArticleModel;
use app\transform\ArticleTransform;
use think\App;
use think\db\exception\DbException;
use think\facade\Lang;
use think\Request;
use think\response\Json;

class Article extends BaseIndexEncryptController
{

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, ArticleModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 内容列表
     * @throws DbException
     */
    public function index(Request $request, ArticleTransform $transform): Json
    {
        $type = $request->param('type', 0);
        $lang = strtolower(Lang::getLangSet());
        $map = [
            'lang', '=', $lang
        ];
        $map[] = ['status', '=', 1];
        if (!empty($type)) {
            $map[] = ['type', 'in', $type];
        }
        $article = $this->model->getAll($map);
        $data = $transform->transformCollection($article);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取内容详情
     * @param Request $request
     * @param ArticleTransform $transform
     * @return Json
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(Request $request, ArticleTransform $transform): Json
    {
        $type = $request->param('type', 0);
        $lang = strtolower(Lang::getLangSet());
        $map = [
            'lang' => $lang,
            'type' => $type,
            'status' => 1
        ];
        $article = $this->model->where($map)->find();
        if ($article) {
            return self::returnMsg(1, $transform->transform($article->toArray()));
        }
        return self::returnMsg(0);
    }
}
