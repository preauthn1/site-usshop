<?php

use think\facade\Route;

//【通用】验证码图片
Route::get('app_admin/common/captcha', 'admin.Common/getCaptcha');
//【账号】登录
Route::post('app_admin/account/login', 'admin.AuthUser/onLogin');
//【通用】获取系统配置参数
Route::get('app_admin/common/config', 'admin.Common/getConfig');
//【更新语言条目】
Route::rule('app_admin/common/updateLang', 'admin.Common/setUpdateLang');

Route::group('app_admin/', function () {
    //【文件】删除url文件
    Route::rule('common/delfile', 'Common/onDelfile');
    //【文件】上传文件
    Route::rule('common/upload', 'Common/onUpload');
    // S3文件上传相关接口
    Route::get('common/s3url', 'Common/getS3PreSignedUrl');
    //    Route::get('common/s3file', 'Common/s3File');
    //【账号】退出登录
    Route::post('account/logout', 'AuthUser/onLogout');
    //【账号】修改密码
    Route::post('account/editpwd', 'AuthUser/onEditpwd');
    //【账号】刷新token
    Route::post('account/token', 'AuthUser/onToken');
    //【账号】用户信息
    Route::get('account/profile', 'AuthUser/getProfile');

    //【缓存】重新生成
    Route::post('caches/reset', 'Caches/onReset');

    //【工作台】
    Route::get('dashboard/statistics', 'Dashboard/getStatistics');

    //【权限-菜单规则】
    Route::get('auth/rule/tree', 'AuthRule/getTree');
    Route::get('auth/rule/info', 'AuthRule/getInfo');
    Route::post('auth/rule/save', 'AuthRule/onSave');
    Route::post('auth/rule/delete', 'AuthRule/onDelete');

    //【权限-角色分组】
    Route::get('auth/group/page', 'AuthGroup/getPage');
    Route::get('auth/group/info', 'AuthGroup/getInfo');
    Route::get('auth/group/all', 'AuthGroup/getAll');
    Route::post('auth/group/save', 'AuthGroup/onSave');
    Route::post('auth/group/delete', 'AuthGroup/onDelete');
    Route::post('auth/group/status', 'AuthGroup/onStatus');

    //【权限-用户管理】
    Route::get('auth/user/page', 'AuthUser/getPage');
    Route::get('auth/user/info', 'AuthUser/getInfo');
    Route::post('auth/user/save', 'AuthUser/onSave');
    Route::post('auth/user/delete', 'AuthUser/onDelete');
    Route::post('auth/user/status', 'AuthUser/onStatus');
    Route::post('auth/user/delcache', 'AuthUser/onDelCache');

    //【系统参数配置】
    Route::get('sys/config/page', 'SysConfig/getPage');
    Route::get('sys/config/info', 'SysConfig/getInfo');
    Route::post('sys/config/save', 'SysConfig/onSave');
    Route::post('sys/config/delete', 'SysConfig/onDelete');

    //【操作日志】
    Route::get('sysLog/page', 'SysLog/getPage');
    Route::get('sysLog/info', 'SysLog/getInfo');
    Route::post('sysLog/save', 'SysLog/onSave');
    Route::post('sysLog/delete', 'SysLog/onDelete');
    Route::post('sysLog/status', 'SysLog/onStatus');

    //【Banner管理】
    Route::get('banner/page', 'SysBanner/getPage');
    Route::get('banner/info', 'SysBanner/getInfo');
    Route::post('banner/save', 'SysBanner/onSave');
    Route::post('banner/delete', 'SysBanner/onDelete');
    Route::post('banner/status', 'SysBanner/onStatus');

    //【站内信管理】
    Route::get('notification/page', 'SysNotification/getPage');
    Route::get('notification/info', 'SysNotification/getInfo');
    Route::post('notification/save', 'SysNotification/onSave');
    Route::post('notification/delete', 'SysNotification/onDelete');
    Route::post('notification/status', 'SysNotification/onStatus');

    //【用户管理】
    Route::get('user/page', 'User/getPage');
    Route::get('user/info', 'User/getInfo');
    Route::post('user/save', 'User/onSave');
    Route::post('user/delete', 'User/onDelete');
    Route::post('user/status', 'User/onStatus');
    Route::post('user/change_top_user_id', 'User/onChangeTopUserId');

    //【代理管理】
    Route::get('proxy/page', 'Proxy/getPage');
    Route::get('proxy/subordinates', 'Proxy/onSubordinates');

    //【兴趣管理】
    Route::get('hobby/page', 'Hobby/getPage');
    Route::get('hobby/info', 'Hobby/getInfo');
    Route::post('hobby/save', 'Hobby/onSave');
    Route::post('hobby/delete', 'Hobby/onDelete');
    Route::post('hobby/status', 'Hobby/onStatus');

    //【热门搜索】
    Route::get('userSearch/page', 'UserSearch/getPage');
    Route::get('userSearch/info', 'UserSearch/getInfo');
    Route::post('userSearch/save', 'UserSearch/onSave');
    Route::post('userSearch/delete', 'UserSearch/onDelete');
    Route::post('userSearch/status', 'UserSearch/onStatus');

    //【旅游套餐管理】
    Route::get('TourismProduct/page', 'TourismProduct/getPage');
    Route::get('tourismProduct/info', 'TourismProduct/getInfo');
    Route::post('tourismProduct/status', 'TourismProduct/onStatus');
    Route::post('tourismProduct/save', 'TourismProduct/onSave');
    Route::post('tourismProduct/delete', 'TourismProduct/onDelete');

    //【旅游套餐-分类】
    Route::get('tourismType/page', 'TourismType/getTree');
    Route::get('tourismType/info', 'TourismType/getInfo');
    Route::post('tourismType/edit', 'TourismType/onEdit');
    Route::post('tourismType/save', 'TourismType/onAdd');
    Route::post('tourismType/delete', 'TourismType/onDelete');
    Route::post('tourismType/status', 'TourismType/onStatus');

    //【旅游套餐-首页分类】
    Route::get('tourismIndex/page', 'TourismIndex/getPage');
    Route::get('tourismIndex/info', 'TourismIndex/getInfo');
    Route::post('tourismIndex/status', 'TourismIndex/onStatus');
    Route::post('tourismIndex/save', 'TourismIndex/onSave');
    Route::post('tourismIndex/delete', 'TourismIndex/onDelete');

    //【旅游套餐标签】
    Route::get('tourismTag/page', 'TourismTag/getPage');
    Route::get('tourismTag/info', 'TourismTag/getInfo');
    Route::post('tourismTag/status', 'TourismTag/onStatus');
    Route::post('tourismTag/save', 'TourismTag/onSave');
    Route::post('tourismTag/delete', 'TourismTag/onDelete');

    //【旅游套餐字典管理】
    Route::get('tourismDict/page', 'TourismDict/getPage');
    Route::get('tourismDict/info', 'TourismDict/getInfo');
    Route::post('tourismDict/status', 'TourismDict/onStatus');
    Route::post('tourismDict/save', 'TourismDict/onSave');
    Route::post('tourismDict/delete', 'TourismDict/onDelete');

    //【旅游套餐购买记录】
    Route::get('tourismBuy/page', 'TourismBuy/getPage');
    Route::get('tourismBuy/info', 'TourismBuy/getInfo');
    Route::post('tourismBuy/status', 'TourismBuy/onStatus');
    Route::post('tourismBuy/save', 'TourismBuy/onSave');
    Route::post('tourismBuy/delete', 'TourismBuy/onDelete');

    //【旅游套餐文档说明】
    Route::get('tourismExplain/page', 'TourismExplain/getPage');
    Route::get('tourismExplain/info', 'TourismExplain/getInfo');
    Route::post('tourismExplain/status', 'TourismExplain/onStatus');
    Route::post('tourismExplain/save', 'TourismExplain/onSave');
    Route::post('tourismExplain/delete', 'TourismExplain/onDelete');

    //【旅游套餐规格管理】
    Route::get('tourismSpec/page', 'TourismSpec/getPage');
    Route::get('tourismSpec/info', 'TourismSpec/getInfo');
    Route::post('tourismSpec/status', 'TourismSpec/onStatus');
    Route::post('tourismSpec/save', 'TourismSpec/onSave');
    Route::post('tourismSpec/delete', 'TourismSpec/onDelete');

    //【旅行社管理】
    Route::get('tourismBureau/page', 'TourismBureau/getPage');
    Route::get('tourismBureau/info', 'TourismBureau/getInfo');
    Route::post('tourismBureau/status', 'TourismBureau/onStatus');
    Route::post('tourismBureau/save', 'TourismBureau/onSave');
    Route::post('tourismBureau/delete', 'TourismBureau/onDelete');

    //【热门城市管理】
    Route::get('tourismCity/page', 'TourismCity/getPage');
    Route::get('tourismCity/info', 'TourismCity/getInfo');
    Route::post('tourismCity/status', 'TourismCity/onStatus');
    Route::post('tourismCity/save', 'TourismCity/onSave');
    Route::post('tourismCity/delete', 'TourismCity/onDelete');

    //【城市列表管理】
    Route::get('tourismRegion/page', 'TourismRegion/getPage');
    Route::get('tourismRegion/info', 'TourismRegion/getInfo');
    Route::post('tourismRegion/status', 'TourismRegion/onStatus');
    Route::post('tourismRegion/save', 'TourismRegion/onSave');
    Route::post('tourismRegion/delete', 'TourismRegion/onDelete');

    //【旅游推荐轮播管理】
    Route::get('tourismBanner/page', 'TourismBanner/getPage');
    Route::get('tourismBanner/info', 'TourismBanner/getInfo');
    Route::post('tourismBanner/status', 'TourismBanner/onStatus');
    Route::post('tourismBanner/save', 'TourismBanner/onSave');
    Route::post('tourismBanner/delete', 'TourismBanner/onDelete');

    //【商品管理】
    Route::get('goodsProduct/page', 'GoodsProduct/getPage');
    Route::get('goodsProduct/info', 'GoodsProduct/getInfo');
    Route::post('goodsProduct/status', 'GoodsProduct/onStatus');
    Route::post('goodsProduct/save', 'GoodsProduct/onSave');
    Route::post('goodsProduct/delete', 'GoodsProduct/onDelete');

    //【商品-分类】
    Route::get('goodsType/page', 'GoodsType/getTree');
    Route::get('goodsType/info', 'GoodsType/getInfo');
    Route::post('goodsType/edit', 'GoodsType/onEdit');
    Route::post('goodsType/save', 'GoodsType/onAdd');
    Route::post('goodsType/delete', 'GoodsType/onDelete');
    Route::post('goodsType/status', 'GoodsType/onStatus');

    //【商品购买记录】
    Route::get('goodsBuy/page', 'GoodsBuy/getPage');
    Route::get('goodsBuy/info', 'GoodsBuy/getInfo');
    Route::post('goodsBuy/status', 'GoodsBuy/onStatus');
    Route::post('goodsBuy/save', 'GoodsBuy/onSave');
    Route::post('goodsBuy/delete', 'GoodsBuy/onDelete');

    //【商品文档说明】
    Route::get('goodsExplain/page', 'GoodsExplain/getPage');
    Route::get('goodsExplain/info', 'GoodsExplain/getInfo');
    Route::post('goodsExplain/status', 'GoodsExplain/onStatus');
    Route::post('goodsExplain/save', 'GoodsExplain/onSave');
    Route::post('goodsExplain/delete', 'GoodsExplain/onDelete');

    //【商品物流公司管理】
    Route::get('goodsLogistics/page', 'GoodsLogistics/getPage');
    Route::get('goodsLogistics/info', 'GoodsLogistics/getInfo');
    Route::post('goodsLogistics/status', 'GoodsLogistics/onStatus');
    Route::post('goodsLogistics/save', 'GoodsLogistics/onSave');
    Route::post('goodsLogistics/delete', 'GoodsLogistics/onDelete');

    //【旅游套餐订单管理】
    Route::get('orderGoods/page', 'OrderGoods/getPage');
    Route::get('orderGoods/info', 'OrderGoods/getInfo');
    Route::post('orderGoods/status', 'OrderGoods/onStatus');
    Route::post('orderGoods/save', 'OrderGoods/onSave');
    Route::post('orderGoods/delete', 'OrderGoods/onDelete');

    //【支付记录】
    Route::get('orderPay/page', 'OrderPay/getPage');
    Route::get('orderPay/info', 'OrderPay/getInfo');
    Route::post('orderPay/status', 'OrderPay/onStatus');
    Route::post('orderPay/save', 'OrderPay/onSave');
    Route::post('orderPay/delete', 'OrderPay/onDelete');

    //【退订规则】
    Route::get('orderDamages/page', 'OrderDamages/getPage');
    Route::get('orderDamages/info', 'OrderDamages/getInfo');
    Route::post('orderDamages/status', 'OrderDamages/onStatus');
    Route::post('orderDamages/save', 'OrderDamages/onSave');
    Route::post('orderDamages/delete', 'OrderDamages/onDelete');

    //【定制套餐管理】
    Route::get('orderCustom/page', 'OrderCustom/getPage');
    Route::get('orderCustom/info', 'OrderCustom/getInfo');
    Route::post('orderCustom/status', 'OrderCustom/onStatus');
    Route::post('orderCustom/save', 'OrderCustom/onSave');
    Route::post('orderCustom/delete', 'OrderCustom/onDelete');

    //【订单评论】
    Route::get('orderComment/page', 'OrderComment/getPage');
    Route::get('orderComment/info', 'OrderComment/getInfo');
    Route::post('orderComment/status', 'OrderComment/onStatus');
    Route::post('orderComment/save', 'OrderComment/onSave');
    Route::post('orderComment/delete', 'OrderComment/onDelete');

    //【客服管理】
    Route::get('customerService/page', 'CustomerService/getPage');
    Route::get('customerService/info', 'CustomerService/getInfo');
    Route::post('customerService/save', 'CustomerService/onSave');
    Route::post('customerService/delete', 'CustomerService/onDelete');
    Route::post('customerService/status', 'CustomerService/onStatus');

    //【APP版本管理】
    Route::get('appVersion/page', 'AppVersion/getPage');
    Route::get('appVersion/info', 'AppVersion/getInfo');
    Route::post('appVersion/save', 'AppVersion/onSave');
    Route::post('appVersion/delete', 'AppVersion/onDelete');
    Route::post('appVersion/status', 'AppVersion/onStatus');

    //【内容管理】
    Route::get('article/page', 'Article/getPage');
    Route::get('article/info', 'Article/getInfo');
    Route::post('article/save', 'Article/onSave');
    Route::post('article/delete', 'Article/onDelete');
    Route::post('article/status', 'Article/onStatus');

    //【公告管理】
    Route::get('bulletin/page', 'SysBulletin/getPage');
    Route::get('bulletin/info', 'SysBulletin/getInfo');
    Route::post('bulletin/save', 'SysBulletin/onSave');
    Route::post('bulletin/delete', 'SysBulletin/onDelete');
    Route::post('bulletin/status', 'SysBulletin/onStatus');

    //【会员等级管理】
    Route::get('vip/page', 'Vip/getPage');
    Route::get('vip/info', 'Vip/getInfo');
    Route::post('vip/save', 'Vip/onSave');
    Route::post('vip/delete', 'Vip/onDelete');
    Route::post('vip/status', 'Vip/onStatus');


    //【权益等级管理】
    Route::get('rights/page', 'Rights/getPage');
    Route::get('rights/info', 'Rights/getInfo');
    Route::post('rights/save', 'Rights/onSave');
    Route::post('rights/delete', 'Rights/onDelete');
    Route::post('rights/status', 'Rights/onStatus');

    //【支付渠道管理】
    Route::get('paymentChannel/page', 'PaymentChannel/getPage');
    Route::get('paymentChannel/info', 'PaymentChannel/getInfo');
    Route::post('paymentChannel/save', 'PaymentChannel/onSave');
    Route::post('paymentChannel/delete', 'PaymentChannel/onDelete');
    Route::post('paymentChannel/status', 'PaymentChannel/onStatus');

    //【返佣比例】
    Route::get('commissionRate/page', 'CommissionRate/getPage');
    Route::get('commissionRate/info', 'CommissionRate/getInfo');
    Route::post('commissionRate/save', 'CommissionRate/onSave');
    Route::post('commissionRate/delete', 'CommissionRate/onDelete');
    Route::post('commissionRate/status', 'CommissionRate/onStatus');

    //【提现管理】
    Route::get('withdrawal/page', 'Withdrawal/getPage');
    Route::get('withdrawal/info', 'Withdrawal/getInfo');
    Route::post('withdrawal/save', 'Withdrawal/onSave');
    Route::post('withdrawal/delete', 'Withdrawal/onDelete');
    Route::post('withdrawal/status', 'Withdrawal/onStatus');

    //【充值管理】
    Route::get('recharge/page', 'Recharge/getPage');
    Route::get('recharge/info', 'Recharge/getInfo');
    Route::post('recharge/save', 'Recharge/onSave');
    Route::post('recharge/delete', 'Recharge/onDelete');
    Route::post('recharge/status', 'Recharge/onStatus');

    //【用户余额记录】
    Route::get('moneyLog/page', 'MoneyLog/getPage');
    Route::get('moneyLog/info', 'MoneyLog/getInfo');
    Route::post('moneyLog/save', 'MoneyLog/onSave');
    Route::post('moneyLog/delete', 'MoneyLog/onDelete');
    Route::post('moneyLog/status', 'MoneyLog/onStatus');

    //【活动管理】
    Route::get('activity/page', 'Activity/getPage');
    Route::get('activity/info', 'Activity/getInfo');
    Route::post('activity/save', 'Activity/onSave');
    Route::post('activity/delete', 'Activity/onDelete');
    Route::post('activity/status', 'Activity/onStatus');

    // 语言管理路由
    Route::group('language', function () {
        Route::get('page', 'Language/getPage');
        Route::get('info', 'Language/getInfo');
        Route::post('save', 'Language/onSave');
        Route::post('delete', 'Language/onDelete');
        Route::post('status', 'Language/onStatus');
        Route::post('rtl', 'Language/onRTL');
        Route::post('is_default', 'Language/onIsDefault');
    });
    // 语言类目管理路由
    Route::group('language/item', function () {
        Route::get('page', 'LanguageItem/getPage');
        Route::post('save', 'LanguageItem/onSave');
        Route::post('delete', 'LanguageItem/onDelete');
    });
    // 语言类目翻译管理路由
    Route::group('language/item/detail', function () {
        Route::rule('file', 'LanguageItemDetail/onFile');
        Route::post('save', 'LanguageItemDetail/onSave');
    });


    // 货币管理路由
    Route::group('currency', function () {
        Route::get('page', 'Currency/getPage');
        Route::get('info', 'Currency/getInfo');
        Route::post('save', 'Currency/onSave');
        Route::post('delete', 'Currency/onDelete');
        Route::post('status', 'Currency/onStatus');
        Route::post('is_default', 'Currency/onIsDefault');
    });
})->prefix('admin.')->middleware(['token', 'auth', 'handleLog']);
