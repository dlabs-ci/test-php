SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 4 hours 30 minutes


Solution
--------
Comments on your solution

First of all, I just want to say that for main task I needed around 2 hours with cloning and project setup.

My solution is to print in terminal all views by month and year and if there are no values(NULL columns) print "n/a". I tested also with deleting first 120 rows with "DELETE FROM views LIMIT 120" so all results regarding, in this case Carl Lagerfeld are deleted for first month. The results are ordered by
name alphabetically.

My biggest problem was with inserting data in views table. First time when I insert data in table, it took almost 7 minutes and 30 seconds to import 
17900 results. I know that this wasn't main idea of the task but I wanted to create faster insertation. At first sight, everything was working fine and I managed to insert this amount of data in 2 seconds. But dates in table was inserted in '0000-00-00' format. When I print query everything looked just fine
but in database all dates were same. So I didn't realize that date in query was observed like integer and finally after some concatenation and adding double and single quotes, everything worked.

I would also add foreign key between these two tables.   