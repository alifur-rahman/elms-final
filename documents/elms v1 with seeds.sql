-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2026 at 12:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elms`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `nid` varchar(50) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `current_salary` decimal(12,2) DEFAULT 0.00,
  `expected_salary` decimal(12,2) DEFAULT 0.00,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `applied_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `job_id`, `code`, `first_name`, `last_name`, `email`, `phone`, `nid`, `blood_group`, `dob`, `gender`, `present_address`, `permanent_address`, `current_salary`, `expected_salary`, `status_id`, `applied_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'CND-001', 'Rahul', 'Sikder', 'rahul.sikder11@email.com', '+8801771635154', NULL, NULL, NULL, NULL, NULL, NULL, 36152.00, 89308.00, 5, '2026-05-19 13:51:54', '2026-07-09 11:51:54', '2026-07-22 10:00:03'),
(2, 1, 'CND-002', 'Priya', 'Malaker', 'priya.malaker21@email.com', '+8801733800210', NULL, NULL, NULL, NULL, NULL, NULL, 28720.00, 77434.00, 5, '2026-06-18 13:51:54', '2026-07-09 11:51:54', '2026-07-16 11:02:44'),
(3, 1, 'CND-003', 'Arif', 'Bose', 'arif.bose79@email.com', '+8801776916423', NULL, NULL, NULL, NULL, NULL, NULL, 135028.00, 145092.00, 8, '2026-05-13 13:51:54', '2026-07-09 11:51:54', '2026-07-16 10:54:02'),
(4, 1, 'CND-004', 'Sadia', 'Haque', 'sadia.haque15@email.com', '+8801761140266', NULL, NULL, NULL, NULL, NULL, NULL, 72296.00, 105894.00, 5, '2026-05-17 13:51:54', '2026-07-09 11:51:54', '2026-07-23 09:12:39'),
(5, 2, 'CND-005', 'Tanvir', 'Chakraborty', 'tanvir.chakraborty34@email.com', '+8801799225811', NULL, NULL, NULL, NULL, NULL, NULL, 143776.00, 190524.00, 4, '2026-06-16 13:51:54', '2026-07-09 11:51:54', '2026-07-19 06:23:41'),
(6, 2, 'CND-006', 'Nusrat', 'Nath', 'nusrat.nath57@email.com', '+8801798738253', NULL, NULL, NULL, NULL, NULL, NULL, 91740.00, 102168.00, 5, '2026-06-17 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:13:40'),
(7, 2, 'CND-007', 'Hasan', 'Poddar', 'hasan.poddar82@email.com', '+8801781497123', NULL, NULL, NULL, NULL, NULL, NULL, 65834.00, 94468.00, 5, '2026-05-11 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:07:22'),
(8, 2, 'CND-008', 'Farzana', 'Dhar', 'farzana.dhar18@email.com', '+8801795358126', NULL, NULL, NULL, NULL, NULL, NULL, 136155.00, 158609.00, 7, '2026-07-08 13:51:54', '2026-07-09 11:51:54', '2026-07-16 10:53:27'),
(9, 3, 'CND-009', 'Kazi', 'Poddar', 'kazi.poddar37@email.com', '+8801758748880', NULL, NULL, NULL, NULL, NULL, NULL, 119185.00, 152187.00, 5, '2026-07-07 13:51:54', '2026-07-09 11:51:54', '2026-07-22 08:30:50'),
(10, 3, 'CND-010', 'Sharmin', 'Haque', 'sharmin.haque97@email.com', '+8801766021429', NULL, NULL, NULL, NULL, NULL, NULL, 83402.00, 141605.00, 5, '2026-06-14 13:51:54', '2026-07-09 11:51:54', '2026-07-23 09:06:56'),
(11, 3, 'CND-011', 'Mehedi', 'Dhar', 'mehedi.dhar26@email.com', '+8801738065256', NULL, NULL, NULL, NULL, NULL, NULL, 34722.00, 65010.00, 5, '2026-06-06 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:07:08'),
(12, 3, 'CND-012', 'Tahmina', 'Mollah', 'tahmina.mollah75@email.com', '+8801750673848', NULL, NULL, NULL, NULL, NULL, NULL, 119757.00, 134389.00, 5, '2026-06-13 13:51:54', '2026-07-09 11:51:54', '2026-07-19 07:00:41'),
(13, 4, 'CND-013', 'Jubayer', 'Kundu', 'jubayer.kundu21@email.com', '+8801748194711', NULL, NULL, NULL, NULL, NULL, NULL, 83690.00, 103267.00, 3, '2026-07-04 13:51:54', '2026-07-09 11:51:54', '2026-07-23 08:17:16'),
(14, 4, 'CND-014', 'Sultana', 'Rahman', 'sultana.rahman92@email.com', '+8801765498982', NULL, NULL, NULL, NULL, NULL, NULL, 44519.00, 66765.00, 5, '2026-07-08 13:51:54', '2026-07-09 11:51:54', '2026-07-23 08:24:17'),
(15, 4, 'CND-015', 'Rakib', 'Datta', 'rakib.datta63@email.com', '+8801753878440', NULL, NULL, NULL, NULL, NULL, NULL, 67830.00, 75953.00, 5, '2026-06-13 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:14:29'),
(16, 4, 'CND-016', 'Nasrin', 'Sikder', 'nasrin.sikder27@email.com', '+8801779879261', NULL, NULL, NULL, NULL, NULL, NULL, 38365.00, 75206.00, 7, '2026-06-03 13:51:54', '2026-07-09 11:51:54', '2026-07-30 09:33:16'),
(17, 5, 'CND-017', 'Imran', 'Barman', 'imran.barman30@email.com', '+8801752837109', NULL, NULL, NULL, NULL, NULL, NULL, 67500.00, 94990.00, 7, '2026-06-11 13:51:54', '2026-07-09 11:51:54', '2026-07-23 08:16:07'),
(18, 5, 'CND-018', 'Shahnaz', 'Barman', 'shahnaz.barman45@email.com', '+8801738150022', NULL, NULL, NULL, NULL, NULL, NULL, 72603.00, 122491.00, 7, '2026-06-11 13:51:54', '2026-07-09 11:51:54', '2026-07-23 08:20:18'),
(19, 5, 'CND-019', 'Sohel', 'Roy', 'sohel.roy73@email.com', '+8801729225483', NULL, NULL, NULL, NULL, NULL, NULL, 77279.00, 124050.00, 4, '2026-07-06 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:02:24'),
(20, 5, 'CND-020', 'Nazma', 'Sarker', 'nazma.sarker97@email.com', '+8801765518436', NULL, NULL, NULL, NULL, NULL, NULL, 61830.00, 116912.00, 5, '2026-05-13 13:51:54', '2026-07-09 11:51:54', '2026-07-23 09:28:11'),
(21, 6, 'CND-021', 'Shakil', 'Barman', 'shakil.barman44@email.com', '+8801734125931', NULL, NULL, NULL, NULL, NULL, NULL, 47151.00, 59215.00, 2, '2026-06-25 13:51:54', '2026-07-09 11:51:54', '2026-07-25 10:23:45'),
(22, 6, 'CND-022', 'Rokeya', 'Das', 'rokeya.das78@email.com', '+8801796564600', NULL, NULL, NULL, NULL, NULL, NULL, 69138.00, 83849.00, 2, '2026-07-03 13:51:54', '2026-07-09 11:51:54', '2026-07-25 10:24:02'),
(23, 6, 'CND-023', 'Mamun', 'Biswas', 'mamun.biswas53@email.com', '+8801776134254', NULL, NULL, NULL, NULL, NULL, NULL, 80006.00, 104908.00, 2, '2026-05-25 13:51:54', '2026-07-09 11:51:54', '2026-07-25 10:24:20'),
(24, 6, 'CND-024', 'Rashida', 'Mistry', 'rashida.mistry60@email.com', '+8801710804964', NULL, NULL, NULL, NULL, NULL, NULL, 138105.00, 189747.00, 4, '2026-07-08 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:03:00'),
(25, 7, 'CND-025', 'Emon', 'Sikder', 'emon.sikder77@email.com', '+8801730507723', NULL, NULL, NULL, NULL, NULL, NULL, 88555.00, 113840.00, 5, '2026-05-28 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:06:49'),
(26, 7, 'CND-026', 'Nargis', 'Khan', 'nargis.khan11@email.com', '+8801784620936', NULL, NULL, NULL, NULL, NULL, NULL, 144562.00, 200681.00, 4, '2026-05-15 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:04:10'),
(27, 7, 'CND-027', 'Fahim', 'Haque', 'fahim.haque77@email.com', '+8801772045254', NULL, NULL, NULL, NULL, NULL, NULL, 37346.00, 43784.00, 4, '2026-05-24 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:02:33'),
(28, 7, 'CND-028', 'Laily', 'Datta', 'laily.datta91@email.com', '+8801789572899', NULL, NULL, NULL, NULL, NULL, NULL, 108079.00, 158048.00, 2, '2026-05-25 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:01:11'),
(29, 8, 'CND-029', 'Shamim', 'Poddar', 'shamim.poddar49@email.com', '+8801727397188', NULL, NULL, NULL, NULL, NULL, NULL, 54058.00, 103065.00, 3, '2026-06-23 13:51:54', '2026-07-09 11:51:54', '2026-07-25 11:04:21'),
(30, 8, 'CND-030', 'Khadija', 'Saha', 'khadija.saha12@email.com', '+8801752543143', NULL, NULL, NULL, NULL, NULL, NULL, 66648.00, 86663.00, 1, '2026-05-18 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(31, 8, 'CND-031', 'Tareq', 'Khan', 'tareq.khan98@email.com', '+8801763721791', NULL, NULL, NULL, NULL, NULL, NULL, 148242.00, 199416.00, 1, '2026-07-02 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(32, 8, 'CND-032', 'Rowshan', 'Mollah', 'rowshan.mollah45@email.com', '+8801730740733', NULL, NULL, NULL, NULL, NULL, NULL, 61107.00, 91486.00, 1, '2026-05-26 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(33, 9, 'CND-033', 'Rashed', 'Rahman', 'rashed.rahman63@email.com', '+8801726015434', NULL, NULL, NULL, NULL, NULL, NULL, 104800.00, 149292.00, 1, '2026-06-30 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(34, 9, 'CND-034', 'Sajeda', 'Saha', 'sajeda.saha96@email.com', '+8801732164385', NULL, NULL, NULL, NULL, NULL, NULL, 137765.00, 173131.00, 1, '2026-06-20 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(35, 9, 'CND-035', 'Milon', 'Nath', 'milon.nath96@email.com', '+8801763845484', NULL, NULL, NULL, NULL, NULL, NULL, 103037.00, 126757.00, 1, '2026-06-27 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(36, 9, 'CND-036', 'Jahanara', 'Chakraborty', 'jahanara.chakraborty40@email.com', '+8801717797751', NULL, NULL, NULL, NULL, NULL, NULL, 137189.00, 170555.00, 1, '2026-07-02 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(37, 10, 'CND-037', 'Himel', 'Roy', 'himel.roy54@email.com', '+8801760446879', NULL, NULL, NULL, NULL, NULL, NULL, 146816.00, 193047.00, 1, '2026-05-11 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(38, 10, 'CND-038', 'Sharmin', 'Sen', 'sharmin.sen53@email.com', '+8801780194960', NULL, NULL, NULL, NULL, NULL, NULL, 149157.00, 175501.00, 1, '2026-05-26 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(39, 10, 'CND-039', 'Mahbub', 'Dhar', 'mahbub.dhar69@email.com', '+8801769159400', NULL, NULL, NULL, NULL, NULL, NULL, 132620.00, 167165.00, 1, '2026-05-11 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(40, 10, 'CND-040', 'Saleha', 'Chakraborty', 'saleha.chakraborty82@email.com', '+8801793001799', NULL, NULL, NULL, NULL, NULL, NULL, 69026.00, 86457.00, 1, '2026-06-29 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(41, 11, 'CND-041', 'Nayeem', 'Barman', 'nayeem.barman73@email.com', '+8801747836129', NULL, NULL, NULL, NULL, NULL, NULL, 141619.00, 170922.00, 1, '2026-06-28 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(42, 11, 'CND-042', 'Shamima', 'Kundu', 'shamima.kundu74@email.com', '+8801751522354', NULL, NULL, NULL, NULL, NULL, NULL, 28456.00, 33653.00, 1, '2026-05-14 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(43, 11, 'CND-043', 'Rafi', 'Nath', 'rafi.nath72@email.com', '+8801714642764', NULL, NULL, NULL, NULL, NULL, NULL, 145691.00, 192079.00, 1, '2026-06-23 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(44, 11, 'CND-044', 'Maksuda', 'Khan', 'maksuda.khan37@email.com', '+8801731634852', NULL, NULL, NULL, NULL, NULL, NULL, 35657.00, 70498.00, 1, '2026-06-01 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(45, 12, 'CND-045', 'Shohel', 'Islam', 'shohel.islam43@email.com', '+8801758515488', NULL, NULL, NULL, NULL, NULL, NULL, 108354.00, 127165.00, 1, '2026-06-19 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(46, 12, 'CND-046', 'Aysha', 'Sarker', 'aysha.sarker44@email.com', '+8801739819079', NULL, NULL, NULL, NULL, NULL, NULL, 119592.00, 146960.00, 1, '2026-05-31 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(47, 12, 'CND-047', 'Jamil', 'Majumder', 'jamil.majumder20@email.com', '+8801744494764', NULL, NULL, NULL, NULL, NULL, NULL, 97843.00, 155775.00, 1, '2026-05-15 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(48, 12, 'CND-048', 'Shahnoor', 'Pal', 'shahnoor.pal74@email.com', '+8801789976509', NULL, NULL, NULL, NULL, NULL, NULL, 32860.00, 40906.00, 1, '2026-06-15 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(49, 13, 'CND-049', 'Anik', 'Rahman', 'anik.rahman23@email.com', '+8801763912113', NULL, NULL, NULL, NULL, NULL, NULL, 36214.00, 65056.00, 1, '2026-05-25 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(50, 13, 'CND-050', 'Ferdousi', 'Chakraborty', 'ferdousi.chakraborty34@email.com', '+8801749648656', NULL, NULL, NULL, NULL, NULL, NULL, 36926.00, 51473.00, 1, '2026-05-11 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(51, 13, 'CND-051', 'Bappy', 'Majumder', 'bappy.majumder61@email.com', '+8801722393723', NULL, NULL, NULL, NULL, NULL, NULL, 114609.00, 158392.00, 1, '2026-06-14 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(52, 13, 'CND-052', 'Morsheda', 'Sarker', 'morsheda.sarker49@email.com', '+8801715670096', NULL, NULL, NULL, NULL, NULL, NULL, 66412.00, 120196.00, 1, '2026-06-29 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(53, 14, 'CND-053', 'Nadim', 'Islam', 'nadim.islam40@email.com', '+8801717436492', NULL, NULL, NULL, NULL, NULL, NULL, 74859.00, 108396.00, 1, '2026-06-15 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(54, 14, 'CND-054', 'Shahida', 'Sutradhar', 'shahida.sutradhar84@email.com', '+8801772211502', NULL, NULL, NULL, NULL, NULL, NULL, 109046.00, 157845.00, 1, '2026-06-04 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(55, 14, 'CND-055', 'Riaz', 'Roy', 'riaz.roy38@email.com', '+8801745083660', NULL, NULL, NULL, NULL, NULL, NULL, 105099.00, 149816.00, 1, '2026-06-03 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(56, 14, 'CND-056', 'Hosne', 'Sutradhar', 'hosne.sutradhar60@email.com', '+8801751789817', NULL, NULL, NULL, NULL, NULL, NULL, 88561.00, 107239.00, 1, '2026-05-20 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(57, 15, 'CND-057', 'Zahir', 'Islam', 'zahir.islam74@email.com', '+8801753487773', NULL, NULL, NULL, NULL, NULL, NULL, 33863.00, 70041.00, 1, '2026-05-12 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(58, 15, 'CND-058', 'Sabina', 'Bose', 'sabina.bose72@email.com', '+8801793262110', NULL, NULL, NULL, NULL, NULL, NULL, 34833.00, 47782.00, 1, '2026-05-10 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(59, 15, 'CND-059', 'Faruk', 'Hossain', 'faruk.hossain85@email.com', '+8801787775778', NULL, NULL, NULL, NULL, NULL, NULL, 61641.00, 83255.00, 1, '2026-06-11 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(60, 15, 'CND-060', 'Suriya', 'Hossain', 'suriya.hossain53@email.com', '+8801738217502', NULL, NULL, NULL, NULL, NULL, NULL, 102505.00, 124074.00, 1, '2026-06-08 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(61, 16, 'CND-061', 'Kawser', 'Mollah', 'kawser.mollah57@email.com', '+8801793334613', NULL, NULL, NULL, NULL, NULL, NULL, 116143.00, 127393.00, 1, '2026-06-25 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(62, 16, 'CND-062', 'Rina', 'Mollah', 'rina.mollah62@email.com', '+8801733637417', NULL, NULL, NULL, NULL, NULL, NULL, 74889.00, 134644.00, 1, '2026-06-04 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(63, 16, 'CND-063', 'Jahid', 'Chakraborty', 'jahid.chakraborty79@email.com', '+8801754351985', NULL, NULL, NULL, NULL, NULL, NULL, 123452.00, 161667.00, 1, '2026-06-27 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(64, 16, 'CND-064', 'Nasima', 'Ahmed', 'nasima.ahmed57@email.com', '+8801795923973', NULL, NULL, NULL, NULL, NULL, NULL, 115282.00, 135431.00, 1, '2026-06-13 13:51:54', '2026-07-09 11:51:54', '2026-07-09 11:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `candidate_logs`
--

CREATE TABLE `candidate_logs` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `from_status` varchar(30) DEFAULT NULL,
  `to_status` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT 'Admin User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate_logs`
--

