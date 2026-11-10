<?php

declare(strict_types=1);

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\base\BaseModel;
use app\model\system\CurrencyModel;
use app\transform\api\CurrencyTransform;
use think\App;
use think\response\Json;

class Currency extends BaseIndexEncryptController
{

    public function __construct(App $app, CurrencyModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
   * Get language translations
   */
  public function index(): Json
  {
      $currency = $this->model->where('status', BaseModel::STATUS_ENABLE)->select();
      $currency = (new CurrencyTransform())->transformCollection($currency->toArray());
      return self::returnUnMsg(1, $currency);
  }
}
