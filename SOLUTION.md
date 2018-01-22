SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 4 hours


Solution
--------
Before beggining reports table must be generated. SQL Code is in reports.sql file.
$> mysql -uroot -p < reports.sql

file reports.sql creates new table with calculated report data. No need for recalculation for every view.


Command 

$>bin/console report:profiles:generate

calculates monthly views for all aviable data and populates reports table.


Command 

$>bin/console report:profiles:update

calculates monthly views for current month and updates reports table. This script should be run by cron every day. It will update data for current month, or in case of 1st, for previous month


