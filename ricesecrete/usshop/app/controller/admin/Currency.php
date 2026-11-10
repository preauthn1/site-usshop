<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\CurrencyModel;
use app\validate\CurrencyValidate;
use think\App;
use think\facade\Cache;
use think\Request;
use think\response\Json;

class Currency extends BaseEncryptController
{
    public function __construct(CurrencyModel $model, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 获取语言列表
     */
    public function getPage(Request $request, string $name = '', int $page = 1, int $limit = 10)
    {
        $status = $request->param('status', 1);
        $map = [];
        if (!empty($name)) {
            $map[] = ['name', 'like', '%' . trim($name) . '%'];
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
     * 添加语言
     */
    public function onSave(Request $request)
    {

        $formData = [
            'id' => $request->param('id'),
            'name' => $request->param('name'),
            'symbol' => $request->param('symbol'),
            'exchange_rate' => $request->param('exchange_rate'),
            'is_default' => $request->param('is_default'),
            'status' => $request->param('status'),
            'code' => $request->param('code'),
        ];

        if (empty($formData['id'])) {
            $validate = CurrencyValidate::class . '.add';
        } else {
            $validate = CurrencyValidate::class;
        }
        $this->validate($formData, $validate);

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            if ($formData['is_default'] == 1) {
                // 把其他的is_default改为2
                $this->model->resetIsDefault($ret['id']);
                Cache::delete("get_currency_rate_" . $formData['code']);
            }
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

    /**
     * 保存状态
     * @param Request $request
     * @return Json
     */
    public function onIsDefault(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'is_default' => $request->param('is_default'),
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            if ($formData['is_default'] == 1) {
                // 把其他的is_default改为2
                $this->model->resetIsDefault($ret['id']);
            }
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
