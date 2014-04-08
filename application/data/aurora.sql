-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2013 at 05:03 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aurora_1_1_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `appsettings`
--

DROP TABLE IF EXISTS `appsettings`;
CREATE TABLE `appsettings` (
  `variable` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `settingType` tinytext NOT NULL,
  KEY `variable` (`variable`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `monthRangeMin` int(11) NOT NULL,
  `monthRangeMax` int(11) NOT NULL,
  `type` enum('local','google') NOT NULL DEFAULT 'local',
  `googleUserName` varchar(255) DEFAULT NULL,
  `googlePassWord` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `name`, `monthRangeMin`, `monthRangeMax`, `type`, `googleUserName`, `googlePassWord`) VALUES
(1, 'default', -1, 12, 'local', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendarevents`
--

DROP TABLE IF EXISTS `calendarevents`;
CREATE TABLE `calendarevents` (
  `eventId` int(11) NOT NULL AUTO_INCREMENT,
  `calendarId` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `eventName` varchar(255) NOT NULL,
  `linkOne` varchar(255) NOT NULL,
  `linkTwo` varchar(255) NOT NULL,
  `eventContent` longtext NOT NULL,
  PRIMARY KEY (`eventId`),
  KEY `eventName` (`eventName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `calendarevents`
--

INSERT INTO `calendarevents` (`eventId`, `calendarId`, `year`, `month`, `day`, `eventName`, `linkOne`, `linkTwo`, `eventContent`) VALUES
(1, 1, 2012, 10, 15, 'Test Event', 'http://linkone.com', 'http://linktwo', 'This is some test content for an event on Oct 15th 2012.'),
(2, 1, 2012, 10, 14, 'Test Event Two', '', '', 'event two content'),
(8, 0, 2012, 10, 17, 'The seventeenth', 'sdvwge', 'rbgwretg', 'event on the 17th'),
(9, 1, 2012, 10, 3, 'asfqerf', 'dfverv', 'adfvwerv', 'dafvwefrvw'),
(10, 1, 2012, 10, 18, 'Bday', 'sdvq', 'avqr', 'cal id = 1, eventId = 10, day = 18th, month = 10, year = 2012'),
(11, 1, 2012, 10, 29, 'New Event', 'link One', 'Link Two', 'This is some content etc');

-- --------------------------------------------------------

--
-- Table structure for table `calendarweeks`
--

DROP TABLE IF EXISTS `calendarweeks`;
CREATE TABLE `calendarweeks` (
  `weekId` int(11) NOT NULL AUTO_INCREMENT,
  `calendarId` int(11) DEFAULT NULL,
  `headingColor` varchar(7) DEFAULT NULL,
  `weekHeading` mediumtext,
  `headingLink` varchar(255) DEFAULT NULL,
  `monthId` int(11) DEFAULT NULL,
  `monthName` varchar(45) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  PRIMARY KEY (`weekId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `rootId` int(11) NOT NULL,
  `categoryName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uri` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `url` (`uri`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `rootId`, `categoryName`, `uri`, `parentId`, `lft`, `rgt`) VALUES
(1, 1, 'Site', 'site', 0, 1, 20),
(2, 2, 'Pages', 'page', 1, 2, 11),
(3, 0, 'About Us', 'About-Us', 2, 3, 4),
(4, 0, 'Services', 'Services', 2, 5, 8),
(5, 0, 'Contact Us', 'Contact-Us', 2, 11, 12),
(6, 0, 'New Category', '', 2, 9, 10),
(7, 0, 'Services Child One', 'Services-Child-One', 4, 6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `category_pages`
--

DROP TABLE IF EXISTS `category_pages`;
CREATE TABLE `category_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL DEFAULT '2',
  `userId` int(11) NOT NULL DEFAULT '0',
  `role` varchar(100) NOT NULL DEFAULT 'guest',
  `label` varchar(50) NOT NULL,
  `uri` varchar(255) NOT NULL COMMENT 'page is queried by this value',
  `visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `createdDate` int(11) DEFAULT NULL,
  `publishDate` int(11) DEFAULT NULL,
  `modifiedDate` int(11) DEFAULT NULL,
  `archivedDate` int(11) DEFAULT NULL,
  `pageOrder` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `keyWords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reindex` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `linkText` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`visibility`,`createdDate`,`modifiedDate`,`archivedDate`,`pageOrder`),
  KEY `role` (`role`),
  KEY `url` (`uri`),
  KEY `pageName` (`label`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category_pages`
--

INSERT INTO `category_pages` (`id`, `categoryId`, `userId`, `role`, `label`, `uri`, `visibility`, `createdDate`, `publishDate`, `modifiedDate`, `archivedDate`, `pageOrder`, `content`, `keyWords`, `description`, `reindex`, `image`, `linkText`) VALUES
(1, 1, 0, 'guest', 'Home', 'Home', 'public', NULL, NULL, NULL, NULL, NULL, 'This is the site categories home page. This page will always be present in the system. It should never be deleted, we will insure it can not be deleted, that is how important this page is.', 'Aurora CMS, Site Management Software, Php, MySQL', 'This page is served via Aurora CMS, its really cool software.', 5, 'home.png', 'Home page'),
(2, 3, 0, 'guest', 'About Us', 'About-Us', 'public', NULL, NULL, NULL, NULL, NULL, 'This is content for the About Us category landing page. It should show when we load the About Us category', 'About Aurora CMS', 'This is the about page description', 5, 'about.png', 'About Us');

-- --------------------------------------------------------

--
-- Table structure for table `content_nodes`
--

DROP TABLE IF EXISTS `content_nodes`;
CREATE TABLE `content_nodes` (
  `nodeId` int(11) NOT NULL AUTO_INCREMENT,
  `nameSpace` varchar(255) NOT NULL,
  `contentItemId` int(11) NOT NULL,
  `spec` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`nodeId`),
  UNIQUE KEY `spec` (`spec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Content Nodes Table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `eventId` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` int(11) NOT NULL,
  `endDate` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `categories` mediumtext NOT NULL,
  `eventContent` longtext NOT NULL,
  PRIMARY KEY (`eventId`),
  KEY `startDate` (`startDate`,`endDate`,`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventId`, `startDate`, `endDate`, `title`, `categories`, `eventContent`) VALUES
(4, 1380603600, 1383282000, 'Testing', '', '<p>\r\n This is test content for the first event. It should be wrapped in a p tag.</p>'),
(5, 1380603600, 1383282000, 'Another Event', '', '<p>\r\n   Another event to test assigning cats to event</p>'),
(6, 1383282000, 1393653600, 'Blah', '', '<p>\r\n    testing result</p>'),
(7, 1383282000, 1393653600, 'BlahBlah', '', '<p>\r\n    testing result</p>'),
(8, 1380603600, 1383282000, 'yada', '', '<p>\r\n    testing forward</p>'),
(9, 1383282000, 1383282000, 'yikes', '3,2,1,2,3,3,2,1,2', '<p>\r\n  This is content</p>');

-- --------------------------------------------------------

--
-- Table structure for table `installedmodules`
--

DROP TABLE IF EXISTS `installedmodules`;
CREATE TABLE `installedmodules` (
  `moduleId` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nameSpace` varchar(255) NOT NULL,
  `publicName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`moduleId`),
  UNIQUE KEY `name` (`name`,`nameSpace`),
  KEY `publicName` (`publicName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `installedmodules`
--

INSERT INTO `installedmodules` (`moduleId`, `name`, `nameSpace`, `publicName`) VALUES
(1, 'admin', 'Admin_', 'Admin Area'),
(2, 'user', 'User_', NULL),
(3, 'pages', 'Pages_', NULL),
(4, 'media', 'Media_', 'Gallery'),
(5, 'contact', 'Contact_', 'Contact'),
(6, 'Calendar', 'Calendar_', 'Calendar'),
(7, 'search', 'Search_', 'Search'),
(8, 'testimonials', 'Testimonials_', 'Testimonials');

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

DROP TABLE IF EXISTS `lang`;
CREATE TABLE `lang` (
  `langKey` varchar(255) NOT NULL,
  `langText` mediumtext NOT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`langKey`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`langKey`, `langText`, `locale`) VALUES
('headerImageUserNotice', 'Page Header images must be width = X and Height = N', 'en_US'),
('welcomeGuest', 'Welcome back guest.', 'en_US');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `fileId` int(11) NOT NULL DEFAULT '0',
  `userName` varchar(255) DEFAULT NULL,
  `timeStamp` varchar(255) NOT NULL,
  `priorityName` varchar(20) NOT NULL,
  `priority` int(1) NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mediaalbums`
--

DROP TABLE IF EXISTS `mediaalbums`;
CREATE TABLE `mediaalbums` (
  `albumId` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `albumName` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'guest',
  `passWord` varchar(40) DEFAULT NULL,
  `albumDesc` mediumtext,
  `serverPath` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`albumId`),
  KEY `albumName` (`albumName`,`userId`),
  KEY `role` (`role`),
  KEY `parentId` (`parentId`),
  KEY `serverPath` (`serverPath`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mediaalbums`
--

INSERT INTO `mediaalbums` (`albumId`, `parentId`, `albumName`, `userId`, `role`, `passWord`, `albumDesc`, `serverPath`, `timestamp`) VALUES
(-3, 0, 'Slider', 1, 'guest', NULL, NULL, '', '0'),
(-2, 0, 'Media', 1, 'guest', NULL, 'This is the default Album for the Media module. This album can not be deleted as the system is dependent upon it for correct operation.', '', '0'),
(-1, 0, 'Pages', 1, 'guest', NULL, 'This is the default Album for the Pages module. This album can not be deleted as the system is dependent upon it for correct operation.', '', '0'),
(1, -2, 'Default', 1, 'guest', NULL, NULL, 'Default', '0'),
(2, -2, 'testing', 0, 'guest', NULL, NULL, 'testing', '0'),
(3, -2, 'testing', 0, 'guest', NULL, NULL, 'testing', '0'),
(4, -2, 'Testing', 0, 'guest', NULL, NULL, 'Testing', '0');

-- --------------------------------------------------------

--
-- Table structure for table `mediafiles`
--

DROP TABLE IF EXISTS `mediafiles`;
CREATE TABLE `mediafiles` (
  `fileId` int(11) NOT NULL AUTO_INCREMENT,
  `albumId` int(11) DEFAULT NULL,
  `fileName` varchar(255) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `order` int(11) NOT NULL,
  `timestamp` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fileId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `mediafiles`
--

INSERT INTO `mediafiles` (`fileId`, `albumId`, `fileName`, `title`, `alt`, `description`, `order`, `timestamp`) VALUES
(1, -3, 'slider-default-one.png', NULL, NULL, '', 0, '1353951475'),
(2, -3, 'slider-default-three.png', NULL, NULL, '', 0, '1353951475'),
(3, -3, 'slider-default-two.png', NULL, NULL, '', 0, '1353951476'),
(8, 1, 'blue-wheat-grass.jpg', NULL, NULL, '', 0, '1355802796'),
(9, 1, 'brokencar.jpg', NULL, NULL, '', 0, '1355802799'),
(10, 1, 'car.jpg', NULL, NULL, '', 0, '1355802800'),
(11, 1, 'redtruck.jpg', NULL, NULL, '', 0, '1355802800'),
(12, 2, 'Sites.png', NULL, NULL, '', 0, '1382387921');

-- --------------------------------------------------------

--
-- Table structure for table `modulesettings`
--

DROP TABLE IF EXISTS `modulesettings`;
CREATE TABLE `modulesettings` (
  `moduleName` varchar(255) NOT NULL,
  `variable` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `settingType` tinytext NOT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modulesettings`
--

INSERT INTO `modulesettings` (`moduleName`, `variable`, `value`, `settingType`) VALUES
('general', 'allowTags', '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<hr>', 'Textarea'),
('contact', 'contactPhoneNumber', '-', 'TextBox'),
('contact', 'emailAddress', '-', 'TextBox'),
('general', 'enableCaptcha', '1', 'CheckBox'),
('contact', 'enableContactInfo', '1', 'CheckBox'),
('general', 'enableDebugMode', '1', 'CheckBox'),
('general', 'enableFbOpenGraph', '0', 'CheckBox'),
('general', 'enableFbPageLink', '1', 'CheckBox'),
('general', 'enableHomeTab', '1', 'CheckBox'),
('general', 'enableLinkLogo', '1', 'CheckBox'),
('general', 'enableLogging', '1', 'CheckBox'),
('user', 'enableMainMenuLogin', '1', 'CheckBox'),
('general', 'enableMobileSupport', '1', 'CheckBox'),
('media', 'enableOnHoverDescriptions', '0', 'CheckBox'),
('user', 'enableRegistration', '1', 'CheckBox'),
('general', 'enableSearch', '1', 'CheckBox'),
('user', 'enableUserLogin', '1', 'CheckBox'),
('general', 'facebookAppId', '431812843521907', 'TextBox'),
('general', 'facebookAppSecret', 'd86702c59bd48f3a76bc57d923cd237e', 'TextBox'),
('general', 'isInstalled', '0', 'CheckBox'),
('general', 'remoteLicenseKey', 'SingleDomain18446aad51de8a3a596b594c3fcca5d137cf8c34', 'Textarea'),
('general', 'seoDescription', 'Custom CMS', 'Textarea'),
('general', 'seoKeyWords', 'Dirextion Inc, Dxcore, Php, Development, MySQL', 'Textarea'),
('general', 'sessionLength', '86400', 'TextBox'),
('user', 'showEmail', '1', 'CheckBox'),
('media', 'showFileDescription', '1', 'CheckBox'),
('media', 'showFileTitleInGallery', '1', 'CheckBox'),
('media', 'showFileUploadTime', '0', 'CheckBox'),
('media', 'showMostRecentFirst', '1', 'CheckBox'),
('general', 'showOnlineList', '1', 'CheckBox'),
('pages', 'showPageHeading', '0', 'CheckBox'),
('media', 'showRecentByDate', '1', 'CheckBox'),
('media', 'showRecentCount', '4', 'TextBox'),
('media', 'showRecentImagesOnHome', '1', 'CheckBox'),
('media', 'showRecentInGallery', '1', 'CheckBox'),
('media', 'showRecentNumDays', '14', 'TextBox'),
('general', 'siteEmail', 'testing@test.com', 'TextBox'),
('general', 'siteName', 'Demo', 'TextBox'),
('general', 'webMasterEmail', 'noreply@dirextion.com', 'TextBox');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `type` enum('all','newsletter','offers','') NOT NULL DEFAULT 'all',
  `exported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagecomments`
--

DROP TABLE IF EXISTS `pagecomments`;
CREATE TABLE `pagecomments` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdDate` int(11) NOT NULL,
  `modifiedDate` int(11) NOT NULL,
  `visibility` enum('public','private') NOT NULL,
  `commentText` longtext NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `pageId` (`pageId`,`userId`,`createdDate`,`modifiedDate`,`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagefiles`
--

DROP TABLE IF EXISTS `pagefiles`;
CREATE TABLE `pagefiles` (
  `fileId` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(255) NOT NULL,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `isMainImage` int(1) DEFAULT NULL,
  PRIMARY KEY (`fileId`),
  KEY `pageId` (`pageId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL DEFAULT '0',
  `role` varchar(100) NOT NULL DEFAULT 'guest',
  `label` varchar(50) NOT NULL,
  `isLanding` tinyint(1) NOT NULL DEFAULT '0',
  `uri` varchar(255) NOT NULL COMMENT 'page is queried by this value',
  `visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `createdDate` int(11) DEFAULT NULL,
  `publishDate` int(11) DEFAULT NULL,
  `modifiedDate` int(11) DEFAULT NULL,
  `archivedDate` int(11) DEFAULT NULL,
  `pageOrder` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `keyWords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reindex` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `linkText` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`visibility`,`createdDate`,`modifiedDate`,`archivedDate`,`pageOrder`),
  KEY `role` (`role`),
  KEY `url` (`uri`),
  KEY `pageName` (`label`),
  KEY `categoryId` (`categoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `categoryId`, `userId`, `role`, `label`, `isLanding`, `uri`, `visibility`, `createdDate`, `publishDate`, `modifiedDate`, `archivedDate`, `pageOrder`, `content`, `keyWords`, `description`, `reindex`, `image`, `linkText`) VALUES
(1, 1, 0, 'guest', 'Home', 1, 'Home', 'public', NULL, NULL, NULL, NULL, NULL, 'This is the site categories home page. This page will always be present in the system. It should never be deleted, we will insure it can not be deleted, that is how important this page is.', 'Aurora CMS, Site Management Software, Php, MySQL', 'This page is served via Aurora CMS, its really cool software.', 5, 'home.png', 'Home page'),
(2, 3, 0, 'guest', 'About Us', 1, 'About-Us', 'public', NULL, NULL, NULL, NULL, NULL, 'This is content for the About Us category landing page. It should show when we load the About Us category', 'About Aurora CMS', 'This is the about page description', 5, 'about.png', 'About Us'),
(3, 4, 0, '5', 'About Company', 0, 'About-Company', 'public', NULL, NULL, NULL, NULL, 1, '<p>This is the about company page</p>\r\n', 'About Webinertia', 'This is the webinertia company page in the About Us category', 5, 'company.png', 'about company'),
(4, 3, 0, 'guest', 'About Something', 0, 'About-Us/About-Something', 'public', NULL, NULL, NULL, NULL, 2, 'This the About SOmething page. Its in the about us category but will only show when linked too. The About Company should show instead when there is no category level content.', 'About, Something', 'About something page', 5, 'something.png', 'about something'),
(5, 7, 0, '5', 'Test One', 0, 'Test-One', 'public', NULL, NULL, NULL, NULL, NULL, '<p>test page</p>\r\n', 'test page', 'test description', 5, NULL, 'test link text');

-- --------------------------------------------------------

--
-- Table structure for table `page_categories`
--

DROP TABLE IF EXISTS `page_categories`;
CREATE TABLE `page_categories` (
  `rowId` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`rowId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Page To Category Intersection' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `page_categories`
--

INSERT INTO `page_categories` (`rowId`, `pageId`, `categoryId`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `roleId` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `inheritsFrom` varchar(255) NOT NULL,
  `publicName` varchar(100) NOT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleId`, `role`, `inheritsFrom`, `publicName`) VALUES
(1, 'admin', 'jradmin', 'Administrator'),
(2, 'jradmin', 'moderator', 'Jr. Administrator'),
(3, 'moderator', 'user', 'Moderator'),
(4, 'user', 'guest', 'Standard User'),
(5, 'guest', 'none', 'Guest');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `modified`, `lifetime`, `data`) VALUES
('068a4b029e1b7aa39af5acc0e0c02cb2', 1385464594, 86400, 'sysData|a:1:{s:12:"referringUri";s:12:"/favicon.ico";}'),
('0e868d10ea6ee07613b3862d0bd89851', 1385464594, 86400, 'sysData|a:1:{s:12:"referringUri";s:11:"/user/login";}'),
('4a019a97fa46c0a9397b78e3c5027c5a', 1385399326, 86400, 'sysData|a:1:{s:12:"referringUri";s:19:"/admin/page/manager";}'),
('4aa0a54ecaf5a961637a4fd166b9b437', 1385464593, 86400, 'sysData|a:1:{s:12:"referringUri";s:19:"/admin/page/manager";}'),
('5aa1394431cd2033494db89a1039646a', 1385419093, 86400, 'sysData|a:1:{s:12:"referringUri";s:19:"/admin/page/manager";}Zend_Auth|a:1:{s:7:"storage";O:8:"stdClass":3:{s:6:"userId";s:1:"0";s:8:"userName";s:7:"dxadmin";s:4:"role";s:7:"dxadmin";}}'),
('5cc60944a535717a68f54420c2db679c', 1385419030, 86400, 'sysData|a:1:{s:12:"referringUri";s:11:"/admin/page";}'),
('6d73a789de244ee8a4f2b9ee3e84a574', 1385399327, 86400, 'sysData|a:1:{s:12:"referringUri";s:11:"/user/login";}'),
('716daa5801598a6ac4d5afb8ff1e68f8', 1385452186, 86400, ''),
('854a4e054ed11661fbf4225fb7eaf59c', 1385452186, 86400, 'sysData|a:1:{s:12:"referringUri";s:1:"/";}'),
('890dedcfca6629d49325d36de6ecc1f8', 1385506713, 86400, 'sysData|a:1:{s:12:"referringUri";s:15:"/admin/settings";}Zend_Auth|a:1:{s:7:"storage";O:8:"stdClass":3:{s:6:"userId";s:1:"0";s:8:"userName";s:7:"dxadmin";s:4:"role";s:7:"dxadmin";}}'),
('98f0b3314a829f92d8f1ce4b7c3df96f', 1385503774, 86400, 'sysData|a:1:{s:12:"referringUri";s:12:"/favicon.ico";}'),
('b64a876907a00c0eab1713564919961c', 1385482645, 86400, 'sysData|a:1:{s:12:"referringUri";s:1:"/";}'),
('b8f06c333671f1a3303293ba46deffcd', 1385482645, 86400, ''),
('d236bba5ea27c9cc059869a154463518', 1385482645, 86400, 'sysData|a:1:{s:12:"referringUri";s:11:"/admin/page";}');

-- --------------------------------------------------------

--
-- Table structure for table `skins`
--

DROP TABLE IF EXISTS `skins`;
CREATE TABLE `skins` (
  `skinId` int(11) NOT NULL AUTO_INCREMENT,
  `skinName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`skinId`),
  UNIQUE KEY `skinName` (`skinName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `skins`
--

INSERT INTO `skins` (`skinId`, `skinName`) VALUES
(1, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `skin_settings`
--

DROP TABLE IF EXISTS `skin_settings`;
CREATE TABLE `skin_settings` (
  `recordId` int(11) NOT NULL AUTO_INCREMENT,
  `skinId` int(11) NOT NULL,
  `spec` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`recordId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `skin_settings`
--

INSERT INTO `skin_settings` (`recordId`, `skinId`, `spec`, `value`) VALUES
(1, 1, 'skinVersion', '1.1.0'),
(2, 1, 'appVersion', '1.1.0'),
(3, 1, 'isCurrentSkin', '1');

-- --------------------------------------------------------

--
-- Table structure for table `slidersettings`
--

DROP TABLE IF EXISTS `slidersettings`;
CREATE TABLE `slidersettings` (
  `name` varchar(255) NOT NULL,
  `isActive` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `effect` varchar(255) NOT NULL DEFAULT 'fade',
  `slices` int(11) NOT NULL DEFAULT '15',
  `boxCols` int(11) NOT NULL DEFAULT '8',
  `boxRows` int(11) NOT NULL DEFAULT '4',
  `animSpeed` int(11) NOT NULL DEFAULT '500',
  `pauseTime` int(11) NOT NULL DEFAULT '3000',
  `startSlide` int(11) NOT NULL DEFAULT '1',
  `directionNav` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `controlNav` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `controlNavThumbs` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `pauseOnHover` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `manualAdvance` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `prevText` varchar(25) NOT NULL DEFAULT 'Prev' COMMENT 'label for prev text',
  `nextText` varchar(25) NOT NULL DEFAULT 'Next' COMMENT 'label for next text',
  `randomStart` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slidersettings`
--

INSERT INTO `slidersettings` (`name`, `isActive`, `effect`, `slices`, `boxCols`, `boxRows`, `animSpeed`, `pauseTime`, `startSlide`, `directionNav`, `controlNav`, `controlNavThumbs`, `pauseOnHover`, `manualAdvance`, `prevText`, `nextText`, `randomStart`) VALUES
('default', 1, 'fade', 15, 8, 4, 500, 3000, 1, 1, 0, 0, 1, 0, 'Prev', 'Next', 0);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guestName` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `isApproved` tinyint(1) NOT NULL DEFAULT '0',
  `createdDate` int(11) NOT NULL,
  `updatedDate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `userName` varchar(128) NOT NULL,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passWord` char(40) NOT NULL,
  `salt` char(32) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `uStatus` varchar(8) NOT NULL DEFAULT 'disabled',
  `registeredDate` varchar(11) NOT NULL,
  `hash` int(10) NOT NULL,
  PRIMARY KEY (`userId`),
  KEY `email_pass` (`email`,`passWord`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `title`, `userName`, `firstName`, `lastName`, `email`, `passWord`, `salt`, `role`, `uStatus`, `registeredDate`, `hash`) VALUES
(0, '', 'dxadmin', '', '', '', 'e1da551374f0a6f136916647ab0f157cc192ea22', '', 'dxadmin', 'enabled', '', 0),
(9, '', 'randallk', 'Randall', 'Kaemmerer', 'randallk@dirextion.com', 'e1da551374f0a6f136916647ab0f157cc192ea22', '', 'admin', 'enabled', '1375316279', 0),
(10, '', 'test', 'TEST', 'TEST', 'test@test.com', '6c30886329e3e6961495d4dc6397c04c8b94f99a', '', 'dxadmin', 'enabled', '1376009650', 1376009650);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL COMMENT 'foreign key to user table primaryKey',
  `group` varchar(20) NOT NULL,
  `company` varchar(255) NOT NULL,
  `addrStreetOne` text NOT NULL,
  `addrStreetTwo` text NOT NULL,
  `addrCity` varchar(255) NOT NULL,
  `addrState` varchar(255) NOT NULL,
  `addrZip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company` (`company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds secondary user info for profiles' AUTO_INCREMENT=1 ;
