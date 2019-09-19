create database if not exists location
  charset utf8 collate utf8_general_ci;

use location;

CREATE TABLE `snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(14) CHARACTER SET ascii NOT NULL COMMENT '手机号',
  `longitude` double NOT NULL DEFAULT '0' COMMENT '经度',
  `latitude` double NOT NULL DEFAULT '0' COMMENT '纬度',
  `created_at` datetime NOT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `ip_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL COMMENT 'ip address xxx.xxx.xxx.xxx',
  `mobile` char(11) DEFAULT NULL COMMENT 'mobile number',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
