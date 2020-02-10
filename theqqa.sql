-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 10, 2020 at 08:37 AM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `k8iqfcv5_theqqaprod`
--

DELIMITER $$
--
-- Functions
--
$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `theqqaadvertising`
--

CREATE TABLE `theqqaadvertising` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `provider_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tracking_code_large` text COLLATE utf8_unicode_ci,
  `tracking_code_medium` text COLLATE utf8_unicode_ci,
  `tracking_code_small` text COLLATE utf8_unicode_ci,
  `active` tinyint(1) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaadvertising`
--

INSERT INTO `theqqaadvertising` (`id`, `slug`, `provider_name`, `tracking_code_large`, `tracking_code_medium`, `tracking_code_small`, `active`) VALUES
(1, 'top', 'Advert Code', NULL, NULL, NULL, 0),
(2, 'bottom', 'Advert Code', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `theqqablacklist`
--

CREATE TABLE `theqqablacklist` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('domain','email','ip','word') COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqacache`
--

CREATE TABLE `theqqacache` (
  `key` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8_unicode_ci,
  `expiration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqacategories`
--

CREATE TABLE `theqqacategories` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `user_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('classified','job-offer','job-search','not-salable') COLLATE utf8_unicode_ci DEFAULT 'classified' COMMENT 'Only select this for parent categories',
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqacategories`
--

INSERT INTO `theqqacategories` (`id`, `translation_lang`, `translation_of`, `parent_id`, `user_type`, `name`, `slug`, `description`, `picture`, `icon_class`, `lft`, `rgt`, `depth`, `type`, `active`) VALUES
(1, 'en', 154, 0, '1,2,6', 'Used Cars', 'used-cars', NULL, 'app/categories/custom/71c1dee8bc8c90a74fe83264082f7af1.png', NULL, 4, 5, 1, 'classified', 1),
(21, 'en', 174, 0, '1,10', 'Car Transfer', 'car-transfer', NULL, 'app/categories/custom/6ecbfe83d6f3743749d52ec9a6b5e4e2.png', NULL, 12, 13, 1, NULL, 1),
(154, 'ar', 154, 0, '1,2,6', 'سيارات مستعملة', 'سيارات-مستعملة', 'سيارات مستعملة', 'app/categories/custom/71c1dee8bc8c90a74fe83264082f7af1.png', NULL, 4, 5, 1, 'classified', 1),
(174, 'ar', 174, 0, '1,10', 'نقل سيارات (سطحات)', 'نقل-سيارات', NULL, 'app/categories/custom/6ecbfe83d6f3743749d52ec9a6b5e4e2.png', NULL, 12, 13, 1, NULL, 1),
(307, 'en', 308, 154, '', 'تويوتا', 'تويوتا', NULL, 'app/categories/custom/80d13d891951485874c7c9531878d1f6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(308, 'ar', 308, 154, '', 'تويوتا', 'تويوتا-ar', NULL, 'app/categories/custom/80d13d891951485874c7c9531878d1f6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(309, 'en', 310, 154, '', 'شيفروليه', 'شيفروليه', NULL, 'app/categories/custom/b89e5c64add3e8d163595317332f75d8.png', NULL, NULL, NULL, NULL, 'classified', 1),
(310, 'ar', 310, 154, '', 'شيفروليه', 'شيفروليه-ar', NULL, 'app/categories/custom/b89e5c64add3e8d163595317332f75d8.png', NULL, NULL, NULL, NULL, 'classified', 1),
(311, 'en', 312, 154, '', 'نيسان', 'نيسان', NULL, 'app/categories/custom/9342a7a5bf7fa7b4c6f4373d829fc449.png', NULL, NULL, NULL, NULL, 'classified', 1),
(312, 'ar', 312, 154, '', 'نيسان', 'نيسان-ar', NULL, 'app/categories/custom/9342a7a5bf7fa7b4c6f4373d829fc449.png', NULL, NULL, NULL, NULL, 'classified', 1),
(313, 'en', 314, 154, '', 'فورد', 'فورد', NULL, 'app/categories/custom/127b1645f89a87289bba5da7103ce63c.png', NULL, NULL, NULL, NULL, 'classified', 1),
(314, 'ar', 314, 154, '', 'فورد', 'فورد-ar', NULL, 'app/categories/custom/127b1645f89a87289bba5da7103ce63c.png', NULL, NULL, NULL, NULL, 'classified', 1),
(315, 'en', 316, 154, '', 'مرسيدس', 'مرسيدس', NULL, 'app/categories/custom/2eb0e8d7c6f696f5d77d5236d88aa9c2.png', NULL, NULL, NULL, NULL, 'classified', 1),
(316, 'ar', 316, 154, '', 'مرسيدس', 'مرسيدس-ar', NULL, 'app/categories/custom/2eb0e8d7c6f696f5d77d5236d88aa9c2.png', NULL, NULL, NULL, NULL, 'classified', 1),
(317, 'en', 318, 154, '', 'GMC', 'GMC', NULL, 'app/categories/custom/e1c6a91ae97f331ad86af6885761d6fd.png', NULL, NULL, NULL, NULL, 'classified', 1),
(318, 'ar', 318, 154, '', 'جي إم سي', 'جي إم سي-ar', NULL, 'app/categories/custom/e1c6a91ae97f331ad86af6885761d6fd.png', NULL, NULL, NULL, NULL, 'classified', 1),
(319, 'en', 320, 154, '', 'بي ام دبليو', 'بي ام دبليو', NULL, 'app/categories/custom/f6dc3dac291aa2b09d4cc3b595191a2e.png', NULL, NULL, NULL, NULL, 'classified', 1),
(320, 'ar', 320, 154, '', 'بي إم دبليو', 'بي إم دبليو-ar', NULL, 'app/categories/custom/f6dc3dac291aa2b09d4cc3b595191a2e.png', NULL, NULL, NULL, NULL, 'classified', 1),
(321, 'en', 322, 154, '', 'لكزس', 'لكزس', NULL, 'app/categories/custom/3f9fbd3cf6ee534d88b5dac566db82fa.png', NULL, NULL, NULL, NULL, 'classified', 1),
(322, 'ar', 322, 154, '', 'لكزس', 'لكزس-ar', NULL, 'app/categories/custom/3f9fbd3cf6ee534d88b5dac566db82fa.png', NULL, NULL, NULL, NULL, 'classified', 1),
(325, 'en', 326, 154, '', 'هونداي', 'هونداي', NULL, 'app/categories/custom/9aeb1ad94f691721fbe5c11f5e38b822.png', NULL, NULL, NULL, NULL, 'classified', 1),
(326, 'ar', 326, 154, '', 'هيونداي', 'هيونداي-ar', NULL, 'app/categories/custom/9aeb1ad94f691721fbe5c11f5e38b822.png', NULL, NULL, NULL, NULL, 'classified', 1),
(327, 'en', 328, 154, '', 'هوندا', 'هوندا', NULL, 'app/categories/custom/3c44612f93c0c3ca0d7d7d72ed7baf10.png', NULL, NULL, NULL, NULL, 'classified', 1),
(328, 'ar', 328, 154, '', 'هوندا', 'هوندا-ar', NULL, 'app/categories/custom/3c44612f93c0c3ca0d7d7d72ed7baf10.png', NULL, NULL, NULL, NULL, 'classified', 1),
(329, 'en', 330, 154, '', 'همر', 'همر', NULL, 'app/categories/custom/5afbee93af1fdd7a88e266b314d632d6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(330, 'ar', 330, 154, '', 'همر', 'همر-ar', NULL, 'app/categories/custom/5afbee93af1fdd7a88e266b314d632d6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(331, 'en', 332, 154, '', 'انفنيتي', 'انفنيتي', NULL, 'app/categories/custom/a08ef72a3e353aa2bf35b57d7f2d8cd8.png', NULL, NULL, NULL, NULL, 'classified', 1),
(332, 'ar', 332, 154, '', 'إنفينيتي', 'إنفينيتي-ar', NULL, 'app/categories/custom/a08ef72a3e353aa2bf35b57d7f2d8cd8.png', NULL, NULL, NULL, NULL, 'classified', 1),
(333, 'en', 334, 154, '', 'لاند روفر', 'لاند روفر', NULL, 'app/categories/custom/5ccd66ac7e08a10514544afca1ad0053.png', NULL, NULL, NULL, NULL, 'classified', 1),
(334, 'ar', 334, 154, '', 'لاند روفر', 'لاند روفر-ar', NULL, 'app/categories/custom/5ccd66ac7e08a10514544afca1ad0053.png', NULL, NULL, NULL, NULL, 'classified', 1),
(335, 'en', 336, 154, '', 'مازدا', 'مازدا', NULL, 'app/categories/custom/92b55b86b3d25416e618cc89ad063772.png', NULL, NULL, NULL, NULL, 'classified', 1),
(336, 'ar', 336, 154, '', 'مازدا', 'مازدا-ar', NULL, 'app/categories/custom/92b55b86b3d25416e618cc89ad063772.png', NULL, NULL, NULL, NULL, 'classified', 1),
(337, 'en', 338, 154, '', 'ميركوري', 'ميركوري', NULL, 'app/categories/custom/1f74f8c8ea6bcbc578aa8c6dc34ac53d.png', NULL, NULL, NULL, NULL, 'classified', 1),
(338, 'ar', 338, 154, '', 'ميركوري', 'ميركوري-ar', NULL, 'app/categories/custom/1f74f8c8ea6bcbc578aa8c6dc34ac53d.png', NULL, NULL, NULL, NULL, 'classified', 1),
(339, 'en', 340, 154, '', 'فولكس واجن', 'فولكس واجن', NULL, 'app/categories/custom/71a3a07209cbb2ecd4fb3fff670e5cc6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(340, 'ar', 340, 154, '', 'فولكس واجن', 'فولكس واجن-ar', NULL, 'app/categories/custom/71a3a07209cbb2ecd4fb3fff670e5cc6.png', NULL, NULL, NULL, NULL, 'classified', 1),
(341, 'en', 342, 154, '', 'ميتسوبيشي', 'ميتسوبيشي', NULL, 'app/categories/custom/753302d58311655575bbc176ab2958aa.png', NULL, NULL, NULL, NULL, 'classified', 1),
(342, 'ar', 342, 154, '', 'ميتسوبيشي', 'ميتسوبيشي-ar', NULL, 'app/categories/custom/753302d58311655575bbc176ab2958aa.png', NULL, NULL, NULL, NULL, 'classified', 1),
(343, 'en', 344, 154, '', 'لنكولن', 'لنكولن', NULL, 'app/categories/custom/61ff8d0b748be9a250913074f498cde5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(344, 'ar', 344, 154, '', 'لنكولن', 'لنكولن-ar', NULL, 'app/categories/custom/61ff8d0b748be9a250913074f498cde5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(345, 'en', 346, 154, '', 'اوبل', 'اوبل', NULL, 'app/categories/custom/53c5d65700901fa4445a6802bdde05e0.png', NULL, NULL, NULL, NULL, 'classified', 1),
(346, 'ar', 346, 154, '', 'أوبل', 'أوبل-ar', NULL, 'app/categories/custom/53c5d65700901fa4445a6802bdde05e0.png', NULL, NULL, NULL, NULL, 'classified', 1),
(347, 'en', 348, 154, '', 'ايسوزو', 'ايسوزو', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(348, 'ar', 348, 154, '', 'ايسوزو', 'ايسوزو-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(349, 'en', 350, 154, '', 'بورش', 'بورش', NULL, 'app/categories/custom/06d3b9ae005d97221d82e98dec48e3dc.png', NULL, NULL, NULL, NULL, 'classified', 0),
(350, 'ar', 350, 154, '', 'بورش', 'بورش-ar', NULL, 'app/categories/custom/06d3b9ae005d97221d82e98dec48e3dc.png', NULL, NULL, NULL, NULL, 'classified', 0),
(351, 'en', 352, 154, '', 'كيا', 'كيا', NULL, 'app/categories/custom/089a5e9b6950c3d28f236e757427a669.png', NULL, NULL, NULL, NULL, 'classified', 0),
(352, 'ar', 352, 154, '', 'كيا', 'كيا-ar', NULL, 'app/categories/custom/089a5e9b6950c3d28f236e757427a669.png', NULL, NULL, NULL, NULL, 'classified', 0),
(353, 'en', 354, 154, '', 'مازيراتي', 'مازيراتي', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(354, 'ar', 354, 154, '', 'مازيراتي', 'مازيراتي-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(355, 'en', 356, 154, '', 'بنتلي', 'بنتلي', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(356, 'ar', 356, 154, '', 'بنتلي', 'بنتلي-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(357, 'en', 358, 154, '', 'استون مارتن', 'استون مارتن', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(358, 'ar', 358, 154, '', 'أستون مارتن', 'أستون مارتن-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(359, 'en', 360, 154, '', 'كاديلاك', 'كاديلاك', NULL, 'app/categories/custom/b5ceba7b96554dd019ad482cd89b19b1.png', NULL, NULL, NULL, NULL, 'classified', 0),
(360, 'ar', 360, 154, '', 'كاديلاك', 'كاديلاك-ar', NULL, 'app/categories/custom/b5ceba7b96554dd019ad482cd89b19b1.png', NULL, NULL, NULL, NULL, 'classified', 0),
(361, 'en', 362, 154, '', 'كرايزلر', 'كرايزلر', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(362, 'ar', 362, 154, '', 'كرايزلر', 'كرايزلر-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(363, 'en', 364, 154, '', 'سيتروين', 'سيتروين', NULL, 'app/categories/custom/4e20dd9f0e9e840c80555dffb54c454a.png', NULL, NULL, NULL, NULL, 'classified', 0),
(364, 'ar', 364, 154, '', 'سيتروين', 'سيتروين-ar', NULL, 'app/categories/custom/4e20dd9f0e9e840c80555dffb54c454a.png', NULL, NULL, NULL, NULL, 'classified', 0),
(365, 'en', 366, 154, '', 'دايو', 'دايو', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(366, 'ar', 366, 154, '', 'دايو', 'دايو-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(367, 'en', 368, 154, '', 'ديهاتسو', 'ديهاتسو', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(368, 'ar', 368, 154, '', 'دايهاتسو', 'دايهاتسو-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(369, 'en', 370, 154, '', 'دودج', 'دودج', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(370, 'ar', 370, 154, '', 'دودج', 'دودج-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(385, 'en', 386, 154, '', 'فيراري', 'فيراري', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(386, 'ar', 386, 154, '', 'فيراري', 'فيراري-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(387, 'en', 388, 154, '', 'فيات', 'فيات', NULL, 'app/categories/custom/bf8ead2f92995c78fef72c0bb99ba27f.png', NULL, NULL, NULL, NULL, 'classified', 0),
(388, 'ar', 388, 154, '', 'فيات', 'فيات-ar', NULL, 'app/categories/custom/bf8ead2f92995c78fef72c0bb99ba27f.png', NULL, NULL, NULL, NULL, 'classified', 0),
(389, 'en', 390, 154, '', 'جاكوار', 'جاكوار', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(390, 'ar', 390, 154, '', 'جاكوار', 'جاكوار-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(391, 'en', 392, 154, '', 'لامبورجيني', 'لامبورجيني', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(392, 'ar', 392, 154, '', 'لامبورجيني', 'لامبورجيني-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(393, 'en', 394, 154, '', 'رولز رويس', 'رولز رويس', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(394, 'ar', 394, 154, '', 'رولز رويس', 'رولز رويس-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(395, 'en', 396, 154, '', 'بيجو', 'بيجو', NULL, 'app/categories/custom/ebe4b09dfaa91ee4716bb88903d3b93b.png', NULL, NULL, NULL, NULL, 'classified', 0),
(396, 'ar', 396, 154, '', 'بيجو', 'بيجو-ar', NULL, 'app/categories/custom/ebe4b09dfaa91ee4716bb88903d3b93b.png', NULL, NULL, NULL, NULL, 'classified', 0),
(397, 'en', 398, 154, '', 'سوبارو', 'سوبارو', NULL, 'app/categories/custom/bbae21b6e92934f580bdfad211275758.png', NULL, NULL, NULL, NULL, 'classified', 0),
(398, 'ar', 398, 154, '', 'سوبارو', 'سوبارو-ar', NULL, 'app/categories/custom/bbae21b6e92934f580bdfad211275758.png', NULL, NULL, NULL, NULL, 'classified', 0),
(399, 'en', 400, 154, '', 'سوزوكي', 'سوزوكي', NULL, 'app/categories/custom/514bea176b54fe532ed03e43f5be9aa3.png', NULL, NULL, NULL, NULL, 'classified', 0),
(400, 'ar', 400, 154, '', 'سوزوكي', 'سوزوكي-ar', NULL, 'app/categories/custom/514bea176b54fe532ed03e43f5be9aa3.png', NULL, NULL, NULL, NULL, 'classified', 0),
(401, 'en', 402, 154, '', 'فولفو', 'فولفو', NULL, 'app/categories/custom/e0801c4cb0a5f1c946d3dc5e86a30f7f.png', NULL, NULL, NULL, NULL, 'classified', 0),
(402, 'ar', 402, 154, '', 'فولفو', 'فولفو-ar', NULL, 'app/categories/custom/e0801c4cb0a5f1c946d3dc5e86a30f7f.png', NULL, NULL, NULL, NULL, 'classified', 0),
(403, 'en', 404, 154, '', 'سكودا', 'سكودا', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(404, 'ar', 404, 154, '', 'سكودا', 'سكودا-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(405, 'en', 406, 154, '', 'اودي', 'اودي', NULL, 'app/categories/custom/b2f390f433eb679c1d413b7e859a4b6e.png', NULL, NULL, NULL, NULL, 'classified', 0),
(406, 'ar', 406, 154, '', 'أودي', 'أودي-ar', NULL, 'app/categories/custom/b2f390f433eb679c1d413b7e859a4b6e.png', NULL, NULL, NULL, NULL, 'classified', 0),
(407, 'en', 408, 154, '', 'رينو', 'رينو', NULL, 'app/categories/custom/5ca8c9bddafec2d4130e57c318473c91.png', NULL, NULL, NULL, NULL, 'classified', 0),
(408, 'ar', 408, 154, '', 'رينو', 'رينو-ar', NULL, 'app/categories/custom/5ca8c9bddafec2d4130e57c318473c91.png', NULL, NULL, NULL, NULL, 'classified', 0),
(409, 'en', 410, 154, '', 'بيوك', 'بيوك', NULL, 'app/categories/custom/df1d168996fae182e0c4426b9a2d4338.png', NULL, NULL, NULL, NULL, 'classified', 0),
(410, 'ar', 410, 154, '', 'بويك', 'بويك-ar', NULL, 'app/categories/custom/df1d168996fae182e0c4426b9a2d4338.png', NULL, NULL, NULL, NULL, 'classified', 0),
(411, 'en', 412, 154, '', 'ساب', 'ساب', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(412, 'ar', 412, 154, '', 'ساب', 'ساب-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(413, 'en', 414, 154, '', 'سيات', 'سيات', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(414, 'ar', 414, 154, '', 'سيات', 'سيات-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(415, 'en', 416, 154, '', 'MG', 'MG', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(416, 'ar', 416, 154, '', 'إم جي', 'إم جي-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(417, 'en', 418, 154, '', 'بروتون', 'بروتون', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(418, 'ar', 418, 154, '', 'بروتون', 'بروتون-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(419, 'en', 420, 154, '', 'سانج يونج', 'سانج يونج', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(420, 'ar', 420, 154, '', 'سانج يونج', 'سانج يونج-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(421, 'en', 422, 154, '', 'تشيري', 'تشيري', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(422, 'ar', 422, 154, '', 'تشيري', 'تشيري-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(423, 'en', 424, 154, '', 'جيلي', 'جيلي', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(424, 'ar', 424, 154, '', 'جيلي', 'جيلي-ar', NULL, NULL, NULL, NULL, NULL, NULL, 'classified', 0),
(427, 'en', 428, 154, '', 'دبابات', 'دبابات', NULL, 'app/categories/custom/a85a166894ba5694caf6d96342da8e7a.png', NULL, NULL, NULL, NULL, 'classified', 0),
(428, 'ar', 428, 154, '', 'معدات ثقيلة', 'معدات ثقيلة-ar', NULL, 'app/categories/custom/a85a166894ba5694caf6d96342da8e7a.png', NULL, NULL, NULL, NULL, 'classified', 0),
(429, 'en', 430, 154, '', 'قطع غيار وملحقات', 'قطع غيار وملحقات', NULL, 'app/categories/custom/48f99ad22d319a85a689b56025bf1104.png', NULL, NULL, NULL, NULL, 'classified', 0),
(430, 'ar', 430, 154, '', 'قطع غيار', 'قطع غيار -ar', NULL, 'app/categories/custom/48f99ad22d319a85a689b56025bf1104.png', NULL, NULL, NULL, NULL, 'classified', 0),
(431, 'en', 432, 154, '', 'شاحنات ومعدات ثقيلة', 'شاحنات ومعدات ثقيلة', NULL, 'app/categories/custom/d0946743f3b9e65739b24b8177cb1e6d.png', NULL, NULL, NULL, NULL, 'classified', 0),
(432, 'ar', 432, 154, '', 'شاحنات', 'شاحنات-ar', NULL, 'app/categories/custom/d0946743f3b9e65739b24b8177cb1e6d.png', NULL, NULL, NULL, NULL, 'classified', 0),
(437, 'en', 438, 0, '1,5', 'Shipping Companies', 'shipping-companies', NULL, 'app/categories/custom/985c5c76c970d2349c185109f8a4f4af.png', NULL, 8, 9, 1, 'classified', 1),
(438, 'ar', 438, 0, '1,5', 'شركات شحن', 'شركات-شحن', NULL, 'app/categories/custom/985c5c76c970d2349c185109f8a4f4af.png', NULL, 8, 9, 1, 'classified', 1),
(439, 'en', 440, 0, '1,2,6', 'New Cars', 'new-cars', NULL, 'app/categories/custom/de1289a2bd72469fa56628818e93cd6c.png', NULL, 2, 3, 1, NULL, 1),
(440, 'ar', 440, 0, '1,2,6', 'سيارات جديدة', 'سيارات-جديدة', NULL, 'app/categories/custom/de1289a2bd72469fa56628818e93cd6c.png', NULL, 2, 3, 1, NULL, 1),
(443, 'ar', 443, 0, '1,3', 'مراكز فحص', 'مراكز-فحص', NULL, 'app/categories/custom/0f3cc4cb8a04e46f922ce498d3936863.png', NULL, 10, 11, 1, NULL, 1),
(444, 'en', 443, 0, '1,3', 'Checkup Centers', 'checkup-centers', NULL, 'app/categories/custom/0f3cc4cb8a04e46f922ce498d3936863.png', NULL, 10, 11, 1, NULL, 1),
(445, 'ar', 445, 0, '1,9', 'إطارات سيارات', 'إطارات-سيارات', NULL, 'app/categories/custom/fd82ec602ecef4b519c38a0cd3514cff.png', NULL, 14, 15, 1, NULL, 1),
(446, 'en', 445, 0, '1,9', 'Tires', 'tires', NULL, 'app/categories/custom/fd82ec602ecef4b519c38a0cd3514cff.png', NULL, 14, 15, 1, NULL, 1),
(447, 'ar', 447, 0, '1,7', 'بطاريات سيارات', 'بطاريات-سيارات', NULL, 'app/categories/custom/6f052bab5c180c4ca9e02808ad936a78.png', NULL, 16, 17, 1, NULL, 1),
(448, 'en', 447, 0, '1,7', 'Batteries', 'batteries', NULL, 'app/categories/custom/6f052bab5c180c4ca9e02808ad936a78.png', NULL, 16, 17, 1, NULL, 1),
(453, 'ar', 453, 0, '1,4', 'مراكز صيانة', 'مراكز-صيانة', NULL, 'app/categories/custom/43976ebe7b4c0d313590aa17ea23fec8.png', NULL, 6, 7, 1, NULL, 1),
(454, 'en', 453, 0, '1,4', 'Maintenance', 'maintenance', NULL, 'app/categories/custom/43976ebe7b4c0d313590aa17ea23fec8.png', NULL, 6, 7, 1, NULL, 1),
(455, 'ar', 455, 0, '1,2,3,4,5,6,7,8,9,10', 'أخرى', 'أخرى', NULL, 'app/categories/custom/78985b267695b0d282a596be6750409e.png', NULL, 24, 25, 1, NULL, 1),
(456, 'en', 455, 0, '1,2,3,4,5,6,7,8,9,10', 'Other', 'other', NULL, 'app/categories/custom/78985b267695b0d282a596be6750409e.png', NULL, 24, 25, 1, NULL, 1),
(457, 'ar', 457, 0, '1,8', 'إكسسوارات وقطع غيار', 'إكسسوارات-وقطع-غيار', NULL, 'app/categories/custom/95ba81028c96c5155634a258e8080bc1.png', NULL, 18, 19, 1, NULL, 1),
(458, 'en', 457, 0, '1,8', 'Accessories', 'accessories', NULL, 'app/categories/custom/95ba81028c96c5155634a258e8080bc1.png', NULL, 18, 19, 1, NULL, 1),
(459, 'ar', 459, 440, '', 'تويوتا', 'تويوتا-ar', NULL, 'app/categories/custom/e968ee7fb92dd8c72787f1d85da75f59.png', NULL, NULL, NULL, NULL, 'classified', 1),
(460, 'en', 459, 440, '', 'تويوتا', 'تويوتا-ar-en', NULL, 'app/categories/custom/e968ee7fb92dd8c72787f1d85da75f59.png', NULL, NULL, NULL, NULL, 'classified', 1),
(461, 'ar', 461, 440, '', 'شيفروليه', 'شيفروليه-ar', NULL, 'app/categories/custom/713c20fc177c18e801303a0abd72fe2b.png', NULL, NULL, NULL, NULL, 'classified', 1),
(462, 'en', 461, 440, '', 'شيفروليه', 'شيفروليه-ar-en', NULL, 'app/categories/custom/713c20fc177c18e801303a0abd72fe2b.png', NULL, NULL, NULL, NULL, 'classified', 1),
(463, 'ar', 463, 440, '', 'نيسان', 'نيسان-ar', NULL, 'app/categories/custom/9eb82c563408d1010260f4ae15222de5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(464, 'en', 463, 440, '', 'نيسان', 'نيسان-ar-en', NULL, 'app/categories/custom/9eb82c563408d1010260f4ae15222de5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(465, 'ar', 465, 440, '', 'فورد', 'فورد-ar', NULL, 'app/categories/custom/07478555a0e5d29713f3839013255731.png', NULL, NULL, NULL, NULL, 'classified', 1),
(466, 'en', 465, 440, '', 'فورد', 'فورد-ar-en', NULL, 'app/categories/custom/07478555a0e5d29713f3839013255731.png', NULL, NULL, NULL, NULL, 'classified', 1),
(467, 'ar', 467, 440, '', 'مرسيدس', 'مرسيدس-ar', NULL, 'app/categories/custom/bc34a5dedb72f19b3b332d26a24a08f5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(468, 'en', 467, 440, '', 'مرسيدس', 'مرسيدس-ar-en', NULL, 'app/categories/custom/bc34a5dedb72f19b3b332d26a24a08f5.png', NULL, NULL, NULL, NULL, 'classified', 1),
(469, 'ar', 469, 440, '', 'جي إم سي', 'جي إم سي-ar', NULL, 'app/categories/custom/42d9627f3d83f40275d5307b5f77d1b4.png', NULL, NULL, NULL, NULL, 'classified', 1),
(470, 'en', 469, 440, '', 'جي إم سي', 'جي إم سي-ar-en', NULL, 'app/categories/custom/42d9627f3d83f40275d5307b5f77d1b4.png', NULL, NULL, NULL, NULL, 'classified', 1),
(471, 'ar', 471, 440, '', 'بي إم دبليو', 'بي إم دبليو-ar', NULL, 'app/categories/custom/eb21732016508aaaabf1477e43ec0e24.png', NULL, NULL, NULL, NULL, 'classified', 1),
(472, 'en', 471, 440, '', 'بي إم دبليو', 'بي إم دبليو-ar-en', NULL, 'app/categories/custom/eb21732016508aaaabf1477e43ec0e24.png', NULL, NULL, NULL, NULL, 'classified', 1),
(473, 'ar', 473, 440, '', 'لكزس', 'لكزس-ar', NULL, 'app/categories/custom/89accfac3e02b071947ea7cc04317dfe.png', NULL, NULL, NULL, NULL, 'classified', 1),
(474, 'en', 473, 440, '', 'لكزس', 'لكزس-ar-en', NULL, 'app/categories/custom/89accfac3e02b071947ea7cc04317dfe.png', NULL, NULL, NULL, NULL, 'classified', 1),
(475, 'ar', 475, 440, '', 'فوليكس', 'فوليكس', 'فوليكس', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(476, 'en', 475, 440, '', 'فوليكس', 'فوليكس-en', 'فوليكس', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(477, 'ar', 477, 440, '', 'شاهين', 'شاهين', 'شاهين', 'app/categories/custom/019521fb7d92827ce78359b2b269b300.png', NULL, NULL, NULL, NULL, NULL, 1),
(478, 'en', 477, 440, '', 'شاهين', 'شاهين-en', 'شاهين', 'app/categories/custom/019521fb7d92827ce78359b2b269b300.png', NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqacategory_field`
--

CREATE TABLE `theqqacategory_field` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `field_id` int(10) UNSIGNED DEFAULT NULL,
  `disabled_in_subcategories` tinyint(1) UNSIGNED DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqacategory_field`
--

INSERT INTO `theqqacategory_field` (`id`, `category_id`, `field_id`, `disabled_in_subcategories`, `parent_id`, `lft`, `rgt`, `depth`) VALUES
(40, 314, 56, 1, NULL, NULL, NULL, NULL),
(41, 310, 52, 0, NULL, NULL, NULL, NULL),
(42, 312, 54, 0, NULL, NULL, NULL, NULL),
(43, 316, 58, 0, NULL, NULL, NULL, NULL),
(44, 308, 59, 0, NULL, NULL, NULL, NULL),
(46, 154, 28, 0, 0, 8, 9, 1),
(49, 154, 29, 0, 0, 4, 5, 1),
(50, 440, 29, 0, 0, 2, 3, 1),
(53, 154, 50, 0, 0, 2, 3, 1),
(55, 154, 33, 0, 0, 6, 7, 1),
(59, 440, 28, 0, 0, 8, 9, 1),
(60, 440, 33, 0, 0, 4, 5, 1),
(61, 440, 50, 0, 0, 6, 7, 1),
(62, 477, 63, 0, NULL, NULL, NULL, NULL),
(63, 453, 50, 0, NULL, NULL, NULL, NULL),
(66, 453, 33, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqacities`
--

CREATE TABLE `theqqacities` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'ISO-3166 2-letter country code, 2 characters',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'name of geographical point (utf8) varchar(200)',
  `asciiname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'name of geographical point in plain ascii characters, varchar(200)',
  `latitude` float DEFAULT NULL COMMENT 'latitude in decimal degrees (wgs84)',
  `longitude` float DEFAULT NULL COMMENT 'longitude in decimal degrees (wgs84)',
  `feature_class` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'see http://www.geonames.org/export/codes.html, char(1)',
  `feature_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'see http://www.geonames.org/export/codes.html, varchar(10)',
  `subadmin1_code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)',
  `subadmin2_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80)',
  `population` bigint(20) DEFAULT NULL COMMENT 'bigint (4 byte int)',
  `time_zone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the timezone id (see file timeZone.txt)',
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `theqqacities`
--

INSERT INTO `theqqacities` (`id`, `country_code`, `name`, `asciiname`, `latitude`, `longitude`, `feature_class`, `feature_code`, `subadmin1_code`, `subadmin2_code`, `population`, `time_zone`, `active`, `created_at`, `updated_at`) VALUES
(100425, 'SA', 'ينبع', 'ينبع', 24.0895, 38.0618, 'P', 'PPL', 'SA.05', NULL, 200161, 'Asia/Riyadh', 1, '2017-05-23 04:00:00', '2019-01-10 12:41:26'),
(100926, 'SA', 'أملج', 'أملج', 25.0213, 37.2685, 'P', 'PPL', 'SA.19', NULL, 33874, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:42:37'),
(101035, 'SA', 'أم الساهك', 'أم الساهك', 26.6536, 49.9164, 'P', 'PPL', 'SA.06', NULL, 11813, 'Asia/Riyadh', 1, '2013-06-22 04:00:00', '2019-01-10 12:43:21'),
(101312, 'SA', 'طريف', 'طريف', 31.6725, 38.6637, 'P', 'PPL', 'SA.15', NULL, 40819, 'Asia/Riyadh', 1, '2016-09-20 04:00:00', '2019-01-10 12:43:57'),
(101322, 'SA', 'تربة', 'تربة', 21.2141, 41.6331, 'P', 'PPL', 'SA.14', NULL, 23235, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:44:50'),
(101344, 'SA', 'التـَوبِي', 'التـَوبِي', 26.5578, 49.9917, 'P', 'PPL', 'SA.06', NULL, 7740, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:45:49'),
(101554, 'SA', 'تاروت', 'تاروت', 26.5733, 50.0403, 'P', 'PPL', 'SA.06', NULL, 85371, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 12:46:28'),
(101581, 'SA', 'تنومة', 'تنومة', 27.1, 44.1333, 'P', 'PPL', 'SA.08', NULL, 13594, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 12:47:19'),
(101628, 'SA', 'تبوك', 'تبوك', 28.3998, 36.5715, 'P', 'PPLA', 'SA.19', NULL, 455450, 'Asia/Riyadh', 1, '2015-09-12 04:00:00', '2019-01-09 18:20:26'),
(101631, 'SA', 'طبرجل', 'طبرجل', 30.4999, 38.216, 'P', 'PPL', 'SA.20', NULL, 40019, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:49:27'),
(101633, 'SA', 'تبالة', 'تبالة', 19.95, 42.4, 'P', 'PPL', 'SA.11', NULL, 5151, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 12:50:15'),
(101760, 'SA', 'سلطانة', 'سلطانة', 24.4926, 39.5857, 'P', 'PPL', 'SA.05', NULL, 946697, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-09 18:19:11'),
(102318, 'SA', 'سيهات', 'سيهات', 26.4834, 50.0485, 'P', 'PPL', 'SA.06', NULL, 66702, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 12:50:49'),
(102451, 'SA', 'صامطة', 'صامطة', 16.596, 42.9444, 'P', 'PPL', 'SA.17', NULL, 26945, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:51:53'),
(102527, 'SA', 'سكاكا', 'سكاكا', 29.9697, 40.2064, 'P', 'PPLA', 'SA.20', NULL, 128332, 'Asia/Riyadh', 1, '2015-12-13 05:00:00', '2019-01-10 12:52:28'),
(102585, 'SA', 'صفوى', 'صفوى', 26.6497, 49.9552, 'P', 'PPL', 'SA.06', NULL, 45876, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 12:53:29'),
(102651, 'SA', 'صبيا', 'صبيا', 17.1495, 42.6254, 'P', 'PPL', 'SA.17', NULL, 54108, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:54:06'),
(102985, 'SA', 'رحيمة', 'رحيمة', 26.7079, 50.0619, 'P', 'PPL', 'SA.06', NULL, 41188, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:55:15'),
(103035, 'SA', 'رابغ', 'رابغ', 22.7986, 39.0349, 'P', 'PPL', 'SA.14', NULL, 41759, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 12:55:46'),
(103369, 'SA', 'بيشة', 'بيشة', 20.0005, 42.6052, 'P', 'PPL', 'SA.11', NULL, 81828, 'Asia/Riyadh', 1, '2017-02-13 05:00:00', '2019-01-10 12:56:41'),
(103630, 'SA', 'نجران', 'نجران', 17.4933, 44.1277, 'P', 'PPLA', 'SA.16', NULL, 258573, 'Asia/Riyadh', 1, '2017-07-05 04:00:00', '2019-01-10 12:57:33'),
(103922, 'SA', 'مليجة', 'مليجة', 27.271, 48.4242, 'P', 'PPL', 'SA.06', NULL, 5247, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 12:58:37'),
(104269, 'SA', 'مسلية', 'Mislīyah', 17.4599, 42.5572, 'P', 'PPL', 'SA.17', NULL, 6117, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:47:21'),
(104376, 'SA', 'مرات', 'مرات', 25.0701, 45.4615, 'P', 'PPL', 'SA.10', NULL, 8885, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 13:48:15'),
(104515, 'SA', 'مكة', 'مكة', 21.4266, 39.8256, 'P', 'PPLA', 'SA.14', NULL, 1323624, 'Asia/Riyadh', 1, '2014-04-29 04:00:00', '2019-01-09 18:18:19'),
(105072, 'SA', 'خميس مشيط', 'خميس مشيط', 18.3, 42.7333, 'P', 'PPL', 'SA.11', NULL, 387553, 'Asia/Riyadh', 1, '2013-11-25 05:00:00', '2019-01-09 18:22:51'),
(105252, 'SA', 'جليجلة', 'جليجلة', 25.5, 49.6, 'P', 'PPL', 'SA.06', NULL, 5359, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:48:57'),
(105299, 'SA', 'جازان', 'جازان', 16.8892, 42.5511, 'P', 'PPLA', 'SA.17', NULL, 105198, 'Asia/Riyadh', 1, '2014-02-01 05:00:00', '2019-01-10 13:49:54'),
(105343, 'SA', 'جدة', 'جدة', 21.5424, 39.198, 'P', 'PPL', 'SA.14', NULL, 2867446, 'Asia/Riyadh', 1, '2017-06-22 04:00:00', '2019-01-09 18:18:03'),
(106281, 'SA', 'حائل', 'حائل', 27.5219, 41.6907, 'P', 'PPLA', 'SA.13', NULL, 267005, 'Asia/Riyadh', 1, '2016-01-28 05:00:00', '2019-01-10 13:50:06'),
(106297, 'SA', 'حفر الباطن', 'حفر الباطن', 28.4328, 45.9708, 'P', 'PPL', 'SA.06', NULL, 271642, 'Asia/Riyadh', 1, '2017-09-12 04:00:00', '2019-01-09 18:25:12'),
(106744, 'SA', 'فرسان', 'فرسان', 16.7022, 42.1183, 'P', 'PPL', 'SA.17', NULL, 10527, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:51:38'),
(106909, 'SA', 'ضبا', 'ضبا', 27.3513, 35.6901, 'P', 'PPL', 'SA.19', NULL, 22000, 'Asia/Riyadh', 1, '2016-01-28 05:00:00', '2019-01-10 13:52:02'),
(107304, 'SA', 'بريدة', 'بريدة', 26.326, 43.975, 'P', 'PPLA', 'SA.08', NULL, 391336, 'Asia/Riyadh', 1, '2014-04-29 04:00:00', '2019-01-09 18:21:22'),
(107312, 'SA', 'بقيق', 'بقيق', 25.934, 49.6688, 'P', 'PPL', 'SA.06', NULL, 29474, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-10 13:52:30'),
(107744, 'SA', 'بدر حنين', 'بدر حنين', 23.7829, 38.7905, 'P', 'PPL', 'SA.05', NULL, 27257, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:53:34'),
(107781, 'SA', 'الزلفي', 'الزلفي', 26.2994, 44.8154, 'P', 'PPL', 'SA.10', NULL, 53144, 'Asia/Riyadh', 1, '2017-07-05 04:00:00', '2019-01-10 13:53:53'),
(107797, 'SA', 'الظهران', 'الظهران', 26.2886, 50.114, 'P', 'PPL', 'SA.06', NULL, 99540, 'Asia/Riyadh', 1, '2013-11-17 05:00:00', '2019-01-10 13:54:11'),
(107959, 'SA', 'الطرف', 'الطرف', 25.3623, 49.7276, 'P', 'PPL', 'SA.06', NULL, 21386, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-10 13:56:31'),
(107968, 'SA', 'الطائف', 'الطائف', 21.2703, 40.4158, 'P', 'PPL', 'SA.14', NULL, 530848, 'Asia/Riyadh', 1, '2015-09-05 04:00:00', '2019-01-09 18:20:04'),
(108048, 'SA', 'السليل', 'السليل', 20.4607, 45.5779, 'P', 'PPL', 'SA.10', NULL, 24097, 'Asia/Riyadh', 1, '2017-07-05 04:00:00', '2019-01-10 13:57:16'),
(108121, 'SA', 'ساجر', 'ساجر', 25.1825, 44.5996, 'P', 'PPL', 'SA.10', NULL, 11717, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:57:38'),
(108142, 'SA', 'السفانية', 'السفانية', 27.9708, 48.73, 'P', 'PPL', 'SA.06', NULL, 7014, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 13:58:07'),
(108410, 'SA', 'الرياض', 'الرياض', 24.6877, 46.7219, 'P', 'PPLC', 'SA.10', NULL, 4205961, 'Asia/Riyadh', 1, '2016-01-21 05:00:00', '2019-01-09 18:16:36'),
(108435, 'SA', 'الرس', 'الرس', 25.8694, 43.4973, 'P', 'PPL', 'SA.08', NULL, 81728, 'Asia/Riyadh', 1, '2017-12-06 05:00:00', '2019-01-10 13:58:29'),
(108512, 'SA', 'عرعر', 'عرعر', 30.9753, 41.0381, 'P', 'PPLA', 'SA.15', NULL, 148540, 'Asia/Riyadh', 1, '2016-10-22 04:00:00', '2019-01-10 13:58:58'),
(108617, 'SA', 'النماص', 'النماص', 19.1455, 42.1201, 'P', 'PPL', 'SA.11', NULL, 24153, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 13:59:16'),
(108648, 'SA', 'القريات', 'القريات', 31.3318, 37.3428, 'P', 'PPL', 'SA.20', 'SA.20.10972289', 102903, 'Asia/Riyadh', 1, '2016-10-23 04:00:00', '2019-01-10 13:59:30'),
(108773, 'SA', 'الوجه', 'الوجه', 26.2455, 36.4525, 'P', 'PPL', 'SA.19', NULL, 26636, 'Asia/Riyadh', 1, '2017-08-31 04:00:00', '2019-01-10 13:59:45'),
(108841, 'SA', 'العلا', 'العلا', 26.6085, 37.9232, 'P', 'PPL', 'SA.05', NULL, 32413, 'Asia/Riyadh', 1, '2016-12-18 05:00:00', '2019-01-10 14:00:26'),
(108890, 'SA', 'القرين', 'القرين', 25.4833, 49.6, 'P', 'PPL', 'SA.06', NULL, 12013, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:02:15'),
(108918, 'SA', 'القيصومة', 'القيصومة', 28.3112, 46.1273, 'P', 'PPL', 'SA.06', NULL, 20685, 'Asia/Riyadh', 1, '2016-10-23 04:00:00', '2019-01-10 14:02:54'),
(108927, 'SA', 'القطيف', 'القطيف', 26.5654, 50.0089, 'P', 'PPL', 'SA.06', NULL, 98259, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 14:03:14'),
(108957, 'SA', 'القارة', 'القارة', 25.4167, 49.6667, 'P', 'PPL', 'SA.06', NULL, 9106, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:04:06'),
(109008, 'SA', 'المطيرفي', 'المطيرفي', 25.4788, 49.5582, 'P', 'PPL', 'SA.06', NULL, 5962, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-10 14:04:27'),
(109059, 'SA', 'المنيزلة', 'المنيزلة', 25.3833, 49.6667, 'P', 'PPL', 'SA.06', NULL, 16296, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:06:06'),
(109101, 'SA', 'المبرّز', 'المبرّز', 25.4077, 49.5903, 'P', 'PPL', 'SA.06', NULL, 290802, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-09 18:24:23'),
(109118, 'SA', 'المندق', 'المندق', 20.1588, 41.2834, 'P', 'PPL', 'SA.02', NULL, 9218, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 14:07:45'),
(109131, 'SA', 'المذنب', 'المذنب', 25.8601, 44.2223, 'P', 'PPL', 'SA.08', NULL, 60870, 'Asia/Riyadh', 1, '2012-10-09 04:00:00', '2019-01-10 14:36:12'),
(109165, 'SA', 'المركز', 'المركز', 25.4, 49.7333, 'P', 'PPL', 'SA.06', NULL, 6464, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:36:35'),
(109223, 'SA', 'المدينة', 'المدينة', 24.4686, 39.6142, 'P', 'PPLA', 'SA.05', NULL, 1300000, 'Asia/Riyadh', 1, '2014-11-05 05:00:00', '2019-01-09 18:18:52'),
(109323, 'SA', 'الخبر', 'الخبر', 26.2794, 50.2083, 'P', 'PPL', 'SA.06', NULL, 165799, 'Asia/Riyadh', 1, '2013-11-17 05:00:00', '2019-01-10 14:37:54'),
(109353, 'SA', 'الخارج', 'الخارج', 24.1554, 47.3346, 'P', 'PPL', 'SA.10', NULL, 425300, 'Asia/Riyadh', 1, '2017-09-13 04:00:00', '2019-01-09 18:20:50'),
(109380, 'SA', 'الخفجي', 'الخفجي', 28.4391, 48.4913, 'P', 'PPL', 'SA.06', NULL, 54857, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:38:17'),
(109417, 'SA', 'الجموم', 'الجموم', 21.6169, 39.6981, 'P', 'PPL', 'SA.14', NULL, 22207, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:38:33'),
(109435, 'SA', 'الجبيل', 'الجبيل', 27.0174, 49.6225, 'P', 'PPL', 'SA.06', NULL, 237274, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 14:38:48'),
(109436, 'SA', 'Al Jubayl', 'Al Jubayl', 25.4, 49.65, 'P', 'PPL', 'SA.06', NULL, 9108, 'Asia/Riyadh', 0, '2012-01-16 05:00:00', '2019-01-10 14:40:31'),
(109481, 'SA', 'الجرادية', 'الجرادية', 16.5795, 42.9124, 'P', 'PPL', 'SA.17', NULL, 7396, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:41:01'),
(109502, 'SA', 'الجفر', 'الجفر', 25.3774, 49.7215, 'P', 'PPL', 'SA.06', NULL, 8715, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-10 14:41:26'),
(109571, 'SA', 'الهفوف', 'الهفوف', 25.3647, 49.5876, 'P', 'PPL', 'SA.06', NULL, 293179, 'Asia/Riyadh', 1, '2017-10-03 04:00:00', '2019-01-09 18:23:21'),
(109878, 'SA', 'البكيرية', 'البكيرية', 26.1442, 43.6593, 'P', 'PPL', 'SA.08', NULL, 25153, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 14:41:57'),
(109915, 'SA', 'البطالية', 'البطالية', 25.4333, 49.6333, 'P', 'PPL', 'SA.06', NULL, 16606, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:42:44'),
(109953, 'SA', 'الباحة', 'الباحة', 20.0129, 41.4677, 'P', 'PPLA', 'SA.02', NULL, 88419, 'Asia/Riyadh', 1, '2016-12-18 05:00:00', '2019-01-10 14:43:00'),
(110031, 'SA', 'الأرطاوية', 'الأرطاوية', 26.5039, 45.3481, 'P', 'PPL', 'SA.10', NULL, 9152, 'Asia/Riyadh', 1, '2018-01-09 05:00:00', '2019-01-10 14:43:20'),
(110107, 'SA', 'الأوجام', 'الأوجام', 26.5632, 49.9433, 'P', 'PPL', 'SA.06', NULL, 11460, 'Asia/Riyadh', 1, '2017-11-07 05:00:00', '2019-01-10 14:43:46'),
(110250, 'SA', 'عفيف', 'عفيف', 23.9065, 42.9172, 'P', 'PPL', 'SA.10', NULL, 40648, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:44:40'),
(110314, 'SA', 'الدلم', 'الدلم', 23.991, 47.1618, 'P', 'PPL', 'SA.10', NULL, 35371, 'Asia/Riyadh', 1, '2014-09-30 04:00:00', '2019-01-10 14:44:56'),
(110325, 'SA', 'الدوادمي', 'الدوادمي', 24.5077, 44.3924, 'P', 'PPL', 'SA.10', NULL, 54474, 'Asia/Riyadh', 1, '2017-07-05 04:00:00', '2019-01-10 14:45:14'),
(110328, 'SA', 'الدرب', 'الدرب', 17.7229, 42.2526, 'P', 'PPL', 'SA.17', NULL, 5378, 'Asia/Riyadh', 1, '2017-06-06 04:00:00', '2019-01-10 14:45:40'),
(110336, 'SA', 'الدمام', 'الدمام', 26.4344, 50.1033, 'P', 'PPLA', 'SA.06', NULL, 768602, 'Asia/Riyadh', 1, '2014-08-19 04:00:00', '2019-01-09 18:19:29'),
(110619, 'SA', 'أبو عريش', 'أبو عريش', 16.9689, 42.8325, 'P', 'PPL', 'SA.17', NULL, 49171, 'Asia/Riyadh', 1, '2012-01-16 05:00:00', '2019-01-10 14:46:10'),
(110690, 'SA', 'أبها', 'أبها', 18.2164, 42.5053, 'P', 'PPLA', 'SA.11', NULL, 210886, 'Asia/Riyadh', 1, '2014-10-16 04:00:00', '2019-01-10 14:46:32'),
(392753, 'SA', 'صوير', 'صوير', 30.1171, 40.3893, 'P', 'PPL', 'SA.20', NULL, 8515, 'Asia/Riyadh', 1, '2012-01-19 05:00:00', '2019-01-10 14:46:46'),
(396550, 'SA', 'تمير', 'تمير', 25.7039, 45.868, 'P', 'PPL', 'SA.10', NULL, 8246, 'Asia/Riyadh', 1, '2012-01-19 05:00:00', '2019-01-10 14:48:09'),
(397513, 'SA', 'الفويلق', 'الفويلق', 26.4436, 43.2516, 'P', 'PPL', 'SA.08', NULL, 5205, 'Asia/Riyadh', 1, '2018-01-10 05:00:00', '2019-01-10 14:48:45'),
(399518, 'SA', 'المجاردة', 'المجاردة', 19.1236, 41.9111, 'P', 'PPL', 'SA.11', NULL, 14830, 'Asia/Riyadh', 1, '2013-08-11 04:00:00', '2019-01-10 14:49:06'),
(409993, 'SA', 'الموية', 'الموية', 22.4333, 41.7583, 'P', 'PPL', 'SA.14', NULL, 7364, 'Asia/Riyadh', 1, '2012-01-19 05:00:00', '2019-01-10 14:49:23'),
(410084, 'SA', 'الهدا', 'الهدا', 21.3675, 40.2869, 'P', 'PPL', 'SA.14', NULL, 6885, 'Asia/Riyadh', 1, '2012-01-19 05:00:00', '2019-01-10 14:49:38'),
(410096, 'SA', 'الشفا', 'الشفا', 21.0721, 40.3119, 'P', 'PPL', 'SA.14', NULL, 72190, 'Asia/Riyadh', 1, '2013-09-16 04:00:00', '2019-01-10 14:49:57'),
(410874, 'SA', 'المزهرة', 'المزهرة', 16.8261, 42.7333, 'P', 'PPL', 'SA.17', NULL, 5529, 'Asia/Riyadh', 1, '2017-06-07 04:00:00', '2019-01-10 14:50:20'),
(8394316, 'SA', 'عنيزة', 'عنيزة', 26.0843, 43.9935, 'P', 'PPL', 'SA.08', NULL, 163729, 'Asia/Riyadh', 1, '2016-02-24 05:00:00', '2019-01-10 14:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `theqqacontinents`
--

CREATE TABLE `theqqacontinents` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqacontinents`
--

INSERT INTO `theqqacontinents` (`id`, `code`, `name`, `active`) VALUES
(1, 'AF', 'Africa', 1),
(2, 'AN', 'Antarctica', 1),
(3, 'AS', 'Asia', 1),
(4, 'EU', 'Europe', 1),
(5, 'NA', 'North America', 1),
(6, 'OC', 'Oceania', 1),
(7, 'SA', 'South America', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqacountries`
--

CREATE TABLE `theqqacountries` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_numeric` int(10) UNSIGNED DEFAULT NULL,
  `fips` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `asciiname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capital` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` int(10) UNSIGNED DEFAULT NULL,
  `population` int(10) UNSIGNED DEFAULT NULL,
  `continent_code` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tld` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_code` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code_format` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code_regex` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `languages` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `neighbours` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `equivalent_fips_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_type` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `admin_field_active` tinyint(1) UNSIGNED DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `theqqacountries`
--

INSERT INTO `theqqacountries` (`id`, `code`, `iso3`, `iso_numeric`, `fips`, `name`, `asciiname`, `capital`, `area`, `population`, `continent_code`, `tld`, `currency_code`, `phone`, `postal_code_format`, `postal_code_regex`, `languages`, `neighbours`, `equivalent_fips_code`, `background_image`, `admin_type`, `admin_field_active`, `active`, `created_at`, `updated_at`) VALUES
(1, 'AD', 'AND', 20, 'AN', 'Andorra', 'Andorra', 'Andorra la Vella', 468, 84000, 'EU', '.ad', 'EUR', '376', 'AD###', '^(?:AD)*(d{3})$', 'ca', 'ES,FR', '', NULL, '0', 0, 0, NULL, NULL),
(2, 'AE', 'ARE', 784, 'AE', 'al-Imārāt', 'United Arab Emirates', 'Abu Dhabi', 82880, 4975593, 'AS', '.ae', 'AED', '971', '', '', 'ar-AE,fa,en,hi,ur', 'SA,OM', '', NULL, '0', 0, 0, NULL, NULL),
(3, 'AF', 'AFG', 4, 'AF', 'Afġānistān', 'Afghanistan', 'Kabul', 647500, 29121286, 'AS', '.af', 'AFN', '93', '', '', 'fa-AF,ps,uz-AF,tk', 'TM,CN,IR,TJ,PK,UZ', '', NULL, '0', 0, 0, NULL, NULL),
(4, 'AG', 'ATG', 28, 'AC', 'Antigua and Barbuda', 'Antigua and Barbuda', 'St. John\'s', 443, 86754, 'NA', '.ag', 'XCD', '+1-268', '', '', 'en-AG', '', '', NULL, '0', 0, 0, NULL, NULL),
(5, 'AI', 'AIA', 660, 'AV', 'Anguilla', 'Anguilla', 'The Valley', 102, 13254, 'NA', '.ai', 'XCD', '+1-264', '', '', 'en-AI', '', '', NULL, '0', 0, 0, NULL, NULL),
(6, 'AL', 'ALB', 8, 'AL', 'Shqipëria', 'Albania', 'Tirana', 28748, 2986952, 'EU', '.al', 'ALL', '355', '', '', 'sq,el', 'MK,GR,ME,RS,XK', '', NULL, '0', 0, 0, NULL, NULL),
(7, 'AM', 'ARM', 51, 'AM', 'Hayastan', 'Armenia', 'Yerevan', 29800, 2968000, 'AS', '.am', 'AMD', '374', '######', '^(d{6})$', 'hy', 'GE,IR,AZ,TR', '', NULL, '0', 0, 0, NULL, NULL),
(8, 'AN', 'ANT', 530, 'NT', 'Netherlands Antilles', 'Netherlands Antilles', 'Willemstad', 960, 136197, 'NA', '.an', 'ANG', '599', '', '', 'nl-AN,en,es', 'GP', '', NULL, '0', 0, 0, NULL, NULL),
(9, 'AO', 'AGO', 24, 'AO', 'Angola', 'Angola', 'Luanda', 1246700, 13068161, 'AF', '.ao', 'AOA', '244', '', '', 'pt-AO', 'CD,NA,ZM,CG', '', NULL, '0', 0, 0, NULL, NULL),
(10, 'AQ', 'ATA', 10, 'AY', 'Antarctica', 'Antarctica', '', 14000000, 0, 'AN', '.aq', '', '', '', '', '', '', '', NULL, '0', 0, 0, NULL, NULL),
(11, 'AR', 'ARG', 32, 'AR', 'Argentina', 'Argentina', 'Buenos Aires', 2766890, 41343201, 'SA', '.ar', 'ARS', '54', '@####@@@', '^([A-Z]d{4}[A-Z]{3})$', 'es-AR,en,it,de,fr,gn', 'CL,BO,UY,PY,BR', '', NULL, '0', 0, 0, NULL, NULL),
(12, 'AS', 'ASM', 16, 'AQ', 'American Samoa', 'American Samoa', 'Pago Pago', 199, 57881, 'OC', '.as', 'USD', '+1-684', '', '', 'en-AS,sm,to', '', '', NULL, '0', 0, 0, NULL, NULL),
(13, 'AT', 'AUT', 40, 'AU', 'Österreich', 'Austria', 'Vienna', 83858, 8205000, 'EU', '.at', 'EUR', '43', '####', '^(d{4})$', 'de-AT,hr,hu,sl', 'CH,DE,HU,SK,CZ,IT,SI,LI', '', NULL, '0', 0, 0, NULL, NULL),
(14, 'AU', 'AUS', 36, 'AS', 'Australia', 'Australia', 'Canberra', 7686850, 21515754, 'OC', '.au', 'AUD', '61', '####', '^(d{4})$', 'en-AU', '', '', NULL, '0', 0, 0, NULL, NULL),
(15, 'AW', 'ABW', 533, 'AA', 'Aruba', 'Aruba', 'Oranjestad', 193, 71566, 'NA', '.aw', 'AWG', '297', '', '', 'nl-AW,es,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(16, 'AX', 'ALA', 248, '', 'Aland Islands', 'Aland Islands', 'Mariehamn', 1580, 26711, 'EU', '.ax', 'EUR', '+358-18', '#####', '^(?:FI)*(d{5})$', 'sv-AX', '', 'FI', NULL, '0', 0, 0, NULL, NULL),
(17, 'AZ', 'AZE', 31, 'AJ', 'Azərbaycan', 'Azerbaijan', 'Baku', 86600, 8303512, 'AS', '.az', 'AZN', '994', 'AZ ####', '^(?:AZ)*(d{4})$', 'az,ru,hy', 'GE,IR,AM,TR,RU', '', NULL, '0', 0, 0, NULL, NULL),
(18, 'BA', 'BIH', 70, 'BK', 'Bosna i Hercegovina', 'Bosnia and Herzegovina', 'Sarajevo', 51129, 4590000, 'EU', '.ba', 'BAM', '387', '#####', '^(d{5})$', 'bs,hr-BA,sr-BA', 'HR,ME,RS', '', NULL, '0', 0, 0, NULL, NULL),
(19, 'BB', 'BRB', 52, 'BB', 'Barbados', 'Barbados', 'Bridgetown', 431, 285653, 'NA', '.bb', 'BBD', '+1-246', 'BB#####', '^(?:BB)*(d{5})$', 'en-BB', '', '', NULL, '0', 0, 0, NULL, NULL),
(20, 'BD', 'BGD', 50, 'BG', 'Bāṅlādēś', 'Bangladesh', 'Dhaka', 144000, 156118464, 'AS', '.bd', 'BDT', '880', '####', '^(d{4})$', 'bn-BD,en', 'MM,IN', '', NULL, '0', 0, 0, NULL, NULL),
(21, 'BE', 'BEL', 56, 'BE', 'Belgique', 'Belgium', 'Brussels', 30510, 10403000, 'EU', '.be', 'EUR', '32', '####', '^(d{4})$', 'nl-BE,fr-BE,de-BE', 'DE,NL,LU,FR', '', NULL, '0', 0, 0, NULL, NULL),
(22, 'BF', 'BFA', 854, 'UV', 'Burkina Faso', 'Burkina Faso', 'Ouagadougou', 274200, 16241811, 'AF', '.bf', 'XOF', '226', '', '', 'fr-BF', 'NE,BJ,GH,CI,TG,ML', '', NULL, '0', 0, 0, NULL, NULL),
(23, 'BG', 'BGR', 100, 'BU', 'Bŭlgarija', 'Bulgaria', 'Sofia', 110910, 7148785, 'EU', '.bg', 'BGN', '359', '####', '^(d{4})$', 'bg,tr-BG,rom', 'MK,GR,RO,TR,RS', '', NULL, '0', 0, 0, NULL, NULL),
(24, 'BH', 'BHR', 48, 'BA', 'al-Baḥrayn', 'Bahrain', 'Manama', 665, 738004, 'AS', '.bh', 'BHD', '973', '####|###', '^(d{3}d?)$', 'ar-BH,en,fa,ur', '', '', NULL, '0', 0, 0, NULL, NULL),
(25, 'BI', 'BDI', 108, 'BY', 'Burundi', 'Burundi', 'Bujumbura', 27830, 9863117, 'AF', '.bi', 'BIF', '257', '', '', 'fr-BI,rn', 'TZ,CD,RW', '', NULL, '0', 0, 0, NULL, NULL),
(26, 'BJ', 'BEN', 204, 'BN', 'Bénin', 'Benin', 'Porto-Novo', 112620, 9056010, 'AF', '.bj', 'XOF', '+229', '', '', 'fr-BJ', 'NE,TG,BF,NG', '', NULL, '0', 0, 0, NULL, '2016-05-10 01:55:29'),
(27, 'BL', 'BLM', 652, 'TB', 'Saint Barthelemy', 'Saint Barthelemy', 'Gustavia', 21, 8450, 'NA', '.gp', 'EUR', '590', '### ###', '', 'fr', '', '', NULL, '0', 0, 0, NULL, NULL),
(28, 'BM', 'BMU', 60, 'BD', 'Bermuda', 'Bermuda', 'Hamilton', 53, 65365, 'NA', '.bm', 'BMD', '+1-441', '@@ ##', '^([A-Z]{2}d{2})$', 'en-BM,pt', '', '', NULL, '0', 0, 0, NULL, NULL),
(29, 'BN', 'BRN', 96, 'BX', 'Brunei Darussalam', 'Brunei', 'Bandar Seri Begawan', 5770, 395027, 'AS', '.bn', 'BND', '673', '@@####', '^([A-Z]{2}d{4})$', 'ms-BN,en-BN', 'MY', '', NULL, '0', 0, 0, NULL, NULL),
(30, 'BO', 'BOL', 68, 'BL', 'Bolivia', 'Bolivia', 'Sucre', 1098580, 9947418, 'SA', '.bo', 'BOB', '591', '', '', 'es-BO,qu,ay', 'PE,CL,PY,BR,AR', '', NULL, '0', 0, 0, NULL, NULL),
(31, 'BQ', 'BES', 535, '', 'Bonaire, Saint Eustatius and Saba ', 'Bonaire, Saint Eustatius and Saba ', '', 328, 18012, 'NA', '.bq', 'USD', '599', '', '', 'nl,pap,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(32, 'BR', 'BRA', 76, 'BR', 'Brasil', 'Brazil', 'Brasilia', 8511965, 201103330, 'SA', '.br', 'BRL', '55', '#####-###', '^(d{8})$', 'pt-BR,es,en,fr', 'SR,PE,BO,UY,GY,PY,GF,VE,CO,AR', '', NULL, '0', 0, 0, NULL, NULL),
(33, 'BS', 'BHS', 44, 'BF', 'Bahamas', 'Bahamas', 'Nassau', 13940, 301790, 'NA', '.bs', 'BSD', '+1-242', '', '', 'en-BS', '', '', NULL, '0', 0, 0, NULL, NULL),
(34, 'BT', 'BTN', 64, 'BT', 'Druk-yul', 'Bhutan', 'Thimphu', 47000, 699847, 'AS', '.bt', 'BTN', '975', '', '', 'dz', 'CN,IN', '', NULL, '0', 0, 0, NULL, NULL),
(35, 'BV', 'BVT', 74, 'BV', 'Bouvet Island', 'Bouvet Island', '', 49, 0, 'AN', '.bv', 'NOK', '', '', '', '', '', '', NULL, '0', 0, 0, NULL, NULL),
(36, 'BW', 'BWA', 72, 'BC', 'Botswana', 'Botswana', 'Gaborone', 600370, 2029307, 'AF', '.bw', 'BWP', '267', '', '', 'en-BW,tn-BW', 'ZW,ZA,NA', '', NULL, '0', 0, 0, NULL, NULL),
(37, 'BY', 'BLR', 112, 'BO', 'Biełaruś', 'Belarus', 'Minsk', 207600, 9685000, 'EU', '.by', 'BYR', '375', '######', '^(d{6})$', 'be,ru', 'PL,LT,UA,RU,LV', '', NULL, '0', 0, 0, NULL, NULL),
(38, 'BZ', 'BLZ', 84, 'BH', 'Belize', 'Belize', 'Belmopan', 22966, 314522, 'NA', '.bz', 'BZD', '501', '', '', 'en-BZ,es', 'GT,MX', '', NULL, '0', 0, 0, NULL, NULL),
(39, 'CA', 'CAN', 124, 'CA', 'Canada', 'Canada', 'Ottawa', 9984670, 33679000, 'NA', '.ca', 'CAD', '1', '@#@ #@#', '^([ABCEGHJKLMNPRSTVXY]d[ABCEGHJKLMNPRSTVWXYZ]) ?(d[ABCEGHJKLMNPRSTVWXYZ]d)$ ', 'en-CA,fr-CA,iu', 'US', '', NULL, '0', 0, 0, NULL, NULL),
(40, 'CC', 'CCK', 166, 'CK', 'Cocos Islands', 'Cocos Islands', 'West Island', 14, 628, 'AS', '.cc', 'AUD', '61', '', '', 'ms-CC,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(41, 'CD', 'COD', 180, 'CG', 'RDC', 'Democratic Republic of the Congo', 'Kinshasa', 2345410, 70916439, 'AF', '.cd', 'CDF', '243', '', '', 'fr-CD,ln,kg', 'TZ,CF,SS,RW,ZM,BI,UG,CG,AO', '', NULL, '0', 0, 0, NULL, NULL),
(42, 'CF', 'CAF', 140, 'CT', 'Centrafrique', 'Central African Republic', 'Bangui', 622984, 4844927, 'AF', '.cf', 'XAF', '236', '', '', 'fr-CF,sg,ln,kg', 'TD,SD,CD,SS,CM,CG', '', NULL, '0', 0, 0, NULL, NULL),
(43, 'CG', 'COG', 178, 'CF', 'Congo', 'Republic of the Congo', 'Brazzaville', 342000, 3039126, 'AF', '.cg', 'XAF', '242', '', '', 'fr-CG,kg,ln-CG', 'CF,GA,CD,CM,AO', '', NULL, '0', 0, 0, NULL, NULL),
(44, 'CH', 'CHE', 756, 'SZ', 'Switzerland', 'Switzerland', 'Berne', 41290, 7581000, 'EU', '.ch', 'CHF', '41', '####', '^(d{4})$', 'de-CH,fr-CH,it-CH,rm', 'DE,IT,LI,FR,AT', '', NULL, '0', 0, 0, NULL, NULL),
(45, 'CI', 'CIV', 384, 'IV', 'Côte d\'Ivoire', 'Ivory Coast', 'Yamoussoukro', 322460, 21058798, 'AF', '.ci', 'XOF', '225', '', '', 'fr-CI', 'LR,GH,GN,BF,ML', '', NULL, '0', 0, 0, NULL, NULL),
(46, 'CK', 'COK', 184, 'CW', 'Cook Islands', 'Cook Islands', 'Avarua', 240, 21388, 'OC', '.ck', 'NZD', '682', '', '', 'en-CK,mi', '', '', NULL, '0', 0, 0, NULL, NULL),
(47, 'CL', 'CHL', 152, 'CI', 'Chile', 'Chile', 'Santiago', 756950, 16746491, 'SA', '.cl', 'CLP', '56', '#######', '^(d{7})$', 'es-CL', 'PE,BO,AR', '', NULL, '0', 0, 0, NULL, NULL),
(48, 'CM', 'CMR', 120, 'CM', 'Cameroun', 'Cameroon', 'Yaounde', 475440, 19294149, 'AF', '.cm', 'XAF', '237', '', '', 'fr-CM,en-CM', 'TD,CF,GA,GQ,CG,NG', '', NULL, '0', 0, 0, NULL, NULL),
(49, 'CN', 'CHN', 156, 'CH', 'Zhōngguó', 'China', 'Beijing', 9596960, 1330044000, 'AS', '.cn', 'CNY', '86', '######', '^(d{6})$', 'zh-CN,yue,wuu,dta,ug,za', 'LA,BT,TJ,KZ,MN,AF,NP,MM,KG,PK,KP,RU,VN,IN', '', NULL, '0', 0, 0, NULL, NULL),
(50, 'CO', 'COL', 170, 'CO', 'Colombia', 'Colombia', 'Bogota', 1138910, 47790000, 'SA', '.co', 'COP', '57', '', '', 'es-CO', 'EC,PE,PA,BR,VE', '', NULL, '0', 0, 0, NULL, NULL),
(51, 'CR', 'CRI', 188, 'CS', 'Costa Rica', 'Costa Rica', 'San Jose', 51100, 4516220, 'NA', '.cr', 'CRC', '506', '####', '^(d{4})$', 'es-CR,en', 'PA,NI', '', NULL, '0', 0, 0, NULL, NULL),
(52, 'CS', 'SCG', 891, 'YI', 'Serbia and Montenegro', 'Serbia and Montenegro', 'Belgrade', 102350, 10829175, 'EU', '.cs', 'RSD', '381', '#####', '^(d{5})$', 'cu,hu,sq,sr', 'AL,HU,MK,RO,HR,BA,BG', '', NULL, '0', 0, 0, NULL, NULL),
(53, 'CU', 'CUB', 192, 'CU', 'Cuba', 'Cuba', 'Havana', 110860, 11423000, 'NA', '.cu', 'CUP', '53', 'CP #####', '^(?:CP)*(d{5})$', 'es-CU', 'US', '', NULL, '0', 0, 0, NULL, NULL),
(54, 'CV', 'CPV', 132, 'CV', 'Cabo Verde', 'Cape Verde', 'Praia', 4033, 508659, 'AF', '.cv', 'CVE', '238', '####', '^(d{4})$', 'pt-CV', '', '', NULL, '0', 0, 0, NULL, NULL),
(55, 'CW', 'CUW', 531, 'UC', 'Curacao', 'Curacao', ' Willemstad', 444, 141766, 'NA', '.cw', 'ANG', '599', '', '', 'nl,pap', '', '', NULL, '0', 0, 0, NULL, NULL),
(56, 'CX', 'CXR', 162, 'KT', 'Christmas Island', 'Christmas Island', 'Flying Fish Cove', 135, 1500, 'AS', '.cx', 'AUD', '61', '####', '^(d{4})$', 'en,zh,ms-CC', '', '', NULL, '0', 0, 0, NULL, NULL),
(57, 'CY', 'CYP', 196, 'CY', 'Kýpros (Kıbrıs)', 'Cyprus', 'Nicosia', 9250, 1102677, 'EU', '.cy', 'EUR', '357', '####', '^(d{4})$', 'el-CY,tr-CY,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(58, 'CZ', 'CZE', 203, 'EZ', 'Česko', 'Czech Republic', 'Prague', 78866, 10476000, 'EU', '.cz', 'CZK', '420', '### ##', '^(d{5})$', 'cs,sk', 'PL,DE,SK,AT', '', NULL, '0', 0, 0, NULL, NULL),
(59, 'DE', 'DEU', 276, 'GM', 'Deutschland', 'Germany', 'Berlin', 357021, 81802257, 'EU', '.de', 'EUR', '49', '#####', '^(d{5})$', 'de', 'CH,PL,NL,DK,BE,CZ,LU,FR,AT', '', NULL, '0', 0, 0, NULL, NULL),
(60, 'DJ', 'DJI', 262, 'DJ', 'Djibouti', 'Djibouti', 'Djibouti', 23000, 740528, 'AF', '.dj', 'DJF', '253', '', '', 'fr-DJ,ar,so-DJ,aa', 'ER,ET,SO', '', NULL, '0', 0, 0, NULL, NULL),
(61, 'DK', 'DNK', 208, 'DA', 'Danmark', 'Denmark', 'Copenhagen', 43094, 5484000, 'EU', '.dk', 'DKK', '45', '####', '^(d{4})$', 'da-DK,en,fo,de-DK', 'DE', '', NULL, '0', 0, 0, NULL, NULL),
(62, 'DM', 'DMA', 212, 'DO', 'Dominica', 'Dominica', 'Roseau', 754, 72813, 'NA', '.dm', 'XCD', '+1-767', '', '', 'en-DM', '', '', NULL, '0', 0, 0, NULL, NULL),
(63, 'DO', 'DOM', 214, 'DR', 'República Dominicana', 'Dominican Republic', 'Santo Domingo', 48730, 9823821, 'NA', '.do', 'DOP', '+809/829/849', '#####', '^(d{5})$', 'es-DO', 'HT', '', NULL, '0', 0, 0, NULL, NULL),
(64, 'DZ', 'DZA', 12, 'AG', 'Algérie', 'Algeria', 'Algiers', 2381740, 34586184, 'AF', '.dz', 'DZD', '213', '#####', '^(d{5})$', 'ar-DZ,fr', 'NE,EH,LY,MR,TN,MA,ML', '', NULL, '0', 0, 0, NULL, NULL),
(65, 'EC', 'ECU', 218, 'EC', 'Ecuador', 'Ecuador', 'Quito', 283560, 14790608, 'SA', '.ec', 'USD', '593', '@####@', '^([a-zA-Z]d{4}[a-zA-Z])$', 'es-EC', 'PE,CO', '', NULL, '0', 0, 0, NULL, NULL),
(66, 'EE', 'EST', 233, 'EN', 'Eesti', 'Estonia', 'Tallinn', 45226, 1291170, 'EU', '.ee', 'EUR', '372', '#####', '^(d{5})$', 'et,ru', 'RU,LV', '', NULL, '0', 0, 0, NULL, NULL),
(67, 'EG', 'EGY', 818, 'EG', 'Egypt', 'Egypt', 'Cairo', 1001450, 80471869, 'AF', '.eg', 'EGP', '20', '#####', '^(d{5})$', 'Ar,En', 'LY,SD,IL,PS', '', 'app/logohttp://127.0.0.1:8000', '0', 0, 0, NULL, '2020-01-27 19:04:44'),
(68, 'EH', 'ESH', 732, 'WI', 'aṣ-Ṣaḥrāwīyâ al-ʿArabīyâ', 'Western Sahara', 'El-Aaiun', 266000, 273008, 'AF', '.eh', 'MAD', '212', '', '', 'ar,mey', 'DZ,MR,MA', '', NULL, '0', 0, 0, NULL, NULL),
(69, 'ER', 'ERI', 232, 'ER', 'Ertrā', 'Eritrea', 'Asmara', 121320, 5792984, 'AF', '.er', 'ERN', '291', '', '', 'aa-ER,ar,tig,kun,ti-ER', 'ET,SD,DJ', '', NULL, '0', 0, 0, NULL, NULL),
(70, 'ES', 'ESP', 724, 'SP', 'España', 'Spain', 'Madrid', 504782, 46505963, 'EU', '.es', 'EUR', '34', '#####', '^(d{5})$', 'es-ES,ca,gl,eu,oc', 'AD,PT,GI,FR,MA', '', NULL, '0', 0, 0, NULL, NULL),
(71, 'ET', 'ETH', 231, 'ET', 'Ityoṗya', 'Ethiopia', 'Addis Ababa', 1127127, 88013491, 'AF', '.et', 'ETB', '251', '####', '^(d{4})$', 'am,en-ET,om-ET,ti-ET,so-ET,sid', 'ER,KE,SD,SS,SO,DJ', '', NULL, '0', 0, 0, NULL, NULL),
(72, 'FI', 'FIN', 246, 'FI', 'Suomi (Finland)', 'Finland', 'Helsinki', 337030, 5244000, 'EU', '.fi', 'EUR', '358', '#####', '^(?:FI)*(d{5})$', 'fi-FI,sv-FI,smn', 'NO,RU,SE', '', NULL, '0', 0, 0, NULL, NULL),
(73, 'FJ', 'FJI', 242, 'FJ', 'Viti', 'Fiji', 'Suva', 18270, 875983, 'OC', '.fj', 'FJD', '679', '', '', 'en-FJ,fj', '', '', NULL, '0', 0, 0, NULL, NULL),
(74, 'FK', 'FLK', 238, 'FK', 'Falkland Islands', 'Falkland Islands', 'Stanley', 12173, 2638, 'SA', '.fk', 'FKP', '500', '', '', 'en-FK', '', '', NULL, '0', 0, 0, NULL, NULL),
(75, 'FM', 'FSM', 583, 'FM', 'Micronesia', 'Micronesia', 'Palikir', 702, 107708, 'OC', '.fm', 'USD', '691', '#####', '^(d{5})$', 'en-FM,chk,pon,yap,kos,uli,woe,nkr,kpg', '', '', NULL, '0', 0, 0, NULL, NULL),
(76, 'FO', 'FRO', 234, 'FO', 'Føroyar', 'Faroe Islands', 'Torshavn', 1399, 48228, 'EU', '.fo', 'DKK', '298', 'FO-###', '^(?:FO)*(d{3})$', 'fo,da-FO', '', '', NULL, '0', 0, 0, NULL, NULL),
(77, 'FR', 'FRA', 250, 'FR', 'France', 'France', 'Paris', 547030, 64768389, 'EU', '.fr', 'EUR', '33', '#####', '^(d{5})$', 'fr-FR,frp,br,co,ca,eu,oc', 'CH,DE,BE,LU,IT,AD,MC,ES', '', NULL, '0', 0, 0, NULL, NULL),
(78, 'GA', 'GAB', 266, 'GB', 'Gabon', 'Gabon', 'Libreville', 267667, 1545255, 'AF', '.ga', 'XAF', '241', '', '', 'fr-GA', 'CM,GQ,CG', '', NULL, '0', 0, 0, NULL, NULL),
(79, 'GD', 'GRD', 308, 'GJ', 'Grenada', 'Grenada', 'St. George\'s', 344, 107818, 'NA', '.gd', 'XCD', '+1-473', '', '', 'en-GD', '', '', NULL, '0', 0, 0, NULL, NULL),
(80, 'GE', 'GEO', 268, 'GG', 'Sak\'art\'velo', 'Georgia', 'Tbilisi', 69700, 4630000, 'AS', '.ge', 'GEL', '995', '####', '^(d{4})$', 'ka,ru,hy,az', 'AM,AZ,TR,RU', '', NULL, '0', 0, 0, NULL, NULL),
(81, 'GF', 'GUF', 254, 'FG', 'Guyane', 'French Guiana', 'Cayenne', 91000, 195506, 'SA', '.gf', 'EUR', '594', '#####', '^((97|98)3d{2})$', 'fr-GF', 'SR,BR', '', NULL, '0', 0, 0, NULL, NULL),
(82, 'GG', 'GGY', 831, 'GK', 'Guernsey', 'Guernsey', 'St Peter Port', 78, 65228, 'EU', '.gg', 'GBP', '+44-1481', '@# #@@|@## #@@|@@# #@@|@@## #@@|@#@ #@@|@@#@ #@@|G', '^(([A-Z]d{2}[A-Z]{2})|([A-Z]d{3}[A-Z]{2})|([A-Z]{2}d{2}[A-Z]{2})|([A-Z]{2}d{3}[A-Z]{2})|([A-Z]d[A-Z]d[A-Z]{2})|([A-Z]{2}d[A-Z]d[A-Z]{2})|(GIR0AA))$', 'en,fr', '', '', NULL, '0', 0, 0, NULL, NULL),
(83, 'GH', 'GHA', 288, 'GH', 'Ghana', 'Ghana', 'Accra', 239460, 24339838, 'AF', '.gh', 'GHS', '233', '', '', 'en-GH,ak,ee,tw', 'CI,TG,BF', '', NULL, '0', 0, 0, NULL, NULL),
(84, 'GI', 'GIB', 292, 'GI', 'Gibraltar', 'Gibraltar', 'Gibraltar', 7, 27884, 'EU', '.gi', 'GIP', '350', '', '', 'en-GI,es,it,pt', 'ES', '', NULL, '0', 0, 0, NULL, NULL),
(85, 'GL', 'GRL', 304, 'GL', 'Grønland', 'Greenland', 'Nuuk', 2166086, 56375, 'NA', '.gl', 'DKK', '299', '####', '^(d{4})$', 'kl,da-GL,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(86, 'GM', 'GMB', 270, 'GA', 'Gambia', 'Gambia', 'Banjul', 11300, 1593256, 'AF', '.gm', 'GMD', '220', '', '', 'en-GM,mnk,wof,wo,ff', 'SN', '', NULL, '0', 0, 0, NULL, NULL),
(87, 'GN', 'GIN', 324, 'GV', 'Guinée', 'Guinea', 'Conakry', 245857, 10324025, 'AF', '.gn', 'GNF', '224', '', '', 'fr-GN', 'LR,SN,SL,CI,GW,ML', '', NULL, '0', 0, 0, NULL, NULL),
(88, 'GP', 'GLP', 312, 'GP', 'Guadeloupe', 'Guadeloupe', 'Basse-Terre', 1780, 443000, 'NA', '.gp', 'EUR', '590', '#####', '^((97|98)d{3})$', 'fr-GP', '', '', NULL, '0', 0, 0, NULL, NULL),
(89, 'GQ', 'GNQ', 226, 'EK', 'Guinée Equatoriale', 'Equatorial Guinea', 'Malabo', 28051, 1014999, 'AF', '.gq', 'XAF', '240', '', '', 'es-GQ,fr', 'GA,CM', '', NULL, '0', 0, 0, NULL, NULL),
(90, 'GR', 'GRC', 300, 'GR', 'Elláda', 'Greece', 'Athens', 131940, 11000000, 'EU', '.gr', 'EUR', '30', '### ##', '^(d{5})$', 'el-GR,en,fr', 'AL,MK,TR,BG', '', NULL, '0', 0, 0, NULL, NULL),
(91, 'GS', 'SGS', 239, 'SX', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'Grytviken', 3903, 30, 'AN', '.gs', 'GBP', '', '', '', 'en', '', '', NULL, '0', 0, 0, NULL, NULL),
(92, 'GT', 'GTM', 320, 'GT', 'Guatemala', 'Guatemala', 'Guatemala City', 108890, 13550440, 'NA', '.gt', 'GTQ', '502', '#####', '^(d{5})$', 'es-GT', 'MX,HN,BZ,SV', '', NULL, '0', 0, 0, NULL, NULL),
(93, 'GU', 'GUM', 316, 'GQ', 'Guam', 'Guam', 'Hagatna', 549, 159358, 'OC', '.gu', 'USD', '+1-671', '969##', '^(969d{2})$', 'en-GU,ch-GU', '', '', NULL, '0', 0, 0, NULL, NULL),
(94, 'GW', 'GNB', 624, 'PU', 'Guiné-Bissau', 'Guinea-Bissau', 'Bissau', 36120, 1565126, 'AF', '.gw', 'XOF', '245', '####', '^(d{4})$', 'pt-GW,pov', 'SN,GN', '', NULL, '0', 0, 0, NULL, NULL),
(95, 'GY', 'GUY', 328, 'GY', 'Guyana', 'Guyana', 'Georgetown', 214970, 748486, 'SA', '.gy', 'GYD', '592', '', '', 'en-GY', 'SR,BR,VE', '', NULL, '0', 0, 0, NULL, NULL),
(96, 'HK', 'HKG', 344, 'HK', 'Hèunggóng', 'Hong Kong', 'Hong Kong', 1092, 6898686, 'AS', '.hk', 'HKD', '852', '', '', 'zh-HK,yue,zh,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(97, 'HM', 'HMD', 334, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', '', 412, 0, 'AN', '.hm', 'AUD', ' ', '', '', '', '', '', NULL, '0', 0, 0, NULL, NULL),
(98, 'HN', 'HND', 340, 'HO', 'Honduras', 'Honduras', 'Tegucigalpa', 112090, 7989415, 'NA', '.hn', 'HNL', '504', '@@####', '^([A-Z]{2}d{4})$', 'es-HN', 'GT,NI,SV', '', NULL, '0', 0, 0, NULL, NULL),
(99, 'HR', 'HRV', 191, 'HR', 'Hrvatska', 'Croatia', 'Zagreb', 56542, 4491000, 'EU', '.hr', 'HRK', '385', '#####', '^(?:HR)*(d{5})$', 'hr-HR,sr', 'HU,SI,BA,ME,RS', '', NULL, '0', 0, 0, NULL, NULL),
(100, 'HT', 'HTI', 332, 'HA', 'Haïti', 'Haiti', 'Port-au-Prince', 27750, 9648924, 'NA', '.ht', 'HTG', '509', 'HT####', '^(?:HT)*(d{4})$', 'ht,fr-HT', 'DO', '', NULL, '0', 0, 0, NULL, NULL),
(101, 'HU', 'HUN', 348, 'HU', 'Magyarország', 'Hungary', 'Budapest', 93030, 9982000, 'EU', '.hu', 'HUF', '36', '####', '^(d{4})$', 'hu-HU', 'SK,SI,RO,UA,HR,AT,RS', '', NULL, '0', 0, 0, NULL, NULL),
(102, 'ID', 'IDN', 360, 'ID', 'Indonesia', 'Indonesia', 'Jakarta', 1919440, 242968342, 'AS', '.id', 'IDR', '62', '#####', '^(d{5})$', 'id,en,nl,jv', 'PG,TL,MY', '', NULL, '0', 0, 0, NULL, NULL),
(103, 'IE', 'IRL', 372, 'EI', 'Ireland', 'Ireland', 'Dublin', 70280, 4622917, 'EU', '.ie', 'EUR', '353', '', '', 'en-IE,ga-IE', 'GB', '', NULL, '0', 0, 0, NULL, NULL),
(104, 'IL', 'ISR', 376, 'IS', 'Yiśrā\'ēl', 'Israel', 'Jerusalem', 20770, 7353985, 'AS', '.il', 'ILS', '972', '#####', '^(d{5})$', 'he,ar-IL,en-IL,', 'SY,JO,LB,EG,PS', '', NULL, '0', 0, 0, NULL, NULL),
(105, 'IM', 'IMN', 833, 'IM', 'Isle of Man', 'Isle of Man', 'Douglas, Isle of Man', 572, 75049, 'EU', '.im', 'GBP', '+44-1624', '@# #@@|@## #@@|@@# #@@|@@## #@@|@#@ #@@|@@#@ #@@|G', '^(([A-Z]d{2}[A-Z]{2})|([A-Z]d{3}[A-Z]{2})|([A-Z]{2}d{2}[A-Z]{2})|([A-Z]{2}d{3}[A-Z]{2})|([A-Z]d[A-Z]d[A-Z]{2})|([A-Z]{2}d[A-Z]d[A-Z]{2})|(GIR0AA))$', 'en,gv', '', '', NULL, '0', 0, 0, NULL, NULL),
(106, 'IN', 'IND', 356, 'IN', 'Bhārat', 'India', 'New Delhi', 3287590, 1173108018, 'AS', '.in', 'INR', '91', '######', '^(d{6})$', 'en-IN,hi,bn,te,mr,ta,ur,gu,kn,ml,or,pa,as,bh,sat,k', 'CN,NP,MM,BT,PK,BD', '', NULL, '0', 0, 0, NULL, NULL),
(107, 'IO', 'IOT', 86, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'Diego Garcia', 60, 4000, 'AS', '.io', 'USD', '246', '', '', 'en-IO', '', '', NULL, '0', 0, 0, NULL, NULL),
(108, 'IQ', 'IRQ', 368, 'IZ', 'al-ʿIrāq', 'Iraq', 'Baghdad', 437072, 29671605, 'AS', '.iq', 'IQD', '964', '#####', '^(d{5})$', 'ar-IQ,ku,hy', 'SY,SA,IR,JO,TR,KW', '', NULL, '0', 0, 0, NULL, NULL),
(109, 'IR', 'IRN', 364, 'IR', 'Īrān', 'Iran', 'Tehran', 1648000, 76923300, 'AS', '.ir', 'IRR', '98', '##########', '^(d{10})$', 'fa-IR,ku', 'TM,AF,IQ,AM,PK,AZ,TR', '', NULL, '0', 0, 0, NULL, NULL),
(110, 'IS', 'ISL', 352, 'IC', 'Ísland', 'Iceland', 'Reykjavik', 103000, 308910, 'EU', '.is', 'ISK', '354', '###', '^(d{3})$', 'is,en,de,da,sv,no', '', '', NULL, '0', 0, 0, NULL, NULL),
(111, 'IT', 'ITA', 380, 'IT', 'Italia', 'Italy', 'Rome', 301230, 60340328, 'EU', '.it', 'EUR', '39', '#####', '^(d{5})$', 'it-IT,en,de-IT,fr-IT,sc,ca,co,sl', 'CH,VA,SI,SM,FR,AT', '', NULL, '0', 0, 0, NULL, NULL),
(112, 'JE', 'JEY', 832, 'JE', 'Jersey', 'Jersey', 'Saint Helier', 116, 90812, 'EU', '.je', 'GBP', '+44-1534', '@# #@@|@## #@@|@@# #@@|@@## #@@|@#@ #@@|@@#@ #@@|G', '^(([A-Z]d{2}[A-Z]{2})|([A-Z]d{3}[A-Z]{2})|([A-Z]{2}d{2}[A-Z]{2})|([A-Z]{2}d{3}[A-Z]{2})|([A-Z]d[A-Z]d[A-Z]{2})|([A-Z]{2}d[A-Z]d[A-Z]{2})|(GIR0AA))$', 'en,pt', '', '', NULL, '0', 0, 0, NULL, NULL),
(113, 'JM', 'JAM', 388, 'JM', 'Jamaica', 'Jamaica', 'Kingston', 10991, 2847232, 'NA', '.jm', 'JMD', '+1-876', '', '', 'en-JM', '', '', NULL, '0', 0, 0, NULL, NULL),
(114, 'JO', 'JOR', 400, 'JO', 'al-Urdun', 'Jordan', 'Amman', 92300, 6407085, 'AS', '.jo', 'JOD', '962', '#####', '^(d{5})$', 'ar-JO,en', 'SY,SA,IQ,IL,PS', '', NULL, '0', 0, 0, NULL, NULL),
(115, 'JP', 'JPN', 392, 'JA', 'Nihon', 'Japan', 'Tokyo', 377835, 127288000, 'AS', '.jp', 'JPY', '81', '###-####', '^(d{7})$', 'ja', '', '', NULL, '0', 0, 0, NULL, NULL),
(116, 'KE', 'KEN', 404, 'KE', 'Kenya', 'Kenya', 'Nairobi', 582650, 40046566, 'AF', '.ke', 'KES', '254', '#####', '^(d{5})$', 'en-KE,sw-KE', 'ET,TZ,SS,SO,UG', '', NULL, '0', 0, 0, NULL, NULL),
(117, 'KG', 'KGZ', 417, 'KG', 'Kyrgyzstan', 'Kyrgyzstan', 'Bishkek', 198500, 5508626, 'AS', '.kg', 'KGS', '996', '######', '^(d{6})$', 'ky,uz,ru', 'CN,TJ,UZ,KZ', '', NULL, '0', 0, 0, NULL, NULL),
(118, 'KH', 'KHM', 116, 'CB', 'Kambucā', 'Cambodia', 'Phnom Penh', 181040, 14453680, 'AS', '.kh', 'KHR', '855', '#####', '^(d{5})$', 'km,fr,en', 'LA,TH,VN', '', NULL, '0', 0, 0, NULL, NULL),
(119, 'KI', 'KIR', 296, 'KR', 'Kiribati', 'Kiribati', 'Tarawa', 811, 92533, 'OC', '.ki', 'AUD', '686', '', '', 'en-KI,gil', '', '', NULL, '0', 0, 0, NULL, NULL),
(120, 'KM', 'COM', 174, 'CN', 'Comores', 'Comoros', 'Moroni', 2170, 773407, 'AF', '.km', 'KMF', '269', '', '', 'ar,fr-KM', '', '', NULL, '0', 0, 0, NULL, NULL),
(121, 'KN', 'KNA', 659, 'SC', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', 'Basseterre', 261, 51134, 'NA', '.kn', 'XCD', '+1-869', '', '', 'en-KN', '', '', NULL, '0', 0, 0, NULL, NULL),
(122, 'KP', 'PRK', 408, 'KN', 'Joseon', 'North Korea', 'Pyongyang', 120540, 22912177, 'AS', '.kp', 'KPW', '850', '###-###', '^(d{6})$', 'ko-KP', 'CN,KR,RU', '', NULL, '0', 0, 0, NULL, NULL),
(123, 'KR', 'KOR', 410, 'KS', 'Hanguk', 'South Korea', 'Seoul', 98480, 48422644, 'AS', '.kr', 'KRW', '82', 'SEOUL ###-###', '^(?:SEOUL)*(d{6})$', 'ko-KR,en', 'KP', '', NULL, '0', 0, 0, NULL, NULL),
(124, 'KW', 'KWT', 414, 'KU', 'al-Kuwayt', 'Kuwait', 'Kuwait City', 17820, 2789132, 'AS', '.kw', 'KWD', '965', '#####', '^(d{5})$', 'ar-KW,en', 'SA,IQ', '', NULL, '0', 0, 0, NULL, NULL),
(125, 'KY', 'CYM', 136, 'CJ', 'Cayman Islands', 'Cayman Islands', 'George Town', 262, 44270, 'NA', '.ky', 'KYD', '+1-345', '', '', 'en-KY', '', '', NULL, '0', 0, 0, NULL, NULL),
(126, 'KZ', 'KAZ', 398, 'KZ', 'Ķazaķstan', 'Kazakhstan', 'Astana', 2717300, 15340000, 'AS', '.kz', 'KZT', '7', '######', '^(d{6})$', 'kk,ru', 'TM,CN,KG,UZ,RU', '', NULL, '0', 0, 0, NULL, NULL),
(127, 'LA', 'LAO', 418, 'LA', 'Lāw', 'Laos', 'Vientiane', 236800, 6368162, 'AS', '.la', 'LAK', '856', '#####', '^(d{5})$', 'lo,fr,en', 'CN,MM,KH,TH,VN', '', NULL, '0', 0, 0, NULL, NULL),
(128, 'LB', 'LBN', 422, 'LE', 'Lubnān', 'Lebanon', 'Beirut', 10400, 4125247, 'AS', '.lb', 'LBP', '961', '#### ####|####', '^(d{4}(d{4})?)$', 'ar-LB,fr-LB,en,hy', 'SY,IL', '', NULL, '0', 0, 0, NULL, NULL),
(129, 'LC', 'LCA', 662, 'ST', 'Saint Lucia', 'Saint Lucia', 'Castries', 616, 160922, 'NA', '.lc', 'XCD', '+1-758', '', '', 'en-LC', '', '', NULL, '0', 0, 0, NULL, NULL),
(130, 'LI', 'LIE', 438, 'LS', 'Liechtenstein', 'Liechtenstein', 'Vaduz', 160, 35000, 'EU', '.li', 'CHF', '423', '####', '^(d{4})$', 'de-LI', 'CH,AT', '', NULL, '0', 0, 0, NULL, NULL),
(131, 'LK', 'LKA', 144, 'CE', 'Šrī Laṁkā', 'Sri Lanka', 'Colombo', 65610, 21513990, 'AS', '.lk', 'LKR', '94', '#####', '^(d{5})$', 'si,ta,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(132, 'LR', 'LBR', 430, 'LI', 'Liberia', 'Liberia', 'Monrovia', 111370, 3685076, 'AF', '.lr', 'LRD', '231', '####', '^(d{4})$', 'en-LR', 'SL,CI,GN', '', NULL, '0', 0, 0, NULL, NULL),
(133, 'LS', 'LSO', 426, 'LT', 'Lesotho', 'Lesotho', 'Maseru', 30355, 1919552, 'AF', '.ls', 'LSL', '266', '###', '^(d{3})$', 'en-LS,st,zu,xh', 'ZA', '', NULL, '0', 0, 0, NULL, NULL),
(134, 'LT', 'LTU', 440, 'LH', 'Lietuva', 'Lithuania', 'Vilnius', 65200, 2944459, 'EU', '.lt', 'EUR', '370', 'LT-#####', '^(?:LT)*(d{5})$', 'lt,ru,pl', 'PL,BY,RU,LV', '', NULL, '0', 0, 0, NULL, NULL),
(135, 'LU', 'LUX', 442, 'LU', 'Lëtzebuerg', 'Luxembourg', 'Luxembourg', 2586, 497538, 'EU', '.lu', 'EUR', '352', 'L-####', '^(d{4})$', 'lb,de-LU,fr-LU', 'DE,BE,FR', '', NULL, '0', 0, 0, NULL, NULL),
(136, 'LV', 'LVA', 428, 'LG', 'Latvija', 'Latvia', 'Riga', 64589, 2217969, 'EU', '.lv', 'EUR', '371', 'LV-####', '^(?:LV)*(d{4})$', 'lv,ru,lt', 'LT,EE,BY,RU', '', NULL, '0', 0, 0, NULL, NULL),
(137, 'LY', 'LBY', 434, 'LY', 'Lībiyā', 'Libya', 'Tripolis', 1759540, 6461454, 'AF', '.ly', 'LYD', '218', '', '', 'ar-LY,it,en', 'TD,NE,DZ,SD,TN,EG', '', NULL, '0', 0, 0, NULL, NULL),
(138, 'MA', 'MAR', 504, 'MO', 'Maroc', 'Morocco', 'Rabat', 446550, 31627428, 'AF', '.ma', 'MAD', '212', '#####', '^(d{5})$', 'ar-MA,fr', 'DZ,EH,ES', '', NULL, '0', 0, 0, NULL, NULL),
(139, 'MC', 'MCO', 492, 'MN', 'Monaco', 'Monaco', 'Monaco', 2, 32965, 'EU', '.mc', 'EUR', '377', '#####', '^(d{5})$', 'fr-MC,en,it', 'FR', '', NULL, '0', 0, 0, NULL, NULL),
(140, 'MD', 'MDA', 498, 'MD', 'Moldova', 'Moldova', 'Chisinau', 33843, 4324000, 'EU', '.md', 'MDL', '373', 'MD-####', '^(?:MD)*(d{4})$', 'ro,ru,gag,tr', 'RO,UA', '', NULL, '0', 0, 0, NULL, NULL),
(141, 'ME', 'MNE', 499, 'MJ', 'Crna Gora', 'Montenegro', 'Podgorica', 14026, 666730, 'EU', '.me', 'EUR', '382', '#####', '^(d{5})$', 'sr,hu,bs,sq,hr,rom', 'AL,HR,BA,RS,XK', '', NULL, '0', 0, 0, NULL, NULL),
(142, 'MF', 'MAF', 663, 'RN', 'Saint Martin', 'Saint Martin', 'Marigot', 53, 35925, 'NA', '.gp', 'EUR', '590', '### ###', '', 'fr', 'SX', '', NULL, '0', 0, 0, NULL, NULL),
(143, 'MG', 'MDG', 450, 'MA', 'Madagascar', 'Madagascar', 'Antananarivo', 587040, 21281844, 'AF', '.mg', 'MGA', '261', '###', '^(d{3})$', 'fr-MG,mg', '', '', NULL, '0', 0, 0, NULL, NULL),
(144, 'MH', 'MHL', 584, 'RM', 'Marshall Islands', 'Marshall Islands', 'Majuro', 181, 65859, 'OC', '.mh', 'USD', '692', '', '', 'mh,en-MH', '', '', NULL, '0', 0, 0, NULL, NULL),
(145, 'MK', 'MKD', 807, 'MK', 'Makedonija', 'Macedonia', 'Skopje', 25333, 2062294, 'EU', '.mk', 'MKD', '389', '####', '^(d{4})$', 'mk,sq,tr,rmm,sr', 'AL,GR,BG,RS,XK', '', NULL, '0', 0, 0, NULL, NULL),
(146, 'ML', 'MLI', 466, 'ML', 'Mali', 'Mali', 'Bamako', 1240000, 13796354, 'AF', '.ml', 'XOF', '223', '', '', 'fr-ML,bm', 'SN,NE,DZ,CI,GN,MR,BF', '', NULL, '0', 0, 0, NULL, NULL),
(147, 'MM', 'MMR', 104, 'BM', 'Mẏanmā', 'Myanmar', 'Nay Pyi Taw', 678500, 53414374, 'AS', '.mm', 'MMK', '95', '#####', '^(d{5})$', 'my', 'CN,LA,TH,BD,IN', '', NULL, '0', 0, 0, NULL, NULL),
(148, 'MN', 'MNG', 496, 'MG', 'Mongol Uls', 'Mongolia', 'Ulan Bator', 1565000, 3086918, 'AS', '.mn', 'MNT', '976', '######', '^(d{6})$', 'mn,ru', 'CN,RU', '', NULL, '0', 0, 0, NULL, NULL),
(149, 'MO', 'MAC', 446, 'MC', 'Ngoumún', 'Macao', 'Macao', 254, 449198, 'AS', '.mo', 'MOP', '853', '', '', 'zh,zh-MO,pt', '', '', NULL, '0', 0, 0, NULL, NULL),
(150, 'MP', 'MNP', 580, 'CQ', 'Northern Mariana Islands', 'Northern Mariana Islands', 'Saipan', 477, 53883, 'OC', '.mp', 'USD', '+1-670', '', '', 'fil,tl,zh,ch-MP,en-MP', '', '', NULL, '0', 0, 0, NULL, NULL),
(151, 'MQ', 'MTQ', 474, 'MB', 'Martinique', 'Martinique', 'Fort-de-France', 1100, 432900, 'NA', '.mq', 'EUR', '596', '#####', '^(d{5})$', 'fr-MQ', '', '', NULL, '0', 0, 0, NULL, NULL),
(152, 'MR', 'MRT', 478, 'MR', 'Mauritanie', 'Mauritania', 'Nouakchott', 1030700, 3205060, 'AF', '.mr', 'MRO', '222', '', '', 'ar-MR,fuc,snk,fr,mey,wo', 'SN,DZ,EH,ML', '', NULL, '0', 0, 0, NULL, NULL),
(153, 'MS', 'MSR', 500, 'MH', 'Montserrat', 'Montserrat', 'Plymouth', 102, 9341, 'NA', '.ms', 'XCD', '+1-664', '', '', 'en-MS', '', '', NULL, '0', 0, 0, NULL, NULL),
(154, 'MT', 'MLT', 470, 'MT', 'Malta', 'Malta', 'Valletta', 316, 403000, 'EU', '.mt', 'EUR', '356', '@@@ ###|@@@ ##', '^([A-Z]{3}d{2}d?)$', 'mt,en-MT', '', '', NULL, '0', 0, 0, NULL, NULL),
(155, 'MU', 'MUS', 480, 'MP', 'Mauritius', 'Mauritius', 'Port Louis', 2040, 1294104, 'AF', '.mu', 'MUR', '230', '', '', 'en-MU,bho,fr', '', '', NULL, '0', 0, 0, NULL, NULL),
(156, 'MV', 'MDV', 462, 'MV', 'Dhivehi', 'Maldives', 'Male', 300, 395650, 'AS', '.mv', 'MVR', '960', '#####', '^(d{5})$', 'dv,en', '', '', NULL, '0', 0, 0, NULL, NULL),
(157, 'MW', 'MWI', 454, 'MI', 'Malawi', 'Malawi', 'Lilongwe', 118480, 15447500, 'AF', '.mw', 'MWK', '265', '', '', 'ny,yao,tum,swk', 'TZ,MZ,ZM', '', NULL, '0', 0, 0, NULL, NULL),
(158, 'MX', 'MEX', 484, 'MX', 'México', 'Mexico', 'Mexico City', 1972550, 112468855, 'NA', '.mx', 'MXN', '52', '#####', '^(d{5})$', 'es-MX', 'GT,US,BZ', '', NULL, '0', 0, 0, NULL, NULL),
(159, 'MY', 'MYS', 458, 'MY', 'Malaysia', 'Malaysia', 'Kuala Lumpur', 329750, 28274729, 'AS', '.my', 'MYR', '60', '#####', '^(d{5})$', 'ms-MY,en,zh,ta,te,ml,pa,th', 'BN,TH,ID', '', NULL, '0', 0, 0, NULL, NULL),
(160, 'MZ', 'MOZ', 508, 'MZ', 'Moçambique', 'Mozambique', 'Maputo', 801590, 22061451, 'AF', '.mz', 'MZN', '258', '####', '^(d{4})$', 'pt-MZ,vmw', 'ZW,TZ,SZ,ZA,ZM,MW', '', NULL, '0', 0, 0, NULL, NULL),
(161, 'NA', 'NAM', 516, 'WA', 'Namibia', 'Namibia', 'Windhoek', 825418, 2128471, 'AF', '.na', 'NAD', '264', '', '', 'en-NA,af,de,hz,naq', 'ZA,BW,ZM,AO', '', NULL, '0', 0, 0, NULL, NULL),
(162, 'NC', 'NCL', 540, 'NC', 'Nouvelle Calédonie', 'New Caledonia', 'Noumea', 19060, 216494, 'OC', '.nc', 'XPF', '687', '#####', '^(d{5})$', 'fr-NC', '', '', NULL, '0', 0, 0, NULL, NULL),
(163, 'NE', 'NER', 562, 'NG', 'Niger', 'Niger', 'Niamey', 1267000, 15878271, 'AF', '.ne', 'XOF', '227', '####', '^(d{4})$', 'fr-NE,ha,kr,dje', 'TD,BJ,DZ,LY,BF,NG,ML', '', NULL, '0', 0, 0, NULL, NULL),
(164, 'NF', 'NFK', 574, 'NF', 'Norfolk Island', 'Norfolk Island', 'Kingston', 35, 1828, 'OC', '.nf', 'AUD', '672', '####', '^(d{4})$', 'en-NF', '', '', NULL, '0', 0, 0, NULL, NULL),
(165, 'NG', 'NGA', 566, 'NI', 'Nigeria', 'Nigeria', 'Abuja', 923768, 154000000, 'AF', '.ng', 'NGN', '234', '######', '^(d{6})$', 'en-NG,ha,yo,ig,ff', 'TD,NE,BJ,CM', '', NULL, '0', 0, 0, NULL, NULL),
(166, 'NI', 'NIC', 558, 'NU', 'Nicaragua', 'Nicaragua', 'Managua', 129494, 5995928, 'NA', '.ni', 'NIO', '505', '###-###-#', '^(d{7})$', 'es-NI,en', 'CR,HN', '', NULL, '0', 0, 0, NULL, NULL),
(167, 'NL', 'NLD', 528, 'NL', 'Nederland', 'Netherlands', 'Amsterdam', 41526, 16645000, 'EU', '.nl', 'EUR', '31', '#### @@', '^(d{4}[A-Z]{2})$', 'nl-NL,fy-NL', 'DE,BE', '', NULL, '0', 0, 0, NULL, NULL),
(168, 'NO', 'NOR', 578, 'NO', 'Norge (Noreg)', 'Norway', 'Oslo', 324220, 5009150, 'EU', '.no', 'NOK', '47', '####', '^(d{4})$', 'no,nb,nn,se,fi', 'FI,RU,SE', '', NULL, '0', 0, 0, NULL, NULL),
(169, 'NP', 'NPL', 524, 'NP', 'Nēpāl', 'Nepal', 'Kathmandu', 140800, 28951852, 'AS', '.np', 'NPR', '977', '#####', '^(d{5})$', 'ne,en', 'CN,IN', '', NULL, '0', 0, 0, NULL, NULL),
(170, 'NR', 'NRU', 520, 'NR', 'Naoero', 'Nauru', 'Yaren', 21, 10065, 'OC', '.nr', 'AUD', '674', '', '', 'na,en-NR', '', '', NULL, '0', 0, 0, NULL, NULL),
(171, 'NU', 'NIU', 570, 'NE', 'Niue', 'Niue', 'Alofi', 260, 2166, 'OC', '.nu', 'NZD', '683', '', '', 'niu,en-NU', '', '', NULL, '0', 0, 0, NULL, NULL),
(172, 'NZ', 'NZL', 554, 'NZ', 'New Zealand', 'New Zealand', 'Wellington', 268680, 4252277, 'OC', '.nz', 'NZD', '64', '####', '^(d{4})$', 'en-NZ,mi', '', '', NULL, '0', 0, 0, NULL, NULL),
(173, 'OM', 'OMN', 512, 'MU', 'ʿUmān', 'Oman', 'Muscat', 212460, 2967717, 'AS', '.om', 'OMR', '968', '###', '^(d{3})$', 'ar-OM,en,bal,ur', 'SA,YE,AE', '', NULL, '0', 0, 0, NULL, NULL),
(174, 'PA', 'PAN', 591, 'PM', 'Panamá', 'Panama', 'Panama City', 78200, 3410676, 'NA', '.pa', 'PAB', '507', '', '', 'es-PA,en', 'CR,CO', '', NULL, '0', 0, 0, NULL, NULL),
(175, 'PE', 'PER', 604, 'PE', 'Perú', 'Peru', 'Lima', 1285220, 29907003, 'SA', '.pe', 'PEN', '51', '', '', 'es-PE,qu,ay', 'EC,CL,BO,BR,CO', '', NULL, '0', 0, 0, NULL, NULL),
(176, 'PF', 'PYF', 258, 'FP', 'Polinésie Française', 'French Polynesia', 'Papeete', 4167, 270485, 'OC', '.pf', 'XPF', '689', '#####', '^((97|98)7d{2})$', 'fr-PF,ty', '', '', NULL, '0', 0, 0, NULL, NULL),
(177, 'PG', 'PNG', 598, 'PP', 'Papua New Guinea', 'Papua New Guinea', 'Port Moresby', 462840, 6064515, 'OC', '.pg', 'PGK', '675', '###', '^(d{3})$', 'en-PG,ho,meu,tpi', 'ID', '', NULL, '0', 0, 0, NULL, NULL),
(178, 'PH', 'PHL', 608, 'RP', 'Pilipinas', 'Philippines', 'Manila', 300000, 99900177, 'AS', '.ph', 'PHP', '63', '####', '^(d{4})$', 'tl,en-PH,fil', '', '', NULL, '0', 0, 0, NULL, NULL),
(179, 'PK', 'PAK', 586, 'PK', 'Pākistān', 'Pakistan', 'Islamabad', 803940, 184404791, 'AS', '.pk', 'PKR', '92', '#####', '^(d{5})$', 'ur-PK,en-PK,pa,sd,ps,brh', 'CN,AF,IR,IN', '', NULL, '0', 0, 0, NULL, NULL),
(180, 'PL', 'POL', 616, 'PL', 'Polska', 'Poland', 'Warsaw', 312685, 38500000, 'EU', '.pl', 'PLN', '48', '##-###', '^(d{5})$', 'pl', 'DE,LT,SK,CZ,BY,UA,RU', '', NULL, '0', 0, 0, NULL, NULL),
(181, 'PM', 'SPM', 666, 'SB', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'Saint-Pierre', 242, 7012, 'NA', '.pm', 'EUR', '508', '#####', '^(97500)$', 'fr-PM', '', '', NULL, '0', 0, 0, NULL, NULL),
(182, 'PN', 'PCN', 612, 'PC', 'Pitcairn', 'Pitcairn', 'Adamstown', 47, 46, 'OC', '.pn', 'NZD', '870', '', '', 'en-PN', '', '', NULL, '0', 0, 0, NULL, NULL),
(183, 'PR', 'PRI', 630, 'RQ', 'Puerto Rico', 'Puerto Rico', 'San Juan', 9104, 3916632, 'NA', '.pr', 'USD', '+1-787/1-939', '#####-####', '^(d{9})$', 'en-PR,es-PR', '', '', NULL, '0', 0, 0, NULL, NULL),
(184, 'PS', 'PSE', 275, 'WE', 'Filasṭīn', 'Palestinian Territory', 'East Jerusalem', 5970, 3800000, 'AS', '.ps', 'ILS', '970', '', '', 'ar-PS', 'JO,IL,EG', '', NULL, '0', 0, 0, NULL, NULL),
(185, 'PT', 'PRT', 620, 'PO', 'Portugal', 'Portugal', 'Lisbon', 92391, 10676000, 'EU', '.pt', 'EUR', '351', '####-###', '^(d{7})$', 'pt-PT,mwl', 'ES', '', NULL, '0', 0, 0, NULL, NULL),
(186, 'PW', 'PLW', 585, 'PS', 'Palau', 'Palau', 'Melekeok', 458, 19907, 'OC', '.pw', 'USD', '680', '96940', '^(96940)$', 'pau,sov,en-PW,tox,ja,fil,zh', '', '', NULL, '0', 0, 0, NULL, NULL),
(187, 'PY', 'PRY', 600, 'PA', 'Paraguay', 'Paraguay', 'Asuncion', 406750, 6375830, 'SA', '.py', 'PYG', '595', '####', '^(d{4})$', 'es-PY,gn', 'BO,BR,AR', '', NULL, '0', 0, 0, NULL, NULL),
(188, 'QA', 'QAT', 634, 'QA', 'Qaṭar', 'Qatar', 'Doha', 11437, 840926, 'AS', '.qa', 'QAR', '974', '', '', 'ar-QA,en', 'SA', '', NULL, '0', 0, 0, NULL, NULL),
(189, 'RE', 'REU', 638, 'RE', 'Réunion', 'Reunion', 'Saint-Denis', 2517, 776948, 'AF', '.re', 'EUR', '262', '#####', '^((97|98)(4|7|8)d{2})$', 'fr-RE', '', '', NULL, '0', 0, 0, NULL, NULL),
(190, 'RO', 'ROU', 642, 'RO', 'România', 'Romania', 'Bucharest', 237500, 21959278, 'EU', '.ro', 'RON', '40', '######', '^(d{6})$', 'ro,hu,rom', 'MD,HU,UA,BG,RS', '', NULL, '0', 0, 0, NULL, NULL),
(191, 'RS', 'SRB', 688, 'RI', 'Srbija', 'Serbia', 'Belgrade', 88361, 7344847, 'EU', '.rs', 'RSD', '381', '######', '^(d{6})$', 'sr,hu,bs,rom', 'AL,HU,MK,RO,HR,BA,BG,ME,XK', '', NULL, '0', 0, 0, NULL, NULL),
(192, 'RU', 'RUS', 643, 'RS', 'Rossija', 'Russia', 'Moscow', 17100000, 140702000, 'EU', '.ru', 'RUB', '7', '######', '^(d{6})$', 'ru,tt,xal,cau,ady,kv,ce,tyv,cv,udm,tut,mns,bua,myv', 'GE,CN,BY,UA,KZ,LV,PL,EE,LT,FI,MN,NO,AZ,KP', '', NULL, '0', 0, 0, NULL, NULL),
(193, 'RW', 'RWA', 646, 'RW', 'Rwanda', 'Rwanda', 'Kigali', 26338, 11055976, 'AF', '.rw', 'RWF', '250', '', '', 'rw,en-RW,fr-RW,sw', 'TZ,CD,BI,UG', '', NULL, '0', 0, 0, NULL, NULL),
(194, 'SA', 'SAU', 682, 'SA', 'as-Saʿūdīyâ', 'Saudi Arabia', 'Riyadh', 1960582, 25731776, 'AS', '.sa', 'SAR', '966', '#####', '^(d{5})$', 'Ar,En', 'QA,OM,IQ,YE,JO,AE,KW', '', 'app/logohttp://127.0.0.1:8000', '0', 0, 1, NULL, '2019-01-08 17:21:19'),
(195, 'SB', 'SLB', 90, 'BP', 'Solomon Islands', 'Solomon Islands', 'Honiara', 28450, 559198, 'OC', '.sb', 'SBD', '677', '', '', 'en-SB,tpi', '', '', NULL, '0', 0, 0, NULL, NULL),
(196, 'SC', 'SYC', 690, 'SE', 'Seychelles', 'Seychelles', 'Victoria', 455, 88340, 'AF', '.sc', 'SCR', '248', '', '', 'en-SC,fr-SC', '', '', NULL, '0', 0, 0, NULL, NULL),
(197, 'SD', 'SDN', 729, 'SU', 'Sudan', 'Sudan', 'Khartoum', 1861484, 35000000, 'AF', '.sd', 'SDG', '249', '#####', '^(d{5})$', 'ar-SD,en,fia', 'SS,TD,EG,ET,ER,LY,CF', '', NULL, '0', 0, 0, NULL, NULL),
(198, 'SE', 'SWE', 752, 'SW', 'Sverige', 'Sweden', 'Stockholm', 449964, 9555893, 'EU', '.se', 'SEK', '46', '### ##', '^(?:SE)*(d{5})$', 'sv-SE,se,sma,fi-SE', 'NO,FI', '', NULL, '0', 0, 0, NULL, NULL),
(199, 'SG', 'SGP', 702, 'SN', 'xīnjiāpō', 'Singapore', 'Singapur', 693, 4701069, 'AS', '.sg', 'SGD', '65', '######', '^(d{6})$', 'cmn,en-SG,ms-SG,ta-SG,zh-SG', '', '', NULL, '0', 0, 0, NULL, NULL),
(200, 'SH', 'SHN', 654, 'SH', 'Saint Helena', 'Saint Helena', 'Jamestown', 410, 7460, 'AF', '.sh', 'SHP', '290', 'STHL 1ZZ', '^(STHL1ZZ)$', 'en-SH', '', '', NULL, '0', 0, 0, NULL, NULL),
(201, 'SI', 'SVN', 705, 'SI', 'Slovenija', 'Slovenia', 'Ljubljana', 20273, 2007000, 'EU', '.si', 'EUR', '386', '####', '^(?:SI)*(d{4})$', 'sl,sh', 'HU,IT,HR,AT', '', NULL, '0', 0, 0, NULL, NULL),
(202, 'SJ', 'SJM', 744, 'SV', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'Longyearbyen', 62049, 2550, 'EU', '.sj', 'NOK', '47', '', '', 'no,ru', '', '', NULL, '0', 0, 0, NULL, NULL),
(203, 'SK', 'SVK', 703, 'LO', 'Slovensko', 'Slovakia', 'Bratislava', 48845, 5455000, 'EU', '.sk', 'EUR', '421', '### ##', '^(d{5})$', 'sk,hu', 'PL,HU,CZ,UA,AT', '', NULL, '0', 0, 0, NULL, NULL),
(204, 'SL', 'SLE', 694, 'SL', 'Sierra Leone', 'Sierra Leone', 'Freetown', 71740, 5245695, 'AF', '.sl', 'SLL', '232', '', '', 'en-SL,men,tem', 'LR,GN', '', NULL, '0', 0, 0, NULL, NULL),
(205, 'SM', 'SMR', 674, 'SM', 'San Marino', 'San Marino', 'San Marino', 61, 31477, 'EU', '.sm', 'EUR', '378', '4789#', '^(4789d)$', 'it-SM', 'IT', '', NULL, '0', 0, 0, NULL, NULL),
(206, 'SN', 'SEN', 686, 'SG', 'Sénégal', 'Senegal', 'Dakar', 196190, 12323252, 'AF', '.sn', 'XOF', '221', '#####', '^(d{5})$', 'fr-SN,wo,fuc,mnk', 'GN,MR,GW,GM,ML', '', NULL, '0', 0, 0, NULL, NULL),
(207, 'SO', 'SOM', 706, 'SO', 'Soomaaliya', 'Somalia', 'Mogadishu', 637657, 10112453, 'AF', '.so', 'SOS', '252', '@@  #####', '^([A-Z]{2}d{5})$', 'so-SO,ar-SO,it,en-SO', 'ET,KE,DJ', '', NULL, '0', 0, 0, NULL, NULL),
(208, 'SR', 'SUR', 740, 'NS', 'Suriname', 'Suriname', 'Paramaribo', 163270, 492829, 'SA', '.sr', 'SRD', '597', '', '', 'nl-SR,en,srn,hns,jv', 'GY,BR,GF', '', NULL, '0', 0, 0, NULL, NULL),
(209, 'SS', 'SSD', 728, 'OD', 'South Sudan', 'South Sudan', 'Juba', 644329, 8260490, 'AF', '', 'SSP', '211', '', '', 'en', 'CD,CF,ET,KE,SD,UG,', '', NULL, '0', 0, 0, NULL, NULL),
(210, 'ST', 'STP', 678, 'TP', 'São Tomé e Príncipe', 'Sao Tome and Principe', 'Sao Tome', 1001, 175808, 'AF', '.st', 'STD', '239', '', '', 'pt-ST', '', '', NULL, '0', 0, 0, NULL, NULL),
(211, 'SV', 'SLV', 222, 'ES', 'El Salvador', 'El Salvador', 'San Salvador', 21040, 6052064, 'NA', '.sv', 'USD', '503', 'CP ####', '^(?:CP)*(d{4})$', 'es-SV', 'GT,HN', '', NULL, '0', 0, 0, NULL, NULL),
(212, 'SX', 'SXM', 534, 'NN', 'Sint Maarten', 'Sint Maarten', 'Philipsburg', 21, 37429, 'NA', '.sx', 'ANG', '599', '', '', 'nl,en', 'MF', '', NULL, '0', 0, 0, NULL, NULL),
(213, 'SY', 'SYR', 760, 'SY', 'Sūrīyâ', 'Syria', 'Damascus', 185180, 22198110, 'AS', '.sy', 'SYP', '963', '', '', 'ar-SY,ku,hy,arc,fr,en', 'IQ,JO,IL,TR,LB', '', NULL, '0', 0, 0, NULL, NULL),
(214, 'SZ', 'SWZ', 748, 'WZ', 'Swaziland', 'Swaziland', 'Mbabane', 17363, 1354051, 'AF', '.sz', 'SZL', '268', '@###', '^([A-Z]d{3})$', 'en-SZ,ss-SZ', 'ZA,MZ', '', NULL, '0', 0, 0, NULL, NULL),
(215, 'TC', 'TCA', 796, 'TK', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'Cockburn Town', 430, 20556, 'NA', '.tc', 'USD', '+1-649', 'TKCA 1ZZ', '^(TKCA 1ZZ)$', 'en-TC', '', '', NULL, '0', 0, 0, NULL, NULL),
(216, 'TD', 'TCD', 148, 'CD', 'Tchad', 'Chad', 'N\'Djamena', 1284000, 10543464, 'AF', '.td', 'XAF', '235', '', '', 'fr-TD,ar-TD,sre', 'NE,LY,CF,SD,CM,NG', '', NULL, '0', 0, 0, NULL, NULL),
(217, 'TF', 'ATF', 260, 'FS', 'French Southern Territories', 'French Southern Territories', 'Port-aux-Francais', 7829, 140, 'AN', '.tf', 'EUR', '', '', '', 'fr', '', '', NULL, '0', 0, 0, NULL, NULL),
(218, 'TG', 'TGO', 768, 'TO', 'Togo', 'Togo', 'Lome', 56785, 6587239, 'AF', '.tg', 'XOF', '228', '', '', 'fr-TG,ee,hna,kbp,dag,ha', 'BJ,GH,BF', '', NULL, '0', 0, 0, NULL, NULL),
(219, 'TH', 'THA', 764, 'TH', 'Prathēt tai', 'Thailand', 'Bangkok', 514000, 67089500, 'AS', '.th', 'THB', '66', '#####', '^(d{5})$', 'th,en', 'LA,MM,KH,MY', '', NULL, '0', 0, 0, NULL, NULL),
(220, 'TJ', 'TJK', 762, 'TI', 'Tojikiston', 'Tajikistan', 'Dushanbe', 143100, 7487489, 'AS', '.tj', 'TJS', '992', '######', '^(d{6})$', 'tg,ru', 'CN,AF,KG,UZ', '', NULL, '0', 0, 0, NULL, NULL),
(221, 'TK', 'TKL', 772, 'TL', 'Tokelau', 'Tokelau', '', 10, 1466, 'OC', '.tk', 'NZD', '690', '', '', 'tkl,en-TK', '', '', NULL, '0', 0, 0, NULL, NULL),
(222, 'TL', 'TLS', 626, 'TT', 'Timór Lorosa\'e', 'East Timor', 'Dili', 15007, 1154625, 'OC', '.tl', 'USD', '670', '', '', 'tet,pt-TL,id,en', 'ID', '', NULL, '0', 0, 0, NULL, NULL),
(223, 'TM', 'TKM', 795, 'TX', 'Turkmenistan', 'Turkmenistan', 'Ashgabat', 488100, 4940916, 'AS', '.tm', 'TMT', '993', '######', '^(d{6})$', 'tk,ru,uz', 'AF,IR,UZ,KZ', '', NULL, '0', 0, 0, NULL, NULL),
(224, 'TN', 'TUN', 788, 'TS', 'Tunisie', 'Tunisia', 'Tunis', 163610, 10589025, 'AF', '.tn', 'TND', '216', '####', '^(d{4})$', 'ar-TN,fr', 'DZ,LY', '', NULL, '0', 0, 0, NULL, NULL),
(225, 'TO', 'TON', 776, 'TN', 'Tonga', 'Tonga', 'Nuku\'alofa', 748, 122580, 'OC', '.to', 'TOP', '676', '', '', 'to,en-TO', '', '', NULL, '0', 0, 0, NULL, NULL),
(226, 'TR', 'TUR', 792, 'TU', 'Türkiye', 'Turkey', 'Ankara', 780580, 77804122, 'AS', '.tr', 'TRY', '90', '#####', '^(d{5})$', 'tr-TR,ku,diq,az,av', 'SY,GE,IQ,IR,GR,AM,AZ,BG', '', NULL, '0', 0, 0, NULL, NULL),
(227, 'TT', 'TTO', 780, 'TD', 'Trinidad and Tobago', 'Trinidad and Tobago', 'Port of Spain', 5128, 1228691, 'NA', '.tt', 'TTD', '+1-868', '', '', 'en-TT,hns,fr,es,zh', '', '', NULL, '0', 0, 0, NULL, NULL),
(228, 'TV', 'TUV', 798, 'TV', 'Tuvalu', 'Tuvalu', 'Funafuti', 26, 10472, 'OC', '.tv', 'AUD', '688', '', '', 'tvl,en,sm,gil', '', '', NULL, '0', 0, 0, NULL, NULL),
(229, 'TW', 'TWN', 158, 'TW', 'T\'ai2-wan1', 'Taiwan', 'Taipei', 35980, 22894384, 'AS', '.tw', 'TWD', '886', '#####', '^(d{5})$', 'zh-TW,zh,nan,hak', '', '', NULL, '0', 0, 0, NULL, NULL),
(230, 'TZ', 'TZA', 834, 'TZ', 'Tanzania', 'Tanzania', 'Dodoma', 945087, 41892895, 'AF', '.tz', 'TZS', '255', '', '', 'sw-TZ,en,ar', 'MZ,KE,CD,RW,ZM,BI,UG,MW', '', NULL, '0', 0, 0, NULL, NULL),
(231, 'UA', 'UKR', 804, 'UP', 'Ukrajina', 'Ukraine', 'Kiev', 603700, 45415596, 'EU', '.ua', 'UAH', '380', '#####', '^(d{5})$', 'uk,ru-UA,rom,pl,hu', 'PL,MD,HU,SK,BY,RO,RU', '', NULL, '0', 0, 0, NULL, NULL),
(232, 'UG', 'UGA', 800, 'UG', 'Uganda', 'Uganda', 'Kampala', 236040, 33398682, 'AF', '.ug', 'UGX', '256', '', '', 'en-UG,lg,sw,ar', 'TZ,KE,SS,CD,RW', '', NULL, '0', 0, 0, NULL, NULL),
(233, 'UK', 'GBR', 826, 'UK', 'United Kingdom', 'United Kingdom', 'London', 244820, 62348447, 'EU', '.uk', 'GBP', '44', '@# #@@|@## #@@|@@# #@@|@@## #@@|@#@ #@@|@@#@ #@@|G', '^(([A-Z]d{2}[A-Z]{2})|([A-Z]d{3}[A-Z]{2})|([A-Z]{2}d{2}[A-Z]{2})|([A-Z]{2}d{3}[A-Z]{2})|([A-Z]d[A-Z]d[A-Z]{2})|([A-Z]{2}d[A-Z]d[A-Z]{2})|(GIR0AA))$', 'en-GB,cy-GB,gd', 'IE', '', NULL, '0', 0, 0, NULL, NULL),
(234, 'UM', 'UMI', 581, '', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', '', 0, 0, 'OC', '.um', 'USD', '1', '', '', 'en-UM', '', '', NULL, '0', 0, 0, NULL, NULL),
(235, 'US', 'USA', 840, 'US', 'USA', 'United States', 'Washington', 9629091, 310232863, 'NA', '.us', 'USD', '1', '#####-####', '^d{5}(-d{4})?$', 'en-US,es-US,haw,fr', 'CA,MX,CU', '', NULL, '0', 0, 0, NULL, NULL),
(236, 'UY', 'URY', 858, 'UY', 'Uruguay', 'Uruguay', 'Montevideo', 176220, 3477000, 'SA', '.uy', 'UYU', '598', '#####', '^(d{5})$', 'es-UY', 'BR,AR', '', NULL, '0', 0, 0, NULL, NULL),
(237, 'UZ', 'UZB', 860, 'UZ', 'O\'zbekiston', 'Uzbekistan', 'Tashkent', 447400, 27865738, 'AS', '.uz', 'UZS', '998', '######', '^(d{6})$', 'uz,ru,tg', 'TM,AF,KG,TJ,KZ', '', NULL, '0', 0, 0, NULL, NULL),
(238, 'VA', 'VAT', 336, 'VT', 'Vaticanum', 'Vatican', 'Vatican City', 0, 921, 'EU', '.va', 'EUR', '379', '#####', '^(d{5})$', 'la,it,fr', 'IT', '', NULL, '0', 0, 0, NULL, NULL),
(239, 'VC', 'VCT', 670, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'Kingstown', 389, 104217, 'NA', '.vc', 'XCD', '+1-784', '', '', 'en-VC,fr', '', '', NULL, '0', 0, 0, NULL, NULL),
(240, 'VE', 'VEN', 862, 'VE', 'Venezuela', 'Venezuela', 'Caracas', 912050, 27223228, 'SA', '.ve', 'VEF', '58', '####', '^(d{4})$', 'es-VE', 'GY,BR,CO', '', NULL, '0', 0, 0, NULL, NULL),
(241, 'VG', 'VGB', 92, 'VI', 'British Virgin Islands', 'British Virgin Islands', 'Road Town', 153, 21730, 'NA', '.vg', 'USD', '+1-284', '', '', 'en-VG', '', '', NULL, '0', 0, 0, NULL, NULL),
(242, 'VI', 'VIR', 850, 'VQ', 'U.S. Virgin Islands', 'U.S. Virgin Islands', 'Charlotte Amalie', 352, 108708, 'NA', '.vi', 'USD', '+1-340', '#####-####', '^d{5}(-d{4})?$', 'en-VI', '', '', NULL, '0', 0, 0, NULL, NULL),
(243, 'VN', 'VNM', 704, 'VM', 'Việt Nam', 'Vietnam', 'Hanoi', 329560, 89571130, 'AS', '.vn', 'VND', '84', '######', '^(d{6})$', 'vi,en,fr,zh,km', 'CN,LA,KH', '', NULL, '0', 0, 0, NULL, NULL),
(244, 'VU', 'VUT', 548, 'NH', 'Vanuatu', 'Vanuatu', 'Port Vila', 12200, 221552, 'OC', '.vu', 'VUV', '678', '', '', 'bi,en-VU,fr-VU', '', '', NULL, '0', 0, 0, NULL, NULL),
(245, 'WF', 'WLF', 876, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', 'Mata Utu', 274, 16025, 'OC', '.wf', 'XPF', '681', '#####', '^(986d{2})$', 'wls,fud,fr-WF', '', '', NULL, '0', 0, 0, NULL, NULL),
(246, 'WS', 'WSM', 882, 'WS', 'Samoa', 'Samoa', 'Apia', 2944, 192001, 'OC', '.ws', 'WST', '685', '', '', 'sm,en-WS', '', '', NULL, '0', 0, 0, NULL, NULL),
(247, 'XK', 'XKX', 0, 'KV', 'Kosovo', 'Kosovo', 'Pristina', 10908, 1800000, 'EU', '', 'EUR', '', '', '', 'sq,sr', 'RS,AL,MK,ME', '', NULL, '0', 0, 0, NULL, NULL),
(248, 'YE', 'YEM', 887, 'YM', 'al-Yaman', 'Yemen', 'Sanaa', 527970, 23495361, 'AS', '.ye', 'YER', '967', '', '', 'ar-YE', 'SA,OM', '', NULL, '0', 0, 0, NULL, NULL),
(249, 'YT', 'MYT', 175, 'MF', 'Mayotte', 'Mayotte', 'Mamoudzou', 374, 159042, 'AF', '.yt', 'EUR', '262', '#####', '^(d{5})$', 'fr-YT', '', '', NULL, '0', 0, 0, NULL, NULL),
(250, 'ZA', 'ZAF', 710, 'SF', 'South Africa', 'South Africa', 'Pretoria', 1219912, 49000000, 'AF', '.za', 'ZAR', '27', '####', '^(d{4})$', 'zu,xh,af,nso,en-ZA,tn,st,ts,ss,ve,nr', 'ZW,SZ,MZ,BW,NA,LS', '', NULL, '0', 0, 0, NULL, NULL),
(251, 'ZM', 'ZMB', 894, 'ZA', 'Zambia', 'Zambia', 'Lusaka', 752614, 13460305, 'AF', '.zm', 'ZMW', '260', '#####', '^(d{5})$', 'en-ZM,bem,loz,lun,lue,ny,toi', 'ZW,TZ,MZ,CD,NA,MW,AO', '', NULL, '0', 0, 0, NULL, NULL),
(252, 'ZW', 'ZWE', 716, 'ZI', 'Zimbabwe', 'Zimbabwe', 'Harare', 390580, 11651858, 'AF', '.zw', 'ZWL', '263', '', '', 'en-ZW,sn,nr,nd', 'ZA,MZ,BW,ZM', '', NULL, '0', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqacurrencies`
--

CREATE TABLE `theqqacurrencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `html_entity` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'From Github : An array of currency symbols as HTML entities',
  `font_arial` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `font_code2000` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unicode_decimal` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unicode_hex` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_left` tinyint(1) UNSIGNED DEFAULT '0',
  `decimal_places` int(10) UNSIGNED DEFAULT '2' COMMENT 'Currency Decimal Places - ISO 4217',
  `decimal_separator` varchar(10) COLLATE utf8_unicode_ci DEFAULT '.',
  `thousand_separator` varchar(10) COLLATE utf8_unicode_ci DEFAULT ',',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqacurrencies`
--

INSERT INTO `theqqacurrencies` (`id`, `code`, `name`, `html_entity`, `font_arial`, `font_code2000`, `unicode_decimal`, `unicode_hex`, `in_left`, `decimal_places`, `decimal_separator`, `thousand_separator`, `created_at`, `updated_at`) VALUES
(1, 'AED', 'United Arab Emirates Dirham', '&#1583;.&#1573;', 'د.إ', 'د.إ', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(2, 'AFN', 'Afghanistan Afghani', '&#65;&#102;', '؋', '؋', '1547', '60b', 0, 2, '.', ',', NULL, NULL),
(3, 'ALL', 'Albania Lek', '&#76;&#101;&#107;', 'Lek', 'Lek', '76, 1', '4c, 6', 0, 2, '.', ',', NULL, NULL),
(4, 'AMD', 'Armenia Dram', '', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(5, 'ANG', 'Netherlands Antilles Guilder', '&#402;', 'ƒ', 'ƒ', '402', '192', 0, 2, '.', ',', NULL, NULL),
(6, 'AOA', 'Angola Kwanza', '&#75;&#122;', 'Kz', 'Kz', NULL, NULL, 1, 2, '.', ',', NULL, NULL),
(7, 'ARS', 'Argentina Peso', '&#36;', '$', '$', '36', '24', 0, 2, '.', ',', NULL, NULL),
(8, 'AUD', 'Australia Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(9, 'AWG', 'Aruba Guilder', '&#402;', 'ƒ', 'ƒ', '402', '192', 0, 2, '.', ',', NULL, NULL),
(10, 'AZN', 'Azerbaijan New Manat', '&#1084;&#1072;&#1085;', 'ман', 'ман', '1084,', '43c, ', 0, 2, '.', ',', NULL, NULL),
(11, 'BAM', 'Bosnia and Herzegovina Convertible Marka', '&#75;&#77;', 'KM', 'KM', '75, 7', '4b, 4', 0, 2, '.', ',', NULL, NULL),
(12, 'BBD', 'Barbados Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(13, 'BDT', 'Bangladesh Taka', '&#2547;', '৳', '৳', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(14, 'BGN', 'Bulgaria Lev', '&#1083;&#1074;', 'лв', 'лв', '1083,', '43b, ', 0, 2, '.', ',', NULL, NULL),
(15, 'BHD', 'Bahrain Dinar', '.&#1583;.&#1576;', NULL, NULL, NULL, NULL, 0, 3, '.', ',', NULL, NULL),
(16, 'BIF', 'Burundi Franc', '&#70;&#66;&#117;', 'FBu', 'FBu', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(17, 'BMD', 'Bermuda Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(18, 'BND', 'Brunei Darussalam Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(19, 'BOB', 'Bolivia Boliviano', '&#36;&#98;', '$b', '$b', '36, 9', '24, 6', 0, 2, '.', ',', NULL, NULL),
(20, 'BRL', 'Brazil Real', '&#82;&#36;', 'R$', 'R$', '82, 3', '52, 2', 0, 2, '.', ',', NULL, NULL),
(21, 'BSD', 'Bahamas Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(22, 'BTN', 'Bhutan Ngultrum', '&#78;&#117;&#46;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(23, 'BWP', 'Botswana Pula', '&#80;', 'P', 'P', '80', '50', 1, 2, '.', ',', NULL, NULL),
(24, 'BYR', 'Belarus Ruble', '&#112;&#46;', 'p.', 'p.', '112, ', '70, 2', 0, 0, '.', ',', NULL, NULL),
(25, 'BZD', 'Belize Dollar', '&#66;&#90;&#36;', 'BZ$', 'BZ$', '66, 9', '42, 5', 1, 2, '.', ',', NULL, NULL),
(26, 'CAD', 'Canada Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(27, 'CDF', 'Congo/Kinshasa Franc', '&#70;&#67;', 'Fr', 'Fr', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(28, 'CHF', 'Switzerland Franc', '', 'Fr', 'Fr', '67, 7', '43, 4', 0, 2, '.', ',', NULL, NULL),
(29, 'CLP', 'Chile Peso', '&#36;', '$', '$', '36', '24', 0, 0, '.', ',', NULL, NULL),
(30, 'CNY', 'China Yuan Renminbi', '&#165;', '¥', '¥', '165', 'a5', 0, 2, '.', ',', NULL, NULL),
(31, 'COP', 'Colombia Peso', '&#36;', '$', '$', '36', '24', 0, 2, '.', ',', NULL, NULL),
(32, 'CRC', 'Costa Rica Colon', '&#8353;', '₡', '₡', '8353', '20a1', 0, 2, '.', ',', NULL, NULL),
(33, 'CUC', 'Cuba Convertible Peso', NULL, NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(34, 'CUP', 'Cuba Peso', '&#8396;', '₱', '₱', '8369', '20b1', 0, 2, '.', ',', NULL, NULL),
(35, 'CVE', 'Cape Verde Escudo', '&#x24;', '$', '$', NULL, NULL, 1, 0, '.', ',', NULL, NULL),
(36, 'CZK', 'Czech Republic Koruna', '&#75;&#269;', 'Kč', 'Kč', '75, 2', '4b, 1', 0, 2, '.', ',', NULL, NULL),
(37, 'DJF', 'Djibouti Franc', '&#70;&#100;&#106;', 'Fr', 'Fr', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(38, 'DKK', 'Denmark Krone', '&#107;&#114;', 'kr', 'kr', '107, ', '6b, 7', 0, 2, '.', ',', NULL, NULL),
(39, 'DOP', 'Dominican Republic Peso', '&#82;&#68;&#36;', 'RD$', 'RD$', '82, 6', '52, 4', 0, 2, '.', ',', NULL, NULL),
(40, 'DZD', 'Algeria Dinar', '&#1583;&#1580;', 'DA', 'DA', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(41, 'EEK', 'Estonia Kroon', NULL, 'kr', 'kr', '107, ', '6b, 7', 0, 2, '.', ',', NULL, NULL),
(42, 'EGP', 'Egypt Pound', '&#163;', '£', '£', '163', 'a3', 1, 2, '.', ',', NULL, '2019-01-08 11:41:16'),
(43, 'ERN', 'Eritrea Nakfa', '&#x4E;&#x66;&#x6B;', 'Nfk', 'Nfk', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(44, 'ETB', 'Ethiopia Birr', '&#66;&#114;', 'Br', 'Br', NULL, NULL, 1, 2, '.', ',', NULL, NULL),
(45, 'EUR', 'Euro Member Countries', '€', '€', '€', '8364', '20ac', 0, 2, ',', ' ', NULL, NULL),
(46, 'FJD', 'Fiji Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(47, 'FKP', 'Falkland Islands (Malvinas) Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(48, 'GBP', 'United Kingdom Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(49, 'GEL', 'Georgia Lari', '&#4314;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(50, 'GGP', 'Guernsey Pound', NULL, '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(51, 'GHC', 'Ghana Cedi', '&#x47;&#x48;&#xA2;', 'GH¢', 'GH¢', '162', 'a2', 1, 2, '.', ',', NULL, NULL),
(52, 'GHS', 'Ghana Cedi', '&#x47;&#x48;&#xA2;', 'GH¢', 'GH¢', NULL, NULL, 1, 2, '.', ',', NULL, NULL),
(53, 'GIP', 'Gibraltar Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(54, 'GMD', 'Gambia Dalasi', '&#68;', 'D', 'D', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(55, 'GNF', 'Guinea Franc', '&#70;&#71;', 'Fr', 'Fr', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(56, 'GTQ', 'Guatemala Quetzal', '&#81;', 'Q', 'Q', '81', '51', 0, 2, '.', ',', NULL, NULL),
(57, 'GYD', 'Guyana Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(58, 'HKD', 'Hong Kong Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(59, 'HNL', 'Honduras Lempira', '&#76;', 'L', 'L', '76', '4c', 0, 2, '.', ',', NULL, NULL),
(60, 'HRK', 'Croatia Kuna', '&#107;&#110;', 'kn', 'kn', '107, ', '6b, 6', 0, 2, '.', ',', NULL, NULL),
(61, 'HTG', 'Haiti Gourde', '&#71;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(62, 'HUF', 'Hungary Forint', '&#70;&#116;', 'Ft', 'Ft', '70, 1', '46, 7', 0, 2, '.', ',', NULL, NULL),
(63, 'IDR', 'Indonesia Rupiah', '&#82;&#112;', 'Rp', 'Rp', '82, 1', '52, 7', 0, 0, '.', ',', NULL, NULL),
(64, 'ILS', 'Israel Shekel', '&#8362;', '₪', '₪', '8362', '20aa', 0, 2, '.', ',', NULL, NULL),
(65, 'IMP', 'Isle of Man Pound', NULL, '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(66, 'INR', 'India Rupee', '&#8377;', '₨', '₨', '', '', 0, 2, '.', ',', NULL, NULL),
(67, 'IQD', 'Iraq Dinar', '&#1593;.&#1583;', 'د.ع;', 'د.ع;', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(68, 'IRR', 'Iran Rial', '&#65020;', '﷼', '﷼', '65020', 'fdfc', 0, 0, '.', ',', NULL, NULL),
(69, 'ISK', 'Iceland Krona', '&#107;&#114;', 'kr', 'kr', '107, ', '6b, 7', 0, 0, '.', ',', NULL, NULL),
(70, 'JEP', 'Jersey Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(71, 'JMD', 'Jamaica Dollar', '&#74;&#36;', 'J$', 'J$', '74, 3', '4a, 2', 1, 2, '.', ',', NULL, NULL),
(72, 'JOD', 'Jordan Dinar', '&#74;&#68;', NULL, NULL, NULL, NULL, 0, 3, '.', ',', NULL, NULL),
(73, 'JPY', 'Japan Yen', '&#165;', '¥', '¥', '165', 'a5', 0, 0, '.', ',', NULL, NULL),
(74, 'KES', 'Kenya Shilling', '&#x4B;&#x53;&#x68;', 'KSh', 'KSh', NULL, NULL, 1, 2, '.', ',', NULL, NULL),
(75, 'KGS', 'Kyrgyzstan Som', '&#1083;&#1074;', 'лв', 'лв', '1083,', '43b, ', 0, 2, '.', ',', NULL, NULL),
(76, 'KHR', 'Cambodia Riel', '&#6107;', '៛', '៛', '6107', '17db', 0, 2, '.', ',', NULL, NULL),
(77, 'KMF', 'Comoros Franc', '&#67;&#70;', 'Fr', 'Fr', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(78, 'KPW', 'Korea (North) Won', '&#8361;', '₩', '₩', '8361', '20a9', 0, 0, '.', ',', NULL, NULL),
(79, 'KRW', 'Korea (South) Won', '&#8361;', '₩', '₩', '8361', '20a9', 0, 0, '.', ',', NULL, NULL),
(80, 'KWD', 'Kuwait Dinar', '&#1583;.&#1603;', 'د.ك', 'د.ك', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(81, 'KYD', 'Cayman Islands Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(82, 'KZT', 'Kazakhstan Tenge', '&#1083;&#1074;', 'лв', 'лв', '1083,', '43b, ', 0, 2, '.', ',', NULL, NULL),
(83, 'LAK', 'Laos Kip', '&#8365;', '₭', '₭', '8365', '20ad', 0, 0, '.', ',', NULL, NULL),
(84, 'LBP', 'Lebanon Pound', '&#163;', '£', '£', '163', 'a3', 0, 0, '.', ',', NULL, NULL),
(85, 'LKR', 'Sri Lanka Rupee', '&#8360;', '₨', '₨', '8360', '20a8', 0, 2, '.', ',', NULL, NULL),
(86, 'LRD', 'Liberia Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(87, 'LSL', 'Lesotho Loti', '&#76;', 'M', 'M', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(88, 'LTL', 'Lithuania Litas', '&#76;&#116;', 'Lt', 'Lt', '76, 1', '4c, 7', 0, 2, '.', ',', NULL, NULL),
(89, 'LVL', 'Latvia Lat', '&#76;&#115;', 'Ls', 'Ls', '76, 1', '4c, 7', 0, 2, '.', ',', NULL, NULL),
(90, 'LYD', 'Libya Dinar', '&#1604;.&#1583;', 'DL', 'DL', NULL, NULL, 0, 3, '.', ',', NULL, NULL),
(91, 'MAD', 'Morocco Dirham', '&#1583;.&#1605;.', 'Dhs', 'Dhs', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(92, 'MDL', 'Moldova Leu', '&#76;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(93, 'MGA', 'Madagascar Ariary', '&#65;&#114;', 'Ar', 'Ar', NULL, NULL, 0, 5, '.', ',', NULL, NULL),
(94, 'MKD', 'Macedonia Denar', '&#1076;&#1077;&#1085;', 'ден', 'ден', '1076,', '434, ', 0, 2, '.', ',', NULL, NULL),
(95, 'MMK', 'Myanmar (Burma) Kyat', '&#75;', NULL, NULL, NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(96, 'MNT', 'Mongolia Tughrik', '&#8366;', '₮', '₮', '8366', '20ae', 0, 2, '.', ',', NULL, NULL),
(97, 'MOP', 'Macau Pataca', '&#77;&#79;&#80;&#36;', NULL, NULL, NULL, NULL, 0, 1, '.', ',', NULL, NULL),
(98, 'MRO', 'Mauritania Ouguiya', '&#85;&#77;', 'UM', 'UM', NULL, NULL, 0, 5, '.', ',', NULL, NULL),
(99, 'MUR', 'Mauritius Rupee', '&#8360;', '₨', '₨', '8360', '20a8', 0, 2, '.', ',', NULL, NULL),
(100, 'MVR', 'Maldives (Maldive Islands) Rufiyaa', '.&#1923;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(101, 'MWK', 'Malawi Kwacha', '&#77;&#75;', 'MK', 'MK', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(102, 'MXN', 'Mexico Peso', '&#36;', '$', '$', '36', '24', 0, 2, '.', ',', NULL, NULL),
(103, 'MYR', 'Malaysia Ringgit', '&#82;&#77;', 'RM', 'RM', '82, 7', '52, 4', 0, 2, '.', ',', NULL, NULL),
(104, 'MZN', 'Mozambique Metical', '&#77;&#84;', 'MT', 'MT', '77, 8', '4d, 5', 0, 2, '.', ',', NULL, NULL),
(105, 'NAD', 'Namibia Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(106, 'NGN', 'Nigeria Naira', '&#8358;', '₦', '₦', '8358', '20a6', 1, 2, '.', ',', NULL, NULL),
(107, 'NIO', 'Nicaragua Cordoba', '&#67;&#36;', 'C$', 'C$', '67, 3', '43, 2', 0, 2, '.', ',', NULL, NULL),
(108, 'NOK', 'Norway Krone', '&#107;&#114;', 'kr', 'kr', '107, ', '6b, 7', 0, 2, '.', ',', NULL, NULL),
(109, 'NPR', 'Nepal Rupee', '&#8360;', '₨', '₨', '8360', '20a8', 0, 2, '.', ',', NULL, NULL),
(110, 'NZD', 'New Zealand Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(111, 'OMR', 'Oman Rial', '&#65020;', '﷼', '﷼', '65020', 'fdfc', 0, 3, '.', ',', NULL, NULL),
(112, 'PAB', 'Panama Balboa', '&#66;&#47;&#46;', 'B/.', 'B/.', '66, 4', '42, 2', 0, 2, '.', ',', NULL, NULL),
(113, 'PEN', 'Peru Nuevo Sol', '&#83;&#47;&#46;', 'S/.', 'S/.', '83, 4', '53, 2', 0, 2, '.', ',', NULL, NULL),
(114, 'PGK', 'Papua New Guinea Kina', '&#75;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(115, 'PHP', 'Philippines Peso', '&#8369;', '₱', '₱', '8369', '20b1', 0, 2, '.', ',', NULL, NULL),
(116, 'PKR', 'Pakistan Rupee', '&#8360;', '₨', '₨', '8360', '20a8', 0, 2, '.', ',', NULL, NULL),
(117, 'PLN', 'Poland Zloty', '&#122;&#322;', 'zł', 'zł', '122, ', '7a, 1', 0, 2, '.', ',', NULL, NULL),
(118, 'PYG', 'Paraguay Guarani', '&#71;&#115;', 'Gs', 'Gs', '71, 1', '47, 7', 0, 0, '.', ',', NULL, NULL),
(119, 'QAR', 'Qatar Riyal', '&#65020;', '﷼', '﷼', '65020', 'fdfc', 0, 2, '.', ',', NULL, NULL),
(120, 'RON', 'Romania New Leu', '&#108;&#101;&#105;', 'lei', 'lei', '108, ', '6c, 6', 0, 2, '.', ',', NULL, NULL),
(121, 'RSD', 'Serbia Dinar', '&#1044;&#1080;&#1085;&#46;', 'Дин.', 'Дин.', '1044,', '414, ', 0, 2, '.', ',', NULL, NULL),
(122, 'RUB', 'Russia Ruble', '&#1088;&#1091;&#1073;', 'руб', 'руб', '1088,', '440, ', 0, 2, '.', ',', NULL, NULL),
(123, 'RWF', 'Rwanda Franc', '&#1585;.&#1587;', 'FRw', 'FRw', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(124, 'SAR', 'Saudi Arabia Riyal', '&#65020;', '﷼', '﷼', '65020', 'fdfc', 1, 2, '.', ',', NULL, '2019-01-08 11:40:54'),
(125, 'SBD', 'Solomon Islands Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(126, 'SCR', 'Seychelles Rupee', '&#8360;', '₨', '₨', '8360', '20a8', 0, 2, '.', ',', NULL, NULL),
(127, 'SDG', 'Sudan Pound', '&#163;', 'DS', 'DS', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(128, 'SEK', 'Sweden Krona', '&#107;&#114;', 'kr', 'kr', '107, ', '6b, 7', 0, 2, '.', ',', NULL, NULL),
(129, 'SGD', 'Singapore Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(130, 'SHP', 'Saint Helena Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(131, 'SLL', 'Sierra Leone Leone', '&#76;&#101;', 'Le', 'Le', NULL, NULL, 1, 0, '.', ',', NULL, NULL),
(132, 'SOS', 'Somalia Shilling', '&#83;', 'S', 'S', '83', '53', 0, 2, '.', ',', NULL, NULL),
(133, 'SPL', 'Seborga Luigino', NULL, NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(134, 'SRD', 'Suriname Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(135, 'SSP', 'South Sudanese Pound', '&#xA3;', '£', '£', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(136, 'STD', 'São Tomé and Príncipe Dobra', '&#68;&#98;', 'Db', 'Db', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(137, 'SVC', 'El Salvador Colon', '&#36;', '$', '$', '36', '24', 0, 2, '.', ',', NULL, NULL),
(138, 'SYP', 'Syria Pound', '&#163;', '£', '£', '163', 'a3', 0, 2, '.', ',', NULL, NULL),
(139, 'SZL', 'Swaziland Lilangeni', '&#76;', 'E', 'E', NULL, NULL, 1, 2, '.', ',', NULL, NULL),
(140, 'THB', 'Thailand Baht', '&#3647;', '฿', '฿', '3647', 'e3f', 0, 2, '.', ',', NULL, NULL),
(141, 'TJS', 'Tajikistan Somoni', '&#84;&#74;&#83;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(142, 'TMT', 'Turkmenistan Manat', '&#109;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(143, 'TND', 'Tunisia Dinar', '&#1583;.&#1578;', 'DT', 'DT', NULL, NULL, 1, 3, '.', ',', NULL, NULL),
(144, 'TOP', 'Tonga Pa\'anga', '&#84;&#36;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(145, 'TRL', 'Turkey Lira', NULL, '₤', '₤', '8356', '20a4', 1, 2, '.', ',', NULL, NULL),
(146, 'TRY', 'Turkey Lira', '&#x20BA;', '₺', '₺', '', '', 1, 2, '.', ',', NULL, NULL),
(147, 'TTD', 'Trinidad and Tobago Dollar', '&#36;', 'TT$', 'TT$', '84, 8', '54, 5', 1, 2, '.', ',', NULL, NULL),
(148, 'TVD', 'Tuvalu Dollar', NULL, '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(149, 'TWD', 'Taiwan New Dollar', '&#78;&#84;&#36;', 'NT$', 'NT$', '78, 8', '4e, 5', 1, 2, '.', ',', NULL, NULL),
(150, 'TZS', 'Tanzania Shilling', '&#x54;&#x53;&#x68;', 'TSh', 'TSh', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(151, 'UAH', 'Ukraine Hryvnia', '&#8372;', '₴', '₴', '8372', '20b4', 0, 2, '.', ',', NULL, NULL),
(152, 'UGX', 'Uganda Shilling', '&#85;&#83;&#104;', 'USh', 'USh', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(153, 'USD', 'United States Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(154, 'UYU', 'Uruguay Peso', '&#36;&#85;', '$U', '$U', '36, 8', '24, 5', 0, 2, '.', ',', NULL, NULL),
(155, 'UZS', 'Uzbekistan Som', '&#1083;&#1074;', 'лв', 'лв', '1083,', '43b, ', 0, 2, '.', ',', NULL, NULL),
(156, 'VEF', 'Venezuela Bolivar', '&#66;&#115;', 'Bs', 'Bs', '66, 1', '42, 7', 0, 2, '.', ',', NULL, NULL),
(157, 'VND', 'Viet Nam Dong', '&#8363;', '₫', '₫', '8363', '20ab', 1, 0, '.', ',', NULL, NULL),
(158, 'VUV', 'Vanuatu Vatu', '&#86;&#84;', NULL, NULL, NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(159, 'WST', 'Samoa Tala', '&#87;&#83;&#36;', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(160, 'XAF', 'Communauté Financière Africaine (BEAC) CFA Franc B', '&#70;&#67;&#70;&#65;', 'F', 'F', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(161, 'XCD', 'East Caribbean Dollar', '&#36;', '$', '$', '36', '24', 1, 2, '.', ',', NULL, NULL),
(162, 'XDR', 'International Monetary Fund (IMF) Special Drawing ', '', NULL, NULL, NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(163, 'XOF', 'Communauté Financière Africaine (BCEAO) Franc', '&#70;&#67;&#70;&#65;', 'F', 'F', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(164, 'XPF', 'Comptoirs Français du Pacifique (CFP) Franc', '&#70;', 'F', 'F', NULL, NULL, 0, 0, '.', ',', NULL, NULL),
(165, 'YER', 'Yemen Rial', '&#65020;', '﷼', '﷼', '65020', 'fdfc', 0, 2, '.', ',', NULL, NULL),
(166, 'ZAR', 'South Africa Rand', '&#82;', 'R', 'R', '82', '52', 1, 2, '.', ',', NULL, NULL),
(167, 'ZMW', 'Zambia Kwacha', NULL, 'ZK', 'ZK', NULL, NULL, 0, 2, '.', ',', NULL, NULL),
(168, 'ZWD', 'Zimbabwe Dollar', NULL, 'Z$', 'Z$', '90, 3', '5a, 2', 1, 2, '.', ',', NULL, NULL),
(169, 'ZWL', 'Zimbabwe Dollar', NULL, 'Z$', 'Z$', '90, 3', '5a, 2', 1, 2, '.', ',', NULL, NULL),
(170, 'XBT', 'Bitcoin', '฿', '฿', '฿', NULL, NULL, 1, 2, '.', ',', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqafields`
--

CREATE TABLE `theqqafields` (
  `id` int(10) UNSIGNED NOT NULL,
  `belongs_to` enum('posts','users') COLLATE utf8_unicode_ci NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('text','textarea','checkbox','checkbox_multiple','select','radio','file') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `max` int(10) UNSIGNED DEFAULT '255',
  `default` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `required` tinyint(1) UNSIGNED DEFAULT NULL,
  `help` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqafields`
--

INSERT INTO `theqqafields` (`id`, `belongs_to`, `translation_lang`, `translation_of`, `name`, `type`, `max`, `default`, `required`, `help`, `active`) VALUES
(4, 'posts', 'en', 28, 'Mileage', 'text', NULL, NULL, 1, NULL, 1),
(5, 'posts', 'en', 29, 'Fuel Type', 'radio', NULL, NULL, 1, NULL, 1),
(6, 'posts', 'en', 30, 'Features', 'checkbox_multiple', NULL, NULL, 1, NULL, 0),
(9, 'posts', 'en', 33, 'Transmission', 'radio', NULL, NULL, 1, NULL, 1),
(28, 'posts', 'ar', 28, 'ممشى السيارة (كم)', 'text', NULL, NULL, 1, NULL, 1),
(29, 'posts', 'ar', 29, 'نوع الوقود', 'radio', NULL, NULL, 1, NULL, 1),
(30, 'posts', 'ar', 30, 'الخصائص', 'checkbox_multiple', NULL, NULL, 1, NULL, 0),
(33, 'posts', 'ar', 33, 'ناقل الحركة', 'radio', NULL, NULL, 1, NULL, 1),
(49, 'posts', 'en', 50, 'Year of production', 'select', NULL, NULL, 1, NULL, 1),
(50, 'posts', 'ar', 50, 'سنة الصنع', 'select', NULL, NULL, 1, NULL, 1),
(51, 'posts', 'en', 52, 'شيفروليه', 'select', NULL, NULL, 1, NULL, 1),
(52, 'posts', 'ar', 52, 'النوع (شيفروليه)', 'select', NULL, NULL, 1, NULL, 1),
(53, 'posts', 'en', 54, 'نيسان', 'select', NULL, NULL, 1, NULL, 1),
(54, 'posts', 'ar', 54, 'النوع (نيسان)', 'select', NULL, NULL, 1, NULL, 1),
(55, 'posts', 'en', 56, 'فورد', 'select', NULL, NULL, 1, NULL, 1),
(56, 'posts', 'ar', 56, 'النوع (فورد)', 'select', NULL, NULL, 1, NULL, 1),
(57, 'posts', 'en', 58, 'مرسيدس', 'select', NULL, NULL, 1, NULL, 1),
(58, 'posts', 'ar', 58, 'النوع (مرسيدس)', 'select', NULL, NULL, 1, NULL, 1),
(59, 'posts', 'ar', 59, 'النوع (تويوتا)', 'select', NULL, NULL, 1, NULL, 1),
(60, 'posts', 'en', 59, 'تويوتا', 'select', NULL, NULL, 1, NULL, 1),
(61, 'posts', 'ar', 61, 'النوع (فوليكس)', 'select', NULL, NULL, 1, NULL, 1),
(62, 'posts', 'en', 61, 'فوليكس', 'select', NULL, NULL, 1, NULL, 1),
(63, 'posts', 'ar', 63, 'النوع(شاهين)', 'select', NULL, NULL, 1, NULL, 1),
(64, 'posts', 'en', 63, 'النوع(شاهين)', 'select', NULL, NULL, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqafields_options`
--

CREATE TABLE `theqqafields_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `field_id` int(10) UNSIGNED DEFAULT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqafields_options`
--

INSERT INTO `theqqafields_options` (`id`, `field_id`, `translation_lang`, `translation_of`, `value`, `parent_id`, `lft`, `rgt`, `depth`) VALUES
(4, 30, 'en', 165, 'Air conditionar', NULL, NULL, NULL, NULL),
(5, 30, 'en', 166, 'Airbags for front passenger', NULL, NULL, NULL, NULL),
(6, 30, 'en', 167, 'Security System', NULL, NULL, NULL, NULL),
(7, 30, 'en', 168, 'sunroof', NULL, NULL, NULL, NULL),
(8, 29, 'en', 169, 'Gasoline', NULL, NULL, NULL, NULL),
(9, 29, 'en', 170, 'Diesel', NULL, NULL, NULL, NULL),
(13, 33, 'en', 174, 'Automatic', NULL, NULL, NULL, NULL),
(14, 33, 'en', 175, 'Manual', NULL, NULL, NULL, NULL),
(165, 30, 'ar', 165, 'تكييف', NULL, NULL, NULL, NULL),
(166, 30, 'ar', 166, 'وسائد هوائية للراكب الأمامي', NULL, NULL, NULL, NULL),
(167, 30, 'ar', 167, 'نظام أمان', NULL, NULL, NULL, NULL),
(168, 30, 'ar', 168, 'فتحة سقف', NULL, NULL, NULL, NULL),
(169, 29, 'ar', 169, 'بنزين', NULL, NULL, NULL, NULL),
(170, 29, 'ar', 170, 'ديزل', NULL, NULL, NULL, NULL),
(174, 33, 'ar', 174, 'أوتوماتيك', NULL, NULL, NULL, NULL),
(175, 33, 'ar', 175, 'عادي', NULL, NULL, NULL, NULL),
(324, 50, 'en', 420, '1970', NULL, NULL, NULL, NULL),
(325, 50, 'en', 420, '1971', NULL, NULL, NULL, NULL),
(326, 50, 'en', 420, '1972', NULL, NULL, NULL, NULL),
(327, 50, 'en', 420, '1973', NULL, NULL, NULL, NULL),
(328, 50, 'en', 420, '1974', NULL, NULL, NULL, NULL),
(329, 50, 'en', 420, '1975', NULL, NULL, NULL, NULL),
(330, 50, 'en', 420, '1976', NULL, NULL, NULL, NULL),
(331, 50, 'en', 420, '1977', NULL, NULL, NULL, NULL),
(332, 50, 'en', 420, '1978', NULL, NULL, NULL, NULL),
(333, 50, 'en', 420, '1979', NULL, NULL, NULL, NULL),
(334, 50, 'en', 420, '1980', NULL, NULL, NULL, NULL),
(335, 50, 'en', 420, '1981', NULL, NULL, NULL, NULL),
(336, 50, 'en', 420, '1982', NULL, NULL, NULL, NULL),
(337, 50, 'en', 420, '1983', NULL, NULL, NULL, NULL),
(338, 50, 'en', 420, '1984', NULL, NULL, NULL, NULL),
(339, 50, 'en', 420, '1985', NULL, NULL, NULL, NULL),
(340, 50, 'en', 420, '1986', NULL, NULL, NULL, NULL),
(341, 50, 'en', 420, '1987', NULL, NULL, NULL, NULL),
(342, 50, 'en', 420, '1988', NULL, NULL, NULL, NULL),
(343, 50, 'en', 420, '1989', NULL, NULL, NULL, NULL),
(344, 50, 'en', 420, '1990', NULL, NULL, NULL, NULL),
(345, 50, 'en', 420, '1991', NULL, NULL, NULL, NULL),
(346, 50, 'en', 420, '1992', NULL, NULL, NULL, NULL),
(347, 50, 'en', 420, '1993', NULL, NULL, NULL, NULL),
(348, 50, 'en', 420, '1994', NULL, NULL, NULL, NULL),
(349, 50, 'en', 420, '1995', NULL, NULL, NULL, NULL),
(350, 50, 'en', 420, '1996', NULL, NULL, NULL, NULL),
(351, 50, 'en', 420, '1997', NULL, NULL, NULL, NULL),
(352, 50, 'en', 420, '1998', NULL, NULL, NULL, NULL),
(353, 50, 'en', 420, '1999', NULL, NULL, NULL, NULL),
(354, 50, 'en', 420, '2000', NULL, NULL, NULL, NULL),
(355, 50, 'en', 420, '2001', NULL, NULL, NULL, NULL),
(356, 50, 'en', 420, '2002', NULL, NULL, NULL, NULL),
(357, 50, 'en', 420, '2003', NULL, NULL, NULL, NULL),
(358, 50, 'en', 420, '2004', NULL, NULL, NULL, NULL),
(359, 50, 'en', 420, '2005', NULL, NULL, NULL, NULL),
(360, 50, 'en', 420, '2006', NULL, NULL, NULL, NULL),
(361, 50, 'en', 420, '2007', NULL, NULL, NULL, NULL),
(362, 50, 'en', 420, '2008', NULL, NULL, NULL, NULL),
(363, 50, 'en', 420, '2009', NULL, NULL, NULL, NULL),
(364, 50, 'en', 420, '2010', NULL, NULL, NULL, NULL),
(365, 50, 'en', 420, '2011', NULL, NULL, NULL, NULL),
(366, 50, 'en', 420, '2012', NULL, NULL, NULL, NULL),
(367, 50, 'en', 420, '2013', NULL, NULL, NULL, NULL),
(368, 50, 'en', 420, '2014', NULL, NULL, NULL, NULL),
(369, 50, 'en', 420, '2015', NULL, NULL, NULL, NULL),
(370, 50, 'en', 420, '2015', NULL, NULL, NULL, NULL),
(371, 50, 'en', 420, '2016', NULL, NULL, NULL, NULL),
(372, 50, 'en', 420, '2017', NULL, NULL, NULL, NULL),
(373, 50, 'en', 420, '2018', NULL, NULL, NULL, NULL),
(374, 50, 'en', 420, '2019', NULL, NULL, NULL, NULL),
(375, 50, 'en', 420, '2020', NULL, NULL, NULL, NULL),
(387, 52, 'en', 420, 'ابلاندر', NULL, NULL, NULL, NULL),
(388, 52, 'en', 420, 'ابيكا', NULL, NULL, NULL, NULL),
(389, 52, 'en', 420, 'افيو', NULL, NULL, NULL, NULL),
(390, 52, 'en', 420, 'استروفان', NULL, NULL, NULL, NULL),
(391, 52, 'en', 420, 'افلانش', NULL, NULL, NULL, NULL),
(392, 54, 'en', 420, 'مكسيما', NULL, NULL, NULL, NULL),
(393, 54, 'en', 420, 'ددسن', NULL, NULL, NULL, NULL),
(394, 54, 'en', 420, 'مورانو', NULL, NULL, NULL, NULL),
(395, 54, 'en', 420, 'التيما', NULL, NULL, NULL, NULL),
(396, 54, 'en', 420, 'جلوريا', NULL, NULL, NULL, NULL),
(397, 56, 'en', 420, 'كراون فكتوريا', NULL, NULL, NULL, NULL),
(398, 56, 'en', 420, 'اسكيب', NULL, NULL, NULL, NULL),
(399, 56, 'en', 420, 'اكسبيدشن', NULL, NULL, NULL, NULL),
(400, 56, 'en', 420, 'اكسبلورر', NULL, NULL, NULL, NULL),
(401, 56, 'en', 420, 'جراند ماركيز', NULL, NULL, NULL, NULL),
(402, 59, 'ar', 420, 'افالون', NULL, NULL, NULL, NULL),
(403, 59, 'ar', 420, 'اوريون', NULL, NULL, NULL, NULL),
(404, 59, 'ar', 420, 'اينوفا', NULL, NULL, NULL, NULL),
(405, 59, 'ar', 420, 'برادو', NULL, NULL, NULL, NULL),
(406, 52, 'ar', 420, 'ابلاندر', NULL, NULL, NULL, NULL),
(407, 52, 'ar', 420, 'ابيكا', NULL, NULL, NULL, NULL),
(408, 52, 'ar', 420, 'افيو', NULL, NULL, NULL, NULL),
(409, 52, 'ar', 420, 'استروفان', NULL, NULL, NULL, NULL),
(410, 52, 'ar', 420, 'امبالا', NULL, NULL, NULL, NULL),
(411, 54, 'ar', 420, 'مورانو', NULL, NULL, NULL, NULL),
(412, 54, 'ar', 420, 'التيما', NULL, NULL, NULL, NULL),
(413, 54, 'ar', 420, 'جلوريا', NULL, NULL, NULL, NULL),
(414, 54, 'ar', 420, 'باثفندر', NULL, NULL, NULL, NULL),
(415, 54, 'ar', 420, 'بريميرا', NULL, NULL, NULL, NULL),
(416, 56, 'ar', 420, 'كراون فكتوريا', NULL, NULL, NULL, NULL),
(417, 56, 'ar', 420, 'اكسبيدشن', NULL, NULL, NULL, NULL),
(418, 56, 'ar', 420, 'لنكون', NULL, NULL, NULL, NULL),
(419, 56, 'ar', 420, 'مونديو', NULL, NULL, NULL, NULL),
(420, 56, 'ar', 420, 'بيك اب', NULL, NULL, NULL, NULL),
(421, 50, 'ar', NULL, '1970', NULL, NULL, NULL, NULL),
(422, 50, 'ar', NULL, '1971', NULL, NULL, NULL, NULL),
(423, 50, 'ar', NULL, '1972', NULL, NULL, NULL, NULL),
(424, 50, 'ar', NULL, '1973', NULL, NULL, NULL, NULL),
(425, 50, 'ar', NULL, '1974', NULL, NULL, NULL, NULL),
(426, 50, 'ar', NULL, '1975', NULL, NULL, NULL, NULL),
(427, 50, 'ar', NULL, '1976', NULL, NULL, NULL, NULL),
(428, 50, 'ar', NULL, '1977', NULL, NULL, NULL, NULL),
(429, 50, 'ar', NULL, '1978', NULL, NULL, NULL, NULL),
(430, 50, 'ar', NULL, '1979', NULL, NULL, NULL, NULL),
(431, 50, 'ar', NULL, '1980', NULL, NULL, NULL, NULL),
(432, 50, 'ar', NULL, '1981', NULL, NULL, NULL, NULL),
(433, 50, 'ar', NULL, '1982', NULL, NULL, NULL, NULL),
(434, 50, 'ar', NULL, '1983', NULL, NULL, NULL, NULL),
(435, 50, 'ar', NULL, '1994', NULL, NULL, NULL, NULL),
(436, 52, 'en', 406, 'ابلاندر', NULL, NULL, NULL, NULL),
(437, 52, 'en', 407, 'ابيكا', NULL, NULL, NULL, NULL),
(438, 52, 'en', 408, 'افيو', NULL, NULL, NULL, NULL),
(439, 52, 'en', 409, 'استروفان', NULL, NULL, NULL, NULL),
(440, 52, 'en', 410, 'امبالا', NULL, NULL, NULL, NULL),
(441, 54, 'en', 411, 'مورانو', NULL, NULL, NULL, NULL),
(442, 54, 'en', 412, 'التيما', NULL, NULL, NULL, NULL),
(443, 54, 'en', 413, 'جلوريا', NULL, NULL, NULL, NULL),
(444, 54, 'en', 414, 'باثفندر', NULL, NULL, NULL, NULL),
(445, 54, 'en', 415, 'بريميرا', NULL, NULL, NULL, NULL),
(446, 59, 'en', 402, 'افالون', NULL, NULL, NULL, NULL),
(447, 59, 'en', 403, 'اوريون', NULL, NULL, NULL, NULL),
(448, 59, 'en', 404, 'اينوفا', NULL, NULL, NULL, NULL),
(449, 59, 'en', 405, 'برادو', NULL, NULL, NULL, NULL),
(450, 56, 'en', 416, 'كراون فكتوريا', NULL, NULL, NULL, NULL),
(451, 56, 'en', 417, 'اكسبيدشن', NULL, NULL, NULL, NULL),
(452, 56, 'en', 418, 'لنكون', NULL, NULL, NULL, NULL),
(453, 56, 'en', 419, 'مونديو', NULL, NULL, NULL, NULL),
(454, 63, 'ar', NULL, 'شاهين1991', NULL, NULL, NULL, NULL),
(455, 63, 'ar', NULL, 'شاهين2000', NULL, NULL, NULL, NULL),
(456, 58, 'ar', NULL, 'مرسيدس280اس', NULL, NULL, NULL, NULL),
(457, 50, 'ar', NULL, '2020', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqagender`
--

CREATE TABLE `theqqagender` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqagender`
--

INSERT INTO `theqqagender` (`id`, `translation_lang`, `translation_of`, `name`) VALUES
(1, 'en', 3, 'Mr'),
(2, 'en', 4, 'Mrs'),
(3, 'ar', 3, 'Mr'),
(4, 'ar', 4, 'Mrs');

-- --------------------------------------------------------

--
-- Table structure for table `theqqahome_sections`
--

CREATE TABLE `theqqahome_sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `method` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `view` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `theqqahome_sections`
--

INSERT INTO `theqqahome_sections` (`id`, `method`, `name`, `value`, `view`, `field`, `parent_id`, `lft`, `rgt`, `depth`, `active`) VALUES
(1, 'getSearchForm', 'Search Form Area', '{\"enable_form_area_customization\":\"1\",\"background_color\":null,\"background_image\":\"app\\/logo\\/header-5c372aaa779d3.jpeg\",\"height\":\"300px\",\"parallax\":\"0\",\"hide_form\":\"0\",\"form_border_color\":\"#467faf\",\"form_border_width\":null,\"form_btn_background_color\":null,\"form_btn_text_color\":null,\"hide_titles\":\"0\",\"title_en\":\"Join our precious clients list\",\"sub_title_en\":\"to sell and buy new and used cars\",\"title_ar\":\"\\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u0622\\u0646 \\u0627\\u0644\\u0627\\u0646\\u0636\\u0645\\u0627\\u0645 \\u0644\\u0642\\u0627\\u0626\\u0645\\u0629 \\u0639\\u0645\\u0644\\u0627\\u0626\\u0646\\u0627 \\u0627\\u0644\\u0643\\u0631\\u0627\\u0645\",\"sub_title_ar\":\"\\u0644\\u0628\\u064a\\u0639 \\u0623\\u0648 \\u0634\\u0631\\u0627\\u0621 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0627\\u062a \\u0627\\u0644\\u062c\\u062f\\u064a\\u062f\\u0629 \\u0648\\u0627\\u0644\\u0645\\u0633\\u062a\\u0639\\u0645\\u0644\\u0629\",\"big_title_color\":\"#ffffff\",\"sub_title_color\":\"#ffffff\",\"active\":\"1\"}', 'home.inc.search', NULL, 0, 2, 3, 1, 1),
(2, 'getLocations', 'Locations & Country Map', NULL, 'home.inc.locations', NULL, 0, 10, 11, 1, 1),
(3, 'getSponsoredPosts', 'Sponsored Ads', '{\"max_items\":\"20\",\"order_by\":\"date\",\"autoplay\":\"1\",\"autoplay_timeout\":null,\"cache_expiration\":null,\"active\":\"1\"}', 'home.inc.featured', NULL, 0, 6, 7, 1, 1),
(4, 'getCategories', 'Categories', '{\"type_of_display\":\"c_picture_icon\",\"show_icon\":\"1\",\"max_sub_cats\":\"3\",\"max_items\":null,\"cache_expiration\":null,\"active\":\"1\"}', 'home.inc.categories', NULL, 0, 4, 5, 1, 1),
(5, 'getLatestPosts', 'Latest Ads', NULL, 'home.inc.latest', NULL, 0, 8, 9, 1, 1),
(6, 'getStats', 'Mini Stats', '{\"active\":\"1\"}', 'home.inc.stats', NULL, 0, 12, 13, 1, 1),
(7, 'getTopAdvertising', 'Advertising #1', '{\"active\":\"1\"}', 'layouts.inc.advertising.top', NULL, 0, 14, 15, 1, 0),
(8, 'getBottomAdvertising', 'Advertising #2', '{\"active\":\"1\"}', 'layouts.inc.advertising.bottom', NULL, 0, 16, 17, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `theqqaimageservice`
--

CREATE TABLE `theqqaimageservice` (
  `id` int(11) NOT NULL,
  `image_code` text NOT NULL,
  `image_title` varchar(255) NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `theqqalanguages`
--

CREATE TABLE `theqqalanguages` (
  `id` int(10) UNSIGNED NOT NULL,
  `abbr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `native` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `script` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direction` enum('ltr','rtl') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ltr',
  `russian_pluralization` tinyint(1) UNSIGNED DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqalanguages`
--

INSERT INTO `theqqalanguages` (`id`, `abbr`, `locale`, `name`, `native`, `flag`, `app_name`, `script`, `direction`, `russian_pluralization`, `active`, `default`, `parent_id`, `lft`, `rgt`, `depth`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'en', 'en_US', 'English', 'EN', NULL, 'english', 'Latn', 'ltr', 1, 1, 0, 0, 2, 3, 1, NULL, NULL, NULL),
(2, 'ar', 'ar_SA', 'Arabic', 'Ar', NULL, 'arabic', NULL, 'rtl', 0, 1, 1, 0, 4, 5, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqamessages`
--

CREATE TABLE `theqqamessages` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `from_user_id` int(10) UNSIGNED DEFAULT '0',
  `from_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_user_id` int(10) UNSIGNED DEFAULT '0',
  `to_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_driving_license` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_purchaser_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_seller_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_car_arr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` text COLLATE utf8_unicode_ci,
  `id_code` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `filename` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) UNSIGNED DEFAULT '0',
  `deleted_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqameta_tags`
--

CREATE TABLE `theqqameta_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `translation_of` int(10) UNSIGNED NOT NULL,
  `page` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `theqqameta_tags`
--

INSERT INTO `theqqameta_tags` (`id`, `translation_lang`, `translation_of`, `page`, `title`, `description`, `keywords`, `active`) VALUES
(1, 'en', 9, 'home', '{app_name} - Theqqa for Digital Marketing', 'Sell and Buy  Cars on {app_name} in Minutes {country}. Free ads in {country}. Looking for a product or service - {country}', '{app_name}, {country}, free ads, ads, script, app, premium ads', 1),
(2, 'en', 10, 'register', 'Sign Up - {app_name}', 'Sign Up on {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(3, 'en', 11, 'login', 'Login - {app_name}', 'Log in to {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(4, 'en', 12, 'create', 'Post Free Ads', 'Post Free Ads - {country}.', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(5, 'en', 13, 'countries', '{app_name} - Theqqa for Digital Marketing', 'Theqqa for Digital Marketing', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads,Theqqa for Digital Marketing', 1),
(6, 'en', 14, 'contact', 'Contact Us - {app_name}', 'Contact Us - {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(7, 'en', 15, 'sitemap', 'Sitemap {app_name} - {country}', 'Sitemap {app_name} - {country}. 100% Free Ads Classified', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(8, 'en', 16, 'password', 'Lost your password? - {app_name}', 'Lost your password? - {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(9, 'ar', 9, 'home', '{app_name} - ثقة للتسويق الإلكتروني', 'Sell and Buy Cars on {app_name} in Minutes {country}. Free ads in {country}. Looking for a product or service - {country}', '{app_name}, {country}, free ads, ads, script, app, premium ads', 1),
(10, 'ar', 10, 'register', 'Sign Up - {app_name}', 'Sign Up on {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(11, 'ar', 11, 'login', 'Login - {app_name}', 'Log in to {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(12, 'ar', 12, 'create', 'Post Free Ads', 'Post Free Ads - {country}.', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(13, 'ar', 13, 'countries', '{app_name} - ثقة للتسويق الإلكتروني', 'ثقة للتسويق الإلكتروني', '{app_name}, {country}, free ads, ads, script, app, premium ads,ثقة للتسويق الإلكتروني', 1),
(14, 'ar', 14, 'contact', 'Contact Us - {app_name}', 'Contact Us - {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(15, 'ar', 15, 'sitemap', 'Sitemap {app_name} - {country}', 'Sitemap {app_name} - {country}. 100% Free Ads Classified', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1),
(16, 'ar', 16, 'password', 'Lost your password? - {app_name}', 'Lost your password? - {app_name}', '{app_name}, {country}, free ads, classified, ads, script, app, premium ads', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqamigrations`
--

CREATE TABLE `theqqamigrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqamigrations`
--

INSERT INTO `theqqamigrations` (`migration`, `batch`) VALUES
('2016_06_01_000001_create_oauth_auth_codes_table', 1),
('2016_06_01_000002_create_oauth_access_tokens_table', 1),
('2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
('2016_06_01_000004_create_oauth_clients_table', 1),
('2016_06_01_000005_create_oauth_personal_access_clients_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqamodel_has_permissions`
--

CREATE TABLE `theqqamodel_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqamodel_has_roles`
--

CREATE TABLE `theqqamodel_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqamodel_has_roles`
--

INSERT INTO `theqqamodel_has_roles` (`role_id`, `model_id`, `model_type`) VALUES
(1, 1, 'App\\Models\\User'),
(1, 256, 'App\\Models\\User'),
(1, 268, 'App\\Models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaoauth_access_tokens`
--

CREATE TABLE `theqqaoauth_access_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaoauth_access_tokens`
--

INSERT INTO `theqqaoauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('008a34fe631197bd26ffc0b6ee250cee51f2026a7dcbf58673f6ebda426c0fe87a7a880e7e52d67e', 1, 2, 'theqqaPassport', '[]', 0, '2019-07-16 18:43:32', '2019-07-16 18:43:32', '2020-07-16 13:43:32'),
('14196d01635439fd5397d8576a084741b013de7d1a11bd748d2c00f38ce7936c595e3e7457045b35', 6, 2, 'theqqaPassport', '[]', 0, '2020-02-07 01:01:36', '2020-02-07 01:01:36', '2021-02-06 19:01:36'),
('26989bff206008d31cf03f32fb00dd6c2dbd085939af98f5f8e24a8e9bf8e80a3d4b0bf206f29982', 2, 2, 'theqqaPassport', '[]', 0, '2020-02-05 21:48:44', '2020-02-05 21:48:44', '2021-02-05 15:48:44'),
('31fef6f953e617ff56592817c2f19010b87fd78dbd770417a8e26ef2a99bce2a07f05b1aca612e6d', 5, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:25:21', '2020-02-07 00:25:21', '2021-02-06 18:25:21'),
('3bae19b29c1167f6b9f961eb42f7eca76ecb95b14f069c1b97f25cb33a32da0be1e02fe812f04753', 3, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:28:01', '2020-02-07 00:28:01', '2021-02-06 18:28:01'),
('543e9919c3e3495168ae004492937a4bbbc9be25039afe77375977b675eb84a8657be0ec853f6071', 4, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:36:44', '2020-02-07 00:36:44', '2021-02-06 18:36:44'),
('745f35a5375374253cfdddbb58757810598e63fb776e395c9022264d7fb6ae462d05ecc4b58950bf', 4, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:14:25', '2020-02-07 00:14:25', '2021-02-06 18:14:25'),
('77bf428cd0df5f0f8360c48566034f49e9d81efffff5e87a4cc87a5112e8fd60c6badf2ae8edb612', 2, 2, 'theqqaPassport', '[]', 0, '2020-02-05 21:52:45', '2020-02-05 21:52:45', '2021-02-05 15:52:45'),
('782656aa7e4467094f3d08b9a56cbd3c3e370c93133094432455e36ae5e9466c189dfff569514326', 6, 2, 'theqqaPassport', '[]', 0, '2020-02-07 01:05:50', '2020-02-07 01:05:50', '2021-02-06 19:05:50'),
('a6cee63c39baf41ed10439dd76375b9ebb956d97264b10668cd1e413f5646f7c6d8060e82a12c73e', 3, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:07:10', '2020-02-07 00:07:10', '2021-02-06 18:07:10'),
('ca505c78245c0f28ec398ae7c776916e819dd4a2313dfac87b11e130e2da4e8f06eac425a96eec35', 5, 2, 'theqqaPassport', '[]', 0, '2020-02-07 00:41:58', '2020-02-07 00:41:58', '2021-02-06 18:41:58'),
('d3df7d5bc653898f335772e485de9f8055b41387206e4ab0564d511faceca1800f1a2ebd646dd19e', 263, 2, 'theqqaPassport', '[]', 0, '2019-12-23 21:17:28', '2019-12-23 21:17:28', '2020-12-23 15:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaoauth_auth_codes`
--

CREATE TABLE `theqqaoauth_auth_codes` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqaoauth_clients`
--

CREATE TABLE `theqqaoauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaoauth_clients`
--

INSERT INTO `theqqaoauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Theqqa Password Client', 'dNAoJEjqHOYEaPHgKCnO5ovabIkkjgy5rcilIZED', 'http://localhost', 0, 1, 0, '2019-03-20 09:42:10', '2019-03-20 09:42:10'),
(2, NULL, 'clint2', 'pSCfrRC6U1kr9GryqkMqhSIQxsDDANBAaUkg5JYR', 'http://localhost', 1, 0, 0, '2019-03-20 09:55:27', '2019-03-20 09:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaoauth_personal_access_clients`
--

CREATE TABLE `theqqaoauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaoauth_personal_access_clients`
--

INSERT INTO `theqqaoauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2019-03-20 09:55:27', '2019-03-20 09:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaoauth_refresh_tokens`
--

CREATE TABLE `theqqaoauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqapackages`
--

CREATE TABLE `theqqapackages` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'In country language',
  `short_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'In country language',
  `ribbon` enum('red','orange','green') COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_badge` tinyint(1) UNSIGNED DEFAULT '0',
  `price` decimal(10,2) UNSIGNED DEFAULT NULL,
  `currency_code` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duration` int(10) UNSIGNED DEFAULT '30' COMMENT 'In days',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'In country language',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapackages`
--

INSERT INTO `theqqapackages` (`id`, `translation_lang`, `translation_of`, `name`, `short_name`, `ribbon`, `has_badge`, `price`, `currency_code`, `duration`, `description`, `parent_id`, `lft`, `rgt`, `depth`, `active`) VALUES
(1, 'en', 5, 'Regular List', 'FREE', NULL, 1, '0.00', 'SAR', 7, 'Regular List', 0, 4, 5, 1, 1),
(2, 'en', 6, 'Urgent Ad', 'Urgent', 'orange', 0, '30.00', 'SAR', 7, 'Urgent', 0, 2, 3, 1, 1),
(5, 'ar', 5, 'إعلان مجاني', 'مجانًا', NULL, 1, '0.00', 'SAR', 7, 'إعلان مجاني', 0, 4, 5, 1, 1),
(6, 'ar', 6, 'إعلان مميز', 'مميز', 'orange', 0, '30.00', 'SAR', 7, 'إعلان مميز', 0, 2, 3, 1, 1),
(9, 'ar', 9, 'خدمة موجز', 'مميزة', 'orange', 0, '75.00', 'SAR', 30, 'خدمة موجز', 0, 14, 15, 1, 1),
(10, 'en', 9, 'Urgent Ad mogaz', 'Urgent', 'orange', 0, '75.00', 'SAR', 30, 'Urgent', 0, 14, 15, 1, 1),
(11, 'ar', 11, 'خدمة تسهيل نقل الملكية', 'مميزة', 'orange', 0, '300.00', 'SAR', 30, 'خدمة تسهيل نقل الملكية', 0, 10, 11, 1, 1),
(12, 'en', 11, 'Urgent Ad ownership', 'Urgent', 'orange', 0, '300.00', 'SAR', 30, 'Urgent', 0, 10, 11, 1, 1),
(13, 'ar', 13, 'خدمة معاينة السيارة', 'مميزة', 'orange', 0, '100.00', 'SAR', 1, 'خدمة معاينة السيارة', 0, 6, 7, 1, 1),
(14, 'en', 13, 'Urgent Ad checking', 'Urgent', 'orange', 0, '100.00', 'SAR', 1, 'Urgent', 0, 6, 7, 1, 1),
(15, 'ar', 15, 'خدمة شحن السيارة', 'مميزة', 'orange', 0, '50.00', 'SAR', 30, 'خدمة شحن السيارة', 0, 12, 13, 1, 1),
(16, 'en', 15, 'Urgent Ad shipping', 'Urgent', 'orange', 0, '50.00', 'SAR', 30, 'Urgent', 0, 12, 13, 1, 1),
(17, 'ar', 17, 'خدمة فحص السيارة', 'مميزة', 'orange', 0, '500.00', 'SAR', 30, 'خدمة فحص السيارة', 0, 8, 9, 1, 1),
(18, 'en', 17, 'Urgent Ad maintenance', 'Urgent', 'orange', 0, '500.00', 'SAR', 30, 'Urgent', 0, 8, 9, 1, 1),
(19, 'ar', 19, 'حجز السيارة', 'مميزة', NULL, 0, '50.00', 'SAR', 1, 'Urgent', 0, 18, 19, 1, 1),
(20, 'en', 19, 'booking car', 'Urgent', NULL, 0, '50.00', 'SAR', 1, 'Urgent', 0, 18, 19, 1, 1),
(21, 'ar', 21, 'خدمة تقدير ثمن السيارة', 'مميزة', 'orange', 0, '0.00', 'SAR', 30, 'خدمة تقدير ثمن السيارة', 0, 16, 17, 1, 1),
(22, 'en', 21, 'Estimation car', 'Urgent', 'orange', 0, '0.00', 'SAR', 30, 'Urgent', 0, 16, 17, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqapages`
--

CREATE TABLE `theqqapages` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('standard','terms','privacy','tips') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `external_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `name_color` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title_color` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_blank` tinyint(1) UNSIGNED DEFAULT '0',
  `excluded_from_footer` tinyint(1) UNSIGNED DEFAULT '0',
  `active` tinyint(1) UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapages`
--

INSERT INTO `theqqapages` (`id`, `translation_lang`, `translation_of`, `parent_id`, `type`, `name`, `slug`, `title`, `picture`, `content`, `external_link`, `lft`, `rgt`, `depth`, `name_color`, `title_color`, `target_blank`, `excluded_from_footer`, `active`, `created_at`, `updated_at`) VALUES
(1, 'en', 5, 0, 'terms', 'Terms', 'terms', 'Terms & Conditions', NULL, '<p>1.</p><p>Location: Theqqa website</p><p>Member: Any real or legal person who has registered on the site</p><p>Advertiser: Any real or legal person who has added his ad on the site for sale</p><p>Browser: Anyone browsing the site without registration</p><p>Business Partners: Any firm that Thiqa contracts with in order to provide additional new and improved services</p><p>2. The Islamic Shari\'a, laws and customs must be observed in all data entered either in declarations or member statements. Any violation will result in the deletion of the ad and may result in the cancellation of membership on the site.</p><p>3. The use of a false or improper username or use of vague letters or numbers is not permitted in the registration process.</p><p>4. No person or any party shall be subjected to insult, abuse, defamation or writing in contravention of the customary laws.</p><p>5. The member or advertiser informs that their contact information (phone number or e-mail) that they have added to the site will be published on the site so that the other party can communicate with him and complete the business process with him. The site is not obliged to confidential this data. The Member and the advertiser shall be fully responsible for all matters related to them and shall fill them in the Site.</p><p>6. When adding an ad, make sure to add it in the right place in terms of section and sub-category.</p><p>7. All credentials must be credited (User Address, Ad Text, City, Contact Numbers, etc.)</p><p>8.Do not repeat the same ad more than once, as this will delete all duplicate ads, and if you repeat the ad from the same member more than once, this will result in the loss of membership.</p><p>9. Classified car advertisements must be adhered to and obscure or incomplete vague ads are not allowed.</p><p>10. Links to other sites are not permitted for advertising purposes.</p><p>11. The site is available to visitors and members to enable easy access to car advertisements in the MENA region. The information provided on the Site is for personal use only. No information may be reproduced or copied to increase traffic to any other site for commercial or informational reasons, including advertising. In addition, the user may not use the “theqqa” logo or reproduce any content for any purpose without written consent. In case of any question or request we hope to contact the email at&nbsp;support@theqqa.com.sa or contact us page.</p><p>12. The content of the Site may not be copied, reproduced, published, broadcast, modified, distributed, commercially exploited in any way or made available through the Services or made available through the Web.</p><p>13. Automatic web browsers, such as software known as robot spider, scraper, etc., may not be used to access the theqqa site and collect content for any purpose or otherwise copy or download content. A limited exception to this rule is given to search engines and public non-commercial archiving libraries, but not to websites with any form of classified lists.</p><p>14. Information about others, including their e-mail addresses, may not be collected or collected without their consent.</p><p>15. All advertisements, topics and responses on the site represent the owners and do not represent the site of theqqa or any of its business partners.</p><p>16. Commercial transactions (sale, purchase, lease, kissing, etc.) between the parties to the transaction are carried out directly and without any liability or intervention from the Site. The site strongly warns against the transfer of funds, and advised to deal directly hand in hand.</p><p>17. Agreements or commercial transactions (sale, purchase, rental, kissing, etc.) between the parties (advertiser and browser) are made directly and without any responsibility or intervention from the site.</p><p>18. There is no need to theqqa a party to enter into any dispute that arises between the member and other members of the site.</p><p>19. You hereby agree to indemnify, defend and protect Confidence with its Shareholders, officers, directors, employees and agents against any claims, claims, requests, damages, losses or fees attributable to or arising out of your use or misuse of the Site or any Content, or your breach of the Terms. Such use, infringement of the property rights of any third party, or disputes relating to or arising out of the Site in any way between the Member and another user or more.</p><p>20. Confidence under these Terms shall be limited to any liability for any damages whatsoever, including without limitation of direct, indirect, special, punitive, incidental and consequential damages and losses suffered by any user in connection with the Site or in connection with the use of the Site or its inability to use or the results of its use, As well as any other linked sites and any materials posted on it, including without limitation any liability for loss of income or revenue; loss of business; loss of profits or contracts; loss of anticipated savings; loss of data; or loss of goodwill Waste of administrative or office time; and any other loss or damage Of any kind, however arising, whether due to abuse (including negligence), breach of contract or otherwise, even if it is foreseeable.</p><p>In particular, Theqqa shall not be liable for any damages or losses suffered by the Member or another party as a result of:</p><p>Use or inability to use the Site</p><p>Services or goods obtained through the Site</p><p>Any content displayed on the site</p><p>A diffusive attack that prevents the service, viruses or other technically harmful material that may infect a computer, software, data or other material owned by a member because of the use of the site or the download of any material posted on it, or on any other linked site.</p><h4><br></h4>', NULL, 6, 7, 1, NULL, NULL, 0, 0, 1, '2017-02-10 11:10:40', '2019-11-17 16:55:50'),
(2, 'en', 6, 0, 'privacy', 'Privacy', 'privacy', 'Privacy', NULL, '<p>This Privacy Policy section of Theqqa website handles matters such as data taken from a registered member of Theqqa and others related to visitors and members in general and applies to any service on the site or product.</p><p>-	Personal data</p><p>We may not collect certain information and personal data from the member or visitor in different ways, for example when he registers on the site, registers for sale or purchase, registers on the mailing list of the site or agrees to collect some data when asked to ask him about Some of the data we may collect from our members and visitors are name, age, email, phone number and credit card data. If they are going to buy something, the user can refuse from Beginning Bezzo We provide any such data unless required to be able to use a service on the site or the site as a whole.</p><p>- Data is not personal</p><p>We may collect some non-personal information and data from the user or visitor to the site in order to interact with the various services of the site, rather it is technical data that helps us to find the best way to enable the user to better connect to our site and its various services</p><p>- Web browser cookies</p><p>Our site may use electronic cookies to improve the user\'s use of our site and various services, and these cookies save themselves on the user\'s computer in order to help the site to perform better for each user has his own cake on the site quick access to the site and download its data and Images fast enough.</p><p>- How do we collect data and information?</p><p>Theqqa collects this data and information in the following ways: Diagnosis of data: It is possible to collect data and information useful in how the site is useful for the user in terms of ease of use and speed of access to various information and services. Website Optimization: We collect some data such as invasions feedback about the views of users on the site and the various services provided to them so that we continuously develop and improve the site and its services. To inform the user of some new services and new topics and new announcements: We carry out these campaigns in order to keep the user aware of the new updates of the site or pass some of the developments of topics and others to keep him informed</p><p>- How do we protect your information?</p><p>Personal data and credit account data passed through the SSL protocol are fully encrypted and never published or stored on the site.</p><p>- Publish your data</p><p>We do not sell, trade or otherwise disclose your personal information to others. We may publish certain public information or statistics that you have shared with us. We do not refer to anyone who has participated in these or other statistics. We use your registered data in case we create another website</p><p>- Announcements</p><p>We post ads on our site and these ads may use cookies that are customized for each user or a specific category of users to suit the ads addressed to them.</p><p>- Changes to Privacy Policy</p><p>We may change the Privacy Policy as a whole, change part of it, or delete part of it without notifying anyone of this and all that we enable to inform the user is the most recent update of this document and the date listed at the end.</p><p>- Certification of privacy policy</p><p>By registering and using the Site, you are deemed to have accepted that you agree to all the terms and conditions of the Privacy Policy.</p><p>-	Contact us</p><p>You can always contact us by&nbsp;support@theqqa.com.sa</p><p>This document was last edited - Privacy Policy - was on Thursday 12 November 2019</p><p>You agree to these Terms of Use and if you do not agree, you have no right to use the Site or engage in any activity in connection with it. The Site has the right to set any limits it deems at its sole discretion on your use of the Site, including but not limited to , The limits on how long the content can remain on the Website, the size of each content file, and the number of content that can be published, the Site reserves the right to block you or deny access to any breach of these Terms of Use at any time without the need to notify you. . These terms may be updated from time to time. We will notify you of any important updates by alerting you of these changes. Your continued use of our services constitutes your agreement to the changes. These terms will remain available to all users on this page for review. Certain provisions of these Terms of Use may be reproduced or supplemented by provisions or notices posted elsewhere on the Site. Please notify us if you know of any breaches of these Terms of Use by others.</p>', NULL, 8, 9, 1, NULL, NULL, 0, 0, 1, '2017-02-10 11:28:37', '2019-11-17 16:54:47'),
(3, 'en', 7, 0, 'standard', 'Cancellation Policy', 'cancellation-policy', 'Cancellation Policy', NULL, '<ul><li><span style=\"color: rgb(34, 34, 34); font-size: 28px;\">Within 12 hours of ordering the order can be canceled and refunded after deducting 10% of the amount</span></li></ul><ul><li><span style=\"color: rgb(34, 34, 34); font-size: 28px;\">In case of charging the car within 24 hours can be canceled and refund after 10 deduction</span><br></li></ul>', NULL, 4, 5, 1, NULL, NULL, 0, 0, 0, '2017-02-10 11:31:56', '2019-11-20 14:06:01'),
(4, 'en', 8, 0, 'standard', 'Refund policy in case of car', 'refund-policy-in-case-of-car', 'Refund policy in case of car', NULL, '<ul><li>The car can be returned within 3 days in case of checking the car before buying through Theqqa program.The vehicle is checked on the customer\'s account before the return to ensure its condition and the amount of 5% is deducted<br></li></ul>', NULL, 2, 3, 1, NULL, NULL, 0, 0, 1, '2017-02-10 11:34:56', '2019-11-17 17:21:22'),
(5, 'ar', 5, 0, 'terms', 'الشروط والأحكام', 'terms', 'الشروط والأحكام', NULL, '<p>1.بيانات موقع ثقة للتسويق الإلكتروني</p><ul><li><strong><u>الموقع</u></strong><strong>:&nbsp;</strong>ثقة للتسويق الإلكتروني<br></li><li><strong><u>عضو</u></strong><strong>:</strong>&nbsp;أي شخص حقيقي او اعتباري قام بالتسجيل في الموقع</li><li><strong><u>المُعلن</u></strong><strong>:</strong>&nbsp;أي شخص حقيقي او اعتباري قام بإضافة إعلانه في الموقع بغرض البيع</li><li><strong><u>المتصفح</u></strong><strong>:</strong>&nbsp;اي شخص يتصفح الموقع بدون التسجيل</li><li><strong><u>الشركاء التجاريين:</u></strong>&nbsp;اي منشأة تقوم&nbsp;<span style=\"font-size: 16px;\">ثقة</span> بالتعاقد معها بهدف تقديم خدمات اضافية جديدة ومحسنة</li></ul><p>2.يجب مراعاة تعاليم الشريعة الإسلامية والقوانين والأعراف في جميع البيانات المدخلة سواء في الإعلانات أو بيانات العضو. أي مخالفة لذلك ستؤدي لحذف الإعلان وقد يؤدي لإلغاء العضوية في الموقع.<br></p><p>3.لا يُسمح باستخدام اسم مستخدم غير حقيقي أو غير لائق أو استخدام حروف أو أرقام مبهمة في عملية التسجيل.<br></p><p>4.يجب عدم التعرض لأي شخص أو لأي جهة بالإهانة أو الإيذاء أو التشهير أو كتابة ما يتعارض مع القوانين المتعارف عليها حيث يؤدي ذلك إلى حذف عضوية العضو وكل ما يخصه.</p><p>5.يعلم العضو أوالمُعلن بأن معلومات الاتصال الخاصة به (رقم الهاتف أو البريد الإلكتروني) والتي قام بإضافتها في الموقع سيتم نشرها على الموقع وذلك ليتمكن الطرف الآخر من التواصل معه وإتمام العملية التجارية معه. وعليه لا يُلزم الموقع بسرية هذه البيانات. ويتحمل العضو والمُعلن المسؤولية الكاملة عن كل ما يخصه ويقوم بتعبئته في الموقع وإذا اتضح أن هذه المعلومات غير صحيحة، سواء بقصد أو بدون قصد، فسيؤدي ذلك إلى حذف عضوية العضو وكل ما يخصه.</p><p>6.عند إضافة أي إعلان يجب التأكد من إضافته في مكانه الصحيح من حيث القسم والتصنيف الفرعي .</p><p>7.يجب الإلتزام بالمصداقية في جميع بيانات الإعلان المُضاف (عنوان العضو، نص الإعلان، المدينة، أرقام الاتصال، ….الخ)</p><p>8.لايُسمح بتكرار الإعلان نفسه أكثر من مره، حيث سيؤدي ذلك إلى حذف كل الإعلانات المكرره، وفي حال القيام بتكرار الإعلان من نفس العضو لأكثر من مره يؤدي ذلك إلى فقدان العضوية.</p><p>9.يجب التقيد بالإعلانات المبوبة الخاصة بالسيارات ولا يُسمح بالإعلانات المبهمة غير الواضحة أو غير الكاملة.</p><p>10.لايُسمح بإضافة روابط لمواقع أخرى بهدف الإعلان لها.</p><p>11.يكون الموقع متاحا للزوار والإعضاء لتمكينهم من الوصول بسهوله إلى إعلانات السيارات في منطقة الشرق الاوسط وشمال افريقيا. وتكون المعلومات المقدمة في الموقع للاستخدام الشخصي فقط. ولا يجوز إعادة إنتاج أو نسخ المعلومات لزيادة الحركة لأي موقع آخر لأسباب تجارية أو معلوماتية بما في ذلك الإعلانات. بالإضافة إلى ذلك لا يجوز للمستخدم استخدام شعار “<span style=\"font-size: 16px;\">ثقة</span>” أو إعادة إنتاج أي محتوى لأي غرض دون الموافقة الخطية. وفي حالة وجود أي سؤال أو طلب نأمل الاتصال على البريد الإلكتروني&nbsp;<a href=\"http://mailto:theqqa@gmail.com\">theqqa@gmail.com</a>&nbsp;أو&nbsp;صفحة اتصل بنا.</p><p>12.لا يجوز نسخ محتوى الموقع، أو إعادة إنتاجه، أو نشره، أو إذاعته، أو تعديله، أو توزيعه، أو استغلاله تجاريا باي شكل من الأشكال أو جعله متاحاً من خلال الخدمات أو جعل تلك المحتويات متاحة من خلال الشبكة.</p><p>13.لا يجوز استخدام برامج التصفح الأوتوماتيكي للشبكة الإلكترونية، كالبرامج المعروفة بالعنكبوت الآلي robot spider برنامج scraper وغيرها، للدخول إلى موقع&nbsp;<span style=\"font-size: 16px;\">ثقة</span> وتجميع المحتوى لأي غرض كان أو القيام بنسخ المحتوى أو تنزيله بطريقة أخرى. ويتم منح استثناء محدود من هذه القاعدة لمحركات البحث ومكتبات الأرشفة العامة غير التجارية، لكن ليس للمواقع الإلكترونية التي تضم أي شكل من أشكال القوائم المبوبة.</p><p>14.لا يجوز جمع أو تحصيل المعلومات عن الآخرين، بما في ذلك عناوين بريدهم الإلكتروني، دون موافقتهم على ذلك.</p><p>15.جميع الإعلانات والمواضيع والردود في الموقع تمثل أصحابها ولا تمثل موقع&nbsp;<span style=\"font-size: 16px;\">ثقة</span> أو اي من شركاءها التجاريين.l,</p><p>16.تتم العمليات التجارية (بيع أو شراء أو تأجير أو تقبيل أو غير ذلك) بين طرفي العملية التجارية مباشرة وبدون أي مسئولية أو تدخل من الموقع. والموقع يُحذّر بشدة من تحويل الأموال، وينصح بالتعامل المباشر يداً بيد.</p><p>17.تتم الاتفاقيات أو العمليات التجارية (بيع، شراء، تأجير، تقبيل، أوغير ذلك) بين طرفي العملية (المعلن والمتصفح) مباشرة وبدون أي مسؤولية أو تدخل من الموقع.</p><p>18.ليس هنا ما يلزم&nbsp;<span style=\"font-size: 16px;\">ثقة</span> بالدخول طرفاً في أي خلاف ينشأ بين العضو وبين أعضاء آخرين للموقع.</p><p>19.توافق بموجب هذه الشروط على تعويض&nbsp;<span style=\"font-size: 16px;\">ثقة</span> والدفاع عنها وحمايتها مع مساهميها ومسؤوليها ومديريها وموظفيها ووكلائها تجاه أي مطالبات أو دعاوى أو طلبات أو أضرار أو خسائر أو أتعاب محامين يتم الادعاء بها أو تنتج عن استخدامك أو إساءة استخدامك للموقع أو لأي محتوى، أو مخالفتك لشروط الاستخدام هذه، أو انتهاك لحقوق الملكية الخاصة بأي طرف آخر، أو الخلافات المتعلقة بالموقع أو الناشئة عنه باي طريقة كانت بينن العضو وبين مستخدم آخر أو أكثر.</p><p>20.ينحصر&nbsp;<span style=\"font-size: 16px;\">ثقة</span> بموجب هذه الشروط، أي مسؤولية لها عن أي أضرار أياً كانت وهذا يشمل دون تحديد الأضرار والخسائر المباشرة وغير المباشرة والخاصة والعقابية والعارضة والاستتباعية التي تصيب أي مستخدم فيما يتعلق بالموقع أو فيما يتعلق باستخدام الموقع أو عدم القدرة على استخدامه أو نتائج استخدامه، وكذلك أية مواقع أخرى مرتبطة به وأية مواد منشورة عليه، وهذا يشمل، لكن من دون تحديد، أي مسؤولية عن خسارة دخل أو إيراد؛ أو خسارة عمل؛ أو خسارة أرباح أو عقود؛ أو خسارة توفيرات متوقعة؛ أو ضياع بيانات؛ أو خسارة سمعة حسنة؛ أو هدر أوقات إدارية أو مكتبية؛ وأي خسارة أو ضرر آخر من أي نوع كان وكيفما نشأ وسواء كان بسبب الإساءة (بما فيها الإهمال) أو مخالفة العقد أو غير ذلك، حتى لو كان ذلك قابلاً للتوقع.</p><p>وعلى وجه الخصوص، لا تتحمل&nbsp;<span style=\"font-size: 16px;\">ثقة</span> المسؤولية عن أية أضرار أو خسائر تصيب العضو أو تصيب طرفاً آخر نتيجة:</p><ul><li>استخدام الموقع أو عدم القدرة على استخدامه</li><li>الخدمات أو السلع التي يتم الحصول عليها عن طريق الموقع</li><li>أي محتوى معروض على الموقع</li><li>هجوم انتشاري يمنع الخدمة، أو فيروسات أو مواد أخرى ضارة تقنياً يمكن ان تصيب أجهزة الحاسوب أو البرامج أو البيانات أو المواد الأخرى التي يمتلكها العضو بسبب استخدام للموقع أو تنزيل لأي مواد منشورة عليه، أو على أي موقع آخر مرتبط به.</li></ul><p>1.تقع تحمل مسؤولية ومخاطرة استخدام هذا الموقع عليك فهذا الموقع مقدم لك دون أي ضمانات وبموجبه فإن&nbsp;<span style=\"font-size: 16px;\">ثقة</span> تنفي تقديم أي ضمانات أو تعهدات من أي نوع كان شفهية كانت أو كتابية، صريحة أو ضمنية، أو ناشئة بمقتضى القانون أو العرف أو ما جرت عليه التعاملات أو العادة التجارية، بشأن الموقع أو أي خدمات تقدم عن طريقه.</p><p>2.نظراً لأن الإعلانات على موقع&nbsp;<span style=\"font-size: 16px;\">ثقة</span> يتم إدخالها من جميع مستخدمي وأعضاء الموقع في جميع أنحاء الشرق الاوسط وشمال افريقيا فإن الموقع غير مسؤول عن أي إعلانات لا تحقق المصداقيه. ويشجع موقع&nbsp;<span style=\"font-size: 16px;\">ثقة</span> جميع مستخدمي الموقع وخدماته أن يرسلوا أي مشاكل أو سوء استخدام للإعلانات أو ملاحظات أخرى على الموقع إلى البريد الإلكتروني على<a href=\"http://mailto:support@theqqa.com.sa\">support@theqqa.com.sa</a></p><p>3.إن شعار&nbsp;<span style=\"font-size: 16px;\">ثقة</span> وكل الشعارات العضوية في الموقع هي علامات تجارية حصرية.</p><p>4.استخدام العضو للموقع يعني موافقة والتزامه بكل بنود الاتفاقية السابق ذكرها.</p><p>5.تحترم&nbsp;<span style=\"font-size: 16px;\">ثقة</span> خصوصية العضو ولذلك قامت بوضع سياسة خصوصية مفصلة وضمنتها هذه الاتفاقية. نرجو أن يأخذ العضو ما يكفي من الوقت في قراءة سياسة الخصوصية الخاصة بنا. وتجدر الإشارة إلى أنه بالموافقة على هذه الشروط، تكون قد وافقت أيضاً على سياسة الخصوصية المعتمدة من قبلنا أيضاً.</p><p>6.يحق للموقع تغيير الشروط والأحكام الخاصة بهذه الإتفاقية في أي وقت رأت ذلك. ويعد الاستخدام المستمر للموقع و خدماته بعد تعديل هذه الشروط والأحكام موافقة من قبل العضو على جميع الشروط والأحكام.</p>', NULL, 6, 7, 1, NULL, NULL, 0, 0, 1, '2019-01-07 08:45:10', '2019-11-26 20:08:04'),
(6, 'ar', 6, 0, 'privacy', 'سياسة الخصوصية', 'privacy', 'سياسة الخصوصية', NULL, '<ol><li><span style=\"font-size: 18px; color: rgb(18, 80, 152);\"><a href=\"https://eg.hatla2ee.com/ar/general-condition#conditionsSummary\"><br></a></span><p>هذا القسم الخاص بسياسة الخصوصية المعمول به في موقع ثقة يعالج أمور مثل البيانات المأخوذة من العضو المسجل بموقع ثقة و غيرها مما يتعلق بالزائرين و الأعضاء بشكل عام و يطبق كل ما فيه على أي خدمة بالموقع أو منتج من منتجاته .</p><ul><b>-	البيانات الشخصية</b><li>نحن ربما ما نقوم بتجميع بعض الملعومات و البيانات الشخصية من العضو أو الزائر بطرق مختلفة، فمثلا عندما يقوم بالتسجيل بالموقع أو تسجيل إعلانه للبيع أو الشراء أو التسجيل بالقائمة البريدية الخاصة بالموقع أو أن يقوم بالموافقة على جمع بعض البيانات عندما يسأل في ذلك من شأنها سؤاله عن تطوير خدمة معينة أو إستطلاع رأي و لربما ما نظهر تلك البيانات معروضة على موقعنا، بعض البيانات التي قد نجمعها من الأعضاء و الزائرين هي الإسم و السن و البريد الإلكتروني ورقم الهاتف و بيانات البطاقة الإتمانية إذا كان سيقوم بشراء شيء ما، يستطيع المستخدم أن يرفض من البداية بتزويدنا أي من تلك البيانات ما لم تكن مطلوبة لإمكانه من إستخدام خدمة بالموقع أو الموقع ككل .</li></ul><ul><b>-	بيانات ليست شخصية</b><li>نحن ربما ما نقوم بتجميع بعض المعلومات و البيانات الغير شخصية من المستخدم العضو أو الزائر للموقع كي يقوموا بالتفاعل مع خدمات الموقع المختلفة، بالأحرى هي بيانات تقنية تساعدنا على إيجاد الطريقة الأمثل لتمكين المستخدم من إتصال أفضل بموقعنا و بخدماته المختلفة</li></ul><ul><b>-	كعكات المتصفح الإلكتروني</b><li>موقعنا من الممكن أن يقوم بإستخدام الكعكات البرمجية الإلكترونية لكي تحسن من مدى إستخدام المستخدم لموقعنا و خدماته المختلفة، و تقوم تلك الكعكات بحفظ نفسها على الكمبيوتر الخاص بالمستخدم لكي تساعد الموقع على أداء أفضل لكل مستخدم له كعكه خاصة به بالموقع بالدخول السريع للموقع و تحميل بياناته و الصور بالسرعة الكافية .</li></ul><ul><b>-	كيف نقوم بجمع البيانات و المعلومات ؟</b><li>يقوم موقع ثقه بتجميع تلك البيانات و المعلومات عن طريق الطرق التالية : تشخيص البيانات : من الممكن أن نجمع بيانات و معلومات تفيدنا في كيف هو الموقع مفيد للمستخدم من ناحية سهولة الإستخدام و سرعة الوصول للمعلومات و الخدمات المختلفة . تحسين الموقع : نقوم بجمع بعض البيانات كالتغزيات المرتجعة عن أراء المستخدمين بالموقع و الخدمات المختلفة المقدمه لهم حتي نقوم بالتطوير المستمر و التحسين المستمر للموقع و خدماته. لإعلام المستخدم ببعض الخدمات الجديدة و المواضيع الجديدة و الإعلانات الجديدة : نقم بهذه الحملات لكي نبقى المستخدم على علم بما نقوم بالموقع من تحديثات جديدة أو تمرير بعض المستجدات من مواضيع و غيرها ليبقيه على علم</li></ul><ul><b>-	كيف نقوم بحماية معلوماتك ؟</b><li>البيانات الشخصية و بيانات حساب الإتمان التي يتم تمريرها عن طريق بروتوكول SSL تكون مشفرة بالكامل ولا يتم نشرها أبداً ولا تخزينها بالموقع .</li></ul><ul><b>-	نشر بياناتك</b><li>لا نقوم ببيع معلوماتك المسجله لدينا ولا الإتجار بها ولا نشر بياناتك الشخصية لأخرون، من الممكن أن نقوم بنشر بعض المعلومات العامة أو الإحصائيات التي قمت بالمشاركة بها مع علم أننا لا نشير بعين إلى أحد من من قاموا بالمشاركة في تلك الإحصائيات أو غيرها، من الممكن أن نقوم بإستخدام بياناتك المسجلة لدينا في حال إنشاء موقع أخر نقوم نحن عليه</li></ul><ul><b>-	الإعلانات</b><li>نقوم بنشر الإعلانات على موقعنا و هذه الإعلانات من الممكن أن تستخدم الكعكات المخصصة لكل مستخدم أو لفئة معينة من المستخدمين كي تناسب الإعلانات الموجهه إليهم .</li></ul><ul><b>-	التغييرات على سياسة الخصوصية</b><li>من الممكن أن نقوم بتغيير سياسة الخصوصية ككل أو تغيير جزء منها أو حذف جزء منها و ذلك بدون إعلام أحداً بهذا و كل ما نقوم بتمكينه لإعلام المستخدم هو أخر تحديث لهذه الوثيقة و المدون في أخرها بالتاريخ.</li></ul><ul><b>-	التصديق على سياسة الخصوصية</b><li>بمجرد تسجيلك بالموقع و إستخدامك له فهذا تصديق منك على أنك موافق على كل ما ورد بسياسة الخصوصية بالبعضية و الكلية .</li></ul><ul><b>-	الإتصال بنا</b><li>تستطيع الإتصال بنا دوما عن طريق&nbsp;<a href=\"http://support@theqqa.com.sa\">&nbsp;</a>التليفون 0551166575</li></ul><p>أخر تحرير لهذه الوثيقة – سياسة الخصوصية – كان في يوم الخميس الموافق 12 نوفمبر 2019</p></li>بموجب تلك القوانين الخاصه بالموقع فانت توافق على شروط الاستخدام الخاصه به واذا لم توافق فلا حق لك فى استخدام الموقع او ممارسه اى نشاط على علاقه به&nbsp;ومن حق الموقع أن يضع أي حدود يراها وفقاً لتقديره الخاص على استخدامك للموقع، وهذا يشمل، لكن لا يقتصر على، الحدود المفروضة على المدة التي يمكن أن يبقى فيها المحتوى على الموقع الإلكتروني، وحجم ملف كل مادة من مواد المحتوى، وعدد مواد المحتوى التي يمكن نشرهاويحتفظ الموقع بحقه في حجبك أو منعك من الدخول لقاء أي خرق لشروط الاستخدام هذه في أي وقت ودون الحاجة لإخطارك بذلك.&nbsp;يمكن أن تتعرض هذه الشروط للتحديث من وقت لآخر. وسوف نبلغك بأي تحديثات هامة تطرأ عليها من خلال توجيه تنبيه إليك يبين هذه التغييرات. ويعتبر استمرار استخدامك لخدماتنا موافقة منك على التغييرات الحاصلة. وستبقى هذه الشروط متاحة لجميع المستخدمين على هذه الصفحة لمطالعتها.&nbsp;كما يمكن أن تنسخ بعض الأحكام الواردة في شروط الاستخدام هذه أو تتمم بأحكام أو إخطارات تنشر في مكان آخر من الموقع ونرجو إبلاغنا إذا علمت بوجود أي مخالفات من الآخرين لشروط الاستخدام هذه.</ol>', NULL, 8, 9, 1, NULL, NULL, 0, 0, 1, '2019-01-07 08:45:10', '2019-11-26 20:11:31'),
(7, 'ar', 7, 0, 'standard', 'سياسة الالغاء', 'sy-s-l-lgh', 'سياسة الالغاء', NULL, '<ul><li>في خلل12ساعة من طلب الخدمة يمكن الغاءالطلب واسترجاع المبلغ بعد خصم10&nbsp; %من المبلغ<br></li><li>في حالة شحن السيارة خلل24ساعة يمكن اللغاءواسترجاع المبلغ بعد خصم10<br></li></ul>', NULL, 4, 5, 1, NULL, NULL, 0, 0, 0, '2019-01-07 08:45:10', '2019-11-20 14:06:01'),
(8, 'ar', 8, 0, 'standard', 'سياسة الاسترجاع', 'sy-s-l-strg-aa', 'سياسة الاسترجاع في حالة السيارات', NULL, '<ul><li>يمكن استرجاع السيارة في خلال 3&nbsp; أيام في حالة فحص السيارة قبل الشراء من خلال برنامج ثقة و يتم فحص السيارة علي حساب العميل قبل الاسترجاع للتأكد من حالتها&nbsp; ويتم خصم مبلغ&nbsp;<span style=\"font-size: 16px;\">5</span> % من قيمة السيارة</li></ul><ul><li>في خلال&nbsp;<span style=\"font-size: 16px;\">12</span> ساعة من طلب الخدمة يمكن إلغاء الطلب واسترجاع المبلغ بعد خصم&nbsp;<span style=\"font-size: 16px;\">10</span> % من المبلغ<br></li></ul><ul><li>في حالة شحن السيارة خلال 24 ساعة يمكن إلغاء و استرجاع المبلغ بعد خصم 10% من المبلغ</li></ul>', NULL, 2, 3, 1, NULL, NULL, 0, 0, 1, '2019-01-07 08:45:10', '2019-11-25 16:56:40'),
(11, 'ar', 11, NULL, 'standard', 'من نحن', 'about-us', 'من نحن', NULL, '<p>تم إنشاء مؤسسة ثقة للتسويق الإلكتروني عام 2019 بسجل تجاري رقم (1010519732)، وتعتبر شركة ثقة أول منصة إلكترونية بالمملكة العربية السعودية لتقديم خدمات السيارات مثل خدمات المعاينة والفحص وشحن السيارة وخدمة موجز للاستعلام عن السيارات وخدمة تقدير قيمة السيارة وتسهيل نقل الملكية تحت الأحكام والشروط المتعارف عليها من الإدارة العامة للمرور،&nbsp;<font>بالإضافة إلى إمكانية البحث عن سيارة وإتمام عملية الشراء سواء عن طريق معارض السيارات أو عن طريق أفراد أو عرض السيارات مجانًا للبيع على البرنامج مما يتيح لك فرص كثيرة للبيع</font><font>.</font></p>', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, '2019-11-25 17:04:09', '2019-11-25 17:05:33'),
(12, 'en', 11, NULL, 'standard', 'About Us', 'about-us-en', 'About Us', NULL, '<p>تم إنشاء مؤسسة ثقة للتسويق الإلكتروني عام 2019 بسجل تجاري رقم (1010519732)، وتعتبر شركة ثقة أول منصة إلكترونية بالمملكة العربية السعودية لتقديم خدمات السيارات مثل خدمات المعاينة والفحص وشحن السيارة وخدمة موجز للاستعلام عن السيارات وخدمة تقدير قيمة السيارة وتسهيل نقل الملكية تحت الأحكام والشروط المتعارف عليها من الإدارة العامة للمرور،&nbsp;<font>بالإضافة إلى إمكانية البحث عن سيارة وإتمام عملية الشراء سواء عن طريق معارض السيارات أو عن طريق أفراد أو عرض السيارات مجانًا للبيع على البرنامج مما يتيح لك فرص كثيرة للبيع</font><font>.</font></p>', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, '2019-11-25 17:04:09', '2019-12-05 18:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `theqqapassword_resets`
--

CREATE TABLE `theqqapassword_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapassword_resets`
--

INSERT INTO `theqqapassword_resets` (`email`, `phone`, `token`, `created_at`) VALUES
('mayyoussri@gmail.com', NULL, '$2y$10$inUKF0l8hiwmQTYWeMoBl.q2hDbfX3Lbnaei7iLc5ZCqjg/8MfU2a', '2019-01-07 10:18:58'),
('mayman0901+e@gmail.com', NULL, '$2y$10$nNyaN06i2E4JJXAYMvBWzOIauikJV9yWSOuYr8WzdvoTF4oDfFbiq', '2019-11-11 11:12:15'),
('theqqa@gmail.com', NULL, '$2y$10$H/57PjmEvJ6JepGCYxLz0u3Mq7XC6Q0Dc3D8uneO9x6cmnyTNHjIq', '2019-11-24 13:35:58'),
('mayman0901+m@gmail.com', NULL, '$2y$10$wuiXF6obvlkH7CQzRDJf3OtUvFSbkgwe5XbV1xDD/k/E3eVTfd.mC', '2019-11-24 16:33:28'),
('amrkhamiss1992+22@gmail.com', NULL, '$2y$10$bHD4ldN9dtEMGKD/DgI4O.m2DHC9VJxzgC3.ZbFd3cWruiHZy4GR2', '2019-11-25 21:20:19'),
('mayman0901+3@gmail.com', NULL, '$2y$10$ZvgZQOpF5AHdNPe00ISa3e2lAMa9SoaQSigaYYHrVTihr726IIdr.', '2020-01-05 19:39:46'),
('hossamhamzawii@gmail.com', NULL, '$2y$10$jTXLzlaXscaj/HxjVgCCVe90tprIuYocpuGC/.UhobTS4nK0ST55e', '2020-01-08 12:22:02'),
('dolitik@gmail.com', NULL, '$2y$10$/Z9BF8KjLTJiFda4qIbTg.ImzFs4SUHQQdH9zS4gQNjCkVi1ehgfW', '2020-02-05 15:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `theqqapayments`
--

CREATE TABLE `theqqapayments` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_method_id` int(10) UNSIGNED DEFAULT '0',
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Transaction''s ID at the Provider',
  `user_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_service` text COLLATE utf8_unicode_ci,
  `price` double NOT NULL,
  `currency_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pt_transaction_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pt_invoice_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapayments`
--

INSERT INTO `theqqapayments` (`id`, `post_id`, `package_id`, `payment_method_id`, `transaction_id`, `user_id`, `image`, `date_service`, `price`, `currency_code`, `pt_transaction_id`, `pt_invoice_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 2, 'التحويل البنكي', 2, '3ff355d38fe249c33dc549c1bc6999a3.jpg', NULL, 30, '', NULL, NULL, 1, '2020-02-05 22:07:03', '2020-02-05 22:12:41'),
(2, 0, 13, 2, 'التحويل البنكي', 2, '21e447cc10a64f5f3ac5cc9598ba743f.jpg', '{\"service_type\":\"checking service\",\"first_name\":\"Mahmoud Mostafa\",\"email\":\"dolitik@gmail.com\",\"car_url\":\"\\u0633\\u064a\\u0627\\u0631\\u0647-\\u0644\\u0639\\u0628\\u0629\\/3\",\"checking_time\":\"18:50:00\",\"address\":\"\\u0667\\u0667 \\u0627\\u0646\\u062a\\u0644\\u0644\\u0629\\u0628\\u0641\",\"phone\":\"01004561042\",\"checking_date\":\"2020-02-20\",\"package_id\":\"13\",\"payment_method_id\":\"2\",\"car_place\":\"109953\",\"id_code\":\"291128\",\"card_user_id\":\"2\",\"paytabs\":\"0\"}', 100, '', NULL, NULL, 0, '2020-02-07 05:51:15', '2020-02-07 05:51:15'),
(3, 0, 17, 2, 'التحويل البنكي', 2, '17d10e912991f246ae8489a635262714.jpg', '{\"service_type\":\"maintenance service\",\"first_name\":\"Mahmoud Mostafa\",\"email\":\"dolitik@gmail.com\",\"package_id\":\"17\",\"payment_method_id\":\"2\",\"address\":\"\\u0668\\u0668 \\u0644\\u0629\\u0638\\u0628\\u0624\\u0629\\u0628\\u064a\",\"owner_id\":\"6980745213\",\"plate_number\":\"368885\",\"serial_number\":\"08456884695\",\"car_place\":\"110107\",\"maintenance_id_yes\":\"5\",\"maintenance_id\":\"5\",\"for_mainten\":\"no\",\"car_url\":null,\"id_code\":\"702964\",\"card_user_id\":\"2\",\"paytabs\":\"0\"}', 500, '', NULL, NULL, 0, '2020-02-07 05:52:38', '2020-02-07 05:52:38'),
(4, 0, 9, 2, 'التحويل البنكي', 2, 'cb7e945819aca4c798754736d26f607f.jpg', '{\"service_type\":\"mogaz service\",\"first_name\":\"Mahmoud Mostafa\",\"email\":\"dolitik@gmail.com\",\"package_id\":\"9\",\"payment_method_id\":\"2\",\"owner_id\":null,\"plate_number\":null,\"serial_number\":null,\"for_mogaz\":\"yes\",\"car_url\":\"\\u0633\\u064a\\u0627\\u0631\\u0647-\\u0644\\u0639\\u0628\\u0629\\/3\",\"id_code\":\"399943\",\"card_user_id\":\"2\",\"paytabs\":\"0\"}', 75, '', NULL, NULL, 0, '2020-02-07 05:58:47', '2020-02-07 05:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `theqqapayment_methods`
--

CREATE TABLE `theqqapayment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `has_ccbox` tinyint(1) UNSIGNED DEFAULT '0',
  `is_compatible_api` tinyint(1) DEFAULT '0',
  `countries` text COLLATE utf8_unicode_ci COMMENT 'Countries codes separated by comma.',
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapayment_methods`
--

INSERT INTO `theqqapayment_methods` (`id`, `name`, `display_name`, `description`, `has_ccbox`, `is_compatible_api`, `countries`, `lft`, `rgt`, `depth`, `parent_id`, `active`) VALUES
(1, 'paypal', 'paypal', 'Payment with Paypal', 0, 0, NULL, 4, 5, 1, 0, 1),
(2, 'Bank transfer', 'Bank transfer', 'Bank transfer', 0, 0, NULL, 2, 3, 1, 0, 1),
(3, 'Sadad', 'PayTabs', 'Payment with PayTabs', 0, 0, NULL, 4, 5, 1, 0, 1),
(4, 'Visa', 'Visa', 'Bank transfer', 0, 0, NULL, 2, 3, 1, 0, 0),
(5, 'STC', 'STC', 'STC', 0, 0, NULL, 2, 3, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `theqqapaytabs`
--

CREATE TABLE `theqqapaytabs` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `service_data` text COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqapermissions`
--

CREATE TABLE `theqqapermissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapermissions`
--

INSERT INTO `theqqapermissions` (`id`, `name`, `guard_name`, `updated_at`, `created_at`) VALUES
(1, 'list-permission', 'web', '2019-01-07 04:32:26', '2019-01-07 04:32:26'),
(2, 'create-permission', 'web', '2019-01-07 04:32:26', '2019-01-07 04:32:26'),
(3, 'update-permission', 'web', '2019-01-07 04:32:26', '2019-01-07 04:32:26'),
(4, 'delete-permission', 'web', '2019-01-07 04:32:26', '2019-01-07 04:32:26'),
(5, 'list-role', 'web', '2019-01-07 04:32:27', '2019-01-07 04:32:27'),
(6, 'create-role', 'web', '2019-01-07 04:32:27', '2019-01-07 04:32:27'),
(7, 'update-role', 'web', '2019-01-07 04:32:27', '2019-01-07 04:32:27'),
(8, 'delete-role', 'web', '2019-01-07 04:32:27', '2019-01-07 04:32:27'),
(9, 'access-dashboard', 'web', '2019-01-07 04:32:27', '2019-01-07 04:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `theqqapictures`
--

CREATE TABLE `theqqapictures` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint(1) UNSIGNED DEFAULT '1' COMMENT 'Set at 0 on updating the ad',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapictures`
--

INSERT INTO `theqqapictures` (`id`, `post_id`, `filename`, `position`, `active`, `created_at`, `updated_at`) VALUES
(4, 1, 'files/sa/1/simpletext31580908023.png', 4, 1, '2020-02-05 22:07:03', '2020-02-05 22:07:03'),
(5, 2, 'files/sa/2/simpletext01581003195.png', 1, 1, '2020-02-07 00:33:15', '2020-02-07 00:33:15'),
(6, 2, 'files/sa/2/simpletext11581003195.png', 2, 1, '2020-02-07 00:33:16', '2020-02-07 00:33:16'),
(7, 2, 'files/sa/2/simpletext21581003196.png', 3, 1, '2020-02-07 00:33:16', '2020-02-07 00:33:16'),
(8, 2, 'files/sa/2/simpletext31581003196.png', 4, 1, '2020-02-07 00:33:16', '2020-02-07 00:33:16'),
(9, 3, 'files/sa/3/simpletext01581003579.png', 1, 1, '2020-02-07 00:39:39', '2020-02-07 00:39:39'),
(10, 3, 'files/sa/3/simpletext11581003579.png', 2, 1, '2020-02-07 00:39:39', '2020-02-07 00:39:39'),
(11, 3, 'files/sa/3/simpletext21581003579.png', 3, 1, '2020-02-07 00:39:39', '2020-02-07 00:39:39'),
(12, 3, 'files/sa/3/simpletext31581003579.png', 4, 1, '2020-02-07 00:39:39', '2020-02-07 00:39:39'),
(13, 4, 'files/sa/4/simpletext01581003969.png', 1, 1, '2020-02-07 00:46:10', '2020-02-07 00:46:10'),
(14, 4, 'files/sa/4/simpletext11581003970.png', 2, 1, '2020-02-07 00:46:10', '2020-02-07 00:46:10'),
(15, 4, 'files/sa/4/simpletext21581003970.png', 3, 1, '2020-02-07 00:46:10', '2020-02-07 00:46:10'),
(16, 4, 'files/sa/4/simpletext31581003970.png', 4, 1, '2020-02-07 00:46:10', '2020-02-07 00:46:10'),
(17, 5, 'files/sa/5/simpletext01581005346.png', 1, 1, '2020-02-07 01:09:06', '2020-02-07 01:09:06'),
(18, 5, 'files/sa/5/simpletext11581005346.png', 2, 1, '2020-02-07 01:09:06', '2020-02-07 01:09:06'),
(19, 5, 'files/sa/5/simpletext21581005346.png', 3, 1, '2020-02-07 01:09:06', '2020-02-07 01:09:06'),
(20, 5, 'files/sa/5/simpletext31581005346.png', 4, 1, '2020-02-07 01:09:06', '2020-02-07 01:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaposts`
--

CREATE TABLE `theqqaposts` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `post_type_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(17,2) UNSIGNED DEFAULT NULL,
  `negotiable` tinyint(1) DEFAULT '0',
  `contact_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_hidden` tinyint(1) DEFAULT '0',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lon` float DEFAULT NULL COMMENT 'longitude in decimal degrees (wgs84)',
  `lat` float DEFAULT NULL COMMENT 'latitude in decimal degrees (wgs84)',
  `ip_addr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visits` int(10) UNSIGNED DEFAULT '0',
  `email_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tmp_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified_email` tinyint(1) DEFAULT '0',
  `verified_phone` tinyint(1) UNSIGNED DEFAULT '1',
  `reviewed` tinyint(1) DEFAULT '0',
  `featured` tinyint(1) UNSIGNED DEFAULT '0',
  `archived` tinyint(1) DEFAULT '0',
  `archived_at` timestamp NULL DEFAULT NULL,
  `deletion_mail_sent_at` timestamp NULL DEFAULT NULL,
  `fb_profile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `url_picture` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaposts`
--

INSERT INTO `theqqaposts` (`id`, `country_code`, `user_id`, `category_id`, `post_type_id`, `title`, `description`, `tags`, `price`, `negotiable`, `contact_name`, `email`, `phone`, `phone_hidden`, `address`, `city_id`, `lon`, `lat`, `ip_addr`, `visits`, `email_token`, `phone_token`, `tmp_token`, `verified_email`, `verified_phone`, `reviewed`, `featured`, `archived`, `archived_at`, `deletion_mail_sent_at`, `fb_profile`, `partner`, `created_at`, `updated_at`, `deleted_at`, `url_picture`) VALUES
(0, 'SA', 1, 459, 4, 'خدمات ثقة', '<p>تزيوتا 2<br></p>', 'خدمات ثقة', '2000.00', NULL, 'Theqqa', 'theqqa@gmail.com', '+966551166575', NULL, NULL, 104515, 39.8256, 21.4266, '197.55.145.76', 22, NULL, NULL, '3be2e8ac6327f4614f8db37c92b78f7f', 1, 1, 1, 1, 0, NULL, NULL, NULL, NULL, '2019-12-02 20:17:51', '2020-01-08 20:11:12', NULL, ''),
(1, 'SA', 2, 318, 3, '٧٧ امىفوربةةل', '<p>ةبننريلابلنه</p>', 'فورد', '999999999.00', 0, 'Mahmoud Mostafa', 'dolitik@gmail.com', '01004561042', NULL, NULL, 108410, 46.7219, 24.6877, '156.194.236.80', 11, NULL, NULL, '85c4bbb17c9e278c45c9301bc5120fa0', 1, 1, 1, 1, 0, NULL, NULL, NULL, NULL, '2020-02-05 22:07:03', '2020-02-09 12:38:05', NULL, ''),
(2, 'SA', 3, 316, 3, 'سيارة صغيرة', '<p>سيارة نضيفة استعمال طبيب</p>', 'lambo', NULL, 1, 'Mazen Person', 'mazen.elsaaid@gmail.com', '+966598867604', NULL, NULL, 108410, 46.7219, 24.6877, '176.225.235.139', 15, NULL, NULL, '9dd28acf9ca1ed783b73b2b20ccfd8be', 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, '2020-02-07 00:33:15', '2020-02-09 16:44:58', NULL, ''),
(3, 'SA', 4, 314, 6, 'سياره لعبة', '<p>سياره صغيره جدا</p>', NULL, '15000.00', 1, 'معرض مازن للسيارات', 'mazen.elsaaid+1@gmail.com', '+966598867605', NULL, NULL, 108410, 46.7219, 24.6877, '176.225.235.139', 16, NULL, NULL, '46bed6d17b7a0f1d7e6fe829267989aa', 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, '2020-02-07 00:39:39', '2020-02-10 20:48:40', NULL, ''),
(4, 'SA', 5, 453, 4, 'الرياض النسيم', '<p>لفحص جميع السيارات</p>', 'فحص', '0.00', 1, 'مركز مازن لفحص السيارات', 'mazen.elsaaid+2@gmail.com', '+966598867606', NULL, NULL, 108410, 46.7219, 24.6877, '176.225.235.139', 9, NULL, NULL, '085e1719ab780ac1bc439b0decfc595e', 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, '2020-02-07 00:46:09', '2020-02-09 21:47:20', NULL, ''),
(5, 'SA', 6, 438, 4, 'شحن اي سيارة لاي مكان', '<p>شحن شحن</p>', NULL, '0.00', 1, 'مازن لشحن السيارات', 'mazen.elsaaid+4@gmail.com', '+966598867607', NULL, NULL, 108410, 46.7219, 24.6877, '176.225.235.139', 9, NULL, NULL, '34bf160ca1f17a9d916b3e1affe6f099', 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, '2020-02-07 01:09:06', '2020-02-10 02:38:09', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `theqqapost_types`
--

CREATE TABLE `theqqapost_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_type_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapost_types`
--

INSERT INTO `theqqapost_types` (`id`, `translation_lang`, `translation_of`, `name`, `user_type_id`, `lft`, `rgt`, `depth`, `active`) VALUES
(1, 'en', 3, 'Individuals', '2', NULL, NULL, NULL, 1),
(2, 'en', 4, 'Companies', '1,3,4,5', NULL, NULL, NULL, 1),
(3, 'ar', 3, 'أفراد', '2', NULL, NULL, NULL, 1),
(4, 'ar', 4, 'شركات', '1,3,4,5', NULL, NULL, NULL, 1),
(5, 'en', 6, 'Exhibitions', '6', NULL, NULL, NULL, 1),
(6, 'ar', 6, 'معارض سيارات', '6', NULL, NULL, NULL, 1),
(7, 'en', 8, 'Other Services', '7,8,9,10', NULL, NULL, NULL, 1),
(8, 'ar', 8, 'خدمات أخرى', '7,8,9,10', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqapost_values`
--

CREATE TABLE `theqqapost_values` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `field_id` int(10) UNSIGNED DEFAULT NULL,
  `option_id` int(10) UNSIGNED DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqapost_values`
--

INSERT INTO `theqqapost_values` (`id`, `post_id`, `field_id`, `option_id`, `value`) VALUES
(1, 1, 50, NULL, '457'),
(2, 1, 29, NULL, '169'),
(3, 1, 33, NULL, '175'),
(4, 1, 28, NULL, '٧٠٠٠٠٠'),
(5, 2, 58, NULL, '456'),
(6, 2, 50, NULL, '457'),
(7, 2, 29, NULL, '169'),
(8, 2, 33, NULL, '174'),
(9, 2, 28, NULL, '10000'),
(10, 3, 56, NULL, '417'),
(11, 3, 50, NULL, '457'),
(12, 3, 29, NULL, '169'),
(13, 3, 33, NULL, '174'),
(14, 3, 28, NULL, '100000'),
(15, 4, 33, NULL, '174'),
(16, 4, 50, NULL, '435');

-- --------------------------------------------------------

--
-- Table structure for table `theqqareport_types`
--

CREATE TABLE `theqqareport_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `translation_lang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation_of` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqareport_types`
--

INSERT INTO `theqqareport_types` (`id`, `translation_lang`, `translation_of`, `name`) VALUES
(1, 'en', 6, 'Fraud'),
(2, 'en', 7, 'Duplicate'),
(3, 'en', 8, 'Spam'),
(4, 'en', 9, 'Wrong category'),
(5, 'en', 10, 'Other'),
(6, 'ar', 6, 'Fraud'),
(7, 'ar', 7, 'Duplicate'),
(8, 'ar', 8, 'Spam'),
(9, 'ar', 9, 'Wrong category'),
(10, 'ar', 10, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `theqqaroles`
--

CREATE TABLE `theqqaroles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqaroles`
--

INSERT INTO `theqqaroles` (`id`, `name`, `guard_name`, `updated_at`, `created_at`) VALUES
(1, 'super-admin', 'web', '2019-01-07 04:32:26', '2019-01-07 04:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `theqqarole_has_permissions`
--

CREATE TABLE `theqqarole_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqarole_has_permissions`
--

INSERT INTO `theqqarole_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqasaved_posts`
--

CREATE TABLE `theqqasaved_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqasaved_posts`
--

INSERT INTO `theqqasaved_posts` (`id`, `user_id`, `post_id`, `created_at`, `updated_at`) VALUES
(180, 7, 2, '2020-02-07 02:52:11', '2020-02-07 02:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `theqqasaved_search`
--

CREATE TABLE `theqqasaved_search` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `keyword` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'To show',
  `query` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count` int(10) UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqasessions`
--

CREATE TABLE `theqqasessions` (
  `id` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payload` text COLLATE utf8_unicode_ci,
  `last_activity` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(250) COLLATE utf8_unicode_ci DEFAULT '',
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theqqasettings`
--

CREATE TABLE `theqqasettings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field` text COLLATE utf8_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqasettings`
--

INSERT INTO `theqqasettings` (`id`, `key`, `name`, `value`, `description`, `field`, `parent_id`, `lft`, `rgt`, `depth`, `active`, `created_at`, `updated_at`) VALUES
(1, 'app', 'Application', '{\"app_name\":\"Theqqa\",\"slogan\":\"Theqqa\",\"logo\":\"app\\/logo\\/logo-5c7817d35ec9b.png\",\"favicon\":\"app\\/ico\\/ico-5c7d07a12b5c4.png\",\"email\":\"theqqa@gmail.com\",\"phone_number\":\"0551166575\",\"default_date_format\":\"%F\",\"default_datetime_format\":\"%F %T\",\"default_timezone\":\"Asia\\/Riyadh\",\"date_force_utf8\":\"1\",\"show_countries_charts\":\"1\",\"latest_entries_limit\":\"5\"}', 'Application Setup', NULL, 0, 2, 3, 1, 1, NULL, NULL),
(2, 'style', 'Style', '{\"app_skin\":\"skin-default\",\"body_background_color\":null,\"body_text_color\":null,\"body_background_image\":null,\"body_background_image_fixed\":\"0\",\"page_width\":null,\"title_color\":null,\"progress_background_color\":null,\"link_color\":null,\"link_color_hover\":null,\"header_sticky\":\"0\",\"header_height\":null,\"header_background_color\":null,\"header_bottom_border_width\":\"1px\",\"header_bottom_border_color\":\"#e8e8e8\",\"header_link_color\":\"#dbbd77\",\"header_link_color_hover\":null,\"footer_background_color\":null,\"footer_text_color\":null,\"footer_title_color\":null,\"footer_link_color\":null,\"footer_link_color_hover\":null,\"payment_icon_top_border_width\":null,\"payment_icon_top_border_color\":null,\"payment_icon_bottom_border_width\":null,\"payment_icon_bottom_border_color\":null,\"btn_post_bg_top_color\":null,\"btn_post_bg_bottom_color\":null,\"btn_post_border_color\":null,\"btn_post_text_color\":null,\"btn_post_bg_top_color_hover\":null,\"btn_post_bg_bottom_color_hover\":null,\"btn_post_border_color_hover\":null,\"btn_post_text_color_hover\":null,\"custom_css\":null,\"admin_skin\":\"skin-blue\"}', 'Style Customization', NULL, 0, 4, 5, 1, 1, NULL, NULL),
(3, 'listing', 'Listing & Search', '{\"display_mode\":\".grid-view\",\"grid_view_cols\":\"4\",\"items_per_page\":\"12\",\"left_sidebar\":\"1\",\"cities_extended_searches\":\"1\",\"search_distance_max\":\"500\",\"search_distance_default\":\"50\",\"search_distance_interval\":\"100\"}', 'Listing & Search Options', NULL, 0, 6, 7, 1, 1, NULL, NULL),
(4, 'single', 'Ads Single Page', '{\"pictures_limit\":\"5\",\"tags_limit\":\"15\",\"guests_can_post_ads\":\"1\",\"posts_review_activation\":\"0\",\"guests_can_contact_seller\":\"1\",\"simditor_wysiwyg\":\"1\",\"ckeditor_wysiwyg\":\"0\",\"show_post_on_googlemap\":\"1\",\"activation_facebook_comments\":\"1\"}', 'Ads Single Page Options', NULL, 0, 8, 9, 1, 1, NULL, NULL),
(5, 'mail', 'Mail', '{\"driver\":\"mail\",\"host\":\"mail.theqqa.com\",\"port\":\"25\",\"username\":\"services@theqqa.com\",\"password\":\"C9=HMotY]c&D\",\"encryption\":\"tls\",\"mailgun_domain\":null,\"mailgun_secret\":null,\"mandrill_secret\":null,\"ses_key\":null,\"ses_secret\":null,\"ses_region\":null,\"sparkpost_secret\":null,\"email_sender\":\"services@theqqa.com\",\"email_verification\":\"1\",\"confirmation\":\"1\",\"admin_notification\":\"1\",\"payment_notification\":\"1\"}', 'Mail Sending Configuration', NULL, 0, 10, 11, 1, 1, NULL, NULL),
(6, 'sms', 'SMS', '{\"driver\":\"nexmo\",\"nexmo_key\":null,\"nexmo_secret\":null,\"nexmo_from\":null,\"twilio_account_sid\":null,\"twilio_auth_token\":null,\"twilio_from\":null,\"phone_verification\":\"0\",\"message_activation\":\"0\"}', 'SMS Sending Configuration', NULL, 0, 12, 13, 1, 1, NULL, NULL),
(7, 'seo', 'SEO', NULL, 'SEO Tools', NULL, 0, 14, 15, 1, 1, NULL, NULL),
(8, 'upload', 'Upload', '{\"image_types\":\"jpg,jpeg,gif,png\",\"file_types\":\"pdf,doc,docx,word,rtf,rtx,ppt,pptx,odt,odp,wps,jpeg,jpg,bmp,png\",\"max_file_size\":\"2500\"}', 'Upload Settings', NULL, 0, 16, 17, 1, 1, NULL, NULL),
(9, 'geo_location', 'Geo Location', '{\"geolocation_activation\":\"0\",\"default_country_code\":\"US\",\"country_flag_activation\":\"1\",\"local_currency_packages_activation\":\"0\"}', 'Geo Location Configuration', NULL, 0, 18, 19, 1, 1, NULL, NULL),
(10, 'security', 'Security', '{\"login_open_in_modal\":\"1\",\"login_max_attempts\":\"5\",\"login_decay_minutes\":\"15\",\"recaptcha_activation\":\"0\",\"recaptcha_public_key\":\"6Lc0PYsUAAAAAOyTx3fPB3iojDQbLiNR_QceLGQt\",\"recaptcha_private_key\":\"6Lc0PYsUAAAAAOrC5tE1pbxsww7i-8yvJkg6d78P\"}', 'Security Options', NULL, 0, 20, 21, 1, 1, NULL, NULL),
(11, 'social_auth', 'Social Login', NULL, 'Social Network Login', NULL, 0, 22, 23, 1, 1, NULL, NULL),
(12, 'social_link', 'Social Network', '{\"facebook_page_url\":\"#\",\"twitter_url\":\"#\",\"google_plus_url\":\"#\",\"linkedin_url\":\"#\",\"pinterest_url\":\"#\"}', 'Social Network Profiles', NULL, 0, 24, 25, 1, 1, NULL, NULL),
(13, 'other', 'Others', '{\"cookie_consent_enabled\":\"0\",\"show_tips_messages\":\"1\",\"googlemaps_key\":\"AIzaSyD96tluDsBsKC0xHXHat117g5fRqKHat4w\",\"timer_new_messages_checking\":\"60000\",\"simditor_wysiwyg\":\"1\",\"ckeditor_wysiwyg\":\"0\",\"ios_app_url\":\"https:\\/\\/apps.apple.com\\/us\\/app\\/theqqa\\/id1487633511?ls=1\",\"android_app_url\":\"https:\\/\\/play.google.com\\/store\\/apps\\/details?id=com.procrew.theqqa\",\"decimals_superscript\":\"0\",\"cookie_expiration\":\"86400\",\"cache_expiration\":\"1440\",\"minify_html_activation\":\"0\",\"js_code\":null}', 'Other Options', NULL, 0, 26, 27, 1, 1, NULL, NULL),
(14, 'cron', 'Cron', '{\"unactivated_posts_expiration\":\"7\",\"activated_posts_expiration\":\"7\",\"archived_posts_expiration\":\"7\"}', 'Cron Job', NULL, 0, 28, 29, 1, 1, NULL, NULL),
(15, 'footer', 'Footer', '{\"hide_links\":\"0\",\"hide_payment_plugins_logos\":\"1\",\"hide_powered_by\":\"0\",\"powered_by_info\":\"ProCrew\",\"tracking_code\":null}', 'Pages Footer', NULL, 0, 30, 31, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqasubadmin1`
--

CREATE TABLE `theqqasubadmin1` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `asciiname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqasubadmin1`
--

INSERT INTO `theqqasubadmin1` (`id`, `code`, `country_code`, `name`, `asciiname`, `active`) VALUES
(2899, 'SA.19', 'SA', 'تبوك', 'تبوك', 1),
(2900, 'SA.16', 'SA', 'نجران', 'نجران', 1),
(2901, 'SA.14', 'SA', 'مكة', 'مكة', 1),
(2902, 'SA.17', 'SA', 'جازان', 'جازان', 1),
(2903, 'SA.13', 'SA', 'حائل', 'حائل', 1),
(2904, 'SA.11', 'SA', 'عسير', 'عسير', 1),
(2905, 'SA.06', 'SA', 'المنطقة الشرقية', 'المنطقة الشرقية', 1),
(2906, 'SA.10', 'SA', 'الرياض', 'الرياض', 1),
(2907, 'SA.08', 'SA', 'القصيم', 'القصيم', 1),
(2908, 'SA.05', 'SA', 'المدينة المنورة', 'المدينة المنورة', 1),
(2909, 'SA.20', 'SA', 'الجوف', 'الجوف', 1),
(2910, 'SA.15', 'SA', 'الحدود الشمالية', 'الحدود الشمالية', 1),
(2911, 'SA.02', 'SA', 'الباحة', 'الباحة', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqasubadmin2`
--

CREATE TABLE `theqqasubadmin2` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subadmin1_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `asciiname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqasubadmin2`
--

INSERT INTO `theqqasubadmin2` (`id`, `code`, `country_code`, `subadmin1_code`, `name`, `asciiname`, `active`) VALUES
(35508, 'SA.11.10972259', 'SA', 'SA.11', 'Abhā', 'Abha', 1),
(35509, 'SA.11.10972260', 'SA', 'SA.11', 'Aḩad Rufaydah', 'Ahad Rufaydah', 1),
(35510, 'SA.11.10972261', 'SA', 'SA.11', 'Al Majāridah', 'Al Majaridah', 1),
(35511, 'SA.11.10972262', 'SA', 'SA.11', 'An Namāş', 'An Namas', 1),
(35512, 'SA.11.10972263', 'SA', 'SA.11', 'Bālqarn', 'Balqarn', 1),
(35513, 'SA.11.10972264', 'SA', 'SA.11', 'Bīshah', 'Bishah', 1),
(35514, 'SA.11.10972265', 'SA', 'SA.11', 'Khamīs Mushayţ', 'Khamis Mushayt', 1),
(35515, 'SA.11.10972266', 'SA', 'SA.11', 'Muḩā’il', 'Muha\'il', 1),
(35516, 'SA.11.10972267', 'SA', 'SA.11', 'Rijāl Alma‘', 'Rijal Alma`', 1),
(35517, 'SA.11.10972268', 'SA', 'SA.11', 'Sarāt ‘Abīdah', 'Sarat `Abidah', 1),
(35518, 'SA.11.10972269', 'SA', 'SA.11', 'Tathlīth', 'Tathlith', 1),
(35519, 'SA.11.10972270', 'SA', 'SA.11', 'Z̧ahrān al Janūb', 'Zahran al Janub', 1),
(35520, 'SA.02.10972271', 'SA', 'SA.02', 'Al ‘Aqīq', 'Al `Aqiq', 1),
(35521, 'SA.02.10972272', 'SA', 'SA.02', 'Al Bāḩah', 'Al Bahah', 1),
(35522, 'SA.02.10972273', 'SA', 'SA.02', 'Al Mandaq', 'Al Mandaq', 1),
(35523, 'SA.02.10972274', 'SA', 'SA.02', 'Al Mikhwāh', 'Al Mikhwah', 1),
(35524, 'SA.02.10972283', 'SA', 'SA.02', 'Al Qurá', 'Al Qura', 1),
(35525, 'SA.02.10972284', 'SA', 'SA.02', 'Baljurashī', 'Baljurashi', 1),
(35526, 'SA.02.10972285', 'SA', 'SA.02', 'Qilwah', 'Qilwah', 1),
(35527, 'SA.15.10972286', 'SA', 'SA.15', '‘Ar‘ar', '`Ar`ar', 1),
(35528, 'SA.15.10972287', 'SA', 'SA.15', 'Rafha', 'Rafha', 1),
(35529, 'SA.15.10972288', 'SA', 'SA.15', 'Ţurayf', 'Turayf', 1),
(35530, 'SA.20.10972289', 'SA', 'SA.20', 'Al Qurayyāt', 'Al Qurayyat', 1),
(35531, 'SA.20.10972290', 'SA', 'SA.20', 'Dawmat al Jandal', 'Dawmat al Jandal', 1),
(35532, 'SA.20.10972291', 'SA', 'SA.20', 'Sakākā', 'Sakaka', 1),
(35533, 'SA.05.10972292', 'SA', 'SA.05', 'Al Ḩinākīyah', 'Al Hinakiyah', 1),
(35534, 'SA.05.10972293', 'SA', 'SA.05', 'Al Madīnah al Munawwarah', 'Al Madinah al Munawwarah', 1),
(35535, 'SA.05.10972294', 'SA', 'SA.05', 'Al Mahd', 'Al Mahd', 1),
(35536, 'SA.05.10972295', 'SA', 'SA.05', 'Al ‘Ulā', 'Al `Ula', 1),
(35537, 'SA.05.10972296', 'SA', 'SA.05', 'Badr', 'Badr', 1),
(35538, 'SA.05.10972297', 'SA', 'SA.05', 'Khaybar', 'Khaybar', 1),
(35539, 'SA.05.10972298', 'SA', 'SA.05', 'Yanbu‘ al Baḩr', 'Yanbu` al Bahr', 1),
(35540, 'SA.08.10972299', 'SA', 'SA.08', 'Al Asyāḩ', 'Al Asyah', 1),
(35541, 'SA.08.10972300', 'SA', 'SA.08', 'Al Badā’i‘', 'Al Bada\'i`', 1),
(35542, 'SA.08.10972301', 'SA', 'SA.08', 'Al Bukayrīyah', 'Al Bukayriyah', 1),
(35543, 'SA.08.10972302', 'SA', 'SA.08', 'Al Midhnab', 'Al Midhnab', 1),
(35544, 'SA.08.10972303', 'SA', 'SA.08', 'An Nabhānīyah', 'An Nabhaniyah', 1),
(35545, 'SA.08.10972304', 'SA', 'SA.08', 'Ar Rass', 'Ar Rass', 1),
(35546, 'SA.08.10972305', 'SA', 'SA.08', 'Ash Shimāsīyah', 'Ash Shimasiyah', 1),
(35547, 'SA.08.10972306', 'SA', 'SA.08', 'Buraydah', 'Buraydah', 1),
(35548, 'SA.08.10972307', 'SA', 'SA.08', 'Riyāḑ al Khabrā’', 'Riyad al Khabra\'', 1),
(35549, 'SA.08.10972308', 'SA', 'SA.08', 'Governorate of Unaizah', 'Governorate of Unaizah', 1),
(35550, 'SA.08.10972309', 'SA', 'SA.08', '‘Uyūn al Jiwā’', '`Uyun al Jiwa\'', 1),
(35551, 'SA.10.10972310', 'SA', 'SA.10', 'Ad Dawādimī', 'Ad Dawadimi', 1),
(35552, 'SA.10.10972311', 'SA', 'SA.10', 'Ad Dir‘īyah', 'Ad Dir`iyah', 1),
(35553, 'SA.10.10972312', 'SA', 'SA.10', '‘Afīf', '`Afif', 1),
(35554, 'SA.10.10972313', 'SA', 'SA.10', 'Al Aflāj', 'Al Aflaj', 1),
(35555, 'SA.10.10972314', 'SA', 'SA.10', 'Al Ghāţ', 'Al Ghat', 1),
(35556, 'SA.10.10972315', 'SA', 'SA.10', 'Al Ḩarīq', 'Al Hariq', 1),
(35557, 'SA.10.10972316', 'SA', 'SA.10', 'Al Kharj', 'Al Kharj', 1),
(35558, 'SA.10.10972317', 'SA', 'SA.10', 'Al Majma‘ah', 'Al Majma`ah', 1),
(35559, 'SA.10.10972318', 'SA', 'SA.10', 'Al Muzāḩimīyah', 'Al Muzahimiyah', 1),
(35560, 'SA.10.10972319', 'SA', 'SA.10', 'Al Quway‘īyah', 'Al Quway`iyah', 1),
(35561, 'SA.10.10972320', 'SA', 'SA.10', 'Ar Riyāḑ', 'Ar Riyad', 1),
(35562, 'SA.10.10972321', 'SA', 'SA.10', 'As Sulayyil', 'As Sulayyil', 1),
(35563, 'SA.10.10972322', 'SA', 'SA.10', 'Az Zulfī', 'Az Zulfi', 1),
(35564, 'SA.10.10972323', 'SA', 'SA.10', 'Ḑurumā', 'Duruma', 1),
(35565, 'SA.10.10972324', 'SA', 'SA.10', 'Ḩawţat Banī Tamīm', 'Hawtat Bani Tamim', 1),
(35566, 'SA.10.10972325', 'SA', 'SA.10', 'Ḩuraymilā’', 'Huraymila\'', 1),
(35567, 'SA.10.10972326', 'SA', 'SA.10', 'Rumāḩ', 'Rumah', 1),
(35568, 'SA.10.10972327', 'SA', 'SA.10', 'Shaqrā’', 'Shaqra\'', 1),
(35569, 'SA.10.10972328', 'SA', 'SA.10', 'Thādiq', 'Thadiq', 1),
(35570, 'SA.10.10972329', 'SA', 'SA.10', 'Wādī ad Dawāsir', 'Wadi ad Dawasir', 1),
(35571, 'SA.06.10972330', 'SA', 'SA.06', 'Ad Dammām', 'Ad Dammam', 1),
(35572, 'SA.06.10972331', 'SA', 'SA.06', 'Al Aḩsā’', 'Al Ahsa\'', 1),
(35573, 'SA.06.10972332', 'SA', 'SA.06', 'Al Jubayl', 'Al Jubayl', 1),
(35574, 'SA.06.10972333', 'SA', 'SA.06', 'Al Khafjī', 'Al Khafji', 1),
(35575, 'SA.06.10972334', 'SA', 'SA.06', 'Al Khubar', 'Al Khubar', 1),
(35576, 'SA.06.10972335', 'SA', 'SA.06', 'An Nu‘ayrīyah', 'An Nu`ayriyah', 1),
(35577, 'SA.06.10972336', 'SA', 'SA.06', 'Al Qaţīf', 'Al Qatif', 1),
(35578, 'SA.06.10972337', 'SA', 'SA.06', 'Buqayq', 'Buqayq', 1),
(35579, 'SA.06.10972338', 'SA', 'SA.06', 'Ḩafr al Bāţin', 'Hafr al Batin', 1),
(35580, 'SA.06.10972339', 'SA', 'SA.06', 'Qaryah al ‘Ulyā', 'Qaryah al `Ulya', 1),
(35581, 'SA.06.10972340', 'SA', 'SA.06', 'Ra’s Tannūrah', 'Ra\'s Tannurah', 1),
(35582, 'SA.13.10972341', 'SA', 'SA.13', 'Al Ghazālah', 'Al Ghazalah', 1),
(35583, 'SA.13.10972342', 'SA', 'SA.13', 'Ash Shinān', 'Ash Shinan', 1),
(35584, 'SA.13.10972343', 'SA', 'SA.13', 'Baq‘ā’', 'Baq`a\'', 1),
(35585, 'SA.13.10972344', 'SA', 'SA.13', 'Ḩā’il', 'Ha\'il', 1),
(35586, 'SA.17.10972345', 'SA', 'SA.17', 'Abū ‘Arīsh', 'Abu `Arish', 1),
(35587, 'SA.17.10972346', 'SA', 'SA.17', 'Ad Dā’ir', 'Ad Da\'ir', 1),
(35588, 'SA.17.10972347', 'SA', 'SA.17', 'Ad Darb', 'Ad Darb', 1),
(35589, 'SA.17.10972348', 'SA', 'SA.17', 'Aḩad al Musāriḩah', 'Ahad al Musarihah', 1),
(35590, 'SA.17.10972349', 'SA', 'SA.17', 'Al ‘Āriḑah', 'Al `Aridah', 1),
(35591, 'SA.17.10972350', 'SA', 'SA.17', 'Al Ḩarth', 'Al Harth', 1),
(35592, 'SA.17.10972351', 'SA', 'SA.17', 'Al ‘Īdābī', 'Al `Idabi', 1),
(35593, 'SA.17.10972352', 'SA', 'SA.17', 'Ar Rayth', 'Ar Rayth', 1),
(35594, 'SA.17.10972353', 'SA', 'SA.17', 'Baysh', 'Baysh', 1),
(35595, 'SA.17.10972354', 'SA', 'SA.17', 'Ḑamad', 'Damad', 1),
(35596, 'SA.17.10972355', 'SA', 'SA.17', 'Farasān', 'Farasan', 1),
(35597, 'SA.17.10972356', 'SA', 'SA.17', 'Jāzān', 'Jazan', 1),
(35598, 'SA.17.10972357', 'SA', 'SA.17', 'Şabyā’', 'Sabya\'', 1),
(35599, 'SA.17.10972358', 'SA', 'SA.17', 'Şāmiţah', 'Samitah', 1),
(35600, 'SA.14.10972359', 'SA', 'SA.14', 'الجموم', 'الجموم', 1),
(35601, 'SA.14.10972360', 'SA', 'SA.14', 'الكامل', 'الكامل', 1),
(35602, 'SA.14.10972361', 'SA', 'SA.14', 'الخرمة', 'الخرمة', 1),
(35603, 'SA.14.10972362', 'SA', 'SA.14', 'الليث', 'الليث', 1),
(35604, 'SA.14.10972363', 'SA', 'SA.14', 'القنفذة', 'القنفذة', 1),
(35605, 'SA.14.10972364', 'SA', 'SA.14', 'الطائف', 'الطائف', 1),
(35606, 'SA.14.10972365', 'SA', 'SA.14', 'جدة', 'جدة', 1),
(35607, 'SA.14.10972366', 'SA', 'SA.14', 'خليص', 'خليص', 1),
(35608, 'SA.14.10972367', 'SA', 'SA.14', 'مكة المكرمة', 'مكة المكرمة', 1),
(35609, 'SA.14.10972368', 'SA', 'SA.14', 'رابغ', 'رابغ', 1),
(35610, 'SA.14.10972369', 'SA', 'SA.14', 'رنية', 'رنية', 1),
(35611, 'SA.14.10972370', 'SA', 'SA.14', 'تربة', 'تربة', 1),
(35612, 'SA.16.10972371', 'SA', 'SA.16', 'الخرخير', 'الخرخير', 1),
(35613, 'SA.16.10972372', 'SA', 'SA.16', 'بدر الجنوب', 'بدر الجنوب', 1),
(35614, 'SA.16.10972373', 'SA', 'SA.16', 'حبونا', 'حبونا', 1),
(35615, 'SA.16.10972374', 'SA', 'SA.16', 'خباش', 'خباش', 1),
(35616, 'SA.16.10972375', 'SA', 'SA.16', 'نجران', 'نجران', 1),
(35617, 'SA.16.10972376', 'SA', 'SA.16', 'شرورة', 'شرورة', 1),
(35618, 'SA.16.10972377', 'SA', 'SA.16', 'ثار', 'ثار', 1),
(35619, 'SA.16.10972378', 'SA', 'SA.16', 'يدمة', 'يدمة', 1),
(35620, 'SA.19.10972379', 'SA', 'SA.19', 'الوجه', 'الوجه', 1),
(35621, 'SA.19.10972380', 'SA', 'SA.19', 'ضبا', 'ضبا', 1),
(35622, 'SA.19.10972381', 'SA', 'SA.19', 'حقل', 'حقل', 1),
(35623, 'SA.19.10972382', 'SA', 'SA.19', 'تبوك', 'تبوك', 1),
(35624, 'SA.19.10972383', 'SA', 'SA.19', 'تيماء', 'تيماء', 1),
(35625, 'SA.19.10972384', 'SA', 'SA.19', 'أملج', 'أملج', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theqqatime_zones`
--

CREATE TABLE `theqqatime_zones` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `time_zone_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT '',
  `gmt` double DEFAULT NULL,
  `dst` double DEFAULT NULL,
  `raw` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqatime_zones`
--

INSERT INTO `theqqatime_zones` (`id`, `country_code`, `time_zone_id`, `gmt`, `dst`, `raw`) VALUES
(1, 'CI', 'Africa/Abidjan', 0, 0, 0),
(2, 'GH', 'Africa/Accra', 0, 0, 0),
(3, 'ET', 'Africa/Addis_Ababa', 3, 3, 3),
(4, 'DZ', 'Africa/Algiers', 1, 1, 1),
(5, 'ER', 'Africa/Asmara', 3, 3, 3),
(6, 'ML', 'Africa/Bamako', 0, 0, 0),
(7, 'CF', 'Africa/Bangui', 1, 1, 1),
(8, 'GM', 'Africa/Banjul', 0, 0, 0),
(9, 'GW', 'Africa/Bissau', 0, 0, 0),
(10, 'MW', 'Africa/Blantyre', 2, 2, 2),
(11, 'CG', 'Africa/Brazzaville', 1, 1, 1),
(12, 'BI', 'Africa/Bujumbura', 2, 2, 2),
(13, 'EG', 'Africa/Cairo', 2, 2, 2),
(14, 'MA', 'Africa/Casablanca', 0, 1, 0),
(15, 'ES', 'Africa/Ceuta', 1, 2, 1),
(16, 'GN', 'Africa/Conakry', 0, 0, 0),
(17, 'SN', 'Africa/Dakar', 0, 0, 0),
(18, 'TZ', 'Africa/Dar_es_Salaam', 3, 3, 3),
(19, 'DJ', 'Africa/Djibouti', 3, 3, 3),
(20, 'CM', 'Africa/Douala', 1, 1, 1),
(21, 'EH', 'Africa/El_Aaiun', 0, 1, 0),
(22, 'SL', 'Africa/Freetown', 0, 0, 0),
(23, 'BW', 'Africa/Gaborone', 2, 2, 2),
(24, 'ZW', 'Africa/Harare', 2, 2, 2),
(25, 'ZA', 'Africa/Johannesburg', 2, 2, 2),
(26, 'SS', 'Africa/Juba', 3, 3, 3),
(27, 'UG', 'Africa/Kampala', 3, 3, 3),
(28, 'SD', 'Africa/Khartoum', 3, 3, 3),
(29, 'RW', 'Africa/Kigali', 2, 2, 2),
(30, 'CD', 'Africa/Kinshasa', 1, 1, 1),
(31, 'NG', 'Africa/Lagos', 1, 1, 1),
(32, 'GA', 'Africa/Libreville', 1, 1, 1),
(33, 'TG', 'Africa/Lome', 0, 0, 0),
(34, 'AO', 'Africa/Luanda', 1, 1, 1),
(35, 'CD', 'Africa/Lubumbashi', 2, 2, 2),
(36, 'ZM', 'Africa/Lusaka', 2, 2, 2),
(37, 'GQ', 'Africa/Malabo', 1, 1, 1),
(38, 'MZ', 'Africa/Maputo', 2, 2, 2),
(39, 'LS', 'Africa/Maseru', 2, 2, 2),
(40, 'SZ', 'Africa/Mbabane', 2, 2, 2),
(41, 'SO', 'Africa/Mogadishu', 3, 3, 3),
(42, 'LR', 'Africa/Monrovia', 0, 0, 0),
(43, 'KE', 'Africa/Nairobi', 3, 3, 3),
(44, 'TD', 'Africa/Ndjamena', 1, 1, 1),
(45, 'NE', 'Africa/Niamey', 1, 1, 1),
(46, 'MR', 'Africa/Nouakchott', 0, 0, 0),
(47, 'BF', 'Africa/Ouagadougou', 0, 0, 0),
(48, 'BJ', 'Africa/Porto-Novo', 1, 1, 1),
(49, 'ST', 'Africa/Sao_Tome', 0, 0, 0),
(50, 'LY', 'Africa/Tripoli', 2, 2, 2),
(51, 'TN', 'Africa/Tunis', 1, 1, 1),
(52, 'NA', 'Africa/Windhoek', 2, 1, 1),
(53, 'US', 'America/Adak', -10, -9, -10),
(54, 'US', 'America/Anchorage', -9, -8, -9),
(55, 'AI', 'America/Anguilla', -4, -4, -4),
(56, 'AG', 'America/Antigua', -4, -4, -4),
(57, 'BR', 'America/Araguaina', -3, -3, -3),
(58, 'AR', 'America/Argentina/Buenos_Aires', -3, -3, -3),
(59, 'AR', 'America/Argentina/Catamarca', -3, -3, -3),
(60, 'AR', 'America/Argentina/Cordoba', -3, -3, -3),
(61, 'AR', 'America/Argentina/Jujuy', -3, -3, -3),
(62, 'AR', 'America/Argentina/La_Rioja', -3, -3, -3),
(63, 'AR', 'America/Argentina/Mendoza', -3, -3, -3),
(64, 'AR', 'America/Argentina/Rio_Gallegos', -3, -3, -3),
(65, 'AR', 'America/Argentina/Salta', -3, -3, -3),
(66, 'AR', 'America/Argentina/San_Juan', -3, -3, -3),
(67, 'AR', 'America/Argentina/San_Luis', -3, -3, -3),
(68, 'AR', 'America/Argentina/Tucuman', -3, -3, -3),
(69, 'AR', 'America/Argentina/Ushuaia', -3, -3, -3),
(70, 'AW', 'America/Aruba', -4, -4, -4),
(71, 'PY', 'America/Asuncion', -3, -4, -4),
(72, 'CA', 'America/Atikokan', -5, -5, -5),
(73, 'BR', 'America/Bahia', -3, -3, -3),
(74, 'MX', 'America/Bahia_Banderas', -6, -5, -6),
(75, 'BB', 'America/Barbados', -4, -4, -4),
(76, 'BR', 'America/Belem', -3, -3, -3),
(77, 'BZ', 'America/Belize', -6, -6, -6),
(78, 'CA', 'America/Blanc-Sablon', -4, -4, -4),
(79, 'BR', 'America/Boa_Vista', -4, -4, -4),
(80, 'CO', 'America/Bogota', -5, -5, -5),
(81, 'US', 'America/Boise', -7, -6, -7),
(82, 'CA', 'America/Cambridge_Bay', -7, -6, -7),
(83, 'BR', 'America/Campo_Grande', -3, -4, -4),
(84, 'MX', 'America/Cancun', -5, -5, -5),
(85, 'VE', 'America/Caracas', -4, -4, -4),
(86, 'GF', 'America/Cayenne', -3, -3, -3),
(87, 'KY', 'America/Cayman', -5, -5, -5),
(88, 'US', 'America/Chicago', -6, -5, -6),
(89, 'MX', 'America/Chihuahua', -7, -6, -7),
(90, 'CR', 'America/Costa_Rica', -6, -6, -6),
(91, 'CA', 'America/Creston', -7, -7, -7),
(92, 'BR', 'America/Cuiaba', -3, -4, -4),
(93, 'CW', 'America/Curacao', -4, -4, -4),
(94, 'GL', 'America/Danmarkshavn', 0, 0, 0),
(95, 'CA', 'America/Dawson', -8, -7, -8),
(96, 'CA', 'America/Dawson_Creek', -7, -7, -7),
(97, 'US', 'America/Denver', -7, -6, -7),
(98, 'US', 'America/Detroit', -5, -4, -5),
(99, 'DM', 'America/Dominica', -4, -4, -4),
(100, 'CA', 'America/Edmonton', -7, -6, -7),
(101, 'BR', 'America/Eirunepe', -5, -5, -5),
(102, 'SV', 'America/El_Salvador', -6, -6, -6),
(103, 'CA', 'America/Fort_Nelson', -7, -7, -7),
(104, 'BR', 'America/Fortaleza', -3, -3, -3),
(105, 'CA', 'America/Glace_Bay', -4, -3, -4),
(106, 'GL', 'America/Godthab', -3, -2, -3),
(107, 'CA', 'America/Goose_Bay', -4, -3, -4),
(108, 'TC', 'America/Grand_Turk', -4, -4, -4),
(109, 'GD', 'America/Grenada', -4, -4, -4),
(110, 'GP', 'America/Guadeloupe', -4, -4, -4),
(111, 'GT', 'America/Guatemala', -6, -6, -6),
(112, 'EC', 'America/Guayaquil', -5, -5, -5),
(113, 'GY', 'America/Guyana', -4, -4, -4),
(114, 'CA', 'America/Halifax', -4, -3, -4),
(115, 'CU', 'America/Havana', -5, -4, -5),
(116, 'MX', 'America/Hermosillo', -7, -7, -7),
(117, 'US', 'America/Indiana/Indianapolis', -5, -4, -5),
(118, 'US', 'America/Indiana/Knox', -6, -5, -6),
(119, 'US', 'America/Indiana/Marengo', -5, -4, -5),
(120, 'US', 'America/Indiana/Petersburg', -5, -4, -5),
(121, 'US', 'America/Indiana/Tell_City', -6, -5, -6),
(122, 'US', 'America/Indiana/Vevay', -5, -4, -5),
(123, 'US', 'America/Indiana/Vincennes', -5, -4, -5),
(124, 'US', 'America/Indiana/Winamac', -5, -4, -5),
(125, 'CA', 'America/Inuvik', -7, -6, -7),
(126, 'CA', 'America/Iqaluit', -5, -4, -5),
(127, 'JM', 'America/Jamaica', -5, -5, -5),
(128, 'US', 'America/Juneau', -9, -8, -9),
(129, 'US', 'America/Kentucky/Louisville', -5, -4, -5),
(130, 'US', 'America/Kentucky/Monticello', -5, -4, -5),
(131, 'BQ', 'America/Kralendijk', -4, -4, -4),
(132, 'BO', 'America/La_Paz', -4, -4, -4),
(133, 'PE', 'America/Lima', -5, -5, -5),
(134, 'US', 'America/Los_Angeles', -8, -7, -8),
(135, 'SX', 'America/Lower_Princes', -4, -4, -4),
(136, 'BR', 'America/Maceio', -3, -3, -3),
(137, 'NI', 'America/Managua', -6, -6, -6),
(138, 'BR', 'America/Manaus', -4, -4, -4),
(139, 'MF', 'America/Marigot', -4, -4, -4),
(140, 'MQ', 'America/Martinique', -4, -4, -4),
(141, 'MX', 'America/Matamoros', -6, -5, -6),
(142, 'MX', 'America/Mazatlan', -7, -6, -7),
(143, 'US', 'America/Menominee', -6, -5, -6),
(144, 'MX', 'America/Merida', -6, -5, -6),
(145, 'US', 'America/Metlakatla', -9, -8, -9),
(146, 'MX', 'America/Mexico_City', -6, -5, -6),
(147, 'PM', 'America/Miquelon', -3, -2, -3),
(148, 'CA', 'America/Moncton', -4, -3, -4),
(149, 'MX', 'America/Monterrey', -6, -5, -6),
(150, 'UY', 'America/Montevideo', -3, -3, -3),
(151, 'MS', 'America/Montserrat', -4, -4, -4),
(152, 'BS', 'America/Nassau', -5, -4, -5),
(153, 'US', 'America/New_York', -5, -4, -5),
(154, 'CA', 'America/Nipigon', -5, -4, -5),
(155, 'US', 'America/Nome', -9, -8, -9),
(156, 'BR', 'America/Noronha', -2, -2, -2),
(157, 'US', 'America/North_Dakota/Beulah', -6, -5, -6),
(158, 'US', 'America/North_Dakota/Center', -6, -5, -6),
(159, 'US', 'America/North_Dakota/New_Salem', -6, -5, -6),
(160, 'MX', 'America/Ojinaga', -7, -6, -7),
(161, 'PA', 'America/Panama', -5, -5, -5),
(162, 'CA', 'America/Pangnirtung', -5, -4, -5),
(163, 'SR', 'America/Paramaribo', -3, -3, -3),
(164, 'US', 'America/Phoenix', -7, -7, -7),
(165, 'HT', 'America/Port-au-Prince', -5, -4, -5),
(166, 'TT', 'America/Port_of_Spain', -4, -4, -4),
(167, 'BR', 'America/Porto_Velho', -4, -4, -4),
(168, 'PR', 'America/Puerto_Rico', -4, -4, -4),
(169, 'CL', 'America/Punta_Arenas', -3, -3, -3),
(170, 'CA', 'America/Rainy_River', -6, -5, -6),
(171, 'CA', 'America/Rankin_Inlet', -6, -5, -6),
(172, 'BR', 'America/Recife', -3, -3, -3),
(173, 'CA', 'America/Regina', -6, -6, -6),
(174, 'CA', 'America/Resolute', -6, -5, -6),
(175, 'BR', 'America/Rio_Branco', -5, -5, -5),
(176, 'BR', 'America/Santarem', -3, -3, -3),
(177, 'CL', 'America/Santiago', -3, -4, -4),
(178, 'DO', 'America/Santo_Domingo', -4, -4, -4),
(179, 'BR', 'America/Sao_Paulo', -2, -3, -3),
(180, 'GL', 'America/Scoresbysund', -1, 0, -1),
(181, 'US', 'America/Sitka', -9, -8, -9),
(182, 'BL', 'America/St_Barthelemy', -4, -4, -4),
(183, 'CA', 'America/St_Johns', -3.5, -2.5, -3.5),
(184, 'KN', 'America/St_Kitts', -4, -4, -4),
(185, 'LC', 'America/St_Lucia', -4, -4, -4),
(186, 'VI', 'America/St_Thomas', -4, -4, -4),
(187, 'VC', 'America/St_Vincent', -4, -4, -4),
(188, 'CA', 'America/Swift_Current', -6, -6, -6),
(189, 'HN', 'America/Tegucigalpa', -6, -6, -6),
(190, 'GL', 'America/Thule', -4, -3, -4),
(191, 'CA', 'America/Thunder_Bay', -5, -4, -5),
(192, 'MX', 'America/Tijuana', -8, -7, -8),
(193, 'CA', 'America/Toronto', -5, -4, -5),
(194, 'VG', 'America/Tortola', -4, -4, -4),
(195, 'CA', 'America/Vancouver', -8, -7, -8),
(196, 'CA', 'America/Whitehorse', -8, -7, -8),
(197, 'CA', 'America/Winnipeg', -6, -5, -6),
(198, 'US', 'America/Yakutat', -9, -8, -9),
(199, 'CA', 'America/Yellowknife', -7, -6, -7),
(200, 'AQ', 'Antarctica/Casey', 11, 11, 11),
(201, 'AQ', 'Antarctica/Davis', 7, 7, 7),
(202, 'AQ', 'Antarctica/DumontDUrville', 10, 10, 10),
(203, 'AU', 'Antarctica/Macquarie', 11, 11, 11),
(204, 'AQ', 'Antarctica/Mawson', 5, 5, 5),
(205, 'AQ', 'Antarctica/McMurdo', 13, 12, 12),
(206, 'AQ', 'Antarctica/Palmer', -3, -3, -3),
(207, 'AQ', 'Antarctica/Rothera', -3, -3, -3),
(208, 'AQ', 'Antarctica/Syowa', 3, 3, 3),
(209, 'AQ', 'Antarctica/Troll', 0, 2, 0),
(210, 'AQ', 'Antarctica/Vostok', 6, 6, 6),
(211, 'SJ', 'Arctic/Longyearbyen', 1, 2, 1),
(212, 'YE', 'Asia/Aden', 3, 3, 3),
(213, 'KZ', 'Asia/Almaty', 6, 6, 6),
(214, 'JO', 'Asia/Amman', 2, 3, 2),
(215, 'RU', 'Asia/Anadyr', 12, 12, 12),
(216, 'KZ', 'Asia/Aqtau', 5, 5, 5),
(217, 'KZ', 'Asia/Aqtobe', 5, 5, 5),
(218, 'TM', 'Asia/Ashgabat', 5, 5, 5),
(219, 'KZ', 'Asia/Atyrau', 5, 5, 5),
(220, 'IQ', 'Asia/Baghdad', 3, 3, 3),
(221, 'BH', 'Asia/Bahrain', 3, 3, 3),
(222, 'AZ', 'Asia/Baku', 4, 4, 4),
(223, 'TH', 'Asia/Bangkok', 7, 7, 7),
(224, 'RU', 'Asia/Barnaul', 7, 7, 7),
(225, 'LB', 'Asia/Beirut', 2, 3, 2),
(226, 'KG', 'Asia/Bishkek', 6, 6, 6),
(227, 'BN', 'Asia/Brunei', 8, 8, 8),
(228, 'RU', 'Asia/Chita', 9, 9, 9),
(229, 'MN', 'Asia/Choibalsan', 8, 8, 8),
(230, 'LK', 'Asia/Colombo', 5.5, 5.5, 5.5),
(231, 'SY', 'Asia/Damascus', 2, 3, 2),
(232, 'BD', 'Asia/Dhaka', 6, 6, 6),
(233, 'TL', 'Asia/Dili', 9, 9, 9),
(234, 'AE', 'Asia/Dubai', 4, 4, 4),
(235, 'TJ', 'Asia/Dushanbe', 5, 5, 5),
(236, 'CY', 'Asia/Famagusta', 3, 3, 3),
(237, 'PS', 'Asia/Gaza', 2, 3, 2),
(238, 'PS', 'Asia/Hebron', 2, 3, 2),
(239, 'VN', 'Asia/Ho_Chi_Minh', 7, 7, 7),
(240, 'HK', 'Asia/Hong_Kong', 8, 8, 8),
(241, 'MN', 'Asia/Hovd', 7, 7, 7),
(242, 'RU', 'Asia/Irkutsk', 8, 8, 8),
(243, 'ID', 'Asia/Jakarta', 7, 7, 7),
(244, 'ID', 'Asia/Jayapura', 9, 9, 9),
(245, 'IL', 'Asia/Jerusalem', 2, 3, 2),
(246, 'AF', 'Asia/Kabul', 4.5, 4.5, 4.5),
(247, 'RU', 'Asia/Kamchatka', 12, 12, 12),
(248, 'PK', 'Asia/Karachi', 5, 5, 5),
(249, 'NP', 'Asia/Kathmandu', 5.75, 5.75, 5.75),
(250, 'RU', 'Asia/Khandyga', 9, 9, 9),
(251, 'IN', 'Asia/Kolkata', 5.5, 5.5, 5.5),
(252, 'RU', 'Asia/Krasnoyarsk', 7, 7, 7),
(253, 'MY', 'Asia/Kuala_Lumpur', 8, 8, 8),
(254, 'MY', 'Asia/Kuching', 8, 8, 8),
(255, 'KW', 'Asia/Kuwait', 3, 3, 3),
(256, 'MO', 'Asia/Macau', 8, 8, 8),
(257, 'RU', 'Asia/Magadan', 11, 11, 11),
(258, 'ID', 'Asia/Makassar', 8, 8, 8),
(259, 'PH', 'Asia/Manila', 8, 8, 8),
(260, 'OM', 'Asia/Muscat', 4, 4, 4),
(261, 'CY', 'Asia/Nicosia', 2, 3, 2),
(262, 'RU', 'Asia/Novokuznetsk', 7, 7, 7),
(263, 'RU', 'Asia/Novosibirsk', 7, 7, 7),
(264, 'RU', 'Asia/Omsk', 6, 6, 6),
(265, 'KZ', 'Asia/Oral', 5, 5, 5),
(266, 'KH', 'Asia/Phnom_Penh', 7, 7, 7),
(267, 'ID', 'Asia/Pontianak', 7, 7, 7),
(268, 'KP', 'Asia/Pyongyang', 8.5, 8.5, 8.5),
(269, 'QA', 'Asia/Qatar', 3, 3, 3),
(270, 'KZ', 'Asia/Qyzylorda', 6, 6, 6),
(271, 'SA', 'Asia/Riyadh', 3, 3, 3),
(272, 'RU', 'Asia/Sakhalin', 11, 11, 11),
(273, 'UZ', 'Asia/Samarkand', 5, 5, 5),
(274, 'KR', 'Asia/Seoul', 9, 9, 9),
(275, 'CN', 'Asia/Shanghai', 8, 8, 8),
(276, 'SG', 'Asia/Singapore', 8, 8, 8),
(277, 'RU', 'Asia/Srednekolymsk', 11, 11, 11),
(278, 'TW', 'Asia/Taipei', 8, 8, 8),
(279, 'UZ', 'Asia/Tashkent', 5, 5, 5),
(280, 'GE', 'Asia/Tbilisi', 4, 4, 4),
(281, 'IR', 'Asia/Tehran', 3.5, 4.5, 3.5),
(282, 'BT', 'Asia/Thimphu', 6, 6, 6),
(283, 'JP', 'Asia/Tokyo', 9, 9, 9),
(284, 'RU', 'Asia/Tomsk', 7, 7, 7),
(285, 'MN', 'Asia/Ulaanbaatar', 8, 8, 8),
(286, 'CN', 'Asia/Urumqi', 6, 6, 6),
(287, 'RU', 'Asia/Ust-Nera', 10, 10, 10),
(288, 'LA', 'Asia/Vientiane', 7, 7, 7),
(289, 'RU', 'Asia/Vladivostok', 10, 10, 10),
(290, 'RU', 'Asia/Yakutsk', 9, 9, 9),
(291, 'MM', 'Asia/Yangon', 6.5, 6.5, 6.5),
(292, 'RU', 'Asia/Yekaterinburg', 5, 5, 5),
(293, 'AM', 'Asia/Yerevan', 4, 4, 4),
(294, 'PT', 'Atlantic/Azores', -1, 0, -1),
(295, 'BM', 'Atlantic/Bermuda', -4, -3, -4),
(296, 'ES', 'Atlantic/Canary', 0, 1, 0),
(297, 'CV', 'Atlantic/Cape_Verde', -1, -1, -1),
(298, 'FO', 'Atlantic/Faroe', 0, 1, 0),
(299, 'PT', 'Atlantic/Madeira', 0, 1, 0),
(300, 'IS', 'Atlantic/Reykjavik', 0, 0, 0),
(301, 'GS', 'Atlantic/South_Georgia', -2, -2, -2),
(302, 'SH', 'Atlantic/St_Helena', 0, 0, 0),
(303, 'FK', 'Atlantic/Stanley', -3, -3, -3),
(304, 'AU', 'Australia/Adelaide', 10.5, 9.5, 9.5),
(305, 'AU', 'Australia/Brisbane', 10, 10, 10),
(306, 'AU', 'Australia/Broken_Hill', 10.5, 9.5, 9.5),
(307, 'AU', 'Australia/Currie', 11, 10, 10),
(308, 'AU', 'Australia/Darwin', 9.5, 9.5, 9.5),
(309, 'AU', 'Australia/Eucla', 8.75, 8.75, 8.75),
(310, 'AU', 'Australia/Hobart', 11, 10, 10),
(311, 'AU', 'Australia/Lindeman', 10, 10, 10),
(312, 'AU', 'Australia/Lord_Howe', 11, 10.5, 10.5),
(313, 'AU', 'Australia/Melbourne', 11, 10, 10),
(314, 'AU', 'Australia/Perth', 8, 8, 8),
(315, 'AU', 'Australia/Sydney', 11, 10, 10),
(316, 'NL', 'Europe/Amsterdam', 1, 2, 1),
(317, 'AD', 'Europe/Andorra', 1, 2, 1),
(318, 'RU', 'Europe/Astrakhan', 4, 4, 4),
(319, 'GR', 'Europe/Athens', 2, 3, 2),
(320, 'RS', 'Europe/Belgrade', 1, 2, 1),
(321, 'DE', 'Europe/Berlin', 1, 2, 1),
(322, 'SK', 'Europe/Bratislava', 1, 2, 1),
(323, 'BE', 'Europe/Brussels', 1, 2, 1),
(324, 'RO', 'Europe/Bucharest', 2, 3, 2),
(325, 'HU', 'Europe/Budapest', 1, 2, 1),
(326, 'DE', 'Europe/Busingen', 1, 2, 1),
(327, 'MD', 'Europe/Chisinau', 2, 3, 2),
(328, 'DK', 'Europe/Copenhagen', 1, 2, 1),
(329, 'IE', 'Europe/Dublin', 0, 1, 0),
(330, 'GI', 'Europe/Gibraltar', 1, 2, 1),
(331, 'GG', 'Europe/Guernsey', 0, 1, 0),
(332, 'FI', 'Europe/Helsinki', 2, 3, 2),
(333, 'IM', 'Europe/Isle_of_Man', 0, 1, 0),
(334, 'TR', 'Europe/Istanbul', 3, 3, 3),
(335, 'JE', 'Europe/Jersey', 0, 1, 0),
(336, 'RU', 'Europe/Kaliningrad', 2, 2, 2),
(337, 'UA', 'Europe/Kiev', 2, 3, 2),
(338, 'RU', 'Europe/Kirov', 3, 3, 3),
(339, 'PT', 'Europe/Lisbon', 0, 1, 0),
(340, 'SI', 'Europe/Ljubljana', 1, 2, 1),
(341, 'UK', 'Europe/London', 0, 1, 0),
(342, 'LU', 'Europe/Luxembourg', 1, 2, 1),
(343, 'ES', 'Europe/Madrid', 1, 2, 1),
(344, 'MT', 'Europe/Malta', 1, 2, 1),
(345, 'AX', 'Europe/Mariehamn', 2, 3, 2),
(346, 'BY', 'Europe/Minsk', 3, 3, 3),
(347, 'MC', 'Europe/Monaco', 1, 2, 1),
(348, 'RU', 'Europe/Moscow', 3, 3, 3),
(349, 'NO', 'Europe/Oslo', 1, 2, 1),
(350, 'FR', 'Europe/Paris', 1, 2, 1),
(351, 'ME', 'Europe/Podgorica', 1, 2, 1),
(352, 'CZ', 'Europe/Prague', 1, 2, 1),
(353, 'LV', 'Europe/Riga', 2, 3, 2),
(354, 'IT', 'Europe/Rome', 1, 2, 1),
(355, 'RU', 'Europe/Samara', 4, 4, 4),
(356, 'SM', 'Europe/San_Marino', 1, 2, 1),
(357, 'BA', 'Europe/Sarajevo', 1, 2, 1),
(358, 'RU', 'Europe/Saratov', 4, 4, 4),
(359, 'RU', 'Europe/Simferopol', 3, 3, 3),
(360, 'MK', 'Europe/Skopje', 1, 2, 1),
(361, 'BG', 'Europe/Sofia', 2, 3, 2),
(362, 'SE', 'Europe/Stockholm', 1, 2, 1),
(363, 'EE', 'Europe/Tallinn', 2, 3, 2),
(364, 'AL', 'Europe/Tirane', 1, 2, 1),
(365, 'RU', 'Europe/Ulyanovsk', 4, 4, 4),
(366, 'UA', 'Europe/Uzhgorod', 2, 3, 2),
(367, 'LI', 'Europe/Vaduz', 1, 2, 1),
(368, 'VA', 'Europe/Vatican', 1, 2, 1),
(369, 'AT', 'Europe/Vienna', 1, 2, 1),
(370, 'LT', 'Europe/Vilnius', 2, 3, 2),
(371, 'RU', 'Europe/Volgograd', 3, 3, 3),
(372, 'PL', 'Europe/Warsaw', 1, 2, 1),
(373, 'HR', 'Europe/Zagreb', 1, 2, 1),
(374, 'UA', 'Europe/Zaporozhye', 2, 3, 2),
(375, 'CH', 'Europe/Zurich', 1, 2, 1),
(376, 'MG', 'Indian/Antananarivo', 3, 3, 3),
(377, 'IO', 'Indian/Chagos', 6, 6, 6),
(378, 'CX', 'Indian/Christmas', 7, 7, 7),
(379, 'CC', 'Indian/Cocos', 6.5, 6.5, 6.5),
(380, 'KM', 'Indian/Comoro', 3, 3, 3),
(381, 'TF', 'Indian/Kerguelen', 5, 5, 5),
(382, 'SC', 'Indian/Mahe', 4, 4, 4),
(383, 'MV', 'Indian/Maldives', 5, 5, 5),
(384, 'MU', 'Indian/Mauritius', 4, 4, 4),
(385, 'YT', 'Indian/Mayotte', 3, 3, 3),
(386, 'RE', 'Indian/Reunion', 4, 4, 4),
(387, 'WS', 'Pacific/Apia', 14, 13, 13),
(388, 'NZ', 'Pacific/Auckland', 13, 12, 12),
(389, 'PG', 'Pacific/Bougainville', 11, 11, 11),
(390, 'NZ', 'Pacific/Chatham', 13.75, 12.75, 12.75),
(391, 'FM', 'Pacific/Chuuk', 10, 10, 10),
(392, 'CL', 'Pacific/Easter', -5, -6, -6),
(393, 'VU', 'Pacific/Efate', 11, 11, 11),
(394, 'KI', 'Pacific/Enderbury', 13, 13, 13),
(395, 'TK', 'Pacific/Fakaofo', 13, 13, 13),
(396, 'FJ', 'Pacific/Fiji', 13, 12, 12),
(397, 'TV', 'Pacific/Funafuti', 12, 12, 12),
(398, 'EC', 'Pacific/Galapagos', -6, -6, -6),
(399, 'PF', 'Pacific/Gambier', -9, -9, -9),
(400, 'SB', 'Pacific/Guadalcanal', 11, 11, 11),
(401, 'GU', 'Pacific/Guam', 10, 10, 10),
(402, 'US', 'Pacific/Honolulu', -10, -10, -10),
(403, 'KI', 'Pacific/Kiritimati', 14, 14, 14),
(404, 'FM', 'Pacific/Kosrae', 11, 11, 11),
(405, 'MH', 'Pacific/Kwajalein', 12, 12, 12),
(406, 'MH', 'Pacific/Majuro', 12, 12, 12),
(407, 'PF', 'Pacific/Marquesas', -9.5, -9.5, -9.5),
(408, 'UM', 'Pacific/Midway', -11, -11, -11),
(409, 'NR', 'Pacific/Nauru', 12, 12, 12),
(410, 'NU', 'Pacific/Niue', -11, -11, -11),
(411, 'NF', 'Pacific/Norfolk', 11, 11, 11),
(412, 'NC', 'Pacific/Noumea', 11, 11, 11),
(413, 'AS', 'Pacific/Pago_Pago', -11, -11, -11),
(414, 'PW', 'Pacific/Palau', 9, 9, 9),
(415, 'PN', 'Pacific/Pitcairn', -8, -8, -8),
(416, 'FM', 'Pacific/Pohnpei', 11, 11, 11),
(417, 'PG', 'Pacific/Port_Moresby', 10, 10, 10),
(418, 'CK', 'Pacific/Rarotonga', -10, -10, -10),
(419, 'MP', 'Pacific/Saipan', 10, 10, 10),
(420, 'PF', 'Pacific/Tahiti', -10, -10, -10),
(421, 'KI', 'Pacific/Tarawa', 12, 12, 12),
(422, 'TO', 'Pacific/Tongatapu', 14, 13, 13),
(423, 'UM', 'Pacific/Wake', 12, 12, 12),
(424, 'WF', 'Pacific/Wallis', 12, 12, 12);

-- --------------------------------------------------------

--
-- Table structure for table `theqqausers`
--

CREATE TABLE `theqqausers` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cities_ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type_id` int(10) UNSIGNED DEFAULT NULL,
  `gender_id` int(10) UNSIGNED DEFAULT NULL,
  `id_number_owner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_data` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_hidden` tinyint(1) UNSIGNED DEFAULT '0',
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) UNSIGNED DEFAULT '0',
  `can_be_impersonated` tinyint(1) UNSIGNED DEFAULT '1',
  `disable_comments` tinyint(1) UNSIGNED DEFAULT '0',
  `receive_newsletter` tinyint(1) UNSIGNED DEFAULT '1',
  `receive_advice` tinyint(1) UNSIGNED DEFAULT '1',
  `ip_addr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_id` int(10) UNSIGNED DEFAULT NULL,
  `email_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified_email` tinyint(1) UNSIGNED DEFAULT '1',
  `verified_phone` tinyint(1) UNSIGNED DEFAULT '0',
  `blocked` tinyint(1) UNSIGNED DEFAULT '0',
  `closed` tinyint(1) UNSIGNED DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqausers`
--

INSERT INTO `theqqausers` (`id`, `country_code`, `cities_ids`, `language_code`, `user_type_id`, `gender_id`, `id_number_owner`, `id_number`, `name`, `photo`, `image_data`, `about`, `phone`, `phone_hidden`, `username`, `email`, `password`, `remember_token`, `is_admin`, `can_be_impersonated`, `disable_comments`, `receive_newsletter`, `receive_advice`, `ip_addr`, `provider`, `provider_id`, `email_token`, `phone_token`, `verified_email`, `verified_phone`, `blocked`, `closed`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SA', '110619,110031,108048,107959,106281,110250', 'ar', 1, 3, NULL, NULL, 'Theqqa', NULL, '', 'Administrator', '+966551166575', 0, 'theqqa', 'services@theqqa.com', '$2y$10$.cud6luKVCvGBw/BFbbgwOnZVK2LkudKv1Nm63zYpPquOoGNLFW0W', 'B9yGDVXJLUBqV5sobyRB6Sh9Sw8yJMt1yv20UyONXvFhmX3h0o22BxcRdkp2', NULL, 1, 0, 1, 1, NULL, NULL, NULL, '557ec48df7aa78d02f51eaa6eeacd64a', NULL, 1, 1, 0, 0, '2020-02-03 11:05:57', NULL, '2020-02-03 17:05:57', NULL),
(2, 'SA', '', 'ar', 2, 3, NULL, '7645789478', 'Mahmoud Mostafa', NULL, '', NULL, '01004561042', NULL, 'mman', 'dolitik@gmail.com', '$2y$10$KZ9cY0htTU004ztkZU2kXeAgz37qs5oakqLz9zZisd0FUWr3.dZ/S', NULL, 0, 1, 0, 1, 1, '156.194.236.80', NULL, NULL, 'e3a459950511cbeff2be58d10ce9d334', '329888', 1, 1, 0, 0, NULL, '2020-02-05 21:48:41', '2020-02-05 21:52:00', NULL),
(3, 'SA', '', 'ar', 2, NULL, NULL, '2424417117', 'Mazen Person', NULL, '', NULL, '+966598867604', NULL, NULL, 'mazen.elsaaid@gmail.com', '$2y$10$UzwYujHTAbAre5VBg6e7NOkZqrIzZQ27r5mb1wJiKm88yC9AfA/Du', 'dzM7fF8mn29SxKTejmHhref8Y1P8KiqIWRCRLulNbVXtHsEHT3SoMRGi8ua4', 0, 1, 0, 1, 1, '176.225.235.139', NULL, NULL, 'a4344fadbf3c024e85434854c4ce7389', '142756', 1, 1, 0, 0, NULL, '2020-02-07 00:07:08', '2020-02-07 00:08:31', NULL),
(4, 'SA', '108410', 'ar', 6, NULL, '2424414117', NULL, 'معرض مازن للسيارات', NULL, '[\"64195063414821fdf115e5f9a975d815.jpg\"]', NULL, '+966598867605', NULL, NULL, 'mazen.elsaaid+1@gmail.com', '$2y$10$7rU/1516.WaeTwD4Fg1BZuE1OFiWOE6Fqt8rj3QpRjvTTKEHZNpPe', 'xY5weJZrzE1NmcuhuT1w30TQEev94T3sgYaQD4DMO0sFTFivrsOcbUvPXnBZ', 0, 1, 0, 1, 1, '176.225.235.139', NULL, NULL, '1b5ef9047c7b1da3ef18d3c33728d934', '143192', 1, 1, 0, 0, NULL, '2020-02-07 00:14:25', '2020-02-07 00:22:17', NULL),
(5, 'SA', '108410', 'ar', 4, NULL, '2424417117', NULL, 'مركز مازن لفحص السيارات', NULL, '[\"4adc9c03d9954a3bd619d0e51982259b.jpg\"]', NULL, '+966598867606', NULL, NULL, 'mazen.elsaaid+2@gmail.com', '$2y$10$4prUiOj1SL3PRHVCeIQZ4OS7I8Me0RjsLEibSArc9lpd6xiwt5sqC', 'JkmVIGOgwjar8zs8FXPPUAeEG86rlpChobO6PEHL6D5p3rotKIMp9YZs07ho', 0, 1, 0, 1, 1, '176.225.235.139', NULL, NULL, 'c3d8e953646b4b4baebfbc97914d4934', '240230', 1, 1, 0, 0, '2020-02-06 18:54:42', '2020-02-07 00:25:21', '2020-02-07 00:54:42', NULL),
(6, 'SA', '108410', 'ar', 5, NULL, '2424417117', NULL, 'مازن لشحن السيارات', NULL, '[\"4489981362e7a4452a464d08bcb376fa.jpg\"]', NULL, '+966598867607', NULL, NULL, 'mazen.elsaaid+4@gmail.com', '$2y$10$lI7/o69gt61/PyWjyskTmuqLkwFvlfP6uq4nvzFJKcTf1JIZmAuRW', NULL, 0, 1, 0, 1, 1, '176.225.235.139', NULL, NULL, 'cb577514e6842e3ab3da3ee6d9095f90', '256788', 1, 1, 0, 0, NULL, '2020-02-07 01:01:35', '2020-02-07 01:02:11', NULL),
(7, 'SA', '', 'ar', 2, 3, NULL, '2158479634', 'محمد عبدالعزيز', NULL, '', NULL, '+966594935331', NULL, 'Birdgo', 'birdgo2016@gmail.com', '$2y$10$JjBkic0.SRrCUVXvaufd9erbhBjvjW3HdAWZCCGKVGD2asumGQVHK', 'xn4HS1m0MUcARYxRUbvF8xFPkORjCaOcXIKzJm4xpawEeW6C8S1CmXYhfBpJ', 0, 1, 0, 1, 1, '37.42.139.214', NULL, NULL, 'ca80e18a5861eec57586dc4a7e72ca79', NULL, 1, 1, 0, 0, '2020-02-06 20:48:55', '2020-02-07 02:47:18', '2020-02-07 02:48:55', NULL),
(8, 'SA', '', 'ar', 2, NULL, NULL, '2315937611', 'JUNAID AHMAD', NULL, '', NULL, '+966590490870', 1, NULL, 'jun3id@yahoo.com', '$2y$10$W.c49cdVLI4/L7zfRKYooePddUJD9zKXsQUdSdhTj0Gp3386qxBsK', 'CJigw09ddJVftxJU7XqmNtSYJp1eBpWGQWlvCh0yVySGo3nZfZr8bdgAja6q', 0, 1, 0, 1, 1, '37.42.139.214', NULL, NULL, '6bfe6ee12447813e1d5cd62330a89061', NULL, 1, 1, 0, 0, '2020-02-06 21:04:43', '2020-02-07 02:56:59', '2020-02-07 03:04:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theqqauser_types`
--

CREATE TABLE `theqqauser_types` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theqqauser_types`
--

INSERT INTO `theqqauser_types` (`id`, `name`, `active`) VALUES
(1, 'شركات', 1),
(2, 'أفراد', 1),
(3, 'شركة صيانة', 1),
(4, 'شركة فحص', 1),
(5, 'شركة شحن', 1),
(6, 'معرض', 1),
(7, 'بطاريات سيارات', 1),
(8, 'إكسسوارات سيارات', 1),
(9, 'قطع غيار سيارات', 1),
(10, 'سطحات', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `theqqaadvertising`
--
ALTER TABLE `theqqaadvertising`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqablacklist`
--
ALTER TABLE `theqqablacklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`,`entry`);

--
-- Indexes for table `theqqacache`
--
ALTER TABLE `theqqacache`
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `theqqacategories`
--
ALTER TABLE `theqqacategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `theqqacategory_field`
--
ALTER TABLE `theqqacategory_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_id` (`category_id`,`field_id`);

--
-- Indexes for table `theqqacities`
--
ALTER TABLE `theqqacities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `name` (`name`),
  ADD KEY `subadmin1_code` (`subadmin1_code`),
  ADD KEY `subadmin2_code` (`subadmin2_code`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqacontinents`
--
ALTER TABLE `theqqacontinents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqacountries`
--
ALTER TABLE `theqqacountries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqacurrencies`
--
ALTER TABLE `theqqacurrencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `theqqafields`
--
ALTER TABLE `theqqafields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`),
  ADD KEY `belongs_to` (`belongs_to`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqafields_options`
--
ALTER TABLE `theqqafields_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`);

--
-- Indexes for table `theqqagender`
--
ALTER TABLE `theqqagender`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`);

--
-- Indexes for table `theqqahome_sections`
--
ALTER TABLE `theqqahome_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqaimageservice`
--
ALTER TABLE `theqqaimageservice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqalanguages`
--
ALTER TABLE `theqqalanguages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abbr` (`abbr`),
  ADD KEY `active` (`active`),
  ADD KEY `default` (`default`);

--
-- Indexes for table `theqqamessages`
--
ALTER TABLE `theqqamessages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `deleted_by` (`deleted_by`);

--
-- Indexes for table `theqqameta_tags`
--
ALTER TABLE `theqqameta_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqamodel_has_permissions`
--
ALTER TABLE `theqqamodel_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`);

--
-- Indexes for table `theqqamodel_has_roles`
--
ALTER TABLE `theqqamodel_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`);

--
-- Indexes for table `theqqaoauth_access_tokens`
--
ALTER TABLE `theqqaoauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `theqqaoauth_auth_codes`
--
ALTER TABLE `theqqaoauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqaoauth_clients`
--
ALTER TABLE `theqqaoauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `theqqaoauth_personal_access_clients`
--
ALTER TABLE `theqqaoauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `theqqaoauth_refresh_tokens`
--
ALTER TABLE `theqqaoauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `theqqapackages`
--
ALTER TABLE `theqqapackages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqapages`
--
ALTER TABLE `theqqapages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqapayments`
--
ALTER TABLE `theqqapayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `package_id` (`package_id`) USING BTREE,
  ADD KEY `post_id` (`post_id`),
  ADD KEY `active` (`active`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `theqqapayment_methods`
--
ALTER TABLE `theqqapayment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `has_ccbox` (`has_ccbox`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqapaytabs`
--
ALTER TABLE `theqqapaytabs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqapermissions`
--
ALTER TABLE `theqqapermissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqapictures`
--
ALTER TABLE `theqqapictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqaposts`
--
ALTER TABLE `theqqaposts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lat` (`lon`,`lat`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `title` (`title`),
  ADD KEY `address` (`address`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `reviewed` (`reviewed`),
  ADD KEY `featured` (`featured`),
  ADD KEY `post_type_id` (`post_type_id`),
  ADD KEY `verified_email` (`verified_email`),
  ADD KEY `verified_phone` (`verified_phone`),
  ADD KEY `contact_name` (`contact_name`),
  ADD KEY `tags` (`tags`);

--
-- Indexes for table `theqqapost_types`
--
ALTER TABLE `theqqapost_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indexes for table `theqqapost_values`
--
ALTER TABLE `theqqapost_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `theqqareport_types`
--
ALTER TABLE `theqqareport_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translation_lang` (`translation_lang`),
  ADD KEY `translation_of` (`translation_of`);

--
-- Indexes for table `theqqaroles`
--
ALTER TABLE `theqqaroles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqarole_has_permissions`
--
ALTER TABLE `theqqarole_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `theqqasaved_posts`
--
ALTER TABLE `theqqasaved_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `theqqasaved_search`
--
ALTER TABLE `theqqasaved_search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `country_code` (`country_code`);

--
-- Indexes for table `theqqasessions`
--
ALTER TABLE `theqqasessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theqqasettings`
--
ALTER TABLE `theqqasettings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqasubadmin1`
--
ALTER TABLE `theqqasubadmin1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `name` (`name`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqasubadmin2`
--
ALTER TABLE `theqqasubadmin2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `subadmin1_code` (`subadmin1_code`),
  ADD KEY `name` (`name`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `theqqatime_zones`
--
ALTER TABLE `theqqatime_zones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `time_zone_id` (`time_zone_id`),
  ADD KEY `country_code` (`country_code`);

--
-- Indexes for table `theqqausers`
--
ALTER TABLE `theqqausers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `user_type_id` (`user_type_id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `verified_email` (`verified_email`),
  ADD KEY `verified_phone` (`verified_phone`),
  ADD KEY `is_admin` (`is_admin`),
  ADD KEY `can_be_impersonated` (`can_be_impersonated`);

--
-- Indexes for table `theqqauser_types`
--
ALTER TABLE `theqqauser_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `theqqaadvertising`
--
ALTER TABLE `theqqaadvertising`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `theqqablacklist`
--
ALTER TABLE `theqqablacklist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theqqacategories`
--
ALTER TABLE `theqqacategories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=479;

--
-- AUTO_INCREMENT for table `theqqacategory_field`
--
ALTER TABLE `theqqacategory_field`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `theqqacontinents`
--
ALTER TABLE `theqqacontinents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `theqqacountries`
--
ALTER TABLE `theqqacountries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `theqqacurrencies`
--
ALTER TABLE `theqqacurrencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `theqqafields`
--
ALTER TABLE `theqqafields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `theqqafields_options`
--
ALTER TABLE `theqqafields_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=458;

--
-- AUTO_INCREMENT for table `theqqagender`
--
ALTER TABLE `theqqagender`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `theqqahome_sections`
--
ALTER TABLE `theqqahome_sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `theqqaimageservice`
--
ALTER TABLE `theqqaimageservice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theqqalanguages`
--
ALTER TABLE `theqqalanguages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `theqqamessages`
--
ALTER TABLE `theqqamessages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theqqameta_tags`
--
ALTER TABLE `theqqameta_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `theqqaoauth_clients`
--
ALTER TABLE `theqqaoauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `theqqaoauth_personal_access_clients`
--
ALTER TABLE `theqqaoauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `theqqapackages`
--
ALTER TABLE `theqqapackages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `theqqapages`
--
ALTER TABLE `theqqapages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `theqqapayments`
--
ALTER TABLE `theqqapayments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `theqqapayment_methods`
--
ALTER TABLE `theqqapayment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `theqqapaytabs`
--
ALTER TABLE `theqqapaytabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theqqapermissions`
--
ALTER TABLE `theqqapermissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `theqqapictures`
--
ALTER TABLE `theqqapictures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `theqqaposts`
--
ALTER TABLE `theqqaposts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `theqqapost_types`
--
ALTER TABLE `theqqapost_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `theqqapost_values`
--
ALTER TABLE `theqqapost_values`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `theqqareport_types`
--
ALTER TABLE `theqqareport_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `theqqaroles`
--
ALTER TABLE `theqqaroles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `theqqasaved_posts`
--
ALTER TABLE `theqqasaved_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `theqqasaved_search`
--
ALTER TABLE `theqqasaved_search`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theqqasettings`
--
ALTER TABLE `theqqasettings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `theqqasubadmin1`
--
ALTER TABLE `theqqasubadmin1`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2912;

--
-- AUTO_INCREMENT for table `theqqasubadmin2`
--
ALTER TABLE `theqqasubadmin2`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35626;

--
-- AUTO_INCREMENT for table `theqqatime_zones`
--
ALTER TABLE `theqqatime_zones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `theqqausers`
--
ALTER TABLE `theqqausers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `theqqamodel_has_permissions`
--
ALTER TABLE `theqqamodel_has_permissions`
  ADD CONSTRAINT `theqqamodel_has_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `theqqapermissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `theqqamodel_has_roles`
--
ALTER TABLE `theqqamodel_has_roles`
  ADD CONSTRAINT `theqqamodel_has_roles_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `theqqaroles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `theqqarole_has_permissions`
--
ALTER TABLE `theqqarole_has_permissions`
  ADD CONSTRAINT `theqqarole_has_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `theqqapermissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theqqarole_has_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `theqqaroles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
