<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 用户管理
 * Class Ver3
 * @package app\upgrade\version
 */
class Ver3 extends VersionBase
{

    public function run()
    {
        //创键建用户表
        $sql = <<<SQL
CREATE TABLE `t_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '密码',
  `pay_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付密码',
  `gender` int DEFAULT '1' COMMENT '性别 1男 2女',
  `nick_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户昵称',
  `head_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户头像',
  `wx_open_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '微信 open_id',
  `qq_open_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'QQ open_id',
  `alipay_open_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付宝user_id',
  `status` int DEFAULT '1' COMMENT '1正常2停用',
  `vip_level` int DEFAULT '0' COMMENT '会员等级 0没有  ',
  `popularize_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '推广码',
  `top_user_id` int DEFAULT '0' COMMENT '推广上级id',
  `top_time` int DEFAULT NULL COMMENT '推广绑定时间',
  `protocol_id` int DEFAULT '0' COMMENT '用户协议',
  `activated` tinyint unsigned NOT NULL DEFAULT '2' COMMENT '激活状态 1-已激活 2-未激活',
  `birthday` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '生日',
  `province` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省名称',
  `city` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '市名称',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户佣金',
  `integral` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户积分',
  `cid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `frozen_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现冻结佣金',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`,`phone`),
  KEY `phone` (`phone`) USING BTREE,
  KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户';
SQL;
        $this->createTableSql("t_user", $sql);

        $pid = $this->addMenuNode('会员管理', 'member', 'iconfont el-icon-coordinate', 600);
        if ($pid) {
            $this->addChildNode('用户管理', 'user', $pid, 1);
        }

        //创键建团队表
        $sql = <<<SQL
CREATE TABLE `t_user_team` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `left_key` int unsigned NOT NULL DEFAULT '0' COMMENT '左键',
  `right_key` int unsigned NOT NULL DEFAULT '0' COMMENT '右键',
  `parent_id` int unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `level` int unsigned NOT NULL DEFAULT '0' COMMENT '所在层级',
  `activated` tinyint unsigned NOT NULL DEFAULT '2' COMMENT '激活状态 1-已激活 2-未激活',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;
        $this->createTableSql("t_user_team", $sql);
    }
}
