<?php

use think\facade\Route;

//【文件】删除url文件
Route::rule('app_index/common/delfile', 'api.Common/onDelfile');
//【文件】上传文件
Route::rule('app_index/common/upload', 'api.Common/onUpload');
//【更新语言条目】
Route::rule('app_index/common/updateLang', 'api.Common/setUpdateLang');
//【获取配置文件】
Route::rule('app_index/common/config', 'api.Common/getConfig');

//【前端无需权限的接口】
Route::group('app_index/', function () {
    //首页
    Route::rule('index/index', 'Index/index');
    //首页banner
    Route::rule('index/banner', 'Index/onBanner');
    //首页模块分类
    Route::rule('index/type', 'Index/onType');
    //获取IP定位
    Route::rule('index/ip_location', 'Index/onIpLocation');
    //获取首页产品列表
    Route::rule('index/product_list', 'Index/onProductList');
    // 公告
    Route::get('bulletin', 'Bulletin/index');

    //获取评论列表
    Route::rule('order/comment_list', 'Order/getCommentList');

    //商品分类
    Route::rule('goods/type', 'Goods/onType');
    // 商品分类带商品
    Route::rule('goods/type_with_goods', 'Goods/onTypeWithGoods');
    //商品列表(产品搜索)
    Route::rule('goods/product_list', 'Goods/onProductList');
    //商品搜索推荐关键词
    Route::rule('goods/search_recommend', 'Goods/onSearchRecommend');
    //删除历史搜索关键词
    Route::rule('goods/del_keyword', 'Goods/onDelKeyword');
    //删除历史搜索记录
    Route::rule('goods/clear_keyword', 'Goods/onClearKeyword');
    //商品详情
    Route::rule('goods/product_details', 'Goods/onProductDetails');
    //商品获取订单分享详情
    Route::rule('goods/order_share_details', 'goods/getOrderShareDetails');

    //获取支付信息
    Route::rule('order/pay_info', 'Order/getPayInfo');

    //用户登录
    Route::rule('user/login', 'User/onLogin');
    //获取刷新token
    Route::rule('user/token', 'User/onToken');

    // 发送注册验证码
    Route::rule('send_code', 'User/sendCode');
    // 验证验证码是否正确
    Route::get('check_code', 'User/checkCode');

    // 用户注册
    Route::rule('user/register', 'User/onRegister');

    // 手机号一键登录
    Route::rule('user/phone_login', 'User/onPhoneLogin');
    // 微信登录
    Route::rule('user/social_login', 'User/onSocialLogin');

    // 检查APP升级
    Route::get('check_update', 'AppVersion/check');

    // 通过手机验证码修改密码
    Route::post('change_password_by_phone', 'User/changePasswordByPhone');
    // 通过邮箱验证码修改密码
    Route::post('change_password_by_email', 'User/changePasswordByEmail');

    // 客服
    Route::get('customer_service', 'CustomerService/index');

    // 获取账号绑定渠道
    Route::get('/user/third_auth_type', 'User/getThirdAuthType');

    // 获取内容列表
    Route::get('article/index', 'Article/index');
    // 获取内容详情
    Route::get('article/detail', 'Article/detail');

    // 活动列表
    Route::get('activity/page', 'Activity/getPage');
    // 活动详情
    Route::get('activity/detail', 'Activity/detail');

    Route::rule('language', 'Language/index');
    Route::rule('translate', 'Language/translate');
    Route::rule('currency', 'Currency/index');

    //酒店接口
    //获取酒店目的地 列表
    Route::rule('hotel/hotels_destination', 'Hotel/getHotelsDestination');
    //获取酒店列表
    Route::rule('hotel/hotels', 'Hotel/getHotels');
    //获取酒店详情信息
    Route::rule('hotel/hotels_detail', 'Hotel/getHotelDetail');

    //机票接口
    //获取机票目的地 列表
    Route::rule('flights/flights_destination', 'Flights/getFlightsDestination');
    //获取航班列表
    Route::rule('flights/flights', 'Flights/getFlights');
    //获取航班详情信息
    Route::rule('flights/flights_detail', 'Flights/getFlightsDetail');

})->prefix('api.')->middleware(['token']);

