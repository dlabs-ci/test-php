SOLUTION
========

Estimation
----------
Estimated: 7 hours

Spent: 7 hours


Solution
--------
ReportYearlyCommand exposes command for yearly report of views per profile.
Added functionality to specify year by command line.


Future ideas
------------
What could be done better:
- Using Doctrine ORM with entities and native Doctrine repositories
- ProfilesRepository could return []Profile
- Private methods ReportYearlyCommand::pivotData and ReportYearlyCommand::fillEmpty could be set public static and moved to separate utils if needed. Personally I did not find it necessary because there would be too many parameters passed and would make code less readable for single use case


Possible test cases
-------------------
- No year passed but no error is shown
- Year is of invalid type but no error is shown
- No historical data but no error is shown
- Profiles are not alphabetically listed
- Empty columns do not have 'n/a'