SOLUTION
========

Estimation
----------
Estimated: 1.5 hours

Spent: 2.5 hours


Solution
--------
All the heavy lifting is done by MySQL which groups views by profile name and a month and displays month data in columns.
I contemplated different approaches but settled for the one with minimal queries and least post-processing with php. 
I also guarded command against incorrect inputs (i.e. year must be a number).

Better product
--------
Suggestions:
- record specific page.
- reports can be cached (especially for past years, because they don't change)
- Created as a web application (more user-friendly) with a form which has
   - a select with all available "year" options (can be cached and updated only when table is updated)".
   - other form controls that are used to display more taylored output which can serve to make more informed business decisions.

Test Cases
--------
1. If no year is specified as an argument, than it should defaults to current year.
2. If year is specified, that it should return table for that year.
3. Year should be a number.
4. Report array returned should have 13 columns (profile + one for each month).
5. Profiles should be sorted alphabetical order.
6. If looking for a yearly report for a year 2020 not in the database, it should output warning that no clicks for that year.
7. If no click for a month is recorderd, it should return "n/a".
8. If number is returned, it should be properly formated (with commas).
9. Profiles that do have no page views, aren't included in a report.