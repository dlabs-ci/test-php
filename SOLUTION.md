SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 3,5 hours
    -  2 hours (thinking and redesigned the code)
    -  45 min setup the environment
    -  45 min coding


Solution
--------
This task can be done with multiples options. One of these options I just wrote it with code. I use Laravel collection package, which helped me to easily 
get array data from DB to multidimensional array (collection).

Other options:
 - The other option how can be this task done is to write raw SQL sentence and get all data with mysql.
 - If I will know a little bit more Symfony ORM package, this can be also done with ORM command and you can get data out from ORM and then display in console:

Other good solution is to display more tables if user doesn't type in year data. Tables can be separate with new line and if have in DB 10 years it will display 10 tables. 
But with this solution you need to be careful, because if you get a lot of years and data in you databases it can too much loaded a server.

This example can be upgraded in the future with nice console message if user doesn't type a year of a report and suggested a option to type a year without to return error exceptions. 

Code can be also separated into function if you need it to reuse it somewhere else.