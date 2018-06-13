# Host: localhost  (Version: 5.5.53)
# Date: 2018-06-13 17:35:08
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "mf_atta"
#

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

#
# Data for table "mf_atta"
#


#
# Structure for table "mf_auth_rule"
#

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

#
# Data for table "mf_auth_rule"
#

INSERT INTO `mf_auth_rule` VALUES (1,'admin','超级管理',1,1,''),(2,'view','查看帖子',1,1,''),(3,'banUser','封禁用户',1,1,''),(4,'move','移动帖子',1,1,''),(5,'down','下载附件',1,1,''),(6,'delete','删除帖子',1,1,''),(7,'comment','允许回复',1,1,''),(8,'create','允许发帖',1,1,''),(9,'top','置顶帖子',1,1,''),(10,'essence','设置精华',1,1,''),(11,'update','编辑帖子',1,1,'');

#
# Structure for table "mf_comment"
#

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

#
# Data for table "mf_comment"
#


#
# Structure for table "mf_forum"
#

DROP TABLE IF EXISTS `mf_forum`;
CREATE TABLE `mf_forum` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `cgroup` varchar(100) NOT NULL DEFAULT '0' COMMENT '允许发帖用户组',
  `topics` int(11) unsigned NOT NULL DEFAULT '0',
  `todaytopics` int(11) unsigned NOT NULL DEFAULT '0',
  `todaycomments` int(11) unsigned NOT NULL DEFAULT '0',
  `introduce` text NOT NULL COMMENT '介绍',
  `notic` text NOT NULL,
  `sort` tinytext NOT NULL COMMENT '排序顺序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` char(60) NOT NULL DEFAULT '',
  `seoTitle` varchar(255) NOT NULL DEFAULT '',
  `seoKeywords` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Data for table "mf_forum"
#

INSERT INTO `mf_forum` VALUES (1,'官方发布板块','1',0,0,0,'官方信息发布板块','默认板块公告','0',0,'','',''),(2,'水漫金山','0',0,0,0,'这里可以进行一些日常交流与讨论。','','0',0,'','','');

#
# Structure for table "mf_group"
#

DROP TABLE IF EXISTS `mf_group`;
CREATE TABLE `mf_group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupName` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Data for table "mf_group"
#

INSERT INTO `mf_group` VALUES (1,'管理员',1,'1,2,3,4,5,6,7,8,9,10'),(2,'注册会员',1,'2,8');

#
# Structure for table "mf_links"
#

DROP TABLE IF EXISTS `mf_links`;
CREATE TABLE `mf_links` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sold` int(11) unsigned NOT NULL DEFAULT '0',
  `title` char(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `picurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Data for table "mf_links"
#

INSERT INTO `mf_links` VALUES (1,0,'作者博客','https://blog.mltree.top','https://cn.gravatar.com/avatar/dce77d27fc8bd42ef671230baf5795a8?s=64&d=mm&r=g'),(2,1,'十载北林SkyDrive','https://pan.kingsr.cc','https://pan.kingsr.cc/static/img/logo_s.png');

#
# Structure for table "mf_options"
#

