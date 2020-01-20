-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: sierra_qualify_oas
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

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
-- Table structure for table `applicant_results`
--

DROP TABLE IF EXISTS `applicant_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applicant_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `index_number` varchar(100) NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `responce` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(50) NOT NULL,
  `Semester` varchar(50) NOT NULL,
  `application_type` int(11) NOT NULL,
  `CSEE` int(11) DEFAULT '0',
  `NT` int(11) DEFAULT '0',
  `entry_category` varchar(11) DEFAULT '0',
  `duration` int(11) DEFAULT '3',
  `FirstName` varchar(255) NOT NULL,
  `MiddleName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Campus` bigint(20) NOT NULL,
  `Disability` int(11) NOT NULL,
  `Nationality` int(11) NOT NULL,
  `form4_index` varchar(255) DEFAULT NULL,
  `form6_index` varchar(255) DEFAULT NULL,
  `diploma_number` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Mobile1` varchar(50) DEFAULT NULL,
  `Mobile2` varchar(50) DEFAULT NULL,
  `postal` text,
  `physical` text,
  `birth_place` text,
  `residence_country` int(11) DEFAULT '0',
  `marital_status` int(11) DEFAULT '1',
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedon` timestamp NULL DEFAULT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.jpg',
  `status` int(11) DEFAULT '0',
  `submitedon` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT '0',
  `submitted` int(11) DEFAULT '0',
  `response` int(11) NOT NULL,
  `tcu_status` int(11) NOT NULL DEFAULT '0',
  `tcu_status_description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1188 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_attachment`
--

DROP TABLE IF EXISTS `application_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate` varchar(11) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `comment` text,
  `applicant_id` int(11) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2059 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_criteria_setting`
--

DROP TABLE IF EXISTS `application_criteria_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_criteria_setting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(50) NOT NULL,
  `entry` varchar(20) DEFAULT NULL,
  `form4_inclusive` text,
  `form4_exclusive` text,
  `form4_pass` int(11) DEFAULT '0',
  `form6_inclusive` text,
  `form6_exclusive` text,
  `form6_pass` int(11) DEFAULT '0',
  `gpa_pass` double DEFAULT NULL,
  `ProgrammeCode` varchar(50) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `modifiedon` timestamp NULL DEFAULT NULL,
  `min_point` double DEFAULT NULL,
  `form4_or_subject` text,
  `form6_or_subject` text,
  `keyword1` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_deadline`
--

DROP TABLE IF EXISTS `application_deadline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_deadline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deadline` date NOT NULL,
  `diploma` int(1) NOT NULL,
  `degree` int(1) NOT NULL,
  `post_graduate` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_diploma_nacteresult`
--

DROP TABLE IF EXISTS `application_diploma_nacteresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_diploma_nacteresult` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `authority_id` bigint(20) NOT NULL,
  `combine` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2033 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_education_authority`
--

DROP TABLE IF EXISTS `application_education_authority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_education_authority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate` varchar(11) DEFAULT NULL,
  `exam_authority` varchar(255) NOT NULL,
  `applicant_id` bigint(20) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `school` text,
  `country` varchar(100) DEFAULT NULL,
  `programme_title` text,
  `createdon` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `index_number` varchar(100) DEFAULT NULL,
  `technician_type` int(11) DEFAULT NULL,
  `completed_year` varchar(100) DEFAULT NULL,
  `center_number` varchar(50) DEFAULT NULL,
  `division_point` varchar(50) DEFAULT NULL,
  `avn` varchar(255) DEFAULT NULL,
  `api_status` int(11) DEFAULT '0',
  `comment` text,
  `response` text,
  `hide` int(11) DEFAULT '0',
  `diploma_code` varchar(50) DEFAULT NULL,
  `programme_category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2299 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_education_subject`
--

DROP TABLE IF EXISTS `application_education_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_education_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `authority_id` bigint(20) NOT NULL,
  `certificate` varchar(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `year` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14006 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_elegibility`
