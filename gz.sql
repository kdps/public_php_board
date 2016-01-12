-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `bddoc`;
CREATE TABLE `bddoc` (
  `blamed` bigint(11) NOT NULL DEFAULT '0',
  `voted` bigint(11) NOT NULL DEFAULT '0',
  `dinfo` text COLLATE utf8_bin,
  `srl` bigint(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) COLLATE utf8_bin NOT NULL,
  `content` longtext COLLATE utf8_bin NOT NULL,
  `nick_name` varchar(250) COLLATE utf8_bin NOT NULL,
  `module` varchar(10) COLLATE utf8_bin NOT NULL,
  `regdate` varchar(14) COLLATE utf8_bin NOT NULL,
  `readed` bigint(11) NOT NULL DEFAULT '0',
  `category` bigint(11) NOT NULL DEFAULT '0',
  `srl_bd` bigint(11) NOT NULL,
  PRIMARY KEY (`srl`),
  KEY `regdate` (`regdate`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `bdfiles`;
CREATE TABLE `bdfiles` (
  `origin` varchar(250) COLLATE utf8_bin NOT NULL,
  `target` bigint(20) NOT NULL,
  `files` varchar(250) COLLATE utf8_bin NOT NULL,
  KEY `target` (`target`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `bdlist`;
CREATE TABLE `bdlist` (
  `title` varchar(250) COLLATE utf8_bin NOT NULL,
  `srl` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `bdname` varchar(250) COLLATE utf8_bin NOT NULL,
  `skin` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`srl`),
  UNIQUE KEY `bdname` (`bdname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `bdthumb`;
CREATE TABLE `bdthumb` (
  `srl` bigint(11) NOT NULL AUTO_INCREMENT,
  `target` bigint(11) NOT NULL,
  `files` varchar(250) NOT NULL,
  PRIMARY KEY (`srl`),
  KEY `target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cmt_list`;
CREATE TABLE `cmt_list` (
  `comment_srl` bigint(11) NOT NULL AUTO_INCREMENT,
  `parent_srl` bigint(11) NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  `module` varchar(250) NOT NULL,
  `document_srl` bigint(11) NOT NULL,
  `nick_name` varchar(250) NOT NULL,
  UNIQUE KEY `comment_srl_2` (`comment_srl`),
  KEY `comment_srl` (`comment_srl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `extravars`;
CREATE TABLE `extravars` (
  `srl` bigint(11) NOT NULL AUTO_INCREMENT,
  `var` varchar(250) COLLATE utf8_bin NOT NULL,
  `type` bigint(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_bin NOT NULL,
  `module` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`srl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `mlist`;
CREATE TABLE `mlist` (
  `srl` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(60) COLLATE utf8_bin NOT NULL,
  `nick_name` varchar(40) COLLATE utf8_bin NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `minfo` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`srl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `thumb_index`;
CREATE TABLE `thumb_index` (
  `target` bigint(11) NOT NULL,
  `files` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- 2016-01-12 00:50:02
