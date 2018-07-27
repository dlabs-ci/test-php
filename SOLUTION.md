SOLUTION
========

Estimation
----------
Estimated: 6 hours

Spent: 4 hours


Solution
--------
ReportYearlyCommand displays yearly breakdown of visits for each user per month in that year.

CONDITIONS OF ACCEPTANCE are matched.

### Extra work done:
 + added index (profile_id) to `views` table in database
 + added pk to `profiles` table in database
 + columns are set to same width of 9 chars
 + columns for month breakdown are aligned to the right for easier reading.
 + added support to implement different renderer - CSV, XML, JSON ...

### Test Cases
 + years  should be ordered ASC
 + profile names should be ordered alphabetically ASC in that years report
 + views should be ordered by month ASC in that years report
 + if some month data is missing for a user n/a should be displayed
 + if user has no views at all in given year his profile should not be in that years report

 - users with same name - how to differentiate them?
 - larger number of view count might break display of table
 - large number of profiles and/or years makes it slow to generate and hard to read in console

### Possible Improvements (estimations in hours,  1 hour as minimum)
 + add user id to the name if there are users with same profile name (1)
 + in case where there would be many profiles it would be good to add command param for specific user(s) and year(s) (or date range) you want to see
   report for (1)
 + In case of large and time consuming reports implementation of job queue might be better solution (x - depends on architecture also)
 + change DB table views engine to MyISAM it is more suitable for "log" data - speed gain (1)
 + using NoSQL storage for view tracking?
 + add options for multi format support and saving to output file (4)