SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 4 hours


Solution
--------
I have created new table reports, which contains monthly views data by year for each profile .Before beggining reports table must be generated. SQL Code is in reports.sql file.
$> mysql -uroot -p < reports.sql

reports table is inititali populated by command

$>bin/console report:profiles:generate

which reads all aviable data, calculate monthly views for each profile and inserts rows into reports table.


Command 

$>bin/console report:profiles:update

calculates monthly views for current month and updates reports table. This script should be run by cron every day. It will update data for current month, or in case of 1st, for previous month.


$> bin/console report:profiles:yearly <year>

expexts one argument, which is year of desired output. It retrieves all profiles data for corresponding year and displays it.



3. Identify and write at least 5 test cases (no code necessary; Gherkin or a written list will suffice)
    - Demonstrate your understanding of the Conditions of Acceptance
    - Identify any appropriate edge cases

- year with no data is selected by user
- year with incomplete data is selected by user
- year with data is selected by user
- year argument is missing
- new entry is added to profiles table 
- entry is removed from profiles table

My implementation specific edge cases:
 - running generate command, if reports table is already populated
 - updating row in reports command on 2.1. each year, where entry not yet exists


Possible improvements:

 - Indexes should be created on appropriate database columns, as smount of data grows ...
 - Both GenerateReports ans UpdateReports commans should retrieve data from database based on month. In this case code shoul be reusable and moved to model. 