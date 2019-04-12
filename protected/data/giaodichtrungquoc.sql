-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 05, 2016 at 07:32 PM
-- Server version: 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `giaodichtrungquoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `active`, `created_time`, `updated_time`, `email`, `password`, `type`, `name`) VALUES
(1, 1, 1445914242, 1445914242, 'thanhtung9630@gmail.com', '$2a$13$SjDiI14uhke/DpPob6AOVeGvmvcz4/92P1eo4RTJqh0F/QJGYAH7e', 1, 'Nguyễn Thanh Tùng');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner`
--

CREATE TABLE IF NOT EXISTS `tbl_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `image` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_collaborator`
--

CREATE TABLE IF NOT EXISTS `tbl_collaborator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `collaborator_group_id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_access_token` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_thumbnail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_manager` int(1) NOT NULL DEFAULT '0',
  `google_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_token` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_collaborator`
--

INSERT INTO `tbl_collaborator` (`id`, `active`, `created_time`, `updated_time`, `name`, `collaborator_group_id`, `type`, `email`, `password`, `phone`, `skype`, `facebook_id`, `facebook_access_token`, `description`, `image`, `image_thumbnail`, `is_manager`, `google_id`, `google_token`) VALUES
(1, 1, 1474360882, 1474367941, 'A1Sale', 1, 1, 'a1sale@gmail.com', '$2a$13$l97oMRKXNkL7vOOuQsWq7uCCmyDjatQ3cidZyWbcld37GuK7g.NQm', '0975289682', 'thanhtung9630', '', NULL, '', '/upload/collaborator/1/image_1474360883.png', '/upload/collaborator/1/image_thumbnail_1474360883.png', 1, '', NULL),
(4, 1, 1474361255, 1474361255, 'A1Ship', 1, 2, 'a1ship@gmail.com', '$2a$13$D8ZPqB0m2s3bd5TdZp3r5ePMt.3kPsjWGYoBw8mpMRLss2rosWfdG', '123456789', 'a1sale', '', NULL, '', NULL, NULL, 0, '', NULL),
(5, 1, 1474361309, 1474361309, 'A1Store', 1, 3, 'a1store@gmail.com', '$2a$13$bq.FXDHMMDfcChogaSLUvud1/e9ZzDycXG1AZbL0Aa36KkK.EdWBe', '234567890', '', '', NULL, '', NULL, NULL, 0, '', NULL),
(6, 1, 1474361359, 1474361359, 'A1Accountant', 1, 4, 'a1accountant@gmail.com', '$2a$13$BvnywQvSdBmii/8TWfko5Od8nAqmufeYte7TX0nBL1MNGUjhQH8LC', '345678901', '', '', NULL, '', NULL, NULL, 0, '', NULL),
(7, 1, 1474361436, 1474361436, 'A2Sale', 2, 1, 'a2sale@gmail.com', '$2a$13$orLPsf1FGYQiPU67t31VVe9a567KxBXbjXNxOlUpbXf9JPpb8OyCe', '', '', '', NULL, '', NULL, NULL, 1, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_collaborator_group`
--

CREATE TABLE IF NOT EXISTS `tbl_collaborator_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_collaborator_group`
--

