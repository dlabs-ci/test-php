SOLUTION
========

Estimation
----------
Estimated: 10 hours

Spent: 12 hours


Solution
--------
I implemented Doctrine ORM in the application so the Entities and Repositories could be used in application.
I would prefer usage of DQL Query builder application repositories, so that queries could be easily used and upgraded throughout further development.
I made changes to the database and the setup.sql, I added primary key to the 'views' table, so I could make 'View' ORM Entity that is referencing 'views' table, and so I could make relation between 'Profile' and 'View'.