-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 21, 2017 at 02:59 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 5.6.30-10+deb.sury.org~xenial+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ZTL_OAS2`
--

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `id` bigint(20) NOT NULL,
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
  `submitted` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_attachment`
--

CREATE TABLE `application_attachment` (
  `id` int(11) NOT NULL,
  `certificate` varchar(11) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `comment` text,
  `applicant_id` int(11) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_criteria_setting`
--

CREATE TABLE `application_criteria_setting` (
  `id` bigint(20) NOT NULL,
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
  `form6_or_subject` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_deadline`
--

CREATE TABLE `application_deadline` (
  `id` int(11) NOT NULL,
  `deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `application_deadline`
--

INSERT INTO `application_deadline` (`id`, `deadline`) VALUES
(1, '2017-08-20');

-- --------------------------------------------------------

--
-- Table structure for table `application_diploma_nacteresult`
--

CREATE TABLE `application_diploma_nacteresult` (
  `id` bigint(20) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `authority_id` bigint(20) NOT NULL,
  `combine` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application_education_authority`
--

CREATE TABLE `application_education_authority` (
  `id` int(11) NOT NULL,
  `certificate` varchar(11) DEFAULT NULL,
  `exam_authority` varchar(255) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `division` varchar(100) NOT NULL,
  `school` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `programme_title` text,
  `createdon` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `index_number` varchar(100) DEFAULT NULL,
  `technician_type` int(11) DEFAULT NULL,
  `completed_year` varchar(100) NOT NULL,
  `center_number` varchar(50) DEFAULT NULL,
  `division_point` varchar(50) DEFAULT NULL,
  `avn` varchar(255) DEFAULT NULL,
  `api_status` int(11) DEFAULT '0',
  `comment` text,
  `response` text,
  `hide` int(11) DEFAULT '0',
  `diploma_code` varchar(50) DEFAULT NULL,
  `programme_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_education_subject`
--

CREATE TABLE `application_education_subject` (
  `id` int(11) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `authority_id` bigint(20) NOT NULL,
  `certificate` varchar(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `year` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_elegibility`
--

CREATE TABLE `application_elegibility` (
  `id` bigint(20) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `ProgrammeCode` varchar(50) NOT NULL,
  `choice` int(11) NOT NULL,
  `AYear` varchar(50) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0  = Not Eligible, 1 = Eligible',
  `comment` text,
  `point` int(11) NOT NULL,
  `entry_category` varchar(30) DEFAULT NULL,
  `gpa` varchar(30) DEFAULT NULL,
  `selected` int(11) DEFAULT '0' COMMENT '0=Not selected, 1= Selected',
  `sitting_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application_employer`
--

CREATE TABLE `application_employer` (
  `id` bigint(20) NOT NULL,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_experience`
--

CREATE TABLE `application_experience` (
  `id` bigint(20) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `name` text NOT NULL,
  `column1` text,
  `column2` text,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `modifiedon` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application_nextkin_info`
--

CREATE TABLE `application_nextkin_info` (
  `id` bigint(20) NOT NULL,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `postal` mediumtext NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `relation` varchar(100) DEFAULT NULL,
  `applicant_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_payment`
--

CREATE TABLE `application_payment` (
  `id` bigint(20) NOT NULL,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` double(100,2) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application_programme_choice`
--

CREATE TABLE `application_programme_choice` (
  `id` bigint(20) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `choice1` varchar(50) NOT NULL,
  `choice2` varchar(50) NOT NULL,
  `choice3` varchar(50) NOT NULL,
  `choice4` varchar(50) DEFAULT NULL,
  `choice5` varchar(50) DEFAULT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_referee`
--

CREATE TABLE `application_referee` (
  `id` bigint(20) NOT NULL,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `organization` text,
  `position` text,
  `address` text NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `applicant_id` bigint(20) NOT NULL,
  `rec_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_referee_recommendation`
--

CREATE TABLE `application_referee_recommendation` (
  `id` bigint(20) NOT NULL,
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
  `modifiedon` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application_sponsor`
--

CREATE TABLE `application_sponsor` (
  `id` bigint(20) NOT NULL,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_steps`
--

CREATE TABLE `application_steps` (
  `id` int(11) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayear`
--

CREATE TABLE `ayear` (
  `id` int(11) NOT NULL,
  `AYear` varchar(45) NOT NULL,
  `Status` varchar(1) DEFAULT '0',
  `semester` varchar(50) DEFAULT NULL,
  `campus_id` bigint(20) DEFAULT '1',
  `auto_update` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayear`
--

INSERT INTO `ayear` (`id`, `AYear`, `Status`, `semester`, `campus_id`, `auto_update`) VALUES
(1, '2017/2018', '1', 'Semester I', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campus`
--

CREATE TABLE `campus` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `campus`
--

INSERT INTO `campus` (`id`, `name`, `location`) VALUES
(1, 'Main Campus', 'Mbeya');

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `PostalAddress` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Mobile` varchar(45) DEFAULT NULL,
  `City` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `Site` varchar(100) NOT NULL,
  `Telegram` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`id`, `Name`, `PostalAddress`, `Email`, `LandLine`, `Mobile`, `City`, `Country`, `Site`, `Telegram`) VALUES
(1, 'ZALONGWA TECHNOLOGIES [ ZTL ]', 'P.O.Box  65001', 'support@zalongwa.com', '+255 022 2150302-6', '+255 022 2150302-6', 'Dar es Salaam', 'TANZANIA', 'http://zalongwa.com', '+255-022-2150465');

-- --------------------------------------------------------

--
-- Table structure for table `college_schools`
--

CREATE TABLE `college_schools` (
  `id` bigint(20) NOT NULL,
  `type1` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `principal` bigint(20) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedby` bigint(20) NOT NULL,
  `modifiedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `Facultyid` varchar(45) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `school_id` int(11) DEFAULT '0',
  `head` bigint(20) DEFAULT '0',
  `location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `disability`
--

CREATE TABLE `disability` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `disability`
--

INSERT INTO `disability` (`id`, `name`) VALUES
(1, 'None'),
(2, 'Skin'),
(3, 'Hearing'),
(4, 'Vision'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `code`, `name`) VALUES
(1, 'M', 'Male'),
(2, 'F', 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `module_id`) VALUES
(1, 'admin', 'Administrator', 1),
(2, 'members', 'Members', 2),
(3, 'hod', 'Head of School/Department', 1),
(4, 'applicant', 'Applicant', 2),
(6, 'Default Group', 'Default Group', 2);

-- --------------------------------------------------------

--
-- Table structure for table `log_notification`
--

CREATE TABLE `log_notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` int(11) NOT NULL,
  `message` text NOT NULL,
  `createdby` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log_notification`
--

INSERT INTO `log_notification` (`id`, `date_time`, `priority`, `message`, `createdby`) VALUES
(1, '2017-07-21 11:42:56', 1, 'New user  Shio Moses created and assigned to group Administrator.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `maritalstatus`
--

CREATE TABLE `maritalstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `maritalstatus`
--

INSERT INTO `maritalstatus` (`id`, `name`) VALUES
(1, 'Single'),
(2, 'Married'),
(3, 'Divorced'),
(4, 'Widowed'),
(5, 'Separated'),
(6, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `message_sent`
--

CREATE TABLE `message_sent` (
  `id` bigint(20) NOT NULL,
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
  `status` varchar(50) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`) VALUES
(1, 'OSA Module', 'OSA Module'),
(2, 'Applicant Module', 'Applicant Module');

-- --------------------------------------------------------

--
-- Table structure for table `module_group_role`
--

CREATE TABLE `module_group_role` (
  `id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `section` varchar(200) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module_group_role`
--

INSERT INTO `module_group_role` (`id`, `group_id`, `module_id`, `section`, `role`) VALUES
(1, 1, 1, 'MANAGE_USERS', '["group_list","grant_access","create_group","create_user","user_list","password_reset"]'),
(2, 1, 1, 'DATA_FROM_SIMS', '["school_list","department_list","programme_list"]'),
(3, 1, 1, 'SETTINGS', '["manage_subject","current_semester","application_deadline"]'),
(4, 1, 1, 'APPLICANT', '["applicant_list","short_listed","applicant_selection"]'),
(5, 1, 1, 'CRITERIA', '["manage_criteria","selection_criteria"]'),
(6, 3, 1, 'MANAGE_USERS', '["create_group","group_list","grant_access","create_user","user_list","password_reset"]'),
(7, 3, 1, 'DATA_FROM_SIMS', '["school_list","department_list","programme_list"]'),
(8, 3, 1, 'SETTINGS', '["manage_subject","current_semester"]'),
(9, 3, 1, 'APPLICANT', '["applicant_list","short_listed","applicant_selection"]'),
(10, 3, 1, 'CRITERIA', '["manage_criteria"]');

-- --------------------------------------------------------

--
-- Table structure for table `module_role`
--

CREATE TABLE `module_role` (
  `id` bigint(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `role` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `only_developer` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module_role`
--

INSERT INTO `module_role` (`id`, `module_id`, `section`, `role`, `description`, `only_developer`) VALUES
(1, 1, 'MANAGE_USERS', 'create_group', 'Create Group', 0),
(2, 1, 'MANAGE_USERS', 'group_list', 'Group List', 0),
(3, 1, 'MANAGE_USERS', 'grant_access', 'Grant/Revorke Access', 0),
(4, 1, 'MANAGE_USERS', 'create_user', 'Edit User Account', 0),
(5, 1, 'DATA_FROM_SIMS', 'school_list', 'College/Institutions List', 0),
(6, 1, 'DATA_FROM_SIMS', 'department_list', 'Department List', 0),
(7, 1, 'DATA_FROM_SIMS', 'programme_list', 'Programme List', 0),
(8, 1, 'MANAGE_USERS', 'user_list', 'User Account List', 0),
(9, 1, 'MANAGE_USERS', 'password_reset', 'Reset User Account Password', 0),
(10, 1, 'SETTINGS', 'manage_subject', 'Manage Subject', 0),
(11, 1, 'SETTINGS', 'current_semester', 'Manage Academic/Application Year', 0),
(12, 1, 'SETTINGS', 'application_deadline', 'Application Deadline', 0),
(13, 1, 'APPLICANT', 'applicant_list', 'Applicant List', 0),
(14, 1, 'APPLICANT', 'short_listed', 'Short Listed', 0),
(15, 1, 'CRITERIA', 'manage_criteria', 'Eligible Criteria Setting', 0),
(16, 1, 'APPLICANT', 'applicant_selection', 'Applicant Selection', 0),
(17, 1, 'CRITERIA', 'selection_criteria', 'Selection Criteria Setting', 0);

-- --------------------------------------------------------

--
-- Table structure for table `module_section`
--

CREATE TABLE `module_section` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module_section`
--

INSERT INTO `module_section` (`id`, `module_id`, `name`, `description`) VALUES
(1, 1, 'MANAGE_USERS', 'Manage Users'),
(2, 1, 'DATA_FROM_SIMS', 'Get Data From SIMS'),
(3, 1, 'SETTINGS', 'Settings'),
(4, 1, 'APPLICANT', 'Applicant'),
(5, 1, 'CRITERIA', 'Selection Criteria');

-- --------------------------------------------------------

--
-- Table structure for table `nationality`
--

CREATE TABLE `nationality` (
  `id` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Country` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nationality`
--

INSERT INTO `nationality` (`id`, `Name`, `Country`) VALUES
(1, 'Afghan', 'Afghanistan'),
(2, 'Åland Island', 'Åland Islands'),
(3, 'Albanian', 'Albania'),
(4, 'Algerian', 'Algeria'),
(5, 'American Samoan', 'American Samoa'),
(6, 'Andorran', 'Andorra'),
(7, 'Angolan', 'Angola'),
(8, 'Anguillan', 'Anguilla'),
(9, 'Antarctic', 'Antarctica'),
(10, 'Antiguan or Barbudan', 'Antigua and Barbuda'),
(11, 'Argentine', 'Argentina'),
(12, 'Armenian', 'Armenia'),
(13, 'Aruban', 'Aruba'),
(14, 'Australian', 'Australia'),
(15, 'Austrian', 'Austria'),
(16, 'Azerbaijani, Azeri', 'Azerbaijan'),
(17, 'Bahamian', 'Bahamas'),
(18, 'Bahraini', 'Bahrain'),
(19, 'Bangladeshi', 'Bangladesh'),
(20, 'Barbadian', 'Barbados'),
(21, 'Belarusian', 'Belarus'),
(22, 'Belgian', 'Belgium'),
(23, 'Belizean', 'Belize'),
(24, 'Beninese, Beninois', 'Benin'),
(25, 'Bermudian, Bermudan', 'Bermuda'),
(26, 'Bhutanese', 'Bhutan'),
(27, 'Bolivian', 'Bolivia (Plurinational State of)'),
(28, 'Bonaire', 'Bonaire, Sint Eustatius and Saba'),
(29, 'Bosnian or Herzegovinian', 'Bosnia and Herzegovina'),
(30, 'Motswana, Botswanan', 'Botswana'),
(31, 'Bouvet Island', 'Bouvet Island'),
(32, 'Brazilian', 'Brazil'),
(33, 'BIOT', 'British Indian Ocean Territory'),
(34, 'Bruneian', 'Brunei Darussalam'),
(35, 'Bulgarian', 'Bulgaria'),
(36, 'Burkinabé', 'Burkina Faso'),
(37, 'Burundian', 'Burundi'),
(38, 'Cabo Verdean', 'Cabo Verde'),
(39, 'Cambodian', 'Cambodia'),
(40, 'Cameroonian', 'Cameroon'),
(41, 'Canadian', 'Canada'),
(42, 'Caymanian', 'Cayman Islands'),
(43, 'Central African', 'Central African Republic'),
(44, 'Chadian', 'Chad'),
(45, 'Chilean', 'Chile'),
(46, 'Chinese', 'China'),
(47, 'Christmas Island', 'Christmas Island'),
(48, 'Cocos Island', 'Cocos (Keeling) Islands'),
(49, 'Colombian', 'Colombia'),
(50, 'Comoran, Comorian', 'Comoros'),
(51, 'Congolese', 'Congo (Republic of the)'),
(52, 'Congolese', 'Congo (Democratic Republic of the)'),
(53, 'Cook Island', 'Cook Islands'),
(54, 'Costa Rican', 'Costa Rica'),
(55, 'Ivorian', 'Côte d\'Ivoire'),
(56, 'Croatian', 'Croatia'),
(57, 'Cuban', 'Cuba'),
(58, 'Curaçaoan', 'Curaçao'),
(59, 'Cypriot', 'Cyprus'),
(60, 'Czech', 'Czech Republic'),
(61, 'Danish', 'Denmark'),
(62, 'Djiboutian', 'Djibouti'),
(63, 'Dominican', 'Dominica'),
(64, 'Dominican', 'Dominican Republic'),
(65, 'Ecuadorian', 'Ecuador'),
(66, 'Egyptian', 'Egypt'),
(67, 'Salvadoran', 'El Salvador'),
(68, 'Equatorial Guinean, Equatoguinean', 'Equatorial Guinea'),
(69, 'Eritrean', 'Eritrea'),
(70, 'Estonian', 'Estonia'),
(71, 'Ethiopian', 'Ethiopia'),
(72, 'Falkland Island', 'Falkland Islands (Malvinas)'),
(73, 'Faroese', 'Faroe Islands'),
(74, 'Fijian', 'Fiji'),
(75, 'Finnish', 'Finland'),
(76, 'French', 'France'),
(77, 'French Guianese', 'French Guiana'),
(78, 'French Polynesian', 'French Polynesia'),
(79, 'French Southern Territories', 'French Southern Territories'),
(80, 'Gabonese', 'Gabon'),
(81, 'Gambian', 'Gambia'),
(82, 'Georgian', 'Georgia'),
(83, 'German', 'Germany'),
(84, 'Ghanaian', 'Ghana'),
(85, 'Gibraltar', 'Gibraltar'),
(86, 'Greek, Hellenic', 'Greece'),
(87, 'Greenlandic', 'Greenland'),
(88, 'Grenadian', 'Grenada'),
(89, 'Guadeloupe', 'Guadeloupe'),
(90, 'Guamanian, Guambat', 'Guam'),
(91, 'Guatemalan', 'Guatemala'),
(92, 'Channel Island', 'Guernsey'),
(93, 'Guinean', 'Guinea'),
(94, 'Bissau-Guinean', 'Guinea-Bissau'),
(95, 'Guyanese', 'Guyana'),
(96, 'Haitian', 'Haiti'),
(97, 'Heard Island or McDonald Islands', 'Heard Island and McDonald Islands'),
(98, 'Vatican', 'Vatican City State'),
(99, 'Honduran', 'Honduras'),
(100, 'Hong Kong, Hong Kongese', 'Hong Kong'),
(101, 'Hungarian, Magyar', 'Hungary'),
(102, 'Icelandic', 'Iceland'),
(103, 'Indian', 'India'),
(104, 'Indonesian', 'Indonesia'),
(105, 'Iranian, Persian', 'Iran'),
(106, 'Iraqi', 'Iraq'),
(107, 'Irish', 'Ireland'),
(108, 'Manx', 'Isle of Man'),
(109, 'Israeli', 'Israel'),
(110, 'Italian', 'Italy'),
(111, 'Jamaican', 'Jamaica'),
(112, 'Japanese', 'Japan'),
(113, 'Channel Island', 'Jersey'),
(114, 'Jordanian', 'Jordan'),
(115, 'Kazakhstani, Kazakh', 'Kazakhstan'),
(116, 'Kenyan', 'Kenya'),
(117, 'I-Kiribati', 'Kiribati'),
(118, 'North Korean', 'Korea (Democratic People\'s Republic of)'),
(119, 'South Korean', 'Korea (Republic of)'),
(120, 'Kuwaiti', 'Kuwait'),
(121, 'Kyrgyzstani, Kyrgyz, Kirgiz, Kirghiz', 'Kyrgyzstan'),
(122, 'Lao, Laotian', 'Lao People\'s Democratic Republic'),
(123, 'Latvian', 'Latvia'),
(124, 'Lebanese', 'Lebanon'),
(125, 'Basotho', 'Lesotho'),
(126, 'Liberian', 'Liberia'),
(127, 'Libyan', 'Libya'),
(128, 'Liechtenstein', 'Liechtenstein'),
(129, 'Lithuanian', 'Lithuania'),
(130, 'Luxembourg, Luxembourgish', 'Luxembourg'),
(131, 'Macanese, Chinese', 'Macao'),
(132, 'Macedonian', 'Macedonia (the former Yugoslav Republic of)'),
(133, 'Malagasy', 'Madagascar'),
(134, 'Malawian', 'Malawi'),
(135, 'Malaysian', 'Malaysia'),
(136, 'Maldivian', 'Maldives'),
(137, 'Malian, Malinese', 'Mali'),
(138, 'Maltese', 'Malta'),
(139, 'Marshallese', 'Marshall Islands'),
(140, 'Martiniquais, Martinican', 'Martinique'),
(141, 'Mauritanian', 'Mauritania'),
(142, 'Mauritian', 'Mauritius'),
(143, 'Mahoran', 'Mayotte'),
(144, 'Mexican', 'Mexico'),
(145, 'Micronesian', 'Micronesia (Federated States of)'),
(146, 'Moldovan', 'Moldova (Republic of)'),
(147, 'Monégasque, Monacan', 'Monaco'),
(148, 'Mongolian', 'Mongolia'),
(149, 'Montenegrin', 'Montenegro'),
(150, 'Montserratian', 'Montserrat'),
(151, 'Moroccan', 'Morocco'),
(152, 'Mozambican', 'Mozambique'),
(153, 'Burmese', 'Myanmar'),
(154, 'Namibian', 'Namibia'),
(155, 'Nauruan', 'Nauru'),
(156, 'Nepali, Nepalese', 'Nepal'),
(157, 'Dutch, Netherlandic', 'Netherlands'),
(158, 'New Caledonian', 'New Caledonia'),
(159, 'New Zealand, NZ', 'New Zealand'),
(160, 'Nicaraguan', 'Nicaragua'),
(161, 'Nigerien', 'Niger'),
(162, 'Nigerian', 'Nigeria'),
(163, 'Niuean', 'Niue'),
(164, 'Norfolk Island', 'Norfolk Island'),
(165, 'Northern Marianan', 'Northern Mariana Islands'),
(166, 'Norwegian', 'Norway'),
(167, 'Omani', 'Oman'),
(168, 'Pakistani', 'Pakistan'),
(169, 'Palauan', 'Palau'),
(170, 'Palestinian', 'Palestine, State of'),
(171, 'Panamanian', 'Panama'),
(172, 'Papua New Guinean, Papuan', 'Papua New Guinea'),
(173, 'Paraguayan', 'Paraguay'),
(174, 'Peruvian', 'Peru'),
(175, 'Philippine, Filipino', 'Philippines'),
(176, 'Pitcairn Island', 'Pitcairn'),
(177, 'Polish', 'Poland'),
(178, 'Portuguese', 'Portugal'),
(179, 'Puerto Rican', 'Puerto Rico'),
(180, 'Qatari', 'Qatar'),
(181, 'Réunionese, Réunionnais', 'Réunion'),
(182, 'Romanian', 'Romania'),
(183, 'Russian', 'Russian Federation'),
(184, 'Rwandan', 'Rwanda'),
(185, 'Barthélemois', 'Saint Barthélemy'),
(186, 'Saint Helenian', 'Saint Helena, Ascension and Tristan da Cunha'),
(187, 'Kittitian or Nevisian', 'Saint Kitts and Nevis'),
(188, 'Saint Lucian', 'Saint Lucia'),
(189, 'Saint-Martinoise', 'Saint Martin (French part)'),
(190, 'Saint-Pierrais or Miquelonnais', 'Saint Pierre and Miquelon'),
(191, 'Saint Vincentian, Vincentian', 'Saint Vincent and the Grenadines'),
(192, 'Samoan', 'Samoa'),
(193, 'Sammarinese', 'San Marino'),
(194, 'São Toméan', 'Sao Tome and Principe'),
(195, 'Saudi, Saudi Arabian', 'Saudi Arabia'),
(196, 'Senegalese', 'Senegal'),
(197, 'Serbian', 'Serbia'),
(198, 'Seychellois', 'Seychelles'),
(199, 'Sierra Leonean', 'Sierra Leone'),
(200, 'Singaporean', 'Singapore'),
(201, 'Sint Maarten', 'Sint Maarten (Dutch part)'),
(202, 'Slovak', 'Slovakia'),
(203, 'Slovenian, Slovene', 'Slovenia'),
(204, 'Solomon Island', 'Solomon Islands'),
(205, 'Somali, Somalian', 'Somalia'),
(206, 'South African', 'South Africa'),
(207, 'South Georgia or South Sandwich Islands', 'South Georgia and the South Sandwich Islands'),
(208, 'South Sudanese', 'South Sudan'),
(209, 'Spanish', 'Spain'),
(210, 'Sri Lankan', 'Sri Lanka'),
(211, 'Sudanese', 'Sudan'),
(212, 'Surinamese', 'Suriname'),
(213, 'Svalbard', 'Svalbard and Jan Mayen'),
(214, 'Swazi', 'Swaziland'),
(215, 'Swedish', 'Sweden'),
(216, 'Swiss', 'Switzerland'),
(217, 'Syrian', 'Syrian Arab Republic'),
(218, 'Chinese, Taiwanese', 'Taiwan, Province of China'),
(219, 'Tajikistani', 'Tajikistan'),
(220, 'Tanzanian', 'Tanzania, United Republic of'),
(221, 'Thai', 'Thailand'),
(222, 'Timorese', 'Timor-Leste'),
(223, 'Togolese', 'Togo'),
(224, 'Tokelauan', 'Tokelau'),
(225, 'Tongan', 'Tonga'),
(226, 'Trinidadian or Tobagonian', 'Trinidad and Tobago'),
(227, 'Tunisian', 'Tunisia'),
(228, 'Turkish', 'Turkey'),
(229, 'Turkmen', 'Turkmenistan'),
(230, 'Turks and Caicos Island', 'Turks and Caicos Islands'),
(231, 'Tuvaluan', 'Tuvalu'),
(232, 'Ugandan', 'Uganda'),
(233, 'Ukrainian', 'Ukraine'),
(234, 'Emirati, Emirian, Emiri', 'United Arab Emirates'),
(235, 'British, UK', 'United Kingdom of Great Britain and Northern Ireland'),
(236, 'American', 'United States Minor Outlying Islands'),
(237, 'American', 'United States of America'),
(238, 'Uruguayan', 'Uruguay'),
(239, 'Uzbekistani, Uzbek', 'Uzbekistan'),
(240, 'Ni-Vanuatu, Vanuatuan', 'Vanuatu'),
(241, 'Venezuelan', 'Venezuela (Bolivarian Republic of)'),
(242, 'Vietnamese', 'Vietnam'),
(243, 'British Virgin Island', 'Virgin Islands (British)'),
(244, 'U.S. Virgin Island', 'Virgin Islands (U.S.)'),
(245, 'Wallis and Futuna, Wallisian or Futunan', 'Wallis and Futuna'),
(246, 'Sahrawi, Sahrawian, Sahraouian', 'Western Sahara'),
(247, 'Yemeni', 'Yemen'),
(248, 'Zambian', 'Zambia'),
(249, 'Zimbabwean', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `necta_check_subject`
--

CREATE TABLE `necta_check_subject` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `status` int(11) DEFAULT '0',
  `response` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `necta_tmp_result`
--

CREATE TABLE `necta_tmp_result` (
  `id` bigint(20) NOT NULL,
  `category` int(11) NOT NULL,
  `authority_id` int(11) NOT NULL,
  `action` varchar(10) NOT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `applicant_id` bigint(20) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notify_tmp`
--

CREATE TABLE `notify_tmp` (
  `id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `programme`
--

CREATE TABLE `programme` (
  `id` int(11) NOT NULL,
  `Code` varchar(45) DEFAULT NULL,
  `Shortname` varchar(255) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Departmentid` varchar(45) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `active` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recommandation_area`
--

CREATE TABLE `recommandation_area` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recommandation_area`
--

INSERT INTO `recommandation_area` (`id`, `name`) VALUES
(1, 'Intellectual Ability'),
(2, 'Maturity'),
(3, 'Motivation'),
(4, 'Discipline'),
(5, 'Diligence'),
(6, 'Ability to work with others');

-- --------------------------------------------------------

--
-- Table structure for table `run_eligibility`
--

CREATE TABLE `run_eligibility` (
  `id` int(11) NOT NULL,
  `ProgrammeCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `run_selection`
--

CREATE TABLE `run_selection` (
  `id` int(11) NOT NULL,
  `ProgrammeCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `secondary_category`
--

CREATE TABLE `secondary_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `secondary_category`
--

INSERT INTO `secondary_category` (`id`, `name`) VALUES
(1, 'O-Level'),
(2, 'A-Level');

-- --------------------------------------------------------

--
-- Table structure for table `secondary_subject`
--

CREATE TABLE `secondary_subject` (
  `id` int(11) NOT NULL,
  `shortname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) DEFAULT '1',
  `code` varchar(50) DEFAULT NULL,
  `category` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `Name`) VALUES
(1, 'Semester I'),
(2, 'Semester II');

-- --------------------------------------------------------

--
-- Table structure for table `technician_type`
--

CREATE TABLE `technician_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `technician_type`
--

INSERT INTO `technician_type` (`id`, `name`) VALUES
(1, 'Assistant Medical Officer (AMO)'),
(2, 'Radiographer Assistant'),
(3, 'Laboratory Assistant'),
(4, 'Pharmaceutical Assistant'),
(5, 'Certificate in lower limb Prosthetics or Orthotics'),
(6, 'Health Assistant');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
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
  `applicant_id` bigint(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `remember_code`, `created_on`, `last_login`, `active`, `firstname`, `lastname`, `phone`, `profile`, `title`, `campus_id`, `forgotten_password_time`, `access_area`, `access_area_id`, `sims_map`, `applicant_id`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$08$w1EUWbWyMBO..yPk/Rfelur9tb0bixj7NgvPEw5FpJjp0nYAxDWY6', NULL, 'miltoneurassa@yahoo.com', NULL, NULL, NULL, 1498301400, 1500638265, 1, 'Miltone', 'Urassa', '', 'default.jpg', NULL, 1, NULL, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_login_attempts`
--

CREATE TABLE `users_login_attempts` (
  `id` bigint(20) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_history`
--

CREATE TABLE `user_login_history` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `browser` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login_history`
--

INSERT INTO `user_login_history` (`id`, `user_id`, `login_time`, `ip`, `browser`) VALUES
(1, 1, '2017-07-21 07:21:36', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133'),
(2, 2, '2017-07-21 11:22:29', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133'),
(3, 2, '2017-07-21 11:25:37', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133'),
(4, 1, '2017-07-21 11:38:43', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133'),
(5, 1, '2017-07-21 11:51:48', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133'),
(6, 1, '2017-07-21 11:57:45', '127.0.0.1', 'Platform : Linux -- Browser : Chrome -- Version : 57.0.2987.133');

-- --------------------------------------------------------

--
-- Table structure for table `user_title`
--

CREATE TABLE `user_title` (
  `id` int(11) NOT NULL,
  `title` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_title`
--

INSERT INTO `user_title` (`id`, `title`) VALUES
(1, 'Mr'),
(2, 'Mrs'),
(3, 'Dr'),
(4, 'Prof'),
(5, 'Ms'),
(6, 'Rev'),
(7, 'Eng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_attachment`
--
ALTER TABLE `application_attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_criteria_setting`
--
ALTER TABLE `application_criteria_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_deadline`
--
ALTER TABLE `application_deadline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_diploma_nacteresult`
--
ALTER TABLE `application_diploma_nacteresult`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_education_authority`
--
ALTER TABLE `application_education_authority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_education_subject`
--
ALTER TABLE `application_education_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_elegibility`
--
ALTER TABLE `application_elegibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_employer`
--
ALTER TABLE `application_employer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_experience`
--
ALTER TABLE `application_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `application_nextkin_info`
--
ALTER TABLE `application_nextkin_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_payment`
--
ALTER TABLE `application_payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt` (`receipt`);

--
-- Indexes for table `application_programme_choice`
--
ALTER TABLE `application_programme_choice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_referee`
--
ALTER TABLE `application_referee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_referee_recommendation`
--
ALTER TABLE `application_referee_recommendation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_sponsor`
--
ALTER TABLE `application_sponsor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_steps`
--
ALTER TABLE `application_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayear`
--
ALTER TABLE `ayear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campus`
--
ALTER TABLE `campus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`);

--
-- Indexes for table `college_schools`
--
ALTER TABLE `college_schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disability`
--
ALTER TABLE `disability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_notification`
--
ALTER TABLE `log_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maritalstatus`
--
ALTER TABLE `maritalstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_sent`
--
ALTER TABLE `message_sent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_group_role`
--
ALTER TABLE `module_group_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_group_role_1_idx` (`group_id`),
  ADD KEY `fk_module_group_role_2_idx` (`module_id`);

--
-- Indexes for table `module_role`
--
ALTER TABLE `module_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_role_1_idx` (`module_id`);

--
-- Indexes for table `module_section`
--
ALTER TABLE `module_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_section_1_idx` (`module_id`);

--
-- Indexes for table `nationality`
--
ALTER TABLE `nationality`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `necta_check_subject`
--
ALTER TABLE `necta_check_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `necta_tmp_result`
--
ALTER TABLE `necta_tmp_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notify_tmp`
--
ALTER TABLE `notify_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programme`
--
ALTER TABLE `programme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`),
  ADD UNIQUE KEY `Code_UNIQUE` (`Code`);

--
-- Indexes for table `recommandation_area`
--
ALTER TABLE `recommandation_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `run_eligibility`
--
ALTER TABLE `run_eligibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `run_selection`
--
ALTER TABLE `run_selection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `secondary_category`
--
ALTER TABLE `secondary_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `secondary_subject`
--
ALTER TABLE `secondary_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`);

--
-- Indexes for table `technician_type`
--
ALTER TABLE `technician_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login_attempts`
--
ALTER TABLE `users_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_login_history`
--
ALTER TABLE `user_login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_title`
--
ALTER TABLE `user_title`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_attachment`
--
ALTER TABLE `application_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_criteria_setting`
--
ALTER TABLE `application_criteria_setting`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_deadline`
--
ALTER TABLE `application_deadline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `application_diploma_nacteresult`
--
ALTER TABLE `application_diploma_nacteresult`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_education_authority`
--
ALTER TABLE `application_education_authority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_education_subject`
--
ALTER TABLE `application_education_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_elegibility`
--
ALTER TABLE `application_elegibility`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_employer`
--
ALTER TABLE `application_employer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_experience`
--
ALTER TABLE `application_experience`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_nextkin_info`
--
ALTER TABLE `application_nextkin_info`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_payment`
--
ALTER TABLE `application_payment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_programme_choice`
--
ALTER TABLE `application_programme_choice`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_referee`
--
ALTER TABLE `application_referee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_referee_recommendation`
--
ALTER TABLE `application_referee_recommendation`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_sponsor`
--
ALTER TABLE `application_sponsor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `application_steps`
--
ALTER TABLE `application_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayear`
--
ALTER TABLE `ayear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `campus`
--
ALTER TABLE `campus`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `college`
--
ALTER TABLE `college`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `college_schools`
--
ALTER TABLE `college_schools`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disability`
--
ALTER TABLE `disability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `log_notification`
--
ALTER TABLE `log_notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `maritalstatus`
--
ALTER TABLE `maritalstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `message_sent`
--
ALTER TABLE `message_sent`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `module_group_role`
--
ALTER TABLE `module_group_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `module_role`
--
ALTER TABLE `module_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `module_section`
--
ALTER TABLE `module_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `nationality`
--
ALTER TABLE `nationality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `necta_check_subject`
--
ALTER TABLE `necta_check_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `necta_tmp_result`
--
ALTER TABLE `necta_tmp_result`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `notify_tmp`
--
ALTER TABLE `notify_tmp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `programme`
--
ALTER TABLE `programme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recommandation_area`
--
ALTER TABLE `recommandation_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `run_eligibility`
--
ALTER TABLE `run_eligibility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `run_selection`
--
ALTER TABLE `run_selection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `secondary_category`
--
ALTER TABLE `secondary_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `secondary_subject`
--
ALTER TABLE `secondary_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `technician_type`
--
ALTER TABLE `technician_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users_login_attempts`
--
ALTER TABLE `users_login_attempts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_login_history`
--
ALTER TABLE `user_login_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
