/*
 Navicat Premium Data Transfer

 Source Server         : 腾讯基础版
 Source Server Type    : MySQL
 Source Server Version : 50718
 Source Host           : mysql-1.ennn.cn:10130
 Source Schema         : demo_muxue_com_cn

 Target Server Type    : MySQL
 Target Server Version : 50718
 File Encoding         : 65001

 Date: 28/03/2021 17:36:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mx_admin
-- ----------------------------
DROP TABLE IF EXISTS `mx_admin`;
CREATE TABLE `mx_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否超级管理员',
  `username` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `nickname` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '头像地址',
  `phone` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '电话',
  `email` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '邮箱',
  `login_ip` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'IP地址',
  `login_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '登录时间',
  `login_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `last_login_ip` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '上次登录IP',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '上次登录时间',
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'user_agent',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_admin
-- ----------------------------
INSERT INTO `mx_admin` VALUES (1, 1, 'zlmlovem', 'f4409a70188c35bc1fa5ad6c3b9a1acf', '管理员', '', '', '', '112.49.92.39', 1616920817, 1, '112.49.92.39', 1616913928, NULL, 1, 1606649826, 1616920817);
INSERT INTO `mx_admin` VALUES (2, 0, 'demo', 'e10adc3949ba59abbe56e057f20f883e', '体验账号', '', '', '', '180.142.65.26', 1616923803, 1, '112.49.92.39', 1616851578, NULL, 1, 1606649826, 1616923803);

-- ----------------------------
-- Table structure for mx_attachment
-- ----------------------------
DROP TABLE IF EXISTS `mx_attachment`;
CREATE TABLE `mx_attachment`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '管理员ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '物理路径',
  `filetype` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '文件类型',
  `filesize` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '文件大小',
  `mimetype` char(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'mime类型',
  `storage` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '存储位置',
  `sha1` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '文件 sha1编码',
  `hash` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '云存储hash(ETag)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for mx_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `mx_auth_group`;
CREATE TABLE `mx_auth_group`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `title` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `comments` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '角色描述',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '角色所拥有的权限ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_auth_group
-- ----------------------------
INSERT INTO `mx_auth_group` VALUES (1, '管理员', '系统管理员', '1,2,46,47,48,3,38,39,40,41,42,43,44,45,4,5,8,9,10,33,6,11,12,13,20,34,7,14,15,16,35', 1, 1606650669, 1614066889);
INSERT INTO `mx_auth_group` VALUES (2, '普通用户', '普通用户', '4,5,8,9,10,33', 1, 1606650669, 1615447994);
INSERT INTO `mx_auth_group` VALUES (3, '游客', '游客', '1,2,48,3,38,41,45,49,51,4,5,33,6,34,7,35,52,53,59,54,63,55,67', 1, 1606650669, 1616749330);

-- ----------------------------
-- Table structure for mx_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `mx_auth_group_access`;
CREATE TABLE `mx_auth_group_access`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '管理员ID',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限角色组关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_auth_group_access
-- ----------------------------
INSERT INTO `mx_auth_group_access` VALUES (1, 1, 1);
INSERT INTO `mx_auth_group_access` VALUES (20, 2, 3);

-- ----------------------------
-- Table structure for mx_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `mx_auth_rule`;
CREATE TABLE `mx_auth_rule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `pid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` char(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否为有效规则',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '规则类型(0链接,1按钮)	',
  `isnav` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '导航菜单(0否,1是)',
  `icon` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '菜单图标',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重排序',
  `open` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '菜单(0收起,1展开)',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `conditions` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则表达式',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 69 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_auth_rule
-- ----------------------------
INSERT INTO `mx_auth_rule` VALUES (1, 0, '', '系统配置', 1, 0, 1, 'layui-icon layui-icon-set', 1, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (2, 1, 'mxadmin/configure/index', '参数配置', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (3, 1, 'mxadmin/oplog/index', '日志管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (4, 0, '', '权限管理', 1, 0, 1, 'layui-icon layui-icon-user', 2, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (5, 4, 'mxadmin/admin/index', '用户管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (6, 4, 'mxadmin/role/index', '角色管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (7, 4, 'mxadmin/auth/index', '规则管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (8, 5, 'mxadmin/admin/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (9, 5, 'mxadmin/admin/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (10, 5, 'mxadmin/admin/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (11, 6, 'mxadmin/role/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (12, 6, 'mxadmin/role/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (13, 6, 'mxadmin/role/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (14, 7, 'mxadmin/auth/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (15, 7, 'mxadmin/auth/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (16, 7, 'mxadmin/auth/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (20, 6, 'mxadmin/role/authlist', '权限分配', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (33, 5, 'mxadmin/admin/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (34, 6, 'mxadmin/role/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (35, 7, 'mxadmin/auth/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (38, 3, 'mxadmin/oplog/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (39, 3, 'mxadmin/oplog/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (40, 3, 'mxadmin/oplog/delall', '一键清空', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (41, 1, 'mxadmin/dictionary/index', '字典管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (42, 41, 'mxadmin/dictionary/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (43, 41, 'mxadmin/dictionary/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (44, 41, 'mxadmin/dictionary/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (45, 41, 'mxadmin/dictionary/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (46, 2, 'mxadmin/configure/submit', '保存配置', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (47, 2, 'mxadmin/upload/upload', '文件上传', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (48, 2, 'mxadmin/configure/index', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (49, 1, 'mxadmin/attach/index', '附件管理', 1, 0, 1, '', 50, 0, 1, '', 1615371984, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (50, 49, 'mxadmin/attach/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615372015, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (51, 49, 'mxadmin/attach/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615372058, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (52, 0, '', '内容管理', 1, 0, 1, 'layui-icon layui-icon-read', 50, 1, 1, '', 1615794543, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (53, 52, 'cms/category/index', '栏目管理', 1, 0, 1, '', 50, 0, 1, '', 1615794658, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (54, 52, 'cms/article/index', '文章管理', 1, 0, 1, '', 50, 0, 1, '', 1615794716, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (55, 52, 'cms/ad/index', '广告管理', 1, 0, 1, '', 50, 0, 1, '', 1615794778, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (56, 53, 'cms/category/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615795287, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (57, 53, 'cms/category/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615795886, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (58, 53, 'cms/category/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615795916, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (59, 53, 'cms/category/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615795933, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (60, 54, 'cms/article/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615796109, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (61, 54, 'cms/article/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615796135, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (62, 54, 'cms/article/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615796210, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (63, 54, 'cms/article/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615796231, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (64, 55, 'cms/ad/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615796258, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (65, 55, 'cms/ad/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615796291, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (66, 55, 'cms/ad/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615796364, 1616923882);
INSERT INTO `mx_auth_rule` VALUES (67, 55, 'cms/ad/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615796568, 1616923883);
INSERT INTO `mx_auth_rule` VALUES (68, 2, 'mxadmin/tpl/password', '修改密码', 1, 1, 0, '', 50, 1, 1, '', 1616748697, 1616923883);

-- ----------------------------
-- Table structure for mx_cms_ad
-- ----------------------------
DROP TABLE IF EXISTS `mx_cms_ad`;
CREATE TABLE `mx_cms_ad`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告分类',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接地址',
  `target` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '打开方式',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_cms_ad
-- ----------------------------

-- ----------------------------
-- Table structure for mx_cms_article
-- ----------------------------
DROP TABLE IF EXISTS `mx_cms_article`;
CREATE TABLE `mx_cms_article`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `category_id` int(10) UNSIGNED NOT NULL COMMENT '栏目ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `shorttitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '副标题',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '缩略图',
  `keywords` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '关键词',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '描述',
  `author` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文章作者',
  `source` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文章来源',
  `click` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击数',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容正文',
  `photos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图片组',
  `video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '视频',
  `audio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '音频',
  `redirecturl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '跳转地址',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权重排序',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否热门(0否,1是)',
  `is_recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐(0否,1是)',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_cms_article
-- ----------------------------
INSERT INTO `mx_cms_article` VALUES (1, 4, 1, '开场问候歌1', NULL, 'http://img.zlm.ennn.cn/20210317/d07297ac326ae91c35770e7e26944759.png', '关键词', '描述', '文章作者', '文章来源', 999, '<p><img src=\"http://img.zlm.ennn.cn/20210317/32705271a89708000a0942de3a213a95.png\" alt=\"\" width=\"483\" height=\"364\" /></p>\n<p><img src=\"http://img.zlm.ennn.cn/20210317/4e42dfe546be9c3cd12f7390516e0fa7.png\" /></p>', 'http://img.zlm.ennn.cn/20210317/693d05b29fc993e3f657649cc20ee524.png,http://img.zlm.ennn.cn/20210317/32705271a89708000a0942de3a213a95.png,http://img.zlm.ennn.cn/20210317/9b3726bbbeb3bb48fec76a50e2630920.png', '', 'http://img.zlm.ennn.cn/20210303/7eb51fe4b3105eae2a25f0566bd2434b.mp3', '', 50, 0, 0, 1, 1614765295, 1615986277);
INSERT INTO `mx_cms_article` VALUES (2, 4, 1, '开场问候图册', NULL, '', '', '', '', '', 0, '<p>Super bog-standard Why bevvy tinkety tonk old fruit twit it\'s all gone to pot David geeza ruddy spiffing, bobby barney a brolly tomfoolery hunky-dory codswallop horse play cuppa hotpot, bamboozled mufty happy days cheesed.</p>\n<p><img class=\"img-fluid\" src=\"http://img.zlm.ennn.cn/20210303/fdaed5e97eaacb199da8d79e419edb80.jpg\" alt=\"#\" width=\"262\" height=\"262\" /></p>\n<p>Baking cakes down the pub crikey cracking goal mufty he legged it arse over tit brolly, old boot happy days my lady me old mucker gormless lemon squeezy butty, the wireless bleeding cor blimey guvnor some dodgy chav Charles Jeffrey. Hotpot me old mucker I cup of tea he legged it my lady twit give us a bell happy days he nicked it cheesed off blimey lurgy gutted mate knees up car boot, ruddy pardon me quaint show off show off pick your nose and blow off brolly chinwag absolutely bladdered owt to do.</p>', '', '', '', '', 50, 0, 0, 1, 1614766298, 1616138731);
INSERT INTO `mx_cms_article` VALUES (3, 4, 1, '开场问候歌2', NULL, '', '', '', '', '', 0, '<p><img src=\"/storage/images/20210319/b61dd85a63797f4c92e5dcd1cb6eedcb.png\" /><img src=\"/storage/images/20210319/fc3dff6b77277fd6e2910d9582cc72ad.png\" alt=\"\" width=\"483\" height=\"364\" /></p>', '/storage/images/20210319/bd05dcb1136718be579f72743f6ec283.png,/storage/images/20210319/a59e7a3a95acd4157ad4ba0c81f87a32.png,/storage/images/20210319/2e57096bd3c7b0a655f6513033d768a2.png,/storage/images/20210319/ec190f693aa720294a725cf4837d4f4f.png,/storage/images/20210319/2253fea4f52f20da7d68f96b7dc6037f.png', '', 'https://www.17sucai.com/preview/493260/2020-02-27/%E6%8F%92%E4%BB%B6/yinyue/dongtian.mp3', '', 50, 0, 0, 1, 1615112398, 1616125511);
INSERT INTO `mx_cms_article` VALUES (4, 4, 1, '开场视频', NULL, '', '', NULL, NULL, NULL, 0, NULL, NULL, 'https://www.17sucai.com/preview/952947/2017-12-07/lightbox/demo/BigBuckBunny_320x180.mp4', '', '', 50, 0, 0, 1, 1615112657, 1615987123);

-- ----------------------------
-- Table structure for mx_cms_category
-- ----------------------------
DROP TABLE IF EXISTS `mx_cms_category`;
CREATE TABLE `mx_cms_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父栏目ID',
  `topid` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '顶级栏目ID',
  `name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '栏目模型(0文章模型,1产品模型)',
  `urltype` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '栏目类型(0列表页,1频道页,2内容页,3外部连接)',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '栏目链接',
  `target` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '打开方式',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图片地址',
  `seotitle` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'seo标题',
  `keywords` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '描述',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重排序',
  `open` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '菜单(0收起,1展开)',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `conditions` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则表达式',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_cms_category
-- ----------------------------
INSERT INTO `mx_cms_category` VALUES (1, 0, 1, '幼儿课件', 0, 0, '/list/1.html', '_self', '', '', '', '', 50, 1, 1, '', 1614682895, 1616842567);
INSERT INTO `mx_cms_category` VALUES (2, 0, 2, '成人课件', 0, 0, '/list/2.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690183, 1616842567);
INSERT INTO `mx_cms_category` VALUES (3, 1, 1, '第一节课', 0, 0, '/list/3.html', '_self', '/storage/images/20210319/0698c02e89bbd1de283aa5a1ba33caac.png', '', '', '', 50, 1, 1, '', 1614690191, 1616842567);
INSERT INTO `mx_cms_category` VALUES (4, 3, 1, '开场：问候歌', 0, 0, '/list/4.html', '_self', 'http://img.zlm.ennn.cn/20210315/a68db21bfba341c1b0675782cbada829.png', '1', '2', '3', 50, 1, 1, '', 1614690216, 1616842565);
INSERT INTO `mx_cms_category` VALUES (5, 3, 1, '热身', 0, 0, '/list/5.html', '_self', '/storage/images/20210315/2d200174d1ba4fd7a2a94b466eaac9e9.png', '', '', '', 50, 1, 1, '', 1614690270, 1616842565);
INSERT INTO `mx_cms_category` VALUES (6, 3, 1, '回课', 0, 0, '/list/6.html', '_self', '/storage/images/20210307/3711595428def9590be1dcf86003a75e.png', '', '', '', 50, 1, 1, '', 1614690279, 1616842565);
INSERT INTO `mx_cms_category` VALUES (7, 3, 1, '乐理：乐理知识', 0, 0, '/list/7.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690319, 1616842565);
INSERT INTO `mx_cms_category` VALUES (8, 3, 1, '新课', 0, 0, '/list/8.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690331, 1616842565);
INSERT INTO `mx_cms_category` VALUES (9, 3, 1, '歌唱', 0, 0, '/list/9.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690339, 1616842565);
INSERT INTO `mx_cms_category` VALUES (10, 3, 1, '结束', 0, 0, '/list/10.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690346, 1616842565);
INSERT INTO `mx_cms_category` VALUES (11, 2, 2, '第一节课', 0, 0, '/list/11.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690432, 1616842567);
INSERT INTO `mx_cms_category` VALUES (12, 2, 2, '第二节课', 0, 0, '/list/12.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690439, 1616842567);
INSERT INTO `mx_cms_category` VALUES (13, 11, 2, '开场：问候歌', 0, 0, '/list/13.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690492, 1616842565);
INSERT INTO `mx_cms_category` VALUES (14, 11, 2, '热身', 0, 0, '/list/14.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690498, 1616842565);
INSERT INTO `mx_cms_category` VALUES (15, 11, 2, '回课', 0, 0, '/list/15.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690503, 1616842565);
INSERT INTO `mx_cms_category` VALUES (16, 11, 2, '乐理：乐理知识', 0, 0, '/list/16.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690509, 1616842565);
INSERT INTO `mx_cms_category` VALUES (17, 11, 2, '新课', 0, 0, '/list/17.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690516, 1616842565);
INSERT INTO `mx_cms_category` VALUES (18, 11, 2, '歌唱', 0, 0, '/list/18.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690522, 1616842565);
INSERT INTO `mx_cms_category` VALUES (19, 11, 2, '结束', 0, 0, '/list/19.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690528, 1616842565);
INSERT INTO `mx_cms_category` VALUES (20, 12, 2, '开场：问候歌', 0, 0, '/list/20.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690533, 1616842565);
INSERT INTO `mx_cms_category` VALUES (21, 12, 2, '热身', 0, 0, '/list/21.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690539, 1616842565);
INSERT INTO `mx_cms_category` VALUES (22, 12, 2, '回课', 0, 0, '/list/22.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690546, 1616842565);
INSERT INTO `mx_cms_category` VALUES (23, 12, 2, '乐理：乐理知识', 0, 0, '/list/23.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690553, 1616842565);
INSERT INTO `mx_cms_category` VALUES (24, 12, 2, '新课', 0, 0, '/list/24.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690558, 1616842565);
INSERT INTO `mx_cms_category` VALUES (25, 12, 2, '歌唱', 0, 0, '/list/25.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690565, 1616842565);
INSERT INTO `mx_cms_category` VALUES (26, 12, 2, '结束', 0, 0, '/list/26.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690571, 1616842565);

-- ----------------------------
-- Table structure for mx_config
-- ----------------------------
DROP TABLE IF EXISTS `mx_config`;
CREATE TABLE `mx_config`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `type` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分类',
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '配置值',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_system_config_type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_config
-- ----------------------------
INSERT INTO `mx_config` VALUES (1, 'system', '{\"typename\":\"system\",\"logo\":\"\",\"file\":\"\",\"webname\":\"\\u798f\\u5dde\\u76ee\\u96ea\\u79d1\\u6280\\u6709\\u9650\\u516c\\u53f8\",\"domain\":\"\",\"title\":\"\",\"keywords\":\"\",\"description\":\"\",\"beian\":\"\",\"miitbeian\":\"\",\"copyright\":\"\"}');
INSERT INTO `mx_config` VALUES (2, 'storage', '{\"typename\":\"storage\",\"engine\":\"1\",\"accesskey\":\"6EzKUbb27RkI7WuiA5_o4OQCzAUqg1b8jK4JDy7r\",\"secretkey\":\"Xf3qGIlP1dXACiaw2UTYZLIcGFUevWWAy1R2N-1c\",\"bucket\":\"zlmlovem\",\"domain\":\"http:\\/\\/img.zlm.ennn.cn\"}');
INSERT INTO `mx_config` VALUES (3, 'weixin', NULL);
INSERT INTO `mx_config` VALUES (4, 'wxapp', NULL);
INSERT INTO `mx_config` VALUES (5, 'wxpay', NULL);
INSERT INTO `mx_config` VALUES (6, 'email', '{\"typename\":\"email\",\"username\":\"xiapuonline@qq.com\",\"fullname\":\"\\u798f\\u5dde\\u76ee\\u96ea\\u79d1\\u6280\\u6709\\u9650\\u516c\\u53f8\",\"password\":\"efnvasvskcsqfhii\",\"host\":\"smtp.qq.com\",\"port\":\"465\",\"subject\":\"\\u90ae\\u7bb1\\u9a8c\\u8bc1\\u7801\",\"body\":\"\\u9a8c\\u8bc1\\u780110\\u5206\\u949f\\u4e4b\\u5185\\u6709\\u6548\\uff0c\\u8bf7\\u5c3d\\u5feb\\u9a8c\\u8bc1\\uff0c\\u90ae\\u7bb1\\u9a8c\\u8bc1\\u7801\\u662f\\uff1a\",\"notice_email\":\"zlmlovem@qq.com\"}');

-- ----------------------------
-- Table structure for mx_dict
-- ----------------------------
DROP TABLE IF EXISTS `mx_dict`;
CREATE TABLE `mx_dict`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `auth_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属权限ID',
  `name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '字典名称',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重排序',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_dict
-- ----------------------------
INSERT INTO `mx_dict` VALUES (1, 0, '广告分类', 50, 1615988608, 1616001097);

-- ----------------------------
-- Table structure for mx_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `mx_dict_data`;
CREATE TABLE `mx_dict_data`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `dict_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '字典ID',
  `name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '字典项名称',
  `weight` mediumint(8) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典项表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_dict_data
-- ----------------------------
INSERT INTO `mx_dict_data` VALUES (1, 1, '分类1', 50, 1, 1615988623, 1616046309);
INSERT INTO `mx_dict_data` VALUES (2, 1, '分类2', 50, 1, 1615988627, 1616046305);

-- ----------------------------
-- Table structure for mx_email_code
-- ----------------------------
DROP TABLE IF EXISTS `mx_email_code`;
CREATE TABLE `mx_email_code`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱地址',
  `code` int(6) UNSIGNED NULL DEFAULT NULL COMMENT '验证码',
  `exp_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_email_code
-- ----------------------------

-- ----------------------------
-- Table structure for mx_oplog
-- ----------------------------
DROP TABLE IF EXISTS `mx_oplog`;
CREATE TABLE `mx_oplog`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `node` char(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '当前操作节点',
  `geoip` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作者IP地址',
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作行为名称',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作内容描述',
  `username` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作人用户名',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 158 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_oplog
-- ----------------------------
INSERT INTO `mx_oplog` VALUES (2, 'mxadmin/login/index', '112.49.92.22', '用户登录', '登录系统后台成功', 'zlmlovem', 1616217009, 1616217009);
INSERT INTO `mx_oplog` VALUES (3, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616748229, 1616748229);
INSERT INTO `mx_oplog` VALUES (4, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616748261, 1616748261);
INSERT INTO `mx_oplog` VALUES (5, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616748329, 1616748329);
INSERT INTO `mx_oplog` VALUES (6, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616748448, 1616748448);
INSERT INTO `mx_oplog` VALUES (7, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616748839, 1616748839);
INSERT INTO `mx_oplog` VALUES (8, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616749083, 1616749083);
INSERT INTO `mx_oplog` VALUES (9, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616749272, 1616749272);
INSERT INTO `mx_oplog` VALUES (10, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616749301, 1616749301);
INSERT INTO `mx_oplog` VALUES (11, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616759403, 1616759403);
INSERT INTO `mx_oplog` VALUES (12, 'mxadmin/login/index', '125.117.235.79', '用户登录', '登录系统后台成功', 'demo', 1616762304, 1616762304);
INSERT INTO `mx_oplog` VALUES (13, 'mxadmin/login/index', '182.118.239.162', '用户登录', '登录系统后台成功', 'demo', 1616762318, 1616762318);
INSERT INTO `mx_oplog` VALUES (14, 'mxadmin/login/index', '118.113.102.43', '用户登录', '登录系统后台成功', 'demo', 1616762322, 1616762322);
INSERT INTO `mx_oplog` VALUES (15, 'mxadmin/login/index', '49.211.18.87', '用户登录', '登录系统后台成功', 'demo', 1616762368, 1616762368);
INSERT INTO `mx_oplog` VALUES (16, 'mxadmin/login/index', '119.141.255.102', '用户登录', '登录系统后台成功', 'demo', 1616762406, 1616762406);
INSERT INTO `mx_oplog` VALUES (17, 'mxadmin/login/index', '183.221.4.215', '用户登录', '登录系统后台成功', 'demo', 1616762450, 1616762450);
INSERT INTO `mx_oplog` VALUES (18, 'mxadmin/login/index', '36.251.86.90', '用户登录', '登录系统后台成功', 'demo', 1616762484, 1616762484);
INSERT INTO `mx_oplog` VALUES (19, 'mxadmin/login/index', '117.139.178.76', '用户登录', '登录系统后台成功', 'demo', 1616762541, 1616762541);
INSERT INTO `mx_oplog` VALUES (20, 'mxadmin/login/index', '14.155.158.9', '用户登录', '登录系统后台成功', 'demo', 1616762721, 1616762721);
INSERT INTO `mx_oplog` VALUES (21, 'mxadmin/login/index', '106.113.84.114', '用户登录', '登录系统后台成功', 'demo', 1616762737, 1616762737);
INSERT INTO `mx_oplog` VALUES (22, 'mxadmin/login/index', '114.217.210.52', '用户登录', '登录系统后台成功', 'demo', 1616762873, 1616762873);
INSERT INTO `mx_oplog` VALUES (23, 'mxadmin/login/index', '220.163.105.139', '用户登录', '登录系统后台成功', 'demo', 1616762977, 1616762977);
INSERT INTO `mx_oplog` VALUES (24, 'mxadmin/login/index', '112.22.20.130', '用户登录', '登录系统后台成功', 'demo', 1616763043, 1616763043);
INSERT INTO `mx_oplog` VALUES (25, 'mxadmin/login/index', '113.89.59.81', '用户登录', '登录系统后台成功', 'demo', 1616763669, 1616763669);
INSERT INTO `mx_oplog` VALUES (26, 'mxadmin/login/index', '27.44.181.93', '用户登录', '登录系统后台成功', 'demo', 1616763929, 1616763929);
INSERT INTO `mx_oplog` VALUES (27, 'mxadmin/login/index', '183.40.204.191', '用户登录', '登录系统后台成功', 'demo', 1616764116, 1616764116);
INSERT INTO `mx_oplog` VALUES (28, 'mxadmin/login/index', '115.204.243.17', '用户登录', '登录系统后台成功', 'demo', 1616764616, 1616764616);
INSERT INTO `mx_oplog` VALUES (29, 'mxadmin/login/index', '117.151.222.170', '用户登录', '登录系统后台成功', 'demo', 1616764705, 1616764705);
INSERT INTO `mx_oplog` VALUES (30, 'mxadmin/login/index', '110.228.34.54', '用户登录', '登录系统后台成功', 'demo', 1616764819, 1616764819);
INSERT INTO `mx_oplog` VALUES (31, 'mxadmin/login/index', '112.122.175.52', '用户登录', '登录系统后台成功', 'demo', 1616764835, 1616764835);
INSERT INTO `mx_oplog` VALUES (32, 'mxadmin/login/index', '116.252.42.239', '用户登录', '登录系统后台成功', 'demo', 1616765047, 1616765047);
INSERT INTO `mx_oplog` VALUES (33, 'mxadmin/login/index', '1.205.152.199', '用户登录', '登录系统后台成功', 'demo', 1616765280, 1616765280);
INSERT INTO `mx_oplog` VALUES (34, 'mxadmin/login/index', '117.136.12.238', '用户登录', '登录系统后台成功', 'demo', 1616765433, 1616765433);
INSERT INTO `mx_oplog` VALUES (35, 'mxadmin/login/index', '118.113.102.43', '用户登录', '登录系统后台成功', 'demo', 1616765473, 1616765473);
INSERT INTO `mx_oplog` VALUES (36, 'mxadmin/login/index', '144.255.35.175', '用户登录', '登录系统后台成功', 'demo', 1616765711, 1616765711);
INSERT INTO `mx_oplog` VALUES (37, 'mxadmin/login/index', '116.232.237.236', '用户登录', '登录系统后台成功', 'demo', 1616766106, 1616766106);
INSERT INTO `mx_oplog` VALUES (38, 'mxadmin/login/index', '114.246.2.40', '用户登录', '登录系统后台成功', 'demo', 1616766351, 1616766351);
INSERT INTO `mx_oplog` VALUES (39, 'mxadmin/login/index', '27.38.8.201', '用户登录', '登录系统后台成功', 'demo', 1616766642, 1616766642);
INSERT INTO `mx_oplog` VALUES (40, 'mxadmin/login/index', '113.102.238.126', '用户登录', '登录系统后台成功', 'demo', 1616767265, 1616767265);
INSERT INTO `mx_oplog` VALUES (41, 'mxadmin/login/index', '111.36.169.210', '用户登录', '登录系统后台成功', 'demo', 1616767816, 1616767816);
INSERT INTO `mx_oplog` VALUES (42, 'mxadmin/login/index', '110.185.29.247', '用户登录', '登录系统后台成功', 'demo', 1616768396, 1616768396);
INSERT INTO `mx_oplog` VALUES (43, 'mxadmin/login/index', '182.124.231.226', '用户登录', '登录系统后台成功', 'demo', 1616768481, 1616768481);
INSERT INTO `mx_oplog` VALUES (44, 'mxadmin/login/index', '116.21.12.150', '用户登录', '登录系统后台成功', 'demo', 1616768584, 1616768584);
INSERT INTO `mx_oplog` VALUES (45, 'mxadmin/login/index', '211.138.116.139', '用户登录', '登录系统后台成功', 'demo', 1616769269, 1616769269);
INSERT INTO `mx_oplog` VALUES (46, 'mxadmin/login/index', '183.228.191.163', '用户登录', '登录系统后台成功', 'demo', 1616769459, 1616769459);
INSERT INTO `mx_oplog` VALUES (47, 'mxadmin/login/index', '123.119.131.164', '用户登录', '登录系统后台成功', 'demo', 1616769507, 1616769507);
INSERT INTO `mx_oplog` VALUES (48, 'mxadmin/login/index', '117.88.154.26', '用户登录', '登录系统后台成功', 'demo', 1616769698, 1616769698);
INSERT INTO `mx_oplog` VALUES (49, 'mxadmin/login/index', '36.59.101.133', '用户登录', '登录系统后台成功', 'demo', 1616770804, 1616770804);
INSERT INTO `mx_oplog` VALUES (50, 'mxadmin/login/index', '121.228.104.210', '用户登录', '登录系统后台成功', 'demo', 1616771373, 1616771373);
INSERT INTO `mx_oplog` VALUES (51, 'mxadmin/login/index', '101.86.237.68', '用户登录', '登录系统后台成功', 'demo', 1616773156, 1616773156);
INSERT INTO `mx_oplog` VALUES (52, 'mxadmin/login/index', '113.16.112.182', '用户登录', '登录系统后台成功', 'demo', 1616773216, 1616773216);
INSERT INTO `mx_oplog` VALUES (53, 'mxadmin/login/index', '118.112.108.57', '用户登录', '登录系统后台成功', 'demo', 1616773391, 1616773391);
INSERT INTO `mx_oplog` VALUES (54, 'mxadmin/login/index', '114.217.160.224', '用户登录', '登录系统后台成功', 'demo', 1616773986, 1616773986);
INSERT INTO `mx_oplog` VALUES (55, 'mxadmin/login/index', '101.73.162.46', '用户登录', '登录系统后台成功', 'demo', 1616774545, 1616774545);
INSERT INTO `mx_oplog` VALUES (56, 'mxadmin/login/index', '113.91.41.110', '用户登录', '登录系统后台成功', 'demo', 1616774554, 1616774554);
INSERT INTO `mx_oplog` VALUES (57, 'mxadmin/login/index', '118.163.180.56', '用户登录', '登录系统后台成功', 'demo', 1616775723, 1616775723);
INSERT INTO `mx_oplog` VALUES (58, 'mxadmin/login/index', '182.110.227.168', '用户登录', '登录系统后台成功', 'demo', 1616775830, 1616775830);
INSERT INTO `mx_oplog` VALUES (59, 'mxadmin/login/index', '119.123.178.11', '用户登录', '登录系统后台成功', 'demo', 1616778404, 1616778404);
INSERT INTO `mx_oplog` VALUES (60, 'mxadmin/login/index', '14.204.0.247', '用户登录', '登录系统后台成功', 'demo', 1616779779, 1616779779);
INSERT INTO `mx_oplog` VALUES (61, 'mxadmin/login/index', '119.251.59.24', '用户登录', '登录系统后台成功', 'demo', 1616781764, 1616781764);
INSERT INTO `mx_oplog` VALUES (62, 'mxadmin/login/index', '110.18.14.219', '用户登录', '登录系统后台成功', 'demo', 1616785470, 1616785470);
INSERT INTO `mx_oplog` VALUES (63, 'mxadmin/login/index', '117.152.236.198', '用户登录', '登录系统后台成功', 'demo', 1616787517, 1616787517);
INSERT INTO `mx_oplog` VALUES (64, 'mxadmin/login/index', '183.210.49.38', '用户登录', '登录系统后台成功', 'demo', 1616796456, 1616796456);
INSERT INTO `mx_oplog` VALUES (65, 'mxadmin/login/index', '120.229.90.46', '用户登录', '登录系统后台成功', 'demo', 1616798441, 1616798441);
INSERT INTO `mx_oplog` VALUES (66, 'mxadmin/login/index', '117.151.222.170', '用户登录', '登录系统后台成功', 'demo', 1616801052, 1616801052);
INSERT INTO `mx_oplog` VALUES (67, 'mxadmin/login/index', '111.224.83.140', '用户登录', '登录系统后台成功', 'demo', 1616803347, 1616803347);
INSERT INTO `mx_oplog` VALUES (68, 'mxadmin/login/index', '27.154.78.29', '用户登录', '登录系统后台成功', 'demo', 1616803478, 1616803478);
INSERT INTO `mx_oplog` VALUES (69, 'mxadmin/login/index', '117.24.202.197', '用户登录', '登录系统后台成功', 'demo', 1616803671, 1616803671);
INSERT INTO `mx_oplog` VALUES (70, 'mxadmin/login/index', '180.125.9.173', '用户登录', '登录系统后台成功', 'demo', 1616804222, 1616804222);
INSERT INTO `mx_oplog` VALUES (71, 'mxadmin/login/index', '112.9.0.235', '用户登录', '登录系统后台成功', 'demo', 1616804543, 1616804543);
INSERT INTO `mx_oplog` VALUES (72, 'mxadmin/login/index', '112.9.0.235', '用户登录', '登录系统后台成功', 'demo', 1616804691, 1616804691);
INSERT INTO `mx_oplog` VALUES (73, 'mxadmin/login/index', '118.112.104.250', '用户登录', '登录系统后台成功', 'demo', 1616804899, 1616804899);
INSERT INTO `mx_oplog` VALUES (74, 'mxadmin/login/index', '111.26.106.53', '用户登录', '登录系统后台成功', 'demo', 1616805819, 1616805819);
INSERT INTO `mx_oplog` VALUES (75, 'mxadmin/login/index', '211.161.243.90', '用户登录', '登录系统后台成功', 'demo', 1616806008, 1616806008);
INSERT INTO `mx_oplog` VALUES (76, 'mxadmin/login/index', '223.104.38.199', '用户登录', '登录系统后台成功', 'demo', 1616806457, 1616806457);
INSERT INTO `mx_oplog` VALUES (77, 'mxadmin/login/index', '183.199.181.241', '用户登录', '登录系统后台成功', 'demo', 1616806768, 1616806768);
INSERT INTO `mx_oplog` VALUES (78, 'mxadmin/login/index', '124.114.78.88', '用户登录', '登录系统后台成功', 'demo', 1616807236, 1616807236);
INSERT INTO `mx_oplog` VALUES (79, 'mxadmin/login/index', '183.3.155.1', '用户登录', '登录系统后台成功', 'demo', 1616808204, 1616808204);
INSERT INTO `mx_oplog` VALUES (80, 'mxadmin/login/index', '61.142.235.160', '用户登录', '登录系统后台成功', 'demo', 1616808517, 1616808517);
INSERT INTO `mx_oplog` VALUES (81, 'mxadmin/login/index', '112.9.0.235', '用户登录', '登录系统后台成功', 'demo', 1616809378, 1616809378);
INSERT INTO `mx_oplog` VALUES (82, 'mxadmin/login/index', '220.168.85.110', '用户登录', '登录系统后台成功', 'demo', 1616809407, 1616809407);
INSERT INTO `mx_oplog` VALUES (83, 'mxadmin/login/index', '171.217.20.218', '用户登录', '登录系统后台成功', 'demo', 1616809533, 1616809533);
INSERT INTO `mx_oplog` VALUES (84, 'mxadmin/login/index', '59.51.52.193', '用户登录', '登录系统后台成功', 'demo', 1616809772, 1616809772);
INSERT INTO `mx_oplog` VALUES (85, 'mxadmin/login/index', '112.96.66.96', '用户登录', '登录系统后台成功', 'demo', 1616810978, 1616810978);
INSERT INTO `mx_oplog` VALUES (86, 'mxadmin/login/index', '106.121.160.110', '用户登录', '登录系统后台成功', 'demo', 1616811049, 1616811049);
INSERT INTO `mx_oplog` VALUES (87, 'mxadmin/login/index', '59.41.171.70', '用户登录', '登录系统后台成功', 'demo', 1616811918, 1616811918);
INSERT INTO `mx_oplog` VALUES (88, 'mxadmin/login/index', '222.210.137.86', '用户登录', '登录系统后台成功', 'demo', 1616812876, 1616812876);
INSERT INTO `mx_oplog` VALUES (89, 'mxadmin/login/index', '42.224.255.158', '用户登录', '登录系统后台成功', 'demo', 1616812938, 1616812938);
INSERT INTO `mx_oplog` VALUES (90, 'mxadmin/login/index', '222.90.105.215', '用户登录', '登录系统后台成功', 'demo', 1616812942, 1616812942);
INSERT INTO `mx_oplog` VALUES (91, 'mxadmin/login/index', '182.46.10.127', '用户登录', '登录系统后台成功', 'demo', 1616813254, 1616813254);
INSERT INTO `mx_oplog` VALUES (92, 'mxadmin/login/index', '112.96.48.109', '用户登录', '登录系统后台成功', 'demo', 1616814468, 1616814468);
INSERT INTO `mx_oplog` VALUES (93, 'mxadmin/login/index', '113.90.32.115', '用户登录', '登录系统后台成功', 'demo', 1616814937, 1616814937);
INSERT INTO `mx_oplog` VALUES (94, 'mxadmin/login/index', '218.0.170.221', '用户登录', '登录系统后台成功', 'demo', 1616815946, 1616815946);
INSERT INTO `mx_oplog` VALUES (95, 'mxadmin/login/index', '27.199.192.199', '用户登录', '登录系统后台成功', 'demo', 1616817128, 1616817128);
INSERT INTO `mx_oplog` VALUES (96, 'mxadmin/login/index', '221.231.215.242', '用户登录', '登录系统后台成功', 'demo', 1616817945, 1616817945);
INSERT INTO `mx_oplog` VALUES (97, 'mxadmin/login/index', '118.250.184.83', '用户登录', '登录系统后台成功', 'demo', 1616820005, 1616820005);
INSERT INTO `mx_oplog` VALUES (98, 'mxadmin/login/index', '119.51.236.85', '用户登录', '登录系统后台成功', 'demo', 1616822209, 1616822209);
INSERT INTO `mx_oplog` VALUES (99, 'mxadmin/login/index', '112.24.27.195', '用户登录', '登录系统后台成功', 'demo', 1616822357, 1616822357);
INSERT INTO `mx_oplog` VALUES (100, 'mxadmin/login/index', '27.18.193.240', '用户登录', '登录系统后台成功', 'demo', 1616823168, 1616823168);
INSERT INTO `mx_oplog` VALUES (101, 'mxadmin/login/index', '58.208.230.246', '用户登录', '登录系统后台成功', 'demo', 1616824216, 1616824216);
INSERT INTO `mx_oplog` VALUES (102, 'mxadmin/login/index', '117.93.190.5', '用户登录', '登录系统后台成功', 'demo', 1616824380, 1616824380);
INSERT INTO `mx_oplog` VALUES (103, 'mxadmin/login/index', '27.18.178.244', '用户登录', '登录系统后台成功', 'demo', 1616825341, 1616825341);
INSERT INTO `mx_oplog` VALUES (104, 'mxadmin/login/index', '175.160.148.179', '用户登录', '登录系统后台成功', 'demo', 1616826348, 1616826348);
INSERT INTO `mx_oplog` VALUES (105, 'mxadmin/login/index', '27.210.114.184', '用户登录', '登录系统后台成功', 'demo', 1616826901, 1616826901);
INSERT INTO `mx_oplog` VALUES (106, 'mxadmin/login/index', '115.60.149.1', '用户登录', '登录系统后台成功', 'demo', 1616829131, 1616829131);
INSERT INTO `mx_oplog` VALUES (107, 'mxadmin/login/index', '36.7.159.60', '用户登录', '登录系统后台成功', 'demo', 1616829787, 1616829787);
INSERT INTO `mx_oplog` VALUES (108, 'mxadmin/login/index', '59.46.224.178', '用户登录', '登录系统后台成功', 'demo', 1616829920, 1616829920);
INSERT INTO `mx_oplog` VALUES (109, 'mxadmin/login/index', '220.191.85.100', '用户登录', '登录系统后台成功', 'demo', 1616830584, 1616830584);
INSERT INTO `mx_oplog` VALUES (110, 'mxadmin/login/index', '60.13.131.22', '用户登录', '登录系统后台成功', 'demo', 1616830673, 1616830673);
INSERT INTO `mx_oplog` VALUES (111, 'mxadmin/login/index', '115.209.45.8', '用户登录', '登录系统后台成功', 'demo', 1616831889, 1616831889);
INSERT INTO `mx_oplog` VALUES (112, 'mxadmin/login/index', '112.28.184.137', '用户登录', '登录系统后台成功', 'demo', 1616831966, 1616831966);
INSERT INTO `mx_oplog` VALUES (113, 'mxadmin/login/index', '115.52.157.204', '用户登录', '登录系统后台成功', 'demo', 1616833547, 1616833547);
INSERT INTO `mx_oplog` VALUES (114, 'mxadmin/login/index', '223.154.42.140', '用户登录', '登录系统后台成功', 'demo', 1616837150, 1616837150);
INSERT INTO `mx_oplog` VALUES (115, 'mxadmin/login/index', '27.189.71.57', '用户登录', '登录系统后台成功', 'demo', 1616837387, 1616837387);
INSERT INTO `mx_oplog` VALUES (116, 'mxadmin/login/index', '35.73.178.13', '用户登录', '登录系统后台成功', 'demo', 1616838747, 1616838747);
INSERT INTO `mx_oplog` VALUES (117, 'mxadmin/login/index', '117.28.206.216', '用户登录', '登录系统后台成功', 'demo', 1616839101, 1616839101);
INSERT INTO `mx_oplog` VALUES (118, 'mxadmin/login/index', '117.28.206.216', '用户登录', '登录系统后台成功', 'demo', 1616839335, 1616839335);
INSERT INTO `mx_oplog` VALUES (119, 'mxadmin/login/index', '121.205.250.35', '用户登录', '登录系统后台成功', 'demo', 1616839595, 1616839595);
INSERT INTO `mx_oplog` VALUES (120, 'mxadmin/login/index', '103.57.12.151', '用户登录', '登录系统后台成功', 'demo', 1616839939, 1616839939);
INSERT INTO `mx_oplog` VALUES (121, 'mxadmin/login/index', '116.31.164.165', '用户登录', '登录系统后台成功', 'demo', 1616840009, 1616840009);
INSERT INTO `mx_oplog` VALUES (122, 'mxadmin/login/index', '183.141.44.192', '用户登录', '登录系统后台成功', 'demo', 1616840211, 1616840211);
INSERT INTO `mx_oplog` VALUES (123, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616841280, 1616841280);
INSERT INTO `mx_oplog` VALUES (124, 'mxadmin/login/index', '171.223.113.177', '用户登录', '登录系统后台成功', 'demo', 1616842454, 1616842454);
INSERT INTO `mx_oplog` VALUES (125, 'mxadmin/login/index', '183.12.195.56', '用户登录', '登录系统后台成功', 'demo', 1616845089, 1616845089);
INSERT INTO `mx_oplog` VALUES (126, 'mxadmin/login/index', '1.80.2.64', '用户登录', '登录系统后台成功', 'demo', 1616846403, 1616846403);
INSERT INTO `mx_oplog` VALUES (127, 'mxadmin/login/index', '112.48.51.106', '用户登录', '登录系统后台成功', 'demo', 1616848233, 1616848233);
INSERT INTO `mx_oplog` VALUES (128, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616849512, 1616849512);
INSERT INTO `mx_oplog` VALUES (129, 'mxadmin/login/index', '122.240.214.75', '用户登录', '登录系统后台成功', 'demo', 1616849738, 1616849738);
INSERT INTO `mx_oplog` VALUES (130, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616850358, 1616850358);
INSERT INTO `mx_oplog` VALUES (131, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616850595, 1616850595);
INSERT INTO `mx_oplog` VALUES (132, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616851272, 1616851272);
INSERT INTO `mx_oplog` VALUES (133, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616851330, 1616851330);
INSERT INTO `mx_oplog` VALUES (134, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616851578, 1616851578);
INSERT INTO `mx_oplog` VALUES (135, 'mxadmin/login/index', '223.86.192.111', '用户登录', '登录系统后台成功', 'demo', 1616851888, 1616851888);
INSERT INTO `mx_oplog` VALUES (136, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'demo', 1616852020, 1616852020);
INSERT INTO `mx_oplog` VALUES (137, 'mxadmin/login/index', '175.43.97.122', '用户登录', '登录系统后台成功', 'demo', 1616853781, 1616853781);
INSERT INTO `mx_oplog` VALUES (138, 'mxadmin/login/index', '223.68.58.247', '用户登录', '登录系统后台成功', 'demo', 1616854915, 1616854915);
INSERT INTO `mx_oplog` VALUES (139, 'mxadmin/login/index', '49.70.124.239', '用户登录', '登录系统后台成功', 'demo', 1616855947, 1616855947);
INSERT INTO `mx_oplog` VALUES (140, 'mxadmin/login/index', '113.214.204.31', '用户登录', '登录系统后台成功', 'demo', 1616859097, 1616859097);
INSERT INTO `mx_oplog` VALUES (141, 'mxadmin/login/index', '116.54.59.128', '用户登录', '登录系统后台成功', 'demo', 1616863749, 1616863749);
INSERT INTO `mx_oplog` VALUES (142, 'mxadmin/login/index', '111.225.194.135', '用户登录', '登录系统后台成功', 'demo', 1616864290, 1616864290);
INSERT INTO `mx_oplog` VALUES (143, 'mxadmin/login/index', '111.13.217.250', '用户登录', '登录系统后台成功', 'demo', 1616875990, 1616875990);
INSERT INTO `mx_oplog` VALUES (144, 'mxadmin/login/index', '111.199.184.221', '用户登录', '登录系统后台成功', 'demo', 1616894168, 1616894168);
INSERT INTO `mx_oplog` VALUES (145, 'mxadmin/login/index', '110.81.137.158', '用户登录', '登录系统后台成功', 'demo', 1616895406, 1616895406);
INSERT INTO `mx_oplog` VALUES (146, 'mxadmin/login/index', '27.18.210.145', '用户登录', '登录系统后台成功', 'demo', 1616896237, 1616896237);
INSERT INTO `mx_oplog` VALUES (147, 'mxadmin/login/index', '60.222.231.230', '用户登录', '登录系统后台成功', 'demo', 1616901703, 1616901703);
INSERT INTO `mx_oplog` VALUES (148, 'mxadmin/login/index', '101.29.228.96', '用户登录', '登录系统后台成功', 'demo', 1616903036, 1616903036);
INSERT INTO `mx_oplog` VALUES (149, 'mxadmin/login/index', '183.70.66.122', '用户登录', '登录系统后台成功', 'demo', 1616904782, 1616904782);
INSERT INTO `mx_oplog` VALUES (150, 'mxadmin/login/index', '112.122.189.123', '用户登录', '登录系统后台成功', 'demo', 1616905817, 1616905817);
INSERT INTO `mx_oplog` VALUES (151, 'mxadmin/login/index', '112.9.0.235', '用户登录', '登录系统后台成功', 'demo', 1616907714, 1616907714);
INSERT INTO `mx_oplog` VALUES (152, 'mxadmin/login/index', '120.85.104.217', '用户登录', '登录系统后台成功', 'demo', 1616911938, 1616911938);
INSERT INTO `mx_oplog` VALUES (153, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616913928, 1616913928);
INSERT INTO `mx_oplog` VALUES (154, 'mxadmin/login/index', '222.82.79.26', '用户登录', '登录系统后台成功', 'demo', 1616919787, 1616919787);
INSERT INTO `mx_oplog` VALUES (155, 'mxadmin/login/index', '110.86.173.148', '用户登录', '登录系统后台成功', 'demo', 1616920537, 1616920537);
INSERT INTO `mx_oplog` VALUES (156, 'mxadmin/login/index', '112.49.92.39', '用户登录', '登录系统后台成功', 'zlmlovem', 1616920817, 1616920817);
INSERT INTO `mx_oplog` VALUES (157, 'mxadmin/login/index', '180.142.65.26', '用户登录', '登录系统后台成功', 'demo', 1616923803, 1616923803);

SET FOREIGN_KEY_CHECKS = 1;
