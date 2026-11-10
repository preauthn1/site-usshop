<?php

declare(strict_types=1);

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\base\BaseModel;
use app\model\system\LanguageModel;
use app\model\system\LanguageItemModel;
use think\App;
use think\facade\Cache;
use think\Response;
use think\response\Json;

class Language extends BaseIndexEncryptController
{

    public function __construct(App $app, LanguageModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    public function index(): Json
    {
        $language = $this->model->where('status', BaseModel::STATUS_ENABLE)->select();
        return self::returnUnMsg(1, $language);
    }

    /**
     * Get language translations
     */
    public function translate(): Response
    {
        $temp_lang = Cache::get(LanguageModel::$cacheKeyTemp);
        if ($temp_lang) {
            return self::returnUnMsg(1, unserialize($temp_lang));
        }
        // Get all enabled languages
        $languages = LanguageModel::where(['status' => BaseModel::STATUS_ENABLE])->select();
        // Get all language items with their details
        $items = LanguageItemModel::with(['details'])->select();
        // Format the response
        $result = [];
        foreach ($languages as $language) {
            $langCode = $language['code'];
            $result[$langCode] = [];
            foreach ($items as $item) {
                $title = $item['title'];
                $translation = '';
                // Find translation for current language
                foreach ($item['details'] as $detail) {
                    if ($detail['language_id'] == $language['id']) {
                        $translation = $detail['text'];
                        break;
                    }
                }
                $result[$langCode][$title] = $translation ?: $title;
            }
        }
        Cache::set(LanguageModel::$cacheKeyTemp, serialize($result));
        Cache::set(LanguageModel::$cacheKeyTempTime, time());
        return self::returnUnMsg(1, $result);
    }
}
