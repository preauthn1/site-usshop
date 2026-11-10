<?php


namespace app\libs;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Psr\SimpleCache\InvalidArgumentException;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Log;

class AliyunSms
{
    /**
     * 发送验证码
     * @param $phone number 手机号
     * @param $code number 验证码
     * @param $ip string 客户端Ip
     * @return array
     * @throws ClientException
     * @throws ServerException
     * @throws InvalidArgumentException
     */
    public static function sendSms($phone, $code, string $ip = ''): array
    {
        $return_data = [
            'code' => 5004,
            'msg' => "发送失败"
        ];
        if (empty($phone) || empty($code)) {
            return $return_data;
        }
        $config = Config::get('system.aliyun_sms');
        $ip = str_replace('.', '_', $ip);
        $ip_verify = Cache::store('redis')->get($ip, 1);
        if ($ip_verify > 20) {
            $return_data['code'] = 5003;
            $return_data['msg'] = "系统检测到刷码行为";
            return $return_data;
        }
        if (!empty($ip)) {
            Cache::store('redis')->set($ip, $ip_verify + 1, 600);
        }
        AlibabaCloud::accessKeyClient($config['accessKeyId'], $config['accessKeySecret'])
            ->regionId($config['regionId'])
            ->asDefaultClient();
        $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => [
                    'RegionId' => $config['regionId'],
                    'PhoneNumbers' => $phone,
                    'SignName' => $config['SignName'],
                    'TemplateCode' => $config['TemplateCode'],
                    'TemplateParam' => json_encode(['code' => $code])
                ],
            ])
            ->request();
        if ($result['Message'] == 'OK' && $result['Code'] == 'OK') {
            Cache::store('redis')->set($phone, $code, 600);
            Cache::store('redis')->set($phone . "_count", 1, 600);
            $return_data['code'] = 1;
            $return_data['msg'] = "发送成功";
            return $return_data;
        } elseif ($result['Code'] === 'isv.MOBILE_NUMBER_ILLEGAL') {
            $return_data['code'] = 5005;
            $return_data['msg'] = $result['Message'];
        } else {
            // 记录错误日志
            Log::channel('sms')->error($phone . "验证码发送失败，原因：" . $result['Message']);

        }
        return $return_data;
    }

    /**
     * 验证短信验证码
     * @param $phone
     * @param $code
     * @return array
     * @throws InvalidArgumentException
     */
    public static function verifySms($phone, $code): array
    {
        $return_data = [
            'code' => 5001,
            'msg' => "验证码不正确"
        ];
        $redis_config = Config::get('cache.stores');
        $count = Cache::store('redis')->get($phone . "_count");
        if ($count >= 5) {
            $return_data['code'] = 5002;
            $return_data['msg'] = "验证次数过多，请重新获取验证码";
            return $return_data;
        }
        $verify_code = Cache::store('redis')->get($phone);
        if ($verify_code == $code) {
            $return_data['code'] = 1;
            $return_data['msg'] = "验证成功";
            Cache::store('redis')->delete($phone);
            Cache::store('redis')->delete($phone . "_count");
            return $return_data;
        } else {
            Cache::store('redis')->set($phone . "_count", $count + 1, 600);
            $return_data['code'] = 5001;
            $return_data['msg'] = "验证码不正确";
            return $return_data;
        }
    }

    /**
     * 发送通知内容消息
     * @param $phone
     * @param $template
     * TemplateCode 模板ID
     * TemplateParam 模板参数
     * @return array
     * @throws ClientException
     * @throws ServerException
     */
    public static function sendSmsMessage($phone, $template)
    {
        $return_data = [
            'code' => 5004,
            'msg' => "发送失败"
        ];
        if (empty($phone) || empty($template)) {
            return $return_data;
        }
        $config = Config::get('system.aliyun_sms');
        AlibabaCloud::accessKeyClient($config['accessKeyId'], $config['accessKeySecret'])
            ->regionId($config['regionId'])
            ->asDefaultClient();
        $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => [
                    'RegionId' => $config['regionId'],
                    'PhoneNumbers' => $phone,
                    'SignName' => $config['SignName'],
                    'TemplateCode' => $template['TemplateCode'],
                    'TemplateParam' => json_encode($template['TemplateParam'])
                ],
            ])
            ->request();
        if ($result['Message'] == 'OK' && $result['Code'] == 'OK') {
            $return_data['code'] = 1;
            $return_data['msg'] = "发送成功";
            return $return_data;
        } elseif ($result['Code'] === 'isv.MOBILE_NUMBER_ILLEGAL') {
            $return_data['code'] = 5005;
            $return_data['msg'] = $result['Message'];
        } else {
            // 记录错误日志
            Log::channel('sms')->error($phone . "验证码发送失败，原因：" . $result['Message']);
        }
        return $return_data;
    }
}
