-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2013 at 03:40 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aurora_1_1_1`
--

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
('general', 'siteEmail', 'jsmith@dirextion.com', 'TextBox'),
('general', 'siteName', 'Demo', 'TextBox'),
('general', 'webMasterEmail', 'noreply@dirextion.com', 'TextBox');
