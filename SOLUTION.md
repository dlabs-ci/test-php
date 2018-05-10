SOLUTION
========

Estimation
----------
Estimated: 4 hours

Spent: 3 hours


Solution
--------
Table displaying data based on Year, User, Monthly Visit



Test Case
---------

In test case we try to test different input parameters and prevent someone to break our app.
1. Run console command any enter string instead year
2. Run console command and try sql injection
3. Run console command and try different date type (only month, fully mysql year, ...)
4. Run console command and try to enter special signs (' " \ ? ) 
5. Run command and try to get other database information, if possible


Edge Case
---------
We always expect user enter data to be dangerous and try to clean them as much as possible and pass them trough various filters, controls and test cases
Our user input field goes directly to database query, which opens possibility for exploits. 