--

DROP TABLE IF EXISTS `application_elegibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_elegibility` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `ProgrammeCode` varchar(50) NOT NULL,
  `choice` int(11) NOT NULL,
  `AYear` varchar(50) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0  = Not Eligible, 1 = Eligible',
  `comment` text,
  `point` int(11) DEFAULT '0',
  `entry_category` varchar(30) DEFAULT NULL,
  `gpa` varchar(30) DEFAULT NULL,
  `selected` int(11) DEFAULT '0' COMMENT '0=Not selected, 1= Selected',
  `selected_counter` int(11) NOT NULL,
  `sitting_no` int(11) DEFAULT NULL,
  `form4_subject` text,
  `form6_subject` text,
  `diploma_info` text,
  `selected_comment` text,
  `round` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=386 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_employer`
--

DROP TABLE IF EXISTS `application_employer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_employer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_experience`
--

DROP TABLE IF EXISTS `application_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_experience` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `name` text NOT NULL,
  `column1` text,
  `column2` text,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `modifiedon` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_id` (`applicant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_nextkin_info`
--

DROP TABLE IF EXISTS `application_nextkin_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_nextkin_info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `postal` mediumtext NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `relation` varchar(100) DEFAULT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1874 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_payment`
--

DROP TABLE IF EXISTS `application_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` double(100,2) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AYear` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`)
) ENGINE=InnoDB AUTO_INCREMENT=977 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_programme_choice`
--

DROP TABLE IF EXISTS `application_programme_choice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_programme_choice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `choice1` varchar(50) NOT NULL,
  `choice2` varchar(50) NOT NULL,
  `choice3` varchar(50) NOT NULL,
  `choice4` varchar(50) DEFAULT NULL,
  `choice5` varchar(50) DEFAULT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AYear` varchar(20) DEFAULT NULL,
  `round` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1098 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_referee`
--

DROP TABLE IF EXISTS `application_referee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_referee` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `organization` text,
  `position` text,
  `address` text NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `applicant_id` bigint(20) NOT NULL,
  `rec_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_referee_recommendation`
--

DROP TABLE IF EXISTS `application_referee_recommendation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_referee_recommendation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `referee_id` bigint(20) NOT NULL,
  `recommend_overall` int(11) DEFAULT '-1',
  `applicant_known` text,
  `description_for_qn3` text,
  `weakness` text,
  `other_degree` int(11) DEFAULT '-1',
  `producing_org_work` int(11) DEFAULT '-1',
  `recommendation_arrea` text,
  `other_capability` text,
  `anything` text,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedon` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_round`
--

DROP TABLE IF EXISTS `application_round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_round` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `round` int(1) NOT NULL,
  `application_type` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_selection_criteria`
--

DROP TABLE IF EXISTS `application_selection_criteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_selection_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `direct` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_selection_criteria_filter`
--

DROP TABLE IF EXISTS `application_selection_criteria_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_selection_criteria_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selection_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `category` int(11) NOT NULL,
  `filter_type` varchar(50) NOT NULL,
  `filter_item` varchar(50) NOT NULL,
  `order_number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_sponsor`
--

DROP TABLE IF EXISTS `application_sponsor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_sponsor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_steps`
--

DROP TABLE IF EXISTS `application_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1188 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ayear`
--

DROP TABLE IF EXISTS `ayear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ayear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(45) NOT NULL,
  `Status` varchar(1) DEFAULT '0',
  `semester` varchar(50) DEFAULT NULL,
  `campus_id` bigint(20) DEFAULT '1',
  `auto_update` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `campus`
--

DROP TABLE IF EXISTS `campus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `college`
--

DROP TABLE IF EXISTS `college`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `college` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `PostalAddress` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Mobile` varchar(45) DEFAULT NULL,
  `City` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `Site` varchar(100) NOT NULL,
  `Telegram` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `swift_code` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `bank_branch` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `college_schools`
