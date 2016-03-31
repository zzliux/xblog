-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-03-31 14:49:08
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `xblog`
--
CREATE DATABASE IF NOT EXISTS `xblog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `xblog`;

-- --------------------------------------------------------

--
-- 表的结构 `xblog_articles`
--

DROP TABLE IF EXISTS `xblog_articles`;
CREATE TABLE IF NOT EXISTS `xblog_articles` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `title` text,
  `content` longtext,
  `tags` tinytext,
  `categories` tinytext,
  `date` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `commentStatus` tinyint(1) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 插入之前先把表清空（truncate） `xblog_articles`
--

TRUNCATE TABLE `xblog_articles`;
--
-- 转存表中的数据 `xblog_articles`
--

INSERT INTO `xblog_articles` (`cid`, `uid`, `title`, `content`, `tags`, `categories`, `date`, `status`, `commentStatus`, `priority`) VALUES
(1, 1, 'Hello,World!', '### 这是第一篇文章\n* 你可以之后将它删除', '|', '|未分类|', 1445181273, 1, 1, 0),
(9, 1, 'About Me', '* 这个博客系统为[zzliux](http://www.zzliux.com)所开发\r\n* 其中用到了以下开源项目并感谢其开发者们(排名不分先后)\r\n  * [Parsedown](https://github.com/erusev/parsedown)\r\n  * [highlightjs](https://highlightjs.org/)\r\n * [Editor.md](https://github.com/pandao/editor.md)\r\n  * [Font-Awesome](https://github.com/FortAwesome/Font-Awesome)\r\n\r\n* 事后你可以将该内容改为你自己的介绍', '|', '|未分类|', 1459427468, 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xblog_comments`
--

DROP TABLE IF EXISTS `xblog_comments`;
CREATE TABLE IF NOT EXISTS `xblog_comments` (
  `coid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned DEFAULT '0',
  `date` int(11) unsigned DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT '0',
  `email` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `ua` varchar(200) DEFAULT NULL,
  `content` text,
  `status` tinyint(4) DEFAULT '0',
  `parent` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`coid`),
  KEY `cid` (`cid`),
  KEY `created` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

--
-- 插入之前先把表清空（truncate） `xblog_comments`
--

TRUNCATE TABLE `xblog_comments`;
-- --------------------------------------------------------

--
-- 表的结构 `xblog_options`
--

DROP TABLE IF EXISTS `xblog_options`;
CREATE TABLE IF NOT EXISTS `xblog_options` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 插入之前先把表清空（truncate） `xblog_options`
--

TRUNCATE TABLE `xblog_options`;
--
-- 转存表中的数据 `xblog_options`
--

INSERT INTO `xblog_options` (`key`, `value`) VALUES
('blog_description', ''),
('blog_siteLink', ''),
('blog_theme', ''),
('blog_theme_default_link_css', ''),
('blog_theme_default_link_js', ''),
('blog_title', '');

-- --------------------------------------------------------

--
-- 表的结构 `xblog_userinfo`
--

DROP TABLE IF EXISTS `xblog_userinfo`;
CREATE TABLE IF NOT EXISTS `xblog_userinfo` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `registered` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 插入之前先把表清空（truncate） `xblog_userinfo`
--

TRUNCATE TABLE `xblog_userinfo`;
--
-- 转存表中的数据 `xblog_userinfo`
--

INSERT INTO `xblog_userinfo` (`uid`, `name`, `email`, `password`, `url`, `registered`) VALUES
(1, 'admin', '2333@qq.com', 'a17dc9277ee2eb4a5ef411f3f4813fed', 'http://www.zzliux.com/', 1445100778);
