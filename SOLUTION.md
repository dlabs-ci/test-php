SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 1 hour 20 min

Coment: Testing was minimal so im not sure all the edge cases are covered, i removed some data from DB to test the "n/a" requirement.

Solution
--------
Comments on your solution

Solution solves all 3 requirments given. Im sure there is a better way of doing this but it woud requirte more time to think. The main problem is the 3rd point witch requires me to "make up" data in the DB this can also be done in code with some injections to the data given by DB.

I tested the solution to folowing cases:
-clean database (as provided by default)
-inserted new user with no history
-inserted new history with no user
-inserted new user with duplicated name

Test cases
--------


 Scenario: All data is avavible and properly connected (history is available)
   When Business Analyst request a report
   Then the script displays sum of all views on the screen in the format provided by the task

 Scenario: All data is avavible and properly connected (history is available)
   When Business Analyst request a report
   Then the script displays sum of all views on the screen gruped by month independant of the year

 Scenario: All data is avavible and properly connected (history is not available)
   When Business Analyst request a report
   Then the script displays sum of all views on the screen gruped by month independant of the year
    And for the user with no history all the months will be summed as "n/a"

 Scenario: All data is avavible but there are some views in the table with no conection to the profiles
   When Business Analyst request a report
   Then the script displays sum of all views on the screen gruped by month independant of the year
    And the the data with no connection to the profiles will not be used in the calculations

 Scenario: All data is avavible but there are some missing month for the user profile
   When Business Analyst request a report
   Then the script displays sum of all views on the screen gruped by month independant of the year
    And the user with no history for specific month will have that month summed "n/a"

 Scenario: All data is avavible and properly connected (history is available)
   When Business Analyst request a report
   Then the script displays sum of all views on the screen gruped by month independant of the year, grouping must be done on profile_id but displayed as profile_name as names can duplicate

Edge cases
--------
Current solution treats 0 views as "n/a", possible modifications.
Duplicated usernames

Other Notes
--------
Localy I removed all the data in january using : DELETE FROM views WHERE MONTH(date) = 1, to test the "n/a" requirement
I added a new user caled "test" and "Karl Lagerfeld"
I added a new history item with non existing profile_id