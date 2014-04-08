-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2013 at 11:06 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aurora_1_1_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `role` varchar(100) NOT NULL DEFAULT 'guest',
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL COMMENT 'page is queried by this value',
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
  KEY `url` (`url`),
  KEY `pageName` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `userId`, `role`, `name`, `url`, `visibility`, `createdDate`, `publishDate`, `modifiedDate`, `archivedDate`, `pageOrder`, `content`, `keyWords`, `description`, `reindex`, `image`, `linkText`) VALUES
(-1, 0, 'guest', 'Home', 'Home', 'public', NULL, NULL, NULL, NULL, -1, 'This is test content for the Home page, which is now assigned to the default category and the default subcategory.', 'Aurora CMS, Site Management Software, Php, MySQL', 'Aurora CMS is currently under development and will be released soon.', 7, 'home.png', 'Home page');

-- --------------------------------------------------------

--
-- Table structure for table `page_categories`
--

DROP TABLE IF EXISTS `page_categories`;
CREATE TABLE `page_categories` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) NOT NULL,
  `catOrder` int(11) NOT NULL DEFAULT '0',
  `visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `role` varchar(255) NOT NULL DEFAULT 'guest',
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reindex` int(11) NOT NULL,
  `keywords` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`catId`),
  KEY `catName` (`catName`,`role`,`url`),
  KEY `catOrder` (`catOrder`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Top level categories' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `page_categories`
--

INSERT INTO `page_categories` (`catId`, `catName`, `catOrder`, `visibility`, `role`, `url`, `description`, `reindex`, `keywords`, `content`) VALUES
(-1, 'default', -1, 'public', 'guest', 'default', '', 0, '', ''),
(1, 'Category One', 0, 'public', '5', 'Category-One', 'cat one description', 5, 'cat one keywords', '<p>content for category one that should be a top level category just below default</p>\r\n'),
(2, 'Category Two', 0, 'public', '5', 'Category-Two', 'cat two description', 5, 'cat two keywords', '<p>cat&nbsp;two content</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `page_sub_categories`
--

DROP TABLE IF EXISTS `page_sub_categories`;
CREATE TABLE `page_sub_categories` (
  `recordId` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recordId`),
  KEY `pageId` (`catId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lookup so we can have sub categories' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `page_sub_categories`
--

INSERT INTO `page_sub_categories` (`recordId`, `catId`, `catName`, `parentId`) VALUES
(-1, -1, 'default', -1),
(1, 1, 'Category One', -1),
(2, 2, 'Category Two', 1);
