-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2021 at 06:26 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unimass`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_getsalesagentauto` (IN `in_category_pk_no` BIGINT, IN `in_area_pk_no` BIGINT)  BEGIN
	
	declare l_lead_sales_agent_pk_no bigint;
	
	-- select lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no, lead_count from (
	
	SELECT lead_sales_agent_pk_no INTO l_lead_sales_agent_pk_no FROM (	
	SELECT @cnt = @cnt+1 cnt, lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no, COUNT(lead_sales_agent_pk_no) lead_count 
	FROM t_leads ld JOIN t_leadlifecycle lc ON (ld.lead_pk_no = lc.lead_pk_no)
	WHERE lead_hold_flag <> 1 AND lead_closed_flag <> 1 AND lead_sold_flag <> 1
	GROUP BY lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no
	ORDER BY lead_count ASC) m
	WHERE m.project_category_pk_no = in_category_pk_no AND m.project_area_pk_no = in_area_pk_no AND cnt =1;
	
	
	select l_lead_sales_agent_pk_no;
	
	
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_getsalesagentauto_ind` (IN `in_category_pk_no` BIGINT, IN `in_area_pk_no` BIGINT)  BEGIN
	
	DECLARE l_user_pk_no BIGINT;
	DECLARE l_teammem_pk_no, l_mxcnt, l_cnt, l_teammem_pk_no1, l_user_pk_no1 BIGINT;
	
	
         
	SET @cnt = 0;
	
	
		
		SET @cnt = 0;
		
		SELECT cnt, teammem_pk_no, user_pk_no INTO l_cnt, l_teammem_pk_no, l_user_pk_no FROM (
		SELECT @cnt := COALESCE(@cnt,0)+1 cnt, teammem_pk_no, user_pk_no, last_auto_select_ind FROM (
		SELECT  tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no 
		AND COALESCE(row_status,0) = 1 AND sl_no IS NOT NULL
		ORDER BY sl_no  ASC, team_sl_no ASC) m ) z
		WHERE COALESCE(last_auto_select_ind,0) = 1;
				
		  -- SELECT 11,	l_cnt, l_mxcnt, l_teammem_pk_no, l_user_pk_no;


	SELECT COUNT(1) INTO l_mxcnt FROM t_teambuild tb 
	WHERE category_lookup_pk_no = in_category_pk_no AND area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1 AND sl_no IS NOT NULL;
	
		
	IF l_user_pk_no IS NULL THEN 
		SELECT teammem_pk_no, user_pk_no INTO l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1 
		AND COALESCE(team_sl_no,0) = 1 AND COALESCE(sl_no,0) = 1
		ORDER BY sl_no  ASC, team_sl_no ASC) m;
		
		 -- SELECT 22,	l_cnt, l_mxcnt, l_teammem_pk_no1, l_user_pk_no1;

				
	END IF;
	
	SET @cnt = 0;
	
	IF l_user_pk_no IS NOT NULL AND l_mxcnt = l_cnt THEN
		SELECT cnt, teammem_pk_no, user_pk_no INTO l_cnt, l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT @cnt := @cnt+1 cnt, teammem_pk_no, user_pk_no FROM (	
		SELECT tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no 
		AND COALESCE(row_status,0) = 1 AND sl_no IS NOT NULL
		ORDER BY sl_no  ASC, team_sl_no ASC) m ) z
		WHERE cnt =1;
		
		-- SELECT 33,	l_cnt, l_mxcnt, l_teammem_pk_no1, l_user_pk_no1;

	ELSEIF l_user_pk_no IS NOT NULL AND l_mxcnt <> l_cnt THEN
		SELECT  teammem_pk_no, user_pk_no INTO  l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT @cnt := @cnt+1 cnt, teammem_pk_no, user_pk_no FROM (
		SELECT  tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no 
		AND COALESCE(row_status,0) = 1 AND sl_no IS NOT NULL
		ORDER BY sl_no  ASC, team_sl_no ASC) m ) z
		WHERE cnt = l_cnt +1;	
		
		-- SELECT 44,	l_cnt, l_mxcnt, l_teammem_pk_no1, l_user_pk_no1;
		
	END IF;
	
	
	UPDATE 	t_teambuild tb 
		SET last_auto_select_ind = 0	
	WHERE category_lookup_pk_no = in_category_pk_no 
		AND teammem_pk_no IN ( SELECT teammem_pk_no FROM t_teambuildchd WHERE area_lookup_pk_no = in_area_pk_no) 
		AND COALESCE(row_status,0) = 1;
	
	UPDATE 	t_teambuild tb 
		SET last_auto_select_ind = 1	
	WHERE teammem_pk_no = l_teammem_pk_no1;
	
	COMMIT;
	
	SELECT l_user_pk_no1 ;
	

    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_getsalesagentauto_ind_5d` (IN `in_category_pk_no` BIGINT, IN `in_area_pk_no` BIGINT, OUT `out_user_pk_no` BIGINT)  BEGIN
	
	DECLARE l_user_pk_no BIGINT;
	DECLARE l_teammem_pk_no, l_mxcnt, l_cnt, l_teammem_pk_no1, l_user_pk_no1 BIGINT;
	
	
         
	SET @cnt = 0;
	
	
	-- select lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no, lead_count from (
	
		SET @cnt = 0;
		
		SELECT cnt, teammem_pk_no, user_pk_no INTO l_cnt, l_teammem_pk_no, l_user_pk_no FROM (
		SELECT @cnt := COALESCE(@cnt,0)+1 cnt, tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1
		ORDER BY sl_no  ASC, team_sl_no ASC) m 
		WHERE COALESCE(last_auto_select_ind,0) = 1;
	/*
	SET @cnt = 0;

	SELECT cnt, teammem_pk_no, user_pk_no FROM (
		SELECT @cnt := COALESCE(@cnt,0)+1 cnt, teammem_pk_no, teammem_id, team_lookup_pk_no, user_pk_no
		, category_lookup_pk_no, area_lookup_pk_no, sl_no, team_sl_no, row_status, last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1
		ORDER BY team_sl_no ASC, sl_no ASC) m ;
		-- WHERE COALESCE(last_auto_select_ind,0) = 1
	*/			
		
	SELECT COUNT(1) INTO l_mxcnt FROM t_teambuild tb 
	WHERE category_lookup_pk_no = in_category_pk_no AND area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1;
	
	-- SELECT 	l_cnt, l_mxcnt, l_teammem_pk_no, l_user_pk_no;
	
	IF l_user_pk_no IS NULL THEN 
		SELECT teammem_pk_no, user_pk_no INTO l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1 
		AND COALESCE(team_sl_no,0) = 1 AND COALESCE(sl_no,0) = 1
		ORDER BY sl_no  ASC, team_sl_no ASC) m;
		
		-- SELECT 	22, l_mxcnt, l_teammem_pk_no, l_user_pk_no;
		
	END IF;
	
	SET @cnt = 0;
	
	IF l_user_pk_no IS NOT NULL AND l_mxcnt = l_cnt THEN
		SELECT cnt, teammem_pk_no, user_pk_no INTO l_cnt, l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT @cnt := @cnt+1 cnt, tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1
		ORDER BY sl_no  ASC, team_sl_no ASC) m 
		WHERE cnt =1;
		
		-- SELECT 	33, l_mxcnt, l_teammem_pk_no, l_user_pk_no;


	ELSEIF l_user_pk_no IS NOT NULL AND l_mxcnt <> l_cnt THEN
		SELECT  teammem_pk_no, user_pk_no INTO  l_teammem_pk_no1, l_user_pk_no1 FROM (
		SELECT @cnt := @cnt+1 cnt, tb.teammem_pk_no, tb.teammem_id, tb.team_lookup_pk_no, tb.user_pk_no
		, tb.category_lookup_pk_no, tb.area_lookup_pk_no, tb.sl_no, tb.team_sl_no, tb.row_status, tb.last_auto_select_ind
		FROM t_teambuild tb JOIN t_teambuildchd tbc ON (tb.teammem_pk_no = tbc.teammem_pk_no)
		WHERE category_lookup_pk_no = in_category_pk_no AND tbc.area_lookup_pk_no = in_area_pk_no AND COALESCE(row_status,0) = 1
		ORDER BY sl_no  ASC, team_sl_no ASC) m 
		WHERE cnt = l_cnt +1;	
		
		-- SELECT 	44, l_mxcnt, l_teammem_pk_no1, l_user_pk_no1;

	END IF;
	
	
	UPDATE 	t_teambuild tb 
		SET last_auto_select_ind = 0	
	WHERE category_lookup_pk_no = in_category_pk_no 
		AND teammem_pk_no IN ( SELECT teammem_pk_no FROM t_teambuildchd WHERE area_lookup_pk_no = in_area_pk_no) 
		AND COALESCE(row_status,0) = 1;
	
	UPDATE 	t_teambuild tb 
		SET last_auto_select_ind = 1	
	WHERE teammem_pk_no = l_teammem_pk_no1;
	
	COMMIT;
	
	SELECT l_user_pk_no1 INTO out_user_pk_no;
	

    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_leadfollowup_ins` (IN `in_lead_followup_id` VARCHAR(30), IN `in_lead_followup_datetime` DATE, IN `in_lead_pk_no` BIGINT, IN `in_Followup_type_pk_no` BIGINT, IN `in_followup_Note` TEXT, IN `in_lead_stage_before_followup` INT, IN `in_next_followup_flag` INT, IN `in_Next_FollowUp_date` DATE, IN `in_next_followup_Prefered_Time` DATETIME, IN `in_next_followup_Note` TEXT, IN `in_lead_stage_after_followup` INT, IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATE, `in_meeting_status` VARCHAR(20), `in_meeting_date` DATE, `in_meeting_datetime` DATETIME, `in_visit_meeting_done` INT, `in_visit_meeting_done_dt` DATE)  BEGIN 
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
created_at,
meeting_status,
meeting_date,
meeting_time,
visit_meeting_done,
visit_meeting_done_dt
) VALUES ( 
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
 in_created_at,
 in_meeting_status,
 in_meeting_date,
 in_meeting_datetime,
 in_visit_meeting_done,
 in_visit_meeting_done_dt
) ;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_leadlifecycle_ins` (IN `in_leadlifecycle_id` VARCHAR(30), IN `in_lead_pk_no` BIGINT, IN `in_lead_dist_type` INT, IN `in_lead_entry_type` VARCHAR(10), IN `in_lead_cluster_head_pk_no` INT, IN `in_lead_cluster_head_assign_dt` DATE, IN `in_lead_sales_agent_pk_no` BIGINT, IN `in_lead_sales_agent_assign_dt` DATE, IN `in_lead_current_stage` INT, IN `in_lead_qc_flag` INT, IN `in_lead_qc_datetime` DATE, IN `in_lead_qc_by` BIGINT, IN `in_lead_k1_flag` INT, IN `in_lead_k1_datetime` DATE, IN `in_lead_k1_by` BIGINT, IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATE)  BEGIN
  INSERT INTO t_leadlifecycle (
    leadlifecycle_id,
    lead_pk_no,
    lead_dist_type,
    lead_entry_type,
    lead_cluster_head_pk_no,
    lead_cluster_head_assign_dt,
    lead_sales_agent_pk_no,
    lead_sales_agent_assign_dt,
    lead_current_stage,
    lead_qc_flag,
    lead_qc_datetime,
    lead_qc_by,
    lead_k1_flag,
    lead_k1_datetime,
    lead_k1_by,
    /*lead_priority_flag, 
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
  )
  VALUES
    (
      in_leadlifecycle_id,
      in_lead_pk_no,
      in_lead_dist_type,
      in_lead_entry_type,
      in_lead_cluster_head_pk_no,
      in_lead_cluster_head_assign_dt,
      in_lead_sales_agent_pk_no,
      in_lead_sales_agent_assign_dt,
      in_lead_current_stage,
      in_lead_qc_flag,
      in_lead_qc_datetime,
      in_lead_qc_by,
      in_lead_k1_flag,
      in_lead_k1_datetime,
      in_lead_k1_by,
      /*in_lead_priority_flag, 
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
    );
  COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_leadlifecycle_upd_stage` (IN `in_lead_pk_no` BIGINT, IN `in_datetime` DATE, IN `in_by` BIGINT, IN `in_tostage` INT, IN `in_c_pk_no_created` BIGINT, IN `in_flat_id` BIGINT)  BEGIN 
IF in_tostage = 2 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_qc_flag = 1, 
	lead_qc_datetime = in_datetime, 
	lead_qc_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 3 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_k1_flag = 1, 
	lead_k1_datetime = in_datetime, 
	lead_k1_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 4 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_priority_flag = 1, 
	lead_priority_datetime = in_datetime, 
	lead_priority_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;	
ELSEIF in_tostage = 5 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_hold_flag = 1, 
	lead_hold_datetime = in_datetime, 
	lead_hold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 6 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_closed_flag = 1, 
	lead_closed_datetime = in_datetime, 
	lead_closed_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 7 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_sold_flag = 1, 
	lead_sold_datetime = in_datetime, 
	lead_sold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 13 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_hp_flag = 1, 
	lead_hp_datetime = in_datetime, 
	lead_hp_by = in_by,
	lead_current_stage = in_tostage,
	flatlist_pk_no = in_flat_id
	WHERE lead_pk_no = in_lead_pk_no;	
ELSE
	UPDATE t_leadlifecycle 
	SET lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;		
END IF;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_leads_ins` (IN `in_lead_id` VARCHAR(30), IN `in_customer_firstname1` VARCHAR(200), IN `in_customer_lastname1` VARCHAR(200), IN `in_customer_firstname2` VARCHAR(200), IN `in_customer_lastname2` VARCHAR(200), IN `in_phone1_code` VARCHAR(10), IN `in_phone1` VARCHAR(200), IN `in_phone2_code` VARCHAR(10), IN `in_phone2` VARCHAR(200), IN `in_email_id` VARCHAR(200), IN `in_occupation_pk_no` BIGINT, IN `in_organization_name` VARCHAR(200), IN `in_cust_designation` VARCHAR(200), IN `in_project_category_pk_no` BIGINT, IN `in_project_area_pk_no` BIGINT, IN `in_Project_pk_no` BIGINT, IN `in_project_size_pk_no` BIGINT, IN `in_source_auto_pk_no` BIGINT, IN `in_source_auto_usergroup_pk_no` BIGINT, IN `in_source_auto_sub` VARCHAR(200), IN `in_source_sac_name` VARCHAR(200), IN `in_source_sac_note` VARCHAR(200), IN `in_source_digital_marketing` VARCHAR(30), IN `in_source_hotline` VARCHAR(30), IN `in_source_internal_reference` VARCHAR(30), IN `in_source_ir_emp_id` VARCHAR(100), IN `in_source_ir_emp_name` VARCHAR(200), IN `in_source_ir_position` VARCHAR(200), IN `in_source_ir_contact_no` INT(11), IN `in_source_sales_executive` BIGINT, IN `in_Customer_dateofbirth` DATE, IN `in_Customer_dateofbirth2` DATE, IN `in_customer_wife_name` VARCHAR(200), IN `in_customer_wife_dataofbirth` DATE, IN `in_Marriage_anniversary` DATE, IN `in_children_name1` VARCHAR(200), IN `in_children_dateofbirth1` DATE, IN `in_children_name2` VARCHAR(200), IN `in_children_dateofbirth2` DATE, IN `in_children_name3` VARCHAR(200), IN `in_children_dateofbirth3` DATE, IN `in_remarks` TEXT, IN `in_pre_holding_no` VARCHAR(100), IN `in_pre_road_no` VARCHAR(100), IN `in_pre_area` BIGINT(20), IN `in_pre_district` BIGINT(20), IN `in_pre_thana` BIGINT(20), IN `in_pre_size` VARCHAR(100), IN `in_per_holding_no` VARCHAR(100), IN `in_per_road_no` VARCHAR(100), IN `in_per_area` BIGINT(20), IN `in_per_district` BIGINT(20), IN `in_per_thana` BIGINT(20), IN `in_office_holding_no` VARCHAR(20), IN `in_office_road_no` VARCHAR(20), IN `in_office_area` BIGINT(20), IN `in_office_district` BIGINT(20), IN `in_office_thana` BIGINT(20), IN `in_meeting_status` INT(20), IN `in_meeting_date` DATE, IN `in_meeting_time` DATETIME, IN `in_food_habit` VARCHAR(200), IN `in_political_opinion` VARCHAR(200), IN `in_car_preference` VARCHAR(200), IN `in_color_preference` VARCHAR(200), IN `in_hobby` VARCHAR(200), IN `in_traveling_history` VARCHAR(200), IN `in_member_of_club` VARCHAR(200), IN `in_child_education` VARCHAR(200), IN `in_disease_name` VARCHAR(200), IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATETIME)  BEGIN
  DECLARE l_lead_pk_no BIGINT;
  DECLARE l_lead_id VARCHAR (30);
  DECLARE l_mm,
  l_yy INT;
  INSERT INTO t_leads (
    lead_id,    
    customer_firstname,
    customer_lastname,
    customer_firstname2,
    customer_lastname2,
    phone1_code,
    phone1,
    phone2_code,
    phone2,
    email_id,
    occupation_pk_no,
    organization_pk_no,
    cust_designation,
    project_category_pk_no,
    project_area_pk_no,
    Project_pk_no,
    project_size_pk_no,
    source_auto_pk_no,
    source_auto_usergroup_pk_no,
    source_auto_sub,
    source_sac_name,
    source_sac_note,
    source_digital_marketing,
    source_hotline,
    source_internal_reference,
    source_ir_emp_id,
    source_ir_name,
    source_ir_position,
    source_ir_contact_no,
    source_sales_executive,
    Customer_dateofbirth,
    Customer_dateofbirth2,
    customer_wife_name,
    customer_wife_dataofbirth,
    Marriage_anniversary,
    children_name1,
    children_dateofbirth1,
    children_name2,
    children_dateofbirth2,
    children_name3,
    children_dateofbirth3,
    remarks,
  
  pre_holding_no,
  pre_road_no,
  pre_area,
  pre_district,
  pre_thana,
  pre_size,
  per_holding_no,
  per_road_no,
  per_area,
  per_district,
  per_thana,
  office_holding_no,
  office_road_no,
  office_area,
  office_district,
  office_thana,   
  meeting_status,
  meeting_date,
  meeting_time, 
  food_habit,
  political_opinion,
  car_preference,
  color_preference,
  hobby,
  traveling_history,
  member_of_club,
  child_education,
  disease_name,
  
    c_pk_no_created,
    created_by,
    created_at
  )
  VALUES
    (
      in_lead_id,      
      in_customer_firstname1,
      in_customer_lastname1,
      in_customer_firstname2,
      in_customer_lastname2,
      in_phone1_code,
      in_phone1,
      in_phone2_code,
      in_phone2,
      in_email_id,
      in_occupation_pk_no,
      in_organization_name,
      in_cust_designation,
      in_project_category_pk_no,
      in_project_area_pk_no,
      in_Project_pk_no,
      in_project_size_pk_no,
      in_source_auto_pk_no,
      in_source_auto_usergroup_pk_no,
      in_source_auto_sub,
      in_source_sac_name,
      in_source_sac_note,
      in_source_digital_marketing,
      in_source_hotline,
      in_source_internal_reference,
      in_source_ir_emp_id,
      in_source_ir_emp_name,
      in_source_ir_position,
      in_source_ir_contact_no,
      in_source_sales_executive,
      in_Customer_dateofbirth,
      in_Customer_dateofbirth2,
      in_customer_wife_name,
      in_customer_wife_dataofbirth,
      in_Marriage_anniversary,
      in_children_name1,
      in_children_dateofbirth1,
      in_children_name2,
      in_children_dateofbirth2,
      in_children_name3,
      in_children_dateofbirth3,
      in_remarks,
      
  in_pre_holding_no,
  in_pre_road_no,
  in_pre_area,
  in_pre_district,
  in_pre_thana,
  in_pre_size,
  in_per_holding_no,
  in_per_road_no,
  in_per_area,
  in_per_district,
  in_per_thana,
  in_office_holding_no,
  in_office_road_no,
  in_office_area,
  in_office_district,
  in_office_thana,
  
  in_meeting_status,
  in_meeting_date,
  in_meeting_time, 
     
  in_food_habit,
  in_political_opinion,
  in_car_preference,
  in_color_preference,
  in_hobby,
  in_traveling_history,
  in_member_of_club,
  in_child_education,
  in_disease_name,
  
      in_c_pk_no_created,
      in_created_by,
      in_created_at
    );
  SET l_lead_pk_no = LAST_INSERT_ID ();
  SET l_lead_id = CONCAT ('L', l_lead_pk_no);
  SELECT
    MONTH (CURTIME()),
    YEAR (CURTIME()) INTO l_mm,
    l_yy;
  SELECT
    CONCAT ('L', l_yy, l_mm, l_lead_pk_no) INTO l_lead_id;
  UPDATE
    t_leads
  SET
    lead_id = l_lead_id
  WHERE lead_pk_no = l_lead_pk_no;
  SELECT
    l_lead_pk_no,
    l_lead_id;
  COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_leadtransfer_ins` (IN `in_lead_transfer_id` VARCHAR(30), IN `in_transfer_datetime` DATE, IN `in_lead_pk_no` BIGINT, IN `in_transfer_from_sales_agent_pk_no` BIGINT, IN `in_transfer_to_sales_agent_pk_no` BIGINT, IN `in_transfer_to_sales_agent_flag` INT, IN `in_re_transfer` INT, IN `in_ch_user_pk_no` BIGINT, IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATE)  BEGIN 

UPDATE     t_leadtransfer lt 
        SET re_transfer = 0 WHERE lt.lead_pk_no=in_lead_pk_no;
        
UPDATE     t_leadlifecycle ll
SET lead_transfer_flag = 1,lead_transfer_from_sales_agent_pk_no=in_transfer_from_sales_agent_pk_no WHERE ll.lead_pk_no=in_lead_pk_no;
        
 INSERT INTO t_leadtransfer (
lead_transfer_id, 
transfer_datetime, 
lead_pk_no, 
transfer_from_sales_agent_pk_no, 
transfer_to_sales_agent_pk_no, 
transfer_to_sales_agent_flag, 
re_transfer,
c_pk_no_created, 
ch_user_pk_no,
created_by, 
created_at
) VALUES ( 
 in_lead_transfer_id, 
 in_transfer_datetime, 
 in_lead_pk_no, 
 in_transfer_from_sales_agent_pk_no, 
 in_transfer_to_sales_agent_pk_no, 
 in_transfer_to_sales_agent_flag, 
 in_re_transfer,
 in_c_pk_no_created, 
 in_ch_user_pk_no,
 in_created_by, 
 in_created_at
);

COMMIT;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_lookdata_ins` (IN `in_lookup_type` VARCHAR(30), IN `in_lookup_id` VARCHAR(30), IN `in_lookup_name` VARCHAR(200), IN `in_lookup_row_status` INT, IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATE)  BEGIN 
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_projectwiseflatlist_ins` (IN `in_project_lookup_pk_no` BIGINT, IN `in_flat_id` VARCHAR(30), IN `in_flat_name` VARCHAR(30), IN `in_category_lookup_pk_no` BIGINT, IN `in_size_lookup_pk_no` BIGINT, IN `in_flat_description` VARCHAR(30), IN `in_flat_status` INT, IN `in_c_pk_no_created` BIGINT, IN `in_created_by` BIGINT, IN `in_created_at` DATE)  BEGIN 
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_setunattended_lead` ()  BEGIN
	
	DECLARE l_user_pk_no BIGINT;
	DECLARE l_teammem_pk_no, l_mxcnt, l_cnt, l_teammem_pk_no1, l_user_pk_no1 BIGINT;
	DECLARE finished INTEGER DEFAULT 0;
	DECLARE l_lead_pk_no, l_area_pk_no, l_category_pk_no, l_a, l_old_sa BIGINT;
	declare l_old_sa_ass_dt date;

        
	DECLARE curUnattended CURSOR FOR 
		SELECT lead_pk_no, project_area_pk_no, project_category_pk_no  FROM (
		SELECT ld.lead_pk_no, lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no
		, lead_sales_agent_assign_dt
		, DATEDIFF(NOW(), lead_sales_agent_assign_dt) peding_days
		FROM t_leads ld JOIN t_leadlifecycle lc ON (ld.lead_pk_no = lc.lead_pk_no)
		LEFT JOIN t_leadfollowup lw ON (ld.lead_pk_no = lw.lead_pk_no)) m
		WHERE peding_days > 5;
		
        DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;


    OPEN curUnattended;
 
    getUna: LOOP
        FETCH curUnattended INTO l_lead_pk_no, l_area_pk_no, l_category_pk_no;
	SET @a = 0;
	
        CALL proc_getsalesagentauto_ind_5d(l_category_pk_no, l_area_pk_no, l_a);
        
        
        SELECT l_a;
        
        select lead_sales_agent_pk_no, lead_sales_agent_assign_dt 
        into l_old_sa, l_old_sa_ass_dt from t_leadlifecycle WHERE  lead_pk_no =l_lead_pk_no;
        
        
        INSERT INTO t_eventlog_detail
		(eventdtl_dttm,  affected_lead_pk_no ,  sales_agent_pk_no_old , sales_agent_assigned_dt_old, sales_agent_pk_no_new)
		VALUES(NOW(),l_lead_pk_no, l_old_sa,lead_sales_agent_assign_dt, l_a);

		if l_a is not null then
--			UPDATE t_leadlifecycle
--			SET lead_sales_agent_pk_no = l_a,
--			 lead_sales_agent_assign_dt = NOW()
--			WHERE  lead_pk_no =l_lead_pk_no;
			set l_a = 0;
		end if;
		
        IF finished = 1 THEN 
            LEAVE getUna;		
        END IF;
        
    END LOOP getUna;
    		

    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `run_sys_procgenerate` ()  begin
CALL sys_procgenerate('t_leads','proc_leads_ins');
CALL sys_procgenerate('t_leadlifecycle','proc_leadlifecycle_ins	');
CALL sys_procgenerate('t_leadfollowup','proc_leadfollowup_ins');
CALL sys_procgenerate('t_leadtransfer','proc_leadtransfer_ins');
CALL sys_procgenerate('s_lookdata','proc_lookdata_ins');
CALL sys_procgenerate('s_projectwiseflatlist','proc_projectwiseflatlist_ins');
CALL sys_procgenerate('s_user','proc_user_inst');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sys_procgenerate` (IN `in_tabname` VARCHAR(30), IN `in_procname` VARCHAR(30))  BEGIN
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
COMMIT;
/*
SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%BEGIN%');
*/
	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
 	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxt,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxtins,in_tabname,in_procname);
	
