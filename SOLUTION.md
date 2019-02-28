SOLUTION
========

Estimation
----------
Estimated: 3,5 hours

Spent: 5 hours (a little more if I count in the code cleanup and writing tests, solutions...)

Tests
----------
1. There might be a row in the table 'views' with profile_id that would not exist/be missing in the table 'profiles'. Those records should be ignored.
2. There are months where a specific profile does not have any views, instead of empty value, there should be 'n/a' instead.
3. According to the database structure there is a possibility of negative views value. Additional validation can be implemented.
4. In case of a 2 or more profiles with the same name, an ID should be included in the report output, to distinguish between the two.
5. If there is no views data for a specific profile, the profile should still be listed in the report, with all the cells showing 'n/a'

Solution
--------
I have decided to do some basic join, grouping and summing with mysql query and do the rest of the logic coding in PHP.
Data returned from the query consisted of multiple rows grouped by person, year and month. With this I also got view sums for each of the groups.
After that I used foreach loops to create appropriate instances of class Profile.

In the end I prepared the data for console output. This required further loops for the correct display of data for each year. This might mean a bit longer processing but it would allow easier expanding and manipulating with the data.

Note: there were rows in the table with different years and the assignment only mentions to display monthly data for a year. But there is no indication on which year that should be.
I saw two options here: 
- ask user for which year the report should be
or
- show reports for all years individually, that is what I did.

