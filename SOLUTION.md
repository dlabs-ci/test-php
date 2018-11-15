SOLUTION
========

Estimation
----------
Estimated: 6 hours

Spent: 7 hours


Solution
--------
ReportYearlyCommand exposes command for yearly report of views per profile.
Added functionality to specify year by command line.

Added PRIMARY KEY and auto_increment to profiles.profile_id and FOREIGN KEY to views.profile_id. Additional SQL needed:

``` SQL
ALTER TABLE profiles ADD PRIMARY KEY (profile_id);
ALTER TABLE profiles MODIFY profile_id int(11) NOT NULL auto_increment;
ALTER TABLE views
ADD CONSTRAINT views_profiles_profile_id_fk
FOREIGN KEY (profile_id) REFERENCES profiles (profile_id);
```


Future ideas
------------
What could have been done better:
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