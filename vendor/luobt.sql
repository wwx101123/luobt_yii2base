-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 2017-11-07 11:04:54
-- 服务器版本： 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `D129`
--

-- --------------------------------------------------------

--
-- 表的结构 `ld_parameter`
--

CREATE TABLE `ld_parameter` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `val` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `explain` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hidden` int(2) NOT NULL DEFAULT '0',
  `show_type` int(2) NOT NULL DEFAULT '0',
  `sort_num` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ld_parameter`
--

INSERT INTO `ld_parameter` (`id`, `name`, `val`, `explain`, `hidden`, `show_type`, `sort_num`) VALUES
(1, '会员级别', '一星|二星|三星|四星', '用|分割', 0, 0, 1),
(2, '注册金额', '1000|6000|12000|24000', '￥', 0, 0, 2),
(3, '单数', '1|6|12|24', '单数', 0, 0, 3),
(4, '奖金名称', '1|2|3|4', '用|分割', 0, 0, 0),
(5, '代理级别', '经理|中级经理|高级经理|总监|董事', '用|分割', 0, 0, 0),
(6, '报单费 ', '3', '百分比', 0, 0, 0),
(7, '提现扣税', '6', '百分比', 0, 0, 0),
(8, '提现进入购物积分', '0', '百分比', 0, 0, 0),
(9, '奖励积分进入购物积分', '20', '百分比', 0, 0, 0),
(10, '提现最低额度', '5000', '', 0, 0, 0),
(11, '提现倍数', '100', '的倍数', 0, 0, 0),
(12, '提现最大额度', '5000', '', 0, 0, 0),
(13, '关闭前台', '0', '1为关闭，0为不关闭', 0, 0, 0),
(14, '充值页面提示', '', '', 0, 0, 0),
(15, '级别条件（总业绩）', '50|100|300|500|1000', '单位:万 用|分割', 0, 0, 0),
(16, '级别条件（小区占比）', '30|30|30|30|30', '百分比 用|分割', 0, 0, 0),
(17, '团队新增业绩提成', '1|1.2|2|2.5|2.5', '百分比 用|分割', 0, 0, 0),
(18, '团队提成总封顶', '20000|50000|-|-|-', '-为不封顶 用|分割', 0, 0, 0),
(19, '团队提成日封顶', '-|-|-|-|10000', '-为不封顶 用|分割', 0, 0, 0),
(20, '团队提成加权', '0|0|0|0|2', '百分比 用|分割', 0, 0, 0),
(21, '奖金分流', '70|30', '购物积分|消费积分', 0, 0, 0),
(22, '消费股东前', '500|10000', '排名 用|分割 大于最后一个参数为最后一个区域', 0, 0, 0),
(23, '消费股东封顶', '0-3|0-2_3-3|0-2_3-3', '用|分割，与上面区域对应推荐N人-N倍_N人-N倍', 0, 0, 0),
(24, '消费股东收益奖金分流', '50|50', '现金积分|消费积分 ', 0, 0, 0),
(25, '分享收益', '12|15', '百分比 用|分割', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ld_parameter`
--
ALTER TABLE `ld_parameter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ld_parameter`
--
ALTER TABLE `ld_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;