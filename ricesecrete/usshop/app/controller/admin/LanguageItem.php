<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\api\ApiIndexCode;
use app\model\system\LanguageItemModel;
use app\validate\LanguageItemValidate;
use think\Request;
use think\response\Json;

class LanguageItem extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new LanguageItemModel();
    }

    /**
     * 获取类目列表
     */
    public function getPage(Request $request, string $title = '', int $page = 1, int $limit = 10): Json
    {
        $map = [];
        if (!empty($title)) {
            $map[] = ['title', 'like', '%' . trim($title) . '%'];
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
     * 添加类目
     */
    public function onSave(Request $request)
    {
        $type = $request->param('type', '');
        if ($type == 'input') {
            $formData = [
                'content' => $request->param('content')
            ];
            $content_arr = explode(",", $formData['content']);
            $LanguageItemModel = new LanguageItemModel();
            foreach ($content_arr as $item) {
                $map = [
                    'title' => $item
                ];
                $id = $this->model->where($map)->value('id');
                if (!empty($id)) {
                    continue;
                }
                $in_data = [
                    'title' => $item
                ];
                $LanguageItemModel->insert($in_data);
            }
            return self::returnMsg(ApiIndexCode::SUCCESS);
        }

        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'details' => $request->param('details'),
        ];
        if (empty($formData['id'])) {
            $validate = LanguageItemValidate::class . '.add';
        } else {
            $validate = LanguageItemValidate::class;
        }
        $this->validate($formData, $validate);

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } else {
            return self::returnMsg(ApiIndexCode::FAIL);
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
}
