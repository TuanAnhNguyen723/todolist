-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 09, 2024 lúc 05:34 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `todolistdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `grouptask`
--

CREATE TABLE `grouptask` (
  `grouptask_id` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `percent` float NOT NULL,
  `state` varchar(255) NOT NULL,
  `time_s` date NOT NULL,
  `time_e` date NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time_start` date NOT NULL,
  `time_end` date NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `grouptask_id` varchar(10) NOT NULL,
  `star` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `task`
--

INSERT INTO `task` (`task_id`, `title`, `description`, `time_start`, `time_end`, `checked`, `user_id`, `grouptask_id`, `star`) VALUES
(26, 'task89', 'test89', '2024-09-02', '2024-09-11', 1, 'some_user_', 'some_group', 1),
(28, 'task1 plus  cc', 'test1 plus cc', '2024-10-08', '2024-09-10', 1, 'some_user_', 'some_group', 1),
(30, 'task66', 'test66', '2024-09-04', '2024-09-13', 1, 'some_user_', 'some_group', 1),
(36, 'task69', 'test69\r\n', '2024-10-15', '2024-10-17', 1, 'some_user_', 'some_group', 1),
(38, 'task7', 'test7', '2024-09-04', '2024-10-15', 1, 'some_user_', 'some_group', 1),
(42, 'Doanh', 'đần', '2024-10-15', '2024-10-14', 0, 'some_user_', 'some_group', 0),
(46, 'Phú An', 'An ', '2024-10-09', '2024-10-14', 1, 'some_user_', 'some_group', 0),
(56, 'Chinh Bui', 'tft', '2024-10-16', '2024-10-21', 1, 'some_user_', 'some_group', 0),
(58, '              anh duc 69            ', 'anh duc 69', '2024-09-02', '2024-10-08', 1, 'some_user_', 'some_group', 1),
(59, '                            test668                        ', 'test668', '2024-09-03', '2024-10-08', 0, 'some_user_', 'some_group', 1),
(61, 'chinh tung', 'tung chinh', '2024-10-15', '2024-10-08', 0, 'some_user_', 'some_group', 0),
(62, 'new task 33', 'new test 3 3', '2024-10-09', '2024-10-11', 0, 'some_user_', 'some_group', 1),
(63, 'hhh', 'hhh', '2024-09-02', '2024-10-07', 0, 'some_user_', 'some_group', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `user_id` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
