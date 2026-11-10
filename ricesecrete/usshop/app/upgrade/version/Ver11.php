<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 会员中心
 * Class Ver11
 * @package app\upgrade\version
 */
class Ver11 extends VersionBase
{

    public function run()
    {
        //会员等级表
        $sql = <<<SQL
CREATE TABLE `t_vip` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL DEFAULT '0' COMMENT '等级标识',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '等级名称',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '等级图片',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消费值',
  `invite_num` int unsigned NOT NULL DEFAULT '0' COMMENT '团队有效下级人数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-启用 2-停用',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员等级';
SQL;
        if ($this->createTableSql("t_vip", $sql)) {
            //会员等级
            $sql = <<<SQL
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (1, 0, '普通会员', '', 0.00, 0, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (2, 1, '青铜会员', '', 500.00, 1, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (3, 2, '白银会员', '', 1000.00, 3, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (4, 3, '黄金会员', '', 1500.00, 5, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (5, 4, '铂金会员', '', 2500.00, 8, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (6, 5, '钻石会员', '', 4000.00, 12, 1723272630, 1723272630, 0);
INSERT INTO `t_vip` (`id`, `level`, `name`, `src`, `money`, `invite_num`, `create_time`, `update_time`, `delete_time`) VALUES (7, 6, '至尊会员', '', 6000.00, 16, 1723272630, 1723272630, 0);
SQL;
            $this->execute($sql);
        }

        $pid = $this->addMenuNode('会员管理', 'member', 'iconfont el-icon-coordinate', 600);
        if ($pid) {
            $this->addChildNode('会员等级', 'vip', $pid, 1);
        }

        //会员权益表
        $sql = <<<SQL
CREATE TABLE `t_rights` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权益名称',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权益图片',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权益内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-启用 2-禁用',
  `create_time` int DEFAULT NULL,
  `update_time` int DEFAULT NULL,
  `delete_time` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权益表';
SQL;
        if ($this->createTableSql("t_rights", $sql)) {
            //会员权益
            $sql = <<<SQL
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (1, '买套餐送积分', '', '服务对象\r\n\r\n\r\n\r\n\r\n已解锁\r\n\r\n普通会员、青铜会员、白银会员、黄金会员、铂金会员、钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n1.在客户购买旅行套餐并完成旅行后，平台会根据客户购买旅行套餐的订单价格和当前会员等级赠送积分，不同会员等级赠送倍数见下表，积分赠送规则是按照每天千分之五的比例赠送至客户的账户中，直到所有积分赠送完成。如购买的某旅行套餐支付订单价格为1000元，则赠送积分为1000积分，每天赠送5积分，200天赠送完成\r\n\r\n\r\n\r\n2.积分可用于转换佣金和兑换积分商品\r\n\r\n积分转换：积分可按照1:1的比例转换为佣金，每日转换额度为100积分，佣金可提现至微信、支付宝。\r\n\r\n兑换积分商品：积分可在积分商城选择积分商品进行兑换，所有兑换的积分商品如在确认收货前发生质量问题可退货，同时返还客户积分\r\n\r\n\r\n\r\n3.积分使用规则最终解释权归玩赚全球旅游有限公司所有', NULL, NULL, NULL);
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (2, '提现免手续费', '', '服务对象\r\n\r\n\r\n\r\n\r\n待解锁\r\n\r\n青铜会员、白银会员、黄金会员、铂金会员、钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n会员可在提现佣金时享受免手续费的服务，不同等级会员每日提现可免手续费笔数见下表，提现规则见下表', NULL, NULL, NULL);
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (3, '生日惊喜福利', '', '服务对象\r\n\r\n白银会员、黄金会员、铂金会员、钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n根据会员个人资料填写的身份信息，生日当天打开【玩赚全球APP】手动领取生日礼包，内含100积分（一次性发放）和生日礼盒（价值300元），每个会员一年仅可获取一次生日礼包', NULL, NULL, NULL);
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (4, '赠送会员专属商品', '', '服务对象\r\n\r\n黄金会员、铂金会员、钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n我们会不定期为会员赠送精美实用的专属商品，可选择如旅行装备、家居用品等品类的商品，满足旅行或者生活需要（通过App内的活动中心发布会员专属商品赠送活动）', NULL, NULL, NULL);
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (5, '分享团队链接返佣', '', '服务对象\r\n\r\n铂金会员、钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n分享团队邀请链接给好友，好友注册加入会员用户的团队之后可获得一笔邀请佣金，好友注册越多，佣金越多', NULL, NULL, NULL);
INSERT INTO `t_rights` (`id`, `name`, `src`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (6, '年会免费参加', '', '服务对象\r\n\r\n钻石会员、至尊会员\r\n\r\n权益说明\r\n\r\n可免费报名玩赚全球贵阳总部的年会活动，尊享专区席位；（年会时间会提前在玩赚全球APP内发布，每年席位有限，优先报名享有）', NULL, NULL, NULL);
SQL;
            $this->execute($sql);
        }

        $pid = $this->addMenuNode('会员管理', 'member', 'iconfont el-icon-coordinate', 600);
        if ($pid) {
            $this->addChildNode('会员权益', 'rights', $pid, 1);
        }


        //会员等级权益关联表
        $sql = <<<SQL
CREATE TABLE `t_vip_rights` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vip_id` int NOT NULL DEFAULT '0' COMMENT '会员id',
  `right_id` int NOT NULL DEFAULT '0' COMMENT '权益id',
  `vip_level` int NOT NULL DEFAULT '0' COMMENT '会员等级',
  `number` int NOT NULL DEFAULT '0' COMMENT '权益值',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权益单位',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-启用 2-禁用',
  `create_time` int DEFAULT '0',
  `update_time` int DEFAULT '0',
  `delete_time` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员权益';
SQL;
        if ($this->createTableSql("t_vip_rights", $sql)) {
            //会员等级权益关联
            $sql = <<<SQL
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (1, 1, 1, 1, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (36, 1, 6, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (29, 1, 5, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (7, 1, 2, 0, '笔', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (22, 1, 4, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (14, 1, 3, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (2, 2, 1, 2, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (37, 2, 6, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (30, 2, 5, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (23, 2, 4, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (15, 2, 3, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (8, 2, 2, 1, '笔', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (3, 3, 1, 3, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (38, 3, 6, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (16, 3, 3, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (31, 3, 5, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (24, 3, 4, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (9, 3, 2, 2, '笔', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (25, 4, 4, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (4, 4, 1, 4, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (32, 4, 5, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (39, 4, 6, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (17, 4, 3, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (10, 4, 2, 3, '笔', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (40, 5, 6, 0, '', 0, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (26, 5, 4, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (5, 5, 1, 5, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (18, 5, 3, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (33, 5, 5, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (11, 5, 2, 4, '笔', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (41, 6, 6, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (27, 6, 4, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (19, 6, 3, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (6, 6, 1, 6, '倍', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (34, 6, 5, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (12, 6, 2, 5, '笔', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (42, 7, 6, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (35, 7, 5, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (28, 7, 4, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (21, 7, 1, 7, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (20, 7, 3, 0, '', 1, 0, 0, 0);
INSERT INTO `t_vip_rights` (`id`, `vip_id`, `right_id`, `number`, `unit`, `is_open`, `create_time`, `update_time`, `delete_time`) VALUES (13, 7, 2, 6, '笔', 1, 0, 0, 0);
SQL;
            $this->execute($sql);
        }
    }
}
