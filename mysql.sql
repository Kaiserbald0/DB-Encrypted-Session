CREATE TABLE IF NOT EXISTS `mySession_Sessions` (
  `sid` varchar(100) NOT NULL DEFAULT '',
  `expires` int(11) unsigned NOT NULL DEFAULT '0',
  `forced_expires` int(11) unsigned NOT NULL,
  `ua` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `mySession_Vars` (
  `name` text NOT NULL,
  `value` text NOT NULL,
  `sid` varchar(100) NOT NULL,
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `mySession_Vars`
  ADD CONSTRAINT `mySession_Vars_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `mySession_Sessions` (`sid`) ON DELETE CASCADE;
