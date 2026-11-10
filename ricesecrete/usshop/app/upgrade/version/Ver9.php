<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 旅游套餐订单
 * Class Ver9
 * @package app\upgrade\version
 */
class Ver9 extends VersionBase
{

    public function run()
    {
        //订单总表
        $sql = <<<SQL
CREATE TABLE `t_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
  `type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
  `product_tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品标签',
  `merchant_id` int DEFAULT '0' COMMENT '商家id',
  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性描述',
  `freight_amount` decimal(10,2) DEFAULT '0.00' COMMENT '运费多少',
  `express_type` tinyint(1) DEFAULT '1' COMMENT '物流类型 1快递  2到店',
  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单金额',
  `pay_amount` decimal(20,2) DEFAULT '0.00' COMMENT '实际支付金额',
  `refund_amount` decimal(20,2) DEFAULT '0.00' COMMENT '退款金额',
  `refund_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '退款原因',
  `return_explain` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '退货退款说明原因 {“text”:"","images":""}',
  `is_holiday` tinyint(1) DEFAULT '1' COMMENT '是否是节假日 1非节假日 2节假日',
  `sms_payment_status` tinyint(1) DEFAULT '1' COMMENT '未支付订单发送消息提醒 1未发送  2已发送',
  `serve_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单接待人员手机号',
  `serve_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单接待人员手机号JSON',
  `status` tinyint DEFAULT '1' COMMENT '订单状态  不同类型订单不一样',
  `order_keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单关键词用于订单搜索',
  `share_user_id` int DEFAULT '0' COMMENT '分享推广用户id',
  `start_day` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '集合时间',
  `city` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '集合城市',
  `take_day` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自提时间',
  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_no` (`order_no`),
  KEY `pay_no` (`pay_no`),
  KEY `type` (`type`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='订单总表';
SQL;
        $this->createTableSql("t_order", $sql);

        //旅游套餐订单表
        $sql = <<<SQL
CREATE TABLE `t_order_tourism` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
  `product_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '套餐标签',
  `merchant_id` int DEFAULT '0' COMMENT '服务商家id',
  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
  `kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童票价',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '成人票价',
  `kid_number` int DEFAULT '0' COMMENT '儿童订购数量',
  `number` int DEFAULT '0' COMMENT '成人订购数量',
  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人信息json 数据',
  `serve_mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单接待人员手机号JSON',
  `ver_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
  `status` tinyint DEFAULT '1' COMMENT '订单状态  0订单关闭  1待确认 2待付款 3待核验 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
  `affirm_time` int DEFAULT '0' COMMENT '确认时间',
  `pay_time` int DEFAULT '0' COMMENT '付款时间',
  `ver_time` int DEFAULT '0' COMMENT '核验时间',
  `evaluate_time` int DEFAULT '0' COMMENT '评价时间',
  `close_time` int DEFAULT '0' COMMENT '订单关闭时间',
  `refund_apply_time` int DEFAULT '0' COMMENT '申请退款时间',
  `refund_time` int DEFAULT '0' COMMENT '退款时间',
  `refuse_time` int DEFAULT '0' COMMENT '拒绝退款时间',
  `user_deltime` int DEFAULT '0' COMMENT '用户删除时间',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐订单表';
SQL;
        $this->createTableSql("t_order_tourism", $sql);

        //订单支付记录
        $sql = <<<SQL
CREATE TABLE `t_order_pay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
  `refund_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '退款订单号',
  `pay_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付方式  weixin微信支付,alipay支付宝支付,unionpay银联支付,integral积分兑换',
  `order_type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `merchant_id` int DEFAULT '0' COMMENT '商家id',
  `pay_amount` decimal(20,2) DEFAULT '0.00' COMMENT '实际支付金额',
  `refund_amount` decimal(20,2) DEFAULT '0.00' COMMENT '退款金额',
  `status` tinyint DEFAULT '1' COMMENT '支付状态 0取消支付 1待支付 2支付成功 3支付失败 4退款成功',
  `pay_time` int DEFAULT '0' COMMENT '支付时间',
  `refund_time` int DEFAULT '0' COMMENT '退款时间',
  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_no` (`order_no`) USING BTREE,
  KEY `pay_no` (`pay_no`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='订单支付记录';
SQL;
        $this->createTableSql("t_order_pay", $sql);

        //订单操作记录
        $sql = <<<SQL
CREATE TABLE `t_order_handle_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT '0' COMMENT '订单id',
  `admin_id` int DEFAULT '0' COMMENT '操作员id',
  `type` tinyint DEFAULT '0' COMMENT '操作类型 0其他  1审核 2修改金额 3后台付款 4核销订单 5取消订单 6删除订单',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作内容',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='订单后台操作记录';
SQL;
        $this->createTableSql("t_order_handle_log", $sql);

        //订单操作记录
        $sql = <<<SQL
CREATE TABLE `t_order_damages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `min_day` int DEFAULT '0' COMMENT '最小天数',
  `max_day` int DEFAULT '0' COMMENT '最大天数据',
  `ratio` float(5,2) DEFAULT '0.00' COMMENT '扣除比例',
  `type` tinyint(1) DEFAULT '1' COMMENT '规则类型 1非节假日 2节假日 3平台取消赔付违约金',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1开启 2关闭',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐退款规则表';
SQL;
        $ret = $this->createTableSql("t_order_damages", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('1', '集合当日', '0', '0', '60.00', '1', '1', '0', '1723724062', '1723723888');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('2', '集合日前3日 - 1日(含)', '1', '3', '40.00', '1', '1', '0', '1723724326', '1723724326');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('3', '集合日前6日 - 4日(含)', '4', '6', '20.00', '1', '1', '0', '1723724380', '1723724380');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('4', '集合日前9日 - 7日(含)', '7', '9', '10.00', '1', '1', '0', '1723724393', '1723724393');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('5', '集合日前10日之前(含)', '10', '100', '0.00', '1', '1', '0', '1723724428', '1723724410');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('6', '集合当日', '0', '0', '65.00', '2', '1', '0', '1723724062', '1723723888');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('7', '集合日前3日 - 1日(含)', '1', '3', '45.00', '2', '1', '0', '1723724326', '1723724326');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('8', '集合日前6日 - 4日(含)', '4', '6', '25.00', '2', '1', '0', '1723724380', '1723724380');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('9', '集合日前9日 - 7日(含)', '7', '9', '15.00', '2', '1', '0', '1723724393', '1723724393');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('10', '集合日前10日之前(含)', '10', '100', '0.00', '2', '1', '0', '1723724428', '1723724410');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('11', '集合当日', '0', '0', '20.00', '3', '1', '0', '1723724062', '1723723888');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('12', '集合日前3日 - 1日(含)', '1', '3', '15.00', '3', '1', '0', '1723724326', '1723724326');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('13', '集合日前6日 - 4日(含)', '4', '6', '10.00', '3', '1', '0', '1723724380', '1723724380');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('14', '集合日前9日 - 7日(含)', '7', '9', '5.00', '3', '1', '0', '1723724393', '1723724393');
INSERT INTO `t_order_damages` (`id`, `name`, `min_day`, `max_day`, `ratio`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('15', '集合日前10日之前(含)', '10', '100', '0.00', '3', '1', '0', '1723724428', '1723724410');
SQL;
            $this->execute($sql);
        }


        //订单评价
        $sql = <<<SQL
CREATE TABLE `t_order_comment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `order_id` int DEFAULT '0' COMMENT '产品订单id',
  `product_id` int DEFAULT '0' COMMENT '套餐id',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1旅游套餐 2正常商品 3积分商品',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户昵称',
  `user_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户头像',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评价内容',
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '图集(视频） json 数组类型',
  `images_num` int DEFAULT '0' COMMENT '图数量',
  `anonymous` tinyint(1) DEFAULT '1' COMMENT '是否匿名 1非匿名  2匿名',
  `all_score` float(10,2) DEFAULT '0.00' COMMENT '体验指数 综合评分 整体评价',
  `tour_guide_score` float(10,2) DEFAULT '0.00' COMMENT '导游水平 评分 描述相符',
  `service_score` float(10,2) DEFAULT '0.00' COMMENT '服务态度 评分 外观包装',
  `experience_score` float(10,2) DEFAULT '0.00' COMMENT '游玩体验 评分 使用感受',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐评价';
SQL;
        $this->createTableSql("t_order_comment", $sql);

        //定制旅游套餐
        $sql = <<<SQL
CREATE TABLE `t_order_custom` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `enterprise` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '企业名称',
  `type` tinyint(1) DEFAULT '1' COMMENT '定制类型 1企业定制 2私人定制  ',
  `address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发地址',
  `destination` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '目的地',
  `start_day` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发时间',
  `number` int DEFAULT '0' COMMENT '人数',
  `budget` decimal(11,2) DEFAULT '0.00' COMMENT '人均预算',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：0取消 1申请中  2接待中 3接待成功 4拒绝接待',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
  `admin_id` int DEFAULT '0' COMMENT '接待管理员id',
  `cancel_time` int DEFAULT '0' COMMENT '取消时间',
  `receive_time` int DEFAULT '0' COMMENT '接待时间',
  `success_time` int DEFAULT '0' COMMENT '接待完成时间',
  `refuse_time` int DEFAULT '0' COMMENT '拒绝接待时间',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐定制表';
SQL;
        $this->createTableSql("t_order_custom", $sql);

        $pid = $this->addMenuNode('订单管理', 'order', 'iconfont el-icon-guide', 900);
        if ($pid) {
            $this->addChildNode('旅游订单', 'orderTourism', $pid, 10);
            $this->addChildNode('定制旅游订单', 'orderCustom', $pid, 7);
            $this->addChildNode('支付记录', 'orderPay', $pid, 5);
            $this->addChildNode('退订规则', 'orderDamages', $pid, 4);
            $this->addChildNode('评论管理', 'orderComment', $pid, 3);
        }
    }
}
