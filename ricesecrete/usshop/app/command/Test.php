<?php
declare (strict_types=1);

namespace app\command;

use app\libs\BookingHotel;
use app\libs\GoogleTranslate;
use app\model\goods\GoodsLangModel;
use app\model\goods\GoodsStoreModel;
use app\model\order\OrderCommentModel;
use app\model\order\OrderModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 指令
 * Class Test
 * @package app\command
 */
class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')->setDescription('the test command');
    }

    /**
     *
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function execute(Input $input, Output $output)
    {
//        //减少库存
//        $temp = BookingHotel::getSearchDestination('New York');
//        print_r($temp);
//        $temp_data = BookingHotel::getSearchHotels('20088325','CITY');
        $temp_data = BookingHotel::getSearchHotelDetails('6152075');
//        file_put_contents('test.txt', json_encode($temp_data));
        print_r($temp_data);
        exit();
//        $order_model = new OrderModel();
//        $return_explain = [
//            'text' => '退款原因',
//            'images' => ''
//        ];
//        $ret = $order_model->applyRefundGoodsOrder(65, "商品有瑕疵", $return_explain, 3);
////        $ret = $order_model->setRefundLogisticsOrder(73, 5, '8787473484', 3);
//        print_r($ret);
//        exit();

//        $order_model = new OrderModel();
////        $ret = $order_model->endPayOrder('PG09091529323799',260);
////        $ret = $order_model->_endPayOrderItem(67);
////        $ret = $order_model->changeOrderStatus(OrderModel::TYPE_NORMAL,67, 2);
//        print_r($ret);
//        exit();
//        $order_id = "65";
//        var_dump(strpos($order_id, ','));
//        print_r(strpos($order_id, ',')!=false);
//        exit();
//        $password = password_hash('123456', PASSWORD_BCRYPT);
//        print_r($password);
//        exit();
//        $arr = [1,2,3,4];
//        print_r(serialize($arr));
//        exit();
//        TourismProductModel::where('id', 1)->inc('buy', 10)->update();
//        exit();
//        GoodsCartModel::changeCart(1,1,1,1,'add');
//        exit();
//        $temp = OrderTourismModel::getOrderItemNum(55, 73);
//        $temp[] = [
//            'product_id' => 1,//测试
//            'spec_id' => 1,//套餐id
//            'number' => 1,//数量
//        ];
//        $temp[] = [
//            'product_id' => 1,//测试
//            'spec_id' => 2,//套餐id
//            'number' => 2,//成人数量
//        ];
//        print_r(json_encode($temp));
//        exit();
        // `order_id` int DEFAULT '0' COMMENT '产品订单id',
        //  `product_id` int DEFAULT '0' COMMENT '套餐id',
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户昵称',
        //  `user_head` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户头像',
        //  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评价内容',
        //  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '图集(视频） json 数组类型',
        //  `anonymous` tinyint(1) DEFAULT '1' COMMENT '是否匿名 1非匿名  2匿名',
        //  `all_score` float(10,2) DEFAULT '0.00' COMMENT '体验指数 综合评分',
        //  `tour_guide_score` float(10,2) DEFAULT '0.00' COMMENT '导游水平 评分',
        //  `service_score` float(10,2) DEFAULT '0.00' COMMENT '服务态度 评分',
        //  `experience_score` float(10,2) DEFAULT '0.00' COMMENT '游玩体验 评分',
        $up_data = [
            'order_id' => 121,
            'product_id' => 14,
            'type' => OrderModel::TYPE_INTEGRAL,
            'user_id' => 3,
            'content' => '评论旅商品信息',
            'images' => [
                'http://ykzn.oss-cn-shanghai.aliyuncs.com/head_img/2024-08-22/5de36e13e03833c9ca6ae96c448fbf5c3454c041.png',
                'http://ykzn.oss-cn-shanghai.aliyuncs.com/head_img/2024-08-22/5de36e13e03833c9ca6ae96c448fbf5c3454c041.png',
            ],
            'anonymous' => 2,
            'all_score' => 4,
            'tour_guide_score' => 4,
            'service_score' => 4,
            'experience_score' => 4,
        ];
        $temp = OrderCommentModel::addCommentLog($up_data);
        print_r($temp);
        exit();
//        exit();
        $data = [
            'type' => 1,//旅游套餐
            'product_id' => 1,//测试
            'user_id' => 2,//测试
            'spec_id' => 46,//套餐id
            'number' => 1,//成人数量
            'kid_number' => 1,//儿童数量
            'contacts' => [
                [
                    'name' => '姓名',
                    'mobile' => '18785169821',
                ]
            ],
            'remarks' => '备注'
        ];
//        $data = [
//            'type' => 2,//正常商品
//            'product_id' => 56,//测试
//            'user_id' => 3,//测试
//            'spec_id' => 3,//套餐id
//            'number' => 1,//数量
//            'take_day' => "2024-10-12",//取货时间
//            'contacts' => [
//                [
//                    'name' => '姓名',
//                    'mobile' => '19985282354',
//                    'province' => '贵州省',
//                    'city' => '贵阳市',
//                    'area' => '观山湖区',
//                    'address' => '详细地址'
//                ]
//            ],
//            'remarks' => '备注'
//        ];
        $order_model = new OrderModel();
//        $temp = $order_model->applyRefundOrder(OrderModel::TYPE_TOURISM, '29', '个人其他突发情况');
        $temp = $order_model->addOrder($data);
//        $temp = $order_model->getPayInfo('66');
//        $temp = $order_model->verOrderCode('19','652878');
//        print_r($temp);
//        $temp = $order_model->endPayOrder('', '66', '130');
        print_r($temp);
        exit();

    }
}
