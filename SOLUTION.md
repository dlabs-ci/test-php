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

### Test Cases
 + if user has no views at all in fiven year it should not be in that years report
 + years  should be ordered ASC
 + profile names should be ordered alphabetically ASC in that years report
 + views should be ordered by month ASC in that years report
 + if some month data is missing for a user n/a should be displayed

### Possible Improvements
 + add user id to the name if there are users with same profile name
 + in case where there would be many profiles it would be good to add command param for specific user(s) and year(s) (or date range) you want to see
   report for
 + In case of large and time consuming reports implementation of job queue might be better solution
 + change DB table views engine to MyISAM if you don't need transactions - speed gain
 + add options for multi format support and saving to output file

 + there is not enough information for more improvements since requirements are broad and it makes a lot room for
   over-engineering

### Extra:
 + added index (profile_id) to `views` table in database
 + added pk to `profiles` table in database

 + columns are set to same width of 9 chars
 + columns for month breakdown are aligned to the right for easier reading.
 + added support to implement different renderer - CSV, XML, JSON ...