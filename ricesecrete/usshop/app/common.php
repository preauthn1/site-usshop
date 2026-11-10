<?php

// 元转分
use app\model\system\LanguageItemModel;
use think\facade\Cache;
use think\facade\Lang;

function yuan2fen(float $yuan): int
{
    return intval($yuan * 100);
}

// 分转元
function fen2yuan(int $fen): float
{
    return round($fen / 100, 2);
}

/**
 * 转换货币类型
 * @param int $price
 * @param string $currency
 * @return float
 */
function currencyToShow($price = 0, $currency = 'USD')
{
    $exchange_rate = \app\model\system\CurrencyModel::getCurrencyRate($currency);
    return round($price * $exchange_rate, 2);
}

if (!function_exists('lang')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function lang($name, $vars = [], $lang = '')
    {
        if (empty($name)) {
            return '';
        }
        $auto_lang = config('lang.auto_lang');
        if ($auto_lang) {
            createLang($name);
        }
        $str = Lang::get($name, $vars, $lang);
        // 判断如果str是中文，则进行翻译
//        if (preg_match('/[\x{4e00}-\x{9fa5}]+/u', $str) && ('zh-cn' != Lang::getLangSet() && 'zh-tw' != Lang::getLangSet())) {
        // 判断是否有缓存
//            $redis = Cache::store('redis');
//            $redis_key = 'lang_'. md5($str);
//            $result = $redis->get($redis_key);
//            if ($result) {
//                return $result;
//            }
//            createLang($name);
//            $trans = new GoogleTranslate();
//            $source = 'zh-CN';//Source language, 源语言
//            $def_lang = ConfigModel::getConfigValue('system_default_lang');
//            $str = $trans->translate($source, $def_lang, $str);
//            $redis->set($redis_key, $str, 24 * 3600 * 5); // 缓存5天
//        }
        return $str;

    }

    function createLang($name)
    {
        $info = LanguageItemModel::where('title', $name)->find();
        if (!$info) {
            LanguageItemModel::create([
                'title' => $name
            ]);
        }
    }

}

function isChinese($str)
{
    return preg_match('/[\x{4e00}-\x{9fa5}]/u', $str) === 1;
}

/**
 * 限制请求频率
 * @param string $key 缓存键
 * @param int $timeout 限制时间（秒）
 * @return bool 返回true表示允许请求，返回错误信息表示请求过于频繁
 * @throws Exception
 */
function requestLimit(string $key, int $timeout = 10): bool
{
    if (Cache::has($key)) {
        throw new Exception('请求过于频繁');
    }

    // 设置缓存，指定时间后过期
    Cache::set($key, true, $timeout);

    return true;
}
