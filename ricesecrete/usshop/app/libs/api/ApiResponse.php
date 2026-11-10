<?php

namespace app\libs\api;

/**
 * API响应数据处理类
 * Trait ApiResponse
 * @package app\libs\api
 */
trait ApiResponse
{
    /**
     * 返回格式化数据
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
            } else if (isset(ApiCode::$code[$code])) {
                $return_data['msg'] = ApiCode::$code[$code];
            }
            if (empty($data)) {
                unset($return_data['data']);
            }
        }
        return json($return_data);
    }
}
