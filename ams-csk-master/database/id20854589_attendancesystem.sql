-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 21, 2023 at 08:10 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id20854589_attendancesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin') NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `date_created`, `name`, `department`, `body`) VALUES
(1, '2023-07-21', 'Ichiji Seika', 'HR', 'We are delighted to express our gratitude to our incredible team members who consistently go above and beyond.'),
(2, '2023-07-11', 'Otogi Aruto', 'Marketing', 'We are excited to announce the launch of our latest product, XYZ! With its cutting-edge features and innovative design, XYZ sets a new standard in the industry.'),
(3, '2023-07-21', 'Saito Miyako', 'IT', 'We are thrilled to announce that [company name] is moving to a new, state-of-the-art office space!');

-- --------------------------------------------------------

--
-- Table structure for table `copy_of_list_of_departments_list_of_teams____intern_management`
--

CREATE TABLE `copy_of_list_of_departments_list_of_teams____intern_management` (
  `COL 1` varchar(27) DEFAULT NULL,
  `COL 2` varchar(5) DEFAULT NULL,
  `COL 3` varchar(21) DEFAULT NULL,
  `COL 4` varchar(21) DEFAULT NULL,
  `COL 5` varchar(10) DEFAULT NULL,
  `COL 6` varchar(26) DEFAULT NULL,
  `COL 7` varchar(27) DEFAULT NULL,
  `COL 8` varchar(8) DEFAULT NULL,
  `COL 9` varchar(21) DEFAULT NULL,
  `COL 10` varchar(10) DEFAULT NULL,
  `COL 11` varchar(27) DEFAULT NULL,
  `COL 12` varchar(25) DEFAULT NULL,
  `COL 13` varchar(8) DEFAULT NULL,
  `COL 14` varchar(21) DEFAULT NULL,
  `COL 15` varchar(10) DEFAULT NULL,
  `COL 16` varchar(27) DEFAULT NULL,
  `COL 17` varchar(25) DEFAULT NULL,
  `COL 18` varchar(15) DEFAULT NULL,
  `COL 19` varchar(21) DEFAULT NULL,
  `COL 20` varchar(10) DEFAULT NULL,
  `COL 21` varchar(23) DEFAULT NULL,
  `COL 22` varchar(5) DEFAULT NULL,
  `COL 23` varchar(8) DEFAULT NULL,
  `COL 24` varchar(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `copy_of_list_of_departments_list_of_teams____intern_management`
--

INSERT INTO `copy_of_list_of_departments_list_of_teams____intern_management` (`COL 1`, `COL 2`, `COL 3`, `COL 4`, `COL 5`, `COL 6`, `COL 7`, `COL 8`, `COL 9`, `COL 10`, `COL 11`, `COL 12`, `COL 13`, `COL 14`, `COL 15`, `COL 16`, `COL 17`, `COL 18`, `COL 19`, `COL 20`, `COL 21`, `COL 22`, `COL 23`, `COL 24`) VALUES
('DEPARTMENT:  ADMINISTRATIVE', '', '', '', '', 'HUMAN RESOURCES DEPARTMENT', '', '', '', '', 'DEPARTMENT:  ADMINISTRATIVE', '', '', '', '', 'DEPARTMENT:  ADMINISTRATIVE', '', '', '', '', 'DEPARTMENT:  ACCOUNTING', '', '', ''),
('TEAM:  BATCH MANAGEMENT', '', '', '', '', 'TEAM: HR', '', '', '', '', 'TEAM:  MARKETING', '', '', '', '', 'TEAM:  IT', '', '', '', '', 'TEAM:  MARKETING', '', '', ''),
('BATCH ', 'NAMES', 'R - HOURS', 'NAME OF TEAM IF 50/50', '', 'BATCH ', 'NAMES', 'R- HOURS', 'NAME OF TEAM IF 50/50', '', 'BATCH ', 'NAMES', 'R- HOURS', 'NAME OF TEAM IF 50/50', '', 'BATCH ', 'NAMES', 'REMAINING HOURS', 'NAME OF TEAM IF 50/50', '', 'BATCH ', 'NAMES', 'R- HOURS', 'NAME OF TEAM IF 50/50'),
('', '', '', '', '', '19-B', 'Dela Pena, Chriscel N. (TM)', '262.5', '', '', '18-B', 'Dimaculangan, Iverson M. ', '318', '', '', '18 A', 'Aingeal Ocampo', '0', '', '', '', '', '', ''),
('', '', '', '', '', '19-B', 'Agustin, Mark Ariel C.(TL)', '362', '', '', '19-B', 'Moreno, Janicalie B.', '241', '', '', '18 A', 'Piolo Cabarlo', '0', '', '', '', '', '', ''),
('', '', '', '', '', '19-C', 'Esguerra, Trisha Gail D.P.', '0', '', '', '', 'Christian Sabong', '208', '', '', '18 A', 'John Paul Mendoza', '0', '', '', '', '', '', ''),
('', '', '', '', '', '19-C', 'Beltran, Latrell A.', '0', '', '', '', 'Jay Ar Geroquia', '208', '', '', '18 C', 'Kenshee Okko', '0', '', '', '', '', '', ''),
('', '', '', '', '', '20-A', 'Publico, Mariel', '326.50', '', '', '', 'Mikhail Punzalan', '208', '', '', '18 C', 'Ronald John Yabut', '0', '', '', '', '', '', ''),
('', '', '', '', '', '20-A', 'San Jose, Roanne', '339', '', '', '', 'Allen Cedric Dimapilis', '208', '', '', '19 A', ' Kyle Christian Quinones', '0', '', '', '', '', '', ''),
('', '', '', '', '', '20-A', 'Belardo, Alexa Sasha B.', '310.5', '', '', '', 'John William Mariquina', '128', '', '', '19 B', 'Janus Regal', '0', '', '', '', '', '', ''),
('', '', 'automatically updated', '', '', '20-D', 'Hualde, Keiko Angeli ', '510', '', '', '', 'Edilberto Sus', '128', '', '', '19 B', 'Jayrald Palmaria', '14', '', '', '', '', '', ''),
('', '', '', '', '', '20-D', 'Salarioza, James Ericson F.', '255', '', '', '', 'Jham Dapadap', '88', '', '', '19 B', 'JM Pedroso Doria', '-', '', '', '', '', '', ''),
('', '  ', '', '', '', '', '', '', '', '', '', '', '', '', '', '19 B', 'Gerald Domingo', '20', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '19 C', 'Marc Eduard Malana', '0', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '19 C', 'Oliver Dela Cruz', '156', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '19 C', 'Rachelle Vargas Malaga', '156', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Albrecht Carl P. Chavez', '520', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Patrick Almeda Bacabis', '168', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Rena Mae Manuel', '152', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '19C', 'Ava Ciara Lacambra', '96', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Janvin Coley Alde', '86', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Joshua Mark Bulanon', '209', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Shawn Española', '222', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19CG2', 'Jonathan Manio', '68', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19DG1', 'Jicel Ann Iyog', '240', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '-', 'Paul Yap', '-', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19DG1', 'Gabriel Terrence Garcia', '80', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19GD1', 'Meryiel Joelelen Cordero', '240', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B19GD1', 'Christian Kenneth Calzada', '336', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '19D', 'Xyrel Genio', '336', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20A', 'Rommel Acob', '280', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20A', 'Kenjie Villamater', '248', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B20AG1', 'Louise Akira Nieva', '282', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B20AG1', 'Rene Letegio', '280', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20A', 'Cj Navarra', '486', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'B20AG1', 'Mark Jay Clemente', '356', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20B', 'Mark Garong', '392', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20B', 'Nicko Reanzares', '312', '', '', '', '', '', ''),
('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20C', 'John Estrada Jr.', '480', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `emp_info`
--

CREATE TABLE `emp_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin','Management','N/A') NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `work_days` varchar(255) NOT NULL,
  `work_hrs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info`
--

INSERT INTO `emp_info` (`id`, `name`, `department`, `position`, `start_date`, `work_days`, `work_hrs`) VALUES
(1, 'Ichiji Seika', 'HR', 'HR Admin', '2023-06-02', 'Monday to Friday', '8am - 5pm'),
(2, 'Viejay Abad', 'Management', 'Quality Control Manager/Operations Manager', '2023-05-05', 'N/A', 'N/A'),
(3, 'admin', 'Admin', 'Admin Test', '2023-07-07', 'Monday to Friday', '8am to 5pm'),
(8, 'Cherizza Grace G. Peñafiel', 'Accounting', 'President', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(9, 'Jazz Jan D. Gregorio ', 'Admin', 'Admin Officer', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(10, 'John Leonard D. Gregorio', 'Accounting', 'Treasury', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(11, 'Cecilia D. Gregorio', 'Management', 'Chairman', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(12, 'Julich Enduma', 'HR', 'HR Supervisor', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(13, 'Justine Grace Rosarda', 'Accounting', 'Accounting Supervisor', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(14, 'Veronica Joyce Gabucan', 'Management', 'Junior Bookkeeper / Admin ', '2019-01-01', 'Subject to change', 'Subject to change'),
(15, 'Karla Barja Alindayo', 'Management', 'Senior Bookkeeper', '2019-01-01', 'Subject to change', 'Subject to change'),
(16, 'Alexandra Jenn Perjes Yap', 'Management', 'Junior Bookkeeper', '2019-01-01', 'Subject to change', 'Subject to change'),
(17, 'John Michael V. De Jose', 'Management', 'IT Manager', '2019-01-01', 'Subject to change', 'Subject to change'),
(18, 'Patricia M. Sayco', 'Management', 'Junior Bookkeeper', '2019-01-01', 'Subject to change', 'Subject to change'),
(19, 'Jennifer Edangan Onnon', 'Management', 'Junior Bookkeeper', '2019-01-01', 'Subject to change', 'Subject to change'),
(20, 'Christine N. Calditaran', 'Management', 'Junior Bookkeeper', '2019-01-01', 'Subject to change', 'Subject to change'),
(21, 'Rubelyn De Jose', 'Accounting', 'Account Receivable Clerk', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(22, 'John Herald Gallego', 'Accounting', 'Junior Bookkeeper', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(23, 'Charichelle V. Laureles', 'Management', 'Subject to change.', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(24, 'Frincez Devillez Convento', 'Management', 'Subject to change.', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(25, 'Milky N. Pagobo', 'Accounting', 'Junior Bookkeeper', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(26, 'Richard A. Araña', 'Accounting', 'Junior Bookkeeper', '2019-01-01', 'Subject to change.', 'Subject to change.'),
(27, 'Rosyl Vallente Perez', 'Accounting', 'Junior Bookkeeper', '2019-01-01', 'Subject to change.', 'Subject to change.');

-- --------------------------------------------------------

--
-- Table structure for table `int_info`
--

CREATE TABLE `int_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin','Management','N/A') NOT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'Intern',
  `start_date` date NOT NULL,
  `work_days` varchar(255) NOT NULL,
  `work_hrs` varchar(255) NOT NULL,
  `hr_req` int(3) NOT NULL,
  `hr_ren` int(3) NOT NULL DEFAULT 0,
  `hr_left` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `int_info`
--

INSERT INTO `int_info` (`id`, `name`, `department`, `position`, `start_date`, `work_days`, `work_hrs`, `hr_req`, `hr_ren`, `hr_left`) VALUES
(1, 'Gabriel Terrence Garcia', 'IT', 'Intern', '2023-03-27', 'Monday to Friday', '8am - 5pm', 486, -21, 507),
(2, 'Ava Ciara Lacambra', 'IT', 'Intern', '2023-03-20', 'Monday to Friday', '8am - 5pm', 486, -9, 495),
(3, 'Shawn Danielle Espanola', 'IT', 'Intern', '2023-03-22', 'Monday to Friday', '8am - 5pm', 486, -10, 496),
(4, 'Louise Akira S. Nieva', 'IT', 'Intern', '2023-04-03', 'Monday to Friday', '8am to 5pm', 600, 433, 167),
(5, 'Joshua Esguerra', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 8, -8),
(6, 'Ma. Jhamille Ann M. Dapadap', 'Marketing', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 0, 0),
(7, 'Cassandra Santos', 'Marketing', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 0, 0),
(8, 'John William Mariquina', 'Marketing', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 0, 0),
(9, 'Lina Hezarsa', 'Marketing', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 0, 0),
(10, 'John Carlo Sano', 'HR', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 0, 0, 0),
(11, 'Test User', 'IT', 'Intern', '2023-07-19', 'Monday to Friday', '8am to 5pm', 600, 0, 0),
(12, 'Testing User 2', 'IT', 'Intern', '2023-07-19', 'Monday to Friday', '8am to 5pm', 600, 0, 0),
(13, 'Test User 3', 'IT', 'Intern', '2023-07-19', 'Monday to Friday', '8am to 5pm', 500, 0, 0),
(14, 'Rena Mae Manuel', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(15, 'Jerene Beatrice Talatala', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 8, 192),
(16, 'Juspher Pedrozo', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 8, 192),
(17, 'Eugene Gueta', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(18, 'Patrick Lirom', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 8, 192),
(19, 'Daniella Langres', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 8, 192),
(20, 'Rene Q. Letegio', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(21, 'Jicel Ann V. Iyog', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(22, 'Xyrel D. Genio', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(23, 'Christian Kenneth Calzada', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(24, 'Mark Anthony O. Garong', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(25, 'Meryiel Joelelen D. Cordero', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(26, 'Joshua Mark Bulanon', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(27, 'Rommel Acob', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 8, 192),
(28, 'Mark Jay G. Clemente ', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(29, 'Nicko V. Reanzares', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0),
(30, 'Patrick Shane A. Bacabis', 'IT', 'Intern', '2019-01-01', 'Subject to change', 'Subject to change', 200, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `leave_type` enum('Planned Leave','School Initiated Leave','Emergency Leave','Sick Leave','Birthday Leave','Vacation Leave') NOT NULL,
  `date_req` datetime NOT NULL,
  `date_app` datetime NOT NULL,
  `status` enum('Approved','Rejected') NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `name`, `leave_type`, `date_req`, `date_app`, `status`, `remarks`) VALUES
(1, 'Gabriel Terrence Garcia', 'School Initiated Leave', '2023-06-07 07:00:00', '2023-06-07 04:45:00', 'Approved', 'ok'),
(2, 'Viejay Abad', 'School Initiated Leave', '2023-07-12 07:24:00', '2023-07-12 07:24:00', 'Approved', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Late','Absent without Leave','Reminder','Late - No time in','Unidentified') NOT NULL,
  `date` date NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin') NOT NULL,
  `body` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `name`, `department`, `body`, `date`) VALUES
(1, 'Ichiji Seika', 'Admin', 'You have received a new message from [Sender Name]. Click here to read it.', '2023-03-16'),
(2, 'Otogi Aruto', 'HR', '[Sender Name] has sent you a friend request. Click here to view and respond.', '2023-03-20'),
(3, 'Saito Miyako', 'HR', 'A new version of [Product Name] is now available. Click here to learn more and download the update.', '2023-04-12');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_activities`
--

CREATE TABLE `tasks_activities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_date_assigned` datetime NOT NULL,
  `task_deadline` datetime NOT NULL,
  `task_from` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks_activities`
--

INSERT INTO `tasks_activities` (`id`, `name`, `department`, `task_name`, `task_date_assigned`, `task_deadline`, `task_from`) VALUES
(1, 'Gotou Hitori', 'IT', 'AMS Development', '2023-07-12 00:00:00', '2023-07-17 11:24:51', 'Admin Department'),
(2, 'Ichiji Nijika', 'HR', 'Monitor IT Department', '2023-07-01 14:23:48', '2023-07-07 14:23:48', 'HR Department'),
(3, '', 'All Departments', 'Attend Weekly Meeting', '2023-07-01 14:26:54', '2023-07-14 14:26:54', 'Admin Department'),
(4, '', 'HR', 'List down Intern Names', '2023-07-13 10:24:48', '2023-07-13 10:24:48', 'HR Department'),
(5, 'Ichiji Nijika', 'HR', 'Test', '2023-05-01 16:37:02', '2023-07-13 10:37:02', ''),
(6, 'Ryo Yamada ', 'Accounting', 'Check Payroll', '2023-07-13 11:15:42', '2023-07-13 11:15:42', '');

-- --------------------------------------------------------

--
-- Table structure for table `time_in`
--

CREATE TABLE `time_in` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `photo_loc` varchar(255) NOT NULL,
  `approval` enum('Approved','Reviewing','Denied','No Time-out') NOT NULL DEFAULT 'No Time-out',
  `token` varchar(255) NOT NULL DEFAULT 'no token generated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_in`
--

INSERT INTO `time_in` (`id`, `name`, `datetime`, `location`, `photo_loc`, `approval`, `token`) VALUES
(29, 'Gabriel Terrence Garcia', '2023-06-26 08:00:00', 'WFH', 'photo_loc', 'Reviewing', '23c3063a10950661'),
(30, 'Louise Akira S. Nieva', '2023-06-26 08:00:00', 'WFH', 'photo_loc', 'Reviewing', '9d759268e16f9f9c'),
(31, 'Louise Akira S. Nieva', '2023-07-01 13:57:26', 'WFH', 'timein_photos/Sketchpad.png', 'Reviewing', '649fc046344ab472262'),
(32, 'Louise Akira S. Nieva', '2023-07-19 09:04:29', 'WFH', 'photo_loc', 'Reviewing', '64b7369d81fcc370201'),
(33, 'Louise Akira S. Nieva', '2023-07-19 17:55:14', 'WFH', 'photo_loc', 'Reviewing', '64b7b30257d58850875'),
(34, 'Louise Akira S. Nieva', '2023-07-19 17:55:40', 'WFH', 'photo_loc', 'Reviewing', '64b7b31c28c3a997041'),
(35, 'Joshua Esguerra', '2023-07-20 07:53:54', 'WFH', 'photo_loc', 'Reviewing', '64b8779275c93199182'),
(36, 'Jerene Beatrice Talatala', '2023-07-20 07:55:41', 'WFH', 'photo_loc', 'Reviewing', '64b877fdb99b8356257'),
(37, 'Rena Mae Manuel', '2023-07-20 07:57:15', 'WFH', 'photo_loc', 'Reviewing', '64b8785bb2368341235'),
(38, 'Rommel Acob', '2023-07-20 08:01:53', 'WFH', 'photo_loc', 'Reviewing', '64b879714caa6593322'),
(39, 'Daniella Langres', '2023-07-20 08:03:16', 'WFH', 'photo_loc', 'Reviewing', '64b879c422cec157468'),
(40, 'Juspher Pedrozo', '2023-07-20 08:04:15', 'WFH', 'photo_loc', 'Reviewing', '64b879ff6b497206044'),
(41, 'Patrick Lirom', '2023-07-20 08:04:33', 'WFH', 'photo_loc', 'Reviewing', '64b87a1149247895223'),
(42, 'Joshua Esguerra', '2023-07-21 07:46:43', 'WFH', 'photo_loc', 'Reviewing', '64b9c7633ae5d607445'),
(43, 'Daniella Langres', '2023-07-21 07:50:03', 'WFH', 'photo_loc', 'Reviewing', '64b9c82b6778d842897'),
(44, 'Jerene Beatrice Talatala', '2023-07-21 07:55:53', 'WFH', 'photo_loc', 'Reviewing', '64b9c98986422358683'),
(45, 'Juspher Pedrozo', '2023-07-21 07:56:26', 'WFH', 'photo_loc', 'Reviewing', '64b9c9aad8b61706931'),
(46, 'Rommel Acob', '2023-07-21 08:03:53', 'WFH', 'photo_loc', 'Reviewing', '64b9cb698f168653408'),
(47, 'Viejay Abad', '2023-07-21 12:27:37', 'WFH', 'photo_loc', 'Reviewing', '64ba093933c03764313'),
(48, 'Viejay Abad', '2023-07-21 12:39:09', 'WFH', 'photo_loc', 'Reviewing', '64ba0bedceab1748222'),
(49, 'Cherizza Grace G. Peñafiel', '2023-07-21 12:44:20', 'WFH', 'photo_loc', 'Reviewing', '64ba0d24e4741503305'),
(50, 'Richard A. Araña', '2023-07-21 13:53:47', 'WFH', 'photo_loc', 'Reviewing', '64ba1d6b64137625968'),
(51, 'admin', '2023-07-21 14:18:13', 'WFH', 'photo_loc', 'Reviewing', '64ba2325006b6281194'),
(52, 'Richard A. Araña', '2023-07-21 15:49:36', 'WFH', 'photo_loc', 'No Time-out', '64ba38903c2a1470775');

-- --------------------------------------------------------

--
-- Table structure for table `time_out`
--

CREATE TABLE `time_out` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `overtime` int(2) NOT NULL DEFAULT 0,
  `hours` int(2) NOT NULL DEFAULT 0,
  `tasks` varchar(400) DEFAULT 'none',
  `approval` enum('Approved','Reviewing','Denied','No Time-out') NOT NULL DEFAULT 'No Time-out',
  `token` varchar(255) NOT NULL DEFAULT 'no token generated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_out`
--

INSERT INTO `time_out` (`id`, `name`, `datetime`, `overtime`, `hours`, `tasks`, `approval`, `token`) VALUES
(20, 'Gabriel Terrence Garcia', '2023-06-26 17:00:00', 0, 8, 'none', 'Approved', '23c3063a10950661'),
(21, 'Louise Akira S. Nieva', '2023-06-26 17:00:00', 0, 8, 'none', 'Denied', '9d759268e16f9f9c'),
(22, 'Louise Akira S. Nieva', '2023-07-01 14:00:13', 0, -1, 'Testing this po. This field po requires a minimum of 50 characters para po hindi pwedeng minimal lang ilagay po.', 'Reviewing', '649fc046344ab472262'),
(23, 'Louise Akira S. Nieva', '2023-07-19 17:33:27', 0, 7, 'tesadfasdfasdfasdfasdfasdfasdf', 'Reviewing', '64b7369d81fcc370201'),
(24, 'Louise Akira S. Nieva', '2023-07-19 17:55:28', 0, -1, '-Testing\r\n-Testing\r\n-tesgintasdf', 'Reviewing', '64b7b30257d58850875'),
(25, 'Louise Akira S. Nieva', '2023-07-19 17:55:56', 0, -1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa, a,a,,,a fasdfaAMASDF ,ASDFASDF AND DAetsetsadnfkad,\r\n', 'Reviewing', '64b7b31c28c3a997041'),
(26, 'Joshua Esguerra', '2023-07-20 16:48:05', 0, 8, 'pagination of admin dash, shows if announcements are available or not, shows the latest announcement', 'Reviewing', '64b8779275c93199182'),
(27, 'Jerene Beatrice Talatala', '2023-07-20 16:55:37', 0, 8, 'Posted a pubmat on Facebook, Meetup with our supervisor to be informed about being TL, Analytics for FB for the past 28 days', 'Reviewing', '64b877fdb99b8356257'),
(28, 'Rena Mae Manuel', NULL, 0, 0, 'none', 'No Time-out', '64b8785bb2368341235'),
(29, 'Rommel Acob', '2023-07-20 16:51:29', 0, 8, 'Passed A video for outgoing interns', 'Reviewing', '64b879714caa6593322'),
(30, 'Daniella Langres', '2023-07-20 16:59:43', 0, 8, 'Facebook posting and publication materials', 'Reviewing', '64b879c422cec157468'),
(31, 'Juspher Pedrozo', '2023-07-20 16:51:02', 0, 8, 'Posted approved pubmats before lunch. After lunch, met with our Supervisor Mr. Viejay about becoming the TM for the IT Department. Afterwards, created a pubmat for hiring of Marketing Interns while the other socmed members created the pubmat for other departments.', 'Reviewing', '64b879ff6b497206044'),
(32, 'Patrick Lirom', '2023-07-20 16:57:41', 0, 8, 'Started production of a video (What is Auditing?)\r\nProduction to be continued tomorrow.', 'Reviewing', '64b87a1149247895223'),
(33, 'Joshua Esguerra', NULL, 0, 0, 'none', 'No Time-out', '64b9c7633ae5d607445'),
(34, 'Daniella Langres', NULL, 0, 0, 'none', 'No Time-out', '64b9c82b6778d842897'),
(35, 'Jerene Beatrice Talatala', NULL, 0, 0, 'none', 'No Time-out', '64b9c98986422358683'),
(36, 'Juspher Pedrozo', NULL, 0, 0, 'none', 'No Time-out', '64b9c9aad8b61706931'),
(37, 'Rommel Acob', NULL, 0, 0, 'none', 'No Time-out', '64b9cb698f168653408'),
(38, 'Viejay Abad', '2023-07-21 12:35:58', 0, -1, 'testing only Testing only  testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only testing only Testing only ', 'Reviewing', '64ba093933c03764313'),
(39, 'Viejay Abad', NULL, 0, 0, 'none', 'No Time-out', '64ba0bedceab1748222'),
(40, 'Cherizza Grace G. Peñafiel', NULL, 0, 0, 'none', 'No Time-out', '64ba0d24e4741503305'),
(41, 'Richard A. Araña', '2023-07-21 14:24:34', 0, 0, 'Conversation at messenger GC and checking for the other branches GL send in the messenger chat.\r\nChecking the send Excel files for Other Branches GL and reporting that there is no Other Branches file there.\r\nInform the assign personnel about the need docs for Other Branches Bank Recon.', 'Reviewing', '64ba1d6b64137625968'),
(42, 'admin', NULL, 0, 0, 'none', 'No Time-out', '64ba2325006b6281194'),
(43, 'Richard A. Araña', NULL, 0, 0, 'none', 'No Time-out', '64ba38903c2a1470775');

-- --------------------------------------------------------

--
-- Table structure for table `untitled_spreadsheet___sheet1__1_`
--

CREATE TABLE `untitled_spreadsheet___sheet1__1_` (
  `COL 3` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `untitled_spreadsheet___sheet1__1_`
--

INSERT INTO `untitled_spreadsheet___sheet1__1_` (`COL 3`) VALUES
('0'),
('0'),
('0'),
('0'),
('0'),
('0'),
('0'),
('14'),
('-'),
('20'),
('0'),
('156'),
('156'),
('520'),
('168'),
('152'),
('96'),
('86'),
('209'),
('222'),
('68'),
('240'),
('-'),
('80'),
('240'),
('336'),
('336'),
('280'),
('248'),
('282'),
('280'),
('486'),
('356'),
('392'),
('312'),
('480');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('admin','regular') NOT NULL DEFAULT 'regular',
  `position` enum('intern','employee') NOT NULL,
  `status` enum('out','in') NOT NULL DEFAULT 'out'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`, `position`, `status`) VALUES
(1, 'amt_admin@csk.com', 'test', 'Ichiji Seika', 'admin', 'employee', 'out'),
(2, 'gab@csk.com', 'doritonijika', 'Gabriel Terrence Garcia', 'regular', 'intern', 'out'),
(3, 'ava@csk.com', 'internAva001', 'Ava Ciara Lacambra', 'regular', 'intern', 'out'),
(4, 'shawn@csk.com', 'internShawn001', 'Shawn Danielle Espanola', 'regular', 'intern', 'out'),
(5, 'qcm.chrisimm@gmail.com', 'Viaba001', 'Viejay Abad', 'admin', 'employee', 'in'),
(6, 'admin', 'admin', 'admin', 'admin', 'employee', 'in'),
(7, 'ao.chrisimm@gmail.com', 'ao001', 'Jazz Jan Gregorio', 'admin', 'employee', 'out'),
(8, 'csk.akiranieva@gmail.com', 'akiranieva001', 'Louise Akira S. Nieva', 'regular', 'intern', 'out'),
(9, 'hrd.chrisimm@gmail.com', 'hrd001', 'Julich Enduma', 'admin', 'employee', 'out'),
(10, 'jr.bookkeeper.csk1@gmail.com', 'acctng001', 'Justine Grace Rosarda', 'admin', 'employee', 'out'),
(11, 'ceo.c2j@gmail.com', 'ceoc2j001', 'Cecilia D. Gregorio', 'admin', 'employee', 'out'),
(12, 'johnlgregorio@gmail.com', 'ea001', 'John Leonard D. Gregorio', 'admin', 'employee', 'out'),
(13, 'ceo.chrisimm@yahoo.com', 'ceo001', 'Cherizza Grace G. Peñafiel', 'admin', 'employee', 'in'),
(14, 'csk.VeronicaJoyceGabucan@gmail.com', 'veronicajoycegabucan001', 'Veronica Joyce Gabucan', 'regular', 'employee', 'out'),
(15, 'csk.karla@gmail.com', 'karla001', 'Karla Barja Alindayo', 'regular', 'employee', 'out'),
(16, 'csk.AlexandraJennPerjesYap@gmail.com', 'alexandrajennperjesyap001', 'Alexandra Jenn Perjes Yap', 'regular', 'employee', 'out'),
(17, 'It.chrisimm.c2j@gmail.com', 'john001', 'John Michael V. De Jose', 'regular', 'employee', 'out'),
(18, 'csk.patriciasayco@gmail.com', 'patriciasayco001', 'Patricia M. Sayco', 'regular', 'employee', 'out'),
(19, 'csk.JenniferEdanganOnnon@gmail.com', 'jennifer001', 'Jennifer Edangan Onnon', 'regular', 'employee', 'out'),
(20, 'csk.ChristineCalditaran@gmail.com', 'christine001', 'Christine N. Calditaran', 'regular', 'employee', 'out'),
(21, 'csk.JoshuaEsguerra@gmail.com', 'joshuaesguerra001', 'Joshua Esguerra', 'regular', 'intern', 'in'),
(22, 'csk.MaJhamilleAnnDapadap@gmail.com', 'majhamilleanndapadap001', 'Ma. Jhamille Ann M. Dapadap', 'regular', 'intern', 'out'),
(23, 'csk.CassandraSantos@gmail.com', 'cassandrasantos001', 'Cassandra Santos', 'regular', 'intern', 'out'),
(24, 'csk.JohnWilliamMariquina', 'JohnWilliam.Mariquina039', 'John William Mariquina', 'regular', 'intern', 'out'),
(25, 'csk.LinaHezarsa@gmail.com', 'linahezarsa001', 'Lina Hezarsa', 'regular', 'intern', 'out'),
(26, 'csk.johncarlosano@gmail.com', 'johncarlosano001', 'John Carlo Sano', 'regular', 'intern', 'out'),
(30, 'csk.RenaMaeManuel@gmail.com', 'rena001', 'Rena Mae Manuel', 'regular', 'intern', 'in'),
(31, 'csk.JereneTalatala@gmail.com', 'jerene001', 'Jerene Beatrice Talatala', 'regular', 'intern', 'in'),
(32, 'csk.JuspherPedrozo@gmail.com', 'juspher001', 'Juspher Pedrozo', 'regular', 'intern', 'in'),
(33, 'csk.EugeneGueta@gmail.com', 'eugene001', 'Eugene Gueta', 'regular', 'intern', 'out'),
(34, 'csk.PatrickLirom@gmail.com', 'patrick001', 'Patrick Lirom', 'regular', 'intern', 'out'),
(35, 'csk.DaniellaLangres@gmail.com', 'daniella001', 'Daniella Langres', 'regular', 'intern', 'in'),
(36, 'gnagcash@gmail.com', 'rubelyn001', 'Rubelyn De Jose', 'regular', 'employee', 'out'),
(37, 'gallegojohnherald@gmail.com', 'johnherald001', 'John Herald Gallego', 'regular', 'employee', 'out'),
(38, 'charichellelaureles@gmail.com', 'charichelle001', 'Charichelle V. Laureles', 'regular', 'employee', 'out'),
(39, 'cfrincez@gmail.com', 'frincez001', 'Frincez Devillez Convento', 'regular', 'employee', 'out'),
(40, 'milkypagobo9@gmail.com', 'milky001', 'Milky N. Pagobo', 'regular', 'employee', 'out'),
(41, '13jrb08.chrisimm@gmail.com', 'richard001', 'Richard A. Araña', 'regular', 'employee', 'in'),
(42, '134jbr.csk@gmail.com', 'rosyl001', 'Rosyl Vallente Perez', 'regular', 'employee', 'out'),
(43, 'csk.ReneLetegio@gmail.com', 'reneletegio001', 'Rene Q. Letegio', 'regular', 'intern', 'out'),
(44, 'csk.JicelAnnIyog@gmail.com', 'jicelanniyog001', 'Jicel Ann V. Iyog', 'regular', 'intern', 'out'),
(45, 'csk.XyrelGenio@gmail.com', 'xyrelgenio001', 'Xyrel D. Genio', 'regular', 'intern', 'out'),
(46, 'csk.ChristianKennethCalzada@gmail.com', 'christiankennethcalzada001', 'Christian Kenneth Calzada', 'regular', 'intern', 'out'),
(47, 'csk.MarkAnthonyGarong@gmail.com', 'markanthonygarong001', 'Mark Anthony O. Garong', 'regular', 'intern', 'out'),
(48, 'csk.MeryielJoelelenCordero@gmail.com', 'meryieljoelelencordero001', 'Meryiel Joelelen D. Cordero', 'regular', 'intern', 'out'),
(49, 'csk.JoshuaMarkBulanon@gmail.com', 'joshuamarkbulanon001', 'Joshua Mark Bulanon', 'regular', 'intern', 'out'),
(50, 'csk.RommelAcob@gmail.com', 'rommelacob001', 'Rommel Acob', 'regular', 'intern', 'in'),
(51, 'csk.MarkJayClemente@gmail.com', 'markjayclemente001', 'Mark Jay G. Clemente ', 'regular', 'intern', 'out'),
(52, 'csk.NickoReanzares@gmail.com', 'nickoreanzares001', 'Nicko V. Reanzares', 'regular', 'intern', 'out'),
(53, 'csk.PatrickBacabis@gmail.com', 'patrickbacabis016', 'Patrick Shane A. Bacabis', 'regular', 'intern', 'out');

--
-- Indexes for dumped tables
--


--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Indexes for table `task_activities`
--
ALTER TABLE `tasks_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `task_activities`
--
ALTER TABLE `tasks_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;


--
-- Indexes for table `emp_info`
--
ALTER TABLE `emp_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `int_info`
--
ALTER TABLE `int_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_in`
--
ALTER TABLE `time_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_out`
--
ALTER TABLE `time_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emp_info`
--
ALTER TABLE `emp_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `int_info`
--
ALTER TABLE `int_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_in`
--
ALTER TABLE `time_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `time_out`
--
ALTER TABLE `time_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
