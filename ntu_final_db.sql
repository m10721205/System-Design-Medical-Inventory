-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 2018-01-28 16:15:33
-- 伺服器版本: 10.1.21-MariaDB
-- PHP 版本： 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `ntu`
--

-- --------------------------------------------------------

--
-- 資料表結構 `check_record`
--

CREATE TABLE `check_record` (
  `id` int(10) NOT NULL COMMENT '盤點紀錄ID',
  `department_id` varchar(5) NOT NULL COMMENT '部門代碼',
  `material_code` int(10) UNSIGNED NOT NULL COMMENT '醫材碼',
  `ref_num` int(5) UNSIGNED NOT NULL COMMENT '規格碼',
  `actual_quantity` int(4) UNSIGNED NOT NULL COMMENT '實際數量',
  `in_order_quantity` int(4) UNSIGNED NOT NULL COMMENT '請購中數量',
  `using_quantity` int(4) UNSIGNED NOT NULL COMMENT '使用中',
  `difference_amount` int(4) NOT NULL COMMENT '差異量',
  `time` datetime NOT NULL COMMENT '時戳',
  `employee_id` varchar(10) NOT NULL COMMENT '員工代碼',
  `remarks` varchar(50) NOT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `department`
--

CREATE TABLE `department` (
  `id` int(11) UNSIGNED NOT NULL,
  `department_id` varchar(10) NOT NULL COMMENT '部門代碼',
  `department_name` varchar(30) NOT NULL COMMENT '部門名稱',
  `extension` varchar(10) NOT NULL COMMENT '分機',
  `username` varchar(50) NOT NULL COMMENT '管理人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 資料表的匯出資料 `department`
--

INSERT INTO `department` (`id`, `department_id`, `department_name`, `extension`, `username`) VALUES
(3, '13A', '13æ¨“Aç—…æˆ¿', '5102', 'admin'),
(5, '13B', '13æ¨“Bç—…æˆ¿', '5103', 'Admin'),
(6, '13C', 'æ¸¬è©¦ç”¨ç—…æˆ¿', '5100', 'admin'),
(7, '13D', 'æ¸¬è©¦ç”¨ç—…æˆ¿2', '5101', 'admin');

-- --------------------------------------------------------

--
-- 資料表結構 `input_stock_recode`
--

CREATE TABLE `input_stock_recode` (
  `barcode` varchar(30) NOT NULL COMMENT '條碼',
  `mdp_id` int(10) UNSIGNED NOT NULL COMMENT '外鍵',
  `lot_num` varchar(10) NOT NULL COMMENT '批號',
  `in_quantity` int(4) NOT NULL COMMENT '入庫量',
  `expiry_date` date NOT NULL COMMENT '到期日',
  `in_unit` varchar(5) NOT NULL COMMENT '入庫單位',
  `employee_id` varchar(10) NOT NULL COMMENT '員工代碼',
  `in_time` datetime NOT NULL COMMENT '時戳',
  `remarks` varchar(50) NOT NULL COMMENT '備註',
  `category` varchar(20) NOT NULL COMMENT '類別'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `input_stock_recode`
--

INSERT INTO `input_stock_recode` (`barcode`, `mdp_id`, `lot_num`, `in_quantity`, `expiry_date`, `in_unit`, `employee_id`, `in_time`, `remarks`, `category`) VALUES
('13A170920213401', 2, '11111', 200, '2018-10-11', '', 'admin', '2017-09-20 21:34:01', '', ''),
('13A170921223401', 2, '22222', 100, '2018-10-11', '', 'admin', '2017-09-21 21:34:01', '', ''),
('13C170920213400', 3, '11111', 200, '2018-09-20', '', 'admin', '2017-09-20 21:34:00', '', ''),
('13C170921213400', 3, '22222', 100, '2018-09-21', '', 'admin', '2017-09-21 21:34:00', '', ''),
('13C171003000818', 3, '22222', 100, '2017-10-03', 'ç›’', 'admin', '2017-10-02 00:00:00', '123', '111');

-- --------------------------------------------------------

--
-- 資料表結構 `material_basic`
--

CREATE TABLE `material_basic` (
  `id` int(11) UNSIGNED NOT NULL,
  `material_code` int(10) UNSIGNED NOT NULL COMMENT '醫材碼',
  `ref_code` int(10) UNSIGNED NOT NULL,
  `material_name` varchar(50) NOT NULL COMMENT '醫材名',
  `material_format` varchar(255) NOT NULL COMMENT '規格',
  `buy_price` int(25) UNSIGNED NOT NULL COMMENT '成本',
  `media_id` int(11) DEFAULT '0' COMMENT '圖片',
  `special` int(1) UNSIGNED NOT NULL COMMENT '特殊醫材(一對多)',
  `use_people_times` int(2) UNSIGNED DEFAULT NULL COMMENT '使用人次上限',
  `use_times` int(2) UNSIGNED DEFAULT NULL COMMENT '使用次數上限',
  `employee_id` varchar(10) NOT NULL COMMENT '新增人',
  `date` date DEFAULT NULL,
  `employee_id2` varchar(10) DEFAULT NULL COMMENT '異動人',
  `date2` date DEFAULT NULL COMMENT '異動日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 資料表的匯出資料 `material_basic`
--

INSERT INTO `material_basic` (`id`, `material_code`, `ref_code`, `material_name`, `material_format`, `buy_price`, `media_id`, `special`, `use_people_times`, `use_times`, `employee_id`, `date`, `employee_id2`, `date2`) VALUES
(1, 20220027, 21354, '(æ©˜)5mm#4216', '5mm\'2mm', 1500, 2, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(2, 20540810, 15345, 'æŽ¢é‡', '12mm\'5mm', 3400, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(3, 20465305, 57242, 'Endo Bag#054100', '15mm\'1mm', 2000, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(4, 20301653, 57254, '5-0MONOCRYL,#Y303H', '12cm\'1mm', 288, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(5, 20331202, 72452, 'CEEA25mm', '26mm\'3mm', 4400, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(6, 20331201, 24535, 'CCEA28mm', '28cm\'1cm', 4400, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(7, 20331200, 85435, 'CEEA31mm', '31mm\'13mm', 4200, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(8, 20325314, 24535, 'EndoClip', '9cm\'1cm', 2590, 0, 1, 5, 15, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(9, 20305001, 89545, 'EndoLoop', '8mm\'2mm', 750, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(10, 20304503, 12856, 'EndoGIAæ§', '15cm\'3cm', 8900, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(11, 20304505, 76522, 'PPH03', '26mm\'3mm', 12110, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(12, 20362826, 95678, 'TycoMesh', '12mm\'5mm', 1050, 0, 0, 1, 1, 'admin', '2017-08-15', 'admin', '2017-09-20'),
(13, 20362800, 35235, 'æŸéƒŽMesh', '5mm\'2mm', 1200, 0, 0, 1, 1, 'admin', '2017-08-18', 'admin', '2017-09-20'),
(14, 20304100, 76545, 'ä¸å¯å¸æ”¶PROTACK', '15cm\'3cm', 9500, 0, 1, 6, 30, 'admin', '2017-08-23', 'admin', '2017-09-20'),
(20, 20265009, 75857, 'æ°£è…¹é‡', '15mm\'1mm', 750, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(21, 20437224, 85462, 'é”å‹w\'d potractor(XS)', '15mm\'1mm', 1720, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(22, 20220065, 73545, 'é”å‹w\'d potractor(S)', '14mm\'7mm', 2480, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(23, 20437241, 54684, 'å°šæˆˆHand port(XS)', '12mm\'5mm', 1550, 1, 0, 1, 1, 'admin', '2017-09-20', 'admin', '2017-09-20'),
(25, 20400225, 86453, 'Ligasure 5mm LF1637', '12mm\'5mm', 29000, 1, 1, 6, 6, 'admin', '2017-09-20', 'admin', '2017-09-20'),
(26, 20312307, 51875, 'Ligasure LF1212', '5mm\'2mm', 16000, 1, 1, 4, 4, 'admin', '2017-09-20', NULL, NULL),
(27, 20325336, 86765, 'Hem-o-loké‡‘', '15cm\'3cm', 600, 1, 0, 1, 1, 'admin', '2017-09-20', 'admin', '2017-09-20'),
(28, 20325335, 13584, 'Hem-o-lokç¶ ', '15cm\'3cm', 250, 1, 0, 1, 1, 'admin', '2017-09-20', 'admin', '2017-09-20'),
(30, 20509402, 97654, 'Cusaæ²–å¸ç®¡', '12mm\'5mm', 3500, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(31, 20400218, 37535, 'Cusaé›»ç‡’é ­', '5mm\'2mm', 11000, 1, 1, 6, 6, 'admin', '2017-09-20', NULL, NULL),
(32, 20400219, 97654, 'Cusaé›»ç‡’é ­', '13mm\'5mm', 14000, 1, 1, 6, 6, 'admin', '2017-09-20', NULL, NULL),
(33, 20300943, 4561, 'V-Loc 180', '13mm\'5mm', 1235, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(34, 20300942, 123546, 'V-Loc 90', '18mm\'5mm', 1240, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(35, 20473800, 6123, 'é©é€è†œè† ', '17mm\'2mm', 310, 2, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(36, 20008504, 23135, 'é–‹å£ä¾¿å¸¶', '10mm\'3mm', 30, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(37, 20220022, 5468, 'å°šæˆˆ10mmå¤–ç®¡', '18mm\'5mm', 680, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(38, 20220015, 54612, 'OMST12BT', '11mm\'1mm', 2500, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(39, 20011200, 125468, 'T-Tube24', '19mm\'6mm', 170, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(40, 20224302, 6542313, 'çŸ½è³ªèƒƒé€ å»”ç®¡24Fr', '2mm\'5mm', 1390, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(41, 20304200, 1654123, 'GIA60-2.5mmç™½é‡˜', '2mm\'2.5mm', 5100, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(42, 20304212, 645165, 'GIA60-38Sæ§+é‡˜', '12mm\'3.8mm', 2100, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(43, 20304211, 135478, 'GIA60-38é‡˜', '12mm\'3.8mm', 2000, 1, 0, 1, 1, 'admin', '2017-09-20', NULL, NULL),
(44, 20220027, 11354, '(æ©˜)5mm#4216', '5mm\'2mm', 1500, 2, 0, 1, 1, 'admin', '2017-10-23', NULL, NULL),
(45, 20220027, 34354, '(æ©˜)5mm#4216', '5mm\'2mm', 1580, 2, 0, 1, 1, 'admin', '2017-10-23', 'admin', '2017-10-23');

-- --------------------------------------------------------

--
-- 資料表結構 `material_deptbasic`
--

CREATE TABLE `material_deptbasic` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` varchar(10) NOT NULL COMMENT '部門代碼',
  `material_code` int(10) UNSIGNED NOT NULL COMMENT '醫材碼',
  `ref_code` int(10) UNSIGNED NOT NULL COMMENT 'REF碼',
  `material_dpname` varchar(30) NOT NULL COMMENT '醫材品項',
  `in_unit` varchar(5) NOT NULL COMMENT '入庫單位',
  `unit_quantity` int(3) UNSIGNED NOT NULL COMMENT '庫存轉換數量',
  `out_unit` varchar(5) NOT NULL COMMENT '出庫單位',
  `safety_stock` int(4) UNSIGNED NOT NULL COMMENT '安全存量',
  `highest_stock` int(3) UNSIGNED NOT NULL COMMENT '最高存量',
  `stock_location` varchar(20) NOT NULL COMMENT '存放位置',
  `employee_id` varchar(10) NOT NULL COMMENT '新增人',
  `date` date NOT NULL COMMENT '新增日期',
  `employee_id2` varchar(10) DEFAULT NULL COMMENT '異動人',
  `date2` date DEFAULT NULL COMMENT '異動日',
  `media_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `material_deptbasic`
--

INSERT INTO `material_deptbasic` (`id`, `department_id`, `material_code`, `ref_code`, `material_dpname`, `in_unit`, `unit_quantity`, `out_unit`, `safety_stock`, `highest_stock`, `stock_location`, `employee_id`, `date`, `employee_id2`, `date2`, `media_id`) VALUES
(2, '13A', 20331202, 72452, 'CEEA25mmm', 'ç›’', 2, 'å€‹', 5, 15, 'å€‰åº«Aæž¶', 'admin', '2017-08-23', 'admin', '2017-08-23', 0),
(3, '13C', 20220027, 21354, '(æ©˜)5mm#4216', 'ç›’', 10, 'å€‹', 30, 60, '12EE', 'admin', '2017-09-18', NULL, NULL, 0),
(5, '13A', 20331202, 72452, '(æ©˜)5mm#4216', 'ç›’', 10, 'å€‹', 30, 60, '12EE', 'admin', '2017-09-18', NULL, NULL, 0),
(6, '13A', 20362800, 35235, '(æ©˜)5mm#4216', 'ç›’', 10, 'å€‹', 30, 60, '12EE', 'admin', '2017-09-18', NULL, NULL, 0),
(7, '13C', 20540810, 15345, 'æŽ¢é‡', 'ç›’', 10, 'å€‹', 150, 200, '13EA', 'admin', '2017-10-20', NULL, NULL, 0),
(8, '13C', 20304503, 12856, 'EndoGIAæ§', 'ç›’', 2, 'å€‹', 20, 25, '13EZ', 'admin', '2017-10-21', NULL, NULL, 0),
(9, '13C', 20220027, 11354, '(æ©˜)5mm#4216', 'ç›’', 2, 'å€‹', 100, 120, '13CEE', 'admin', '2017-10-23', NULL, NULL, 0),
(10, '13C', 20220027, 34354, '(æ©˜)5mm#4216', 'ç›’', 2, 'å€‹', 150, 170, '13CEA', 'admin', '2017-10-23', NULL, NULL, 0),
(11, '13C', 20331200, 85435, 'CEEA31mm', 'ç›’', 3, 'å€‹', 100, 150, '13CA', 'admin', '2017-10-23', NULL, NULL, 0),
(12, '13C', 20362826, 95678, 'TycoMesh', 'åŒ…', 10, 'ç‰‡', 200, 250, '13CAE', 'admin', '2017-10-23', NULL, NULL, 0),
(13, '13C', 20304100, 76545, 'ä¸å¯å¸æ”¶PROTACK', 'ç›’', 2, 'å€‹', 100, 120, '13CEQ', 'admin', '2017-10-23', NULL, NULL, 0),
(14, '13C', 20362826, 95678, 'TycoMesh', 'ç›’', 3, 'å€‹', 100, 20, '13QWE', 'admin', '2017-10-23', NULL, NULL, 0),
(15, '13C', 20362800, 35235, 'æŸéƒŽMesh', 'å€‹', 1, 'å€‹', 5, 8, '13CA', 'admin', '2017-10-23', NULL, NULL, 0),
(16, '13C', 20304505, 76522, 'PPH03', 'å€‹', 1, 'å€‹', 2, 4, '13CA1', 'admin', '2017-10-23', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(1, '11181391_837235096368095_1732280683_o1.jpg', 'image/jpeg'),
(2, '.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- 資料表結構 `otm_material_recode`
--

CREATE TABLE `otm_material_recode` (
  `barcode` varchar(20) NOT NULL COMMENT '條碼',
  `use_times` int(2) UNSIGNED NOT NULL COMMENT '使用次數',
  `time` datetime NOT NULL COMMENT '時戳',
  `employee_id` varchar(10) NOT NULL COMMENT '出庫人',
  `department_id` varchar(5) NOT NULL COMMENT '部門代碼',
  `otm_condition` varchar(10) NOT NULL COMMENT '狀態',
  `remarks` varchar(50) NOT NULL COMMENT '備註',
  `patient_history` varchar(20) NOT NULL COMMENT '病人帳號'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `output_stock_recode`
--

CREATE TABLE `output_stock_recode` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '流水號',
  `barcode` varchar(30) NOT NULL COMMENT '條碼',
  `out_quantity` int(4) UNSIGNED NOT NULL COMMENT '出庫量',
  `category` varchar(20) NOT NULL COMMENT '類別',
  `patient_history` varchar(20) NOT NULL COMMENT '病人帳號',
  `employee_id` varchar(10) NOT NULL COMMENT '員工代碼',
  `time` datetime NOT NULL COMMENT '出庫時戳',
  `remarks` varchar(50) NOT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `output_stock_recode`
--

INSERT INTO `output_stock_recode` (`id`, `barcode`, `out_quantity`, `category`, `patient_history`, `employee_id`, `time`, `remarks`) VALUES
(6, '13A170920213401', 5, '', '1234567890', 'admin', '2017-10-15 00:00:00', ''),
(7, '13A170920213401', 25, '', '1234567890', 'admin', '2017-10-15 00:00:00', ''),
(8, '13C170920213400', 30, '', '1234567891', 'admin', '2017-10-15 00:00:00', ''),
(9, '13C171003000818', 10, '', '1234567892', 'admin', '2017-10-15 00:00:00', ''),
(10, '13C171003000818', 10, '', '1234567892', 'admin', '2017-10-15 00:00:00', ''),
(11, '13C170920213400', 10, '', '1234567893', 'admin', '2017-10-15 00:00:00', ''),
(12, '13C170920213400', 10, '', '1234567893', 'admin', '2017-10-15 00:00:00', '');

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT '0',
  `date` datetime NOT NULL,
  `real_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `real_name`) VALUES
(1, '21', '999', '0.00', '0.00', 1, 0, '2017-08-09 15:19:06', '20331200666'),
(2, 'CEEA 28mm', '1000999', NULL, '0.00', 1, 0, '2017-08-10 12:11:56', '20305001');

-- --------------------------------------------------------

--
-- 資料表結構 `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `scrap_recode`
--

CREATE TABLE `scrap_recode` (
  `id` int(10) NOT NULL COMMENT '流水碼',
  `barcode` varchar(20) NOT NULL COMMENT '條碼',
  `quantity` int(3) UNSIGNED NOT NULL COMMENT '報廢量',
  `time` datetime NOT NULL COMMENT '報廢時戳',
  `employee_id` varchar(10) NOT NULL COMMENT '員工代碼',
  `remarks` varchar(50) NOT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '員工代碼',
  `name` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '名稱',
  `password` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '密碼',
  `department_id` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '部門代碼',
  `user_level` int(2) NOT NULL COMMENT '權限',
  `image` varchar(255) CHARACTER SET utf8 DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL COMMENT '是否啟用',
  `last_login` datetime DEFAULT NULL COMMENT '上一次登入'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `password`, `department_id`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'admin', ' Admin User', 'd033e22ae348aeb5660fc2140aec35850c4da997', '13C', 1, 'mbqoupt41.jpg', 1, '2017-10-23 14:36:09'),
(2, 'Special', 'Special User', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', '13C', 2, 'no_image.jpg', 1, '2017-10-23 12:11:16'),
(3, 'user', 'Default User', '12dea96fec20593566ab75692c9949596833adc9', '13C', 3, 'no_image.jpg', 1, '2017-10-23 14:31:28');

-- --------------------------------------------------------

--
-- 資料表結構 `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '職稱',
  `group_level` int(2) NOT NULL COMMENT '權限代碼',
  `group_status` int(1) NOT NULL COMMENT '是否啟用'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin(ä¿ç®¡çµ„)', 1, 1),
(2, 'Special(è­·ç†é•·+æ›¸è¨˜)', 2, 1),
(3, 'User(è­·å£«)', 3, 1);

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_id` (`department_id`),
  ADD KEY `username` (`username`);

--
-- 資料表索引 `input_stock_recode`
--
ALTER TABLE `input_stock_recode`
  ADD PRIMARY KEY (`barcode`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `mdp_id` (`mdp_id`);

--
-- 資料表索引 `material_basic`
--
ALTER TABLE `material_basic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_ID` (`employee_id`),
  ADD KEY `employee_ID2` (`employee_id2`);

--
-- 資料表索引 `material_deptbasic`
--
ALTER TABLE `material_deptbasic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_ID` (`employee_id`),
  ADD KEY `employee_ID2` (`employee_id2`),
  ADD KEY `material_code` (`material_code`),
  ADD KEY `department_ID` (`department_id`);

--
-- 資料表索引 `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- 資料表索引 `otm_material_recode`
--
ALTER TABLE `otm_material_recode`
  ADD UNIQUE KEY `department_id` (`department_id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `employee_id` (`employee_id`);

--
-- 資料表索引 `output_stock_recode`
--
ALTER TABLE `output_stock_recode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `employee_id` (`employee_id`);

--
-- 資料表索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- 資料表索引 `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_level` (`user_level`),
  ADD KEY `department_id` (`department_id`);

--
-- 資料表索引 `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用資料表 AUTO_INCREMENT `material_basic`
--
ALTER TABLE `material_basic`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- 使用資料表 AUTO_INCREMENT `material_deptbasic`
--
ALTER TABLE `material_deptbasic`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- 使用資料表 AUTO_INCREMENT `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用資料表 AUTO_INCREMENT `output_stock_recode`
--
ALTER TABLE `output_stock_recode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=13;
--
-- 使用資料表 AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用資料表 AUTO_INCREMENT `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用資料表 AUTO_INCREMENT `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- 資料表的 Constraints `input_stock_recode`
--
ALTER TABLE `input_stock_recode`
  ADD CONSTRAINT `input_stock_recode_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `input_stock_recode_ibfk_3` FOREIGN KEY (`mdp_id`) REFERENCES `material_deptbasic` (`id`);

--
-- 資料表的 Constraints `material_basic`
--
ALTER TABLE `material_basic`
  ADD CONSTRAINT `material_basic_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `material_basic_ibfk_2` FOREIGN KEY (`employee_id2`) REFERENCES `users` (`username`);

--
-- 資料表的 Constraints `material_deptbasic`
--
ALTER TABLE `material_deptbasic`
  ADD CONSTRAINT `material_deptbasic_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `material_deptbasic_ibfk_2` FOREIGN KEY (`employee_id2`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `material_deptbasic_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- 資料表的 Constraints `otm_material_recode`
--
ALTER TABLE `otm_material_recode`
  ADD CONSTRAINT `otm_material_recode_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `otm_material_recode_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `users` (`username`);

--
-- 資料表的 Constraints `output_stock_recode`
--
ALTER TABLE `output_stock_recode`
  ADD CONSTRAINT `output_stock_recode_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `output_stock_recode_ibfk_3` FOREIGN KEY (`barcode`) REFERENCES `input_stock_recode` (`barcode`);

--
-- 資料表的 Constraints `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
