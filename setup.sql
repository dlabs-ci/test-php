DROP DATABASE IF EXISTS `bof_test`;
CREATE DATABASE IF NOT EXISTS `bof_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'bof-test'@'localhost';
CREATE USER 'bof-test'@'localhost' IDENTIFIED BY 'bof-test';


GRANT ALL PRIVILEGES ON `bof\_test` . * TO 'bof-test'@'localhost' WITH GRANT OPTION ;

DROP TABLE IF EXISTS `bof_test`.`profiles`;
CREATE TABLE `bof_test`.`profiles` (
`profile_id` INT NOT NULL ,
`profile_name` VARCHAR( 100 ) NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `bof_test`.`views`;
CREATE TABLE `bof_test`.`views` (
`profile_id` INT NOT NULL ,
`date` DATE NOT NULL ,
`views` INT NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `bof_test`.`profiles` VALUES(1, 'Karl Lagerfeld'), (2, 'Anna Wintour'), (3, 'Tom Ford'), (4, 'Pierre Alexis Dumas'), (5, 'Sandra Choi');