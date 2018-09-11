SOLUTION
========

Estimation
----------
Estimated: 2 hours

Spent: 2 hours


Solution
--------

I tried to make the solution as easy and as transparent as possible. I only used one SQL query with a LEFT JOIN and I also did all of the ordering in the query. I had to use a few loops in order to build the array and format the data (large numbers, adding "n/a" when no data is available...)

My code does the following:
- Gets a list of user profiles with associated views and builds an array
- Builds a table for each year and each month with number of views for each user
- Formats the large numbers (comma after thousands)
- Adds "n/a" for each month that has no data

If this was an actual project I would add a way to set a range of years to show, otherwise the app shows show ALL data there is in the database. I could also add some more filtering/ordering options, for example by profile name, by specific date or month...
I would also cache the results, especially for the past years.

Testing:
- If there is no data available the code outputs "n/a" and returns false
- If there is a problem with the database connection the code outputs "n/a" and returns false
- If there is no data available for the specific month, but there is other data the code will output the table normally and print "n/a" for the month that has no data
- If a new user is encountered in the database a new entry is added in the array
- The fields returned from the database cannot be empty due to NOT NULL rule
