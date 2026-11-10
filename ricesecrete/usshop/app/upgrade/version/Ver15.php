<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 用户充值订单表
 * Class Ver14
 * @package app\upgrade\version
 */
class Ver15 extends VersionBase
{

    public function run()
    {
        //商品翻译拓展表
        $sql = <<<SQL
CREATE TABLE `t_balance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT '0' COMMENT '用户id',
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值单号',
  `pay_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '充值金额',
  `end_amount` decimal(20,2) DEFAULT '0.00' COMMENT '到账金额',
  `status` tinyint DEFAULT '1' COMMENT '支付状态 0取消支付 1待支付 2支付成功 3支付失败',
  `pay_time` int DEFAULT '0' COMMENT '支付时间',
  `pay_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付渠道',
  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='充值订单';
SQL;
        $ret = $this->createTableSql("t_balance", $sql);
        if ($ret) {
            $pid = $this->addMenuNode('充值提现', 'RechargeWithdrawal', 'iconfont el-icon-sell', 950);
            if ($pid) {
                $this->addChildNode('充值订单', 'recharge', $pid, 1);
                $this->addChildNode('用户余额记录', 'moneyLog', $pid, 2);
            }
        }
    }
}
