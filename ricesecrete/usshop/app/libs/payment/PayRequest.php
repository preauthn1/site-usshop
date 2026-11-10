<?php

namespace app\libs\payment;

use think\facade\Log;

class PayRequest
{
    /**
     * 提交表单
     * @param $url
     * @param $return_array
     * @param $header array|string
     * @param  int  $timeout
     * @return bool|string
     * @throws \Exception
     */
    public static function curlS($url, $return_array, $header = [], int $timeout = 10)
    {
        if (!$header) {
            $header = ["Content-type: application/x-www-form-urlencoded"];
        }

        // 增加HTTP代理请求
        $proxy = config('system.proxy');


        if (is_array($return_array)) {
            $postData = "";
            foreach ($return_array as $key => $val) {
                $postData = $postData.$key."=".$val."&";
            }
            $postData = rtrim($postData, '&');
        } else {
            $postData = $return_array;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        $contents = curl_exec($ch);
        if ($contents === false) {
            Log::channel('payment')->info('payment_request_error:'.var_export([
                    'url'       => $url,
                    'post_data' => $postData,
                    'header'    => $header,
                    'error' => curl_error($ch)
                ], true));
            throw new \Exception('请求异常，请确认三方是否收到订单再继续操作', 0);
        }

        curl_close($ch);
        return $contents;
    }
}