DROP TABLE IF EXISTS `mf_options`;
CREATE TABLE `mf_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `option_name` (`name`(10))
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

#
# Data for table "mf_options"
#

INSERT INTO `mf_options` VALUES (1,'defaulegroup','2','reg'),(2,'fromName','MlTree Forum','email'),(3,'fromAdress','forum@admin.com','email'),(4,'smtpHost','smtp.mxhichina.com','email'),(5,'smtpPort','25','email'),(6,'replyTo','forum@admin.com','email'),(7,'smtpUser','forum@admin.com','email'),(8,'smtpPass','admin','email'),(9,'encriptionType','no','email'),(10,'siteTitle','MlTree Forum','base'),(11,'siteDes','本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。','base'),(12,'siteKeywords','MlTree Forum PHP 开源 轻论坛 轻社区 Material Design Thinkphp','base'),(13,'forumNum','25','forum'),(14,'siteFooterJs','','base'),(15,'commentNum','10','forum'),(16,'regStatus','1','reg'),(17,'regMail','1','reg'),(18,'reg_mail_content','<!DOCTYPE html>\r\n<html>\r\n\r\n  <head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum邮件模板</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n  </head>\r\n\r\n  <body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-9 mdui-center mdui-text-center\">\r\n      <div class=\"mdui-card\">\r\n\r\n        <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n        <div class=\"mdui-card-media\">\r\n          <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n        </div>\r\n\r\n        <!-- 卡片的标题和副标题 -->\r\n        <div class=\"mdui-card-primary\">\r\n          <div class=\"mdui-card-primary-title\">注册{siteTitle}账户</div>\r\n          <div class=\"mdui-card-primary-subtitle\">Welcome</div>\r\n        </div>\r\n\r\n        <!-- 卡片的内容 -->\r\n        <div class=\"mdui-card-content\">\r\n          亲爱的用户：\r\n          <br/> 您正在注册{siteTitle}。\r\n          <br/>\r\n          您的验证码为：{code}\r\n        </div>\r\n\r\n      </div>\r\n    </div>\r\n\r\n  </body>\r\n\r\n</html>','mailTemplate'),(19,'mail_template_reset','<!DOCTYPE html>\r\n<html>\r\n\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum 管理后台</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n</head>\r\n\r\n<body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-4 mdui-center mdui-text-center\">\r\n        <div class=\"mdui-card\">\r\n\r\n            <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n            <div class=\"mdui-card-media\">\r\n                <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n            </div>\r\n\r\n            <!-- 卡片的标题和副标题 -->\r\n            <div class=\"mdui-card-primary\">\r\n                <div class=\"mdui-card-primary-title\">找回{siteTitle}账户</div>\r\n                <div class=\"mdui-card-primary-subtitle\"></div>\r\n            </div>\r\n\r\n            <!-- 卡片的内容 -->\r\n            <div class=\"mdui-card-content\">\r\n                亲爱的{userName}：\r\n                <br/> 您申请了找回账户，请点击下方按钮重置密码。\r\n\r\n                <a href=\"{url}\" class=\"mdui-btn mdui-color-blue\">重置密码</a>\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n\r\n</body>\r\n\r\n</html>','mailTemplate'),(20,'siteStatus','1','base'),(21,'reg_mail_title','{siteTitle} 激活邮件','mailTemplate'),(22,'notice','欢迎来到MlTree Forum<br>本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。<br>程序下载地址：<a href=\"https://forum.mltree.top/topic/3\">MlTree Forum Beta1.0.0</a>','base'),(23,'full','1','base'),(24,'editor','1','forum'),(26,'closeContent','站点正在进行闭站维护…… <br/>预计一小时后完成。','base'),(27,'siteIcp','','base'),(29,'golink','1','base'),(31,'allowQQreg','1','reg'),(32,'themePrimary','cyan','theme'),(33,'themeAccent','pink','theme'),(34,'themeLayout','light','theme'),(35,'discolour','true','theme');

#
# Structure for table "mf_topic"
#

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "mf_topic"
#


#
# Structure for table "mf_user"
#

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
  `qqconnectId` varchar(32) DEFAULT NULL COMMENT 'QQ互联UserOpenId',
  PRIMARY KEY (`uid`),
  KEY `gid` (`gid`,`username`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "mf_user"
#

INSERT INTO `mf_user` VALUES (1,1,'admin@admin.com','Admin','$2y$10$AhXiLtn.WWRbA9skrMknrOky20teFzT7r3F8gk/bh0QxuC/3B19RW','\\static\\images\\user_defaule.png',NULL,'','',0,0,0,0,'127.0.0.1',0,'127.0.0.1',0,0,NULL);
