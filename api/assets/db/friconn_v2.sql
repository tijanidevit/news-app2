-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2021 at 11:21 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `friconn_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer_votes`
--

CREATE TABLE IF NOT EXISTS `answer_votes` (
`id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `points_earned` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer_votes`
--

INSERT INTO `answer_votes` (`id`, `answer_id`, `friconn_id`, `points_earned`, `created_at`, `updated_at`) VALUES
(1, 1, 88837618, 3, '2021-03-13 17:23:38', '2021-03-13 17:23:38'),
(2, 1, 88837618, 3, '2021-03-13 17:23:43', '2021-03-13 17:23:43'),
(3, 1, 88837618, 3, '2021-03-13 17:23:47', '2021-03-13 17:23:47'),
(4, 1, 88837618, 3, '2021-03-13 17:26:54', '2021-03-13 17:26:54'),
(5, 1, 88837618, 3, '2021-03-13 17:26:57', '2021-03-13 17:26:57'),
(6, 1, 88837618, 3, '2021-03-13 17:32:33', '2021-03-13 17:32:33'),
(7, 2, 88837618, 3, '2021-03-13 17:37:08', '2021-03-13 17:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `conversion_rates`
--

CREATE TABLE IF NOT EXISTS `conversion_rates` (
`id` int(11) NOT NULL,
  `naira_per_point` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversion_rates`
--

INSERT INTO `conversion_rates` (`id`, `naira_per_point`, `created_at`, `updated_at`) VALUES
(1, '10', '2021-03-05 01:30:02', '2021-03-05 01:30:02'),
(2, '5', '2021-03-05 01:58:23', '2021-03-05 01:58:23');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
`id` int(11) NOT NULL,
  `course` varchar(120) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`, `course_code`, `created_at`, `updated_at`) VALUES
(4, 'Introduction to Food Technology', 'FST101', '2021-03-12 06:53:42', '2021-03-12 06:53:42'),
(5, 'Introduction to computer', 'COM101', '2021-03-13 16:51:45', '2021-03-13 16:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
`id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `department` varchar(120) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `faculty_id`, `department`) VALUES
(1, 1, 'Computer Science'),
(2, 1, 'Food Technology'),
(3, 1, 'Science and Laboratory Technology');

-- --------------------------------------------------------

--
-- Table structure for table `department_courses`
--

CREATE TABLE IF NOT EXISTS `department_courses` (
`id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department_courses`
--

INSERT INTO `department_courses` (`id`, `department_id`, `course_id`, `created_at`) VALUES
(1, 2, 5, '2021-03-13 18:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `eduministers_profile`
--

CREATE TABLE IF NOT EXISTS `eduministers_profile` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `employment_status_id` smallint(2) NOT NULL,
  `state_id` smallint(4) NOT NULL,
  `gender_id` tinyint(1) NOT NULL,
  `linked_in_url` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `points` decimal(10,0) NOT NULL DEFAULT '0',
  `balance` decimal(10,0) NOT NULL,
  `bio` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eduministers_profile`
--

INSERT INTO `eduministers_profile` (`id`, `friconn_id`, `employment_status_id`, `state_id`, `gender_id`, `linked_in_url`, `dob`, `phone`, `points`, `balance`, `bio`, `created_at`, `updated_at`) VALUES
(1, 69494519, 0, 0, 0, '', '0000-00-00', '', '0', '0', '', '2021-03-03 10:06:15', '2021-03-03 10:06:15'),
(2, 84365720, 1, 1, 1, 'here.com', '0000-00-00', '69494519175272', '230000000', '0', 'I love coding', '2021-03-03 10:26:25', '2021-03-04 21:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `eduminister_courses`
--

CREATE TABLE IF NOT EXISTS `eduminister_courses` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eduminister_courses`
--

INSERT INTO `eduminister_courses` (`id`, `friconn_id`, `course_id`, `created_at`, `updated_at`) VALUES
(5, 84365720, 1, '2021-03-12 22:55:00', '2021-03-12 22:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `eduminister_payouts`
--

CREATE TABLE IF NOT EXISTS `eduminister_payouts` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `conversion_rate_id` int(11) NOT NULL,
  `amount` decimal(20,0) NOT NULL,
  `deducted_points` decimal(10,0) NOT NULL,
  `points_balance` decimal(11,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eduminister_payouts`
--

INSERT INTO `eduminister_payouts` (`id`, `friconn_id`, `conversion_rate_id`, `amount`, `deducted_points`, `points_balance`, `created_at`, `updated_at`) VALUES
(1, 84365720, 1, '1201', '120', '2000', '2021-03-04 23:58:14', '2021-03-04 23:58:14'),
(2, 69494519, 1, '1000', '20', '1000', '2021-03-04 23:58:14', '2021-03-05 01:37:40'),
(3, 84365720, 2, '1201', '120', '2000', '2021-03-05 00:52:19', '2021-03-05 01:59:08'),
(4, 84365720, 1, '1201', '120', '2000', '2021-03-05 01:04:28', '2021-03-05 01:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `eduminister_qualifications`
--

CREATE TABLE IF NOT EXISTS `eduminister_qualifications` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `qualification_id` smallint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employment_statuses`
--

CREATE TABLE IF NOT EXISTS `employment_statuses` (
`id` tinyint(4) NOT NULL,
  `employment_status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employment_statuses`
--

INSERT INTO `employment_statuses` (`id`, `employment_status`, `created_at`, `updated_at`) VALUES
(1, 'Self-employed', '2021-03-04 21:18:13', '2021-03-04 21:18:13'),
(2, 'Employed', '2021-03-04 21:18:13', '2021-03-04 21:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE IF NOT EXISTS `faculties` (
`id` int(11) NOT NULL,
  `faculty` varchar(120) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `faculty`) VALUES
(1, 'Pure and Applied Science'),
(2, 'Management');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE IF NOT EXISTS `genders` (
`id` tinyint(1) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'Male', '2021-03-04 21:15:34', '2021-03-04 21:15:34'),
(2, 'Female', '2021-03-04 21:15:34', '2021-03-04 21:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE IF NOT EXISTS `institutions` (
`id` int(11) NOT NULL,
  `institution` varchar(120) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `institution`) VALUES
(1, 'The Federal Polytechnic Ilaro');

-- --------------------------------------------------------

--
-- Table structure for table `learners_profile`
--

CREATE TABLE IF NOT EXISTS `learners_profile` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `points` decimal(10,0) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `learners_profile`
--

INSERT INTO `learners_profile` (`id`, `friconn_id`, `institution_id`, `department_id`, `dob`, `phone`, `points`, `created_at`, `updated_at`) VALUES
(1, 2298544, 0, 0, '2021-03-17', '08149617083', '1', '2021-03-02 11:48:06', '2021-03-26 11:47:34'),
(2, 9413695, 0, 0, '0000-00-00', '', '0', '2021-03-02 22:50:08', '2021-03-02 22:50:08'),
(3, 4251356, 0, 0, '0000-00-00', '', '0', '2021-03-02 22:51:39', '2021-03-02 22:51:39'),
(4, 2657367, 0, 0, '0000-00-00', '', '0', '2021-03-02 22:52:58', '2021-03-02 22:52:58'),
(5, 6149188, 0, 0, '0000-00-00', '', '0', '2021-03-02 22:58:57', '2021-03-02 22:58:57'),
(6, 6783699, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:03:23', '2021-03-02 23:03:23'),
(7, 75833310, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:04:42', '2021-03-02 23:04:42'),
(8, 45154811, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:04:56', '2021-03-02 23:04:56'),
(9, 14392112, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:05:05', '2021-03-02 23:05:05'),
(10, 71546313, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:24:30', '2021-03-02 23:24:30'),
(11, 45813114, 0, 0, '0000-00-00', '', '0', '2021-03-02 23:28:17', '2021-03-02 23:28:17'),
(12, 92974915, 0, 0, '0000-00-00', '', '0', '2021-03-03 05:43:26', '2021-03-03 05:43:26'),
(13, 51982416, 0, 0, '0000-00-00', '', '0', '2021-03-03 05:43:45', '2021-03-03 05:43:45'),
(14, 73256217, 0, 0, '0000-00-00', '', '0', '2021-03-03 05:51:52', '2021-03-03 05:51:52'),
(15, 88837618, 1, 1, '0000-00-00', '69494519175272', '230000000', '2021-03-03 07:23:57', '2021-03-11 17:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `learner_courses`
--

CREATE TABLE IF NOT EXISTS `learner_courses` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `learner_courses`
--

INSERT INTO `learner_courses` (`id`, `friconn_id`, `course_id`, `created_at`, `updated_at`) VALUES
(3, 88837618, 1, '2021-03-12 22:03:12', '2021-03-12 22:03:12'),
(4, 88837618, 2, '2021-03-12 22:03:20', '2021-03-12 22:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `password_requests`
--

CREATE TABLE IF NOT EXISTS `password_requests` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `token` bigint(20) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE IF NOT EXISTS `qualifications` (
`id` smallint(4) NOT NULL,
  `qualification` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject` varchar(120) NOT NULL,
  `question` text NOT NULL,
  `answered` int(11) NOT NULL DEFAULT '0',
  `points_charged` decimal(10,0) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `friconn_id`, `course_id`, `subject`, `question`, `answered`, `points_charged`, `active_status`, `created_at`, `updated_at`) VALUES
(1, 88837618, 1, 'FST101', 'I don''t understand at all', 0, '3', 1, '2021-03-13 06:36:45', '2021-03-13 06:36:45'),
(2, 88837618, 2, 'Boring lectures', 'This lecture is too boring. How can I help myself', 0, '0', 1, '2021-03-13 06:38:08', '2021-03-13 16:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `question_answers`
--

CREATE TABLE IF NOT EXISTS `question_answers` (
`id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `is_answer` int(11) NOT NULL DEFAULT '0',
  `points_earned` decimal(10,0) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_answers`
--

INSERT INTO `question_answers` (`id`, `question_id`, `friconn_id`, `answer`, `is_answer`, `points_earned`, `created_at`, `updated_at`) VALUES
(1, 1, 88837618, 'I don''t understand at all', 0, '0', '2021-03-13 08:20:09', '2021-03-13 08:20:09'),
(2, 1, 88837618, 'Well, you need to read a lot', 0, '0', '2021-03-13 08:20:36', '2021-03-13 08:20:36'),
(3, 1, 88837618, 'Well, you need to read a lot', 0, '0', '2021-03-13 08:22:44', '2021-03-13 08:22:44'),
(4, 1, 88837618, 'Well, you need to read a lot', 0, '0', '2021-03-13 08:27:37', '2021-03-13 08:27:37'),
(5, 1, 88837618, 'Well, you need to read a lot', 0, '0', '2021-03-13 08:27:54', '2021-03-13 08:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` smallint(3) NOT NULL,
  `role` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2021-03-02 11:24:14', '2021-03-02 11:24:14'),
(2, 'Moderator', '2021-03-02 11:24:50', '2021-03-02 11:24:50'),
(3, 'Learner', '2021-03-02 11:24:50', '2021-03-02 11:24:50'),
(4, 'Eduminister', '2021-03-03 10:25:53', '2021-03-03 10:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
`id` smallint(4) NOT NULL,
  `state` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state`, `created_at`, `updated_at`) VALUES
(1, 'Abia', '2021-03-04 21:11:11', '2021-03-04 21:11:11'),
(2, 'Adamawa', '2021-03-04 21:11:11', '2021-03-04 21:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `role_id` smallint(3) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `other_names` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` text NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `profile_image` varchar(40) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `password_token` varchar(30) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `friconn_id`, `role_id`, `last_name`, `other_names`, `email`, `password`, `verification_code`, `profile_image`, `approved`, `blocked`, `password_token`, `created_at`, `updated_at`) VALUES
(7, 2657367, 3, 'Obadimu', 'Ismail Idowu', 't@sw.com', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '409686', 'image', 0, 0, '', '2021-03-02 22:52:58', '2021-03-02 22:52:58'),
(8, 6149188, 3, 'Obadimu', 'Ismail Idowu', 't@skw.com', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '846211', 'image', 0, 0, '', '2021-03-02 22:58:56', '2021-03-02 22:58:56'),
(9, 6783699, 3, 'Obadimu', 'Ismail Idowu', 't@eskw.com', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '848555', '', 0, 0, '', '2021-03-02 23:03:21', '2021-03-02 23:03:21'),
(10, 75833310, 3, 'Obadimu', 'Ismail Idowu', 't@eeskw.com', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '557128', '', 0, 0, '', '2021-03-02 23:04:42', '2021-03-02 23:04:42'),
(11, 45154811, 3, 'Obadimu', 'Ismail Idowu', 't@eeskw.comw', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '463529', '', 0, 0, '', '2021-03-02 23:04:56', '2021-03-02 23:04:56'),
(12, 14392112, 3, 'Obadimu', 'Ismail Idowu', 't@eeskw.comwe', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '337242', '', 0, 0, '', '2021-03-02 23:05:04', '2021-03-02 23:05:04'),
(13, 71546313, 3, 'Obadimu1', 'Ismail Idowu', 't@s', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '254723', '', 0, 0, '', '2021-03-02 23:24:29', '2021-03-02 23:24:29'),
(14, 45813114, 3, 'Obadimu', 'Ismail Idowu', 't@sd', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '845251', '', 0, 0, '', '2021-03-02 23:28:17', '2021-03-02 23:28:17'),
(15, 92974915, 3, 'Obadimu', 'Ismail Idowu', 't@sdd', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '330316', '', 0, 0, '', '2021-03-03 05:43:25', '2021-03-03 05:43:25'),
(16, 51982416, 3, 'Obadimu', 'Ismail Idowu', 't@sddw', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '896526', '', 0, 0, '', '2021-03-03 05:43:45', '2021-03-03 05:43:45'),
(17, 73256217, 3, 'Obadimu', 'Ismail Idowu', 't@sddwe', 'T2NubnlKSlVLdEsvZ2luc2FUMUVhQT09', '558413', '', 1, 0, '', '2021-03-03 05:51:51', '2021-03-03 07:18:15'),
(18, 88837618, 3, 'Ade', 'ff', 'dd@ee', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', '495714', '', 0, 0, '', '2021-03-03 07:23:57', '2021-03-11 17:42:00'),
(19, 69494519, 4, 'AyoadeY', 'Lawal', 'thenewxpat@gmail.com', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', '820800', '', 1, 0, '', '2021-03-03 10:06:15', '2021-03-05 00:55:52'),
(20, 84365720, 4, 'Ayoade', 'Lawal', 'thenewxpat@gmail.com1', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', '', '', 1, 0, '', '2021-03-03 10:26:25', '2021-03-03 23:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_points`
--

CREATE TABLE IF NOT EXISTS `user_points` (
`id` int(11) NOT NULL,
  `friconn_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `point` decimal(10,0) NOT NULL,
  `type` varchar(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_id` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer_votes`
--
ALTER TABLE `answer_votes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversion_rates`
--
ALTER TABLE `conversion_rates`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_courses`
--
ALTER TABLE `department_courses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eduministers_profile`
--
ALTER TABLE `eduministers_profile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eduminister_courses`
--
ALTER TABLE `eduminister_courses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eduminister_payouts`
--
ALTER TABLE `eduminister_payouts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eduminister_qualifications`
--
ALTER TABLE `eduminister_qualifications`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learners_profile`
--
ALTER TABLE `learners_profile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learner_courses`
--
ALTER TABLE `learner_courses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_requests`
--
ALTER TABLE `password_requests`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qualifications`
--
ALTER TABLE `qualifications`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_answers`
--
ALTER TABLE `question_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_points`
--
ALTER TABLE `user_points`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer_votes`
--
ALTER TABLE `answer_votes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `conversion_rates`
--
ALTER TABLE `conversion_rates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `department_courses`
--
ALTER TABLE `department_courses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `eduministers_profile`
--
ALTER TABLE `eduministers_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `eduminister_courses`
--
ALTER TABLE `eduminister_courses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `eduminister_payouts`
--
ALTER TABLE `eduminister_payouts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `eduminister_qualifications`
--
ALTER TABLE `eduminister_qualifications`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `learners_profile`
--
ALTER TABLE `learners_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `learner_courses`
--
ALTER TABLE `learner_courses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `password_requests`
--
ALTER TABLE `password_requests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qualifications`
--
ALTER TABLE `qualifications`
MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `question_answers`
--
ALTER TABLE `question_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` smallint(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user_points`
--
ALTER TABLE `user_points`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
