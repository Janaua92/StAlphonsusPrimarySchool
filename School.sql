-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2025 at 09:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `School`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `Admin_ID` int(16) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Address` text NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Background_Check` text NOT NULL,
  `Salary_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`Admin_ID`, `Name`, `Email`, `Username`, `Password`, `Address`, `Phone_Number`, `Background_Check`, `Salary_ID`) VALUES
(1, 'Ivan Smith', 'Ivan_001.admin.sta.ac.uk', 'Ivan_001', 'Password1', '33 Elmwood Road, M20 6DB', '+44 7482607607', 'Good ', 12),
(2, 'Sarah Johnson', 'Sarah_002.admin.sta.ac.uk', 'Sarah_002', 'Password2', '45 Maple Street, M21 7AB', '+44 7482607608', 'Good', 11),
(3, 'Tom Brown', 'Tom_003.admin.sta.ac.uk', 'Tom_003', 'Password3', '12 Oak Avenue, M22 8CD', '+44 7482607609', 'Good', 10);

UPDATE `Admin` 
SET `Password` = '$2y$10$eImiTXuWVxfM37uY4JANjQe5a2b5y1Z1lFz5Z9zFZ4x0Z9zFZ4x0Z9' 
WHERE `Username` = 'Ivan_001';

SELECT `Password` FROM `Admin` WHERE `Username` = 'Ivan_001';

-- --------------------------------------------------------

--
-- Table structure for table `Book_Borrowed`
--

CREATE TABLE `Book_Borrowed` (
  `Borrowed_ID` int(16) NOT NULL,
  `Book_ID` int(16) NOT NULL,
  `Pupil_ID` int(16) NOT NULL,
  `Date_Borrowed` date NOT NULL,
  `Due_Date` date NOT NULL,
  `Return_Date` date DEFAULT NULL,
  `Status` enum('Active','Returned','Overdue','') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Book_Borrowed`
--

INSERT INTO `Book_Borrowed` (`Borrowed_ID`, `Book_ID`, `Pupil_ID`, `Date_Borrowed`, `Due_Date`, `Return_Date`, `Status`) VALUES
(1, 101, 201, '2025-04-01', '2025-04-15', NULL, 'Active'),
(2, 102, 202, '2025-03-25', '2025-04-08', '2025-04-07', 'Returned'),
(3, 103, 203, '2025-03-20', '2025-04-03', NULL, 'Overdue');

-- --------------------------------------------------------

--
-- Table structure for table `Classes`
--

