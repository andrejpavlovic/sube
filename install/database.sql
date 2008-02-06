--
-- Table structure for table `contact_seller`
--

DROP TABLE IF EXISTS `contact_seller`;
CREATE TABLE `contact_seller` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `listid` int(10) unsigned NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `message` text NOT NULL,
  `sent` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

--
-- Table structure for table `courseware`
--

DROP TABLE IF EXISTS `courseware`;
CREATE TABLE `courseware` (
  `listid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `time` varchar(30) NOT NULL default '',
  `category` tinyint(4) NOT NULL default '0',
  `description` text,
  `price` decimal(10,2) NOT NULL default '0.00',
  `title` varchar(255) default NULL,
  `author` varchar(255) default NULL,
  `isbn` varchar(13) default NULL,
  `term` varchar(15) default NULL,
  `year` varchar(4) default NULL,
  `vacancies` tinyint(4) default NULL,
  `remove` tinyint(1) NOT NULL default '0',
  `phone` varchar(255) default NULL,
  PRIMARY KEY  (`listid`)
);

--
-- Table structure for table `cw_category`
--

DROP TABLE IF EXISTS `cw_category`;
CREATE TABLE `cw_category` (
  `code` int(11) NOT NULL default '0',
  `category_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`code`)
);

--
-- Dumping data for table `cw_category`
--

INSERT INTO `cw_category` (`code`, `category_name`) VALUES (0,'Other');
INSERT INTO `cw_category` (`code`, `category_name`) VALUES (1,'Books');
INSERT INTO `cw_category` (`code`, `category_name`) VALUES (2,'Course Notes');
INSERT INTO `cw_category` (`code`, `category_name`) VALUES (3,'Handwritten Notes');
INSERT INTO `cw_category` (`code`, `category_name`) VALUES (4,'Test Papers');
INSERT INTO `cw_category` (`code`, `category_name`) VALUES (5,'Housing');


--
-- Table structure for table `cw_courses`
--

DROP TABLE IF EXISTS `cw_courses`;
CREATE TABLE `cw_courses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `listid` int(10) unsigned NOT NULL default '0',
  `course` varchar(10) NOT NULL default '',
  `number` char(3) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`uid`)
);
