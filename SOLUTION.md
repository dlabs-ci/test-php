SOLUTION
========

Estimation
----------

Estimated: 3 hours

Spent: 1 hour

Solution
--------

Yearly reports for number of profile views are built using a stored procedure declared inside `setup.sql` file. Data is fetched inside the command using a simple call to the MySQL procedure. After that, table headers and `NULL` values are transformed to be more UI friendly. This could be accomplished inside the procedure itself, but it might end up being used in some other SQL script where `NULL` values are preferred.

### Test cases
  - Validate output table format (all columns present, year in "Profile" column)
  - Check that profiles are ordered by name
  - Given a year argument, test possible invalid values (not specified, not a number, etc.)
  - Check "n/a" values where expected, create dummy profiles with no views if needed
  - Verify report of profiles with no views for some or all months in a year
  - Verify expected behavior with zero profiles in database

### Possible improvements
  - Avoid reconstructing reports on each request, by using `profile_views_yearly` procedure to create a fixed table
  - Create additional commands for daily views, most popular profiles or global views breakdown, to see how page performs as a whole
