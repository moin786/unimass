/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 5.6.41-84.1 : Database - xactidea_btillcdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `status` smallint(2) DEFAULT '1' COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(11) DEFAULT NULL,
  `iso` char(6) DEFAULT NULL,
  `name` varchar(240) DEFAULT NULL,
  `nicename` varchar(240) DEFAULT NULL,
  `iso3` char(9) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` int(11) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `proc_code` */

DROP TABLE IF EXISTS `proc_code`;

CREATE TABLE `proc_code` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `script` text COLLATE utf8_unicode_ci,
  `tname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=587 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `profiles` */

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` datetime DEFAULT NULL,
  `city_id` int(10) unsigned DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `s_company` */

DROP TABLE IF EXISTS `s_company`;

CREATE TABLE `s_company` (
  `c_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `c_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_pk_no` bigint(20) DEFAULT NULL,
  `c_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_logo` varchar(201) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_slogan` varchar(202) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr1` varchar(203) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr2` varchar(204) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr3` varchar(205) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_city` varchar(206) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_phone1` varchar(207) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_phone2` varchar(208) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_contact_person` varchar(209) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`c_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `s_groupcomp` */

DROP TABLE IF EXISTS `s_groupcomp`;

CREATE TABLE `s_groupcomp` (
  `gc_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `gc_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_logo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_slogan` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr3` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_contact_person` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`gc_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `s_lookdata` */

DROP TABLE IF EXISTS `s_lookdata`;

CREATE TABLE `s_lookdata` (
  `lookup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lookup_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_row_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lookup_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `s_projectwiseflatlist` */

DROP TABLE IF EXISTS `s_projectwiseflatlist`;

