-- MySQL dump 10.13  Distrib 5.5.21, for Win32 (x86)
--
-- Host: 101.200.215.232    Database: shangcheng
-- ------------------------------------------------------
-- Server version	5.5.44-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Config Name',
  `value` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Config Values',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` double DEFAULT NULL,
  `prefix` text COLLATE utf8_unicode_ci,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_log_level` (`level`),
  KEY `idx_log_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_address`
--

DROP TABLE IF EXISTS `sc_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地址编号',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '联系手机号',
  `user_name` varchar(64) NOT NULL DEFAULT '' COMMENT '收件人',
  `province` varchar(32) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(32) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(32) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `is_default_address` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是默认地址 1为是默认地址，2否',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_default_address` (`is_default_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_address`
--

LOCK TABLES `sc_address` WRITE;
/*!40000 ALTER TABLE `sc_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_auth`
--

DROP TABLE IF EXISTS `sc_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '权限名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `class_name` varchar(32) NOT NULL DEFAULT '' COMMENT '类名',
  `method_name` varchar(32) NOT NULL DEFAULT '' COMMENT '方法名',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_auth`
--

LOCK TABLES `sc_auth` WRITE;
/*!40000 ALTER TABLE `sc_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_banner`
--

DROP TABLE IF EXISTS `sc_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'banner类型',
  `image_url` varchar(1024) NOT NULL DEFAULT '' COMMENT 'banner路径',
  `href` varchar(1024) NOT NULL DEFAULT '' COMMENT 'banner链接',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_banner`
--

LOCK TABLES `sc_banner` WRITE;
/*!40000 ALTER TABLE `sc_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_brand`
--

DROP TABLE IF EXISTS `sc_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_brand`
--

LOCK TABLES `sc_brand` WRITE;
/*!40000 ALTER TABLE `sc_brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_cart`
--

DROP TABLE IF EXISTS `sc_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `product_spec` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格',
  `product_spec_info` varchar(256) NOT NULL DEFAULT '' COMMENT '商品规格值',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_cart`
--

LOCK TABLES `sc_cart` WRITE;
/*!40000 ALTER TABLE `sc_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_category`
--

DROP TABLE IF EXISTS `sc_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '分类icon',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_category`
--

LOCK TABLES `sc_category` WRITE;
/*!40000 ALTER TABLE `sc_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_collect`
--

DROP TABLE IF EXISTS `sc_collect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收藏类型',
  `date_id` int(11) NOT NULL DEFAULT '0' COMMENT '数据ID',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `date_id` (`date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_collect`
--

LOCK TABLES `sc_collect` WRITE;
/*!40000 ALTER TABLE `sc_collect` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_collect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_comment`
--

DROP TABLE IF EXISTS `sc_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `point` varchar(4) NOT NULL DEFAULT '' COMMENT '评分',
  `content` text COMMENT '评论内容',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_comment`
--

LOCK TABLES `sc_comment` WRITE;
/*!40000 ALTER TABLE `sc_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_express`
--

DROP TABLE IF EXISTS `sc_express`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `area` text NOT NULL COMMENT '配送范围',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_express`
--

LOCK TABLES `sc_express` WRITE;
/*!40000 ALTER TABLE `sc_express` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_express` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_message`
--

DROP TABLE IF EXISTS `sc_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息类型',
  `content` text COMMENT '消息内容',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_message`
--

LOCK TABLES `sc_message` WRITE;
/*!40000 ALTER TABLE `sc_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_order`
--

DROP TABLE IF EXISTS `sc_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '下单会员ID',
  `order_code` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `product_id` varchar(256) NOT NULL DEFAULT '' COMMENT '商品ID',
  `order_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总额',
  `allcount` int(11) NOT NULL DEFAULT '0' COMMENT '商品总数',
  `pay_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付款项',
  `express_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '快递费用',
  `pay_type` smallint(1) NOT NULL DEFAULT '0' COMMENT '支付方式 1:微信 2:积分 3:支付宝 4:银行卡',
  `is_groupon` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否用券 0:不用 1:使用',
  `groupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `ticket_value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `is_redpacket` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否用红包 0:不用 1:使用',
  `redpacket_value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `redpacket_id` varchar(20) NOT NULL DEFAULT '' COMMENT '使用的红包id串',
  `order_status` smallint(2) NOT NULL DEFAULT '0' COMMENT '订单状态 0待支付，1待收货，2待评价，3已评价完成，4已取消，5申请退货，6.交易关闭,7.已支付,未发货，8.已支付，部分发货 9,部分收货',
  `recv_mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `recv_name` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `recv_address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `market_prompt` varchar(255) NOT NULL DEFAULT '' COMMENT '活动提示',
  `order_memo` text COMMENT '订单备注',
  `is_invoice` int(11) NOT NULL DEFAULT '0' COMMENT '是否需要发票 0:不需要 1:需要',
  `invoice_type` int(11) NOT NULL DEFAULT '0' COMMENT '发票类型 1:电子发票 2:纸质发票 3:增值税专用发票',
  `invoice_title` varchar(80) NOT NULL DEFAULT '' COMMENT '发票抬头',
  `aftersale_status` smallint(1) NOT NULL DEFAULT '0' COMMENT '售后状态 0:无售后 1:退货/退款 2:换货',
  `return_code` varchar(255) NOT NULL DEFAULT '' COMMENT '支付返回代码',
  `attach` varchar(255) NOT NULL DEFAULT '' COMMENT '支付附加信息',
  `transaction_id` varchar(255) NOT NULL DEFAULT '' COMMENT '交易id',
  `pay_datetime` datetime DEFAULT NULL COMMENT '支付时间',
  `recv_datetime` datetime DEFAULT NULL COMMENT '确认收货时间',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  `create_time` datetime NOT NULL COMMENT '下单时间',
  PRIMARY KEY (`id`),
  KEY `order_code` (`order_code`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_order`
--

LOCK TABLES `sc_order` WRITE;
/*!40000 ALTER TABLE `sc_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_order_product`
--

DROP TABLE IF EXISTS `sc_order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '默认ID',
  `order_id` varchar(20) NOT NULL DEFAULT '' COMMENT '订单ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `product_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `wide_imgsrc` varchar(1024) NOT NULL DEFAULT '' COMMENT '商品图片',
  `machine_code` varchar(40) NOT NULL DEFAULT '' COMMENT '机种名',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `real_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '修改订单金额功能（有权限的人可改）',
  `express_cost` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '快递费用',
  `real_price_sum` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '商品金额合计',
  `product_spec` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格',
  `product_spec_info` varchar(1024) NOT NULL DEFAULT '' COMMENT '规格值',
  `is_after` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已申请售后 1:申请',
  `product_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发货0未发货1部分发货2 全部发货',
  `receive_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否收货0未收货1收货',
  `is_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评论0未评论1已评论',
  `order_time` datetime NOT NULL COMMENT '订单时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_status` (`product_status`),
  KEY `product_spec` (`product_spec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_order_product`
--

LOCK TABLES `sc_order_product` WRITE;
/*!40000 ALTER TABLE `sc_order_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_order_refound`
--

DROP TABLE IF EXISTS `sc_order_refound`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_order_refound` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` varchar(16) NOT NULL DEFAULT '' COMMENT '服务类型',
  `order_code` varchar(32) NOT NULL DEFAULT '' COMMENT '所属订单编号',
  `server_code` varchar(32) NOT NULL DEFAULT '' COMMENT '退单服务单号',
  `product_name` varchar(1024) NOT NULL DEFAULT '' COMMENT '退单商品名称',
  `product_image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `product_spec` varchar(32) NOT NULL DEFAULT '' COMMENT '商品规格',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `question` varchar(32) NOT NULL DEFAULT '' COMMENT '退货问题',
  `introduction` text COMMENT '问题描述',
  `images` text COMMENT '图片',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否同意1:同意，2:拒绝',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_order_refound`
--

LOCK TABLES `sc_order_refound` WRITE;
/*!40000 ALTER TABLE `sc_order_refound` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_order_refound` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_order_refound_subsidiary`
--

DROP TABLE IF EXISTS `sc_order_refound_subsidiary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_order_refound_subsidiary` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `r_id` int(11) NOT NULL DEFAULT '0' COMMENT '退单表ID',
  `refound_address` varchar(1024) NOT NULL DEFAULT '' COMMENT '回寄地址',
  `express_company` varchar(32) NOT NULL DEFAULT '' COMMENT '退单物流公司',
  `express_code` varchar(32) NOT NULL DEFAULT '' COMMENT '物流单号',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `r_id` (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_order_refound_subsidiary`
--

LOCK TABLES `sc_order_refound_subsidiary` WRITE;
/*!40000 ALTER TABLE `sc_order_refound_subsidiary` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_order_refound_subsidiary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_product`
--

DROP TABLE IF EXISTS `sc_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(1024) NOT NULL DEFAULT '' COMMENT '商品名称',
  `title` varchar(1024) NOT NULL DEFAULT '' COMMENT '简介',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `brand_id` int(11) NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `introduction` text COMMENT '商品详情',
  `images` varchar(1024) NOT NULL DEFAULT '' COMMENT '商品图片集',
  `show_images` varchar(128) NOT NULL DEFAULT '' COMMENT '商品展示图片',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品状态:1:上架，2:下架',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐1:推荐',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否上新1:上新',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `creat_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_product`
--

LOCK TABLES `sc_product` WRITE;
/*!40000 ALTER TABLE `sc_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_product_sku`
--

DROP TABLE IF EXISTS `sc_product_sku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_product_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `sku_attr_value` varchar(255) NOT NULL DEFAULT '' COMMENT '规格属性',
  `spec_id` varchar(255) NOT NULL DEFAULT '' COMMENT '规格id组',
  `sku_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性价格',
  `sku_stock` int(11) NOT NULL DEFAULT '0' COMMENT '属性库存',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_product_sku`
--

LOCK TABLES `sc_product_sku` WRITE;
/*!40000 ALTER TABLE `sc_product_sku` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_product_sku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_product_spec`
--

DROP TABLE IF EXISTS `sc_product_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_product_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '商品规格键名',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序号',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_product_spec`
--

LOCK TABLES `sc_product_spec` WRITE;
/*!40000 ALTER TABLE `sc_product_spec` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_product_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_product_spec_info`
--

DROP TABLE IF EXISTS `sc_product_spec_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_product_spec_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格键名id',
  `info_title` varchar(40) NOT NULL DEFAULT '' COMMENT '商品规格键值',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `spec_id` (`spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_product_spec_info`
--

LOCK TABLES `sc_product_spec_info` WRITE;
/*!40000 ALTER TABLE `sc_product_spec_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_product_spec_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_role`
--

DROP TABLE IF EXISTS `sc_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `desc` text COMMENT '描述',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_role`
--

LOCK TABLES `sc_role` WRITE;
/*!40000 ALTER TABLE `sc_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_role_auth`
--

DROP TABLE IF EXISTS `sc_role_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_role_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `auth_id` text COMMENT '权限列表',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_role_auth`
--

LOCK TABLES `sc_role_auth` WRITE;
/*!40000 ALTER TABLE `sc_role_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_role_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_user`
--

DROP TABLE IF EXISTS `sc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `wechat_id` varchar(28) NOT NULL DEFAULT '' COMMENT 'wechat_id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `truename` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '城市',
  `area` varchar(64) NOT NULL DEFAULT '' COMMENT '区域',
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '详细地址',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别',
  `idCode` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `old` varchar(6) NOT NULL DEFAULT '' COMMENT '年龄',
  `headimgurl` varchar(1024) NOT NULL DEFAULT '' COMMENT '头像URL地址',
  `user_tag` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `tag` varchar(256) NOT NULL DEFAULT '' COMMENT '会员标签',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `lasttime` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `m_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `token` varchar(32) NOT NULL DEFAULT '' COMMENT '商户token',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `remark` varchar(1024) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`uid`),
  KEY `index` (`uid`,`username`,`mobile`,`create_time`) USING BTREE,
  KEY `m_id` (`m_id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_user`
--

LOCK TABLES `sc_user` WRITE;
/*!40000 ALTER TABLE `sc_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_user_admin`
--

DROP TABLE IF EXISTS `sc_user_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_user_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL COMMENT '自动登录key',
  `password_hash` varchar(255) NOT NULL COMMENT '加密密码',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT '重置密码token',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10' COMMENT '角色等级',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_user_admin`
--

LOCK TABLES `sc_user_admin` WRITE;
/*!40000 ALTER TABLE `sc_user_admin` DISABLE KEYS */;
INSERT INTO `sc_user_admin` VALUES (1,'admin','ZpmNr7YQZ3stKI6AsTMGUgatdp_ZX2JV','$2y$13$NEjNQ5pk9QzHEmzVug1BQOkoqyXHhY.u2JOmkafSJzjSsxrRN61Oy',NULL,'admin@163.com',10,10,1495083487,1495083487);
/*!40000 ALTER TABLE `sc_user_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_user_suggestion`
--

DROP TABLE IF EXISTS `sc_user_suggestion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_user_suggestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '意见反馈内容',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_user_suggestion`
--

LOCK TABLES `sc_user_suggestion` WRITE;
/*!40000 ALTER TABLE `sc_user_suggestion` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_user_suggestion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_wxinfo`
--

DROP TABLE IF EXISTS `sc_wxinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_wxinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `m_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `wxname` varchar(128) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `winxintype` smallint(2) NOT NULL DEFAULT '0' COMMENT '公众号类型',
  `appid` varchar(64) NOT NULL DEFAULT '' COMMENT 'APPID',
  `appsecret` varchar(64) NOT NULL DEFAULT '' COMMENT 'APPSECRET',
  `wxid` varchar(32) NOT NULL DEFAULT '' COMMENT '公众号原始ID',
  `weixin` varchar(64) NOT NULL DEFAULT '' COMMENT '微信号',
  `headerpic` varchar(256) NOT NULL DEFAULT '' COMMENT '头像地址',
  `secret_type` smallint(2) NOT NULL DEFAULT '0' COMMENT '消息加密方式',
  `encodingaes_key` varchar(256) NOT NULL DEFAULT '' COMMENT '消息加密秘钥',
  `weiyingjia_token` varchar(256) NOT NULL DEFAULT '' COMMENT '微信后台token',
  `token` varchar(256) NOT NULL DEFAULT '' COMMENT 'token',
  `tpltypeid` varchar(16) NOT NULL DEFAULT '1' COMMENT '默认首页模版ID',
  `tpltypename` varchar(32) NOT NULL DEFAULT '' COMMENT '首页模版名',
  `tpllistid` varchar(4) NOT NULL DEFAULT '' COMMENT '列表模版ID',
  `tpllistname` varchar(32) NOT NULL DEFAULT '' COMMENT '列表模版名',
  `tplcontentid` varchar(2) NOT NULL DEFAULT '' COMMENT '内容模版ID',
  `tplcontentname` varchar(32) NOT NULL DEFAULT '' COMMENT '内容模版名',
  `oauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否授权登录',
  `oauthinfo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方式',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `createtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `m_id_weixin` (`m_id`,`weixin`),
  KEY `m_id` (`m_id`),
  KEY `token` (`token`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_wxinfo`
--

LOCK TABLES `sc_wxinfo` WRITE;
/*!40000 ALTER TABLE `sc_wxinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_wxinfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-05-22 13:44:45
