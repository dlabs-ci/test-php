SOLUTION
========

Estimation
----------
Estimated: 6 hours

Spent: 8 hours

Requirements:
----------
PHP version: 7.1+ 

Solution
--------
I've implemented generating of yearly reports data using Doctrine ORM / Entities to be able 
easily improve this application with saving / updating generated reports, pre-generate reports and etc.

Except the query for collecting data that I've used in this applications I've also tried to use few more, for example something like that:

~~~~
SELECT pn, y, m, SUM(v.views)
FROM(
  (SELECT profiles.profile_name pn, profiles.profile_id pi FROM profiles)	pnames,
  (SELECT YEAR(CURDATE())-1 y ) years,
  (SELECT '01' m UNION ALL SELECT '02' UNION ALL SELECT '03' UNION ALL SELECT '04'
    UNION ALL SELECT '05' UNION ALL SELECT '06' UNION ALL SELECT '07' UNION ALL SELECT '08'
    UNION ALL SELECT '09' UNION ALL SELECT '10' UNION ALL SELECT '11' UNION ALL SELECT '12') months
    )
/*     LEFT JOIN views v ON (DATE_FORMAT(v.date, '%Y-%m') = CONCAT_WS('-', y, m) AND v.profile_id = pnames.pi ) */
    LEFT JOIN views v ON (YEAR(v.date) = y AND MONTH(v.date) = m AND v.profile_id = pnames.pi )

-- WHERE y = '2016'  
GROUP BY pn, y, m
ORDER BY pn, y, m``
~~~~

But it's not faster then the implemented one and also it's necessary to do data pivot for query like described above.

What can be improved:
- because of that this application uses too havy SQL queries it would be better to implement at least generating reports by cron in advance;
- I would recommend to use for applications like this one some OLAP solutions like Mondrioan OLAP server that I used to work with on one project;
- it would be useful to provide an ability to use different "output printers" such as html/pdf/csv etc.; Printers collector service and Printers interfaces are already implemented;
- add real tests using for instance SQLite db with checksum pre-calculated data;
- use doctrine console for schema create insteadof sql file;
- use fixtures / faker for fill test users data;
 