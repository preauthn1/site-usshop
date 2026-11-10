<?php

namespace app\controller\admin;

use app\base\BaseController;
use app\base\BaseModel;
use app\libs\S3;
use app\model\system\LanguageItemModel;
use app\model\system\LanguageModel;
use think\facade\Cache;

/**
 * 通用处理
 * Class Common
 * @package app\controller
 */
class Common extends BaseController
{
    /**
     * 图形验证码
     * @return mixed
     */
    public function getCaptcha()
    {
        return captcha();
    }

    /**
     * 上传文件
     * @param $folder
     * @return \think\response\Json
     */
    public function onUpload($folder = '')
    {
        $rtn = S3::uploadFile($folder);
        return self::returnMsg($rtn);
    }

    /**
     * 删除文件
     * @param $url
     * @return \think\response\Json
     */
    public function onDelfile($url)
    {
        //假删除
        return self::returnMsg(1);
//        $rtn = S3::delFile($url);
//        return self::returnMsg($rtn);
    }

    /**
     * 获取S3预签名URL
     */
    public function getS3PreSignedUrl()
    {
        $folder = input('folder', '');
        $filename = input('filename', '');
        return S3::getPresignedUrl($folder, $filename);
    }

    /**
     * 获取S3文件URL
     * @return \think\response\Json
     */
    public function s3File()
    {
        $filename = input('filename');
        $pre_signed = input('pre_signed/b', false);  // 添加布尔类型参数
        return json(S3::getFileUrl($filename, $pre_signed));
    }

    /**
     * 设置更新语言
     * @param string $title
     * @return \think\response\Json
     */
    public function setUpdateLang($title = '')
    {
        $auto_lang = config('lang.auto_lang');
        if ($auto_lang && !empty($title)) {
            createLang($title);
        }
        return self::returnMsg(1);
    }

    /**
     * 获取配置信息
     * @return \think\response\Json
     */
    public function getConfig()
    {
        $temp_lang = Cache::get(LanguageModel::$cacheKeyTemp);
        if ($temp_lang) {
            $lang = unserialize($temp_lang);
        } else {
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
            $lang = $result;
        }
        $data = [
            'lang' => $lang,
            'lang_list' => LanguageModel::getCacheList(),
            'langCacheTime' => Cache::get(LanguageModel::$cacheKeyTempTime, 0)
        ];
        $out_data = [
            'code' => 1,
            'msg' => 'success',
            'data' => $data
        ];
        return json($out_data);
    }
}
