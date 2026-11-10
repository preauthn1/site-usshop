<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 商品管理
 * Class Ver13
 * @package app\upgrade\version
 */
class Ver13 extends VersionBase
{

    public function run()
    {
        //商品主表
        $sql = <<<SQL
CREATE TABLE `t_goods_product` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
  `classify` tinyint(1) DEFAULT '2' COMMENT '商品模块分类  2正常商品 3积分商品',
  `type_id` int DEFAULT '0' COMMENT '归属分类',
  `merchant_id` int DEFAULT '0' COMMENT '商家id',
  `spec` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格名称spec json',
  `express_type` tinyint(1) DEFAULT '1' COMMENT '物流类型 1快递  2到店',
  `max_price` decimal(20,2) DEFAULT '0.00' COMMENT '规格最高价格',
  `min_price` decimal(20,2) DEFAULT '0.00' COMMENT '规格最低价格',
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
  `county` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自提具体地址',
  `lng` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：2下线，1上线',
  `sort` int DEFAULT '0' COMMENT '排序',
  `hot` tinyint(1) DEFAULT '1' COMMENT '是否热门 2否 1是',
  `rec` tinyint(1) DEFAULT '1' COMMENT '是否推荐 2否 1是',
  `buy` int DEFAULT '0' COMMENT '销量',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `type_id` (`type_id`),
  KEY `title` (`title`),
  KEY `status` (`status`),
  KEY `sort` (`sort`),
  KEY `hot` (`hot`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品列表';
SQL;
        $this->createTableSql("t_goods_product", $sql);

        //商品规格表
        $sql = <<<SQL
CREATE TABLE `t_goods_spec` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `product_id` int DEFAULT '0' COMMENT '商品id',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格图片 默认是产品封面图',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格名称',
  `spec` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格',
  `store` int DEFAULT '0' COMMENT '商品库存',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '售价',
  `cost_price` decimal(20,2) DEFAULT '0.00' COMMENT '进价',
  `limit` int DEFAULT '0' COMMENT '限购数量 0表示不限',
  `buy` int DEFAULT '0' COMMENT '销量',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品规格列表';
SQL;
        $this->createTableSql("t_goods_spec", $sql);

        //商品分类表
        $sql = <<<SQL
CREATE TABLE `t_goods_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片地址',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `parent_id` int DEFAULT '0' COMMENT '父级id',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 ,2禁用',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示 1显示 2不显示',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特殊分类跳转链接',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品分类表';
SQL;
        $this->createTableSql("t_goods_type", $sql);

        //商品库存表
        $sql = <<<SQL
CREATE TABLE `t_goods_store` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `admin_id` int DEFAULT '0' COMMENT '操作员',
  `user_id` int DEFAULT '0' COMMENT '购买用户id',
  `product_id` int DEFAULT '0' COMMENT '产品id',
  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
  `store` int DEFAULT '0' COMMENT '改变前剩余库存',
  `num` int DEFAULT '0' COMMENT '变化数量',
  `change` tinyint(1) DEFAULT '1' COMMENT '变化类型 1购买  2退回 3入库 4出库',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品库存变动表';
SQL;
        $this->createTableSql("t_goods_store", $sql);

        //商品购买记录表
        $sql = <<<SQL
CREATE TABLE `t_goods_buy_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '购买用户',
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户昵称',
  `user_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户头像',
  `product_id` int DEFAULT '0' COMMENT '产品id',
  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
  `num` int DEFAULT '0' COMMENT '变动数量',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '购买费用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品购买记录';
SQL;
        $this->createTableSql("t_goods_buy_log", $sql);

        //商品说明文档
        $sql = <<<SQL
CREATE TABLE `t_goods_explain` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `type` tinyint DEFAULT '1' COMMENT '类型 1运费说明  2退货说明  3赔付说明 4发货说明 5到店说明',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品说明文档';
SQL;
        $this->createTableSql("t_goods_explain", $sql);

        //商品订单
        $sql = <<<SQL
CREATE TABLE `t_order_goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '单价',
  `number` int DEFAULT '0' COMMENT '购买数量',
  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
  `contacts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收货地址json 数据',
  `logistics` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '物流信息json',
  `ver_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
  `status` tinyint DEFAULT '1' COMMENT '订单状态  0订单关闭 1待付款 2待发货 3待签收 4待评价 5退款中 6退款成功 7订单完成 8删除订单 9拒绝退款 10申请退货退款 11同意退款退货 12退货物流中 13退货退款完成 14拒绝退货退款',
  `pay_time` int DEFAULT '0' COMMENT '付款时间',
  `send_time` int DEFAULT '0' COMMENT '发货时间',
  `ver_time` int DEFAULT '0' COMMENT '核验时间',
  `evaluate_time` int DEFAULT '0' COMMENT '评价时间',
  `close_time` int DEFAULT '0' COMMENT '订单关闭时间',
  `refund_apply_time` int DEFAULT '0' COMMENT '申请退款时间',
  `refund_ok_time` int DEFAULT '0' COMMENT '同意申请时间',
  `refund_logistics` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '退货物流信息',
  `refund_sales_time` int DEFAULT '0' COMMENT '申请退货退款时间',
  `refund_ing_time` int DEFAULT '0' COMMENT '退货中',
  `refund_time` int DEFAULT '0' COMMENT '退款时间',
  `refuse_time` int DEFAULT '0' COMMENT '拒绝退款时间',
  `user_deltime` int DEFAULT '0' COMMENT '用户删除时间',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品订单表';
SQL;
        $this->createTableSql("t_order_goods", $sql);

        //商品购物车
        $sql = <<<SQL
CREATE TABLE `t_goods_cart` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `product_id` int DEFAULT '0' COMMENT '商品id',
  `express_type` tinyint(1) DEFAULT '1' COMMENT '物流类型 1快递  2到店',
  `spec_id` int DEFAULT '0' COMMENT '商品规格id',
  `number` int DEFAULT '0' COMMENT '数量',
  `radio` tinyint(1) DEFAULT '0' COMMENT '是否选择  0未选择 1已选择',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品购物车表';
SQL;
        $this->createTableSql("t_goods_cart", $sql);

        //物流表
        $sql = <<<SQL
CREATE TABLE `t_goods_logistics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '物流名称',
  `code` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '物流编码',
  `status` tinyint DEFAULT '1' COMMENT '状态 1开启 2关闭',
  `sort` int DEFAULT '0' COMMENT '排序',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='物流表';
SQL;
        $ret = $this->createTableSql("t_goods_logistics", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('4', '申通快递', 'STO', '1', '0', '0', '1578278665', '1578278665');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('5', '百世快递', 'HTKY', '1', '0', '0', '1578278665', '1578278665');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('6', '中通快递', 'ZTO', '1', '0', '0', '1578278665', '1578278665');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('8', '圆通速递', 'YTO', '1', '0', '0', '1578278665', '1578278665');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('9', '顺丰速运', 'SF', '1', '0', '0', '1592565877', '1592565877');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('10', '韵达速递', 'YD', '1', '0', '0', '1592809733', '1592809733');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('11', '邮政快递包裹', 'YZPY', '1', '0', '0', '1592809745', '1592809745');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('12', 'EMS', 'EMS', '1', '0', '0', '1592809759', '1592809759');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('13', '天天快递', 'HHTT', '1', '0', '0', '1592809771', '1592809771');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('14', '京东快递', 'JD', '1', '0', '0', '1592809781', '1592809781');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('15', '优速快递', 'UC', '1', '0', '0', '1592809791', '1592809791');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('16', '德邦快递', 'DBL', '1', '0', '0', '1592809799', '1592809799');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('17', '宅急送', 'ZJS', '1', '0', '0', '1592809806', '1592809806');
INSERT INTO `t_goods_logistics` (`id`, `name`, `code`, `status`, `sort`, `delete_time`, `update_time`, `create_time`) VALUES ('18', '京东快递', 'JD', '1', '0', '0', '1606983554', '1606983554');
SQL;
            $this->execute($sql);
        }

        $pid = $this->addMenuNode('商品管理', 'goods', 'iconfont el-icon-sell', 950);
        if ($pid) {
            $this->addChildNode('商品列表', 'goodsProduct', $pid, 9);
            $this->addChildNode('商品分类', 'goodsType', $pid, 7);
            $this->addChildNode('购买记录', 'goodsBuy', $pid, 5);
            $this->addChildNode('说明文档', 'goodsExplain', $pid, 3);
            $this->addChildNode('物流管理', 'goodsLogistics', $pid, 2);
        }
        $pid = $this->addMenuNode('订单管理', 'order', 'iconfont el-icon-guide', 900);
        if ($pid) {
            $this->addChildNode('商品订单', 'orderGoods', $pid, 9);
            $this->addChildNode('积分订单', 'orderIntegral', $pid, 8);
        }
    }
}
