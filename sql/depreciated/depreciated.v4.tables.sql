# Open Translation Engine (OTE)
# Table definitions for depreciated MySql 4.x

CREATE TABLE `ote_task` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `status` enum('OPEN','CLOSED') default NULL,
  `mode` enum('ADD','DEL') default NULL,
  `s_id` int(11) unsigned NOT NULL default '0',
  `s_code` char(3) NOT NULL,
  `s_word` varchar(128) NOT NULL,
  `t_id` int(11) unsigned NOT NULL default '0',
  `t_code` char(3) NOT NULL,
  `t_word` varchar(128) NOT NULL,
  `started` datetime NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL default '0',
  `admin_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `ote_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `level` enum('0','1','5','9') NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_host` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM;

INSERT INTO `ote_user` ( `username`, `password`, `email`, `level`, `created`, `last_login` ) 
VALUES ( 'admin', 'admin', 'admin@localhost', '9', NOW(), NOW() );

CREATE TABLE `ote_word` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `word` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `word` (`word`),
  FULLTEXT KEY `word_2` (`word`)
) ENGINE=MyISAM;

CREATE TABLE `ote_word2word` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `s_id` int(11) unsigned NOT NULL default '0',
  `s_code` char(3) NOT NULL,
  `t_id` int(11) unsigned NOT NULL default '0',
  `t_code` char(3) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `source` (`s_id`,`s_code`,`t_id`,`t_code`)
) ENGINE=MyISAM;

