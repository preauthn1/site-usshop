<?php

namespace app\libs;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use think\facade\Config;

class WorkWeixin
{
    protected $config;

    public function __construct()
    {
        $this->config = Config::get('system.work_weixin');
    }

    public function getToken()
    {
        $params = [
            'corpid'     => $this->config['corpid'],
            'corpsecret' => $this->config['corpsecret'],
        ];
        $res = $this->request('https://qyapi.weixin.qq.com/cgi-bin/gettoken', $params, 'GET');
        if ($res['errcode'] === 0) {
            // 缓存token
            $cache_key = $this->config['agentid'].'_access_token';
            cache($cache_key, $res['access_token'], 7200);
            return $res['access_token'];
        } else {
            if ($res['errcode'] === 41004) { // 缺少secret参数

            }
            // 42001 access_token 过期
        }
        return null;
    }

    public function sendEmail($email, $subject, $content): bool
    {
        $params = [
            'to'      => [
                'emails' => [$email]
            ],
            'subject' => $subject,
            'content' => $content,
        ];
        $access_token = cache($this->config['agentid'].'_access_token');
        if (!$access_token) {
            $access_token = $this->getToken();
        }
        $res = $this->request('https://qyapi.weixin.qq.com/cgi-bin/exmail/app/compose_send?access_token='.$access_token,
            $params, 'POST');
        if ($res['errcode'] === 0) {
            return true;
        }
        return false;
    }

    /**
     * 发起请求
     * @param  string  $url
     * @param  array  $data
     * @param  string  $method
     * @return array
     */
    private static function request(string $url, array $data, string $method = 'post'): array
    {
        $client = new Client();
        $options = [
            'timeout' => 10,
        ];

        if ('post' === strtolower($method)) {
            $options['json'] = $data;
        } else {
            $url .= '?'.http_build_query($data);
        }

        try {
            $response = $client->request($method, $url, $options);
        } catch (GuzzleException|\Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }

        $content = $response->getBody()->getContents();
        return json_decode($content, true);
    }
}
