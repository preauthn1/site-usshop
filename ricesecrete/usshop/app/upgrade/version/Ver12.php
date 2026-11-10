<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * APP版本
 * Class Ver12
 * @package app\upgrade\version
 */
class Ver12 extends VersionBase
{

    public function run()
    {
        // APP版本表
        $sql = <<<SQL
CREATE TABLE `t_app_version` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `version_code` int unsigned NOT NULL DEFAULT '0' COMMENT '版本号，用来比较',
  `version_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '版本号，用来显示',
  `bulletin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公告',
  `platform_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '平台 1-Android 2-iOS',
  `is_force_update` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否强制升级 1强制升级 2不强制升级',
  `is_wgt` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否是wgt包 1 是 2 不是',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '下载链接',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='App版本';
SQL;
        $this->createTableSql("t_app_version", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('APP版本管理', 'appVersion', $pid, 1);
        }


        // 内容管理表
        $sql = <<<SQL
CREATE TABLE `t_article` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `create_time` int NOT NULL DEFAULT '0',
  `update_time` int NOT NULL DEFAULT '0',
  `delete_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容表';
SQL;
        $this->createTableSql("t_article", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('内容管理', 'article', $pid, 1);
        }

        // 钱包记录
        $sql = <<<SQL
CREATE TABLE `t_money_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL COMMENT '用户id',
  `admin_id` int unsigned NOT NULL DEFAULT '0' COMMENT '管理员id 0-系统',
  `order_id` int unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `from_user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '返佣用户id',
  `from_user_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '返佣用户消费金额',
  `change_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '余额修改类型 -1-减少 1-增加',
  `from_type` tinyint NOT NULL COMMENT '余额修改来源 1-佣金到账 2-积分到账 3-佣金提现 4-积分转佣金',
  `balance_type` tinyint unsigned NOT NULL COMMENT '余额类型 1-佣金 2-积分',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账单标题',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '操作的金额',
  `balance_before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '操作前余额',
  `balance_after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '操作后余额',
  `create_time` int unsigned DEFAULT '0',
  `update_time` int unsigned DEFAULT '0',
  `delete_time` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='钱包记录';
SQL;
        $ret = $this->createTableSql("t_money_log", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES (7, '积分兑换佣金比例', 'integralToCommissionRate', '0.005', 1724646735, 1724646735, 0);
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES (8, '每日积分转佣金次数', 'dailyIntegralToCommissionCount', '1', 1724650632, 1724650703, 0);
SQL;
            $this->execute($sql);
        }


        // 意见反馈
        $sql = <<<SQL
CREATE TABLE `t_feedback` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint unsigned NOT NULL COMMENT '1-功能异常 2-产品建议 3-界面美观 4-其他问题',
  `content` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '反馈内容',
  `picture` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '问题截图',
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系方式',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='意见反馈';
SQL;
        $this->createTableSql("t_feedback", $sql);


        $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);

        // 支付渠道
        $sql = <<<SQL
CREATE TABLE `t_payment_channel` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道名称',
  `ident` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标识',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `min_withdrawal_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最低提现金额',
  `max_withdrawal_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最高提现金额',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-启用 2-停用',
  `pay_status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '支付状态 1-启用 2-停用',
  `withdrawal_status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '提现状态 1-启用 2-停用',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付渠道';
SQL;
        $ret = $this->createTableSql("t_payment_channel", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_payment_channel` (`id`, `name`, `ident`, `min_withdrawal_money`, `max_withdrawal_money`, `status`, `pay_status`, `withdrawal_status`, `create_time`, `update_time`, `delete_time`) VALUES (1, '微信', 'weixin', 0.00, 0.00, 2, 1, 1, 0, 1724822841, 0);
INSERT INTO `t_payment_channel` (`id`, `name`, `ident`, `min_withdrawal_money`, `max_withdrawal_money`, `status`, `pay_status`, `withdrawal_status`, `create_time`, `update_time`, `delete_time`) VALUES (2, '支付宝', 'alipay', 0.00, 0.00, 1, 1, 1, 0, 0, 0);
INSERT INTO `t_payment_channel` (`id`, `name`, `ident`, `min_withdrawal_money`, `max_withdrawal_money`, `status`, `pay_status`, `withdrawal_status`, `create_time`, `update_time`, `delete_time`) VALUES (3, '银联', 'unionpay', 0.00, 0.00, 1, 1, 1, 1725609184, 1725609184, 0);
SQL;
            $this->execute($sql);

        }


        $pid = $this->addMenuNode('业务设置', 'businessSetting', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('支付渠道', 'paymentChannel', $pid, 1);
        }

        // 提现申请表
        $sql = <<<SQL
