<?php

namespace app\libs\api;

use app\libs\Aes;
use think\facade\Config;

/**
 * API响应数据处理类
 * Trait ApiEncryptResponse
 * @package app\libs\api
 */
trait ApiEncryptResponse
{
    /**
     * 返回格式化数据(加密数据)
     * @param string $code
     * @param array $data
     * @param string $msg
     * @param array $header
     * @return \think\response\Json
     */
    public static function returnMsg($code = '', $data = [], $msg = '', $header = [])
    {
        if (is_array($code)) {
            $obj = $code;
            $code = isset($obj['code']) ? $obj['code'] : 500;
            $data = isset($obj['data']) ? $obj['data'] : [];
            $msg = isset($obj['msg']) ? $obj['msg'] : '';
            $header = isset($obj['header']) ? $obj['header'] : [];
        }
        $return_data = [
            'code' => '500',
            'msg' => 'undefined message',
            'data' => $code == 1 ? $data : [],
            'timestamp' => time()
        ];
        foreach ($header as $name => $val) {
            if (is_null($val)) {
                header($name);
            } else {
                header($name . ':' . $val);
            }
        }
        if (isset($code)) {
            $return_data['code'] = $code;
            if (!empty($msg)) {
                $return_data['msg'] = $msg;
            } else if (isset(ApiIndexCode::$code[$code])) {
                $return_data['msg'] = ApiIndexCode::$code[$code];
            }
            if (empty($data)) {
                unset($return_data['data']);
            }
        }
        $aes_encrypt = Config::get("system.aes_encrypt");
        if ($aes_encrypt && isset($return_data['data'])) {
            $return_data_json = json_encode($return_data['data']);
            $aes = new Aes();
            $return_encrypt_data = $aes->encrypt($return_data_json);
            $return_data['data'] = $return_encrypt_data;
            $return_data['encrypt'] = 1;//加密数据
        } else {
            $return_data['encrypt'] = 0;//非加密数据
        }
        return json($return_data);
    }

    /**
     * 返回格式化数据(未加密数据)
     * @param string $code
     * @param array $data
     * @param string $msg
     * @param array $header
     * @return \think\response\Json
     */
    public static function returnUnMsg($code = '', $data = [], $msg = '', $header = [])
    {
        if (is_array($code)) {
            $obj = $code;
            $code = isset($obj['code']) ? $obj['code'] : 500;
            $data = isset($obj['data']) ? $obj['data'] : [];
            $msg = isset($obj['msg']) ? $obj['msg'] : '';
            $header = isset($obj['header']) ? $obj['header'] : [];
        }
        $return_data = [
            'code' => '500',
            'msg' => 'undefined message',
            'data' => $code == 1 ? $data : [],
            'timestamp' => time()
        ];
        foreach ($header as $name => $val) {
            if (is_null($val)) {
                header($name);
            } else {
                header($name . ':' . $val);
            }
        }
        if (isset($code)) {
            $return_data['code'] = $code;
            if (!empty($msg)) {
                $return_data['msg'] = $msg;
            } else if (isset(ApiCode::$code[$code])) {
                $return_data['msg'] = ApiCode::$code[$code];
            }
            if (empty($data)) {
                unset($return_data['data']);
            }
        }
        $return_data['encrypt'] = 0;//非加密数据
        return json($return_data);
    }
}
