<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\S3;
use app\model\system\CurrencyModel;
use app\model\system\LanguageModel;
use think\facade\Cache;
use think\facade\Lang;

/**
 * 通用处理
 * Class Common
 * @package app\controller
 */
class Common extends BaseIndexEncryptController
{

    /**
     * 获取配置文件
     * @return \think\response\Json
     */
    public function getConfig()
    {
        $currency = CurrencyModel::where('is_default', 1)->find();
        if (empty($currency)) {
            $currency = [
                'name' => 'U.S. Dollar1',
                'symbol' => '$',
                'code' => 'USD',
                'exchange_rate' => 1,
            ];
        }
        $langCacheTime = Cache::get(LanguageModel::$cacheKeyTempTime, 0);
        $lang = LanguageModel::getDefault();
        $config = [
            'currency_name' => $currency['name'],//货币名称
            'currency_symbol' => $currency['symbol'],//货币符号
            'currency_code' => $currency['code'],//货币代码
            'currency_exchange_rate' => $currency['exchange_rate'],//汇率
            'lang_cache_time' => $langCacheTime,//语言缓存时间
            'lang' => $lang,//语言缓存时间
        ];
        return self::returnMsg(1, $config);
    }

    /**
     * 上传文件
     * @param $folder
     * @param $uid
     * @return \think\response\Json
     */
    public function onUpload($folder = '', $uid = '')
    {
        $rtn = S3::uploadFile($folder, $uid);
        return self::returnMsg($rtn);
    }

    /**
     * 删除文件
     * @param $url
     * @return \think\response\Json
     */
    public function onDelfile($url = '')
    {
        $rtn = S3::delFile($url);
        return self::returnMsg($rtn);
    }

    /**
     * 设置更新语言
     * @param string $title
     * @return \think\response\Json
     */
    public function setUpdateLang($title = '')
    {
        $auto_lang = config('lang.auto_lang');
        if (is_array($title)) {
            foreach ($title as $key => $value) {
                createLang($value);
            }
        } else {
            if ($auto_lang && !empty($title)) {
                createLang($title);
            }
        }
        return self::returnMsg(1);
    }
}
