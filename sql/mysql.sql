# phpMyAdmin MySQL-Dump
# version 2.2.5
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
# --------------------------------------------------------

#
# Table structures for tables used in Video Tube module
#

CREATE TABLE vp_videos (
  id int(5) unsigned NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  uid int(11) NOT NULL default '0',
  pub int(1) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  code varchar(20) NOT NULL,
  title text NOT NULL,
  artist text NOT NULL,
  embedcode text NULL,
  fullembedcode text NOT NULL,
  description text NOT NULL,
  servicename varchar(30) NOT NULL default '',
  thumb text NULL,
  service int(11) NULL default '0',
  views int(11) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE vp_categories (
  refid int(5) unsigned NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  title varchar(50) NOT NULL,
  disporder int(11) NOT NULL default '0',
  weight int(11) NOT NULL default '0',
  PRIMARY KEY  (refid)
) TYPE=MyISAM;

CREATE TABLE vp_reports (
  repid int(11) unsigned NOT NULL auto_increment,
  vid int(11) NOT NULL default '0',
  reasoncode int(11) NOT NULL default '0',
  reasontext varchar(50) NOT NULL,
  numreports int(11) NOT NULL default '0',
  PRIMARY KEY  (repid)
) TYPE=MyISAM;