COMMIT;
	
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `status` smallint(6) DEFAULT 1 COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `COUNTRY_PK_NO` int(11) NOT NULL,
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nicename` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`COUNTRY_PK_NO`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 610),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIA', 'Russia', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` int(11) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `division_id` int(11) DEFAULT NULL,
  `district_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `bn_name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lon` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `division_id`, `district_name`, `bn_name`, `lat`, `lon`, `url`) VALUES
(1, 1, 'Comilla', 'কুমিল্লা', '23.4682747', '91.1788135', 'www.comilla.gov.bd'),
(2, 1, 'Feni', 'ফেনী', '23.023231', '91.3840844', 'www.feni.gov.bd'),
(3, 1, 'Brahmanbaria', 'ব্রাহ্মণবাড়িয়া', '23.9570904', '91.1119286', 'www.brahmanbaria.gov.bd'),
(4, 1, 'Rangamati', 'রাঙ্গামাটি', NULL, NULL, 'www.rangamati.gov.bd'),
(5, 1, 'Noakhali', 'নোয়াখালী', '22.869563', '91.099398', 'www.noakhali.gov.bd'),
(6, 1, 'Chandpur', 'চাঁদপুর', '23.2332585', '90.6712912', 'www.chandpur.gov.bd'),
(7, 1, 'Lakshmipur', 'লক্ষ্মীপুর', '22.942477', '90.841184', 'www.lakshmipur.gov.bd'),
(8, 1, 'Chattogram', 'চট্টগ্রাম', '22.335109', '91.834073', 'www.chittagong.gov.bd'),
(9, 1, 'Coxsbazar', 'কক্সবাজার', NULL, NULL, 'www.coxsbazar.gov.bd'),
(10, 1, 'Khagrachhari', 'খাগড়াছড়ি', '23.119285', '91.984663', 'www.khagrachhari.gov.bd'),
(11, 1, 'Bandarban', 'বান্দরবান', '22.1953275', '92.2183773', 'www.bandarban.gov.bd'),
(12, 2, 'Sirajganj', 'সিরাজগঞ্জ', '24.4533978', '89.7006815', 'www.sirajganj.gov.bd'),
(13, 2, 'Pabna', 'পাবনা', '23.998524', '89.233645', 'www.pabna.gov.bd'),
(14, 2, 'Bogura', 'বগুড়া', '24.8465228', '89.377755', 'www.bogra.gov.bd'),
(15, 2, 'Rajshahi', 'রাজশাহী', NULL, NULL, 'www.rajshahi.gov.bd'),
(16, 2, 'Natore', 'নাটোর', '24.420556', '89.000282', 'www.natore.gov.bd'),
(17, 2, 'Joypurhat', 'জয়পুরহাট', NULL, NULL, 'www.joypurhat.gov.bd'),
(18, 2, 'Chapainawabganj', 'চাঁপাইনবাবগঞ্জ', '24.5965034', '88.2775122', 'www.chapainawabganj.gov.bd'),
(19, 2, 'Naogaon', 'নওগাঁ', NULL, NULL, 'www.naogaon.gov.bd'),
(20, 3, 'Jashore', 'যশোর', '23.16643', '89.2081126', 'www.jessore.gov.bd'),
(21, 3, 'Satkhira', 'সাতক্ষীরা', NULL, NULL, 'www.satkhira.gov.bd'),
(22, 3, 'Meherpur', 'মেহেরপুর', '23.762213', '88.631821', 'www.meherpur.gov.bd'),
(23, 3, 'Narail', 'নড়াইল', '23.172534', '89.512672', 'www.narail.gov.bd'),
(24, 3, 'Chuadanga', 'চুয়াডাঙ্গা', '23.6401961', '88.841841', 'www.chuadanga.gov.bd'),
(25, 3, 'Kushtia', 'কুষ্টিয়া', '23.901258', '89.120482', 'www.kushtia.gov.bd'),
(26, 3, 'Magura', 'মাগুরা', '23.487337', '89.419956', 'www.magura.gov.bd'),
(27, 3, 'Khulna', 'খুলনা', '22.815774', '89.568679', 'www.khulna.gov.bd'),
(28, 3, 'Bagerhat', 'বাগেরহাট', '22.651568', '89.785938', 'www.bagerhat.gov.bd'),
(29, 3, 'Jhenaidah', 'ঝিনাইদহ', '23.5448176', '89.1539213', 'www.jhenaidah.gov.bd'),
(30, 4, 'Jhalakathi', 'ঝালকাঠি', NULL, NULL, 'www.jhalakathi.gov.bd'),
(31, 4, 'Patuakhali', 'পটুয়াখালী', '22.3596316', '90.3298712', 'www.patuakhali.gov.bd'),
(32, 4, 'Pirojpur', 'পিরোজপুর', NULL, NULL, 'www.pirojpur.gov.bd'),
(33, 4, 'Barisal', 'বরিশাল', NULL, NULL, 'www.barisal.gov.bd'),
(34, 4, 'Bhola', 'ভোলা', '22.685923', '90.648179', 'www.bhola.gov.bd'),
(35, 4, 'Barguna', 'বরগুনা', NULL, NULL, 'www.barguna.gov.bd'),
(36, 5, 'Sylhet', 'সিলেট', '24.8897956', '91.8697894', 'www.sylhet.gov.bd'),
(37, 5, 'Moulvibazar', 'মৌলভীবাজার', '24.482934', '91.777417', 'www.moulvibazar.gov.bd'),
(38, 5, 'Habiganj', 'হবিগঞ্জ', '24.374945', '91.41553', 'www.habiganj.gov.bd'),
(39, 5, 'Sunamganj', 'সুনামগঞ্জ', '25.0658042', '91.3950115', 'www.sunamganj.gov.bd'),
(40, 6, 'Narsingdi', 'নরসিংদী', '23.932233', '90.71541', 'www.narsingdi.gov.bd'),
(41, 6, 'Gazipur', 'গাজীপুর', '24.0022858', '90.4264283', 'www.gazipur.gov.bd'),
(42, 6, 'Shariatpur', 'শরীয়তপুর', NULL, NULL, 'www.shariatpur.gov.bd'),
(43, 6, 'Narayanganj', 'নারায়ণগঞ্জ', '23.63366', '90.496482', 'www.narayanganj.gov.bd'),
(44, 6, 'Tangail', 'টাঙ্গাইল', NULL, NULL, 'www.tangail.gov.bd'),
(45, 6, 'Kishoreganj', 'কিশোরগঞ্জ', '24.444937', '90.776575', 'www.kishoreganj.gov.bd'),
(46, 6, 'Manikganj', 'মানিকগঞ্জ', NULL, NULL, 'www.manikganj.gov.bd'),
(47, 6, 'Dhaka', 'ঢাকা', '23.7115253', '90.4111451', 'www.dhaka.gov.bd'),
(48, 6, 'Munshiganj', 'মুন্সিগঞ্জ', NULL, NULL, 'www.munshiganj.gov.bd'),
(49, 6, 'Rajbari', 'রাজবাড়ী', '23.7574305', '89.6444665', 'www.rajbari.gov.bd'),
(50, 6, 'Madaripur', 'মাদারীপুর', '23.164102', '90.1896805', 'www.madaripur.gov.bd'),
(51, 6, 'Gopalganj', 'গোপালগঞ্জ', '23.0050857', '89.8266059', 'www.gopalganj.gov.bd'),
(52, 6, 'Faridpur', 'ফরিদপুর', '23.6070822', '89.8429406', 'www.faridpur.gov.bd'),
(53, 7, 'Panchagarh', 'পঞ্চগড়', '26.3411', '88.5541606', 'www.panchagarh.gov.bd'),
(54, 7, 'Dinajpur', 'দিনাজপুর', '25.6217061', '88.6354504', 'www.dinajpur.gov.bd'),
(55, 7, 'Lalmonirhat', 'লালমনিরহাট', NULL, NULL, 'www.lalmonirhat.gov.bd'),
(56, 7, 'Nilphamari', 'নীলফামারী', '25.931794', '88.856006', 'www.nilphamari.gov.bd'),
(57, 7, 'Gaibandha', 'গাইবান্ধা', '25.328751', '89.528088', 'www.gaibandha.gov.bd'),
(58, 7, 'Thakurgaon', 'ঠাকুরগাঁও', '26.0336945', '88.4616834', 'www.thakurgaon.gov.bd'),
(59, 7, 'Rangpur', 'রংপুর', '25.7558096', '89.244462', 'www.rangpur.gov.bd'),
(60, 7, 'Kurigram', 'কুড়িগ্রাম', '25.805445', '89.636174', 'www.kurigram.gov.bd'),
(61, 8, 'Sherpur', 'শেরপুর', '25.0204933', '90.0152966', 'www.sherpur.gov.bd'),
(62, 8, 'Mymensingh', 'ময়মনসিংহ', NULL, NULL, 'www.mymensingh.gov.bd'),
(63, 8, 'Jamalpur', 'জামালপুর', '24.937533', '89.937775', 'www.jamalpur.gov.bd'),
(64, 8, 'Netrokona', 'নেত্রকোণা', '24.870955', '90.727887', 'www.netrokona.gov.bd');

-- --------------------------------------------------------

--
-- Stand-in structure for view `duplicate_phn`
-- (See below for the actual view)
--
CREATE TABLE `duplicate_phn` (
`lead_pk_no` mediumtext
,`min_lead_pk_no` bigint(20)
,`lead_ids` mediumtext
,`phone1_count` bigint(21)
,`GROUP_CONCAT(phone1)` mediumtext
,`phone1_code` varchar(10)
,`phone1` varchar(200)
,`lead_current_stage` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `kpi_acr`
-- (See below for the actual view)
--
CREATE TABLE `kpi_acr` (
`user_pk_no` bigint(20)
,`team_lead_user_pk_no` int(11)
,`user_name` varchar(500)
,`k1_count` decimal(32,0)
,`priority_count` decimal(32,0)
,`sold_count` decimal(32,0)
,`k1_priority_ratio` decimal(36,4)
,`priority_sold_ratio` decimal(36,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `kpi_acr_count`
-- (See below for the actual view)
--
CREATE TABLE `kpi_acr_count` (
`lead_sales_agent_pk_no` bigint(20)
,`k1_count` decimal(32,0)
,`priority_count` decimal(32,0)
,`sold_count` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `kpi_apt`
-- (See below for the actual view)
--
CREATE TABLE `kpi_apt` (
`user_pk_no` bigint(20)
,`team_lead_user_pk_no` int(11)
,`user_name` varchar(500)
,`lead2k1` decimal(10,4)
,`k12priority` decimal(10,4)
,`priority2sold` decimal(10,4)
,`k12sold` decimal(10,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `kpi_apt_avgprocdays`
-- (See below for the actual view)
--
CREATE TABLE `kpi_apt_avgprocdays` (
`lead_sales_agent_pk_no` bigint(20)
,`lead2k1` decimal(10,4)
,`k12priority` decimal(10,4)
,`priority2sold` decimal(10,4)
,`k12sold` decimal(10,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `kpi_avt`
-- (See below for the actual view)
--
CREATE TABLE `kpi_avt` (
`user_pk_no` bigint(20)
,`team_lead_user_pk_no` int(11)
,`user_name` varchar(500)
,`yy_mm` varchar(30)
,`target_amount` int(11)
,`target_by_lead_qty` int(11)
,`sold_yymm` varchar(7)
,`sold_amt` double
);

-- --------------------------------------------------------

--
-- Table structure for table `kpi_soldamt_yymm`
--

CREATE TABLE `kpi_soldamt_yymm` (
  `lead_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `sold_yymm` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sold_amt` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proc_code`
--