//【前端权限的接口】
Route::group('app_index/', function () {
    //获取用户基本信息
    Route::get('user/userInfo', 'User/getUserInfo');
    // 修改用户信息
    Route::post('user/userInfo', 'User/updateUserInfo');
    // 修改密码
    Route::post('change_password', 'User/changePassword');
    //收藏或者取消收藏
    Route::rule('user/collect_product', 'User/onCollectProduct');
    //获取收藏列表
    Route::rule('user/collect_list', 'User/getCollectList');
    //获取浏览记录
    Route::rule('user/view_list', 'User/getViewList');
    //删除浏览记录
    Route::rule('user/del_view', 'User/onDelView');
    //获取用户余额记录
    Route::rule('user/money_log', 'User/getMoneyLog');

    //商品获取下单信息
    Route::rule('goods/order_info', 'Goods/onOrderInfo');
    //商品创建订单
    Route::rule('goods/create_order', 'goods/onCreateOrder');
    //商品获取订单详情
    Route::rule('goods/order_details', 'goods/getOrderDetails');
    //购物车列表
    Route::rule('goods/cart_list', 'goods/onCartList');
    //添加购物车（减少）
    Route::rule('goods/add_cart', 'goods/onAddCart');
    //修改购物车
    Route::rule('goods/edit_cart', 'goods/onEditCart');
    //清空购物车
    Route::rule('goods/clear_cart', 'goods/onClearCart');

    //使用积分支付
    Route::rule('order/integral_pay', 'Order/onIntegralPay');
    //修改支付凭证
    Route::rule('order/update_pay_img', 'Order/onUpdatePayImg');
    //余额支付
    Route::rule('order/balance_pay', 'Order/onBalancePay');
    //提交评论
    Route::rule('order/submit_comment', 'Order/onSubmitComment');
    //取消订单
    Route::rule('order/cancel_order', 'Order/onCancelOrder');
    //确认签收订单
    Route::rule('order/sign_order', 'Order/onSignOrder');
    //删除订单
    Route::rule('order/delete_order', 'Order/onDeleteOrder');
    //获取物流列表
    Route::rule('order/get_logistics_list', 'Order/onGetLogisticsList');
    //申请退款
    Route::rule('order/apply_refund_order', 'Order/onApplyRefundOrder');
    //申请退货退款
    Route::rule('order/apply_refund_goods_order', 'Order/onApplyRefundGoodsOrder');
    //设置退货物流
    Route::rule('order/set_refund_logistics_order', 'Order/onSetRefundLogisticsOrder');
    //取消退款
    Route::rule('order/cancel_refund_order', 'Order/onCancelRefundOrder');
    //操作员核销订单
    Route::rule('order/ver_order_code', 'Order/onVerOrderCode');
    //获取订单列表
    Route::rule('order/order_list', 'Order/getOrderList');
    //获取核销订单
    Route::rule('order/order_ver', 'Order/getOrderVer');
    //添加定制套餐
    Route::rule('order/add_custom', 'Order/onAddCustom');
    //获取订单套餐列表
    Route::rule('order/custom_list', 'Order/getCustomList');
    //获取定制套餐详情
    Route::rule('order/custom_detail', 'Order/getCustomDetail');
    //取消定制套餐
    Route::rule('order/custom_cancel', 'Order/onCustomCancel');
    //删除定制套餐
    Route::rule('order/custom_delete', 'Order/onCustomDelete');

    //用户兴趣
    Route::get('hobby', 'Hobby/index');
    Route::get('hobbyList', 'Hobby/hobbyList');
    Route::post('hobby', 'Hobby/update');

    //用户收货地址
    Route::get('address/page', 'UserAddress/getPage');
    Route::post('address/save', 'UserAddress/onSave');
    Route::post('address/delete', 'UserAddress/onDelete');

    // 团队
    Route::get('team', 'Team/index');
    // 团队成员
    Route::get('team/user/page', 'Team/userPage');
    // 团队提现
    Route::get('team/withdrawal/page', 'Team/withdrawalPage');
    // 佣金记录列表
    Route::get('team/commission/page', 'Team/commissionPage');

    // 会员中心
    Route::get('vip', 'Vip/index');
    Route::get('rights', 'Vip/rights');

    //用户消息
    Route::get('notification/page', 'Notification/getPage');
    Route::post('notification/read', 'Notification/read');
    Route::post('notification/readAll', 'Notification/readAll');
    Route::get('notification/detail', 'Notification/detail');
    // 未读消息数量
    Route::get('notification/unread_count', 'Notification/unReadMsgCount');

    // 银行卡列表
    Route::get('bank', 'Bank/index');
    Route::get('user_bank', 'UserBank/index');
    Route::post('user_bank', 'UserBank/onSave');
    Route::post('user_bank/delete', 'UserBank/onDelete');


    // 支付渠道
    Route::get('payment_channel', 'PaymentChannel/index');

    // 提现
    Route::get('withdrawal', 'Withdrawal/index');
    Route::post('withdrawal', 'Withdrawal/onSave');

    // 充值
    Route::post('recharge', 'Recharge/onSave');

    // 积分兑换
    Route::get('integral', 'Integral/index');
    Route::get('integral/page', 'Integral/getPage');
    Route::post('integral/exchange', 'Integral/exchange');

    // 到账记录
    Route::get('income/integral', 'Income/integral');
    Route::get('income/commission', 'Income/commission');

    // 意见反馈
    Route::post('feedback', 'Feedback/onSave');

    // 发送邮箱验证码
    Route::post('send_email_code', 'User/sendEmailCode');
    // 绑定邮箱
    Route::post('bind_email', 'User/bindEmail');
    Route::post('bind_phone', 'User/bindPhone');
})->prefix('api.')->middleware(['token', 'auth']);
