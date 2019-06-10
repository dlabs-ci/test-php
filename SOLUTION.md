SOLUTION
========
I have added primary key to main table profiles, added new column to table views with primary key.
I have changed src\TestDataResetCommand to get more logic data (one view number per day, per profile). Before it had 4 records for same day and same profile (random numbers).
fix: setup.sql script. Drop user if exists works only on mysql 5.7+, ref https://dev.mysql.com/doc/refman/5.7/en/drop-user.html, docs 5.6 https://dev.mysql.com/doc/refman/5.6/en/drop-user.html

Estimation
----------
Estimated: 1 hours

Spent: 2 hours

Solution
--------
I should use framework PDO for building sql query. This would avoid any sql injectons.
Using PDO would allow us to use any other sql database supported by framework and it would make a lot easier to update host or framework.
If is this a end user application, I would add one simple web form to display data. Estimate would be aprox. 1h.
I should separete few parts of code into own methods inside class ReportYearlyCommand.

Test scenarios
--------
- check input data (input parameters)
- check output table 
- check database data integrity and if valid data 
- check speed of app and set acceptance duration time
