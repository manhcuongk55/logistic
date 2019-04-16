-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2019 at 07:25 AM
-- Server version: 5.5.41
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `active`, `created_time`, `updated_time`, `email`, `password`, `type`, `name`) VALUES
(2, 1, 1479691966, 1527751825, 'vanchuyentrungquoc.hn@gmail.com', '$2a$13$nCjoLpPsp1aDk3LE6pNlb.LL9YJ.LnvnQOufXm8bnG13fk857Y0yy', 1, 'KHIẾU NẠI'),
(3, 1, 1521442750, 1522912866, 'cskhorderhip@gmail.com', '$2a$13$wlyOkhw2JAgwhx/U1lfmiu.HRSwuMSg1GjLlZ8fcCFDLwXDjavhM2', 3, 'CSKH'),
(5, 1, 1537802015, 1537802015, 'tpkdorderhip@gmail.com', '$2a$13$PWQdimaObaSCJl7RRQOjwu9Fc/pCIo5S/Ds0qDbb12/CggMr5VIya', 2, 'TP ORDER');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner`
--

CREATE TABLE `tbl_banner` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `image` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_collaborator`
--

CREATE TABLE `tbl_collaborator` (
  `id` int(11) NOT NULL,
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
  `google_token` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_collaborator_group`
--

CREATE TABLE `tbl_collaborator_group` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_admin_group` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_type`
--

CREATE TABLE `tbl_customer_type` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `service_price_percentage` float NOT NULL,
  `weight_price` float NOT NULL,
  `exchange_rate` float NOT NULL,
  `volume_price` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_delivery_order`
--

CREATE TABLE `tbl_delivery_order` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `product_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `params` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `is_read` int(1) NOT NULL DEFAULT '0',
  `user_type` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
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
  `total_quantity` int(11) NOT NULL DEFAULT '0',
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
  `extra_price` float DEFAULT NULL,
  `real_exchange_rate` float DEFAULT NULL,
  `is_weight_price_fixed` int(1) NOT NULL DEFAULT '0',
  `sms_status` int(11) NOT NULL DEFAULT '0',
  `shop_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_price_ndt` float DEFAULT NULL,
  `real_price` float DEFAULT NULL,
  `shop_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_service_price_fixed` int(1) NOT NULL DEFAULT '0',
  `is_total_weight_price_fixed` int(1) NOT NULL DEFAULT '0',
  `volume_price` float NOT NULL DEFAULT '0',
  `total_volume` float NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_product`
--

CREATE TABLE `tbl_order_product` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website_type` int(1) NOT NULL DEFAULT '4',
  `name` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `count` int(11) NOT NULL,
  `vietnamese_name` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `web_price` float DEFAULT NULL,
  `ordered_count` int(11) DEFAULT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_delivery_order_id` int(11) DEFAULT NULL,
  `shop_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `real_price` float DEFAULT NULL,
  `collaborator_note` varchar(600) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page`
--

CREATE TABLE `tbl_page` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `page_id` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `content` text COLLATE utf32_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `image_thumbnail` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `meta_description` mediumtext COLLATE utf32_unicode_ci,
  `meta_keyword` mediumtext COLLATE utf32_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE `tbl_post` (
  `id` int(11) NOT NULL,
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
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop_delivery_order`
--

CREATE TABLE `tbl_shop_delivery_order` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_order_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `delivery_price_ndt` float DEFAULT NULL,
  `total_weight` float NOT NULL DEFAULT '0',
  `total_volume` float NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `real_price` float DEFAULT NULL,
  `shop_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `china_delivery_time` int(11) DEFAULT NULL,
  `vietnam_delivery_time` int(11) DEFAULT NULL,
  `order_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num_block` float NOT NULL DEFAULT '0',
  `purchase_price` float NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '1',
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unknown_shop_delivery_order`
--

CREATE TABLE `tbl_unknown_shop_delivery_order` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `delivery_order_code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `total_weight` float DEFAULT NULL,
  `total_volume` float DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
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
  `address` varchar(200) COLLATE utf32_unicode_ci DEFAULT NULL,
  `customer_type_id` int(11) DEFAULT NULL,
  `collaborator_id` int(11) DEFAULT NULL,
  `birthday` varchar(45) COLLATE utf32_unicode_ci DEFAULT NULL,
  `warehouse` varchar(200) COLLATE utf32_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_view`
--

CREATE TABLE `tbl_view` (
  `id` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `user_agent` varchar(200) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_collaborator`
--
ALTER TABLE `tbl_collaborator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_collaborator_group`
--
ALTER TABLE `tbl_collaborator_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_type`
--
ALTER TABLE `tbl_customer_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_delivery_order`
--
ALTER TABLE `tbl_delivery_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_time` (`created_time`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `tbl_order_product`
--
ALTER TABLE `tbl_order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shop_delivery_order`
--
ALTER TABLE `tbl_shop_delivery_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_order_code` (`delivery_order_code`);

--
-- Indexes for table `tbl_unknown_shop_delivery_order`
--
ALTER TABLE `tbl_unknown_shop_delivery_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_view`
--
ALTER TABLE `tbl_view`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_collaborator`
--
ALTER TABLE `tbl_collaborator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_collaborator_group`
--
ALTER TABLE `tbl_collaborator_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customer_type`
--
ALTER TABLE `tbl_customer_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_delivery_order`
--
ALTER TABLE `tbl_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_product`
--
ALTER TABLE `tbl_order_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post`
--
ALTER TABLE `tbl_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_shop_delivery_order`
--
ALTER TABLE `tbl_shop_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unknown_shop_delivery_order`
--
ALTER TABLE `tbl_unknown_shop_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_view`
--
ALTER TABLE `tbl_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*21/4/2019 them thuoc tinh order_type vao bang tbl_order de phan biet 3 loai don hang
  Xuan Cuong */
ALTER TABLE `orderhip`.`tbl_order` 
  ADD COLUMN `order_type` VARCHAR(45) NULL AFTER `extra_description`;
COMMIT;