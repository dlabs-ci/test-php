DROP DATABASE IF EXISTS `bof_test`;
CREATE DATABASE IF NOT EXISTS `bof_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'bof-test'@'localhost';
CREATE USER 'bof-test'@'localhost' IDENTIFIED BY 'bof-test';

GRANT USAGE ON * . * TO 'bof-test'@'localhost' IDENTIFIED BY 'bof-test' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

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

USE bof_test;

-- Profile with no views
INSERT INTO profiles VALUES(6, 'John Doe');

-- Create procedures
DROP PROCEDURE IF EXISTS profile_views_yearly;
DELIMITER //
CREATE PROCEDURE profile_views_yearly(in_year INT)
BEGIN
    SELECT
        p.profile_name,
        SUM(r.Jan),
        SUM(r.Feb),
        SUM(r.Mar),
        SUM(r.Apr),
        SUM(r.May),
        SUM(r.Jun),
        SUM(r.Jul),
        SUM(r.Aug),
        SUM(r.Sep),
        SUM(r.Oct),
        SUM(r.Nov),
        SUM(r.Dec)
    FROM (
        -- Sum of views per month
        SELECT p.profile_id,
            CASE MONTH(v.date) WHEN 1 THEN SUM(v.views) END AS Jan,
            CASE MONTH(v.date) WHEN 2 THEN SUM(v.views) END AS Feb,
            CASE MONTH(v.date) WHEN 3 THEN SUM(v.views) END AS Mar,
            CASE MONTH(v.date) WHEN 4 THEN SUM(v.views) END AS Apr,
            CASE MONTH(v.date) WHEN 5 THEN SUM(v.views) END AS May,
            CASE MONTH(v.date) WHEN 6 THEN SUM(v.views) END AS Jun,
            CASE MONTH(v.date) WHEN 7 THEN SUM(v.views) END AS Jul,
            CASE MONTH(v.date) WHEN 8 THEN SUM(v.views) END AS Aug,
            CASE MONTH(v.date) WHEN 9 THEN SUM(v.views) END AS Sep,
            CASE MONTH(v.date) WHEN 10 THEN SUM(v.views) END AS Oct,
            CASE MONTH(v.date) WHEN 11 THEN SUM(v.views) END AS Nov,
            CASE MONTH(v.date) WHEN 12 THEN SUM(v.views) END AS `Dec`
        FROM profiles p LEFT JOIN views v ON p.profile_id = v.profile_id
        WHERE YEAR(v.date) = in_year
        GROUP BY p.profile_id, MONTH(v.date)
    ) r
    RIGHT JOIN profiles p ON r.profile_id = p.profile_id
    -- Group views per profile
    GROUP BY p.profile_id
    ORDER BY p.profile_name;
END//
DELIMITER ;
