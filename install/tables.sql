--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` char(255) NOT NULL,
  `sha1` text NOT NULL,
  `admin` smallint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (1,'test@tess.tt','f8a220a6978b95256a1752da2eddad3573e324c1',1);
UNLOCK TABLES;

--
-- Table structure for table `task`
--
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `priority` smallint(6) NOT NULL DEFAULT '1',
  `duedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
INSERT INTO `task` VALUES (20,'test todo 1',1,'2015-06-30 08:37:29',1,1,1),(21,'test todo 2',1,'2015-06-29 08:37:31',2,1,1),(26,'arc todo',2,'2015-06-24 11:13:32',1,2,1),(27,'arc todo done',2,'2015-06-23 11:17:44',2,2,1);
UNLOCK TABLES;