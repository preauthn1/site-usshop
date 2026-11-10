<?php

use think\facade\Env;

return [
    // 超级管理员用户名
    'administrator' => 'admin',

    // JWT加密key
    'jwt_key' => 'REDACTED',

    // AES加密key
    'aes' => [
        'key' => 'c922b15a7f3562a5',
        'iv' => '9512741612342671'
    ],
    // 前端AES加密key
    'aes_web' => [
        'key' => 'c900b15a7f0912a5',
        'iv' => '9012700612310671'
    ],
    //前端数据秘钥加密数据 key_vi_time()  使用 aes 加密
    'key_iv' => '',
    //接口是否加密配置
    'aes_encrypt' => false,

    // 新增用户初始默认密码
    'default_pwd' => '123456',

    // token设置
    'token_set' => [
        // 后台
        'admin' => [
            'access_expires' => 24 * 15 * 3600,
            'refresh_expires' => 24 * 15 * 3600,
            'white_path' => [
                'app_admin/account/token',
                'app_admin/account/logout',
                'app_admin/common/upload',
                'app_admin/common/s3url',
                'app_admin/common/delfile',
                'app_admin/common/config',
                'app_admin/common/setUpdateLang',
            ]
        ],
        // 前台
        'index' => [
            'access_expires' => 24 * 15 * 3600,
            'refresh_expires' => 24 * 30 * 3600,
        ]
    ],

    // 高德地图配置
    'amap' => [
        'key' => '8c9ca8efb5ea119b3e9803d6aa07963a',
    ],
    // 阿里云oss配置
    'aliyun_oss' => [
        'accessKeyId' => 'REDACTED',
        'accessKeySecret' => 'REDACTED',
        'endpoint' => 'oss-cn-chengdu.aliyuncs.com',
        'region' => 'oss-cn-chengdu',
        'bucket' => 'wzqqfiles',
        'url' => 'http://wzqqfiles.oss-cn-chengdu.aliyuncs.com'
    ],
    // 阿里云sms配置
    'aliyun_sms' => [
        'accessKeyId' => 'REDACTED',
        'accessKeySecret' => 'REDACTED',
        'regionId' => 'cn-chengdu',
        'TemplateCode' => 'SMS_302836157', //默认模板 $code
        'TemplateCodeBind' => 'SMS_471435293', //手机绑定模板 $code
        'TemplateCodeMod' => 'SMS_471335252', //手机修改模板  $code
        'TemplatePayment' => 'SMS_471425268', //未支付提醒通知
        'TemplatePaid' => 'SMS_471440286', //订单支付成功通知 $travel_name $travel_date1 $travel_date2 $travel_address $guide_name $guide_phone
        'TemplateCancel' => 'SMS_471435293', //未支付取消通知
        'TemplateVerify' => 'SMS_471425268', //订单核验通知
        'SignName' => "玩赚全球",
    ],
    //亲爱的用户，您预定的旅游套餐（${travel_name}${travel_date1}出发），订单号：${num}，出行信息如下： 出行日期：${travel_date2} 集合地点：${travel_address} 联系人：${guide_name} 联系方式：${guide_phone} 请在抵达出发地后出示核验码确认订单。 如有疑问请联系客服，感谢支持！
    'uniapp' => [
        'getPhoneNumberSecret' => 'www-wzqq-com',
        'getPhoneNumberUrl' => 'https://fc-mp-45e6b30e-d7a5-4b5e-bf76-a17b0c73e755.next.bspapp.com/getPhoneNumber',
    ],
    'work_weixin' => [
        'corpid' => 'wwe52931bf7e4d3b70', // 企业ID
        'corpsecret' => 'uF7jjyDi5A1Mv3KjqaDk7lKGl9JeSlKlAWOghrVPlMw', // 应用密钥
        'agentid' => '1000003' // 应用ID
    ],
    'aws_s3' => [
        'credentials_key' => 'REDACTED',
        'credentials_secret' => '9GREmz3Wn/vaJyDyNb9mYcSzwijbxw3e5TMNzqMs',
        'region' => 'us-east-1',
        'bucket' => 'usheen',
        'proxy' => '',
        'url' => 'https://usheen.s3.amazonaws.com'
    ],
    'proxy' => Env::get('proxy.url', ''),
];
