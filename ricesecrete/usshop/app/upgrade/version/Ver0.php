<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 基础版本
 * Class Ver0
 * @package app\upgrade\version
 */
class Ver0 extends VersionBase
{

    public function run()
    {
        //创键建管理员表
        $sql = <<<SQL
CREATE TABLE `t_auth_user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录用户名',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录密码',
  `realname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像地址',
  `status` tinyint unsigned DEFAULT '1' COMMENT '状态标识，0禁用，1正常',
  `bureau_id` int DEFAULT '0' COMMENT '绑定的旅行社id',
  `app_id` int DEFAULT '0' COMMENT '数据归属',
  `login_num` int unsigned DEFAULT '0' COMMENT '登录次数',
  `login_last_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '最后登录IP',
  `login_last_dt` int unsigned DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `idx_username` (`username`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员表';
SQL;
        $ret = $this->createTableSql("t_auth_user", $sql);
        if ($ret) {
            //插入默认数据
            $password = password_hash("123456", PASSWORD_BCRYPT);
            $sql = <<<SQL
INSERT INTO `t_auth_user` (`id`, `username`, `password`, `realname`, `avatar`, `status`, `app_id`, `login_num`, `login_last_ip`, `login_last_dt`, `delete_time`, `update_time`, `create_time`) VALUES ('1', 'admin', '{$password}', '超级管理员', NULL, '1', NULL, '205', '127.0.0.1', '1573119576', '0', '1668417736', '1528700859');
SQL;
            $this->execute($sql);
        }
        //创键建路由表
        $sql = <<<SQL
CREATE TABLE `t_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '规则URL',
  `url_param` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路由参数',
  `icon` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图标',
  `type` tinyint(3) unsigned DEFAULT '0' COMMENT '类型，0目录，1菜单，2页面，3按钮元素，4接口',
  `api_method` tinyint(3) unsigned DEFAULT '0' COMMENT '接口类型，0无，1get，2post，3delete，4put',
  `rank` int(10) unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '父级ID',
  `delete_time` int(10) unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `app_id` int(11) DEFAULT '0' COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `idx_name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='路由表';
SQL;
        $ret = $this->createTableSql("t_auth_rule", $sql);
        if ($ret) {
            //创键建路由表初始化数据
            $sql = <<<SQL
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('119', 'home', '工作台', '/home', NULL, 'iconfont icon-gongzuotai', '1', '0', '1000', '0', '0', '1567415120', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('139', 'auth', '权限管理', '', '', 'iconfont icon-quanxianguanli', '0', '0', '550', '0', '0', '1594279859', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('239', 'authUser', '管理员', '/system/auth/user', NULL, NULL, '1', '0', '0', '139', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('240', 'authRole', '角色分组', '/system/auth/role', NULL, NULL, '1', '0', '0', '139', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('241', 'authRule', '菜单权限', '/system/auth/rule', NULL, NULL, '1', '0', '0', '139', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('242', 'authRuleEdit', '编辑', '/system/auth/rule/edit', '/:id?', NULL, '2', '0', '0', '241', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('243', 'authRoleEdit', '编辑', '/system/auth/role/edit', '/:id?', NULL, '2', '0', '0', '240', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('244', 'authUserEdit', '编辑', '/system/auth/user/edit', '/:id?', NULL, '2', '0', '0', '239', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('249', 'system', '系统管理', '', '', 'iconfont icon-xitongshezhi', '0', '0', '500', '0', '0', '1594279874', '1564560451', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('265', 'editpwd', '修改密码', '/system/editpwd', '', '', '1', '0', '0', '249', '0', '1700031560', '1564738110', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('401', 'authUserDelBtn', '删除按钮', '', '', '', '3', '0', '0', '239', '0', '1574233280', '1574233280', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('402', 'auth/user/page', '获取列表', 'auth/user/page', '', '', '4', '1', '0', '239', '0', '1574233297', '1574233297', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('403', 'auth/user/info', '获取详情', 'auth/user/info', '', '', '4', '1', '0', '244', '0', '1574233313', '1574233313', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('404', 'auth/user/save', '保存', 'auth/user/save', '', '', '4', '2', '0', '244', '0', '1574233327', '1574233327', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('405', 'auth/user/delete', '删除', 'auth/user/delete', '', '', '4', '2', '0', '401', '0', '1574233341', '1574233341', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('406', 'auth/user/status', '保存状态', 'auth/user/status', '', '', '4', '2', '0', '239', '0', '1574233360', '1574233360', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('407', 'authRoleDelBtn', '删除按钮', '', '', '', '3', '0', '0', '240', '0', '1574233392', '1574233392', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('408', 'auth/group/page', '获取列表', 'auth/group/page', '', '', '4', '1', '0', '240', '0', '1574233409', '1574233409', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('409', 'auth/group/status', '修改状态', 'auth/group/status', '', '', '4', '2', '0', '240', '0', '1574233424', '1574233424', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('410', 'auth/group/info', '获取详情', 'auth/group/info', '', '', '4', '1', '0', '243', '0', '1574233439', '1574233439', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('411', 'auth/group/all', '获取所有', 'auth/group/all', '', '', '4', '1', '0', '240', '0', '1574233454', '1574233454', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('412', 'auth/group/save', '保存', 'auth/group/save', '', '', '4', '2', '0', '243', '0', '1574233469', '1574233469', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('413', 'auth/group/delete', '删除', 'auth/group/delete', '', '', '4', '2', '0', '407', '0', '1574233483', '1574233483', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('414', 'authRuleDelBtn', '删除按钮', '', '', '', '3', '0', '0', '241', '0', '1574233513', '1574233513', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('415', 'auth/rule/tree', '获取列表', 'auth/rule/tree', '', '', '4', '1', '0', '241', '0', '1574233526', '1574233526', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('416', 'auth/rule/info', '获取详情', 'auth/rule/info', '', '', '4', '1', '0', '242', '0', '1574233541', '1574233541', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('417', 'auth/rule/save', '保存', 'auth/rule/save', '', '', '4', '2', '0', '242', '0', '1574233556', '1574233556', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('418', 'auth/rule/delete', '删除', 'auth/rule/delete', '', '', '4', '2', '0', '414', '0', '1574233573', '1574233573', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('427', 'account/editpwd', '修改密码', 'account/editpwd', '', '', '4', '2', '0', '265', '0', '1574233794', '1574233794', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('428', 'account/profile', '用户信息', 'account/profile', '', '', '4', '1', '0', '249', '0', '1574233827', '1574233827', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('429', 'caches/reset', '重置缓存', 'caches/reset', '', '', '4', '2', '0', '430', '0', '1574236964', '1574233845', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('430', 'cachesBtn', '重置缓存按钮', '', '', '', '3', '0', '0', '249', '0', '1574236954', '1574236845', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('435', 'user/delcache', '清除缓存', 'user/delcache', '', '', '4', '2', '0', '434', '0', '1574303889', '1574303889', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('438', 'authUserDelCacheBtn', '清除缓存按钮', '', '', '', '3', '0', '0', '239', '0', '1574317463', '1574317463', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('439', 'auth/user/delcache', '清除缓存', 'auth/user/delcache', '', '', '4', '2', '0', '438', '0', '1574317480', '1574317480', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('462', 'sysConfig', '参数配置', '/system/config', '', '', '1', '0', '0', '249', '0', '1700031419', '1577936415', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('463', 'sys/config/page', '获取列表', 'sys/config/page', '', '', '4', '1', '0', '462', '0', '1574233297', '1574233297', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('464', 'sys/config/info', '获取详情', 'auth/user/info', '', '', '4', '1', '0', '462', '0', '1574233313', '1574233313', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('465', 'sys/config/save', '保存', 'auth/user/save', '', '', '4', '2', '0', '462', '0', '1574233327', '1574233327', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('466', 'sys/config/delete', '删除', 'auth/user/delete', '', '', '4', '2', '0', '462', '0', '1574233341', '1574233341', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('467', 'sysConfigEdit', '参数编辑', '/system/config/edit', '/:id?', '', '2', '0', '0', '462', '0', '1559197777', '1559197777', '0');
INSERT INTO `t_auth_rule` (`id`, `name`, `title`, `url`, `url_param`, `icon`, `type`, `api_method`, `rank`, `parent_id`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('468', 'sysConfigDelBtn', '删除按钮', '', '', '', '3', '0', '0', '462', '0', '1574233392', '1574233392', '0');
SQL;
            $this->execute($sql);
        }
        //创键权限分组表
        $sql = <<<SQL
CREATE TABLE `t_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '0状态：0禁用，1正常',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规则ID，多个规则","隔开',
  `delete_time` int(10) unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `app_id` int(11) DEFAULT '0' COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='权限分组表';
SQL;
        $ret = $this->createTableSql("t_auth_group", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_auth_group` (`id`, `title`, `status`, `rules`, `delete_time`, `update_time`, `create_time`, `app_id`) VALUES ('4', '管理员', '1', '119,502,503,510,509,508,507,506,505,511,518,517,516,515,514,513,519,526,525,524,523,522,521,527,534,533,532,531,530,529,535,542,541,540,539,538,537,536,544,528,543,550,549,548,547,546,545,560,520,559,566,565,564,563,562,561,512,504,493,495,494,501,500,499,498,497,496,139,239,244,401,402,406,438,240,243,407,408,409,411,241,242,414,415,249,265,427,428,430,429,462,463,464,465,466,467,468,469,471,480,552,470,477,476,475,474,473,472,479,486,485,484,483,482,481,551,558,557,556,555,554,553', '0', '1723207217', '1568786270', '0');
SQL;
            $this->execute($sql);
        }
        //创键授权关系表单
        $sql = <<<SQL
CREATE TABLE `t_auth_group_access` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组ID',
  `app_id` int(11) DEFAULT '0' COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_user_id` (`user_id`) USING BTREE,
  KEY `idx_group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='授权关系表单';
SQL;
        $this->createTableSql("t_auth_group_access", $sql);

        //创键系统配置数据
        $sql = <<<SQL
CREATE TABLE `t_sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `name` varchar(50) DEFAULT '' COMMENT '参数名称',
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '数据键值',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '数据内容',
  `add_time` int(11) DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `app_id` int(11) DEFAULT '0' COMMENT '数据归属',
  PRIMARY KEY (`id`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置数据';
SQL;
        $ret = $this->createTableSql("t_sys_config", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('1', 'OSS链接地址', 'ossUrl', 'https://wzqqfile.oss-cn-chengdu.aliyuncs.com', '1709710173', '1709710958', '0');
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('2', 'OSS地区域名', 'ossRegion', 'oss-cn-chengdu', '1709710890', '1709710890', '0');
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('3', 'OSS节点域名', 'ossEndpoint', 'oss-cn-chengdu.aliyuncs.com', '1709710937', '1709710937', '0');
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('4', 'OSS存储空间名称', 'ossBucket', 'wzqqfile', '1709711087', '1709711087', '0');
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('5', 'OSS账号KEY', 'ossAccessKeyId', 'REDACTED', '1709711122', '1709711122', '0');
INSERT INTO `t_sys_config` (`id`, `name`, `key`, `value`, `add_time`, `update_time`, `app_id`) VALUES ('6', 'OSS账号秘钥', 'ossAccessKeySecret', 'segXRLZ0GGoUep87lal5GKJBPiHgZ0', '1709711148', '1709711148', '0');
SQL;
            $this->execute($sql);
        }
        //创键用户日志表
        $sql = <<<SQL
CREATE TABLE `t_auth_handle_log` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `admin_id` int NOT NULL DEFAULT '0' COMMENT '管理员id',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作说明',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作路由',
  `param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作参数',
  `bureau_id` int DEFAULT '0' COMMENT '旅行社id',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_id` (`admin_id`),
  KEY `bureau_id` (`bureau_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员操作日志表';
SQL;
        $ret = $this->createTableSql("t_auth_handle_log", $sql);
        if ($ret) {
            $pid = $this->addMenuNode('系统管理', 'system', 'iconfont icon-xitongshezhi', 500);
            if ($pid) {
                $this->addChildNode('操作日志', 'sysLog', $pid, 10);
            }
        }
    }
}
