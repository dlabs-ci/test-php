SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 3.5 hours


Solution
--------
I'm not really familiar with Symfony framework so I'm making a lot of assumptions regarding the implementation process. I'm sure there are a lot out-of-the-box functionalities inside the Symfony framework and I'm also sure that this simple task could be done in x-y ways.
But I'm a quick learner and I always try to follow good coding practices (the general and best practices by each framework).
After reading the Symfony Docs I'm prety sure that I should create a Service class for my ReportyViewCountByProfile class and not just a vanilla PHP class as I did. The idea is that my service class would be responsible for everything ; database ; queries ; input arguments, DI, etc. Current solution is not optimal and two classes are mixing responsibilities - which is not OK. The front-end class (ReportYearlyCommand)  should only delegate execution to a internal Service class (ReportyViewCountByProfile) and this class should prepare the environment (db, query, ...) and then just return the results to the calling class which is still going to display the results.
Of course each report has to have it's own class with this strategy we avoid sphageti code and if we seperate our code there is a lot more 
opportunity for code reuse. Me not using ORM was a decision by-design because I don't think that ORM is a good fit for reports&analytics - for performance reasons and query maintenance reasons the plan-sql is the right way to go. ORM is a good fit for basic CRUD actions.

In my opinion, a more suitable (production ready) solution would be to build a RESTFull API for fetching report data. The service would accept input arguments and returned a structured JSON response object. But given that this is not a production code and you don't need to spend time on the presentational part of the app (view) it is a good fit. With API calls we can more easly incorporate user authentication strategies, auditing, scaling, ... .


Test cases : 
1. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and specifies input arguments (year)
    Result : The system returns the date for a given year
    Output : 

2. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and does not specifies input arguments (year)
    Result : The system returns a message to the user explaining that the function has a mandatory argument year 
    Output :  Not enough arguments (missing: "year").
 
3. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and an QUERY EXCEPTION ocurres stoping the execution.
    Result : The system returns a message to the user explaining that there is a problem with the query passed to the report 
    Output :  EXCEPTION : Exception ocurred while executing the query :

4. Running bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and an unknown program error ocurres stoping the execution.
    Result : The system returns a message to the user explaining that there is a problem with the query passed to the report 
    Output :  Something unexpected happened!! OH-My-OH-My. : You have requested a non-existent service "database_connectionww".
