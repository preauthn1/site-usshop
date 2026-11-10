<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\AppVersionModel;
use app\transform\AppVersionTransform;
use app\validate\AppVersionValidate;
use think\App;
use think\Request;
use think\response\Json;


/**
 * App版本接口
 * Class User
 * @package app\controller
 */
class AppVersion extends BaseIndexEncryptController
{
    protected $model;

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * 检查app是否有新版本
     */
    public function check(Request $request, AppVersionTransform $transform): Json
    {
        $formData = [
            'version_code'  => $request->param('version_code'),
            'platform_type' => $request->param('platform_type'),
        ];

        $validate = AppVersionValidate::class.'.check';
        $this->validate($formData, $validate);
        $result = AppVersionModel::where('version_code', '>', $formData['version_code'])
            ->where('platform_type', $formData['platform_type'])
            ->status(1)
            ->find();
        if ($result) {
            return self::returnUnMsg(1, $transform->transform($result->toarray()));
        }
        return self::returnMsg(0, '', '没有新版本');
    }

}