--

DROP TABLE IF EXISTS `college_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `college_schools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type1` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `principal` bigint(20) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedby` bigint(20) NOT NULL,
  `modifiedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Facultyid` varchar(45) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `school_id` int(11) DEFAULT '0',
  `head` bigint(20) DEFAULT '0',
  `location` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disability`
--

DROP TABLE IF EXISTS `disability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ega_auth`
--

DROP TABLE IF EXISTS `ega_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ega_auth` (
  `username` varchar(255) NOT NULL,
  `api_secret` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `call_url` varchar(255) NOT NULL,
  `spcode` varchar(10) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ega_logs`
--

DROP TABLE IF EXISTS `ega_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ega_logs` (
  `int` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request` text NOT NULL,
  `responce` text,
  `status` int(1) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`int`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enrolled_student`
--

DROP TABLE IF EXISTS `enrolled_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enrolled_student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regno` varchar(100) NOT NULL,
  `code` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_structure`
--

DROP TABLE IF EXISTS `fee_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `amount` decimal(50,0) NOT NULL,
  `percentage` int(1) NOT NULL DEFAULT '1',
  `fee_code` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_type`
--

DROP TABLE IF EXISTS `fee_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gfscode` varchar(50) NOT NULL,
  `hidden` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` double(100,2) NOT NULL,
  `GfsCode` varchar(100) DEFAULT NULL,
  `control_number` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  `student_name` varchar(255) NOT NULL,
  `student_mobile` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `fee_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `control_number` (`control_number`),
  KEY `userid` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_notification`
--

DROP TABLE IF EXISTS `log_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_notification` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` int(11) NOT NULL,
  `message` text NOT NULL,
  `createdby` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maritalstatus`
--

DROP TABLE IF EXISTS `maritalstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maritalstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_sent`
--

DROP TABLE IF EXISTS `message_sent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_sent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `priority` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_status` varchar(100) NOT NULL,
  `sms_count` int(11) NOT NULL,
  `is_sent` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1155 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_group_role`
--

DROP TABLE IF EXISTS `module_group_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_group_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `section` varchar(200) NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_group_role_1_idx` (`group_id`),
  KEY `fk_module_group_role_2_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_role`
--

DROP TABLE IF EXISTS `module_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `role` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `only_developer` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_module_role_1_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_section`
--

DROP TABLE IF EXISTS `module_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_section_1_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nationality`
--

DROP TABLE IF EXISTS `nationality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nationality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Country` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `necta_check_subject`
--

DROP TABLE IF EXISTS `necta_check_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `necta_check_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `status` int(11) DEFAULT '0',
  `response` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `necta_tmp_result`
--

DROP TABLE IF EXISTS `necta_tmp_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `necta_tmp_result` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `authority_id` int(11) NOT NULL,
  `action` varchar(10) NOT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `applicant_id` bigint(20) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1027 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notify_tmp`
--

DROP TABLE IF EXISTS `notify_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notify_tmp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(255) DEFAULT NULL,
  `chanell_transaction_id` varchar(255) DEFAULT NULL,
  `ega_refference` varchar(255) DEFAULT NULL,
  `invoice_number` int(11) DEFAULT NULL,
  `control_number` varchar(255) DEFAULT NULL,
  `paid_amount` double(50,2) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_channell` varchar(255) DEFAULT NULL,
  `payer_mobile` varchar(12) DEFAULT NULL,
  `payer_name` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `channel_name` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payments_log`
--

DROP TABLE IF EXISTS `payments_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(191) DEFAULT NULL,
  `receipt` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `data` varchar(1000) DEFAULT NULL,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  `response` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2722 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programme`
--

DROP TABLE IF EXISTS `programme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(45) DEFAULT NULL,
  `Shortname` varchar(255) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Departmentid` varchar(45) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `active` int(11) DEFAULT '1',
  `campus_id` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`),
  UNIQUE KEY `Code_UNIQUE` (`Code`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recommandation_area`
--

DROP TABLE IF EXISTS `recommandation_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommandation_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `run_eligibility`
--

DROP TABLE IF EXISTS `run_eligibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_eligibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgrammeCode` varchar(50) NOT NULL,
  `round` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `run_selection`
--

DROP TABLE IF EXISTS `run_selection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_selection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgrammeCode` varchar(50) NOT NULL,
  `choice` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `secondary_category`
--

DROP TABLE IF EXISTS `secondary_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secondary_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `secondary_subject`
--

DROP TABLE IF EXISTS `secondary_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secondary_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) DEFAULT '1',
  `code` varchar(50) DEFAULT NULL,
  `category` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_fee`
--

DROP TABLE IF EXISTS `student_fee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_fee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` int(10) unsigned NOT NULL,
  `student_regno` varchar(255) NOT NULL,
  `fee_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tcu_admitted`
--

DROP TABLE IF EXISTS `tcu_admitted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tcu_admitted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f4index` varchar(50) NOT NULL,
  `f6index` varchar(50) NOT NULL,
  `programmechoices` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `admissionstatus` varchar(10) NOT NULL,
  `admittedprogramme` varchar(10) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `response` varchar(200) DEFAULT NULL,
  `round` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=985 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tcu_records`
--

DROP TABLE IF EXISTS `tcu_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tcu_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `error_code` varchar(50) DEFAULT NULL,
  `f4indexno` varchar(15) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `request_status` int(11) DEFAULT NULL,
  `request` varchar(250) DEFAULT NULL,
  `response` varchar(250) DEFAULT NULL,
  `createnon` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1348 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technician_type`
--

DROP TABLE IF EXISTS `technician_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_login_history`
--

DROP TABLE IF EXISTS `user_login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `browser` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14238 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_title`
--

DROP TABLE IF EXISTS `user_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_title` (
  `id` int(11) NOT NULL,
  `title` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile` varchar(255) DEFAULT 'default.jpg',
  `title` varchar(50) DEFAULT NULL,
  `campus_id` int(11) DEFAULT '1',
  `forgotten_password_time` int(11) DEFAULT NULL,
  `access_area` int(11) DEFAULT NULL COMMENT '1=Schools,2=Department',
  `access_area_id` int(11) DEFAULT NULL,
  `sims_map` bigint(20) DEFAULT '0',
  `applicant_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1844 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1844 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_login_attempts`
--

DROP TABLE IF EXISTS `users_login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_login_attempts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4179 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-20 13:24:38

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'127.0.0.1','administrator','$2y$08$KDOmsevSNK4JFRfSCnyKUOdqGozxmMdhobNF2IS0Bd.2GDiL8CZEm',NULL,'elipokea.mosses@zalongwa.com',NULL,'folc37Yv9dGAYisrNP7GHe1592a090ae9ce46c63',NULL,1498301400,1577371094,1,'Elipokea','Shio','255784313200','default.jpg','Mr',1,1502782071,0,0,0,0),(653,'197.250.98.15','winifrid.mapunda','$2y$08$/A3f6REKkmSdNKRgsEftC.qb6gj4GsQrhOEDhxcQdBUUO6bboNV6a',NULL,'winfrid31@gmail.com',NULL,NULL,NULL,1562315732,1574844785,1,'Winifrid','Mapunda','255755115164','default.jpg','Mr',1,NULL,0,0,0,0);
UNLOCK TABLES;

LOCK TABLES `user_title` WRITE;
/*!40000 ALTER TABLE `user_title` DISABLE KEYS */;
INSERT INTO `user_title` VALUES (1,'Mr'),(2,'Mrs'),(3,'Dr'),(4,'Prof'),(5,'Ms'),(6,'Rev'),(7,'Eng');
/*!40000 ALTER TABLE `user_title` ENABLE KEYS */;
UNLOCK TABLES;