CREATE TABLE `proc_code` (
  `id` bigint(20) NOT NULL,
  `script` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` datetime DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `address`, `gender`, `image`, `phone`, `dob`, `city_id`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 55, NULL, NULL, NULL, NULL, '2020-02-03 00:00:00', NULL, NULL, '2020-02-03 09:26:14', '2020-02-03 09:26:14'),
(2, 59, NULL, NULL, NULL, NULL, '2020-02-03 00:00:00', NULL, NULL, '2020-02-03 09:27:25', '2020-02-03 09:27:25'),
(3, 76, NULL, NULL, NULL, NULL, '2020-02-17 00:00:00', NULL, NULL, '2020-02-17 07:43:53', '2020-02-17 09:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `project_schedule_collectoins`
--

CREATE TABLE `project_schedule_collectoins` (
  `id` int(10) NOT NULL,
  `schedule_id` int(10) NOT NULL,
  `lead_pk_no` int(10) NOT NULL,
  `lead_id` varchar(50) NOT NULL,
  `collected_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sold_project_schedules`
--

CREATE TABLE `sold_project_schedules` (
  `id` int(10) NOT NULL,
  `lead_pk_no` int(10) NOT NULL,
  `lead_id` varchar(50) DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `installment` varchar(100) NOT NULL,
  `amount` float(12,2) NOT NULL,
  `percent_of_total_apt_price` float(12,2) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sold_project_schedules`
--

INSERT INTO `sold_project_schedules` (`id`, `lead_pk_no`, `lead_id`, `schedule_date`, `installment`, `amount`, `percent_of_total_apt_price`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 2, 'L202122', '2021-02-10', '1st Istallment', 6952500.00, 45.00, 'In Complete', '2021-02-26 17:12:48', '2021-02-26 17:12:48'),
(2, 2, 'L202122', '2021-02-10', '2nd Istallment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:48', '2021-02-26 17:12:48'),
(3, 2, 'L202122', '2021-02-10', '3rd installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:48', '2021-02-26 17:12:48'),
(4, 2, 'L202122', '2021-02-25', '4th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:48', '2021-02-26 17:12:48'),
(5, 2, 'L202122', '2021-02-16', '5th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:48', '2021-02-26 17:12:48'),
(6, 2, 'L202122', '2021-02-17', '6th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:49', '2021-02-26 17:12:49'),
(7, 2, 'L202122', '2021-02-09', '7th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:49', '2021-02-26 17:12:49'),
(8, 2, 'L202122', '2021-02-17', '8th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:49', '2021-02-26 17:12:49'),
(9, 2, 'L202122', '2021-02-11', '9th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:49', '2021-02-26 17:12:49'),
(10, 2, 'L202122', '2021-02-16', '10th installment', 944166.00, 6.11, 'In Complete', '2021-02-26 17:12:49', '2021-02-26 17:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `s_company`
--

CREATE TABLE `s_company` (
  `c_pk_no` bigint(20) NOT NULL,
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
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `s_groupcomp`
--

CREATE TABLE `s_groupcomp` (
  `gc_pk_no` bigint(20) NOT NULL,
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
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `s_lookdata`
--

CREATE TABLE `s_lookdata` (
  `lookup_pk_no` bigint(20) NOT NULL,
  `lookup_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_row_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `s_lookdata`
--

INSERT INTO `s_lookdata` (`lookup_pk_no`, `lookup_type`, `lookup_id`, `lookup_name`, `lookup_row_status`, `c_pk_no_created`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`) VALUES
(1, '0', '1', 'Super Admin', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(2, '10', '1', 'Business', 1, 1, 1, '2020-01-04', NULL, '2020-03-02', NULL),
(3, '10', '1', 'Service Holder', 1, 1, 1, '2020-01-04', NULL, '2020-01-12', NULL),
(5, '10', '1', 'Bank Job', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(6, '10', '1', 'Private Service Holder', 1, 1, 1, '2020-01-04', NULL, '2020-01-12', NULL),
(7, '10', '1', 'University Professor', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(8, '10', '1', 'Job', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(9, '10', '1', 'Creative designer', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(10, '10', '1', 'Corporate Service', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(11, '10', '1', 'Banker', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(12, '10', '1', 'Businessman (Civil Engineer)', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(13, '10', '1', 'Doctor', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(14, '10', '1', 'Teacher', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(15, '10', '1', 'Engineer', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(16, '10', '1', 'Student', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(17, '10', '1', 'Interior Architect', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(18, '10', '1', 'Freelancer', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(19, '10', '1', 'Private company service', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(20, '10', '1', 'Private Service', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(21, '10', '1', 'Private Service (National Professional)', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(22, '10', '1', 'Architect', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(619, '2', '1', 'Other', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(620, '2', '1', 'Buy Back Investment', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(621, '2', '1', 'NRB- Facebook', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(73, '1', '1', 'GM(CRS)', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(77, '1', '1', 'Sales Exe', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(83, '16', '1', 'Prospect', 1, 1, 1, '2020-01-04', NULL, '2020-11-28', NULL),
(84, '16', '1', 'Leads (SQL)', 1, 1, 1, '2020-01-04', NULL, '2020-11-29', NULL),
(85, '16', '1', 'Priority', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(86, '16', '1', 'Transferred', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(87, '16', '1', 'Sold', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(88, '16', '1', 'Hold', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(89, '16', '1', 'Closed', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(90, '16', '1', 'Accepted', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(753, '6', '1', 'Supreme Serenity', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(524, '23', '1', '108', 1, 1, 1, '2020-11-09', NULL, '2020-12-22', NULL),
(525, '24', '1', '500', 1, 1, 1, '2020-11-09', NULL, '2021-02-02', NULL),
(529, '25', '1', '24', 1, 1, 1, '2020-11-12', NULL, '2020-11-24', NULL),
(555, '5', '1', 'Uttara', 1, 1, 1, '2020-11-24', NULL, NULL, NULL),
(531, '2', '1', 'Newspaper', 0, 1, 1, '2020-11-16', NULL, '2020-12-07', NULL),
(618, '2', '1', 'Employee Referal Program', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(617, '2', '1', 'Walking', 1, 1, 1, '2020-12-08', NULL, NULL, NULL),
(109, '17', '1', 'Today Follow up List', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(110, '17', '1', 'Missed Follow up', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(111, '17', '1', 'Next Follow up', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(112, '2', '1', 'Facebook', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(113, '2', '1', 'Web Site', 1, 1, 1, '2020-01-04', NULL, '2020-12-07', NULL),
(114, '2', '1', 'Email', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(115, '2', '1', 'Youtube', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(116, '2', '1', 'Linkedin', 1, 1, 1, '2020-01-04', NULL, NULL, NULL),
(532, '2', '1', 'TV', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(533, '2', '1', 'Bill Board', 1, 1, 1, '2020-11-16', NULL, '2020-12-07', NULL),
(534, '2', '1', 'Voice SMS', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(535, '2', '1', 'Fencing', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(536, '2', '1', 'Door to Door', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(537, '2', '1', 'Existing Customer', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(538, '2', '1', 'Direct Sales Person', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(548, '26', '1', 'Meeting Set', 1, 1, 1, '2020-11-18', NULL, NULL, NULL),
(540, '2', '1', 'Radio', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(541, '2', '1', 'Fair', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(742, '2', '1', 'Data Base', 1, 1, 1, '2021-01-12', NULL, NULL, NULL),
(674, '21', '1', 'Bakoliya', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(675, '21', '1', 'Bandar', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(678, '21', '1', 'Chaowkbazar', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(612, '2', '1', 'Data Base', 0, 1, 1, '2020-12-07', NULL, '2021-01-12', NULL),
(613, '2', '1', 'Direct Sales Person', 0, 1, 1, '2020-12-07', NULL, '2020-12-07', NULL),
(614, '2', '1', 'Head Office', 1, 1, 1, '2020-12-07', NULL, NULL, NULL),
(615, '2', '1', 'Online Paper', 1, 1, 1, '2020-12-07', NULL, NULL, NULL),
(616, '2', '1', 'Paper Ads', 1, 1, 1, '2020-12-07', NULL, NULL, NULL),
(677, '21', '1', 'Chandgaon', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(676, '21', '1', 'Bhujpur', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(679, '21', '1', 'Chittagong Kotwali', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(680, '21', '1', 'Double Mooring', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(681, '21', '1', 'Halishahar', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(682, '21', '1', 'Karnaphuli', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(683, '21', '1', 'Khulshi', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(684, '21', '1', 'Pahartali', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(685, '21', '1', 'Panchlaish', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(686, '21', '1', 'Patenga', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(687, '21', '1', 'Sadarghat', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(688, '21', '1', 'ADABOR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(689, '21', '1', 'BADDA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(690, '21', '1', 'BANGSHAL', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(691, '21', '1', 'BIMAN BANDAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(692, '21', '1', 'BANANI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(693, '21', '1', 'CANTONMENT', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(694, '21', '1', 'CHAK BAZAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(695, '21', '1', 'DAKSHINKHAN', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(608, '29', '3', 'SGL', 1, 1, 1, '2020-11-30', NULL, NULL, NULL),
(609, '4', '1', 'Undefined', 1, 1, 1, '2020-12-07', NULL, '2020-12-07', NULL),
(611, '7', '1', 'Undefined', 1, 1, 1, '2020-12-07', NULL, NULL, NULL),
(696, '21', '1', 'DARUS SALAM', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(697, '21', '1', 'DEMRA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(698, '21', '1', 'DHAMRAI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(699, '21', '1', 'DHANMONDI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(700, '21', '1', 'DOHAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(701, '21', '1', 'BHASAN TEK', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(702, '21', '1', 'BHATARA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(703, '21', '1', 'GENDARIA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(704, '21', '1', 'GULSHAN', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(705, '21', '1', 'HAZARIBAGH', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(706, '21', '1', 'JATRABARI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(707, '21', '1', 'KAFRUL', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(708, '21', '1', 'KADAMTALI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(709, '21', '1', 'KALABAGAN', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(710, '21', '1', 'KAMRANGIR CHAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(711, '21', '1', 'KHILGAON', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(712, '21', '1', 'KHILKHET', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(713, '21', '1', 'KERANIGANJ', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(714, '21', '1', 'KOTWALI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(715, '21', '1', 'LALBAGH', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(716, '21', '1', 'MIRPUR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(717, '21', '1', 'MOHAMMADPUR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(718, '21', '1', 'MOTIJHEEL', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(719, '21', '1', 'MUGDA PARA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(720, '21', '1', 'NAWABGANJ', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(721, '21', '1', 'NEW MARKET', 1, 1, 1, '2021-01-11', NULL, '2021-01-11', NULL),
(722, '21', '1', 'PALLABI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(440, '1', '1', 'Admin', 1, 1, 1, '2020-07-18', NULL, NULL, NULL),
(723, '21', '1', 'PALTAN', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(724, '21', '1', 'RAMNA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(725, '21', '1', 'RAMPURA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(726, '21', '1', 'SABUJBAGH', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(727, '21', '1', 'RUPNAGAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(728, '21', '1', 'SAVAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(729, '21', '1', 'SHAHJAHANPUR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(730, '21', '1', 'SHAH ALI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(731, '21', '1', 'SHAHBAGH', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(732, '21', '1', 'SHYAMPUR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(733, '21', '1', 'SHER-E-BANGLA NAGAR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(734, '21', '1', 'SUTRAPUR', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(735, '21', '1', 'TEJGAON', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(736, '21', '1', 'TEJGAON IND. AREA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(737, '21', '1', 'TURAG', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(738, '21', '1', 'UTTARA PASCHIM', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(739, '21', '1', 'UTTARA PURBA', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(766, '5', '1', 'Dilu Road', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(765, '5', '1', 'Jahanara Garden', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(764, '5', '1', 'Kalabagan', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(542, '2', '1', 'Event', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(763, '5', '1', 'Rayerbazar', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(762, '5', '1', 'Zafrabad', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(761, '5', '1', 'Pallabi', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(760, '5', '1', 'Uttara 10', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(547, '2', '1', 'AdWord', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(759, '5', '1', 'Kallyanpur', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(627, '2', '1', 'Employee Referal Program', 1, 1, 1, '2020-12-27', NULL, NULL, NULL),
(626, '2', '1', 'Head Office', 1, 1, 1, '2020-12-27', NULL, NULL, NULL),
(624, '2', '1', 'Leaflet', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(752, '6', '1', 'Heritage De Zarina', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(751, '6', '1', 'Aras Dram', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(750, '6', '1', 'Mohsena Terrace', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(561, '7', '1', '100-500', 1, 1, 1, '2020-11-24', NULL, '2020-11-28', NULL),
(562, '7', '1', '500-1000', 1, 1, 1, '2020-11-24', NULL, '2020-11-28', NULL),
(623, '2', '1', 'REHAB Winter Fair', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(622, '2', '1', 'Rental Offer', 1, 1, 1, '2020-12-09', NULL, NULL, NULL),
(543, '2', '1', 'Bikroy', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(549, '26', '1', 'Visit Set', 1, 1, 1, '2020-11-18', NULL, NULL, NULL),
(545, '2', '1', 'Colleague', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(546, '2', '1', 'Existing Client', 1, 1, 1, '2020-11-16', NULL, NULL, NULL),
(605, '16', '1', 'Higher Prospect', 1, 1, 1, '2020-11-29', NULL, NULL, NULL),
(749, '6', '1', 'Verde Solace', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(748, '6', '1', 'Sneho Chaya', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(747, '6', '1', 'Castle La Blanca', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(597, '7', '1', '1000-1500', 1, 1, 1, '2020-11-28', NULL, '2020-11-28', NULL),
(598, '7', '1', '1500-2000', 1, 1, 1, '2020-11-28', NULL, '2020-11-28', NULL),
(599, '7', '1', '2000-2500', 1, 1, 1, '2020-11-28', NULL, '2020-11-28', NULL),
(473, '9', '1', 'Hollow Blocks Sources - Flyers', 1, 1, 1, '2020-08-18', NULL, '2020-08-18', NULL),
(606, '29', '1', 'MQL', 1, 1, 1, '2020-11-30', NULL, NULL, NULL),
(595, '7', '1', '2500-3000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(746, '6', '1', 'Castle La Paz', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(593, '7', '1', '3000-3500', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(596, '7', '1', '3500-4000', 1, 1, 1, '2020-11-28', NULL, '2020-11-28', NULL),
(592, '7', '1', '4000-4500', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(591, '7', '1', '4500-5000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(590, '7', '1', '5000-5500', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(589, '7', '1', '5500-6000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(588, '7', '1', '6000-6500', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(587, '7', '1', '6500-7353', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(586, '7', '1', '14000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(585, '7', '1', '20,000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(584, '7', '1', '2,25,000', 1, 1, 1, '2020-11-26', NULL, '2020-11-28', NULL),
(583, '4', '1', 'Unimass Projects', 1, 1, 1, '2020-11-26', NULL, NULL, NULL),
(778, '18', '1', 'Project-2', 1, 1, 1, '2021-02-07', NULL, NULL, NULL),
(777, '18', '1', 'Project-1', 1, 1, 1, '2021-02-07', NULL, NULL, NULL),
(607, '29', '2', 'Walk In', 1, 1, 1, '2020-11-30', NULL, NULL, NULL),
(776, '6', '1', 'Project-1', 1, 1, 1, '2021-02-07', NULL, NULL, NULL),
(512, '19', '1', 'Dhaka', 1, 1, 1, '2020-11-05', NULL, NULL, NULL),
(513, '19', '1', 'Tangail', 1, 1, 1, '2020-11-05', NULL, NULL, NULL),
(514, '20', '1', 'Banani Thana', 1, 1, 1, '2020-11-05', NULL, NULL, NULL),
(758, '5', '1', 'West Dhanmondi', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(516, '2', '1', 'Instagram', 1, 1, 1, '2020-11-07', NULL, NULL, NULL),
(517, '2', '1', 'SMS', 1, 1, 1, '2020-11-07', NULL, NULL, NULL),
(740, '21', '1', 'UTTAR KHAN', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(741, '21', '1', 'WARI', 1, 1, 1, '2021-01-11', NULL, NULL, NULL),
(551, '1', '1', 'DFS', 1, 1, 1, '2020-11-24', NULL, NULL, NULL),
(754, '6', '1', 'Barakat Elegant Ridge', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(755, '6', '1', 'Jubilant Edifice', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(756, '6', '1', 'Saad Grandeur', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(757, '6', '1', 'Dale Adenia', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(673, '21', '1', 'Baizid', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(672, '21', '1', 'Akbor Sha', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(671, '21', '1', 'Template:Dhaka City Labelled Map', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(670, '21', '1', 'Wari Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(669, '21', '1', 'Vatara Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(668, '21', '1', 'Uttara (Town)', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(667, '21', '1', 'Uttar Khan Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(666, '21', '1', 'Turag Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(665, '21', '1', 'Tejgaon Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(664, '21', '1', 'Tejgaon Industrial Area Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(663, '21', '1', 'Sutrapur Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(662, '21', '1', 'Shyampur Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(661, '21', '1', 'Sher-e-Bangla Nagar', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(660, '21', '1', 'Shahbag', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(659, '21', '1', 'Shah Ali Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(658, '21', '1', 'Sabujbagh Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(657, '21', '1', 'Rampura Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(656, '21', '1', 'Ramna Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(655, '21', '1', 'Panthapath', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(654, '21', '1', 'Paltan', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(653, '21', '1', 'Pallabi Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(652, '21', '1', 'New Market Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(651, '21', '1', 'Motijheel Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(650, '21', '1', 'Mohammadpur Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(649, '21', '1', 'Mirpur Model Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(648, '21', '1', 'Lalbagh Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(647, '21', '1', 'Kotwali Thana (Dhaka)', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(646, '21', '1', 'Khilkhet Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(645, '21', '1', 'Khilgaon Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(644, '21', '1', 'Kamrangirchar Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(643, '21', '1', 'Kalabagan', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(642, '21', '1', 'Kafrul Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(641, '21', '1', 'Kadamtali Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(640, '21', '1', 'Hazaribagh Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(639, '21', '1', 'Gulshan Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(638, '21', '1', 'Gendaria Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(637, '21', '1', 'Dhanmondi Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(636, '21', '1', 'Demra Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(635, '21', '1', 'Darus Salam Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(634, '21', '1', 'Chowkbazar Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(633, '21', '1', 'Cantonment Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(632, '21', '1', 'Bimanbandar Thana (Dhaka)', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(631, '21', '1', 'Bangsal Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(630, '21', '1', 'Badda Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(629, '21', '1', 'Azampur', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(628, '21', '1', 'Adabar Thana', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(582, '21', '1', 'Uttara', 1, 1, 1, '2020-11-26', NULL, NULL, NULL),
(515, '21', '1', 'Banani', 0, 1, 1, '2020-11-05', NULL, '2020-11-26', NULL),
(767, '10', '1', 'Govt. Service Holder', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(768, '10', '1', 'Businessman', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(769, '10', '1', 'FCA', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(770, '10', '1', 'Lawyer', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(771, '10', '1', 'Athletic', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(772, '10', '1', 'Professor', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(773, '10', '1', 'Artist', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(774, '10', '1', 'Others', 1, 1, 1, '2021-02-06', NULL, NULL, NULL),
(775, '1', '1', 'CSD', 1, 1, 1, '2021-02-06', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_pages`
--

CREATE TABLE `s_pages` (
  `page_pk_no` bigint(20) NOT NULL,
  `page_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `page_route` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `row_status` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `module_lookup_pk_no` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `s_pages`
--

INSERT INTO `s_pages` (`page_pk_no`, `page_name`, `page_route`, `row_status`, `created_by`, `created_at`, `updated_by`, `updated_at`, `module_lookup_pk_no`) VALUES
(1, 'Lead Entry', 'lead.index', 1, 1, '2019-11-29', NULL, NULL, 1),
(3, 'Lead Followup', 'lead_follow_up.index', 1, 1, '2019-11-29', NULL, NULL, 2),
(4, 'Lead Transfer', 'lead_transfer', 1, 1, '2019-11-29', NULL, NULL, 2),
(5, 'Search Engine', 'search_engine', 1, 1, '2019-11-29', NULL, NULL, 3),
(6, 'Lookup', 'settings.index', 1, 1, '2019-11-29', NULL, NULL, 4),
(7, 'Users', 'user.index', 1, 1, '2019-11-29', NULL, NULL, 4),
(8, 'Access Management', 'rbac', 1, 1, '2019-11-29', NULL, NULL, 4),
(9, 'Team Management', 'team.index', 1, 1, '2019-11-29', NULL, NULL, 4),
(10, 'Lead Distribution', 'lead_dist_list', 1, 1, '2019-11-29', NULL, NULL, 1),
(11, 'Project wise Flat setup', 'project_wise_flat', 1, 1, '2019-11-29', NULL, NULL, 4),
(12, 'Dashboard', 'admin.dashboard', 1, 1, '2019-11-29', NULL, NULL, 5),
(14, 'Stage wise attribute setup', 'Stage_wise_attribute_list', 1, 1, '2019-11-29', NULL, NULL, 4),
(15, 'Validation Setup', 'validation_setup', 1, 1, NULL, NULL, NULL, 4),
(16, 'Return Lead', 'return_lead', 1, 1, NULL, NULL, NULL, 1),
(17, 'Stage Wise Report', 'report.stage_wise_user_report', 1, 1, '2020-11-17', NULL, NULL, 3),
(18, 'Lead Distribution ', 'lead.lead_distribution', 1, NULL, NULL, NULL, NULL, 1),
(19, 'Daily Lead Report', 'report.daily_lead_report', 1, 1, '2020-11-21', NULL, NULL, 3),
(20, 'Source Report', 'report.source_report', 1, 1, NULL, NULL, NULL, 3),
(21, 'Project Wise Report', 'report.project_report', 1, 1, NULL, NULL, NULL, 3),
(22, 'Blocked Lead List', 'block_list_lead', 1, NULL, NULL, NULL, NULL, 1),
(23, 'Junk Lead List', 'junk_work_list', 1, NULL, '2020-12-13', NULL, NULL, 1),
(24, 'Feasibility  Approval', 'note_sheet_list', NULL, NULL, NULL, NULL, NULL, 1),
(25, 'District & Upazila Setup', 'district_thana_setup', NULL, NULL, NULL, NULL, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `s_projectwiseflatlist`
--

CREATE TABLE `s_projectwiseflatlist` (
  `flatlist_pk_no` bigint(20) NOT NULL,
  `project_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_lookup_pk_no` bigint(20) DEFAULT NULL,
  `area_lookup_pk_no` bigint(20) DEFAULT NULL,
  `size_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_description` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_asking_price` double DEFAULT NULL,
  `flat_down_payment` double DEFAULT NULL,
  `flat_installment` double DEFAULT NULL,
  `flat_number_installment` int(11) DEFAULT NULL,
  `flat_status` int(11) DEFAULT NULL,
  `block_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `s_projectwiseflatlist`
--

INSERT INTO `s_projectwiseflatlist` (`flatlist_pk_no`, `project_lookup_pk_no`, `flat_id`, `flat_name`, `category_lookup_pk_no`, `area_lookup_pk_no`, `size_lookup_pk_no`, `flat_description`, `flat_asking_price`, `flat_down_payment`, `flat_installment`, `flat_number_installment`, `flat_status`, `block_status`, `c_pk_no_created`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`) VALUES
(1, 746, NULL, 'A-6', 583, 758, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(2, 746, NULL, 'A-7', 583, 758, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(3, 746, NULL, 'A-8', 583, 758, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(4, 746, NULL, 'C-9', 583, 758, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(5, 747, NULL, 'A-2', 583, 758, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(6, 747, NULL, 'B-2', 583, 758, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(7, 747, NULL, 'B-4', 583, 758, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(8, 747, NULL, 'B-5', 583, 758, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(9, 748, NULL, 'A-5', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(10, 748, NULL, 'A-6', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(11, 748, NULL, 'B-5', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(12, 748, NULL, 'B-6', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(13, 748, NULL, 'C-3', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(14, 748, NULL, 'C-4', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(15, 748, NULL, 'C-5', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(16, 748, NULL, 'C-6', 583, 759, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(17, 749, NULL, 'A-2', 583, 760, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(18, 749, NULL, 'A-3', 583, 760, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(19, 749, NULL, 'A-6', 583, 760, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(20, 749, NULL, 'A-7', 583, 760, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(21, 750, NULL, 'AB-7', 583, 761, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(22, 751, NULL, 'AB-6', 583, 762, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(23, 751, NULL, 'AB-7', 583, 762, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(24, 751, NULL, 'AB-8', 583, 762, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(25, 751, NULL, 'AB-9', 583, 762, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(26, 752, NULL, 'A-2', 583, 763, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(27, 752, NULL, 'B-2', 583, 763, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(28, 752, NULL, 'B-4', 583, 763, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(29, 752, NULL, 'B-6', 583, 763, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(30, 752, NULL, 'B-7', 583, 763, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(31, 753, NULL, 'A-2', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(32, 753, NULL, 'A-3', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(33, 753, NULL, 'A-4', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(34, 753, NULL, 'A-6', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(35, 753, NULL, 'B-2', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(36, 753, NULL, 'B-3', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(37, 753, NULL, 'B-6', 583, 760, 597, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(38, 754, NULL, 'A-2', 583, 761, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(39, 754, NULL, 'A-5', 583, 761, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(40, 754, NULL, 'A-7', 583, 761, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(41, 755, NULL, 'A-2', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(42, 755, NULL, 'A-4', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(43, 755, NULL, 'A-6', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(44, 755, NULL, 'A-8', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(45, 755, NULL, 'A-3', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(46, 755, NULL, 'A-5', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(47, 755, NULL, 'A-7', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(48, 755, NULL, 'A-9', 583, 764, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(49, 756, NULL, 'A 2', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(50, 756, NULL, 'A 3', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(51, 756, NULL, 'A 6', 583, 765, 599, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(52, 756, NULL, 'A 8', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(53, 756, NULL, 'B 2', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(54, 756, NULL, 'B 3', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(55, 756, NULL, 'B 6', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(56, 756, NULL, 'B 8', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(57, 756, NULL, 'A 9', 583, 765, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL),
(58, 757, NULL, 'B-3', 583, 766, 598, ' ', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '2021-02-06', NULL, '2021-02-06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_rbac`
--

CREATE TABLE `s_rbac` (
  `rbac_pk_no` bigint(20) NOT NULL,
  `role_lookup_pk_no` bigint(20) NOT NULL,
  `page_pk_no` bigint(20) NOT NULL,
  `row_status` int(11) NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `s_rbac`
--

INSERT INTO `s_rbac` (`rbac_pk_no`, `role_lookup_pk_no`, `page_pk_no`, `row_status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(22, 73, 12, 1, NULL, '2020-01-04', NULL, '2020-01-04'),
(21, 73, 1, 1, NULL, '2020-01-04', NULL, '2020-01-04'),
(16, 1, 13, 1, NULL, '2020-01-02', NULL, '2020-01-02'),
(15, 1, 11, 1, NULL, '2020-01-02', NULL, '2020-01-02'),
(14, 1, 12, 1, NULL, '2020-01-02', NULL, '2020-01-02'),
(13, 1, 10, 1, NULL, '2019-12-22', NULL, '2019-12-22'),
(9, 1, 7, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(8, 1, 4, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(7, 1, 9, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(6, 1, 6, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(5, 1, 5, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(4, 1, 3, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(3, 1, 2, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(2, 1, 1, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(1, 1, 8, 1, NULL, '2019-12-14', NULL, '2019-12-14'),
(29, 73, 2, 0, NULL, '2020-01-04', NULL, '2020-01-04'),
(30, 73, 10, 0, NULL, '2020-01-04', NULL, '2020-01-04'),
(31, 73, 3, 1, NULL, '2020-01-04', NULL, '2020-01-04'),
(32, 73, 4, 1, NULL, '2020-01-04', NULL, '2020-01-04'),
(33, 73, 13, 0, NULL, '2020-01-04', NULL, '2020-01-04'),
(34, 73, 5, 0, NULL, '2020-01-04', NULL, '2020-01-04'),
(37, 73, 8, 0, NULL, '2020-01-05', NULL, '2020-01-05'),
(38, 73, 9, 0, NULL, '2020-01-05', NULL, '2020-01-05'),
(42, 77, 3, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(43, 77, 4, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(44, 77, 5, 0, NULL, '2020-01-13', NULL, '2020-01-13'),
(45, 77, 12, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(49, 77, 1, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(50, 77, 2, 0, NULL, '2020-01-13', NULL, '2020-01-13'),
(51, 77, 10, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(52, 77, 13, 1, NULL, '2020-01-13', NULL, '2020-01-13'),
(145, 440, 3, 1, NULL, '2020-12-24', NULL, '2020-12-24'),
(144, 440, 23, 1, NULL, '2020-12-17', NULL, '2020-12-17'),
(143, 551, 23, 1, NULL, '2020-12-17', NULL, '2020-12-17'),
(142, 1, 23, 1, 1, '2020-12-13', NULL, NULL),
(141, 73, 23, 0, NULL, '2020-12-13', NULL, '2020-12-13'),
(140, 551, 22, 1, NULL, '2020-12-07', NULL, '2020-12-07'),
(139, 77, 21, 1, NULL, '2020-12-07', NULL, '2020-12-07'),
(138, 551, 18, 0, NULL, '2020-12-07', NULL, '2020-12-07'),
(137, 551, 16, 0, NULL, '2020-12-07', NULL, '2020-12-07'),
(136, 551, 10, 0, NULL, '2020-12-07', NULL, '2020-12-07'),
(135, 551, 2, 0, NULL, '2020-12-07', NULL, '2020-12-07'),
(134, 551, 1, 0, NULL, '2020-12-07', NULL, '2020-12-07'),
(133, 551, 21, 1, NULL, '2020-12-07', NULL, '2020-12-07'),
(132, 1, 21, 1, NULL, NULL, NULL, NULL),
(131, 77, 17, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(130, 77, 20, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(129, 77, 19, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(128, 77, 18, 0, NULL, '2020-11-24', NULL, '2020-11-24'),
(127, 551, 5, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(126, 551, 17, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(125, 551, 19, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(124, 551, 20, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(123, 551, 12, 1, NULL, '2020-11-24', NULL, '2020-11-24'),
(122, 73, 20, 1, NULL, '2020-11-23', NULL, '2020-11-23'),
(110, 1, 14, 1, NULL, NULL, NULL, NULL),
(121, 73, 19, 1, NULL, '2020-11-23', NULL, '2020-11-23'),
(120, 73, 17, 1, NULL, '2020-11-23', NULL, '2020-11-23'),
(113, 73, 15, 0, NULL, '2020-11-12', NULL, '2020-11-12'),
(114, 73, 16, 1, NULL, '2020-11-12', NULL, '2020-11-12'),
(115, 1, 17, 1, NULL, '2020-11-17', NULL, NULL),
(116, 73, 18, 1, NULL, '2020-11-18', NULL, '2020-11-18'),
(117, 1, 19, 1, 1, '2020-11-21', NULL, NULL),
(118, 1, 20, 1, NULL, NULL, NULL, NULL),
(119, 1, 15, 1, NULL, NULL, NULL, NULL),
(146, 77, 24, 0, NULL, '2021-02-15', NULL, '2021-02-15'),
(147, 73, 24, 1, NULL, '2021-02-15', NULL, '2021-02-15'),
(148, 551, 24, 1, NULL, '2021-02-15', NULL, '2021-02-15'),
(149, 1, 25, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_teamuser`
--

CREATE TABLE `s_teamuser` (
  `team_pk_no` bigint(20) NOT NULL,
  `role_lookup_pk_no` bigint(20) DEFAULT NULL,
  `user_pk_no` bigint(20) DEFAULT NULL,
  `is_team_leader` int(11) DEFAULT NULL,
  `row_status` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `s_user`
--

CREATE TABLE `s_user` (
  `user_pk_no` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `User_name` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fullname` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_lookup_pk_no` bigint(20) DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_photo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `designation` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `corporate_mobile_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `row_status` int(11) DEFAULT 1,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  `is_bypass` int(11) DEFAULT NULL,
  `bypass_date` date DEFAULT NULL,
  `user_type` int(11) DEFAULT 0,
  `is_super_admin` int(11) DEFAULT NULL,
  `auto_distribute` int(11) DEFAULT 0,
  `distribute_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `s_user`
--

INSERT INTO `s_user` (`user_pk_no`, `user_id`, `User_name`, `user_fullname`, `employee_id`, `role_lookup_pk_no`, `email_id`, `mobile_no`, `address`, `nid`, `user_photo`, `designation`, `corporate_mobile_number`, `row_status`, `c_pk_no_created`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`, `is_bypass`, `bypass_date`, `user_type`, `is_super_admin`, `auto_distribute`, `distribute_date`) VALUES
(15, 24, 'NEXTGENiT Super Amin', 'NEXTGENiT Super Amin', '', 1, 'admin@app.com', '01882348340', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2020-12-14', NULL, NULL, NULL, 0, 1, 0, NULL),
(304, 327, 'Mr. Mozaher Uddin', 'Mr. Mozaher Uddin', '', 551, 'mozaher.uddin@unimassbd.com', '01755-500 117', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'DFS', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(305, 328, 'Mr. S M Shamim Rahman', 'Mr. S M Shamim Rahman', '', 73, 'shamim.rahman@unimassbd.com', '01313-714 350', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'GM', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 1, NULL, 0, NULL),
(306, 329, 'Mr. Md. Abul Kalam Azad', 'Mr. Md. Abul Kalam Azad', '', 77, 'abul.kalam@unimassbd.com', '01755-598 788', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'AGM', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(307, 330, 'Mr. Md. Mahmudur Rahman', 'Mr. Md. Mahmudur Rahman', '', 775, 'rahman786', '01321-137 555', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'AM', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 3, NULL, 0, NULL),
(308, 331, 'Mr. Md. Asifuzzaman Khan', 'Mr. Md. Asifuzzaman Khan', '', 77, 'asifkhan786', '01775-499 085', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'AM', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(309, 332, 'Mr. Md. Ismail Hossain', 'Mr. Md. Ismail Hossain', '', 77, 'ismail786', '01313-714 348', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Sr.Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(310, 333, 'Mr. Tanvir Ahmed Lipon', 'Mr. Tanvir Ahmed Lipon', '', 77, 'lipon786', '01321-137 554', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(311, 334, 'Mr. Md. Arafat Rahamn', 'Mr. Md. Arafat Rahamn', '', 77, 'arafat786', '01321-137 556', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(312, 335, 'Mr. Md. Habibur Rahman', 'Mr. Md. Habibur Rahman', '', 77, 'habib786', '01321-137 552', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(313, 336, 'Mr. S.M. Jauhan Uddin', 'Mr. S.M. Jauhan Uddin', '', 77, 'jauhan786', '01321-137 553', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL),
(314, 337, 'Mr. Md. Sadman Sakib', 'Mr. Md. Sadman Sakib', '', 77, 'sadman786', '01313-714 347', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, 'Jr.Exe', NULL, 1, NULL, NULL, '2021-02-06', NULL, '2021-02-06', NULL, NULL, NULL, 2, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_eventlog`
--

CREATE TABLE `t_eventlog` (
  `event_pk_no` bigint(20) NOT NULL,
  `event_dttm` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_eventlog_detail`
--

CREATE TABLE `t_eventlog_detail` (
  `eventdtl_pk_no` bigint(20) NOT NULL,
  `eventdtl_dttm` date DEFAULT NULL,
  `affected_lead_pk_no` bigint(20) DEFAULT NULL,
  `sales_agent_pk_no_old` bigint(20) DEFAULT NULL,
  `sales_agent_assigned_dt_old` date DEFAULT NULL,
  `sales_agent_pk_no_new` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `t_lead2lifecycle_vw`
-- (See below for the actual view)
--
CREATE TABLE `t_lead2lifecycle_vw` (
`lead_pk_no` bigint(20)
,`lead_id` varchar(30)
,`customer_firstname` varchar(200)
,`customer_lastname` varchar(200)
,`customer_firstname2` varchar(200)
,`customer_lastname2` varchar(200)
,`phone1_code` varchar(10)
,`phone1` varchar(200)
,`phone2_code` varchar(10)
,`phone2` varchar(200)
,`email_id` varchar(200)
,`occupation_pk_no` bigint(20)
,`occup_name` varchar(200)
,`organization_pk_no` varchar(500)
,`org_name` varchar(200)
,`project_category_pk_no` bigint(20)
,`project_category_name` varchar(200)
,`project_area_pk_no` bigint(20)
,`project_area` varchar(200)
,`Project_pk_no` bigint(20)
,`project_name` varchar(200)
,`project_size_pk_no` bigint(20)
,`project_size` varchar(200)
,`flatlist_pk_no` bigint(20)
,`source_auto_pk_no` bigint(20)
,`user_full_name` varchar(500)
,`source_auto_usergroup_pk_no` bigint(20)
,`source_auto_usergroup` varchar(200)
,`source_auto_sub` varchar(200)
,`source_sac_name` varchar(200)
,`source_sac_note` varchar(200)
,`source_digital_marketing` varchar(30)
,`source_hotline` varchar(30)
,`source_internal_reference` varchar(30)
,`source_ir_emp_id` int(11)
,`source_ir_name` varchar(200)
,`source_ir_position` varchar(200)
,`source_ir_contact_no` int(11)
,`source_sales_executive` bigint(20)
,`remarks` text
,`Customer_dateofbirth` date
,`customer_wife_name` varchar(200)
,`customer_wife_dataofbirth` date
,`Marriage_anniversary` date
,`children_name1` varchar(200)
,`children_dateofbirth1` date
,`children_name2` varchar(200)
,`children_dateofbirth2` date
,`children_name3` varchar(200)
,`children_dateofbirth3` date
,`cust_designation` varchar(200)
,`pre_holding_no` varchar(100)
,`pre_road_no` varchar(100)
,`pre_area` bigint(20)
,`pre_district` bigint(20)
,`pre_thana` bigint(20)
,`pre_size` varchar(100)
,`per_holding_no` varchar(100)
,`per_road_no` varchar(100)
,`per_area` bigint(20)
,`per_district` bigint(20)
,`per_thana` bigint(20)
,`office_holding_no` varchar(20)
,`office_road_no` varchar(20)
,`office_area` bigint(20)
,`office_district` bigint(20)
,`office_thana` bigint(20)
,`meeting_status` int(11)
,`meeting_date` date
,`meeting_time` datetime
,`food_habit` varchar(200)
,`political_opinion` varchar(200)
,`car_preference` varchar(200)
,`color_preference` varchar(200)
,`hobby` varchar(200)
,`traveling_history` varchar(200)
,`member_of_club` varchar(200)
,`child_education` varchar(200)
,`disease_name` varchar(200)
,`created_by` bigint(20)
,`created_at` datetime
,`leadlifecycle_pk_no` bigint(20)
,`leadlifecycle_id` varchar(30)
,`lead_dist_type` int(11)
,`lead_sales_agent_pk_no` bigint(20)
,`lead_sales_agent_assign_dt` date
,`lead_cluster_head_assign_dt` date
,`lead_cluster_head_pk_no` int(11)
,`is_block` int(11)
,`is_approved` int(11)
,`is_approved_by` int(11)
,`lead_cluster_head_name` varchar(500)
,`lead_sales_agent_name` varchar(500)
,`lead_sales_agent_number` varchar(50)
,`role_lookup_pk_no` bigint(20)
,`user_group_name` varchar(200)
,`lead_current_stage` int(11)
,`lead_current_stage_name` varchar(200)
,`lead_qc_flag` int(11)
,`lead_qc_datetime` date
,`lead_qc_by` bigint(20)
,`lead_k1_flag` int(11)
,`lead_k1_datetime` date
,`lead_k1_by` bigint(20)
,`lead_hp_flag` int(11)
,`lead_hp_datetime` date
,`lead_hp_by` int(11)
,`lead_priority_flag` int(11)
,`lead_priority_datetime` date
,`lead_priority_by` bigint(20)
,`lead_hold_flag` int(11)
,`lead_hold_datetime` date
,`lead_hold_by` bigint(20)
,`lead_closed_flag` int(11)
,`lead_closed_datetime` date
,`lead_closed_by` bigint(20)
,`lead_sold_flag` int(11)
,`lead_sold_datetime` date
,`lead_sold_by` bigint(20)
,`lead_sold_date_manual` date
,`lead_sold_flatcost` float
,`lead_sold_utilitycost` float
,`lead_sold_parkingcost` float
,`lead_sold_customer_pk_no` bigint(20)
,`lead_sold_sales_agent_pk_no` bigint(20)
,`lead_sold_team_lead_pk_no` bigint(20)
,`lead_sold_team_manager_pk_no` bigint(20)
,`lead_transfer_flag` int(11)
,`lead_entry_type` varchar(10)
,`lead_dist_by` int(11)
,`distribute_to` int(11)
,`junk_ind` int(11)
,`lead_transfer_from_sales_agent_pk_no` bigint(20)
,`lead_reserve_money` float
,`is_note_sheet_approved` int(11)
,`all_phone_no` char(0)
);

-- --------------------------------------------------------

--
-- Table structure for table `t_leadfollowup`
--

CREATE TABLE `t_leadfollowup` (
  `lead_followup_pk_no` bigint(20) NOT NULL,
  `lead_followup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_followup_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `Followup_type_pk_no` bigint(20) DEFAULT NULL,
  `followup_Note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_stage_before_followup` int(11) DEFAULT NULL,
  `next_followup_flag` int(11) DEFAULT NULL,
  `Next_FollowUp_date` date DEFAULT NULL,
  `next_followup_Prefered_Time` datetime DEFAULT NULL,
  `next_followup_Note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_stage_after_followup` int(11) DEFAULT NULL,
  `meeting_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `meeting_time` datetime DEFAULT NULL,
  `visit_meeting_done` int(11) DEFAULT NULL,
  `visit_meeting_done_dt` date DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_leadfollowup`
--

INSERT INTO `t_leadfollowup` (`lead_followup_pk_no`, `lead_followup_id`, `lead_followup_datetime`, `lead_pk_no`, `Followup_type_pk_no`, `followup_Note`, `lead_stage_before_followup`, `next_followup_flag`, `Next_FollowUp_date`, `next_followup_Prefered_Time`, `next_followup_Note`, `lead_stage_after_followup`, `meeting_status`, `meeting_date`, `meeting_time`, `visit_meeting_done`, `visit_meeting_done_dt`, `c_pk_no_created`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`) VALUES
(1, NULL, '1970-01-01', 4, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(2, NULL, '1970-01-01', 7, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(3, NULL, '1970-01-01', 9, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(4, NULL, '1970-01-01', 11, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(5, NULL, '1970-01-01', 15, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(6, NULL, '1970-01-01', 18, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(7, NULL, '1970-01-01', 20, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(8, NULL, '1970-01-01', 22, 0, '', 7, 1, '1970-01-01', NULL, '', 7, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(9, '1', '2021-02-24', 2, 0, 'abc abc abc abc abc abc abc', 1, 1, '2021-02-26', '2021-02-26 11:45:00', 'abc abc abc abc abc', 1, '548', '2021-02-28', '2021-02-28 11:45:00', 0, '1970-01-01', 1, 308, '2021-02-24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_leadfollowup_attribute`
--

CREATE TABLE `t_leadfollowup_attribute` (
  `followup_attr_pk_no` bigint(20) NOT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `attr_pk_no` bigint(20) DEFAULT NULL,
  `attr_type` bigint(20) DEFAULT NULL COMMENT 'Checkbox/Date/Text',
  `attr_value` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `row_status` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT 0,
  `created_at` date DEFAULT NULL,
  `updated_by` int(11) DEFAULT 0,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadkychistory`
--

CREATE TABLE `t_leadkychistory` (
  `t_leadkyc_pk_no` int(11) NOT NULL,
  `lead_pk_no` int(11) DEFAULT NULL,
  `lead_id` varchar(200) DEFAULT NULL,
  `Customer_dateofbirth` date DEFAULT NULL,
  `Customer_dateofbirth2` date DEFAULT NULL,
  `customer_wife_name` varchar(200) DEFAULT NULL,
  `customer_wife_dataofbirth` date DEFAULT NULL,
  `Marriage_anniversary` date DEFAULT NULL,
  `children_name1` varchar(200) DEFAULT NULL,
  `children_dateofbirth1` date DEFAULT NULL,
  `children_name2` varchar(200) DEFAULT NULL,
  `children_dateofbirth2` date DEFAULT NULL,
  `children_name3` varchar(200) DEFAULT NULL,
  `children_dateofbirth3` date DEFAULT NULL,
  `food_habit` varchar(200) DEFAULT NULL,
  `political_opinion` varchar(200) DEFAULT NULL,
  `car_preference` varchar(200) DEFAULT NULL,
  `color_preference` varchar(200) DEFAULT NULL,
  `hobby` varchar(200) DEFAULT NULL,
  `traveling_history` varchar(200) DEFAULT NULL,
  `member_of_club` varchar(200) DEFAULT NULL,
  `child_education` varchar(200) DEFAULT NULL,
  `disease_name` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadlifecycle`
--

CREATE TABLE `t_leadlifecycle` (
  `leadlifecycle_pk_no` bigint(20) NOT NULL,
  `leadlifecycle_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_entry_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_dist_type` int(11) DEFAULT 0,
  `lead_dist_by` int(11) DEFAULT NULL,
  `lead_cluster_head_pk_no` int(11) DEFAULT NULL,
  `lead_cluster_head_assign_dt` date DEFAULT NULL,
  `lead_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_sales_agent_assign_dt` date DEFAULT NULL,
  `lead_current_stage` int(11) DEFAULT NULL,
  `lead_qc_flag` int(11) DEFAULT NULL,
  `lead_qc_datetime` date DEFAULT NULL,
  `lead_qc_by` bigint(20) DEFAULT NULL,
  `lead_k1_flag` int(11) DEFAULT NULL,
  `lead_k1_datetime` date DEFAULT NULL,
  `lead_k1_by` bigint(20) DEFAULT NULL,
  `lead_hp_flag` int(11) DEFAULT NULL,
  `lead_hp_datetime` date DEFAULT NULL,
  `lead_hp_by` int(11) DEFAULT NULL,
  `lead_priority_flag` int(11) DEFAULT NULL,
  `lead_priority_datetime` date DEFAULT NULL,
  `lead_priority_by` bigint(20) DEFAULT NULL,
  `lead_hold_flag` int(11) DEFAULT NULL,
  `lead_hold_datetime` date DEFAULT NULL,
  `lead_hold_by` bigint(20) DEFAULT NULL,
  `lead_closed_flag` int(11) DEFAULT NULL,
  `lead_closed_datetime` date DEFAULT NULL,
  `lead_closed_by` bigint(20) DEFAULT NULL,
  `lead_sold_flag` int(11) DEFAULT 0,
  `flatlist_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_datetime` date DEFAULT NULL,
  `lead_sold_by` bigint(20) DEFAULT NULL,
  `lead_sold_date_manual` date DEFAULT NULL,
  `lead_sold_flatcost` float DEFAULT NULL,
  `lead_sold_utilitycost` float DEFAULT NULL,
  `lead_sold_parkingcost` float DEFAULT NULL,
  `lead_sold_bookingmoney` int(11) DEFAULT NULL,
  `lead_sold_agreement_status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_sold_customer_pk_no` bigint(20) DEFAULT NULL,
  `lead_reserve_money` float DEFAULT NULL,
  `lead_sold_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_manager_pk_no` bigint(20) DEFAULT NULL,
  `lead_transfer_flag` int(11) DEFAULT NULL,
  `lead_transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `is_note_sheet_approved` int(11) DEFAULT 0,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `flat_id` int(11) DEFAULT NULL,
  `is_block` int(11) DEFAULT NULL,
  `is_approved_by` int(11) DEFAULT NULL,
  `is_approved` int(11) DEFAULT NULL,
  `junk_ind` int(11) DEFAULT NULL,
  `distribute_to` int(11) DEFAULT NULL,
  `is_rejected` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  `script` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_leadlifecycle`
--

INSERT INTO `t_leadlifecycle` (`leadlifecycle_pk_no`, `leadlifecycle_id`, `lead_pk_no`, `lead_entry_type`, `lead_dist_type`, `lead_dist_by`, `lead_cluster_head_pk_no`, `lead_cluster_head_assign_dt`, `lead_sales_agent_pk_no`, `lead_sales_agent_assign_dt`, `lead_current_stage`, `lead_qc_flag`, `lead_qc_datetime`, `lead_qc_by`, `lead_k1_flag`, `lead_k1_datetime`, `lead_k1_by`, `lead_hp_flag`, `lead_hp_datetime`, `lead_hp_by`, `lead_priority_flag`, `lead_priority_datetime`, `lead_priority_by`, `lead_hold_flag`, `lead_hold_datetime`, `lead_hold_by`, `lead_closed_flag`, `lead_closed_datetime`, `lead_closed_by`, `lead_sold_flag`, `flatlist_pk_no`, `lead_sold_datetime`, `lead_sold_by`, `lead_sold_date_manual`, `lead_sold_flatcost`, `lead_sold_utilitycost`, `lead_sold_parkingcost`, `lead_sold_bookingmoney`, `lead_sold_agreement_status`, `lead_sold_customer_pk_no`, `lead_reserve_money`, `lead_sold_sales_agent_pk_no`, `lead_sold_team_lead_pk_no`, `lead_sold_team_manager_pk_no`, `lead_transfer_flag`, `lead_transfer_from_sales_agent_pk_no`, `is_note_sheet_approved`, `c_pk_no_created`, `flat_id`, `is_block`, `is_approved_by`, `is_approved`, `junk_ind`, `distribute_to`, `is_rejected`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`, `script`) VALUES
(1, NULL, 1, '1', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(2, NULL, 2, '0', 0, NULL, 305, '2021-02-24', 308, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', 1, '2021-02-24', NULL, NULL),
(3, NULL, 3, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(4, NULL, 4, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-22', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(5, NULL, 5, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(6, NULL, 6, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(7, NULL, 7, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-22', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(8, NULL, 8, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(9, NULL, 9, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-22', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(10, NULL, 10, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(11, NULL, 11, '0', 0, NULL, 0, '2021-02-22', 0, '2021-02-22', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-22', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-22', NULL, NULL, NULL, NULL),
(12, NULL, 12, '1', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(13, NULL, 13, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(14, NULL, 14, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(15, NULL, 15, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-23', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(16, NULL, 16, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(17, NULL, 17, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(18, NULL, 18, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-23', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(19, NULL, 19, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(20, NULL, 20, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-23', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(21, NULL, 21, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(22, NULL, 22, '0', 0, NULL, 0, '2021-02-23', 0, '2021-02-23', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-23', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-23', NULL, NULL, NULL, NULL),
(23, NULL, 24, '1', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-02-24', NULL, NULL, NULL, NULL),
(24, NULL, 25, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(25, NULL, 26, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(26, NULL, 27, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(27, NULL, 28, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(28, NULL, 29, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(29, NULL, 30, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(30, NULL, 31, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(31, NULL, 32, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(32, NULL, 33, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL),
(33, NULL, 34, '0', 0, NULL, 0, '2021-02-24', 0, '2021-02-24', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '2021-02-24', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_leads`
--

CREATE TABLE `t_leads` (
  `lead_pk_no` bigint(20) NOT NULL,
  `lead_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_bk` int(11) DEFAULT NULL,
  `phone2_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation_pk_no` bigint(20) DEFAULT NULL,
  `organization_pk_no` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_designation` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_holding_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_road_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_area` bigint(20) DEFAULT NULL,
  `pre_district` bigint(20) DEFAULT NULL,
  `pre_thana` bigint(20) DEFAULT NULL,
  `pre_size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_holding_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_road_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_area` bigint(20) DEFAULT NULL,
  `per_district` bigint(20) DEFAULT NULL,
  `per_thana` bigint(20) DEFAULT NULL,
  `office_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_area` bigint(20) DEFAULT NULL,
  `office_district` bigint(20) DEFAULT NULL,
  `office_thana` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_usergroup_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_sub` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_digital_marketing` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_hotline` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_internal_reference` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_emp_id` int(11) DEFAULT NULL,
  `source_ir_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_position` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_contact_no` int(11) DEFAULT NULL,
  `source_sales_executive` bigint(20) DEFAULT NULL,
  `Customer_dateofbirth` date DEFAULT NULL,
  `Customer_dateofbirth2` date DEFAULT NULL,
  `customer_wife_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_wife_dataofbirth` date DEFAULT NULL,
  `Marriage_anniversary` date DEFAULT NULL,
  `children_name1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth1` date DEFAULT NULL,
  `children_name2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth2` date DEFAULT NULL,
  `children_name3` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth3` date DEFAULT NULL,
  `meeting_status` int(11) DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `meeting_time` datetime DEFAULT NULL,
  `food_habit` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `political_opinion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `car_preference` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color_preference` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hobby` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `traveling_history` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `member_of_club` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `child_education` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disease_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_leads`
--

INSERT INTO `t_leads` (`lead_pk_no`, `lead_id`, `customer_firstname`, `customer_lastname`, `customer_firstname2`, `customer_lastname2`, `phone1_code`, `phone1`, `phone1_bk`, `phone2_code`, `phone2`, `email_id`, `occupation_pk_no`, `organization_pk_no`, `cust_designation`, `pre_holding_no`, `pre_road_no`, `pre_area`, `pre_district`, `pre_thana`, `pre_size`, `per_holding_no`, `per_road_no`, `per_area`, `per_district`, `per_thana`, `office_holding_no`, `office_road_no`, `office_area`, `office_district`, `office_thana`, `project_category_pk_no`, `project_area_pk_no`, `Project_pk_no`, `project_size_pk_no`, `source_auto_pk_no`, `source_auto_usergroup_pk_no`, `source_auto_sub`, `source_sac_name`, `source_sac_note`, `source_digital_marketing`, `source_hotline`, `source_internal_reference`, `source_ir_emp_id`, `source_ir_name`, `source_ir_position`, `source_ir_contact_no`, `source_sales_executive`, `Customer_dateofbirth`, `Customer_dateofbirth2`, `customer_wife_name`, `customer_wife_dataofbirth`, `Marriage_anniversary`, `children_name1`, `children_dateofbirth1`, `children_name2`, `children_dateofbirth2`, `children_name3`, `children_dateofbirth3`, `meeting_status`, `meeting_date`, `meeting_time`, `food_habit`, `political_opinion`, `car_preference`, `color_preference`, `hobby`, `traveling_history`, `member_of_club`, `child_education`, `disease_name`, `remarks`, `c_pk_no_created`, `created_by`, `created_at`, `updated_by`, `updated_at`, `c_pk_no_updated`) VALUES
(1, 'L202121', 'Jahid', 'Hasan', 'Zabir', 'Bin Hasan', '880', '1670000000', NULL, '880', '1844525204', 'jahid0209@gmail.com', 3, 'NextGeniT', 'CTO', '17/A', '17', 0, 47, 0, '5000', '17/A', '17', 0, 47, 0, '17/A', '17', 0, 47, 0, 0, 582, 0, 591, 305, 0, '', '', '', '112', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '2020-12-06', '2020-12-06 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(2, 'L202122', 'Mr. Didarul Alam', '', '', '', '880', '1711165810', NULL, '880', '', 'dalam165810@gmail.com', 0, '4s Aviation Service', 'Proprietor', 'New-22', 'Old-83', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(3, 'L202123', 'Mr. K.M Masudur Rahman', '', '', '', '880', '1711596320', NULL, '880', '', 'masud4s@yahoo.com', 0, '4S Tours & Travels Ltd', 'Managing Director', 'House # 56 (2nd Floor)', 'Road # Garib-E-Newaz Avenue', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(4, 'L202124', 'Mr. Md. Saiful Islam', '', '', '', '880', '1812475630', NULL, '880', '', 'akazadtravels@gmail.com', 0, 'A K Azad Travel & Tours', 'Proprietor', 'Room # E1/B, (4th Floor), Islam Tower, 65', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(5, 'L202125', 'Mr.Mohammed Abul Kalam', '', '', '', '880', '1971023351', NULL, '880', '1711023351', 'amsaviation70@gmail.com', 0, 'A M S Aviation', 'Proprietor', '131 Rafatun Mansion (4th Floor)', 'DIT Extension Road', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(6, 'L202126', 'Mr.Md.Saidur Rahman (Sadek)', '', '', '', '880', '1711128748', NULL, '880', '', '', 0, 'A Matribhumi Travels International', 'Proprietor', '89/1, Kakrail Super Market (1st Floor)', '', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(7, 'L202127', 'Mr. Advocate Md. Ibrahim', '', '', '', '880', '1555026678', NULL, '880', '1710262610', 'ibrahim_atoztt@yahoo.com', 0, 'A to Z Tours & Travels', 'Proprietor', 'Akmol Mansion, Kakrail Super Market (1st Floor, Suite # 134), 89/1', 'Kakrail', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(8, 'L202128', 'Mr. M. A. Ahmed', '', '', '', '880', '1711074914', NULL, '880', '', 'ahmed01bd@yahoo.com', 0, 'A. J. Air International', 'Proprietor', 'Aziz Co-operative Market (4th Floor), 204', 'Shahid Syed Nazrul Islam Sarani', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(9, 'L202129', 'Mr. Mohammad Abul Kalam Azad', '', '', '', '880', '1713043765', NULL, '880', '', 'ak.internationaldhk@yahoo.com', 0, 'A. K International', 'Proprietor', 'House # 07 (3rd Floor)', 'Road# 20', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(10, 'L2021210', 'Mr. Md. Arab Ali Majumder', '', '', '', '880', '1819242760', NULL, '880', '', 'arab.majumder@gmail.com', 0, 'A. M. Travel Agent', 'Proprietor', 'Eastern View 50', 'DIT Extension Road Naya paltan, (11 Floor, Room 11/4-9)', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(11, 'L2021211', 'Mr. Paresh Chandra Paul', '', '', '', '880', '1711268837', NULL, '880', '', 'aptoursandt@gmail.com', 0, 'A. P. Tours & Travels', '', '44', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-22 00:00:00', NULL, NULL, NULL),
(12, 'L2021212', 'Jahid', 'Hasan', 'Zabir', 'Bin Hasan', '880', '1670000000', NULL, '880', '1844525204', 'jahid0209@gmail.com', 3, 'NextGeniT', 'CTO', '17/A', '17', 0, 47, 0, '5000', '17/A', '17', 0, 47, 0, '17/A', '17', 0, 47, 0, 0, 582, 0, 591, 305, 0, '', '', '', '112', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '2020-12-06', '2020-12-06 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(13, 'L2021213', 'Mr. Didarul Alam', '', '', '', '880', '1711165810', NULL, '880', '', 'dalam165810@gmail.com', 0, '4s Aviation Service', 'Proprietor', 'New-22', 'Old-83', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(14, 'L2021214', 'Mr. K.M Masudur Rahman', '', '', '', '880', '1711596320', NULL, '880', '', 'masud4s@yahoo.com', 0, '4S Tours & Travels Ltd', 'Managing Director', 'House # 56 (2nd Floor)', 'Road # Garib-E-Newaz Avenue', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(15, 'L2021215', 'Mr. Md. Saiful Islam', '', '', '', '880', '1812475630', NULL, '880', '', 'akazadtravels@gmail.com', 0, 'A K Azad Travel & Tours', 'Proprietor', 'Room # E1/B, (4th Floor), Islam Tower, 65', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(16, 'L2021216', 'Mr.Mohammed Abul Kalam', '', '', '', '880', '1971023351', NULL, '880', '1711023351', 'amsaviation70@gmail.com', 0, 'A M S Aviation', 'Proprietor', '131 Rafatun Mansion (4th Floor)', 'DIT Extension Road', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(17, 'L2021217', 'Mr.Md.Saidur Rahman (Sadek)', '', '', '', '880', '1711128748', NULL, '880', '', '', 0, 'A Matribhumi Travels International', 'Proprietor', '89/1, Kakrail Super Market (1st Floor)', '', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(18, 'L2021218', 'Mr. Advocate Md. Ibrahim', '', '', '', '880', '1555026678', NULL, '880', '1710262610', 'ibrahim_atoztt@yahoo.com', 0, 'A to Z Tours & Travels', 'Proprietor', 'Akmol Mansion, Kakrail Super Market (1st Floor, Suite # 134), 89/1', 'Kakrail', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(19, 'L2021219', 'Mr. M. A. Ahmed', '', '', '', '880', '1711074914', NULL, '880', '', 'ahmed01bd@yahoo.com', 0, 'A. J. Air International', 'Proprietor', 'Aziz Co-operative Market (4th Floor), 204', 'Shahid Syed Nazrul Islam Sarani', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(20, 'L2021220', 'Mr. Mohammad Abul Kalam Azad', '', '', '', '880', '1713043765', NULL, '880', '', 'ak.internationaldhk@yahoo.com', 0, 'A. K International', 'Proprietor', 'House # 07 (3rd Floor)', 'Road# 20', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(21, 'L2021221', 'Mr. Md. Arab Ali Majumder', '', '', '', '880', '1819242760', NULL, '880', '', 'arab.majumder@gmail.com', 0, 'A. M. Travel Agent', 'Proprietor', 'Eastern View 50', 'DIT Extension Road Naya paltan, (11 Floor, Room 11/4-9)', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(22, 'L2021222', 'Mr. Paresh Chandra Paul', '', '', '', '880', '1711268837', NULL, '880', '', 'aptoursandt@gmail.com', 0, 'A. P. Tours & Travels', '', '44', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-23 00:00:00', NULL, NULL, NULL),
(23, 'L2021223', 'Jahid', 'Hasan', 'Zabir', 'Bin Hasan', '880', '1670000000', NULL, '880', '1844525204', 'jahid0209@gmail.com', 3, 'NextGeniT', 'CTO', '17/A', '17', 0, 47, 0, '5000', '17/A', '17', 0, 47, 0, '17/A', '17', 0, 47, 0, 0, 582, 0, 591, 0, 0, '', '', '', '112', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '2020-12-06', '2020-12-06 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 0, '2021-02-24 00:00:00', NULL, NULL, NULL),
(24, 'L2021224', 'Jahid', 'Hasan', 'Zabir', 'Bin Hasan', '880', '1670000000', NULL, '880', '1844525204', 'jahid0209@gmail.com', 3, 'NextGeniT', 'CTO', '17/A', '17', 0, 47, 0, '5000', '17/A', '17', 0, 47, 0, '17/A', '17', 0, 47, 0, 0, 582, 0, 591, 0, 0, '', '', '', '112', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '2020-12-06', '2020-12-06 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 0, '2021-02-24 00:00:00', NULL, NULL, NULL),
(25, 'L2021225', 'Mr. Didarul Alam', '', '', '', '880', '1711165810', NULL, '880', '', 'dalam165810@gmail.com', 0, '4s Aviation Service', 'Proprietor', 'New-22', 'Old-83', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(26, 'L2021226', 'Mr. K.M Masudur Rahman', '', '', '', '880', '1711596320', NULL, '880', '', 'masud4s@yahoo.com', 0, '4S Tours & Travels Ltd', 'Managing Director', 'House # 56 (2nd Floor)', 'Road # Garib-E-Newaz Avenue', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(27, 'L2021227', 'Mr. Md. Saiful Islam', '', '', '', '880', '1812475630', NULL, '880', '', 'akazadtravels@gmail.com', 0, 'A K Azad Travel & Tours', 'Proprietor', 'Room # E1/B, (4th Floor), Islam Tower, 65', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(28, 'L2021228', 'Mr.Mohammed Abul Kalam', '', '', '', '880', '1971023351', NULL, '880', '1711023351', 'amsaviation70@gmail.com', 0, 'A M S Aviation', 'Proprietor', '131 Rafatun Mansion (4th Floor)', 'DIT Extension Road', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(29, 'L2021229', 'Mr.Md.Saidur Rahman (Sadek)', '', '', '', '880', '1711128748', NULL, '880', '', '', 0, 'A Matribhumi Travels International', 'Proprietor', '89/1, Kakrail Super Market (1st Floor)', '', 0, 47, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(30, 'L2021230', 'Mr. Advocate Md. Ibrahim', '', '', '', '880', '1555026678', NULL, '880', '1710262610', 'ibrahim_atoztt@yahoo.com', 0, 'A to Z Tours & Travels', 'Proprietor', 'Akmol Mansion, Kakrail Super Market (1st Floor, Suite # 134), 89/1', 'Kakrail', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(31, 'L2021231', 'Mr. M. A. Ahmed', '', '', '', '880', '1711074914', NULL, '880', '', 'ahmed01bd@yahoo.com', 0, 'A. J. Air International', 'Proprietor', 'Aziz Co-operative Market (4th Floor), 204', 'Shahid Syed Nazrul Islam Sarani', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(32, 'L2021232', 'Mr. Mohammad Abul Kalam Azad', '', '', '', '880', '1713043765', NULL, '880', '', 'ak.internationaldhk@yahoo.com', 0, 'A. K International', 'Proprietor', 'House # 07 (3rd Floor)', 'Road# 20', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(33, 'L2021233', 'Mr. Md. Arab Ali Majumder', '', '', '', '880', '1819242760', NULL, '880', '', 'arab.majumder@gmail.com', 0, 'A. M. Travel Agent', 'Proprietor', 'Eastern View 50', 'DIT Extension Road Naya paltan, (11 Floor, Room 11/4-9)', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL),
(34, 'L2021234', 'Mr. Paresh Chandra Paul', '', '', '', '880', '1711268837', NULL, '880', '', 'aptoursandt@gmail.com', 0, 'A. P. Tours & Travels', '', '44', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 305, 0, '', '', '', '0', '', '', 0, '', '', 0, 1, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', 0, '1970-01-01', '1970-01-01 00:00:00', '', '', '', '', '', '', '', '', '', '', 1, 305, '2021-02-24 00:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_leadshistory`
--

CREATE TABLE `t_leadshistory` (
  `leadhistory_pk_no` bigint(20) NOT NULL,
  `lead_pk_no` bigint(20) NOT NULL,
  `customer_firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation_pk_no` bigint(20) DEFAULT NULL,
  `organization_pk_no` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_designation` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_district` int(11) DEFAULT NULL,
  `pre_thana` int(11) DEFAULT NULL,
  `pre_area` int(11) DEFAULT NULL,
  `per_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_area` int(11) DEFAULT NULL,
  `per_district` int(11) DEFAULT NULL,
  `per_thana` int(11) DEFAULT NULL,
  `office_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_area` int(11) DEFAULT NULL,
  `office_district` int(11) DEFAULT NULL,
  `office_thana` int(11) DEFAULT NULL,
  `remarks` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadstagehistory`
--

CREATE TABLE `t_leadstagehistory` (
  `lead_stage_pk_no` bigint(20) NOT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `lead_stage_before_update` int(11) DEFAULT NULL,
  `lead_stage_after_update` int(11) DEFAULT NULL,
  `sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadstage_attribute`
--

CREATE TABLE `t_leadstage_attribute` (
  `attr_pk_no` bigint(20) NOT NULL,
  `stage_id` int(11) NOT NULL,
  `attr_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attr_type` bigint(20) NOT NULL COMMENT 'Checkbox/Date/Text',
  `attr_sl_no` int(11) DEFAULT NULL,
  `row_status` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT 0,
  `created_at` date DEFAULT NULL,
  `updated_by` int(11) DEFAULT 0,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_leadstage_attribute`
--

INSERT INTO `t_leadstage_attribute` (`attr_pk_no`, `stage_id`, `attr_name`, `attr_type`, `attr_sl_no`, `row_status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(14, 9, 'Client Not Interested', 1, 1, 1, 0, NULL, 0, NULL),
(15, 9, 'Do not Qualified for the product', 1, 2, 1, 0, NULL, 0, NULL),
(16, 9, 'Others', 3, 3, 1, 0, NULL, 0, NULL),
(17, 13, 'Already Confirmed the Unit', 2, 1, 1, 0, NULL, 0, NULL),
(18, 3, 'Visit Done', 2, 1, 1, 0, NULL, 0, NULL),
(19, 3, 'Meeting with TL/BH/CH?', 2, 2, 1, 0, NULL, 0, NULL),
(20, 3, 'Have Interest for Product of RCU', 1, 3, 1, 0, NULL, 0, NULL),
(21, 4, 'Price Determined', 1, 1, 1, 0, NULL, 0, NULL),
(22, 4, 'Determined Payment Schedule', 1, 2, 1, 0, NULL, 0, NULL),
(23, 4, 'Note sheet submitted', 1, 3, 1, 0, NULL, 0, NULL),
(24, 14, 'Inventory Selection(Unit/Flat)', 1, 4, 1, 0, NULL, 0, NULL),
(25, 14, 'Cheque No', 3, 1, 1, 0, NULL, 0, NULL),
(26, 14, 'Cheque Date', 3, 2, 1, 0, NULL, 0, NULL),
(27, 14, 'Bank Name', 3, 3, 1, 0, NULL, 0, NULL),
(28, 14, 'Sold Date (Automatic)', 2, 4, 1, 0, NULL, 0, NULL),
(29, 14, 'Back to prospect', 3, 1, 1, 0, NULL, 0, NULL),
(30, 14, 'Dump', 3, 2, 1, 0, NULL, 0, NULL),
(31, 14, 'Note Sheet Done', 1, 4, 1, 0, NULL, 0, NULL),
(32, 14, 'Posible Closing Date', 3, 4, 1, 0, NULL, 0, NULL),
(33, 4, 'Posible Closing Date', 2, 4, 1, 0, NULL, 0, NULL),
(34, 13, 'Unit No', 3, 2, 1, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_leads_copy`
--

CREATE TABLE `t_leads_copy` (
  `lead_pk_no` bigint(20) NOT NULL,
  `lead_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_bk` int(11) DEFAULT NULL,
  `phone2_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation_pk_no` bigint(20) DEFAULT NULL,
  `organization_pk_no` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pre_area` bigint(20) DEFAULT NULL,
  `pre_district` bigint(20) DEFAULT NULL,
  `pre_thana` bigint(20) DEFAULT NULL,
  `per_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_area` bigint(20) DEFAULT NULL,
  `per_district` bigint(20) DEFAULT NULL,
  `per_thana` bigint(20) DEFAULT NULL,
  `office_holding_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_road_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_area` bigint(20) DEFAULT NULL,
  `office_district` bigint(20) DEFAULT NULL,
  `office_thana` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_usergroup_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_sub` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_digital_marketing` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_hotline` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_internal_reference` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_emp_id` int(11) DEFAULT NULL,
  `source_ir_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_position` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_contact_no` int(11) DEFAULT NULL,
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
  `food_habit` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `political_opinion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `car_preference` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color_preference` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hobby` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `traveling_history` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `member_of_club` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `child_education` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disease_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadtransfer`
--

CREATE TABLE `t_leadtransfer` (
  `transfer_pk_no` bigint(20) NOT NULL,
  `lead_transfer_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_flag` int(11) DEFAULT NULL,
  `re_transfer` int(11) DEFAULT 1,
  `is_rejected` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `ch_user_pk_no` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_leadtransferhistory`
--

CREATE TABLE `t_leadtransferhistory` (
  `transhistory_pk_no` bigint(20) NOT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `is_rejected` int(11) DEFAULT NULL,
  `ch_user_no_pk` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `t_lead_followup_count_by_current_stage_vw`
-- (See below for the actual view)
--
CREATE TABLE `t_lead_followup_count_by_current_stage_vw` (
`lead_pk_no` bigint(20)
,`lead_id` varchar(30)
,`created_at` datetime
,`customer_firstname` varchar(200)
,`customer_lastname` varchar(200)
,`phone1_code` varchar(10)
,`phone1` varchar(200)
,`lead_current_stage` int(11)
,`project_category_name` varchar(200)
,`project_area` varchar(200)
,`project_name` varchar(200)
,`project_size` varchar(200)
,`lead_sales_agent_pk` bigint(20)
,`lead_sales_agent_name` varchar(500)
,`project_category_pk_no` bigint(20)
,`created_by` bigint(20)
,`user_full_name` varchar(500)
,`no_of_followup` bigint(21)
,`last_lead_followup_datetime` date
);

-- --------------------------------------------------------

--
-- Table structure for table `t_teambuild`
--

CREATE TABLE `t_teambuild` (
  `teammem_pk_no` bigint(20) NOT NULL,
  `teammem_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `team_lookup_pk_no` int(11) NOT NULL,
  `user_pk_no` bigint(20) NOT NULL,
  `category_lookup_pk_no` int(11) DEFAULT 0,
  `area_lookup_pk_no` varchar(200) COLLATE utf8_unicode_ci DEFAULT '0',
  `hod_flag` int(11) DEFAULT 0,
  `hot_flag` int(11) DEFAULT 0,
  `team_lead_flag` int(11) DEFAULT 0,
  `hod_user_pk_no` int(11) DEFAULT 0,
  `hot_user_pk_no` int(11) DEFAULT 0,
  `team_lead_user_pk_no` int(11) DEFAULT 0,
  `agent_type` int(11) DEFAULT 0,
  `row_status` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT 0,
  `created_at` date DEFAULT NULL,
  `updated_by` int(11) DEFAULT 0,
  `updated_at` date DEFAULT NULL,
  `sl_no` int(11) DEFAULT NULL,
  `team_sl_no` int(11) DEFAULT NULL,
  `last_auto_select_ind` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_teambuild`
--

INSERT INTO `t_teambuild` (`teammem_pk_no`, `teammem_id`, `team_lookup_pk_no`, `user_pk_no`, `category_lookup_pk_no`, `area_lookup_pk_no`, `hod_flag`, `hot_flag`, `team_lead_flag`, `hod_user_pk_no`, `hot_user_pk_no`, `team_lead_user_pk_no`, `agent_type`, `row_status`, `created_by`, `created_at`, `updated_by`, `updated_at`, `sl_no`, `team_sl_no`, `last_auto_select_ind`) VALUES
(14, '1', 777, 310, 583, '0', 0, 0, 0, 305, 0, 306, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(6, '1', 777, 308, 583, '0', 0, 0, 0, 305, 0, 306, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(5, '1', 777, 306, 583, '0', 0, 0, 1, 305, 0, 306, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(7, '1', 777, 311, 583, '0', 0, 0, 0, 305, 0, 306, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(9, '1', 778, 306, 583, '0', 0, 0, 0, 305, 0, 309, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(10, '1', 778, 309, 583, '0', 0, 0, 1, 305, 0, 309, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(11, '1', 778, 312, 583, '0', 0, 0, 0, 305, 0, 309, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(12, '1', 778, 313, 583, '0', 0, 0, 0, 305, 0, 309, 2, 1, 15, '2021-02-07', 0, '2021-02-07', 0, NULL, NULL),
(15, '1', 778, 305, 583, '', 1, 0, 0, 305, 0, 309, 2, 1, 15, '2021-02-07', 0, '2021-02-07', NULL, NULL, NULL),
(16, '1', 777, 305, 583, '', 1, 0, 0, 305, 0, 306, 2, 1, 15, '2021-02-07', 0, '2021-02-07', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_teambuildchd`
--

CREATE TABLE `t_teambuildchd` (
  `teammemchd_pk_no` bigint(20) NOT NULL,
  `team_lookup_pk_no` int(11) NOT NULL,
  `teammem_pk_no` bigint(20) NOT NULL,
  `area_lookup_pk_no` int(11) NOT NULL,
  `created_by` int(11) DEFAULT 0,
  `created_at` date DEFAULT NULL,
  `updated_by` int(11) DEFAULT 0,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_teamtarget`
--

CREATE TABLE `t_teamtarget` (
  `target_pk_no` bigint(20) NOT NULL,
  `teammem_pk_no` int(11) DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `user_pk_no` bigint(20) DEFAULT NULL,
  `target_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_lookup_pk_no` bigint(20) DEFAULT NULL,
  `area_lookup_pk_no` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `yy_mm` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_amount` int(11) DEFAULT NULL,
  `target_by_lead_qty` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upazilas`
--

CREATE TABLE `upazilas` (
  `id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `thana_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `bn_name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `upazilas`
--

INSERT INTO `upazilas` (`id`, `district_id`, `thana_name`, `bn_name`, `url`) VALUES
(1, 1, 'Debidwar', 'দেবিদ্বার', 'debidwar.comilla.gov.bd'),
(2, 1, 'Barura', 'বরুড়া', 'barura.comilla.gov.bd'),
(3, 1, 'Brahmanpara', 'ব্রাহ্মণপাড়া', 'brahmanpara.comilla.gov.bd'),
(4, 1, 'Chandina', 'চান্দিনা', 'chandina.comilla.gov.bd'),
(5, 1, 'Chauddagram', 'চৌদ্দগ্রাম', 'chauddagram.comilla.gov.bd'),
(6, 1, 'Daudkandi', 'দাউদকান্দি', 'daudkandi.comilla.gov.bd'),
(7, 1, 'Homna', 'হোমনা', 'homna.comilla.gov.bd'),
(8, 1, 'Laksam', 'লাকসাম', 'laksam.comilla.gov.bd'),
(9, 1, 'Muradnagar', 'মুরাদনগর', 'muradnagar.comilla.gov.bd'),
(10, 1, 'Nangalkot', 'নাঙ্গলকোট', 'nangalkot.comilla.gov.bd'),
(11, 1, 'Comilla Sadar', 'কুমিল্লা সদর', 'comillasadar.comilla.gov.bd'),
(12, 1, 'Meghna', 'মেঘনা', 'meghna.comilla.gov.bd'),
(13, 1, 'Monohargonj', 'মনোহরগঞ্জ', 'monohargonj.comilla.gov.bd'),
(14, 1, 'Sadarsouth', 'সদর দক্ষিণ', 'sadarsouth.comilla.gov.bd'),
(15, 1, 'Titas', 'তিতাস', 'titas.comilla.gov.bd'),
(16, 1, 'Burichang', 'বুড়িচং', 'burichang.comilla.gov.bd'),
(17, 1, 'Lalmai', 'লালমাই', 'lalmai.comilla.gov.bd'),
(18, 2, 'Chhagalnaiya', 'ছাগলনাইয়া', 'chhagalnaiya.feni.gov.bd'),
(19, 2, 'Feni Sadar', 'ফেনী সদর', 'sadar.feni.gov.bd'),
(20, 2, 'Sonagazi', 'সোনাগাজী', 'sonagazi.feni.gov.bd'),
(21, 2, 'Fulgazi', 'ফুলগাজী', 'fulgazi.feni.gov.bd'),
(22, 2, 'Parshuram', 'পরশুরাম', 'parshuram.feni.gov.bd'),
(23, 2, 'Daganbhuiyan', 'দাগনভূঞা', 'daganbhuiyan.feni.gov.bd'),
(24, 3, 'Brahmanbaria Sadar', 'ব্রাহ্মণবাড়িয়া সদর', 'sadar.brahmanbaria.gov.bd'),
(25, 3, 'Kasba', 'কসবা', 'kasba.brahmanbaria.gov.bd'),
(26, 3, 'Nasirnagar', 'নাসিরনগর', 'nasirnagar.brahmanbaria.gov.bd'),
(27, 3, 'Sarail', 'সরাইল', 'sarail.brahmanbaria.gov.bd'),
(28, 3, 'Ashuganj', 'আশুগঞ্জ', 'ashuganj.brahmanbaria.gov.bd'),
(29, 3, 'Akhaura', 'আখাউড়া', 'akhaura.brahmanbaria.gov.bd'),
(30, 3, 'Nabinagar', 'নবীনগর', 'nabinagar.brahmanbaria.gov.bd'),
(31, 3, 'Bancharampur', 'বাঞ্ছারামপুর', 'bancharampur.brahmanbaria.gov.bd'),
(32, 3, 'Bijoynagar', 'বিজয়নগর', 'bijoynagar.brahmanbaria.gov.bd    '),
(33, 4, 'Rangamati Sadar', 'রাঙ্গামাটি সদর', 'sadar.rangamati.gov.bd'),
(34, 4, 'Kaptai', 'কাপ্তাই', 'kaptai.rangamati.gov.bd'),
(35, 4, 'Kawkhali', 'কাউখালী', 'kawkhali.rangamati.gov.bd'),
(36, 4, 'Baghaichari', 'বাঘাইছড়ি', 'baghaichari.rangamati.gov.bd'),
(37, 4, 'Barkal', 'বরকল', 'barkal.rangamati.gov.bd'),
(38, 4, 'Langadu', 'লংগদু', 'langadu.rangamati.gov.bd'),
(39, 4, 'Rajasthali', 'রাজস্থলী', 'rajasthali.rangamati.gov.bd'),
(40, 4, 'Belaichari', 'বিলাইছড়ি', 'belaichari.rangamati.gov.bd'),
(41, 4, 'Juraichari', 'জুরাছড়ি', 'juraichari.rangamati.gov.bd'),
(42, 4, 'Naniarchar', 'নানিয়ারচর', 'naniarchar.rangamati.gov.bd'),
(43, 5, 'Noakhali Sadar', 'নোয়াখালী সদর', 'sadar.noakhali.gov.bd'),
(44, 5, 'Companiganj', 'কোম্পানীগঞ্জ', 'companiganj.noakhali.gov.bd'),
(45, 5, 'Begumganj', 'বেগমগঞ্জ', 'begumganj.noakhali.gov.bd'),
(46, 5, 'Hatia', 'হাতিয়া', 'hatia.noakhali.gov.bd'),
(47, 5, 'Subarnachar', 'সুবর্ণচর', 'subarnachar.noakhali.gov.bd'),
(48, 5, 'Kabirhat', 'কবিরহাট', 'kabirhat.noakhali.gov.bd'),
(49, 5, 'Senbug', 'সেনবাগ', 'senbug.noakhali.gov.bd'),
(50, 5, 'Chatkhil', 'চাটখিল', 'chatkhil.noakhali.gov.bd'),
(51, 5, 'Sonaimori', 'সোনাইমুড়ী', 'sonaimori.noakhali.gov.bd'),
(52, 6, 'Haimchar', 'হাইমচর', 'haimchar.chandpur.gov.bd'),
(53, 6, 'Kachua', 'কচুয়া', 'kachua.chandpur.gov.bd'),
(54, 6, 'Shahrasti', 'শাহরাস্তি	', 'shahrasti.chandpur.gov.bd'),
(55, 6, 'Chandpur Sadar', 'চাঁদপুর সদর', 'sadar.chandpur.gov.bd'),
(56, 6, 'Matlab South', 'মতলব দক্ষিণ', 'matlabsouth.chandpur.gov.bd'),
(57, 6, 'Hajiganj', 'হাজীগঞ্জ', 'hajiganj.chandpur.gov.bd'),
(58, 6, 'Matlab North', 'মতলব উত্তর', 'matlabnorth.chandpur.gov.bd'),
(59, 6, 'Faridgonj', 'ফরিদগঞ্জ', 'faridgonj.chandpur.gov.bd'),
(60, 7, 'Lakshmipur Sadar', 'লক্ষ্মীপুর সদর', 'sadar.lakshmipur.gov.bd'),
(61, 7, 'Kamalnagar', 'কমলনগর', 'kamalnagar.lakshmipur.gov.bd'),
(62, 7, 'Raipur', 'রায়পুর', 'raipur.lakshmipur.gov.bd'),
(63, 7, 'Ramgati', 'রামগতি', 'ramgati.lakshmipur.gov.bd'),
(64, 7, 'Ramganj', 'রামগঞ্জ', 'ramganj.lakshmipur.gov.bd'),
(65, 8, 'Rangunia', 'রাঙ্গুনিয়া', 'rangunia.chittagong.gov.bd'),
(66, 8, 'Sitakunda', 'সীতাকুন্ড', 'sitakunda.chittagong.gov.bd'),
(67, 8, 'Mirsharai', 'মীরসরাই', 'mirsharai.chittagong.gov.bd'),
(68, 8, 'Patiya', 'পটিয়া', 'patiya.chittagong.gov.bd'),
(69, 8, 'Sandwip', 'সন্দ্বীপ', 'sandwip.chittagong.gov.bd'),
(70, 8, 'Banshkhali', 'বাঁশখালী', 'banshkhali.chittagong.gov.bd'),
(71, 8, 'Boalkhali', 'বোয়ালখালী', 'boalkhali.chittagong.gov.bd'),
(72, 8, 'Anwara', 'আনোয়ারা', 'anwara.chittagong.gov.bd'),
(73, 8, 'Chandanaish', 'চন্দনাইশ', 'chandanaish.chittagong.gov.bd'),
(74, 8, 'Satkania', 'সাতকানিয়া', 'satkania.chittagong.gov.bd'),
(75, 8, 'Lohagara', 'লোহাগাড়া', 'lohagara.chittagong.gov.bd'),
(76, 8, 'Hathazari', 'হাটহাজারী', 'hathazari.chittagong.gov.bd'),
(77, 8, 'Fatikchhari', 'ফটিকছড়ি', 'fatikchhari.chittagong.gov.bd'),
(78, 8, 'Raozan', 'রাউজান', 'raozan.chittagong.gov.bd'),
(79, 8, 'Karnafuli', 'কর্ণফুলী', 'karnafuli.chittagong.gov.bd'),
(80, 9, 'Coxsbazar Sadar', 'কক্সবাজার সদর', 'sadar.coxsbazar.gov.bd'),
(81, 9, 'Chakaria', 'চকরিয়া', 'chakaria.coxsbazar.gov.bd'),
(82, 9, 'Kutubdia', 'কুতুবদিয়া', 'kutubdia.coxsbazar.gov.bd'),
(83, 9, 'Ukhiya', 'উখিয়া', 'ukhiya.coxsbazar.gov.bd'),
(84, 9, 'Moheshkhali', 'মহেশখালী', 'moheshkhali.coxsbazar.gov.bd'),
(85, 9, 'Pekua', 'পেকুয়া', 'pekua.coxsbazar.gov.bd'),
(86, 9, 'Ramu', 'রামু', 'ramu.coxsbazar.gov.bd'),
(87, 9, 'Teknaf', 'টেকনাফ', 'teknaf.coxsbazar.gov.bd'),
(88, 10, 'Khagrachhari Sadar', 'খাগড়াছড়ি সদর', 'sadar.khagrachhari.gov.bd'),
(89, 10, 'Dighinala', 'দিঘীনালা', 'dighinala.khagrachhari.gov.bd'),
(90, 10, 'Panchari', 'পানছড়ি', 'panchari.khagrachhari.gov.bd'),
(91, 10, 'Laxmichhari', 'লক্ষীছড়ি', 'laxmichhari.khagrachhari.gov.bd'),
(92, 10, 'Mohalchari', 'মহালছড়ি', 'mohalchari.khagrachhari.gov.bd'),
(93, 10, 'Manikchari', 'মানিকছড়ি', 'manikchari.khagrachhari.gov.bd'),
(94, 10, 'Ramgarh', 'রামগড়', 'ramgarh.khagrachhari.gov.bd'),
(95, 10, 'Matiranga', 'মাটিরাঙ্গা', 'matiranga.khagrachhari.gov.bd'),
(96, 10, 'Guimara', 'গুইমারা', 'guimara.khagrachhari.gov.bd'),
(97, 11, 'Bandarban Sadar', 'বান্দরবান সদর', 'sadar.bandarban.gov.bd'),
(98, 11, 'Alikadam', 'আলীকদম', 'alikadam.bandarban.gov.bd'),
(99, 11, 'Naikhongchhari', 'নাইক্ষ্যংছড়ি', 'naikhongchhari.bandarban.gov.bd'),
(100, 11, 'Rowangchhari', 'রোয়াংছড়ি', 'rowangchhari.bandarban.gov.bd'),
(101, 11, 'Lama', 'লামা', 'lama.bandarban.gov.bd'),
(102, 11, 'Ruma', 'রুমা', 'ruma.bandarban.gov.bd'),
(103, 11, 'Thanchi', 'থানচি', 'thanchi.bandarban.gov.bd'),
(104, 12, 'Belkuchi', 'বেলকুচি', 'belkuchi.sirajganj.gov.bd'),
(105, 12, 'Chauhali', 'চৌহালি', 'chauhali.sirajganj.gov.bd'),
(106, 12, 'Kamarkhand', 'কামারখন্দ', 'kamarkhand.sirajganj.gov.bd'),
(107, 12, 'Kazipur', 'কাজীপুর', 'kazipur.sirajganj.gov.bd'),
(108, 12, 'Raigonj', 'রায়গঞ্জ', 'raigonj.sirajganj.gov.bd'),
(109, 12, 'Shahjadpur', 'শাহজাদপুর', 'shahjadpur.sirajganj.gov.bd'),
(110, 12, 'Sirajganj Sadar', 'সিরাজগঞ্জ সদর', 'sirajganjsadar.sirajganj.gov.bd'),
(111, 12, 'Tarash', 'তাড়াশ', 'tarash.sirajganj.gov.bd'),
(112, 12, 'Ullapara', 'উল্লাপাড়া', 'ullapara.sirajganj.gov.bd'),
(113, 13, 'Sujanagar', 'সুজানগর', 'sujanagar.pabna.gov.bd'),
(114, 13, 'Ishurdi', 'ঈশ্বরদী', 'ishurdi.pabna.gov.bd'),
(115, 13, 'Bhangura', 'ভাঙ্গুড়া', 'bhangura.pabna.gov.bd'),
(116, 13, 'Pabna Sadar', 'পাবনা সদর', 'pabnasadar.pabna.gov.bd'),
(117, 13, 'Bera', 'বেড়া', 'bera.pabna.gov.bd'),
(118, 13, 'Atghoria', 'আটঘরিয়া', 'atghoria.pabna.gov.bd'),
(119, 13, 'Chatmohar', 'চাটমোহর', 'chatmohar.pabna.gov.bd'),
(120, 13, 'Santhia', 'সাঁথিয়া', 'santhia.pabna.gov.bd'),
(121, 13, 'Faridpur', 'ফরিদপুর', 'faridpur.pabna.gov.bd'),
(122, 14, 'Kahaloo', 'কাহালু', 'kahaloo.bogra.gov.bd'),
(123, 14, 'Bogra Sadar', 'বগুড়া সদর', 'sadar.bogra.gov.bd'),
(124, 14, 'Shariakandi', 'সারিয়াকান্দি', 'shariakandi.bogra.gov.bd'),
(125, 14, 'Shajahanpur', 'শাজাহানপুর', 'shajahanpur.bogra.gov.bd'),
(126, 14, 'Dupchanchia', 'দুপচাচিঁয়া', 'dupchanchia.bogra.gov.bd'),
(127, 14, 'Adamdighi', 'আদমদিঘি', 'adamdighi.bogra.gov.bd'),
(128, 14, 'Nondigram', 'নন্দিগ্রাম', 'nondigram.bogra.gov.bd'),
(129, 14, 'Sonatala', 'সোনাতলা', 'sonatala.bogra.gov.bd'),
(130, 14, 'Dhunot', 'ধুনট', 'dhunot.bogra.gov.bd'),
(131, 14, 'Gabtali', 'গাবতলী', 'gabtali.bogra.gov.bd'),
(132, 14, 'Sherpur', 'শেরপুর', 'sherpur.bogra.gov.bd'),
(133, 14, 'Shibganj', 'শিবগঞ্জ', 'shibganj.bogra.gov.bd'),
(134, 15, 'Paba', 'পবা', 'paba.rajshahi.gov.bd'),
(135, 15, 'Durgapur', 'দুর্গাপুর', 'durgapur.rajshahi.gov.bd'),
(136, 15, 'Mohonpur', 'মোহনপুর', 'mohonpur.rajshahi.gov.bd'),
(137, 15, 'Charghat', 'চারঘাট', 'charghat.rajshahi.gov.bd'),
(138, 15, 'Puthia', 'পুঠিয়া', 'puthia.rajshahi.gov.bd'),
(139, 15, 'Bagha', 'বাঘা', 'bagha.rajshahi.gov.bd'),
(140, 15, 'Godagari', 'গোদাগাড়ী', 'godagari.rajshahi.gov.bd'),
(141, 15, 'Tanore', 'তানোর', 'tanore.rajshahi.gov.bd'),
(142, 15, 'Bagmara', 'বাগমারা', 'bagmara.rajshahi.gov.bd'),
(143, 16, 'Natore Sadar', 'নাটোর সদর', 'natoresadar.natore.gov.bd'),
(144, 16, 'Singra', 'সিংড়া', 'singra.natore.gov.bd'),
(145, 16, 'Baraigram', 'বড়াইগ্রাম', 'baraigram.natore.gov.bd'),
(146, 16, 'Bagatipara', 'বাগাতিপাড়া', 'bagatipara.natore.gov.bd'),
(147, 16, 'Lalpur', 'লালপুর', 'lalpur.natore.gov.bd'),
(148, 16, 'Gurudaspur', 'গুরুদাসপুর', 'gurudaspur.natore.gov.bd'),
(149, 16, 'Naldanga', 'নলডাঙ্গা', 'naldanga.natore.gov.bd'),
(150, 17, 'Akkelpur', 'আক্কেলপুর', 'akkelpur.joypurhat.gov.bd'),
(151, 17, 'Kalai', 'কালাই', 'kalai.joypurhat.gov.bd'),
(152, 17, 'Khetlal', 'ক্ষেতলাল', 'khetlal.joypurhat.gov.bd'),
(153, 17, 'Panchbibi', 'পাঁচবিবি', 'panchbibi.joypurhat.gov.bd'),
(154, 17, 'Joypurhat Sadar', 'জয়পুরহাট সদর', 'joypurhatsadar.joypurhat.gov.bd'),
(155, 18, 'Chapainawabganj Sadar', 'চাঁপাইনবাবগঞ্জ সদর', 'chapainawabganjsadar.chapainawabganj.gov.bd'),
(156, 18, 'Gomostapur', 'গোমস্তাপুর', 'gomostapur.chapainawabganj.gov.bd'),
(157, 18, 'Nachol', 'নাচোল', 'nachol.chapainawabganj.gov.bd'),
(158, 18, 'Bholahat', 'ভোলাহাট', 'bholahat.chapainawabganj.gov.bd'),
(159, 18, 'Shibganj', 'শিবগঞ্জ', 'shibganj.chapainawabganj.gov.bd'),
(160, 19, 'Mohadevpur', 'মহাদেবপুর', 'mohadevpur.naogaon.gov.bd'),
(161, 19, 'Badalgachi', 'বদলগাছী', 'badalgachi.naogaon.gov.bd'),
(162, 19, 'Patnitala', 'পত্নিতলা', 'patnitala.naogaon.gov.bd'),
(163, 19, 'Dhamoirhat', 'ধামইরহাট', 'dhamoirhat.naogaon.gov.bd'),
(164, 19, 'Niamatpur', 'নিয়ামতপুর', 'niamatpur.naogaon.gov.bd'),
(165, 19, 'Manda', 'মান্দা', 'manda.naogaon.gov.bd'),
(166, 19, 'Atrai', 'আত্রাই', 'atrai.naogaon.gov.bd'),
(167, 19, 'Raninagar', 'রাণীনগর', 'raninagar.naogaon.gov.bd'),
(168, 19, 'Naogaon Sadar', 'নওগাঁ সদর', 'naogaonsadar.naogaon.gov.bd'),
(169, 19, 'Porsha', 'পোরশা', 'porsha.naogaon.gov.bd'),
(170, 19, 'Sapahar', 'সাপাহার', 'sapahar.naogaon.gov.bd'),
(171, 20, 'Manirampur', 'মণিরামপুর', 'manirampur.jessore.gov.bd'),
(172, 20, 'Abhaynagar', 'অভয়নগর', 'abhaynagar.jessore.gov.bd'),
(173, 20, 'Bagherpara', 'বাঘারপাড়া', 'bagherpara.jessore.gov.bd'),
(174, 20, 'Chougachha', 'চৌগাছা', 'chougachha.jessore.gov.bd'),
(175, 20, 'Jhikargacha', 'ঝিকরগাছা', 'jhikargacha.jessore.gov.bd'),
(176, 20, 'Keshabpur', 'কেশবপুর', 'keshabpur.jessore.gov.bd'),
(177, 20, 'Jessore Sadar', 'যশোর সদর', 'sadar.jessore.gov.bd'),
(178, 20, 'Sharsha', 'শার্শা', 'sharsha.jessore.gov.bd'),
(179, 21, 'Assasuni', 'আশাশুনি', 'assasuni.satkhira.gov.bd'),
(180, 21, 'Debhata', 'দেবহাটা', 'debhata.satkhira.gov.bd'),
(181, 21, 'Kalaroa', 'কলারোয়া', 'kalaroa.satkhira.gov.bd'),
(182, 21, 'Satkhira Sadar', 'সাতক্ষীরা সদর', 'satkhirasadar.satkhira.gov.bd'),
(183, 21, 'Shyamnagar', 'শ্যামনগর', 'shyamnagar.satkhira.gov.bd'),
(184, 21, 'Tala', 'তালা', 'tala.satkhira.gov.bd'),
(185, 21, 'Kaliganj', 'কালিগঞ্জ', 'kaliganj.satkhira.gov.bd'),
(186, 22, 'Mujibnagar', 'মুজিবনগর', 'mujibnagar.meherpur.gov.bd'),
(187, 22, 'Meherpur Sadar', 'মেহেরপুর সদর', 'meherpursadar.meherpur.gov.bd'),
(188, 22, 'Gangni', 'গাংনী', 'gangni.meherpur.gov.bd'),
(189, 23, 'Narail Sadar', 'নড়াইল সদর', 'narailsadar.narail.gov.bd'),
(190, 23, 'Lohagara', 'লোহাগড়া', 'lohagara.narail.gov.bd'),
(191, 23, 'Kalia', 'কালিয়া', 'kalia.narail.gov.bd'),
(192, 24, 'Chuadanga Sadar', 'চুয়াডাঙ্গা সদর', 'chuadangasadar.chuadanga.gov.bd'),
(193, 24, 'Alamdanga', 'আলমডাঙ্গা', 'alamdanga.chuadanga.gov.bd'),
(194, 24, 'Damurhuda', 'দামুড়হুদা', 'damurhuda.chuadanga.gov.bd'),
(195, 24, 'Jibannagar', 'জীবননগর', 'jibannagar.chuadanga.gov.bd'),
(196, 25, 'Kushtia Sadar', 'কুষ্টিয়া সদর', 'kushtiasadar.kushtia.gov.bd'),
(197, 25, 'Kumarkhali', 'কুমারখালী', 'kumarkhali.kushtia.gov.bd'),
(198, 25, 'Khoksa', 'খোকসা', 'khoksa.kushtia.gov.bd'),
(199, 25, 'Mirpur', 'মিরপুর', 'mirpurkushtia.kushtia.gov.bd'),
(200, 25, 'Daulatpur', 'দৌলতপুর', 'daulatpur.kushtia.gov.bd'),
(201, 25, 'Bheramara', 'ভেড়ামারা', 'bheramara.kushtia.gov.bd'),
(202, 26, 'Shalikha', 'শালিখা', 'shalikha.magura.gov.bd'),
(203, 26, 'Sreepur', 'শ্রীপুর', 'sreepur.magura.gov.bd'),
(204, 26, 'Magura Sadar', 'মাগুরা সদর', 'magurasadar.magura.gov.bd'),
(205, 26, 'Mohammadpur', 'মহম্মদপুর', 'mohammadpur.magura.gov.bd'),
(206, 27, 'Paikgasa', 'পাইকগাছা', 'paikgasa.khulna.gov.bd'),
(207, 27, 'Fultola', 'ফুলতলা', 'fultola.khulna.gov.bd'),
(208, 27, 'Digholia', 'দিঘলিয়া', 'digholia.khulna.gov.bd'),
(209, 27, 'Rupsha', 'রূপসা', 'rupsha.khulna.gov.bd'),
(210, 27, 'Terokhada', 'তেরখাদা', 'terokhada.khulna.gov.bd'),
(211, 27, 'Dumuria', 'ডুমুরিয়া', 'dumuria.khulna.gov.bd'),
(212, 27, 'Botiaghata', 'বটিয়াঘাটা', 'botiaghata.khulna.gov.bd'),
(213, 27, 'Dakop', 'দাকোপ', 'dakop.khulna.gov.bd'),
(214, 27, 'Koyra', 'কয়রা', 'koyra.khulna.gov.bd'),
(215, 28, 'Fakirhat', 'ফকিরহাট', 'fakirhat.bagerhat.gov.bd'),
(216, 28, 'Bagerhat Sadar', 'বাগেরহাট সদর', 'sadar.bagerhat.gov.bd'),
(217, 28, 'Mollahat', 'মোল্লাহাট', 'mollahat.bagerhat.gov.bd'),
(218, 28, 'Sarankhola', 'শরণখোলা', 'sarankhola.bagerhat.gov.bd'),
(219, 28, 'Rampal', 'রামপাল', 'rampal.bagerhat.gov.bd'),
(220, 28, 'Morrelganj', 'মোড়েলগঞ্জ', 'morrelganj.bagerhat.gov.bd'),
(221, 28, 'Kachua', 'কচুয়া', 'kachua.bagerhat.gov.bd'),
(222, 28, 'Mongla', 'মোংলা', 'mongla.bagerhat.gov.bd'),
(223, 28, 'Chitalmari', 'চিতলমারী', 'chitalmari.bagerhat.gov.bd'),
(224, 29, 'Jhenaidah Sadar', 'ঝিনাইদহ সদর', 'sadar.jhenaidah.gov.bd'),
(225, 29, 'Shailkupa', 'শৈলকুপা', 'shailkupa.jhenaidah.gov.bd'),
(226, 29, 'Harinakundu', 'হরিণাকুন্ডু', 'harinakundu.jhenaidah.gov.bd'),
(227, 29, 'Kaliganj', 'কালীগঞ্জ', 'kaliganj.jhenaidah.gov.bd'),
(228, 29, 'Kotchandpur', 'কোটচাঁদপুর', 'kotchandpur.jhenaidah.gov.bd'),
(229, 29, 'Moheshpur', 'মহেশপুর', 'moheshpur.jhenaidah.gov.bd'),
(230, 30, 'Jhalakathi Sadar', 'ঝালকাঠি সদর', 'sadar.jhalakathi.gov.bd'),
(231, 30, 'Kathalia', 'কাঠালিয়া', 'kathalia.jhalakathi.gov.bd'),
(232, 30, 'Nalchity', 'নলছিটি', 'nalchity.jhalakathi.gov.bd'),
(233, 30, 'Rajapur', 'রাজাপুর', 'rajapur.jhalakathi.gov.bd'),
(234, 31, 'Bauphal', 'বাউফল', 'bauphal.patuakhali.gov.bd'),
(235, 31, 'Patuakhali Sadar', 'পটুয়াখালী সদর', 'sadar.patuakhali.gov.bd'),
(236, 31, 'Dumki', 'দুমকি', 'dumki.patuakhali.gov.bd'),
(237, 31, 'Dashmina', 'দশমিনা', 'dashmina.patuakhali.gov.bd'),
(238, 31, 'Kalapara', 'কলাপাড়া', 'kalapara.patuakhali.gov.bd'),
(239, 31, 'Mirzaganj', 'মির্জাগঞ্জ', 'mirzaganj.patuakhali.gov.bd'),
(240, 31, 'Galachipa', 'গলাচিপা', 'galachipa.patuakhali.gov.bd'),
(241, 31, 'Rangabali', 'রাঙ্গাবালী', 'rangabali.patuakhali.gov.bd'),
(242, 32, 'Pirojpur Sadar', 'পিরোজপুর সদর', 'sadar.pirojpur.gov.bd'),
(243, 32, 'Nazirpur', 'নাজিরপুর', 'nazirpur.pirojpur.gov.bd'),
(244, 32, 'Kawkhali', 'কাউখালী', 'kawkhali.pirojpur.gov.bd'),
(245, 32, 'Zianagar', 'জিয়ানগর', 'zianagar.pirojpur.gov.bd'),
(246, 32, 'Bhandaria', 'ভান্ডারিয়া', 'bhandaria.pirojpur.gov.bd'),
(247, 32, 'Mathbaria', 'মঠবাড়ীয়া', 'mathbaria.pirojpur.gov.bd'),
(248, 32, 'Nesarabad', 'নেছারাবাদ', 'nesarabad.pirojpur.gov.bd'),
(249, 33, 'Barisal Sadar', 'বরিশাল সদর', 'barisalsadar.barisal.gov.bd'),
(250, 33, 'Bakerganj', 'বাকেরগঞ্জ', 'bakerganj.barisal.gov.bd'),
(251, 33, 'Babuganj', 'বাবুগঞ্জ', 'babuganj.barisal.gov.bd'),
(252, 33, 'Wazirpur', 'উজিরপুর', 'wazirpur.barisal.gov.bd'),
(253, 33, 'Banaripara', 'বানারীপাড়া', 'banaripara.barisal.gov.bd'),
(254, 33, 'Gournadi', 'গৌরনদী', 'gournadi.barisal.gov.bd'),
(255, 33, 'Agailjhara', 'আগৈলঝাড়া', 'agailjhara.barisal.gov.bd'),
(256, 33, 'Mehendiganj', 'মেহেন্দিগঞ্জ', 'mehendiganj.barisal.gov.bd'),
(257, 33, 'Muladi', 'মুলাদী', 'muladi.barisal.gov.bd'),
(258, 33, 'Hizla', 'হিজলা', 'hizla.barisal.gov.bd'),
(259, 34, 'Bhola Sadar', 'ভোলা সদর', 'sadar.bhola.gov.bd'),
(260, 34, 'Borhan Sddin', 'বোরহান উদ্দিন', 'borhanuddin.bhola.gov.bd'),
(261, 34, 'Charfesson', 'চরফ্যাশন', 'charfesson.bhola.gov.bd'),
(262, 34, 'Doulatkhan', 'দৌলতখান', 'doulatkhan.bhola.gov.bd'),
(263, 34, 'Monpura', 'মনপুরা', 'monpura.bhola.gov.bd'),
(264, 34, 'Tazumuddin', 'তজুমদ্দিন', 'tazumuddin.bhola.gov.bd'),
(265, 34, 'Lalmohan', 'লালমোহন', 'lalmohan.bhola.gov.bd'),
(266, 35, 'Amtali', 'আমতলী', 'amtali.barguna.gov.bd'),
(267, 35, 'Barguna Sadar', 'বরগুনা সদর', 'sadar.barguna.gov.bd'),
(268, 35, 'Betagi', 'বেতাগী', 'betagi.barguna.gov.bd'),
(269, 35, 'Bamna', 'বামনা', 'bamna.barguna.gov.bd'),
(270, 35, 'Pathorghata', 'পাথরঘাটা', 'pathorghata.barguna.gov.bd'),
(271, 35, 'Taltali', 'তালতলি', 'taltali.barguna.gov.bd'),
(272, 36, 'Balaganj', 'বালাগঞ্জ', 'balaganj.sylhet.gov.bd'),
(273, 36, 'Beanibazar', 'বিয়ানীবাজার', 'beanibazar.sylhet.gov.bd'),
(274, 36, 'Bishwanath', 'বিশ্বনাথ', 'bishwanath.sylhet.gov.bd'),
(275, 36, 'Companiganj', 'কোম্পানীগঞ্জ', 'companiganj.sylhet.gov.bd'),
(276, 36, 'Fenchuganj', 'ফেঞ্চুগঞ্জ', 'fenchuganj.sylhet.gov.bd'),
(277, 36, 'Golapganj', 'গোলাপগঞ্জ', 'golapganj.sylhet.gov.bd'),
(278, 36, 'Gowainghat', 'গোয়াইনঘাট', 'gowainghat.sylhet.gov.bd'),
(279, 36, 'Jaintiapur', 'জৈন্তাপুর', 'jaintiapur.sylhet.gov.bd'),
(280, 36, 'Kanaighat', 'কানাইঘাট', 'kanaighat.sylhet.gov.bd'),
(281, 36, 'Sylhet Sadar', 'সিলেট সদর', 'sylhetsadar.sylhet.gov.bd'),
(282, 36, 'Zakiganj', 'জকিগঞ্জ', 'zakiganj.sylhet.gov.bd'),
(283, 36, 'Dakshinsurma', 'দক্ষিণ সুরমা', 'dakshinsurma.sylhet.gov.bd'),
(284, 36, 'Osmaninagar', 'ওসমানী নগর', 'osmaninagar.sylhet.gov.bd'),
(285, 37, 'Barlekha', 'বড়লেখা', 'barlekha.moulvibazar.gov.bd'),
(286, 37, 'Kamolganj', 'কমলগঞ্জ', 'kamolganj.moulvibazar.gov.bd'),
(287, 37, 'Kulaura', 'কুলাউড়া', 'kulaura.moulvibazar.gov.bd'),
(288, 37, 'Moulvibazar Sadar', 'মৌলভীবাজার সদর', 'moulvibazarsadar.moulvibazar.gov.bd'),
(289, 37, 'Rajnagar', 'রাজনগর', 'rajnagar.moulvibazar.gov.bd'),
(290, 37, 'Sreemangal', 'শ্রীমঙ্গল', 'sreemangal.moulvibazar.gov.bd'),
(291, 37, 'Juri', 'জুড়ী', 'juri.moulvibazar.gov.bd'),
(292, 38, 'Nabiganj', 'নবীগঞ্জ', 'nabiganj.habiganj.gov.bd'),
(293, 38, 'Bahubal', 'বাহুবল', 'bahubal.habiganj.gov.bd'),
(294, 38, 'Ajmiriganj', 'আজমিরীগঞ্জ', 'ajmiriganj.habiganj.gov.bd'),
(295, 38, 'Baniachong', 'বানিয়াচং', 'baniachong.habiganj.gov.bd'),
(296, 38, 'Lakhai', 'লাখাই', 'lakhai.habiganj.gov.bd'),
(297, 38, 'Chunarughat', 'চুনারুঘাট', 'chunarughat.habiganj.gov.bd'),
(298, 38, 'Habiganj Sadar', 'হবিগঞ্জ সদর', 'habiganjsadar.habiganj.gov.bd'),
(299, 38, 'Madhabpur', 'মাধবপুর', 'madhabpur.habiganj.gov.bd'),
(300, 39, 'Sunamganj Sadar', 'সুনামগঞ্জ সদর', 'sadar.sunamganj.gov.bd'),
(301, 39, 'South Sunamganj', 'দক্ষিণ সুনামগঞ্জ', 'southsunamganj.sunamganj.gov.bd'),
(302, 39, 'Bishwambarpur', 'বিশ্বম্ভরপুর', 'bishwambarpur.sunamganj.gov.bd'),
(303, 39, 'Chhatak', 'ছাতক', 'chhatak.sunamganj.gov.bd'),
(304, 39, 'Jagannathpur', 'জগন্নাথপুর', 'jagannathpur.sunamganj.gov.bd'),
(305, 39, 'Dowarabazar', 'দোয়ারাবাজার', 'dowarabazar.sunamganj.gov.bd'),
(306, 39, 'Tahirpur', 'তাহিরপুর', 'tahirpur.sunamganj.gov.bd'),
(307, 39, 'Dharmapasha', 'ধর্মপাশা', 'dharmapasha.sunamganj.gov.bd'),
(308, 39, 'Jamalganj', 'জামালগঞ্জ', 'jamalganj.sunamganj.gov.bd'),
(309, 39, 'Shalla', 'শাল্লা', 'shalla.sunamganj.gov.bd'),
(310, 39, 'Derai', 'দিরাই', 'derai.sunamganj.gov.bd'),
(311, 40, 'Belabo', 'বেলাবো', 'belabo.narsingdi.gov.bd'),
(312, 40, 'Monohardi', 'মনোহরদী', 'monohardi.narsingdi.gov.bd'),
(313, 40, 'Narsingdi Sadar', 'নরসিংদী সদর', 'narsingdisadar.narsingdi.gov.bd'),
(314, 40, 'Palash', 'পলাশ', 'palash.narsingdi.gov.bd'),
(315, 40, 'Raipura', 'রায়পুরা', 'raipura.narsingdi.gov.bd'),
(316, 40, 'Shibpur', 'শিবপুর', 'shibpur.narsingdi.gov.bd'),
(317, 41, 'Kaliganj', 'কালীগঞ্জ', 'kaliganj.gazipur.gov.bd'),
(318, 41, 'Kaliakair', 'কালিয়াকৈর', 'kaliakair.gazipur.gov.bd'),
(319, 41, 'Kapasia', 'কাপাসিয়া', 'kapasia.gazipur.gov.bd'),
(320, 41, 'Gazipur Sadar', 'গাজীপুর সদর', 'sadar.gazipur.gov.bd'),
(321, 41, 'Sreepur', 'শ্রীপুর', 'sreepur.gazipur.gov.bd'),
(322, 42, 'Shariatpur Sadar', 'শরিয়তপুর সদর', 'sadar.shariatpur.gov.bd'),
(323, 42, 'Naria', 'নড়িয়া', 'naria.shariatpur.gov.bd'),
(324, 42, 'Zajira', 'জাজিরা', 'zajira.shariatpur.gov.bd'),
(325, 42, 'Gosairhat', 'গোসাইরহাট', 'gosairhat.shariatpur.gov.bd'),
(326, 42, 'Bhedarganj', 'ভেদরগঞ্জ', 'bhedarganj.shariatpur.gov.bd'),
(327, 42, 'Damudya', 'ডামুড্যা', 'damudya.shariatpur.gov.bd'),
(328, 43, 'Araihazar', 'আড়াইহাজার', 'araihazar.narayanganj.gov.bd'),
(329, 43, 'Bandar', 'বন্দর', 'bandar.narayanganj.gov.bd'),
(330, 43, 'Narayanganj Sadar', 'নারায়নগঞ্জ সদর', 'narayanganjsadar.narayanganj.gov.bd'),
(331, 43, 'Rupganj', 'রূপগঞ্জ', 'rupganj.narayanganj.gov.bd'),
(332, 43, 'Sonargaon', 'সোনারগাঁ', 'sonargaon.narayanganj.gov.bd'),
(333, 44, 'Basail', 'বাসাইল', 'basail.tangail.gov.bd'),
(334, 44, 'Bhuapur', 'ভুয়াপুর', 'bhuapur.tangail.gov.bd'),
(335, 44, 'Delduar', 'দেলদুয়ার', 'delduar.tangail.gov.bd'),
(336, 44, 'Ghatail', 'ঘাটাইল', 'ghatail.tangail.gov.bd'),
(337, 44, 'Gopalpur', 'গোপালপুর', 'gopalpur.tangail.gov.bd'),
(338, 44, 'Madhupur', 'মধুপুর', 'madhupur.tangail.gov.bd'),
(339, 44, 'Mirzapur', 'মির্জাপুর', 'mirzapur.tangail.gov.bd'),
(340, 44, 'Nagarpur', 'নাগরপুর', 'nagarpur.tangail.gov.bd'),
(341, 44, 'Sakhipur', 'সখিপুর', 'sakhipur.tangail.gov.bd'),
(342, 44, 'Tangail Sadar', 'টাঙ্গাইল সদর', 'tangailsadar.tangail.gov.bd'),
(343, 44, 'Kalihati', 'কালিহাতী', 'kalihati.tangail.gov.bd'),
(344, 44, 'Dhanbari', 'ধনবাড়ী', 'dhanbari.tangail.gov.bd'),
(345, 45, 'Itna', 'ইটনা', 'itna.kishoreganj.gov.bd'),
(346, 45, 'Katiadi', 'কটিয়াদী', 'katiadi.kishoreganj.gov.bd'),
(347, 45, 'Bhairab', 'ভৈরব', 'bhairab.kishoreganj.gov.bd'),
(348, 45, 'Tarail', 'তাড়াইল', 'tarail.kishoreganj.gov.bd'),
(349, 45, 'Hossainpur', 'হোসেনপুর', 'hossainpur.kishoreganj.gov.bd'),
(350, 45, 'Pakundia', 'পাকুন্দিয়া', 'pakundia.kishoreganj.gov.bd'),
(351, 45, 'Kuliarchar', 'কুলিয়ারচর', 'kuliarchar.kishoreganj.gov.bd'),
(352, 45, 'Kishoreganj Sadar', 'কিশোরগঞ্জ সদর', 'kishoreganjsadar.kishoreganj.gov.bd'),
(353, 45, 'Karimgonj', 'করিমগঞ্জ', 'karimgonj.kishoreganj.gov.bd'),
(354, 45, 'Bajitpur', 'বাজিতপুর', 'bajitpur.kishoreganj.gov.bd'),
(355, 45, 'Austagram', 'অষ্টগ্রাম', 'austagram.kishoreganj.gov.bd'),
(356, 45, 'Mithamoin', 'মিঠামইন', 'mithamoin.kishoreganj.gov.bd'),
(357, 45, 'Nikli', 'নিকলী', 'nikli.kishoreganj.gov.bd'),
(358, 46, 'Harirampur', 'হরিরামপুর', 'harirampur.manikganj.gov.bd'),
(359, 46, 'Saturia', 'সাটুরিয়া', 'saturia.manikganj.gov.bd'),
(360, 46, 'Manikganj Sadar', 'মানিকগঞ্জ সদর', 'sadar.manikganj.gov.bd'),
(361, 46, 'Gior', 'ঘিওর', 'gior.manikganj.gov.bd'),
(362, 46, 'Shibaloy', 'শিবালয়', 'shibaloy.manikganj.gov.bd'),
(363, 46, 'Doulatpur', 'দৌলতপুর', 'doulatpur.manikganj.gov.bd'),
(364, 46, 'Singiar', 'সিংগাইর', 'singiar.manikganj.gov.bd'),
(365, 47, 'Savar', 'সাভার', 'savar.dhaka.gov.bd'),
(366, 47, 'Dhamrai', 'ধামরাই', 'dhamrai.dhaka.gov.bd'),
(367, 47, 'Keraniganj', 'কেরাণীগঞ্জ', 'keraniganj.dhaka.gov.bd'),
(368, 47, 'Nawabganj', 'নবাবগঞ্জ', 'nawabganj.dhaka.gov.bd'),
(369, 47, 'Dohar', 'দোহার', 'dohar.dhaka.gov.bd'),
(370, 48, 'Munshiganj Sadar', 'মুন্সিগঞ্জ সদর', 'sadar.munshiganj.gov.bd'),
(371, 48, 'Sreenagar', 'শ্রীনগর', 'sreenagar.munshiganj.gov.bd'),
(372, 48, 'Sirajdikhan', 'সিরাজদিখান', 'sirajdikhan.munshiganj.gov.bd'),
(373, 48, 'Louhajanj', 'লৌহজং', 'louhajanj.munshiganj.gov.bd'),
(374, 48, 'Gajaria', 'গজারিয়া', 'gajaria.munshiganj.gov.bd'),
(375, 48, 'Tongibari', 'টংগীবাড়ি', 'tongibari.munshiganj.gov.bd'),
(376, 49, 'Rajbari Sadar', 'রাজবাড়ী সদর', 'sadar.rajbari.gov.bd'),
(377, 49, 'Goalanda', 'গোয়ালন্দ', 'goalanda.rajbari.gov.bd'),
(378, 49, 'Pangsa', 'পাংশা', 'pangsa.rajbari.gov.bd'),
(379, 49, 'Baliakandi', 'বালিয়াকান্দি', 'baliakandi.rajbari.gov.bd'),
(380, 49, 'Kalukhali', 'কালুখালী', 'kalukhali.rajbari.gov.bd'),
(381, 50, 'Madaripur Sadar', 'মাদারীপুর সদর', 'sadar.madaripur.gov.bd'),
(382, 50, 'Shibchar', 'শিবচর', 'shibchar.madaripur.gov.bd'),
(383, 50, 'Kalkini', 'কালকিনি', 'kalkini.madaripur.gov.bd'),
(384, 50, 'Rajoir', 'রাজৈর', 'rajoir.madaripur.gov.bd'),
(385, 51, 'Gopalganj Sadar', 'গোপালগঞ্জ সদর', 'sadar.gopalganj.gov.bd'),
(386, 51, 'Kashiani', 'কাশিয়ানী', 'kashiani.gopalganj.gov.bd'),
(387, 51, 'Tungipara', 'টুংগীপাড়া', 'tungipara.gopalganj.gov.bd'),
(388, 51, 'Kotalipara', 'কোটালীপাড়া', 'kotalipara.gopalganj.gov.bd'),
(389, 51, 'Muksudpur', 'মুকসুদপুর', 'muksudpur.gopalganj.gov.bd'),
(390, 52, 'Faridpur Sadar', 'ফরিদপুর সদর', 'sadar.faridpur.gov.bd'),
(391, 52, 'Alfadanga', 'আলফাডাঙ্গা', 'alfadanga.faridpur.gov.bd'),
(392, 52, 'Boalmari', 'বোয়ালমারী', 'boalmari.faridpur.gov.bd'),
(393, 52, 'Sadarpur', 'সদরপুর', 'sadarpur.faridpur.gov.bd'),
(394, 52, 'Nagarkanda', 'নগরকান্দা', 'nagarkanda.faridpur.gov.bd'),
(395, 52, 'Bhanga', 'ভাঙ্গা', 'bhanga.faridpur.gov.bd'),
(396, 52, 'Charbhadrasan', 'চরভদ্রাসন', 'charbhadrasan.faridpur.gov.bd'),
(397, 52, 'Madhukhali', 'মধুখালী', 'madhukhali.faridpur.gov.bd'),
(398, 52, 'Saltha', 'সালথা', 'saltha.faridpur.gov.bd'),
(399, 53, 'Panchagarh Sadar', 'পঞ্চগড় সদর', 'panchagarhsadar.panchagarh.gov.bd'),
(400, 53, 'Debiganj', 'দেবীগঞ্জ', 'debiganj.panchagarh.gov.bd'),
(401, 53, 'Boda', 'বোদা', 'boda.panchagarh.gov.bd'),
(402, 53, 'Atwari', 'আটোয়ারী', 'atwari.panchagarh.gov.bd'),
(403, 53, 'Tetulia', 'তেতুলিয়া', 'tetulia.panchagarh.gov.bd'),
(404, 54, 'Nawabganj', 'নবাবগঞ্জ', 'nawabganj.dinajpur.gov.bd'),
(405, 54, 'Birganj', 'বীরগঞ্জ', 'birganj.dinajpur.gov.bd'),
(406, 54, 'Ghoraghat', 'ঘোড়াঘাট', 'ghoraghat.dinajpur.gov.bd'),
(407, 54, 'Birampur', 'বিরামপুর', 'birampur.dinajpur.gov.bd'),
(408, 54, 'Parbatipur', 'পার্বতীপুর', 'parbatipur.dinajpur.gov.bd'),
(409, 54, 'Bochaganj', 'বোচাগঞ্জ', 'bochaganj.dinajpur.gov.bd'),
(410, 54, 'Kaharol', 'কাহারোল', 'kaharol.dinajpur.gov.bd'),
(411, 54, 'Fulbari', 'ফুলবাড়ী', 'fulbari.dinajpur.gov.bd'),
(412, 54, 'Dinajpur Sadar', 'দিনাজপুর সদর', 'dinajpursadar.dinajpur.gov.bd'),
(413, 54, 'Hakimpur', 'হাকিমপুর', 'hakimpur.dinajpur.gov.bd'),
(414, 54, 'Khansama', 'খানসামা', 'khansama.dinajpur.gov.bd'),
(415, 54, 'Birol', 'বিরল', 'birol.dinajpur.gov.bd'),
(416, 54, 'Chirirbandar', 'চিরিরবন্দর', 'chirirbandar.dinajpur.gov.bd'),
(417, 55, 'Lalmonirhat Sadar', 'লালমনিরহাট সদর', 'sadar.lalmonirhat.gov.bd'),
(418, 55, 'Kaliganj', 'কালীগঞ্জ', 'kaliganj.lalmonirhat.gov.bd'),
(419, 55, 'Hatibandha', 'হাতীবান্ধা', 'hatibandha.lalmonirhat.gov.bd'),
(420, 55, 'Patgram', 'পাটগ্রাম', 'patgram.lalmonirhat.gov.bd'),
(421, 55, 'Aditmari', 'আদিতমারী', 'aditmari.lalmonirhat.gov.bd'),
(422, 56, 'Syedpur', 'সৈয়দপুর', 'syedpur.nilphamari.gov.bd'),
(423, 56, 'Domar', 'ডোমার', 'domar.nilphamari.gov.bd'),
(424, 56, 'Dimla', 'ডিমলা', 'dimla.nilphamari.gov.bd'),
(425, 56, 'Jaldhaka', 'জলঢাকা', 'jaldhaka.nilphamari.gov.bd'),
(426, 56, 'Kishorganj', 'কিশোরগঞ্জ', 'kishorganj.nilphamari.gov.bd'),
(427, 56, 'Nilphamari Sadar', 'নীলফামারী সদর', 'nilphamarisadar.nilphamari.gov.bd'),
(428, 57, 'Sadullapur', 'সাদুল্লাপুর', 'sadullapur.gaibandha.gov.bd'),
(429, 57, 'Gaibandha Sadar', 'গাইবান্ধা সদর', 'gaibandhasadar.gaibandha.gov.bd'),
(430, 57, 'Palashbari', 'পলাশবাড়ী', 'palashbari.gaibandha.gov.bd'),
(431, 57, 'Saghata', 'সাঘাটা', 'saghata.gaibandha.gov.bd'),
(432, 57, 'Gobindaganj', 'গোবিন্দগঞ্জ', 'gobindaganj.gaibandha.gov.bd'),
(433, 57, 'Sundarganj', 'সুন্দরগঞ্জ', 'sundarganj.gaibandha.gov.bd'),
(434, 57, 'Phulchari', 'ফুলছড়ি', 'phulchari.gaibandha.gov.bd'),
(435, 58, 'Thakurgaon Sadar', 'ঠাকুরগাঁও সদর', 'thakurgaonsadar.thakurgaon.gov.bd'),
(436, 58, 'Pirganj', 'পীরগঞ্জ', 'pirganj.thakurgaon.gov.bd'),
(437, 58, 'Ranisankail', 'রাণীশংকৈল', 'ranisankail.thakurgaon.gov.bd'),
(438, 58, 'Haripur', 'হরিপুর', 'haripur.thakurgaon.gov.bd'),
(439, 58, 'Baliadangi', 'বালিয়াডাঙ্গী', 'baliadangi.thakurgaon.gov.bd'),
(440, 59, 'Rangpur Sadar', 'রংপুর সদর', 'rangpursadar.rangpur.gov.bd'),
(441, 59, 'Gangachara', 'গংগাচড়া', 'gangachara.rangpur.gov.bd'),
(442, 59, 'Taragonj', 'তারাগঞ্জ', 'taragonj.rangpur.gov.bd'),
(443, 59, 'Badargonj', 'বদরগঞ্জ', 'badargonj.rangpur.gov.bd'),
(444, 59, 'Mithapukur', 'মিঠাপুকুর', 'mithapukur.rangpur.gov.bd'),
(445, 59, 'Pirgonj', 'পীরগঞ্জ', 'pirgonj.rangpur.gov.bd'),
(446, 59, 'Kaunia', 'কাউনিয়া', 'kaunia.rangpur.gov.bd'),
(447, 59, 'Pirgacha', 'পীরগাছা', 'pirgacha.rangpur.gov.bd'),
(448, 60, 'Kurigram Sadar', 'কুড়িগ্রাম সদর', 'kurigramsadar.kurigram.gov.bd'),
(449, 60, 'Nageshwari', 'নাগেশ্বরী', 'nageshwari.kurigram.gov.bd'),
(450, 60, 'Bhurungamari', 'ভুরুঙ্গামারী', 'bhurungamari.kurigram.gov.bd'),
(451, 60, 'Phulbari', 'ফুলবাড়ী', 'phulbari.kurigram.gov.bd'),
(452, 60, 'Rajarhat', 'রাজারহাট', 'rajarhat.kurigram.gov.bd'),
(453, 60, 'Ulipur', 'উলিপুর', 'ulipur.kurigram.gov.bd'),
(454, 60, 'Chilmari', 'চিলমারী', 'chilmari.kurigram.gov.bd'),
(455, 60, 'Rowmari', 'রৌমারী', 'rowmari.kurigram.gov.bd'),
(456, 60, 'Charrajibpur', 'চর রাজিবপুর', 'charrajibpur.kurigram.gov.bd'),
(457, 61, 'Sherpur Sadar', 'শেরপুর সদর', 'sherpursadar.sherpur.gov.bd'),
(458, 61, 'Nalitabari', 'নালিতাবাড়ী', 'nalitabari.sherpur.gov.bd'),
(459, 61, 'Sreebordi', 'শ্রীবরদী', 'sreebordi.sherpur.gov.bd'),
(460, 61, 'Nokla', 'নকলা', 'nokla.sherpur.gov.bd'),
(461, 61, 'Jhenaigati', 'ঝিনাইগাতী', 'jhenaigati.sherpur.gov.bd'),
(462, 62, 'Fulbaria', 'ফুলবাড়ীয়া', 'fulbaria.mymensingh.gov.bd'),
(463, 62, 'Trishal', 'ত্রিশাল', 'trishal.mymensingh.gov.bd'),
(464, 62, 'Bhaluka', 'ভালুকা', 'bhaluka.mymensingh.gov.bd'),
(465, 62, 'Muktagacha', 'মুক্তাগাছা', 'muktagacha.mymensingh.gov.bd'),
(466, 62, 'Mymensingh Sadar', 'ময়মনসিংহ সদর', 'mymensinghsadar.mymensingh.gov.bd'),
(467, 62, 'Dhobaura', 'ধোবাউড়া', 'dhobaura.mymensingh.gov.bd'),
(468, 62, 'Phulpur', 'ফুলপুর', 'phulpur.mymensingh.gov.bd'),
(469, 62, 'Haluaghat', 'হালুয়াঘাট', 'haluaghat.mymensingh.gov.bd'),
(470, 62, 'Gouripur', 'গৌরীপুর', 'gouripur.mymensingh.gov.bd'),
(471, 62, 'Gafargaon', 'গফরগাঁও', 'gafargaon.mymensingh.gov.bd'),
(472, 62, 'Iswarganj', 'ঈশ্বরগঞ্জ', 'iswarganj.mymensingh.gov.bd'),
(473, 62, 'Nandail', 'নান্দাইল', 'nandail.mymensingh.gov.bd'),
(474, 62, 'Tarakanda', 'তারাকান্দা', 'tarakanda.mymensingh.gov.bd'),
(475, 63, 'Jamalpur Sadar', 'জামালপুর সদর', 'jamalpursadar.jamalpur.gov.bd'),
(476, 63, 'Melandah', 'মেলান্দহ', 'melandah.jamalpur.gov.bd'),
(477, 63, 'Islampur', 'ইসলামপুর', 'islampur.jamalpur.gov.bd'),
(478, 63, 'Dewangonj', 'দেওয়ানগঞ্জ', 'dewangonj.jamalpur.gov.bd'),
(479, 63, 'Sarishabari', 'সরিষাবাড়ী', 'sarishabari.jamalpur.gov.bd'),
(480, 63, 'Madarganj', 'মাদারগঞ্জ', 'madarganj.jamalpur.gov.bd'),
(481, 63, 'Bokshiganj', 'বকশীগঞ্জ', 'bokshiganj.jamalpur.gov.bd'),
(482, 64, 'Barhatta', 'বারহাট্টা', 'barhatta.netrokona.gov.bd'),
(483, 64, 'Durgapur', 'দুর্গাপুর', 'durgapur.netrokona.gov.bd'),
(484, 64, 'Kendua', 'কেন্দুয়া', 'kendua.netrokona.gov.bd'),
(485, 64, 'Atpara', 'আটপাড়া', 'atpara.netrokona.gov.bd'),
(486, 64, 'Madan', 'মদন', 'madan.netrokona.gov.bd'),
(487, 64, 'Khaliajuri', 'খালিয়াজুরী', 'khaliajuri.netrokona.gov.bd'),
(488, 64, 'Kalmakanda', 'কলমাকান্দা', 'kalmakanda.netrokona.gov.bd'),
(489, 64, 'Mohongonj', 'মোহনগঞ্জ', 'mohongonj.netrokona.gov.bd'),
(490, 64, 'Purbadhala', 'পূর্বধলা', 'purbadhala.netrokona.gov.bd'),
(491, 64, 'Netrokona Sadar', 'নেত্রকোণা সদর', 'netrokonasadar.netrokona.gov.bd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` int(11) DEFAULT 0,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ic_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_super_admin` int(11) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `user_type`, `email`, `phone`, `address`, `ic_number`, `email_verified_at`, `is_super_admin`, `password`, `remember_token`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(24, 'NEXTGENiT Super Amin', '1', 0, 'admin@app.com', '01882348340', NULL, NULL, NULL, 1, '$2y$10$rhM4b9sjMzu8jPPdnJYkIeA6A0VduJdjevff4U3JZVLg/gS4dyNTu', 'ET3CS2f1lJPo4zp3Fekiub4U7tIlUE2gshFQ04k2KdiE3z87yFodyDVliaC0', 1, '2020-01-04 07:24:42', '2021-01-06 04:19:31', NULL),
(327, 'Mr. Mozaher Uddin', '551', 2, 'mozaher.uddin@unimassbd.com', '01755-500 117', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$hOnXLuUH4izWxVOzjALVSei31CQx9Q/B2l/ovSngzcSz3WY16WaLi', NULL, 1, '2021-02-06 11:43:01', '2021-02-06 11:43:01', NULL),
(328, 'Mr. S M Shamim Rahman', '73', 1, 'shamim.rahman@unimassbd.com', '01313-714 350', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$aAkzrtw47O85YbvjbwL4AOWVDFrlaWdXnv2FsKtUXaTh5qgX4kGcy', NULL, 1, '2021-02-06 11:46:45', '2021-02-06 11:46:45', NULL),
(329, 'Mr. Md. Abul Kalam Azad', '77', 2, 'abul.kalam@unimassbd.com', '01755-598 788', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$QSjpnlkpoTkE94dxOKF3VOmiJ5OjqE6OPBW1gz1NjIs5YvcU0qapS', NULL, 1, '2021-02-06 11:47:48', '2021-02-06 11:47:48', NULL),
(330, 'Mr. Md. Mahmudur Rahman', '775', 3, 'rahman786', '01321-137 555', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$bjfuFXk8mj8MWJ0KMh8t0.znVCGDp/1VxlOedht/jEuhh5X6JpzYy', NULL, 1, '2021-02-06 11:52:11', '2021-02-06 11:55:44', NULL),
(331, 'Mr. Md. Asifuzzaman Khan', '77', 2, 'asifkhan786', '01775-499 085', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$YDiSCdjJ0Nj091zzys1FeeYAwK9INqwwE5ISebdzZ9zfeg.A6hmqO', NULL, 1, '2021-02-06 11:56:57', '2021-02-06 11:56:57', NULL),
(332, 'Mr. Md. Ismail Hossain', '77', 2, 'ismail786', '01313-714 348', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$qG5ZDldELwHjZokCugp8CetAYY7fM3rHCN7ypKb5m./3xap7jIU3a', NULL, 1, '2021-02-06 11:57:51', '2021-02-06 11:57:51', NULL),
(333, 'Mr. Tanvir Ahmed Lipon', '77', 2, 'lipon786', '01321-137 554', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$eMXwk2MitTDIt8dy29wfbuyDaMuk.cOrDLIB.PUu98uSgqeUwHWBO', NULL, 1, '2021-02-06 11:59:07', '2021-02-06 11:59:07', NULL),
(334, 'Mr. Md. Arafat Rahamn', '77', 2, 'arafat786', '01321-137 556', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$.LLaF2TUj41.4SuPFTms8O/VfS/ybQ6d/uOEBXjxQrdhw3jIquL9i', NULL, 1, '2021-02-06 11:59:58', '2021-02-06 11:59:58', NULL),
(335, 'Mr. Md. Habibur Rahman', '77', 2, 'habib786', '01321-137 552', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$Vh/XnpbflCWyLPqL2t3.MOE65M1V7WPivvQqLOuwe2vJxgQiFtNL6', NULL, 1, '2021-02-06 12:00:50', '2021-02-06 12:00:50', NULL),
(336, 'Mr. S.M. Jauhan Uddin', '77', 2, 'jauhan786', '01321-137 553', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$1xuz1XlK1TRpm6.taHornOUOooHSgk0DbnQrtbW.nQPlb1PXXroZ2', NULL, 1, '2021-02-06 12:01:34', '2021-02-06 12:01:34', NULL),
(337, 'Mr. Md. Sadman Sakib', '77', 2, 'sadman786', '01313-714 347', 'House # 18, kazi Nazrul Islam Avenue, Shahbagh, Dhaka.', NULL, NULL, NULL, '$2y$10$7lPkD3ncLDCb2F/giR8KC.3ckMw1Ia9G7Im1knoc/WDb/yGoDWaY2', NULL, 1, '2021-02-06 12:02:46', '2021-02-06 12:02:46', NULL);

-- --------------------------------------------------------

--
-- Structure for view `duplicate_phn`
--
DROP TABLE IF EXISTS `duplicate_phn`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `duplicate_phn`  AS  select `x`.`GROUP_CONCAT(lead_pk_no)` AS `lead_pk_no`,`x`.`min(lead_pk_no)` AS `min_lead_pk_no`,`x`.`lead_ids` AS `lead_ids`,`x`.`phone1_count` AS `phone1_count`,`x`.`GROUP_CONCAT(phone1)` AS `GROUP_CONCAT(phone1)`,`x`.`phone1_code` AS `phone1_code`,`x`.`phone1` AS `phone1`,`x`.`lead_current_stage` AS `lead_current_stage` from (select group_concat(`t_lead2lifecycle_vw`.`lead_pk_no` separator ',') AS `GROUP_CONCAT(lead_pk_no)`,min(`t_lead2lifecycle_vw`.`lead_pk_no`) AS `min(lead_pk_no)`,group_concat(`t_lead2lifecycle_vw`.`lead_id` separator ',') AS `lead_ids`,group_concat(`t_lead2lifecycle_vw`.`lead_current_stage` separator ',') AS `lead_current_stage`,count(`t_lead2lifecycle_vw`.`phone1`) AS `phone1_count`,group_concat(`t_lead2lifecycle_vw`.`phone1` separator ',') AS `GROUP_CONCAT(phone1)`,`t_lead2lifecycle_vw`.`phone1_code` AS `phone1_code`,`t_lead2lifecycle_vw`.`phone1` AS `phone1` from `t_lead2lifecycle_vw` group by `t_lead2lifecycle_vw`.`phone1_code`,`t_lead2lifecycle_vw`.`phone1` order by `t_lead2lifecycle_vw`.`phone1`) `x` where `x`.`phone1_count` > 1 ;

-- --------------------------------------------------------

--
-- Structure for view `kpi_acr`
--
DROP TABLE IF EXISTS `kpi_acr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kpi_acr`  AS  select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`user_fullname` AS `user_name`,`acrcnt`.`k1_count` AS `k1_count`,`acrcnt`.`priority_count` AS `priority_count`,`acrcnt`.`sold_count` AS `sold_count`,`acrcnt`.`priority_count` / `acrcnt`.`k1_count` AS `k1_priority_ratio`,`acrcnt`.`sold_count` / `acrcnt`.`priority_count` AS `priority_sold_ratio` from ((`t_teambuild` `tb` join `s_user` `u` on(`tb`.`user_pk_no` = `u`.`user_pk_no`)) left join `kpi_acr_count` `acrcnt` on(`acrcnt`.`lead_sales_agent_pk_no` = `u`.`user_pk_no`)) ;

-- --------------------------------------------------------

--
-- Structure for view `kpi_acr_count`
--
DROP TABLE IF EXISTS `kpi_acr_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kpi_acr_count`  AS  select `t_leadlifecycle`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,sum(`t_leadlifecycle`.`lead_k1_flag`) AS `k1_count`,sum(`t_leadlifecycle`.`lead_priority_flag`) AS `priority_count`,sum(`t_leadlifecycle`.`lead_sold_flag`) AS `sold_count` from `t_leadlifecycle` group by `t_leadlifecycle`.`lead_sales_agent_pk_no` ;

-- --------------------------------------------------------

--
-- Structure for view `kpi_apt`
--
DROP TABLE IF EXISTS `kpi_apt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kpi_apt`  AS  select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`user_fullname` AS `user_name`,`aptdd`.`lead2k1` AS `lead2k1`,`aptdd`.`k12priority` AS `k12priority`,`aptdd`.`priority2sold` AS `priority2sold`,`aptdd`.`k12sold` AS `k12sold` from ((`t_teambuild` `tb` join `s_user` `u` on(`tb`.`user_pk_no` = `u`.`user_pk_no`)) left join `kpi_apt_avgprocdays` `aptdd` on(`aptdd`.`lead_sales_agent_pk_no` = `u`.`user_pk_no`)) ;

-- --------------------------------------------------------

--
-- Structure for view `kpi_apt_avgprocdays`
--
DROP TABLE IF EXISTS `kpi_apt_avgprocdays`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kpi_apt_avgprocdays`  AS  select `t_leadlifecycle`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,avg(to_days(`t_leadlifecycle`.`lead_k1_datetime`) - to_days(`t_leadlifecycle`.`lead_qc_datetime`)) AS `lead2k1`,avg(to_days(`t_leadlifecycle`.`lead_priority_datetime`) - to_days(`t_leadlifecycle`.`lead_k1_datetime`)) AS `k12priority`,avg(to_days(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`)) - to_days(`t_leadlifecycle`.`lead_priority_datetime`)) AS `priority2sold`,avg(to_days(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`)) - to_days(`t_leadlifecycle`.`lead_k1_datetime`)) AS `k12sold` from `t_leadlifecycle` group by `t_leadlifecycle`.`lead_sales_agent_pk_no` ;

-- --------------------------------------------------------

--
-- Structure for view `kpi_avt`
--
DROP TABLE IF EXISTS `kpi_avt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kpi_avt`  AS  select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`user_fullname` AS `user_name`,`tt`.`yy_mm` AS `yy_mm`,`tt`.`target_amount` AS `target_amount`,`tt`.`target_by_lead_qty` AS `target_by_lead_qty`,`samt`.`sold_yymm` AS `sold_yymm`,`samt`.`sold_amt` AS `sold_amt` from (((`t_teambuild` `tb` join `s_user` `u` on(`tb`.`user_pk_no` = `u`.`user_pk_no`)) left join `t_teamtarget` `tt` on(`u`.`user_pk_no` = `tt`.`user_pk_no`)) left join `kpi_soldamt_yymm` `samt` on(`samt`.`lead_sales_agent_pk_no` = `u`.`user_pk_no` and `tt`.`yy_mm` = `samt`.`sold_yymm`)) ;

-- --------------------------------------------------------

--
-- Structure for view `t_lead2lifecycle_vw`
--
DROP TABLE IF EXISTS `t_lead2lifecycle_vw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `t_lead2lifecycle_vw`  AS  select `ld`.`lead_pk_no` AS `lead_pk_no`,`ld`.`lead_id` AS `lead_id`,`ld`.`customer_firstname` AS `customer_firstname`,`ld`.`customer_lastname` AS `customer_lastname`,`ld`.`customer_firstname2` AS `customer_firstname2`,`ld`.`customer_lastname2` AS `customer_lastname2`,`ld`.`phone1_code` AS `phone1_code`,`ld`.`phone1` AS `phone1`,`ld`.`phone2_code` AS `phone2_code`,`ld`.`phone2` AS `phone2`,`ld`.`email_id` AS `email_id`,`ld`.`occupation_pk_no` AS `occupation_pk_no`,`lk_oc`.`lookup_name` AS `occup_name`,`ld`.`organization_pk_no` AS `organization_pk_no`,`lk_og`.`lookup_name` AS `org_name`,`ld`.`project_category_pk_no` AS `project_category_pk_no`,`lk_cat`.`lookup_name` AS `project_category_name`,`ld`.`project_area_pk_no` AS `project_area_pk_no`,`lk_area`.`lookup_name` AS `project_area`,`ld`.`Project_pk_no` AS `Project_pk_no`,`lk_pr`.`lookup_name` AS `project_name`,`ld`.`project_size_pk_no` AS `project_size_pk_no`,`lk_size`.`lookup_name` AS `project_size`,`lf`.`flatlist_pk_no` AS `flatlist_pk_no`,`ld`.`source_auto_pk_no` AS `source_auto_pk_no`,`usr`.`user_fullname` AS `user_full_name`,`ld`.`source_auto_usergroup_pk_no` AS `source_auto_usergroup_pk_no`,`usr_grp`.`lookup_name` AS `source_auto_usergroup`,`ld`.`source_auto_sub` AS `source_auto_sub`,`ld`.`source_sac_name` AS `source_sac_name`,`ld`.`source_sac_note` AS `source_sac_note`,`ld`.`source_digital_marketing` AS `source_digital_marketing`,`ld`.`source_hotline` AS `source_hotline`,`ld`.`source_internal_reference` AS `source_internal_reference`,`ld`.`source_ir_emp_id` AS `source_ir_emp_id`,`ld`.`source_ir_name` AS `source_ir_name`,`ld`.`source_ir_position` AS `source_ir_position`,`ld`.`source_ir_contact_no` AS `source_ir_contact_no`,`ld`.`source_sales_executive` AS `source_sales_executive`,`ld`.`remarks` AS `remarks`,`ld`.`Customer_dateofbirth` AS `Customer_dateofbirth`,`ld`.`customer_wife_name` AS `customer_wife_name`,`ld`.`customer_wife_dataofbirth` AS `customer_wife_dataofbirth`,`ld`.`Marriage_anniversary` AS `Marriage_anniversary`,`ld`.`children_name1` AS `children_name1`,`ld`.`children_dateofbirth1` AS `children_dateofbirth1`,`ld`.`children_name2` AS `children_name2`,`ld`.`children_dateofbirth2` AS `children_dateofbirth2`,`ld`.`children_name3` AS `children_name3`,`ld`.`children_dateofbirth3` AS `children_dateofbirth3`,`ld`.`cust_designation` AS `cust_designation`,`ld`.`pre_holding_no` AS `pre_holding_no`,`ld`.`pre_road_no` AS `pre_road_no`,`ld`.`pre_area` AS `pre_area`,`ld`.`pre_district` AS `pre_district`,`ld`.`pre_thana` AS `pre_thana`,`ld`.`pre_size` AS `pre_size`,`ld`.`per_holding_no` AS `per_holding_no`,`ld`.`per_road_no` AS `per_road_no`,`ld`.`per_area` AS `per_area`,`ld`.`per_district` AS `per_district`,`ld`.`per_thana` AS `per_thana`,`ld`.`office_holding_no` AS `office_holding_no`,`ld`.`office_road_no` AS `office_road_no`,`ld`.`office_area` AS `office_area`,`ld`.`office_district` AS `office_district`,`ld`.`office_thana` AS `office_thana`,`ld`.`meeting_status` AS `meeting_status`,`ld`.`meeting_date` AS `meeting_date`,`ld`.`meeting_time` AS `meeting_time`,`ld`.`food_habit` AS `food_habit`,`ld`.`political_opinion` AS `political_opinion`,`ld`.`car_preference` AS `car_preference`,`ld`.`color_preference` AS `color_preference`,`ld`.`hobby` AS `hobby`,`ld`.`traveling_history` AS `traveling_history`,`ld`.`member_of_club` AS `member_of_club`,`ld`.`child_education` AS `child_education`,`ld`.`disease_name` AS `disease_name`,`ld`.`created_by` AS `created_by`,`ld`.`created_at` AS `created_at`,`lf`.`leadlifecycle_pk_no` AS `leadlifecycle_pk_no`,`lf`.`leadlifecycle_id` AS `leadlifecycle_id`,`lf`.`lead_dist_type` AS `lead_dist_type`,`lf`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,`lf`.`lead_sales_agent_assign_dt` AS `lead_sales_agent_assign_dt`,`lf`.`lead_cluster_head_assign_dt` AS `lead_cluster_head_assign_dt`,`lf`.`lead_cluster_head_pk_no` AS `lead_cluster_head_pk_no`,`lf`.`is_block` AS `is_block`,`lf`.`is_approved` AS `is_approved`,`lf`.`is_approved_by` AS `is_approved_by`,`s_ch`.`user_fullname` AS `lead_cluster_head_name`,`s_agent`.`user_fullname` AS `lead_sales_agent_name`,`s_agent`.`mobile_no` AS `lead_sales_agent_number`,`s_agent`.`role_lookup_pk_no` AS `role_lookup_pk_no`,`s_user_group`.`lookup_name` AS `user_group_name`,`lf`.`lead_current_stage` AS `lead_current_stage`,`lk_st`.`lookup_name` AS `lead_current_stage_name`,`lf`.`lead_qc_flag` AS `lead_qc_flag`,`lf`.`lead_qc_datetime` AS `lead_qc_datetime`,`lf`.`lead_qc_by` AS `lead_qc_by`,`lf`.`lead_k1_flag` AS `lead_k1_flag`,`lf`.`lead_k1_datetime` AS `lead_k1_datetime`,`lf`.`lead_k1_by` AS `lead_k1_by`,`lf`.`lead_hp_flag` AS `lead_hp_flag`,`lf`.`lead_hp_datetime` AS `lead_hp_datetime`,`lf`.`lead_hp_by` AS `lead_hp_by`,`lf`.`lead_priority_flag` AS `lead_priority_flag`,`lf`.`lead_priority_datetime` AS `lead_priority_datetime`,`lf`.`lead_priority_by` AS `lead_priority_by`,`lf`.`lead_hold_flag` AS `lead_hold_flag`,`lf`.`lead_hold_datetime` AS `lead_hold_datetime`,`lf`.`lead_hold_by` AS `lead_hold_by`,`lf`.`lead_closed_flag` AS `lead_closed_flag`,`lf`.`lead_closed_datetime` AS `lead_closed_datetime`,`lf`.`lead_closed_by` AS `lead_closed_by`,`lf`.`lead_sold_flag` AS `lead_sold_flag`,`lf`.`lead_sold_datetime` AS `lead_sold_datetime`,`lf`.`lead_sold_by` AS `lead_sold_by`,`lf`.`lead_sold_date_manual` AS `lead_sold_date_manual`,`lf`.`lead_sold_flatcost` AS `lead_sold_flatcost`,`lf`.`lead_sold_utilitycost` AS `lead_sold_utilitycost`,`lf`.`lead_sold_parkingcost` AS `lead_sold_parkingcost`,`lf`.`lead_sold_customer_pk_no` AS `lead_sold_customer_pk_no`,`lf`.`lead_sold_sales_agent_pk_no` AS `lead_sold_sales_agent_pk_no`,`lf`.`lead_sold_team_lead_pk_no` AS `lead_sold_team_lead_pk_no`,`lf`.`lead_sold_team_manager_pk_no` AS `lead_sold_team_manager_pk_no`,`lf`.`lead_transfer_flag` AS `lead_transfer_flag`,`lf`.`lead_entry_type` AS `lead_entry_type`,`lf`.`lead_dist_by` AS `lead_dist_by`,`lf`.`distribute_to` AS `distribute_to`,`lf`.`junk_ind` AS `junk_ind`,`lf`.`lead_transfer_from_sales_agent_pk_no` AS `lead_transfer_from_sales_agent_pk_no`,`lf`.`lead_reserve_money` AS `lead_reserve_money`,`lf`.`is_note_sheet_approved` AS `is_note_sheet_approved`,'' AS `all_phone_no` from (((((((((((((`t_leads` `ld` left join `t_leadlifecycle` `lf` on(`ld`.`lead_pk_no` = `lf`.`lead_pk_no`)) left join `s_lookdata` `lk_oc` on(`lk_oc`.`lookup_pk_no` = `ld`.`occupation_pk_no`)) left join `s_lookdata` `lk_og` on(`lk_og`.`lookup_pk_no` = `ld`.`organization_pk_no`)) left join `s_lookdata` `lk_st` on(`lk_st`.`lookup_pk_no` = `lf`.`lead_current_stage`)) left join `s_lookdata` `lk_pr` on(`lk_pr`.`lookup_pk_no` = `ld`.`Project_pk_no`)) left join `s_user` `s_agent` on(`s_agent`.`user_pk_no` = `lf`.`lead_sales_agent_pk_no`)) left join `s_user` `s_ch` on(`s_ch`.`user_pk_no` = `lf`.`lead_cluster_head_pk_no`)) left join `s_lookdata` `s_user_group` on(`s_user_group`.`lookup_pk_no` = `s_agent`.`role_lookup_pk_no`)) left join `s_lookdata` `lk_cat` on(`lk_cat`.`lookup_pk_no` = `ld`.`project_category_pk_no`)) left join `s_lookdata` `lk_area` on(`lk_area`.`lookup_pk_no` = `ld`.`project_area_pk_no`)) left join `s_lookdata` `lk_size` on(`lk_size`.`lookup_pk_no` = `ld`.`project_size_pk_no`)) left join `s_user` `usr` on(`usr`.`user_pk_no` = `ld`.`source_auto_pk_no`)) left join `s_lookdata` `usr_grp` on(`usr_grp`.`lookup_pk_no` = `ld`.`source_auto_usergroup_pk_no`)) ;

-- --------------------------------------------------------

--
-- Structure for view `t_lead_followup_count_by_current_stage_vw`
--
DROP TABLE IF EXISTS `t_lead_followup_count_by_current_stage_vw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `t_lead_followup_count_by_current_stage_vw`  AS  select `a`.`lead_pk_no` AS `lead_pk_no`,`a`.`lead_id` AS `lead_id`,`a`.`created_at` AS `created_at`,`a`.`customer_firstname` AS `customer_firstname`,`a`.`customer_lastname` AS `customer_lastname`,`a`.`phone1_code` AS `phone1_code`,`a`.`phone1` AS `phone1`,`a`.`lead_current_stage` AS `lead_current_stage`,`a`.`project_category_name` AS `project_category_name`,`a`.`project_area` AS `project_area`,`a`.`project_name` AS `project_name`,`a`.`project_size` AS `project_size`,`a`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk`,`a`.`lead_sales_agent_name` AS `lead_sales_agent_name`,`a`.`project_category_pk_no` AS `project_category_pk_no`,`a`.`created_by` AS `created_by`,`a`.`user_full_name` AS `user_full_name`,count(`b`.`lead_stage_after_followup`) AS `no_of_followup`,max(`b`.`lead_followup_datetime`) AS `last_lead_followup_datetime` from (`t_lead2lifecycle_vw` `a` left join `t_leadfollowup` `b` on(`a`.`lead_pk_no` = `b`.`lead_pk_no` and `a`.`lead_current_stage` = `b`.`lead_stage_after_followup`)) group by `a`.`lead_pk_no`,`a`.`lead_id`,`a`.`created_at`,`a`.`created_by`,`a`.`customer_firstname`,`a`.`customer_lastname`,`a`.`phone1_code`,`a`.`phone1`,`a`.`lead_current_stage`,`a`.`project_category_name`,`a`.`project_area`,`a`.`project_name`,`a`.`project_size`,`a`.`lead_sales_agent_pk_no`,`a`.`project_category_pk_no`,`a`.`lead_sales_agent_name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`COUNTRY_PK_NO`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proc_code`
--
ALTER TABLE `proc_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_schedule_collectoins`
--
ALTER TABLE `project_schedule_collectoins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sold_project_schedules`
--
ALTER TABLE `sold_project_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_company`
--
ALTER TABLE `s_company`
  ADD PRIMARY KEY (`c_pk_no`);

--
-- Indexes for table `s_groupcomp`
--
ALTER TABLE `s_groupcomp`
  ADD PRIMARY KEY (`gc_pk_no`);

--
-- Indexes for table `s_lookdata`
--
ALTER TABLE `s_lookdata`
  ADD PRIMARY KEY (`lookup_pk_no`);

--
-- Indexes for table `s_pages`
--
ALTER TABLE `s_pages`
  ADD PRIMARY KEY (`page_pk_no`);

--
-- Indexes for table `s_projectwiseflatlist`
--
ALTER TABLE `s_projectwiseflatlist`
  ADD PRIMARY KEY (`flatlist_pk_no`);

--
-- Indexes for table `s_rbac`
--
ALTER TABLE `s_rbac`
  ADD PRIMARY KEY (`rbac_pk_no`);

--
-- Indexes for table `s_teamuser`
--
ALTER TABLE `s_teamuser`
  ADD PRIMARY KEY (`team_pk_no`);

--
-- Indexes for table `s_user`
--
ALTER TABLE `s_user`
  ADD PRIMARY KEY (`user_pk_no`);

--
-- Indexes for table `t_eventlog`
--
ALTER TABLE `t_eventlog`
  ADD PRIMARY KEY (`event_pk_no`);

--
-- Indexes for table `t_eventlog_detail`
--
ALTER TABLE `t_eventlog_detail`
  ADD PRIMARY KEY (`eventdtl_pk_no`);

--
-- Indexes for table `t_leadfollowup`
--
ALTER TABLE `t_leadfollowup`
  ADD PRIMARY KEY (`lead_followup_pk_no`),
  ADD KEY `lead_pk_no` (`lead_pk_no`);

--
-- Indexes for table `t_leadfollowup_attribute`
--
ALTER TABLE `t_leadfollowup_attribute`
  ADD PRIMARY KEY (`followup_attr_pk_no`);

--
-- Indexes for table `t_leadkychistory`
--
ALTER TABLE `t_leadkychistory`
  ADD PRIMARY KEY (`t_leadkyc_pk_no`);

--
-- Indexes for table `t_leadlifecycle`
--
ALTER TABLE `t_leadlifecycle`
  ADD PRIMARY KEY (`leadlifecycle_pk_no`),
  ADD KEY `lead_pk_no` (`lead_pk_no`);

--
-- Indexes for table `t_leads`
--
ALTER TABLE `t_leads`
  ADD PRIMARY KEY (`lead_pk_no`);

--
-- Indexes for table `t_leadshistory`
--
ALTER TABLE `t_leadshistory`
  ADD PRIMARY KEY (`leadhistory_pk_no`),
  ADD KEY `lead_pk_no` (`lead_pk_no`),
  ADD KEY `phone1_code` (`phone1_code`),
  ADD KEY `phone1` (`phone1`);

--
-- Indexes for table `t_leadstagehistory`
--
ALTER TABLE `t_leadstagehistory`
  ADD PRIMARY KEY (`lead_stage_pk_no`);

--
-- Indexes for table `t_leadstage_attribute`
--
ALTER TABLE `t_leadstage_attribute`
  ADD PRIMARY KEY (`attr_pk_no`);

--
-- Indexes for table `t_leads_copy`
--
ALTER TABLE `t_leads_copy`
  ADD PRIMARY KEY (`lead_pk_no`);

--
-- Indexes for table `t_leadtransfer`
--
ALTER TABLE `t_leadtransfer`
  ADD PRIMARY KEY (`transfer_pk_no`),
  ADD KEY `lead_pk_no` (`lead_pk_no`);

--
-- Indexes for table `t_leadtransferhistory`
--
ALTER TABLE `t_leadtransferhistory`
  ADD PRIMARY KEY (`transhistory_pk_no`),
  ADD KEY `lead_pk_no` (`lead_pk_no`);

--
-- Indexes for table `t_teambuild`
--
ALTER TABLE `t_teambuild`
  ADD PRIMARY KEY (`teammem_pk_no`);

--
-- Indexes for table `t_teambuildchd`
--
ALTER TABLE `t_teambuildchd`
  ADD PRIMARY KEY (`teammemchd_pk_no`);

--
-- Indexes for table `t_teamtarget`
--
ALTER TABLE `t_teamtarget`
  ADD PRIMARY KEY (`target_pk_no`);

--
-- Indexes for table `upazilas`
--
ALTER TABLE `upazilas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `COUNTRY_PK_NO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `proc_code`
--
ALTER TABLE `proc_code`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=587;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_schedule_collectoins`
--
ALTER TABLE `project_schedule_collectoins`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sold_project_schedules`
--
ALTER TABLE `sold_project_schedules`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `s_company`
--
ALTER TABLE `s_company`
  MODIFY `c_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_groupcomp`
--
ALTER TABLE `s_groupcomp`
  MODIFY `gc_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_lookdata`
--
ALTER TABLE `s_lookdata`
  MODIFY `lookup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=779;

--
-- AUTO_INCREMENT for table `s_pages`
--
ALTER TABLE `s_pages`
  MODIFY `page_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `s_projectwiseflatlist`
--
ALTER TABLE `s_projectwiseflatlist`
  MODIFY `flatlist_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `s_rbac`
--
ALTER TABLE `s_rbac`
  MODIFY `rbac_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `s_teamuser`
--
ALTER TABLE `s_teamuser`
  MODIFY `team_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_user`
--
ALTER TABLE `s_user`
  MODIFY `user_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `t_eventlog`
--
ALTER TABLE `t_eventlog`
  MODIFY `event_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_eventlog_detail`
--
ALTER TABLE `t_eventlog_detail`
  MODIFY `eventdtl_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadfollowup`
--
ALTER TABLE `t_leadfollowup`
  MODIFY `lead_followup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_leadfollowup_attribute`
--
ALTER TABLE `t_leadfollowup_attribute`
  MODIFY `followup_attr_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadkychistory`
--
ALTER TABLE `t_leadkychistory`
  MODIFY `t_leadkyc_pk_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadlifecycle`
--
ALTER TABLE `t_leadlifecycle`
  MODIFY `leadlifecycle_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `t_leads`
--
ALTER TABLE `t_leads`
  MODIFY `lead_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `t_leadshistory`
--
ALTER TABLE `t_leadshistory`
  MODIFY `leadhistory_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadstagehistory`
--
ALTER TABLE `t_leadstagehistory`
  MODIFY `lead_stage_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadstage_attribute`
--
ALTER TABLE `t_leadstage_attribute`
  MODIFY `attr_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `t_leads_copy`
--
ALTER TABLE `t_leads_copy`
  MODIFY `lead_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadtransfer`
--
ALTER TABLE `t_leadtransfer`
  MODIFY `transfer_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_leadtransferhistory`
--
ALTER TABLE `t_leadtransferhistory`
  MODIFY `transhistory_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_teambuild`
--
ALTER TABLE `t_teambuild`
  MODIFY `teammem_pk_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `t_teambuildchd`
--
ALTER TABLE `t_teambuildchd`
  MODIFY `teammemchd_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_teamtarget`
--
ALTER TABLE `t_teamtarget`
  MODIFY `target_pk_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upazilas`
--
ALTER TABLE `upazilas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
