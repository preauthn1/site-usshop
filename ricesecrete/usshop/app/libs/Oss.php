<?php

namespace app\libs;

use OSS\Core\OssException;
use OSS\OssClient;
use think\facade\Config;

/**
 * 阿里云oss操作类
 * Class Oss
 * @package app\libs
 */
class Oss
{
    /**
     * 上传文件
     * @param string $folder
     * @param string $uid
     * @return array
     */
    public static function uploadOssFile($folder = '', $uid = '')
    {
        $files = request()->file('files');
        if (empty($files)) {
            $files = request()->file();
        }
        if (!is_array($files)) {
            $files = [$files];
        }
        $fileArr = [];
        if (!empty($files) && count($files) > 0) {
            foreach ($files as $file) {
                $width = null;
                $height = null;
                try {
                    $config = Config::get('system.aliyun_oss');
                    //实例化对象 将配置传入
                    $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
                    //这里是有sha1加密 生成文件名 之后连接上后缀
                    $fileName = $folder . '/' . date("Y-m-d") . "/" . sha1(date('YmdHis', time()) . uniqid()) . '.' . $file->extension();
                    //执行阿里云上传
                    $result = $ossClient->uploadFile($config['bucket'], $fileName, $file);
                    if ($result['info']['http_code'] == '200') {
                        $fileArr[] = ['name' => $fileName, 'url' => $config['url'] . '/' . $fileName, 'uid' => $uid, 'type' => $file->extension()];
                    }
                } catch (OssException $e) {
                    return ['code' => 3003, 'msg' => $e->getMessage()];
                }
            }
        } else {
            return ['code' => 3004];
        }
        return ['code' => 1, 'data' => $fileArr];
    }

    public static function moveOssFile($object, $file)
    {
        $back['url'] = '';
        if (empty($file)) {
            $back['code'] = 0;
            $back['msg'] = '文件不能为空';
            return $back;
        }
        $config = Config::get('system.aliyun_oss');
        $web = $config['url'];
        $filetemp = $file;
        if (!file_exists($filetemp)) {
            $back['code'] = 0;
            $back['msg'] = '本地文件不存在';
            return $back;
        }
        //实例化OSS
        try {
            $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
            //uploadFile的上传方法
            $ossClient->uploadFile($config['bucket'], $object, $file);
        } catch (OssException $e) {
            //如果出错这里返回报错信息
            $back['code'] = 0;
            $back['msg'] = $e->getMessage();
            return $back;
        }
        $back['code'] = 1;
        $back['url'] = $web . "/" . $object;
        $back['msg'] = '上传成功';
        return $back;
    }

    /**
     * 根据url删除
     * @param $url
     * @return array
     */
    public static function delOssFile($url)
    {
        if (empty($url)) {
            return ['code' => 3005];
        }
        try {
            $config = Config::get('system.aliyun_oss');
            if (strpos($url, $config['url']) !== false) {
                $urlArr = explode($config['url'] . '/', $url);
                if (isset($urlArr[1])) {
                    $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
                    $ossClient->deleteObject($config['bucket'], $urlArr[1]);
                    return ['code' => 1];
                }
            }
            return ['code' => 3006];
        } catch (\Exception $e) {
            return ['code' => 992, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 签名
     * @param $dir
     * @return array
     */
    public function sign($dir)
    {
        if (empty($dir)) {
            $dir = date("Ymd", time()) . '/';
        } else {
            $dir .= '/';
        }

        $end = time() + 10000;
        $expiration = date(DATE_ISO8601, $end);
        $expiration = substr($expiration, 0, strpos($expiration, '+'));
        $expiration .= "Z";

        //最大文件大小.用户可以自己设置
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => 1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);
        $conditions[] = $start;
        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->config['accessKeySecret'], true));

        $response = array();
        $response['accessid'] = $this->config['accessKeyId'];
        $response['host'] = $this->config['url'];
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['dir'] = $dir;
        return $response;
    }
}