CREATE TABLE `Classes` (
  `Class_ID` int(16) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Capacity` int(16) NOT NULL,
  `Teacher_ID` int(16) NOT NULL,
  `Pupils_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Classes`
--

INSERT INTO `Classes` (`Class_ID`, `Name`, `Capacity`, `Teacher_ID`, `Pupils_ID`) VALUES
(1, 'Year 1', 30, 301, 201),
(2, 'Year 2', 30, 302, 202),
(3, 'Year 3', 30, 303, 203);

-- --------------------------------------------------------

--
-- Table structure for table `Classes_TAssistant`
--

CREATE TABLE `Classes_TAssistant` (
  `Class_ID` int(16) NOT NULL,
  `TAssistant_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Classes_TAssistant`
--

INSERT INTO `Classes_TAssistant` (`Class_ID`, `TAssistant_ID`) VALUES
(1, 401),
(2, 402),
(3, 403);

-- --------------------------------------------------------

--
-- Table structure for table `Libary_Books`
--

CREATE TABLE `Libary_Books` (
  `Book_ID` int(16) NOT NULL,
  `Title` text NOT NULL,
  `Author` varchar(100) NOT NULL,
  `Ganre` varchar(50) NOT NULL,
  `Published_Year` int(16) NOT NULL,
  `Availability` enum('Available','Checked Out','Lost','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Libary_Books`
--

INSERT INTO `Libary_Books` (`Book_ID`, `Title`, `Author`, `Ganre`, `Published_Year`, `Availability`) VALUES
(101, 'Mathematics for Beginners', 'John Doe', 'Education', 2020, 'Available'),
(102, 'Science Experiments', 'Jane Smith', 'Education', 2019, 'Checked Out'),
(103, 'History of the UK', 'Emily White', 'History', 2018, 'Lost');

-- --------------------------------------------------------

--
-- Table structure for table `Parents/Guardians`
--

CREATE TABLE `Parents/Guardians` (
  `Parent_ID` int(16) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` text NOT NULL,
  `Phone_Number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Parents/Guardians`
--

INSERT INTO `Parents/Guardians` (`Parent_ID`, `Name`, `Email`, `Address`, `Phone_Number`) VALUES
(1, 'Michael Green', 'michael.green@example.com', '78 Birch Lane, M23 9EF', '+44 7482607610'),
(2, 'Laura Blue', 'laura.blue@example.com', '56 Cedar Road, M24 1GH', '+44 7482607611'),
(3, 'David Black', 'david.black@example.com', '34 Pine Street, M25 2IJ', '+44 7482607612');

-- --------------------------------------------------------

--
-- Table structure for table `Pupils`
--

CREATE TABLE `Pupils` (
  `Pupils_ID` int(16) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Age` int(16) NOT NULL,
  `Address` text NOT NULL,
  `Medical_Info` text DEFAULT NULL,
  `Class_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Pupils`
--

INSERT INTO `Pupils` (`Pupils_ID`, `Name`, `Email`, `Username`, `Password`, `Age`, `Address`, `Medical_Info`, `Class_ID`) VALUES
(201, 'Alice Brown', 'alice.brown@example.com', 'Alice_201', 'Password4', 6, '12 Oak Avenue, M22 8CD', 'Asthma', 1),
(202, 'James White', 'james.white@example.com', 'James_202', 'Password5', 7, '45 Maple Street, M21 7AB', NULL, 2),
(203, 'Emma Green', 'emma.green@example.com', 'Emma_203', 'Password6', 8, '78 Birch Lane, M23 9EF', 'Peanut Allergy', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Pupils_Parent`
--

CREATE TABLE `Pupils_Parent` (
  `Pupils_ID` int(16) NOT NULL,
  `Parent_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Pupils_Parent`
--

INSERT INTO `Pupils_Parent` (`Pupils_ID`, `Parent_ID`) VALUES
(201, 1),
(202, 2),
(203, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Salary`
--

CREATE TABLE `Salary` (
  `Salary_ID` int(16) NOT NULL,
  `Annual_Amount` decimal(10,2) NOT NULL,
  `Role` enum('Teacher','Teaching Assistant','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Salary`
--

INSERT INTO `Salary` (`Salary_ID`, `Annual_Amount`, `Role`) VALUES
(1, 31000.00, 'Teacher'),
(2, 33000.00, 'Teacher'),
(3, 35000.00, 'Teacher'),
(4, 38000.00, 'Teacher'),
(5, 48000.00, 'Teacher'),
(6, 17000.00, 'Teaching Assistant'),
(7, 20000.00, 'Teaching Assistant'),
(8, 25000.00, 'Teaching Assistant'),
(9, 29000.00, 'Teaching Assistant'),
(10, 23000.00, 'Admin'),
(11, 25000.00, 'Admin'),
(12, 28000.00, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `Teacher`
--

CREATE TABLE `Teacher` (
  `Teacher_ID` int(16) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Address` text NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Background_Check` text NOT NULL,
  `Salary_ID` int(16) NOT NULL,
  `Class_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Teacher`
--

INSERT INTO `Teacher` (`Teacher_ID`, `Name`, `Email`, `Username`, `Password`, `Address`, `Phone_Number`, `Background_Check`, `Salary_ID`, `Class_ID`) VALUES
(301, 'Mr. Andrew Taylor', 'andrew.taylor@example.com', 'Andrew_301', 'Password7', '23 Willow Drive, M26 3KL', '+44 7482607613', 'Good', 1, 1),
(302, 'Ms. Rachel Adams', 'rachel.adams@example.com', 'Rachel_302', 'Password8', '67 Elmwood Road, M20 6DB', '+44 7482607614', 'Good', 2, 2),
(303, 'Mrs. Sophie Brown', 'sophie.brown@example.com', 'Sophie_303', 'Password9', '89 Maple Street, M21 7AB', '+44 7482607615', 'Good', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Teaching_Assistant`
--

CREATE TABLE `Teaching_Assistant` (
  `TAssistant_ID` int(16) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Address` text NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Background_Check` text NOT NULL,
  `Salary_ID` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Teaching_Assistant`
--

INSERT INTO `Teaching_Assistant` (`TAssistant_ID`, `Name`, `Email`, `Username`, `Password`, `Address`, `Phone_Number`, `Background_Check`, `Salary_ID`) VALUES
(401, 'Anna Wilson', 'anna.wilson@example.com', 'Anna_401', 'Password10', '34 Pine Street, M25 2IJ', '+44 7482607616', 'Good', 6),
(402, 'Tom Harris', 'tom.harris@example.com', 'Tom_402', 'Password11', 'Good', 7),
(403, 'Lucy Evans', 'lucy.evans@example.com', 'Lucy_403', 'Password12', '78 Birch Lane, M23 9EF', 'Good', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Email` (`Email`,`Username`,`Phone_Number`),
  ADD KEY `Salary_ID` (`Salary_ID`);

--
-- Indexes for table `Book_Borrowed`
--
ALTER TABLE `Book_Borrowed`
  ADD PRIMARY KEY (`Borrowed_ID`),
  ADD KEY `Book_ID` (`Book_ID`),
  ADD KEY `Pupil_ID` (`Pupil_ID`);

--
-- Indexes for table `Classes`
--
ALTER TABLE `Classes`
  ADD PRIMARY KEY (`Class_ID`),
  ADD UNIQUE KEY `Pupils` (`Pupils_ID`),
  ADD UNIQUE KEY `Teacher_ID` (`Teacher_ID`,`Pupils_ID`);

--
-- Indexes for table `Classes_TAssistant`
--
ALTER TABLE `Classes_TAssistant`
  ADD KEY `Class_ID` (`Class_ID`),
  ADD KEY `TAssistant_ID` (`TAssistant_ID`);

--
-- Indexes for table `Libary_Books`
--
ALTER TABLE `Libary_Books`
  ADD PRIMARY KEY (`Book_ID`);

--
-- Indexes for table `Parents/Guardians`
--
ALTER TABLE `Parents/Guardians`
  ADD PRIMARY KEY (`Parent_ID`),
  ADD UNIQUE KEY `Email` (`Email`,`Phone_Number`);

--
-- Indexes for table `Pupils`
--
ALTER TABLE `Pupils`
  ADD PRIMARY KEY (`Pupils_ID`),
  ADD UNIQUE KEY `Email` (`Email`,`Username`),
  ADD KEY `Test` (`Class_ID`);

--
-- Indexes for table `Pupils_Parent`
--
ALTER TABLE `Pupils_Parent`
  ADD KEY `test2` (`Parent_ID`),
  ADD KEY `tes3` (`Pupils_ID`);

--
-- Indexes for table `Salary`
--
ALTER TABLE `Salary`
  ADD PRIMARY KEY (`Salary_ID`);

--
-- Indexes for table `Teacher`
--
ALTER TABLE `Teacher`
  ADD PRIMARY KEY (`Teacher_ID`),
  ADD UNIQUE KEY `Email` (`Email`,`Username`,`Phone_Number`),
  ADD KEY `test4` (`Class_ID`),
  ADD KEY `Salary_ID` (`Salary_ID`);

--
-- Indexes for table `Teaching_Assistant`
--
ALTER TABLE `Teaching_Assistant`
  ADD PRIMARY KEY (`TAssistant_ID`),
  ADD UNIQUE KEY `Email` (`Email`,`Username`,`Phone_Number`),
  ADD KEY `Salary_ID` (`Salary_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admin`
--
ALTER TABLE `Admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`Salary_ID`) REFERENCES `Salary` (`Salary_ID`);

--
-- Constraints for table `Book_Borrowed`
--
ALTER TABLE `Book_Borrowed`
  ADD CONSTRAINT `book_borrowed_ibfk_1` FOREIGN KEY (`Book_ID`) REFERENCES `Libary_Books` (`Book_ID`),
  ADD CONSTRAINT `book_borrowed_ibfk_2` FOREIGN KEY (`Pupil_ID`) REFERENCES `Pupils` (`Pupils_ID`);

--
-- Constraints for table `Classes`
--
ALTER TABLE `Classes`
  ADD CONSTRAINT `test5` FOREIGN KEY (`Teacher_ID`) REFERENCES `Teacher` (`Teacher_ID`),
  ADD CONSTRAINT `test6` FOREIGN KEY (`Pupils_ID`) REFERENCES `Pupils` (`Pupils_ID`);

--
-- Constraints for table `Classes_TAssistant`
--
ALTER TABLE `Classes_TAssistant`
  ADD CONSTRAINT `classes_tassistant_ibfk_1` FOREIGN KEY (`Class_ID`) REFERENCES `Classes` (`Class_ID`),
  ADD CONSTRAINT `classes_tassistant_ibfk_2` FOREIGN KEY (`TAssistant_ID`) REFERENCES `Teaching_Assistant` (`TAssistant_ID`);

--
-- Constraints for table `Pupils`
--
ALTER TABLE `Pupils`
  ADD CONSTRAINT `Test` FOREIGN KEY (`Class_ID`) REFERENCES `Classes` (`Class_ID`);

--
-- Constraints for table `Pupils_Parent`
--
ALTER TABLE `Pupils_Parent`
  ADD CONSTRAINT `tes3` FOREIGN KEY (`Pupils_ID`) REFERENCES `Pupils` (`Pupils_ID`),
  ADD CONSTRAINT `test2` FOREIGN KEY (`Parent_ID`) REFERENCES `Parents/Guardians` (`Parent_ID`);

--
-- Constraints for table `Teacher`
--
ALTER TABLE `Teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`Salary_ID`) REFERENCES `Salary` (`Salary_ID`),
  ADD CONSTRAINT `test4` FOREIGN KEY (`Class_ID`) REFERENCES `Classes` (`Class_ID`);

--
-- Constraints for table `Teaching_Assistant`
--
ALTER TABLE `Teaching_Assistant`
  ADD CONSTRAINT `teaching_assistant_ibfk_1` FOREIGN KEY (`Salary_ID`) REFERENCES `Salary` (`Salary_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
