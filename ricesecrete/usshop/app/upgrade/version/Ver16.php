<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * Class Ver16
 * @package app\upgrade\version
 */
class Ver16 extends VersionBase
{

    public function run()
    {
        // 支付渠道新增字段
        $sql = <<<SQL
ALTER TABLE `t_payment_channel` ADD COLUMN `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置' AFTER `withdrawal_status`;
SQL;
        $this->execute($sql);

        // 用户表新增字段
        $sql = <<<SQL
ALTER TABLE user 
ADD COLUMN is_agent TINYINT(1) NOT NULL DEFAULT 2 COMMENT '是否是代理',
ADD COLUMN language VARCHAR(10) NOT NULL DEFAULT 'en' COMMENT '语言设置',
ADD COLUMN currency VARCHAR(10) NOT NULL DEFAULT 'USD' COMMENT '货币设置';

SQL;
        $this->execute($sql);

        // 用户表新增字段
        $sql = <<<SQL
ALTER TABLE `t_user_address` 
MODIFY COLUMN `is_default` tinyint UNSIGNED NOT NULL DEFAULT 2 COMMENT '是否默认地址 1-是 2-否' AFTER `address`;
SQL;
        $this->execute($sql);

        // 虚拟币链表
        $sql = <<<SQL
CREATE TABLE `t_chain` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链代码',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链图标',
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链描述',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 2禁用 1启用',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='虚拟币链表';
SQL;
        $ret = $this->createTableSql("t_chain", $sql);
        // 用户虚拟币链表
        $sql = <<<SQL
CREATE TABLE `t_user_chain` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `chain_id` int unsigned NOT NULL DEFAULT '0' COMMENT '链id',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '地址',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户银行卡表';
SQL;
        $ret = $this->createTableSql("t_user_chain", $sql);

    }
}
