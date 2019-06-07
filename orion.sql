-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2019 at 10:29 AM
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
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `atext` text NOT NULL,
  `dateofanswer` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateofaudit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `completedcourses`
--

CREATE TABLE `completedcourses` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `score` float NOT NULL DEFAULT '-1',
  `dateofcomplete` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `dateofpublish` datetime DEFAULT NULL,
  `cimg` text,
  `category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `cstudents`
--

CREATE TABLE `cstudents` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateofjoining` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `csyllabus`
--

CREATE TABLE `csyllabus` (
  `cid` int(11) NOT NULL,
  `csyllabus` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ctrainers`
--

CREATE TABLE `ctrainers` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lastviewedqna`
--

CREATE TABLE `lastviewedqna` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `lastviewed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `questionid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `qtext` text NOT NULL,
  `dateofquestion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quizanswer`
--

CREATE TABLE `quizanswer` (
  `quizid` int(11) NOT NULL,
  `qno` int(11) NOT NULL,
  `answertext` text NOT NULL,
  `marks` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quizattempts`
--

CREATE TABLE `quizattempts` (
  `quizid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `score` float NOT NULL,
  `dateofattempt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quizquestion`
--

CREATE TABLE `quizquestion` (
  `quizid` int(11) NOT NULL,
  `qno` int(11) NOT NULL,
  `quizqtext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerid`);

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
-- Indexes for table `lastviewedqna`
--
ALTER TABLE `lastviewedqna`
  ADD PRIMARY KEY (`cid`,`uid`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`questionid`);

--
-- Indexes for table `quizanswer`
--
ALTER TABLE `quizanswer`
  ADD KEY `quizanswer_ibfk_1` (`quizid`);

--
-- Indexes for table `quizquestion`
--
ALTER TABLE `quizquestion`
  ADD PRIMARY KEY (`quizid`,`qno`);

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
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `commentreplies`
--
ALTER TABLE `commentreplies`
  MODIFY `replyid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cresources`
--
ALTER TABLE `cresources`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `creviews`
--
ALTER TABLE `creviews`
  MODIFY `crid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `questionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rescomments`
--
ALTER TABLE `rescomments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `quizanswer`
--
ALTER TABLE `quizanswer`
  ADD CONSTRAINT `quizanswer_ibfk_1` FOREIGN KEY (`quizid`) REFERENCES `cresources` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quizquestion`
--
ALTER TABLE `quizquestion`
  ADD CONSTRAINT `fkey_quiz_questions` FOREIGN KEY (`quizid`) REFERENCES `cresources` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

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
