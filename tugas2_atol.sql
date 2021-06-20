-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2021 at 10:24 PM
-- Server version: 10.4.12-MariaDB
-- PHP Version: 7.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `denzkkur_tugas2`
--

-- --------------------------------------------------------

--
-- Table structure for table `group_album`
--

CREATE TABLE `group_album` (
  `id_album` int(10) UNSIGNED NOT NULL,
  `id_group` int(10) UNSIGNED NOT NULL,
  `title_album` varchar(40) NOT NULL,
  `jenis` enum('Mini Album','Full Album') NOT NULL,
  `tgl_rilis` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_album`
--

INSERT INTO `group_album` (`id_album`, `id_group`, `title_album`, `jenis`, `tgl_rilis`) VALUES
(1, 1, 'COLOR*IZ', 'Mini Album', '2018-10-29'),
(2, 1, 'HEART*IZ', 'Mini Album', '2019-04-01'),
(3, 1, 'BLOOZ*IZ', 'Full Album', '2020-02-17'),
(4, 1, 'Oneiric Diary', 'Mini Album', '2020-06-15'),
(5, 1, 'One-reeler/Act IV', 'Mini Album', '2020-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `group_member`
--

CREATE TABLE `group_member` (
  `id_name` int(10) UNSIGNED NOT NULL,
  `id_group` int(10) UNSIGNED NOT NULL,
  `nama_member` varchar(40) NOT NULL,
  `tgl_lahir` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_member`
--

INSERT INTO `group_member` (`id_name`, `id_group`, `nama_member`, `tgl_lahir`) VALUES
(1, 1, 'Kwon Eun Bi', '1995-09-27'),
(2, 1, 'Miyawaki Sakura', '1998-03-19'),
(3, 1, 'Kang Hye Won', '1999-06-05'),
(4, 1, 'Choi Yena', '1999-09-29'),
(5, 1, 'Lee Chae Yeon', '2000-01-11'),
(6, 1, 'Kim Chae Won', '2000-08-11'),
(7, 1, 'Kim Min Ju', '2001-02-05'),
(8, 1, 'Yabuki Nako', '2001-06-18'),
(9, 1, 'Honda Hitomi', '2001-10-06'),
(10, 1, 'Jo Yu Ri', '2001-10-22'),
(11, 1, 'An Yu Jin', '2003-09-01'),
(12, 1, 'Jang Won Young', '2004-08-31'),
(13, 2, 'Nayeon', '1995-09-22'),
(14, 2, 'Jeongyeon', '1996-11-01'),
(15, 2, 'Momo', '1996-11-09'),
(16, 2, 'Sana', '1996-12-29'),
(17, 2, 'Jihyo', '1996-02-01'),
(18, 2, 'Mina', '1997-03-24'),
(19, 2, 'Dahyun', '1998-05-28'),
(20, 2, 'Chaeyoung', '1999-04-23'),
(21, 2, 'Tzuyu', '1999-06-14');

-- --------------------------------------------------------

--
-- Table structure for table `group_name`
--

CREATE TABLE `group_name` (
  `id_group` int(10) UNSIGNED NOT NULL,
  `nama_group` varchar(40) NOT NULL,
  `tgl_debut` date NOT NULL,
  `agensi` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_name`
--

INSERT INTO `group_name` (`id_group`, `nama_group`, `tgl_debut`, `agensi`) VALUES
(1, 'IZ*ONE', '2018-10-29', 'Off The Record'),
(2, 'TWICE', '2015-10-20', 'JYP Entertainment'),
(3, 'ITZY', '2019-02-12', 'JYP Entertainment'),
(4, 'Red Velvet', '2014-08-01', 'SM Enternainment'),
(5, 'Mamamoo', '2014-06-19', 'RBW');

-- --------------------------------------------------------

--
-- Table structure for table `group_song`
--

CREATE TABLE `group_song` (
  `id_song` int(10) UNSIGNED NOT NULL,
  `id_group` int(10) UNSIGNED NOT NULL,
  `id_album` int(11) UNSIGNED NOT NULL,
  `title_song` varchar(40) NOT NULL,
  `main_track` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_song`
--

INSERT INTO `group_song` (`id_song`, `id_group`, `id_album`, `title_song`, `main_track`) VALUES
(1, 1, 1, 'COLORS', 0),
(2, 1, 1, 'O\'My!', 0),
(3, 1, 1, 'La Vie en Rose', 1),
(4, 1, 1, 'Memory', 0),
(5, 1, 1, 'We Together (IZ*ONE Ver.)', 0),
(6, 1, 1, 'Suki ni Nacchau Dar┼ì? (IZ*ONE ver.) ', 0),
(7, 1, 1, 'Yume wo Miteiru Aida (IZ*ONE ver.)', 0),
(8, 1, 1, 'Nekkoya (IZ*ONE Ver.) (CD Only)', 0),
(9, 1, 2, 'Hey, Bae. Like. It.', 0),
(10, 1, 2, 'Violeta', 1),
(11, 1, 2, 'Highlight', 0),
(12, 1, 2, 'Really Like You', 0),
(13, 1, 2, 'Airplane', 0),
(14, 1, 2, 'Up', 0),
(15, 1, 2, 'NEKONI NARITAI (Korean Version)', 0),
(16, 1, 2, 'GOKIGEN SAYONARA (Korean Version)', 0),
(17, 1, 3, 'EYES', 0),
(18, 1, 3, 'FIESTA', 1),
(19, 1, 3, 'DREAMLIKE', 0),
(20, 1, 3, 'AYAYAYA', 0),
(21, 1, 3, 'SO CURIOUS', 0),
(22, 1, 3, 'SPACESHIP', 0),
(23, 1, 3, 'DESTINY', 0),
(24, 1, 3, 'YOU & I', 0),
(25, 1, 3, 'DAYDREAM', 0),
(26, 1, 3, 'PINK BLUSHER', 0),
(27, 1, 3, 'SOMEDAY', 0),
(28, 1, 3, 'OPEN YOUR EYES', 0),
(29, 1, 4, 'Welcome', 0),
(30, 1, 4, 'Secret Story of the Swan', 1),
(31, 1, 4, 'Pretty', 0),
(32, 1, 4, 'Merry-Go-Round', 0),
(33, 1, 4, 'Rococo', 0),
(34, 1, 4, 'With*One', 0),
(35, 1, 4, 'Secret Story of The Swan (Japanese Ver.)', 0),
(36, 1, 4, 'Merry-Go-Round (Japanese Ver.)', 0),
(37, 1, 5, 'Mise-en-Sc├¿ne', 0),
(38, 1, 5, 'Panorama', 1),
(39, 1, 5, 'Island', 0),
(40, 1, 5, 'Sequence', 0),
(41, 1, 5, 'O Sole Mio', 0),
(42, 1, 5, 'Slow Journey', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `user_name` varchar(20) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`user_name`, `user_id`, `user_password`) VALUES
('admin', 1, '407c6798fe20fd5d75de4a233c156cc0fce510e3'),
('notmint', 4, '407c6798fe20fd5d75de4a233c156cc0fce510e3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group_album`
--
ALTER TABLE `group_album`
  ADD PRIMARY KEY (`id_album`),
  ADD KEY `id_group` (`id_group`);

--
-- Indexes for table `group_member`
--
ALTER TABLE `group_member`
  ADD PRIMARY KEY (`id_name`),
  ADD KEY `id_group` (`id_group`);

--
-- Indexes for table `group_name`
--
ALTER TABLE `group_name`
  ADD PRIMARY KEY (`id_group`);

--
-- Indexes for table `group_song`
--
ALTER TABLE `group_song`
  ADD PRIMARY KEY (`id_song`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_album` (`id_album`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group_album`
--
ALTER TABLE `group_album`
  MODIFY `id_album` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `group_member`
--
ALTER TABLE `group_member`
  MODIFY `id_name` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `group_name`
--
ALTER TABLE `group_name`
  MODIFY `id_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `group_song`
--
ALTER TABLE `group_song`
  MODIFY `id_song` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_album`
--
ALTER TABLE `group_album`
  ADD CONSTRAINT `group_album_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group_name` (`id_group`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `group_member`
--
ALTER TABLE `group_member`
  ADD CONSTRAINT `group_member_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group_name` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `group_song`
--
ALTER TABLE `group_song`
  ADD CONSTRAINT `group_song_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group_name` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_song_ibfk_2` FOREIGN KEY (`id_album`) REFERENCES `group_album` (`id_album`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
