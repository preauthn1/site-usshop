<?php

namespace app\libs;

use think\facade\Config;

/**
 * AES加密解密
 * Class Aes
 * @package app\libs
 */
class Aes
{
    private $key;

    private $iv;

    public function __construct()
    {
        $aes_config = Config::get('system.aes');
        $this->key = $aes_config['key'];
        $this->iv = $aes_config['iv'];
    }

    /**
     * 加密字符串
     * @param $input
     * @return string
     */
    public function encrypt($input)
    {
        $data = openssl_encrypt($input, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * 解密字符串
     * @param $input
     * @return string
     */
    public function decrypt($input)
    {
        $input = base64_decode($input);
        return openssl_decrypt($input, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
    }


    /**
     * 前端数据秘钥生成
     * @param $input
     * @return string
     */
    public function webEncrypt($input)
    {
        $aes_config = Config::get('system.aes_web');
        $key = $aes_config['key'];
        $iv = $aes_config['iv'];
        $data = openssl_encrypt($input, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * 前端数据秘钥解密
     * @param $input
     * @return string
     */
    public function webDecrypt($input)
    {
        $aes_config = Config::get('system.aes_web');
        $key = $aes_config['key'];
        $iv = $aes_config['iv'];
        $input = base64_decode($input);
        return openssl_decrypt($input, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }
}