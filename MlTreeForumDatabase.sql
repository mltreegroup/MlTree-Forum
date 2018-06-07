-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-06-07 19:22:53
-- 服务器版本： 5.6.37-log
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum_kingsr_cc`
--

-- --------------------------------------------------------

--
-- 表的结构 `mf_atta`
--

CREATE TABLE `mf_atta` (
  `aid` int(11) UNSIGNED NOT NULL,
  `tid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `fileName` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `downs` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `isimages` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_atta`
--

-- INSERT INTO `mf_atta` (`aid`, `tid`, `uid`, `fileName`, `url`, `downs`, `isimages`) VALUES
-- (3, 0, 1, '64188802_p0_master1200.jpg', '/uploads/20180226/8b18adb4e7184c17e80f6d2e87fb6f56.jpg', 0, 0),
-- (4, 0, 1, '64188802_p0_master1200.jpg', '/uploads/20180226/96f348ef90e3025be59548c6558860d3.jpg', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mf_auth_rule`
--

CREATE TABLE `mf_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_auth_rule`
--

INSERT INTO `mf_auth_rule` (`id`, `name`, `title`, `type`, `status`, `condition`) VALUES
(1, 'admin', '超级管理', 1, 1, ''),
(2, 'view', '查看帖子', 1, 1, ''),
(3, 'banUser', '封禁用户', 1, 1, ''),
(4, 'move', '移动帖子', 1, 1, ''),
(5, 'down', '下载附件', 1, 1, ''),
(6, 'delete', '删除帖子', 1, 1, ''),
(7, 'comment', '允许回复', 1, 1, ''),
(8, 'create', '允许发帖', 1, 1, ''),
(9, 'top', '置顶帖子', 1, 1, ''),
(10, 'essence', '设置精华', 1, 1, ''),
(11, 'update', '编辑帖子', 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `mf_comment`
--

CREATE TABLE `mf_comment` (
  `cid` int(11) UNSIGNED NOT NULL,
  `tid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `likes` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `downs` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `reCid` int(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mf_forum`
--

CREATE TABLE `mf_forum` (
  `fid` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `cgroup` varchar(100) NOT NULL DEFAULT '0' COMMENT '允许发帖用户组',
  `topics` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `todaytopics` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `todaycomments` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `introduce` text NOT NULL COMMENT '介绍',
  `notic` text NOT NULL,
  `sort` tinytext NOT NULL COMMENT '排序顺序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `icon` char(60) NOT NULL DEFAULT '',
  `seoTitle` varchar(255) NOT NULL DEFAULT '',
  `seoKeywords` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_forum`
--

INSERT INTO `mf_forum` (`fid`, `name`, `cgroup`, `topics`, `todaytopics`, `todaycomments`, `introduce`, `notic`, `sort`, `create_time`, `icon`, `seoTitle`, `seoKeywords`) VALUES
(1, '官方发布板块', '1', 0, 0, 0, '官方信息发布板块', '默认板块公告', '0', 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `mf_group`
--

CREATE TABLE `mf_group` (
  `gid` int(11) UNSIGNED NOT NULL,
  `groupName` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_group`
--

INSERT INTO `mf_group` (`gid`, `groupName`, `status`, `rules`) VALUES
(1, '管理员', 1, '1,2,3,4,5,6,7,8,9,10'),
(2, '注册会员', 1, '2,8');

-- --------------------------------------------------------

--
-- 表的结构 `mf_links`
--

CREATE TABLE `mf_links` (
  `Id` int(11) NOT NULL,
  `sold` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `title` char(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `picurl` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_links`
--

INSERT INTO `mf_links` (`Id`, `sold`, `title`, `url`, `picurl`) VALUES
(1, 0, '作者博客', 'https://blog.mltree.top', 'https://cn.gravatar.com/avatar/dce77d27fc8bd42ef671230baf5795a8?s=64&d=mm&r=g');

-- --------------------------------------------------------

--
-- 表的结构 `mf_options`
--

CREATE TABLE `mf_options` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_options`
--

INSERT INTO `mf_options` (`id`, `name`, `value`, `type`) VALUES
(1, 'defaulegroup', '2', 'reg'),
(2, 'fromName', 'MlTree Forum', 'email'),
(3, 'fromAdress', 'admin@admin.com', 'email'),
(4, 'smtpHost', 'smtp.mxhichina.com', 'email'),
(5, 'smtpPort', '25', 'email'),
(6, 'replyTo', 'admin@admin.com', 'email'),
(7, 'smtpUser', 'admin@admin.com', 'email'),
(8, 'smtpPass', 'admin', 'email'),
(9, 'encriptionType', 'no', 'email'),
(10, 'siteTitle', 'MlTree Forum', 'base'),
(11, 'siteDes', '本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。', 'base'),
(12, 'siteKeywords', 'MlTree Forum PHP 开源 轻论坛 轻社区 Material Design Thinkphp', 'base'),
(13, 'forumNum', '25', 'forum'),
(14, 'siteFooterJs', '<script>\n(function(){\n    var bp = document.createElement(\'script\');\n    var curProtocol = window.location.protocol.split(\':\')[0];\n    if (curProtocol === \'https\') {\n        bp.src = \'https://zz.bdstatic.com/linksubmit/push.js\';\n    }\n    else {\n        bp.src = \'http://push.zhanzhang.baidu.com/push.js\';\n    }\n    var s = document.getElementsByTagName(\"script\")[0];\n    s.parentNode.insertBefore(bp, s);\n})();\n</script>\n', 'base'),
(15, 'commentNum', '10', 'forum'),
(16, 'regStatus', '1', 'reg'),
(17, 'regMail', '1', 'reg'),
(18, 'reg_mail_content', '<!DOCTYPE html>\r\n<html>\r\n\r\n  <head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum邮件模板</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n  </head>\r\n\r\n  <body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-9 mdui-center mdui-text-center\">\r\n      <div class=\"mdui-card\">\r\n\r\n        <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n        <div class=\"mdui-card-media\">\r\n          <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n        </div>\r\n\r\n        <!-- 卡片的标题和副标题 -->\r\n        <div class=\"mdui-card-primary\">\r\n          <div class=\"mdui-card-primary-title\">注册{siteTitle}账户</div>\r\n          <div class=\"mdui-card-primary-subtitle\">Welcome</div>\r\n        </div>\r\n\r\n        <!-- 卡片的内容 -->\r\n        <div class=\"mdui-card-content\">\r\n          亲爱的用户：\r\n          <br/> 您正在注册{siteTitle}。\r\n          <br/>\r\n          您的验证码为：{code}\r\n        </div>\r\n\r\n      </div>\r\n    </div>\r\n\r\n  </body>\r\n\r\n</html>', 'mailTemplate'),
(19, 'mail_template_reset', '<!DOCTYPE html>\r\n<html>\r\n\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <title>MlTreeForum 管理后台</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"keywords\" content=\"MlTreeForum PHP 开源 轻论坛 轻社区 Material Design Thinkphp\" />\r\n    <meta name=\"description\" content=\"本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。\" />\r\n    <meta name=\"author\" content=\"北林\">\r\n    <link rel=\"stylesheet\" href=\"https://cdn.bootcss.com/mdui/0.4.0/css/mdui.min.css\">\r\n    <script src=\"https://cdn.bootcss.com/mdui/0.4.0/js/mdui.min.js\"></script>\r\n</head>\r\n\r\n<body class=\"mdui-theme-primary-pink mdui-theme-accent-pink mdui-center\">\r\n    <div class=\"mdui-col-xs-12 mdui-col-sm-4 mdui-center mdui-text-center\">\r\n        <div class=\"mdui-card\">\r\n\r\n            <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->\r\n            <div class=\"mdui-card-media\">\r\n                <img src=\"https://piccdn.freejishu.com/images/2016/04/04/z5gpqMql.jpg\" height=\"300px\" />\r\n            </div>\r\n\r\n            <!-- 卡片的标题和副标题 -->\r\n            <div class=\"mdui-card-primary\">\r\n                <div class=\"mdui-card-primary-title\">找回{siteTitle}账户</div>\r\n                <div class=\"mdui-card-primary-subtitle\"></div>\r\n            </div>\r\n\r\n            <!-- 卡片的内容 -->\r\n            <div class=\"mdui-card-content\">\r\n                亲爱的{userName}：\r\n                <br/> 您申请了找回账户，请点击下方按钮重置密码。\r\n\r\n                <a href=\"{url}\" class=\"mdui-btn mdui-color-blue\">重置密码</a>\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n\r\n</body>\r\n\r\n</html>', 'mailTemplate'),
(20, 'siteStatus', '1', 'base'),
(21, 'reg_mail_title', '{siteTitle} 激活邮件', 'mailTemplate'),
(22, 'notice', '欢迎来到MlTree Forum<br>本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。<br>程序下载地址：<a href=\"https://forum.mltree.top/topic/3\">MlTree Forum Beta1.0.0</a>', 'base'),
(23, 'full', '1', 'base'),
(24, 'editor', '1', 'forum');

-- --------------------------------------------------------

--
-- 表的结构 `mf_topic`
--

CREATE TABLE `mf_topic` (
  `tid` int(11) UNSIGNED NOT NULL,
  `fid` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `userip` char(16) NOT NULL DEFAULT '',
  `subject` char(128) NOT NULL DEFAULT '',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `content` text NOT NULL,
  `views` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `comment` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数',
  `images` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `closed` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否关闭',
  `tops` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `essence` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '精华',
  `likes` int(11) UNSIGNED DEFAULT '0' COMMENT '点赞人数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mf_user`
--

CREATE TABLE `mf_user` (
  `uid` int(11) UNSIGNED NOT NULL,
  `gid` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户组编号',
  `email` char(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(100) NOT NULL DEFAULT '\\static\\images\\user_defaule.png' COMMENT '头像URL',
  `motto` varchar(255) DEFAULT NULL COMMENT '签名',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` char(15) NOT NULL DEFAULT '' COMMENT 'QQ',
  `topics` int(11) NOT NULL DEFAULT '0' COMMENT '发帖数',
  `essence` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '精华数',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '回帖数',
  `credits` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `golds` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `coupons` int(11) NOT NULL DEFAULT '0' COMMENT '点券',
  `create_ip` char(16) NOT NULL DEFAULT '0' COMMENT '创建时IP',
  `create_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `login_ip` char(16) NOT NULL DEFAULT '0' COMMENT '登录时IP',
  `login_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logins` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mf_user`
--

INSERT INTO `mf_user` (`uid`, `gid`, `email`, `username`, `password`, `avatar`, `motto`, `mobile`, `qq`, `topics`, `essence`, `comments`, `credits`, `golds`, `coupons`, `create_ip`, `create_date`, `login_ip`, `login_date`, `logins`) VALUES
(1, 1, 'admin@admin.com', 'Admin', '$2y$10$AhXiLtn.WWRbA9skrMknrOky20teFzT7r3F8gk/bh0QxuC/3B19RW', '', NULL, '', '', 8, 1, 13, 0, 0, 0, '127.0.0.1', 1519483028, '127.0.0.1', 1528253263, 63);
--
-- Indexes for dumped tables
--

--
-- Indexes for table `mf_atta`
--
ALTER TABLE `mf_atta`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `mf_auth_rule`
--
ALTER TABLE `mf_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `mf_comment`
--
ALTER TABLE `mf_comment`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `mf_forum`
--
ALTER TABLE `mf_forum`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `mf_group`
--
ALTER TABLE `mf_group`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `mf_links`
--
ALTER TABLE `mf_links`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `mf_options`
--
ALTER TABLE `mf_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_name` (`name`(10));

--
-- Indexes for table `mf_topic`
--
ALTER TABLE `mf_topic`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `fid` (`fid`,`tid`),
  ADD KEY `uid` (`uid`,`userip`);

--
-- Indexes for table `mf_user`
--
ALTER TABLE `mf_user`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `gid` (`gid`,`username`),
  ADD KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `mf_atta`
--
ALTER TABLE `mf_atta`
  MODIFY `aid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `mf_auth_rule`
--
ALTER TABLE `mf_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `mf_comment`
--
ALTER TABLE `mf_comment`
  MODIFY `cid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `mf_forum`
--
ALTER TABLE `mf_forum`
  MODIFY `fid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `mf_group`
--
ALTER TABLE `mf_group`
  MODIFY `gid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `mf_links`
--
ALTER TABLE `mf_links`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `mf_options`
--
ALTER TABLE `mf_options`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `mf_topic`
--
ALTER TABLE `mf_topic`
  MODIFY `tid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `mf_user`
--
ALTER TABLE `mf_user`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