CREATE TABLE `t_withdrawal` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `original_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '申请提现金额',
  `arrival_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '到账金额',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `exchange_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '汇率',
  `check_time` int unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提现备注',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '提现状态 1-待审核 2-审核成功 3-审核失败 4-支付成功 5-支付失败',
  `true_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '银行名称',
  `card_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '行号',
  `opening_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '开户行',
  `channel_id` int unsigned NOT NULL DEFAULT '0' COMMENT '渠道id',
  `channel_indent` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道标识',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '三方订单号',
  `pay_time` int unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现申请表';
SQL;
        $this->createTableSql("t_withdrawal", $sql);


        $pid = $this->addMenuNode('订单管理', 'order', 'iconfont el-icon-guide', 900);
        if ($pid) {
            $this->addChildNode('提现订单', 'withdrawal', $pid, 1);
        }


        // 用户银行卡表
        $sql = <<<SQL
CREATE TABLE `t_user_bank` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `bank_id` int unsigned NOT NULL DEFAULT '0' COMMENT '银行id',
  `bank_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '行号',
  `opening_bank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '开户行',
  `card_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '银行卡号',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户银行卡表';
SQL;
        $this->createTableSql("t_user_bank", $sql);

        // 银行表
        $sql = <<<SQL
CREATE TABLE `t_bank` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-启用 2-禁用',
  `create_time` int DEFAULT '0',
  `update_time` int DEFAULT '0',
  `delete_time` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='银行表';
SQL;
        $this->createTableSql("t_bank", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('银行管理', 'bank', $pid, 1);
        }

        // 返佣比例
        $sql = <<<SQL
CREATE TABLE `t_commission_rate` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL COMMENT '代理级别',
  `rate` decimal(10,2) NOT NULL COMMENT '返佣比例 %',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否开启 1-启用 2-停用',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='返佣比例';
SQL;
        $ret = $this->createTableSql("t_commission_rate", $sql);

        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_commission_rate` (`id`, `level`, `rate`, `status`, `create_time`, `update_time`, `delete_time`) VALUES (1, 1, 10.00, 1, 0, 0, 0);
INSERT INTO `t_commission_rate` (`id`, `level`, `rate`, `status`, `create_time`, `update_time`, `delete_time`) VALUES (2, 2, 5.00, 1, 0, 0, 0);
SQL;
            $this->execute($sql);
        }
        $pid = $this->addMenuNode('业务设置', 'businessSetting', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('返佣比例', 'commissionRate', $pid, 1);
        }

        // 返佣比例
        $sql = <<<SQL
CREATE TABLE `t_third_auth` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ident` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标识',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-启用 2-停用',
  `is_login` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-启用 2-停用',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='三方授权渠道';
SQL;
        $ret = $this->createTableSql("t_third_auth", $sql);

        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_third_auth` (`id`, `name`, `ident`, `status`, `is_login`, `create_time`, `update_time`, `delete_time`) VALUES (1, '微信', 'weixin', 1, 1, 0, 0, 0);
INSERT INTO `t_third_auth` (`id`, `name`, `ident`, `status`, `is_login`, `create_time`, `update_time`, `delete_time`) VALUES (2, 'QQ', 'qq', 1, 1, 0, 0, 0);
INSERT INTO `t_third_auth` (`id`, `name`, `ident`, `status`, `is_login`, `create_time`, `update_time`, `delete_time`) VALUES (3, '支付宝', 'alipay', 1, 0, 0, 0, 0);
SQL;
            $this->execute($sql);
        }
        $pid = $this->addMenuNode('业务设置', 'businessSetting', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('三方授权渠道', 'thirdAuth', $pid, 1);
        }


        // 活动表
        $sql = <<<SQL
CREATE TABLE `t_activity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '活动标题',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '活动类型 1-通知类活动',
  `head_img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头图',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '活动内容',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL;
        $this->createTableSql("t_activity", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('活动管理', 'activity', $pid, 1);
        }
    }
}
