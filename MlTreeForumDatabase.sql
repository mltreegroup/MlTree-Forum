/*
Navicat MySQL Data Transfer

Source Server         : Forum
Source Server Version : 50637
Source Host           : 127.0.0.1:3306
Source Database       : beta_kingsr_cc

Target Server Type    : MYSQL
Target Server Version : 50637
File Encoding         : 65001

Date: 2018-06-25 15:24:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mf_atta
-- ----------------------------
DROP TABLE IF EXISTS `mf_atta`;
CREATE TABLE `mf_atta` (
  `aid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sign` varchar(60) DEFAULT '0' COMMENT '附件标识确保与Topic附加标识一致',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fileName` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `downs` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `isimages` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_atta
-- ----------------------------

-- ----------------------------
-- Table structure for mf_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `mf_auth_rule`;
CREATE TABLE `mf_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_auth_rule
-- ----------------------------
INSERT INTO `mf_auth_rule` VALUES ('1', 'admin', '超级管理', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('2', 'view', '查看帖子', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('3', 'banUser', '封禁用户', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('4', 'move', '移动帖子', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('5', 'down', '下载附件', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('6', 'delete', '删除帖子', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('7', 'comment', '允许回复', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('8', 'create', '允许发帖', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('9', 'top', '置顶帖子', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('10', 'essence', '设置精华', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('11', 'update', '编辑帖子', '1', '1', '');
INSERT INTO `mf_auth_rule` VALUES ('12', 'message', '发送信息', '1', '1', '');

-- ----------------------------
-- Table structure for mf_comment
-- ----------------------------
DROP TABLE IF EXISTS `mf_comment`;
CREATE TABLE `mf_comment` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `likes` int(11) unsigned NOT NULL DEFAULT '0',
  `downs` int(11) unsigned NOT NULL DEFAULT '0',
  `reCid` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '回复id',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_comment
-- ----------------------------

-- ----------------------------
-- Table structure for mf_forum
-- ----------------------------
DROP TABLE IF EXISTS `mf_forum`;
CREATE TABLE `mf_forum` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `cgroup` varchar(100) NOT NULL DEFAULT '0' COMMENT '允许发帖用户组',
  `topics` int(11) unsigned NOT NULL DEFAULT '0',
  `introduce` text NOT NULL COMMENT '介绍',
  `notice` text NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序顺序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` char(60) NOT NULL DEFAULT '',
  `seoDes` varchar(255) NOT NULL DEFAULT '',
  `seoKeywords` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_forum
-- ----------------------------
INSERT INTO `mf_forum` VALUES ('1', '官方发布板块', '1', '2', '官方信息发布板块', '默认板块公告', '0', '0', '', '官方信息发布板块', '官方');

-- ----------------------------
-- Table structure for mf_group
-- ----------------------------
DROP TABLE IF EXISTS `mf_group`;
CREATE TABLE `mf_group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupName` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_group
-- ----------------------------
INSERT INTO `mf_group` VALUES ('1', '管理员', '1', '1,2,3,4,5,6,7,8,9,10');
INSERT INTO `mf_group` VALUES ('2', '注册会员', '1', '2,8,11');

-- ----------------------------
-- Table structure for mf_links
-- ----------------------------
DROP TABLE IF EXISTS `mf_links`;
CREATE TABLE `mf_links` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sold` int(11) unsigned NOT NULL DEFAULT '0',
  `title` char(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `picurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_links
-- ----------------------------
INSERT INTO `mf_links` VALUES ('1', '0', '作者博客', 'https://blog.mltree.top', 'https://cn.gravatar.com/avatar/dce77d27fc8bd42ef671230baf5795a8?s=64&d=mm&r=g');
INSERT INTO `mf_links` VALUES ('2', '1', '十载北林SkyDrive', 'https://pan.kingsr.cc', 'https://pan.kingsr.cc/static/img/logo_s.png');

-- ----------------------------
-- Table structure for mf_message
-- ----------------------------
DROP TABLE IF EXISTS `mf_message`;
CREATE TABLE `mf_message` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '发送者uid',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `userName` varchar(20) NOT NULL,
  `toUid` int(11) unsigned NOT NULL COMMENT '目标uid',
  `title` varchar(30) DEFAULT NULL,
  `content` text NOT NULL COMMENT '消息内容',
  `status` int(1) unsigned zerofill NOT NULL DEFAULT '0' COMMENT '是否已阅0未阅1已阅',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_message
-- ----------------------------

-- ----------------------------
-- Table structure for mf_options
-- ----------------------------
DROP TABLE IF EXISTS `mf_options`;
CREATE TABLE `mf_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `option_name` (`name`(10))
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_options
-- ----------------------------
INSERT INTO `mf_options` VALUES ('1', 'defaulegroup', '2', 'reg');
INSERT INTO `mf_options` VALUES ('2', 'fromName', 'MlTree Forum', 'email');
INSERT INTO `mf_options` VALUES ('3', 'fromAdress', 'forum@admin.com', 'email');
INSERT INTO `mf_options` VALUES ('4', 'smtpHost', 'smtp.mxhichina.com', 'email');
INSERT INTO `mf_options` VALUES ('5', 'smtpPort', '25', 'email');
INSERT INTO `mf_options` VALUES ('6', 'replyTo', 'forum@admin.com', 'email');
INSERT INTO `mf_options` VALUES ('7', 'smtpUser', 'forum@admin.com', 'email');
INSERT INTO `mf_options` VALUES ('8', 'smtpPass', 'admin', 'email');
INSERT INTO `mf_options` VALUES ('9', 'encriptionType', 'no', 'email');
INSERT INTO `mf_options` VALUES ('10', 'siteTitle', 'MlTree Forum', 'base');
INSERT INTO `mf_options` VALUES ('11', 'siteDes', '本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。', 'base');
INSERT INTO `mf_options` VALUES ('12', 'siteKeywords', 'MlTree Forum PHP 开源 轻论坛 轻社区 Material Design Thinkphp', 'base');
INSERT INTO `mf_options` VALUES ('13', 'forumNum', '25', 'forum');
INSERT INTO `mf_options` VALUES ('14', 'siteFooterJs', '', 'base');
INSERT INTO `mf_options` VALUES ('15', 'commentNum', '10', 'forum');
INSERT INTO `mf_options` VALUES ('16', 'regStatus', '1', 'reg');
INSERT INTO `mf_options` VALUES ('17', 'regMail', '1', 'reg');
INSERT INTO `mf_options` VALUES ('18', 'reg_mail_content', '<!DOCTYPE html>\r\n<html>\r\n\r\n  <head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum邮件模板</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n  </head>\r\n\r\n  <body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-9 mdui-center mdui-text-center\">\r\n      <div class=\"mdui-card\">\r\n\r\n        <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n        <div class=\"mdui-card-media\">\r\n          <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n        </div>\r\n\r\n        <!-- 卡片的标题和副标题 -->\r\n        <div class=\"mdui-card-primary\">\r\n          <div class=\"mdui-card-primary-title\">注册{siteTitle}账户</div>\r\n          <div class=\"mdui-card-primary-subtitle\">Welcome</div>\r\n        </div>\r\n\r\n        <!-- 卡片的内容 -->\r\n        <div class=\"mdui-card-content\">\r\n          亲爱的用户：\r\n          <br/> 您正在注册{siteTitle}。\r\n          <br/>\r\n          您的验证码为：{code}\r\n        </div>\r\n\r\n      </div>\r\n    </div>\r\n\r\n  </body>\r\n\r\n</html>', 'mailTemplate');
INSERT INTO `mf_options` VALUES ('19', 'reset_mail_content', '<!DOCTYPE html>\r\n<html>\r\n\r\n  <head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum邮件模板</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n  </head>\r\n\r\n  <body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-7 dui-center mdui-text-center\">\r\n      <div class=\"mdui-card\">\r\n\r\n        <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n        <div class=\"mdui-card-media\">\r\n          <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n        </div>\r\n\r\n        <!-- 卡片的标题和副标题 -->\r\n        <div class=\"mdui-card-primary\">\r\n          <div class=\"mdui-card-primary-title\">找回{siteTitle}账户</div>\r\n          <div class=\"mdui-card-primary-subtitle\">Welcome</div>\r\n        </div>\r\n\r\n        <!-- 卡片的内容 -->\r\n        <div class=\"mdui-card-content\">\r\n          亲爱的{userName}：\r\n          <br/> 您申请了找回账户，\r\n          <br/>您的验证码为 <code>{code}</code>\r\n        </div>\r\n\r\n      </div>\r\n    </div>\r\n\r\n  </body>\r\n\r\n</html>', 'mailTemplate');
INSERT INTO `mf_options` VALUES ('20', 'siteStatus', '1', 'base');
INSERT INTO `mf_options` VALUES ('21', 'reg_mail_title', '{siteTitle} 激活邮件', 'mailTemplate');
INSERT INTO `mf_options` VALUES ('22', 'notice', '欢迎来到MlTree Forum<br>本站是 MlTree Forum 论坛社区产品的测试站点。<br/>如果你需要测试登录等功能请使用以下账号密码：<br/>账号：<code>admin@admin.com</code><br/>密码：<code>admin</code><br/>我们会不定期检查大家测试内容是否违规，发布违规信息者，封IP。我们不欢迎你使用本程序。谢谢。', 'base');
INSERT INTO `mf_options` VALUES ('23', 'full', '1', 'base');
INSERT INTO `mf_options` VALUES ('24', 'editor', '1', 'forum');
INSERT INTO `mf_options` VALUES ('26', 'closeContent', '站点正在进行闭站维护…… <br/>预计一小时后完成。', 'base');
INSERT INTO `mf_options` VALUES ('27', 'siteIcp', '', 'base');
INSERT INTO `mf_options` VALUES ('29', 'golink', '1', 'base');
INSERT INTO `mf_options` VALUES ('31', 'allowQQreg', '1', 'reg');
INSERT INTO `mf_options` VALUES ('32', 'themePrimary', 'cyan', 'theme');
INSERT INTO `mf_options` VALUES ('33', 'themeAccent', 'pink', 'theme');
INSERT INTO `mf_options` VALUES ('34', 'themeLayout', 'light', 'theme');
INSERT INTO `mf_options` VALUES ('35', 'discolour', 'true', 'theme');

-- ----------------------------
-- Table structure for mf_topic
-- ----------------------------
DROP TABLE IF EXISTS `mf_topic`;
CREATE TABLE `mf_topic` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(11) unsigned NOT NULL DEFAULT '1',
  `sign` varchar(60) DEFAULT NULL COMMENT '附件标识',
  `userip` char(16) NOT NULL DEFAULT '',
  `subject` char(128) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `content` text NOT NULL,
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `images` int(11) unsigned NOT NULL DEFAULT '0',
  `closed` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否关闭',
  `tops` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `essence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '精华',
  `likes` int(11) unsigned DEFAULT '0' COMMENT '点赞人数',
  PRIMARY KEY (`tid`),
  KEY `fid` (`fid`,`tid`),
  KEY `uid` (`uid`,`userip`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_topic
-- ----------------------------
INSERT INTO `mf_topic` VALUES ('1', '1', '1', 'HxIKCc5WLuRVEfAt71GkBdyTnsJj6l', '127.0.0.1', '欢迎来到MlTreeForum测试站点', '1529586961', '1529898873', '&lt;p&gt;\n&lt;/p&gt;&lt;p&gt;欢迎来到MlTreeForum测试站点。&lt;/p&gt;&lt;p&gt;\n如果你需要测试登录等功能请使用以下账号密码：&lt;br&gt;账号：&lt;code&gt;admin@admin.com&lt;/code&gt;&lt;br&gt;密码：&lt;code&gt;admin&lt;/code&gt;&lt;br&gt;我们会不定期检查大家测试内容是否违规，发布违规信息者，&lt;span style=&quot;color: rgb(194, 79, 74);&quot;&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;&lt;span&gt;封IP&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;。我们不欢迎你使用本程序。谢谢。\n\n&lt;/p&gt;\n\n', '127', '23', '0', '0', '1', '1', '0');

-- ----------------------------
-- Table structure for mf_user
-- ----------------------------
DROP TABLE IF EXISTS `mf_user`;
CREATE TABLE `mf_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '用户组编号',
  `email` char(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(100) NOT NULL DEFAULT '\\static\\images\\user_defaule.png' COMMENT '头像URL',
  `motto` varchar(255) DEFAULT NULL COMMENT '签名',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` char(15) NOT NULL DEFAULT '' COMMENT 'QQ',
  `topics` int(11) NOT NULL DEFAULT '0' COMMENT '发帖数',
  `essence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '精华数',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '回帖数',
  `credits` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `create_ip` char(16) NOT NULL DEFAULT '0' COMMENT '创建时IP',
  `create_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `login_ip` char(16) NOT NULL DEFAULT '0' COMMENT '登录时IP',
  `login_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logins` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `qqconnectId` varchar(32) DEFAULT NULL COMMENT 'QQ互联UserOpenId',
  PRIMARY KEY (`uid`),
  KEY `gid` (`gid`,`username`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mf_user
-- ----------------------------
INSERT INTO `mf_user` VALUES ('1', '1', 'admin@admin.com', 'ADMIN', '$2y$10$jsHsqCP9WrcNQ8SoRLcG/OnH8cOUKEV778fch4JzEwJgvuCB.uljW', '/avatar/20180623/424552835d873109d9570310c6d41f1c.png', null, '', '', '0', '0', '23', '0', '0', '0', '180.139.20.207', '1529893632', '7', '1', null);
