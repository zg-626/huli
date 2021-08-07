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

 Date: 07/08/2021 10:10:31
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
  `login_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `last_login_ip` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '上次登录IP',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '上次登录时间',
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'user_agent',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_admin
-- ----------------------------
INSERT INTO `mx_admin` VALUES (1, 1, 'zlmlovem', 'f4409a70188c35bc1fa5ad6c3b9a1acf', '管理员', '', '', '', '110.90.104.226', 1628223909, 22, '110.90.104.226', 1628051266, NULL, 1, 1606649826, 1628223909);
INSERT INTO `mx_admin` VALUES (2, 0, 'demo', 'e10adc3949ba59abbe56e057f20f883e', '体验账号', '', '', '', '222.175.61.22', 1628296832, 799, '218.86.249.6', 1628178374, NULL, 1, 1606649826, 1628296832);
INSERT INTO `mx_admin` VALUES (18, 0, 'test', 'e10adc3949ba59abbe56e057f20f883e', '测试', '', '', '', NULL, NULL, 0, '', NULL, NULL, 1, 1628062269, 1628179813);

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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_attachment
-- ----------------------------
INSERT INTO `mx_attachment` VALUES (9, 1, '/storage/images/20210714/866dd9f5f575d5512e3c37e5755cd542.jpg', 'jpg', 5143, 'image/jpeg', 'local', 'ebeffddf8c3b92e88edf814056024d767ccf17d8', '', 1626256499, 1626256499);

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
) ENGINE = InnoDB AUTO_INCREMENT = 63 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限角色组关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_auth_group_access
-- ----------------------------
INSERT INTO `mx_auth_group_access` VALUES (52, 1, 0);
INSERT INTO `mx_auth_group_access` VALUES (60, 18, 3);
INSERT INTO `mx_auth_group_access` VALUES (61, 2, 2);
INSERT INTO `mx_auth_group_access` VALUES (62, 2, 3);

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
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_auth_rule
-- ----------------------------
INSERT INTO `mx_auth_rule` VALUES (1, 0, '', '系统配置', 1, 0, 1, 'layui-icon layui-icon-set', 1, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (2, 1, 'mxadmin/configure/index', '参数配置', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (3, 1, 'mxadmin/oplog/index', '日志管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (4, 0, '', '权限管理', 1, 0, 1, 'layui-icon layui-icon-user', 2, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (5, 4, 'mxadmin/admin/index', '用户管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (6, 4, 'mxadmin/role/index', '角色管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (7, 4, 'mxadmin/auth/index', '规则管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (8, 5, 'mxadmin/admin/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (9, 5, 'mxadmin/admin/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (10, 5, 'mxadmin/admin/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (11, 6, 'mxadmin/role/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (12, 6, 'mxadmin/role/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (13, 6, 'mxadmin/role/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (14, 7, 'mxadmin/auth/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (15, 7, 'mxadmin/auth/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (16, 7, 'mxadmin/auth/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (20, 6, 'mxadmin/role/authlist', '权限分配', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (33, 5, 'mxadmin/admin/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (34, 6, 'mxadmin/role/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (35, 7, 'mxadmin/auth/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (38, 3, 'mxadmin/oplog/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (39, 3, 'mxadmin/oplog/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (40, 3, 'mxadmin/oplog/delall', '一键清空', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (41, 1, 'mxadmin/dictionary/index', '字典管理', 1, 0, 1, '', 50, 1, 1, '', 1606650726, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (42, 41, 'mxadmin/dictionary/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (43, 41, 'mxadmin/dictionary/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (44, 41, 'mxadmin/dictionary/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (45, 41, 'mxadmin/dictionary/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (46, 2, 'mxadmin/configure/submit', '保存配置', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (47, 2, 'mxadmin/upload/upload', '文件上传', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (48, 2, 'mxadmin/configure/index', '查看', 1, 1, 0, '', 50, 1, 1, '', 1606650726, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (49, 1, 'mxadmin/attach/index', '附件管理', 1, 0, 1, '', 50, 1, 1, '', 1615371984, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (50, 49, 'mxadmin/attach/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615372015, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (51, 49, 'mxadmin/attach/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615372058, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (52, 0, '', '内容管理', 1, 0, 1, 'layui-icon layui-icon-read', 50, 1, 1, '', 1615794543, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (53, 52, 'cms/category/index', '栏目管理', 1, 0, 1, '', 50, 1, 1, '', 1615794658, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (54, 52, 'cms/article/index', '文章管理', 1, 0, 1, '', 50, 1, 1, '', 1615794716, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (55, 52, 'cms/ad/index', '广告管理', 1, 0, 1, '', 50, 1, 1, '', 1615794778, 1628153299);
INSERT INTO `mx_auth_rule` VALUES (56, 53, 'cms/category/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615795287, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (57, 53, 'cms/category/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615795886, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (58, 53, 'cms/category/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615795916, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (59, 53, 'cms/category/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615795933, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (60, 54, 'cms/article/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615796109, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (61, 54, 'cms/article/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615796135, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (62, 54, 'cms/article/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615796210, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (63, 54, 'cms/article/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615796231, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (64, 55, 'cms/ad/add', '添加', 1, 1, 0, '', 50, 1, 1, '', 1615796258, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (65, 55, 'cms/ad/edit', '修改', 1, 1, 0, '', 50, 1, 1, '', 1615796291, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (66, 55, 'cms/ad/del', '删除', 1, 1, 0, '', 50, 1, 1, '', 1615796364, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (67, 55, 'cms/ad/datalist', '查看', 1, 1, 0, '', 50, 1, 1, '', 1615796568, 1628153296);
INSERT INTO `mx_auth_rule` VALUES (68, 2, 'mxadmin/tpl/password', '修改密码', 1, 1, 0, '', 50, 1, 1, '', 1616748697, 1628153296);

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
INSERT INTO `mx_cms_article` VALUES (1, 4, 1, '开场问候歌1', NULL, 'http://img.zlm.ennn.cn/20210317/d07297ac326ae91c35770e7e26944759.png', '关键词', '描述', '文章作者', '文章来源', 999, '<p><img src=\"http://img.zlm.ennn.cn/20210317/32705271a89708000a0942de3a213a95.png\" alt=\"\" width=\"483\" height=\"364\" /></p>\n<p><img src=\"http://img.zlm.ennn.cn/20210317/4e42dfe546be9c3cd12f7390516e0fa7.png\" /></p>', '', '', 'http://img.zlm.ennn.cn/20210303/7eb51fe4b3105eae2a25f0566bd2434b.mp3', '', 50, 0, 0, 1, 1614765295, 1618908641);

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
INSERT INTO `mx_cms_category` VALUES (1, 0, 1, '幼儿课件', 0, 0, '/list/1.html', '_self', '', '', '', '', 50, 0, 1, '', 1614682895, 1628261364);
INSERT INTO `mx_cms_category` VALUES (2, 0, 2, '成人课件', 0, 0, '/list/2.html', '_self', '', '', '', '', 50, 0, 1, '', 1614690183, 1628261364);
INSERT INTO `mx_cms_category` VALUES (3, 1, 1, '第一节课', 0, 0, '/list/3.html', '_self', '/storage/images/20210319/0698c02e89bbd1de283aa5a1ba33caac.png', '', '', '', 50, 0, 1, '', 1614690191, 1628261364);
INSERT INTO `mx_cms_category` VALUES (4, 3, 1, '开场：问候歌', 0, 0, '/list/4.html', '_self', 'http://img.zlm.ennn.cn/20210315/a68db21bfba341c1b0675782cbada829.png', '1', '2', '3', 50, 1, 1, '', 1614690216, 1628261364);
INSERT INTO `mx_cms_category` VALUES (5, 3, 1, '热身', 0, 0, '/list/5.html', '_self', '/storage/images/20210315/2d200174d1ba4fd7a2a94b466eaac9e9.png', '', '', '', 50, 1, 1, '', 1614690270, 1628261364);
INSERT INTO `mx_cms_category` VALUES (6, 3, 1, '回课', 0, 0, '/list/6.html', '_self', '/storage/images/20210307/3711595428def9590be1dcf86003a75e.png', '', '', '', 50, 1, 1, '', 1614690279, 1628261364);
INSERT INTO `mx_cms_category` VALUES (7, 3, 1, '乐理：乐理知识', 0, 0, '/list/7.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690319, 1628261364);
INSERT INTO `mx_cms_category` VALUES (8, 3, 1, '新课', 0, 0, '/list/8.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690331, 1628261364);
INSERT INTO `mx_cms_category` VALUES (9, 3, 1, '歌唱', 0, 0, '/list/9.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690339, 1628261364);
INSERT INTO `mx_cms_category` VALUES (10, 3, 1, '结束', 0, 0, '/list/10.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690346, 1628261364);
INSERT INTO `mx_cms_category` VALUES (11, 2, 2, '第一节课', 0, 0, '/list/11.html', '_self', '', '', '', '', 50, 0, 1, '', 1614690432, 1628261364);
INSERT INTO `mx_cms_category` VALUES (12, 2, 2, '第二节课', 0, 0, '/list/12.html', '_self', '', '', '', '', 50, 0, 1, '', 1614690439, 1628261364);
INSERT INTO `mx_cms_category` VALUES (13, 11, 2, '开场：问候歌', 0, 0, '/list/13.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690492, 1628261364);
INSERT INTO `mx_cms_category` VALUES (14, 11, 2, '热身', 0, 0, '/list/14.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690498, 1628261364);
INSERT INTO `mx_cms_category` VALUES (15, 11, 2, '回课', 0, 0, '/list/15.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690503, 1628261364);
INSERT INTO `mx_cms_category` VALUES (16, 11, 2, '乐理：乐理知识', 0, 0, '/list/16.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690509, 1628261364);
INSERT INTO `mx_cms_category` VALUES (17, 11, 2, '新课', 0, 0, '/list/17.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690516, 1628261364);
INSERT INTO `mx_cms_category` VALUES (18, 11, 2, '歌唱', 0, 0, '/list/18.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690522, 1628261364);
INSERT INTO `mx_cms_category` VALUES (19, 11, 2, '结束', 0, 0, '/list/19.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690528, 1628261364);
INSERT INTO `mx_cms_category` VALUES (20, 12, 2, '开场：问候歌', 0, 0, '/list/20.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690533, 1628261364);
INSERT INTO `mx_cms_category` VALUES (21, 12, 2, '热身', 0, 0, '/list/21.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690539, 1628261364);
INSERT INTO `mx_cms_category` VALUES (22, 12, 2, '回课', 0, 0, '/list/22.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690546, 1628261364);
INSERT INTO `mx_cms_category` VALUES (23, 12, 2, '乐理：乐理知识', 0, 0, '/list/23.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690553, 1628261364);
INSERT INTO `mx_cms_category` VALUES (24, 12, 2, '新课', 0, 0, '/list/24.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690558, 1628261364);
INSERT INTO `mx_cms_category` VALUES (25, 12, 2, '歌唱', 0, 0, '/list/25.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690565, 1628261364);
INSERT INTO `mx_cms_category` VALUES (26, 12, 2, '结束', 0, 0, '/list/26.html', '_self', '', '', '', '', 50, 1, 1, '', 1614690571, 1628261364);

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
INSERT INTO `mx_config` VALUES (1, 'system', '{\"typename\":\"system\",\"logo\":\"\",\"file\":\"\",\"webname\":\"\",\"domain\":\"\",\"title\":\"\",\"keywords\":\"\",\"description\":\"\",\"beian\":\"\",\"miitbeian\":\"\",\"copyright\":\"\"}');
INSERT INTO `mx_config` VALUES (2, 'storage', '{\"typename\":\"storage\",\"engine\":\"1\",\"accesskey\":\"\",\"secretkey\":\"\",\"bucket\":\"\",\"domain\":\"\"}');
INSERT INTO `mx_config` VALUES (3, 'weixin', NULL);
INSERT INTO `mx_config` VALUES (4, 'wxapp', NULL);
INSERT INTO `mx_config` VALUES (5, 'wxpay', NULL);
INSERT INTO `mx_config` VALUES (6, 'email', '{\"typename\":\"email\",\"username\":\"\",\"fullname\":\"\",\"password\":\"\",\"host\":\"\",\"port\":\"\",\"subject\":\"\",\"body\":\"\",\"notice_email\":\"\"}');

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典项表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1399 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mx_oplog
-- ----------------------------
INSERT INTO `mx_oplog` VALUES (1115, 'mxadmin/login/index', '101.30.69.108', '用户登录', '登录系统后台成功', 'demo', 1625104083, 1625104083);
INSERT INTO `mx_oplog` VALUES (1116, 'mxadmin/login/index', '140.75.171.254', '用户登录', '登录系统后台成功', 'demo', 1625104152, 1625104152);
INSERT INTO `mx_oplog` VALUES (1117, 'mxadmin/login/index', '171.217.164.13', '用户登录', '登录系统后台成功', 'demo', 1625107115, 1625107115);
INSERT INTO `mx_oplog` VALUES (1118, 'mxadmin/login/index', '39.162.129.59', '用户登录', '登录系统后台成功', 'demo', 1625119129, 1625119129);
INSERT INTO `mx_oplog` VALUES (1119, 'mxadmin/login/index', '61.49.68.170', '用户登录', '登录系统后台成功', 'demo', 1625126646, 1625126646);
INSERT INTO `mx_oplog` VALUES (1120, 'mxadmin/login/index', '223.155.47.231', '用户登录', '登录系统后台成功', 'demo', 1625129331, 1625129331);
INSERT INTO `mx_oplog` VALUES (1121, 'mxadmin/login/index', '58.246.143.7', '用户登录', '登录系统后台成功', 'demo', 1625130726, 1625130726);
INSERT INTO `mx_oplog` VALUES (1122, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1625192885, 1625192885);
INSERT INTO `mx_oplog` VALUES (1123, 'mxadmin/login/index', '222.65.225.127', '用户登录', '登录系统后台成功', 'demo', 1625213726, 1625213726);
INSERT INTO `mx_oplog` VALUES (1124, 'mxadmin/login/index', '106.6.61.67', '用户登录', '登录系统后台成功', 'demo', 1625219905, 1625219905);
INSERT INTO `mx_oplog` VALUES (1125, 'mxadmin/login/index', '119.137.53.63', '用户登录', '登录系统后台成功', 'demo', 1625234978, 1625234978);
INSERT INTO `mx_oplog` VALUES (1126, 'mxadmin/login/index', '36.47.138.2', '用户登录', '登录系统后台成功', 'demo', 1625235674, 1625235674);
INSERT INTO `mx_oplog` VALUES (1127, 'mxadmin/login/index', '36.47.138.2', '用户登录', '登录系统后台成功', 'demo', 1625268209, 1625268209);
INSERT INTO `mx_oplog` VALUES (1128, 'mxadmin/login/index', '58.101.194.245', '用户登录', '登录系统后台成功', 'demo', 1625269134, 1625269134);
INSERT INTO `mx_oplog` VALUES (1129, 'mxadmin/login/index', '1.192.179.42', '用户登录', '登录系统后台成功', 'demo', 1625279194, 1625279194);
INSERT INTO `mx_oplog` VALUES (1130, 'mxadmin/login/index', '182.45.107.98', '用户登录', '登录系统后台成功', 'demo', 1625293060, 1625293060);
INSERT INTO `mx_oplog` VALUES (1131, 'mxadmin/login/index', '121.16.135.45', '用户登录', '登录系统后台成功', 'demo', 1625297318, 1625297318);
INSERT INTO `mx_oplog` VALUES (1132, 'mxadmin/login/index', '113.134.75.76', '用户登录', '登录系统后台成功', 'demo', 1625354868, 1625354868);
INSERT INTO `mx_oplog` VALUES (1133, 'mxadmin/login/index', '115.60.89.20', '用户登录', '登录系统后台成功', 'demo', 1625380083, 1625380083);
INSERT INTO `mx_oplog` VALUES (1134, 'mxadmin/login/index', '183.224.118.179', '用户登录', '登录系统后台成功', 'demo', 1625401255, 1625401255);
INSERT INTO `mx_oplog` VALUES (1136, 'mxadmin/login/index', '171.217.164.13', '用户登录', '登录系统后台成功', 'demo', 1625407018, 1625407018);
INSERT INTO `mx_oplog` VALUES (1137, 'mxadmin/login/index', '117.27.207.119', '用户登录', '登录系统后台成功', 'demo', 1625407499, 1625407499);
INSERT INTO `mx_oplog` VALUES (1138, 'mxadmin/login/index', '110.184.142.179', '用户登录', '登录系统后台成功', 'demo', 1625408986, 1625408986);
INSERT INTO `mx_oplog` VALUES (1139, 'mxadmin/login/index', '36.47.137.33', '用户登录', '登录系统后台成功', 'demo', 1625441386, 1625441386);
INSERT INTO `mx_oplog` VALUES (1140, 'mxadmin/login/index', '39.162.129.19', '用户登录', '登录系统后台成功', 'demo', 1625453929, 1625453929);
INSERT INTO `mx_oplog` VALUES (1141, 'mxadmin/login/index', '223.20.86.55', '用户登录', '登录系统后台成功', 'demo', 1625454188, 1625454188);
INSERT INTO `mx_oplog` VALUES (1142, 'mxadmin/login/index', '223.20.86.55', '用户登录', '登录系统后台成功', 'demo', 1625454205, 1625454205);
INSERT INTO `mx_oplog` VALUES (1143, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1625478606, 1625478606);
INSERT INTO `mx_oplog` VALUES (1144, 'mxadmin/login/index', '36.47.143.193', '用户登录', '登录系统后台成功', 'demo', 1625484383, 1625484383);
INSERT INTO `mx_oplog` VALUES (1145, 'mxadmin/login/index', '180.143.154.246', '用户登录', '登录系统后台成功', 'demo', 1625488066, 1625488066);
INSERT INTO `mx_oplog` VALUES (1146, 'mxadmin/login/index', '171.212.45.72', '用户登录', '登录系统后台成功', 'demo', 1625495672, 1625495672);
INSERT INTO `mx_oplog` VALUES (1147, 'mxadmin/login/index', '36.47.143.193', '用户登录', '登录系统后台成功', 'demo', 1625526040, 1625526040);
INSERT INTO `mx_oplog` VALUES (1148, 'mxadmin/login/index', '36.47.143.193', '用户登录', '登录系统后台成功', 'demo', 1625527834, 1625527834);
INSERT INTO `mx_oplog` VALUES (1149, 'mxadmin/login/index', '120.10.25.50', '用户登录', '登录系统后台成功', 'demo', 1625531751, 1625531751);
INSERT INTO `mx_oplog` VALUES (1150, 'mxadmin/login/index', '36.47.143.193', '用户登录', '登录系统后台成功', 'demo', 1625547160, 1625547160);
INSERT INTO `mx_oplog` VALUES (1151, 'mxadmin/login/index', '58.48.158.31', '用户登录', '登录系统后台成功', 'demo', 1625547326, 1625547326);
INSERT INTO `mx_oplog` VALUES (1152, 'mxadmin/login/index', '39.162.129.20', '用户登录', '登录系统后台成功', 'demo', 1625556756, 1625556756);
INSERT INTO `mx_oplog` VALUES (1153, 'mxadmin/login/index', '36.47.143.193', '用户登录', '登录系统后台成功', 'demo', 1625563476, 1625563476);
INSERT INTO `mx_oplog` VALUES (1154, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1625565967, 1625565967);
INSERT INTO `mx_oplog` VALUES (1155, 'mxadmin/login/index', '218.65.194.248', '用户登录', '登录系统后台成功', 'demo', 1625567743, 1625567743);
INSERT INTO `mx_oplog` VALUES (1156, 'mxadmin/login/index', '182.37.51.118', '用户登录', '登录系统后台成功', 'demo', 1625585079, 1625585079);
INSERT INTO `mx_oplog` VALUES (1157, 'mxadmin/login/index', '223.74.196.156', '用户登录', '登录系统后台成功', 'demo', 1625624250, 1625624250);
INSERT INTO `mx_oplog` VALUES (1158, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1625624526, 1625624526);
INSERT INTO `mx_oplog` VALUES (1159, 'mxadmin/login/index', '218.28.42.42', '用户登录', '登录系统后台成功', 'demo', 1625630023, 1625630023);
INSERT INTO `mx_oplog` VALUES (1160, 'mxadmin/login/index', '222.217.62.160', '用户登录', '登录系统后台成功', 'demo', 1625645622, 1625645622);
INSERT INTO `mx_oplog` VALUES (1161, 'mxadmin/login/index', '60.16.218.221', '用户登录', '登录系统后台成功', 'demo', 1625645973, 1625645973);
INSERT INTO `mx_oplog` VALUES (1162, 'mxadmin/login/index', '183.184.22.250', '用户登录', '登录系统后台成功', 'demo', 1625647808, 1625647808);
INSERT INTO `mx_oplog` VALUES (1163, 'mxadmin/login/index', '123.145.221.180', '用户登录', '登录系统后台成功', 'demo', 1625650813, 1625650813);
INSERT INTO `mx_oplog` VALUES (1164, 'mxadmin/login/index', '223.73.9.181', '用户登录', '登录系统后台成功', 'demo', 1625658376, 1625658376);
INSERT INTO `mx_oplog` VALUES (1165, 'mxadmin/login/index', '117.173.131.160', '用户登录', '登录系统后台成功', 'demo', 1625665412, 1625665412);
INSERT INTO `mx_oplog` VALUES (1166, 'mxadmin/login/index', '124.202.203.78', '用户登录', '登录系统后台成功', 'demo', 1625707897, 1625707897);
INSERT INTO `mx_oplog` VALUES (1167, 'mxadmin/login/index', '182.148.53.200', '用户登录', '登录系统后台成功', 'demo', 1625715126, 1625715126);
INSERT INTO `mx_oplog` VALUES (1168, 'mxadmin/login/index', '117.89.133.111', '用户登录', '登录系统后台成功', 'demo', 1625716932, 1625716932);
INSERT INTO `mx_oplog` VALUES (1169, 'mxadmin/login/index', '223.73.9.181', '用户登录', '登录系统后台成功', 'demo', 1625719707, 1625719707);
INSERT INTO `mx_oplog` VALUES (1170, 'mxadmin/login/index', '117.25.106.229', '用户登录', '登录系统后台成功', 'zlmlovem', 1625723380, 1625723380);
INSERT INTO `mx_oplog` VALUES (1171, 'mxadmin/login/index', '39.162.129.20', '用户登录', '登录系统后台成功', 'demo', 1625727956, 1625727956);
INSERT INTO `mx_oplog` VALUES (1172, 'mxadmin/login/index', '223.73.9.181', '用户登录', '登录系统后台成功', 'demo', 1625736609, 1625736609);
INSERT INTO `mx_oplog` VALUES (1173, 'mxadmin/login/index', '183.198.225.117', '用户登录', '登录系统后台成功', 'demo', 1625747997, 1625747997);
INSERT INTO `mx_oplog` VALUES (1174, 'mxadmin/login/index', '219.159.102.248', '用户登录', '登录系统后台成功', 'demo', 1625792560, 1625792560);
INSERT INTO `mx_oplog` VALUES (1175, 'mxadmin/login/index', '223.104.239.4', '用户登录', '登录系统后台成功', 'demo', 1625800133, 1625800133);
INSERT INTO `mx_oplog` VALUES (1176, 'mxadmin/login/index', '183.51.190.31', '用户登录', '登录系统后台成功', 'demo', 1625811619, 1625811619);
INSERT INTO `mx_oplog` VALUES (1177, 'mxadmin/login/index', '110.245.177.186', '用户登录', '登录系统后台成功', 'demo', 1625814967, 1625814967);
INSERT INTO `mx_oplog` VALUES (1178, 'mxadmin/login/index', '117.71.55.120', '用户登录', '登录系统后台成功', 'demo', 1625819762, 1625819762);
INSERT INTO `mx_oplog` VALUES (1179, 'mxadmin/login/index', '110.90.105.253', '用户登录', '登录系统后台成功', 'zlmlovem', 1625821339, 1625821339);
INSERT INTO `mx_oplog` VALUES (1180, 'mxadmin/login/index', '120.43.173.229', '用户登录', '登录系统后台成功', 'demo', 1625823763, 1625823763);
INSERT INTO `mx_oplog` VALUES (1181, 'mxadmin/login/index', '182.246.58.203', '用户登录', '登录系统后台成功', 'demo', 1625836934, 1625836934);
INSERT INTO `mx_oplog` VALUES (1182, 'mxadmin/login/index', '223.104.63.6', '用户登录', '登录系统后台成功', 'demo', 1625839506, 1625839506);
INSERT INTO `mx_oplog` VALUES (1183, 'mxadmin/login/index', '120.244.188.133', '用户登录', '登录系统后台成功', 'demo', 1625842631, 1625842631);
INSERT INTO `mx_oplog` VALUES (1184, 'mxadmin/login/index', '1.207.134.249', '用户登录', '登录系统后台成功', 'demo', 1625889215, 1625889215);
INSERT INTO `mx_oplog` VALUES (1185, 'mxadmin/login/index', '180.126.46.48', '用户登录', '登录系统后台成功', 'demo', 1625896056, 1625896056);
INSERT INTO `mx_oplog` VALUES (1186, 'mxadmin/login/index', '182.47.214.26', '用户登录', '登录系统后台成功', 'demo', 1625901075, 1625901075);
INSERT INTO `mx_oplog` VALUES (1187, 'mxadmin/login/index', '120.239.209.128', '用户登录', '登录系统后台成功', 'demo', 1625904216, 1625904216);
INSERT INTO `mx_oplog` VALUES (1188, 'mxadmin/login/index', '120.239.9.101', '用户登录', '登录系统后台成功', 'demo', 1625916755, 1625916755);
INSERT INTO `mx_oplog` VALUES (1189, 'mxadmin/login/index', '120.239.9.101', '用户登录', '登录系统后台成功', 'demo', 1625917424, 1625917424);
INSERT INTO `mx_oplog` VALUES (1190, 'mxadmin/login/index', '223.104.63.0', '用户登录', '登录系统后台成功', 'demo', 1625961156, 1625961156);
INSERT INTO `mx_oplog` VALUES (1191, 'mxadmin/login/index', '113.101.117.166', '用户登录', '登录系统后台成功', 'demo', 1625962876, 1625962876);
INSERT INTO `mx_oplog` VALUES (1192, 'mxadmin/login/index', '182.47.214.26', '用户登录', '登录系统后台成功', 'demo', 1625983586, 1625983586);
INSERT INTO `mx_oplog` VALUES (1193, 'mxadmin/login/index', '218.10.234.27', '用户登录', '登录系统后台成功', 'demo', 1626000896, 1626000896);
INSERT INTO `mx_oplog` VALUES (1194, 'mxadmin/login/index', '182.47.214.26', '用户登录', '登录系统后台成功', 'demo', 1626012084, 1626012084);
INSERT INTO `mx_oplog` VALUES (1195, 'mxadmin/login/index', '182.120.225.198', '用户登录', '登录系统后台成功', 'demo', 1626017214, 1626017214);
INSERT INTO `mx_oplog` VALUES (1196, 'mxadmin/login/index', '14.152.68.241', '用户登录', '登录系统后台成功', 'demo', 1626040031, 1626040031);
INSERT INTO `mx_oplog` VALUES (1197, 'mxadmin/login/index', '113.101.117.166', '用户登录', '登录系统后台成功', 'demo', 1626047884, 1626047884);
INSERT INTO `mx_oplog` VALUES (1198, 'mxadmin/login/index', '58.48.158.31', '用户登录', '登录系统后台成功', 'demo', 1626048862, 1626048862);
INSERT INTO `mx_oplog` VALUES (1199, 'mxadmin/login/index', '101.29.99.214', '用户登录', '登录系统后台成功', 'demo', 1626055099, 1626055099);
INSERT INTO `mx_oplog` VALUES (1200, 'mxadmin/login/index', '14.152.68.104', '用户登录', '登录系统后台成功', 'demo', 1626062649, 1626062649);
INSERT INTO `mx_oplog` VALUES (1201, 'mxadmin/login/index', '101.230.76.35', '用户登录', '登录系统后台成功', 'demo', 1626070446, 1626070446);
INSERT INTO `mx_oplog` VALUES (1202, 'mxadmin/login/index', '58.211.149.42', '用户登录', '登录系统后台成功', 'demo', 1626070909, 1626070909);
INSERT INTO `mx_oplog` VALUES (1203, 'mxadmin/login/index', '101.230.76.35', '用户登录', '登录系统后台成功', 'demo', 1626071726, 1626071726);
INSERT INTO `mx_oplog` VALUES (1204, 'mxadmin/login/index', '113.248.148.151', '用户登录', '登录系统后台成功', 'demo', 1626075768, 1626075768);
INSERT INTO `mx_oplog` VALUES (1205, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1626078531, 1626078531);
INSERT INTO `mx_oplog` VALUES (1206, 'mxadmin/login/index', '110.90.105.253', '用户登录', '登录系统后台成功', 'zlmlovem', 1626079098, 1626079098);
INSERT INTO `mx_oplog` VALUES (1207, 'mxadmin/login/index', '171.120.218.102', '用户登录', '登录系统后台成功', 'demo', 1626082870, 1626082870);
INSERT INTO `mx_oplog` VALUES (1208, 'mxadmin/login/index', '124.200.54.126', '用户登录', '登录系统后台成功', 'demo', 1626144495, 1626144495);
INSERT INTO `mx_oplog` VALUES (1209, 'mxadmin/login/index', '1.198.232.216', '用户登录', '登录系统后台成功', 'demo', 1626147078, 1626147078);
INSERT INTO `mx_oplog` VALUES (1210, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1626158858, 1626158858);
INSERT INTO `mx_oplog` VALUES (1211, 'mxadmin/login/index', '110.90.105.253', '用户登录', '登录系统后台成功', 'zlmlovem', 1626162865, 1626162865);
INSERT INTO `mx_oplog` VALUES (1212, 'mxadmin/login/index', '211.161.240.106', '用户登录', '登录系统后台成功', 'demo', 1626172997, 1626172997);
INSERT INTO `mx_oplog` VALUES (1213, 'mxadmin/login/index', '182.37.14.198', '用户登录', '登录系统后台成功', 'demo', 1626225364, 1626225364);
INSERT INTO `mx_oplog` VALUES (1214, 'mxadmin/login/index', '183.191.206.23', '用户登录', '登录系统后台成功', 'demo', 1626228668, 1626228668);
INSERT INTO `mx_oplog` VALUES (1215, 'mxadmin/login/index', '110.90.105.253', '用户登录', '登录系统后台成功', 'zlmlovem', 1626229021, 1626229021);
INSERT INTO `mx_oplog` VALUES (1216, 'mxadmin/login/index', '115.48.47.86', '用户登录', '登录系统后台成功', 'demo', 1626229848, 1626229848);
INSERT INTO `mx_oplog` VALUES (1217, 'mxadmin/login/index', '115.48.47.86', '用户登录', '登录系统后台成功', 'demo', 1626229848, 1626229848);
INSERT INTO `mx_oplog` VALUES (1218, 'mxadmin/login/index', '115.237.160.50', '用户登录', '登录系统后台成功', 'demo', 1626239146, 1626239146);
INSERT INTO `mx_oplog` VALUES (1219, 'mxadmin/login/index', '110.90.105.253', '用户登录', '登录系统后台成功', 'zlmlovem', 1626245595, 1626245595);
INSERT INTO `mx_oplog` VALUES (1220, 'mxadmin/login/index', '101.66.65.166', '用户登录', '登录系统后台成功', 'demo', 1626258942, 1626258942);
INSERT INTO `mx_oplog` VALUES (1221, 'mxadmin/login/index', '163.125.246.18', '用户登录', '登录系统后台成功', 'demo', 1626276009, 1626276009);
INSERT INTO `mx_oplog` VALUES (1222, 'mxadmin/login/index', '125.77.94.176', '用户登录', '登录系统后台成功', 'demo', 1626320370, 1626320370);
INSERT INTO `mx_oplog` VALUES (1223, 'mxadmin/login/index', '182.35.247.164', '用户登录', '登录系统后台成功', 'demo', 1626330829, 1626330829);
INSERT INTO `mx_oplog` VALUES (1224, 'mxadmin/login/index', '182.139.13.192', '用户登录', '登录系统后台成功', 'demo', 1626333893, 1626333893);
INSERT INTO `mx_oplog` VALUES (1225, 'mxadmin/login/index', '58.48.158.31', '用户登录', '登录系统后台成功', 'demo', 1626342986, 1626342986);
INSERT INTO `mx_oplog` VALUES (1226, 'mxadmin/login/index', '183.132.248.41', '用户登录', '登录系统后台成功', 'demo', 1626358977, 1626358977);
INSERT INTO `mx_oplog` VALUES (1227, 'mxadmin/login/index', '182.120.226.155', '用户登录', '登录系统后台成功', 'demo', 1626359190, 1626359190);
INSERT INTO `mx_oplog` VALUES (1228, 'mxadmin/login/index', '124.227.211.210', '用户登录', '登录系统后台成功', 'demo', 1626369467, 1626369467);
INSERT INTO `mx_oplog` VALUES (1229, 'mxadmin/login/index', '117.151.249.14', '用户登录', '登录系统后台成功', 'demo', 1626375429, 1626375429);
INSERT INTO `mx_oplog` VALUES (1230, 'mxadmin/login/index', '182.35.247.164', '用户登录', '登录系统后台成功', 'demo', 1626399254, 1626399254);
INSERT INTO `mx_oplog` VALUES (1231, 'mxadmin/login/index', '117.30.115.28', '用户登录', '登录系统后台成功', 'demo', 1626402274, 1626402274);
INSERT INTO `mx_oplog` VALUES (1232, 'mxadmin/login/index', '218.11.214.168', '用户登录', '登录系统后台成功', 'demo', 1626402950, 1626402950);
INSERT INTO `mx_oplog` VALUES (1233, 'mxadmin/login/index', '60.174.141.82', '用户登录', '登录系统后台成功', 'demo', 1626421144, 1626421144);
INSERT INTO `mx_oplog` VALUES (1234, 'mxadmin/login/index', '58.48.158.31', '用户登录', '登录系统后台成功', 'demo', 1626421678, 1626421678);
INSERT INTO `mx_oplog` VALUES (1235, 'mxadmin/login/index', '106.6.162.200', '用户登录', '登录系统后台成功', 'demo', 1626421919, 1626421919);
INSERT INTO `mx_oplog` VALUES (1236, 'mxadmin/login/index', '124.89.237.70', '用户登录', '登录系统后台成功', 'demo', 1626422622, 1626422622);
INSERT INTO `mx_oplog` VALUES (1237, 'mxadmin/login/index', '124.89.237.70', '用户登录', '登录系统后台成功', 'demo', 1626423034, 1626423034);
INSERT INTO `mx_oplog` VALUES (1238, 'mxadmin/login/index', '121.206.249.129', '用户登录', '登录系统后台成功', 'demo', 1626449126, 1626449126);
INSERT INTO `mx_oplog` VALUES (1239, 'mxadmin/login/index', '182.45.37.212', '用户登录', '登录系统后台成功', 'demo', 1626482852, 1626482852);
INSERT INTO `mx_oplog` VALUES (1240, 'mxadmin/login/index', '106.6.60.157', '用户登录', '登录系统后台成功', 'demo', 1626493057, 1626493057);
INSERT INTO `mx_oplog` VALUES (1241, 'mxadmin/login/index', '119.137.55.39', '用户登录', '登录系统后台成功', 'demo', 1626502071, 1626502071);
INSERT INTO `mx_oplog` VALUES (1242, 'mxadmin/login/index', '1.192.214.192', '用户登录', '登录系统后台成功', 'demo', 1626503076, 1626503076);
INSERT INTO `mx_oplog` VALUES (1243, 'mxadmin/login/index', '121.206.249.129', '用户登录', '登录系统后台成功', 'demo', 1626532097, 1626532097);
INSERT INTO `mx_oplog` VALUES (1244, 'mxadmin/login/index', '112.115.49.175', '用户登录', '登录系统后台成功', 'demo', 1626541864, 1626541864);
INSERT INTO `mx_oplog` VALUES (1245, 'mxadmin/login/index', '121.206.249.129', '用户登录', '登录系统后台成功', 'demo', 1626620040, 1626620040);
INSERT INTO `mx_oplog` VALUES (1246, 'mxadmin/login/index', '121.237.234.153', '用户登录', '登录系统后台成功', 'demo', 1626658304, 1626658304);
INSERT INTO `mx_oplog` VALUES (1247, 'mxadmin/login/index', '121.237.234.153', '用户登录', '登录系统后台成功', 'zlmlovem', 1626658380, 1626658380);
INSERT INTO `mx_oplog` VALUES (1248, 'mxadmin/login/index', '27.47.6.18', '用户登录', '登录系统后台成功', 'demo', 1626660755, 1626660755);
INSERT INTO `mx_oplog` VALUES (1249, 'mxadmin/login/index', '1.192.214.192', '用户登录', '登录系统后台成功', 'demo', 1626674947, 1626674947);
INSERT INTO `mx_oplog` VALUES (1250, 'mxadmin/login/index', '27.47.5.183', '用户登录', '登录系统后台成功', 'demo', 1626688746, 1626688746);
INSERT INTO `mx_oplog` VALUES (1251, 'mxadmin/login/index', '39.144.190.38', '用户登录', '登录系统后台成功', 'demo', 1626750018, 1626750018);
INSERT INTO `mx_oplog` VALUES (1252, 'mxadmin/login/index', '223.99.20.72', '用户登录', '登录系统后台成功', 'demo', 1626768304, 1626768304);
INSERT INTO `mx_oplog` VALUES (1253, 'mxadmin/login/index', '183.95.63.193', '用户登录', '登录系统后台成功', 'demo', 1626771357, 1626771357);
INSERT INTO `mx_oplog` VALUES (1254, 'mxadmin/login/index', '106.226.57.165', '用户登录', '登录系统后台成功', 'demo', 1626775792, 1626775792);
INSERT INTO `mx_oplog` VALUES (1255, 'mxadmin/login/index', '61.149.239.175', '用户登录', '登录系统后台成功', 'demo', 1626777506, 1626777506);
INSERT INTO `mx_oplog` VALUES (1256, 'mxadmin/login/index', '27.45.9.164', '用户登录', '登录系统后台成功', 'demo', 1626849543, 1626849543);
INSERT INTO `mx_oplog` VALUES (1257, 'mxadmin/login/index', '182.149.163.58', '用户登录', '登录系统后台成功', 'demo', 1626850149, 1626850149);
INSERT INTO `mx_oplog` VALUES (1258, 'mxadmin/login/index', '123.139.16.105', '用户登录', '登录系统后台成功', 'demo', 1626856071, 1626856071);
INSERT INTO `mx_oplog` VALUES (1259, 'mxadmin/login/index', '182.47.224.234', '用户登录', '登录系统后台成功', 'demo', 1626879836, 1626879836);
INSERT INTO `mx_oplog` VALUES (1260, 'mxadmin/login/index', '183.92.40.30', '用户登录', '登录系统后台成功', 'demo', 1626892939, 1626892939);
INSERT INTO `mx_oplog` VALUES (1261, 'mxadmin/login/index', '123.153.30.67', '用户登录', '登录系统后台成功', 'demo', 1626916098, 1626916098);
INSERT INTO `mx_oplog` VALUES (1262, 'mxadmin/login/index', '182.35.247.164', '用户登录', '登录系统后台成功', 'demo', 1626922798, 1626922798);
INSERT INTO `mx_oplog` VALUES (1263, 'mxadmin/login/index', '113.66.32.239', '用户登录', '登录系统后台成功', 'demo', 1626934525, 1626934525);
INSERT INTO `mx_oplog` VALUES (1264, 'mxadmin/login/index', '14.114.37.65', '用户登录', '登录系统后台成功', 'demo', 1626935112, 1626935112);
INSERT INTO `mx_oplog` VALUES (1265, 'mxadmin/login/index', '113.119.59.54', '用户登录', '登录系统后台成功', 'demo', 1626936891, 1626936891);
INSERT INTO `mx_oplog` VALUES (1266, 'mxadmin/login/index', '58.48.158.31', '用户登录', '登录系统后台成功', 'demo', 1626942901, 1626942901);
INSERT INTO `mx_oplog` VALUES (1267, 'mxadmin/login/index', '182.35.247.164', '用户登录', '登录系统后台成功', 'demo', 1626944634, 1626944634);
INSERT INTO `mx_oplog` VALUES (1268, 'mxadmin/login/index', '120.195.118.146', '用户登录', '登录系统后台成功', 'demo', 1626951661, 1626951661);
INSERT INTO `mx_oplog` VALUES (1269, 'mxadmin/login/index', '182.47.224.234', '用户登录', '登录系统后台成功', 'demo', 1626963552, 1626963552);
INSERT INTO `mx_oplog` VALUES (1270, 'mxadmin/login/index', '125.70.162.30', '用户登录', '登录系统后台成功', 'demo', 1626976055, 1626976055);
INSERT INTO `mx_oplog` VALUES (1271, 'mxadmin/login/index', '182.35.247.164', '用户登录', '登录系统后台成功', 'demo', 1627000824, 1627000824);
INSERT INTO `mx_oplog` VALUES (1272, 'mxadmin/login/index', '221.231.29.128', '用户登录', '登录系统后台成功', 'demo', 1627010067, 1627010067);
INSERT INTO `mx_oplog` VALUES (1273, 'mxadmin/login/index', '110.90.105.5', '用户登录', '登录系统后台成功', 'zlmlovem', 1627013873, 1627013873);
INSERT INTO `mx_oplog` VALUES (1274, 'mxadmin/login/index', '221.231.29.128', '用户登录', '登录系统后台成功', 'demo', 1627015382, 1627015382);
INSERT INTO `mx_oplog` VALUES (1275, 'mxadmin/login/index', '39.86.7.108', '用户登录', '登录系统后台成功', 'demo', 1627024660, 1627024660);
INSERT INTO `mx_oplog` VALUES (1276, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627106593, 1627106593);
INSERT INTO `mx_oplog` VALUES (1277, 'mxadmin/login/index', '117.89.133.111', '用户登录', '登录系统后台成功', 'demo', 1627108675, 1627108675);
INSERT INTO `mx_oplog` VALUES (1278, 'mxadmin/login/index', '119.134.202.102', '用户登录', '登录系统后台成功', 'demo', 1627108773, 1627108773);
INSERT INTO `mx_oplog` VALUES (1279, 'mxadmin/login/index', '113.87.136.90', '用户登录', '登录系统后台成功', 'demo', 1627117982, 1627117982);
INSERT INTO `mx_oplog` VALUES (1280, 'mxadmin/login/index', '59.40.9.36', '用户登录', '登录系统后台成功', 'demo', 1627125061, 1627125061);
INSERT INTO `mx_oplog` VALUES (1281, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627136120, 1627136120);
INSERT INTO `mx_oplog` VALUES (1282, 'mxadmin/login/index', '112.233.60.4', '用户登录', '登录系统后台成功', 'demo', 1627139527, 1627139527);
INSERT INTO `mx_oplog` VALUES (1283, 'mxadmin/login/index', '49.113.187.79', '用户登录', '登录系统后台成功', 'demo', 1627145380, 1627145380);
INSERT INTO `mx_oplog` VALUES (1284, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627172948, 1627172948);
INSERT INTO `mx_oplog` VALUES (1285, 'mxadmin/login/index', '110.181.0.147', '用户登录', '登录系统后台成功', 'demo', 1627181971, 1627181971);
INSERT INTO `mx_oplog` VALUES (1286, 'mxadmin/login/index', '110.181.0.147', '用户登录', '登录系统后台成功', 'demo', 1627183707, 1627183707);
INSERT INTO `mx_oplog` VALUES (1287, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627189770, 1627189770);
INSERT INTO `mx_oplog` VALUES (1288, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627193637, 1627193637);
INSERT INTO `mx_oplog` VALUES (1289, 'mxadmin/login/index', '171.37.3.233', '用户登录', '登录系统后台成功', 'demo', 1627218434, 1627218434);
INSERT INTO `mx_oplog` VALUES (1290, 'mxadmin/login/index', '117.65.190.153', '用户登录', '登录系统后台成功', 'demo', 1627222311, 1627222311);
INSERT INTO `mx_oplog` VALUES (1291, 'mxadmin/login/index', '113.101.117.58', '用户登录', '登录系统后台成功', 'demo', 1627260394, 1627260394);
INSERT INTO `mx_oplog` VALUES (1292, 'mxadmin/login/index', '167.179.60.198', '用户登录', '登录系统后台成功', 'demo', 1627270178, 1627270178);
INSERT INTO `mx_oplog` VALUES (1293, 'mxadmin/login/index', '123.133.101.121', '用户登录', '登录系统后台成功', 'demo', 1627278633, 1627278633);
INSERT INTO `mx_oplog` VALUES (1294, 'mxadmin/login/index', '183.185.218.146', '用户登录', '登录系统后台成功', 'demo', 1627283395, 1627283395);
INSERT INTO `mx_oplog` VALUES (1295, 'mxadmin/login/index', '42.231.252.103', '用户登录', '登录系统后台成功', 'demo', 1627349863, 1627349863);
INSERT INTO `mx_oplog` VALUES (1296, 'mxadmin/login/index', '116.54.67.204', '用户登录', '登录系统后台成功', 'demo', 1627370005, 1627370005);
INSERT INTO `mx_oplog` VALUES (1297, 'mxadmin/login/index', '222.217.63.108', '用户登录', '登录系统后台成功', 'demo', 1627370449, 1627370449);
INSERT INTO `mx_oplog` VALUES (1298, 'mxadmin/login/index', '113.134.79.138', '用户登录', '登录系统后台成功', 'demo', 1627374110, 1627374110);
INSERT INTO `mx_oplog` VALUES (1299, 'mxadmin/login/index', '221.2.232.58', '用户登录', '登录系统后台成功', 'demo', 1627374117, 1627374117);
INSERT INTO `mx_oplog` VALUES (1300, 'mxadmin/login/index', '125.92.188.61', '用户登录', '登录系统后台成功', 'demo', 1627411224, 1627411224);
INSERT INTO `mx_oplog` VALUES (1301, 'mxadmin/login/index', '113.134.79.223', '用户登录', '登录系统后台成功', 'demo', 1627427739, 1627427739);
INSERT INTO `mx_oplog` VALUES (1302, 'mxadmin/login/index', '59.42.86.141', '用户登录', '登录系统后台成功', 'demo', 1627441581, 1627441581);
INSERT INTO `mx_oplog` VALUES (1303, 'mxadmin/login/index', '58.49.124.40', '用户登录', '登录系统后台成功', 'demo', 1627463325, 1627463325);
INSERT INTO `mx_oplog` VALUES (1304, 'mxadmin/login/index', '112.0.180.38', '用户登录', '登录系统后台成功', 'demo', 1627475172, 1627475172);
INSERT INTO `mx_oplog` VALUES (1305, 'mxadmin/login/index', '112.42.247.131', '用户登录', '登录系统后台成功', 'demo', 1627479069, 1627479069);
INSERT INTO `mx_oplog` VALUES (1306, 'mxadmin/login/index', '42.228.230.67', '用户登录', '登录系统后台成功', 'demo', 1627483378, 1627483378);
INSERT INTO `mx_oplog` VALUES (1307, 'mxadmin/login/index', '36.37.197.142', '用户登录', '登录系统后台成功', 'demo', 1627490664, 1627490664);
INSERT INTO `mx_oplog` VALUES (1308, 'mxadmin/login/index', '113.134.79.223', '用户登录', '登录系统后台成功', 'demo', 1627516374, 1627516374);
INSERT INTO `mx_oplog` VALUES (1309, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627525132, 1627525132);
INSERT INTO `mx_oplog` VALUES (1310, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627530933, 1627530933);
INSERT INTO `mx_oplog` VALUES (1311, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627532364, 1627532364);
INSERT INTO `mx_oplog` VALUES (1312, 'mxadmin/login/index', '61.166.221.34', '用户登录', '登录系统后台成功', 'demo', 1627540161, 1627540161);
INSERT INTO `mx_oplog` VALUES (1313, 'mxadmin/login/index', '58.48.192.44', '用户登录', '登录系统后台成功', 'demo', 1627544894, 1627544894);
INSERT INTO `mx_oplog` VALUES (1314, 'mxadmin/login/index', '101.85.194.242', '用户登录', '登录系统后台成功', 'demo', 1627545922, 1627545922);
INSERT INTO `mx_oplog` VALUES (1315, 'mxadmin/login/index', '119.1.33.151', '用户登录', '登录系统后台成功', 'demo', 1627546764, 1627546764);
INSERT INTO `mx_oplog` VALUES (1316, 'mxadmin/login/index', '113.120.121.170', '用户登录', '登录系统后台成功', 'demo', 1627546975, 1627546975);
INSERT INTO `mx_oplog` VALUES (1317, 'mxadmin/login/index', '144.0.45.198', '用户登录', '登录系统后台成功', 'demo', 1627547076, 1627547076);
INSERT INTO `mx_oplog` VALUES (1318, 'mxadmin/login/index', '112.207.61.113', '用户登录', '登录系统后台成功', 'demo', 1627547452, 1627547452);
INSERT INTO `mx_oplog` VALUES (1319, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627547612, 1627547612);
INSERT INTO `mx_oplog` VALUES (1320, 'mxadmin/login/index', '27.202.34.243', '用户登录', '登录系统后台成功', 'demo', 1627547747, 1627547747);
INSERT INTO `mx_oplog` VALUES (1321, 'mxadmin/login/index', '113.120.123.133', '用户登录', '登录系统后台成功', 'demo', 1627547819, 1627547819);
INSERT INTO `mx_oplog` VALUES (1322, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627606792, 1627606792);
INSERT INTO `mx_oplog` VALUES (1323, 'mxadmin/login/index', '121.34.48.130', '用户登录', '登录系统后台成功', 'demo', 1627611266, 1627611266);
INSERT INTO `mx_oplog` VALUES (1324, 'mxadmin/login/index', '111.202.78.18', '用户登录', '登录系统后台成功', 'demo', 1627618481, 1627618481);
INSERT INTO `mx_oplog` VALUES (1325, 'mxadmin/login/index', '223.102.46.238', '用户登录', '登录系统后台成功', 'demo', 1627632585, 1627632585);
INSERT INTO `mx_oplog` VALUES (1326, 'mxadmin/login/index', '106.35.203.166', '用户登录', '登录系统后台成功', 'demo', 1627650496, 1627650496);
INSERT INTO `mx_oplog` VALUES (1327, 'mxadmin/login/index', '111.23.203.90', '用户登录', '登录系统后台成功', 'demo', 1627652461, 1627652461);
INSERT INTO `mx_oplog` VALUES (1328, 'mxadmin/login/index', '183.92.40.30', '用户登录', '登录系统后台成功', 'demo', 1627661663, 1627661663);
INSERT INTO `mx_oplog` VALUES (1329, 'mxadmin/login/index', '111.165.115.0', '用户登录', '登录系统后台成功', 'demo', 1627669327, 1627669327);
INSERT INTO `mx_oplog` VALUES (1330, 'mxadmin/login/index', '171.104.153.23', '用户登录', '登录系统后台成功', 'demo', 1627694409, 1627694409);
INSERT INTO `mx_oplog` VALUES (1331, 'mxadmin/login/index', '171.81.129.226', '用户登录', '登录系统后台成功', 'demo', 1627698648, 1627698648);
INSERT INTO `mx_oplog` VALUES (1332, 'mxadmin/login/index', '1.26.127.214', '用户登录', '登录系统后台成功', 'demo', 1627700236, 1627700236);
INSERT INTO `mx_oplog` VALUES (1333, 'mxadmin/login/index', '117.22.100.86', '用户登录', '登录系统后台成功', 'demo', 1627702287, 1627702287);
INSERT INTO `mx_oplog` VALUES (1334, 'mxadmin/login/index', '123.139.95.176', '用户登录', '登录系统后台成功', 'demo', 1627712715, 1627712715);
INSERT INTO `mx_oplog` VALUES (1335, 'mxadmin/login/index', '39.86.7.108', '用户登录', '登录系统后台成功', 'demo', 1627721145, 1627721145);
INSERT INTO `mx_oplog` VALUES (1336, 'mxadmin/login/index', '123.139.44.91', '用户登录', '登录系统后台成功', 'demo', 1627735408, 1627735408);
INSERT INTO `mx_oplog` VALUES (1337, 'mxadmin/login/index', '113.117.221.102', '用户登录', '登录系统后台成功', 'demo', 1627737232, 1627737232);
INSERT INTO `mx_oplog` VALUES (1338, 'mxadmin/login/index', '113.117.221.102', '用户登录', '登录系统后台成功', 'demo', 1627744761, 1627744761);
INSERT INTO `mx_oplog` VALUES (1339, 'mxadmin/login/index', '106.108.24.138', '用户登录', '登录系统后台成功', 'demo', 1627799353, 1627799353);
INSERT INTO `mx_oplog` VALUES (1340, 'mxadmin/login/index', '124.135.243.241', '用户登录', '登录系统后台成功', 'demo', 1627806323, 1627806323);
INSERT INTO `mx_oplog` VALUES (1341, 'mxadmin/login/index', '106.117.98.219', '用户登录', '登录系统后台成功', 'demo', 1627828006, 1627828006);
INSERT INTO `mx_oplog` VALUES (1342, 'mxadmin/login/index', '111.121.79.206', '用户登录', '登录系统后台成功', 'demo', 1627868231, 1627868231);
INSERT INTO `mx_oplog` VALUES (1343, 'mxadmin/login/index', '110.84.205.169', '用户登录', '登录系统后台成功', 'demo', 1627871493, 1627871493);
INSERT INTO `mx_oplog` VALUES (1344, 'mxadmin/login/index', '124.193.76.34', '用户登录', '登录系统后台成功', 'demo', 1627873881, 1627873881);
INSERT INTO `mx_oplog` VALUES (1345, 'mxadmin/login/index', '119.123.0.25', '用户登录', '登录系统后台成功', 'demo', 1627875271, 1627875271);
INSERT INTO `mx_oplog` VALUES (1346, 'mxadmin/login/index', '61.183.156.2', '用户登录', '登录系统后台成功', 'demo', 1627877920, 1627877920);
INSERT INTO `mx_oplog` VALUES (1347, 'mxadmin/login/index', '111.121.74.20', '用户登录', '登录系统后台成功', 'demo', 1627884249, 1627884249);
INSERT INTO `mx_oplog` VALUES (1348, 'mxadmin/login/index', '183.215.168.206', '用户登录', '登录系统后台成功', 'demo', 1627894747, 1627894747);
INSERT INTO `mx_oplog` VALUES (1349, 'mxadmin/login/index', '111.121.74.20', '用户登录', '登录系统后台成功', 'demo', 1627896760, 1627896760);
INSERT INTO `mx_oplog` VALUES (1350, 'mxadmin/login/index', '60.165.39.3', '用户登录', '登录系统后台成功', 'demo', 1627897812, 1627897812);
INSERT INTO `mx_oplog` VALUES (1351, 'mxadmin/login/index', '58.58.64.154', '用户登录', '登录系统后台成功', 'demo', 1627900358, 1627900358);
INSERT INTO `mx_oplog` VALUES (1352, 'mxadmin/login/index', '58.40.162.50', '用户登录', '登录系统后台成功', 'demo', 1627908851, 1627908851);
INSERT INTO `mx_oplog` VALUES (1353, 'mxadmin/login/index', '112.231.117.15', '用户登录', '登录系统后台成功', 'demo', 1627912771, 1627912771);
INSERT INTO `mx_oplog` VALUES (1354, 'mxadmin/login/index', '218.86.248.203', '用户登录', '登录系统后台成功', 'demo', 1627924125, 1627924125);
INSERT INTO `mx_oplog` VALUES (1355, 'mxadmin/login/index', '49.80.224.173', '用户登录', '登录系统后台成功', 'demo', 1627926362, 1627926362);
INSERT INTO `mx_oplog` VALUES (1356, 'mxadmin/login/index', '117.136.75.214', '用户登录', '登录系统后台成功', 'zlmlovem', 1627955096, 1627955096);
INSERT INTO `mx_oplog` VALUES (1357, 'mxadmin/login/index', '61.52.18.202', '用户登录', '登录系统后台成功', 'demo', 1627957873, 1627957873);
INSERT INTO `mx_oplog` VALUES (1358, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1627958722, 1627958722);
INSERT INTO `mx_oplog` VALUES (1359, 'mxadmin/login/index', '120.212.173.0', '用户登录', '登录系统后台成功', 'demo', 1627960876, 1627960876);
INSERT INTO `mx_oplog` VALUES (1360, 'mxadmin/login/index', '118.114.166.2', '用户登录', '登录系统后台成功', 'demo', 1627961296, 1627961296);
INSERT INTO `mx_oplog` VALUES (1361, 'mxadmin/login/index', '114.216.124.93', '用户登录', '登录系统后台成功', 'demo', 1627962165, 1627962165);
INSERT INTO `mx_oplog` VALUES (1362, 'mxadmin/login/index', '111.121.74.20', '用户登录', '登录系统后台成功', 'demo', 1627962495, 1627962495);
INSERT INTO `mx_oplog` VALUES (1363, 'mxadmin/login/index', '180.125.104.56', '用户登录', '登录系统后台成功', 'demo', 1627964970, 1627964970);
INSERT INTO `mx_oplog` VALUES (1364, 'mxadmin/login/index', '113.13.40.250', '用户登录', '登录系统后台成功', 'demo', 1627972967, 1627972967);
INSERT INTO `mx_oplog` VALUES (1365, 'mxadmin/login/index', '119.131.116.34', '用户登录', '登录系统后台成功', 'demo', 1627979715, 1627979715);
INSERT INTO `mx_oplog` VALUES (1366, 'mxadmin/login/index', '180.125.104.56', '用户登录', '登录系统后台成功', 'demo', 1627987340, 1627987340);
INSERT INTO `mx_oplog` VALUES (1367, 'mxadmin/login/index', '183.215.168.206', '用户登录', '登录系统后台成功', 'demo', 1628003485, 1628003485);
INSERT INTO `mx_oplog` VALUES (1368, 'mxadmin/login/index', '1.189.121.183', '用户登录', '登录系统后台成功', 'demo', 1628037801, 1628037801);
INSERT INTO `mx_oplog` VALUES (1369, 'mxadmin/login/index', '58.58.64.154', '用户登录', '登录系统后台成功', 'demo', 1628038023, 1628038023);
INSERT INTO `mx_oplog` VALUES (1370, 'mxadmin/login/index', '60.174.141.82', '用户登录', '登录系统后台成功', 'demo', 1628041439, 1628041439);
INSERT INTO `mx_oplog` VALUES (1371, 'mxadmin/login/index', '110.90.104.226', '用户登录', '登录系统后台成功', 'zlmlovem', 1628051266, 1628051266);
INSERT INTO `mx_oplog` VALUES (1372, 'mxadmin/login/index', '182.45.7.64', '用户登录', '登录系统后台成功', 'demo', 1628058962, 1628058962);
INSERT INTO `mx_oplog` VALUES (1373, 'mxadmin/login/index', '125.73.199.31', '用户登录', '登录系统后台成功', 'demo', 1628059333, 1628059333);
INSERT INTO `mx_oplog` VALUES (1374, 'mxadmin/login/index', '117.139.223.5', '用户登录', '登录系统后台成功', 'demo', 1628060069, 1628060069);
INSERT INTO `mx_oplog` VALUES (1375, 'mxadmin/login/index', '110.90.104.226', '用户登录', '登录系统后台成功', 'demo', 1628060981, 1628060981);
INSERT INTO `mx_oplog` VALUES (1376, 'mxadmin/login/index', '110.90.104.226', '用户登录', '登录系统后台成功', 'zlmlovem', 1628062428, 1628062428);
INSERT INTO `mx_oplog` VALUES (1377, 'mxadmin/login/index', '123.8.46.20', '用户登录', '登录系统后台成功', 'demo', 1628063242, 1628063242);
INSERT INTO `mx_oplog` VALUES (1378, 'mxadmin/login/index', '116.233.189.184', '用户登录', '登录系统后台成功', 'demo', 1628063530, 1628063530);
INSERT INTO `mx_oplog` VALUES (1379, 'mxadmin/login/index', '125.73.199.31', '用户登录', '登录系统后台成功', 'demo', 1628066004, 1628066004);
INSERT INTO `mx_oplog` VALUES (1380, 'mxadmin/login/index', '210.22.75.54', '用户登录', '登录系统后台成功', 'demo', 1628070923, 1628070923);
INSERT INTO `mx_oplog` VALUES (1381, 'mxadmin/login/index', '115.224.38.25', '用户登录', '登录系统后台成功', 'demo', 1628082794, 1628082794);
INSERT INTO `mx_oplog` VALUES (1382, 'mxadmin/login/index', '117.151.251.1', '用户登录', '登录系统后台成功', 'demo', 1628083819, 1628083819);
INSERT INTO `mx_oplog` VALUES (1383, 'mxadmin/login/index', '171.212.45.225', '用户登录', '登录系统后台成功', 'demo', 1628092860, 1628092860);
INSERT INTO `mx_oplog` VALUES (1384, 'mxadmin/login/index', '36.63.70.99', '用户登录', '登录系统后台成功', 'demo', 1628097948, 1628097948);
INSERT INTO `mx_oplog` VALUES (1385, 'mxadmin/login/index', '202.109.191.136', '用户登录', '登录系统后台成功', 'demo', 1628130970, 1628130970);
INSERT INTO `mx_oplog` VALUES (1386, 'mxadmin/login/index', '171.212.45.225', '用户登录', '登录系统后台成功', 'demo', 1628153283, 1628153283);
INSERT INTO `mx_oplog` VALUES (1387, 'mxadmin/login/index', '61.166.221.107', '用户登录', '登录系统后台成功', 'demo', 1628163094, 1628163094);
INSERT INTO `mx_oplog` VALUES (1388, 'mxadmin/login/index', '222.216.237.207', '用户登录', '登录系统后台成功', 'demo', 1628173367, 1628173367);
INSERT INTO `mx_oplog` VALUES (1389, 'mxadmin/login/index', '218.86.249.6', '用户登录', '登录系统后台成功', 'demo', 1628178374, 1628178374);
INSERT INTO `mx_oplog` VALUES (1390, 'mxadmin/login/index', '171.212.45.225', '用户登录', '登录系统后台成功', 'demo', 1628216330, 1628216330);
INSERT INTO `mx_oplog` VALUES (1391, 'mxadmin/login/index', '183.224.118.179', '用户登录', '登录系统后台成功', 'demo', 1628219610, 1628219610);
INSERT INTO `mx_oplog` VALUES (1392, 'mxadmin/login/index', '183.215.168.206', '用户登录', '登录系统后台成功', 'demo', 1628222151, 1628222151);
INSERT INTO `mx_oplog` VALUES (1393, 'mxadmin/login/index', '110.90.104.226', '用户登录', '登录系统后台成功', 'zlmlovem', 1628223909, 1628223909);
INSERT INTO `mx_oplog` VALUES (1394, 'mxadmin/login/index', '36.47.137.151', '用户登录', '登录系统后台成功', 'demo', 1628230311, 1628230311);
INSERT INTO `mx_oplog` VALUES (1395, 'mxadmin/login/index', '1.204.62.120', '用户登录', '登录系统后台成功', 'demo', 1628257105, 1628257105);
INSERT INTO `mx_oplog` VALUES (1396, 'mxadmin/login/index', '120.85.97.90', '用户登录', '登录系统后台成功', 'demo', 1628261202, 1628261202);
INSERT INTO `mx_oplog` VALUES (1397, 'mxadmin/login/index', '115.49.21.15', '用户登录', '登录系统后台成功', 'demo', 1628267785, 1628267785);
INSERT INTO `mx_oplog` VALUES (1398, 'mxadmin/login/index', '222.175.61.22', '用户登录', '登录系统后台成功', 'demo', 1628296832, 1628296832);

SET FOREIGN_KEY_CHECKS = 1;
