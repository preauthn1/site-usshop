<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 旅游套餐管理
 * Class Ver5
 * @package app\upgrade\version
 */
class Ver5 extends VersionBase
{

    public function run()
    {
        //旅游套餐表
        $sql = <<<SQL
CREATE TABLE `t_tourism_product` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '套餐标题',
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标签',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '旅游路线',
  `index_id` int DEFAULT '0' COMMENT '首页分类',
  `type_id` int DEFAULT '0' COMMENT '归属分类',
  `policy_id` int DEFAULT '0' COMMENT '退订政策',
  `integral_id` int DEFAULT '0' COMMENT '积分说明',
  `merchant_id` int DEFAULT '0' COMMENT '商家信息id',
  `days` int DEFAULT '0' COMMENT '天数',
  `max_days` int DEFAULT '0' COMMENT '最大出行天数',
  `min_days` int DEFAULT '0' COMMENT '最小出行天数',
  `max_people` int DEFAULT '0' COMMENT '最多出行人数',
  `min_people` int DEFAULT '0' COMMENT '最少出行人数',
  `max_price` decimal(20,2) DEFAULT '0.00' COMMENT '套餐最高价格',
  `min_price` decimal(20,2) DEFAULT '0.00' COMMENT '套餐最低价格',
  `max_kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童套餐最高价格',
  `min_kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童价格最小套餐金额',
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
  `county` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
  `lng` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `start_day` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '套餐最新出发时间',
  `end_day` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '套餐最新结束时间',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '旅游套餐详情 图集',
  `fees` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '费用说明',
  `notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '须知说明',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：2下线，1上线',
  `sort` int DEFAULT '0' COMMENT '排序',
  `hot` tinyint(1) DEFAULT '1' COMMENT '是否热门 2否 1是',
  `rec` tinyint(1) DEFAULT '1' COMMENT '是否推荐 2否 1是',
  `buy` int DEFAULT '0' COMMENT '销量',
  `poster` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '海报图片',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐表';
SQL;
        $this->createTableSql("t_tourism_product", $sql);

        //旅游套餐规格表
        $sql = <<<SQL
CREATE TABLE `t_tourism_spec` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `product_id` int DEFAULT '0' COMMENT '旅游套餐id',
  `start_day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '开始时间',
  `end_day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '结束时间',
  `days` int DEFAULT '0' COMMENT '天数',
  `days_str` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '时间说明 （5天4晚）',
  `kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童价额',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '成人价格',
  `province` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
  `region` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1上线,2下线',
  `journey` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '路线行程',
  `people` int DEFAULT '0' COMMENT '套餐人数限制',
  `buy_people` int DEFAULT '0' COMMENT '订购人数',
  `is_holiday` tinyint(1) DEFAULT '1' COMMENT '是否是节假日 1非节假日 2节假日',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐规格表';

SQL;
        $this->createTableSql("t_tourism_spec", $sql);

        //旅游分类表
        $sql = <<<SQL
CREATE TABLE `t_tourism_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片地址',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `parent_id` int DEFAULT '0' COMMENT '父级id',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 ,2禁用',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示 1显示 2不显示',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '跳转地址 （定制游地址）',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游分类表';
SQL;
        $this->createTableSql("t_tourism_type", $sql);

        //旅游产品标签库
        $sql = <<<SQL
CREATE TABLE `t_tourism_tag` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标签名称',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游产品标签库';
SQL;
        $this->createTableSql("t_tourism_tag", $sql);

        //旅游套餐购买记录
        $sql = <<<SQL
CREATE TABLE `t_tourism_buy_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '购买用户',
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户昵称',
  `user_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户头像',
  `product_id` int DEFAULT '0' COMMENT '产品id',
  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
  `num` int DEFAULT '0' COMMENT '购买数量',
  `price` decimal(20,2) DEFAULT '0.00' COMMENT '购买费用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐购买记录';
SQL;
        $this->createTableSql("t_tourism_buy_log", $sql);

        //旅游套餐解释说明文档
        $sql = <<<SQL
CREATE TABLE `t_tourism_explain` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明标题',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '类型 1退订政策 2赠送积分 3报名须知 4报名限制 5旅游装备 6退订政策',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐解释说明文档';
SQL;
        $this->createTableSql("t_tourism_explain", $sql);

        //旅行社信息表
        $sql = <<<SQL

CREATE TABLE `t_tourism_bureau` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '旅行社名称',
  `mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系电话',
  `license` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '营业执照',
  `place` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '营业场所图片',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '旅行社营业地址',
  `lng` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅行社信息表';

SQL;
        $this->createTableSql("t_tourism_bureau", $sql);

        //旅行社核验工作人员表
        $sql = <<<SQL
CREATE TABLE `t_tourism_worker` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `bureau_id` int DEFAULT '0' COMMENT '旅行社',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作人员 账号',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作人员姓名',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bureau_id` (`bureau_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅行社核验工作人员';
SQL;
        $this->createTableSql("t_tourism_worker", $sql);


        //旅行套餐banner
        $sql = <<<SQL
CREATE TABLE `t_tourism_banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `jump_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '跳转地址',
  `tourism_type` int DEFAULT '0' COMMENT '产品分类id',
  `tourism_id` int DEFAULT '0' COMMENT '产品ID',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：2下线，1上线',
  `index` tinyint(1) DEFAULT '1' COMMENT '是否在首页显示：1显示，2不显示',
  `lng` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品经度',
  `lat` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品纬度',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游套餐精选Banner表';
SQL;
        $this->createTableSql("t_tourism_banner", $sql);

        //旅行套餐首页分类表
        $sql = <<<SQL
CREATE TABLE `t_tourism_index` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片地址',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 ,2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='首页分类表';
SQL;
        $this->createTableSql("t_tourism_index", $sql);

        $sql = <<<SQL
CREATE TABLE `t_tourism_city` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `city` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '城市名称',
  `sort` int DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `city` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅行套餐筛选热门城市表';
SQL;
        $this->createTableSql("t_tourism_city", $sql);

        //旅游套餐下单联系人表
        $sql = <<<SQL
CREATE TABLE `t_tourism_contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '姓名',
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系电话',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='套餐下单联系人';
SQL;
        $this->createTableSql("t_tourism_contacts", $sql);



        //城市地区表
        $sql = <<<SQL
CREATE TABLE `t_tourism_region` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `initial` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '首字母',
  `city_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市名称',
  `city_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型  地级市  县级市',
  `province_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省份',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 ,2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='市级数据表';
SQL;
        $ret = $this->createTableSql("t_tourism_region", $sql);
        if ($ret) {
            //字典数据初始化
            $sql = <<<SQL
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('1', 'A', '阿勒泰市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('2', 'A', '安陆市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('3', 'A', '阿尔山市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('4', 'A', '阿拉山口市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('5', 'A', '安宁市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('6', 'A', '阿图什市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('7', 'A', '安顺市', '地级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('8', 'A', '安达市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('9', 'A', '安阳市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('10', 'A', '安国市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('11', 'A', '阿拉尔市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('12', 'A', '安丘市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('13', 'A', '澳门特别行政区', '特别行政区', '澳门特别行政区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('14', 'A', '阿克苏市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('15', 'A', '安庆市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('16', 'A', '安康市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('17', 'A', '鞍山市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('18', 'B', '本溪市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('19', 'B', '北京市', '直辖市', '北京市', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('20', 'B', '保山市', '地级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('21', 'B', '巴彦淖尔市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('22', 'B', '包头市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('23', 'B', '宝鸡市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('24', 'B', '毕节市', '地级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('25', 'B', '巴中市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('26', 'B', '北镇市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('27', 'B', '北票市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('28', 'B', '白银市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('29', 'B', '白山市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('30', 'B', '白城市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('31', 'B', '百色市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('32', 'B', '北海市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('33', 'B', '北安市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('34', 'B', '滨州市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('35', 'B', '蚌埠市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('36', 'B', '亳州市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('37', 'B', '彬州市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('38', 'B', '北流市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('39', 'B', '博乐市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('40', 'B', '保定市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('41', 'B', '霸州市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('42', 'B', '北屯市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('43', 'B', '泊头市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('44', 'C', '昌吉市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('45', 'C', '成都市', '地级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('46', 'C', '赤壁市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('47', 'C', '昌邑市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('48', 'C', '楚雄市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('49', 'C', '长春市', '地级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('50', 'C', '慈溪市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('51', 'C', '赤水市', '县级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('52', 'C', '昌都市', '地级市', '西藏自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('53', 'C', '常州市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('54', 'C', '长沙市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('55', 'C', '赤峰市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('56', 'C', '澄江市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('57', 'C', '崇州市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('58', 'C', '常德市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('59', 'C', '郴州市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('60', 'C', '滁州市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('61', 'C', '承德市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('62', 'C', '岑溪市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('63', 'C', '常熟市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('64', 'C', '长垣市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('65', 'C', '潮州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('66', 'C', '长治市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('67', 'C', '崇左市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('68', 'C', '重庆市', '直辖市', '重庆市', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('69', 'C', '常宁市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('70', 'C', '沧州市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('71', 'C', '长葛市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('72', 'C', '巢湖市', '县级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('73', 'C', '池州市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('74', 'D', '达州市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('75', 'D', '灯塔市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('76', 'D', '丹江口市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('77', 'D', '都江堰市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('78', 'D', '调兵山市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('79', 'D', '大石桥市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('80', 'D', '大同市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('81', 'D', '德惠市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('82', 'D', '大冶市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('83', 'D', '德阳市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('84', 'D', '东方市', '县级市', '海南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('85', 'D', '丹阳市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('86', 'D', '儋州市', '地级市', '海南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('87', 'D', '当阳市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('88', 'D', '东兴市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('89', 'D', '大安市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('90', 'D', '东宁市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('91', 'D', '敦化市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('92', 'D', '东莞市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('93', 'D', '大庆市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('94', 'D', '东港市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('95', 'D', '敦煌市', '县级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('96', 'D', '定西市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('97', 'D', '德令哈市', '县级市', '青海省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('98', 'D', '德兴市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('99', 'D', '东阳市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('100', 'D', '邓州市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('101', 'D', '东台市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('102', 'D', '登封市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('103', 'D', '德州市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('104', 'D', '丹东市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('105', 'D', '定州市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('106', 'D', '东营市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('107', 'D', '大理市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('108', 'D', '大连市', '地级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('109', 'D', '都匀市', '县级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('110', 'E', '恩施市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('111', 'E', '额尔古纳市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('112', 'E', '鄂尔多斯市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('113', 'E', '恩平市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('114', 'E', '二连浩特市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('115', 'E', '鄂州市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('116', 'E', '峨眉山市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('117', 'F', '汾阳市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('118', 'F', '阜康市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('119', 'F', '福安市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('120', 'F', '福鼎市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('121', 'F', '阜阳市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('122', 'F', '抚州市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('123', 'F', '福清市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('124', 'F', '抚远市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('125', 'F', '丰城市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('126', 'F', '凤城市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('127', 'F', '福泉市', '县级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('128', 'F', '防城港市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('129', 'F', '福州市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('130', 'F', '阜新市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('131', 'F', '佛山市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('132', 'F', '抚顺市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('133', 'F', '肥城市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('134', 'F', '扶余市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('135', 'F', '丰镇市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('136', 'F', '富锦市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('137', 'G', '高邮市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('138', 'G', '高雄市', '地级市', '台湾', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('139', 'G', '广州市', '地级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('140', 'G', '赣州市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('141', 'G', '共青城市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('142', 'G', '高安市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('143', 'G', '贵溪市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('144', 'G', '高密市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('145', 'G', '广水市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('146', 'G', '巩义市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('147', 'G', '桂林市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('148', 'G', '广德市', '县级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('149', 'G', '高平市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('150', 'G', '盖州市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('151', 'G', '高碑店市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('152', 'G', '根河市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('153', 'G', '个旧市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('154', 'G', '桂平市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('155', 'G', '格尔木市', '县级市', '青海省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('156', 'G', '固原市', '地级市', '宁夏回族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('157', 'G', '广安市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('158', 'G', '古交市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('159', 'G', '贵阳市', '地级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('160', 'G', '广汉市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('161', 'G', '公主岭市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('162', 'G', '贵港市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('163', 'G', '高州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('164', 'G', '广元市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('165', 'H', '霍林郭勒市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('166', 'H', '鹤壁市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('167', 'H', '海阳市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('168', 'H', '邯郸市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('169', 'H', '湖州市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('170', 'H', '杭州市', '地级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('171', 'H', '辉县市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('172', 'H', '鹤山市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('173', 'H', '葫芦岛市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('174', 'H', '菏泽市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('175', 'H', '黄骅市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('176', 'H', '胡杨河市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('177', 'H', '海东市', '地级市', '青海省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('178', 'H', '合作市', '县级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('179', 'H', '华亭市', '县级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('180', 'H', '华阴市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('181', 'H', '黄山市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('182', 'H', '淮北市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('183', 'H', '淮南市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('184', 'H', '韩城市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('185', 'H', '合肥市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('186', 'H', '海宁市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('187', 'H', '河津市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('188', 'H', '汉中市', '县级市', '陕西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('189', 'H', '侯马市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('190', 'H', '霍州市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('191', 'H', '怀仁市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('192', 'H', '呼和浩特市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('193', 'H', '河间市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('194', 'H', '呼伦贝尔市', '地级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('195', 'H', '华蓥市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('196', 'H', '化州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('197', 'H', '海安市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('198', 'H', '海林市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('199', 'H', '合山市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('200', 'H', '怀化市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('201', 'H', '贺州市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('202', 'H', '海伦市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('203', 'H', '洪江市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('204', 'H', '河池市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('205', 'H', '珲春市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('206', 'H', '淮安市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('207', 'H', '河源市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('208', 'H', '虎林市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('209', 'H', '和龙市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('210', 'H', '哈尔滨市', '地级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('211', 'H', '惠州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('212', 'H', '黑河市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('213', 'H', '鹤岗市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('214', 'H', '衡阳市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('215', 'H', '会理市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('216', 'H', '海口市', '地级市', '海南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('217', 'H', '黄石市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('218', 'H', '哈密市', '地级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('219', 'H', '黄冈市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('220', 'H', '海城市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('221', 'H', '汉川市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('222', 'H', '霍尔果斯市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('223', 'H', '和田市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('224', 'H', '洪湖市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('225', 'H', '衡水市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('226', 'H', '桦甸市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('227', 'J', '基隆市', '县级市', '台湾', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('228', 'J', '嘉峪关市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('229', 'J', '建瓯市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('230', 'J', '金昌市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('231', 'J', '荆门市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('232', 'J', '简阳市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('233', 'J', '揭阳市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('234', 'J', '晋江市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('235', 'J', '江门市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('236', 'J', '酒泉市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('237', 'J', '江油市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('238', 'J', '九江市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('239', 'J', '荆州市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('240', 'J', '焦作市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('241', 'J', '靖西市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('242', 'J', '景德镇市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('243', 'J', '吉首市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('244', 'J', '京山市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('245', 'J', '吉安市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('246', 'J', '津市市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('247', 'J', '界首市', '县级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('248', 'J', '济源市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('249', 'J', '景洪市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('250', 'J', '济南市', '地级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('251', 'J', '济宁市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('252', 'J', '胶州市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('253', 'J', '井冈山市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('254', 'J', '嘉义市', '县级市', '台湾', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('255', 'J', '建德市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('256', 'J', '嘉兴市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('257', 'J', '晋中市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('258', 'J', '晋城市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('259', 'J', '蛟河市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('260', 'J', '锦州市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('261', 'J', '江阴市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('262', 'J', '鸡西市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('263', 'J', '句容市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('264', 'J', '佳木斯市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('265', 'J', '江山市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('266', 'J', '晋州市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('267', 'J', '金华市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('268', 'J', '集安市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('269', 'J', '吉林市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('270', 'J', '靖江市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('271', 'J', '介休市', '县级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('272', 'K', '康定市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('273', 'K', '开原市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('274', 'K', '凯里市', '县级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('275', 'K', '昆明市', '地级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('276', 'K', '开平市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('277', 'K', '开远市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('278', 'K', '开封市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('279', 'K', '库尔勒市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('280', 'K', '克拉玛依市', '地级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('281', 'K', '喀什市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('282', 'K', '库车市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('283', 'K', '昆玉市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('284', 'K', '可克达拉市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('285', 'K', '奎屯市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('286', 'K', '昆山市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('287', 'L', '灵宝市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('288', 'L', '龙井市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('289', 'L', '临湘市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('290', 'L', '来宾市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('291', 'L', '临江市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('292', 'L', '雷州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('293', 'L', '乐陵市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('294', 'L', '临清市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('295', 'L', '莱西市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('296', 'L', '丽水市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('297', 'L', '柳州市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('298', 'L', '廉江市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('299', 'L', '浏阳市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('300', 'L', '乐昌市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('301', 'L', '醴陵市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('302', 'L', '洛阳市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('303', 'L', '冷水江市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('304', 'L', '涟源市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('305', 'L', '耒阳市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('306', 'L', '娄底市', '地级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('307', 'L', '陆丰市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('308', 'L', '龙口市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('309', 'L', '连云港市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('310', 'L', '罗定市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('311', 'L', '利川市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('312', 'L', '老河口市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('313', 'L', '溧阳市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('314', 'L', '莱州市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('315', 'L', '莱阳市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('316', 'L', '连州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('317', 'L', '泸州市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('318', 'L', '荔浦市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('319', 'L', '兰州市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('320', 'L', '龙港市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('321', 'L', '龙泉市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('322', 'L', '泸水市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('323', 'L', '吕梁市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('324', 'L', '龙海市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('325', 'L', '临汾市', '地级市', '山西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('326', 'L', '陇南市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('327', 'L', '丽江市', '地级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('328', 'L', '临夏市', '县级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('329', 'L', '六安市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('330', 'L', '拉萨市', '地级市', '西藏自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('331', 'L', '龙岩市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('332', 'L', '林芝市', '地级市', '西藏自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('333', 'L', '灵武市', '县级市', '宁夏回族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('334', 'L', '廊坊市', '地级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('335', 'L', '临沧市', '地级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('336', 'L', '林州市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('337', 'L', '辽阳市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('338', 'L', '临海市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('339', 'L', '聊城市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('340', 'L', '凌海市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('341', 'L', '兰溪市', '县级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('342', 'L', '隆昌市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('343', 'L', '凌源市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('344', 'L', '阆中市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('345', 'L', '乐山市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('346', 'L', '临沂市', '县级市', '山东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('347', 'L', '辽源市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('348', 'L', '六盘水市', '地级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('349', 'L', '乐平市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('350', 'L', '庐山市', '县级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('351', 'M', '绵竹市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('352', 'M', '梅河口市', '县级市', '吉林省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('353', 'M', '麻城市', '县级市', '湖北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('354', 'M', '茫崖市', '县级市', '青海省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('355', 'M', '眉山市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('356', 'M', '汨罗市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('357', 'M', '漠河市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('358', 'M', '穆棱市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('359', 'M', '牡丹江市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('360', 'M', '马尔康市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('361', 'M', '弥勒市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('362', 'M', '蒙自市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('363', 'M', '密山市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('364', 'M', '梅州市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('365', 'M', '茂名市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('366', 'M', '绵阳市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('367', 'M', '芒市', '县级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('368', 'M', '满洲里市', '县级市', '内蒙古自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('369', 'M', '明光市', '县级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('370', 'M', '孟州市', '县级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('371', 'M', '马鞍山市', '地级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('372', 'N', '南雄市', '县级市', '广东省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('373', 'N', '南充市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('374', 'N', '宁德市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('375', 'N', '宁波市', '地级市', '浙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('376', 'N', '那曲市', '地级市', '西藏自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('377', 'N', '宁安市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('378', 'N', '宁乡市', '县级市', '湖南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('379', 'N', '南安市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('380', 'N', '南京市', '地级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('381', 'N', '南宫市', '县级市', '河北省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('382', 'N', '南昌市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('383', 'N', '南通市', '县级市', '江苏省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('384', 'N', '南平市', '县级市', '福建省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('385', 'N', '嫩江市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('386', 'N', '南宁市', '地级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('387', 'N', '宁国市', '县级市', '安徽省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('388', 'N', '讷河市', '县级市', '黑龙江省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('389', 'N', '南阳市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('390', 'N', '内江市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('391', 'P', '彭州市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('392', 'P', '萍乡市', '地级市', '江西省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('393', 'P', '盘锦市', '县级市', '辽宁省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('394', 'P', '攀枝花市', '县级市', '四川省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('395', 'P', '普洱市', '地级市', '云南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('396', 'P', '濮阳市', '地级市', '河南省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('397', 'P', '盘州市', '县级市', '贵州省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('398', 'P', '平凉市', '地级市', '甘肃省', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('399', 'P', '平果市', '县级市', '广西壮族自治区', '1', '0', '1725946031', '1725946031');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('400', 'P', '平顶山市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('401', 'P', '平度市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('402', 'P', '邳州市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('403', 'P', '普宁市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('404', 'P', '蓬莱市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('405', 'P', '平湖市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('406', 'P', '莆田市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('407', 'P', '凭祥市', '县级市', '广西壮族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('408', 'P', '磐石市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('409', 'P', '平泉市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('410', 'Q', '邛崃市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('411', 'Q', '清远市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('412', 'Q', '青州市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('413', 'Q', '齐齐哈尔市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('414', 'Q', '钦州市', '地级市', '广西壮族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('415', 'Q', '青岛市', '地级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('416', 'Q', '七台河市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('417', 'Q', '衢州市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('418', 'Q', '曲阜市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('419', 'Q', '琼海市', '县级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('420', 'Q', '清镇市', '县级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('421', 'Q', '沁阳市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('422', 'Q', '潜江市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('423', 'Q', '庆阳市', '地级市', '甘肃省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('424', 'Q', '曲靖市', '地级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('425', 'Q', '秦皇岛市', '地级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('426', 'Q', '潜山市', '县级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('427', 'Q', '迁安市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('428', 'Q', '启东市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('429', 'Q', '青铜峡市', '县级市', '宁夏回族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('430', 'Q', '泉州市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('431', 'R', '乳山市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('432', 'R', '荣成市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('433', 'R', '汝州市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('434', 'R', '如皋市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('435', 'R', '日喀则市', '地级市', '西藏自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('436', 'R', '瑞安市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('437', 'R', '任丘市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('438', 'R', '仁怀市', '县级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('439', 'R', '瑞金市', '县级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('440', 'R', '瑞昌市', '县级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('441', 'R', '日照市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('442', 'R', '瑞丽市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('443', 'S', '朔州市', '地级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('444', 'S', '三明市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('445', 'S', '双鸭山市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('446', 'S', '寿光市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('447', 'S', '射洪市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('448', 'S', '上饶市', '地级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('449', 'S', '石河子市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('450', 'S', '石家庄市', '地级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('451', 'S', '双河市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('452', 'S', '沈阳市', '地级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('453', 'S', '四会市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('454', 'S', '绥化市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('455', 'S', '尚志市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('456', 'S', '上海市', '直辖市', '上海市', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('457', 'S', '嵊州市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('458', 'S', '什邡市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('459', 'S', '宿州市', '地级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('460', 'S', '三亚市', '地级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('461', 'S', '遂宁市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('462', 'S', '深州市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('463', 'S', '三河市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('464', 'S', '松原市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('465', 'S', '邵武市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('466', 'S', '三沙市', '地级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('467', 'S', '石狮市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('468', 'S', '水富市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('469', 'S', '神木市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('470', 'S', '山南市', '地级市', '西藏自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('471', 'S', '石嘴山市', '地级市', '宁夏回族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('472', 'S', '沙河市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('473', 'S', '双辽市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('474', 'S', '商洛市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('475', 'S', '舒兰市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('476', 'S', '四平市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('477', 'S', '松滋市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('478', 'S', '邵东市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('479', 'S', '汕尾市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('480', 'S', '汕头市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('481', 'S', '石首市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('482', 'S', '随州市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('483', 'S', '商丘市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('484', 'S', '韶山市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('485', 'S', '宿迁市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('486', 'S', '绥芬河市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('487', 'S', '绍兴市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('488', 'S', '深圳市', '地级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('489', 'S', '十堰市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('490', 'S', '韶关市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('491', 'S', '三门峡市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('492', 'S', '苏州市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('493', 'S', '邵阳市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('494', 'T', '太原市', '地级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('495', 'T', '天津市', '直辖市', '天津市', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('496', 'T', '图木舒克市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('497', 'T', '铜陵市', '地级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('498', 'T', '泰州市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('499', 'T', '泰安市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('500', 'T', '天长市', '县级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('501', 'T', '天门市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('502', 'T', '通化市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('503', 'T', '铜仁市', '地级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('504', 'T', '塔城市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('505', 'T', '铁岭市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('506', 'T', '吐鲁番市', '地级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('507', 'T', '漯河市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('508', 'T', '洮南市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('509', 'T', '唐山市', '地级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('510', 'T', '铁门关市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('511', 'T', '台北市', '地级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('512', 'T', '天水市', '地级市', '甘肃省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('513', 'T', '滕州市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('514', 'T', '台山市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('515', 'T', '太仓市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('516', 'T', '台南市', '地级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('517', 'T', '台中市', '地级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('518', 'T', '台州市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('519', 'T', '铜川市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('520', 'T', '桃园市', '地级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('521', 'T', '桐乡市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('522', 'T', '桐城市', '县级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('523', 'T', '铁力市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('524', 'T', '图们市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('525', 'T', '腾冲市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('526', 'T', '通辽市', '地级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('527', 'T', '同江市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('528', 'T', '泰兴市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('529', 'W', '武安市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('530', 'W', '瓦房店市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('531', 'W', '万源市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('532', 'W', '武汉市', '地级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('533', 'W', '武威市', '地级市', '甘肃省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('534', 'W', '武夷山市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('535', 'W', '乌鲁木齐市', '地级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('536', 'W', '武穴市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('537', 'W', '乌海市', '地级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('538', 'W', '吴忠市', '地级市', '宁夏回族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('539', 'W', '无锡市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('540', 'W', '文山市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('541', 'W', '乌兰察布市', '地级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('542', 'W', '渭南市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('543', 'W', '芜湖市', '地级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('544', 'W', '温岭市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('545', 'W', '乌兰浩特市', '县级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('546', 'W', '万宁市', '县级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('547', 'W', '五家渠市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('548', 'W', '梧州市', '地级市', '广西壮族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('549', 'W', '五大连池市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('550', 'W', '乌苏市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('551', 'W', '吴川市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('552', 'W', '温州市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('553', 'W', '威海市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('554', 'W', '无为市', '县级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('555', 'W', '五指山市', '县级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('556', 'W', '舞钢市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('557', 'W', '五常市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('558', 'W', '武冈市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('559', 'W', '文昌市', '县级市', '海南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('560', 'W', '潍坊市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('561', 'W', '卫辉市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('562', 'X', '新余市', '地级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('563', 'X', '西宁市', '地级市', '青海省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('564', 'X', '信阳市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('565', 'X', '宣威市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('566', 'X', '荥阳市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('567', 'X', '栖霞市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('568', 'X', '香格里拉市', '县级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('569', 'X', '宣城市', '地级市', '安徽省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('570', 'X', '西安市', '地级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('571', 'X', '新北市', '地级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('572', 'X', '信宜市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('573', 'X', '咸阳市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('574', 'X', '孝义市', '县级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('575', 'X', '香港特别行政区', '特别行政区', '香港特别行政区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('576', 'X', '新泰市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('577', 'X', '兴平市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('578', 'X', '新竹市', '县级市', '台湾', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('579', 'X', '兴宁市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('580', 'X', '新密市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('581', 'X', '新乐市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('582', 'X', '兴化市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('583', 'X', '新乡市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('584', 'X', '忻州市', '地级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('585', 'X', '辛集市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('586', 'X', '徐州市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('587', 'X', '湘潭市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('588', 'X', '兴城市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('589', 'X', '新民市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('590', 'X', '厦门市', '地级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('591', 'X', '西昌市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('592', 'X', '许昌市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('593', 'X', '项城市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('594', 'X', '孝感市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('595', 'X', '新沂市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('596', 'X', '襄阳市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('597', 'X', '锡林浩特市', '县级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('598', 'X', '湘乡市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('599', 'X', '咸宁市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('600', 'X', '新郑市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('601', 'X', '仙桃市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('602', 'X', '兴仁市', '县级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('603', 'X', '兴义市', '县级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('604', 'X', '邢台市', '地级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('605', 'Y', '永安市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('606', 'Y', '仪征市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('607', 'Y', '应城市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('608', 'Y', '玉树市', '县级市', '青海省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('609', 'Y', '偃师市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('610', 'Y', '扬中市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('611', 'Y', '玉门市', '县级市', '甘肃省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('612', 'Y', '禹州市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('613', 'Y', '永城市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('614', 'Y', '阳泉市', '地级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('615', 'Y', '伊宁市', '县级市', '新疆维吾尔自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('616', 'Y', '银川市', '地级市', '宁夏回族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('617', 'Y', '义马市', '县级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('618', 'Y', '宜兴市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('619', 'Y', '宜城市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('620', 'Y', '宜昌市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('621', 'Y', '英德市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('622', 'Y', '运城市', '地级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('623', 'Y', '余姚市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('624', 'Y', '宜宾市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('625', 'Y', '延吉市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('626', 'Y', '扬州市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('627', 'Y', '宜春市', '地级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('628', 'Y', '盐城市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('629', 'Y', '禹城市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('630', 'Y', '营口市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('631', 'Y', '益阳市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('632', 'Y', '乐清市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('633', 'Y', '岳阳市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('634', 'Y', '义乌市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('635', 'Y', '永康市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('636', 'Y', '榆树市', '县级市', '吉林省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('637', 'Y', '永州市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('638', 'Y', '沅江市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('639', 'Y', '雅安市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('640', 'Y', '玉溪市', '地级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('641', 'Y', '烟台市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('642', 'Y', '伊春市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('643', 'Y', '延安市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('644', 'Y', '阳江市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('645', 'Y', '云浮市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('646', 'Y', '永济市', '县级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('647', 'Y', '阳春市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('648', 'Y', '原平市', '县级市', '山西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('649', 'Y', '榆林市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('650', 'Y', '宜都市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('651', 'Y', '鹰潭市', '地级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('652', 'Y', '玉林市', '地级市', '广西壮族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('653', 'Y', '牙克石市', '县级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('654', 'Y', '玉环市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('655', 'Z', '中山市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('656', 'Z', '自贡市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('657', 'Z', '湛江市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('658', 'Z', '舟山市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('659', 'Z', '枣庄市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('660', 'Z', '珠海市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('661', 'Z', '张家口市', '地级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('662', 'Z', '肇庆市', '县级市', '广东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('663', 'Z', '资兴市', '县级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('664', 'Z', '肇东市', '县级市', '黑龙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('665', 'Z', '诸城市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('666', 'Z', '招远市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('667', 'Z', '郑州市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('668', 'Z', '邹平市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('669', 'Z', '樟树市', '县级市', '江西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('670', 'Z', '资阳市', '县级市', '四川省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('671', 'Z', '昭通市', '地级市', '云南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('672', 'Z', '子长市', '县级市', '陕西省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('673', 'Z', '周口市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('674', 'Z', '张掖市', '地级市', '甘肃省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('675', 'Z', '漳平市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('676', 'Z', '驻马店市', '地级市', '河南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('677', 'Z', '钟祥市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('678', 'Z', '镇江市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('679', 'Z', '枣阳市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('680', 'Z', '扎兰屯市', '县级市', '内蒙古自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('681', 'Z', '枝江市', '县级市', '湖北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('682', 'Z', '涿州市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('683', 'Z', '淄博市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('684', 'Z', '漳州市', '县级市', '福建省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('685', 'Z', '张家界市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('686', 'Z', '中卫市', '地级市', '宁夏回族自治区', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('687', 'Z', '遵义市', '地级市', '贵州省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('688', 'Z', '株洲市', '地级市', '湖南省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('689', 'Z', '张家港市', '县级市', '江苏省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('690', 'Z', '朝阳市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('691', 'Z', '庄河市', '县级市', '辽宁省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('692', 'Z', '诸暨市', '县级市', '浙江省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('693', 'Z', '遵化市', '县级市', '河北省', '1', '0', '1725946032', '1725946032');
INSERT INTO `play_gain`.`t_tourism_region` (`id`, `initial`, `city_name`, `city_type`, `province_name`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('694', 'Z', '邹城市', '县级市', '山东省', '1', '0', '1725946032', '1725946032');
SQL;
            $this->execute($sql);
        }
        //旅游套餐字典表
        $sql = <<<SQL
CREATE TABLE `t_tourism_dict` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `type` int DEFAULT '0' COMMENT '归属类型 1费用  ',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 ,2禁用',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='旅游分类部分字典表';
SQL;
        $ret = $this->createTableSql("t_tourism_dict", $sql);
        if ($ret) {
            //字典数据初始化
            $sql = <<<SQL
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('1', '交通', '0', '1', '1', '0', '1722861587', '1722861587');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('2', '住宿', '0', '1', '1', '0', '1722861814', '1722861814');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('3', '餐食', '0', '1', '1', '0', '1722862038', '1722862038');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('4', '门票及景交', '0', '1', '1', '0', '1722862196', '1722862196');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('5', '随团服务人员', '0', '1', '1', '0', '1722862266', '1722862266');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('6', '优惠补充说明', '0', '1', '1', '0', '1722861587', '1722861587');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('7', '保险', '0', '1', '1', '0', '1722861814', '1722861814');
INSERT INTO `t_tourism_dict` (`id`, `name`, `sort`, `type`, `status`, `delete_time`, `update_time`, `create_time`) VALUES ('8', '其他费用', '0', '1', '1', '0', '1722862038', '1722862038');
SQL;
            $this->execute($sql);
        }
        $pid = $this->addMenuNode('旅游管理', 'tourism', 'iconfont el-icon-guide', 990);
        if ($pid) {
            $this->addChildNode('旅游套餐', 'tourismProduct', $pid, 10);
            $this->addChildNode('旅游套餐规格', 'tourismSpec', $pid, 9);
            $this->addChildNode('旅游模块', 'tourismType', $pid, 8);
            $this->addChildNode('首页旅游分类', 'tourismIndex', $pid, 7);
            $this->addChildNode('套餐推荐轮播', 'tourismBanner', $pid, 6);
            $this->addChildNode('旅游套餐标签', 'tourismTag', $pid, 5);
            $this->addChildNode('套餐购买记录', 'tourismBuy', $pid, 4);
            $this->addChildNode('套餐说明协议', 'tourismExplain', $pid, 3);
            $this->addChildNode('旅行商家信息', 'tourismBureau', $pid, 2);
            $this->addChildNode('热门城市管理', 'tourismCity', $pid, 1);
            $this->addChildNode('套餐字典管理', 'tourismDict', $pid, 1);
            $this->addChildNode('城市列表管理', 'tourismRegion', $pid, 1);
        }
    }
}
