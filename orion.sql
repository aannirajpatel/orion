-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2019 at 12:15 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orion`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentreplies`
--

CREATE TABLE `commentreplies` (
  `replyid` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `replytext` text NOT NULL,
  `dateofreply` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commentreplies`
--

INSERT INTO `commentreplies` (`replyid`, `commentid`, `uid`, `replytext`, `dateofreply`) VALUES
(1, 1, 2, 'This is a trial reply.', '2019-05-25 12:56:40'),
(2, 2, 2, 'Lets try a reply', '2019-05-25 13:36:53'),
(3, 2, 2, '', '2019-05-25 13:37:39'),
(4, 2, 2, 'This is one big reply. This is one big reply. This is one big reply. This is one big reply. This is one big reply. This is one big reply. This is one big reply. This is one big reply.', '2019-05-25 13:42:38'),
(5, 4, 2, 'Replying', '2019-05-25 14:03:54'),
(6, 3, 2, 'OK', '2019-05-25 14:04:07'),
(7, 6, 2, 'This is a reply', '2019-05-25 14:07:02'),
(8, 6, 2, 'Hello', '2019-05-25 16:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `completedcourses`
--

CREATE TABLE `completedcourses` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateofcomplete` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `completedcourses`
--

INSERT INTO `completedcourses` (`cid`, `uid`, `dateofcomplete`) VALUES
(4, 2, '2019-05-26 14:51:27'),
(6, 2, '2019-05-26 12:07:51'),
(10, 2, '2019-05-26 12:07:34'),
(11, 2, '2019-05-26 15:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `cid` int(11) NOT NULL,
  `cname` text NOT NULL,
  `cdesc` text NOT NULL,
  `dateofcreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creatoruid` int(11) NOT NULL,
  `cost` float NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '0',
  `dateofpublish` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`cid`, `cname`, `cdesc`, `dateofcreation`, `creatoruid`, `cost`, `published`, `dateofpublish`) VALUES
(1, 'Bootstrap Fundamentals', 'Learn B4 with Aan!', '2019-05-15 16:33:29', 1, 0, 0, '2019-05-20 15:21:12'),
(3, 'JavaScript Fundamentals', 'Learn JS with Aan!', '2019-05-15 16:46:54', 1, 0, 1, '2019-05-26 10:36:04'),
(4, 'HTML Fundamentals', 'A fun HTML course created by Mr. Aan Patel hoho3wwaa. <b>boldie hehe</b><div><b>xfgxfgo <u>abcd</u></b></div>', '2019-05-15 17:45:00', 1, 0, 1, '2019-05-22 12:27:59'),
(5, 'Advanced PHP with Aan Patel', 'Gain some cool web dev skills with Aan!', '2019-05-24 14:22:07', 1, 100, 0, NULL),
(6, 'Node.JS Fundamentals', 'Hello <b>WORLD</b>', '2019-05-24 14:55:31', 1, 150, 1, '2019-05-26 10:32:52'),
(9, 'The Perfect DevOps Course', 'A great DevOps course by Aan Patel.', '2019-05-24 15:28:22', 1, 220, 0, NULL),
(10, 'ExpressJS To Perfection', 'Learn Advanced Express', '2019-05-25 16:31:01', 1, 300, 1, '2019-05-26 10:40:50'),
(11, 'Trial', 'Trial course', '2019-05-26 15:16:26', 1, 200, 1, '2019-05-26 15:17:18');

-- --------------------------------------------------------

--
-- Table structure for table `courseanswers`
--

CREATE TABLE `courseanswers` (
  `aid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `answer` text NOT NULL,
  `dateofanswer` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coursequestions`
--

CREATE TABLE `coursequestions` (
  `qid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `question` text NOT NULL,
  `dateofask` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpurchases`
--

CREATE TABLE `cpurchases` (
  `pid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cost` float NOT NULL,
  `dateofpurchase` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_id` varchar(64) NOT NULL,
  `order_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpurchases`
--

INSERT INTO `cpurchases` (`pid`, `cid`, `uid`, `cost`, `dateofpurchase`, `cust_id`, `order_id`) VALUES
(1, 4, 2, 250, '2019-05-19 15:38:36', '', ''),
(2, 4, 2, 225, '2019-04-19 15:40:44', '', ''),
(3, 3, 2, 0, '2019-05-24 14:02:37', 'CUST200000000000000000000000000000000000000000000000000000000000', 'ORDER2C3T15586867570000000000000000000000000000000'),
(9, 5, 2, 100, '2019-05-24 14:54:48', 'CUST000000000000000000000000000000000000000000000000000000000002', 'ORDER00000000000000000000000000000002C5T1558689878'),
(10, 6, 2, 150, '2019-05-24 15:05:01', 'CUST000000000000000000000000000000000000000000000000000000000002', 'ORDER00000000000000000000000000000002C6T1558690489'),
(11, 9, 2, 220, '2019-05-24 15:29:56', 'CUST000000000000000000000000000000000000000000000000000000000002', 'ORDER00000000000000000000000000000002C9T1558691976'),
(12, 10, 2, 300, '2019-05-25 16:34:40', 'CUST000000000000000000000000000000000000000000000000000000000002', 'ORDER0000000000000000000000000000002C10T1558782256');

-- --------------------------------------------------------

--
-- Table structure for table `cresources`
--

CREATE TABLE `cresources` (
  `rid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `rtype` int(1) NOT NULL,
  `rdata` longtext,
  `rtext` text,
  `raddr` text,
  `rdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cresources`
--

INSERT INTO `cresources` (`rid`, `cid`, `section`, `rtype`, `rdata`, `rtext`, `raddr`, `rdate`) VALUES
(2, 4, 1, 0, 'Till now, HTML 4 was being used. From now, this course will cover HTML 5 also. Enjoy!', 'A Note To New Students', NULL, '2019-05-18 13:55:19'),
(3, 4, 1, 2, NULL, 'Doc2', './res/4/1-3.txt', '2019-05-18 14:24:23'),
(4, 4, 1, 4, NULL, 'Google Link', 'https://www.google.com/search?q=hello+world', '2019-05-18 16:07:21'),
(5, 4, 2, 3, NULL, 'My Youtube Video2', 'https://www.youtube.com/watch?v=zBkVCpbNnkU', '2019-05-18 16:35:34'),
(6, 4, 1, 2, NULL, 'Aan', './res/4/1-6. Delhibabu.pdf', '2019-05-18 18:01:18'),
(7, 4, 2, 1, NULL, 'Myvideo2', './res/4/2-7.avi', '2019-05-18 18:03:27'),
(8, 3, 1, 1, NULL, 'Lecture 101', './res/3/1-8.avi', '2019-05-22 11:12:45'),
(9, 5, 1, 1, NULL, 'Hello World', './res/5/1-9.avi', '2019-05-24 14:24:51'),
(10, 10, 1, 1, NULL, 'Myvideo', './res/10/1-10.avi', '2019-05-25 16:32:23'),
(11, 6, 1, 0, 'This note is to <b>introduce </b>you to Node.JS and its environment.', 'Intronote', NULL, '2019-05-26 11:45:05'),
(12, 11, 1, 0, 'This is note', 'Note', NULL, '2019-05-26 15:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `creviews`
--

CREATE TABLE `creviews` (
  `crid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rtitle` text NOT NULL,
  `rdesc` text NOT NULL,
  `rating` int(1) NOT NULL,
  `dateofreview` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `creviews`
--

INSERT INTO `creviews` (`crid`, `cid`, `uid`, `rtitle`, `rdesc`, `rating`, `dateofreview`) VALUES
(1, 10, 2, 'Bad', 'Bad course. Bad course. Bad course. Bad course. Bad course. Bad course. Bad course.', 1, '2019-05-26 12:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `csections`
--

CREATE TABLE `csections` (
  `cid` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `sname` text NOT NULL,
  `sdesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `csections`
--

INSERT INTO `csections` (`cid`, `section`, `sname`, `sdesc`) VALUES
(1, 1, 'Intro to this course', 'Course intro section <b>description...</b>'),
(3, 1, 'Intro to JS', 'Intro section for JS.'),
(4, 1, 'Introduction to HTML Tags', '                                                                                                                                Intro to HTML tags...2                            <div><br></div><div>T<b>dfsfs</b></div>'),
(4, 2, 'Making tables in HTML', '                                This is a section which will teach you the usage of tables in <b><u>HTML</u></b>.                            '),
(5, 1, 'Hello World', 'Here we\'ll setup the environment for PHP programming and write a hello world code! Can\'t wait to get started!'),
(6, 1, 'Intro to Node', 'This is an intro'),
(10, 1, 'Intro', 'Section desc'),
(11, 1, 'Section Intro', 'Section this');

-- --------------------------------------------------------

--
-- Table structure for table `cstudents`
--

CREATE TABLE `cstudents` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateofjoining` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cstudents`
--

INSERT INTO `cstudents` (`cid`, `uid`, `dateofjoining`) VALUES
(1, 2, '2019-05-24 14:19:41'),
(3, 2, '2019-05-24 14:15:30'),
(4, 2, '2019-05-19 11:01:54'),
(5, 2, '2019-05-24 14:54:48'),
(6, 2, '2019-05-24 15:05:01'),
(9, 2, '2019-05-24 15:29:56'),
(10, 2, '2019-05-25 16:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `csyllabus`
--

CREATE TABLE `csyllabus` (
  `cid` int(11) NOT NULL,
  `csyllabus` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `csyllabus`
--

INSERT INTO `csyllabus` (`cid`, `csyllabus`) VALUES
(1, 'This is a syllabys.'),
(3, 'Learning JS is awesome.'),
(4, 'This is a syllabus34 yotzi<font face=\"georgia\">fdffa</font><font face=\"impact\">Sdafa</font><font face=\"comic sans ms\">afdfs</font><div><font face=\"comic sans ms\">Ggg<font size=\"5\">dfefsfsrwe</font></font></div>'),
(5, '<ol><li>Hello World</li><li>Variables</li><li>Calculations</li><li>String manipulation</li><li>Arrays</li><li>Loops</li><li>Functions</li><li>Control statements: If, else, elseif, and switch</li><li>Superglobals</li><li>Handling forms - text and numbers</li><li>Handling forms - files</li><li>Advanced PHP - Emails</li></ol>'),
(6, ''),
(9, 'This is some syllabus'),
(10, 'Express syll'),
(11, '');

-- --------------------------------------------------------

--
-- Table structure for table `ctrainers`
--

CREATE TABLE `ctrainers` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ctrainers`
--

INSERT INTO `ctrainers` (`cid`, `uid`) VALUES
(1, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(9, 1),
(10, 1),
(11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rescomments`
--

CREATE TABLE `rescomments` (
  `commentid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `commtext` text NOT NULL,
  `dateofcomment` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rescomments`
--

INSERT INTO `rescomments` (`commentid`, `rid`, `uid`, `commtext`, `dateofcomment`) VALUES
(1, 3, 2, 'This is a great and useful doc.', '2019-05-25 12:17:53'),
(2, 2, 2, 'This is a comment for notes.', '2019-05-25 13:35:58'),
(3, 4, 2, 'This is a comment on link resource.', '2019-05-25 14:03:28'),
(4, 4, 2, 'This is another comment.', '2019-05-25 14:03:41'),
(5, 4, 2, 'Where will this link take me?', '2019-05-25 14:04:29'),
(6, 7, 2, 'This is a comment on video.', '2019-05-25 14:06:54'),
(7, 5, 2, 'This is a comment on a youtube video resource.', '2019-05-25 14:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `treviews`
--

CREATE TABLE `treviews` (
  `trid` int(11) NOT NULL,
  `tuid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `review` text NOT NULL,
  `rating` int(1) NOT NULL,
  `dateofreview` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `profileImageFileName` text NOT NULL,
  `bio` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `fname`, `lname`, `email`, `password`, `type`, `profileImageFileName`, `bio`) VALUES
(1, 'Aan', 'Patel', 'patelaan13@gmail.com', '5d41402abc4b2a76b9719d911017c592', 1, 'user.svg', 'yoyo honey singh'),
(2, 'Aanu', 'Patel', 'patelaanu13@gmail.com', '5d41402abc4b2a76b9719d911017c592', 0, '2.jpeg', 'This is my bio234');

-- --------------------------------------------------------

--
-- Table structure for table `viewresource`
--

CREATE TABLE `viewresource` (
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateofview` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `viewresource`
--

INSERT INTO `viewresource` (`rid`, `uid`, `dateofview`) VALUES
(2, 2, '2019-05-26 14:42:33'),
(3, 2, '2019-05-26 14:51:07'),
(4, 2, '2019-05-26 14:42:48'),
(5, 2, '2019-05-26 14:51:21'),
(6, 2, '2019-05-26 14:51:15'),
(7, 2, '2019-05-26 14:51:27'),
(10, 2, '2019-05-26 11:05:27'),
(11, 2, '2019-05-26 11:49:58'),
(12, 2, '2019-05-26 15:17:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentreplies`
--
ALTER TABLE `commentreplies`
  ADD PRIMARY KEY (`replyid`);

--
-- Indexes for table `completedcourses`
--
ALTER TABLE `completedcourses`
  ADD PRIMARY KEY (`cid`,`uid`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `courseanswers`
--
ALTER TABLE `courseanswers`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `coursequestions`
--
ALTER TABLE `coursequestions`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `cpurchases`
--
ALTER TABLE `cpurchases`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `cresources`
--
ALTER TABLE `cresources`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `cid` (`cid`,`section`);

--
-- Indexes for table `creviews`
--
ALTER TABLE `creviews`
  ADD PRIMARY KEY (`crid`),
  ADD UNIQUE KEY `cid` (`cid`,`uid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `csections`
--
ALTER TABLE `csections`
  ADD PRIMARY KEY (`cid`,`section`) USING BTREE;

--
-- Indexes for table `cstudents`
--
ALTER TABLE `cstudents`
  ADD PRIMARY KEY (`cid`,`uid`);

--
-- Indexes for table `csyllabus`
--
ALTER TABLE `csyllabus`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `ctrainers`
--
ALTER TABLE `ctrainers`
  ADD PRIMARY KEY (`cid`,`uid`) USING BTREE,
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `rescomments`
--
ALTER TABLE `rescomments`
  ADD PRIMARY KEY (`commentid`);

--
-- Indexes for table `treviews`
--
ALTER TABLE `treviews`
  ADD PRIMARY KEY (`trid`),
  ADD KEY `tuid` (`tuid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `viewresource`
--
ALTER TABLE `viewresource`
  ADD PRIMARY KEY (`rid`,`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentreplies`
--
ALTER TABLE `commentreplies`
  MODIFY `replyid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `courseanswers`
--
ALTER TABLE `courseanswers`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coursequestions`
--
ALTER TABLE `coursequestions`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpurchases`
--
ALTER TABLE `cpurchases`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cresources`
--
ALTER TABLE `cresources`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `creviews`
--
ALTER TABLE `creviews`
  MODIFY `crid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rescomments`
--
ALTER TABLE `rescomments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `treviews`
--
ALTER TABLE `treviews`
  MODIFY `trid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cresources`
--
ALTER TABLE `cresources`
  ADD CONSTRAINT `cresources_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `course` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cresources_ibfk_2` FOREIGN KEY (`cid`,`section`) REFERENCES `csections` (`cid`, `section`) ON DELETE CASCADE;

--
-- Constraints for table `creviews`
--
ALTER TABLE `creviews`
  ADD CONSTRAINT `creviews_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `course` (`cid`) ON DELETE CASCADE;

--
-- Constraints for table `csections`
--
ALTER TABLE `csections`
  ADD CONSTRAINT `csections_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `course` (`cid`) ON DELETE CASCADE;

--
-- Constraints for table `csyllabus`
--
ALTER TABLE `csyllabus`
  ADD CONSTRAINT `csyllabus_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `course` (`cid`) ON DELETE CASCADE;

--
-- Constraints for table `ctrainers`
--
ALTER TABLE `ctrainers`
  ADD CONSTRAINT `ctrainers_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `course` (`cid`) ON DELETE CASCADE;

--
-- Constraints for table `treviews`
--
ALTER TABLE `treviews`
  ADD CONSTRAINT `treviews_ibfk_1` FOREIGN KEY (`tuid`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `treviews_ibfk_2` FOREIGN KEY (`tuid`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `treviews_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