INSERT INTO `candidate_logs` (`id`, `candidate_id`, `action`, `from_status`, `to_status`, `notes`, `created_by`, `created_at`) VALUES
(67, 5, 'screening_shortlist', 'Applied', 'Shortlisted', 'Screen result not good but need to take interview', 'Admin User', '2026-07-16 10:51:57'),
(68, 1, 'screening_shortlist', 'Applied', 'Shortlisted', 'Good to go', 'Admin User', '2026-07-16 10:52:35'),
(69, 2, 'screening_shortlist', 'Applied', 'Shortlisted', 'Good to go', 'Admin User', '2026-07-16 10:52:54'),
(70, 8, 'screening_reject', 'Applied', 'Rejected', 'Not capable', 'Admin User', '2026-07-16 10:53:27'),
(71, 3, 'screening_hold', 'Applied', 'Hold', 'Hold for now.', 'Admin User', '2026-07-16 10:54:02'),
(72, 2, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (Writter) - In-Person with Interview 1', 'Admin User', '2026-07-16 10:54:55'),
(73, 2, 'interview_hold', 'Interview', 'Hold', 'Good to go for practical interview', 'Admin User', '2026-07-16 10:55:44'),
(74, 2, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 2 (Practical) - In-Person with Interviewer 2', 'Admin User', '2026-07-16 10:56:13'),
(75, 2, 'interview_pass', 'Interview', 'Selected', 'fsdfa', 'Admin User', '2026-07-16 11:01:51'),
(76, 2, 'salary_proposed', NULL, NULL, 'Salary proposed: 77434 (v1)', 'Admin User', '2026-07-16 11:02:02'),
(77, 2, 'salary_proposed', NULL, NULL, 'Salary proposed: 77434 (v2)', 'Admin User', '2026-07-16 11:02:10'),
(78, 2, 'salary_finalized', NULL, NULL, 'Salary finalized: 77434', 'Admin User', '2026-07-16 11:02:18'),
(79, 2, 'offer_created', 'Selected', 'Offered', 'Offer created with salary 77434', 'Admin User', '2026-07-16 11:02:44'),
(80, 2, 'offer_approved', NULL, NULL, 'Offer approved after all levels.', 'Admin User', '2026-07-16 11:03:03'),
(81, 2, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-16 11:03:15'),
(82, 2, 'offer_sent', NULL, NULL, 'Offer sent via email to priya.malaker21@email.com', 'Admin User', '2026-07-16 11:03:20'),
(83, 2, 'probation_updated', NULL, NULL, 'Probation appraisal #6 updated.', 'Admin User', '2026-07-16 11:04:23'),
(84, 2, 'probation_submitted', NULL, NULL, 'Probation appraisal #6 submitted for approval.', 'Admin User', '2026-07-16 11:04:23'),
(85, 1, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (Written) - In-Person with Interview 1', 'Admin User', '2026-07-16 11:55:34'),
(86, 1, 'interview_fail', 'Interview', 'Rejected', 'dsfasdf | Reason: Lack of technical skills', 'Admin User', '2026-07-16 12:02:43'),
(87, 1, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 2 (practical) - In-Person with Interview 2', 'Admin User', '2026-07-16 12:03:16'),
(88, 1, 'interview_round_pass', 'Interview', 'Interview', 'asdfa', 'Admin User', '2026-07-16 12:07:20'),
(89, 1, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 3 (sdfasdf a) - Video with ffasdf', 'Admin User', '2026-07-16 12:07:45'),
(90, 1, 'interview_fail', 'Interview', 'Rejected', 'asdfasf | Reason: Cultural fit', 'Admin User', '2026-07-16 12:08:01'),
(91, 4, 'screening_shortlist', 'Applied', 'Shortlisted', 'sdf', 'Admin User', '2026-07-19 04:31:58'),
(92, 6, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasf', 'Admin User', '2026-07-19 04:32:07'),
(93, 9, 'screening_shortlist', 'Applied', 'Shortlisted', 'asdfa', 'Admin User', '2026-07-19 04:32:17'),
(94, 7, 'screening_shortlist', 'Applied', 'Shortlisted', 'sdfa', 'Admin User', '2026-07-19 04:32:29'),
(95, 12, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdf a', 'Admin User', '2026-07-19 04:32:40'),
(96, 7, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (technical) - In-Person with interview 1', 'Admin User', '2026-07-19 04:33:16'),
(97, 7, 'interview_round_pass', 'Interview', 'Interview', 'good on this round', 'Admin User', '2026-07-19 04:35:50'),
(98, 7, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 2 (Management) - In-Person with interviewer2', 'Admin User', '2026-07-19 04:36:33'),
(99, 7, 'interview_fail', 'Interview', 'Rejected', 'Not capable | Reason: Salary expectation mismatch', 'Admin User', '2026-07-19 04:37:48'),
(100, 7, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 3 (Management again) - In-Person with inteviewer 3', 'Admin User', '2026-07-19 04:38:32'),
(101, 7, 'interview_pass', 'Interview', 'Selected', 'good to go', 'Admin User', '2026-07-19 04:39:20'),
(102, 5, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (technical) - In-Person with interviewer 2', 'Admin User', '2026-07-19 04:40:01'),
(103, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #6 approved at Supervisor level.', 'Admin User', '2026-07-19 06:07:05'),
(104, 7, 'salary_proposed', NULL, NULL, 'Salary proposed: 94468 (v1)', 'Admin User', '2026-07-19 06:16:25'),
(105, 7, 'salary_finalized', NULL, NULL, 'Salary finalized: 94468', 'Admin User', '2026-07-19 06:16:40'),
(106, 12, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (fasdf) - In-Person with fasdf sd', 'Admin User', '2026-07-19 06:22:44'),
(107, 12, 'interview_pass', 'Interview', 'Selected', 'sdfasdf sd', 'Admin User', '2026-07-19 06:22:58'),
(108, 5, 'interview_pass', 'Interview', 'Selected', 'fasdfasdf', 'Admin User', '2026-07-19 06:23:41'),
(109, 4, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (asdfa) - In-Person with asdfasd', 'Admin User', '2026-07-19 06:24:20'),
(110, 4, 'interview_pass', 'Interview', 'Selected', 'asdfasdf', 'Admin User', '2026-07-19 06:24:35'),
(111, 6, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (dfasdfasdf) - In-Person with fasdfasdf', 'Admin User', '2026-07-19 06:24:50'),
(112, 6, 'interview_fail', 'Interview', 'Rejected', 'sadfasdf', 'Admin User', '2026-07-19 06:25:01'),
(113, 5, 'salary_proposed', NULL, NULL, 'Salary proposed: 190524 (v1)', 'Admin User', '2026-07-19 06:27:37'),
(114, 5, 'proposal_submitted_approval', NULL, NULL, 'Proposal #9 submitted for approval', 'Admin User', '2026-07-19 06:43:45'),
(115, 12, 'salary_proposed', NULL, NULL, 'Salary proposed: 134389 (v1)', 'Admin User', '2026-07-19 06:55:07'),
(116, 1, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 4 (asdfasf) - In-Person with interveiw', 'Admin User', '2026-07-19 06:56:55'),
(117, 1, 'interview_pass', 'Interview', 'Selected', 'asdfasdf', 'Admin User', '2026-07-19 06:57:06'),
(118, 12, 'proposal_submitted_approval', NULL, NULL, 'Proposal #10 submitted for approval', 'Admin User', '2026-07-19 07:00:09'),
(119, 12, 'proposal_approved', NULL, NULL, 'Proposal #10 approved. Offer #6 auto-created with salary 134389.00', 'Admin User', '2026-07-19 07:00:41'),
(120, 5, 'proposal_rejected', NULL, NULL, 'Proposal #9 rejected: Not capable.', 'Admin User', '2026-07-19 07:03:14'),
(121, 9, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (asdfasd f) - Video with dsfasfd', 'Admin User', '2026-07-19 07:12:22'),
(122, 9, 'interview_pass', 'Interview', 'Selected', 'fasdf', 'Admin User', '2026-07-19 07:12:29'),
(123, 9, 'salary_proposed', NULL, NULL, 'Salary proposed: 100000 (v1)', 'Admin User', '2026-07-19 08:34:10'),
(124, 9, 'proposal_submitted_approval', NULL, NULL, 'Proposal #11 submitted for approval', 'Admin User', '2026-07-19 08:34:37'),
(125, 12, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-19 09:38:28'),
(126, 12, 'offer_sent', NULL, NULL, 'Offer sent via email to tahmina.mollah75@email.com', 'Admin User', '2026-07-19 09:44:52'),
(127, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #6 approved at Dept Head level.', 'Admin User', '2026-07-19 09:46:29'),
(128, 12, 'probation_updated', NULL, NULL, 'Probation appraisal #7 updated.', 'Admin User', '2026-07-19 09:48:47'),
(129, 12, 'probation_submitted', NULL, NULL, 'Probation appraisal #7 submitted for approval.', 'Admin User', '2026-07-19 09:48:47'),
(130, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #6 approved at HR Review level.', 'Admin User', '2026-07-19 10:06:02'),
(131, 12, 'probation_rejected', NULL, NULL, 'Probation appraisal #7 rejected at Supervisor level.', 'Admin User', '2026-07-19 10:25:56'),
(132, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #6 approved at GM level.', 'Admin User', '2026-07-19 10:36:38'),
(133, 2, 'probation_completed', NULL, NULL, 'Probation appraisal #6 fully approved.', 'Admin User', '2026-07-19 10:36:46'),
(134, 9, 'proposal_approved', NULL, NULL, 'Proposal #11 approved. Offer #7 auto-created with salary 100000.00', 'Admin User', '2026-07-22 08:30:50'),
(135, 1, 'salary_proposed', NULL, NULL, 'Salary proposed: 100000 (v1)', 'Admin User', '2026-07-22 09:59:13'),
(136, 1, 'proposal_submitted_approval', NULL, NULL, 'Proposal #12 submitted for approval', 'Admin User', '2026-07-22 09:59:20'),
(137, 1, 'proposal_approved', NULL, NULL, 'Proposal #12 approved. Offer #8 auto-created with salary 100000.00', 'Admin User', '2026-07-22 10:00:03'),
(138, 1, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-22 10:29:51'),
(139, 1, 'offer_sent', NULL, NULL, 'Offer sent via email to rahul.sikder11@email.com', 'Admin User', '2026-07-23 06:56:21'),
(140, 10, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdfasdf', 'Admin User', '2026-07-23 08:15:06'),
(141, 11, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdf', 'Admin User', '2026-07-23 08:15:16'),
(142, 13, 'screening_shortlist', 'Applied', 'Shortlisted', 'fsdf', 'Admin User', '2026-07-23 08:15:24'),
(143, 14, 'screening_shortlist', 'Applied', 'Shortlisted', 'fadf asd', 'Admin User', '2026-07-23 08:15:33'),
(144, 15, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdf', 'Admin User', '2026-07-23 08:15:46'),
(145, 16, 'screening_shortlist', 'Applied', 'Shortlisted', 'fsadf', 'Admin User', '2026-07-23 08:15:56'),
(146, 17, 'screening_reject', 'Applied', 'Rejected', 'asdf', 'Admin User', '2026-07-23 08:16:07'),
(147, 18, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdf', 'Admin User', '2026-07-23 08:16:23'),
(148, 19, 'screening_shortlist', 'Applied', 'Shortlisted', 'sadf f', 'Admin User', '2026-07-23 08:16:32'),
(149, 20, 'screening_shortlist', 'Applied', 'Shortlisted', 'sdfasdf', 'Admin User', '2026-07-23 08:16:46'),
(150, 13, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (writting) - In-Person with in1', 'Admin User', '2026-07-23 08:17:16'),
(151, 14, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (Reda) - In-Person with inter 2', 'Admin User', '2026-07-23 08:17:57'),
(152, 19, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (fasdf e) - In-Person with inter', 'Admin User', '2026-07-23 08:18:10'),
(153, 10, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (casdc e) - In-Person with inter', 'Admin User', '2026-07-23 08:18:26'),
(154, 18, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 () - In-Person with asdf', 'Admin User', '2026-07-23 08:18:39'),
(155, 13, 'interview_round_pass', 'Interview', 'Interview', 'fasdf', 'Admin User', '2026-07-23 08:18:52'),
(156, 13, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 2 (group) - In-Person with interviewer 3', 'Admin User', '2026-07-23 08:19:22'),
(157, 13, 'interview_round_pass', 'Interview', 'Interview', 'asdf', 'Admin User', '2026-07-23 08:19:44'),
(158, 18, 'interview_fail', 'Interview', 'Rejected', 'asdf | Reason: Lack of technical skills', 'Admin User', '2026-07-23 08:20:18'),
(159, 6, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 2 (fsfd) - Video with asdf as', 'Admin User', '2026-07-23 08:20:45'),
(160, 6, 'interview_fail', 'Interview', 'Rejected', 'asdf', 'Admin User', '2026-07-23 08:21:35'),
(161, 10, 'interview_pass', 'Interview', 'Selected', 'asdf', 'Admin User', '2026-07-23 08:22:09'),
(162, 14, 'interview_pass', 'Interview', 'Selected', 'asdf', 'Admin User', '2026-07-23 08:22:26'),
(163, 6, 'interview_scheduled', 'Interview', 'Interview', 'Scheduled: Round 3 (fsdf) - In-Person with dfasdf', 'Admin User', '2026-07-23 08:22:38'),
(164, 14, 'salary_proposed', NULL, NULL, 'Salary proposed: 1112 (v1)', 'Admin User', '2026-07-23 08:23:56'),
(165, 14, 'proposal_submitted_approval', NULL, NULL, 'Proposal #13 submitted for approval', 'Admin User', '2026-07-23 08:24:01'),
(166, 14, 'proposal_approved', NULL, NULL, 'Proposal #13 approved. Offer #9 auto-created with salary 1112.00', 'Admin User', '2026-07-23 08:24:17'),
(167, 9, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 08:24:56'),
(168, 9, 'offer_sent', NULL, NULL, 'Offer sent via email to kazi.poddar37@email.com', 'Admin User', '2026-07-23 08:25:06'),
(169, 20, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (sdf s) - In-Person with interview 2', 'Admin User', '2026-07-23 08:57:21'),
(170, 15, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (techincih) - In-Person with Interview 4', 'Admin User', '2026-07-23 08:57:43'),
(171, 20, 'interview_pass', 'Interview', 'Selected', 'fasdf', 'Admin User', '2026-07-23 08:57:54'),
(172, 6, 'interview_pass', 'Interview', 'Selected', 'asdf', 'Admin User', '2026-07-23 08:58:01'),
(173, 15, 'interview_pass', 'Interview', 'Selected', 'asdf asdf sa', 'Admin User', '2026-07-23 08:58:13'),
(174, 4, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-23 09:02:21'),
(175, 10, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-23 09:03:14'),
(176, 10, 'proposal_submitted_approval', NULL, NULL, 'Proposal #15 submitted for approval', 'Admin User', '2026-07-23 09:03:20'),
(177, 4, 'proposal_submitted_approval', NULL, NULL, 'Proposal #14 submitted for approval', 'Admin User', '2026-07-23 09:03:22'),
(178, 15, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-23 09:03:29'),
(179, 20, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-23 09:06:31'),
(180, 15, 'proposal_submitted_approval', NULL, NULL, 'Proposal #16 submitted for approval', 'Admin User', '2026-07-23 09:06:37'),
(181, 10, 'proposal_approved', NULL, NULL, 'Proposal #15 approved. Offer #10 auto-created with salary 100.00', 'Admin User', '2026-07-23 09:06:56'),
(182, 7, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-23 09:12:00'),
(183, 20, 'proposal_submitted_approval', NULL, NULL, 'Proposal #17 submitted for approval', 'Admin User', '2026-07-23 09:12:08'),
(184, 4, 'proposal_approved', NULL, NULL, 'Proposal #14 approved. Offer #11 auto-created with salary 100.00', 'Admin User', '2026-07-23 09:12:39'),
(185, 10, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:13:57'),
(186, 10, 'offer_sent', NULL, NULL, 'Offer sent via email to sharmin.haque97@email.com', 'Admin User', '2026-07-23 09:14:11'),
(187, 4, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:14:19'),
(188, 14, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:15:08'),
(189, 4, 'offer_sent', NULL, NULL, 'Offer sent via email to sadia.haque15@email.com', 'Admin User', '2026-07-23 09:15:38'),
(190, 14, 'offer_sent', NULL, NULL, 'Offer sent via email to sultana.rahman92@email.com', 'Admin User', '2026-07-23 09:15:41'),
(191, 12, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:16:49'),
(192, 12, 'offer_sent', NULL, NULL, 'Offer sent via email to tahmina.mollah75@email.com', 'Admin User', '2026-07-23 09:17:08'),
(193, 20, 'proposal_approved', NULL, NULL, 'Proposal #17 approved. Offer #12 auto-created with salary 100.00', 'Admin User', '2026-07-23 09:28:11'),
(194, 20, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:28:21'),
(195, 20, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-23 09:28:26'),
(196, 20, 'offer_sent', NULL, NULL, 'Offer sent via email to nazma.sarker97@email.com', 'Admin User', '2026-07-23 09:28:34'),
(197, 2, 'probation_updated', NULL, NULL, 'Probation appraisal #8 updated.', 'Admin User', '2026-07-23 09:30:45'),
(198, 2, 'probation_submitted', NULL, NULL, 'Probation appraisal #8 submitted for approval.', 'Admin User', '2026-07-23 09:30:46'),
(199, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #8 approved at Supervisor level.', 'Admin User', '2026-07-23 09:56:24'),
(200, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #8 approved at Dept Head level.', 'Admin User', '2026-07-23 09:56:37'),
(201, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #8 approved at HR Review level.', 'Admin User', '2026-07-23 09:56:42'),
(202, 2, 'probation_approved', NULL, NULL, 'Probation appraisal #8 approved at GM level.', 'Admin User', '2026-07-23 09:56:51'),
(203, 2, 'probation_completed', NULL, NULL, 'Probation appraisal #8 fully approved.', 'Admin User', '2026-07-23 09:56:55'),
(204, 21, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasdf', 'Admin User', '2026-07-25 10:23:45'),
(205, 22, 'screening_shortlist', 'Applied', 'Shortlisted', 'afs', 'Admin User', '2026-07-25 10:24:02'),
(206, 23, 'screening_shortlist', 'Applied', 'Shortlisted', 'fasd', 'Admin User', '2026-07-25 10:24:20'),
(207, 24, 'screening_shortlist', 'Applied', 'Shortlisted', 'asdf as', 'Admin User', '2026-07-25 10:24:30'),
(208, 25, 'screening_shortlist', 'Applied', 'Shortlisted', 'afs', 'Admin User', '2026-07-25 10:24:39'),
(209, 26, 'screening_shortlist', 'Applied', 'Shortlisted', 'gdsf', 'Admin User', '2026-07-25 11:00:43'),
(210, 27, 'screening_shortlist', 'Applied', 'Shortlisted', 'sdfg', 'Admin User', '2026-07-25 11:00:53'),
(211, 29, 'screening_shortlist', 'Applied', 'Shortlisted', 'sdg', 'Admin User', '2026-07-25 11:01:02'),
(212, 28, 'screening_shortlist', 'Applied', 'Shortlisted', 'gsdg', 'Admin User', '2026-07-25 11:01:11'),
(213, 24, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (gdfg) - In-Person with dfgs', 'Admin User', '2026-07-25 11:01:33'),
(214, 25, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (gdfg) - Technical with dsgf', 'Admin User', '2026-07-25 11:01:44'),
(215, 27, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (dfgdsg) - Technical with gdsfg', 'Admin User', '2026-07-25 11:01:55'),
(216, 11, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (gsdfg) - Video with gsdfg', 'Admin User', '2026-07-25 11:02:07'),
(217, 19, 'interview_pass', 'Interview', 'Selected', 'dfgsd', 'Admin User', '2026-07-25 11:02:24'),
(218, 27, 'interview_pass', 'Interview', 'Selected', 'gdg gsdf', 'Admin User', '2026-07-25 11:02:33'),
(219, 25, 'interview_pass', 'Interview', 'Selected', 'sdfg', 'Admin User', '2026-07-25 11:02:40'),
(220, 11, 'interview_pass', 'Interview', 'Selected', 'sdfg s', 'Admin User', '2026-07-25 11:02:53'),
(221, 24, 'interview_pass', 'Interview', 'Selected', 'sdfg', 'Admin User', '2026-07-25 11:03:00'),
(222, 16, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (dgfsdfg) - Technical with sdfgsdfg', 'Admin User', '2026-07-25 11:03:29'),
(223, 16, 'interview_pass', 'Interview', 'Selected', 'fgsdfg', 'Admin User', '2026-07-25 11:03:40'),
(224, 26, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (gsdfgsdg) - In-Person with sdfg', 'Admin User', '2026-07-25 11:04:03'),
(225, 26, 'interview_pass', 'Interview', 'Selected', 'gsdfgsdfg', 'Admin User', '2026-07-25 11:04:10'),
(226, 29, 'interview_scheduled', 'Shortlisted', 'Interview', 'Scheduled: Round 1 (dsfgsdfgs) - Technical with dgsd', 'Admin User', '2026-07-25 11:04:21'),
(227, 16, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-25 11:04:38'),
(228, 6, 'salary_proposed', NULL, NULL, 'Salary proposed: 100 (v1)', 'Admin User', '2026-07-25 11:04:53'),
(229, 16, 'proposal_submitted_approval', NULL, NULL, 'Proposal #19 submitted for approval', 'Admin User', '2026-07-25 11:05:20'),
(230, 6, 'proposal_submitted_approval', NULL, NULL, 'Proposal #20 submitted for approval', 'Admin User', '2026-07-25 11:05:25'),
(231, 11, 'salary_proposed', NULL, NULL, 'Salary proposed: 1000 (v1)', 'Admin User', '2026-07-25 11:05:33'),
(232, 25, 'salary_proposed', NULL, NULL, 'Salary proposed: 2009 (v1)', 'Admin User', '2026-07-25 11:05:55'),
(233, 25, 'proposal_submitted_approval', NULL, NULL, 'Proposal #22 submitted for approval', 'Admin User', '2026-07-25 11:05:59'),
(234, 11, 'proposal_submitted_approval', NULL, NULL, 'Proposal #21 submitted for approval', 'Admin User', '2026-07-25 11:06:01'),
(235, 7, 'proposal_submitted_approval', NULL, NULL, 'Proposal #18 submitted for approval', 'Admin User', '2026-07-25 11:06:11'),
(236, 25, 'proposal_approved', NULL, NULL, 'Proposal #22 approved. Offer #13 auto-created with salary 2009.00', 'Admin User', '2026-07-25 11:06:49'),
(237, 11, 'proposal_approved', NULL, NULL, 'Proposal #21 approved. Offer #14 auto-created with salary 1000.00', 'Admin User', '2026-07-25 11:07:08'),
(238, 7, 'proposal_approved', NULL, NULL, 'Proposal #18 approved. Offer #15 auto-created with salary 100.00', 'Admin User', '2026-07-25 11:07:22'),
(239, 2, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:08:10'),
(240, 2, 'offer_sent', NULL, NULL, 'Offer sent via email to priya.malaker21@email.com', 'Admin User', '2026-07-25 11:08:24'),
(241, 11, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:08:41'),
(242, 11, 'offer_sent', NULL, NULL, 'Offer sent via email to mehedi.dhar26@email.com', 'Admin User', '2026-07-25 11:08:48'),
(243, 7, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:12:14'),
(244, 7, 'offer_sent', NULL, NULL, 'Offer sent via email to hasan.poddar82@email.com', 'Admin User', '2026-07-25 11:12:18'),
(245, 25, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:12:40'),
(246, 25, 'offer_sent', NULL, NULL, 'Offer sent via email to emon.sikder77@email.com', 'Admin User', '2026-07-25 11:12:55'),
(247, 16, 'proposal_approved', NULL, NULL, 'Proposal #19 approved. Offer #16 auto-created with salary 100.00', 'Admin User', '2026-07-25 11:13:09'),
(248, 6, 'proposal_approved', NULL, NULL, 'Proposal #20 approved. Offer #17 auto-created with salary 100.00', 'Admin User', '2026-07-25 11:13:40'),
(249, 16, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:13:57'),
(250, 16, 'offer_sent', NULL, NULL, 'Offer sent via email to nasrin.sikder27@email.com', 'Admin User', '2026-07-25 11:14:04'),
(251, 6, 'offer_letter_generated', NULL, NULL, 'Offer letter generated.', 'Admin User', '2026-07-25 11:14:17'),
(252, 15, 'proposal_approved', NULL, NULL, 'Proposal #16 approved. Offer #18 auto-created with salary 100.00', 'Admin User', '2026-07-25 11:14:29'),
(253, 19, 'salary_proposed', NULL, NULL, 'Salary proposed: 400 (v1)', 'Admin User', '2026-07-25 11:14:42'),
(254, 19, 'proposal_submitted_approval', NULL, NULL, 'Proposal #23 submitted for approval', 'Admin User', '2026-07-25 11:14:53'),
(255, 24, 'salary_proposed', NULL, NULL, 'Salary proposed: 500 (v1)', 'Admin User', '2026-07-25 11:15:02'),
(256, 20, 'probation_updated', NULL, NULL, 'Probation appraisal #9 updated.', 'Admin User', '2026-07-25 11:18:04'),
(257, 20, 'probation_submitted', NULL, NULL, 'Probation appraisal #9 submitted for approval.', 'Admin User', '2026-07-25 11:18:04'),
(258, 4, 'probation_updated', NULL, NULL, 'Probation appraisal #10 updated.', 'Admin User', '2026-07-26 05:34:58'),
(259, 4, 'probation_submitted', NULL, NULL, 'Probation appraisal #10 submitted for approval.', 'Admin User', '2026-07-26 05:34:58'),
(260, 4, 'probation_approved', NULL, NULL, 'Probation appraisal #10 approved at Supervisor level.', 'Admin User', '2026-07-26 05:35:11'),
(261, 4, 'probation_approved', NULL, NULL, 'Probation appraisal #10 approved at Dept Head level.', 'Admin User', '2026-07-26 05:35:14'),
(262, 4, 'probation_approved', NULL, NULL, 'Probation appraisal #10 approved at HR Review level.', 'Admin User', '2026-07-26 05:35:18'),
(263, 4, 'probation_approved', NULL, NULL, 'Probation appraisal #10 approved at GM level.', 'Admin User', '2026-07-26 05:35:21'),
(264, 4, 'probation_completed', NULL, NULL, 'Probation appraisal #10 fully approved.', 'Admin User', '2026-07-26 05:35:25'),
(265, 16, 'offer_rejected_candidate', NULL, NULL, 'Offer rejected by candidate: test rejected', 'Admin User', '2026-07-30 09:33:16'),
(266, 14, 'probation_updated', NULL, NULL, 'Probation appraisal #11 updated.', 'Admin User', '2026-07-30 09:36:54'),
(267, 14, 'probation_submitted', NULL, NULL, 'Probation appraisal #11 submitted for approval.', 'Admin User', '2026-07-30 09:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `candidate_statuses`
--

CREATE TABLE `candidate_statuses` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate_statuses`
--

INSERT INTO `candidate_statuses` (`id`, `code`, `name`, `sort_order`, `description`) VALUES
(1, 'Applied', 'Applied', 1, 'Initial application submitted'),
(2, 'Shortlisted', 'Shortlisted', 2, 'Shortlisted after screening'),
(3, 'Interview', 'Interview', 3, 'Interview in progress'),
(4, 'Selected', 'Selected', 4, 'Passed all interviews'),
(5, 'Offered', 'Offered', 5, 'Offer sent to candidate'),
(6, 'Joined', 'Joined', 6, 'Candidate joined the organization'),
(7, 'Rejected', 'Rejected', 99, 'Candidate rejected'),
(8, 'Hold', 'Hold', 98, 'Candidate on hold');

-- --------------------------------------------------------

--
-- Table structure for table `confirmation_letters`
--

CREATE TABLE `confirmation_letters` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `appraisal_id` int(11) DEFAULT NULL,
  `letter_content` longtext DEFAULT NULL,
  `letter_format` varchar(10) DEFAULT 'html',
  `employment_type` varchar(50) DEFAULT 'Probationer',
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `generated_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `sent_to_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmation_letters`
--

INSERT INTO `confirmation_letters` (`id`, `employee_id`, `offer_id`, `appraisal_id`, `letter_content`, `letter_format`, `employment_type`, `status`, `generated_at`, `approved_at`, `approved_by`, `sent_at`, `sent_to_email`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 6, '<p>Test letter</p>', 'html', 'Permanent', 'Sent', '2026-07-23 15:54:00', '2026-07-23 15:55:35', 'Admin User', '2026-07-23 15:55:44', 'tahmina.mollah75@email.com', '2026-07-23 09:54:00', '2026-07-23 09:55:44'),
(2, 1, NULL, 8, '<h2 class=\"ql-align-center\"><span class=\"ql-font-serif\">TechCorp International</span></h2><p class=\"ql-align-center\"><span class=\"ql-font-serif\" style=\"color: rgb(102, 102, 102);\">Employee Lifecycle Management</span></p><p><span class=\"ql-font-serif\">Date: July 23, 2026</span></p><p><span class=\"ql-font-serif\">Dear Priya Malaker,</span></p><p><strong class=\"ql-font-serif\">Subject: Confirmation of Employment</strong></p><p><span class=\"ql-font-serif\">We are pleased to inform you that, based on your performance during the probation period, your employment with TechCorp International has been confirmed effective from </span><strong class=\"ql-font-serif\">January 16, 2027</strong><span class=\"ql-font-serif\">.</span></p><p><span class=\"ql-font-serif\">You are being confirmed as </span><strong class=\"ql-font-serif\">Software Engineer</strong><span class=\"ql-font-serif\"> in the </span><strong class=\"ql-font-serif\">Engineering</strong><span class=\"ql-font-serif\"> department.</span></p><table><tbody><tr><td data-row=\"1\"><span class=\"ql-font-serif\">Employee Code:</span></td><td data-row=\"1\"><span class=\"ql-font-serif\">EMP-00001</span></td></tr><tr><td data-row=\"2\"><span class=\"ql-font-serif\">Designation:</span></td><td data-row=\"2\"><span class=\"ql-font-serif\">Software Engineer</span></td></tr><tr><td data-row=\"3\"><span class=\"ql-font-serif\">Department:</span></td><td data-row=\"3\"><span class=\"ql-font-serif\">Engineering</span></td></tr><tr><td data-row=\"4\"><span class=\"ql-font-serif\">Joining Date:</span></td><td data-row=\"4\"><span class=\"ql-font-serif\">July 16, 2026</span></td></tr><tr><td data-row=\"5\"><span class=\"ql-font-serif\">Probation Period:</span></td><td data-row=\"5\"><span class=\"ql-font-serif\">6 months</span></td></tr><tr><td data-row=\"6\"><span class=\"ql-font-serif\">Current Salary:</span></td><td data-row=\"6\"><span class=\"ql-font-serif\">77,434.00 BDT</span></td></tr></tbody></table><p><span class=\"ql-font-serif\">We appreciate your contribution and look forward to your continued success with the organization.</span></p><p><span class=\"ql-font-serif\">We wish you all the best in your future endeavors with us.</span></p><p><br></p><p><span class=\"ql-font-serif\">Best Regards,</span></p><p><strong class=\"ql-font-serif\">HR Department</strong></p><p><span class=\"ql-font-serif\">Human Resources Department</span></p><p><span class=\"ql-font-serif\">TechCorp International</span></p><p><br></p>', 'html', 'Permanent', 'Approved', '2026-07-23 15:57:28', '2026-07-23 15:57:38', 'Admin User', NULL, NULL, '2026-07-23 09:57:28', '2026-07-23 09:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`) VALUES
(1, 'Engineering', 'ENG'),
(2, 'Marketing', 'MKT'),
(3, 'Finance', 'FIN'),
(4, 'Human Resources', 'HR'),
(5, 'Design', 'DES'),
(6, 'Operations', 'OPS'),
(7, 'Sales', 'SAL'),
(8, 'IT', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `dept_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `code`, `dept_id`) VALUES
(1, 'Software Engineer', 'SE', 1),
(2, 'Senior Developer', 'SD', 1),
(3, 'Team Lead', 'TL', 1),
(4, 'Marketing Specialist', 'MS', 2),
(5, 'Financial Analyst', 'FA', 3),
(6, 'HR Executive', 'HRE', 4),
(7, 'UX Designer', 'UXD', 5),
(8, 'Operations Manager', 'OM', 6),
(9, 'Sales Representative', 'SR', 7),
(10, 'IT Support', 'ITS', 8);

-- --------------------------------------------------------

--
-- Table structure for table `interview_log`
--

CREATE TABLE `interview_log` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `round_name` varchar(100) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `interviewer` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'Waiting for Feedback',
  `score_technical` decimal(3,1) DEFAULT NULL,
  `score_communication` decimal(3,1) DEFAULT NULL,
  `score_problem_solving` decimal(3,1) DEFAULT NULL,
  `score_attitude` decimal(3,1) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `decision` varchar(20) DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `is_final_round` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interview_log`
--

INSERT INTO `interview_log` (`id`, `candidate_id`, `round`, `round_name`, `type`, `interviewer`, `date`, `status`, `score_technical`, `score_communication`, `score_problem_solving`, `score_attitude`, `rating`, `decision`, `reject_reason`, `feedback`, `is_final_round`, `created_at`, `updated_at`) VALUES
(12, 2, 1, 'Writter', 'In-Person', 'Interview 1', '2026-07-16 03:33:00', 'hold', 4.0, 3.0, 4.0, 3.0, 3.5, 'Hold', NULL, 'Good to go for practical interview', 0, '2026-07-16 10:54:55', '2026-07-16 10:58:20'),
(13, 2, 2, 'Practical', 'In-Person', 'Interviewer 2', '2026-07-16 03:33:00', 'Completed', 4.0, 4.0, 3.0, 3.0, 3.5, 'Pass', NULL, 'fsdfa', 1, '2026-07-16 10:56:13', '2026-07-16 11:01:51'),
(14, 1, 1, 'Written', 'In-Person', 'Interview 1', '2026-07-16 22:22:00', 'Rescheduled', 3.0, 3.0, 3.0, 3.0, 3.0, 'Fail', 'Lack of technical skills', 'dsfasdf', 0, '2026-07-16 11:55:34', '2026-07-16 12:03:16'),
(15, 1, 2, 'practical', 'In-Person', 'Interview 2', '2026-07-16 03:33:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'asdfa', 0, '2026-07-16 12:03:16', '2026-07-16 12:07:20'),
(16, 1, 3, 'sdfasdf a', 'Video', 'ffasdf', '2026-07-16 03:33:00', 'Completed', 2.0, 2.0, 2.0, 2.0, 2.0, 'Fail', 'Cultural fit', 'asdfasf', 0, '2026-07-16 12:07:45', '2026-07-16 12:08:01'),
(17, 7, 1, 'technical', 'In-Person', 'interview 1', '2026-07-19 03:33:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'good on this round', 0, '2026-07-19 04:33:16', '2026-07-19 04:35:50'),
(18, 7, 2, 'Management 3', 'In-Person', 'interviewer2', '2026-07-19 04:44:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Fail', 'Salary expectation mismatch', 'Not capable', 0, '2026-07-19 04:36:33', '2026-07-19 04:37:48'),
(19, 7, 3, 'Management again', 'In-Person', 'inteviewer 4', '2026-07-19 03:33:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'good to go', 1, '2026-07-19 04:38:32', '2026-07-19 04:39:20'),
(20, 5, 1, 'technical', 'In-Person', 'interviewer 2', '2026-07-19 03:33:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'fasdfasdf', 1, '2026-07-19 04:40:01', '2026-07-19 06:23:41'),
(21, 12, 1, 'fasdf', 'In-Person', 'fasdf sd', '2026-07-19 03:33:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'sdfasdf sd', 1, '2026-07-19 06:22:44', '2026-07-19 06:22:58'),
(22, 4, 1, 'asdfa', 'In-Person', 'asdfasd', '3333-03-31 03:03:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'asdfasdf', 1, '2026-07-19 06:24:20', '2026-07-19 06:24:35'),
(23, 6, 1, 'dfasdfasdf', 'In-Person', 'fasdfasdf', '2026-07-19 03:33:00', 'Completed', 1.0, 1.0, 1.0, 1.0, 1.0, 'Fail', NULL, 'sadfasdf', 1, '2026-07-19 06:24:50', '2026-07-19 06:25:01'),
(24, 1, 4, 'asdfasf', 'In-Person', 'interveiw', '2026-07-19 03:33:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'asdfasdf', 1, '2026-07-19 06:56:55', '2026-07-19 06:57:06'),
(25, 9, 1, 'asdfasd f', 'Video', 'dsfasfd', '2026-07-19 03:33:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'fasdf', 1, '2026-07-19 07:12:22', '2026-07-19 07:12:29'),
(26, 13, 1, 'writting', 'In-Person', 'in1', '2026-07-23 03:03:00', 'Completed', 2.0, 2.0, 2.0, 2.0, 2.0, 'Pass', NULL, 'fasdf', 0, '2026-07-23 08:17:16', '2026-07-23 08:18:52'),
(27, 14, 1, 'Reda', 'In-Person', 'inter 2', '2026-07-23 03:33:00', 'Completed', 4.0, 4.0, 3.0, 3.0, 3.5, 'Pass', NULL, 'asdf', 1, '2026-07-23 08:17:57', '2026-07-23 08:22:26'),
(28, 19, 1, 'fasdf e', 'In-Person', 'inter', '2026-07-23 03:03:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'dfgsd', 1, '2026-07-23 08:18:10', '2026-07-25 11:02:24'),
(29, 10, 1, 'casdc e', 'In-Person', 'inter', '2026-07-23 03:03:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'asdf', 1, '2026-07-23 08:18:26', '2026-07-23 08:22:09'),
(30, 18, 1, 'Round 1', 'In-Person', 'asdf', '2026-07-23 03:03:00', 'Completed', 1.0, 3.0, 3.0, 3.0, 2.5, 'Fail', 'Lack of technical skills', 'asdf', 0, '2026-07-23 08:18:39', '2026-07-23 08:20:18'),
(31, 13, 2, 'group', 'In-Person', 'interviewer 3', '2026-07-23 03:03:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'asdf', 0, '2026-07-23 08:19:22', '2026-07-23 08:19:44'),
(32, 6, 2, 'fsfd', 'Video', 'asdf as', '2026-07-23 03:03:00', 'Completed', 1.0, 1.0, 1.0, 1.0, 1.0, 'Fail', NULL, 'asdf', 1, '2026-07-23 08:20:45', '2026-07-23 08:21:35'),
(33, 6, 3, 'fsdf', 'In-Person', 'dfasdf', '2026-07-23 03:03:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'asdf', 1, '2026-07-23 08:22:38', '2026-07-23 08:58:01'),
(34, 20, 1, 'sdf s', 'In-Person', 'interview 2', '2026-07-23 03:03:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'fasdf', 1, '2026-07-23 08:57:21', '2026-07-23 08:57:54'),
(35, 15, 1, 'techincih', 'In-Person', 'Interview 4', '2026-07-23 03:03:00', 'Completed', 3.0, 3.0, 3.0, 3.0, 3.0, 'Pass', NULL, 'asdf asdf sa', 1, '2026-07-23 08:57:43', '2026-07-23 08:58:13'),
(36, 24, 1, 'gdfg', 'In-Person', 'dfgs', '2026-07-25 05:05:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'sdfg', 1, '2026-07-25 11:01:33', '2026-07-25 11:03:00'),
(37, 25, 1, 'gdfg', 'Technical', 'dsgf', '2026-07-25 04:34:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'sdfg', 1, '2026-07-25 11:01:44', '2026-07-25 11:02:40'),
(38, 27, 1, 'dfgdsg', 'Technical', 'gdsfg', '2026-07-25 04:44:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'gdg gsdf', 1, '2026-07-25 11:01:55', '2026-07-25 11:02:33'),
(39, 11, 1, 'gsdfg', 'Video', 'gsdfg', '2026-07-25 04:04:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'sdfg s', 1, '2026-07-25 11:02:07', '2026-07-25 11:02:53'),
(40, 16, 1, 'dgfsdfg', 'Technical', 'sdfgsdfg', '2026-07-25 04:44:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'fgsdfg', 1, '2026-07-25 11:03:29', '2026-07-25 11:03:40'),
(41, 26, 1, 'gsdfgsdg', 'In-Person', 'sdfg', '2026-07-25 04:04:00', 'Completed', 4.0, 4.0, 4.0, 4.0, 4.0, 'Pass', NULL, 'gsdfgsdfg', 1, '2026-07-25 11:04:03', '2026-07-25 11:04:10'),
(42, 29, 1, 'dsfgsdfgs', 'Technical', 'dgsd', '2026-07-25 04:04:00', 'Waiting for Feedback', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-07-25 11:04:21', '2026-07-25 11:04:21');

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

CREATE TABLE `letter_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'Offer',
  `content` longtext NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `letter_templates`
--

INSERT INTO `letter_templates` (`id`, `name`, `type`, `content`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Offer Letter', 'Offer', '<h1 style=\"text-align:center;color:#1a73e8;\">{{COMPANY_NAME}}</h1><p>\n</p><p style=\"text-align:center;color:#9aa0a6;font-size:13px;\">Employee Lifecycle Management</p><p>\n</p><p><br></p><p>\n</p><p style=\"text-align:right;color:#5f6368;font-size:13px;\">Date: {{DATE}}</p><p>\n</p><p>Dear <strong>{{CANDIDATE_NAME}}</strong>,</p><p>\n</p><h2 style=\"color:#1a73e8;border-bottom:2px solid #1a73e8;padding-bottom:6px;\">Offer of Employment</h2><p>\n</p><p>We are pleased to offer you the position of <strong>{{JOB_TITLE}}</strong> with <strong>{{COMPANY_NAME}}</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p>\n</p><h3 style=\"color:#3c4043;\">Position Details</h3><p>\n</p><p><strong>Position:</strong> {{DESIGNATION}}</p><p>\n</p><p><strong>Department:</strong> {{DEPARTMENT}}</p><p>\n</p><p><strong>Offered Salary:</strong> {{SALARY}}</p><p>\n</p><p><strong>Joining Date:</strong> {{JOINING_DATE}}</p><p>\n</p><p><strong>Probation Period:</strong> {{PROBATION_MONTHS}} months</p><p>\n</p><h3 style=\"color:#3c4043;\">Salary Breakdown</h3><p>\n{{SALARY_BREAKDOWN}}\n</p><h2 style=\"color:#1a73e8;border-bottom:2px solid #1a73e8;padding-bottom:6px;\">Acceptance</h2><p>\n</p><p>Please confirm your acceptance of this offer by <strong>{{RESPONSE_DATE}}</strong>. If you have any questions, please feel free to contact us.</p><p>\n\n</p><p>Best Regards,\n</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>{{COMPANY_NAME}}</strong>\n</p>', 'Active', '2026-07-19 07:04:38', '2026-07-22 10:45:24'),
(10, 'Confirmation Letter Template', 'Confirmation', '<div style=\"font-family:serif;line-height:1.8;max-width:700px;margin:0 auto;padding:24px\">\n    <div style=\"text-align:center;border-bottom:2px solid #000;padding-bottom:16px;margin-bottom:16px\">\n        <h2 style=\"margin:0\">{{COMPANY_NAME}}</h2>\n        <p style=\"color:#666;font-size:12px\">Employee Lifecycle Management</p>\n    </div>\n    <p>Date: {{DATE}}</p>\n    <p>Dear {{EMPLOYEE_NAME}},</p>\n    <p><strong>Subject: Confirmation of Employment</strong></p>\n    <p>We are pleased to inform you that, based on your performance during the probation period, your employment with {{COMPANY_NAME}} has been confirmed effective from <strong>{{CONFIRMATION_DATE}}</strong>.</p>\n    <p>You are being confirmed as <strong>{{DESIGNATION}}</strong> in the <strong>{{DEPARTMENT}}</strong> department.</p>\n    <table style=\"width:100%;border-collapse:collapse;margin:12px 0\">\n        <tr><td style=\"padding:4px 8px;font-weight:500;width:200px\">Employee Code:</td><td>{{EMPLOYEE_CODE}}</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Designation:</td><td>{{DESIGNATION}}</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Department:</td><td>{{DEPARTMENT}}</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Joining Date:</td><td>{{JOINING_DATE}}</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Probation Period:</td><td>{{PROBATION_MONTHS}} months</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Current Salary:</td><td>{{SALARY}}</td></tr>\n    </table>\n    {{SALARY_BREAKDOWN}}\n    <p>We appreciate your contribution and look forward to your continued success with the organization.</p>\n    <p>We wish you all the best in your future endeavors with us.</p>\n    <br>\n    <p>Best Regards,</p>\n    <p style=\"margin-top:32px\"><strong>{{HR_NAME}}</strong><br>Human Resources Department<br>{{COMPANY_NAME}}</p>\n</div>', 'Active', '2026-07-23 09:53:08', '2026-07-23 09:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `merge_tags`
--

CREATE TABLE `merge_tags` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'Offer',
  `tag` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merge_tags`
--

INSERT INTO `merge_tags` (`id`, `type`, `tag`, `label`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Offer', 'CANDIDATE_NAME', 'Candidate Name', 'Full name of the candidate', 1, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(2, 'Offer', 'DESIGNATION', 'Designation', 'Offered designation/title', 2, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(3, 'Offer', 'JOB_TITLE', 'Job Title', 'Title of the position', 3, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(4, 'Offer', 'DEPARTMENT', 'Department', 'Department name', 4, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(5, 'Offer', 'SALARY', 'Salary', 'Offered salary amount', 5, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(6, 'Offer', 'JOINING_DATE', 'Joining Date', 'Expected date of joining', 6, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(7, 'Offer', 'PROBATION_MONTHS', 'Probation Months', 'Probation period in months', 7, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(8, 'Offer', 'COMPANY_NAME', 'Company Name', 'Name of the organization', 8, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(9, 'Offer', 'HR_NAME', 'HR Name', 'HR representative name', 9, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(10, 'Offer', 'DATE', 'Current Date', 'Today\'s date', 10, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(11, 'Offer', 'RESPONSE_DATE', 'Response Deadline', 'Offer response deadline date', 11, '2026-07-19 05:07:39', '2026-07-19 05:07:39'),
(38, 'Offer', 'SALARY_BREAKDOWN', 'Salary Breakdown Table', 'Full salary breakdown rendered as an HTML table', 12, '2026-07-19 09:17:36', '2026-07-19 09:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `salary` decimal(12,2) NOT NULL,
  `joining_date` date NOT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `probation_months` int(11) DEFAULT 6,
  `notes` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `response_status` varchar(20) DEFAULT 'Pending',
  `responded_at` datetime DEFAULT NULL,
  `response_notes` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `accepted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `candidate_id`, `salary`, `joining_date`, `designation_id`, `dept_id`, `probation_months`, `notes`, `status`, `response_status`, `responded_at`, `response_notes`, `sent_at`, `accepted_at`, `created_at`, `updated_at`) VALUES
(5, 2, 77434.00, '2026-07-16', 1, 1, 6, 'efsdf', 'Approved', 'Accepted', '2026-07-16 17:03:32', '', '2026-07-25 17:08:24', NULL, '2026-07-16 11:02:44', '2026-07-25 11:08:24'),
(6, 12, 134389.00, '2026-08-01', 4, 2, 6, 'fsdfawef', 'Approved', 'Accepted', '2026-07-19 15:45:19', 'asdfdas', '2026-07-23 15:17:08', NULL, '2026-07-19 07:00:41', '2026-07-23 09:17:08'),
(7, 9, 100000.00, '2026-07-22', 4, 2, 6, 'kkk', 'Approved', 'Accepted', '2026-07-23 12:55:37', 'fasdf', '2026-07-23 14:25:06', NULL, '2026-07-22 08:30:50', '2026-07-23 08:25:06'),
(8, 1, 100000.00, '2026-07-22', 2, 1, 6, 'fasdf', 'Approved', 'Accepted', '2026-07-23 12:56:30', 'sdf', '2026-07-23 12:56:21', NULL, '2026-07-22 10:00:03', '2026-07-23 06:56:30'),
(9, 14, 1112.00, '2026-07-23', 4, 2, 6, 'sdf', 'Approved', 'Accepted', '2026-07-23 14:25:30', 'sdf', '2026-07-23 15:15:41', NULL, '2026-07-23 08:24:17', '2026-07-23 09:15:41'),
(10, 10, 100.00, '2026-07-23', 4, 2, 1, 'gsdfg', 'Approved', 'Accepted', '2026-07-25 17:12:07', 'asdf', '2026-07-23 15:14:11', NULL, '2026-07-23 09:06:56', '2026-07-25 11:12:07'),
(11, 4, 100.00, '2026-07-23', 2, 1, 2, 'sdf as', 'Approved', 'Accepted', '2026-07-23 15:27:45', 'fasdf', '2026-07-23 15:15:38', NULL, '2026-07-23 09:12:39', '2026-07-23 09:27:45'),
(12, 20, 100.00, '2026-07-23', 5, 3, 1, '', 'Approved', 'Accepted', '2026-07-25 17:12:04', 'fads', '2026-07-23 15:28:34', NULL, '2026-07-23 09:28:11', '2026-07-25 11:12:04'),
(13, 25, 2009.00, '2026-08-01', 6, 4, 1, 'sdf', 'Approved', 'Accepted', '2026-07-25 17:13:21', 'asdf', '2026-07-25 17:12:55', NULL, '2026-07-25 11:06:49', '2026-07-25 11:13:21'),
(14, 11, 1000.00, '2026-07-25', 4, 2, 1, 'sdfg', 'Approved', 'Accepted', '2026-07-25 17:09:15', '', '2026-07-25 17:08:48', NULL, '2026-07-25 11:07:08', '2026-07-25 11:09:15'),
(15, 7, 100.00, '2026-07-25', 2, 1, 3, 'sdfg', 'Approved', 'Accepted', '2026-07-25 17:12:28', 'sdf', '2026-07-25 17:12:18', NULL, '2026-07-25 11:07:22', '2026-07-25 11:12:28'),
(16, 16, 100.00, '2026-07-25', 4, 2, 4, 'fasd', 'Approved', 'Rejected', '2026-07-30 15:33:16', 'test rejected', '2026-07-25 17:14:04', NULL, '2026-07-25 11:13:09', '2026-07-30 09:33:16'),
(17, 6, 100.00, '2026-07-25', 2, 1, 3, 'dfasdf', 'Approved', 'Pending', NULL, NULL, '2026-07-25 17:13:40', NULL, '2026-07-25 11:13:40', '2026-07-25 11:13:40'),
(18, 15, 100.00, '2026-07-25', 4, 2, 3, 'sdfasf', 'Approved', 'Pending', NULL, NULL, '2026-07-25 17:14:29', NULL, '2026-07-25 11:14:29', '2026-07-25 11:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `offer_approvals`
--

CREATE TABLE `offer_approvals` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `approver` varchar(100) DEFAULT 'Admin User',
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer_approvals`
--

INSERT INTO `offer_approvals` (`id`, `offer_id`, `level`, `approver`, `status`, `comment`, `created_at`, `updated_at`) VALUES
(5, 5, 1, 'Admin User', 'Approved', '', '2026-07-16 11:02:44', '2026-07-16 11:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `offer_email_log`
--

CREATE TABLE `offer_email_log` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Sent',
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer_email_log`
--

INSERT INTO `offer_email_log` (`id`, `offer_id`, `recipient`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES
(5, 5, 'priya.malaker21@email.com', 'Offer of Employment - Priya Malaker', '<div style=\"font-family:serif;line-height:1.8;max-width:700px;margin:0 auto;padding:24px\">\n    <div style=\"text-align:center;border-bottom:2px solid #000;padding-bottom:16px;margin-bottom:16px\">\n        <h2 style=\"margin:0\">TechCorp International</h2>\n        <p style=\"color:#666;font-size:12px\">Employee Lifecycle Management</p>\n    </div>\n    <p>Date: July 16, 2026</p>\n    <p>Dear Priya Malaker,</p>\n    <p><strong>Sub: Offer of Employment</strong></p>\n    <p>We are pleased to offer you the position of <strong>Software Engineer</strong> with TechCorp International. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p>\n    <p>Your offer details are as follows:</p>\n    <table style=\"width:100%;border-collapse:collapse;margin:12px 0\">\n        <tr><td style=\"padding:4px 8px;font-weight:500;width:200px\">Position:</td><td>Software Engineer</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Department:</td><td>Engineering</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Offered Salary:</td><td>৳77434.00</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Joining Date:</td><td>July 16, 2026</td></tr>\n        <tr><td style=\"padding:4px 8px;font-weight:500\">Probation Period:</td><td>6 months</td></tr>\n    </table>\n    <p>Please confirm your acceptance of this offer by July 30, 2026. If you have any questions, please feel free to contact us.</p>\n    <br>\n    <p>Best Regards,</p>\n    <p style=\"margin-top:32px\"><strong>Admin User</strong><br>Human Resources Manager<br>TechCorp International</p>\n</div>', '2026-07-16 17:03:20', 'Sent', NULL),
(6, 6, 'tahmina.mollah75@email.com', 'Offer Letter - Digital Marketing Manager', 'sdf', '2026-07-19 15:44:52', 'Sent', NULL),
(7, 8, 'rahul.sikder11@email.com', 'Offer Letter - Senior Software Engineer', 'fsdf', '2026-07-23 12:56:21', 'Sent', NULL),
(8, 7, 'kazi.poddar37@email.com', 'Offer Letter - Digital Marketing Manager', '', '2026-07-23 14:25:06', 'Sent', NULL),
(9, 10, 'sharmin.haque97@email.com', 'Offer Letter - Digital Marketing Manager', 'fasdf', '2026-07-23 15:14:11', 'Sent', NULL),
(10, 11, 'sadia.haque15@email.com', 'Offer Letter - Senior Software Engineer', 'fasd', '2026-07-23 15:15:38', 'Sent', NULL),
(11, 9, 'sultana.rahman92@email.com', 'Offer Letter - Content Writer', 'fasdf', '2026-07-23 15:15:41', 'Sent', NULL),
(12, 6, 'tahmina.mollah75@email.com', 'Offer Letter - Digital Marketing Manager', 'adfa', '2026-07-23 15:17:08', 'Sent', NULL),
(13, 12, 'nazma.sarker97@email.com', 'Offer Letter - Financial Analyst', 'sdf', '2026-07-23 15:28:34', 'Sent', NULL),
(14, 5, 'priya.malaker21@email.com', 'Offer Letter - Senior Software Engineer', 'sadf', '2026-07-25 17:08:24', 'Sent', NULL),
(15, 14, 'mehedi.dhar26@email.com', 'Offer Letter - Digital Marketing Manager', 'asdf', '2026-07-25 17:08:48', 'Sent', NULL),
(16, 15, 'hasan.poddar82@email.com', 'Offer Letter - Junior Software Developer', '', '2026-07-25 17:12:18', 'Sent', NULL),
(17, 13, 'emon.sikder77@email.com', 'Offer Letter - HR Manager', 'sadf', '2026-07-25 17:12:55', 'Sent', NULL),
(18, 16, 'nasrin.sikder27@email.com', 'Offer Letter - Content Writer', 'sdfsaf', '2026-07-25 17:14:04', 'Sent', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `offer_letters`
--

CREATE TABLE `offer_letters` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `letter_content` longtext DEFAULT NULL,
  `letter_format` varchar(10) DEFAULT 'html',
  `generated_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `sent_to_email` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer_letters`
--

INSERT INTO `offer_letters` (`id`, `offer_id`, `letter_content`, `letter_format`, `generated_at`, `sent_at`, `sent_to_email`, `status`, `created_at`, `updated_at`) VALUES
(6, 8, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 22, 2026</span></p><p>Dear <strong>Rahul Sikder</strong>,</p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p>We are pleased to offer you the position of <strong>Senior Software Engineer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><strong>Position:</strong> Senior Developer</p><p><strong>Department:</strong> Engineering</p><p><strong>Offered Salary:</strong> 100,000.00 BDT</p><p><strong>Joining Date:</strong> July 22, 2026</p><p><strong>Probation Period:</strong> 6 months</p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100,000.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57,142.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">28,571.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">5,714.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">8,571.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100,000.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(8,750.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">91,250.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p>Please confirm your acceptance of this offer by <strong>August 5, 2026</strong>. If you have any questions, please feel free to contact us.</p><blockquote><span style=\"color: rgb(95, 99, 104); background-color: rgb(241, 243, 244);\">Best Regards,</span></blockquote><blockquote><strong style=\"color: rgb(95, 99, 104); background-color: rgb(241, 243, 244);\">Admin User</strong></blockquote><blockquote><span style=\"color: rgb(95, 99, 104); background-color: rgb(241, 243, 244);\">Human Resources Manager</span></blockquote><blockquote><strong style=\"color: rgb(95, 99, 104); background-color: rgb(241, 243, 244);\">TechCorp International</strong></blockquote><p><br></p>', 'html', '2026-07-22 16:29:51', '2026-07-23 12:56:21', 'rahul.sikder11@email.com', 'Sent', '2026-07-22 10:29:51', '2026-07-23 06:56:21'),
(7, 7, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Kazi Poddar</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Digital Marketing Manager</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 100,000.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 22, 2026</p><p><br></p><p><strong>Probation Period:</strong> 6 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100,000.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57,142.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">28,571.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">5,714.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">8,571.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100,000.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(8,750.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">91,250.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 14:24:56', '2026-07-23 14:25:06', 'kazi.poddar37@email.com', 'Sent', '2026-07-23 08:24:56', '2026-07-23 08:25:06'),
(8, 10, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Sharmin Haque</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Digital Marketing Manager</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 23, 2026</p><p><br></p><p><strong>Probation Period:</strong> 1 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 15:13:57', '2026-07-23 15:14:11', 'sharmin.haque97@email.com', 'Sent', '2026-07-23 09:13:57', '2026-07-23 09:14:11'),
(9, 11, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Sadia Haque</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Senior Software Engineer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Senior Developer</p><p><br></p><p><strong>Department:</strong> Engineering</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 23, 2026</p><p><br></p><p><strong>Probation Period:</strong> 2 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 15:14:19', '2026-07-23 15:15:38', 'sadia.haque15@email.com', 'Sent', '2026-07-23 09:14:19', '2026-07-23 09:15:38'),
(10, 9, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Sultana Rahman</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Content Writer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 1,112.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 23, 2026</p><p><br></p><p><strong>Probation Period:</strong> 6 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">1,112.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">635.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">318.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">64.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">95.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">1,112.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">1,112.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 15:15:08', '2026-07-23 15:15:41', 'sultana.rahman92@email.com', 'Sent', '2026-07-23 09:15:08', '2026-07-23 09:15:41'),
(11, 6, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Tahmina Mollah</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Digital Marketing Manager</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 134,389.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> August 1, 2026</p><p><br></p><p><strong>Probation Period:</strong> 6 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 15:16:49', '2026-07-23 15:17:08', 'tahmina.mollah75@email.com', 'Sent', '2026-07-23 09:16:49', '2026-07-23 09:17:08'),
(12, 12, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 23, 2026</span></p><p><br></p><p>Dear <strong>Nazma Sarker</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Financial Analyst</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Financial Analyst</p><p><br></p><p><strong>Department:</strong> Finance</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 23, 2026</p><p><br></p><p><strong>Probation Period:</strong> 1 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\">Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 6, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-23 15:28:26', '2026-07-23 15:28:34', 'nazma.sarker97@email.com', 'Sent', '2026-07-23 09:28:21', '2026-07-23 09:28:34'),
(13, 5, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Priya Malaker</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Senior Software Engineer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Software Engineer</p><p><br></p><p><strong>Department:</strong> Engineering</p><p><br></p><p><strong>Offered Salary:</strong> 77,434.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 16, 2026</p><p><br></p><p><strong>Probation Period:</strong> 6 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:08:10', '2026-07-25 17:08:24', 'priya.malaker21@email.com', 'Sent', '2026-07-25 11:08:10', '2026-07-25 11:08:24'),
(14, 14, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Mehedi Dhar</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Digital Marketing Manager</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 1,000.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 25, 2026</p><p><br></p><p><strong>Probation Period:</strong> 1 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">1,000.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">571.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">286.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">86.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">1,000.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">1,000.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:08:41', '2026-07-25 17:08:48', 'mehedi.dhar26@email.com', 'Sent', '2026-07-25 11:08:41', '2026-07-25 11:08:48'),
(15, 15, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Hasan Poddar</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Junior Software Developer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Senior Developer</p><p><br></p><p><strong>Department:</strong> Engineering</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 25, 2026</p><p><br></p><p><strong>Probation Period:</strong> 3 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:12:14', '2026-07-25 17:12:18', 'hasan.poddar82@email.com', 'Sent', '2026-07-25 11:12:14', '2026-07-25 11:12:18'),
(16, 13, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Emon Sikder</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>HR Manager</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> HR Executive</p><p><br></p><p><strong>Department:</strong> Human Resources</p><p><br></p><p><strong>Offered Salary:</strong> 2,009.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> August 1, 2026</p><p><br></p><p><strong>Probation Period:</strong> 1 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">2,009.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">1,148.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">574.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">115.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">172.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">2,009.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">2,009.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:12:40', '2026-07-25 17:12:55', 'emon.sikder77@email.com', 'Sent', '2026-07-25 11:12:40', '2026-07-25 11:12:55'),
(17, 16, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Nasrin Sikder</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Content Writer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Marketing Specialist</p><p><br></p><p><strong>Department:</strong> Marketing</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 25, 2026</p><p><br></p><p><strong>Probation Period:</strong> 4 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:13:57', '2026-07-25 17:14:04', 'nasrin.sikder27@email.com', 'Sent', '2026-07-25 11:13:57', '2026-07-25 11:14:04'),
(18, 17, '<h1 class=\"ql-align-center\"><span style=\"color: rgb(26, 115, 232);\">TechCorp International</span></h1><p><br></p><p class=\"ql-align-center\"><span style=\"color: rgb(154, 160, 166);\">Employee Lifecycle Management</span></p><p><br></p><p><br></p><p><br></p><p class=\"ql-align-right\"><span style=\"color: rgb(95, 99, 104);\">Date: July 25, 2026</span></p><p><br></p><p>Dear <strong>Nusrat Nath</strong>,</p><p><br></p><h2><span style=\"color: rgb(26, 115, 232);\">Offer of Employment</span></h2><p><br></p><p>We are pleased to offer you the position of <strong>Junior Software Developer</strong> with <strong>TechCorp International</strong>. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Position Details</span></h3><p><br></p><p><strong>Position:</strong> Senior Developer</p><p><br></p><p><strong>Department:</strong> Engineering</p><p><br></p><p><strong>Offered Salary:</strong> 100.00 BDT</p><p><br></p><p><strong>Joining Date:</strong> July 25, 2026</p><p><br></p><p><strong>Probation Period:</strong> 3 months</p><p><br></p><h3><span style=\"color: rgb(60, 64, 67);\">Salary Breakdown</span></h3><table><tbody><tr><td data-row=\"1\"><span style=\"background-color: rgb(245, 245, 245);\">Component Amount (BDT)</span></td></tr><tr><td data-row=\"2\"> Gross Salary</td><td data-row=\"2\" class=\"ql-align-right\">100.00</td></tr><tr><td data-row=\"3\">Basic</td><td data-row=\"3\" class=\"ql-align-right\">57.00</td></tr><tr><td data-row=\"4\">House Rent</td><td data-row=\"4\" class=\"ql-align-right\">29.00</td></tr><tr><td data-row=\"5\">Medical Allowance</td><td data-row=\"5\" class=\"ql-align-right\">6.00</td></tr><tr><td data-row=\"6\">Conveyance Allowance</td><td data-row=\"6\" class=\"ql-align-right\">9.00</td></tr><tr><td data-row=\"7\"><span style=\"background-color: rgb(249, 249, 249);\">Total Salary</span></td><td data-row=\"7\" class=\"ql-align-right\"><span style=\"background-color: rgb(249, 249, 249);\">100.00</span></td></tr><tr><td data-row=\"8\">Personal PF</td><td data-row=\"8\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"9\">PF Deductible</td><td data-row=\"9\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"10\">Monthly Income Tax</td><td data-row=\"10\" class=\"ql-align-right\">(0.00)</td></tr><tr><td data-row=\"11\"><span style=\"background-color: rgb(240, 247, 255);\">Total Payable</span></td><td data-row=\"11\" class=\"ql-align-right\"><span style=\"background-color: rgb(240, 247, 255);\">100.00</span></td></tr></tbody></table><h2><span style=\"color: rgb(26, 115, 232);\">Acceptance</span></h2><p><br></p><p>Please confirm your acceptance of this offer by <strong>August 8, 2026</strong>. If you have any questions, please feel free to contact us.</p><p><br></p><p>Best Regards,</p><p><strong>Admin User</strong></p><p>Human Resources Manager</p><p><strong>TechCorp International</strong></p>', 'html', '2026-07-25 17:14:17', NULL, NULL, 'Generated', '2026-07-25 11:14:17', '2026-07-25 11:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_basic_info`
--

CREATE TABLE `personnel_basic_info` (
  `PBI_ID` bigint(20) NOT NULL,
  `PBI_CODE` varchar(100) NOT NULL,
  `MACHINE_ID` varchar(50) NOT NULL DEFAULT '',
  `PBI_DUE_DOJ` date NOT NULL,
  `PBI_NAME` varchar(50) DEFAULT NULL,
  `PBI_FATHER_NAME` varchar(50) DEFAULT NULL,
  `PBI_MOTHER_NAME` varchar(50) DEFAULT NULL,
  `DEPT_ID` int(10) DEFAULT NULL,
  `PBI_DEPARTMENT` int(10) DEFAULT NULL,
  `LEAVE_RULE_ID` int(11) NOT NULL DEFAULT 0,
  `nid` bigint(20) DEFAULT NULL,
  `employee_type` varchar(20) NOT NULL DEFAULT 'Non Roster',
  `DESG_ID` int(10) DEFAULT NULL,
  `PBI_DESIGNATION` varchar(50) DEFAULT NULL,
  `PBI_ZONE` varchar(50) DEFAULT NULL,
  `PBI_AREA` varchar(50) DEFAULT NULL,
  `PBI_BRANCH` varchar(50) DEFAULT NULL,
  `PBI_REGION` int(11) NOT NULL DEFAULT 0,
  `PBI_DOJ` date DEFAULT NULL,
  `PBI_SERVICE_LENGTH` varchar(50) DEFAULT NULL,
  `PBI_DOC` varchar(50) DEFAULT NULL,
  `PBI_DOB` date DEFAULT NULL,
  `PBI_SEX` varchar(10) DEFAULT NULL,
  `PBI_MARITAL_STA` varchar(12) DEFAULT NULL,
  `PBI_RELIGION` varchar(12) DEFAULT NULL,
  `PBI_NATIONALITY` varchar(50) DEFAULT NULL,
  `PBI_PERMANENT_ADD` varchar(250) DEFAULT NULL,
  `PBI_PRESENT_ADD` varchar(250) DEFAULT NULL,
  `PBI_PHONE` varchar(50) DEFAULT NULL,
  `PBI_MOBILE` varchar(50) DEFAULT NULL,
  `PBI_EMAIL` varchar(50) DEFAULT NULL,
  `PBI_JOB_STATUS` varchar(50) DEFAULT NULL,
  `PBI_DOC2` date DEFAULT NULL,
  `resign_date` date DEFAULT NULL,
  `PBI_BLOOD_GROUP` varchar(32) NOT NULL DEFAULT '',
  `PBI_CON_TYPE` varchar(100) NOT NULL DEFAULT '',
  `PBI_ORG` int(11) NOT NULL DEFAULT 1,
  `incharge_id` int(11) NOT NULL DEFAULT 0,
  `PBI_EMAIL_ALT` varchar(80) NOT NULL DEFAULT '',
  `EMPLOYMENT_TYPE` varchar(70) NOT NULL DEFAULT '',
  `define_schedule` varchar(100) NOT NULL DEFAULT '',
  `resign_type` varchar(255) NOT NULL DEFAULT '',
  `note` text DEFAULT NULL,
  `PBI_PICTURE_ATT_PATH` text DEFAULT NULL,
  `PBI_NID_ATT_PATH` text DEFAULT NULL,
  `JOB_LOC_ID` int(11) NOT NULL DEFAULT 0,
  `PBI_TIN_ATT_PATH` text DEFAULT NULL,
  `PBI_PASSPORT_ATT_PATH` text DEFAULT NULL,
  `pre_block_no` varchar(100) NOT NULL DEFAULT '',
  `pre_ps` varchar(100) NOT NULL DEFAULT '',
  `pre_house_no` varchar(100) NOT NULL DEFAULT '',
  `pre_flat` varchar(100) NOT NULL DEFAULT '',
  `pre_road_no` varchar(100) NOT NULL DEFAULT '',
  `pre_district` varchar(100) NOT NULL DEFAULT '',
  `PBI_CV_ATT_PATH` varchar(100) NOT NULL DEFAULT '',
  `PBI_OTHER_ATT_PATH` varchar(100) NOT NULL DEFAULT '',
  `PBI_DURATION` varchar(11) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `cell_no_traking` varchar(100) NOT NULL DEFAULT '',
  `section` varchar(100) NOT NULL DEFAULT '',
  `cost_center` varchar(100) NOT NULL DEFAULT '',
  `PBI_FUNCTION` varchar(100) NOT NULL DEFAULT '',
  `job_title` varchar(100) NOT NULL DEFAULT '',
  `grade` int(11) NOT NULL DEFAULT 0,
  `job_description` varchar(100) NOT NULL DEFAULT '',
  `class` varchar(100) NOT NULL DEFAULT '',
  `level` varchar(100) NOT NULL DEFAULT '',
  `cost_type` varchar(100) NOT NULL DEFAULT '',
  `age_on_join` varchar(100) NOT NULL DEFAULT '',
  `PBI_WORK_STATION` varchar(100) NOT NULL DEFAULT '',
  `joining_designation` varchar(100) NOT NULL DEFAULT '',
  `incharge_id_2` int(11) NOT NULL DEFAULT 0,
  `Friday` varchar(100) NOT NULL DEFAULT '',
  `Saturday` varchar(100) NOT NULL DEFAULT '',
  `Sunday` varchar(100) NOT NULL DEFAULT '',
  `Monday` varchar(100) NOT NULL DEFAULT '',
  `Tuesday` varchar(100) NOT NULL DEFAULT '',
  `Wednesday` varchar(100) NOT NULL DEFAULT '',
  `Thursday` varchar(100) NOT NULL DEFAULT '',
  `emp_deletion_reason` varchar(100) NOT NULL DEFAULT '',
  `emp_date_deleted_at` varchar(100) NOT NULL DEFAULT '',
  `payroll_designation` varchar(255) NOT NULL DEFAULT '',
  `spouse_name` varchar(100) NOT NULL DEFAULT '',
  `highte` varchar(100) NOT NULL DEFAULT '',
  `weight` varchar(100) NOT NULL DEFAULT '',
  `birth_reg_no` varchar(100) NOT NULL DEFAULT '',
  `child1_details` varchar(100) NOT NULL DEFAULT '',
  `hobbies` varchar(100) NOT NULL DEFAULT '',
  `nominee` varchar(100) NOT NULL DEFAULT '',
  `nominee_relation` varchar(100) NOT NULL DEFAULT '',
  `child1_name` varchar(100) NOT NULL DEFAULT '',
  `child1_dob` varchar(100) NOT NULL DEFAULT '',
  `child2_name` varchar(255) NOT NULL DEFAULT '',
  `child2_dob` varchar(255) NOT NULL DEFAULT '',
  `child2_details` varchar(255) NOT NULL DEFAULT '',
  `child3_name` varchar(255) NOT NULL DEFAULT '',
  `child3_dob` varchar(255) NOT NULL DEFAULT '',
  `child3_details` varchar(255) NOT NULL DEFAULT '',
  `child4_name` varchar(255) NOT NULL DEFAULT '',
  `child4_dob` varchar(255) NOT NULL DEFAULT '',
  `child4_details` varchar(255) NOT NULL DEFAULT '',
  `salary_schedule` int(11) NOT NULL DEFAULT 0,
  `line` varchar(50) NOT NULL DEFAULT '',
  `schedule_type` enum('Regular','Roster') NOT NULL DEFAULT 'Regular',
  `punch_type` enum('Regular','Single','No Punch') NOT NULL DEFAULT 'Regular',
  `entry_by` int(11) NOT NULL DEFAULT 0,
  `edit_by` int(11) NOT NULL DEFAULT 0,
  `territory` int(11) NOT NULL DEFAULT 0,
  `candidate_id` int(11) DEFAULT NULL,
  `offer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `personnel_basic_info`
--

INSERT INTO `personnel_basic_info` (`PBI_ID`, `PBI_CODE`, `MACHINE_ID`, `PBI_DUE_DOJ`, `PBI_NAME`, `PBI_FATHER_NAME`, `PBI_MOTHER_NAME`, `DEPT_ID`, `PBI_DEPARTMENT`, `LEAVE_RULE_ID`, `nid`, `employee_type`, `DESG_ID`, `PBI_DESIGNATION`, `PBI_ZONE`, `PBI_AREA`, `PBI_BRANCH`, `PBI_REGION`, `PBI_DOJ`, `PBI_SERVICE_LENGTH`, `PBI_DOC`, `PBI_DOB`, `PBI_SEX`, `PBI_MARITAL_STA`, `PBI_RELIGION`, `PBI_NATIONALITY`, `PBI_PERMANENT_ADD`, `PBI_PRESENT_ADD`, `PBI_PHONE`, `PBI_MOBILE`, `PBI_EMAIL`, `PBI_JOB_STATUS`, `PBI_DOC2`, `resign_date`, `PBI_BLOOD_GROUP`, `PBI_CON_TYPE`, `PBI_ORG`, `incharge_id`, `PBI_EMAIL_ALT`, `EMPLOYMENT_TYPE`, `define_schedule`, `resign_type`, `note`, `PBI_PICTURE_ATT_PATH`, `PBI_NID_ATT_PATH`, `JOB_LOC_ID`, `PBI_TIN_ATT_PATH`, `PBI_PASSPORT_ATT_PATH`, `pre_block_no`, `pre_ps`, `pre_house_no`, `pre_flat`, `pre_road_no`, `pre_district`, `PBI_CV_ATT_PATH`, `PBI_OTHER_ATT_PATH`, `PBI_DURATION`, `last_name`, `cell_no_traking`, `section`, `cost_center`, `PBI_FUNCTION`, `job_title`, `grade`, `job_description`, `class`, `level`, `cost_type`, `age_on_join`, `PBI_WORK_STATION`, `joining_designation`, `incharge_id_2`, `Friday`, `Saturday`, `Sunday`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `emp_deletion_reason`, `emp_date_deleted_at`, `payroll_designation`, `spouse_name`, `highte`, `weight`, `birth_reg_no`, `child1_details`, `hobbies`, `nominee`, `nominee_relation`, `child1_name`, `child1_dob`, `child2_name`, `child2_dob`, `child2_details`, `child3_name`, `child3_dob`, `child3_details`, `child4_name`, `child4_dob`, `child4_details`, `salary_schedule`, `line`, `schedule_type`, `punch_type`, `entry_by`, `edit_by`, `territory`, `candidate_id`, `offer_id`) VALUES
(1, 'EMP-00001', '', '2026-07-16', 'Priya', NULL, NULL, 1, NULL, 0, NULL, 'Non Roster', 1, 'Software Engineer', NULL, NULL, NULL, 0, '2026-07-16', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801733800210', '+8801733800210', 'priya.malaker21@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Malaker', '', '', '', '', 'Senior Software Engineer', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 2, 5),
(2, 'EMP-00002', '', '2026-08-01', 'Tahmina', NULL, NULL, 2, NULL, 0, 65555555, 'Roster', 4, 'Marketing Specialist', 'fsdf', 'sdf', 'fasf', 0, '2026-08-01', NULL, NULL, '2002-07-23', 'Female', 'Single', 'Islam', 'Bangladeshi', 'afds', 'asdfas', '+8801750673848', '+8801750673848', 'tahmina.mollah75@email.com', NULL, NULL, NULL, 'A+', '', 1, 0, 'tahmina.mollah75@email.com', 'Full-Time', '', '', 'fsdfsd', NULL, NULL, 0, NULL, NULL, '3434', 'fsdf', 'fasdf', 'sdfa', '34343', 'sf', '', '', '', 'Mollah', '', 'sfdasdfas', 'asdfasdfa', 'dffas', 'Digital Marketing Manager', 0, '', 'sdf', 'fasdf', 'fasdf', '', 'fasdf', '', 0, 'Off', '', '', '', '', '', '', '', '', '', 'fsdf', '5\'8\"', '70', '343434', 'fsadf', 'Football', 'dfasd', 'asdfa', 'fsdf', '3423', 'sdf', '34', 'fsdf', '', '', '', '', '', '', 0, 'fasf', 'Roster', 'Single', 1, 0, 0, 12, 6),
(3, 'EMP-00003', '', '2026-07-22', 'Kazi', NULL, NULL, 2, NULL, 0, NULL, 'Non Roster', 4, 'Marketing Specialist', NULL, NULL, NULL, 0, '2026-07-22', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801758748880', '+8801758748880', 'kazi.poddar37@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Full-Time', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Poddar', '', '', '', '', 'Digital Marketing Manager', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 9, 7),
(4, 'EMP-00004', '', '2026-07-22', 'Rahul', NULL, NULL, 1, NULL, 0, NULL, 'Non Roster', 2, 'Senior Developer', NULL, NULL, NULL, 0, '2026-07-22', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801771635154', '+8801771635154', 'rahul.sikder11@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Full-Time', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Sikder', '', '', '', '', 'Senior Software Engineer', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 1, 8),
(5, 'EMP-00005', '', '2026-07-23', 'Sultana', NULL, NULL, 2, NULL, 0, NULL, 'Non Roster', 4, 'Marketing Specialist', NULL, NULL, NULL, 0, '2026-07-23', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801765498982', '+8801765498982', 'sultana.rahman92@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Rahman', '', '', '', '', 'Content Writer', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 14, 9),
(6, 'EMP-00006', '', '2026-07-23', 'Sadia', NULL, NULL, 1, NULL, 0, NULL, 'Non Roster', 2, 'Senior Developer', NULL, NULL, NULL, 0, '2026-07-23', NULL, NULL, '2026-08-01', 'Male', 'Single', 'Islam', 'Bangladeshi', NULL, NULL, '+8801761140266', '+8801761140266', 'sadia.haque15@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Haque', '', '', '', '', 'Senior Software Engineer', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 4, 11),
(7, 'EMP-00007', '', '2026-07-23', 'Nazma', NULL, NULL, 3, NULL, 0, NULL, 'Non Roster', 5, 'Financial Analyst', NULL, NULL, NULL, 0, '2026-07-23', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801765518436', '+8801765518436', 'nazma.sarker97@email.com', NULL, NULL, NULL, '', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Sarker', '', '', '', '', 'Financial Analyst', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 20, 12),
(8, 'EMP-00008', '', '2026-08-01', 'Emon', NULL, NULL, 4, NULL, 0, NULL, 'Non Roster', 6, 'HR Executive', NULL, NULL, NULL, 0, '2026-08-01', NULL, NULL, NULL, NULL, NULL, NULL, 'Bangladeshi', NULL, NULL, '+8801730507723', '+8801730507723', 'emon.sikder77@email.com', NULL, NULL, NULL, 'A+', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Sikder', '', '', '', '', 'HR Manager', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 25, 13),
(9, 'EMP-00009', '', '2026-07-23', 'Sharmin', NULL, NULL, 2, NULL, 0, NULL, 'Non Roster', 4, 'Marketing Specialist', NULL, NULL, NULL, 0, '2026-07-23', NULL, NULL, NULL, NULL, 'Single', NULL, 'Bangladeshi', NULL, NULL, '+8801766021429', '+8801766021429', 'sharmin.haque97@email.com', NULL, NULL, NULL, 'A+', '', 1, 0, '', 'Probationer', '', '', NULL, NULL, NULL, 0, NULL, NULL, '', '', '', '', '', '', '', '', '', 'Haque', '', '', '', '', 'Digital Marketing Manager', 0, '', '', '', '', '', '', '', 0, 'Off', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', 'Regular', 'Regular', 1, 0, 0, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `probation_appraisals`
--

CREATE TABLE `probation_appraisals` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `joining_date` date DEFAULT NULL,
  `probation_months` int(11) NOT NULL DEFAULT 6,
  `end_date` date DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `overall_score` decimal(5,2) DEFAULT NULL,
  `recommendation` varchar(30) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `supervisor_comments` text DEFAULT NULL,
  `dept_head_comments` text DEFAULT NULL,
  `hr_review` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT 'Admin User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `probation_appraisals`
--

INSERT INTO `probation_appraisals` (`id`, `employee_id`, `joining_date`, `probation_months`, `end_date`, `review_date`, `status`, `overall_score`, `recommendation`, `remarks`, `supervisor_comments`, `dept_head_comments`, `hr_review`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 2, '2026-07-16', 6, '2027-01-16', NULL, 'Completed', 3.94, 'Confirm', NULL, 'sdf', 'sdf', 'fasf', 'Admin User', '2026-07-16 11:04:23', '2026-07-19 10:36:46'),
(8, 1, '2026-07-16', 6, '2027-01-16', NULL, 'Completed', 3.72, 'Confirm', NULL, 'fsad', 'adsf', 'asdf', 'Admin User', '2026-07-23 09:30:45', '2026-07-23 09:56:55'),
(9, 7, '2026-07-23', 1, '2026-08-23', NULL, 'Pending', 4.55, 'Confirm', NULL, NULL, NULL, NULL, 'Admin User', '2026-07-25 11:18:04', '2026-07-25 11:18:04'),
(10, 6, '2026-07-23', 2, '2026-09-23', NULL, 'Completed', 5.00, 'Confirm', NULL, NULL, NULL, NULL, 'Admin User', '2026-07-26 05:34:58', '2026-07-26 05:35:25'),
(11, 5, '2026-07-23', 6, '2027-01-23', NULL, 'Pending', 1.00, 'Extend', NULL, NULL, NULL, NULL, 'Admin User', '2026-07-30 09:36:53', '2026-07-30 09:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `probation_categories`
--

CREATE TABLE `probation_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `probation_categories`
--

INSERT INTO `probation_categories` (`id`, `name`, `weight`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Attendance', 15.00, 1, 'Active', '2026-07-13 12:07:58'),
(2, 'Discipline', 10.00, 2, 'Active', '2026-07-13 12:07:58'),
(3, 'Job Knowledge', 15.00, 3, 'Active', '2026-07-13 12:07:58'),
(4, 'Productivity', 15.00, 4, 'Active', '2026-07-13 12:07:58'),
(5, 'Quality of Work', 15.00, 5, 'Active', '2026-07-13 12:07:58'),
(6, 'Communication', 10.00, 6, 'Active', '2026-07-13 12:07:58'),
(7, 'Leadership', 5.00, 7, 'Active', '2026-07-13 12:07:58'),
(8, 'Initiative', 5.00, 8, 'Active', '2026-07-13 12:07:58'),
(9, 'Behavior', 5.00, 9, 'Active', '2026-07-13 12:07:58'),
(10, 'Teamwork', 5.00, 10, 'Active', '2026-07-13 12:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `probation_scores`
--

CREATE TABLE `probation_scores` (
  `id` int(11) NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `probation_scores`
--

INSERT INTO `probation_scores` (`id`, `appraisal_id`, `category_id`, `score`, `comments`, `created_at`) VALUES
(118, 6, 1, 4.00, NULL, '2026-07-16 11:04:23'),
(119, 6, 1, 4.00, NULL, '2026-07-16 11:04:23'),
(120, 6, 1, 4.00, NULL, '2026-07-16 11:04:23'),
(121, 6, 2, 5.00, NULL, '2026-07-16 11:04:23'),
(122, 6, 2, 3.00, NULL, '2026-07-16 11:04:23'),
(123, 6, 2, 5.00, NULL, '2026-07-16 11:04:23'),
(124, 6, 3, 3.00, NULL, '2026-07-16 11:04:23'),
(125, 6, 3, 3.00, NULL, '2026-07-16 11:04:23'),
(126, 6, 3, 5.00, NULL, '2026-07-16 11:04:23'),
(127, 6, 4, 5.00, NULL, '2026-07-16 11:04:23'),
(128, 6, 4, 2.00, NULL, '2026-07-16 11:04:23'),
(129, 6, 5, 4.00, NULL, '2026-07-16 11:04:23'),
(130, 6, 5, 5.00, NULL, '2026-07-16 11:04:23'),
(131, 6, 5, 5.00, NULL, '2026-07-16 11:04:23'),
(132, 6, 6, 2.00, NULL, '2026-07-16 11:04:23'),
(133, 6, 6, 5.00, NULL, '2026-07-16 11:04:23'),
(134, 6, 6, 5.00, NULL, '2026-07-16 11:04:23'),
(135, 6, 7, 3.00, NULL, '2026-07-16 11:04:23'),
(136, 6, 7, 3.00, NULL, '2026-07-16 11:04:23'),
(137, 6, 7, 5.00, NULL, '2026-07-16 11:04:23'),
(138, 6, 8, 5.00, NULL, '2026-07-16 11:04:23'),
(139, 6, 8, 4.00, NULL, '2026-07-16 11:04:23'),
(140, 6, 8, 3.00, NULL, '2026-07-16 11:04:23'),
(141, 6, 9, 2.00, NULL, '2026-07-16 11:04:23'),
(142, 6, 9, 5.00, NULL, '2026-07-16 11:04:23'),
(143, 6, 9, 5.00, NULL, '2026-07-16 11:04:23'),
(144, 6, 10, 1.00, NULL, '2026-07-16 11:04:23'),
(145, 6, 10, 3.00, NULL, '2026-07-16 11:04:23'),
(146, 6, 10, 5.00, NULL, '2026-07-16 11:04:23'),
(177, 8, 1, 2.00, NULL, '2026-07-23 09:30:45'),
(178, 8, 1, 4.00, NULL, '2026-07-23 09:30:45'),
(179, 8, 1, 4.00, NULL, '2026-07-23 09:30:45'),
(180, 8, 2, 2.00, NULL, '2026-07-23 09:30:45'),
(181, 8, 2, 5.00, NULL, '2026-07-23 09:30:45'),
(182, 8, 2, 3.00, NULL, '2026-07-23 09:30:45'),
(183, 8, 3, 2.00, NULL, '2026-07-23 09:30:45'),
(184, 8, 3, 3.00, NULL, '2026-07-23 09:30:45'),
(185, 8, 3, 5.00, NULL, '2026-07-23 09:30:45'),
(186, 8, 4, 5.00, NULL, '2026-07-23 09:30:45'),
(187, 8, 4, 3.00, NULL, '2026-07-23 09:30:45'),
(188, 8, 4, 5.00, NULL, '2026-07-23 09:30:45'),
(189, 8, 5, 5.00, NULL, '2026-07-23 09:30:45'),
(190, 8, 5, 2.00, NULL, '2026-07-23 09:30:45'),
(191, 8, 5, 5.00, NULL, '2026-07-23 09:30:45'),
(192, 8, 6, 3.00, NULL, '2026-07-23 09:30:45'),
(193, 8, 6, 5.00, NULL, '2026-07-23 09:30:45'),
(194, 8, 6, 3.00, NULL, '2026-07-23 09:30:45'),
(195, 8, 7, 3.00, NULL, '2026-07-23 09:30:45'),
(196, 8, 7, 5.00, NULL, '2026-07-23 09:30:45'),
(197, 8, 7, 5.00, NULL, '2026-07-23 09:30:45'),
(198, 8, 8, 5.00, NULL, '2026-07-23 09:30:45'),
(199, 8, 8, 5.00, NULL, '2026-07-23 09:30:45'),
(200, 8, 8, 5.00, NULL, '2026-07-23 09:30:45'),
(201, 8, 9, 5.00, NULL, '2026-07-23 09:30:45'),
(202, 8, 9, 5.00, NULL, '2026-07-23 09:30:45'),
(203, 8, 9, 5.00, NULL, '2026-07-23 09:30:45'),
(204, 8, 10, 1.00, NULL, '2026-07-23 09:30:45'),
(205, 8, 10, 1.00, NULL, '2026-07-23 09:30:45'),
(206, 8, 10, 1.00, NULL, '2026-07-23 09:30:45'),
(207, 9, 1, 4.00, NULL, '2026-07-25 11:18:04'),
(208, 9, 1, 5.00, NULL, '2026-07-25 11:18:04'),
(209, 9, 1, 5.00, NULL, '2026-07-25 11:18:04'),
(210, 9, 2, 3.00, NULL, '2026-07-25 11:18:04'),
(211, 9, 2, 5.00, NULL, '2026-07-25 11:18:04'),
(212, 9, 2, 5.00, NULL, '2026-07-25 11:18:04'),
(213, 9, 3, 5.00, NULL, '2026-07-25 11:18:04'),
(214, 9, 3, 5.00, NULL, '2026-07-25 11:18:04'),
(215, 9, 3, 5.00, NULL, '2026-07-25 11:18:04'),
(216, 9, 4, 4.00, NULL, '2026-07-25 11:18:04'),
(217, 9, 4, 4.00, NULL, '2026-07-25 11:18:04'),
(218, 9, 4, 5.00, NULL, '2026-07-25 11:18:04'),
(219, 9, 5, 5.00, NULL, '2026-07-25 11:18:04'),
(220, 9, 5, 5.00, NULL, '2026-07-25 11:18:04'),
(221, 9, 6, 5.00, NULL, '2026-07-25 11:18:04'),
(222, 9, 6, 4.00, NULL, '2026-07-25 11:18:04'),
(223, 9, 6, 5.00, NULL, '2026-07-25 11:18:04'),
(224, 9, 7, 5.00, NULL, '2026-07-25 11:18:04'),
(225, 9, 7, 3.00, NULL, '2026-07-25 11:18:04'),
(226, 9, 7, 3.00, NULL, '2026-07-25 11:18:04'),
(227, 9, 8, 5.00, NULL, '2026-07-25 11:18:04'),
(228, 9, 8, 4.00, NULL, '2026-07-25 11:18:04'),
(229, 9, 8, 5.00, NULL, '2026-07-25 11:18:04'),
(230, 9, 9, 3.00, NULL, '2026-07-25 11:18:04'),
(231, 9, 9, 5.00, NULL, '2026-07-25 11:18:04'),
(232, 9, 10, 5.00, NULL, '2026-07-25 11:18:04'),
(233, 9, 10, 3.00, NULL, '2026-07-25 11:18:04'),
(234, 9, 10, 3.00, NULL, '2026-07-25 11:18:04'),
(235, 10, 1, 5.00, NULL, '2026-07-26 05:34:58'),
(236, 10, 1, 5.00, NULL, '2026-07-26 05:34:58'),
(237, 10, 1, 5.00, NULL, '2026-07-26 05:34:58'),
(238, 10, 2, 5.00, NULL, '2026-07-26 05:34:58'),
(239, 10, 2, 5.00, NULL, '2026-07-26 05:34:58'),
(240, 10, 2, 5.00, NULL, '2026-07-26 05:34:58'),
(241, 10, 3, 5.00, NULL, '2026-07-26 05:34:58'),
(242, 10, 3, 5.00, NULL, '2026-07-26 05:34:58'),
(243, 10, 3, 5.00, NULL, '2026-07-26 05:34:58'),
(244, 10, 4, 5.00, NULL, '2026-07-26 05:34:58'),
(245, 10, 4, 5.00, NULL, '2026-07-26 05:34:58'),
(246, 10, 4, 5.00, NULL, '2026-07-26 05:34:58'),
(247, 10, 5, 5.00, NULL, '2026-07-26 05:34:58'),
(248, 10, 5, 5.00, NULL, '2026-07-26 05:34:58'),
(249, 10, 5, 5.00, NULL, '2026-07-26 05:34:58'),
(250, 10, 6, 5.00, NULL, '2026-07-26 05:34:58'),
(251, 10, 6, 5.00, NULL, '2026-07-26 05:34:58'),
(252, 10, 6, 5.00, NULL, '2026-07-26 05:34:58'),
(253, 10, 7, 5.00, NULL, '2026-07-26 05:34:58'),
(254, 10, 7, 5.00, NULL, '2026-07-26 05:34:58'),
(255, 10, 7, 5.00, NULL, '2026-07-26 05:34:58'),
(256, 10, 8, 5.00, NULL, '2026-07-26 05:34:58'),
(257, 10, 8, 5.00, NULL, '2026-07-26 05:34:58'),
(258, 10, 8, 5.00, NULL, '2026-07-26 05:34:58'),
(259, 10, 9, 5.00, NULL, '2026-07-26 05:34:58'),
(260, 10, 9, 5.00, NULL, '2026-07-26 05:34:58'),
(261, 10, 9, 5.00, NULL, '2026-07-26 05:34:58'),
(262, 10, 10, 5.00, NULL, '2026-07-26 05:34:58'),
(263, 10, 10, 5.00, NULL, '2026-07-26 05:34:58'),
(264, 10, 10, 5.00, NULL, '2026-07-26 05:34:58'),
(265, 11, 1, 1.00, NULL, '2026-07-30 09:36:54'),
(266, 11, 1, 1.00, NULL, '2026-07-30 09:36:54'),
(267, 11, 1, 1.00, NULL, '2026-07-30 09:36:54'),
(268, 11, 2, 1.00, NULL, '2026-07-30 09:36:54'),
(269, 11, 2, 1.00, NULL, '2026-07-30 09:36:54'),
(270, 11, 2, 1.00, NULL, '2026-07-30 09:36:54'),
(271, 11, 3, 1.00, NULL, '2026-07-30 09:36:54'),
(272, 11, 3, 1.00, NULL, '2026-07-30 09:36:54'),
(273, 11, 3, 1.00, NULL, '2026-07-30 09:36:54'),
(274, 11, 4, 1.00, NULL, '2026-07-30 09:36:54'),
(275, 11, 4, 1.00, NULL, '2026-07-30 09:36:54'),
(276, 11, 4, 1.00, NULL, '2026-07-30 09:36:54'),
(277, 11, 5, 1.00, NULL, '2026-07-30 09:36:54'),
(278, 11, 5, 1.00, NULL, '2026-07-30 09:36:54'),
(279, 11, 5, 1.00, NULL, '2026-07-30 09:36:54'),
(280, 11, 6, 1.00, NULL, '2026-07-30 09:36:54'),
(281, 11, 6, 1.00, NULL, '2026-07-30 09:36:54'),
(282, 11, 6, 1.00, NULL, '2026-07-30 09:36:54'),
(283, 11, 7, 1.00, NULL, '2026-07-30 09:36:54'),
(284, 11, 7, 1.00, NULL, '2026-07-30 09:36:54'),
(285, 11, 7, 1.00, NULL, '2026-07-30 09:36:54'),
(286, 11, 8, 1.00, NULL, '2026-07-30 09:36:54'),
(287, 11, 8, 1.00, NULL, '2026-07-30 09:36:54'),
(288, 11, 8, 1.00, NULL, '2026-07-30 09:36:54'),
(289, 11, 9, 1.00, NULL, '2026-07-30 09:36:54'),
(290, 11, 9, 1.00, NULL, '2026-07-30 09:36:54'),
(291, 11, 9, 1.00, NULL, '2026-07-30 09:36:54'),
(292, 11, 10, 1.00, NULL, '2026-07-30 09:36:54'),
(293, 11, 10, 1.00, NULL, '2026-07-30 09:36:54'),
(294, 11, 10, 1.00, NULL, '2026-07-30 09:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `probation_workflow`
--

CREATE TABLE `probation_workflow` (
  `id` int(11) NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `level` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `action_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `probation_workflow`
--

INSERT INTO `probation_workflow` (`id`, `appraisal_id`, `level`, `status`, `approved_by`, `comments`, `action_at`, `created_at`) VALUES
(31, 6, 'Self Assessment', 'Approved', 'Admin User', NULL, '2026-07-16 17:04:23', '2026-07-16 11:04:23'),
(32, 6, 'Supervisor', 'Approved', 'Admin User', 'fdsf', '2026-07-19 12:07:05', '2026-07-16 11:04:23'),
(33, 6, 'Dept Head', 'Approved', 'Admin User', 'sdf', '2026-07-19 15:46:29', '2026-07-16 11:04:23'),
(34, 6, 'HR Review', 'Approved', 'Admin User', 'asdfa', '2026-07-19 16:06:02', '2026-07-16 11:04:23'),
(35, 6, 'GM', 'Approved', 'Admin User', 'asdfasdf', '2026-07-19 16:36:38', '2026-07-16 11:04:23'),
(36, 6, 'Managing Director', 'Approved', 'Admin User', 'asdfasdf', '2026-07-19 16:36:46', '2026-07-16 11:04:23'),
(43, 8, 'Self Assessment', 'Approved', 'Admin User', NULL, '2026-07-23 15:30:46', '2026-07-23 09:30:45'),
(44, 8, 'Supervisor', 'Approved', 'Admin User', 'fsdf', '2026-07-23 15:56:24', '2026-07-23 09:30:45'),
(45, 8, 'Dept Head', 'Approved', 'Admin User', 'sdfasd', '2026-07-23 15:56:37', '2026-07-23 09:30:45'),
(46, 8, 'HR Review', 'Approved', 'Admin User', 'fasdf', '2026-07-23 15:56:42', '2026-07-23 09:30:45'),
(47, 8, 'GM', 'Approved', 'Admin User', 'asdf', '2026-07-23 15:56:51', '2026-07-23 09:30:45'),
(48, 8, 'Managing Director', 'Approved', 'Admin User', 'fasdf', '2026-07-23 15:56:55', '2026-07-23 09:30:45'),
(49, 9, 'Self Assessment', 'Approved', 'Admin User', NULL, '2026-07-25 17:18:04', '2026-07-25 11:18:04'),
(50, 9, 'Supervisor', 'Pending', NULL, NULL, NULL, '2026-07-25 11:18:04'),
(51, 9, 'Dept Head', 'Pending', NULL, NULL, NULL, '2026-07-25 11:18:04'),
(52, 9, 'HR Review', 'Pending', NULL, NULL, NULL, '2026-07-25 11:18:04'),
(53, 9, 'GM', 'Pending', NULL, NULL, NULL, '2026-07-25 11:18:04'),
(54, 9, 'Managing Director', 'Pending', NULL, NULL, NULL, '2026-07-25 11:18:04'),
(55, 10, 'Self Assessment', 'Approved', 'Admin User', NULL, '2026-07-26 11:34:58', '2026-07-26 05:34:58'),
(56, 10, 'Supervisor', 'Approved', 'Admin User', 'a', '2026-07-26 11:35:11', '2026-07-26 05:34:58'),
(57, 10, 'Dept Head', 'Approved', 'Admin User', 'a', '2026-07-26 11:35:14', '2026-07-26 05:34:58'),
(58, 10, 'HR Review', 'Approved', 'Admin User', 'a', '2026-07-26 11:35:18', '2026-07-26 05:34:58'),
(59, 10, 'GM', 'Approved', 'Admin User', 'a', '2026-07-26 11:35:21', '2026-07-26 05:34:58'),
(60, 10, 'Managing Director', 'Approved', 'Admin User', 'a', '2026-07-26 11:35:24', '2026-07-26 05:34:58'),
(61, 11, 'Self Assessment', 'Approved', 'Admin User', NULL, '2026-07-30 15:36:54', '2026-07-30 09:36:53'),
(62, 11, 'Supervisor', 'Pending', NULL, NULL, NULL, '2026-07-30 09:36:53'),
(63, 11, 'Dept Head', 'Pending', NULL, NULL, NULL, '2026-07-30 09:36:53'),
(64, 11, 'HR Review', 'Pending', NULL, NULL, NULL, '2026-07-30 09:36:53'),
(65, 11, 'GM', 'Pending', NULL, NULL, NULL, '2026-07-30 09:36:53'),
(66, 11, 'Managing Director', 'Pending', NULL, NULL, NULL, '2026-07-30 09:36:53');

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'Full Time',
  `vacancies` int(11) NOT NULL DEFAULT 1,
  `deadline` date DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `experience` decimal(4,1) DEFAULT 0.0,
  `salary_min` decimal(12,2) DEFAULT 0.00,
  `salary_max` decimal(12,2) DEFAULT 0.00,
  `skills` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'Medium',
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`id`, `title`, `dept_id`, `location`, `type`, `vacancies`, `deadline`, `education`, `experience`, `salary_min`, `salary_max`, `skills`, `description`, `responsibilities`, `benefits`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Senior Software Engineer', 1, 'Dhaka, Bangladesh', 'Full Time', 3, '2026-10-04', NULL, 0.0, 120000.00, 180000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(2, 'Junior Software Developer', 1, 'Dhaka, Bangladesh', 'Full Time', 5, '2026-10-08', NULL, 0.0, 40000.00, 70000.00, NULL, NULL, NULL, NULL, 'Medium', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(3, 'Digital Marketing Manager', 2, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-09-18', NULL, 0.0, 80000.00, 120000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(4, 'Content Writer', 2, 'Remote', 'Contract', 3, '2026-08-21', NULL, 0.0, 25000.00, 40000.00, NULL, NULL, NULL, NULL, 'Low', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(5, 'Financial Analyst', 3, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-09-08', NULL, 0.0, 60000.00, 90000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(6, 'Accountant', 3, 'Dhaka, Bangladesh', 'Full Time', 1, '2026-08-31', NULL, 0.0, 35000.00, 50000.00, NULL, NULL, NULL, NULL, 'Medium', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(7, 'HR Manager', 4, 'Dhaka, Bangladesh', 'Full Time', 1, '2026-09-01', NULL, 0.0, 90000.00, 130000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(8, 'Recruitment Specialist', 4, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-10-29', NULL, 0.0, 45000.00, 65000.00, NULL, NULL, NULL, NULL, 'Medium', 'Active', '2026-07-09 11:51:53', '2026-07-09 11:51:53'),
(9, 'Senior UX Designer', 5, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-10-15', NULL, 0.0, 100000.00, 150000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(10, 'Graphic Designer', 5, 'Dhaka, Bangladesh', 'Full Time', 3, '2026-10-28', NULL, 0.0, 30000.00, 50000.00, NULL, NULL, NULL, NULL, 'Low', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(11, 'Operations Manager', 6, 'Dhaka, Bangladesh', 'Full Time', 1, '2026-10-06', NULL, 0.0, 100000.00, 150000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(12, 'Supply Chain Coordinator', 6, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-10-21', NULL, 0.0, 40000.00, 60000.00, NULL, NULL, NULL, NULL, 'Medium', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(13, 'Sales Manager', 7, 'Dhaka, Bangladesh', 'Full Time', 1, '2026-10-26', NULL, 0.0, 80000.00, 120000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(14, 'Sales Representative', 7, 'Dhaka, Bangladesh', 'Full Time', 5, '2026-08-16', NULL, 0.0, 25000.00, 40000.00, NULL, NULL, NULL, NULL, 'Medium', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(15, 'System Administrator', 8, 'Dhaka, Bangladesh', 'Full Time', 2, '2026-09-25', NULL, 0.0, 50000.00, 80000.00, NULL, NULL, NULL, NULL, 'High', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54'),
(16, 'IT Support Engineer', 8, 'Dhaka, Bangladesh', 'Full Time', 3, '2026-08-18', NULL, 0.0, 25000.00, 40000.00, NULL, NULL, NULL, NULL, 'Low', 'Active', '2026-07-09 11:51:54', '2026-07-09 11:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `salary_info`
--

CREATE TABLE `salary_info` (
  `id` int(11) NOT NULL,
  `PBI_ID` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `salary_type` varchar(255) NOT NULL DEFAULT '',
  `basic_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `commission` decimal(4,2) NOT NULL DEFAULT 0.00,
  `consolidated_salary` decimal(20,2) NOT NULL DEFAULT 0.00,
  `house_rent` decimal(20,2) NOT NULL DEFAULT 0.00,
  `dearness_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `contribution` decimal(20,2) NOT NULL DEFAULT 0.00,
  `entertainment` decimal(20,2) NOT NULL DEFAULT 0.00,
  `special_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `convenience` decimal(20,2) NOT NULL DEFAULT 0.00,
  `utility_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `children_edu_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `responsibility_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `superintendent_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `servant_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `fuel_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `comstry_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `marketing_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `realestate_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `car_driver_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `ta` decimal(20,2) NOT NULL DEFAULT 0.00,
  `da` decimal(20,2) NOT NULL DEFAULT 0.00,
  `others` decimal(20,2) NOT NULL DEFAULT 0.00,
  `other_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `food_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `mobile_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `mobile_ceiling` decimal(20,2) NOT NULL DEFAULT 0.00,
  `bonus_applicable` varchar(32) NOT NULL DEFAULT '',
  `overtime_applicable` varchar(32) NOT NULL DEFAULT '',
  `ot_weekend_applicable` varchar(255) NOT NULL DEFAULT '',
  `ot_holiday_applicable` varchar(255) NOT NULL DEFAULT '',
  `pf` decimal(20,2) NOT NULL DEFAULT 0.00,
  `sm_money` decimal(20,2) NOT NULL DEFAULT 0.00,
  `income_tax` decimal(20,2) NOT NULL DEFAULT 0.00,
  `group_insurance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `cfund` decimal(20,2) NOT NULL DEFAULT 0.00,
  `cash_bank` varchar(32) NOT NULL DEFAULT '',
  `ac_no` varchar(255) NOT NULL DEFAULT '',
  `cash_amt` decimal(20,2) NOT NULL DEFAULT 0.00,
  `bank_amt` decimal(20,2) NOT NULL DEFAULT 0.00,
  `branch_info` varchar(255) NOT NULL DEFAULT '',
  `security_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `cooperative_share` int(11) NOT NULL DEFAULT 0,
  `motorcycle_install` decimal(20,2) NOT NULL DEFAULT 0.00,
  `fixed_tada` decimal(20,2) NOT NULL DEFAULT 0.00,
  `gross_salary` int(11) NOT NULL DEFAULT 0,
  `total_salary` int(11) NOT NULL DEFAULT 0,
  `total_payable` decimal(20,0) NOT NULL DEFAULT 0,
  `m_income_tex` int(100) NOT NULL DEFAULT 0,
  `bank_name` int(11) NOT NULL DEFAULT 0,
  `pf_company` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax_status` varchar(100) NOT NULL DEFAULT '',
  `pf_status` varchar(100) NOT NULL DEFAULT '',
  `cash` int(11) NOT NULL DEFAULT 0,
  `edit_by` varchar(20) NOT NULL DEFAULT '',
  `pf_applicable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `pf_deductible` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_info`
--

INSERT INTO `salary_info` (`id`, `PBI_ID`, `offer_id`, `salary_type`, `basic_salary`, `commission`, `consolidated_salary`, `house_rent`, `dearness_allowance`, `contribution`, `entertainment`, `special_allowance`, `convenience`, `utility_allowance`, `children_edu_allowance`, `responsibility_allowance`, `superintendent_allowance`, `servant_allowance`, `transport_allowance`, `fuel_allowance`, `comstry_allowance`, `marketing_allowance`, `realestate_allowance`, `car_driver_allowance`, `ta`, `da`, `others`, `other_allowance`, `food_allowance`, `mobile_allowance`, `medical_allowance`, `mobile_ceiling`, `bonus_applicable`, `overtime_applicable`, `ot_weekend_applicable`, `ot_holiday_applicable`, `pf`, `sm_money`, `income_tax`, `group_insurance`, `cfund`, `cash_bank`, `ac_no`, `cash_amt`, `bank_amt`, `branch_info`, `security_amount`, `cooperative_share`, `motorcycle_install`, `fixed_tada`, `gross_salary`, `total_salary`, `total_payable`, `m_income_tex`, `bank_name`, `pf_company`, `tax_status`, `pf_status`, `cash`, `edit_by`, `pf_applicable`, `pf_deductible`, `created_at`, `updated_at`) VALUES
(1, 2, 6, '', 76793.00, 0.00, 0.00, 38397.00, 0.00, 0.00, 0.00, 0.00, 11519.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 7679.00, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 134389, 134389, 0, 0, 0, 0.00, '', '', 0, '', 'No', 'No', '2026-07-23 06:54:18', '2026-07-23 06:54:18'),
(2, 3, 7, '', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 0, 100000, 0, 0, 0, 0.00, '', '', 0, '', 'No', 'No', '2026-07-23 06:59:05', '2026-07-23 06:59:05'),
(3, 4, 8, '', 57142.00, 0.00, 0.00, 28571.00, 0.00, 0.00, 0.00, 0.00, 8571.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5714.00, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 100000, 100000, 0, 0, 0, 0.00, '', '', 0, '', 'No', 'No', '2026-07-23 07:22:37', '2026-07-23 07:22:37'),
(4, 5, 9, '', 635.00, 0.00, 0.00, 318.00, 0.00, 0.00, 0.00, 0.00, 95.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 64.00, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 1112, 1112, 0, 0, 0, 0.00, '', '', 0, '', 'No', 'No', '2026-07-23 08:56:14', '2026-07-23 08:56:14'),
(5, 6, 11, '', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 0, 0, 0, 0, 0, 0.00, '', '', 0, '', 'No', '', '2026-07-25 11:00:02', '2026-07-25 11:00:02'),
(6, 7, 12, '', 57.14, 0.00, 0.00, 28.57, 0.00, 0.00, 0.00, 0.00, 8.57, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5.71, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 100, 100, 100, 0, 0, 0.00, '', '', 0, '', 'No', '', '2026-07-25 11:15:58', '2026-07-25 11:15:58'),
(7, 8, 13, '', 342.85, 0.00, 0.00, 171.43, 0.00, 0.00, 0.00, 0.00, 51.43, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 34.29, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 600, 600, 600, 0, 0, 0.00, '', '', 0, '', 'No', '', '2026-07-30 09:39:13', '2026-07-30 09:39:13'),
(8, 9, 10, '', 342.85, 0.00, 0.00, 171.43, 0.00, 0.00, 0.00, 0.00, 51.43, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 34.29, 0.00, '', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0.00, 0.00, '', 0.00, 0, 0.00, 0.00, 600, 600, 600, 0, 0, 0.00, '', '', 0, '', 'No', '', '2026-07-30 09:45:59', '2026-07-30 09:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `salary_proposals`
--

CREATE TABLE `salary_proposals` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `proposed_salary` decimal(12,2) NOT NULL,
  `offered_salary` decimal(12,2) DEFAULT NULL,
  `negotiation_notes` text DEFAULT NULL,
  `salary_breakdown` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `version` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(100) DEFAULT 'Admin User',
  `finalized_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_proposals`
--

INSERT INTO `salary_proposals` (`id`, `candidate_id`, `proposed_salary`, `offered_salary`, `negotiation_notes`, `salary_breakdown`, `status`, `version`, `created_by`, `finalized_at`, `created_at`, `updated_at`) VALUES
(6, 2, 77434.00, NULL, '', NULL, 'Proposed', 1, 'Admin User', NULL, '2026-07-16 11:02:02', '2026-07-16 11:02:02'),
(7, 2, 77434.00, 77434.00, 'fsd', NULL, 'Finalized', 2, 'Admin User', '2026-07-16 17:02:18', '2026-07-16 11:02:10', '2026-07-16 11:02:18'),
(9, 5, 190524.00, NULL, 'sdfasdf a', NULL, 'Rejected', 1, 'Admin User', NULL, '2026-07-19 06:27:37', '2026-07-19 07:03:14'),
(10, 12, 134389.00, 134389.00, '', NULL, 'Finalized', 1, 'Admin User', '2026-07-19 13:00:41', '2026-07-19 06:55:07', '2026-07-19 07:00:41'),
(11, 9, 100000.00, 100000.00, '', '{\"gross_salary\":100000,\"basic\":57142,\"house_rent\":28571,\"medical_allowance\":5714,\"conveyance_allowance\":8571,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":8750,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100000,\"total_payable\":91250,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-22 14:30:50', '2026-07-19 08:34:10', '2026-07-22 08:30:50'),
(12, 1, 100000.00, 100000.00, 'sdfasdf', '{\"gross_salary\":100000,\"basic\":57142,\"house_rent\":28571,\"medical_allowance\":5714,\"conveyance_allowance\":8571,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":8750,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100000,\"total_payable\":91250,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"sdfasdf\"}', 'Finalized', 1, 'Admin User', '2026-07-22 16:00:03', '2026-07-22 09:59:13', '2026-07-22 10:00:03'),
(13, 14, 1112.00, 1112.00, 'fasdf', '{\"gross_salary\":1112,\"basic\":635,\"house_rent\":318,\"medical_allowance\":64,\"conveyance_allowance\":95,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":1112,\"total_payable\":1112,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"fasdf\"}', 'Finalized', 1, 'Admin User', '2026-07-23 14:24:17', '2026-07-23 08:23:56', '2026-07-23 08:24:17'),
(14, 4, 100.00, 100.00, 'fasdf', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"fasdf\"}', 'Finalized', 1, 'Admin User', '2026-07-23 15:12:39', '2026-07-23 09:02:21', '2026-07-23 09:12:39'),
(15, 10, 100.00, 100.00, 'sdfas', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"sdfas\"}', 'Finalized', 1, 'Admin User', '2026-07-23 15:06:56', '2026-07-23 09:03:14', '2026-07-23 09:06:56'),
(16, 15, 100.00, 100.00, 'asdf', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"asdf\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:14:29', '2026-07-23 09:03:29', '2026-07-25 11:14:29'),
(17, 20, 100.00, 100.00, 'gggg', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"gggg\"}', 'Finalized', 1, 'Admin User', '2026-07-23 15:28:11', '2026-07-23 09:06:31', '2026-07-23 09:28:11'),
(18, 7, 100.00, 100.00, '', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:07:22', '2026-07-23 09:12:00', '2026-07-25 11:07:22'),
(19, 16, 100.00, 100.00, '', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:13:09', '2026-07-25 11:04:38', '2026-07-25 11:13:09'),
(20, 6, 100.00, 100.00, '', '{\"gross_salary\":100,\"basic\":57,\"house_rent\":29,\"medical_allowance\":6,\"conveyance_allowance\":9,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":100,\"total_payable\":100,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:13:40', '2026-07-25 11:04:53', '2026-07-25 11:13:40'),
(21, 11, 1000.00, 1000.00, '', '{\"gross_salary\":1000,\"basic\":571,\"house_rent\":286,\"medical_allowance\":57,\"conveyance_allowance\":86,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":1000,\"total_payable\":1000,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:07:08', '2026-07-25 11:05:33', '2026-07-25 11:07:08'),
(22, 25, 2009.00, 2009.00, '', '{\"gross_salary\":2009,\"basic\":1148,\"house_rent\":574,\"medical_allowance\":115,\"conveyance_allowance\":172,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":2009,\"total_payable\":2009,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Finalized', 1, 'Admin User', '2026-07-25 17:06:49', '2026-07-25 11:05:55', '2026-07-25 11:06:49'),
(23, 19, 400.00, NULL, '', '{\"gross_salary\":400,\"basic\":229,\"house_rent\":114,\"medical_allowance\":23,\"conveyance_allowance\":34,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":400,\"total_payable\":400,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Pending_Approval', 1, 'Admin User', NULL, '2026-07-25 11:14:42', '2026-07-25 11:14:53'),
(24, 24, 500.00, NULL, '', '{\"gross_salary\":500,\"basic\":286,\"house_rent\":143,\"medical_allowance\":29,\"conveyance_allowance\":43,\"food_allowance\":0,\"special_allowance\":0,\"mobile_allowance\":0,\"personal_pf\":0,\"company_pf\":0,\"pf_deductible\":0,\"monthly_income_tax\":0,\"cash_paid\":0,\"bank_paid\":0,\"total_salary\":500,\"total_payable\":500,\"gender\":\"male\",\"pf_applicable\":false,\"ot_applicable_out\":false,\"ot_holiday\":false,\"ot_weekend\":false,\"bank_name\":\"\",\"account_no\":\"\",\"receiving_bank_code\":\"\",\"receiving_bank_name\":\"\",\"salary_given_by\":\"\",\"negotiation_notes\":\"\"}', 'Proposed', 1, 'Admin User', NULL, '2026-07-25 11:15:02', '2026-07-25 11:15:02');

-- --------------------------------------------------------

--
-- Table structure for table `screenings`
--

CREATE TABLE `screenings` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `screened_by` varchar(100) DEFAULT 'Admin User',
  `date` date DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `communication_score` decimal(3,1) DEFAULT NULL,
  `experience_match` varchar(20) DEFAULT NULL,
  `skill_match` varchar(20) DEFAULT NULL,
  `education_match` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `result` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screenings`
--

INSERT INTO `screenings` (`id`, `candidate_id`, `screened_by`, `date`, `rating`, `communication_score`, `experience_match`, `skill_match`, `education_match`, `remarks`, `result`, `created_at`, `updated_at`) VALUES
(11, 5, 'Admin User', '2026-07-16', 4.0, 4.0, 'Low', 'Low', 'Low', 'Screen result not good but need to take interview', 'Shortlist', '2026-07-16 10:51:57', '2026-07-16 10:51:57'),
(12, 1, 'Admin User', '2026-07-16', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'Good to go', 'Shortlist', '2026-07-16 10:52:35', '2026-07-16 10:52:35'),
(13, 2, 'Admin User', '2026-07-16', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'Good to go', 'Shortlist', '2026-07-16 10:52:54', '2026-07-16 10:52:54'),
(14, 8, 'Admin User', '2026-07-16', 1.0, 1.0, 'Low', 'Low', 'Low', 'Not capable', 'Reject', '2026-07-16 10:53:27', '2026-07-16 10:53:27'),
(15, 3, 'Admin User', '2026-07-16', 2.0, 2.0, 'Low', 'Medium', 'Low', 'Hold for now.', 'Hold', '2026-07-16 10:54:02', '2026-07-16 10:54:02'),
(16, 4, 'Admin User', '2026-07-19', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'sdf', 'Shortlist', '2026-07-19 04:31:58', '2026-07-19 04:31:58'),
(17, 6, 'Admin User', '2026-07-19', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'fasf', 'Shortlist', '2026-07-19 04:32:07', '2026-07-19 04:32:07'),
(18, 9, 'Admin User', '2026-07-19', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'asdfa', 'Shortlist', '2026-07-19 04:32:17', '2026-07-19 04:32:17'),
(19, 7, 'Admin User', '2026-07-19', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'sdfa', 'Shortlist', '2026-07-19 04:32:29', '2026-07-19 04:32:29'),
(20, 12, 'Admin User', '2026-07-19', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'fasdf a', 'Shortlist', '2026-07-19 04:32:40', '2026-07-19 04:32:40'),
(21, 10, 'Admin User', '2026-07-23', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'fasdfasdf', 'Shortlist', '2026-07-23 08:15:06', '2026-07-23 08:15:06'),
(22, 11, 'Admin User', '2026-07-23', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'fasdf', 'Shortlist', '2026-07-23 08:15:16', '2026-07-23 08:15:16'),
(23, 13, 'Admin User', '2026-07-23', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'fsdf', 'Shortlist', '2026-07-23 08:15:24', '2026-07-23 08:15:24'),
(24, 14, 'Admin User', '2026-07-23', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'fadf asd', 'Shortlist', '2026-07-23 08:15:33', '2026-07-23 08:15:33'),
(25, 15, 'Admin User', '2026-07-23', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'fasdf', 'Shortlist', '2026-07-23 08:15:46', '2026-07-23 08:15:46'),
(26, 16, 'Admin User', '2026-07-23', 4.0, 4.0, 'High', 'High', 'High', 'fsadf', 'Shortlist', '2026-07-23 08:15:56', '2026-07-23 08:15:56'),
(27, 17, 'Admin User', '2026-07-23', 2.0, 2.0, 'Low', 'Low', 'Medium', 'asdf', 'Reject', '2026-07-23 08:16:07', '2026-07-23 08:16:07'),
(28, 18, 'Admin User', '2026-07-23', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'fasdf', 'Shortlist', '2026-07-23 08:16:23', '2026-07-23 08:16:23'),
(29, 19, 'Admin User', '2026-07-23', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'sadf f', 'Shortlist', '2026-07-23 08:16:32', '2026-07-23 08:16:32'),
(30, 20, 'Admin User', '2026-07-23', 5.0, 5.0, 'High', 'High', 'High', 'sdfasdf', 'Shortlist', '2026-07-23 08:16:46', '2026-07-23 08:16:46'),
(31, 21, 'Admin User', '2026-07-25', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'fasdf', 'Shortlist', '2026-07-25 10:23:45', '2026-07-25 10:23:45'),
(32, 22, 'Admin User', '2026-07-25', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'afs', 'Shortlist', '2026-07-25 10:24:02', '2026-07-25 10:24:02'),
(33, 23, 'Admin User', '2026-07-25', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'fasd', 'Shortlist', '2026-07-25 10:24:20', '2026-07-25 10:24:20'),
(34, 24, 'Admin User', '2026-07-25', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'asdf as', 'Shortlist', '2026-07-25 10:24:30', '2026-07-25 10:24:30'),
(35, 25, 'Admin User', '2026-07-25', 3.0, 3.0, 'Medium', 'Medium', 'Medium', 'afs', 'Shortlist', '2026-07-25 10:24:39', '2026-07-25 10:24:39'),
(36, 26, 'Admin User', '2026-07-25', 5.0, 4.0, 'Medium', 'Medium', 'Medium', 'gdsf', 'Shortlist', '2026-07-25 11:00:43', '2026-07-25 11:00:43'),
(37, 27, 'Admin User', '2026-07-25', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'sdfg', 'Shortlist', '2026-07-25 11:00:53', '2026-07-25 11:00:53'),
(38, 29, 'Admin User', '2026-07-25', 4.0, 3.0, 'Medium', 'Medium', 'Medium', 'sdg', 'Shortlist', '2026-07-25 11:01:02', '2026-07-25 11:01:02'),
(39, 28, 'Admin User', '2026-07-25', 4.0, 4.0, 'Medium', 'Medium', 'Medium', 'gsdg', 'Shortlist', '2026-07-25 11:01:11', '2026-07-25 11:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `template_modules`
--

CREATE TABLE `template_modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `render_file` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `template_modules`
--

INSERT INTO `template_modules` (`id`, `module_name`, `label`, `render_file`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Offer', 'Offer / Recruitment', 'templates/render/offer.php', 'Merge tags for offer letters and recruitment', 1, '2026-07-19 05:44:54', '2026-07-19 05:44:54'),
(2, 'Confirmation', 'Confirmation', 'templates/render/confirmation.php', 'Merge tags for confirmation letters', 1, '2026-07-19 05:44:54', '2026-07-19 05:44:54'),
(3, 'Probation', 'Probation', 'templates/render/probation.php', 'Merge tags for probation appraisal letters', 1, '2026-07-19 05:44:54', '2026-07-19 05:44:54'),
(4, 'General', 'General Letter', 'templates/render/general.php', 'General purpose merge tags', 1, '2026-07-19 05:44:54', '2026-07-19 05:44:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `candidate_logs`
--
ALTER TABLE `candidate_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `candidate_statuses`
--
ALTER TABLE `candidate_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `confirmation_letters`
--
ALTER TABLE `confirmation_letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `interview_log`
--
ALTER TABLE `interview_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `letter_templates`
--
ALTER TABLE `letter_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merge_tags`
--
ALTER TABLE `merge_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tag_type` (`tag`,`type`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `designation_id` (`designation_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `offer_approvals`
--
ALTER TABLE `offer_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indexes for table `offer_email_log`
--
ALTER TABLE `offer_email_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indexes for table `offer_letters`
--
ALTER TABLE `offer_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indexes for table `personnel_basic_info`
--
ALTER TABLE `personnel_basic_info`
  ADD PRIMARY KEY (`PBI_ID`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indexes for table `probation_appraisals`
--
ALTER TABLE `probation_appraisals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `probation_categories`
--
ALTER TABLE `probation_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `probation_scores`
--
ALTER TABLE `probation_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appraisal_id` (`appraisal_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `probation_workflow`
--
ALTER TABLE `probation_workflow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appraisal_id` (`appraisal_id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dept_id` (`dept_id`);

--
-- Indexes for table `salary_info`
--
ALTER TABLE `salary_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PBI_ID` (`PBI_ID`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indexes for table `salary_proposals`
--
ALTER TABLE `salary_proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `screenings`
--
ALTER TABLE `screenings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `template_modules`
--
ALTER TABLE `template_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `module_name` (`module_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `candidate_logs`
--
ALTER TABLE `candidate_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `candidate_statuses`
--
ALTER TABLE `candidate_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `confirmation_letters`
--
ALTER TABLE `confirmation_letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `interview_log`
--
ALTER TABLE `interview_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `letter_templates`
--
ALTER TABLE `letter_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `merge_tags`
--
ALTER TABLE `merge_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `offer_approvals`
--
ALTER TABLE `offer_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `offer_email_log`
--
ALTER TABLE `offer_email_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `offer_letters`
--
ALTER TABLE `offer_letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `personnel_basic_info`
--
ALTER TABLE `personnel_basic_info`
  MODIFY `PBI_ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `probation_appraisals`
--
ALTER TABLE `probation_appraisals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `probation_categories`
--
ALTER TABLE `probation_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `probation_scores`
--
ALTER TABLE `probation_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `probation_workflow`
--
ALTER TABLE `probation_workflow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `salary_info`
--
ALTER TABLE `salary_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salary_proposals`
--
ALTER TABLE `salary_proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `screenings`
--
ALTER TABLE `screenings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `template_modules`
--
ALTER TABLE `template_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `candidate_statuses` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `candidate_logs`
--
ALTER TABLE `candidate_logs`
  ADD CONSTRAINT `candidate_logs_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `interview_log`
--
ALTER TABLE `interview_log`
  ADD CONSTRAINT `interview_log_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `offers_ibfk_3` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `offer_approvals`
--
ALTER TABLE `offer_approvals`
  ADD CONSTRAINT `offer_approvals_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer_email_log`
--
ALTER TABLE `offer_email_log`
  ADD CONSTRAINT `offer_email_log_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer_letters`
--
ALTER TABLE `offer_letters`
  ADD CONSTRAINT `offer_letters_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `probation_scores`
--
ALTER TABLE `probation_scores`
  ADD CONSTRAINT `probation_scores_ibfk_1` FOREIGN KEY (`appraisal_id`) REFERENCES `probation_appraisals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `probation_scores_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `probation_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `probation_workflow`
--
ALTER TABLE `probation_workflow`
  ADD CONSTRAINT `probation_workflow_ibfk_1` FOREIGN KEY (`appraisal_id`) REFERENCES `probation_appraisals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD CONSTRAINT `fk_requisition_dept` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `salary_proposals`
--
ALTER TABLE `salary_proposals`
  ADD CONSTRAINT `salary_proposals_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `screenings`
--
ALTER TABLE `screenings`
  ADD CONSTRAINT `screenings_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
