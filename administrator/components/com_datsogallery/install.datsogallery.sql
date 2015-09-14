CREATE TABLE IF NOT EXISTS `#__datsogallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `imgprice` decimal(10,2) NOT NULL,
  `imgtitle` text NOT NULL,
  `imgauthor` varchar(50) DEFAULT NULL,
  `imgauthorurl` varchar(250) NOT NULL DEFAULT '',
  `imgtext` text NOT NULL,
  `imgdate` varchar(20) DEFAULT NULL,
  `imgcounter` int(11) NOT NULL DEFAULT '0',
  `imgdownloaded` int(11) NOT NULL DEFAULT '0',
  `imgvotes` int(11) NOT NULL DEFAULT '0',
  `imgvotesum` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `imgoriginalname` varchar(50) NOT NULL DEFAULT '',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `approved` int(1) NOT NULL DEFAULT '0',
  `useruploaded` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_basket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `published` char(1) NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_catg` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '0',
  `description` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `published` char(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_comments` (
  `cmtid` int(10) NOT NULL AUTO_INCREMENT,
  `cmtpic` int(10) NOT NULL DEFAULT '0',
  `cmtip` varchar(15) NOT NULL DEFAULT '',
  `cmtname` varchar(20) NOT NULL DEFAULT '',
  `cmtmail` varchar(100) NOT NULL DEFAULT '',
  `cmttext` text NOT NULL,
  `cmtdate` varchar(20) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmtid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `ddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `hdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_purchases` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(9) NOT NULL,
  `image_id` int(7) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_ip` varchar(16) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(64) NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag` varchar(200) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__datsogallery_votes` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `vpic` int(11) NOT NULL DEFAULT '0',
  `vip` varchar(50) NOT NULL DEFAULT '',
  `vdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `vsum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM;