<?php

use Yansongda\Pay\Pay;

return [
    'alipay'        => [
        'default' => [
            // 必填-支付宝分配的 app_id
            'app_id'                  => '9021000139681594',
            // 必填-应用私钥 字符串或路径
            // 在 https://open.alipay.com/develop/manage 《应用详情->开发设置->接口加签方式》中设置
            'app_secret_cert'         => 'MIIEowIBAAKCAQEArqTR/VoMyXIsM9L6R7IV0R6SmUAbN2Fx+0qYo3nsiFyRfsWXe2q9Lfyu/34XnQ+cg/Y1J2PfHrlUOCN6LK0sXID0u4wxEH7+zysAcgDV/S3yAYCvsDZ01jYeoUEy1iD1yoBR2ItzdSHsAsbFMH/iLDY/WBGPxRIKNWKiunS9Z3jAVZbB4q7oj0zlBC5e7Xi2H2KPkhB52xAKxUJkjGHnaq75C2p46NnW2ImajoWy1IIKypROwjIktOZQOfK7lBKG2qsTl0bPlkTirfelDZXI1qJJrQe4iwlnXderBy4P65p6Yqdwp2Q/As3wcTZKM4EB6X7Um9NqUc28bp+fWVij5wIDAQABAoIBAC7EeG9OBAqWBnxCTT+R0wHTHBLp9d/Yuns+uK1eYxcJIvU92Jj2Nn7wIbTNgWlP2uwDrKxFtGQ34PO/nGIGZdDHv8HRgvoV6Ap2S4I78xH/kcURPvsDOjp696zbFs/8t5Vo04IXpe2CDf+YVwnr1Rdd14yv9z4g3Jb7OPSRen8Qh0Q69TEtd+qQwvXeRougmSQO/8+5nbr8bnvLWVB3gUCWLk5FqM4nKdalMt+hqNM4I4eGimx+yyDe1TWdeVnnUEcFS+6DDTedohQIgehlkaq4LsPwf9z6GixeWFMvRVAFeIS7OqQ2vHWwMfiVzLRykn8g4xVWNhme0x9x93NYDhECgYEA6BD2dYFNMCv65vqCSn2PinlUbUs3U5LB2neimk9hxRW65MHwSl0bsAM9vQihLQxI+dOmVvwsBzGQirV/Xwa1TxuiZElXUst56qHHxPS1PDpvtBWn00ptzaMQjXKITk5Vx/XQ+YIqzihswf6IZIi48SwjlVMTZ8y2tkYj6FM2fPMCgYEAwKfI270JP9dklTyBCt6b0dAfLFw9sLuCpqD55EOU9CX97U3iDdDMLzV3mpYTi0h8aumJq0s+KeM6182FYHh3S+aDJHgbQIneZzRhRHiTJnfv0CQtktgUITwn7ujR3B1M+XXbK+oRqsk3Q4AD2n1tQ0XmFQmKbQG09cmnDdrvKj0CgYEArDWSPuaB2iJ44Ri6wP5mG3Mn5KbxWpJISo+G2Y3Y1sDmXMVSREzNtBLjqLiTqnM4YDPgdyJQpdHxGg3PhDvdJeQy4MrQAz+9rBD7xrt1FkmjBPchABl2XCgKq3x3i+xzmZxfxBgFHdE/y8HPgxM2crm3eiMY/mkamS55jPjbMWMCgYB4MjYmojH7Gq8uTKlV1rzwkiBg1MvIT7SbsSM3PtLX4Dwkqcq3Q+WgLoOIdsmKxJabPUkfDbqi95nmudrU7VPAjviS8DxQb7PKP+vPhEqKXZnJCklErHsioR1o+Oi1o7I6kUVEiKytGQzZmCzPTCG3Lwz1YxZyG/aCwmTvSACfIQKBgEzVydYL7DLAe2dhO7IjvQKB8oBBcRXBgTSQneP2x1RDWg3C/vta6pSgD8i4hn68JBXykpYTwrISAnfJksBkgoN2NanGsCpKv6zIvIj/4LCud5XKYSlw8cviPrtGh4RAGj5W1I9GmPZ+d6cgGEvTf0rUnWbD1G9plc8aa86P+RgV',
            // 必填-应用公钥证书 路径
            // 设置应用私钥后，即可下载得到以下3个证书
            'app_public_cert_path'    => app_path().'/libs/Alipay/cert/appPublicCert.crt',
            // 必填-支付宝公钥证书 路径
            'alipay_public_cert_path' => app_path().'/libs/Alipay/cert/alipayPublicCert.crt',
            // 必填-支付宝根证书 路径
            'alipay_root_cert_path'   => app_path().'/libs/Alipay/cert/alipayRootCert.crt',
            'return_url'              => 'https://www.wzqqly.com/',
            'notify_url'              => 'https://api.wzqqly.com/alipay/notify',
            // 选填-第三方应用授权token
            'app_auth_token'          => '',
            // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
            'service_provider_id'     => '',
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
            'mode'                    => Pay::MODE_SANDBOX,
        ]
    ],
    'wechat' => [
        'default' => [
            // 必填-商户号，服务商模式下为服务商商户号
            // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
            'mch_id' => '',
            // 选填-v2商户私钥
            'mch_secret_key_v2' => '',
            // 必填-v3 商户秘钥
            // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
            'mch_secret_key' => '',
            // 必填-商户私钥 字符串或路径
            // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_key.pem
            'mch_secret_cert' => '',
            // 必填-商户公钥证书路径
            // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_cert.pem
            'mch_public_cert_path' => '',
            // 必填-微信回调url
            // 不能有参数，如?号，空格等，否则会无法正确回调
            'notify_url' => 'https://yansongda.cn/wechat/notify',
            // 选填-公众号 的 app_id
            // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
            'mp_app_id' => '2016082000291234',
            // 选填-小程序 的 app_id
            'mini_app_id' => '',
            // 选填-app 的 app_id
            'app_id' => '',
            // 选填-服务商模式下，子公众号 的 app_id
            'sub_mp_app_id' => '',
            // 选填-服务商模式下，子 app 的 app_id
            'sub_app_id' => '',
            // 选填-服务商模式下，子小程序 的 app_id
            'sub_mini_app_id' => '',
            // 选填-服务商模式下，子商户id
            'sub_mch_id' => '',
            // 选填-微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
            'wechat_public_cert_path' => [
                '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/Cert/wechatPublicKey.crt',
            ],
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
            'mode' => Pay::MODE_NORMAL,
        ]
    ],
    'unipay' => [
        'default' => [
            // 必填-商户号
            'mch_id' => '777290058167151',
            // 选填-商户密钥：为银联条码支付综合前置平台配置：https://up.95516.com/open/openapi?code=unionpay
            'mch_secret_key' => 'REDACTED',
            // 必填-商户公私钥
            'mch_cert_path' => __DIR__.'/Cert/unipayAppCert.pfx',
            // 必填-商户公私钥密码
            'mch_cert_password' => '000000',
            // 必填-银联公钥证书路径
            'unipay_public_cert_path' => __DIR__.'/Cert/unipayCertPublicKey.cer',
            // 必填
            'return_url' => 'https://yansongda.cn/unipay/return',
            // 必填
            'notify_url' => 'https://yansongda.cn/unipay/notify',
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'douyin' => [
        'default' => [
            // 选填-商户号
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 产品管理 --> 商户号
            'mch_id' => '73744242495132490630',
            // 必填-支付 Token，用于支付回调签名
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> Token(令牌)
            'mch_secret_token' => 'douyin_mini_token',
            // 必填-支付 SALT，用于支付签名
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> SALT
            'mch_secret_salt' => 'oDxWDBr4U7FAAQ8hnGDm29i4A6pbTMDKme4WLLvA',
            // 必填-小程序 app_id
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> 小程序appid
            'mini_app_id' => 'tt226e54d3bd581bf801',
            // 选填-抖音开放平台服务商id
            'thirdparty_id' => '',
            // 选填-抖音支付回调地址
            'notify_url' => 'https://yansongda.cn/douyin/notify',
        ],
    ],
    'jsb' => [
        'default' => [
            // 服务代码
            'svr_code' => '',
            // 必填-合作商ID
            'partner_id' => '',
            // 必填-公私钥对编号
            'public_key_code' => '00',
            // 必填-商户私钥(加密签名)
            'mch_secret_cert_path' => '',
            // 必填-商户公钥证书路径(提供江苏银行进行验证签名用)
            'mch_public_cert_path' => '',
            // 必填-江苏银行的公钥(用于解密江苏银行返回的数据)
            'jsb_public_cert_path' => '',
            //支付通知地址
            'notify_url' => '',
            // 选填-默认为正常模式。可选为： MODE_NORMAL:正式环境, MODE_SANDBOX:测试环境
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'logger' => [
        'enable' => true,
        'file' => './logs/pay.log',
        'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
        'type' => 'single', // optional, 可选 daily.
        'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
    ],
    'http' => [ // optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
];