CREATE TABLE `s_projectwiseflatlist` (
  `flatlist_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_lookup_pk_no` bigint(20) DEFAULT NULL,
  `size_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_description` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`flatlist_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `s_user` */

DROP TABLE IF EXISTS `s_user`;

CREATE TABLE `s_user` (
  `user_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `User_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fullname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_lookup_pk_no` bigint(20) DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_photo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `row_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`user_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `t_leadfollowup` */

DROP TABLE IF EXISTS `t_leadfollowup`;

CREATE TABLE `t_leadfollowup` (
  `lead_followup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_followup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_followup_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `Followup_type_pk_no` bigint(20) DEFAULT NULL,
  `followup_Note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_stage_before_followup` int(11) DEFAULT NULL,
  `next_followup_flag` int(11) DEFAULT NULL,
  `Next_FollowUp_date` date DEFAULT NULL,
  `next_followup_Prefered_Time` date DEFAULT NULL,
  `next_followup_Note` int(11) DEFAULT NULL,
  `lead_stage_after_followup` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lead_followup_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `t_leadlifecycle` */

DROP TABLE IF EXISTS `t_leadlifecycle`;

CREATE TABLE `t_leadlifecycle` (
  `leadlifecycle_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `leadlifecycle_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_current_stage` int(11) DEFAULT NULL,
  `lead_qc_flag` int(11) DEFAULT NULL,
  `lead_qc_datetime` date DEFAULT NULL,
  `lead_qc_by` bigint(20) DEFAULT NULL,
  `lead_k1_flag` int(11) DEFAULT NULL,
  `lead_k1_datetime` date DEFAULT NULL,
  `lead_k1_by` bigint(20) DEFAULT NULL,
  `lead_priority_flag` int(11) DEFAULT NULL,
  `lead_priority_datetime` date DEFAULT NULL,
  `lead_priority_by` bigint(20) DEFAULT NULL,
  `lead_hold_flag` int(11) DEFAULT NULL,
  `lead_hold_datetime` date DEFAULT NULL,
  `lead_hold_by` bigint(20) DEFAULT NULL,
  `lead_closed_flag` int(11) DEFAULT NULL,
  `lead_closed_datetime` date DEFAULT NULL,
  `lead_closed_by` bigint(20) DEFAULT NULL,
  `lead_sold_flag` int(11) DEFAULT NULL,
  `lead_sold_datetime` date DEFAULT NULL,
  `lead_sold_by` bigint(20) DEFAULT NULL,
  `lead_sold_date_manual` date DEFAULT NULL,
  `lead_sold_flatcost` float DEFAULT NULL,
  `lead_sold_utilitycost` float DEFAULT NULL,
  `lead_sold_parkingcost` float DEFAULT NULL,
  `lead_sold_customer_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_manager_pk_no` bigint(20) DEFAULT NULL,
  `lead_transfer_flag` int(11) DEFAULT NULL,
  `lead_transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`leadlifecycle_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `t_leads` */

DROP TABLE IF EXISTS `t_leads`;

CREATE TABLE `t_leads` (
  `lead_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation_pk_no` bigint(20) DEFAULT NULL,
  `organization_pk_no` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_usergroup_pk_no` bigint(20) DEFAULT NULL,
  `source_sac_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_digital_marketing` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_hotline` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_internal_reference` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sales_executive` bigint(20) DEFAULT NULL,
  `Customer_dateofbirth` date DEFAULT NULL,
  `customer_wife_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_wife_dataofbirth` date DEFAULT NULL,
  `Marriage_anniversary` date DEFAULT NULL,
  `children_name1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth1` date DEFAULT NULL,
  `children_name2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth2` date DEFAULT NULL,
  `children_name3` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth3` date DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lead_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `t_leadtransfer` */

DROP TABLE IF EXISTS `t_leadtransfer`;

CREATE TABLE `t_leadtransfer` (
  `transfer_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_transfer_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_flag` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`transfer_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `ic_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Procedure structure for procedure `proc_leadfollowup_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadfollowup_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_leadfollowup_ins`(
 IN in_lead_followup_id varchar (30),
 IN in_lead_followup_datetime date,
 IN in_lead_pk_no bigint,
 IN in_Followup_type_pk_no bigint,
 IN in_followup_Note varchar (200),
 IN in_lead_stage_before_followup int,
 IN in_next_followup_flag int,
 IN in_Next_FollowUp_date date,
 IN in_next_followup_Prefered_Time date,
 IN in_next_followup_Note int,
 IN in_lead_stage_after_followup int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO t_leadfollowup (
lead_followup_id, 
lead_followup_datetime, 
lead_pk_no, 
Followup_type_pk_no, 
followup_Note, 
lead_stage_before_followup, 
next_followup_flag, 
Next_FollowUp_date, 
next_followup_Prefered_Time, 
next_followup_Note, 
lead_stage_after_followup, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_followup_id, 
 in_lead_followup_datetime, 
 in_lead_pk_no, 
 in_Followup_type_pk_no, 
 in_followup_Note, 
 in_lead_stage_before_followup, 
 in_next_followup_flag, 
 in_Next_FollowUp_date, 
 in_next_followup_Prefered_Time, 
 in_next_followup_Note, 
 in_lead_stage_after_followup, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadlifecycle_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadlifecycle_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_leadlifecycle_ins`(
 IN in_leadlifecycle_id VARCHAR (30),
 IN in_lead_pk_no BIGINT,
 IN in_lead_sales_agent_pk_no BIGINT,
 IN in_lead_current_stage INT,
 IN in_lead_qc_flag INT,
 IN in_lead_qc_datetime DATE,
 IN in_lead_qc_by BIGINT,
/* 
 IN in_lead_k1_flag int,
 IN in_lead_k1_datetime date,
 IN in_lead_k1_by bigint,
 IN in_lead_priority_flag int,
 IN in_lead_priority_datetime date,
 IN in_lead_priority_by bigint,
 IN in_lead_hold_flag int,
 IN in_lead_hold_datetime date,
 IN in_lead_hold_by bigint,
 IN in_lead_closed_flag int,
 IN in_lead_closed_datetime date,
 IN in_lead_closed_by bigint,
 IN in_lead_sold_flag int,
 IN in_lead_sold_datetime date,
 IN in_lead_sold_by bigint,
 IN in_lead_sold_date_manual date,
 IN in_lead_sold_flatcost float,
 IN in_lead_sold_utilitycost float,
 IN in_lead_sold_parkingcost float,
 IN in_lead_sold_customer_pk_no bigint,
 IN in_lead_sold_sales_agent_pk_no bigint,
 IN in_lead_sold_team_lead_pk_no bigint,
 IN in_lead_sold_team_manager_pk_no bigint,
 IN in_lead_transfer_flag int,
 IN in_lead_transfer_from_sales_agent_pk_no bigint,
 */
 IN in_c_pk_no_created BIGINT,
 IN in_created_by BIGINT,
IN in_created_at DATE
)
BEGIN 
 INSERT INTO t_leadlifecycle (
leadlifecycle_id, 
lead_pk_no, 
lead_sales_agent_pk_no, 
lead_current_stage, 
lead_qc_flag, 
lead_qc_datetime, 
lead_qc_by, 
/*
lead_k1_flag, 
lead_k1_datetime, 
lead_k1_by, 
lead_priority_flag, 
lead_priority_datetime, 
lead_priority_by, 
lead_hold_flag, 
lead_hold_datetime, 
lead_hold_by, 
lead_closed_flag, 
lead_closed_datetime, 
lead_closed_by, 
lead_sold_flag, 
lead_sold_datetime, 
lead_sold_by, 
lead_sold_date_manual, 
lead_sold_flatcost, 
lead_sold_utilitycost, 
lead_sold_parkingcost, 
lead_sold_customer_pk_no, 
lead_sold_sales_agent_pk_no, 
lead_sold_team_lead_pk_no, 
lead_sold_team_manager_pk_no, 
lead_transfer_flag, 
lead_transfer_from_sales_agent_pk_no, 
*/
c_pk_no_created, 
created_by, 
created_at
) VALUES ( 
 in_leadlifecycle_id, 
 in_lead_pk_no, 
 in_lead_sales_agent_pk_no, 
 in_lead_current_stage, 
 in_lead_qc_flag, 
 in_lead_qc_datetime, 
 in_lead_qc_by, 
 /*
 in_lead_k1_flag, 
 in_lead_k1_datetime, 
 in_lead_k1_by, 
 in_lead_priority_flag, 
 in_lead_priority_datetime, 
 in_lead_priority_by, 
 in_lead_hold_flag, 
 in_lead_hold_datetime, 
 in_lead_hold_by, 
 in_lead_closed_flag, 
 in_lead_closed_datetime, 
 in_lead_closed_by, 
 in_lead_sold_flag, 
 in_lead_sold_datetime, 
 in_lead_sold_by, 
 in_lead_sold_date_manual, 
 in_lead_sold_flatcost, 
 in_lead_sold_utilitycost, 
 in_lead_sold_parkingcost, 
 in_lead_sold_customer_pk_no, 
 in_lead_sold_sales_agent_pk_no, 
 in_lead_sold_team_lead_pk_no, 
 in_lead_sold_team_manager_pk_no, 
 in_lead_transfer_flag, 
 in_lead_transfer_from_sales_agent_pk_no, 
 */
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
COMMIT;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadlifecycle_upd_stage` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadlifecycle_upd_stage` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_leadlifecycle_upd_stage`(
 IN in_lead_pk_no BIGINT,
 IN in_datetime DATE,
 IN in_by BIGINT,
 in in_tostage int,
 IN in_c_pk_no_created BIGINT
)
BEGIN 
 
 
-- 1 = Lead, 2=QC Passed, 3 = K1 Stage, 4= Priority, 5=Hold, 6= Closed, 9 = Sold


if in_tostage = 2 then
	update t_leadlifecycle 
	set  
	lead_qc_flag = in_tostage, 
	lead_qc_datetime = in_datetime, 
	lead_qc_by = in_by,
	lead_current_stage = in_tostage
	where lead_pk_no = in_lead_pk_no;

elseif in_tostage = 3 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_k1_flag = in_tostage, 
	lead_k1_datetime = in_datetime, 
	lead_k1_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 4 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_priority_flag = in_tostage, 
	lead_priority_datetime = in_datetime, 
	lead_priority_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;	
ELSEIF in_tostage = 5 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_hold_flag = in_tostage, 
	lead_hold_datetime = in_datetime, 
	lead_hold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 6 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_closed_flag = in_tostage, 
	lead_closed_datetime = in_datetime, 
	lead_closed_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 7 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_sold_flag = in_tostage, 
	lead_sold_datetime = in_datetime, 
	lead_sold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;				
end if;

COMMIT;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leads_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leads_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_leads_ins`(
 IN in_lead_id varchar (30),
 IN in_customer_firstname varchar (200),
 IN in_customer_lastname varchar (200),
 IN in_phone1 varchar (200),
 IN in_phone2 varchar (200),
 IN in_email_id varchar (200),
 IN in_occupation_pk_no bigint,
 IN in_organization_pk_no bigint,
 IN in_project_category_pk_no bigint,
 IN in_project_area_pk_no bigint,
 IN in_Project_pk_no bigint,
 IN in_project_size_pk_no bigint,
 IN in_source_auto_pk_no bigint,
 IN in_source_auto_usergroup_pk_no bigint,
 IN in_source_sac_name varchar (200),
 IN in_source_sac_note varchar (200),
 IN in_source_digital_marketing varchar (30),
 IN in_source_hotline varchar (30),
 IN in_source_internal_reference varchar (30),
 IN in_source_sales_executive bigint,
 IN in_Customer_dateofbirth date,
 IN in_customer_wife_name varchar (200),
 IN in_customer_wife_dataofbirth date,
 IN in_Marriage_anniversary date,
 IN in_children_name1 varchar (200),
 IN in_children_dateofbirth1 date,
 IN in_children_name2 varchar (200),
 IN in_children_dateofbirth2 date,
 IN in_children_name3 varchar (200),
 IN in_children_dateofbirth3 date,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO t_leads (
lead_id, 
customer_firstname, 
customer_lastname, 
phone1, 
phone2, 
email_id, 
occupation_pk_no, 
organization_pk_no, 
project_category_pk_no, 
project_area_pk_no, 
Project_pk_no, 
project_size_pk_no, 
source_auto_pk_no, 
source_auto_usergroup_pk_no, 
source_sac_name, 
source_sac_note, 
source_digital_marketing, 
source_hotline, 
source_internal_reference, 
source_sales_executive, 
Customer_dateofbirth, 
customer_wife_name, 
customer_wife_dataofbirth, 
Marriage_anniversary, 
children_name1, 
children_dateofbirth1, 
children_name2, 
children_dateofbirth2, 
children_name3, 
children_dateofbirth3, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_id, 
 in_customer_firstname, 
 in_customer_lastname, 
 in_phone1, 
 in_phone2, 
 in_email_id, 
 in_occupation_pk_no, 
 in_organization_pk_no, 
 in_project_category_pk_no, 
 in_project_area_pk_no, 
 in_Project_pk_no, 
 in_project_size_pk_no, 
 in_source_auto_pk_no, 
 in_source_auto_usergroup_pk_no, 
 in_source_sac_name, 
 in_source_sac_note, 
 in_source_digital_marketing, 
 in_source_hotline, 
 in_source_internal_reference, 
 in_source_sales_executive, 
 in_Customer_dateofbirth, 
 in_customer_wife_name, 
 in_customer_wife_dataofbirth, 
 in_Marriage_anniversary, 
 in_children_name1, 
 in_children_dateofbirth1, 
 in_children_name2, 
 in_children_dateofbirth2, 
 in_children_name3, 
 in_children_dateofbirth3, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadtransfer_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadtransfer_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_leadtransfer_ins`(
 IN in_lead_transfer_id varchar (30),
 IN in_transfer_datetime date,
 IN in_lead_pk_no bigint,
 IN in_transfer_from_sales_agent_pk_no bigint,
 IN in_transfer_to_sales_agent_pk_no bigint,
 IN in_transfer_to_sales_agent_flag int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO t_leadtransfer (
lead_transfer_id, 
transfer_datetime, 
lead_pk_no, 
transfer_from_sales_agent_pk_no, 
transfer_to_sales_agent_pk_no, 
transfer_to_sales_agent_flag, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_transfer_id, 
 in_transfer_datetime, 
 in_lead_pk_no, 
 in_transfer_from_sales_agent_pk_no, 
 in_transfer_to_sales_agent_pk_no, 
 in_transfer_to_sales_agent_flag, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_lookdata_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_lookdata_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_lookdata_ins`(
 IN in_lookup_type varchar (30),
 IN in_lookup_id varchar (30),
 IN in_lookup_name varchar (200),
 IN in_lookup_row_status int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO s_lookdata (
lookup_type, 
lookup_id, 
lookup_name, 
lookup_row_status, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lookup_type, 
 in_lookup_id, 
 in_lookup_name, 
 in_lookup_row_status, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_projectwiseflatlist_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_projectwiseflatlist_ins` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `proc_projectwiseflatlist_ins`(
 IN in_project_lookup_pk_no bigint,
 IN in_flat_id varchar (30),
 IN in_flat_name varchar (30),
 IN in_category_lookup_pk_no bigint,
 IN in_size_lookup_pk_no bigint,
 IN in_flat_description varchar (30),
 IN in_flat_status int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO s_projectwiseflatlist (
project_lookup_pk_no, 
flat_id, 
flat_name, 
category_lookup_pk_no, 
size_lookup_pk_no, 
flat_description, 
flat_status, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_project_lookup_pk_no, 
 in_flat_id, 
 in_flat_name, 
 in_category_lookup_pk_no, 
 in_size_lookup_pk_no, 
 in_flat_description, 
 in_flat_status, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `run_sys_procgenerate` */

/*!50003 DROP PROCEDURE IF EXISTS  `run_sys_procgenerate` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `run_sys_procgenerate`()
begin

CALL sys_procgenerate('t_leads','proc_leads_ins');
CALL sys_procgenerate('t_leadlifecycle','proc_leadlifecycle_ins	');
CALL sys_procgenerate('t_leadfollowup','proc_leadfollowup_ins');
CALL sys_procgenerate('t_leadtransfer','proc_leadtransfer_ins');
CALL sys_procgenerate('s_lookdata','proc_lookdata_ins');
CALL sys_procgenerate('s_projectwiseflatlist','proc_projectwiseflatlist_ins');
CALL sys_procgenerate('s_user','proc_user_inst');


END */$$
DELIMITER ;

/* Procedure structure for procedure `sys_procgenerate` */

/*!50003 DROP PROCEDURE IF EXISTS  `sys_procgenerate` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`xactidea_admin`@`localhost` PROCEDURE `sys_procgenerate`( IN in_tabname VARCHAR(30), IN in_procname VARCHAR(30))
BEGIN

DECLARE finished INTEGER DEFAULT 0;
DECLARE vcols VARCHAR(500);   

DECLARE out_txtend VARCHAR(500);
DECLARE out_txtst VARCHAR(500);
DECLARE vcolsins VARCHAR(500);
DECLARE instxt, instxtins VARCHAR(500);
DECLARE valtxt VARCHAR(500);
DECLARE cnt INT; 
DECLARE l_id BIGINT;

    DECLARE curColumns 
        CURSOR FOR 
		SELECT CONCAT(t1.column_def, t1.datalen) cols FROM (
		SELECT CONCAT(' IN in_' , column_name ,  ' ' , data_type ) AS column_def, 
		CASE WHEN data_type ='varchar' THEN CONCAT(' (',character_maximum_length,'),') ELSE ',' END AS datalen, ordinal_position AS pos
		FROM information_schema.`COLUMNS` WHERE table_name COLLATE utf8_general_ci = in_tabname COLLATE utf8_general_ci
		AND column_default IS NULL ) t1 WHERE pos > 1 ORDER BY pos ASC;
 
 
     DECLARE curColumnsIns 
        CURSOR FOR 
		SELECT CONCAT(column_name ,  ', ' ) AS column_ins
			, CONCAT(' in_' , column_name ,  ', ' ) column_val
			FROM information_schema.`COLUMNS` WHERE table_name COLLATE utf8_general_ci = in_tabname COLLATE utf8_general_ci 
			AND column_default IS NULL AND ordinal_position >1 ORDER BY ordinal_position ASC;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
        
 DELETE FROM proc_code WHERE tname= in_tabname;
 COMMIT;
 
SET out_txtst = 'DELIMITER $$';

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SELECT CONCAT('DROP PROCEDURE IF EXISTS ', in_procname, '$$') INTO out_txtst ;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);
 
 
SELECT CONCAT('CREATE PROCEDURE ',  in_procname , ' (') INTO out_txtst;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SET out_txtst = NULL;
SET cnt =0;
    OPEN curColumns;
 
    getCols: LOOP
        FETCH curColumns INTO vcols;
	
        IF vcols IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname); END IF;
	
        SET vcols = NULL;
        
        IF finished = 1 THEN 
            LEAVE getCols;		
        END IF;
        
    END LOOP getCols;
    CLOSE curColumns;


SET out_txtst = NULL;

SET out_txtst = ')  BEGIN ';

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SELECT CONCAT(' INSERT INTO ', in_tabname,' (') INTO out_txtst;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);


-- DECLARE finished INTEGER DEFAULT 0;
SET finished = 0;
        


    OPEN curColumnsIns;
 
    getColsins: LOOP
        FETCH curColumnsIns INTO vcols, vcolsins;
        
        SELECT CONCAT(instxt,vcols) INTO instxt;
        SELECT CONCAT(instxtins,vcolsins) INTO instxtins;
        
        -- INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
	IF vcols IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname); END IF;
	SET  vcols  = NULL;
	
        IF finished = 1 THEN 
            LEAVE getColsins;
        END IF;
        
        -- build email list
        -- SET emailList = CONCAT(emailAddress,";",emailList);
    END LOOP getColsins;
    CLOSE curColumnsIns;
    
    SET vcolsins = ') values ( ';
	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
    
    
SET finished = 0; 

    OPEN curColumnsIns;
 
    getColsins: LOOP
        FETCH curColumnsIns INTO vcols, vcolsins;
        
        SELECT CONCAT(instxt,vcols) INTO instxt;
        SELECT CONCAT(instxtins,vcolsins) INTO instxtins;
        
        -- INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
	IF vcolsins IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname); END IF;
	SET  vcolsins  = NULL;
	
        IF finished = 1 THEN 
            LEAVE getColsins;
        END IF;
        
        -- build email list
        -- SET emailList = CONCAT(emailAddress,";",emailList);
    END LOOP getColsins;
    CLOSE curColumnsIns;

SET vcolsins = ') ;';
 
 
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);


SET vcolsins = 'commit;';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);


SET vcolsins = 'END$$';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
SET vcolsins = 'DELIMITER ;';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
 	


DELETE FROM proc_code
WHERE tname = in_tabname AND script LIKE '%updated%';


SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%BEGIN%');

UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;

SELECT l_id;

SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND script LIKE '%values%');


UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;

SELECT l_id;


SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%) ;%');

UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;

-- SELECT l_id;

COMMIT;

/*
SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%BEGIN%');
*/



	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
 	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxt,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxtins,in_tabname,in_procname);
	
COMMIT;
	
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