INSERT INTO `tbl_collaborator_group` (`id`, `active`, `created_time`, `updated_time`, `name`, `description`) VALUES
(1, 1, 1474359834, 1474360759, 'A1', NULL),
(2, 1, 1474359841, 1474360751, 'A2', NULL),
(3, 1, 1474359849, 1474360744, 'A3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_delivery_order`
--

CREATE TABLE IF NOT EXISTS `tbl_delivery_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_order_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `total_price` float DEFAULT NULL,
  `total_weight` float DEFAULT NULL,
  `is_paid` int(1) NOT NULL DEFAULT '0',
  `deposit_amount` float DEFAULT NULL,
  `delivery_price` float DEFAULT NULL,
  `total_real_price` float DEFAULT NULL,
  `storehouse_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_delivery_order`
--

INSERT INTO `tbl_delivery_order` (`id`, `active`, `created_time`, `updated_time`, `user_id`, `delivery_order_code`, `description`, `total_price`, `total_weight`, `is_paid`, `deposit_amount`, `delivery_price`, `total_real_price`, `storehouse_name`, `status`) VALUES
(1, 1, 1475461188, 1475478709, 1, 'ABC123', 'Ha ha ha', 30000, 10000, 1, 100000, 20000, 20000, 'Quảng Đông 123', 8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE IF NOT EXISTS `tbl_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `params` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `is_read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tbl_notification`
--

INSERT INTO `tbl_notification` (`id`, `active`, `created_time`, `updated_time`, `user_id`, `type`, `params`, `is_read`) VALUES
(1, 1, 1475999278, 1476002105, 1, 2, '6:4', 1),
(2, 1, 1477461861, 1478344971, 1, 2, '3:1', 1),
(3, 1, 1477461884, 1478344971, 1, 2, '4:1', 1),
(4, 1, 1477461973, 1478344971, 1, 2, '5:1', 1),
(5, 1, 1477465567, 1478344971, 1, 2, '6:1', 1),
(6, 1, 1477465629, 1478344971, 1, 2, '8:1', 1),
(7, 1, 1477465655, 1478344971, 1, 2, '7:1', 1),
(8, 1, 1477465710, 1478344971, 1, 2, '9:1', 1),
(9, 1, 1478340967, 1478344971, 1, 2, '3:3', 1),
(10, 1, 1478340985, 1478344971, 1, 2, '4:3', 1),
(11, 1, 1478340998, 1478344971, 1, 2, '5:3', 1),
(12, 1, 1478341044, 1478344971, 1, 2, '6:3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE IF NOT EXISTS `tbl_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `total_price` float DEFAULT NULL,
  `total_weight` float DEFAULT NULL,
  `is_paid` int(1) NOT NULL DEFAULT '0',
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deposit_amount` float DEFAULT NULL,
  `total_delivery_price` float DEFAULT NULL,
  `total_real_price` float DEFAULT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_real_price_ndt` float DEFAULT NULL,
  `total_price_ndt` float DEFAULT NULL,
  `total_delivery_price_ndt` float DEFAULT NULL,
  `weight_price` float DEFAULT NULL,
  `service_price` float DEFAULT NULL,
  `final_price` float DEFAULT NULL,
  `remaining_amount` float DEFAULT NULL,
  `exchange_rate` float DEFAULT NULL,
  `shipping_home_price` float DEFAULT NULL,
  `total_weight_price` float DEFAULT NULL,
  `service_price_percentage` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `active`, `created_time`, `updated_time`, `user_id`, `status`, `total_price`, `total_weight`, `is_paid`, `description`, `deposit_amount`, `total_delivery_price`, `total_real_price`, `total_quantity`, `total_real_price_ndt`, `total_price_ndt`, `total_delivery_price_ndt`, `weight_price`, `service_price`, `final_price`, `remaining_amount`, `exchange_rate`, `shipping_home_price`, `total_weight_price`, `service_price_percentage`) VALUES
(1, 1, 1477459869, 1477465710, 1, 9, 529695, 110, 1, NULL, 300000, 74655000, 355500000, 2, 100000, 149, 21000, 15000, 37078.6, 76871800, 76571800, 3555, 0, 1650000, 7),
(2, 1, 1478339352, 1478339352, 1, 2, NULL, NULL, 0, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 1478339819, 1478341044, 1, 6, 1480880000, 0, 0, NULL, 3000000, 735000000, 0, 3, 0, 423110, 210000, 25000, 296177000, 2512060000, 2509060000, 3500, 1000, 0, 20),
(4, 1, 1478342993, 1478342993, 1, 2, NULL, NULL, 0, NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_product`
--

CREATE TABLE IF NOT EXISTS `tbl_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `website_type` int(1) NOT NULL,
  `name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `count` int(11) NOT NULL,
  `vietnamese_name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `web_price` float NOT NULL,
  `ordered_count` int(11) DEFAULT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_delivery_order_id` int(11) DEFAULT NULL,
  `shop_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tbl_order_product`
--

INSERT INTO `tbl_order_product` (`id`, `active`, `created_time`, `updated_time`, `order_id`, `url`, `website_type`, `name`, `price`, `count`, `vietnamese_name`, `image`, `description`, `web_price`, `ordered_count`, `color`, `shop_delivery_order_id`, `shop_id`) VALUES
(1, 1, 1477459869, 1477461673, 1, 'https://detail.1688.com/offer/538683626006.html?spm=a262q.7917246.2226378.1.YzehdS', 3, '【时光旅人】2016冬季新款 韩版加厚面包服短款立领棉马甲女626', 100, 1, '[2016] Thời gian quần áo du lịch mùa đông mới của Hàn Quốc phiên bản của một phần ngắn của bánh dày cổ áo bông vest nữ 626', 'https://cbu01.alicdn.com/img/ibank/2016/152/052/3470250251_1157220614.310x310.jpg', NULL, 83.5, 1, NULL, NULL, NULL),
(2, 1, 1477459869, 1477461652, 1, 'https://detail.1688.com/offer/536693577399.html?spm=a26279.8224918.2326025.4.dKBiAr', 3, '精典泰迪A类亲肤云毯子 母婴床上用品生产厂家批发实体微商代发', 49, 1, 'Cổ điển Teddy Class A đám mây chăn mẹ thân thiện với da giường có nhà sản xuất tóc bán buôn Shang thực thể vi', 'https://cbu01.alicdn.com/img/ibank/2016/560/970/3311079065_745497359.310x310.jpg', NULL, 49, 1, NULL, NULL, NULL),
(3, 1, 1478339352, 1478339352, 2, '//world.taobao.com/item/530762904536.htm', 1, 'SINCE THEN 新品 原创设计 深V领沙滩裙度假短裙 蓝珍珠', 0, 1, 'SINCE thiết kế THEN gốc mới sâu cổ chữ V bãi biển váy Blue Pearl Resort', '//gd3.alicdn.com/bao/uploaded/i3/267096381/TB2TnlgnVXXXXb8XXXXXXXXXXXX_!!267096381.jpg_600x600.jpg', NULL, 199, NULL, NULL, NULL, NULL),
(4, 1, 1478339352, 1478339352, 2, '//world.taobao.com/item/528694290468.htm', 1, '泰国潮牌性感交叉肩带大露背吊带仿真丝缎面背心上衣连衣裙女夏季', 0, 1, 'Thái Lan Tide thương hiệu dây sexy chéo dây đeo dây satin mô phỏng bề mặt nữ mùa hè vest Tops', '//gd4.alicdn.com/bao/uploaded/i4/653659480/TB2XV_ClFXXXXbwXXXXXXXXXXXX_!!653659480.jpg_600x600.jpg', NULL, 68, NULL, NULL, NULL, NULL),
(5, 1, 1478339352, 1478339352, 2, 'https://detail.1688.com/offer/527452293363.html?tracelog=p4p', 3, '运动包尼龙圆桶健身包单肩旅游旅行包行李袋跆拳道圆筒包定做logo', 0, 1, 'Thể dục thể thao nylon túi túi túi túi thùng vai du lịch hành lý taekwondo trụ biểu tượng gói tùy chỉnh', 'https://cbu01.alicdn.com/img/ibank/2016/575/587/3483785575_1308938595.310x310.jpg', NULL, 14, NULL, NULL, NULL, NULL),
(6, 1, 1478339352, 1478339352, 2, '//world.taobao.com/item/529071660188.htm', 1, '度假V领挂脖水溶蕾丝胸衣花边吊带交叉露背打底内衣带胸垫裹胸女', 0, 1, 'dây đai dây Tốt V-cổ qua tan dây ren áo ngực ren đáy nữ đồ lót với một ngực ngực pad bọc', '//gd1.alicdn.com/bao/uploaded/i7/TB1BGrrMXXXXXcNXpXXYXGcGpXX_M2.SS2_600x600.jpg', NULL, 89, NULL, NULL, NULL, NULL),
(7, 1, 1478339819, 1478340694, 3, 'https://detail.1688.com/offer/527452293363.html?tracelog=p4p', 3, '运动包尼龙圆桶健身包单肩旅游旅行包行李袋跆拳道圆筒包定做logo', 20000, 1, 'Thể dục thể thao nylon túi túi túi túi thùng vai du lịch hành lý taekwondo trụ biểu tượng gói tùy chỉnh', 'https://cbu01.alicdn.com/img/ibank/2016/575/587/3483785575_1308938595.310x310.jpg', NULL, 14, 20, NULL, 3, 'shop1385485037404'),
(8, 1, 1478339819, 1478340686, 3, '//world.taobao.com/item/528694290468.htm', 1, '泰国潮牌性感交叉肩带大露背吊带仿真丝缎面背心上衣连衣裙女夏季', 2000, 1, 'Thái Lan Tide thương hiệu dây sexy chéo dây đeo dây satin mô phỏng bề mặt nữ mùa hè vest Tops', '//gd4.alicdn.com/bao/uploaded/i4/653659480/TB2XV_ClFXXXXbwXXXXXXXXXXXX_!!653659480.jpg_600x600.jpg', NULL, 68, 1, NULL, 4, 'shop69287004'),
(9, 1, 1478339819, 1478340673, 3, '//world.taobao.com/item/529071660188.htm', 1, '度假V领挂脖水溶蕾丝胸衣花边吊带交叉露背打底内衣带胸垫裹胸女', 2111, 1, 'dây đai dây Tốt V-cổ qua tan dây ren áo ngực ren đáy nữ đồ lót với một ngực ngực pad bọc', '//gd1.alicdn.com/bao/uploaded/i7/TB1BGrrMXXXXXcNXpXXYXGcGpXX_M2.SS2_600x600.jpg', NULL, 89, 10, NULL, 4, 'shop69287004'),
(10, 1, 1478342993, 1478342993, 4, 'https://detail.1688.com/offer/527452293363.html?tracelog=p4p', 3, '运动包尼龙圆桶健身包单肩旅游旅行包行李袋跆拳道圆筒包定做logo', 0, 5, 'Thể dục thể thao nylon túi túi túi túi thùng vai du lịch hành lý taekwondo trụ biểu tượng gói tùy chỉnh', 'https://cbu01.alicdn.com/img/ibank/2016/967/694/3485496769_1308938595.400x400.jpg', NULL, 14, 5, NULL, 5, 'shop1385485037404'),
(11, 1, 1478342993, 1478342993, 4, '//world.taobao.com/item/528694290468.htm', 1, '泰国潮牌性感交叉肩带大露背吊带仿真丝缎面背心上衣连衣裙女夏季', 0, 1, 'Thái Lan Tide thương hiệu dây sexy chéo dây đeo dây satin mô phỏng bề mặt nữ mùa hè vest Tops', '//gd1.alicdn.com/bao/uploaded/https://img.alicdn.com/imgextra/i2/TB1pURYMXXXXXaPXFXXYXGcGpXX_M2.SS2_600x600.jpg', NULL, 68, 1, NULL, 6, 'shop69287004'),
(12, 1, 1478342993, 1478342993, 4, '//world.taobao.com/item/529071660188.htm', 1, '度假V领挂脖水溶蕾丝胸衣花边吊带交叉露背打底内衣带胸垫裹胸女', 0, 2, 'dây đai dây Tốt V-cổ qua tan dây ren áo ngực ren đáy nữ đồ lót với một ngực ngực pad bọc', 'https://gd4.alicdn.com/bao/uploaded/i4/653659480/TB2zShamXXXXXa9XXXXXXXXXXXX_!!653659480.jpg_600x600.jpg', NULL, 89, 2, NULL, 6, 'shop69287004');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE IF NOT EXISTS `tbl_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_description` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `admin_id` int(11) NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_post`
--

INSERT INTO `tbl_post` (`id`, `active`, `created_time`, `updated_time`, `title`, `slug`, `content`, `meta_keywords`, `meta_description`, `short_description`, `admin_id`, `image`) VALUES
(4, 1, 1474362303, 1474362303, 'test01', 'test01', 'TEST01', NULL, NULL, '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop_delivery_order`
--

CREATE TABLE IF NOT EXISTS `tbl_shop_delivery_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_order_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_price_ndt` float NOT NULL,
  `total_weight` float NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `real_price` float NOT NULL,
  `shop_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_shop_delivery_order`
--

INSERT INTO `tbl_shop_delivery_order` (`id`, `active`, `created_time`, `updated_time`, `order_id`, `delivery_order_code`, `shop_name`, `delivery_price_ndt`, `total_weight`, `status`, `real_price`, `shop_id`) VALUES
(1, 1, 1477460670, 1477465655, 1, NULL, 'ABC', 1000, 10, 4, 80000, NULL),
(2, 1, 1477460686, 1477465653, 1, NULL, 'DEF', 20000, 100, 4, 20000, NULL),
(3, 1, 1478339819, 1478341044, 3, 'asdf', 'shop1385485037404', 10000, 0, 2, 0, 'shop1385485037404'),
(4, 1, 1478339819, 1478341026, 3, 'ABC123', 'shop69287004', 200000, 0, 4, 0, 'shop69287004'),
(5, 1, 1478342993, 1478342993, 4, NULL, 'shop1385485037404', 0, 0, 1, 0, 'shop1385485037404'),
(6, 1, 1478342993, 1478342993, 4, NULL, 'shop69287004', 0, 0, 1, 0, 'shop69287004');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '0',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf32_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `is_email_active` int(1) NOT NULL DEFAULT '0',
  `email_active_token` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `google_id` int(11) DEFAULT NULL,
  `google_token` varchar(3000) COLLATE utf32_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `image_thumbnail` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  `facebook_access_token` varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
  `collaborator_group_id` int(11) DEFAULT NULL,
  `skype` varchar(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `active`, `created_time`, `updated_time`, `name`, `email`, `password`, `is_email_active`, `email_active_token`, `google_id`, `google_token`, `phone`, `image`, `image_thumbnail`, `facebook_id`, `facebook_access_token`, `collaborator_group_id`, `skype`) VALUES
(1, 1, 1475110997, 1475569920, 'Tung Nguyen', 'thanhtung9630@gmail.com', '$2a$13$5qqb2.axgDch6bDqTmWtxuzZqWj5h0CWS.M.mYxtx.mEOPBS0/QK6', 1, NULL, NULL, NULL, '0975289682', '/upload/user/1/image_1475110998.png', '/upload/user/1/image_thumbnail_1475110997.png', '1425504907465775', 'EAAaDkogvZAK0BAN34ZCOiXpn0buVHCxHikCjnrEyfpbCJiqc91BlMigEanzNE3rvjKLnKkLq9j0ZAAjMlLG4ScPHH2G0jMma63V6pnhLJTbIxewZCIz5KgcLZA0ibykHPx0J3hw6qBjEBuwyge9KljdMwYupod50ZD', 1, 'thanhtung9630');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
